<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
Use Cookie;
use DataTables;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use Log;

class ReportAging extends Controller{
    private $form_number;
    private $exluded_customer_id_temporary;

    public function __construct()
    {
        $this->form_number = "";
        $this->exluded_customer_id_temporary = ['7999999997', '7999999999'];
    }

    function report_ar_aging_vendor_getData(Request $request){
        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc'));

        $options = [
            'rtrim'=>true,
        ];

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="KMS";
            $plant="KMS1";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";

        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
        }

        $date_from = date('Y0101', strtotime('-10 year'));
        // dd($date_from);
        if($request->get('date', false))
            $date_to = date('Ymd',strtotime($request->get('date', false)));
        else 
            $date_to = null;
        // $filter_company=strtoupper((empty($request->input('company'))) ? $company_code : $request->input('company'));
        $filter_plant= empty($request->input('plant')) ? explode('-', $company_code."-".$plant) : explode('-', $request->input('plant'));
        // dd($filter_plant);
        $filtered = false;
        $data_aging=[];

        try {
            $data['list_company']=DB::connection('dbintranet')
                                    ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
            // $data['list_plant']=DB::connection('dbintranet')
            //                     ->select("SELECT * FROM INT_BUSINESS_PLANT WHERE COMPANY_CODE ='".$filter_company."' ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");
            $data['list_plant']=DB::connection('dbintranet')
                                ->select("SELECT * FROM INT_BUSINESS_PLANT ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");
            // $company = DB::connection('dbintranet')
            // ->table('dbo.INT_COMPANY')
            // ->select('COMPANY_NAME','COMPANY_CODE')
            // ->where('COMPANY_CODE',$filter_company)
            // ->get()->first();

            $plant = DB::connection('dbintranet')
            ->table('dbo.INT_BUSINESS_PLANT')
            ->select('COMPANY_CODE', 'SAP_PLANT_ID', 'SAP_PLANT_NAME')
            ->where('SAP_PLANT_ID', isset($filter_plant[1]) ? $filter_plant[1] : '')
            ->get()->first();
        } catch(\Exception $e){
            $data['list_company'] = [];
            $data['list_plant'] = [];
            $plant = (object)[];
        }

