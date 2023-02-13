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
use Illuminate\Support\Facades\Input;
use Illuminate\Http\UploadedFile;
use App\Models\HumanResource\CompanyModel;
use App\Models\Zoho\ZohoFormModel;
use Cookie;
use Log;
use DataTables;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use App\Http\Controllers\Traits\IntranetTrait;
use Illuminate\Validation\ValidationException as ValidationException;

class AddMaterialMaster extends Controller{

    private $form_number;
    private $form_view;
    private $approval_view;
    private $approval_view_link;
    private $link_request;
    use IntranetTrait;

    public function __construct()
    {
        $this->form_number = "MTRL";
        $this->form_number_recipe = "MTRLRCP";
        $this->form_view="VIEW_FORM_REQUEST_MATERIAL_MASTER";
        $this->approval_view="VIEW_APPROVAL_FORM_REQUEST_MATERIAL_MASTER";
        $this->approval_view_link="/finance/add-material-master/approval";
        $this->link_request = "/finance/add-material-master/request";
    }

    public function request(Request $request){
        //init RFC
        $rfc = new SapConnection(config('intranet.rfc_prod'));
        $options = [
            'rtrim'=>true,
        ];
        //===

        $param = array();
        $function_type = $rfc->getFunction('ZFM_MM_MD_MATERIAL_TYPE');
        $material_type = $function_type->invoke($param, $options)['GT_REPORT'];

        $function_group = $rfc->getFunction('ZFM_MATERIAL_GROUP');
        $material_group = $function_group->invoke($param, $options)['ZTA_T023'];

        $function_valuation = $rfc->getFunction('ZFM_V_MD_VALUATION_CLASS');
        $material_valuation = $function_valuation->invoke($param, $options)['GT_VALUATION'];

        $function_measurement = $rfc->getFunction('ZFM_MD_UOM_LIST');
        $material_uom = $function_measurement->invoke($param, $options)['IT_DATA'];

        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

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
            $job_title = Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
            $midjob_id = Session::get('assignment')[0]->MIDJOB_TITLE_ID;
        }

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        $form_location = DB::connection('dbintranet')->table('INT_FORM_MASTER_LOCATION')->get();


        // START -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST
        $cek_alur_approval=DB::connection('dbintranet')
        ->table('INT_FORM_APPROVAL_MAPPING')
        ->where('FORMAPPROVAL_REQUESTOR_COSTCENTER', $cost_center_id)
        ->where('FORMAPPROVAL_REQUESTOR_MIDJOB', $midjob_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'MTRL')
        ->orWhere('FORMAPPROVAL_REQUESTOR_EMPLOYEE_ID',$employee_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'MTRL')
        ->get();

        $itung_alur_approval=count($cek_alur_approval);
        $allow_add_request=($itung_alur_approval>0)? true : false;

        $check_cross_plant_user = DB::connection('dbintranet')->table('INT_FORM_APPROVAL_MAPPING_CROSS_USER')->where('EMPLOYEE_ID',$employee_id)->where('TYPE_FORM','MTRL')->count();
        $is_cross_plant_user = ($check_cross_plant_user>0)? true : false;
        // END -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST

