<?php

namespace App\Http\Controllers\SAP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDF;
use Log;
Use Cookie;
use DataTables;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use App\Http\Controllers\Traits\IntranetTrait;

class RealEstate extends Controller{
    use IntranetTrait;

    function cmp($array_check=array()) {
        $payment_level = ['pending','deny','cancel','failure','capture','settlement'];
        $latest_status = [];
        foreach($array_check as $payment_type){
            if(in_array($payment_type, $payment_level))
                $latest_status[array_search($payment_type, $payment_level)] = $payment_type;
        }
        try {
            return [$latest_status[max(array_keys($latest_status))]];
        }catch(\Exception $e) { return []; };
    }

    // Testing only
    public function refx_revenue(Request $request, $company='WKK') {
        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc_dev'));

        $options = [
            'rtrim'=>true,
        ];

        try{
            $param = [
                'P_COMPANY'=>$company,
            ];

            $function = $rfc->getFunction('ZFM_MID_REFX_AMORTISATION');
            $result= $function->invoke($param, $options);
            if(isset($result['IT_DATA']))
                dd($result['IT_DATA']);
            else
                dd($result);
        }catch(SapException $e){
            dd($e);
        }
    }

    public static function check_invoice_status_blade(Request $request) : string
    {
        $no_invoice = '';
        $plant_code = ''; 
        $client='310';
        if($request->get('no_invoice'))
            $no_invoice = $request->get('no_invoice');
        if($request->get('plant_code'))
            $plant_code = $request->get('plant_code');
        if($request->get('client'))
            $client = $request->get('client_code');


        $rs_instance = new RealEstate();
        $is_production = config('payment.is_production');
        $username=config('payment.username');
        $password=config('payment.password');
        $cek_invoice_status = collect([]);
        if($is_production){
            $cek_invoice_status = DB::connection('dbintranet')
            ->table('INT_MAP_PAYMENT_INVOICE_PROD')
            ->where('CAANO', $no_invoice)
            ->get();
        }
        else {
            if($client=='110' || $client=='120'){
                $username=config('payment.username_qas');
                $password=config('payment.password_qas');
                $cek_invoice_status = DB::connection('dbintranet')
                ->table('INT_MAP_PAYMENT_INVOICE_QAS')
                ->where('CAANO', $no_invoice)
                ->get();
            } else {
                $cek_invoice_status = DB::connection('dbintranet')
                ->table('INT_MAP_PAYMENT_INVOICE')
                ->where('CAANO', $no_invoice)
                ->get();
            }
        }

        $order_id = $cek_invoice_status->groupBy('CAANO')->toArray();
        $any_payment = [];
        if($order_id){
            switch ($plant_code) {
                case 'kms1' : case 'kms':
                    if(!$is_production)
                        $server_key = config('payment.serverkey.sandbox.kms1');
                    else
                        $server_key = config('payment.serverkey.prod.kms1');
                break;
                case 'njp1': case 'njp':
                    if(!$is_production)
                        $server_key = config('payment.serverkey.sandbox.njp1');
                    else
                        $server_key = config('payment.serverkey.prod.njp1');
                    break;
                default:
                    $server_key = null;
                    break;
            }

            if(!$server_key){
                return 'N/A';
            }

            foreach ($order_id as $key => $value) {
                // Cek status dengan sistem pembayaran selain CC
                $payload = json_encode( array( "Caano" => $key ) );
                $param=urlencode($payload);
                $OTH_PAYMENT = config('payment.sap_url.cc_notif').''.$param;
                if(!$is_production){
                    if($client=='110')
                        $OTH_PAYMENT = config('payment.sap_url.cc_notif_qas').''.$param;

                }
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
                curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                $server_output = curl_exec($ch);
                curl_close ($ch);

                $post=json_decode($server_output);
                if(isset($post->Statuscode) && $post->Statuscode == '200')
                    array_push($any_payment, strtolower($post->Transactionstatus));

                // Cek Pembayaran CC dengan midtrans API
                foreach ($value as $obj) {
                    $CC_PAYMENT = 'https://api.midtrans.com/v2/'.$obj->ORDER_ID.'/status';
                    if(!$is_production){
                        $CC_PAYMENT = 'https://api.sandbox.midtrans.com/v2/'.$obj->ORDER_ID.'/status';

                    }
                    $token = base64_encode($server_key);
                    $headers = array(
                       "Authorization: Basic ${token}",
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );
                    curl_setopt($ch, CURLOPT_URL,$CC_PAYMENT);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $server_output = curl_exec($ch);
                    curl_close ($ch);
                    $post=json_decode($server_output);
                    if(isset($post->status_code) && $post->status_code == '200')
                        array_push($any_payment, strtolower($post->transaction_status));
                }
            }
        }
        else {
            // Cek status dengan sistem pembayaran selain CC
            $payload = json_encode( array( "Caano" => $no_invoice ) );
            $param=urlencode($payload);
            $OTH_PAYMENT = config('payment.sap_url.cc_notif').''.$param;
            if(!$is_production){
                if($client=='110')
                    $OTH_PAYMENT = config('payment.sap_url.cc_notif_qas').''.$param;

            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            $server_output = curl_exec($ch);
            curl_close ($ch);

            $post=json_decode($server_output);
            if(isset($post->Statuscode) && $post->Statuscode == '200')
                array_push($any_payment, strtolower($post->Transactionstatus));
        }

        $any_payment = $rs_instance->cmp(array_unique($any_payment));
        $status_payment = 'N/A';
        foreach($any_payment as $any_payment){
            switch ($any_payment) {
                case 'pending':
                    $status_payment = '<span class="text-white p-1 px-2 bg-warning rounded"><b>PENDING</b></span>';
                    break;
                case 'capture':
                    $status_payment = '<span class="text-white p-1 px-2 bg-info rounded"><b>CAPTURED</b></span>';
                    break;
                case 'deny':
                    $status_payment = '<span class="text-white p-1 px-2 bg-danger rounded"><b>DENIED</b></span>';
                    break;
                case 'settlement': case 'settle':
                    $status_payment = '<span class="text-white p-1 px-2 bg-success rounded"><b>SETTLED</b></span>';
                    break;
                case 'cancel':
                    $status_payment = 'CANCELLED';
                    break;
                default:
                    break;
            }
        }
        return $status_payment;
    }

    public static function check_invoice_status($no_invoice, $plant_code, $client='310') : string
    {
        $no_invoice=$no_invoice;
        $plant_code=$plant_code; 
        $client=$client;

        $rs_instance = new RealEstate();
        $is_production = config('payment.is_production');
        $username=config('payment.username');
        $password=config('payment.password');
        $cek_invoice_status = collect([]);
        if($is_production){
            $cek_invoice_status = DB::connection('dbintranet')
            ->table('INT_MAP_PAYMENT_INVOICE_PROD')
            ->where('CAANO', $no_invoice)
            ->get();
        }
        else {
            if($client=='110' || $client=='120'){
                $username=config('payment.username_qas');
                $password=config('payment.password_qas');
                $cek_invoice_status = DB::connection('dbintranet')
                ->table('INT_MAP_PAYMENT_INVOICE_QAS')
                ->where('CAANO', $no_invoice)
                ->get();
            } else {
                $cek_invoice_status = DB::connection('dbintranet')
                ->table('INT_MAP_PAYMENT_INVOICE')
                ->where('CAANO', $no_invoice)
                ->get();
            }
        }

        $order_id = $cek_invoice_status->groupBy('CAANO')->toArray();
        $any_payment = [];
        if($order_id){
            switch ($plant_code) {
                case 'kms1' : case 'kms':
                    if(!$is_production)
                        $server_key = config('payment.serverkey.sandbox.kms1');
                    else
                        $server_key = config('payment.serverkey.prod.kms1');
                break;
                case 'njp1': case 'njp':
                    if(!$is_production)
                        $server_key = config('payment.serverkey.sandbox.njp1');
                    else
                        $server_key = config('payment.serverkey.prod.njp1');
                    break;
                default:
                    $server_key = null;
                    break;
            }

            if(!$server_key){
                return 'N/A';
            }

            foreach ($order_id as $key => $value) {
                // Cek status dengan sistem pembayaran selain CC
                $payload = json_encode( array( "Caano" => $key ) );
                $param=urlencode($payload);
                $OTH_PAYMENT = config('payment.sap_url.cc_notif').''.$param;
                if(!$is_production){
                    if($client=='110')
                        $OTH_PAYMENT = config('payment.sap_url.cc_notif_qas').''.$param;

                }
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
                curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
                $server_output = curl_exec($ch);
                curl_close ($ch);

                $post=json_decode($server_output);
                if(isset($post->Statuscode) && $post->Statuscode == '200')
                    array_push($any_payment, strtolower($post->Transactionstatus));

                // Cek Pembayaran CC dengan midtrans API
                foreach ($value as $obj) {
                    $CC_PAYMENT = 'https://api.midtrans.com/v2/'.$obj->ORDER_ID.'/status';
                    if(!$is_production){
                        $CC_PAYMENT = 'https://api.sandbox.midtrans.com/v2/'.$obj->ORDER_ID.'/status';

                    }
                    $token = base64_encode($server_key);
                    $headers = array(
                       "Authorization: Basic ${token}",
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );
                    curl_setopt($ch, CURLOPT_URL,$CC_PAYMENT);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    $server_output = curl_exec($ch);
                    curl_close ($ch);
                    $post=json_decode($server_output);
                    if(isset($post->status_code) && $post->status_code == '200')
                        array_push($any_payment, strtolower($post->transaction_status));
                }
            }
        }
        else {
            // Cek status dengan sistem pembayaran selain CC
            $payload = json_encode( array( "Caano" => $no_invoice ) );
            $param=urlencode($payload);
            $OTH_PAYMENT = config('payment.sap_url.cc_notif').''.$param;
            if(!$is_production){
                if($client=='110')
                    $OTH_PAYMENT = config('payment.sap_url.cc_notif_qas').''.$param;

            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            $server_output = curl_exec($ch);
            curl_close ($ch);

            $post=json_decode($server_output);
            if(isset($post->Statuscode) && $post->Statuscode == '200')
                array_push($any_payment, strtolower($post->Transactionstatus));
        }

        $any_payment = $rs_instance->cmp(array_unique($any_payment));
        $status_payment = 'N/A';
        foreach($any_payment as $any_payment){
            switch ($any_payment) {
                case 'pending':
                    $status_payment = '<span class="text-white p-1 px-2 bg-warning rounded"><b>PENDING</b></span>';
                    break;
                case 'capture':
                    $status_payment = '<span class="text-white p-1 px-2 bg-info rounded"><b>CAPTURED</b></span>';
                    break;
                case 'deny':
                    $status_payment = '<span class="text-white p-1 px-2 bg-danger rounded"><b>DENIED</b></span>';
                    break;
                case 'settlement': case 'settle':
                    $status_payment = '<span class="text-white p-1 px-2 bg-success rounded"><b>SETTLED</b></span>';
                    break;
                case 'cancel':
                    $status_payment = 'CANCELLED';
                    break;
                default:
                    break;
            }
        }
        return $status_payment;
    }

    function check_invoice_has_detail($param, $client='310'){
        $username=config('payment.username');
        $password=config('payment.password');

        $no_invoice = $param;
        $payload = json_encode( array( "CAANO" => $param ) );
        $param=urlencode($payload);
        $url=config('payment.sap_url.invoice_detail')."".$param;

        if($client=='110' || $client=='120'){
            $username=config('payment.username_qas');
            $password=config('payment.password_qas');
            if($client == '120')
                $url=config('payment.sap_url.invoice_detail_qas').''.$param;
            else 
                $url=config('payment.sap_url.invoice_detail_pre_live').''.$param;
        }
           
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        return $post;
    }

    function rental_object_list(Request $request){
        // $date_start=(!empty($request->get('date_start')))? date('Ymd',strtotime($request->get('date_start'))) : '';
        // $date_end=(!empty($request->get('date_end')))? date('Ymd',strtotime($request->get('date_end'))) : '';
        $date_start='';
        $date_end='';
        $data['date_start'] = $date_start;
        $data['date_end'] = $date_end;

        $is_production = config('intranet.is_production');
        if($is_production)
            $connection = new SapConnection(config('intranet.rfc_prod'));
        else
            $connection = new SapConnection(config('intranet.rfc_dev'));

        $options = [
            'rtrim'=>true,
        ];

        // RENTAL OBJECT
        // FILTER LIST [
        //  'P_RENT_OBJ'=>'KODE OBJECT RENTAL',
        //  'P_CONTRACT_NUM'=>'NOMOR KONTRAK',
        //  'P_COMPANY'=>'COMPANY CODE',
        //  'P_BUSINES_ENTITY_NUM'=>'PLANT CODE'
        // ]
         
        $company_list = [];
        $plant_list = [];
        try{
            $company_list = DB::connection('dbintranet')
            ->table('INT_COMPANY')
            ->select('COMPANY_CODE', 'COMPANY_NAME')
            ->get()->pluck('COMPANY_NAME', 'COMPANY_CODE')->toArray();

            $plant_list = DB::connection('dbintranet')
            ->table('INT_BUSINESS_PLANT')
            ->select('COMPANY_CODE', 'SAP_PLANT_ID', 'SAP_PLANT_NAME')
            ->get()->pluck('SAP_PLANT_NAME','SAP_PLANT_ID')->toArray();

        } catch(\Exception $e){
            Log::error("ERROR POPULATING COMPANY AND PLANT : ".$e->getMessage());
        }

        $data['COMPANY'] = $company_list;
        $data['PLANT'] = $plant_list;

        $filter_list = [
            'P_RENT_OBJ' => "",
            'P_CONTRACT_NUM' => "",
            'P_COMPANY' => "NJP",
            'P_BUSINES_ENTITY_NUM' => "",
        ];

        if($date_start && $date_end){
            $filter_list["P_START_DATE"] = $date_start;
            $filter_list["P_END_DATE"] = $date_end;
        }
        else {
            $data['date_start'] = date('Y-m-d');
            $data['date_end'] = date('Y-m-d');
        }

        try{
            if($request->get('company_code')){
                $filter_list['P_COMPANY'] = $request->get('company_code');
            }
        } catch(\Exception $e){}

        // $data['RENTAL_OBJECT'] = [];
        $data['RENTAL_OBJECT_SUMMARY'] = [];
        $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL'] = [];
        $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT'] = [];
        $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED'] = [];
        $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'] = [];
        
        $data['FILTER'] = $filter_list;

        try {
            $function = $connection->getFunction('ZFM_MM_IM_RE_FX_REPORT_V2');
            $result= $function->invoke($filter_list, $options);
            $measurement_data = isset($result['GT_MEASUREMENT_RENTAL']) ? collect($result['GT_MEASUREMENT_RENTAL'])->groupBy(['OBJECT_ID','OBJECT_TYPE']) : [];
            // $measurement_contract = isset($result['GT_MEASUREMENT']) ? collect($result['GT_MEASUREMENT'])->groupBy(['OBJECT_ID','OBJECT_TYPE']) : [];

            if(count(array_keys($result)) > 0 && in_array('GT_REPORT', array_keys($result))){
                $data['RENTAL_OBJECT_DETAIL'] = collect($result['GT_REPORT'])->groupBy('BUSINESSENTITYNUMBER')->mapWithKeys(function($item, $keyPlant) use ($measurement_data, $data){
                    $sort_data = $item->sort(function($a, $b){
                        return (strlen($a['CONTRACTNUMBER']) < strlen($b['CONTRACTNUMBER'])) ? 1 : -1;
                    })->groupBy(['SAPRENTALOBJECTID','OBJECT_ID','CONDITIONTYPE'])->mapWithKeys(function($itemContract, $key){
                        $itemNew = collect($itemContract)->reject(function($data, $key){
                            return empty($key);
                        });
                        return [$key=>$itemNew];
                    })->mapWithKeys(function($item, $key) use ($measurement_data, $keyPlant, $data){
                        $data_customer = [];
                        $data_rental = [];
                        $count_contract = count($item);

                        if($count_contract > 0) {
                            // AMBIL SATU DATA CUSTOMER DARI KONTRAK
                            $cek_customer = collect($item)->map(function($itemCondition, $keyCondition) use (&$data_customer, &$data_rental, $measurement_data, $key){
                                $count_condition = count($itemCondition);
                                if($count_condition > 0) {
                                    // Detail Customer
                                    $data_customer['SAPCUSTOMERNAME'] = isset($itemCondition->first()[0]['SAPCUSTOMERNAME']) && !empty($itemCondition->first()[0]['SAPCUSTOMERNAME']) ? $itemCondition->first()[0]['SAPCUSTOMERNAME'] : '';

                                    $data_customer['SAPCUSTOMERID'] = isset($itemCondition->first()[0]['SAPCUSTOMERID']) && !empty($itemCondition->first()[0]['SAPCUSTOMERID']) ? $itemCondition->first()[0]['SAPCUSTOMERID'] : '';
                                    $data_customer['SAPBILLTOID'] = isset($itemCondition->first()[0]['SAPBILLTOID']) && !empty($itemCondition->first()[0]['SAPBILLTOID']) ? $itemCondition->first()[0]['SAPBILLTOID'] : '';
                                    $data_customer['SAPBILLTONAME'] = isset($itemCondition->first()[0]['SAPBILLTONAME']) && !empty($itemCondition->first()[0]['SAPBILLTONAME']) ? $itemCondition->first()[0]['SAPBILLTONAME'] : '';
                                    $data_customer['SAPBILLTONAME'] = isset($itemCondition->first()[0]['SAPBILLTONAME']) && !empty($itemCondition->first()[0]['SAPBILLTONAME']) ? $itemCondition->first()[0]['SAPBILLTONAME'] : '';
                                    
                                    $data_customer['PHONE_NO'] = isset($itemCondition->first()[0]['PHONE_NO']) && !empty($itemCondition->first()[0]['PHONE_NO']) ? implode('<br>', explode(',', $itemCondition->first()[0]['PHONE_NO'])) : '';
                                    $data_customer['EMAIL_ADDRES'] = isset($itemCondition->first()[0]['EMAIL_ADDRES']) && !empty($itemCondition->first()[0]['EMAIL_ADDRES']) ? implode('<br>', explode(',', $itemCondition->first()[0]['EMAIL_ADDRES'])) : '';
                                    $data_customer['PENDINGMAINTENANCEORDER'] = isset($itemCondition->first()[0]['DESC']) && !empty($itemCondition->first()[0]['DESC']) ? $itemCondition->first()[0]['DESC'] : '';
                                    
                                    // Detail Rental
                                    $data_rental['SAPRENTALOBJECTNAME'] = isset($itemCondition->first()[0]['SAPRENTALOBJECTNAME']) ? $itemCondition->first()[0]['SAPRENTALOBJECTNAME'] : '';

                                    $data_rental['STATUS'] = isset($measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']][0]['MEASUREMENT_DESC']) ? implode('<br>', $measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']]->pluck('MEASUREMENT_DESC', 'MEASUREMENT_DESC')->toArray()) : '';
                                    
                                    $data_rental['MEASUREMENT_UNIT'] = isset($measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']][0]['UNIT']) ? implode('<br>', $measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']]->pluck('UNIT', 'UNIT')->toArray()) : '';

                                    $data_rental['MEASUREMENT_AMOUNT'] = isset($measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']][0]['VALUE_COMPL']) ? $measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']]->pluck('VALUE_COMPL', 'VALUE_COMPL')->toArray() : 0;
                                }

                                return $itemCondition;
                            });
                        }
                        else {
                            $object_id = isset($data['FILTER']['P_COMPANY']) && !empty($data['FILTER']['P_COMPANY']) ? "{$data['FILTER']['P_COMPANY']}/{$keyPlant}/{$key}" : '';

                            $data_rental['STATUS'] = isset($measurement_data[$object_id]['IM'][0]['MEASUREMENT_DESC']) ? implode('<br>', $measurement_data[$object_id]['IM']->pluck('MEASUREMENT_DESC', 'MEASUREMENT_DESC')->toArray()) : '';
                            $data_rental['MEASUREMENT_UNIT'] = isset($measurement_data[$object_id]['IM'][0]['UNIT']) ? implode('<br>', $measurement_data[$object_id]['IM']->pluck('UNIT', 'UNIT')->toArray()) : '';
                            $data_rental['MEASUREMENT_AMOUNT'] = isset($measurement_data[$object_id]['IM'][0]['VALUE_COMPL']) ? $measurement_data[$object_id]['IM']->pluck('VALUE_COMPL', 'VALUE_COMPL')->toArray() : 0;

                        }

                        return [$key=>array("CONTRACT"=>$item, "CUSTOMER"=>$data_customer, 'RENTAL_INFO'=>$data_rental)];
                    });

                    return [$keyPlant=>$sort_data];
                })->toArray();
                ksort($data['RENTAL_OBJECT_DETAIL']);

