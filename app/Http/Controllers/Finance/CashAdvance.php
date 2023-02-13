<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\HumanResource\CompanyModel;
use App\Models\Zoho\ZohoFormModel;
Use Cookie;
use DataTables;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;

class CashAdvance extends Controller{

    public function __construct()
    {
        $this->form_number = "CA";
        $this->form_number_extended = "CA-EXT";
        $this->form_view="VIEW_FORM_REQUEST_CASH_ADVANCE";
        $this->form_view_extended="VIEW_FORM_REQUEST_CASH_ADVANCE_EXT";
        $this->approval_view="VIEW_APPROVAL_FORM_REQUEST_CASH_ADVANCE";
        $this->approval_view_extended="VIEW_APPROVAL_FORM_REQUEST_CASH_ADVANCE_EXT";
        $this->approval_view_link="/finance/cash-advance/approval";
        $this->link_request = "/finance/cash-advance/request";
        $this->treshold_approval_gm=1000000; //lebih dari ini maka perlu approval GM
    }


    // FUNGSI PARK CA, dipake di CA GC juga
    public function insert_ca_sap(Request $request, $array_data=NULL){
        try {

            if(empty($array_data)){
                $CA_number = !empty($request->input('ca_number')) ? $request->input('ca_number') : '';
                $company_code = !empty($request->input('company_code')) ? $request->input('company_code') : '';
                $plant = !empty($request->input('plant')) ? $request->input('plant') : '';
                $cajo_number = !empty($request->input('cajo_number')) ? $request->input('cajo_number') : '';
                $business_trans = !empty($request->input('business_trans')) ? $request->input('business_trans') : '';
                $currency = !empty($request->input('currency')) ? $request->input('currency') : '';
                $purpose = !empty($request->input('purpose')) ? $request->input('purpose') : '';
                $grand_total = !empty($request->input('grand_total')) ? $request->input('grand_total') : 0;
                $ca_category = !empty($request->input('ca_category')) ? $request->input('ca_category') : 'PO';
                $is_parked = !empty($request->input('is_parked')) ? $request->input('is_parked') : '0';
            }else{
                $CA_number = $array_data['ca_number'];
                $company_code = $array_data['company_code'];
                $plant = $array_data['plant'];
                $cajo_number = $array_data['cajo_number'];
                $business_trans = $array_data['business_trans'];
                $currency = $array_data['currency'];
                $purpose = $array_data['purpose'];
                $grand_total = $array_data['grand_total'];
                $ca_category = $array_data['ca_category'];
                $is_parked = '0';
            }

            $existing_ca_property = DB::connection('dbintranet')
            ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
            ->where('FORM_ID',$CA_number)
            ->get();
            $existing_ca_formdata = DB::connection('dbintranet')
            ->table('INT_FIN_APPR_RAW_DATA')
            ->where('UID',$CA_number)
            ->get();

            $existing_cash_journal = (isset($existing_ca_property[0]->CURRENT_CA_CASH_JOURNAL) ? $existing_ca_property[0]->CURRENT_CA_CASH_JOURNAL :  NULL);
            $existing_posting_number = (isset($existing_ca_property[0]->CURRENT_CA_NUMBER) ? $existing_ca_property[0]->CURRENT_CA_NUMBER :  NULL);
            $existing_year=date('Y',strtotime($existing_ca_formdata[0]->INSERT_DATE));



            if($CA_number && $company_code && $plant && $cajo_number && $business_trans && $currency && $purpose && $grand_total){
                $is_production = config('intranet.is_production');
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc_dev'));
                }
                $options = [
                    'rtrim'=>true,
                ];

                $grand_total_parse = (float) $grand_total;
                $param = array(
                    'I_HEADER'=>[
                        'BUKRS' => $company_code,
                        'BLDAT' => date('Ymd'),
                        'BUDAT' => date('Ymd'),
                        'XBLNR' => $CA_number,
                        'CAJO_NUMBER' => $cajo_number,
                        'ALLOC_NMBR' => '',
                        'TEXT1' => 'Cash Advance '.$CA_number. ' request from intranet',
                        'CURRENCY' => $currency
                    ],
                    'IT_ITEMS'=>[
                       [
                          'POSITION_NUMBER' => '001',
                          'TRANSACT_NUMBER' => $business_trans,
                          'P_PAYMENTS' => $grand_total_parse,
                          'POSITION_TEXT' => $purpose,
                          'PROFIT_CTR' => $plant
                       ]
                    ]
                );

                $function_type = $rfc->getFunction('Z_FAP_PETTY_CASH_HO');
                $call= $function_type->invoke($param, $options);
                if(isset($call['E_BELNR']) && !empty($call['E_BELNR'])){
                    // insert nomor CA ke tabel
                    DB::connection('dbintranet')
                    ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
                    ->where('FORM_ID',$CA_number)
                    ->update([
                        'STATUS_CATEGORY'=>$ca_category,
                        'CURRENT_CA_NUMBER'=>$call['E_BELNR'],
                        'CURRENT_CA_BUSINESS_TRANSACTION'=>$business_trans,
                        'CURRENT_CA_CASH_JOURNAL'=>$cajo_number,
                        'CURRENT_CA_POSTING_DATE'=>date('Y-m-d')
                    ]);

                    // jika ada flag is_parked == 1 , maka artinya harus menghapus existing CA yang sudah di park
                    if($is_parked=="1"){
                        $array_delete=[
                            'company_code'=>$company_code,
                            'cash_journal'=>$existing_cash_journal,
                            'posting_number'=>$existing_posting_number,
                            'year'=>$existing_year

                        ];
                        $delete=App::call('App\Http\Controllers\Finance\CashAdvanceGC@delete_parked_ca', ['data'=> $array_delete]);
                    }

                    return response()->json(['status_code'=>200, 'status'=>'success', 'message'=>'Success insert Cash Advance. Document Number : '.$call['E_BELNR']]);
                }
                else {
                    $return_message_sap = (
                        (isset($call['GT_RETURN'][1]['MESSAGE'])) ? $call['GT_RETURN'][ 1]['MESSAGE'] :
                        ((isset($call['GT_RETURN'][0]['MESSAGE'])) ? $call['GT_RETURN'][0]['MESSAGE'] :
                        'Something went wrong when trying to get error message')
                    );
                    return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$return_message_sap]);
                }
            } else {
                return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>'Required fields are [Cash Advance Number, Company Code, Plant, Cash Journal Number, Business Transaction Number, Currency, Cash Advance Purpose, Cash Advance Grand Total]. Please make sure to provide all requred fields']);
            }
        } catch(SapException $e){
            return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$e->getMessage()]);
        } catch(\Exception $e){
            return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$e->getMessage()]);
        }
    }

    // POST CA
    public function post_ca_sap(Request $request){
        try {


            $CA_number = !empty($request->input('ca_number')) ? $request->input('ca_number') : '';
            $company_code = !empty($request->input('company_code')) ? $request->input('company_code') : '';
            $plant = !empty($request->input('plant')) ? $request->input('plant') : '';
            $cajo_number = !empty($request->input('cajo_number')) ? $request->input('cajo_number') : '';
            $business_trans = !empty($request->input('business_trans')) ? $request->input('business_trans') : '';
            $currency = !empty($request->input('currency')) ? $request->input('currency') : '';
            $purpose = !empty($request->input('purpose')) ? $request->input('purpose') : '';
            $grand_total = !empty($request->input('grand_total')) ? $request->input('grand_total') : 0;
            $ca_category = !empty($request->input('ca_category')) ? $request->input('ca_category') : 'PO';
            $is_parked = !empty($request    ->input('is_parked')) ? $request->input('is_parked') : '0';

            $grand_total_parse = (float) $grand_total;


            $existing_ca_property = DB::connection('dbintranet')
            ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
            ->where('FORM_ID',$CA_number)
            ->get();
            $existing_ca_formdata = DB::connection('dbintranet')
            ->table('INT_FIN_APPR_RAW_DATA')
            ->where('UID',$CA_number)
            ->get();

            $existing_ca_json=json_decode($existing_ca_formdata[0]->JSON_ENCODE);
            $existing_cash_journal = (isset($existing_ca_property[0]->CURRENT_CA_CASH_JOURNAL) ? $existing_ca_property[0]->CURRENT_CA_CASH_JOURNAL :  NULL);
            $existing_posting_number = (isset($existing_ca_property[0]->CURRENT_CA_NUMBER) ? $existing_ca_property[0]->CURRENT_CA_NUMBER :  NULL);
            $existing_year=date('Y',strtotime($existing_ca_property[0]->CURRENT_CA_POSTING_DATE));
            $existing_posting_date=date('Ymd',strtotime($existing_ca_property[0]->CURRENT_CA_POSTING_DATE));
            $existing_business_trans = (isset($existing_ca_property[0]->CURRENT_CA_BUSINESS_TRANSACTION) ? $existing_ca_property[0]->CURRENT_CA_BUSINESS_TRANSACTION :  NULL);

            $ca_currency=$existing_ca_json->currency[0];


            if($CA_number && $company_code && $plant && $cajo_number && $business_trans && $currency && $purpose && $grand_total){
                $is_production = config('intranet.is_production');
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc_dev'));
                }
                $options = [
                    'rtrim'=>true,
                ];

                $param = array(
                    'P_COMPANY'=>strtoupper($company_code)
                );

                $transact_name= $gl_account = '';
                $function_type = $rfc->getFunction('ZFM_FI_CASH_JURNAL');
                $call= $function_type->invoke($param, $options);
                if(isset($call['IT_DATA']) && count($call['IT_DATA'])){
                    $business_list=collect($call['IT_DATA'])->filter(function ($item) use (&$transact_name, &$gl_account, $existing_business_trans){
                        if($item['TRANSACT_NUMBER'] == $existing_business_trans){
                            $transact_name=$item['TRANSACT_NAME'];
                            $gl_account=$item['GL_ACCOUNT'];
                        }
                    });
                }


                $param = array(
                    'I_COMP_CODE'=>$company_code,
                    'I_CAJO_NUMBER'=>$cajo_number,
                    'I_CURRENCY'=>$ca_currency,
                    'ITCJ_POSTINGS'=>[
                        [
                            'CAJO_NUMBER'=>$cajo_number,
                            'COMP_CODE'=>$company_code,
                            'FISC_YEAR'=>$existing_year,
                            'POSTING_NUMBER'=>$existing_posting_number,
                            'H_PAYMENTS'=>(int)$grand_total,
                            'H_NET_AMOUNT'=>(int)$grand_total,
                            'DOCUMENT_DATE'=>$existing_posting_date,
                            'DOCUMENT_NUMBER'=>$CA_number,
                            'POSTING_DATE'=>date('Ymd'),
                            'DOCUMENT_STATUS'=>'S',
                            'ACCOUNTANT'=>'SPN_SOLT3',
                            'TRANSACT_NAME'=>$transact_name,
                            'KOSTL'=>'',
                            'GL_ACCOUNT'=>$gl_account
                        ]
                    ]
                );

                $function_type = $rfc->getFunction('ZFM_FI_FCJ_POST_ALL');
                $call= $function_type->invoke($param, $options);
                if( isset($call['ITCJ_POSTINGS']) && count(['ITCJ_POSTINGS']) > 0 ) {

                    DB::connection('dbintranet')
                    ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
                    ->where('FORM_ID',$CA_number)
                    ->update([
                        'STATUS_POSTING'=>'YES',
                        'POSTING_DATE'=>date('Y-m-d H:i:s')
                    ]);
                    return response()->json(['status_code'=>200, 'status'=>'success', 'message'=>'Success Posting Cash Advance.']);
                }else{
                   $message='Something went wrong when posting Cash Advance';
                    return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$message]);
                }

            } else {
                return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>'Required fields are [Cash Advance Number, Company Code, Plant, Cash Journal Number, Business Transaction Number, Currency, Cash Advance Purpose, Cash Advance Grand Total]. Please make sure to provide all requred fields']);
            }
        } catch(SAPFunctionException $e){
            return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$e->getMessage()]);
        } catch(\Exception $e){
            return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$e->getMessage()]);
        }
    }

    // CASH JOURNAL LIST
    public function cajo_list(Request $request){
        try {
            $company_code = !empty($request->input('company_code')) ? $request->input('company_code') : '';
            $currency = !empty($request->input('currency')) ? $request->input('currency') : 'IDR';
            if(!$company_code)
                return response()->json(['status_code'=>422, 'status'=>'error', 'message'=>'Please provide Company Code!']);
            // TES RFC CA
            $is_production = config('intranet.is_production');
            if($is_production){
                $rfc = new SapConnection(config('intranet.rfc_prod'));
            }else{
                $rfc = new SapConnection(config('intranet.rfc_dev'));
            }
            $options = [
                'rtrim'=>true,
            ];
            $param = array(
                'P_COMPANY'=>strtoupper($company_code)
            );

            $function_type = $rfc->getFunction('ZFM_MD_CAJO_LIST');
            $call= $function_type->invoke($param, $options);

            if(isset($call['IT_DATA']) && count($call['IT_DATA'])){
                $cajo = collect($call['IT_DATA'])->filter(function ($value, $key) use ($currency) {
                    return $value['CURRENCY']==$currency;
                });
                return response()->json(['status_code'=>200, 'status'=>'success', 'message'=>'Success fetching data Cash Journal', 'data'=>$cajo]);
            } else {
                return response()->json(['status_code'=>200, 'status'=>'success', 'message'=>'No Cash Journal data available', 'data'=>[]]);
            }
        } catch(SapException $e){
            return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$e->getMessage()]);
        } catch(\Exception $e){
            return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$e->getMessage()]);
        }

    }

    // BUSINESS_TRANSACTION LIST
    public function business_trans_list(Request $request){
        try {
            $company_code = !empty($request->input('company_code')) ? $request->input('company_code') : '';
            if(!$company_code)
                return response()->json(['status_code'=>422, 'status'=>'error', 'message'=>'Please provide Company Code!']);

            // TES RFC CA
            $is_production = config('intranet.is_production');
            if($is_production){
                $rfc = new SapConnection(config('intranet.rfc_prod'));
            }else{
                $rfc = new SapConnection(config('intranet.rfc_dev'));
            }
            $options = [
                'rtrim'=>true,
            ];
            $param = array(
                'P_COMPANY'=>strtoupper($company_code)
            );

            $function_type = $rfc->getFunction('ZFM_FI_CASH_JURNAL');
            $call= $function_type->invoke($param, $options);
            if(isset($call['IT_DATA']) && count($call['IT_DATA'])){
                return response()->json(['status_code'=>200, 'status'=>'success', 'message'=>'Success fetching data Cash Journal', 'data'=>$call['IT_DATA']]);
            } else {
                return response()->json(['status_code'=>200, 'status'=>'success', 'message'=>'No Cash Journal data available', 'data'=>[]]);
            }
        } catch(SapException $e){
            return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$e->getMessage()]);
        } catch(\Exception $e){
            return response()->json(['status_code'=>400, 'status'=>'error', 'message'=>$e->getMessage()]);
        }
    }

    public function request(Request $request){

        //init RFC
        // $is_production = config('intranet.is_production');
        // if($is_production){
        //     $rfc = new SapConnection(config('intranet.rfc_prod'));
        // }else{
        //     $rfc = new SapConnection(config('intranet.rfc'));
        // }
        // $options = [
        //     'rtrim'=>true,
        // ];
        // //===

        $employee_id=Session::get('user_id');

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";
            $midjob_id ="SYSADMIN";


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
            $midjob_id =Session::get('assignment')[0]->MIDJOB_TITLE_ID;
        }

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        // START -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST
        $cek_alur_approval=DB::connection('dbintranet')
        ->table('INT_FORM_APPROVAL_MAPPING')
        ->where('FORMAPPROVAL_REQUESTOR_COSTCENTER', $cost_center_id)
        ->where('FORMAPPROVAL_REQUESTOR_MIDJOB', $midjob_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'CA')   
        ->orWhere('FORMAPPROVAL_REQUESTOR_EMPLOYEE_ID',$employee_id)
        ->get();
        $itung_alur_approval=count($cek_alur_approval);
        $allow_add_request=($itung_alur_approval>0)? true : false;
        
        //memberikan plant default untuk user
        // $allowed_plant=[];

        $list_cost_center=DB::connection('dbintranet')
            ->select("SELECT * FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID = '$cost_center_id'");


        //mencari plant yang diassign untuk user tersebut selain plant default
        $list_plant_sap=DB::connection('dbintranet')
        ->table('dbo.INT_BUSINESS_PLANT as p')
        ->leftJoin('dbo.SAP_PR_EMP_COSTCENTER_MAPPING as map','map.PLANT_ID','=','p.SAP_PLANT_ID')
        ->select('p.SAP_PLANT_ID','p.SAP_PLANT_NAME')
        ->where('EMPLOYEE_ID',$employee_id)
        ->orWhere('p.SAP_PLANT_ID',$plant)
        ->orderBy('SAP_PLANT_ID','ASC')
        ->distinct()->get()->toArray();

        $list_plant=$list_plant_sap;

        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;
        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        $data=array(
            'company_code'=>$company_code,
            'plant'=>$plant,
            'plant_name'=>$plant_name,
            'employee_id'=>$employee_id,
            'employee_name'=>$employee_name,
            'division'=>$division,
            'department'=>$department,
            'cost_center_id'=>$cost_center_id,
            'status'=>$status,
            'territory_id'=>$territory_id,
            'territory_name'=>$territory_name,
            'job_title'=>$job_title,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
            'allow_add_request'=>$allow_add_request,
            'form_code'=>$this->form_number,
            'list_plant'=>$list_plant,
            'list_cost_center'=>$list_cost_center
        );
        return view('pages.finance.cash-advance.request', ['data' => $data]);
    }

    public function request_getData(Request $request){
        try{
            $employee_id=$request->input('employee_id');
            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $status=strtoupper($request->input('status'));
            $request_type=strtoupper($request->input('request_type'));

            if ($request_type == 'REPORT'){
                $where = "ID <> '' ";
            }else{
                $where = "REQUESTOR_ID = '".$employee_id."' ";
            }

            if (($filter != null or $filter !="")&&($value != null or $value !="")){
                if ($filter == "All"){
                    $where = $where." and (SUBJECT like '%".$value."%'";
                    $where = $where." or REQUESTOR_NAME like '%".$value."%')";
                }
                else
                {
                    $where = $where." and ".$filter." like '%".$value."%'";
                }
            }

            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                $where = $where." and INSERT_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
            }

            if ($status != null or $status !=""){
                $where = $where." and STATUS_APPROVAL = '".$status."'";
            }

            // DB::connection('dbintranet')->enableQueryLog();
            $data = DB::connection('dbintranet')
                    ->table("$this->form_view AS a")
                    ->leftJoin('dbo.INT_FORM_CASH_ADVANCE_PROPERTIES AS b', 'a.UID', 'b.FORM_ID')
                    ->whereraw(DB::raw($where))->get();
            // dd(DB::getQueryLog());




            $result=array();
            foreach($data as $key=>$value){
                $data_json=json_decode($value->JSON_ENCODE);
                $extended_uid=isset($value->APPROVAL_EXTENDED_NUMBER) ? $value->APPROVAL_EXTENDED_NUMBER : '';

                $check_extended_ca = DB::connection('dbintranet')
                ->table($this->form_view_extended)
                ->where('UID', $extended_uid)
                ->select('APPROVAL_LEVEL','LAST_APPROVAL_DATE', 'LAST_APPROVAL_ID', 'LAST_APPROVAL_NAME', 'STATUS_APPROVAL', 'UID')
                ->get()->first();
                if($check_extended_ca)
                    $check_extended_ca = $check_extended_ca;
                else
                    $check_extended_ca = [];

                $result[]=array(
                    'UID'=>$value->UID,
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'INSERT_DATE'=>$value->INSERT_DATE,
                    'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                    'SAP_PLANT_NAME'=>$value->SAP_PLANT_NAME,
                    'DEPARTMENT_NAME'=>$value->DEPARTMENT_NAME,
                    'JSON_ENCODE'=>$data_json,
                    'REASON'=>$value->REASON,
                    'EXTENTED_CA'=>$check_extended_ca
                );
            }
            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function approval(Request $request){

        //init RFC
        $rfc = new SapConnection(config('intranet.rfc'));
        $options = [
            'rtrim'=>true,
        ];
        //===



        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        if(empty(Session::get('assignment')[0])){
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $division="SYSADMIN";
            $department="SYSADMIN";
            $department_id="";
            $midjob_id="";
            $costcenter="";
        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $department_id=Session::get('assignment')[0]->DEPARTMENT_ID;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $midjob_id=Session::get('assignment')[0]->MIDJOB_TITLE_ID;
            $costcenter=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
        }

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;


        $data=array(
            'company_code'=>$company_code,
            'plant'=>$plant,
            'employee_id'=>$employee_id,
            'employee_name'=>$employee_name,
            'division'=>$division,
            'department_id'=>$department_id,
            'department'=>$department,
            'midjob_id'=>$midjob_id,
            'costcenter'=>$costcenter,
            'status'=>$status,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
            'form_code'=>$this->form_number,
        );


        return view('pages.finance.cash-advance.approval', ['data' => $data]);
    }

    public function approval_getData(Request $request)
    {

        try{
            DB::enableQueryLog();
            $deptId=$request->input('deptId');
            $employeeId=$request->input('employeeId');
            $midjobId=$request->input('midjobId');
            $costcenter=$request->input('costcenter');
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID; //dapetin plant user yang login skrg
            $territory=Session::get('assignment')[0]->TERRITORY_ID; //dapetin plant user yang login skrg


            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');


            $where="";
            // start looping approval untuk form normal
            for ($i=0; $i<=7;$i++){
                $j=$i+1; // init variable untuk level approval sebenernya
                $prepend="";
                $append="";
                if($i>0){
                    $prepend="OR (";
                    $append=")";
                }
                // query filter untuk approval
                $where .=$prepend." APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                AND STATUS_APPROVAL <> 'REJECTED' AND  APPROVAL_".$j."_TERRITORY_ID =
                CASE WHEN (
                    SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->form_view."
                    where APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."'
                    AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                ) = '0' THEN '0'
                WHEN (
                    SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->form_view."
                    where APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."'
                    AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                ) <> '0' THEN '".$territory."'
                END
                OR APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                AND APPROVAL_LEVEL = ".$i." AND STATUS_APPROVAL <> 'REJECTED'
                ".$append."
                ";
            }

            $whereExt="";
            // start looping approval untuk form normal
            for ($i=0; $i<=7;$i++){
                $j=$i+1; // init variable untuk level approval sebenernya
                $prepend="";
                $append="";
                if($i>0){
                    $prepend="OR (";
                    $append=")";
                }
                // query filter untuk approval
                $whereExt .=$prepend." APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                AND STATUS_APPROVAL <> 'REJECTED' AND  APPROVAL_".$j."_TERRITORY_ID =
                CASE WHEN (
                    SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->form_view_extended."
                    where APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."'
                    AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                ) = '0' THEN '0'
                WHEN (
                    SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->form_view_extended."
                    where APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."'
                    AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                ) <> '0' THEN '".$territory."'
                END
                OR APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                AND APPROVAL_LEVEL = ".$i." AND STATUS_APPROVAL <> 'REJECTED'
                ".$append."
                ";
            }

            //=============================================================

            if (($filter != null or $filter !="")&&($value != null or $value !="")){
                if ($filter == "All"){
                    $where = $where." and (SUBJECT like '%".$value."%'";
                    $where = $where." or REQUESTOR_NAME like '%".$value."%')";

                    $whereExt = $whereExt." and (SUBJECT like '%".$value."%'";
                    $whereExt = $whereExt." or REQUESTOR_NAME like '%".$value."%')";
                }
                else
                {
                    $where = $where." and ".$filter." like '%".$value."%'";
                    $whereExt = $whereExt." and ".$filter." like '%".$value."%'";
                }
            }

            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                $where = $where." and INSERT_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                $whereExt = $whereExt." and INSERT_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
            }



            $data=DB::connection('dbintranet')
                ->select("
                SELECT * FROM
                (
                    SELECT * FROM ".$this->approval_view."
                    WHERE ".$where."
                ) AS DATA_FULL
                INNER JOIN
                (
                    SELECT UID AS SECOND_UID,
                    CASE WHEN GRAND_TOTAL > LIMIT_AMOUNT_TO_EXTEND_APPROVAL THEN MAXIMUM_APPROVAL_LEVEL
                    ELSE MINIMUM_APPROVAL_LEVEL
                    END AS MAX_APPROVAL_LEVEL
                    FROM(
                        SELECT source1.*, cfg.LIMIT_AMOUNT_TO_EXTEND_APPROVAL, cfg.MINIMUM_APPROVAL_LEVEL, cfg.MAXIMUM_APPROVAL_LEVEL FROM (
                            SELECT
                            V.UID,
                            JSON_VALUE(CAST(V.JSON_ENCODE AS nvarchar(max)), '$.grandTotal') AS GRAND_TOTAL,
                            JSON_VALUE(CAST(V.JSON_ENCODE AS nvarchar(max)), '$.Requestor_Plant_ID') AS PLANT_ID
                            FROM ".$this->approval_view." V
                        )source1
                        INNER JOIN CONFIG_FORM_CASH_ADVANCE_PER_PLANT cfg ON source1.PLANT_ID = cfg.PLANT_ID
                    )dt

                ) DATA_SECOND
                ON DATA_FULL.UID = DATA_SECOND.SECOND_UID
                WHERE APPROVAL_LEVEL < MAX_APPROVAL_LEVEL
                ");

            $dataExt=DB::connection('dbintranet')
            ->select("
            SELECT * FROM
            (
                SELECT * FROM ".$this->approval_view_extended."
                WHERE ".$whereExt."
            ) AS DATA_FULL
            INNER JOIN
            (
                SELECT UID AS SECOND_UID,
                CASE WHEN GRAND_TOTAL > FINAL_SETTLEMENT_AMOUNT_TRESHOLD THEN FINAL_SETTLEMENT_MAXIMUM_APPROVAL_LEVEL
                ELSE FINAL_SETTLEMENT_MINIMUM_APPROVAL_LEVEL
                END AS MAX_APPROVAL_LEVEL
                FROM(
                    SELECT source1.*, cfg.FINAL_SETTLEMENT_AMOUNT_TRESHOLD, cfg.FINAL_SETTLEMENT_MINIMUM_APPROVAL_LEVEL, cfg.FINAL_SETTLEMENT_MAXIMUM_APPROVAL_LEVEL FROM (
                        SELECT
                        V.UID,
                        JSON_VALUE(CAST(V.JSON_ENCODE AS nvarchar(max)), '$.final_under') AS GRAND_TOTAL,
                        JSON_VALUE(CAST(V.JSON_ENCODE AS nvarchar(max)), '$.Requestor_Plant_ID') AS PLANT_ID
                        FROM ".$this->approval_view_extended." V
                    )source1
                    INNER JOIN CONFIG_FORM_CASH_ADVANCE_PER_PLANT cfg ON source1.PLANT_ID = cfg.PLANT_ID
                )dt

            ) DATA_SECOND
            ON DATA_FULL.UID = DATA_SECOND.SECOND_UID
            WHERE APPROVAL_LEVEL < MAX_APPROVAL_LEVEL
            ");

            $combine_data=array_merge($data,$dataExt);

            $result=array();
            foreach($combine_data as $key=>$value){
                $data_json=json_decode($value->JSON_ENCODE);
                $result[]=array(
                    'UID'=>$value->UID,
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'INSERT_DATE'=>$value->INSERT_DATE,
                    'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                    'SAP_PLANT_NAME'=>$value->SAP_PLANT_NAME,
                    'DEPARTMENT_NAME'=>$value->DEPARTMENT_NAME,
                    'JSON_ENCODE'=>$data_json,
                    'REQUESTOR_NAME' =>$value->REQUESTOR_NAME,
                    'DIVISION_NAME' =>$value->DIVISION_NAME,
                    'APPROVAL_LEVEL'=>$value->APPROVAL_LEVEL
                );
            }

            return DataTables::of($result)->make(true);

        } catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }


    public function getHistoryApproval(Request $request){
        $rest = new ZohoFormModel();
        $result = $rest->getHistoryApprovalCA($request, $this->form_view);
        return response($result);
    }

    public function getHistoryApprovalExtended(Request $request){
        $rest = new ZohoFormModel();
        $form_id = $request->input('form_number', 0);
        $result = $rest->getHistoryApprovalCAExtended($form_id, $this->form_view_extended);
        return response($result);
    }

    public function save(Request $request){
        // =============
        // cari data sequence dari FORM
        try{
            $type_form=$this->form_number;
            $year = date('Y');
            $last_seq=DB::connection('dbintranet')
                        ->select("SELECT TOP 1 CASE WHEN UID IS NULL THEN NULL ELSE UID END AS LAST_SEQ FROM INT_FIN_APPR_RAW_DATA WHERE TYPE_FORM ='$type_form'  ORDER BY ID DESC ");
            if(!empty($last_seq[0]->LAST_SEQ)){
                $explode_uid=explode('-',$last_seq[0]->LAST_SEQ);
                $nomor_akhir=(int)end($explode_uid);
            }else{
                $nomor_akhir=0;
            }


            $new_seq = $nomor_akhir + 1;
            $new_seq = sprintf("%010d", $new_seq);

            $uid=$type_form.'-'.$new_seq;

            $data=$request->post();
            unset($data['_token']);
            $data['uid']=$uid;


            // =============
            //kebutuhan insert data
            $data_json=json_encode($data);
            $employee_id=$data['Requestor_Employee_ID'];
            $type="Request Cash Advance";

            $insert=DB::connection('dbintranet')
            ->table('INT_FIN_APPR_RAW_DATA')
            ->insert(
                [
                    "JSON_ENCODE" => $data_json,
                    "TYPE" => $type,
                    "INSERT_DATE" => date('Y-m-d H:i:s'),
                    "UID" => $uid,
                    "EMPLOYEE_ID" => $employee_id,
                    "TYPE_FORM" => $type_form,
                ]
            );

            if($insert){

                $insert=DB::connection('dbintranet')
                ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
                ->insert(
                    [
                        "FORM_ID" => $uid
                    ]
                );

                $level_approval = 1;
                $notif_link=$this->approval_view_link;
                $notif_desc="Please approve Cash Advance Form : ".$uid."";
                $notif_type="info";

                $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);
            }
            $success=true;
            $code = 200;
            $msg = 'Your request has been sent';

        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));
    }

    public function submitApprovalForm(Request $request)
    {
        $FormID=imuneString($request->input('form_id'));
        $data = explode(";" , $FormID);
        $EmployeID=imuneString($request->input('employe_id'));
        $StatusApproval=imuneString($request->input('status_approval'));
        $TypeForm=imuneString($request->input('type_form'));
        $Reason=imuneString($request->input('reason'));


        $totalData=0;
        $success=0;
        $failed=0;


        foreach ($data as $key => $dataId) {

            $detail='';
            $dataDetail=explode("#",$dataId);
            $idform=$dataDetail[0];
            $formlevel=$dataDetail[1];

            $approvalLevel=$formlevel;
            $approvalLevel=$approvalLevel+1;


            $result = $this->approve($idform, $approvalLevel, $EmployeID, $StatusApproval,$TypeForm,$Reason);

            if ($result['code']==200){
                $success++;
            }else{
                $failed++;
            }

            $totalData++;
        }

        $hasil["Total_Data"]=$totalData;
        $hasil["Total_Success"]=$success;
        $hasil["Total_Failed"]=$failed;

        return $hasil;
    }


    public function approve($FormID,$ApprovalLevel,$LastApprovalID,$StatusApproval,$TypeForm,$Reason)
    {

        try {
            $connection = DB::connection('dbintranet');
            $connection->beginTransaction();

            $CountData=DB::connection('dbintranet')->table('INT_FIN_APPR_LIST')
                    ->where('FORM_ID',$FormID)
                    ->count();

            // cek dulu apakah form ini merupakan CA yang extended atau bukan
            if($this->IsExtendedCA($FormID)){
                // query untuk form yang EXTENDED e.g CA-00001-EXT
                $form_view = $this->form_view_extended;
                $form_approval = $this->approval_view_extended;
                $filter_amount = "$.final_under";
                $filter_max_level ='FINAL_SETTLEMENT_MAXIMUM_APPROVAL_LEVEL';
                $filter_min_level = 'FINAL_SETTLEMENT_MINIMUM_APPROVAL_LEVEL';
                $filter_treshold='FINAL_SETTLEMENT_AMOUNT_TRESHOLD';
            }else{
                // query untuk form yang normal e.g CA-00001
                $form_view = $this->form_view;
                $form_approval = $this->approval_view;
                $filter_amount = "$.grandTotal";
                $filter_max_level ='MAXIMUM_APPROVAL_LEVEL';
                $filter_min_level = 'MINIMUM_APPROVAL_LEVEL';
                $filter_treshold='LIMIT_AMOUNT_TO_EXTEND_APPROVAL';
            }


            // query untuk mendapatkan maksimum approval yang bisa didapatkan oleh form
            // dengan menggunakan filter amount dari form
            $query=DB::connection('dbintranet')
                ->select("SELECT UID,
                    CASE WHEN GRAND_TOTAL > ".$filter_treshold." THEN ".$filter_max_level."
                    ELSE ".$filter_min_level."
                    END AS MAX_APPROVAL_LEVEL
                    FROM(
                        SELECT source1.*, cfg.".$filter_treshold.", cfg.".$filter_min_level.", cfg.".$filter_max_level." FROM (
                            SELECT
                            V.UID,
                            JSON_VALUE(CAST(V.JSON_ENCODE AS nvarchar(max)), '".$filter_amount."') AS GRAND_TOTAL,
                            JSON_VALUE(CAST(V.JSON_ENCODE AS nvarchar(max)), '$.Requestor_Plant_ID') AS PLANT_ID
                            FROM $form_view V
                        )source1
                        INNER JOIN CONFIG_FORM_CASH_ADVANCE_PER_PLANT cfg ON source1.PLANT_ID = cfg.PLANT_ID
                    )dt
                    WHERE UID ='".$FormID."'
                ");

             // validasi jika form sudah sampai di tahap terakhir, maka status akan jadi finished
             // mengikuti hasil dari query di atas

            $max_approval=isset($query[0])? $query[0]->MAX_APPROVAL_LEVEL : 0;
            if($ApprovalLevel==$max_approval && $StatusApproval=="APPROVED"){
                $StatusApproval ='FINISHED';

                // Cek approval ext finished dan ubah status backup secara otomatis
                // Tanpa GC perlu klik save adjustment pada saat approval selesai
                if($this->IsExtendedCA($FormID)){
                    $form_ca = substr($FormID, 0, -4);
                    $UpdateEXT=DB::connection('dbintranet')
                    ->table('dbo.INT_FORM_CASH_ADVANCE_PROPERTIES')
                    ->where('FORM_ID',$form_ca)
                    ->update(
                        [
                            "STATUS_ADJUSTMENT_FIRST" => "YES",
                            "STATUS_BACKUP" => 'YES'
                        ]
                    );
                }

            }
            //======================

            if ($CountData == 0){ // jika blm ada di tabel list (dimana tabel list ini nyimpen progress dari approval)  makan akan create record dengan approval terakhir
                $SaveApproval=DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_LIST')
                            ->insert(
                                [
                                    "FORM_ID" => $FormID,
                                    "APPROVAL_LEVEL" => $ApprovalLevel,
                                    "LAST_APPROVAL_ID" => $LastApprovalID,
                                    "LAST_APPROVAL_DATE" => DB::raw("GETDATE()"),
                                    "STATUS_APPROVAL" => $StatusApproval,
                                    "TYPE_FORM" => $TypeForm,
                                    "REASON" => $Reason
                                ]
                            );
            }else{ // jika sudah ada di tabel list (dimana tabel list ini nyimpen progress dari approval) maka akan update datanya dengan approval terakhir
                $UpdateApproval=DB::connection('dbintranet')
                                ->table('INT_FIN_APPR_LIST')
                                ->where('FORM_ID',$FormID)
                                ->update(
                                    [
                                        "APPROVAL_LEVEL" => $ApprovalLevel,
                                        "LAST_APPROVAL_ID" => $LastApprovalID,
                                        "LAST_APPROVAL_DATE" => DB::raw("GETDATE()"),
                                        "STATUS_APPROVAL" => $StatusApproval,
                                        "TYPE_FORM" => $TypeForm,
                                        "REASON" => $Reason
                                    ]
                                );
            }

            $CountDataHistory=DB::connection('dbintranet')
                                ->table('INT_FIN_APPR_HISTORY')
                                ->where('FORM_ID',$FormID)
                                ->where('APPROVAL_LEVEL',$ApprovalLevel)
                                ->count();

            if ($CountDataHistory == 0){ //jika belum ada data history
                $InsertLog=DB::connection('dbintranet')
                        ->table('INT_FIN_APPR_HISTORY')
                        ->insert(
                            [
                                "FORM_ID" => $FormID,
                                "APPROVAL_ID" => $LastApprovalID,
                                "APPROVAL_DATE" => DB::raw("GETDATE()"),
                                "STATUS_APPROVAL" => $StatusApproval,
                                "APPROVAL_LEVEL" => $ApprovalLevel,
                                "TYPE_FORM" => $TypeForm,
                                "REASON" => $Reason
                            ]
                        );
            }else{ // update data approval history
                $UpdateLog=DB::connection('dbintranet')
                        ->table('INT_FIN_APPR_HISTORY')
                        ->where('FORM_ID',$FormID)
                        ->where('APPROVAL_LEVEL',$ApprovalLevel)
                        ->update(
                            [
                                "APPROVAL_LEVEL" => $ApprovalLevel,
                                "APPROVAL_ID" => $LastApprovalID,
                                "APPROVAL_DATE" => DB::raw("GETDATE()"),
                                "STATUS_APPROVAL" => $StatusApproval,
                                "TYPE_FORM" => $TypeForm,
                                "REASON" => $Reason
                            ]
                        );
            }


            $uid = $FormID;

            //insert ke notifikasi untuk approver
            if($StatusApproval!=="FINISHED" && $StatusApproval=="APPROVED"){
                // jika belum finish, maka kirim notif ke approver
                $level_approval = $ApprovalLevel+1;
                $notif_desc = "Please approve Cash Advance Form : ".$FormID."";
                $notif_type="info";
                $notif_link=$this->approval_view_link;
                $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);

            }else if($StatusApproval=="REJECTED"){
                //jika reject, maka kirim notif ke requestor
                $data_approval = DB::connection('dbintranet')
                ->table(DB::raw($form_approval))
                ->where('UID',$uid)
                ->get();

                $notif_employee_id=$data_approval[0]->REQUESTOR_ID;
                $notif_link=$this->link_request;
                $notif_desc="Your request Cash Advance : ".$uid." is rejected";
                $notif_type="reject";

                $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type);
            }
            if($StatusApproval=="FINISHED"){
                //jika sudah finish, maka kirim notif ke requestor
                $data_approval = DB::connection('dbintranet')
                ->table(DB::raw($form_approval))
                ->where('UID',$uid)
                ->get();

                $notif_employee_id=$data_approval[0]->REQUESTOR_ID;
                $notif_link=$this->link_request;
                $notif_desc="Your request Cash Advance : ".$uid." is approved";
                $notif_type="approve";

                $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type);

            }


            $data['code'] = 200;
            $data['message'] = 'Success';
            $connection->commit();
        }
        catch(QueryException $e) {
            $data['code'] = 401;
            $data['message'] = $e->errorInfo;
            $connection->rollback();
        }
        return $data;
    }

    public function insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type){

        if($this->IsExtendedCA($uid)){
            $approval_view = $this->approval_view_extended;
        }else{
            $approval_view = $this->approval_view;
        }

        $data_approval = DB::connection('dbintranet')
            ->table(DB::raw($approval_view))
            ->where('UID',$uid)
            ->get();

        if(!$data_approval->isEmpty()){
            $data_approval=collect($data_approval[0])->toArray();

            $i = $level_approval; // mencari approval selanjutnya untuk diberikan notif
            $appr_midjob=($data_approval['APPROVAL_'.$i.'_MIDJOB_ID']) ? $data_approval['APPROVAL_'.$i.'_MIDJOB_ID'] : NULL;
            $appr_employeeId=($data_approval['APPROVAL_'.$i.'_EMPLOYEE_ID']) ? $data_approval['APPROVAL_'.$i.'_EMPLOYEE_ID'] : NULL;

            // cek dulu apakah di tabel approval mapping untuk tingkatan itu ada Employee ID
            // karena employee ID akan override dari semua field lain, maka dahulukan dulu cek employee ID pada tingkatan tersebut


            if(!empty($appr_employeeId)){
                $select = "SELECT EMPLOYEE_ID FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$appr_employeeId."' ";
            }else if(!empty($appr_midjob)){
                $select = "SELECT EMPLOYEE_ID FROM VIEW_EMPLOYEE WHERE MIDJOB_TITLE_ID ='".$appr_midjob."' ";
                $appr_plant=($data_approval['APPROVAL_'.$i.'_PLANT_ID']) ? " AND SAP_PLANT_ID='".$data_approval['APPROVAL_'.$i.'_PLANT_ID']."'" : NULL;
                $appr_territory=($data_approval['APPROVAL_'.$i.'_TERRITORY_ID']) ? " AND TERRITORY_ID='".$data_approval['APPROVAL_'.$i.'_TERRITORY_ID']."'" : NULL;
                $select .= $appr_plant.$appr_territory;
            }

            if(!empty($select)){
                $emp_appr=DB::connection('dbintranet')
                ->select($select);
                foreach($emp_appr AS $notif_appr){
                    $notif_employee_id=$notif_appr->EMPLOYEE_ID;
                    $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type); // insert notif
                }
            }

        }
    }

    public function modal_detail(Request $request)
    {

        $uid=$request->input('id');
        $action=(!empty($request->input('action')))? $request->input('action') : 'view'; // flag action, gunanya adalah ketika di modal detail supaya bisa kasi validasi apakah harus kasi tombol approve & reject di modal atau tidak (tapi berbeda dengan modal approve detail, ini hanya untuk approval tanpa inputan apapun)
        $data_form=NULL;
        $data_json=NULL;
        $data_file=NULL;
        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
                    ->table($this->form_view)
                    ->where('UID',$uid)
                    ->get();;
            if(!isset($data_form[0])){
                $data_form=DB::connection('dbintranet')
                    ->table($this->form_view_extended)
                    ->where('UID',$uid)
                    ->get();
            }
            $data_json = json_decode($data_form[0]->JSON_ENCODE);

        }
        $data=array(
            'data_form'=>$data_form[0],
            'data_json'=>$data_json,
            'action'=>$action,
        );
        if(isset($data_form[0]->TYPE_FORM) && $data_form[0]->TYPE_FORM == 'CA-EXT')
            return View::make('pages.finance.cash-advance.modal-detail-ext')->with('data', $data)->render();
        return View::make('pages.finance.cash-advance.modal-detail')->with('data', $data)->render();
    }

    public function IsExtendedCA($form_id){
        if (strpos($form_id, 'EXT') !== false) {
            return true;
        }else{
            return false;
        }
    }
}