        $list_plant=DB::connection('dbintranet')->table('INT_BUSINESS_PLANT')->select('SAP_PLANT_ID')->orderBy('SAP_PLANT_ID','ASC')->get();
        $material_type_allow = ['27', '28', '31'];
        $material_type_recipe = collect($material_type)->reject(function($mtype) use ($material_type_allow){
            $type = isset($mtype['MATERIAL_TYPE']) ? $mtype['MATERIAL_TYPE'] : '';
            return !in_array($type, $material_type_allow);
        })->values()->all();

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
        'job_title'=>$job_title,
        'territory_id'=>$territory_id,
        'territory_name'=>$territory_name,
        'request_date_from'=>$request_date_from,
        'request_date_to'=>$request_date_to,
        'form_location'=>$form_location,
        'form_code'=>$this->form_number,
        'material_type'=>$material_type,
        'material_group'=>$material_group,
        'material_valuation'=>$material_valuation,
        'allow_add_request'=>$allow_add_request,
        'is_cross_plant_user'=>$is_cross_plant_user,
        'list_plant'=>$list_plant,
        'material_type_recipe'=>$material_type_recipe,
        'material_unit'=>$material_uom
        );

        return view('pages.finance.add-material-master.request', ['data' => $data]);
    }

    public function approval(Request $request){

        //init RFC
        $rfc = new SapConnection(config('intranet.rfc_prod'));
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
            'material_type'=>$material_type,
            'material_group'=>$material_group,
            'material_valuation'=>$material_valuation

        );


        return view('pages.finance.add-material-master.approval', ['data' => $data]);
    }

    public function report(Request $request){

        //init RFC
        $rfc = new SapConnection(config('intranet.rfc_prod'));
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
            $division="SYSADMIN";
            $department="SYSADMIN";
            $department_id="SYSADMIN";
            $company_code="SYSADMIN";
            $plant_name="SYSADMIN";
            $plant="SYSADMIN";
            $midjob_id="SYSADMIN";
            $costcenter="SYSADMIN";

        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $department_id=Session::get('assignment')[0]->DEPARTMENT_ID;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
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
        'plant_name'=>$plant_name,
        'employee_id'=>$employee_id,
        'employee_name'=>$employee_name,
        'division'=>$division,
        'department'=>$department,
        'department_id'=>$department_id,
        'midjob_id'=>$midjob_id,
        'costcenter'=>$costcenter,
        'status'=>$status,
        'request_date_from'=>$request_date_from,
        'request_date_to'=>$request_date_to,
        'form_code'=>$this->form_number,
        'material_type'=>$material_type,
        'material_group'=>$material_group,
        'material_valuation'=>$material_valuation
        );


        return view('pages.finance.add-material-master.report', ['data' => $data]);
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
                    ->table(DB::raw($this->form_view))
                    ->whereraw(DB::raw($where))->get();
            // dd(DB::getQueryLog());




            $result=array();
            foreach($data as $key=>$value){
                $data_json=json_decode($value->JSON_ENCODE);
                $result[]=array(
                    'UID'=>$value->UID,
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'INSERT_DATE'=>$value->INSERT_DATE,
                    'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                    'SAP_PLANT_NAME'=>$value->SAP_PLANT_NAME,
                    'DEPARTMENT_NAME'=>$value->DEPARTMENT_NAME,
                    'JSON_ENCODE'=>$value->JSON_ENCODE,
                    'REASON'=>$value->REASON,
                    'MATERIAL_NAME'=>$data_json->material_name
                );
            }

            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function report_getData(Request $request)
    {
        $result=array();
        try{

            $deptId=$request->input('deptId');
            $employeeId=$request->input('employeeId');
            $midjobId=$request->input('midjobId');
            $costcenter=$request->input('costcenter');
            $plant=isset(Session::get('assignment')[0]->SAP_PLANT_ID) ? Session::get('assignment')[0]->SAP_PLANT_ID : ''; //dapetin plant user yang login skrg
            $territory=isset(Session::get('assignment')[0]->TERRITORY_ID) ? Session::get('assignment')[0]->TERRITORY_ID : ''; //dapetin plant user yang login skrg

            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $status=strtoupper($request->input('status'));
            $requestor_name=$request->input('REQUESTOR_NAME', false);

            if($requestor_name){
                $where = "REQUESTOR_NAME='${requestor_name}' AND (";
            } else {
                $where="( ";
            }
            //start looping approval
            for ($i=0; $i<=7;$i++){
                $j=$i+1; // init variable untuk level approval sebenernya
                $prepend="";
                $append="";
                if($i>0){
                    $prepend="OR (";
                    $append=")";
                }
                // approval untuk superior, approval level 0 berarti belum ada approval sama sekali
                $where .=$prepend." APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                AND  APPROVAL_".$j."_TERRITORY_ID =
                CASE WHEN (
                    SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->form_view."
                    where APPROVAL_".$j."_PLANT_ID='".$plant."'
                    AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                ) = '0' THEN '0'
                WHEN (
                    SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->form_view."
                    where APPROVAL_".$j."_PLANT_ID='".$plant."'
                    AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                ) <> '0' THEN '".$territory."'
                END
                OR APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                ".$append."
                ";
            }
            $where = $where . " ) ";

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

            if(!empty($status)){
                $where = $where . " AND STATUS_APPROVAL ='$status' ";
            }

            $data = DB::connection('dbintranet')
                ->table(DB::raw($this->approval_view))
                ->whereraw(DB::raw($where))
                ->get()->unique('UID');

            foreach($data as $key=>$value){
                $data_json=json_decode($value->JSON_ENCODE);
                $result[]=array(
                    'UID'=>$value->UID,
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'INSERT_DATE'=>$value->INSERT_DATE,
                    'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                    'SAP_PLANT_NAME'=>$value->SAP_PLANT_NAME,
                    'DEPARTMENT_NAME'=>$value->DEPARTMENT_NAME,
                    'JSON_ENCODE'=>$value->JSON_ENCODE,
                    'NAME'=>$value->REQUESTOR_NAME,
                    'ALIAS'=>$value->REQUESTOR_NAME,
                    'REQUESTOR_NAME' =>$value->REQUESTOR_NAME,
                    'DIVISION_NAME' =>$value->DIVISION_NAME,
                    'APPROVAL_LEVEL'=>$value->APPROVAL_LEVEL,
                    'MATERIAL_NAME'=>$data_json->material_name,
                    'CROSS_PLANT'=>isset($value->CROSS_PLANT) ? $value->CROSS_PLANT : ''
                );
            }

            // return DataTables::of($result)->make(true);
        } catch(\Exception $e){
            Log::error('ADA ERROR DI MATERIAL REPORT GETDATA | '.(string)$e);
            // return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
        return DataTables::of($result)->make(true);
    }


    public function approval_getData(Request $request)
    {
        $result=array();
        try{
            $deptId=$request->input('deptId');
            $employeeId=$request->input('employeeId');
            $midjobId=$request->input('midjobId');
            $costcenter=$request->input('costcenter');
            $plant=isset(Session::get('assignment')[0]->SAP_PLANT_ID) ? Session::get('assignment')[0]->SAP_PLANT_ID : ''; //dapetin plant user yang login skrg
            $territory=isset(Session::get('assignment')[0]->TERRITORY_ID) ? Session::get('assignment')[0]->TERRITORY_ID : ''; //dapetin plant user yang login skrg
            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $requestor_name = $request->input('REQUESTOR_NAME', false);

            $where="";
            if($requestor_name){
                $where = $where."REQUESTOR_NAME='${requestor_name}' AND";
            }
            //start looping approval
            for ($i=0; $i<=7;$i++){
                $j=$i+1; // init variable untuk level approval sebenernya
                $prepend="";
                $append="";
                if($i>0){
                    $prepend="OR (";
                    $append=")";
                }
                // approval untuk superior, approval level 0 berarti belum ada approval sama sekali
                
                if($requestor_name){
                    $where .=$prepend."
                    REQUESTOR_NAME='".$requestor_name."' AND APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
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
                    OR REQUESTOR_NAME='".$requestor_name."' AND APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                    AND APPROVAL_LEVEL = ".$i." AND STATUS_APPROVAL <> 'REJECTED'
                    ".$append."
                    ";
                } else {
                    $where .=$prepend."
                    APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
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


            $data = DB::connection('dbintranet')
                ->table(DB::raw($this->approval_view))
                ->whereraw(DB::raw($where))
                ->orderBy('INSERT_DATE', 'ASC')
                ->get();

            foreach($data as $key=>$value){
                $data_json=json_decode($value->JSON_ENCODE);
                $result[]=array(
                    'UID'=>$value->UID,
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'INSERT_DATE'=>$value->INSERT_DATE,
                    'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                    'SAP_PLANT_NAME'=>$value->SAP_PLANT_NAME,
                    'DEPARTMENT_NAME'=>$value->DEPARTMENT_NAME,
                    'JSON_ENCODE'=>$value->JSON_ENCODE,
                    'NAME'=>$value->REQUESTOR_NAME,
                    'ALIAS'=>$value->REQUESTOR_NAME,
                    'REQUESTOR_NAME' =>$value->REQUESTOR_NAME,
                    'DIVISION_NAME' =>$value->DIVISION_NAME,
                    'APPROVAL_LEVEL'=>$value->APPROVAL_LEVEL,
                    'MATERIAL_NAME'=>$data_json->material_name
                );
            }
        } catch(\Exception $e){
            Log::error('APPROVAL GET DATA MATERIAL ERROR | '.(string)$e);
            // return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }

        return DataTables::of($result)->make(true);

    }

    public function getHistoryApproval(Request $request){
        $rest = new ZohoFormModel();
        $result = $rest->getHistoryApproval($request,$this->form_view, $this->approval_view);
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

    public function save_with_form_data(Request $request){
        try{
            $connection = DB::connection('dbintranet');
            $connection->beginTransaction();

            $FormID=imuneString($request->input('form_number')); //mendapatkan UID formnya
            $approvalLevel=$request->input('approval_level_previous'); // mendapatkan approval level sebelumnya
            $approvalLevel=$approvalLevel+1; //tambah approval untuk jenjang sekarang
            $EmployeID=imuneString($request->input('employee_id')); //employee id yang approve
            $StatusApproval=imuneString($request->input('status_approval')); // APPROVED / REJECTED
            $TypeForm=imuneString($request->input('type_form')); // type form nya ADD MATERIAL
            $Reason=imuneString($request->input('reason')); //reason
            $aksi=imuneString($request->input('aksi')); //aksi : approve / reject


            $result = $this->approve($FormID, $approvalLevel, $EmployeID, $StatusApproval,$TypeForm,$Reason);


            if($result['code']=='200'){ //jika berhasil masukkan ke function approve

                if($aksi=="approve"){ // jika aksi APPROVE
                    $existing_data=DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_RAW_DATA')
                            ->where('UID',$FormID)
                            ->get();
                    $json=json_decode($existing_data[0]->JSON_ENCODE);
                    // dd($json, $approvalLevel);
                    // START UPDATE DATA
                    if(isset($json->Is_Recipe) && strtoupper($json->Is_Recipe) == 'Y'){
                        if($approvalLevel=="2"){
                            //approval yang dilakukan oleh accounting (level 3)
                            $json->material_type = $request->input('material_type');
                            $json->material_group = $request->input('material_group');
                            $json->material_valuation = $request->input('material_valuation');

                            $json_updated = json_encode($json);

                            $update_data=DB::connection('dbintranet')
                                        ->table('INT_FIN_APPR_RAW_DATA')
                                        ->where('UID',$FormID)
                                        ->update(
                                            [
                                                "JSON_ENCODE" => $json_updated,
                                            ]
                                        );
                        }else if($approvalLevel=="3"){
                            //approval yang dilakukan oleh IT (level 4)
                            $json->material_number = $request->input('material_number');
                            $json_updated = json_encode($json);

                            $update_data=DB::connection('dbintranet')
                                        ->table('INT_FIN_APPR_RAW_DATA')
                                        ->where('UID',$FormID)
                                        ->update(
                                            [
                                                "JSON_ENCODE" => $json_updated,
                                            ]
                                        );
                        }
                    } else {
                        if($approvalLevel=="2"){
                            //approval yang dilakukan oleh purchasing (level 2)
                            $json->material_name = $request->input('material_name');
                            $json->material_unit = $request->input('material_unit');

                            $json_updated = json_encode($json);

                            $update_data=DB::connection('dbintranet')
                                        ->table('INT_FIN_APPR_RAW_DATA')
                                        ->where('UID',$FormID)
                                        ->update(
                                            [
                                                "JSON_ENCODE" => $json_updated,
                                            ]
                                        );
                        }
                        else if($approvalLevel=="3"){
                            //approval yang dilakukan oleh accounting (level 3)
                            $json->material_type = $request->input('material_type');
                            $json->material_group = $request->input('material_group');
                            $json->material_valuation = $request->input('material_valuation');

                            $json_updated = json_encode($json);

                            $update_data=DB::connection('dbintranet')
                                        ->table('INT_FIN_APPR_RAW_DATA')
                                        ->where('UID',$FormID)
                                        ->update(
                                            [
                                                "JSON_ENCODE" => $json_updated,
                                            ]
                                        );
                        }else if($approvalLevel=="4"){
                            //approval yang dilakukan oleh IT (level 4)
                            $json->material_number = $request->input('material_number');
                            $json_updated = json_encode($json);

                            $update_data=DB::connection('dbintranet')
                                        ->table('INT_FIN_APPR_RAW_DATA')
                                        ->where('UID',$FormID)
                                        ->update(
                                            [
                                                "JSON_ENCODE" => $json_updated,
                                            ]
                                        );
                        }
                    }
                }


                $hasil['code'] = 200;
                $hasil['message'] = 'Request has been approved';
                $connection->commit();
            }else{
                $hasil['code'] = 401;
                $hasil['message'] = $e->errorInfo;
            }
        }catch(QueryException $e) {
            $hasil['code'] = 401;
            $hasil['message'] = $e->errorInfo;
            $connection->rollback();
        }
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


            // validasi jika form sudah sampai di tahap terakhir, maka status akan jadi finished
            $cek_finished_approval=DB::connection('dbintranet')->select("SELECT MAX(LEVEL_APPROVAL) AS MAX_APPROVAL FROM INT_FIN_APPR_RAW_DATA A INNER JOIN INT_FIN_APPR_ROLE B ON A.TYPE_FORM = B.FORM_NUMBER
            WHERE A.UID = '".$FormID."'");
            $max_approval = $cek_finished_approval[0]->MAX_APPROVAL;
            if($ApprovalLevel==$max_approval && $StatusApproval=="APPROVED"){
                $StatusApproval ='FINISHED';
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
                $notif_desc = "Please approve Add Material Master Form : ".$FormID."";
                $notif_type="info";
                $notif_link=$this->approval_view_link;
                $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);

            }else if($StatusApproval=="REJECTED"){
                //jika reject, maka kirim notif ke requestor
                $data_approval = DB::connection('dbintranet')
                ->table(DB::raw($this->approval_view))
                ->where('UID',$uid)
                ->get();

                $notif_employee_id=$data_approval[0]->REQUESTOR_ID;
                $notif_link=$this->link_request;
                $notif_desc="Your request Add Material Master : ".$uid." is rejected";
                $notif_type="reject";

                $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type);
            }
            if($StatusApproval=="FINISHED"){
                //jika sudah finish, maka kirim notif ke requestor
                $data_approval = DB::connection('dbintranet')
                ->table(DB::raw($this->approval_view))
                ->where('UID',$uid)
                ->get();

                $notif_employee_id=$data_approval[0]->REQUESTOR_ID;
                $notif_link=$this->link_request;
                $notif_desc="Your request Add Material Master : ".$uid." is approved";
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

    public function modal_request(){
        return View::make('pages.finance.add-material-master.modal_request')->render();
    }

    public function ajax_location(Request $request){
        $id=$request->input('id');
        $data=DB::table('INT_FORM_MASTER_SBU')
                ->where('SBU_LOCATION_ID',$id)
                ->get();
        $result='<option value="" disabled selected>-Select-</option>';
        foreach($data as $d){
            $result.="<option value='".$d->SBU_ID."'>".$d->SBU_NAME."</option>";
        }
        return $result;
    }

    public function ajax_sbu(Request $request){
        $id=$request->input('id');
        $data=DB::table('INT_FORM_MASTER_OUTLET')
                ->where('OUTLET_SBU_ID',$id)
                ->get();
        $result='<option value="" disabled selected>-Select-</option>';
        foreach($data as $d){
            $result.="<option value='".$d->OUTLET_ID."'>".$d->OUTLET_NAME."</option>";
        }
        return $result;
    }

    public function ajax_enttype(Request $request){

        $territory=$request->input('territory');

        if(!empty($territory)){
            $data=DB::connection('dbintranet')->table('INT_FORM_MASTER_LOCATION')
                ->where('LOCATION_TERRITORY','like','%'.$territory.'%')
                ->get();

        }else{
            $data=DB::connection('dbintranet')->table('INT_FORM_MASTER_LOCATION')
                ->orderBy('LOCATION_NAME')
                ->get();
        }


        $result='<option value="" disabled selected>-Select-</option>';
        foreach($data as $d){
            $result.="<option value='".$d->LOCATION_ID."'>".$d->LOCATION_NAME."</option>";
        }
        return $result;
    }


    public function save(Request $request){
        // =============
        // cari data sequence dari FORM
        try{
            if($request->post('Is_Recipe') && strtoupper($request->post('Is_Recipe')) == 'Y')
                $type_form=$this->form_number_recipe;
            else 
                $type_form=$this->form_number;

            $year = date('Y');
            $last_seq=DB::connection('dbintranet')
                        ->select("SELECT TOP 1 CASE WHEN UID IS NULL THEN NULL ELSE UID END AS LAST_SEQ FROM INT_FIN_APPR_RAW_DATA WHERE TYPE_FORM ='$type_form'  ORDER BY ID DESC ");
            if(isset($last_seq[0]->LAST_SEQ) && !empty($last_seq[0]->LAST_SEQ)){
                $explode_uid=explode('-',$last_seq[0]->LAST_SEQ);
                $nomor_akhir=(int)end($explode_uid);
            }else{
                $nomor_akhir=0;
            }


            $new_seq = $nomor_akhir + 1;
            $new_seq = sprintf("%010d", $new_seq);

            $uid=$type_form.'-'.$new_seq;

            $data=$request->post();
            $data['material_name']=strtoupper($data['material_name']); //force uppercase
            $data['material_unit']=strtoupper($data['material_unit']);

            unset($data['_token']);
            $data['uid']=$uid;

            $cross_plant = isset($data['custom_plant']) && !empty($data['custom_plant']) ? 1 : 0;

            $validation = $request->validate([
                'material_image' => 'mimes:jpg,jpeg,png,JPG,JPEG,PNG,pdf|max:2048'
            ]);

             /* upload material image */
            if(!empty($request->material_image)){
                $original_name= $request->material_image->getClientOriginalName();
                $imageName = time().'.'.$original_name;
                $request->material_image->move(public_path('upload/material'), $imageName);
                $data['material_image']=$imageName;

            }else{
                $data['material_image']=null;
            }

            /* upload quotation */
            if(!empty($request->quotation)){
                $original_name= $request->quotation->getClientOriginalName();
                $imageName = time().'.'.$original_name;
                $request->quotation->move(public_path('upload/material'), $imageName);
                $data['quotation']=$imageName;

            }else{
                $data['quotation']=null;
            }


            // =============
            //kebutuhan insert data
            $data_json=json_encode($data);
            $employee_id=$data['Requestor_Employee_ID'];
            $type="Request Penambahan Material";

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
                    "CROSS_PLANT" => $cross_plant
                ]
            );

            //insert ke notifikasi untuk approver
            if($insert){

                $level_approval = 1;
                $notif_link=$this->approval_view_link;
                $notif_desc="Please approve Add Material Master Form : ".$uid."";
                $notif_type="info";

                $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);

            }

            $success=true;
            $code = 200;
            $msg = 'Your request has been sent';
        } catch(\Illuminate\Validation\ValidationException $e) {
            $error_image = isset($e->errors()['material_image'][0]) ? $e->errors()['material_image'][0] : 'Type of uploaded file is not valid';
            $success=false;
            $code=403;
            $msg=$error_image;
        } catch(QueryException $e) {
            $success=false;
            $code=403;
            $msg=$e->errorInfo;
        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));
    }

    public function modal_detail(Request $request)
    {        
        //init RFC
        $rfc = new SapConnection(config('intranet.rfc_prod'));
        $options = [
            'rtrim'=>true,
        ];
        //===

        $uid=$request->input('id');
        $action=(!empty($request->input('action')))? $request->input('action') : 'view'; // flag action, gunanya adalah ketika di modal detail supaya bisa kasi validasi apakah harus kasi tombol approve & reject di modal atau tidak (tapi berbeda dengan modal approve detail, ini hanya untuk approval tanpa inputan apapun)
        $data_form=NULL;
        $data_json=NULL;

        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
                    ->table($this->form_view)
                    ->where('UID',$uid)
                    ->get();
            $data_json = json_decode($data_form[0]->JSON_ENCODE);
        }

        $param = array();
        $function_type = $rfc->getFunction('ZFM_MM_MD_MATERIAL_TYPE');
        $material_type= $function_type->invoke($param, $options)['GT_REPORT'];

        $function_group = $rfc->getFunction('ZFM_MATERIAL_GROUP');
        $material_group= $function_group->invoke($param, $options)['ZTA_T023'];

        $function_valuation = $rfc->getFunction('ZFM_V_MD_VALUATION_CLASS');
        $material_valuation= $function_valuation->invoke($param, $options)['GT_VALUATION'];

        $is_cross_plant = (isset($data_form[0]->CROSS_PLANT) && $data_form[0]->CROSS_PLANT==1)? true : false;

        $data_custom_request=NULL;

        if($is_cross_plant){
            $custom_cost_center=$data_form[0]->COST_CENTER_ID;
            $data_custom_request=DB::connection('dbintranet')->select("SELECT LEFT(TERRITORY_ID,4) AS PLANT FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID='$custom_cost_center' ");
        }

        $material_type_allow = ['27', '28', '31'];
        $material_type_recipe = collect($material_type)->reject(function($mtype) use ($material_type_allow){
            $type = isset($mtype['MATERIAL_TYPE']) ? $mtype['MATERIAL_TYPE'] : '';
            return !in_array($type, $material_type_allow);
        })->values()->all();

        $data=array(
            'material_type'=>$material_type,
            'material_group'=>$material_group,
            'material_valuation'=>$material_valuation,
            'material_type_recipe'=>$material_type_recipe,
            'data_form'=>$data_form[0],
            'data_json'=>$data_json,
            'action'=>$action,
            'is_cross_plant'=>$is_cross_plant,
            'data_custom_request'=>$data_custom_request
        );

        return View::make('pages.finance.add-material-master.modal-detail')->with('data', $data)->render();
    }

    public function modal_approve_detail(Request $request)
    {
        //init RFC
        $rfc = new SapConnection(config('intranet.rfc_prod'));
        $options = [
            'rtrim'=>true,
        ];
        $param = array();

        // set applucation json contenttype to get mat group and valuation class
        if(self::wantsJson($request)){
            $new_material_group = [];
            $new_valuation_class = [];
            $plant=isset(Session::get('assignment')[0]->SAP_PLANT_ID) ? Session::get('assignment')[0]->SAP_PLANT_ID : '';

            /* If request ajax and Material Type change, find material group based on selection */
            if($request->get('material_type')){
                $param['P_MATERIAL_TYPE'] = isset(explode('-',$request->get('material_type'))[0]) ? explode('-',$request->get('material_type'))[0] : '';
                $function_group = $rfc->getFunction('ZFM_MATERIAL_GROUP');
                $new_material_group = $function_group->invoke($param, $options)['ZTA_T023'];

                $function_valuation = $rfc->getFunction('ZFM_MD_VALUATION_CLASS');
                $new_valuation_class = $function_valuation->invoke($param, $options)['GT_VALUATION'];

                /**
                 * Mapping Material Group By Plant
                 * KMS1=AYBL
                 * PPC1=AYKW
                 * PAD1=AYJK
                 * WKK1=DHKR
                 * PAD3=PAD3
                 */
                $plant_type = [
                    'KMS1'=>'AYBL',
                    'PPC1'=>'AYKW',
                    'PAD1'=>'AYJK',
                    'WKK1'=>'DHKR',
                    'PAD3'=>'PAD3'
                ];

                if($request->get('refer') && strtolower($request->get('refer')) == 'mtrl_req'){                    
                    $plant_selected = isset($plant_type[$plant]) ? $plant_type[$plant] : '';
                    if($plant_selected){
                        $new_material_group = collect($new_material_group)->reject(function($item) use ($plant_selected){
                            $item_data = isset($item['WGBEZ']) ? $item['WGBEZ'] : '';
                            return strpos($item_data, $plant_selected) === false;
                        })->values()->all();
                    } else {
                        $new_material_group = [];
                    }
                }

                else if($request->get('refer') && strtolower($request->get('refer')) == 'mtrl_approve'){
                    try {
                        $plant_requestor = $request->get('plant_requestor');
                        $plant_selected = isset($plant_type[$plant_requestor]) ? $plant_type[$plant_requestor] : '';
                        //$new_material_group_check = collect($new_material_group)->reject(function($item) use ($plant_selected){
                        //    $item_data = isset($item['WGBEZ']) ? $item['WGBEZ'] : '';
                        //    return strpos($item_data, $plant_selected) === false;
                        //})->values()->all();
                        //if(count($new_material_group_check) > 0)
                        //    $new_material_group = $new_material_group_check;

                    } catch(\Exception $e){}
                }
            }


            return response()->json(['MAT_GROUP'=>$new_material_group, 'VAL_CLASS'=>$new_valuation_class]);
        } else {
            //===
            $uid=$request->input('id');
            $data_form=NULL;
            $data_json=NULL;
            if(!empty($uid)){
                $data_form=DB::connection('dbintranet')
                        ->table('INT_FIN_APPR_RAW_DATA')
                        ->join('INT_FIN_APPR_LIST','INT_FIN_APPR_RAW_DATA.UID','=','INT_FIN_APPR_LIST.FORM_ID')
                        ->where('INT_FIN_APPR_RAW_DATA.UID',$uid)
                        ->get();
                $data_json = json_decode($data_form[0]->JSON_ENCODE);
            }


            $function_type = $rfc->getFunction('ZFM_MM_MD_MATERIAL_TYPE');
            $material_type= $function_type->invoke($param, $options)['GT_REPORT'];

            $function_group = $rfc->getFunction('ZFM_MATERIAL_GROUP');
            $material_group= $function_group->invoke($param, $options)['ZTA_T023'];

            $function_valuation = $rfc->getFunction('ZFM_V_MD_VALUATION_CLASS');
            $material_valuation= $function_valuation->invoke($param, $options)['GT_VALUATION'];            
            $is_cross_plant = (isset($data_form[0]->CROSS_PLANT) && $data_form[0]->CROSS_PLANT==1)? true : false;

            $data_custom_request=NULL;
            if($is_cross_plant){
                // $custom_cost_center=$data_form[0]->COST_CENTER_ID;
                $custom_cost_center = $data_json->custom_cost_center;
                $data_custom_request=DB::connection('dbintranet')->select("SELECT LEFT(TERRITORY_ID,4) AS PLANT FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID='$custom_cost_center' ");
            }

            $material_type_allow = ['27', '28', '31'];
            $material_type_recipe = collect($material_type)->reject(function($mtype) use ($material_type_allow){
                $type = isset($mtype['MATERIAL_TYPE']) ? $mtype['MATERIAL_TYPE'] : '';
                return !in_array($type, $material_type_allow);
            })->values()->all();

            $data=array(
                'material_type'=>$material_type,
                'material_group'=>$material_group,
                'material_valuation'=>$material_valuation,
                'material_type_recipe'=>$material_type_recipe,
                'data_form'=>$data_form[0],
                'data_json'=>$data_json,
                'is_cross_plant'=>$is_cross_plant,
                'data_custom_request'=>$data_custom_request

            );

            return View::make('pages.finance.add-material-master.modal-approve-detail')->with('data', $data)->render();
        }

    }

    public function getCostCenterApprovalList($costcenter, $level){
        $approve_level = $level+1; //adalah level approve yang dilakukan saat ini,sedangkan level adalah nilai approve sebelumnya yang harus dicapai untuk melakukan approve



        $string="";
        // $getCostCenter=DB::connection('dbintranet')
        //                     ->select("SELECT DISTINCT(A.UID) FROM ".$this->form_view." A INNER JOIN INT_FIN_APPR_ROLE B ON A.TYPE_FORM = B.FORM_NUMBER WHERE B.TYPE_APPROVAL = 'COST_CENTER' AND A.APPROVAL_LEVEL = ".$level." AND B.VALUE_APPROVAL LIKE '%".$costcenter."%'");

        $getCostCenter=DB::connection('dbintranet')
        ->select("SELECT V.* FROM ".$this->form_view." V
        INNER JOIN INT_FIN_APPR_ROLE R ON V.TYPE_FORM = R.FORM_NUMBER
        WHERE R.TYPE_APPROVAL = 'COST_CENTER' AND R.LEVEL_APPROVAL = ".$approve_level."
        AND R.VALUE_APPROVAL LIKE '%".$costcenter."%'
        AND V.APPROVAL_LEVEL = ".$level."");

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

    public function insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type){
        $data_approval = DB::connection('dbintranet')
            ->table(DB::raw($this->approval_view))
            ->where('UID',$uid)
            ->get();
        $select=NULL;


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

    public function ajax_getCostCenterCustom(Request $request){
        $plant=$request->input('plant');
        if($plant){
            $cost_center=DB::connection('dbintranet')
            ->select("SELECT aps.FORMAPPROVAL_REQUESTOR_COSTCENTER AS COST_CENTER, cc.SAP_COST_CENTER_DESCRIPTION AS DESCRIPTION
                FROM INT_SAP_COST_CENTER cc
                INNER JOIN INT_FORM_APPROVAL_MAPPING aps ON cc.SAP_COST_CENTER_ID = aps.FORMAPPROVAL_REQUESTOR_COSTCENTER
                WHERE LEFT(cc.TERRITORY_ID,4) = '$plant' AND aps.FORMAPPROVAL_REQUESTOR_TYPE_FORM='MTRL'
                GROUP BY aps.FORMAPPROVAL_REQUESTOR_COSTCENTER, cc.SAP_COST_CENTER_DESCRIPTION
            ");
            return json_encode($cost_center);
        }
    }

    public function ajax_getMidjobCustom(Request $request){
        $cost_center=$request->input('cost_center');
        if($cost_center){
            $midjob=DB::connection('dbintranet')
            ->select("SELECT maps.FORMAPPROVAL_REQUESTOR_MIDJOB AS MIDJOB, midjob.MIDJOB_TITLE_NAME AS DESCRIPTION
                FROM INT_MIDJOB_GRADING midjob
                INNER JOIN INT_FORM_APPROVAL_MAPPING maps ON midjob.MIDJOB_TITLE_ID = maps.FORMAPPROVAL_REQUESTOR_MIDJOB
                WHERE maps.FORMAPPROVAL_REQUESTOR_COSTCENTER ='$cost_center' AND maps.FORMAPPROVAL_REQUESTOR_TYPE_FORM='MTRL'
                GROUP BY maps.FORMAPPROVAL_REQUESTOR_MIDJOB, midjob.MIDJOB_TITLE_NAME
            ");
            return json_encode($midjob);
        }
    }




}