                $data['RENTAL_OBJECT_SUMMARY'] = collect($data['RENTAL_OBJECT_DETAIL'])->mapWithKeys(function($itemParent, $key) use (&$data){
                    $total_all = count($itemParent);
                    $total_vacant = count(collect($itemParent)->filter(function($item, $key){
                        return (isset($item['CONTRACT']) && count($item['CONTRACT']) == 0);
                    })->values()->all());
                    $total_occupied = count(collect($itemParent)->filter(function($item, $key){
                        return (isset($item['CONTRACT']) && count($item['CONTRACT']) > 0);
                    })->values()->all());
                    $total_occupancy_rate = ((int)$total_occupied / (int)$total_all) * 100; 
                    $summary = array(
                        'TOTAL_ALL'=>$total_all,
                        'TOTAL_VACANT'=>$total_vacant,
                        'TOTAL_OCCUPIED'=>$total_occupied,
                        'TOTAL_OCCUPANCY_RATE'=>$total_occupancy_rate
                    );
                    array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL'], $total_all);
                    array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT'], $total_vacant);
                    array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED'], $total_occupied);
                    if($total_occupancy_rate && $total_occupancy_rate != 0)
                        array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'], $total_occupancy_rate);
                    return [$key=>$summary];
                })->toArray();
            }
        } catch(SapException $e){
            Log::error($e->getMessage());
            // dd($e->getMessage(), $filter_list);
            $request->session()->flash('error_rental', 'Something went wrong when trying to retrieve data from SAP, please try again.');
        } catch(\Exception $e){
            Log::error($e->getMessage());
            // dd($e->getMessage());
            $request->session()->flash('error_rental', 'Something went wrong, please try again.');
        }
        
        // return view('pages.sap.rental-object.rental_object_list', ['data'=>$data]);
        $rawHtml = view('pages.sap.rental-object.template', ['data'=>$data])->render();
        return view('pages.sap.rental-object.index', ['template'=>$rawHtml, 'data'=>$data]);
    }

    function rental_object_contract_detail(Request $request){
        $company_sent = $request->get('company', '');
        $plant_sent = $request->get('plant', '');
        $rental_unit_sent = $request->get('rental_unit', '');
        if(!$company_sent || !$plant_sent || !$rental_unit_sent)
            return response()->json(["status"=>"Error", 'message'=>"Company, Plant, and Rental Unit must be defined"], 412);

        $date_start='';
        $date_end='';
        $data['date_start'] = $date_start;
        $data['date_end'] = $date_end;

        $is_production = config('intranet.is_production');
        if($is_production)
            $connection = new SapConnection(config('intranet.rfc_prod'));
        else
            $connection = new SapConnection(config('intranet.rfc_dev'));

        $options = [
            'rtrim'=>true,
        ];

        $data['RENTAL_OBJECT_DETAIL'] = [];
        $filter_list = [
            'P_RENT_OBJ' => "$rental_unit_sent",
            'P_CONTRACT_NUM' => "",
            'P_COMPANY' => $company_sent,
            'P_BUSINES_ENTITY_NUM' => $plant_sent,
            'P_START_DATE'=>'01012000',
            'P_END_DATE'=>'31129999'
        ];

        try {
            $function = $connection->getFunction('ZFM_MM_IM_RE_FX_REPORT_V2');
            $result= $function->invoke($filter_list, $options);

            if(count(array_keys($result)) > 0 && in_array('GT_REPORT', array_keys($result))){
                $data['RENTAL_OBJECT_DETAIL'] = collect($result['GT_REPORT'])->groupBy('BUSINESSENTITYNUMBER')->mapWithKeys(function($item, $key){
                    $sort_data = $item->sort(function($a, $b){
                        return (strlen($a['CONTRACTNUMBER']) < strlen($b['CONTRACTNUMBER'])) ? 1 : -1;
                    })->groupBy(['SAPRENTALOBJECTID','OBJECT_ID','CONDITIONTYPE'])->mapWithKeys(function($itemContract, $key){
                        $itemNew = collect($itemContract)->reject(function($data, $key){
                            return empty($key);
                        });
                        return [$key=>$itemNew];
                    })->mapWithKeys(function($item, $key){
                        $is_available_contract = count($item);
                        if($is_available_contract){
                            $item_new = $item->mapWithKeys(function($contract, $key){
                                $condition = $contract;
                                $contract_detail = [];
                                $total_bill_balance = 0;
                                $total_account_balance = 0;
                                $available_condition = count($contract);
                                if($available_condition){
                                    $contract_detail['CONTRACTNAME'] = isset($contract->first()[0]['CONTRACTNAME']) ? $contract->first()[0]['CONTRACTNAME'] : '';
                                    $contract_detail['CONTRACTNUMBER'] = isset($contract->first()[0]['CONTRACTNUMBER']) ? $contract->first()[0]['CONTRACTNUMBER'] : '';
                                    $contract_detail['CONTRACTSTARTDATE'] = isset($contract->first()[0]['CONTRACTSTARTDATE']) && $contract->first()[0]['CONTRACTSTARTDATE'] != '00000000' ? date('d M, Y', strtotime($contract->first()[0]['CONTRACTSTARTDATE'])) : '';
                                    $contract_detail['CONTRACTENDDATE'] = isset($contract->first()[0]['CONTRACTENDDATE']) && $contract->first()[0]['CONTRACTENDDATE'] != '00000000' ? date('d M, Y', strtotime($contract->first()[0]['CONTRACTENDDATE'])) : '';
                                    $contract_detail['CURRENCY'] = isset($contract->first()[0]['CURRENCY']) ? $contract->first()[0]['CURRENCY'] : '';
                                    $contract->map(function($item, $key) use (&$total_bill_balance, &$total_account_balance){
                                        $bill_balance = isset($item[0]['BILLBALANCE']) ? (float) $item[0]['BILLBALANCE'] : 0;
                                        $account_balance = isset($item[0]['ACCOUNTBALANCEAMOUNT']) ? (float) $item[0]['ACCOUNTBALANCEAMOUNT'] : 0;

                                        $total_bill_balance += $bill_balance;
                                        $total_account_balance += $account_balance;
                                    });
                                    $contract_detail['BILLBALANCE'] = $total_bill_balance;
                                    $contract_detail['ACCOUNTBALANCEAMOUNT'] = $total_account_balance;
                                }
                                return [$key=>['CONDITIONS'=>$condition->toArray(), 'CONTRACT'=>$contract_detail]];
                            })->toArray();
                            return $item_new;
                        }
                        return [];
                    });
                    return $sort_data;
                })->toArray();
                ksort($data['RENTAL_OBJECT_DETAIL']);
                // dd($data);
            }
            return view('pages.sap.rental-object.contract_detail', ['data'=>$data]);

        } catch(SapException $e){
            Log::error($e->getMessage());
            // dd($e->getMessage(), $filter_list);
            return response()->json(["status"=>"Error", 'message'=>"Something went wrong with the SAP RFC, please try again"], 412);
        } catch(\Exception $e){
            Log::error($e->getMessage());
            // dd($e->getMessage());
            return response()->json(["status"=>"Error", 'message'=>"Something went wrong, please try again"], 412);
        }
    }

    function rental_object_list_filter(Request $request){
        if(self::wantsJson($request)){
            // $date_start=(!empty($request->get('date_start')))? date('Ymd',strtotime($request->get('date_start'))) : '';
            // $date_end=(!empty($request->get('date_end')))? date('Ymd',strtotime($request->get('date_end'))) : '';
            $date_start='';
            $date_end='';
            $data['date_start'] = $date_start;
            $data['date_end'] = $date_end;

            $is_production = config('intranet.is_production');
            if($is_production)
                $connection = new SapConnection(config('intranet.rfc_prod'));
            else
                $connection = new SapConnection(config('intranet.rfc_dev'));

            $options = [
                'rtrim'=>true,
            ];

            // RENTAL OBJECT
            // FILTER LIST [
            //  'P_RENT_OBJ'=>'KODE OBJECT RENTAL',
            //  'P_CONTRACT_NUM'=>'NOMOR KONTRAK',
            //  'P_COMPANY'=>'COMPANY CODE',
            //  'P_BUSINES_ENTITY_NUM'=>'PLANT CODE'
            // ]
             
            $company_list = [];
            $plant_list = [];
            try{
                $company_list = DB::connection('dbintranet')
                ->table('INT_COMPANY')
                ->select('COMPANY_CODE', 'COMPANY_NAME')
                ->get()->pluck('COMPANY_NAME', 'COMPANY_CODE')->toArray();

                $plant_list = DB::connection('dbintranet')
                ->table('INT_BUSINESS_PLANT')
                ->select('COMPANY_CODE', 'SAP_PLANT_ID', 'SAP_PLANT_NAME')
                ->get()->pluck('SAP_PLANT_NAME','SAP_PLANT_ID')->toArray();

            } catch(\Exception $e){
                Log::error("ERROR POPULATING COMPANY AND PLANT : ".$e->getMessage());
            }

            $data['COMPANY'] = $company_list;
            $data['PLANT'] = $plant_list;

            $filter_list = [
                'P_RENT_OBJ' => "",
                'P_CONTRACT_NUM' => "",
                'P_COMPANY' => "NJP",
                'P_BUSINES_ENTITY_NUM' => "",
            ];

            if($date_start && $date_end){
                $filter_list["P_START_DATE"] = $date_start;
                $filter_list["P_END_DATE"] = $date_end;
            }
            else {
                $data['date_start'] = date('Y-m-d');
                $data['date_end'] = date('Y-m-d');
            }

            try{
                if($request->get('company_code')){
                    $filter_list['P_COMPANY'] = $request->get('company_code');
                }
            } catch(\Exception $e){}

            // $data['RENTAL_OBJECT'] = [];
            $data['RENTAL_OBJECT_SUMMARY'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'] = [];
            
            $data['FILTER'] = $filter_list;

            try {
                $function = $connection->getFunction('ZFM_MM_IM_RE_FX_REPORT_V2');
                $result= $function->invoke($filter_list, $options);
                $measurement_data = isset($result['GT_MEASUREMENT_RENTAL']) ? collect($result['GT_MEASUREMENT_RENTAL'])->groupBy(['OBJECT_ID','OBJECT_TYPE']) : [];
                // $measurement_contract = isset($result['GT_MEASUREMENT']) ? collect($result['GT_MEASUREMENT'])->groupBy(['OBJECT_ID','OBJECT_TYPE']) : [];

                if(count(array_keys($result)) > 0 && in_array('GT_REPORT', array_keys($result))){
                    $data['RENTAL_OBJECT_DETAIL'] = collect($result['GT_REPORT'])->groupBy('BUSINESSENTITYNUMBER')->mapWithKeys(function($item, $keyPlant) use ($measurement_data, $data){
                        $sort_data = $item->sort(function($a, $b){
                            return (strlen($a['CONTRACTNUMBER']) < strlen($b['CONTRACTNUMBER'])) ? 1 : -1;
                        })->groupBy(['SAPRENTALOBJECTID','OBJECT_ID','CONDITIONTYPE'])->mapWithKeys(function($itemContract, $key){
                            $itemNew = collect($itemContract)->reject(function($data, $key){
                                return empty($key);
                            });
                            return [$key=>$itemNew];
                        })->mapWithKeys(function($item, $key) use ($measurement_data, $keyPlant, $data){
                            $data_customer = [];
                            $data_rental = [];
                            $count_contract = count($item);

                            if($count_contract > 0) {
                                // AMBIL SATU DATA CUSTOMER DARI KONTRAK
                                $cek_customer = collect($item)->map(function($itemCondition, $keyCondition) use (&$data_customer, &$data_rental, $measurement_data, $key){
                                    $count_condition = count($itemCondition);
                                    if($count_condition > 0) {
                                        // Detail Customer
                                        $data_customer['SAPCUSTOMERNAME'] = isset($itemCondition->first()[0]['SAPCUSTOMERNAME']) && !empty($itemCondition->first()[0]['SAPCUSTOMERNAME']) ? $itemCondition->first()[0]['SAPCUSTOMERNAME'] : '';

                                        $data_customer['SAPCUSTOMERID'] = isset($itemCondition->first()[0]['SAPCUSTOMERID']) && !empty($itemCondition->first()[0]['SAPCUSTOMERID']) ? $itemCondition->first()[0]['SAPCUSTOMERID'] : '';
                                        $data_customer['SAPBILLTOID'] = isset($itemCondition->first()[0]['SAPBILLTOID']) && !empty($itemCondition->first()[0]['SAPBILLTOID']) ? $itemCondition->first()[0]['SAPBILLTOID'] : '';
                                        $data_customer['SAPBILLTONAME'] = isset($itemCondition->first()[0]['SAPBILLTONAME']) && !empty($itemCondition->first()[0]['SAPBILLTONAME']) ? $itemCondition->first()[0]['SAPBILLTONAME'] : '';
                                        $data_customer['SAPBILLTONAME'] = isset($itemCondition->first()[0]['SAPBILLTONAME']) && !empty($itemCondition->first()[0]['SAPBILLTONAME']) ? $itemCondition->first()[0]['SAPBILLTONAME'] : '';
                                        
                                        $data_customer['PHONE_NO'] = isset($itemCondition->first()[0]['PHONE_NO']) && !empty($itemCondition->first()[0]['PHONE_NO']) ? implode('<br>', explode(',', $itemCondition->first()[0]['PHONE_NO'])) : '';
                                        $data_customer['EMAIL_ADDRES'] = isset($itemCondition->first()[0]['EMAIL_ADDRES']) && !empty($itemCondition->first()[0]['EMAIL_ADDRES']) ? implode('<br>', explode(',', $itemCondition->first()[0]['EMAIL_ADDRES'])) : '';
                                        $data_customer['PENDINGMAINTENANCEORDER'] = isset($itemCondition->first()[0]['DESC']) && !empty($itemCondition->first()[0]['DESC']) ? $itemCondition->first()[0]['DESC'] : '';
                                        
                                        // Detail Rental
                                        $data_rental['SAPRENTALOBJECTNAME'] = isset($itemCondition->first()[0]['SAPRENTALOBJECTNAME']) ? $itemCondition->first()[0]['SAPRENTALOBJECTNAME'] : '';

                                        $data_rental['STATUS'] = isset($measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']][0]['MEASUREMENT_DESC']) ? implode('<br>', $measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']]->pluck('MEASUREMENT_DESC', 'MEASUREMENT_DESC')->toArray()) : '';
                                        
                                        $data_rental['MEASUREMENT_UNIT'] = isset($measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']][0]['UNIT']) ? implode('<br>', $measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']]->pluck('UNIT', 'UNIT')->toArray()) : '';

                                        $data_rental['MEASUREMENT_AMOUNT'] = isset($measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']][0]['VALUE_COMPL']) ? $measurement_data[$itemCondition->first()[0]['CALCULATION_OBJECT_ID']][$itemCondition->first()[0]['CALCULATION_OBJECT_TYPE']]->pluck('VALUE_COMPL', 'VALUE_COMPL')->toArray() : 0;
                                    }

                                    return $itemCondition;
                                });
                            }
                            else {
                                $object_id = isset($data['FILTER']['P_COMPANY']) && !empty($data['FILTER']['P_COMPANY']) ? "{$data['FILTER']['P_COMPANY']}/{$keyPlant}/{$key}" : '';

                                $data_rental['STATUS'] = isset($measurement_data[$object_id]['IM'][0]['MEASUREMENT_DESC']) ? implode('<br>', $measurement_data[$object_id]['IM']->pluck('MEASUREMENT_DESC', 'MEASUREMENT_DESC')->toArray()) : '';
                                $data_rental['MEASUREMENT_UNIT'] = isset($measurement_data[$object_id]['IM'][0]['UNIT']) ? implode('<br>', $measurement_data[$object_id]['IM']->pluck('UNIT', 'UNIT')->toArray()) : '';
                                $data_rental['MEASUREMENT_AMOUNT'] = isset($measurement_data[$object_id]['IM'][0]['VALUE_COMPL']) ? $measurement_data[$object_id]['IM']->pluck('VALUE_COMPL', 'VALUE_COMPL')->toArray() : 0;

                            }

                            return [$key=>array("CONTRACT"=>$item, "CUSTOMER"=>$data_customer, 'RENTAL_INFO'=>$data_rental)];
                        });

                        return [$keyPlant=>$sort_data];
                    })->toArray();
                    ksort($data['RENTAL_OBJECT_DETAIL']);

                    $data['RENTAL_OBJECT_SUMMARY'] = collect($data['RENTAL_OBJECT_DETAIL'])->mapWithKeys(function($itemParent, $key) use (&$data){
                        $total_all = count($itemParent);
                        $total_vacant = count(collect($itemParent)->filter(function($item, $key){
                            return (isset($item['CONTRACT']) && count($item['CONTRACT']) == 0);
                        })->values()->all());
                        $total_occupied = count(collect($itemParent)->filter(function($item, $key){
                            return (isset($item['CONTRACT']) && count($item['CONTRACT']) > 0);
                        })->values()->all());
                        $total_occupancy_rate = ((int)$total_occupied / (int)$total_all) * 100; 
                        $summary = array(
                            'TOTAL_ALL'=>$total_all,
                            'TOTAL_VACANT'=>$total_vacant,
                            'TOTAL_OCCUPIED'=>$total_occupied,
                            'TOTAL_OCCUPANCY_RATE'=>$total_occupancy_rate
                        );
                        array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL'], $total_all);
                        array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT'], $total_vacant);
                        array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED'], $total_occupied);
                        if($total_occupancy_rate && $total_occupancy_rate != 0)
                            array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'], $total_occupancy_rate);
                        return [$key=>$summary];
                    })->toArray();
                }
            } catch(SapException $e){
                Log::error($e->getMessage());
                // dd($e->getMessage(), $filter_list);
                $request->session()->flash('error_rental', 'Something went wrong when trying to retrieve data from SAP, please try again.');
            } catch(\Exception $e){
                Log::error($e->getMessage());
                // dd($e->getMessage());
                $request->session()->flash('error_rental', 'Something went wrong, please try again.');
            }
        
            // return view('pages.sap.rental-object.rental_object_list', ['data'=>$data]);
            $rawHtml = view('pages.sap.rental-object.template', ['data'=>$data])->render();
            $plant_check = isset($data['COMPANY'][$filter_list['P_COMPANY']]) ? $data['COMPANY'][$filter_list['P_COMPANY']] : '-';

            $date_start = $date_start ? date('d/m/Y',strtotime(str_replace('/', '-', $request->get('date_start')))) : date('d/m/Y');
            $date_end = $date_end ? date('d/m/Y',strtotime(str_replace('/', '-', $request->get('date_end')))) : date('d/m/Y');

            return ['data'=>$rawHtml, 'plant'=>$plant_check, 'date_start'=>$date_start, 'date_end'=>$date_end];
        } else {
            abort(403);
        }
    }

    function invoice_list(Request $request){
        $data=array();
        $username=config('payment.username');
        $password=config('payment.password');
        $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : date('Y-m-01');
        $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');

        $payload = json_encode( array( "DATEFR" => $date_start, "DATETO" => $date_end ) );
        $param=urlencode($payload);

        // $url="http://10.192.2.181:82/api/InvoiceList/".$param;
        $url=config('payment.sap_url.invoice_list')."".$param;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        if(!empty($post) && isset($post->status) && $post->status == '401')
            // abort(401);
            $post=[];

        $post = collect($post)->groupBy('CAANO')->mapWithKeys(function($item, $key) {
            $subtotal_statement = $item->reduce(function ($carry, $item) {
                $item_sub = isset($item->WRBTR) ? (float)$item->WRBTR : (float)0; 
                return (float)$carry + $item_sub;
            }, 0);
            $item = count($item) > 0 ? $item->first() : [];
            if(isset($item->INVTY)){
                $item->WRBTR = $subtotal_statement;
                switch (strtolower($item->INVTY)) {
                    case 're':
                        $item->INVTY = 'RE-FX';
                        break;
                    case 'dp':
                        $item->INVTY = 'DOWN PAYMENT';
                        break;
                    case 'fi':
                        $item->INVTY = 'FI';
                        break;
                    default:
                        break;
                }
            }
            return [$item->CAANO => $item];
        });
    
        $invoice = $post;
        $data['date_start']=$date_start;
        $data['date_end']=$date_end;
        return view('pages.sap.invoice.invoice_list', ['data'=>$data , 'invoice'=>$invoice]);
    }
     
    
    function invoice_list_getData(Request $request){
        $data=array();
        $username=config('payment.username');
        $password=config('payment.password');
        $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
        $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');

        $payload = json_encode( array( "DATEFR" => $date_start, "DATETO" => $date_end ) );
        $param=urlencode($payload);

        // $url="http://10.192.2.181:82/api/InvoiceList/".$param;
        $url=config('payment.sap_url.invoice_list')."".$param;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        if(!empty($post) && isset($post->status) && $post->status == '401')
            // abort(401);
            $post=[];

        $post = collect($post)->groupBy('CAANO')->mapWithKeys(function($item, $key) {
            $subtotal_statement = $item->reduce(function ($carry, $item) {
                $item_sub = isset($item->WRBTR) ? (float)$item->WRBTR : (float)0; 
                return (float)$carry + $item_sub;
            }, 0);
            $item = count($item) > 0 ? $item->first() : [];
            if(isset($item->INVTY)){
                $item->WRBTR = $subtotal_statement;
                switch (strtolower($item->INVTY)) {
                    case 're':
                        $item->INVTY = 'RE-FX';
                        break;
                    case 'dp':
                        $item->INVTY = 'DOWN PAYMENT';
                        break;
                    case 'fi':
                        $item->INVTY = 'FI';
                        break;
                    default:
                        break;
                }
            }
            return [$item->CAANO => $item];
        });

        $invoice = $post->toArray() ? $post->toArray() : $post;
        $iterable = 0;
        $datatables = DataTables::of($invoice)
        ->addColumn('NUM_ORDER', function ($json) use (&$iterable) {
            $iterable = $iterable + 1;
            return "<a class='text-primary' style='cursor:pointer'>".$iterable."</a>";
        })
        ->editColumn('CAANO', function($json) use ($request){
            return '<a class="detail-link" data-origin="/'.$request->path().'" href="'.route('sap.invoice.detail', $json->CAANO).'">'.$json->CAANO.'</a>';
        })
        ->addColumn('CAANO_PLAIN', function($json) use ($request){
            return $json->CAANO;
        })
        ->rawColumns(['CAANO', 'NUM_ORDER']);

        // Untuk filter datatable
        if ($keyword = isset($request->get('columns')[1]['search']['value']) ? $request->get('columns')[1]['search']['value'] : '') {
            $datatables->filter(function($query) use ($keyword){
                $query->filter(function($item, $key) use ($keyword){
                    return $item->CAANO_PLAIN == $keyword;
                });
            });
        }
        return $datatables->make(true);
    }

    function invoice_detail($param, Request $request){
        $data=array();
        $username=config('payment.username');
        $password=config('payment.password');
        $no_invoice = $param;

        $payload = json_encode( array( "CAANO" => $param ) );
        $param=urlencode($payload);
        // $url="http://10.192.2.181:82/api/InvoiceDetail/".$param;
        $url=config('payment.sap_url.invoice_detail')."".$param;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        if(!empty($post) && isset($post->status) && $post->status == '401')
            abort(401);
        
        if($post){
            $data['invoice']=$post;
        }else{
            return view('pages.exception.blank_page');
            echo "no data";
            die;
        }

        switch ($post) {
            case count((array)$post->ITEM_DETAILS_RE) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_RE;
                try{
                    unset($new_post['ITEM_DETAILS_FI']);
                    unset($new_post['ITEM_DETAILS_DP']);
                } catch(\Exception $e){};
                break;
            case count((array)$post->ITEM_DETAILS_FI) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_FI;
                try{
                    unset($new_post['ITEM_DETAILS_RE']);
                    unset($new_post['ITEM_DETAILS_DP']);
                } catch(\Exception $e){};
                break;
            case count((array)$post->ITEM_DETAILS_DP) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_DP;
                try{
                    unset($new_post['ITEM_DETAILS_RE']);
                    unset($new_post['ITEM_DETAILS_FI']);
                } catch(\Exception $e){};
                break;
            default:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = [];
                break;
        }

        if(!$new_post){
            $request->session()->flash('error_detail_invoice', 'No Details Found, please try again');
            return view('pages.exception.blank_page');
            die;
        }

        $company_code = isset($post->BUKRS) && !empty($post->BUKRS) ? strtolower($post->BUKRS) : '';
        if(!$company_code){
            $request->session()->flash('error_detail_invoice', 'Unable to find statement details, please try again later');
            return view('pages.exception.blank_page');
            die;
        }

        switch ($company_code) {
            // Default is KMS, so dont need to be validated
            case 'kms': case 'kms1': case 'kms2': case 'kms3':
                $route = 'pages.sap.invoice.invoice_payment_kms_sap';
                break;
            case 'njp': case 'njp0': case 'njp1':
                $route = 'pages.sap.invoice.invoice_payment_njp_sap';
                break;
            case 'ppc' : case 'ppc0': case 'ppc1':
                $route = 'pages.sap.invoice.invoice_payment_ppc_sap';
                break;
            case 'pad' : case 'pad0': case 'pad1': case 'pad2':
                $route = 'pages.sap.invoice.invoice_payment_pad_sap';
                break;
            case 'pnb' : case 'pnb0': case 'pnb1':
                $route = 'pages.sap.invoice.invoice_payment_pnb_sap';
                break;
            case 'wkk' : case 'wkk0': case 'wkk1': case 'wkk2':
                $route = 'pages.sap.invoice.invoice_payment_wkk_sap';
                break;
            default:
                $request->session()->flash('error_detail_invoice', 'Unable to find Company Code, please try again');
                $route = 'pages.exception.blank_page';
                break;
        }
        
        $cek_status_prod = config('payment.is_production');
        if($cek_status_prod == false){
            $data['invoice'] = json_decode(json_encode($new_post));
            $cek_invoice_status = DB::connection('dbintranet')
            ->table('INT_MAP_PAYMENT_INVOICE')
            ->where('CAANO', $data['invoice']->CAANO)
            ->get();
        }
        else {
            $data['invoice'] = json_decode(json_encode($new_post));
            $cek_invoice_status = DB::connection('dbintranet')
            ->table('INT_MAP_PAYMENT_INVOICE_PROD')
            ->where('CAANO', $data['invoice']->CAANO)
            ->get();
        }

        $order_id = $cek_invoice_status->groupBy('CAANO')->toArray();
        $any_payment = [];

        $plant_code = isset($post->PRCTR) && !empty($post->PRCTR) ? strtolower($post->PRCTR) : '';
        if(!$plant_code){
            $request->session()->flash('error_detail_invoice', 'Unable to read plant code, please make sure the data sent by SAP is correct');
            return view('pages.exception.blank_page');
            die;
        }

        /* Cek Payment Status */
        // if($order_id){
        //     switch ($plant_code) {
        //         case 'kms1':
        //             if(!$cek_status_prod)
        //                 $server_key = config('payment.serverkey.sandbox.kms1');
        //             else
        //                 $server_key = config('payment.serverkey.prod.kms1');
        //         break;
        //         case 'njp1':
        //             if(!$cek_status_prod)
        //                 $server_key = config('payment.serverkey.sandbox.njp1');
        //             else
        //                 $server_key = config('payment.serverkey.prod.njp1');
        //             break;
        //         default:
        //             $server_key = null;
        //             break;
        //     }

        //     if(!$server_key){
        //         $request->session()->flash('error_detail_invoice', 'Unable to retrive payment status on midtrans due to lack of or missing configuration');
        //         return view('pages.exception.blank_page');
        //         die;
        //     }

        //     foreach ($order_id as $key => $value) {
        //         // Cek status dengan sistem pembayaran selain CC
        //         $payload = json_encode( array( "Caano" => $key ) );
        //         $param=urlencode($payload);
        //         $OTH_PAYMENT = config('payment.sap_url.cc_notif')."".$param;
        //         $ch = curl_init();
        //         curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        //         curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //         curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //         $server_output = curl_exec($ch);
        //         curl_close ($ch);

        //         $post=json_decode($server_output);
        //         if(isset($post->Statuscode) && $post->Statuscode == '200')
        //             array_push($any_payment, strtolower($post->Transactionstatus));

        //         // Cek Pembayaran CC dengan midtrans API
        //         foreach ($value as $obj) {
        //             if(!$cek_status_prod)
        //                 $CC_PAYMENT = 'https://api.sandbox.midtrans.com/v2/'.$obj->ORDER_ID.'/status';
        //             else
        //                 $CC_PAYMENT = 'https://api.midtrans.com/v2/'.$obj->ORDER_ID.'/status';

        //             $token = base64_encode($server_key);
        //             $headers = array(
        //                "Authorization: Basic ${token}",
        //             );

        //             $ch = curl_init();
        //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        //             curl_setopt($ch, CURLOPT_URL,$CC_PAYMENT);
        //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //             $server_output = curl_exec($ch);
        //             curl_close ($ch);
        //             $post=json_decode($server_output);
        //             if(isset($post->status_code) && $post->status_code == '200')
        //                 array_push($any_payment, strtolower($post->transaction_status));
        //         }
        //     }
        // }
        // else {
        //     // Cek status dengan sistem pembayaran selain CC
        //     $payload = json_encode( array( "Caano" => $no_invoice ) );
        //     $param=urlencode($payload);
        //     $OTH_PAYMENT = config('payment.sap_url.cc_notif')."".$param;
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        //     curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //     $server_output = curl_exec($ch);
        //     curl_close ($ch);

        //     $post=json_decode($server_output);
        //     if(isset($post->Statuscode) && $post->Statuscode == '200')
        //         array_push($any_payment, strtolower($post->Transactionstatus));
        // }

        $any_payment = $this->cmp(array_unique($any_payment));
        // $status_payment = '
        // <div class="pt-5 pb-1 text-center">
        //     <h3><i class="fa fa-clock text-default"></i>&nbsp; Waiting for transaction to be created.</h3>
        // </div>';
         
        $status_payment = '
        <div class="pt-3 pb-1 text-center">
        </div>';

        foreach($any_payment as $any_payment){
            switch ($any_payment) {
                case 'pending':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-clock text-default"></i>&nbsp; Transaction within this invoice is created and available / waiting to be paid.</h3>
                    </div>';
                    break;
                case 'capture':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-info-circle text-info"></i>&nbsp; Transaction is successful and credit card balance is captured successfully.</h3>
                        <h6 class="text-muted"> If no action taken any further, transaction will be settled on the next day.</h6>
                    </div>';
                    break;
                case 'settlement': case 'settle':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-check-circle text-success"></i>&nbsp; Transaction is successfully settled. Funds have been received.</h3>
                    </div>';
                    break;
                case 'cancel':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-window-close text-danger"></i>&nbsp; Transaction within this invoice is cancelled.</h3>
                    </div>';
                    break;
                default:
                    break;
            }
        }

        // Cek data Plant dari database (Seperti alamat dan email)
        $plant_info = [];
        try{
            $plant = $data['invoice']->PRCTR;
            $plant_info = DB::connection('dbintranet')
            ->table('INT_BUSINESS_PLANT')
            ->where('SAP_PLANT_ID', $plant)
            ->get()->first();

        } catch(\Exception $e){}
        // dd($data);
        
        return view($route, ['data'=>$data, 'status_payment'=>$status_payment, 'plant_info'=>$plant_info]);
    }

    function invoice_list_qas(Request $request){
        $data=array();
        $username=config('payment.username_qas');
        $password=config('payment.password_qas');
        $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
        $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');

        $payload = json_encode( array( "DATEFR" => $date_start, "DATETO" => $date_end ) );
        $param=urlencode($payload);

        $url=config('payment.sap_url.invoice_list_qas').''.$param;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        if(!empty($post) && isset($post->status) && $post->status == '401')
            // abort(401);
            $post=[];


        $post = collect($post)->mapWithKeys(function($item, $key) {
            if(isset($item->INVTY)){
                switch (strtolower($item->INVTY)) {
                    case 're':
                        $item->INVTY = 'RE-FX';
                        break;
                    case 'dp':
                        $item->INVTY = 'DOWN PAYMENT';
                        break;
                    case 'fi':
                        $item->INVTY = 'FI';
                        break;
                    default:
                        break;
                }
            }
            return [$item->CAANO => $item];
        });

        $invoice=$post;
        // $obj_class = $this;
        // $invoice=$invoice->filter(function($item, $key) use ($obj_class){
        //     $no_invoice = $key;
        //     $plant = isset($item->BUKRS) ? strtolower($item->BUKRS) : '';
        //     $invoice_status = $obj_class->check_invoice_status($no_invoice, $plant);
        //     $has_detail = $obj_class->check_invoice_has_detail($no_invoice);
        //     $has_total_amount = isset($item->WRBTR) ? (int)$item->WRBTR : 0;
        //     $has_detail = isset($has_detail->CAANO) ? $has_detail->CAANO : '';
        //     return (strip_tags(strtolower($invoice_status)) != 'settled' && !empty($has_detail) && $has_total_amount);
        // })->map(function($item, $key) use ($obj_class){
        //     $statement_date = $obj_class->check_invoice_has_detail($key);
        //     $new_period = '';
        //     if(isset($statement_date->PERIOD) && $statement_date->PERIOD){
        //         try {
        //             $new_period = [];
        //             $caano_period = explode(' - ',$statement_date->PERIOD);
        //             foreach($caano_period as $period){
        //                 if(!in_array(date('d M Y', strtotime($period)), $new_period))
        //                     $new_period[] = date('d M Y', strtotime($period));
        //             }
        //             $new_period = isset($new_period[1]) ? $new_period[1] : '';
        //         } catch(\Exception $e){}
        //     }
        //     $item->STATEMENT_DATE = $new_period;
        //     return $item;
        // });
        // dd($invoice);
        $data['date_start']=$date_start;
        $data['date_end']=$date_end;

        return view('pages.sap.invoice.QAS.invoice_list', ['data'=>$data , 'invoice'=>$invoice]);
    }

    function invoice_detail_qas($param,Request $request){
        $data=array();
        $username=config('payment.username_qas');
        $password=config('payment.password_qas');
        $no_invoice = $param;

        $payload = json_encode( array( "CAANO" => $param ) );
        $param=urlencode($payload);
        $url=config('payment.sap_url.invoice_detail_qas').''.$param;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        if(!empty($post) && isset($post->status) && $post->status == '401')
            abort(401);
        
        if($post){
            $data['invoice']=$post;
        }else{
            return view('pages.exception.blank_page');
            echo "no data";
            die;
        }

        switch ($post) {
            case count((array)$post->ITEM_DETAILS_RE) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_RE;
                try{
                    unset($new_post['ITEM_DETAILS_FI']);
                    unset($new_post['ITEM_DETAILS_DP']);
                } catch(\Exception $e){};
                break;
            case count((array)$post->ITEM_DETAILS_FI) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_FI;
                try{
                    unset($new_post['ITEM_DETAILS_RE']);
                    unset($new_post['ITEM_DETAILS_DP']);
                } catch(\Exception $e){};
                break;
            case count((array)$post->ITEM_DETAILS_DP) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_DP;
                try{
                    unset($new_post['ITEM_DETAILS_RE']);
                    unset($new_post['ITEM_DETAILS_FI']);
                } catch(\Exception $e){};
                break;
            default:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = [];
                break;
        }

        if(!$new_post){
            $request->session()->flash('error_detail_invoice', 'No Details Found, please try again');
            return view('pages.exception.blank_page');
            die;
        }

        $company_code = isset($post->BUKRS) && !empty($post->BUKRS) ? strtolower($post->BUKRS) : '';
        if(!$company_code){
            $request->session()->flash('error_detail_invoice', 'Unable to find Company Code, please try again');
            return view('pages.exception.blank_page');
            die;
        }

        switch ($company_code) {
            // Default is KMS, so dont need to be validated
            case 'kms': case 'kms1': case 'kms2': case 'kms3':
                $route = 'pages.sap.invoice.QAS.invoice_payment_kms_sap';
                break;
            case 'njp': case 'njp0': case 'njp1':
                $route = 'pages.sap.invoice.QAS.invoice_payment_njp_sap';
                break;
            case 'ppc' : case 'ppc0': case 'ppc1':
                $route = 'pages.sap.invoice.QAS.invoice_payment_ppc_sap';
                break;
            case 'pad' : case 'pad0': case 'pad1': case 'pad2':
                $route = 'pages.sap.invoice.QAS.invoice_payment_pad_sap';
                break;
            case 'pnb' : case 'pnb0': case 'pnb1':
                $route = 'pages.sap.invoice.QAS.invoice_payment_pnb_sap';
                break;
            case 'wkk' : case 'wkk0': case 'wkk1': case 'wkk2':
                $route = 'pages.sap.invoice.QAS.invoice_payment_wkk_sap';
                break;
            default:
                $request->session()->flash('error_detail_invoice', 'Unable to find Company Code, please try again');
                $route = 'pages.exception.blank_page';
                break;
        }
        
        $cek_status_prod = config('payment.is_production');
        $data['invoice'] = json_decode(json_encode($new_post));
        $cek_invoice_status = DB::connection('dbintranet')
        ->table('INT_MAP_PAYMENT_INVOICE_QAS')
        ->where('CAANO', $data['invoice']->CAANO)
        ->get();

        $order_id = $cek_invoice_status->groupBy('CAANO')->toArray();
        $any_payment = [];

        $plant_code = isset($post->PRCTR) && !empty($post->PRCTR) ? strtolower($post->PRCTR) : '';
        if(!$plant_code){
            $request->session()->flash('error_detail_invoice', 'Unable to read plant code, please make sure the data sent by SAP is correct');
            return view('pages.exception.blank_page');
            die;
        }            

        /* Cek Payment Status */
        // if($order_id){
        //     switch ($plant_code) {
        //         case 'kms1':
        //             if(!$cek_status_prod)
        //                 $server_key = config('payment.serverkey.sandbox.kms1');
        //             else
        //                 $server_key = config('payment.serverkey.prod.kms1');
        //         break;
        //         case 'njp1':
        //             if(!$cek_status_prod)
        //                 $server_key = config('payment.serverkey.sandbox.njp1');
        //             else
        //                 $server_key = config('payment.serverkey.prod.njp1');
        //             break;
        //         default:
        //             $server_key = null;
        //             break;
        //     }

        //     if(!$server_key){
        //         $request->session()->flash('error_detail_invoice', 'Unable to retrive payment status on midtrans due to lack of or missing configuration');
        //         return view('pages.exception.blank_page');
        //         die;
        //     }
        //     foreach ($order_id as $key => $value) {
        //         // Cek status dengan sistem pembayaran selain CC
        //         $payload = json_encode( array( "Caano" => $key ) );
        //         $param=urlencode($payload);
        //         $OTH_PAYMENT = config('payment.sap_url.cc_notif_qas').''.$param;
        //         $ch = curl_init();
        //         curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        //         curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //         curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //         $server_output = curl_exec($ch);
        //         curl_close ($ch);

        //         $post=json_decode($server_output);
        //         if(isset($post->Statuscode) && $post->Statuscode == '200')
        //             array_push($any_payment, strtolower($post->Transactionstatus));

        //         // Cek Pembayaran CC dengan midtrans API
        //         foreach ($value as $obj) {
        //             $CC_PAYMENT = 'https://api.sandbox.midtrans.com/v2/'.$obj->ORDER_ID.'/status';
        //             $token = base64_encode($server_key);
        //             $headers = array(
        //                "Authorization: Basic ${token}",
        //             );

        //             $ch = curl_init();
        //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        //             curl_setopt($ch, CURLOPT_URL,$CC_PAYMENT);
        //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //             $server_output = curl_exec($ch);
        //             curl_close ($ch);
        //             $post=json_decode($server_output);
        //             if(isset($post->status_code) && $post->status_code == '200')
        //                 array_push($any_payment, strtolower($post->transaction_status));
        //         }
        //     }
        // }
        // else {
        //     // Cek status dengan sistem pembayaran selain CC
        //     $payload = json_encode( array( "Caano" => $no_invoice ) );
        //     $param=urlencode($payload);
        //     $OTH_PAYMENT = config('payment.sap_url.cc_notif_qas').''.$param;
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        //     curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //     $server_output = curl_exec($ch);
        //     curl_close ($ch);

        //     $post=json_decode($server_output);
        //     if(isset($post->Statuscode) && $post->Statuscode == '200')
        //         array_push($any_payment, strtolower($post->Transactionstatus));
        // }

        $any_payment = $this->cmp(array_unique($any_payment));
        // $status_payment = '
        // <div class="pt-5 pb-1 text-center">
        //     <h3><i class="fa fa-clock text-default"></i>&nbsp; Waiting for transaction to be created.</h3>
        // </div>';
        
        $status_payment = '
        <div class="pt-3 pb-1 text-center">
        </div>';

        foreach($any_payment as $any_payment){
            switch ($any_payment) {
                case 'pending':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-clock text-default"></i>&nbsp; Transaction within this invoice is created and available / waiting to be paid.</h3>
                    </div>';
                    break;
                case 'capture':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-info-circle text-info"></i>&nbsp; Transaction is successful and credit card balance is captured successfully.</h3>
                        <h6 class="text-muted"> If no action taken any further, transaction will be settled on the next day.</h6>
                    </div>';
                    break;
                case 'settlement': case 'settle':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-check-circle text-success"></i>&nbsp; Transaction is successfully settled. Funds have been received.</h3>
                    </div>';
                    break;
                case 'cancel':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-window-close text-danger"></i>&nbsp; Transaction within this invoice is cancelled.</h3>
                    </div>';
                    break;
                default:
                    break;
            }
        }

        // Cek data Plant dari database (Seperti alamat dan email)
        $plant_info = [];
        try{
            $plant = $data['invoice']->PRCTR;
            $plant_info = DB::connection('dbintranet')
            ->table('INT_BUSINESS_PLANT')
            ->where('SAP_PLANT_ID', $plant)
            ->get()->first();

        } catch(\Exception $e){}
        return view($route, ['data'=>$data, 'status_payment'=>$status_payment, 'plant_info'=>$plant_info]);
    }

    function invoice_list_pre_live(Request $request){
        $data=array();
        $username=config('payment.username_qas');
        $password=config('payment.password_qas');
        $date_start=(!empty($request->get('date_start')))? date('Y-m-d',strtotime($request->get('date_start'))) : '2021-01-01';
        $date_end=(!empty($request->get('date_end')))? date('Y-m-d',strtotime($request->get('date_end'))) : date('Y-m-d');

        $payload = json_encode( array( "DATEFR" => $date_start, "DATETO" => $date_end ) );
        $param=urlencode($payload);

        $url=config('payment.sap_url.invoice_list_pre_live').''.$param;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        if(!empty($post) && isset($post->status) && $post->status == '401')
            // abort(401);
            $post=[];
            
        
        $post = collect($post)->mapWithKeys(function($item, $key) {
            if(isset($item->INVTY)){
                switch (strtolower($item->INVTY)) {
                    case 're':
                        $item->INVTY = 'RE-FX';
                        break;
                    case 'dp':
                        $item->INVTY = 'DOWN PAYMENT';
                        break;
                    case 'fi':
                        $item->INVTY = 'FI';
                        break;
                    default:
                        break;
                }
            }
            return [$item->CAANO => $item];
        });

        $invoice=$post;
        // $obj_class = $this;
        // $invoice=$invoice->filter(function($item, $key) use ($obj_class){
        //     $no_invoice = $key;
        //     $plant = isset($item->BUKRS) ? strtolower($item->BUKRS) : '';
        //     $invoice_status = $obj_class->check_invoice_status($no_invoice, $plant);
        //     $has_detail = $obj_class->check_invoice_has_detail($no_invoice);
        //     $has_total_amount = isset($item->WRBTR) ? (int)$item->WRBTR : 0;
        //     $has_detail = isset($has_detail->CAANO) ? $has_detail->CAANO : '';
        //     return (strip_tags(strtolower($invoice_status)) != 'settled' && !empty($has_detail) && $has_total_amount);
        // })->map(function($item, $key) use ($obj_class){
        //     $statement_date = $obj_class->check_invoice_has_detail($key);
        //     $new_period = '';
        //     if(isset($statement_date->PERIOD) && $statement_date->PERIOD){
        //         try {
        //             $new_period = [];
        //             $caano_period = explode(' - ',$statement_date->PERIOD);
        //             foreach($caano_period as $period){
        //                 if(!in_array(date('d M Y', strtotime($period)), $new_period))
        //                     $new_period[] = date('d M Y', strtotime($period));
        //             }
        //             $new_period = isset($new_period[1]) ? $new_period[1] : '';
        //         } catch(\Exception $e){}
        //     }
        //     $item->STATEMENT_DATE = $new_period;
        //     return $item;
        // });
        // dd($invoice);
        $data['date_start']=$date_start;
        $data['date_end']=$date_end;

        return view('pages.sap.invoice.PRE_LIVE.invoice_list', ['data'=>$data , 'invoice'=>$invoice]);
    }

    function invoice_detail_pre_live($param,Request $request){
        $data=array();
        $username=config('payment.username_qas');
        $password=config('payment.password_qas');
        $no_invoice = $param;

        $payload = json_encode( array( "CAANO" => $param ) );
        $param=urlencode($payload);
        $url=config('payment.sap_url.invoice_detail_pre_live').''.$param;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        if(!empty($post) && isset($post->status) && $post->status == '401')
            abort(401);
        
        if($post){
            $data['invoice']=$post;
        }else{
            return view('pages.exception.blank_page');
            echo "no data";
            die;
        }

        switch ($post) {
            case count((array)$post->ITEM_DETAILS_RE) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_RE;
                try{
                    unset($new_post['ITEM_DETAILS_FI']);
                    unset($new_post['ITEM_DETAILS_DP']);
                } catch(\Exception $e){};
                break;
            case count((array)$post->ITEM_DETAILS_FI) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_FI;
                try{
                    unset($new_post['ITEM_DETAILS_RE']);
                    unset($new_post['ITEM_DETAILS_DP']);
                } catch(\Exception $e){};
                break;
            case count((array)$post->ITEM_DETAILS_DP) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_DP;
                try{
                    unset($new_post['ITEM_DETAILS_RE']);
                    unset($new_post['ITEM_DETAILS_FI']);
                } catch(\Exception $e){};
                break;
            default:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = [];
                break;
        }

        if(!$new_post){
            $request->session()->flash('error_detail_invoice', 'No Details Found, please try again');
            return view('pages.exception.blank_page');
            die;
        }

        $company_code = isset($post->BUKRS) && !empty($post->BUKRS) ? strtolower($post->BUKRS) : '';
        if(!$company_code){
            $request->session()->flash('error_detail_invoice', 'Unable to find Company Code, please try again');
            return view('pages.exception.blank_page');
            die;
        }

        switch ($company_code) {
            // Default is KMS, so dont need to be validated
            case 'kms': case 'kms1': case 'kms2': case 'kms3':
                $route = 'pages.sap.invoice.PRE_LIVE.invoice_payment_kms_sap';
                break;
            case 'njp': case 'njp0': case 'njp1':
                $route = 'pages.sap.invoice.PRE_LIVE.invoice_payment_njp_sap';
                break;
            case 'ppc' : case 'ppc0': case 'ppc1':
                $route = 'pages.sap.invoice.PRE_LIVE.invoice_payment_ppc_sap';
                break;
            case 'pad' : case 'pad0': case 'pad1': case 'pad2':
                $route = 'pages.sap.invoice.PRE_LIVE.invoice_payment_pad_sap';
                break;
            case 'pnb' : case 'pnb0': case 'pnb1':
                $route = 'pages.sap.invoice.PRE_LIVE.invoice_payment_pnb_sap';
                break;
            case 'wkk' : case 'wkk0': case 'wkk1': case 'wkk2':
                $route = 'pages.sap.invoice.PRE_LIVE.invoice_payment_wkk_sap';
                break;
            default:
                $request->session()->flash('error_detail_invoice', 'Unable to find Company Code, please try again');
                $route = 'pages.exception.blank_page';
                break;
        }
        
        $cek_status_prod = config('payment.is_production');
        $data['invoice'] = json_decode(json_encode($new_post));
        $cek_invoice_status = DB::connection('dbintranet')
        ->table('INT_MAP_PAYMENT_INVOICE_QAS')
        ->where('CAANO', $data['invoice']->CAANO)
        ->get();

        $order_id = $cek_invoice_status->groupBy('CAANO')->toArray();
        $any_payment = [];

        $plant_code = isset($post->PRCTR) && !empty($post->PRCTR) ? strtolower($post->PRCTR) : '';
        if(!$plant_code){
            $request->session()->flash('error_detail_invoice', 'Unable to read plant code, please make sure the data sent by SAP is correct');
            return view('pages.exception.blank_page');
            die;
        }            

        /* Cek Payment Status */
        // if($order_id){
        //     switch ($plant_code) {
        //         case 'kms1':
        //             if(!$cek_status_prod)
        //                 $server_key = config('payment.serverkey.sandbox.kms1');
        //             else
        //                 $server_key = config('payment.serverkey.prod.kms1');
        //         break;
        //         case 'njp1':
        //             if(!$cek_status_prod)
        //                 $server_key = config('payment.serverkey.sandbox.njp1');
        //             else
        //                 $server_key = config('payment.serverkey.prod.njp1');
        //             break;
        //         default:
        //             $server_key = null;
        //             break;
        //     }

        //     if(!$server_key){
        //         $request->session()->flash('error_detail_invoice', 'Unable to retrive payment status on midtrans due to lack of or missing configuration');
        //         return view('pages.exception.blank_page');
        //         die;
        //     }
        //     foreach ($order_id as $key => $value) {
        //         // Cek status dengan sistem pembayaran selain CC
        //         $payload = json_encode( array( "Caano" => $key ) );
        //         $param=urlencode($payload);
        //         $OTH_PAYMENT = config('payment.sap_url.cc_notif_pre_live').''.$param;
        //         $ch = curl_init();
        //         curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        //         curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //         curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //         $server_output = curl_exec($ch);
        //         curl_close ($ch);

        //         $post=json_decode($server_output);
        //         if(isset($post->Statuscode) && $post->Statuscode == '200')
        //             array_push($any_payment, strtolower($post->Transactionstatus));

        //         // Cek Pembayaran CC dengan midtrans API
        //         foreach ($value as $obj) {
        //             $CC_PAYMENT = 'https://api.sandbox.midtrans.com/v2/'.$obj->ORDER_ID.'/status';
        //             $token = base64_encode($server_key);
        //             $headers = array(
        //                "Authorization: Basic ${token}",
        //             );

        //             $ch = curl_init();
        //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        //             curl_setopt($ch, CURLOPT_URL,$CC_PAYMENT);
        //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //             $server_output = curl_exec($ch);
        //             curl_close ($ch);
        //             $post=json_decode($server_output);
        //             if(isset($post->status_code) && $post->status_code == '200')
        //                 array_push($any_payment, strtolower($post->transaction_status));
        //         }
        //     }
        // }
        // else {
        //     // Cek status dengan sistem pembayaran selain CC
        //     $payload = json_encode( array( "Caano" => $no_invoice ) );
        //     $param=urlencode($payload);
        //     $OTH_PAYMENT = config('payment.sap_url.cc_notif_qas').''.$param;
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        //     curl_setopt($ch, CURLOPT_URL,$OTH_PAYMENT);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //     $server_output = curl_exec($ch);
        //     curl_close ($ch);

        //     $post=json_decode($server_output);
        //     if(isset($post->Statuscode) && $post->Statuscode == '200')
        //         array_push($any_payment, strtolower($post->Transactionstatus));
        // }

        $any_payment = $this->cmp(array_unique($any_payment));
        // $status_payment = '
        // <div class="pt-5 pb-1 text-center">
        //     <h3><i class="fa fa-clock text-default"></i>&nbsp; Waiting for transaction to be created.</h3>
        // </div>';

        $status_payment = '
        <div class="pt-3 pb-1 text-center">
        </div>';

        foreach($any_payment as $any_payment){
            switch ($any_payment) {
                case 'pending':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-clock text-default"></i>&nbsp; Transaction within this invoice is created and available / waiting to be paid.</h3>
                    </div>';
                    break;
                case 'capture':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-info-circle text-info"></i>&nbsp; Transaction is successful and credit card balance is captured successfully.</h3>
                        <h6 class="text-muted"> If no action taken any further, transaction will be settled on the next day.</h6>
                    </div>';
                    break;
                case 'settlement': case 'settle':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-check-circle text-success"></i>&nbsp; Transaction is successfully settled. Funds have been received.</h3>
                    </div>';
                    break;
                case 'cancel':
                    $status_payment = '
                    <div class="pt-5 pb-1 text-center">
                        <h3><i class="fa fa-window-close text-danger"></i>&nbsp; Transaction within this invoice is cancelled.</h3>
                    </div>';
                    break;
                default:
                    break;
            }
        }

        // Cek data Plant dari database (Seperti alamat dan email)
        $plant_info = [];
        try{
            $plant = $data['invoice']->PRCTR;
            $plant_info = DB::connection('dbintranet')
            ->table('INT_BUSINESS_PLANT')
            ->where('SAP_PLANT_ID', $plant)
            ->get()->first();

        } catch(\Exception $e){}
        return view($route, ['data'=>$data, 'status_payment'=>$status_payment, 'plant_info'=>$plant_info]);
    }

}