        if($date_to){
            $filtered = true;
            try{
                $inventory = array();
                $param = [
                    'P_COMPANY'=>isset($filter_plant[0]) ? $filter_plant[0] : '',
                    'P_PROFIT_CENTER'=>isset($filter_plant[1]) ? $filter_plant[1] : '',
                    'P_DATE_FROM'=>$date_from,
                    'P_DATE_TO'=>$date_to,
                    'P_CUSTOMER_FROM'=>'',
                    'P_CUSTOMER_TO'=>'',
                    'P_SUM' => ''
                ];

                $function = $rfc->getFunction('ZFM_MID_FI_AR_AGING');
                $result= $function->invoke($param, $options);
                $data_aging = isset($result['IT_DATA']) ? collect($result['IT_DATA']) : collect([]);
                // $data_aging = $data_aging->filter(function($item, $key){
                //     return isset($item['DC_CURRENCY']) && $item['DC_CURRENCY'] == 'IDR';
                // })->groupBy('CUSTOMER_NUMBER');
                $data_aging = $data_aging->groupBy('CUSTOMER_NUMBER');

                $data['TOTAL_ALL_AGING'] = [
                    'day_0_30'  => 0,
                    'day_31_60' => 0,
                    'day_61_90' => 0,
                    'day_GT_90' => 0,
                    'total_amount' => 0
                ];

                $data_order = 0;
                $data_excluded = $this->exluded_customer_id_temporary;
                $data_aging = $data_aging->mapWithKeys(function($item, $customer_id) use (&$data, &$data_order){
                    $day_0_30  = $item->sum('00_30_DAYS');
                    $day_31_60 = $item->sum('31_60_DAYS');
                    $day_61_90 = $item->sum('61_90_DAYS');
                    $day_GT_90 = $item->sum('GT_90_DAYS');
                    $total_amount = $item->sum('AMOUNT_LC');

                    $aging = [
                        'day_0_30'  => $day_0_30,
                        'day_31_60' => $day_31_60,
                        'day_61_90' => $day_61_90,
                        'day_GT_90' => $day_GT_90,
                        'total_amount' => $total_amount
                    ];

                    $detail_customer = [
                        'COMPANY_CODE' => isset($item[0]['COMPANY_CODE']) ? $item[0]['COMPANY_CODE'] : 'Unknown Company',
                        'ACCOUNT_NO'   => isset($item[0]['CUSTOMER_NUMBER']) ? $item[0]['CUSTOMER_NUMBER'] : 'Unknown Account Number',
                        'ACCOUNT_NAME' => isset($item[0]['CUSTOMER_NAME']) ? $item[0]['CUSTOMER_NAME'] : 'Unknown Account Name',
                    ];

                    $data['TOTAL_ALL_AGING']['day_0_30'] += $day_0_30;
                    $data['TOTAL_ALL_AGING']['day_31_60'] += $day_31_60;
                    $data['TOTAL_ALL_AGING']['day_61_90'] += $day_61_90;
                    $data['TOTAL_ALL_AGING']['day_GT_90'] += $day_GT_90;
                    $data['TOTAL_ALL_AGING']['total_amount'] += $total_amount;

                    $data_order++;
                    return [$customer_id=>['DETAILS'=>$detail_customer, 'AR_AGING'=>$aging, 'NUM_ORDER'=>$data_order]];
                })->reject(function($data, $key) use ($data_excluded){
                    return in_array($key, $data_excluded);
                })->toArray();
                // dd(collect($result['IT_DATA'])->groupBy('CUSTOMER_NUMBER')->toArray());
                // die;
            } catch(\Exception $e){
                Log::error('AR AGING VENDOR GENERAL EXCEPTION |'.$e->getMessage());
            } catch(SAPFunctionException $e){
                Log::error('AR AGING VENDOR SAP EXCEPTION |');
                Log::error($e);
            }
        } else {
            $date_to = date('Y-m-d');
        }

