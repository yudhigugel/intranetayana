<?php

namespace App\Http\Controllers\Finance;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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

class EntertainmentForm extends Controller{

    private $form_number;
    private $form_view;
    private $approval_view;
    private $approval_view_link;
    private $link_request;

    public function __construct()
    {
        $this->form_number = "FENT";
        $this->form_view="VIEW_FORM_REQUEST_ENTERTAINMENT";
        $this->approval_view="VIEW_APPROVAL_FORM_REQUEST_ENTERTAINMENT";
        $this->approval_view_link="/finance/entertainmentForm/approval";
        $this->link_request = "/finance/entertainmentForm/request";
    }

    public function entertainmentform_request(Request $request){

        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        // dd(Session::get('assignment'));

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

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        $form_location = DB::connection('dbintranet')->table('INT_FORM_MASTER_LOCATION')->get();

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
        'form_location'=>$form_location
        );


        return view('pages.finance.entertainmentform.request', ['data' => $data]);
    }

    public function entertainmentform_approval(Request $request){

        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        if(empty(Session::get('assignment')[0])){
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $division="SYSADMIN";
            $department="SYSADMIN";
            $department_id="";
            $midjob_id="";
        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $department_id=Session::get('assignment')[0]->DEPARTMENT_ID;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $midjob_id=Session::get('assignment')[0]->MIDJOB_TITLE_ID;
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
            'status'=>$status,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
            'form_code'=>"REQ-ENT"

        );


        return view('pages.finance.entertainmentform.approval', ['data' => $data]);
    }

    public function entertainmentform_report(Request $request){

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
        'request_date_to'=>$request_date_to
        );


        return view('pages.finance.entertainmentform.report', ['data' => $data]);
    }

    public function entertainmentform_request_getData(Request $request){
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

            DB::connection('dbintranet')->enableQueryLog();
            $data = DB::connection('dbintranet')
                    ->table(DB::raw('VIEW_FORM_REQUEST_ENTERTAINMENT'))
                    ->whereraw(DB::raw($where))->get();
            $result=array();
            foreach($data as $key=>$value){
            $result[]=array(
                'UID'=>$value->UID,
                'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                'INSERT_DATE'=>$value->INSERT_DATE,
                'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                'SAP_PLANT_NAME'=>$value->SAP_PLANT_NAME,
                'DEPARTMENT_NAME'=>$value->DEPARTMENT_NAME,
                'JSON_ENCODE'=>$value->JSON_ENCODE,
                'REASON'=>$value->REASON
            );
            }

            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function entertainmentform_approval_getData(Request $request)
    {

        try{

            $deptId=$request->input('deptId');
            $employeeId=$request->input('employeeId');
            $midjobId=$request->input('midjobId');
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID; //dapetin plant user yang login skrg
            $territory=Session::get('assignment')[0]->TERRITORY_ID; //dapetin plant user yang login skrg


            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');


            $where="";
            // start looping approval
            for ($i=0; $i<=7;$i++){
                $j=$i+1; // init variable untuk level approval sebenernya
                $prepend="";
                $append="";
                if($i>0){
                    $prepend="OR (";
                    $append=")";
                }
                // approval untuk superior, approval level 0 berarti belum ada approval sama sekali
                $where .=$prepend."APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
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
                END ".$append."
                ";
            }


            DB::enableQueryLog();


            $data=DB::connection('dbintranet')
                ->select("
                SELECT * FROM
                (
                    SELECT * FROM ".$this->approval_view."
                    INNER JOIN [INT_FORM_APPROVAL_MAPPING] on [INT_FORM_APPROVAL_MAPPING].[FORMAPPROVAL_REQUESTOR_COSTCENTER] = ".$this->approval_view.".[COST_CENTER_ID]
                    WHERE ".$where."
                ) AS DATA_FULL
                LEFT JOIN
                (
                    SELECT UID AS SECOND_UID, MAX_APPROVAL_LEVEL FROM
                    (SELECT * FROM
                        (
                        SELECT V.UID, JSON_VALUE(CAST(V.JSON_ENCODE AS nvarchar(max)), '$.No_Guest') AS PAX, V.COST_CENTER_ID, V.APPROVAL_LEVEL,
                        L.PAXLIMIT_MINIMUM_PAX AS MINIMUM_PAX, L.PAXLIMIT_MAXIMUM_PAX AS MAXIMUM_PAX, L.PAXLIMIT_APPROVAL_MAXIMUM AS MAX_APPROVAL_LEVEL
                        FROM INT_FORM_APPROVAL_PAX_LIMIT L
                        INNER JOIN ".$this->approval_view." V ON L.PAXLIMIT_COST_CENTER_ID = V.COST_CENTER_ID
                        ) AS DATA
                    WHERE PAX >= MINIMUM_PAX AND PAX <= MAXIMUM_PAX
                    ) AS DATA_2
                ) DATA_SECOND
                ON DATA_FULL.UID = DATA_SECOND.SECOND_UID
                WHERE APPROVAL_LEVEL < MAX_APPROVAL_LEVEL
                ");

            $result=array();
            foreach($data as $key=>$value){
            $result[]=array(
                'UID'=>$value->UID,
                'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                'INSERT_DATE'=>$value->INSERT_DATE,
                'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                'SAP_PLANT_NAME'=>$value->SAP_PLANT_NAME,
                'DEPARTMENT_NAME'=>$value->DEPARTMENT_NAME,
                'JSON_ENCODE'=>$value->JSON_ENCODE,
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

            $form_view="VIEW_FORM_REQUEST_ENTERTAINMENT";
            $rest = new ZohoFormModel();
            $result = $rest->getHistoryApprovalWithLimit($request,$form_view);
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

    public function approve($FormID,$ApprovalLevel,$LastApprovalID,$StatusApproval,$TypeForm,$Reason)
    {

        try {
            $connection = DB::connection('dbintranet');
            $connection->beginTransaction();

            $CountData=DB::connection('dbintranet')->table('INT_FIN_APPR_LIST')
                    ->where('FORM_ID',$FormID)
                    ->count();


             // validasi jika form sudah sampai di tahap terakhir, maka status akan jadi finished
             // kali ini pakai query ke tabel INT_FORM_APPROVAL_PAX_LIMIT

                $query = DB::connection('dbintranet')
                ->select("
                SELECT LEVEL_APPROVAL FROM (
                    SELECT * FROM
                        (select C.LEVEL_APPROVAL, C.TYPE_DESC , D.APPROVAL_ID,E.EMPLOYEE_NAME , D.APPROVAL_DATE, D.STATUS_APPROVAL, D.REASON,
                        L.PAXLIMIT_MINIMUM_PAX AS MINIMUM_PAX, L.PAXLIMIT_MAXIMUM_PAX AS MAXIMUM_PAX, L.PAXLIMIT_APPROVAL_MAXIMUM AS MAX_APPROVAL_LEVEL,
                        JSON_VALUE(CAST(B.JSON_ENCODE AS nvarchar(max)), '$.No_Guest') AS PAX
                        from INT_FIN_APPR_LIST A
                        right join ".$this->form_view." B on B.UID = A.FORM_ID
                        right join INT_FIN_APPR_ROLE C on C.FORM_NUMBER = B.TYPE_FORM
                        left join INT_FIN_APPR_HISTORY D on D.FORM_ID = A.FORM_ID and D.APPROVAL_LEVEL = C.LEVEL_APPROVAL
                        left join VIEW_EMPLOYEE E on E.EMPLOYEE_ID = D.APPROVAL_ID
                        left join INT_FORM_APPROVAL_PAX_LIMIT L on L.PAXLIMIT_COST_CENTER_ID = B.COST_CENTER_ID
                        where B.UID = '".$FormID."' ) AS DATA
                    WHERE PAX >= MINIMUM_PAX AND PAX <= MAXIMUM_PAX
                ) AS DATA_2
                WHERE LEVEL_APPROVAL <= MAX_APPROVAL_LEVEL
                ORDER BY LEVEL_APPROVAL
                ");

                $max_approval=end($query);

                $max_approval=$max_approval->LEVEL_APPROVAL;
                if($ApprovalLevel==$max_approval && $StatusApproval=="APPROVED"){
                    $StatusApproval ='FINISHED';
                }

             //======================

            if ($CountData == 0){
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
            }else{
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

            if ($CountDataHistory == 0){
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
            }else{
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
                $notif_desc = "Please approve Entertainment Form : ".$FormID."";
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
                $notif_desc="Your request Entertainment Form : ".$uid." is rejected";
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
                $notif_desc="Your request Entertainment Form : ".$uid." is approved";
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

    public function modal_detail(Request $request)
    {


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

        $form_location = DB::connection('dbintranet')->table('INT_FORM_MASTER_LOCATION')->get();

        if(!empty($data_json->Location)){
            $data_json->Location_desc = DB::connection('dbintranet')->select("SELECT LOCATION_NAME FROM INT_FORM_MASTER_LOCATION WHERE LOCATION_ID = $data_json->Location");
            $data_json->Location_desc= $data_json->Location_desc[0]->LOCATION_NAME;
        }

        if(!empty($data_json->SBU)){
            $data_json->SBU_desc = DB::connection('dbintranet')->select("SELECT SBU_NAME FROM INT_FORM_MASTER_SBU WHERE SBU_ID = $data_json->SBU");
            $data_json->SBU_desc= $data_json->SBU_desc[0]->SBU_NAME;
        }

        if(!empty($data_json->Outlet)){
            $data_json->Outlet_desc = DB::connection('dbintranet')->select("SELECT OUTLET_NAME FROM INT_FORM_MASTER_OUTLET WHERE OUTLET_ID = $data_json->Outlet");
            $data_json->Outlet_desc= $data_json->Outlet_desc[0]->OUTLET_NAME;
        }

        $data=array(
            'data_form'=>$data_form[0],
            'data_json'=>$data_json,
            'form_location'=>$form_location,
            'action'=>$action
        );

        return View::make('pages.finance.entertainmentform.modal-detail')->with('data', $data)->render();
    }

    public function modal_request(){
        return View::make('pages.finance.entertainmentform.modal_request')->render();
    }

    public function ajax_location(Request $request){
        $id=$request->input('id');
        $data=DB::connection('dbintranet')->table('INT_FORM_MASTER_SBU')
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
        $data=DB::connection('dbintranet')->table('INT_FORM_MASTER_OUTLET')
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
        // cari data sequence dari REQ-ENT
        try{
            $type_form=$this->form_number;
            $year = date('Y');
            $last_seq=DB::connection('dbintranet')
                        // ->select("SELECT  CASE WHEN MAX(ID) IS NULL THEN 0 ELSE MAX(ID)END AS LAST_SEQ FROM INT_FIN_APPR_RAW_DATA WHERE TYPE_FORM ='REQ-ENT' AND UID LIKE '%REQ-ENT-2021%'");
                        ->select("SELECT TOP 1 CASE WHEN UID IS NULL THEN NULL ELSE UID END AS LAST_SEQ FROM INT_FIN_APPR_RAW_DATA WHERE TYPE_FORM ='$type_form' ORDER BY ID DESC ");
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
            $type="Request Entertainment";



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

            //insert ke notifikasi untuk approver
            if($insert){

                $level_approval = 1;
                $notif_link=$this->approval_view_link;
                $notif_desc="Please approve Entertainment Form : ".$uid."";
                $notif_type="info";

                $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);

            }



            $data['code'] = 200;
            $data['message'] = 'Success';

        } catch(QueryException $e) {
            $data['code'] = 403;
            $data['message'] = $e->errorInfo;
        }

        return $data;
    }

    public function insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type){
        $data_approval = DB::connection('dbintranet')
            ->table(DB::raw($this->approval_view))
            ->where('UID',$uid)
            ->get();

            // $notif_link=$this->approval_view_link;
            // $notif_desc=$desc;
            // $notif_type="info";

            if(!$data_approval->isEmpty()){
                $data_approval=collect($data_approval[0])->toArray();

                $i = $level_approval; // mencari approval selanjutnya untuk diberikan notif
                $appr_midjob=($data_approval['APPROVAL_'.$i.'_MIDJOB_ID']) ? $data_approval['APPROVAL_'.$i.'_MIDJOB_ID'] : NULL;

                if(!empty($appr_midjob)){

                    $select = "SELECT EMPLOYEE_ID FROM VIEW_EMPLOYEE WHERE MIDJOB_TITLE_ID ='".$appr_midjob."' ";
                    $appr_plant=($data_approval['APPROVAL_'.$i.'_PLANT_ID']) ? " AND SAP_PLANT_ID='".$data_approval['APPROVAL_'.$i.'_PLANT_ID']."'" : NULL;
                    $appr_territory=($data_approval['APPROVAL_'.$i.'_TERRITORY_ID']) ? " AND TERRITORY_ID='".$data_approval['APPROVAL_'.$i.'_TERRITORY_ID']."'" : NULL;
                    $select .= $appr_plant.$appr_territory;

                    $emp_appr=DB::connection('dbintranet')
                    ->select($select);
                    foreach($emp_appr AS $notif_appr){
                        $notif_employee_id=$notif_appr->EMPLOYEE_ID;
                        $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type); // insert notif
                    }
                }
            }
    }


}




