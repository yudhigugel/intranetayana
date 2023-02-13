<?php

namespace App\Http\Controllers\Finance;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\HumanResource\CompanyModel;
use App\Models\Zoho\ZohoFormModel;
use Carbon\Carbon;
Use Cookie;
use DataTables;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use Log;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class PurchaseRequisition extends Controller{

    private $form_number;
    private $form_view;

    public function __construct()
    {
        $this->form_number = "PURCHASE-REQUISITION";
        $this->form_view="VIEW_FORM_REQUEST_PURCHASE_REQUISITION";
    }

    public function request(Request $request){
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

        //memberikan plant default untuk user
        $allowed_plant=[];

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

        // $allowed_plant=collect($allowed_plant)->values()->sort()->toArray();
        // $list_plant=array_values($allowed_plant); //simpan plant yang diperbolehkan


        // done cari plant default



        // start -- cari cost center yang diperbolehkan
        $cc_where="SAP_COST_CENTER_ID = '$cost_center_id'";
        $i=0;
        // foreach($allowed_plant AS $ap){
        //     // if($i<1){
        //     //     $cc_where.="SAP_COST_CENTER_NAME LIKE '".$ap."%'";
        //     // }else{
        //     //     $cc_where.=" OR SAP_COST_CENTER_NAME LIKE '".$ap."%'";
        //     // }

        //     $i++;
        // }


        DB::enableQueryLog();
        $check_multiple_cost_center = DB::connection('dbintranet')
        ->table('SAP_PR_EMP_COSTCENTER_MAPPING AS cm')
        ->join('INT_SAP_COST_CENTER AS cc', 'cm.SAP_COSTCENTER_ID', '=', 'cc.SAP_COST_CENTER_ID')
        ->where('cm.EMPLOYEE_ID', $employee_id)
        ->select('cm.EMPLOYEE_ID', 'cm.SAP_COSTCENTER_ID AS SAP_COST_CENTER_ID', 'cc.SAP_COST_CENTER_NAME', 'cc.DIVISION_NAME', 'cc.DEPARTMENT_NAME')
        ->get();

        // Jika cost center requestor ada di mapping (Lebih dari 1 cost center)
        if($check_multiple_cost_center->count() > 0){
            $list_cost_center = $check_multiple_cost_center;
        }
        else {
            $list_cost_center=DB::connection('dbintranet')
            ->select("SELECT * FROM INT_SAP_COST_CENTER WHERE $cc_where ");
        }

        // end -- cari cost center yang diperbolehkan
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;
        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= in_array('status', array_keys($request->all())) ? $request->input('status') : 'Waiting';

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
        'form_code'=>$this->form_number,
        'list_plant'=>$list_plant,
        'list_cost_center'=>$list_cost_center
        );


        return view('pages.finance.purchase-requisition.request', ['data' => $data]);
    }

    public function approval(Request $request){

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

        $param = array();
        $function_type = $rfc->getFunction('ZFM_MM_MD_MATERIAL_TYPE');
        $material_type= $function_type->invoke($param, $options)['GT_REPORT'];

        $function_group = $rfc->getFunction('ZFM_MATERIAL_GROUP');
        $material_group= $function_group->invoke($param, $options)['ZTA_T023'];

        $function_valuation = $rfc->getFunction('ZFM_V_MD_VALUATION_CLASS');
        $material_valuation= $function_valuation->invoke($param, $options)['GT_VALUATION'];

        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        if(count(Session::get('assignment')) ==  0) {
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $division="SYSADMIN";
            $department="SYSADMIN";
            $department_id="";
            $midjob_id="";
            $costcenter="";
            $releasecode="";
        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $department_id=Session::get('assignment')[0]->DEPARTMENT_ID;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $midjob_id=Session::get('assignment')[0]->MIDJOB_TITLE_ID;
            $costcenter=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $releasecode=Session::get('user_data')->RELEASE_CODE;
        }

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        $current_user_release_code= isset(Session::get('assignment')[0]->RELEASE_CODE) ? Session::get('assignment')[0]->RELEASE_CODE : '';

        // START -- CARI DATA APPROVAL UNTUK DATATABLE
        $approver_company_allowed = DB::connection('dbintranet')
        ->table('SAP_PR_RELEASE_GROUP_APPROVAL_MAPPING')
        ->select('RELEASE_GROUP_CODE')
        ->get()->pluck('RELEASE_GROUP_CODE')->toArray();

        $result=array();
        foreach ($approver_company_allowed as $key => $release_group_sap) {
            // END -- filter RELEASE GROUP MANUAL
            $current_loggedin_employee_id=Session::get('user_id');
            $cek_release_code_approver = DB::connection('dbintranet')
            ->table('SAP_PR_RELEASE_CODE_NEW')
            ->where('APP_EMPLOYEE_ID', $current_loggedin_employee_id)
            ->orWhere(function($query) use ($current_loggedin_employee_id){
                $query->where(['ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
            })
            ->select('RELEASE_CODE')
            ->distinct()->pluck('RELEASE_CODE')->toArray();

            foreach ($cek_release_code_approver as $key => $release_code_approver_mapping) {
                try {
                    $param = array(
                        'GV_REL_GROUP'=>$release_group_sap,
                        // 'GV_REL_CODE'=>"$releasecode"
                        'GV_REL_CODE'=>$release_code_approver_mapping

                    );

                    $function_type = $rfc->getFunction('ZFM_PR_GETITEMSREL_INTRA');
                    $data= $function_type->invoke($param, $options);



                    // END -- FUNCTION UNTUK CARI DATA APPROVAL KE SAP
                    foreach($data['GI_ITEMS'] as $key=>$value){
                        $data_requestor = [];
                        $uid=$value['PREQ_NO']; // nomor PR

                        // cari dulu apakah PR tersebut ada di DB WH (choice 1)
                        $cek_requestor_wh=DB::connection('dbintranet')
                            ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");


                        // jika ada requestor ada, maka akan diutamakan dulu untuk get data dari DB WH
                        if(!empty($cek_requestor_wh)){

                            $data_header=DB::connection('dbintranet')
                                        ->select("SELECT H.PRHEADER_ID, H.PRNUMBER, SUM(CAST(D.QUANTITY AS FLOAT)* CAST(D.AMOUNT AS INT)) AS GRAND_TOTAL, D.CURRENCY
                                        FROM TBL_PR_DETAIL D
                                        INNER JOIN TBL_PR_HEADER H ON H.PRHEADER_ID = D.PRREQUESTHEADER_ID
                                        WHERE H.PRNUMBER = '$uid'
                                        GROUP BY H.PRHEADER_ID, H.PRNUMBER, D.CURRENCY");

                            if(count($data_header)>0){
                                $data_requestor=DB::connection('dbintranet')
                                ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");
                                $pr_cost_center = isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '';
                                $pr_midjob = isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '';
                                $pr_requestor_id = isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '';
                                $pr_requestor_name = $data_requestor[0]->EMPLOYEE_NAME;
                                $pr_insert_date = $data_requestor[0]->INSERT_DATE;
                                $pr_currency = $data_header[0]->CURRENCY;
                                $pr_grandtotal = $data_header[0]->GRAND_TOTAL;
                                $data_json=json_decode($data_requestor[0]->JSON_ENCODE);
                                $purpose=(isset($data_json->purpose))? $data_json->purpose : '';

                            }


                        }else{
                        // jika tidak ada data requestor di DB WH, maka akan coba cari data di SAP untuk mendapatkan recordnya sesuai PR Number

                            $param = array(
                                'GV_NUMBER'=>$uid
                            );
                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $detail_pr= $function_type->invoke($param, $options);

                            $cost_center = (isset($detail_pr['GI_ITEMS'][0]['TRACKINGNO']) && !empty($detail_pr['GI_ITEMS'][0]['TRACKINGNO']))? $detail_pr['GI_ITEMS'][0]['TRACKINGNO'] : NULL;
                            $requestor_employee_id = (isset($detail_pr['GI_ITEMS'][0]['PREQ_ID']) && !empty($detail_pr['GI_ITEMS'][0]['PREQ_ID']))? $detail_pr['GI_ITEMS'][0]['PREQ_ID'] : NULL;


                            // jika requestor employee ada di SAP, maka bisa mengambil datanya
                            if(!empty($requestor_employee_id)){

                                $data_requestor=DB::connection('dbintranet')
                                ->select("SELECT SAP_COST_CENTER_ID, MIDJOB_TITLE_ID, EMPLOYEE_NAME FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID='".$requestor_employee_id."' ");

                                $pr_cost_center = $cost_center;
                                $pr_midjob = isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '';
                                $pr_requestor_id = $requestor_employee_id;
                                $pr_requestor_name = isset($data_requestor[0]->EMPLOYEE_NAME) ? $data_requestor[0]->EMPLOYEE_NAME : '' ;
                                $pr_insert_date = (isset($detail_pr['GI_ITEMS'][0]['PREQ_DATE']) && !empty($detail_pr['GI_ITEMS'][0]['PREQ_DATE'])) ? date('Y-m-d',strtotime($detail_pr['GI_ITEMS'][0]['PREQ_DATE'])) : NULL;

                                $pr_currency = isset($detail_pr['GI_ITEMS'][0]['CURRENCY']) ? $detail_pr['GI_ITEMS'][0]['CURRENCY'] : '-';
                                $pr_grandtotal = $detail_pr['GV_TOTAL_TXT'];
                                $purpose="";
                            }

                        }


                        if(count($data_requestor)>0){
                            $result[]=array(
                                'UID'=>$value['PREQ_NO'],
                                'DOC_TYPE'=>$value['BATXT'],
                                'REQ_BY'=>$pr_requestor_name,
                                'REQ_BY_ID'=>$pr_requestor_id,
                                'REQ_BY_MIDJOB'=>$pr_midjob,
                                'REQ_BY_COSTCENTER'=>$pr_cost_center,
                                'REQ_DATE'=>$pr_insert_date,
                                'PLANT'=>$value['PLANT'],
                                'TRACKING_NO'=>$value['TRACKINGNO'],
                                'TRACKING_DESC'=>$value['TRACK_DESC'],
                                'CURR'=>$pr_currency,
                                'GRAND_TOTAL'=>$pr_grandtotal,
                                'PURPOSE'=>$purpose
                            );
                        }

                    }

                    if($result){
                        $result = collect($result)->filter(function($item, $key) use ($current_loggedin_employee_id){
                            $check_qualified_approver = DB::connection('dbintranet')
                            ->table('SAP_PR_RELEASE_CODE_NEW')
                            ->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'MIDJOB_TITLE'=>$item['REQ_BY_MIDJOB'], 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                            ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                                $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'MIDJOB_TITLE'=>$item['REQ_BY_MIDJOB'], 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
                            })
                            ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                                $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                                ->whereNull('MIDJOB_TITLE');
                            })
                            ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                                $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                                ->whereNull('MIDJOB_TITLE');
                            })
                            ->get()->count();
                            if($check_qualified_approver)
                                return true;
                        });
                    }

                } catch(\Exception $e){
                    // dd($e);
                    // return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
                    Log::error('GET PR APPROVAL ADA ERROR | '.(string)$e);

                }
            }

            // filter by tanggal
            $request_date_to_carbon = (!empty($request_date_to))? Carbon::createFromFormat('!Y-m-d', date('Y-m-d',strtotime($request_date_to))) : NULL;
            $request_date_from_carbon = (!empty($request_date_from))? Carbon::createFromFormat('!Y-m-d', date('Y-m-d',strtotime($request_date_from))) : NULL;

            if($request_date_to_carbon && $request_date_from_carbon){
               $result= collect($result)->filter(function($item, $key) use ($request_date_to_carbon, $request_date_from_carbon){
                    try{
                        $data_sort = Carbon::createFromFormat('!Y-m-d', date('Y-m-d',strtotime($item['REQ_DATE'])));
                        return $data_sort->gte($request_date_from_carbon) && $data_sort->lte($request_date_to_carbon);
                    } catch(\Exception $e){
                        return $item;
                    }
                })->toArray();
            }
        }
        // END -- CARI DATA APPROVAL UNTUK DATATABLE

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
            'releasecode'=>$releasecode,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
            'form_code'=>$this->form_number,
            'material_type'=>$material_type,
            'material_group'=>$material_group,
            'material_valuation'=>$material_valuation,
            'current_user_release_code'=>$current_user_release_code,

        );
        return view('pages.finance.purchase-requisition.approval', ['data' => $data, 'result'=>$result]);
    }

    public function report(Request $request){

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

        $param = array();
        $function_type = $rfc->getFunction('ZFM_MM_MD_MATERIAL_TYPE');
        $material_type= $function_type->invoke($param, $options)['GT_REPORT'];

        $function_group = $rfc->getFunction('ZFM_MATERIAL_GROUP');
        $material_group= $function_group->invoke($param, $options)['ZTA_T023'];

        $function_valuation = $rfc->getFunction('ZFM_V_MD_VALUATION_CLASS');
        $material_valuation= $function_valuation->invoke($param, $options)['GT_VALUATION'];

        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        // dd(Session::get('assignment'));

        if(empty(Session::get('assignment')[0])){
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $division="SYSADMIN";
            $department="SYSADMIN";
            $plant_name="SYSADMIN";
        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
        }

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
        'status'=>$status,
        'request_date_from'=>$request_date_from,
        'request_date_to'=>$request_date_to,
        'form_code'=>$this->form_number,
        'material_type'=>$material_type,
        'material_group'=>$material_group,
        'material_valuation'=>$material_valuation
        );


        return view('pages.finance.purchase-requisition.report', ['data' => $data]);
    }

    public function request_getData(Request $request){
        try{
            $is_production = config('intranet.is_production');
            if($is_production){
                $rfc = new SapConnection(config('intranet.rfc_prod'));
            }else{
                $rfc = new SapConnection(config('intranet.rfc'));
            }
            $options = [
                'rtrim'=>true,
            ];

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
                if($status=="WAITING" || $status=='FINISHED'){
                    // $where = $where." and (STATUS_APPROVAL = 'REQUESTED' OR STATUS_APPROVAL = 'APPROVED') ";
                    $where = $where." and (STATUS_APPROVAL = 'REQUESTED' OR STATUS_APPROVAL = 'APPROVED' OR STATUS_APPROVAL = 'FINISHED') ";
                }else{
                    $where = $where." and STATUS_APPROVAL = '".$status."'";
                }
            }


            DB::connection('dbintranet')->enableQueryLog();
            $data = DB::connection('dbintranet')
                    ->table(DB::raw($this->form_view))
                    ->whereraw(DB::raw($where))->get();

            $result=array();
            $current_loggedin_employee_id=Session::get('user_id');
            foreach($data as $key=>$value){
                $po_complete = 0;
                $po_created = [];
                $data_form=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_RAW_DATA')
                ->leftJoin('INT_FIN_APPR_LIST','INT_FIN_APPR_RAW_DATA.UID','=','INT_FIN_APPR_LIST.FORM_ID')
                ->where('INT_FIN_APPR_RAW_DATA.UID',$value->UID)
                ->get();

                $data_json = isset($data_form[0]->JSON_ENCODE) ? json_decode($data_form[0]->JSON_ENCODE) : [];
                $grand_total=isset($data_json->grandTotal) ? $data_json->grandTotal : '';
                @$currency = isset($data_json->currency) ? $data_json->currency : '';
                $tracking_no=isset($data_json->cost_center) ? $data_json->cost_center : '';
                $purpose = isset($data_json->purpose) ? $data_json->purpose : '';

                $uid = $value->UID;
                $data_requestor=DB::connection('dbintranet')
                    ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");

                // start cari divison dan dept dari tracking number
                $data_costcenter=DB::connection('dbintranet')
                    ->select("SELECT TERRITORY_NAME, DEPARTMENT_NAME, DIVISION_NAME, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID = '".$tracking_no."'");

                // $cost_center_desc = (!empty($data_costcenter))? $data_costcenter[0]->TERRITORY_NAME.' '.$data_costcenter[0]->DEPARTMENT_NAME.' - '.$data_costcenter[0]->DIVISION_NAME : '' ;
                $cost_center_desc = (!empty($data_costcenter)) ? $data_costcenter[0]->SAP_COST_CENTER_DESCRIPTION : '';
                $data_pr_detail = [];
                try{
                    $param = array(
                        // 'GV_NUMBER'=>$uid
                        'NUMBER'=>$uid
                    );
                    // $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                    $function_type = $rfc->getFunction('BAPI_REQUISITION_GETDETAIL');
                    $data_pr_detail= $function_type->invoke($param, $options);
                }catch(SAPFunctionException $e){}

                // filter jika sudah ada nomor PO
                // $nomor_po = (isset($data_pr_detail['GI_ITEMS'][0]['PO']) && !empty($data_pr_detail['GI_ITEMS'][0]['PO'])) ? $data_pr_detail['GI_ITEMS'][0]['PO'] : '';
                // $nomor_po = (isset($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER']) && !empty($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'])) ? $data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'] : '';
                // if($uid == '2022024584'){
                //     dd($data_pr_detail);
                // }

                if(isset($data_pr_detail['REQUISITION_ITEMS']) && count($data_pr_detail['REQUISITION_ITEMS']) > 0){
                    $nomor_po = collect($data_pr_detail['REQUISITION_ITEMS'])->filter(function($item, $key) use ($rfc, $options, &$po_complete){
                        $current_release = null;
                        $po_number = isset($item['PO_NUMBER']) ? $item['PO_NUMBER'] : '';
                        try {
                            $param = array(
                                'PURCHASEORDER' => $po_number
                            );
                            $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
                            $release_info = $function_type->invoke($param, $options);
                            $current_release = isset($release_info['GENERAL_RELEASE_INFO']['REL_IND']) ? $release_info['GENERAL_RELEASE_INFO']['REL_IND'] : null;
                            if($current_release == 'R')
                                $po_complete += 1;
                        } catch(\Exception $e){}
                        return !empty($current_release);
                    })->pluck('PO_NUMBER', 'PO_NUMBER')->filter(function($item, $key){
                        return $item || !empty($item);
                    })->values()->all();
                    $po_created = $nomor_po;
                    $nomor_po = implode(',', $nomor_po);
                } else {
                    $nomor_po = '';
                }

                // Release check on SAP
                // START -- MENGAMBIL DATA RELEASE STRATEGY
                $midjob_id = isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '';
                try{
                    $param = array(
                        'NUMBER'=>$uid
                    );
                    $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                    $sap_release= $function_type->invoke($param, $options);

                    $release_info = collect(isset($sap_release['RELEASE_FINAL'][0]) ? $sap_release['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                        // take only description of release strategy code
                        return substr($key, 0, 8) == 'REL_CODE';
                    })->filter()->toArray();
                    $release_code = array_values($release_info);

                    $data_release_mapping = DB::connection('dbintranet')
                    ->table('SAP_PR_RELEASE_CODE_NEW AS ap')
                    ->where(['COSTCENTER'=>$tracking_no, 'MIDJOB_TITLE'=>$midjob_id])
                    ->orWhere(function($query) use ($tracking_no, $midjob_id){
                        $query->where(['COSTCENTER'=>$tracking_no, 'MIDJOB_TITLE'=>$midjob_id]);
                    })
                    ->orWhere(function($query) use ($tracking_no, $midjob_id){
                        $query->where(['COSTCENTER'=>$tracking_no])
                        ->whereNull('MIDJOB_TITLE');
                    })
                    ->orWhere(function($query) use ($tracking_no, $midjob_id){
                        $query->where(['COSTCENTER'=>$tracking_no])
                        ->whereNull('MIDJOB_TITLE');
                    })
                    ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                        $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
                    })
                    ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                        $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
                    })
                    ->select('ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
                    ->get()->toArray();

                    $prior_release_approve = array_values(
                        collect(
                            isset($sap_release['RELEASE_ALREADY_POSTED'][0]) ? $sap_release['RELEASE_ALREADY_POSTED'][0] : []
                        )->filter(function($item, $key){
                            // take only description of release strategy code
                            return substr($key, 0, 8) == 'REL_CODE';
                        }
                    )->filter()->toArray());

                    // Cek yang seharusnya approve selanjutnya
                    $now_release_approve = '';
                    foreach ($release_code as $key_release => $val_release) {
                        if (!in_array($val_release, $prior_release_approve)) {
                            $now_release_approve = $val_release;
                            break;
                        }
                    }
                }catch(SAPFunctionException $e){
                    Log::error('ERROR IN DETAIL PURCHASE REQUISITION | '.$e->getMessage());
                    $sap_release = [];
                    $release_code = [];
                    $data_release_mapping = [];
                    $prior_release_approve = [];
                    $now_release_approve = '';
                }
                // END -- MENGAMBIL DATA RELEASE STRATEGY

                if($status == 'FINISHED'){
                    if($value->STATUS_APPROVAL != 'FINISHED'){
                        if(count($data_release_mapping) == count($prior_release_approve)) {
                            if(count($po_created) > 0 && $po_complete == count($po_created)){
                                $result[]=array(
                                    'UID'=>$value->UID,
                                    'PO_NUMBER'=>$nomor_po,
                                    'DOC_TYPE'=>$value->DOCTYPE_DESC,
                                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                                    'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                                    'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                                    'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                                    'REASON'=>$value->LAST_APPROVAL_REASON,
                                    'REQ_DATE'=>$value->REQUESTEDDATE,
                                    'PLANT'=>$value->PLANT,
                                    'TRACKING_NO'=>$tracking_no,
                                    'TRACKING_DESC'=>$cost_center_desc,
                                    'CURR'=>@$currency,
                                    'GRAND_TOTAL'=>$grand_total,
                                    'PURPOSE'=>$purpose,
                                    'RELEASE_CODE_SAP'=>$release_code,
                                    'DATA_RELEASE_MAPPING'=>$data_release_mapping,
                                    'ALREADY_APPROVE_SAP'=>$prior_release_approve,
                                    'CURRENT_APPROVAL_SAP'=>$now_release_approve
                                );
                            }
                        }
                    } else if($value->STATUS_APPROVAL == 'FINISHED'){
                        if(count($po_created) > 0 && $po_complete == count($po_created)){
                            $result[]=array(
                                'UID'=>$value->UID,
                                'PO_NUMBER'=>$nomor_po,
                                'DOC_TYPE'=>$value->DOCTYPE_DESC,
                                'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                                'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                                'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                                'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                                'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                                'REASON'=>$value->LAST_APPROVAL_REASON,
                                'REQ_DATE'=>$value->REQUESTEDDATE,
                                'PLANT'=>$value->PLANT,
                                'TRACKING_NO'=>$tracking_no,
                                'TRACKING_DESC'=>$cost_center_desc,
                                'CURR'=>@$currency,
                                'GRAND_TOTAL'=>$grand_total,
                                'PURPOSE'=>$purpose,
                                'RELEASE_CODE_SAP'=>$release_code,
                                'DATA_RELEASE_MAPPING'=>$data_release_mapping,
                                'ALREADY_APPROVE_SAP'=>$prior_release_approve,
                                'CURRENT_APPROVAL_SAP'=>$now_release_approve
                            );
                        }
                    }
                } else {
                    // Display only PR which 
                    // only doesn't have PO / incomplete PO
                    if(!$status){
                        // if($value->STATUS_APPROVAL == 'REQUESTED' || $value->STATUS_APPROVAL == 'APPROVED' || $value->STATUS_APPROVAL == 'FINISHED'){
                            // if($po_complete < count($po_created) || count($po_created) == 0){
                        $result[]=array(
                            'UID'=>$value->UID,
                            'PO_NUMBER'=>$nomor_po,
                            'DOC_TYPE'=>$value->DOCTYPE_DESC,
                            'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                            'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                            'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                            'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                            'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                            'REASON'=>$value->LAST_APPROVAL_REASON,
                            'REQ_DATE'=>$value->REQUESTEDDATE,
                            'PLANT'=>$value->PLANT,
                            'TRACKING_NO'=>$tracking_no,
                            'TRACKING_DESC'=>$cost_center_desc,
                            'CURR'=>@$currency,
                            'GRAND_TOTAL'=>$grand_total,
                            'PURPOSE'=>$purpose,
                            'RELEASE_CODE_SAP'=>$release_code,
                            'DATA_RELEASE_MAPPING'=>$data_release_mapping,
                            'ALREADY_APPROVE_SAP'=>$prior_release_approve,
                            'CURRENT_APPROVAL_SAP'=>$now_release_approve
                        );
                            // }
                        // }
                    } else {
                        if(strtoupper($status) == 'WAITING') {
                            // if($value->STATUS_APPROVAL == 'REQUESTED' && count($prior_release_approve) < count($data_release_mapping) || $value->STATUS_APPROVAL == 'APPROVED' && count($prior_release_approve) < count($data_release_mapping) || count($release_code) && count($prior_release_approve) < count($data_release_mapping)){
                            if($value->STATUS_APPROVAL == 'REQUESTED' || $value->STATUS_APPROVAL == 'APPROVED' || $value->STATUS_APPROVAL == 'FINISHED'){
                                if($po_complete < count($po_created) || count($po_created) == 0){
                                    $result[]=array(
                                        'UID'=>$value->UID,
                                        'PO_NUMBER'=>$nomor_po,
                                        'DOC_TYPE'=>$value->DOCTYPE_DESC,
                                        'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                                        'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                                        'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                                        'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                                        'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                                        'REASON'=>$value->LAST_APPROVAL_REASON,
                                        'REQ_DATE'=>$value->REQUESTEDDATE,
                                        'PLANT'=>$value->PLANT,
                                        'TRACKING_NO'=>$tracking_no,
                                        'TRACKING_DESC'=>$cost_center_desc,
                                        'CURR'=>@$currency,
                                        'GRAND_TOTAL'=>$grand_total,
                                        'PURPOSE'=>$purpose,
                                        'RELEASE_CODE_SAP'=>$release_code,
                                        'DATA_RELEASE_MAPPING'=>$data_release_mapping,
                                        'ALREADY_APPROVE_SAP'=>$prior_release_approve,
                                        'CURRENT_APPROVAL_SAP'=>$now_release_approve
                                    );
                                }
                            }
                        } else {
                            $result[]=array(
                                'UID'=>$value->UID,
                                'PO_NUMBER'=>$nomor_po,
                                'DOC_TYPE'=>$value->DOCTYPE_DESC,
                                'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                                'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                                'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                                'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                                'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                                'REASON'=>$value->LAST_APPROVAL_REASON,
                                'REQ_DATE'=>$value->REQUESTEDDATE,
                                'PLANT'=>$value->PLANT,
                                'TRACKING_NO'=>$tracking_no,
                                'TRACKING_DESC'=>$cost_center_desc,
                                'CURR'=>@$currency,
                                'GRAND_TOTAL'=>$grand_total,
                                'PURPOSE'=>$purpose,
                                'RELEASE_CODE_SAP'=>$release_code,
                                'DATA_RELEASE_MAPPING'=>$data_release_mapping,
                                'ALREADY_APPROVE_SAP'=>$prior_release_approve,
                                'CURRENT_APPROVAL_SAP'=>$now_release_approve
                            );
                        }
                    }
                }
            }

            if($request_type == 'REPORT' && count($result) > 0){
                $result = collect($result)->filter(function($item, $key) use ($current_loggedin_employee_id){
                    $check_qualified_approver = DB::connection('dbintranet')
                    ->table('SAP_PR_RELEASE_CODE_NEW')
                    ->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'MIDJOB_TITLE'=>$item['REQ_BY_MIDJOB'], 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                        $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'MIDJOB_TITLE'=>$item['REQ_BY_MIDJOB'], 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
                    })
                    ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                        $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                        ->whereNull('MIDJOB_TITLE');
                    })
                    ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                        $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                        ->whereNull('MIDJOB_TITLE');
                    })
                    ->get()->count();
                    if($check_qualified_approver)
                        return true;
                });
            }

            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function approval_getData(Request $request)
    {

         // START -- INIT RFC
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc'));
        }
         $options = [
             'rtrim'=>true
         ];
         // END -- INIT RFC

        try{
            DB::enableQueryLog();
            $deptId=$request->input('deptId');
            $employeeId=$request->input('employeeId');
            $midjobId=$request->input('midjobId');
            $costcenter=$request->input('costcenter');
            $releasecode=$request->input('releasecode');
            $companycode=$request->input('companycode');


            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');


            // START -- FUNCTION UNTUK CARI DATA APPROVAL KE SAP

            // START -- filter RELEASE GROUP MANUAL
            // switch ($companycode) {
            //     case 'NJP':
            //         $releasegroup="Y1";
            //         break;
            //     case 'KMS':
            //         $releasegroup="Y0";
            //         break;
            //     default :
            //         $releasegroup="Y1";
            //     break;
            // }

            $approver_company_allowed = DB::connection('dbintranet')
            ->table('SAP_PR_RELEASE_GROUP_APPROVAL_MAPPING')
            ->select('RELEASE_GROUP_CODE')
            ->get()->pluck('RELEASE_GROUP_CODE')->toArray();

            $result=array();
            foreach ($approver_company_allowed as $key => $release_group_sap) {
                // END -- filter RELEASE GROUP MANUAL
                $current_loggedin_employee_id=Session::get('user_id');
                $cek_release_code_approver = DB::connection('dbintranet')
                ->table('SAP_PR_RELEASE_CODE_NEW')
                ->where('APP_EMPLOYEE_ID', $current_loggedin_employee_id)
                ->orWhere(function($query) use ($current_loggedin_employee_id){
                    $query->where(['ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
                })
                ->select('RELEASE_CODE')
                ->distinct()->pluck('RELEASE_CODE')->toArray();

                foreach ($cek_release_code_approver as $key => $release_code_approver_mapping) {
                    try {
                        $param = array(
                            'GV_REL_GROUP'=>$release_group_sap,
                            // 'GV_REL_CODE'=>"$releasecode"
                            'GV_REL_CODE'=>$release_code_approver_mapping

                        );

                        $function_type = $rfc->getFunction('ZFM_PR_GETITEMSREL_INTRA');
                        $data= $function_type->invoke($param, $options);

                        // END -- FUNCTION UNTUK CARI DATA APPROVAL KE SAP
                        foreach($data['GI_ITEMS'] as $key=>$value){
                            // cari data requestor di DBWH by PR number
                            $uid=$value['PREQ_NO'];

                            $data_requestor=DB::connection('dbintranet')
                                            ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");
                            DB::enableQueryLog();
                            $data_header=DB::connection('dbintranet')
                                        ->select("SELECT H.PRHEADER_ID, H.PRNUMBER, SUM(CAST(D.QUANTITY AS FLOAT* CAST(D.AMOUNT AS INT)) AS GRAND_TOTAL, D.CURRENCY
                                        FROM TBL_PR_DETAIL D
                                        INNER JOIN TBL_PR_HEADER H ON H.PRHEADER_ID = D.PRREQUESTHEADER_ID
                                        WHERE H.PRNUMBER = '$uid'
                                        GROUP BY H.PRHEADER_ID, H.PRNUMBER, D.CURRENCY");

                            if(count($data_requestor)>0 && count($data_header)>0){
                                $data_json=json_decode($data_requestor[0]->JSON_ENCODE);
                                $purpose=(!empty($data_json->purpose))? $data_json->purpose : '';
                                $result[]=array(
                                    'UID'=>$value['PREQ_NO'],
                                    'DOC_TYPE'=>$value['BATXT'],
                                    'REQ_BY'=>$data_requestor[0]->EMPLOYEE_NAME,
                                    'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                                    'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                                    'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                                    'REQ_DATE'=>$data_requestor[0]->INSERT_DATE,
                                    'PLANT'=>$value['PLANT'],
                                    'TRACKING_NO'=>$value['TRACKINGNO'],
                                    'TRACKING_DESC'=>$value['TRACK_DESC'],
                                    'CURR'=>$data_header[0]->CURRENCY,
                                    'GRAND_TOTAL'=>$data_header[0]->GRAND_TOTAL,
                                    'PURPOSE'=>$purpose
                                );
                            }

                        }

                        if($result){
                            $result = collect($result)->filter(function($item, $key) use ($current_loggedin_employee_id){
                                $check_qualified_approver = DB::connection('dbintranet')
                                ->table('SAP_PR_RELEASE_CODE_NEW')
                                ->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'MIDJOB_TITLE'=>$item['REQ_BY_MIDJOB'], 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                                ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                                    $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'MIDJOB_TITLE'=>$item['REQ_BY_MIDJOB'], 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
                                })
                                ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                                    $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                                    ->whereNull('MIDJOB_TITLE');
                                })
                                ->orWhere(function($query) use ($item, $current_loggedin_employee_id){
                                    $query->where(['COSTCENTER'=>$item['REQ_BY_COSTCENTER'], 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                                    ->whereNull('MIDJOB_TITLE');
                                })
                                ->get()->count();
                                if($check_qualified_approver)
                                    return true;
                            });
                        }
                    } catch(\Exception $e){}
                }
            }
            return DataTables::of($result)->make(true);

        } catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function getHistoryApproval(Request $request){


        $rest = new ZohoFormModel();
        $result = $rest->getHistoryApproval($request,$this->form_view);
        return response($result);
    }

    public function submitApprovalForm(Request $request)
    {
        $FormID=imuneString($request->input('form_id'));
        $data = explode(";" , $FormID);
        $EmployeID=imuneString($request->input('employe_id'));
        $StatusApproval=imuneString($request->input('status_approval'));
        $TypeForm=imuneString($request->input('type_form'));
        $Reason=imuneString($request->input('reason'));

        $relcode=imuneString($request->input('relcode'));
        $totalData=0;
        $success=0;
        $failed=0;

        foreach ($data as $key => $dataId) {

            $result = $this->approve_batch($dataId, $EmployeID, $StatusApproval, $TypeForm, $Reason, $relcode);

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



    public function approve_batch($FormID, $LastApprovalID, $StatusApproval, $TypeForm, $Reason, $relcode)
    {

        //init RFC
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc'));
        }
        $options = [
            'rtrim'=>true
        ];
        //===

        try {
            $connection = DB::connection('dbintranet');
            $connection->beginTransaction();

            $base_url=URL::to('/');

            $prnumber=$FormID;
            $release_code=$relcode;

            $reason = $Reason;
            $employee_id = $LastApprovalID;

            if($StatusApproval=="APPROVED"){

                // Cek release code dari mapping approver
                $cek_data_pr=DB::connection('dbintranet')
                ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$prnumber' ");

                try{
                    $param = array(
                        'GV_NUMBER'=>$prnumber
                    );
                    $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                    $data_form= $function_type->invoke($param, $options);
                }catch(SAPFunctionException $e){
                    throw new \Exception("There is problem connection with SAP RFC : ZFM_MID_PR_GET_DETAIL_INTRA, please try again later or contact IT Department");
                }


                $requestor_employee_id = (!empty($data_form['GI_ITEMS'][0]['PREQ_ID']))? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                // if(!$cek_data_pr)
                    // throw new \Exception("Purchase Requisition data is not found in data warehouse");
                if(!$cek_data_pr){
                    // cari data karyawan requestor di DB warehouse
                    $data_requestor=collect(DB::connection('dbintranet')->select("SELECT * FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$requestor_employee_id."'"))->first();
                    $midjob_id = isset($data_requestor->MIDJOB_TITLE_ID) ? $data_requestor->MIDJOB_TITLE_ID : '';
                    $cost_center = (!empty($data_form['GI_ITEMS'][0]['TRACKINGNO']))? $data_form['GI_ITEMS'][0]['TRACKINGNO'] : NULL;
                }else{
                    $midjob_id = isset($cek_data_pr[0]->MIDJOB_TITLE_ID) ? $cek_data_pr[0]->MIDJOB_TITLE_ID : '';
                    $cost_center = isset(json_decode($cek_data_pr[0]->JSON_ENCODE)->cost_center) ? json_decode($cek_data_pr[0]->JSON_ENCODE)->cost_center : '';
                }

                $current_loggedin_employee_id=Session::get('user_id');


                $check_qualified_approver = DB::connection('dbintranet')
                ->table('SAP_PR_RELEASE_CODE_NEW')
                ->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id, 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id, 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
                })
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->whereNull('MIDJOB_TITLE');
                })
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->whereNull('MIDJOB_TITLE');
                })
                ->get()->pluck('RELEASE_CODE')->first();

                if(!$check_qualified_approver)
                    throw new \Exception("Cannot find any release code for current employee, please check the data");
                // End Cek release code dari mapping approver

                $param = array(
                    'GV_NUMBER'=>$prnumber,
                    // 'GV_REL_CODE'=>$release_code
                    'GV_REL_CODE'=>$check_qualified_approver

                );
                $function_type = $rfc->getFunction('ZFM_PR_RELEASE_INTRA');
                $approve= $function_type->invoke($param, $options);

                $new_indicator=$approve['GV_REL_INDICATOR_NEW'];
                $new_status=isset($approve['GV_REL_STATUS_NEW']) ? $approve['GV_REL_STATUS_NEW'] : '';
                $next_approval=$approve['GV_REL_CODE_NEXT'];

                if(!empty($new_status)){// jika GV_REL_STATUS_NEW = X maka artinya approve success
                    //cek apakah dia sudah finish atau masih lanjut

                    $cek_data_pr=DB::connection('dbintranet')
                    ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$prnumber' ");

                    try{
                        $param = array(
                            'GV_NUMBER'=>$prnumber
                        );
                        $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                        $data_form= $function_type->invoke($param, $options);
                    }catch(SAPFunctionException $e){
                        throw new \Exception("There is problem connection with SAP RFC : ZFM_MID_PR_GET_DETAIL_INTRA, please try again later or contact IT Department");
                    }

                    $requestor_employee_id = (!empty($data_form['GI_ITEMS'][0]['PREQ_ID']))? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;

                    if(!$cek_data_pr){
                        // throw new \Exception("Purchase Requisition data is not found in data warehouse");
                        // cari data karyawan requestor di DB warehouse
                        $data_requestor=collect(DB::connection('dbintranet')->select("SELECT * FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$requestor_employee_id."'"))->first();
                        $midjob_id = isset($data_requestor->MIDJOB_TITLE_ID) ? $data_requestor->MIDJOB_TITLE_ID : '';
                        $cost_center = (!empty($data_form['GI_ITEMS'][0]['TRACKINGNO']))? $data_form['GI_ITEMS'][0]['TRACKINGNO'] : NULL;
                    }else{
                        $midjob_id = isset($cek_data_pr[0]->MIDJOB_TITLE_ID) ? $cek_data_pr[0]->MIDJOB_TITLE_ID : '';
                        $cost_center = isset(json_decode($cek_data_pr[0]->JSON_ENCODE)->cost_center) ? json_decode($cek_data_pr[0]->JSON_ENCODE)->cost_center : '';
                    }

                    $doctype=(isset($data_form['GI_ITEMS'][0]['DOC_TYPE']) && !empty($data_form['GI_ITEMS'][0]['DOC_TYPE'])) ? $data_form['GI_ITEMS'][0]['DOC_TYPE'] : NULL;
                    $preq_id=(isset($data_form['GI_ITEMS'][0]['PREQ_ID']) && !empty($data_form['GI_ITEMS'][0]['PREQ_ID'])) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                    $plant=(isset($data_form['GI_ITEMS'][0]['PLANT']) && !empty($data_form['GI_ITEMS'][0]['PLANT'])) ? $data_form['GI_ITEMS'][0]['PLANT'] : NULL;
                    $preq_date=(isset($data_form['GI_ITEMS'][0]['PREQ_DATE']) && !empty($data_form['GI_ITEMS'][0]['PREQ_DATE'])) ? date('m/d/Y',strtotime($data_form['GI_ITEMS'][0]['PREQ_DATE'])) : NULL;


                    if(empty($next_approval) || $next_approval==""){
                        $last_approve_status="FINISHED";
                    }else{
                        $last_approve_status="APPROVED";
                    }

                    // cek jika ada  di table HEADER
                    $itung_pr_header=DB::connection('dbintranet')
                    ->table('TBL_PR_HEADER')
                    ->select('PRNUMBER')
                    ->where('PRNUMBER',$prnumber)
                    ->count();

                    if($itung_pr_header>0){
                        // jika ada di table header, maka update statusnya
                        DB::connection('dbintranet')
                        ->table('TBL_PR_HEADER')
                        ->where('PRNUMBER',$prnumber)
                        ->update(
                            [
                                "LAST_APPROVAL_STATUS"=>$last_approve_status,
                                "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                                "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                                "LAST_APPROVAL_REASON"=>""
                            ]
                        );
                    }else{
                        // jika tidak ada, maka insert (karena datang dari SAP)
                        DB::connection('dbintranet')
                        ->table('TBL_PR_HEADER')
                        ->insert(
                            [
                                "PRNUMBER"=>$prnumber,
                                "DOCTYPE"=>$doctype,
                                "PLANT"=>$plant,
                                "REQUESTEDBY"=>$preq_id,
                                "REQUESTEDDATE"=>$preq_date,
                                "LAST_APPROVAL_STATUS"=>$last_approve_status,
                                "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                                "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                                "LAST_APPROVAL_REASON"=>""
                            ]
                        );

                        //tambahan juga harus input di tabel INT APPR RAW DATA
                        $itung_pr_raw=DB::connection('dbintranet')
                        ->table('INT_FIN_APPR_RAW_DATA')
                        ->select('UID')
                        ->where('UID',$prnumber)
                        ->count();

                        if($itung_pr_raw<1){
                            DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_RAW_DATA')
                            ->insert(
                                [
                                    "JSON_ENCODE"=>"{}",
                                    "TYPE"=>"Request Purchase Requisition",
                                    "INSERT_DATE"=>$preq_date,
                                    "UID"=>$prnumber,
                                    "EMPLOYEE_ID"=>$preq_id,
                                    "TYPE_FORM"=>"PURCHASE-REQUISITION",
                                    "FULLFILL_ZOHO"=>""
                                ]
                            );
                        }

                    }

                    // cek jika ada  di table RELEASE

                    $itung_pr_release=DB::connection('dbintranet')
                    ->table('TBL_PR_RELEASE')
                    ->select('PRNUMBER')
                    ->where('PRNUMBER',$prnumber)
                    ->where('RELCODE',$check_qualified_approver)
                    ->count();

                    if($itung_pr_release>0){
                        // jika ada di table release, maka update statusnya
                        DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->where('PRNUMBER',$prnumber)
                        ->where('RELCODE',$check_qualified_approver)
                        ->update(
                            [
                                "STATUS"=>"APPROVED",
                                "RELEASEDATE"=>date('Y-m-d H:i:s'),
                                "RELEASE_CODE_NAME_ID"=>$employee_id
                            ]
                        );
                    }else{
                        // jika tidak ada, maka insert (karena datang dari SAP)
                        try{
                            $param = array(
                                'NUMBER'=>$prnumber
                            );
                            $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                            $sap_release= $function_type->invoke($param, $options);
                        }catch(SAPFunctionException $e){
                            throw new \Exception("Cannot find release strategy on SAP");
                        }

                        // insert ke table pr release
                        $data_release=$sap_release['RELEASE_FINAL'][0];
                        for($i=1;$i<=8;$i++){
                            DB::connection('dbintranet')
                            ->table('TBL_PR_RELEASE')
                            ->insert(
                                [
                                    "RELCODE" => $data_release['REL_CODE'.$i],
                                    "RELCODEDESC" => $data_release['REL_CD_TX'.$i],
                                    "RELCODENAME" =>NULL,
                                    "STATUS" => NULL,
                                    "RELEASEDATE" => NULL,
                                    "PRNUMBER" => $prnumber,
                                    "REASON" =>NULL,
                                    "RELEASE_CODE_NAME_ID"=>NULL
                                ]
                            );
                        }

                        //lalu update
                        DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->where('PRNUMBER',$prnumber)
                        ->where('RELCODE',$check_qualified_approver)
                        ->update(
                            [
                                "STATUS"=>"APPROVED",
                                "RELEASEDATE"=>date('Y-m-d H:i:s'),
                                "RELEASE_CODE_NAME_ID"=>$employee_id
                            ]
                        );
                    }



                    // ========================================================================= START NOTIFICATION

                    if($last_approve_status=="FINISHED"){
                        //start insert notifikasi ke user jika PR telah selesai
                        $requestor=DB::connection('dbintranet')
                        ->select("SELECT REQUESTEDBY FROM TBL_PR_HEADER WHERE PRNUMBER='".$prnumber."'");

                        @$requestor_id=(!empty($requestor[0]->REQUESTEDBY))? $requestor[0]->REQUESTEDBY : NULL;

                        if(empty($requestor_id)){
                            //jika kosong, maka cari dari RFC get detail PR
                            $param = array(
                                'GV_NUMBER'=>$prnumber
                            );
                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $data_form= $function_type->invoke($param, $options);
                            $requestor_id=(!empty($data_form['GI_ITEMS'][0]['PREQ_ID'])) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                        }

                        if(!empty($requestor_id)){
                            $notif_link="/finance/purchase-requisition/detail/".$prnumber;
                            $notif_desc="Your Purchase Requisition : ".$prnumber." is approved";
                            $notif_type="approve";
                            insertNotification($requestor_id, $notif_link, $notif_desc, $notif_type);
                        }

                    }elseif($last_approve_status=="APPROVED"){
                        //start insert notifikasi ke user selanjutnya untuk diapprove

                        $uid = $prnumber;
                        try{
                            $param = array(
                                'NUMBER'=>$uid
                            );
                            $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                            $sap_release= $function_type->invoke($param, $options);
                        }catch(SAPFunctionException $e){
                            $sap_release=[];
                        }

                        try{
                            $param = array(
                                'GV_NUMBER'=>$uid
                            );
                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $data_form= $function_type->invoke($param, $options);
                        }catch(SAPFunctionException $e){
                            $data_form=[];
                        }

                        $cost_center = (!empty($data_form['GI_ITEMS'][0]['TRACKINGNO']))? $data_form['GI_ITEMS'][0]['TRACKINGNO'] : NULL;
                        $requestor_employee_id = (!empty($data_form['GI_ITEMS'][0]['PREQ_ID']))? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;

                        // cari data karyawan requestor di DB warehouse
                        $data_requestor=collect(DB::connection('dbintranet')->select("SELECT * FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$requestor_employee_id."'"))->first();
                        $midjob_id = isset($data_requestor->MIDJOB_TITLE_ID) ? $data_requestor->MIDJOB_TITLE_ID : '';


                        $release_info = collect(isset($sap_release['RELEASE_FINAL'][0]) ? $sap_release['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                            // take only description of release strategy code
                            return substr($key, 0, 8) == 'REL_CODE';
                        })->filter()->toArray();
                        $release_code = array_values($release_info);

                        $prior_release_approve = array_values(
                            collect(
                                isset($sap_release['RELEASE_ALREADY_POSTED'][0]) ? $sap_release['RELEASE_ALREADY_POSTED'][0] : []
                            )->filter(function($item, $key){
                                // take only description of release strategy code
                                return substr($key, 0, 8) == 'REL_CODE';
                            }
                        )->filter()->toArray());


                        // Cek yang seharusnya approve selanjutnya
                        $now_release_approve = '';
                        foreach ($release_code as $key => $value) {
                            if (!in_array($value, $prior_release_approve)) {
                                $now_release_approve = $value;
                                break;
                            }
                        }

                        $release_mapping = DB::connection('dbintranet')
                            ->table('SAP_PR_RELEASE_CODE_NEW AS ap')
                            ->where(['ap.RELEASE_CODE'=>$now_release_approve,'COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id])
                            ->orWhere(function($query) use ($cost_center, $midjob_id, $now_release_approve){
                                $query->where(['ap.RELEASE_CODE'=>$now_release_approve,'COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id]);
                            })
                            ->orWhere(function($query) use ($cost_center, $midjob_id,$now_release_approve){
                                $query->where(['ap.RELEASE_CODE'=>$now_release_approve,'COSTCENTER'=>$cost_center])
                                ->whereNull('MIDJOB_TITLE');
                            })
                            ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                                $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
                            })
                            ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                                $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
                            })
                            ->select('ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
                            ->get()->toArray();

                        if(!empty($release_mapping[0])){
                            @$requestor_id=(!empty($release_mapping[0]['EMPLOYEE_ID']))? $release_mapping[0]['EMPLOYEE_ID'] : $release_mapping[0]['ALT_EMPLOYEE_ID'];
                            if(!empty($requestor_id)){
                                // start input notifikasi
                                $notif_link="/finance/purchase-requisition/detail/".$prnumber;
                                $notif_desc="Please approve Purchase Requisition : ".$prnumber."";
                                $notif_type="info";
                                insertNotification($requestor_id, $notif_link, $notif_desc, $notif_type);
                            }
                        }

                    }
                    // ========================================================================= END NOTIFICATION
                }


            }else if($StatusApproval=="REJECTED"){
                $param = array(
                    'GV_NUMBER'=>$prnumber
                );
                $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                $data_form= $function_type->invoke($param, $options);
                
                $param = array(
                    'GV_NUMBER'=>$prnumber
                );

                $function_type = $rfc->getFunction('ZFM_PR_CANCEL_REL_INTRA');
                try{
                    $reject= $function_type->invoke($param, $options);
                    // if($reject['GI_RETURN'][0]['CODE'] == "W5041"){// flag jika cancellation sukses
                    $doctype=(isset($data_form['GI_ITEMS'][0]['DOC_TYPE']) && !empty($data_form['GI_ITEMS'][0]['DOC_TYPE'])) ? $data_form['GI_ITEMS'][0]['DOC_TYPE'] : NULL;
                    $preq_id=(isset($data_form['GI_ITEMS'][0]['PREQ_ID']) && !empty($data_form['GI_ITEMS'][0]['PREQ_ID'])) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                    $plant=(isset($data_form['GI_ITEMS'][0]['PLANT']) && !empty($data_form['GI_ITEMS'][0]['PLANT'])) ? $data_form['GI_ITEMS'][0]['PLANT'] : NULL;
                    $preq_date=(isset($data_form['GI_ITEMS'][0]['PREQ_DATE']) && !empty($data_form['GI_ITEMS'][0]['PREQ_DATE'])) ? date('m/d/Y',strtotime($data_form['GI_ITEMS'][0]['PREQ_DATE'])) : NULL;

                    if(isset($reject['GI_RETURN'][0]['CODE']) && $reject['GI_RETURN'][0]['CODE'] == "W5041" || isset($reject['GI_RETURN'][0]['TYPE']) && strtoupper($reject['GI_RETURN'][0]['TYPE']) == "S"){
                        $keterangan = "REJECTED";
                        // cek jika ada  di table HEADER
                        $itung_pr_header=DB::connection('dbintranet')
                        ->table('TBL_PR_HEADER')
                        ->select('PRNUMBER')
                        ->where('PRNUMBER',$prnumber)
                        ->count();

                        if($itung_pr_header>0){
                            // jika ada di table header, maka update statusnya
                            DB::connection('dbintranet')
                            ->table('TBL_PR_HEADER')
                            ->where('PRNUMBER',$prnumber)
                            ->update(
                                [
                                    "LAST_APPROVAL_STATUS"=>$keterangan,
                                    "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                                    "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                                    "LAST_APPROVAL_REASON"=>$reason
                                ]
                            );
                        }else{
                            // jika tidak ada, maka insert (karena datang dari SAP)
                            DB::connection('dbintranet')
                            ->table('TBL_PR_HEADER')
                            ->insert(
                                [
                                    "PRNUMBER"=>$prnumber,
                                    "DOCTYPE"=>$doctype,
                                    "PLANT"=>$plant,
                                    "REQUESTEDBY"=>$preq_id,
                                    "REQUESTEDDATE"=>$preq_date,
                                    "LAST_APPROVAL_STATUS"=>$keterangan,
                                    "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                                    "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                                    "LAST_APPROVAL_REASON"=>$reason
                                ]
                            );

                             //tambahan juga harus input di tabel INT APPR RAW DATA
                            $itung_pr_raw=DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_RAW_DATA')
                            ->select('UID')
                            ->where('UID',$prnumber)
                            ->count();

                            if($itung_pr_raw<1){
                                DB::connection('dbintranet')
                                ->table('INT_FIN_APPR_RAW_DATA')
                                ->insert(
                                    [
                                        "JSON_ENCODE"=>"{}",
                                        "TYPE"=>"Request Purchase Requisition",
                                        "INSERT_DATE"=>$preq_date,
                                        "UID"=>$prnumber,
                                        "EMPLOYEE_ID"=>$preq_id,
                                        "TYPE_FORM"=>"PURCHASE-REQUISITION",
                                        "FULLFILL_ZOHO"=>""
                                    ]
                                );
                            }
                        }

                        $itung_pr_release=DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->select('PRNUMBER')
                        ->where('PRNUMBER',$prnumber)
                        ->where('RELCODE',$release_code)
                        ->count();

                        if($itung_pr_release>0){
                            // jika ada di table release, maka update statusnya
                            DB::connection('dbintranet')
                            ->table('TBL_PR_RELEASE')
                            ->where('PRNUMBER',$prnumber)
                            ->where('RELCODE',$release_code)
                            ->update(
                                [
                                    "STATUS"=>$keterangan,
                                    "RELEASEDATE"=>date('Y-m-d H:i:s'),
                                    "RELEASE_CODE_NAME_ID"=>$employee_id
                                ]
                            );
                        }else{
                            // jika tidak ada, maka insert (karena datang dari SAP)
                            try{
                                $param = array(
                                    'NUMBER'=>$prnumber
                                );
                                $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                                $sap_release= $function_type->invoke($param, $options);
                            }catch(SAPFunctionException $e){
                                throw new \Exception("Cannot find release strategy on SAP, please try again later or contact IT Department");
                            }

                            // insert ke table pr release
                            $data_release=$sap_release['RELEASE_FINAL'][0];
                            for($i=1;$i<=8;$i++){
                                DB::connection('dbintranet')
                                ->table('TBL_PR_RELEASE')
                                ->insert(
                                    [
                                        "RELCODE" => $data_release['REL_CODE'.$i],
                                        "RELCODEDESC" => $data_release['REL_CD_TX'.$i],
                                        "RELCODENAME" =>NULL,
                                        "STATUS" => NULL,
                                        "RELEASEDATE" => NULL,
                                        "PRNUMBER" => $prnumber,
                                        "REASON" =>NULL,
                                        "RELEASE_CODE_NAME_ID"=>NULL
                                    ]
                                );
                            }

                            //lalu update
                            DB::connection('dbintranet')
                            ->table('TBL_PR_RELEASE')
                            ->where('PRNUMBER',$prnumber)
                            ->where('RELCODE',$release_code)
                            ->update(
                                [
                                    "STATUS"=>$keterangan,
                                    "RELEASEDATE"=>date('Y-m-d H:i:s'),
                                    "RELEASE_CODE_NAME_ID"=>$employee_id
                                ]
                            );
                        }




                        // ========================================================================= START NOTIFICATION
                        $requestor=DB::connection('dbintranet')
                        ->select("SELECT REQUESTEDBY FROM TBL_PR_HEADER WHERE PRNUMBER='".$prnumber."'");

                        @$requestor_id=(!empty($requestor[0]->REQUESTEDBY))? $requestor[0]->REQUESTEDBY : NULL;

                        if(empty($requestor_id)){
                            //jika kosong, maka cari dari RFC get detail PR
                            $param = array(
                                'GV_NUMBER'=>$prnumber
                            );
                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $data_form= $function_type->invoke($param, $options);
                            $requestor_id=(!empty($data_form['GI_ITEMS'][0]['PREQ_ID'])) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                        }

                        if(!empty($requestor_id)){
                            $notif_link="/finance/purchase-requisition/detail/".$prnumber;
                            $notif_desc="Your Purchase Requisition : ".$prnumber." is rejected";
                            $notif_type="reject";
                            insertNotification($requestor_id, $notif_link, $notif_desc, $notif_type);
                        }
                        // ========================================================================= END NOTIFICATION

                    }

                }catch(SapException $e){
                    $success=false;
                    $code = 403;
                    $msg = $e->errorInfo;
                }
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

    public function modal_request(){
        return View::make('pages.finance.purchase-requisition.modal_request')->render();
    }

    public function detail($id = NULL, Request $request){
        //init RFC
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc'));
        }
        $options = [
            'rtrim'=>true
        ];
        //===
        // jika $id = NULL maka kebutuhan untuk modal
        // jika $id != NULL maka kebutuhan untuk page detail dedicated
        $uid = (empty($id))? $request->input('id') : $id;
        $data_form = [];
        $data_form_alt = [];
        // START -- CARI DATA DETAIL DARI SAP
        try{
            $param = array(
                'GV_NUMBER'=>$uid
            );
            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
            $data_form= $function_type->invoke($param, $options);
        }catch(SAPFunctionException $e){
            throw new \Exception ("Failed to communicate with SAP, please try again later or contact IT Department");
        }

        $cost_center = (isset($data_form['GI_ITEMS'][0]['TRACKINGNO']))? $data_form['GI_ITEMS'][0]['TRACKINGNO'] : NULL;
        if(!$cost_center){
            if(!empty($id)){
                $status = array('msg'=>sprintf('Cost center is not found in PR No. %s detail SAP, please check data in SAP and try again', $uid), 'type'=>'danger');
                $HTTP_REFERER = $request->server->get('HTTP_REFERER') ? $request->server->get('HTTP_REFERER') : '/finance/purchase-requisition/request';
                return redirect($HTTP_REFERER)->with('message', $status);
            }
        }


        $requestor_employee_id = (isset($data_form['GI_ITEMS'][0]['PREQ_ID']))? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
        $currency = (isset($data_form['GI_ITEMS'][0]['CURRENCY']))? $data_form['GI_ITEMS'][0]['CURRENCY'] : NULL;

        // filter jika sudah ada nomor PO
        $nomor_po = (!empty($data_form['GI_ITEMS'][0]['PO']))? $data_form['GI_ITEMS'][0]['PO'] : NULL;

        // validasi untuk sumber data dari data item
        $source_item_pr="rfc";
        if(empty($data_form['GI_ITEMS'])){
            // menggunakan BAPI default dari SAP untuk mendapatkan data item
            try{
                $param = array(
                    'NUMBER'=>$uid
                );
                $function_type = $rfc->getFunction('BAPI_REQUISITION_GETDETAIL');
                $data_form_alt= $function_type->invoke($param, $options);
            }catch(SAPFunctionException $e){
                throw new \Exception ("Failed to communicate with SAP, please try again later or contact IT Department");
            }
            if(isset($data_form_alt['REQUISITION_ITEMS'][0])){
                $source_item_pr="bapi";
            }else{
                $source_item_pr="database";
            }
        }

        $data_asset=(empty($data_form['GI_ASSET_DETAIL']))? '' : $data_form['GI_ASSET_DETAIL'];

        // restruktur array supaya asset bisa terkelompokkan per item number
        if(!empty($data_asset)){
            $data_asset_new=array();
            foreach($data_asset as $asset_key => $asset_value){
                $data_asset_new[$asset_value['ITEM_NUMBER']]=$asset_value;
            }
            $data_asset=$data_asset_new;
        }
        // END -- CARI DATA DETAIL DARI SAP

        // START -- MENGAMBIL DATA HEADER DAN DETAIL
        $data_header= DB::connection('dbintranet')
        ->table('TBL_PR_HEADER')
        ->select('*')
        ->join('TBL_PR_DOCTYPE','TBL_PR_HEADER.DOCTYPE','=','TBL_PR_DOCTYPE.PRDOCTYPE_CODE')
        ->where('PRNUMBER',$uid)
        ->get();

        $data_warehouse_exist=true;
        if(count($data_header)<1){
            // jika tidak ada data PR di DB warehouse
            $last_approval_status="";
            $data_warehouse_exist=false;


            $data_detail=NULL;
        }else{
            //jika ada data di DB warehhouse
            $last_approval_status=$data_header[0]->LAST_APPROVAL_STATUS;
            $header_id=$data_header[0]->PRHEADER_ID;
            $data_detail= DB::connection('dbintranet')
            ->table('TBL_PR_DETAIL')
            ->where('PRREQUESTHEADER_ID',$header_id)
            ->get();
        }

        // END -- MENGAMBIL DATA HEADER DAN DETAIL


        $data_json=NULL;
        $data_file=NULL;
        if(!empty($uid)){

            $data_form['intranet']=DB::connection('dbintranet')
            ->table('INT_FIN_APPR_RAW_DATA')
            ->leftJoin('INT_FIN_APPR_LIST','INT_FIN_APPR_RAW_DATA.UID','=','INT_FIN_APPR_LIST.FORM_ID')
            ->where('INT_FIN_APPR_RAW_DATA.UID',$uid)
            ->get();


            $data_json = (!empty($data_form['intranet'][0]->JSON_ENCODE))?  json_decode($data_form['intranet'][0]->JSON_ENCODE) : NULL ;

            // START -- MENGAMBIL DATA RELEASE STRATEGY
            try{
                $param = array(
                    'NUMBER'=>$uid
                );
                $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                $sap_release= $function_type->invoke($param, $options);
            }catch(SAPFunctionException $e){
                // dd($e);
                Log::error('ERROR IN DETAIL PURCHASE REQUISITION | '.$e->getMessage());
                $sap_release = [];
            }


            $current_loggedin_employee_id= Session::get('user_id');

            // cari data karyawan requestor di DB warehouse
            $data_requestor=collect(DB::connection('dbintranet')->select("SELECT * FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$requestor_employee_id."'"))->first();
            $midjob_id = isset($data_requestor->MIDJOB_TITLE_ID) ? $data_requestor->MIDJOB_TITLE_ID : '';


            $release_info = collect(isset($sap_release['RELEASE_FINAL'][0]) ? $sap_release['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                // take only description of release strategy code
                return substr($key, 0, 8) == 'REL_CODE';
            })->filter()->toArray();
            $release_code = array_values($release_info);

            $data_release = DB::connection('dbintranet')
                ->table('SAP_PR_RELEASE_CODE_NEW AS ap')
                ->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id, 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id, 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
                })
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->whereNull('MIDJOB_TITLE');
                })
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->whereNull('MIDJOB_TITLE');
                })
                ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                    $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
                })
                ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                    $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
                })
                ->select('ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
                ->get()->toArray();


            $prior_release_approve = array_values(
                collect(
                    isset($sap_release['RELEASE_ALREADY_POSTED'][0]) ? $sap_release['RELEASE_ALREADY_POSTED'][0] : []
                )->filter(function($item, $key){
                    // take only description of release strategy code
                    return substr($key, 0, 8) == 'REL_CODE';
                }
            )->filter()->toArray());


            // Cek yang seharusnya approve selanjutnya
            $now_release_approve = '';
            foreach ($release_code as $key => $value) {
                if (!in_array($value, $prior_release_approve)) {
                    $now_release_approve = $value;
                    break;
                }
            }


            $release_indicator = isset($sap_release['GENERAL_RELEASE_INFO'][0]['REL_IND']) ? $sap_release['GENERAL_RELEASE_INFO'][0]['REL_IND'] : null;
            // DB::enableQueryLog();

            $release_mapping = DB::connection('dbintranet')
            ->table('SAP_PR_RELEASE_CODE_NEW AS ap')
            ->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id])
            ->orWhere(function($query) use ($cost_center, $midjob_id){
                $query->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id]);
            })
            ->orWhere(function($query) use ($cost_center, $midjob_id){
                $query->where(['COSTCENTER'=>$cost_center])
                ->whereNull('MIDJOB_TITLE');
            })
            ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
            })
            ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
            })
            ->select('ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
            ->get()->toArray();



            $release_history = DB::connection('dbintranet')
                ->table('dbo.TBL_PR_RELEASE')
                ->where('PRNUMBER', $uid)
                ->select('RELEASE_CODE_NAME_ID','RELEASEDATE')
                ->get()->pluck('RELEASEDATE','RELEASE_CODE_NAME_ID')->toArray();
            // END -- MENGAMBIL DATA RELEASE STRATEGY

            $approval_history = DB::connection('dbintranet')
                ->table('dbo.TBL_PR_RELEASE')
                ->where('PRNUMBER', $uid)
                ->select('STATUS','RELCODE')
                ->get()->pluck('STATUS','RELCODE')->toArray();


            // START -- VALIDASI APAKAH USER BISA KOMENTAR, GANTI ATTACHMENT, APPROVE/REJECT ATAU CANCEL
            $current_user_release_code=isset(Session::get('assignment')[0]) ? Session::get('assignment')[0]->RELEASE_CODE : '';
            $current_user_id=isset(Session::get('assignment')[0]) ? Session::get('assignment')[0]->EMPLOYEE_ID : '';

            // Cek approver release code dengan mapping terbaru
            $cek_data_pr=DB::connection('dbintranet')
            ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");

            $check_qualified_approver = '';
            // if($cek_data_pr){
                $check_qualified_approver = DB::connection('dbintranet')
                ->table('SAP_PR_RELEASE_CODE_NEW')
                ->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id, 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id, 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
                })
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->whereNull('MIDJOB_TITLE');
                })
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->whereNull('MIDJOB_TITLE');
                })
                ->get()->pluck('RELEASE_CODE')->first();
            // }
            // END Cek approver release code dengan mapping terbaru



            $allow_comment=false;
            $allow_cancel=false;
            $allow_modify_attachment=false;
            $allow_approve_reject=false;


            if(isset($requestor_employee_id) && $requestor_employee_id == $current_user_id){ //jika id requestor form sama dengan id user
                $allow_cancel=true; // yang boleh cancel hanya requestor
                $allow_modify_attachment=true; // yang boleh ubah attachment hanya requester
                $allow_comment=true;
            }
            // END -- VALIDASI APAKAH USER BISA KOMENTAR, GANTI ATTACHMENT, APPROVE/REJECT ATAU CANCEL

            // cek apakah allow atau tidak approve sesuai release strategy
            $is_finished_approval = isset($last_approval_status) && strtoupper($last_approval_status) == 'FINISHED' ? true : false;

            foreach($data_release as $cek_release){
                // if($cek_release->RELCODE == $current_user_release_code){
                if($cek_release->RELEASE_CODE == $check_qualified_approver && !$is_finished_approval){
                    $allow_comment=true; // allow comment jika dia adalah approver
                    $allow_approve_reject=true;
                }
            }



            // START -- CARI KOMENTAR DARI PR
            $query_comments=DB::connection('dbintranet')
            ->table('TBL_PR_COMMENT')
            ->where('PRNUMBER',$uid)
            ->where('IS_DELETED',0)
            ->orderby('PRCOMMENT_ID','ASC')
            ->get();
            $data_comments=array();
            if(count($query_comments)>0){
                foreach($query_comments as $komentar){
                    $data_comment_user=DB::connection('dbintranet')
                    ->table('dbo.VIEW_EMPLOYEE')
                    ->select('IMAGE_PHOTO', 'EMPLOYEE_NAME')
                    ->where('EMPLOYEE_ID',$komentar->EMPLOYEE_ID)
                    ->get();

                    if(!empty($data_comment_user[0]->IMAGE_PHOTO)){
                        $photo=asset('upload/profile_photo/'.$data_comment_user[0]->IMAGE_PHOTO);
                    }else{
                        $photo=asset('image/default-avatar.png');
                    }
                    $parse_comment=array(
                        'comment_id'=>$komentar->PRCOMMENT_ID,
                        'comment_photo'=>$photo,
                        'comment_user'=>$data_comment_user[0]->EMPLOYEE_NAME,
                        'comment_text'=>$komentar->COMMENT,
                        'comment_date'=>$komentar->DATE,
                        'comment_owner_id'=>$komentar->EMPLOYEE_ID,
                        'last_update'=>$komentar->LAST_UPDATED_DATE
                    );

                array_push($data_comments,$parse_comment);

                }
            }
            // END -- CARI KOMENTAR DARI PR
        }

        $data=array(
            'pr_number'=>$uid,
            'current_user_id'=>$current_user_id,
            'current_user_release_code'=>$check_qualified_approver,
            'data_form'=>$data_form,
            'data_form_alt'=>$data_form_alt,
            'data_json'=>$data_json,
            'data_header'=>(isset($data_header[0])) ? $data_header[0] : [],
            'data_detail'=>$data_detail,
            'data_asset'=>$data_asset,
            'data_comments'=>$data_comments,
            'data_release'=>$data_release,
            'allow_comment'=>$allow_comment,
            'allow_cancel'=>$allow_cancel,
            'allow_modify_attachment'=>$allow_modify_attachment,
            'allow_approve_reject'=>$allow_approve_reject,
            'source_item_pr'=>$source_item_pr,
            'nomor_po'=>$nomor_po,
            'data_requestor_sap'=>$data_requestor,
            'last_approval_status'=>$last_approval_status,
            'currency'=>$currency
        );

        if(empty($id)){
            return View::make('pages.finance.purchase-requisition.modal-detail')->with([
                'data'=>$data,
                'release_strategy'=>$release_mapping,
                'prior_release_approve'=>$prior_release_approve,
                'now_release_approve'=>$now_release_approve,
                'release_indicator'=>$release_indicator,
                'current_login_employee'=>$current_user_id,
                'release_history'=>$release_history,
                'release_code_collected'=>$release_code,
                'approval_history'=>$approval_history
            ])->render();
        }else{
            return view('pages.finance.purchase-requisition.detail', [
                'data' => $data,
                'release_strategy'=>$release_mapping,
                'prior_release_approve'=>$prior_release_approve,
                'now_release_approve'=>$now_release_approve,
                'release_indicator'=>$release_indicator,
                'current_login_employee'=>$current_user_id,
                'release_history'=>$release_history,
                'release_code_collected'=>$release_code,
                'approval_history'=>$approval_history
            ]);
        }
    }

    public function save(Request $request){
        try{
            $type_form=$this->form_number;
            $year = date('Y');

            // START -- CARI LAST SEQUENCE FORM
            $last_seq=DB::connection('dbintranet')
                        ->select("SELECT TOP 1 CASE WHEN UID IS NULL THEN NULL ELSE UID END AS LAST_SEQ FROM INT_FIN_APPR_RAW_DATA WHERE TYPE_FORM ='$type_form'  ORDER BY ID DESC ");
            if(!empty($last_seq[0]->LAST_SEQ)){
                $explode_uid=explode('-',$last_seq[0]->LAST_SEQ);
                $nomor_akhir=(int)end($explode_uid);
            }else{
                $nomor_akhir=0;
            }
            $new_seq = $nomor_akhir + 1;
            $uid=$type_form.'-'.$new_seq;

            // END -- CARI LAST SEQUENCE FORM

            $data=$request->post();
            // dd($data);
            unset($data['_token']);
            $data['uid']=$uid;
            $data['file']='';
            $data['currency']=(!empty($data['currency']))? $data['currency'] : 'IDR';

            // START -- UPLOAD FILE - Unused per 19 November 2021
            if(!empty($request->file())){


                $validator = Validator::make($request->all(), [
                    'file' => 'required|max:30720' //30 mb
                ]);

                if ($validator->fails()) {
                    throw new \Exception("Your file size exceeded the limit, maximum file size is 10mb");
                }

                $allowed_extension=array('zip','rar','7zip','gzip','tar');
                $files_upload=$request->file();
                $original_name= $request->file->getClientOriginalName();
                $file_extension = $request->file->extension();
                if(!in_array($file_extension,$allowed_extension)){
                    $success=false;
                    $code = 403;
                    $log=$file_extension;
                    $msg ="Invalid attachment extension, please use the correct one";
                    return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 'log' => $log, 200));
                    die;
                }
                $imageName = time().'.'.$original_name;
                $request->file->move(public_path('upload/purchase_requisition'), $imageName);
                $data['file']=$imageName;
            }
            // END -- UPLOAD FILE


            // START -- INIT RFC
            $is_production = config('intranet.is_production');
            if($is_production){
                $rfc = new SapConnection(config('intranet.rfc_prod'));
            }else{
                $rfc = new SapConnection(config('intranet.rfc'));
            }
            $options = [
                'rtrim'=>true
            ];
            // END -- INIT RFC


            // START -- KEPERLUAN UNTUK SAVE KE SAP
            $GiItemText = array(); //init variable
            $GiReqAccAss = array(); //init variable
            $GiReqItems = array(); //init variable
            $GiReturn =array(); //init variable

            $cost_center = strtoupper($request->input("cost_center")); //keperluan untuk cari approval
            @$midjob = Session::get('assignment')[0]->MIDJOB_TITLE_ID; //keperluan untuk cari approval

            $division = strtoupper($request->input("Requestor_Division"));
            $docType = strtoupper($request->input("doc_type"));
            $department = strtoupper($request->input("Requestor_Department"));
            $plant = strtoupper($request->input("plant"));
            $territory = strtoupper($request->input("Requestor_Territory_ID"));
            $requestedBy = strtoupper($request->input("Requestor_Employee_ID"));
            $requesterName = strtoupper($request->input("Requestor_Name"));
            $companycode = strtoupper($request->input("Requestor_Company_Code"));
            $jobPosition = strtoupper($request->input("Requestor_Job_Title"));
            $dateRequest = strtoupper($request->input("Request_Date"));
            $deliveryDate = strtoupper($request->input("deliveryDate"));
            $vendor = strtoupper($request->input("vendor_code"));
            $vendor2 = strtoupper($request->input("vendor_name"));
            $mobileVendor = strtoupper($request->input("vendor_mobile"));
            $addressVendor = strtoupper($request->input("vendor_address"));
            $phoneVendor = strtoupper($request->input("vendor_phone"));
            $contactPersonVendor = strtoupper($request->input("vendor_cp"));
            $emailVendor = strtoupper($request->input("vendor_email"));
            $faxVendor = strtoupper($request->input("vendor_fax"));
            $tableRow = strtoupper($request->input("tableRow"));
            $purpose = strtoupper($request->input("purpose"));
            // dd($request->input('costCenter'));
            // dd($request->tableRow,$request->tableRow, count($request->preqItem));

            $material_purpose = count($request->input('materialPurpose'));
            for($i=0;$i<(int)$tableRow-1;$i++){
                //GiItemText
                // $TextLine='';
                // if($j==1){
                // $TextLine=(isset($request->input('materialPurpose')[$i]) ? strtoupper($request->input('materialPurpose')[$i]) : 'No Purpose Text')." - (Info) ".strtoupper($request->input('additionalInfo')[$i]);
                $TextLine=(isset($request->input('materialPurpose')[$i]) ? strtoupper($request->input('materialPurpose')[$i]) : 'No Purpose Text')." - (Info) ".strtoupper($purpose);
                // }
                $GiItemText[]=array(
                    'PREQ_NO'=>'',
                    'PREQ_ITEM'=>strtoupper($request->input('preqItem')[$i]),
                    'TEXT_ID'=>'B01',
                    'TEXT_FORM'=>'',
                    'TEXT_LINE'=>$TextLine
                );

                // echo $request->input('preqItem')[$i].' - '.$request->input('costCenter')[$i];
                //GiReqAccAss
                $item_costCenter=(!empty($request->input('costCenter')[$i]))? strtoupper($request->input('costCenter')[$i]) : '';
                $item_assetNo=(!empty($request->input('assetNo')[$i]))? strtoupper($request->input('assetNo')[$i]) : '';
                $item_orderNo=(!empty($request->input('orderNo')[$i]))? strtoupper($request->input('orderNo')[$i]) : '';
                $GiReqAccAss[]=array(
                    'PREQ_ITEM'=>strtoupper($request->input('preqItem')[$i]),
                    'G_L_ACCT'=>'',
                    'BUS_AREA'=>'',
                    'COST_CTR'=>$item_costCenter,
                    'ASSET_NO'=>$item_assetNo,
                    'ORDER_NO'=>$item_orderNo,
                    'SERIAL_NO'=>"01",
                    'PREQ_QTY'=>(float)$request->input('quantity')[$i],
                );

                $deliv_date=$request->input('delivDate')[$i];
                if(empty($deliv_date)){
                    $deliv_date= date('Ymd',strtotime($dateRequest));
                }else{
                    $deliv_date= date('Ymd',strtotime($request->input('delivDate')[$i]));
                }

                //validasi untuk document Type YOCN
                $item_cat=(strtoupper($docType) == "YOCN")? '2' : '';

                //validasi jika harga yang dimasukkan adalah 0
                $harga_item = ($request->input('cAmitBapi')[$i] < 1 || empty($request->input('cAmitBapi')[$i])) ? 1 : $request->input('cAmitBapi')[$i];
                $last_purchase_price = isset($request->input('cAmitBapx')[$i]) ? (float)str_replace(',', '', $request->input('cAmitBapx')[$i]) : 0;

                $gr_non_val= strtoupper((isset($request->input('acctasscat')[$i]) && $request->input('acctasscat')[$i] =='4' )? 'X' : '');

                // Cek tipe account assignment
                if(isset($request->input('acctasscat')[$i]) && $request->input('acctasscat')[$i] == '0') {
                    $cost_center_type = DB::connection('dbintranet')
                    ->table('dbo.INT_SAP_COST_CENTER')
                    ->where('SAP_COST_CENTER_ID', $cost_center)
                    ->select('EXPENSE_TYPE')
                    ->get()->pluck('EXPENSE_TYPE')->first();
                    if(!$cost_center_type){
                        return response()->json(array('success' => false, 'msg' => 'Cannot determine type of expense for Cost Center '.$cost_center.', please check the data and try again', 'code' => '403', 'log' => '', 'insert_id'=> '', 200));
                    }

                    $acctasscat = strtoupper((String)$cost_center_type);
                } else {
                    $acctasscat = strtoupper(isset($request->input('acctasscat')[$i]) ? $request->input('acctasscat')[$i] : '');
                }

                $GiReqItems[]=array(
                    'PREQ_ITEM'=>strtoupper($request->input('preqItem')[$i]),
                    'DOC_TYPE'=>strtoupper($docType),
                    'PUR_GROUP'=>'',
                    'PREQ_NAME'=>strtoupper($requestedBy),
                    'PREQ_DATE'=>date('Ymd',strtotime($dateRequest)),
                    'MATERIAL'=>strtoupper($request->input('materials')[$i]),
                    'PLANT'=>strtoupper($plant),
                    'MAT_GRP'=>'',
                    'QUANTITY'=>(float)$request->input('quantity')[$i],
                    'UNIT'=>strtoupper($request->input('unit')[$i]),
                    'STORE_LOC'=>strtoupper($request->input('item_sloc')[$i]),
                    'DELIV_DATE'=>$deliv_date,
                    'PRICE_UNIT'=>1,
                    'ACCTASSCAT'=>$acctasscat,
                    // 'FIXED_VEND'=>strtoupper($vendor),
                    'CURRENCY'=>strtoupper($request->input('currency')),
                    'GR_IND'=>'X',
                    'GR_NON_VAL'=>$gr_non_val,
                    'IR_IND'=>'X',
                    'PURCH_ORG'=>'',
                    'VAL_TYPE'=>'',
                    'TRACKINGNO'=>strtoupper($request->input('trackingNo')[$i]),
                    'PO_PRICE'=>'',
                    'CMMT_ITEM'=>strtoupper($request->input('cmmtItem')[$i]),
                    'ITEM_CAT'=>$item_cat
                );

                if($last_purchase_price > 0){
                    if((float)$request->input('quantity')[$i] > 0){
                        $last_purchase_price = $last_purchase_price / (float)$request->input('quantity')[$i];
                        $GiReqItems[$i]['C_AMT_BAPI'] = (int)$last_purchase_price;
                    }
                } else if($harga_item == 1 || $harga_item == '1'){
                    $GiReqItems[$i]['C_AMT_BAPI'] = (int)$harga_item;
                }

            }

            $GiReturn[]=array(
                'TYPE'=>'',
                'CODE'=>'',
                'MESSAGE'=>'',
                'LOG_NO'=>'',
                'LOG_MSG_NO'=>'',
                'MESSAGE_V1'=>'',
                'MESSAGE_V2'=>'',
                'MESSAGE_V3'=>'',
                'MESSAGE_V4'=>''
            );

            $GiHeaderText[] = array(
                'PREQ_NO'=>'',
                'PREQ_ITEM'=>'',
                'TEXT_ID'=>'B01',
                'TEXT_FORM'=>'',
                'TEXT_LINE'=>$purpose
            );

            $param = array(
                'GI_REQ_ITEMS'=>$GiReqItems,
                'GI_REQ_ACC_ASS'=>$GiReqAccAss,
                // 'GI_RETURN'=>$GiReturn,
                'GI_ITEM_TEXT'=>$GiItemText,
                'GI_HEADER_TEXT'=>$GiHeaderText
            );

            $function_type = $rfc->getFunction('ZFM_PR_CREATE_INTRA_MID');
            $save_sap= $function_type->invoke($param, $options);
            // END -- KEPERLUAN UNTUK SAVE KE SAP

            $result_sap = $save_sap['GI_RETURN'];

            if(!empty($save_sap['GV_NUMBER'])){

                    $uid=$save_sap['GV_NUMBER']; // nomor PR yang ter create di SAP
                    $data['uid']=$uid;


                    // ------------------------------------------------------------ IMPORTANT
                    // * proses untuk override attachment supaya mengikuti dari URL yang digenerate
                    $append_link=($is_production)? "PR-" : "PR-DEV-";
                    $link_attachment_sap = 'https://sap-intranet.ayana.id/Attachment?folder='.$append_link.$uid."&year=".date('Y')."";
                    $data['file']=$link_attachment_sap; // override data attachment

                    if($is_production){
                        $url="http://10.192.2.181:900/APIPublish/api/UploadPRURL"; // prod
                    }else{
                        $url="http://10.192.2.181:860/APIPublish/api/UploadPRURL"; // dev
                    }

                    //start untuk upload attachment URL ke SAP

                    $client = new Client();
                    $response = $client->post($url, [
                        RequestOptions::JSON => ['BANFN' => $uid, 'TITLE' => 'Default Attachment', 'URL'=> $link_attachment_sap] // or 'json' => [...]
                    ]);
                    // $body = $response->getBody();
                    // echo $body;
                    // echo $response->getStatusCode(); // 200
                    // echo $response->getReasonPhrase(); // OK
                    // echo $response->getProtocolVersion(); // 1.1

                    // ------------------------------------------------------------ END IMPORTANT

                    // START -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA
                    $data_json=json_encode($data);
                    $employee_id=$data['Requestor_Employee_ID'];
                    $type="Request Purchase Requisition";

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
                    // END -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA


                    // START -- KEBUTUHAN INSERT DATA KE TABEL PR_RELEASE
                    $data_release=$save_sap['GW_RELEASE'];
                    for($i=1;$i<=8;$i++){
                        DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->insert(
                            [
                                "RELCODE" => $data_release['REL_CODE'.$i],
                                "RELCODEDESC" => $data_release['REL_CD_TX'.$i],
                                "RELCODENAME" =>NULL,
                                "STATUS" => NULL,
                                "RELEASEDATE" => NULL,
                                "PRNUMBER" => $uid,
                                "REASON" =>NULL,
                                "RELEASE_CODE_NAME_ID"=>NULL
                            ]
                        );
                    }

                    // END -- KEBUTUHAN INSERT DATA KE TABEL PR_RELEASE
                    DB::enableQueryLog();
                    // START -- KEBUTUHAN INSERT DATA KE TABEL PR_HEADER
                    $id_pr_header= DB::connection('dbintranet')
                        ->table('TBL_PR_HEADER')
                        ->insertGetId(
                            [
                                "PRNUMBER" =>$uid,
                                "DOCTYPE" =>$data['doc_type'],
                                "PLANT" =>$data['plant'],
                                "REQUESTEDBY" =>$data['Requestor_Employee_ID'],
                                "REQUESTEDDATE" =>date('Y-m-d H:i:s'),
                                "DIVISION" =>$data['Requestor_Division'],
                                "DEPARTMENT" =>$data['Requestor_Department'],
                                "TERRITORY" =>$data['Requestor_Territory'],
                                "ESTIMATEDDELIVERYTIME" =>$data['delivDate'][0],
                                "VENDOR_ID" =>$data['vendor_code'],
                                "VENDOR_NAME" =>$data['vendor_name'],
                                "VENDOR_ADDRESS" =>$data['vendor_address'],
                                "VENDOR_MOBILE" =>$data['vendor_mobile'],
                                "VENDOR_CONTACTPERSON" =>$data['vendor_cp'],
                                "VENDOR_PHONE" =>$data['vendor_phone'],
                                "VENDOR_FAX" =>$data['vendor_fax'],
                                "VENDOR_EMAIL" =>$data['vendor_email'],
                                "REQUESTOR_EMPLOYEE_ID" =>$data['Requestor_Employee_ID'],
                                "REQUESTOR_COST_CENTER_ID" =>$data['Requestor_Cost_Center_ID'],
                                "PURPOSE" =>$data['purpose'],
                                "ATTACHMENT" =>$data['file'],
                                "REASON_VENDOR" =>$data['reason'],
                                "PPN" =>@$data['ppn'],
                                "DP" =>''
                            ]
                        );
                    // END -- KEBUTUHAN INSERT DATA KE TABEL PR_HEADER

                    // START -- KEBUTUHAN INSERT DATA KE TABEL PR_DETAIL
                    for($i=0;$i<$tableRow-1;$i++){
                        $item_costCenter=(!empty($request->input('costCenter')[$i]))? strtoupper($request->input('costCenter')[$i]) : '';
                        $item_assetNo=(!empty($request->input('assetNo')[$i]))? strtoupper($request->input('assetNo')[$i]) : '';
                        $item_orderNo=(!empty($request->input('orderNo')[$i]))? strtoupper($request->input('orderNo')[$i]) : '';

                        DB::connection('dbintranet')
                        ->table('TBL_PR_DETAIL')
                        ->insert(
                            [
                                "ITEM" => strtoupper($request->input('preqItem')[$i]),
                                "MATERIAL" => strtoupper($request->input('materials')[$i]),
                                "MATERIALDESC" => strtoupper($request->input('materialDesc')[$i]),
                                "QUANTITY" => (float)$request->input('quantity')[$i],
                                "UNIT" => strtoupper($request->input('unit')[$i]),
                                "AMOUNT" => (int)$request->input('cAmitBapi')[$i],
                                "CURRENCY" => strtoupper($request->input('currency')),
                                "ACCOUNT_ASSIGNMENT" => strtoupper($request->input('acctasscat')[$i]),
                                "COST_CENTER" => $item_costCenter,
                                "ASSET_NUMBER" => $item_assetNo,
                                "ORDER_NUMBER" => $item_orderNo,
                                "TRACKING_NUMBER" => strtoupper($request->input('trackingNo')[$i]),
                                "COMMITMENT_ITEM" => strtoupper($request->input('cmmtItem')[$i]),
                                "FUNDS_CURRENCY" => strtoupper($request->input('fundsCurr')[$i]),
                                "PRREQUESTHEADER_ID" => $id_pr_header,
                                "REMAINBUDGET_MONTH" =>strtoupper($request->input('amountTxt')[$i]),
                                "REMAINBUDGET_YEAR" => strtoupper($request->input('amountYearTxt')[$i]),
                                "PRICE_UNIT" => ''
                            ]
                        );
                    }
                    // END -- KEBUTUHAN INSERT DATA KE TABEL PR_DETAIL


                    // START -- Kirim notifikasi ke approver
                    $approver=DB::connection('dbintranet')
                    ->select("
                        SELECT
                            A.RELEASE_CODE,
                            A.COSTCENTER,
                            A.MIDJOB_TITLE,
                            C.EMPLOYEE_ID AS APP_EMPLOYEE_ID,
                            C.EMPLOYEE_NAME AS APP_EMPLOYEE_NAME,
                            D.EMPLOYEE_ID AS ALT_EMPLOYEE_ID,
                            D.EMPLOYEE_NAME AS ALT_EMPLOYEE_NAME
                        FROM
                            SAP_PR_RELEASE_CODE_NEW A
                            INNER JOIN TBL_PR_RELEASE B ON A.RELEASE_CODE = B.RELCODE
                            LEFT JOIN INT_EMPLOYEE C ON A.APP_EMPLOYEE_ID = C.EMPLOYEE_ID
                            LEFT JOIN INT_EMPLOYEE D ON A.ALT_EMPLOYEE_ID = D.EMPLOYEE_ID
                        WHERE
                            (
                            B.PRNUMBER = '".$uid."'
                            AND A.COSTCENTER = '".$cost_center."'
                            AND A.MIDJOB_TITLE = '".$midjob."'
                            )
                            OR
                            (
                            B.PRNUMBER = '".$uid."'
                            AND A.COSTCENTER = '".$cost_center."'
                            AND A.MIDJOB_TITLE IS NULL
                            )
                    ");

                    if(isset($approver[0]) && !empty($approver)){
                        @$employee_id_approver = (!empty($approver[0]->APP_EMPLOYEE_ID))? $approver[0]->APP_EMPLOYEE_ID : $approver[0]->ALT_EMPLOYEE_ID;

                        if(!empty($employee_id_approver)){
                            $notif_link="/finance/purchase-requisition/detail/".$uid;
                            $notif_desc="Please approve Purchase Requisition : ".$uid."";
                            $notif_type="info";

                            insertNotification($employee_id_approver, $notif_link, $notif_desc, $notif_type);
                        }
                    }

                    // END -- Kirim notifikasi ke approver

                    $success=true;
                    $code = 200;
                    $log=$save_sap;
                    $msg = 'Your request has been sent';
                    $insert_id=$uid;
            }else{
                $success=false;
                $code = 403;
                $log=$save_sap;
                $insert_id="";
                $msg="";
                foreach($result_sap as $loop_error){
                    $msg.=(isset($loop_error['MESSAGE']) && !empty($loop_error['MESSAGE']))? $loop_error['MESSAGE'].' ' : '';
                }
                if(strpos($msg,"not maintain")){
                    $msg .= ' Please contact IT Administrator';
                }

                $msg=(empty($msg))? 'Something wrong with the SAP Integration, please try again later or contact IT Dept' : $msg;

            }



        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $log="";
            $msg = $e->errorInfo;
            $insert_id="";
        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 'log' => $log, 'insert_id'=> $insert_id, 200));
    }

    public function save_comment(Request $request){
        // =============
        // cari data sequence dari FORM
        try{

            $id_form=$request->input('id');
            $id_user=$request->input('id_user');
            $comment=$request->input('text');


            if(!empty($id_form)){


                $insert=DB::connection('dbintranet')
                ->table('TBL_PR_COMMENT')
                ->insert(
                    [
                        "DATE" => date('Y-m-d H:i:s'),
                        "PRNUMBER" => $id_form,
                        "COMMENT" => $comment,
                        "EMPLOYEE_ID" => $id_user
                    ]
                );


                $success=true;
                $code = 200;
                $msg = 'Your comment has been added';

                if(!empty(app('user_login')->IMAGE_PHOTO)){
                    $photo=asset('upload/profile_photo/'.app('user_login')->IMAGE_PHOTO);
                }else{
                    $photo=asset('image/default-avatar.png');
                }

                $html='<div class="comment-container col-md-12 float-left" >
                    <div class="float-left col-md-2">
                        <div class="comment-user">
                            <img src="'.$photo.'" alt="">
                            <p><b>'.app('user_login')->EMPLOYEE_NAME.'</b></p>
                        </div>
                    </div>
                    <div class="float-left col-md-10">
                        <div class="comment-text">
                            <p><small>'.date('d F Y - H:i').'</small></p>
                            <p>'.$comment.'</p>
                        </div>
                    </div>
                </div>';
            }

        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
            $html="";

        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'html'=>$html, 'code' => $code, 200));
    }

    public function update_attachment(Request $request){
        // =============
        // cari data sequence dari FORM
        try{

            $id_form=$request->input('id');

              /* upload file */
            if(!empty($request->file())){
                    $files_upload=$request->file();
                    $original_name= $request->file->getClientOriginalName();
                    $imageName = time().'.'.$original_name; //name nya
                    $request->file->move(public_path('upload/purchase_requisition'), $imageName);
            }


            if(!empty($id_form)){

                $insert=DB::connection('dbintranet')
                ->table('TBL_PR_HEADER')
                ->where('PRNUMBER',$id_form)
                ->update(
                    [
                        "ATTACHMENT" => $imageName
                    ]
                );
                $success=true;
                $code = 200;
                $msg = 'Your attachment has been updated';

                $html='<a target="_blank" href="'.asset("upload/purchase_requisition/".$imageName).'">'.$imageName.'</a>';
            }

        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
            $html="";

        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'html'=>$html, 'code' => $code, 200));
    }

    public function update_comment(Request $request){

        try{

            $id=$request->input('id');
            $comment=$request->input('comment');

            if(!empty($id)){

                $insert=DB::connection('dbintranet')
                ->table('TBL_PR_COMMENT')
                ->where('PRCOMMENT_ID',$id)
                ->update(
                    [
                        "COMMENT" =>  $comment,
                        "LAST_UPDATED_DATE"=> date('Y-m-d H:i:s')
                    ]
                );
                $success=true;
                $code = 200;
                $msg = 'Your comment has been updated';

                $html=$comment;
            }

        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
            $html="";
            $id="";

        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'html'=>$html, 'id' => $id, 'code' => $code, 200));
    }

    public function delete_comment(Request $request){

        try{

            $id=$request->input('id');

            if(!empty($id)){

                $insert=DB::connection('dbintranet')
                ->table('TBL_PR_COMMENT')
                ->where('PRCOMMENT_ID',$id)
                ->update(
                    [
                        "IS_DELETED" =>  1,
                        "IS_DELETED_DATE"=> date('Y-m-d H:i:s')
                    ]
                );
                $success=true;
                $code = 200;
                $msg = 'Your comment has been deleted';
            }

        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
            $id="";

        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'id' => $id, 'code' => $code, 200));
    }

    public function approve(Request $request){
        // =============
        // cari data sequence dari FORM
        // START -- INIT RFC
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc'));
        }
        $options = [
            'rtrim'=>true
        ];
        // END -- INIT RFC

        try{
            $prnumber=$request->input('uid');
            $release_code=$request->input('relcode');
            $employee_id = Session::get('assignment')[0]->EMPLOYEE_ID;
            $current_loggedin_employee_id=Session::get('user_id');

            // if(!empty($prnumber) && !empty($release_code)){
            if(!empty($prnumber)){
                // Cek release code dari mapping approver
                $cek_data_pr=DB::connection('dbintranet')
                ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$prnumber' ");

                try{
                    $param = array(
                        'GV_NUMBER'=>$prnumber
                    );
                    $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                    $data_form= $function_type->invoke($param, $options);
                }catch(SAPFunctionException $e){
                   throw new \Exception("There is problem connection with SAP RFC : ZFM_MID_PR_GET_DETAIL_INTRA, please try again later or contact IT Department");
                }

                $requestor_employee_id = (!empty($data_form['GI_ITEMS'][0]['PREQ_ID']))? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;

                if(!$cek_data_pr){
                    // throw new \Exception("Purchase Requisition data is not found in data warehouse");
                    // cari data karyawan requestor di DB warehouse
                    $data_requestor=collect(DB::connection('dbintranet')->select("SELECT * FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$requestor_employee_id."'"))->first();
                    $midjob_id = isset($data_requestor->MIDJOB_TITLE_ID) ? $data_requestor->MIDJOB_TITLE_ID : '';
                    $cost_center = (!empty($data_form['GI_ITEMS'][0]['TRACKINGNO']))? $data_form['GI_ITEMS'][0]['TRACKINGNO'] : NULL;
                }else{
                    $midjob_id = isset($cek_data_pr[0]->MIDJOB_TITLE_ID) ? $cek_data_pr[0]->MIDJOB_TITLE_ID : '';
                    $cost_center = isset(json_decode($cek_data_pr[0]->JSON_ENCODE)->cost_center) ? json_decode($cek_data_pr[0]->JSON_ENCODE)->cost_center : '';
                }

                $doctype=(isset($data_form['GI_ITEMS'][0]['DOC_TYPE']) && !empty($data_form['GI_ITEMS'][0]['DOC_TYPE'])) ? $data_form['GI_ITEMS'][0]['DOC_TYPE'] : NULL;
                $preq_id=(isset($data_form['GI_ITEMS'][0]['PREQ_ID']) && !empty($data_form['GI_ITEMS'][0]['PREQ_ID'])) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                $plant=(isset($data_form['GI_ITEMS'][0]['PLANT']) && !empty($data_form['GI_ITEMS'][0]['PLANT'])) ? $data_form['GI_ITEMS'][0]['PLANT'] : NULL;
                $preq_date=(isset($data_form['GI_ITEMS'][0]['PREQ_DATE']) && !empty($data_form['GI_ITEMS'][0]['PREQ_DATE'])) ? date('m/d/Y',strtotime($data_form['GI_ITEMS'][0]['PREQ_DATE'])) : NULL;



                $check_qualified_approver = DB::connection('dbintranet')
                ->table('SAP_PR_RELEASE_CODE_NEW')
                ->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id, 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id, 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id]);
                })
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'APP_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->whereNull('MIDJOB_TITLE');
                })
                ->orWhere(function($query) use ($cost_center, $midjob_id, $current_loggedin_employee_id){
                    $query->where(['COSTCENTER'=>$cost_center, 'ALT_EMPLOYEE_ID'=>$current_loggedin_employee_id])
                    ->whereNull('MIDJOB_TITLE');
                })
                ->get()->pluck('RELEASE_CODE')->first();


                if(!$check_qualified_approver)
                    throw new \Exception("Cannot find any release code for current employee, please check the data");
                // End Cek release code dari mapping approver



                // START -- APPROVE KE SAP
                $param = array(
                    'GV_NUMBER'=>$prnumber,
                    // 'GV_REL_CODE'=>$release_code
                    'GV_REL_CODE'=>$check_qualified_approver

                );

                $function_type = $rfc->getFunction('ZFM_PR_RELEASE_INTRA');
                $approve= $function_type->invoke($param, $options);

                $new_indicator=$approve['GV_REL_INDICATOR_NEW'];
                // $new_status=$approve['GV_REL_STATUS_NEW'];
                $new_status=isset($approve['GV_REL_STATUS_NEW']) ? $approve['GV_REL_STATUS_NEW'] : '';
                $next_approval=$approve['GV_REL_CODE_NEXT'];


                //if($new_status=="X" || $new_status=="XX" || $new_status=="XXX" || $new_status=="XXXX"){// jika GV_REL_STATUS_NEW = X maka artinya approve success
                if(!empty($new_status)) {// jika GV_REL_STATUS_NEW = X maka artinya approve success

                    //cek apakah dia sudah finish atau masih lanjut
                    if(empty($next_approval) || $next_approval==""){
                        $last_approve_status="FINISHED";

                    }else{
                        $last_approve_status="APPROVED";
                    }

                    // cek jika ada  di table HEADER
                    $itung_pr_header=DB::connection('dbintranet')
                    ->table('TBL_PR_HEADER')
                    ->select('PRNUMBER')
                    ->where('PRNUMBER',$prnumber)
                    ->count();

                    if($itung_pr_header>0){
                        // jika ada di table header, maka update statusnya
                        DB::connection('dbintranet')
                        ->table('TBL_PR_HEADER')
                        ->where('PRNUMBER',$prnumber)
                        ->update(
                            [
                                "LAST_APPROVAL_STATUS"=>$last_approve_status,
                                "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                                "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                                "LAST_APPROVAL_REASON"=>""
                            ]
                        );
                    }else{
                        // jika tidak ada, maka insert (karena datang dari SAP)
                        DB::connection('dbintranet')
                        ->table('TBL_PR_HEADER')
                        ->insert(
                            [
                                "PRNUMBER"=>$prnumber,
                                "DOCTYPE"=>$doctype,
                                "PLANT"=>$plant,
                                "REQUESTEDBY"=>$preq_id,
                                "REQUESTEDDATE"=>$preq_date,
                                "LAST_APPROVAL_STATUS"=>$last_approve_status,
                                "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                                "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                                "LAST_APPROVAL_REASON"=>""
                            ]
                        );

                        //tambahan juga harus input di tabel INT APPR RAW DATA
                        $itung_pr_raw=DB::connection('dbintranet')
                        ->table('INT_FIN_APPR_RAW_DATA')
                        ->select('UID')
                        ->where('UID',$prnumber)
                        ->count();

                        if($itung_pr_raw<1){
                            DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_RAW_DATA')
                            ->insert(
                                [
                                    "JSON_ENCODE"=>"{}",
                                    "TYPE"=>"Request Purchase Requisition",
                                    "INSERT_DATE"=>$preq_date,
                                    "UID"=>$prnumber,
                                    "EMPLOYEE_ID"=>$preq_id,
                                    "TYPE_FORM"=>"PURCHASE-REQUISITION",
                                    "FULLFILL_ZOHO"=>""
                                ]
                            );
                        }

                    }

                    // cek jika ada  di table RELEASE

                    $itung_pr_release=DB::connection('dbintranet')
                    ->table('TBL_PR_RELEASE')
                    ->select('PRNUMBER')
                    ->where('PRNUMBER',$prnumber)
                    ->where('RELCODE',$check_qualified_approver)
                    ->count();

                    if($itung_pr_release>0){
                        // jika ada di table release, maka update statusnya
                        DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->where('PRNUMBER',$prnumber)
                        ->where('RELCODE',$check_qualified_approver)
                        ->update(
                            [
                                "STATUS"=>"APPROVED",
                                "RELEASEDATE"=>date('Y-m-d H:i:s'),
                                "RELEASE_CODE_NAME_ID"=>$employee_id
                            ]
                        );
                    }else{
                        // jika tidak ada, maka insert (karena datang dari SAP)
                        try{
                            $param = array(
                                'NUMBER'=>$prnumber
                            );
                            $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                            $sap_release= $function_type->invoke($param, $options);
                        }catch(SAPFunctionException $e){
                            throw new \Exception("Cannot find release strategy on SAP");
                        }

                        // insert ke table pr release
                        $data_release=$sap_release['RELEASE_FINAL'][0];
                        for($i=1;$i<=8;$i++){
                            DB::connection('dbintranet')
                            ->table('TBL_PR_RELEASE')
                            ->insert(
                                [
                                    "RELCODE" => $data_release['REL_CODE'.$i],
                                    "RELCODEDESC" => $data_release['REL_CD_TX'.$i],
                                    "RELCODENAME" =>NULL,
                                    "STATUS" => NULL,
                                    "RELEASEDATE" => NULL,
                                    "PRNUMBER" => $prnumber,
                                    "REASON" =>NULL,
                                    "RELEASE_CODE_NAME_ID"=>NULL
                                ]
                            );
                        }

                        //lalu update
                        DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->where('PRNUMBER',$prnumber)
                        ->where('RELCODE',$check_qualified_approver)
                        ->update(
                            [
                                "STATUS"=>"APPROVED",
                                "RELEASEDATE"=>date('Y-m-d H:i:s'),
                                "RELEASE_CODE_NAME_ID"=>$employee_id
                            ]
                        );
                    }



                    // ========================================================================= START NOTIFICATION

                    if($last_approve_status=="FINISHED"){
                        //start insert notifikasi ke user jika PR telah selesai
                        $requestor=DB::connection('dbintranet')
                        ->select("SELECT REQUESTEDBY FROM TBL_PR_HEADER WHERE PRNUMBER='".$prnumber."'");

                        @$requestor_id=(!empty($requestor[0]->REQUESTEDBY))? $requestor[0]->REQUESTEDBY : NULL;

                        if(empty($requestor_id)){
                            //jika kosong, maka cari dari RFC get detail PR
                            $param = array(
                                'GV_NUMBER'=>$prnumber
                            );
                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $data_form= $function_type->invoke($param, $options);
                            $requestor_id=(!empty($data_form['GI_ITEMS'][0]['PREQ_ID'])) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                        }

                        if(!empty($requestor_id)){
                            $notif_link="/finance/purchase-requisition/detail/".$prnumber;
                            $notif_desc="Your Purchase Requisition : ".$prnumber." is approved";
                            $notif_type="approve";
                            insertNotification($requestor_id, $notif_link, $notif_desc, $notif_type);
                        }

                    }elseif($last_approve_status=="APPROVED"){
                        //start insert notifikasi ke user selanjutnya untuk diapprove

                        $uid = $prnumber;
                        try{
                            $param = array(
                                'NUMBER'=>$uid
                            );
                            $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                            $sap_release= $function_type->invoke($param, $options);
                        }catch(SAPFunctionException $e){
                            return response()->json(array('success' => false, 'msg' => $e->getMessage(), 'code' => 403, 403));
                        }

                        try{
                            $param = array(
                                'GV_NUMBER'=>$uid
                            );
                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $data_form= $function_type->invoke($param, $options);
                        }catch(SAPFunctionException $e){
                            return response()->json(array('success' => false, 'msg' => $e->getMessage(), 'code' => 403, 403));
                        }

                        $cost_center = (!empty($data_form['GI_ITEMS'][0]['TRACKINGNO']))? $data_form['GI_ITEMS'][0]['TRACKINGNO'] : NULL;
                        $requestor_employee_id = (!empty($data_form['GI_ITEMS'][0]['PREQ_ID']))? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;

                        // cari data karyawan requestor di DB warehouse
                        $data_requestor=collect(DB::connection('dbintranet')->select("SELECT * FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$requestor_employee_id."'"))->first();
                        $midjob_id = isset($data_requestor->MIDJOB_TITLE_ID) ? $data_requestor->MIDJOB_TITLE_ID : '';


                        $release_info = collect(isset($sap_release['RELEASE_FINAL'][0]) ? $sap_release['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                            // take only description of release strategy code
                            return substr($key, 0, 8) == 'REL_CODE';
                        })->filter()->toArray();
                        $release_code = array_values($release_info);

                        $prior_release_approve = array_values(
                            collect(
                                isset($sap_release['RELEASE_ALREADY_POSTED'][0]) ? $sap_release['RELEASE_ALREADY_POSTED'][0] : []
                            )->filter(function($item, $key){
                                // take only description of release strategy code
                                return substr($key, 0, 8) == 'REL_CODE';
                            }
                        )->filter()->toArray());


                        // Cek yang seharusnya approve selanjutnya
                        $now_release_approve = '';
                        foreach ($release_code as $key => $value) {
                            if (!in_array($value, $prior_release_approve)) {
                                $now_release_approve = $value;
                                break;
                            }
                        }

                        $release_mapping = DB::connection('dbintranet')
                            ->table('SAP_PR_RELEASE_CODE_NEW AS ap')
                            ->where(['ap.RELEASE_CODE'=>$now_release_approve,'COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id])
                            ->orWhere(function($query) use ($cost_center, $midjob_id, $now_release_approve){
                                $query->where(['ap.RELEASE_CODE'=>$now_release_approve,'COSTCENTER'=>$cost_center, 'MIDJOB_TITLE'=>$midjob_id]);
                            })
                            ->orWhere(function($query) use ($cost_center, $midjob_id,$now_release_approve){
                                $query->where(['ap.RELEASE_CODE'=>$now_release_approve,'COSTCENTER'=>$cost_center])
                                ->whereNull('MIDJOB_TITLE');
                            })
                            ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                                $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
                            })
                            ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                                $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
                            })
                            ->select('ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
                            ->get()->toArray();

                        if(!empty($release_mapping[0])){
                            @$requestor_id=(!empty($release_mapping[0]['EMPLOYEE_ID']))? $release_mapping[0]['EMPLOYEE_ID'] : $release_mapping[0]['ALT_EMPLOYEE_ID'];
                            if(!empty($requestor_id)){
                                // start input notifikasi
                                $notif_link="/finance/purchase-requisition/detail/".$prnumber;
                                $notif_desc="Please approve Purchase Requisition : ".$prnumber."";
                                $notif_type="info";
                                insertNotification($requestor_id, $notif_link, $notif_desc, $notif_type);
                            }
                        }

                    }
                    // ========================================================================= END NOTIFICATION

                    $success=true;
                    $code = 200;
                    $msg = 'PR Request has been Approved';
                }else{
                    $success=false;
                    $code = 403;
                    @$msg = "SAP Feedback is not recognized, GV_REL_STATUS_NEW value is empty";
                }

            } else {
                $success=false;
                $code = 403;
                $msg = "PR Number is not recognized, please check the data sent by intranet";
            }


        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));
    }

    public function reject(Request $request){
        // =============
        // cari data sequence dari FORM
        try{

            $prnumber=$request->input('uid');
            $reason=$request->input('reason');
            $release_code=$request->input('relcode');
            $employee_id = Session::get('assignment')[0]->EMPLOYEE_ID;
            $type=$request->input('type');

            if(!empty($prnumber)){

                //init RFC
                $is_production = config('intranet.is_production');
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc'));
                }
                $options = [
                    'rtrim'=>true
                ];
                //===


                try{
                    $param = array(
                        'GV_NUMBER'=>$prnumber
                    );
                    $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                    $data_form= $function_type->invoke($param, $options);
                }catch(SAPFunctionException $e){
                   throw new \Exception("There is problem connection with SAP RFC : ZFM_MID_PR_GET_DETAIL_INTRA, please try again later or contact IT Department");
                }

                $doctype=(isset($data_form['GI_ITEMS'][0]['DOC_TYPE']) && !empty($data_form['GI_ITEMS'][0]['DOC_TYPE'])) ? $data_form['GI_ITEMS'][0]['DOC_TYPE'] : NULL;
                $preq_id=(isset($data_form['GI_ITEMS'][0]['PREQ_ID']) && !empty($data_form['GI_ITEMS'][0]['PREQ_ID'])) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                $plant=(isset($data_form['GI_ITEMS'][0]['PLANT']) && !empty($data_form['GI_ITEMS'][0]['PLANT'])) ? $data_form['GI_ITEMS'][0]['PLANT'] : NULL;
                $preq_date=(isset($data_form['GI_ITEMS'][0]['PREQ_DATE']) && !empty($data_form['GI_ITEMS'][0]['PREQ_DATE'])) ? date('m/d/Y',strtotime($data_form['GI_ITEMS'][0]['PREQ_DATE'])) : NULL;


                // START -- CARI DATA DETAIL DARI SAP
                $param = array(
                    'GV_NUMBER'=>$prnumber
                );

                $function_type = $rfc->getFunction('ZFM_PR_CANCEL_REL_INTRA');
                try{
                    $reject= $function_type->invoke($param, $options);
                    // if(isset($reject['GI_RETURN'][0]['CODE']) && $reject['GI_RETURN'][0]['CODE'] == "W5041"){ // flag jika cancellation sukses
                    if(isset($reject['GI_RETURN'][0]['CODE']) && $reject['GI_RETURN'][0]['CODE'] == "W5041" || isset($reject['GI_RETURN'][0]['TYPE']) && strtoupper($reject['GI_RETURN'][0]['TYPE']) == "S"){ // flag jika cancellation sukses
                        if($type=="reject"){ // jika reject dia harus update ke table PR_RELEASE untuk kasi status nya
                            $keterangan = "REJECTED";
                            // DB::connection('dbintranet')
                            // ->table('TBL_PR_RELEASE')
                            // ->where('PRNUMBER',$prnumber)
                            // ->where('RELCODE',$release_code)
                            // ->update(
                            //     [
                            //         "STATUS"=>"REJECTED",
                            //         "RELEASEDATE"=>date('Y-m-d H:i:s'),
                            //         "RELEASE_CODE_NAME_ID"=>$employee_id
                            //     ]
                            // );
                        }else{
                            $keterangan = "CANCELED"; // sedangkan untuk cancel tidak perlu ke tabel PR RELEASE karena hanya mengubah PR HEADER
                        }
                        // DB::connection('dbintranet')
                        // ->table('TBL_PR_HEADER')
                        // ->where('PRNUMBER',$prnumber)
                        // ->update(
                        //     [
                        //         "LAST_APPROVAL_STATUS"=>$keterangan,
                        //         "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                        //         "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                        //         "LAST_APPROVAL_REASON"=>$reason
                        //     ]
                        // );

                        //-----------------------------


                        // cek jika ada  di table HEADER
                        $itung_pr_header=DB::connection('dbintranet')
                        ->table('TBL_PR_HEADER')
                        ->select('PRNUMBER')
                        ->where('PRNUMBER',$prnumber)
                        ->count();

                        if($itung_pr_header>0){
                            // jika ada di table header, maka update statusnya
                            DB::connection('dbintranet')
                            ->table('TBL_PR_HEADER')
                            ->where('PRNUMBER',$prnumber)
                            ->update(
                                [
                                    "LAST_APPROVAL_STATUS"=>$keterangan,
                                    "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                                    "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                                    "LAST_APPROVAL_REASON"=>$reason
                                ]
                            );
                        }else{
                            // jika tidak ada, maka insert (karena datang dari SAP)
                            DB::connection('dbintranet')
                            ->table('TBL_PR_HEADER')
                            ->insert(
                                [
                                    "PRNUMBER"=>$prnumber,
                                    "DOCTYPE"=>$doctype,
                                    "PLANT"=>$plant,
                                    "REQUESTEDBY"=>$preq_id,
                                    "REQUESTEDDATE"=>$preq_date,
                                    "LAST_APPROVAL_STATUS"=>$keterangan,
                                    "LAST_APPROVAL_DATE"=>date('Y-m-d H:i:s'),
                                    "LAST_APPROVAL_EMPLOYEE_ID"=> $employee_id,
                                    "LAST_APPROVAL_REASON"=>$reason
                                ]
                            );

                             //tambahan juga harus input di tabel INT APPR RAW DATA
                            $itung_pr_raw=DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_RAW_DATA')
                            ->select('UID')
                            ->where('UID',$prnumber)
                            ->count();

                            if($itung_pr_raw<1){
                                DB::connection('dbintranet')
                                ->table('INT_FIN_APPR_RAW_DATA')
                                ->insert(
                                    [
                                        "JSON_ENCODE"=>"{}",
                                        "TYPE"=>"Request Purchase Requisition",
                                        "INSERT_DATE"=>$preq_date,
                                        "UID"=>$prnumber,
                                        "EMPLOYEE_ID"=>$preq_id,
                                        "TYPE_FORM"=>"PURCHASE-REQUISITION",
                                        "FULLFILL_ZOHO"=>""
                                    ]
                                );
                            }
                        }

                        $itung_pr_release=DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->select('PRNUMBER')
                        ->where('PRNUMBER',$prnumber)
                        ->where('RELCODE',$release_code)
                        ->count();

                        if($itung_pr_release>0){
                            // jika ada di table release, maka update statusnya
                            DB::connection('dbintranet')
                            ->table('TBL_PR_RELEASE')
                            ->where('PRNUMBER',$prnumber)
                            ->where('RELCODE',$release_code)
                            ->update(
                                [
                                    "STATUS"=>$keterangan,
                                    "RELEASEDATE"=>date('Y-m-d H:i:s'),
                                    "RELEASE_CODE_NAME_ID"=>$employee_id
                                ]
                            );
                        }else{
                            // jika tidak ada, maka insert (karena datang dari SAP)
                            try{
                                $param = array(
                                    'NUMBER'=>$prnumber
                                );
                                $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                                $sap_release= $function_type->invoke($param, $options);
                            }catch(SAPFunctionException $e){
                                throw new \Exception("Cannot find release strategy on SAP, please try again later or contact IT Department");
                            }

                            // insert ke table pr release
                            $data_release=isset($sap_release['RELEASE_FINAL'][0]) ? $sap_release['RELEASE_FINAL'][0] : [];
                            for($i=1;$i<=8;$i++){
                                DB::connection('dbintranet')
                                ->table('TBL_PR_RELEASE')
                                ->insert(
                                    [
                                        "RELCODE" => isset($data_release['REL_CODE'.$i]) ? $data_release['REL_CODE'.$i] : NULL,
                                        "RELCODEDESC" => isset($data_release['REL_CD_TX'.$i]) ? $data_release['REL_CD_TX'.$i] : NULL,
                                        "RELCODENAME" => NULL,
                                        "STATUS" => NULL,
                                        "RELEASEDATE" => NULL,
                                        "PRNUMBER" => $prnumber,
                                        "REASON" =>NULL,
                                        "RELEASE_CODE_NAME_ID"=>NULL
                                    ]
                                );
                            }

                            //lalu update
                            DB::connection('dbintranet')
                            ->table('TBL_PR_RELEASE')
                            ->where('PRNUMBER',$prnumber)
                            ->where('RELCODE',$release_code)
                            ->update(
                                [
                                    "STATUS"=>$keterangan,
                                    "RELEASEDATE"=>date('Y-m-d H:i:s'),
                                    "RELEASE_CODE_NAME_ID"=>$employee_id
                                ]
                            );
                        }




                        // ========================================================================= START NOTIFICATION
                        $requestor=DB::connection('dbintranet')
                        ->select("SELECT REQUESTEDBY FROM TBL_PR_HEADER WHERE PRNUMBER='".$prnumber."'");

                        @$requestor_id=(!empty($requestor[0]->REQUESTEDBY))? $requestor[0]->REQUESTEDBY : NULL;

                        if(empty($requestor_id)){
                            //jika kosong, maka cari dari RFC get detail PR
                            $param = array(
                                'GV_NUMBER'=>$prnumber
                            );
                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $data_form= $function_type->invoke($param, $options);
                            $requestor_id=(!empty($data_form['GI_ITEMS'][0]['PREQ_ID'])) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                        }

                        if(!empty($requestor_id)){
                            $notif_link="/finance/purchase-requisition/detail/".$prnumber;
                            $notif_desc="Your Purchase Requisition : ".$prnumber." is rejected";
                            $notif_type="reject";
                            insertNotification($requestor_id, $notif_link, $notif_desc, $notif_type);
                        }
                        // ========================================================================= END NOTIFICATION



                        $success=true;
                        $code = 200;
                        $msg = 'PR request has been '.$keterangan.'';
                    }else{
                        $success=false;
                        $code = 403;
                        $msg = 'Failed to set status in SAP, please check in SAP or contact IT Dept. Please also make sure if the PR is not already rejected';
                    }

                }catch(SapException $e){
                    $success=false;
                    $code = 403;
                    $msg = $e->errorInfo;
                }

            } else {
                $success=false;
                $code = 403;
                $msg = 'PR Number is not recognized, please check posted data from intranet';
            }

        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));
    }




    public function getCostCenterApprovalList($costcenter, $level){
        $approve_level = $level+1; //approve_level adalah level approve yang dilakukan saat ini,sedangkan level adalah nilai approve sebelumnya yang harus dicapai untuk melakukan approve

        $string="";
        DB::enableQueryLog();
        $getCostCenter=DB::connection('dbintranet')
        ->select("SELECT V.* FROM ".$this->form_view." V
        INNER JOIN INT_FIN_APPR_ROLE R ON V.TYPE_FORM = R.FORM_NUMBER
        WHERE R.TYPE_APPROVAL = 'COST_CENTER' AND R.LEVEL_APPROVAL = ".$approve_level."
        AND R.VALUE_APPROVAL LIKE '%".$costcenter."%'
        AND V.APPROVAL_LEVEL = ".$level."");
        // DD(DB::getQueryLog());
        if(!empty($getCostCenter)){
            foreach($getCostCenter as $cc){
                $string.=",'".$cc->UID."'";
            }
            $string=substr($string,1);
        }else{
            $string="NULL";
        }

        return $string;
    }

    public function search_vendor(Request $request){

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

        // $keyword=strtoupper($request->input('keywordParameter'));

        // $param = array(
        //     'GV_MCDK1'=>"*".$keyword."*",
        //     'GV_MAX_ROWS'=>9999,
        //     'GV_BUKRS'=>''
        // );
        // $function_type = $rfc->getFunction('ZFM_POPUP_VEN_INTRA');
        // $list_vendor= $function_type->invoke($param, $options);



        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        // dd(Session::get('assignment'));

        if(empty(Session::get('assignment')[0])){
        $company_code="SYSADMIN";
        $plant="SYSADMIN";
        $division="SYSADMIN";
        $department="SYSADMIN";
        $plant_name="SYSADMIN";
        }else{
        $division=Session::get('assignment')[0]->DIVISION_NAME;
        $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
        $company_code=Session::get('assignment')[0]->COMPANY_CODE;
        $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
        $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
        }

        $keyword=strtoupper($request->input('keywordParameter'));

        $param = array(
            'GV_MCDK1'=>"*".$keyword."*",
            'GV_MAX_ROWS'=>9999,
            'GV_BUKRS'=>$company_code
        );
        $function_type = $rfc->getFunction('ZFM_POPUP_VEN_INTRA');
        $list_vendor= $function_type->invoke($param, $options);

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
        'status'=>$status,
        'request_date_from'=>$request_date_from,
        'request_date_to'=>$request_date_to,
        'form_code'=>$this->form_number,
        'list_vendor'=>$list_vendor['GI_HEADER']
        );

        return view('pages.finance.purchase-requisition.search_vendor', ['data' => $data]);
    }

    public function search_material(Request $request){
        $data = [];
        try {
            //init rfc
            $is_production = config('intranet.is_production');
            if($is_production){
                $rfc = new SapConnection(config('intranet.rfc_prod'));
            }else{
                $rfc = new SapConnection(config('intranet.rfc'));
            }
            $options = [
                'ltrim'=>true,
                'rtrim'=>true,
                'use_function_desc_cache' => false,
            ];

            $employee_id=Session::get('user_id');
            $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

            if(empty(Session::get('assignment')[0])){
                $company_code="SYSADMIN";
                $plant="SYSADMIN";
                $division="SYSADMIN";
                $department="SYSADMIN";
                $plant_name="SYSADMIN";
            }else{
                $division=Session::get('assignment')[0]->DIVISION_NAME;
                $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
                $company_code=Session::get('assignment')[0]->COMPANY_CODE;
                $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
                $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            }

            $keyword=strtoupper($request->input('materialName'));
            $acctasscat=(!empty($request->input('acctasscat')))? $request->input('acctasscat') : '';
            $plant_code=$request->input('plantParameter');
            $id_row=$request->input('idRow');

            $param = array(
                'GV_MAKTX'=>"*".$keyword."*",
                // 'GV_BUKRS'=>$company_code,
                // 'GV_RFIKRS'=>$company_code,
                'GV_ACCTASSCAT'=>$acctasscat,
                'GV_WERKS'=>$plant_code,
                'GV_MAX_ROWS'=>9999
            );

            $function_type = $rfc->getFunction('ZFM_POPUP_MAT_BDT_INTRA_MONTH');
            $list_material= $function_type->invoke($param, $options);

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
                'status'=>$status,
                'request_date_from'=>$request_date_from,
                'request_date_to'=>$request_date_to,
                'form_code'=>$this->form_number,
                'list_material' =>$list_material['GI_HEADER'],
                'id_row'=>$id_row
            );
            // dd($data);
            return response()->json($data, 200);
        } catch(\Throwable $e){
            Log::error('Search Material Error | '.$e->getMessage());
            return response()->json($data, 500);
        }
        // return view('pages.finance.purchase-requisition.search_material', ['data' => $data]);
    }

    public function search_asset(Request $request){
        $data = [];
        try {
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

            $employee_id=Session::get('user_id');
            $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

            if(empty(Session::get('assignment')[0])){
                $company_code="SYSADMIN";
                $plant="SYSADMIN";
                $division="SYSADMIN";
                $department="SYSADMIN";
                $plant_name="SYSADMIN";
            }else{
                $division=Session::get('assignment')[0]->DIVISION_NAME;
                $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
                $company_code=Session::get('assignment')[0]->COMPANY_CODE;
                $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
                $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            }

            $id_row=$request->input('idRow');
            $keyword=strtoupper($request->input('assetNo'));
            $fundsCtr=(!empty($request->input('fundsCtr')))? $request->input('fundsCtr') : '';
            $filter_company=(!empty($request->input('company')))? $request->input('company') : '';
            try{
                $param = array(
                    // 'GV_MCOA1'=>"*".$keyword."*",
                    'GV_ANLN1'=>$keyword,
                    'GV_BUKRS'=>$filter_company,
                    // 'GV_RFUNDSCTR'=>$fundsCtr,
                    'GV_MAX_ROWS'=>9999
                );
                $function_type = $rfc->getFunction('ZFM_POPUP_AST_INTRA_MONTH');
                $list_asset= $function_type->invoke($param, $options);
                if(isset($list_asset['GI_HEADER']) && count($list_asset['GI_HEADER']) < 1 ){
                    $param = array(
                        'GV_MCOA1'=>"*".$keyword."*",
                        'GV_BUKRS'=>$filter_company,
                        // 'GV_RFUNDSCTR'=>$fundsCtr,
                        'GV_MAX_ROWS'=>9999
                    );
                    $function_type = $rfc->getFunction('ZFM_POPUP_AST_INTRA_MONTH');
                    $list_asset= $function_type->invoke($param, $options);
                }
            }catch(SAPFunctionException $e){
                // echo json_encode($param);
                Log::error('Error searching Asset Purchase Requisition | '. $e->getMessage());
            }

            $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
            $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
            $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

            // $data=array(
            //     'company_code'=>$company_code,
            //     'plant'=>$plant,
            //     'plant_name'=>$plant_name,
            //     'employee_id'=>$employee_id,
            //     'employee_name'=>$employee_name,
            //     'division'=>$division,
            //     'department'=>$department,
            //     'status'=>$status,
            //     'request_date_from'=>$request_date_from,
            //     'request_date_to'=>$request_date_to,
            //     'form_code'=>$this->form_number,
            //     'list_asset' =>isset($list_asset['GI_HEADER']) ? $list_asset['GI_HEADER'] : [],
            //     'id_row'=>$id_row
            // );
             
            $data = isset($list_asset['GI_HEADER']) ? $list_asset['GI_HEADER'] : [];
            return response()->json(['data'=>$data, 'status'=>'success', 'message'=>'Data has been successfully loaded'], 200);
        } catch(\Exception $e){
            return response()->json(['data'=>$data, 'status'=>'error', 'message'=>$e->getMessage()], 400);
        }
        // return view('pages.finance.purchase-requisition.search_asset', ['data' => $data]);
    }


    function checkReleaseMappingOrder($a, $b)
    {
        $awal = isset($a->RELEASE_CODE) ? (int)substr($a->RELEASE_CODE,1) : '';
        $selanjutnya = isset($b->RELEASE_CODE) ? (int)substr($b->RELEASE_CODE, 1) : '';
        if ($awal == $selanjutnya) {
            return 0;
        }
        elseif ($awal == 0) {
            return -1;
        }
        // Jika F4 Return true karena tidak ada F1 di KMS
        elseif ($awal == 4 || $selanjutnya == 4) {
            return 1;
        }
        return ($awal < $selanjutnya) ? -1 : 1;
    }


    public function modal_detail_po(Request $request)
    {
        $id=$request->get('id');

        $data['po'] = [];
        $release_mapping = [];
        $prior_release_approve = [];
        $release_indicator = null;
        $release_history = [];
        $PR_employee = null;
        try {
            $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
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
            $param = array(
                'PURCHASEORDER' =>$id,
                'ITEM_TEXTS'=>'X',
                'HEADER_TEXTS'=>'X'
            );
            $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
            $po_list = $function_type->invoke($param, $options);

            if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                try{
                    $param = array(
                        'VENDORNO' => isset($po_list['PO_HEADER']['VENDOR']) ? $po_list['PO_HEADER']['VENDOR'] : ''
                    );
                    $function_type = $rfc->getFunction('BAPI_VENDOR_GETDETAIL');
                    $vendor_detail = $function_type->invoke($param, $options);
                    if(isset($vendor_detail['GENERALDETAIL'])){
                        $po_list['PO_HEADER']['VEND_NAME'] = strtoupper($vendor_detail['GENERALDETAIL']['NAME']);
                        $po_list['PO_HEADER']['VEND_CITY'] = strtoupper($vendor_detail['GENERALDETAIL']['CITY']);
                        $po_list['PO_HEADER']['VEND_ADDRESS'] = strtoupper($vendor_detail['GENERALDETAIL']['STREET']);
                        $po_list['PO_HEADER']['VEND_COUNTRY'] = strtoupper($vendor_detail['GENERALDETAIL']['COUNTRY']);
                        $po_list['PO_HEADER']['VEND_TEL'] = strtoupper($vendor_detail['GENERALDETAIL']['TELEPHONE']);
                    }
                } catch(\Exception $e){}

                 // mendapatkan data detail item dengan BAPI lain untuk mencari requisitioner per item
                try{
                    $param = array(
                        'PURCHASEORDER' => $id
                    );
                    $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL1');
                    $item_detail = $function_type->invoke($param, $options);
                    $po_list['PO_HEADER']['ITEM_ALT'] = (isset($item_detail['POITEM'][0])) ? $item_detail['POITEM'] : array() ;
                } catch(\Exception $e){}
                // end

                try {
                    $po_list['PO_ITEMS'] = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function($value, $key){
                        $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                        return !empty($delete_ind);
                    })->values()->all() : [];
                } catch(\Exception $e){}
                
                $po_list['PO_HEADER']['CREATED_ON'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['CREATED_ON']));
                $po_list['PO_HEADER']['DOC_DATE'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['DOC_DATE']));
                $po_list['PO_HEADER']['COST_CENTER'] = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_ITEMS'][0]['PREQ_NO']) ? $po_list['PO_ITEMS'][0]['PREQ_NO'] : '';
                // mencari header text
                $header_text ='';
                if(isset($po_list['PO_HEADER_TEXTS'][0]) && !empty($po_list['PO_HEADER_TEXTS'][0])){
                    foreach($po_list['PO_HEADER_TEXTS'] as $loop_header){
                        $header_text .= ' - '.$loop_header['TEXT_LINE'];
                    }
                    $header_text=substr($header_text,3);
                }
                $po_list['PO_HEADER']['PO_HEADER_TEXTS']= $header_text;

                try {
                    // dd($real_header['REL_IND']);
                    $cost_center_requestor = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                    $get_cost_center_name = DB::connection('dbintranet')
                    ->table('INT_SAP_COST_CENTER')
                    ->where('SAP_COST_CENTER_ID', $cost_center_requestor)
                    ->select('SAP_COST_CENTER_DESCRIPTION')
                    ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                    if($get_doc_type_desc)
                        $po_list['PO_HEADER']['REQUESTOR_COST_CENTER'] = $get_cost_center_name;
                } catch(\Exception $e){}

                try {
                    $param = array(
                        'GV_NUMBER'=>$po_list['PO_HEADER']['REQ_NO']
                    );
                    // $function_type = $rfc->getFunction('ZFM_PR_GET_DETAIL_INTRA');
                    $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                    $data_form= $function_type->invoke($param, $options);
                    if(isset($data_form['GI_ITEMS']) && count($data_form['GI_ITEMS'])){
                        $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                    }

                } catch(\Exception $e){}

                $data['po'] = $po_list['PO_HEADER'];
                // $detail_po = isset($po_list['PO_ITEMS']) ? $po_list['PO_ITEMS'] : [];
                $detail_po = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function($value, $key){
                    $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                    return !empty($delete_ind);
                })->toArray() : [];
                $data['po']['PO_DETAILS'] = $detail_po;
                $item_texts = collect($po_list['PO_ITEM_TEXTS'])->groupBy('PO_ITEM');
                $data['po']['PO_ITEM_TEXTS'] = $item_texts;

            }

            // Now check approval / release strategy
            // try {
            $param = array(
                'PURCHASEORDER'=>$id,
                'PO_REL_CODE'=>'',
            );
            $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
            $last_approve_status = $function_type->invoke($param, $options);
            $release_info = collect(isset($last_approve_status['RELEASE_FINAL'][0]) ? $last_approve_status['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                // take only description of release strategy code
                return substr($key, 0, 8) == 'REL_CODE';
            })->filter()->toArray();

            $release_group = isset($data['po']['REL_GROUP']) ? $data['po']['REL_GROUP'] : '';
            $cost_center = isset($data['po']['COST_CENTER']) ? $data['po']['COST_CENTER'] : '';
            $prior_release_approve = array_values(collect(isset($last_approve_status['RELEASE_ALREADY_POSTED']) ? $last_approve_status['RELEASE_ALREADY_POSTED'] : [])->filter(function($item, $key){
                // take only description of release strategy code
                return substr($key, 0, 8) == 'REL_CODE';
            })->filter()->toArray());
            $release_code = array_values($release_info);

            // Cek yang seharusnya approve selanjutnya
            $now_release_approve = '';
            foreach ($release_code as $key => $value) {
                if (!in_array($value, $prior_release_approve)) {
                    $now_release_approve = $value;
                    break;
                }
            }

            $release_indicator = isset($data['po']['REL_IND']) ? $data['po']['REL_IND'] : null;

            /* MENGGUNAKAN COST CENTER DAN MIDJOB */
            $requestor_midjob = DB::connection('dbintranet')
            ->table('INT_EMPLOYEE_ASSIGNMENT')
            ->where('EMPLOYEE_ID', $PR_employee)
            ->select('MIDJOB_TITLE_ID')
            ->get()->pluck('MIDJOB_TITLE_ID')->first();

            // Mencari release untuk PO dengan cost center dan midjob tertentu (Siapa saja yg bisa approve)
            $release_mapping = DB::connection('dbintranet')
            ->table('dbo.SAP_PO_RELEASE_CODE_NEW AS ap')
            ->where(['ap.COSTCENTER'=>$cost_center, 'ap.MIDJOB_TITLE'=>$requestor_midjob])
            ->orWhere(function($query) use ($cost_center){
                $query->where('ap.COSTCENTER', $cost_center)
                ->whereNull('ap.MIDJOB_TITLE');
            })
            ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
            })
            ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
            })
            ->select('ap.SEQ_ID', 'ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
            ->distinct()
            ->get()->toArray();

            // Sort Release Strategy mapping
            $release_mapping = collect($release_mapping)->sortBy('SEQ_ID')->toArray();
            // usort($release_mapping, array( $this, 'checkReleaseMappingOrder' ));

            // Cek history approval di DB warehouse
            $release_history = DB::connection('dbintranet')
            ->table('dbo.SAP_PO_RELEASE')
            ->where('PO_NUMBER', $id)
            ->select('RELEASE_DATE', DB::raw("CONCAT(RELEASE_CODE,'-',RELEASE_CODE_NAME_ID) AS RELEASE_CODE_NAME_ID"))
            ->get()->pluck('RELEASE_DATE','RELEASE_CODE_NAME_ID')->toArray();
            // } catch(\Exception $e){}

        } catch(SAPFunctionException $e){
            if($request->ajax()){
                return response()->json(['type'=>'error', 'message'=>$e->getMessage()]);
            } else {
                $request->session()->flash('error_po_approval', 'Something went wrong when trying to get data from SAP');
                return redirect()->route('finance.purchase-order.list');
            }
        } catch(\Exception $e){
            if($request->ajax()){
                return response()->json(['type'=>'error', 'message'=>$e->getMessage()]);
            } else {
                $request->session()->flash('error_po_approval', 'Something went wrong, please try again in a moment');
                return redirect()->route('finance.purchase-order.list');
            }
        }

        return View::make('pages.finance.purchase-order.modal-detail')->with([
            'data' => $data['po'],
            'release_strategy'=>$release_mapping,
            'prior_release_approve'=>$prior_release_approve,
            'now_release_approve'=>$now_release_approve,
            'release_indicator'=>$release_indicator,
            'current_login_employee'=>$employee_id,
            'release_history'=>$release_history,
            'release_code_collected'=>$release_code
        ])->render();



    }

    function ajax_sloc(Request $request){
        // $company = $request->get('plant');
        $plant = $request->get('plant');
        if(!empty($plant)){
            $is_production = config('intranet.is_production');
            if($is_production)
                $rfc = new SapConnection(config('intranet.rfc_prod'));
            else
                $rfc = new SapConnection(config('intranet.rfc'));

            $options = [
                'rtrim'=>true,
            ];

            $param_sloc = [
                'P_COMPANY'=>"",
                'P_PLANT'=>$plant
            ];

            $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
            $result= $function->invoke($param_sloc, $options);
            $sap_sloc = $result['IT_SLOC'];

            return json_encode($sap_sloc);
        }
    }

    function ajax_costcenter(Request $request){
        // $company = $request->get('plant');
        $employee_id=Session::get('user_id');
        $default_cost_center=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
        $plant = $request->get('plant');
        if(!empty($plant)){

            $cost_center=DB::connection('dbintranet')
            ->table('SAP_PR_EMP_COSTCENTER_MAPPING as map')
            ->join('INT_SAP_COST_CENTER as c','map.SAP_COSTCENTER_ID','=','c.SAP_COST_CENTER_ID')
            ->select('c.SAP_COST_CENTER_ID','c.SAP_COST_CENTER_NAME','c.SAP_COST_CENTER_DESCRIPTION')
            ->where('EMPLOYEE_ID',$employee_id)
            ->where('PLANT_ID',$plant)
            ->get()->toArray();

            if(count($cost_center) <1 ){
                //pakai default cost center kalau misal tidak ada cost centernya

                $return=DB::connection('dbintranet')
                ->table('INT_SAP_COST_CENTER')
                ->where('SAP_COST_CENTER_ID',$default_cost_center)
                ->select('SAP_COST_CENTER_ID','SAP_COST_CENTER_NAME','SAP_COST_CENTER_DESCRIPTION')
                ->get()->toArray();

            }else{
                $return = $cost_center;
            }

            return json_encode($return);
        }


    }

    public function list_pr(Request $request){
        $is_superuser = isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER ? true : false;
        if($request->ajax()){
            try{
                $is_production = config('intranet.is_production');
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc'));
                }
                $options = [
                    'rtrim'=>true,
                ];

                // Datatable server-side query
                $limit = $request->get('length');
                $start = $request->get('start');
                $search = isset($request->get('search')['value']) ? $request->get('search')['value'] : NULL;

                // Datatable custom parameter
                $insert_date_from=$request->input('created_date_from');
                $insert_date_to=$request->input('created_date_to');
                $status=strtoupper($request->input('status'));
                $cost_center=$request->input('cost_center');

                // Get all PR with not null ID (PR-NUMBER)
                $where = "ID <> '' ";
                if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                    $where = $where." and INSERT_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                }

                if(!$is_superuser or $is_superuser && $cost_center){
                    $where = $where." and JSON_ENCODE LIKE '%\"cost_center\":\"$cost_center\"%'";
                }

                if ($status != null or $status !=""){
                    if($status=="WAITING" || $status=='FINISHED'){
                        // $where = $where." and (STATUS_APPROVAL = 'REQUESTED' OR STATUS_APPROVAL = 'APPROVED') ";
                        $where = $where." and (STATUS_APPROVAL = 'REQUESTED' OR STATUS_APPROVAL = 'APPROVED' OR STATUS_APPROVAL = 'FINISHED') ";
                    }else{
                        $where = $where." and STATUS_APPROVAL = '".$status."'";
                    }

                }

                $result=array();
                $current_loggedin_employee_id=Session::get('user_id');
                DB::connection('dbintranet')->enableQueryLog();

                $data = DB::connection('dbintranet')
                ->table(DB::raw($this->form_view))
                ->whereraw(DB::raw($where));
                $recordsTotal = $data->count();

                if(!empty($search)){
                    $where = $where." and LAST_APPROVAL_NAME LIKE '%$search%' OR ".$where." and UID LIKE '%$search%'";
                    $data = $data->whereRaw(DB::raw($where));
                }

                $recordsFiltered = $data->count();
                $data = $data->orderBy('ID', 'DESC');
                $data = $data->offset($start)->limit($limit)->get();
                foreach($data as $key=>$value){
                    $data_form=DB::connection('dbintranet')
                    ->table('INT_FIN_APPR_RAW_DATA')
                    // ->leftJoin('INT_FIN_APPR_LIST','INT_FIN_APPR_RAW_DATA.UID','=','INT_FIN_APPR_LIST.FORM_ID')
                    ->where('INT_FIN_APPR_RAW_DATA.UID',$value->UID)
                    ->get();

                    $data_json = isset($data_form[0]->JSON_ENCODE) ? json_decode($data_form[0]->JSON_ENCODE) : [];
                    $grand_total=isset($data_json->grandTotal) ? $data_json->grandTotal : '';
                    @$currency = isset($data_json->currency) ? $data_json->currency : '';
                    $tracking_no=isset($data_json->cost_center) ? $data_json->cost_center : '';
                    $purpose = isset($data_json->purpose) ? $data_json->purpose : '';

                    $uid = $value->UID;
                    $data_requestor=DB::connection('dbintranet')
                        ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");

                    // start cari divison dan dept dari tracking number
                    $data_costcenter=DB::connection('dbintranet')
                        ->select("SELECT TERRITORY_NAME, DEPARTMENT_NAME, DIVISION_NAME, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID = '".$tracking_no."'");

                    // $cost_center_desc = (!empty($data_costcenter))? $data_costcenter[0]->TERRITORY_NAME.' '.$data_costcenter[0]->DEPARTMENT_NAME.' - '.$data_costcenter[0]->DIVISION_NAME : '' ;
                    $cost_center_desc = (!empty($data_costcenter)) ? $data_costcenter[0]->SAP_COST_CENTER_DESCRIPTION : '';
                    $data_pr_detail = [];
                    try{
                        $param = array(
                            // 'GV_NUMBER'=>$uid
                            'NUMBER'=>$uid
                        );
                        // $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                        $function_type = $rfc->getFunction('BAPI_REQUISITION_GETDETAIL');
                        $data_pr_detail= $function_type->invoke($param, $options);
                    }catch(SAPFunctionException $e){}

                    // filter jika sudah ada nomor PO
                    // $nomor_po = (isset($data_pr_detail['GI_ITEMS'][0]['PO']) && !empty($data_pr_detail['GI_ITEMS'][0]['PO'])) ? $data_pr_detail['GI_ITEMS'][0]['PO'] : '';
                    // $nomor_po = (isset($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER']) && !empty($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'])) ? $data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'] : '';
                    if(isset($data_pr_detail['REQUISITION_ITEMS']) && count($data_pr_detail['REQUISITION_ITEMS']) > 0){
                        $nomor_po = collect($data_pr_detail['REQUISITION_ITEMS'])->pluck('PO_NUMBER', 'PO_NUMBER')->filter(function($item, $key){
                            // if(!$item || empty($item))
                            //     $item = '-';
                            return $item || !empty($item);
                        })->values()->all();
                        $nomor_po = implode(',', $nomor_po);
                    } else {
                        $nomor_po = '';
                    }

                    // Release check on SAP
                    // START -- MENGAMBIL DATA RELEASE STRATEGY
                    $midjob_id = isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '';
                    try{
                        $param = array(
                            'NUMBER'=>$uid
                        );
                        $function_type = $rfc->getFunction('BAPI_REQUISITION_GETRELINFO');
                        $sap_release= $function_type->invoke($param, $options);

                        $release_info = collect(isset($sap_release['RELEASE_FINAL'][0]) ? $sap_release['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                            // take only description of release strategy code
                            return substr($key, 0, 8) == 'REL_CODE';
                        })->filter()->toArray();
                        $release_code = array_values($release_info);

                        $data_release_mapping = DB::connection('dbintranet')
                        ->table('SAP_PR_RELEASE_CODE_NEW AS ap')
                        ->where(['COSTCENTER'=>$tracking_no, 'MIDJOB_TITLE'=>$midjob_id])
                        ->orWhere(function($query) use ($tracking_no, $midjob_id){
                            $query->where(['COSTCENTER'=>$tracking_no, 'MIDJOB_TITLE'=>$midjob_id]);
                        })
                        ->orWhere(function($query) use ($tracking_no, $midjob_id){
                            $query->where(['COSTCENTER'=>$tracking_no])
                            ->whereNull('MIDJOB_TITLE');
                        })
                        ->orWhere(function($query) use ($tracking_no, $midjob_id){
                            $query->where(['COSTCENTER'=>$tracking_no])
                            ->whereNull('MIDJOB_TITLE');
                        })
                        ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                            $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
                        })
                        ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                            $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
                        })
                        ->select('ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
                        ->get()->toArray();

                        $prior_release_approve = array_values(
                            collect(
                                isset($sap_release['RELEASE_ALREADY_POSTED'][0]) ? $sap_release['RELEASE_ALREADY_POSTED'][0] : []
                            )->filter(function($item, $key){
                                // take only description of release strategy code
                                return substr($key, 0, 8) == 'REL_CODE';
                            }
                        )->filter()->toArray());

                        // Cek yang seharusnya approve selanjutnya
                        $now_release_approve = '';
                        foreach ($release_code as $key_release => $val_release) {
                            if (!in_array($val_release, $prior_release_approve)) {
                                $now_release_approve = $val_release;
                                break;
                            }
                        }
                    }catch(SAPFunctionException $e){
                        Log::error('ERROR IN DETAIL PURCHASE REQUISITION | '.$e->getMessage());
                        $sap_release = [];
                        $release_code = [];
                        $data_release_mapping = [];
                        $prior_release_approve = [];
                        $now_release_approve = '';
                    }
                    // END -- MENGAMBIL DATA RELEASE STRATEGY

                    if($status == 'FINISHED'){
                        if($value->STATUS_APPROVAL != 'FINISHED'){
                            if(count($data_release_mapping) == count($prior_release_approve)) {
                                $result[]=array(
                                    'UID'=>$value->UID,
                                    'PO_NUMBER'=>$nomor_po,
                                    'DOC_TYPE'=>$value->DOCTYPE_DESC,
                                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                                    'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                                    'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                                    'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                                    'REASON'=>$value->LAST_APPROVAL_REASON,
                                    'REQ_DATE'=>$value->REQUESTEDDATE,
                                    'PLANT'=>$value->PLANT,
                                    'TRACKING_NO'=>$tracking_no,
                                    'TRACKING_DESC'=>$cost_center_desc,
                                    'CURR'=>@$currency,
                                    'GRAND_TOTAL'=>$grand_total,
                                    'PURPOSE'=>$purpose,
                                    'RELEASE_CODE_SAP'=>$release_code,
                                    'DATA_RELEASE_MAPPING'=>$data_release_mapping,
                                    'ALREADY_APPROVE_SAP'=>$prior_release_approve,
                                    'CURRENT_APPROVAL_SAP'=>$now_release_approve
                                );
                            }
                        } else if($value->STATUS_APPROVAL == 'FINISHED'){
                            $result[]=array(
                                'UID'=>$value->UID,
                                'PO_NUMBER'=>$nomor_po,
                                'DOC_TYPE'=>$value->DOCTYPE_DESC,
                                'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                                'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                                'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                                'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                                'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                                'REASON'=>$value->LAST_APPROVAL_REASON,
                                'REQ_DATE'=>$value->REQUESTEDDATE,
                                'PLANT'=>$value->PLANT,
                                'TRACKING_NO'=>$tracking_no,
                                'TRACKING_DESC'=>$cost_center_desc,
                                'CURR'=>@$currency,
                                'GRAND_TOTAL'=>$grand_total,
                                'PURPOSE'=>$purpose,
                                'RELEASE_CODE_SAP'=>$release_code,
                                'DATA_RELEASE_MAPPING'=>$data_release_mapping,
                                'ALREADY_APPROVE_SAP'=>$prior_release_approve,
                                'CURRENT_APPROVAL_SAP'=>$now_release_approve
                            );
                        }
                    } else {
                        $result[]=array(
                            'UID'=>$value->UID,
                            'PO_NUMBER'=>$nomor_po,
                            'DOC_TYPE'=>$value->DOCTYPE_DESC,
                            'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                            'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                            'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                            'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                            'REQ_BY_COSTCENTER'=>isset(json_decode($data_requestor[0]->JSON_ENCODE)->cost_center) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                            'REASON'=>$value->LAST_APPROVAL_REASON,
                            'REQ_DATE'=>$value->REQUESTEDDATE,
                            'PLANT'=>$value->PLANT,
                            'TRACKING_NO'=>$tracking_no,
                            'TRACKING_DESC'=>$cost_center_desc,
                            'CURR'=>@$currency,
                            'GRAND_TOTAL'=>$grand_total,
                            'PURPOSE'=>$purpose,
                            'RELEASE_CODE_SAP'=>$release_code,
                            'DATA_RELEASE_MAPPING'=>$data_release_mapping,
                            'ALREADY_APPROVE_SAP'=>$prior_release_approve,
                            'CURRENT_APPROVAL_SAP'=>$now_release_approve
                        );
                    }
                }

                // $datatable = DataTables::of($result)->make(true);
                // return $datatable;
                return response()->json([
                    "draw" => intval($request->get('draw')),  
                    "recordsTotal" => intval($recordsTotal),  
                    "recordsFiltered" => intval($recordsFiltered),
                    "data" => $result
                ]);
            }
            catch(\Exception $e){
                return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
            }
        } else {
            try {
                $is_production = config('intranet.is_production');
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc'));
                }
                $options = [
                    'rtrim'=>true,
                ];

                if(empty(Session::get('assignment')[0])){
                    $division="SYSADMIN";
                    $department="SYSADMIN";
                    $company_code="SYSADMIN";
                    $plant="SYSADMIN";
                    $plant_name="SYSADMIN";
                    $territory_id = "SYSADMIN";
                    $territory_name = "SYSADMIN";
                    $cost_center_id="SYSADMIN";
                    $job_title="SYSADMIN";

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

                $filter_created_from = !empty($request->get('created_date_from')) ? date('Ymd',strtotime($request->get('created_date_from'))) : '';
                $filter_created_to = !empty($request->get('created_date_to')) ? date('Ymd',strtotime($request->get('created_date_to'))) : '';
                $filter_cost_center = !empty($request->get('cost_center')) ? $request->get('cost_center') : $cost_center_id;
                $status = !empty($request->get('status')) ? $request->get('status') : '';

                $data['created_date_to']=$filter_created_to;
                $data['created_date_from']=$filter_created_from;
                $data['cost_center']=$filter_cost_center;
                $data['status']=$status;

                if($is_superuser || Session::get('permission_menu') && Session::get('permission_menu')->has("view_".route('finance.purchase-requisition.list_pr', array(), false))) {
                    $list_cost_center=DB::connection('dbintranet')
                    ->select("SELECT SAP_COST_CENTER_ID, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER");
                } else {
                    $list_cost_center=DB::connection('dbintranet')
                    ->select("SELECT SAP_COST_CENTER_ID, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE LEFT(SAP_DEPARTMENT_CODE, 4) = '$plant'");
                }

            } catch(\Exception $e){
                $data = [];
                $filter_cost_center = NULL;
                $list_cost_center = [];
            }

            return view('pages.finance.purchase-requisition.list-pr', [
                'data' => $data,
                'filtered_cost_center'=>$filter_cost_center,
                'list_cost_center'=>$list_cost_center
            ]);
        }
    }
}