        // dd($data_aging);
        return DataTables::of($data_aging)
        ->make(true);
        
    }

    function report_ar_aging_vendor(Request $request) {
        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="KMS";
            $plant="KMS1";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";

        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
        }

        $date_from = date('Y0101', strtotime('-10 year'));
        if($request->get('date', false))
            $date_to = date('Ymd',strtotime($request->get('date', false)));
        else 
            $date_to = null;
        
        $filter_plant= empty($request->input('plant')) ? explode('-', $company_code."-".$plant) : explode('-', $request->input('plant'));
        $filtered = count($request->input());
        $data_aging=[];

        try {
            $data['list_company']=DB::connection('dbintranet')
                                    ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
            $data['list_plant']=DB::connection('dbintranet')
                                ->select("SELECT * FROM INT_BUSINESS_PLANT ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");

            $plant = DB::connection('dbintranet')
            ->table('dbo.INT_BUSINESS_PLANT')
            ->select('COMPANY_CODE', 'SAP_PLANT_ID', 'SAP_PLANT_NAME')
            ->where('SAP_PLANT_ID', isset($filter_plant[1]) ? $filter_plant[1] : '')
            ->get()->first();
        } catch(\Exception $e){
            $data['list_company'] = [];
            $data['list_plant'] = [];
            $plant = (object)[];
        }

        if(!$date_to){
            $date_to = date('Y-m-d');
        }

        $data['date_lookup'] = $date_to;
        return view('pages.sap.report-ar-aging.customer-summary', [
            'data_plant'=>$plant,
            'data_aging'=>$data_aging,
            'data' =>$data,
            'filter_plant'=>$filter_plant,
            'filtered'=>$filtered
        ]);


    }

    function ar_aging(Request $request) {
        $data=array();

        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc'));

        $options = [
            'rtrim'=>true,
        ];

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="KMS";
            $plant="KMS1";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";

        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
        }

        $filter_company=strtoupper((empty($request->input('company')))? $company_code : $request->input('company'));

        //filter kalau KMS supaya ngambil KM01 defaultnya
        // $default_plant = (empty($request->input('plant')) && $filter_company=="KMS")? 'KMS1' : $plant;
        // $filter_plant=strtoupper((empty($request->input('plant')))? $default_plant : $request->input('plant'));
        $filter_plant= empty($request->input('plant')) ? explode('-', $company_code."-".$plant) : explode('-', $request->input('plant'));
        $filter_currency=strtoupper((empty($request->input('currency')))? NULL : $request->input('currency'));




        // $data['list_company']=DB::connection('dbintranet')
        //                         ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
        // $data['list_plant']=DB::connection('dbintranet')
        //                     ->select("SELECT * FROM INT_BUSINESS_PLANT WHERE COMPANY_CODE ='".$filter_company."' ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");
        $data['list_company']=DB::connection('dbintranet')
                                    ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
        $data['list_plant']=DB::connection('dbintranet')
                            ->select("SELECT * FROM INT_BUSINESS_PLANT ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");

        $currency=['IDR','USD','CNY'];
        $data['currency']=$currency;


        $customer_number=(!empty($request->get('customer_number'))) ? $request->get('customer_number') : '';
        $year=(!empty($request->get('year'))) ? $request->get('year') : date('Y');

        $data_customer=[];
        $data_aging=[];
        $grouped_aging=[];

        if(!empty($customer_number) && !in_array($customer_number, $this->exluded_customer_id_temporary)){
            try{
                $param = [
                    // 'P_COMPANY'=>$filter_company,
                    'P_COMPANY'=>isset($filter_plant[0]) ? $filter_plant[0] : '',
                    'P_CUSTOMER'=>''.$customer_number.'',
                ];

                $function = $rfc->getFunction('ZFM_MD_CUSTOMER');
                $result= $function->invoke($param, $options);
                $data_customer = $result['IT_CUSTOMER'];
            }catch(SapException $e){
                Log::error('AR AGING VENDOR SAP EXCEPTION |');
                Log::error($e);
            }

            try{
                // Perlu DATE_TO DAN DATE_FROM karena RFCnya udh diubah mas endra
                $date_from = date('Y0101', strtotime('-10 year'));
                $date_to = date('Ymd');

                $inventory = array();
                // $param = [
                //     'P_COMPANY'=>$filter_company,
                //     // 'P_YEAR'=>''.$year.'',
                //     'P_DATE_FROM'=>$date_from,
                //     'P_DATE_TO'=>$date_to,
                //     'P_CUSTOMER_FROM'=>''.$customer_number.'',
                //     'P_CUSTOMER_TO'=>''
                // ];
                $param = [
                    'P_COMPANY'=>isset($filter_plant[0]) ? $filter_plant[0] : '',
                    'P_PROFIT_CENTER'=>isset($filter_plant[1]) ? $filter_plant[1] : '',
                    'P_DATE_FROM'=>$date_from,
                    'P_DATE_TO'=>$date_to,
                    'P_CUSTOMER_FROM'=>''.$customer_number.'',
                    'P_CUSTOMER_TO'=>'',
                ];

                $function = $rfc->getFunction('ZFM_MID_FI_AR_AGING');
                $result= $function->invoke($param, $options);
                $data_aging = $result['IT_DATA'];
                // echo json_encode($data_aging);
                // die;

                $collect_aging=collect($data_aging);

                if(!empty($filter_currency)){
                    $collect_aging= collect($data_aging)->filter(function ($value,$key) use($filter_currency){
                        return $value['DC_CURRENCY']==$filter_currency;
                    });
                }

                $grouped_aging = $collect_aging->groupBy('ACCOUNTING_DOCUMENT_NUMBER')->transform(function($item, $k) {
                    $total_amount=$day_0_30=$day_31_60=$day_61_90=$day_91=0;
                    $item->each(function ($item2, $key2) use (&$total_amount, &$day_0_30, &$day_31_60, &$day_61_90, &$day_91) {
                        $total_amount+=$item2['AMOUNT_DC'];
                        $day_0_30+=$item2['00_30_DAYS'];
                        $day_31_60+=$item2['31_60_DAYS'];
                        $day_61_90+=$item2['61_90_DAYS'];
                        $day_91+=$item2['GT_90_DAYS'];
                    });
                    $return = $item[0];
                    $return['TOTAL_AMOUNT']=$total_amount;
                    $return['TOTAL_0_30']=$day_0_30;
                    $return['TOTAL_31_60']=$day_31_60;
                    $return['TOTAL_61_90']=$day_61_90;
                    $return['TOTAL_91']=$day_91;

                    return $return;
                });


            } catch(\Exception $e){
                Log::error('AR AGING VENDOR GENERAL EXCEPTION |'.$e->getMessage());
            } catch(SapException $e){
                // dd($e);
                Log::error('AR AGING VENDOR SAP EXCEPTION |');
                Log::error($e);
            }
        }


        // $company=DB::connection('dbintranet')
        // ->table('dbo.INT_COMPANY')
        // ->select('COMPANY_NAME','COMPANY_CODE')
        // ->where('COMPANY_CODE',$filter_company)
        // ->get()->first();
        $plant = DB::connection('dbintranet')
        ->table('dbo.INT_BUSINESS_PLANT')
        ->select('COMPANY_CODE', 'SAP_PLANT_ID', 'SAP_PLANT_NAME')
        ->where('SAP_PLANT_ID', isset($filter_plant[1]) ? $filter_plant[1] : '')
        ->get()->first();

        return view('pages.sap.report-ar-aging.index', [
            // 'data_company'=>$company,
            'data_plant'=>$plant,
            'data_aging'=>$grouped_aging,
            'data_customer'=>$data_customer,
            'data' =>$data,
            'filter_company'=>$filter_company,
            'filter_plant'=> $filter_plant,
            'filter_customer' => $customer_number,
            'filter_currency'=>$filter_currency
        ]);
    }

    function report_ap_aging_vendor_getData(Request $request){
        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc'));

        $options = [
            'rtrim'=>true,
        ];

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="KMS";
            $plant="KMS1";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";

        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
        }

        $date_from = date('Y0101', strtotime('-1 year'));
        // dd($date_from);
        if($request->get('date', false))
            $date_to = date('Ymd', strtotime($request->get('date', false)) );
        else 
            $date_to = null;
        // $filter_company=strtoupper((empty($request->input('company'))) ? $company_code : $request->input('company'));
        $filter_plant= empty($request->input('plant')) ? explode('-', $company_code."-".$plant) : explode('-', $request->input('plant'));
        // dd($filter_plant);
        $filtered = false;
        $data_aging = [];
        $temp_aging = collect([]);
        $date_populate = [];

        if($date_to){
            try{
                $begin = new \DateTime($date_from);
                $end = new \DateTime($date_to);

                $interval = \DateInterval::createFromDateString('1 day');
                $period = new \DatePeriod($begin, $interval, $end, 2);
                $loop = 1;
                $temporary_date_start = '';
                $temporary_date_end = '';
                $store_current_date = '';
                foreach ($period as $index => $dt) {
                    // echo $dt->format("Ymd")."\n";
                    if($loop % 2 > 0){
                        $store_current_date = $dt->format("Ymd");
                        // if(strtotime($store_current_date) == strtotime($date_to)){
                        //     dd($store_current_date, $date_to);
                        // }
                    }

                    else if($loop % 2 == 0){
                        // echo "Execute here \n";
                        $temporary_date_start = $store_current_date;
                        $temporary_date_end = $dt->format("Ymd");
                        $param = [
                            'P_COMPANY'=>isset($filter_plant[0]) ? $filter_plant[0] : '',
                            'P_PROFIT_CENTER'=>isset($filter_plant[1]) ? $filter_plant[1] : '',
                            'P_DATE_FROM'=>$temporary_date_start,
                            'P_DATE_TO'=>$temporary_date_end,
                            'P_VENDOR_FROM'=>'',
                            'P_VENDOR_TO'=>'',
                            'P_SUM' => ''
                        ];
                        
                        $function = $rfc->getFunction('ZFM_MID_FI_AP_AGING');
                        $result= $function->invoke($param, $options);
                        if(isset($result['IT_DATA']) && count($result['IT_DATA'])){
                            $merge = $temp_aging->concat($result['IT_DATA'])->toArray();
                            $data_aging = $data_aging + array_merge($merge, $data_aging);
                        }
                        $date_populate[] = [
                            $temporary_date_start,
                            $temporary_date_end
                        ];
                    }

                    $loop ++;
                }
                dd($date_populate);
                $data_aging = count($data_aging) > 0 ? collect($data_aging) : collect([]);
                // $data_aging = $data_aging->filter(function($item, $key){
                //     return isset($item['DC_CURRENCY']) && $item['DC_CURRENCY'] == 'IDR';
                // })->groupBy('VENDOR_CODE');
                $data_aging = $data_aging->groupBy('VENDOR_CODE');
                // dd($data_aging);
                $data['TOTAL_ALL_AGING'] = [
                    'day_0_30'  => 0,
                    'day_31_60' => 0,
                    'day_61_90' => 0,
                    'day_GT_90' => 0,
                    'total_amount' => 0
                ];

                $data_order = 0;
                $data_excluded = $this->exluded_customer_id_temporary;
                $data_aging = $data_aging->mapWithKeys(function($item, $customer_id) use (&$data, &$data_order){
                    $day_0_30  = $item->sum('00_30_DAYS');
                    $day_31_60 = $item->sum('31_60_DAYS');
                    $day_61_90 = $item->sum('61_90_DAYS');
                    $day_GT_90 = $item->sum('GT_90_DAYS');
                    $total_amount = $item->sum('AMOUNT_LC');

                    $aging = [
                        'day_0_30'  => $day_0_30,
                        'day_31_60' => $day_31_60,
                        'day_61_90' => $day_61_90,
                        'day_GT_90' => $day_GT_90,
                        'total_amount' => $total_amount
                    ];

                    $detail_customer = [
                        'COMPANY_CODE' => isset($item[0]['COMPANY_CODE']) ? $item[0]['COMPANY_CODE'] : 'Unknown Company',
                        'ACCOUNT_NO'   => isset($item[0]['VENDOR_CODE']) ? $item[0]['VENDOR_CODE'] : 'Unknown Account Number',
                        'ACCOUNT_NAME' => isset($item[0]['VENDOR_NAME']) ? $item[0]['VENDOR_NAME'] : 'Unknown Account Name',
                    ];

                    $data['TOTAL_ALL_AGING']['day_0_30'] += $day_0_30;
                    $data['TOTAL_ALL_AGING']['day_31_60'] += $day_31_60;
                    $data['TOTAL_ALL_AGING']['day_61_90'] += $day_61_90;
                    $data['TOTAL_ALL_AGING']['day_GT_90'] += $day_GT_90;
                    $data['TOTAL_ALL_AGING']['total_amount'] += $total_amount;

                    $data_order++;
                    return [$customer_id=>['DETAILS'=>$detail_customer, 'AR_AGING'=>$aging, 'NUM_ORDER'=>$data_order]];
                })->reject(function($data, $key) use ($data_excluded){
                    return in_array($key, $data_excluded);
                })->toArray();
                // dd(collect($result['IT_DATA'])->groupBy('CUSTOMER_NUMBER')->toArray());
                // die;
            } catch(\Exception $e){
                // dd($e);
                Log::error('AP AGING VENDOR GENERAL EXCEPTION |'.$e->getMessage());
            } catch(SAPFunctionException $e){
                // dd($e);
                Log::error('AP AGING VENDOR SAP EXCEPTION |');
                Log::error($e);
            }
        } else {
            $date_to = date('Y-m-d');
        }

        // dd($data_aging);
        return DataTables::of($data_aging)
        ->make(true);
        
    }

    function report_ap_aging_vendor(Request $request) {
        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="KMS";
            $plant="KMS1";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";

        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
        }

        $date_from = date('Y0101', strtotime('-10 year'));
        // dd($date_from);
        if($request->get('date', false))
            $date_to = date('Ymd',strtotime($request->get('date', false)));
        else 
            $date_to = null;
        // $filter_company=strtoupper((empty($request->input('company'))) ? $company_code : $request->input('company'));
        $filter_plant= empty($request->input('plant')) ? explode('-', $company_code."-".$plant) : explode('-', $request->input('plant'));
        // dd($filter_plant);
        $filtered = count($request->input());
        $data_aging=[];

        try {
            $data['list_company']=DB::connection('dbintranet')
                                    ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
            $data['list_plant']=DB::connection('dbintranet')
                                ->select("SELECT * FROM INT_BUSINESS_PLANT ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");

            $plant = DB::connection('dbintranet')
            ->table('dbo.INT_BUSINESS_PLANT')
            ->select('COMPANY_CODE', 'SAP_PLANT_ID', 'SAP_PLANT_NAME')
            ->where('SAP_PLANT_ID', isset($filter_plant[1]) ? $filter_plant[1] : '')
            ->get()->first();
        } catch(\Exception $e){
            $data['list_company'] = [];
            $data['list_plant'] = [];
            $plant = (object)[];
        }

        if(!$date_to){
            $date_to = date('Y-m-d');
        }

        $data['date_lookup'] = $date_to;
        return view('pages.sap.report-ap-aging.customer-summary', [
            'data_plant'=>$plant,
            'data_aging'=>$data_aging,
            'data' =>$data,
            'filter_plant'=>$filter_plant,
            'filtered'=>$filtered
        ]);
    }

    function ap_aging(Request $request) {
        $data=array();

        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc'));

        $options = [
            'rtrim'=>true,
        ];

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="KMS";
            $plant="KMS1";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";

        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
        }

        $filter_company=strtoupper((empty($request->input('company')))? $company_code : $request->input('company'));

        //filter kalau KMS supaya ngambil KM01 defaultnya
        $default_plant = (empty($request->input('plant')) && $filter_company=="KMS")? 'KMS1' : $plant;

        // $filter_plant=strtoupper((empty($request->input('plant')))? $default_plant : $request->input('plant'));
        $filter_plant= empty($request->input('plant')) ? explode('-', $company_code."-".$plant) : explode('-', $request->input('plant'));
        $filter_currency=strtoupper((empty($request->input('currency')))? NULL : $request->input('currency'));




        // $data['list_company']=DB::connection('dbintranet')
        //                         ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
        // $data['list_plant']=DB::connection('dbintranet')
        //                     ->select("SELECT * FROM INT_BUSINESS_PLANT WHERE COMPANY_CODE ='".$filter_company."' ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");
        $data['list_company']=DB::connection('dbintranet')
                                    ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
        $data['list_plant']=DB::connection('dbintranet')
                            ->select("SELECT * FROM INT_BUSINESS_PLANT ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");


        $currency=['IDR','USD','CNY'];
        $data['currency']=$currency;


        $customer_number=(!empty($request->get('customer_number'))) ? $request->get('customer_number') : '';

        $year=(!empty($request->get('year'))) ? $request->get('year') : date('Y');

        $data_customer=[];
        $data_aging=[];
        $grouped_aging=[];

        if(!empty($customer_number) && !in_array($customer_number, $this->exluded_customer_id_temporary)){
            try{
                $param = [
                    // 'P_COMPANY'=>$filter_company,
                    'P_COMPANY'=>isset($filter_plant[0]) ? $filter_plant[0] : '',
                    'P_CUSTOMER'=>''.$customer_number.'',

                ];

                $function = $rfc->getFunction('ZFM_MD_CUSTOMER');
                $result= $function->invoke($param, $options);
                $data_customer = $result['IT_CUSTOMER'];
            }catch(SapException $e){
                // dd($e);
                Log::error('AP AGING VENDOR SAP EXCEPTION |');
                Log::error($e);
            }

            try{
                // Perlu DATE_TO DAN DATE_FROM karena RFCnya udh diubah mas endra
                $date_from = date('Y0101', strtotime('-1 year'));
                $date_to = date('Ymd');
                $inventory = array();
                // $param = [
                //     'P_COMPANY'=>$filter_company,
                //     'P_VENDOR_FROM'=>''.$customer_number.'',
                //     'P_VENDOR_TO'=>''
                // ];
                $param = [
                    'P_COMPANY'=>isset($filter_plant[0]) ? $filter_plant[0] : '',
                    'P_PROFIT_CENTER'=>isset($filter_plant[1]) ? $filter_plant[1] : '',
                    'P_DATE_FROM'=>$date_from,
                    'P_DATE_TO'=>$date_to,
                    'P_VENDOR_FROM'=>''.$customer_number.'',
                    'P_VENDOR_TO'=>'',
                ];

                $function = $rfc->getFunction('ZFM_MID_FI_AP_AGING');
                $result= $function->invoke($param, $options);
                $data_aging = $result['IT_DATA'];
                // echo json_encode($data_aging);
                // die;

                $collect_aging=collect($data_aging);

                if(!empty($filter_currency)){
                    $collect_aging= collect($data_aging)->filter(function ($value,$key) use($filter_currency){
                        return $value['DC_CURRENCY']==$filter_currency;
                    });
                }

                $grouped_aging = $collect_aging->groupBy('ACCOUNTING_DOCUMENT_NUMBER')->transform(function($item, $k) {
                    $total_amount=$day_0_30=$day_31_60=$day_61_90=$day_91=0;
                    $item->each(function ($item2, $key2) use (&$total_amount, &$day_0_30, &$day_31_60, &$day_61_90, &$day_91) {
                        $total_amount+=$item2['AMOUNT_DC'];
                        $day_0_30+=$item2['00_30_DAYS'];
                        $day_31_60+=$item2['31_60_DAYS'];
                        $day_61_90+=$item2['61_90_DAYS'];
                        $day_91+=$item2['GT_90_DAYS'];
                    });
                    $return = $item[0];
                    $return['TOTAL_AMOUNT']=$total_amount;
                    $return['TOTAL_0_30']=$day_0_30;
                    $return['TOTAL_31_60']=$day_31_60;
                    $return['TOTAL_61_90']=$day_61_90;
                    $return['TOTAL_91']=$day_91;

                    return $return;
                });


            }catch(SapException $e){
                // dd($e);
                Log::error('AP AGING VENDOR SAP EXCEPTION |');
                Log::error($e);
            }
        }


        // $company=DB::connection('dbintranet')
        // ->table('dbo.INT_COMPANY')
        // ->select('COMPANY_NAME','COMPANY_CODE')
        // ->where('COMPANY_CODE',$filter_company)
        // ->get()->first();
        $plant = DB::connection('dbintranet')
        ->table('dbo.INT_BUSINESS_PLANT')
        ->select('COMPANY_CODE', 'SAP_PLANT_ID', 'SAP_PLANT_NAME')
        ->where('SAP_PLANT_ID', isset($filter_plant[1]) ? $filter_plant[1] : '')
        ->get()->first();

        return view('pages.sap.report-ap-aging.index', [
            // 'data_company'=>$company,
            'data_plant'=>$plant,
            'data_aging'=>$grouped_aging,
            'data_customer'=>$data_customer,
            'data' =>$data,
            'filter_company'=>$filter_company,
            'filter_plant'=> $filter_plant,
            'filter_customer' => $customer_number,
            'filter_currency'=>$filter_currency
        ]);
    }

    public function search_customer(Request $request){

        //init RFC
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc'));
        }
        $options = [
            'rtrim'=>true,
        ];
        //===

        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        $keyword=strtoupper($request->input('keywordParameter'));
        $company=explode('-', $request->input('company'));

        $param = array(
            'P_COMPANY'=>isset($company[0]) ? $company[0] : '',
            'P_CUSTOMER'=>''.$keyword.''
        );

        $function_type = $rfc->getFunction('ZFM_MD_CUSTOMER');
        $list_vendor= $function_type->invoke($param, $options);

        // Exclude customer dummy manually
        if(in_array($keyword, $this->exluded_customer_id_temporary))
            $collect_customer = collect([]);
        else
            $collect_customer=collect($list_vendor['IT_CUSTOMER'])->groupBy('CUSTOMER_CODE');

        $data=array(
            'employee_id'=>$employee_id,
            'employee_name'=>$employee_name,
            'form_code'=>$this->form_number,
            'list_vendor'=>$collect_customer
        );

        return view('pages.sap.report-ar-aging.search_customer', ['data' => $data]);
    }

}



