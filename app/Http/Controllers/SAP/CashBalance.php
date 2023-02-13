<?php

namespace App\Http\Controllers\SAP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDF;
Use Cookie;
use DataTables;
use Log;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use App\Http\Controllers\Traits\IntranetTrait;

class CashBalance extends Controller{
    use IntranetTrait;

    function index(Request $request) {
        $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
        $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');
        $data['date_start'] = $date_start;
        $data['date_end'] = $date_end;
        $data['IS_NEED_PASSWORD'] = true;
        $cash_balance_company = [];

        $cek_permission_cash_balance = 'view_'. (String)route('cash_balance.index',[],false);
        // $current_user_login_company = isset(session()->get('user_data')->EMPLOYEE_ID) ? substr(session()->get('user_data')->EMPLOYEE_ID, 0,2) : '';
        $user_id = isset(session()->get('user_data')->EMPLOYEE_ID) ? session()->get('user_data')->EMPLOYEE_ID : '';
        $cek_plant_user = DB::connection('dbintranet')
        ->table('dbo.VIEW_EMPLOYEE_ACCESS')
        ->where('EMPLOYEE_ID', $user_id)
        ->select('SAP_PLANT_ID', 'COMPANY_CODE')
        ->get()->pluck('SAP_PLANT_ID','COMPANY_CODE')->filter()->toArray();

        // Jika tidak memiliki full access
        if(session()->get('permission_menu')->has($cek_permission_cash_balance) == false) {
            $cash_balance_company = $cek_plant_user;
        }
        else {
            // Tidak memerlukan password cash balance karena sudah memiliki full access
            $data['IS_NEED_PASSWORD'] = false;
            $get_owning_company = DB::connection('dbintranet')
            ->table('INT_BUSINESS_PLANT')
            ->select('SAP_PLANT_ID', 'COMPANY_CODE')
            ->get();
            $get_owning_company = $get_owning_company->filter(function ($value, $key) {
                return self::isOwningCompany($value->SAP_PLANT_ID);
            })->pluck('SAP_PLANT_ID','COMPANY_CODE')->toArray();
            // Karena AHM tidak ada indikasi owning ataupun anak perusahaan lain, ditambahkan manual
            if($get_owning_company){
                $get_owning_company['AHM']= 'AHM';
            }
            $cash_balance_company = $get_owning_company;
            // $cash_balance_company = ['KMS'=>'KMS0', 'PAD'=>'PAD0', 'PPC'=>'PPC0', 'NJP'=>'NJP0',  'PNB'=>'PNB0', 'WKK'=>'WKK0', 'AHM'=>'AHM0'];
        }

        // dd($current_user_login_company, $cash_balance_company);
        // RFC Cash balance dan Time Deposit
        $is_production = config('intranet.is_production');
        if($is_production)
            $connection = new SapConnection(config('intranet.rfc_prod'));
        else
            $connection = new SapConnection(config('intranet.rfc'));

        $options = [
            'rtrim'=>true,
        ];
        
        $cash_total_IDR = 0;
        $cash_total_USD = 0;
        $cash_total_JPY = 0;
        $cash_total_SGD = 0;
        $cash_total_EUR = 0;
        $cash_total_CNY = 0;
        $cash_total_ALL_IDR = 0;

        $deposit_total_IDR = 0;
        $deposit_total_USD = 0;
        $deposit_total_JPY = 0;
        $deposit_total_SGD = 0;
        $deposit_total_EUR = 0;
        $deposit_total_CNY = 0;
        $deposit_total_ALL_IDR = 0;
        $year = date('Y');
        $data['RFC'] = collect($cash_balance_company)->mapWithKeys(function($itemParent, $key) use ($connection, $options, 
            &$cash_total_IDR, &$cash_total_USD, &$cash_total_JPY, &$cash_total_SGD, &$cash_total_EUR, &$cash_total_CNY, &$cash_total_ALL_IDR,
            &$deposit_total_IDR, &$deposit_total_USD, &$deposit_total_JPY, &$deposit_total_SGD, &$deposit_total_EUR, &$deposit_total_CNY, &$deposit_total_ALL_IDR, $year
        ){
            $data = [];
            $company_info = DB::connection('dbintranet')
            ->table('INT_COMPANY')
            ->where('COMPANY_CODE', $key)
            ->select('COMPANY_NAME', 'COMPANY_CODE')
            ->get()->first();

            if($company_info){
                if(self::isOwningCompany($itemParent)){
                    $plant_info = DB::connection('dbintranet')
                    ->table('INT_BUSINESS_PLANT')
                    ->where('COMPANY_CODE', $company_info->COMPANY_CODE)
                    ->select('SAP_PLANT_NAME', 'SAP_PLANT_ID', 'COMPANY_CODE')
                    ->get();
                }
                else {
                    $plant_info = DB::connection('dbintranet')
                    ->table('INT_BUSINESS_PLANT')
                    ->where('COMPANY_CODE', $company_info->COMPANY_CODE)
                    ->where('SAP_PLANT_ID', $itemParent)
                    ->select('SAP_PLANT_NAME', 'SAP_PLANT_ID', 'COMPANY_CODE')
                    ->get();
                }

                // Jika plant ditemukan
                if(count($plant_info) > 0){
                    $data['PLANT_BALANCE'] = $plant_info->mapWithKeys(function($item, $order) use ($key, $connection, $options, 
                    &$cash_total_IDR, &$cash_total_USD, &$cash_total_JPY, &$cash_total_EUR, &$cash_total_CNY, &$cash_total_SGD, &$cash_total_ALL_IDR,
                    &$deposit_total_IDR, &$deposit_total_USD, &$deposit_total_JPY, &$deposit_total_SGD, &$deposit_total_EUR, &$deposit_total_CNY, &$deposit_total_ALL_IDR, $year){
                        $param = [
                            'GV_BUKRS'=>$item->COMPANY_CODE,
                            'GV_PROFIT_CENTER'=>$item->SAP_PLANT_ID,
                            'GV_FISCAL_YEAR'=>'',
                        ];
                
                        $cash_balance = [];
                        $time_deposit = [];

                        // TIME DEPOSIT
                        try {
                            $function = $connection->getFunction('Z_FGL_TIME_DEPOSIT');
                            $result= $function->invoke($param, $options);
                            $time_deposit = json_decode(json_encode($result));
                        } catch(SAPFunctionException $e){
                            Log::error($e->getMessage());
                        } catch(SapException $e){
                            Log::error($e->getMessage());
                        }

                        $data['TIME_DEPOSIT']['data'] = isset($time_deposit->IT_TIME_BALANCE) ? $time_deposit->IT_TIME_BALANCE : [];
                        if($data['TIME_DEPOSIT']['data']){
                            $data['TIME_DEPOSIT']['SUM_IDR'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_IDR');
                            $data['TIME_DEPOSIT']['SUM_USD'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_USD');
                            $data['TIME_DEPOSIT']['SUM_JPY'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_JPY');
                            $data['TIME_DEPOSIT']['SUM_SGD'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_SGD');
                            $data['TIME_DEPOSIT']['SUM_EUR'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_EUR');
                            $data['TIME_DEPOSIT']['SUM_CNY'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_CNY');
                            $data['TIME_DEPOSIT']['SUM_ALL'] = collect($data['TIME_DEPOSIT']['data'])->sum('EQUIV_IDR');

                            $deposit_total_IDR += $data['TIME_DEPOSIT']['SUM_IDR'];
                            $deposit_total_USD += $data['TIME_DEPOSIT']['SUM_USD'];
                            $deposit_total_JPY += $data['TIME_DEPOSIT']['SUM_JPY'];
                            $deposit_total_SGD += $data['TIME_DEPOSIT']['SUM_SGD'];
                            $deposit_total_EUR += $data['TIME_DEPOSIT']['SUM_EUR'];
                            $deposit_total_CNY += $data['TIME_DEPOSIT']['SUM_CNY'];
                            $deposit_total_ALL_IDR += $data['TIME_DEPOSIT']['SUM_ALL'];
                        }
                        
                        $param = [
                            'GV_BUKRS'=>$item->COMPANY_CODE,
                            'GV_PROFIT_CENTER'=>$item->SAP_PLANT_ID,
                            'GV_FISCAL_YEAR'=>$year,
                        ];
                        //CASH BALANCE
                        try {
                            $function = $connection->getFunction('Z_FGL_CASH_BALANCE');
                            $result= $function->invoke($param, $options);
                            $cash_balance = json_decode(json_encode($result));
                        } catch(SAPFunctionException $e){
                            Log::error($e->getMessage());
                        } catch(SapException $e){
                            Log::error($e->getMessage());                
                        }
                
                        $data['CASH_BALANCE']['data'] = isset($cash_balance->IT_CASH_BALANCE) ? $cash_balance->IT_CASH_BALANCE : [];   
                        if($data['CASH_BALANCE']['data']){
                            $data['CASH_BALANCE']['SUM_IDR'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_IDR');
                            $data['CASH_BALANCE']['SUM_USD'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_USD');
                            $data['CASH_BALANCE']['SUM_JPY'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_JPY');
                            $data['CASH_BALANCE']['SUM_SGD'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_SGD');
                            $data['CASH_BALANCE']['SUM_EUR'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_EUR');
                            $data['CASH_BALANCE']['SUM_CNY'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_CNY');
                            $data['CASH_BALANCE']['SUM_ALL'] = collect($data['CASH_BALANCE']['data'])->sum('EQUIV_IDR');

                            $cash_total_IDR += $data['CASH_BALANCE']['SUM_IDR'];
                            $cash_total_USD += $data['CASH_BALANCE']['SUM_USD'];
                            $cash_total_JPY += $data['CASH_BALANCE']['SUM_JPY'];
                            $cash_total_SGD += $data['CASH_BALANCE']['SUM_SGD'];
                            $cash_total_EUR += $data['CASH_BALANCE']['SUM_EUR'];
                            $cash_total_CNY += $data['CASH_BALANCE']['SUM_CNY'];
                            $cash_total_ALL_IDR += $data['CASH_BALANCE']['SUM_ALL'];
                        }

                        try {
                           
                           $data['RATE_USD'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray()))*1000 : 0 : 0;
                            $data['RATE_JPY'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray())) : 0 : 0;
                            $data['RATE_SGD'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray()))*1000 : 0 : 0;
                            $data['RATE_EUR'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray()))*1000 : 0 : 0;
                            $data['RATE_CNY'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray()))*1000 : 0 : 0;


                        } catch(\Exception $e){
                            $data['RATE_USD'] = 0;
                            $data['RATE_JPY'] = 0;
                            $data['RATE_SGD'] = 0;
                            $data['RATE_EUR'] = 0;
                            $data['RATE_CNY'] = 0;
                            Log::info('CASH BALANCE EXCHANGE RATE ERROR | '. $e->getMessage());
                        }

                        $data['CASH_BALANCE']['data'] = collect($data['CASH_BALANCE']['data'])->groupBy(function($item, $key){
                            return isset(explode('-', $item->BANK_NAME)[0]) ? trim(explode('-', $item->BANK_NAME)[0]) : $item->BANK_NAME;
                        })->map(function($item, $key){
                            $SUM_BALANCE_IDR = collect($item)->sum('BALANCE_IDR');
                            $SUM_BALANCE_USD = collect($item)->sum('BALANCE_USD');
                            $SUM_BALANCE_JPY = collect($item)->sum('BALANCE_JPY');
                            $SUM_BALANCE_SGD = collect($item)->sum('BALANCE_SGD');
                            $SUM_BALANCE_EUR = collect($item)->sum('BALANCE_EUR');
                            $SUM_BALANCE_CNY = collect($item)->sum('BALANCE_CNY');

                            $SUM_BALANCE_EQUIV_IDR = collect($item)->sum('EQUIV_IDR');
                            $item->GROUP_IDR = $SUM_BALANCE_IDR;
                            $item->GROUP_USD = $SUM_BALANCE_USD;
                            $item->GROUP_JPY = $SUM_BALANCE_JPY;
                            $item->GROUP_SGD = $SUM_BALANCE_SGD;
                            $item->GROUP_EUR = $SUM_BALANCE_EUR;
                            $item->GROUP_CNY = $SUM_BALANCE_CNY;
                            $item->GROUP_ALL = $SUM_BALANCE_EQUIV_IDR;

                            return $item;
                        })->all();

                        return ["(".$item->SAP_PLANT_ID.") ".$item->SAP_PLANT_NAME=>$data];
                    })->toArray();
                }
                return [$company_info->COMPANY_NAME => $data];
            }
        })->toArray();
        // dd($data['RFC']['PT NUSA JAYA PRIMA']);

        $data['CASH_BALANCE']['SUM_GROUP_IDR'] = $cash_total_IDR;
        $data['CASH_BALANCE']['SUM_GROUP_USD'] = $cash_total_USD;
        $data['CASH_BALANCE']['SUM_GROUP_JPY'] = $cash_total_JPY;
        $data['CASH_BALANCE']['SUM_GROUP_SGD'] = $cash_total_SGD;
        $data['CASH_BALANCE']['SUM_GROUP_EUR'] = $cash_total_EUR;
        $data['CASH_BALANCE']['SUM_GROUP_CNY'] = $cash_total_CNY;
        $data['CASH_BALANCE']['SUM_GROUP_ALL'] = $cash_total_ALL_IDR;

        $data['TIME_DEPOSIT']['SUM_GROUP_IDR'] = $deposit_total_IDR;
        $data['TIME_DEPOSIT']['SUM_GROUP_USD'] = $deposit_total_USD;
        $data['TIME_DEPOSIT']['SUM_GROUP_JPY'] = $deposit_total_JPY;
        $data['TIME_DEPOSIT']['SUM_GROUP_SGD'] = $deposit_total_SGD;
        $data['TIME_DEPOSIT']['SUM_GROUP_EUR'] = $deposit_total_EUR;
        $data['TIME_DEPOSIT']['SUM_GROUP_CNY'] = $deposit_total_CNY;
        $data['TIME_DEPOSIT']['SUM_GROUP_ALL'] = $deposit_total_ALL_IDR;
        $data['FISCAL_YEAR'] = $year;
        
        // return view('pages.sap.cash-balance.index', ['data'=>$data])->render();
        $rawHtml = view('pages.sap.cash-balance.template', ['data'=>$data])->render();
        return view('pages.sap.cash-balance.index', ['template'=>$rawHtml, 'data'=>$data]);
    }

    public static function filter_cash_balance(Request $request){
        if(self::wantsJson($request)){

            $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
            $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');
            $data['date_start'] = $date_start;
            $data['date_end'] = $date_end;
            $data['IS_NEED_PASSWORD'] = true;
            $cash_balance_company = [];

            $cek_permission_cash_balance = 'view_'. (String)route('cash_balance.index',[],false);
            // $current_user_login_company = isset(session()->get('user_data')->EMPLOYEE_ID) ? substr(session()->get('user_data')->EMPLOYEE_ID, 0,2) : '';
            $user_id = isset(session()->get('user_data')->EMPLOYEE_ID) ? session()->get('user_data')->EMPLOYEE_ID : '';
            $cek_plant_user = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE_ACCESS')
            ->where('EMPLOYEE_ID', $user_id)
            ->select('SAP_PLANT_ID', 'COMPANY_CODE')
            ->get()->pluck('SAP_PLANT_ID','COMPANY_CODE')->filter()->toArray();

            if(session()->get('permission_menu')->has($cek_permission_cash_balance) == false) {
                $cash_balance_company = $cek_plant_user;
            }
            else {
                $data['IS_NEED_PASSWORD'] = false;
                $get_owning_company = DB::connection('dbintranet')
                ->table('INT_BUSINESS_PLANT')
                ->select('SAP_PLANT_ID', 'COMPANY_CODE')
                ->get();
                $get_owning_company = $get_owning_company->filter(function ($value, $key) {
                    return self::isOwningCompany($value->SAP_PLANT_ID);
                })->pluck('SAP_PLANT_ID','COMPANY_CODE')->toArray();
                // Karena AHM tidak ada indikasi owning ataupun anak perusahaan lain, ditambahkan manual
                if($get_owning_company){
                    $get_owning_company['AHM']= 'AHM';
                }
                $cash_balance_company = $get_owning_company;
                // $cash_balance_company = ['KMS'=>'KMS0', 'PAD'=>'PAD0', 'PPC'=>'PPC0', 'NJP'=>'NJP0',  'PNB'=>'PNB0', 'WKK'=>'WKK0', 'AHM'=>'AHM0'];    
            }

            // dd($current_user_login_company, $cash_balance_company);
            // RFC Cash balance dan Time Deposit
            $is_production = config('intranet.is_production');
            if($is_production)
                $connection = new SapConnection(config('intranet.rfc_prod'));
            else
                $connection = new SapConnection(config('intranet.rfc'));

            $options = [
                'rtrim'=>true,
            ];
            
            $cash_total_IDR = 0;
            $cash_total_USD = 0;
            $cash_total_JPY = 0;
            $cash_total_SGD = 0;
            $cash_total_EUR = 0;
            $cash_total_CNY = 0;
            $cash_total_ALL_IDR = 0;

            $deposit_total_IDR = 0;
            $deposit_total_USD = 0;
            $deposit_total_JPY = 0;
            $deposit_total_SGD = 0;
            $deposit_total_EUR = 0;
            $deposit_total_CNY = 0;
            $deposit_total_ALL_IDR = 0;
            $year = '';
            $year_balance = date('Y');
            if($request->get('year', false)){
                $year = $request->get('year') == (String)date('Y') ? '' : $request->get('year');
                $year_balance = $request->get('year');
            }

            $data['RFC'] = collect($cash_balance_company)->mapWithKeys(function($itemParent, $key) use ($connection, $options, 
                &$cash_total_IDR, &$cash_total_USD, &$cash_total_JPY, &$cash_total_SGD, &$cash_total_EUR, &$cash_total_CNY, &$cash_total_ALL_IDR,
                &$deposit_total_IDR, &$deposit_total_USD, &$deposit_total_JPY, &$deposit_total_SGD, &$deposit_total_EUR, &$deposit_total_CNY, &$deposit_total_ALL_IDR, $year, $year_balance
            ){
                $data = [];
                $company_info = DB::connection('dbintranet')
                ->table('INT_COMPANY')
                ->where('COMPANY_CODE', $key)
                ->select('COMPANY_NAME', 'COMPANY_CODE')
                ->get()->first();

                if($company_info){
                    if(self::isOwningCompany($itemParent)){
                        $plant_info = DB::connection('dbintranet')
                        ->table('INT_BUSINESS_PLANT')
                        ->where('COMPANY_CODE', $company_info->COMPANY_CODE)
                        ->select('SAP_PLANT_NAME', 'SAP_PLANT_ID', 'COMPANY_CODE')
                        ->get();
                    }
                    else {
                        $plant_info = DB::connection('dbintranet')
                        ->table('INT_BUSINESS_PLANT')
                        ->where('COMPANY_CODE', $company_info->COMPANY_CODE)
                        ->where('SAP_PLANT_ID', $itemParent)
                        ->select('SAP_PLANT_NAME', 'SAP_PLANT_ID', 'COMPANY_CODE')
                        ->get();
                    }

                    // Jika plant ditemukan
                    if(count($plant_info) > 0){
                        $data['PLANT_BALANCE'] = $plant_info->mapWithKeys(function($item, $order) use ($key, $connection, $options, 
                        &$cash_total_IDR, &$cash_total_USD, &$cash_total_JPY, &$cash_total_EUR, &$cash_total_CNY, &$cash_total_SGD, &$cash_total_ALL_IDR,
                        &$deposit_total_IDR, &$deposit_total_USD, &$deposit_total_JPY, &$deposit_total_SGD, &$deposit_total_EUR, &$deposit_total_CNY, &$deposit_total_ALL_IDR, $year, $year_balance){
                            $param = [
                                'GV_BUKRS'=>$item->COMPANY_CODE,
                                'GV_PROFIT_CENTER'=>$item->SAP_PLANT_ID,
                                'GV_FISCAL_YEAR'=>$year,
                            ];
                    
                            $cash_balance = [];
                            $time_deposit = [];

                            // TIME DEPOSIT
                            try {
                                $function = $connection->getFunction('Z_FGL_TIME_DEPOSIT');
                                $result= $function->invoke($param, $options);
                                $time_deposit = json_decode(json_encode($result));
                            } catch(SAPFunctionException $e){
                                Log::error($e->getMessage());
                            } catch(SapException $e){
                                Log::error($e->getMessage());
                            }

                            $data['TIME_DEPOSIT']['data'] = isset($time_deposit->IT_TIME_BALANCE) ? $time_deposit->IT_TIME_BALANCE : [];
                            if($data['TIME_DEPOSIT']['data']){
                                $data['TIME_DEPOSIT']['SUM_IDR'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_IDR');
                                $data['TIME_DEPOSIT']['SUM_USD'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_USD');
                                $data['TIME_DEPOSIT']['SUM_JPY'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_JPY');
                                $data['TIME_DEPOSIT']['SUM_SGD'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_SGD');
                                $data['TIME_DEPOSIT']['SUM_EUR'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_EUR');
                                $data['TIME_DEPOSIT']['SUM_CNY'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_CNY');
                                $data['TIME_DEPOSIT']['SUM_ALL'] = collect($data['TIME_DEPOSIT']['data'])->sum('EQUIV_IDR');

                                $deposit_total_IDR += $data['TIME_DEPOSIT']['SUM_IDR'];
                                $deposit_total_USD += $data['TIME_DEPOSIT']['SUM_USD'];
                                $deposit_total_JPY += $data['TIME_DEPOSIT']['SUM_JPY'];
                                $deposit_total_SGD += $data['TIME_DEPOSIT']['SUM_SGD'];
                                $deposit_total_EUR += $data['TIME_DEPOSIT']['SUM_EUR'];
                                $deposit_total_CNY += $data['TIME_DEPOSIT']['SUM_CNY'];
                                $deposit_total_ALL_IDR += $data['TIME_DEPOSIT']['SUM_ALL'];
                            }
                    
                            //CASH BALANCE
                            $param = [
                                'GV_BUKRS'=>$item->COMPANY_CODE,
                                'GV_PROFIT_CENTER'=>$item->SAP_PLANT_ID,
                                'GV_FISCAL_YEAR'=>$year_balance,
                            ];
                            try {
                                $function = $connection->getFunction('Z_FGL_CASH_BALANCE');
                                $result= $function->invoke($param, $options);
                                $cash_balance = json_decode(json_encode($result));
                            } catch(SAPFunctionException $e){
                                Log::error($e->getMessage());
                            } catch(SapException $e){
                                Log::error($e->getMessage());                
                            }
                    
                            $data['CASH_BALANCE']['data'] = isset($cash_balance->IT_CASH_BALANCE) ? $cash_balance->IT_CASH_BALANCE : [];   
                            if($data['CASH_BALANCE']['data']){
                                $data['CASH_BALANCE']['SUM_IDR'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_IDR');
                                $data['CASH_BALANCE']['SUM_USD'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_USD');
                                $data['CASH_BALANCE']['SUM_JPY'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_JPY');
                                $data['CASH_BALANCE']['SUM_SGD'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_SGD');
                                $data['CASH_BALANCE']['SUM_EUR'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_EUR');
                                $data['CASH_BALANCE']['SUM_CNY'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_CNY');
                                $data['CASH_BALANCE']['SUM_ALL'] = collect($data['CASH_BALANCE']['data'])->sum('EQUIV_IDR');

                                $cash_total_IDR += $data['CASH_BALANCE']['SUM_IDR'];
                                $cash_total_USD += $data['CASH_BALANCE']['SUM_USD'];
                                $cash_total_JPY += $data['CASH_BALANCE']['SUM_JPY'];
                                $cash_total_SGD += $data['CASH_BALANCE']['SUM_SGD'];
                                $cash_total_EUR += $data['CASH_BALANCE']['SUM_EUR'];
                                $cash_total_CNY += $data['CASH_BALANCE']['SUM_CNY'];
                                $cash_total_ALL_IDR += $data['CASH_BALANCE']['SUM_ALL'];
                            }

                            try {
                               
                               $data['RATE_USD'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray()))*1000 : 0 : 0;
                                $data['RATE_JPY'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray())) : 0 : 0;
                                $data['RATE_SGD'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray()))*1000 : 0 : 0;
                                $data['RATE_EUR'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray()))*1000 : 0 : 0;
                                $data['RATE_CNY'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray()))*1000 : 0 : 0;


                            } catch(\Exception $e){
                                $data['RATE_USD'] = 0;
                                $data['RATE_JPY'] = 0;
                                $data['RATE_SGD'] = 0;
                                $data['RATE_EUR'] = 0;
                                $data['RATE_CNY'] = 0;
                                Log::info('CASH BALANCE EXCHANGE RATE ERROR | '. $e->getMessage());
                            }

                            $data['CASH_BALANCE']['data'] = collect($data['CASH_BALANCE']['data'])->groupBy(function($item, $key){
                                return isset(explode('-', $item->BANK_NAME)[0]) ? trim(explode('-', $item->BANK_NAME)[0]) : $item->BANK_NAME;
                            })->map(function($item, $key){
                                $SUM_BALANCE_IDR = collect($item)->sum('BALANCE_IDR');
                                $SUM_BALANCE_USD = collect($item)->sum('BALANCE_USD');
                                $SUM_BALANCE_JPY = collect($item)->sum('BALANCE_JPY');
                                $SUM_BALANCE_SGD = collect($item)->sum('BALANCE_SGD');
                                $SUM_BALANCE_EUR = collect($item)->sum('BALANCE_EUR');
                                $SUM_BALANCE_CNY = collect($item)->sum('BALANCE_CNY');

                                $SUM_BALANCE_EQUIV_IDR = collect($item)->sum('EQUIV_IDR');
                                $item->GROUP_IDR = $SUM_BALANCE_IDR;
                                $item->GROUP_USD = $SUM_BALANCE_USD;
                                $item->GROUP_JPY = $SUM_BALANCE_JPY;
                                $item->GROUP_SGD = $SUM_BALANCE_SGD;
                                $item->GROUP_EUR = $SUM_BALANCE_EUR;
                                $item->GROUP_CNY = $SUM_BALANCE_CNY;
                                $item->GROUP_ALL = $SUM_BALANCE_EQUIV_IDR;

                                return $item;
                            })->all();

                            return ["(".$item->SAP_PLANT_ID.") ".$item->SAP_PLANT_NAME=>$data];
                        })->toArray();
                    }
                    return [$company_info->COMPANY_NAME => $data];
                }
            })->toArray();
            // dd($data['RFC']['PT NUSA JAYA PRIMA']);

            $data['CASH_BALANCE']['SUM_GROUP_IDR'] = $cash_total_IDR;
            $data['CASH_BALANCE']['SUM_GROUP_USD'] = $cash_total_USD;
            $data['CASH_BALANCE']['SUM_GROUP_JPY'] = $cash_total_JPY;
            $data['CASH_BALANCE']['SUM_GROUP_SGD'] = $cash_total_SGD;
            $data['CASH_BALANCE']['SUM_GROUP_EUR'] = $cash_total_EUR;
            $data['CASH_BALANCE']['SUM_GROUP_CNY'] = $cash_total_CNY;
            $data['CASH_BALANCE']['SUM_GROUP_ALL'] = $cash_total_ALL_IDR;

            $data['TIME_DEPOSIT']['SUM_GROUP_IDR'] = $deposit_total_IDR;
            $data['TIME_DEPOSIT']['SUM_GROUP_USD'] = $deposit_total_USD;
            $data['TIME_DEPOSIT']['SUM_GROUP_JPY'] = $deposit_total_JPY;
            $data['TIME_DEPOSIT']['SUM_GROUP_SGD'] = $deposit_total_SGD;
            $data['TIME_DEPOSIT']['SUM_GROUP_EUR'] = $deposit_total_EUR;
            $data['TIME_DEPOSIT']['SUM_GROUP_CNY'] = $deposit_total_CNY;
            $data['TIME_DEPOSIT']['SUM_GROUP_ALL'] = $deposit_total_ALL_IDR;
            $data['FISCAL_YEAR'] = $year;

            $rawHtml = view('pages.sap.cash-balance.template', ['data'=>$data])->render();
            return $rawHtml;
        }
        else {
            abort(403);
        }
    }

    function index_new(Request $request) {
        $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
        $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');
        $data['date_start'] = $date_start;
        $data['date_end'] = $date_end;
        $data['IS_NEED_PASSWORD'] = true;
        $cash_balance_company = [];

        $cek_permission_cash_balance = 'view_'. (String)route('cash_balance.index.new',[],false);
        $user_id = isset(session()->get('user_data')->EMPLOYEE_ID) ? session()->get('user_data')->EMPLOYEE_ID : '';
        $cek_plant_user = DB::connection('dbintranet')
        ->table('dbo.VIEW_EMPLOYEE_ACCESS')
        ->where('EMPLOYEE_ID', $user_id)
        ->select('SAP_PLANT_ID', 'COMPANY_CODE')
        ->get()->pluck('SAP_PLANT_ID','COMPANY_CODE')->filter()->toArray();

        // Jika tidak memiliki full access
        if(session()->get('permission_menu')->has($cek_permission_cash_balance) == false) {
            $cash_balance_company = $cek_plant_user;
        }
        else {
            // Tidak memerlukan password cash balance karena sudah memiliki full access
            $data['IS_NEED_PASSWORD'] = false;
            $get_owning_company = DB::connection('dbintranet')
            ->table('INT_BUSINESS_PLANT')
            ->select('SAP_PLANT_ID', 'COMPANY_CODE')
            ->get();
            $get_owning_company = $get_owning_company->filter(function ($value, $key) {
                return self::isOwningCompany($value->SAP_PLANT_ID);
            })->pluck('SAP_PLANT_ID','COMPANY_CODE')->toArray();
            // Karena AHM tidak ada indikasi owning ataupun anak perusahaan lain, ditambahkan manual
            if($get_owning_company){
                $get_owning_company['AHM']= 'AHM';
            }
            $cash_balance_company = $get_owning_company;
        }

        // RFC Cash balance dan Time Deposit
        $is_production = config('intranet.is_production');
        if($is_production)
            $connection = new SapConnection(config('intranet.rfc_prod'));
        else
            $connection = new SapConnection(config('intranet.rfc'));

        $options = [
            'rtrim'=>true,
        ];
        
        $cash_total_IDR = 0;
        $cash_total_USD = 0;
        $cash_total_JPY = 0;
        $cash_total_SGD = 0;
        $cash_total_EUR = 0;
        $cash_total_CNY = 0;
        $cash_total_ALL_IDR = 0;

        $deposit_total_IDR = 0;
        $deposit_total_USD = 0;
        $deposit_total_JPY = 0;
        $deposit_total_SGD = 0;
        $deposit_total_EUR = 0;
        $deposit_total_CNY = 0;
        $deposit_total_ALL_IDR = 0;

        $oth_fin_total_IDR = 0;
        $oth_fin_total_USD = 0;
        $oth_fin_total_JPY = 0;
        $oth_fin_total_SGD = 0;
        $oth_fin_total_EUR = 0;
        $oth_fin_total_CNY = 0;
        $oth_fin_total_ALL_IDR = 0;
        $year = date('Y');
        $data['RFC'] = collect($cash_balance_company)->mapWithKeys(function($itemParent, $key) use ($connection, $options, 
            &$cash_total_IDR, &$cash_total_USD, &$cash_total_JPY, &$cash_total_SGD, &$cash_total_EUR, &$cash_total_CNY, &$cash_total_ALL_IDR,
            &$deposit_total_IDR, &$deposit_total_USD, &$deposit_total_JPY, &$deposit_total_SGD, &$deposit_total_EUR, &$deposit_total_CNY, &$deposit_total_ALL_IDR, &$oth_fin_total_IDR, &$oth_fin_total_USD, &$oth_fin_total_JPY, &$oth_fin_total_SGD, &$oth_fin_total_EUR, &$oth_fin_total_CNY, &$oth_fin_total_ALL_IDR, $year
        ){
            $data = [];
            $company_info = DB::connection('dbintranet')
            ->table('INT_COMPANY')
            ->where('COMPANY_CODE', $key)
            ->select('COMPANY_NAME', 'COMPANY_CODE')
            ->get()->first();

            if($company_info){
                if(self::isOwningCompany($itemParent)){
                    $plant_info = DB::connection('dbintranet')
                    ->table('INT_BUSINESS_PLANT')
                    ->where('COMPANY_CODE', $company_info->COMPANY_CODE)
                    ->select('SAP_PLANT_NAME', 'SAP_PLANT_ID', 'COMPANY_CODE')
                    ->get();
                }
                else {
                    $plant_info = DB::connection('dbintranet')
                    ->table('INT_BUSINESS_PLANT')
                    ->where('COMPANY_CODE', $company_info->COMPANY_CODE)
                    ->where('SAP_PLANT_ID', $itemParent)
                    ->select('SAP_PLANT_NAME', 'SAP_PLANT_ID', 'COMPANY_CODE')
                    ->get();
                }

                $total_per_company = array(
                    'IDR'=>0,
                    'USD'=>0,
                    'JPY'=>0,
                    'SGD'=>0,
                    'EUR'=>0,
                    'CNY'=>0,
                    'TOTAL_IDR'=>0
                );
                // Jika plant ditemukan
                if(count($plant_info) > 0){
                    $data['PLANT_BALANCE'] = $plant_info->mapWithKeys(function($item, $order) use ($key, $connection, $options, 
                    &$cash_total_IDR, &$cash_total_USD, &$cash_total_JPY, &$cash_total_EUR, &$cash_total_CNY, &$cash_total_SGD, &$cash_total_ALL_IDR,
                    &$deposit_total_IDR, &$deposit_total_USD, &$deposit_total_JPY, &$deposit_total_SGD, &$deposit_total_EUR, &$deposit_total_CNY, &$deposit_total_ALL_IDR, &$oth_fin_total_IDR, &$oth_fin_total_USD, &$oth_fin_total_JPY, &$oth_fin_total_SGD, &$oth_fin_total_EUR, &$oth_fin_total_CNY, &$oth_fin_total_ALL_IDR, $year, &$total_per_company){
                        $param = [
                            'GV_BUKRS'=>$item->COMPANY_CODE,
                            'GV_PROFIT_CENTER'=>$item->SAP_PLANT_ID,
                            'GV_FISCAL_YEAR'=>'',
                        ];
                
                        $cash_balance = [];
                        $time_deposit = [];
                        $other_financial_asset = [];

                        // TIME DEPOSIT
                        try {
                            $function = $connection->getFunction('Z_FGL_TIME_DEPOSIT');
                            $result= $function->invoke($param, $options);
                            $time_deposit = json_decode(json_encode($result));
                        } catch(SAPFunctionException $e){
                            Log::error($e->getMessage());
                        } catch(SapException $e){
                            Log::error($e->getMessage());
                        }


                        $time_deposit = collect(isset($time_deposit->IT_TIME_BALANCE) ? $time_deposit->IT_TIME_BALANCE : [])->reject(function($val) use (&$other_financial_asset)
                        {
                            $desc = isset($val->TXT50) ? strtoupper($val->TXT50) : '';
                            // insert to other financial asset (Bonds, Mutual Fund, Money Market)
                            if(strpos($desc, 'DEPOSIT') === false)
                                array_push($other_financial_asset, $val);
                            // Finally reject text that does not include deposit
                            return strpos($desc, 'DEPOSIT') === false;
                        })->values()->all();

                        $data['TIME_DEPOSIT']['data'] = $time_deposit;
                        if($data['TIME_DEPOSIT']['data']){
                            $data['TIME_DEPOSIT']['SUM_IDR'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_IDR');
                            $data['TIME_DEPOSIT']['SUM_USD'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_USD');
                            $data['TIME_DEPOSIT']['SUM_JPY'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_JPY');
                            $data['TIME_DEPOSIT']['SUM_SGD'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_SGD');
                            $data['TIME_DEPOSIT']['SUM_EUR'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_EUR');
                            $data['TIME_DEPOSIT']['SUM_CNY'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_CNY');
                            $data['TIME_DEPOSIT']['SUM_ALL'] = collect($data['TIME_DEPOSIT']['data'])->sum('EQUIV_IDR');

                            $deposit_total_IDR += $data['TIME_DEPOSIT']['SUM_IDR'];
                            $deposit_total_USD += $data['TIME_DEPOSIT']['SUM_USD'];
                            $deposit_total_JPY += $data['TIME_DEPOSIT']['SUM_JPY'];
                            $deposit_total_SGD += $data['TIME_DEPOSIT']['SUM_SGD'];
                            $deposit_total_EUR += $data['TIME_DEPOSIT']['SUM_EUR'];
                            $deposit_total_CNY += $data['TIME_DEPOSIT']['SUM_CNY'];
                            $deposit_total_ALL_IDR += $data['TIME_DEPOSIT']['SUM_ALL'];

                            $total_per_company['IDR'] += $data['TIME_DEPOSIT']['SUM_IDR'];
                            $total_per_company['USD'] += $data['TIME_DEPOSIT']['SUM_USD'];
                            $total_per_company['JPY'] += $data['TIME_DEPOSIT']['SUM_JPY'];
                            $total_per_company['SGD'] += $data['TIME_DEPOSIT']['SUM_SGD'];
                            $total_per_company['EUR'] += $data['TIME_DEPOSIT']['SUM_EUR'];
                            $total_per_company['CNY'] += $data['TIME_DEPOSIT']['SUM_CNY'];
                            $total_per_company['TOTAL_IDR'] += $data['TIME_DEPOSIT']['SUM_ALL'];
                        }

                        $data['OTHER_FIN_ASSET']['data'] = $other_financial_asset;
                        if($data['OTHER_FIN_ASSET']['data']){
                            $data['OTHER_FIN_ASSET']['SUM_IDR'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_IDR');
                            $data['OTHER_FIN_ASSET']['SUM_USD'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_USD');
                            $data['OTHER_FIN_ASSET']['SUM_JPY'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_JPY');
                            $data['OTHER_FIN_ASSET']['SUM_SGD'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_SGD');
                            $data['OTHER_FIN_ASSET']['SUM_EUR'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_EUR');
                            $data['OTHER_FIN_ASSET']['SUM_CNY'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_CNY');
                            $data['OTHER_FIN_ASSET']['SUM_ALL'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('EQUIV_IDR');

                            $oth_fin_total_IDR += $data['OTHER_FIN_ASSET']['SUM_IDR'];
                            $oth_fin_total_USD += $data['OTHER_FIN_ASSET']['SUM_USD'];
                            $oth_fin_total_JPY += $data['OTHER_FIN_ASSET']['SUM_JPY'];
                            $oth_fin_total_SGD += $data['OTHER_FIN_ASSET']['SUM_SGD'];
                            $oth_fin_total_EUR += $data['OTHER_FIN_ASSET']['SUM_EUR'];
                            $oth_fin_total_CNY += $data['OTHER_FIN_ASSET']['SUM_CNY'];
                            $oth_fin_total_ALL_IDR += $data['OTHER_FIN_ASSET']['SUM_ALL'];

                            $total_per_company['IDR'] += $data['OTHER_FIN_ASSET']['SUM_IDR'];
                            $total_per_company['USD'] += $data['OTHER_FIN_ASSET']['SUM_USD'];
                            $total_per_company['JPY'] += $data['OTHER_FIN_ASSET']['SUM_JPY'];
                            $total_per_company['SGD'] += $data['OTHER_FIN_ASSET']['SUM_SGD'];
                            $total_per_company['EUR'] += $data['OTHER_FIN_ASSET']['SUM_EUR'];
                            $total_per_company['CNY'] += $data['OTHER_FIN_ASSET']['SUM_CNY'];
                            $total_per_company['TOTAL_IDR'] += $data['OTHER_FIN_ASSET']['SUM_ALL'];
                        }
                        
                        $param = [
                            'GV_BUKRS'=>$item->COMPANY_CODE,
                            'GV_PROFIT_CENTER'=>$item->SAP_PLANT_ID,
                            'GV_FISCAL_YEAR'=>$year,
                        ];
                        //CASH BALANCE
                        try {
                            $function = $connection->getFunction('Z_FGL_CASH_BALANCE');
                            $result= $function->invoke($param, $options);
                            $cash_balance = json_decode(json_encode($result));
                        } catch(SAPFunctionException $e){
                            Log::error($e->getMessage());
                        } catch(SapException $e){
                            Log::error($e->getMessage());                
                        }
                
                        $data['CASH_BALANCE']['data'] = isset($cash_balance->IT_CASH_BALANCE) ? $cash_balance->IT_CASH_BALANCE : [];   
                        if($data['CASH_BALANCE']['data']){
                            $data['CASH_BALANCE']['SUM_IDR'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_IDR');
                            $data['CASH_BALANCE']['SUM_USD'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_USD');
                            $data['CASH_BALANCE']['SUM_JPY'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_JPY');
                            $data['CASH_BALANCE']['SUM_SGD'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_SGD');
                            $data['CASH_BALANCE']['SUM_EUR'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_EUR');
                            $data['CASH_BALANCE']['SUM_CNY'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_CNY');
                            $data['CASH_BALANCE']['SUM_ALL'] = collect($data['CASH_BALANCE']['data'])->sum('EQUIV_IDR');

                            $cash_total_IDR += $data['CASH_BALANCE']['SUM_IDR'];
                            $cash_total_USD += $data['CASH_BALANCE']['SUM_USD'];
                            $cash_total_JPY += $data['CASH_BALANCE']['SUM_JPY'];
                            $cash_total_SGD += $data['CASH_BALANCE']['SUM_SGD'];
                            $cash_total_EUR += $data['CASH_BALANCE']['SUM_EUR'];
                            $cash_total_CNY += $data['CASH_BALANCE']['SUM_CNY'];
                            $cash_total_ALL_IDR += $data['CASH_BALANCE']['SUM_ALL'];

                            $total_per_company['IDR'] += $data['CASH_BALANCE']['SUM_IDR'];
                            $total_per_company['USD'] += $data['CASH_BALANCE']['SUM_USD'];
                            $total_per_company['JPY'] += $data['CASH_BALANCE']['SUM_JPY'];
                            $total_per_company['SGD'] += $data['CASH_BALANCE']['SUM_SGD'];
                            $total_per_company['EUR'] += $data['CASH_BALANCE']['SUM_EUR'];
                            $total_per_company['CNY'] += $data['CASH_BALANCE']['SUM_CNY'];
                            $total_per_company['TOTAL_IDR'] += $data['CASH_BALANCE']['SUM_ALL'];
                        }

                        try {                           
                           $data['RATE_USD'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray()))*1000 : 0 : 0;
                            $data['RATE_JPY'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray())) : 0 : 0;
                            $data['RATE_SGD'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray()))*1000 : 0 : 0;
                            $data['RATE_EUR'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray()))*1000 : 0 : 0;
                            $data['RATE_CNY'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray()))*1000 : 0 : 0;


                        } catch(\Exception $e){
                            $data['RATE_USD'] = 0;
                            $data['RATE_JPY'] = 0;
                            $data['RATE_SGD'] = 0;
                            $data['RATE_EUR'] = 0;
                            $data['RATE_CNY'] = 0;
                            Log::info('CASH BALANCE EXCHANGE RATE ERROR | '. $e->getMessage());
                        }

                        $data['CASH_BALANCE']['data'] = collect($data['CASH_BALANCE']['data'])->groupBy(function($item, $key){
                            return isset(explode('-', $item->BANK_NAME)[0]) ? trim(explode('-', $item->BANK_NAME)[0]) : $item->BANK_NAME;
                        })->map(function($item, $key){
                            $SUM_BALANCE_IDR = collect($item)->sum('BALANCE_IDR');
                            $SUM_BALANCE_USD = collect($item)->sum('BALANCE_USD');
                            $SUM_BALANCE_JPY = collect($item)->sum('BALANCE_JPY');
                            $SUM_BALANCE_SGD = collect($item)->sum('BALANCE_SGD');
                            $SUM_BALANCE_EUR = collect($item)->sum('BALANCE_EUR');
                            $SUM_BALANCE_CNY = collect($item)->sum('BALANCE_CNY');

                            $SUM_BALANCE_EQUIV_IDR = collect($item)->sum('EQUIV_IDR');
                            $item->GROUP_IDR = $SUM_BALANCE_IDR;
                            $item->GROUP_USD = $SUM_BALANCE_USD;
                            $item->GROUP_JPY = $SUM_BALANCE_JPY;
                            $item->GROUP_SGD = $SUM_BALANCE_SGD;
                            $item->GROUP_EUR = $SUM_BALANCE_EUR;
                            $item->GROUP_CNY = $SUM_BALANCE_CNY;
                            $item->GROUP_ALL = $SUM_BALANCE_EQUIV_IDR;

                            return $item;
                        })->all();

                        return ["(".$item->SAP_PLANT_ID.") ".$item->SAP_PLANT_NAME=>$data];
                    })->toArray();
                }
                $data['TOTAL_COMPANY'] = $total_per_company;
                return [$company_info->COMPANY_NAME => $data];
            }
        })->toArray();
    
        $data['BALANCE']['SUM_IDR'] = $cash_total_IDR + $deposit_total_IDR + $oth_fin_total_IDR;
        $data['BALANCE']['SUM_USD'] = $cash_total_USD + $deposit_total_USD + $oth_fin_total_USD;
        $data['BALANCE']['SUM_JPY'] = $cash_total_JPY + $deposit_total_JPY + $oth_fin_total_JPY;
        $data['BALANCE']['SUM_SGD'] = $cash_total_SGD + $deposit_total_SGD + $oth_fin_total_SGD;
        $data['BALANCE']['SUM_EUR'] = $cash_total_EUR + $deposit_total_EUR + $oth_fin_total_EUR;
        $data['BALANCE']['SUM_CNY'] = $cash_total_CNY + $deposit_total_CNY + $oth_fin_total_CNY;
        $data['BALANCE']['SUM_ALL'] = $cash_total_ALL_IDR + $deposit_total_ALL_IDR + $oth_fin_total_ALL_IDR;
        $data['FISCAL_YEAR'] = $year;
        
        $rawHtml = view('pages.sap.cash-balance-new.template', ['data'=>$data])->render();
        return view('pages.sap.cash-balance-new.index', ['template'=>$rawHtml, 'data'=>$data]);
    }

    public static function filter_cash_balance_new(Request $request){
        if(self::wantsJson($request)){

            $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
            $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');
            $data['date_start'] = $date_start;
            $data['date_end'] = $date_end;
            $data['IS_NEED_PASSWORD'] = true;
            $cash_balance_company = [];

            $cek_permission_cash_balance = 'view_'. (String)route('cash_balance.index.new',[],false);
            // $current_user_login_company = isset(session()->get('user_data')->EMPLOYEE_ID) ? substr(session()->get('user_data')->EMPLOYEE_ID, 0,2) : '';
            $user_id = isset(session()->get('user_data')->EMPLOYEE_ID) ? session()->get('user_data')->EMPLOYEE_ID : '';
            $cek_plant_user = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE_ACCESS')
            ->where('EMPLOYEE_ID', $user_id)
            ->select('SAP_PLANT_ID', 'COMPANY_CODE')
            ->get()->pluck('SAP_PLANT_ID','COMPANY_CODE')->filter()->toArray();

            if(session()->get('permission_menu')->has($cek_permission_cash_balance) == false) {
                $cash_balance_company = $cek_plant_user;
            }
            else {
                $data['IS_NEED_PASSWORD'] = false;
                $get_owning_company = DB::connection('dbintranet')
                ->table('INT_BUSINESS_PLANT')
                ->select('SAP_PLANT_ID', 'COMPANY_CODE')
                ->get();
                $get_owning_company = $get_owning_company->filter(function ($value, $key) {
                    return self::isOwningCompany($value->SAP_PLANT_ID);
                })->pluck('SAP_PLANT_ID','COMPANY_CODE')->toArray();
                // Karena AHM tidak ada indikasi owning ataupun anak perusahaan lain, ditambahkan manual
                if($get_owning_company){
                    $get_owning_company['AHM']= 'AHM';
                }
                $cash_balance_company = $get_owning_company;
                // $cash_balance_company = ['KMS'=>'KMS0', 'PAD'=>'PAD0', 'PPC'=>'PPC0', 'NJP'=>'NJP0',  'PNB'=>'PNB0', 'WKK'=>'WKK0', 'AHM'=>'AHM0'];    
            }

            // dd($current_user_login_company, $cash_balance_company);
            // RFC Cash balance dan Time Deposit
            $is_production = config('intranet.is_production');
            if($is_production)
                $connection = new SapConnection(config('intranet.rfc_prod'));
            else
                $connection = new SapConnection(config('intranet.rfc'));

            $options = [
                'rtrim'=>true,
            ];
            
            $cash_total_IDR = 0;
            $cash_total_USD = 0;
            $cash_total_JPY = 0;
            $cash_total_SGD = 0;
            $cash_total_EUR = 0;
            $cash_total_CNY = 0;
            $cash_total_ALL_IDR = 0;

            $deposit_total_IDR = 0;
            $deposit_total_USD = 0;
            $deposit_total_JPY = 0;
            $deposit_total_SGD = 0;
            $deposit_total_EUR = 0;
            $deposit_total_CNY = 0;
            $deposit_total_ALL_IDR = 0;

            $oth_fin_total_IDR = 0;
            $oth_fin_total_USD = 0;
            $oth_fin_total_JPY = 0;
            $oth_fin_total_SGD = 0;
            $oth_fin_total_EUR = 0;
            $oth_fin_total_CNY = 0;
            $oth_fin_total_ALL_IDR = 0;

            $year = '';
            $year_balance = date('Y');
            if($request->get('year', false)){
                $year = $request->get('year') == (String)date('Y') ? '' : $request->get('year');
                $year_balance = $request->get('year');
            }

            $data['RFC'] = collect($cash_balance_company)->mapWithKeys(function($itemParent, $key) use ($connection, $options, 
                &$cash_total_IDR, &$cash_total_USD, &$cash_total_JPY, &$cash_total_SGD, &$cash_total_EUR, &$cash_total_CNY, &$cash_total_ALL_IDR,
                &$deposit_total_IDR, &$deposit_total_USD, &$deposit_total_JPY, &$deposit_total_SGD, &$deposit_total_EUR, &$deposit_total_CNY, &$deposit_total_ALL_IDR, &$oth_fin_total_IDR, &$oth_fin_total_USD, &$oth_fin_total_JPY, &$oth_fin_total_SGD, &$oth_fin_total_EUR, &$oth_fin_total_CNY, &$oth_fin_total_ALL_IDR, $year, $year_balance
            ){
                $data = [];
                $company_info = DB::connection('dbintranet')
                ->table('INT_COMPANY')
                ->where('COMPANY_CODE', $key)
                ->select('COMPANY_NAME', 'COMPANY_CODE')
                ->get()->first();

                if($company_info){
                    if(self::isOwningCompany($itemParent)){
                        $plant_info = DB::connection('dbintranet')
                        ->table('INT_BUSINESS_PLANT')
                        ->where('COMPANY_CODE', $company_info->COMPANY_CODE)
                        ->select('SAP_PLANT_NAME', 'SAP_PLANT_ID', 'COMPANY_CODE')
                        ->get();
                    }
                    else {
                        $plant_info = DB::connection('dbintranet')
                        ->table('INT_BUSINESS_PLANT')
                        ->where('COMPANY_CODE', $company_info->COMPANY_CODE)
                        ->where('SAP_PLANT_ID', $itemParent)
                        ->select('SAP_PLANT_NAME', 'SAP_PLANT_ID', 'COMPANY_CODE')
                        ->get();
                    }

                    $total_per_company = array(
                        'IDR'=>0,
                        'USD'=>0,
                        'JPY'=>0,
                        'SGD'=>0,
                        'EUR'=>0,
                        'CNY'=>0,
                        'TOTAL_IDR'=>0
                    );
                    // Jika plant ditemukan
                    if(count($plant_info) > 0){
                        $data['PLANT_BALANCE'] = $plant_info->mapWithKeys(function($item, $order) use ($key, $connection, $options, 
                        &$cash_total_IDR, &$cash_total_USD, &$cash_total_JPY, &$cash_total_EUR, &$cash_total_CNY, &$cash_total_SGD, &$cash_total_ALL_IDR,
                        &$deposit_total_IDR, &$deposit_total_USD, &$deposit_total_JPY, &$deposit_total_SGD, &$deposit_total_EUR, &$deposit_total_CNY, &$deposit_total_ALL_IDR, &$oth_fin_total_IDR, &$oth_fin_total_USD, &$oth_fin_total_JPY, &$oth_fin_total_SGD, &$oth_fin_total_EUR, &$oth_fin_total_CNY, &$oth_fin_total_ALL_IDR, $year, $year_balance, &$total_per_company){
                            $param = [
                                'GV_BUKRS'=>$item->COMPANY_CODE,
                                'GV_PROFIT_CENTER'=>$item->SAP_PLANT_ID,
                                'GV_FISCAL_YEAR'=>$year,
                            ];
                    
                            $cash_balance = [];
                            $time_deposit = [];
                            $other_financial_asset = [];

                            // TIME DEPOSIT
                            try {
                                $function = $connection->getFunction('Z_FGL_TIME_DEPOSIT');
                                $result= $function->invoke($param, $options);
                                $time_deposit = json_decode(json_encode($result));
                            } catch(SAPFunctionException $e){
                                Log::error($e->getMessage());
                            } catch(SapException $e){
                                Log::error($e->getMessage());
                            }

                            $time_deposit = collect(isset($time_deposit->IT_TIME_BALANCE) ? $time_deposit->IT_TIME_BALANCE : [])->reject(function($val) use (&$other_financial_asset)
                            {
                                $desc = isset($val->TXT50) ? strtoupper($val->TXT50) : '';
                                // insert to other financial asset (Bonds, Mutual Fund, Money Market)
                                if(strpos($desc, 'DEPOSIT') === false)
                                    array_push($other_financial_asset, $val);
                                // Finally reject text that does not include deposit
                                return strpos($desc, 'DEPOSIT') === false;
                            })->values()->all();

                            $data['TIME_DEPOSIT']['data'] = $time_deposit;
                            if($data['TIME_DEPOSIT']['data']){
                                $data['TIME_DEPOSIT']['SUM_IDR'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_IDR');
                                $data['TIME_DEPOSIT']['SUM_USD'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_USD');
                                $data['TIME_DEPOSIT']['SUM_JPY'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_JPY');
                                $data['TIME_DEPOSIT']['SUM_SGD'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_SGD');
                                $data['TIME_DEPOSIT']['SUM_EUR'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_EUR');
                                $data['TIME_DEPOSIT']['SUM_CNY'] = collect($data['TIME_DEPOSIT']['data'])->sum('CURR_CNY');
                                $data['TIME_DEPOSIT']['SUM_ALL'] = collect($data['TIME_DEPOSIT']['data'])->sum('EQUIV_IDR');

                                $deposit_total_IDR += $data['TIME_DEPOSIT']['SUM_IDR'];
                                $deposit_total_USD += $data['TIME_DEPOSIT']['SUM_USD'];
                                $deposit_total_JPY += $data['TIME_DEPOSIT']['SUM_JPY'];
                                $deposit_total_SGD += $data['TIME_DEPOSIT']['SUM_SGD'];
                                $deposit_total_EUR += $data['TIME_DEPOSIT']['SUM_EUR'];
                                $deposit_total_CNY += $data['TIME_DEPOSIT']['SUM_CNY'];
                                $deposit_total_ALL_IDR += $data['TIME_DEPOSIT']['SUM_ALL'];

                                $total_per_company['IDR'] += $data['TIME_DEPOSIT']['SUM_IDR'];
                                $total_per_company['USD'] += $data['TIME_DEPOSIT']['SUM_USD'];
                                $total_per_company['JPY'] += $data['TIME_DEPOSIT']['SUM_JPY'];
                                $total_per_company['SGD'] += $data['TIME_DEPOSIT']['SUM_SGD'];
                                $total_per_company['EUR'] += $data['TIME_DEPOSIT']['SUM_EUR'];
                                $total_per_company['CNY'] += $data['TIME_DEPOSIT']['SUM_CNY'];
                                $total_per_company['TOTAL_IDR'] += $data['TIME_DEPOSIT']['SUM_ALL'];
                            }

                            $data['OTHER_FIN_ASSET']['data'] = $other_financial_asset;
                            if($data['OTHER_FIN_ASSET']['data']){
                                $data['OTHER_FIN_ASSET']['SUM_IDR'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_IDR');
                                $data['OTHER_FIN_ASSET']['SUM_USD'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_USD');
                                $data['OTHER_FIN_ASSET']['SUM_JPY'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_JPY');
                                $data['OTHER_FIN_ASSET']['SUM_SGD'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_SGD');
                                $data['OTHER_FIN_ASSET']['SUM_EUR'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_EUR');
                                $data['OTHER_FIN_ASSET']['SUM_CNY'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('CURR_CNY');
                                $data['OTHER_FIN_ASSET']['SUM_ALL'] = collect($data['OTHER_FIN_ASSET']['data'])->sum('EQUIV_IDR');

                                $oth_fin_total_IDR += $data['OTHER_FIN_ASSET']['SUM_IDR'];
                                $oth_fin_total_USD += $data['OTHER_FIN_ASSET']['SUM_USD'];
                                $oth_fin_total_JPY += $data['OTHER_FIN_ASSET']['SUM_JPY'];
                                $oth_fin_total_SGD += $data['OTHER_FIN_ASSET']['SUM_SGD'];
                                $oth_fin_total_EUR += $data['OTHER_FIN_ASSET']['SUM_EUR'];
                                $oth_fin_total_CNY += $data['OTHER_FIN_ASSET']['SUM_CNY'];
                                $oth_fin_total_ALL_IDR += $data['OTHER_FIN_ASSET']['SUM_ALL'];

                                $total_per_company['IDR'] += $data['OTHER_FIN_ASSET']['SUM_IDR'];
                                $total_per_company['USD'] += $data['OTHER_FIN_ASSET']['SUM_USD'];
                                $total_per_company['JPY'] += $data['OTHER_FIN_ASSET']['SUM_JPY'];
                                $total_per_company['SGD'] += $data['OTHER_FIN_ASSET']['SUM_SGD'];
                                $total_per_company['EUR'] += $data['OTHER_FIN_ASSET']['SUM_EUR'];
                                $total_per_company['CNY'] += $data['OTHER_FIN_ASSET']['SUM_CNY'];
                                $total_per_company['TOTAL_IDR'] += $data['OTHER_FIN_ASSET']['SUM_ALL'];
                            }
                    
                            //CASH BALANCE
                            $param = [
                                'GV_BUKRS'=>$item->COMPANY_CODE,
                                'GV_PROFIT_CENTER'=>$item->SAP_PLANT_ID,
                                'GV_FISCAL_YEAR'=>$year_balance,
                            ];
                            try {
                                $function = $connection->getFunction('Z_FGL_CASH_BALANCE');
                                $result= $function->invoke($param, $options);
                                $cash_balance = json_decode(json_encode($result));
                            } catch(SAPFunctionException $e){
                                Log::error($e->getMessage());
                            } catch(SapException $e){
                                Log::error($e->getMessage());                
                            }
                    
                            $data['CASH_BALANCE']['data'] = isset($cash_balance->IT_CASH_BALANCE) ? $cash_balance->IT_CASH_BALANCE : [];   
                            if($data['CASH_BALANCE']['data']){
                                $data['CASH_BALANCE']['SUM_IDR'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_IDR');
                                $data['CASH_BALANCE']['SUM_USD'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_USD');
                                $data['CASH_BALANCE']['SUM_JPY'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_JPY');
                                $data['CASH_BALANCE']['SUM_SGD'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_SGD');
                                $data['CASH_BALANCE']['SUM_EUR'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_EUR');
                                $data['CASH_BALANCE']['SUM_CNY'] = collect($data['CASH_BALANCE']['data'])->sum('BALANCE_CNY');
                                $data['CASH_BALANCE']['SUM_ALL'] = collect($data['CASH_BALANCE']['data'])->sum('EQUIV_IDR');

                                $cash_total_IDR += $data['CASH_BALANCE']['SUM_IDR'];
                                $cash_total_USD += $data['CASH_BALANCE']['SUM_USD'];
                                $cash_total_JPY += $data['CASH_BALANCE']['SUM_JPY'];
                                $cash_total_SGD += $data['CASH_BALANCE']['SUM_SGD'];
                                $cash_total_EUR += $data['CASH_BALANCE']['SUM_EUR'];
                                $cash_total_CNY += $data['CASH_BALANCE']['SUM_CNY'];
                                $cash_total_ALL_IDR += $data['CASH_BALANCE']['SUM_ALL'];

                                $total_per_company['IDR'] += $data['CASH_BALANCE']['SUM_IDR'];
                                $total_per_company['USD'] += $data['CASH_BALANCE']['SUM_USD'];
                                $total_per_company['JPY'] += $data['CASH_BALANCE']['SUM_JPY'];
                                $total_per_company['SGD'] += $data['CASH_BALANCE']['SUM_SGD'];
                                $total_per_company['EUR'] += $data['CASH_BALANCE']['SUM_EUR'];
                                $total_per_company['CNY'] += $data['CASH_BALANCE']['SUM_CNY'];
                                $total_per_company['TOTAL_IDR'] += $data['CASH_BALANCE']['SUM_ALL'];
                            }

                            try {
                               
                               $data['RATE_USD'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_USD')->filter()->toArray()))*1000 : 0 : 0;
                                $data['RATE_JPY'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_JPY')->filter()->toArray())) : 0 : 0;
                                $data['RATE_SGD'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_SGD')->filter()->toArray()))*1000 : 0 : 0;
                                $data['RATE_EUR'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_EUR')->filter()->toArray()))*1000 : 0 : 0;
                                $data['RATE_CNY'] = collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray() ? max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray()) != 0 ? (float)(max(collect($data['CASH_BALANCE']['data'])->pluck('EXC_CNY')->filter()->toArray()))*1000 : 0 : 0;


                            } catch(\Exception $e){
                                $data['RATE_USD'] = 0;
                                $data['RATE_JPY'] = 0;
                                $data['RATE_SGD'] = 0;
                                $data['RATE_EUR'] = 0;
                                $data['RATE_CNY'] = 0;
                                Log::info('CASH BALANCE EXCHANGE RATE ERROR | '. $e->getMessage());
                            }

                            $data['CASH_BALANCE']['data'] = collect($data['CASH_BALANCE']['data'])->groupBy(function($item, $key){
                                return isset(explode('-', $item->BANK_NAME)[0]) ? trim(explode('-', $item->BANK_NAME)[0]) : $item->BANK_NAME;
                            })->map(function($item, $key){
                                $SUM_BALANCE_IDR = collect($item)->sum('BALANCE_IDR');
                                $SUM_BALANCE_USD = collect($item)->sum('BALANCE_USD');
                                $SUM_BALANCE_JPY = collect($item)->sum('BALANCE_JPY');
                                $SUM_BALANCE_SGD = collect($item)->sum('BALANCE_SGD');
                                $SUM_BALANCE_EUR = collect($item)->sum('BALANCE_EUR');
                                $SUM_BALANCE_CNY = collect($item)->sum('BALANCE_CNY');

                                $SUM_BALANCE_EQUIV_IDR = collect($item)->sum('EQUIV_IDR');
                                $item->GROUP_IDR = $SUM_BALANCE_IDR;
                                $item->GROUP_USD = $SUM_BALANCE_USD;
                                $item->GROUP_JPY = $SUM_BALANCE_JPY;
                                $item->GROUP_SGD = $SUM_BALANCE_SGD;
                                $item->GROUP_EUR = $SUM_BALANCE_EUR;
                                $item->GROUP_CNY = $SUM_BALANCE_CNY;
                                $item->GROUP_ALL = $SUM_BALANCE_EQUIV_IDR;

                                return $item;
                            })->all();

                            return ["(".$item->SAP_PLANT_ID.") ".$item->SAP_PLANT_NAME=>$data];
                        })->toArray();
                    }

                    $data['TOTAL_COMPANY'] = $total_per_company;
                    return [$company_info->COMPANY_NAME => $data];
                }
            })->toArray();
            
            $data['BALANCE']['SUM_IDR'] = $cash_total_IDR + $deposit_total_IDR + $oth_fin_total_IDR;
            $data['BALANCE']['SUM_USD'] = $cash_total_USD + $deposit_total_USD + $oth_fin_total_USD;
            $data['BALANCE']['SUM_JPY'] = $cash_total_JPY + $deposit_total_JPY + $oth_fin_total_JPY;
            $data['BALANCE']['SUM_SGD'] = $cash_total_SGD + $deposit_total_SGD + $oth_fin_total_SGD;
            $data['BALANCE']['SUM_EUR'] = $cash_total_EUR + $deposit_total_EUR + $oth_fin_total_EUR;
            $data['BALANCE']['SUM_CNY'] = $cash_total_CNY + $deposit_total_CNY + $oth_fin_total_CNY;
            $data['BALANCE']['SUM_ALL'] = $cash_total_ALL_IDR + $deposit_total_ALL_IDR + $oth_fin_total_ALL_IDR;
            $data['FISCAL_YEAR'] = $year;

            $rawHtml = view('pages.sap.cash-balance-new.template', ['data'=>$data])->render();
            return $rawHtml;
        }
        else {
            abort(403);
        }
    }
}
