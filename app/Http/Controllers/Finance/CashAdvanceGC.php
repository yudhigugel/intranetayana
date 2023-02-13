<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Finance\CashAdvance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Models\HumanResource\CompanyModel;
use App\Models\Zoho\ZohoFormModel;
Use Cookie;
use DataTables;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Database\QueryException;
use stdClass;

class CashAdvanceGC extends Controller{

    public function __construct()
    {
        $this->form_number = "CA";
        $this->form_number_extended = "CA-EXT";
        $this->form_view="VIEW_FORM_REQUEST_CASH_ADVANCE";
        $this->form_view_extended="VIEW_FORM_REQUEST_CASH_ADVANCE_EXT";
        $this->reimbursement_view = "VIEW_FORM_REQUEST_CASH_ADVANCE_REIMBURSEMENT";
        $this->approval_view="VIEW_APPROVAL_FORM_REQUEST_CASH_ADVANCE";
        $this->approval_view_extended="VIEW_APPROVAL_FORM_REQUEST_CASH_ADVANCE_EXT";
        $this->approval_view_link="/finance/cash-advance/approval";
        $this->link_request = "/finance/cash-advance/request";
    }

    public function all(Request $request){

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

          $employee_name=Session::get('user_data')->EMPLOYEE_NAME;
          $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
          $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
          $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;


        $data=[
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
        ];

        return view('pages.finance.cash-advance-gc.all', ['data' => $data]);
    }

    public function all_getData(Request $request){
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

            // $data = DB::connection('dbintranet')
            //         ->table(DB::raw($this->form_view))
            //         ->whereraw(DB::raw($where))->get();

            $data= DB::connection('dbintranet')
            ->select("SELECT * FROM ".$this->form_view." V INNER JOIN INT_FORM_CASH_ADVANCE_PROPERTIES PROPS ON V.UID = PROPS.FORM_ID WHERE STATUS_REIMBURSEMENT IS NULL AND STATUS_APPROVAL='FINISHED'");

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
                    'JSON_ENCODE'=>$data_json,
                    'REASON'=>$value->REASON,
                    'STATUS_BACKUP'=>$value->STATUS_BACKUP,
                    'STATUS_CATEGORY'=>$value->STATUS_CATEGORY,
                    'STATUS_SETTLEMENT'=>$value->STATUS_SETTLEMENT,
                    'STATUS_REIMBURSEMENT'=>$value->STATUS_REIMBURSEMENT
                );
            }
            // dd($result);
            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function add_reimbursement(Request $request){

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

        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;
        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $category= (!empty($request->input('category')))?  $request->input('category') : 'PO' ;


      $data=[
          'company_code'=>$company_code,
          'plant'=>$plant,
          'plant_name'=>$plant_name,
          'employee_id'=>$employee_id,
          'employee_name'=>$employee_name,
          'division'=>$division,
          'department'=>$department,
          'cost_center_id'=>$cost_center_id,
          'category'=>$category,
          'territory_id'=>$territory_id,
          'territory_name'=>$territory_name,
          'job_title'=>$job_title,
          'request_date_from'=>$request_date_from,
          'request_date_to'=>$request_date_to,
      ];

      return view('pages.finance.cash-advance-gc.add-reimbursement', ['data' => $data]);
    }
    public function add_reimbursement_getData(Request $request){
        try{
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $category=strtoupper($request->input('category'));


            $where='';

            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                $where = $where." AND INSERT_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
            }

            if ($category != null or $category !=""){
                $where = $where." AND STATUS_CATEGORY = '".$category."'";
            }

            $data= DB::connection('dbintranet')
            ->select("SELECT * FROM ".$this->form_view." V INNER JOIN INT_FORM_CASH_ADVANCE_PROPERTIES PROPS ON V.UID = PROPS.FORM_ID WHERE STATUS_REIMBURSEMENT IS NULL AND STATUS_ADJUSTMENT_FINAL='YES' $where ");



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
                    'JSON_ENCODE'=>$data_json,
                    'REASON'=>$value->REASON,
                    'STATUS_BACKUP'=>$value->STATUS_BACKUP,
                    'STATUS_CATEGORY'=>$value->STATUS_CATEGORY,
                    'STATUS_SETTLEMENT'=>$value->STATUS_SETTLEMENT,
                    'STATUS_REIMBURSEMENT'=>$value->STATUS_REIMBURSEMENT
                );
            }
            // dd($result);
            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function list_reimbursement(Request $request){

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

        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;
        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $category= (!empty($request->input('category')))?  $request->input('category') : 'PO' ;


        $data=[
            'company_code'=>$company_code,
            'plant'=>$plant,
            'plant_name'=>$plant_name,
            'employee_id'=>$employee_id,
            'employee_name'=>$employee_name,
            'division'=>$division,
            'department'=>$department,
            'cost_center_id'=>$cost_center_id,
            'category'=>$category,
            'territory_id'=>$territory_id,
            'territory_name'=>$territory_name,
            'job_title'=>$job_title,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
        ];

        return view('pages.finance.cash-advance-gc.list-reimbursement', ['data' => $data]);
    }

    public function list_reimbursement_getData(Request $request){
        try{
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $where='';

            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                $where = $where." AND REIMBURSE_INSERT_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
            }


            $dataset = DB::connection('dbintranet')
            ->select("SELECT * FROM ".$this->reimbursement_view." WHERE (STATUS IS NULL OR STATUS='PAID') $where ORDER BY REIMBURSEMENT_ID DESC");
            $data = $this->getDatasetReimbursement($dataset);


            return DataTables::of($data)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function save_adjustment_first(Request $request){
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc_dev'));
        }
        $options = [
            'rtrim'=>true,
        ];
        try{

            $type_form=$this->form_number_extended;
            $ca_real_number = $request->input('ca_number');
            $year = date('Y');
            $uid= $ca_real_number.'-EXT'; // ini nomor CA extended untuk approval tambahan
            $data=$request->post();
            unset($data['_token']);

            // cari data di cash advance properties
            $ca_properties= DB::connection('dbintranet')->table('INT_FORM_CASH_ADVANCE_PROPERTIES')->where('FORM_ID',$ca_real_number)->get();

            // bypass jika request ini dilakukan setelah proses approval extended
            if(!empty($ca_properties[0]->APPROVAL_EXTENDED_NUMBER)){
                $query_finished=DB::connection('dbintranet')->table('INT_FIN_APPR_LIST')->where('FORM_ID',$uid)->select('STATUS_APPROVAL')->get();
                $is_finish_approve_ext = (isset($query_finished[0]) && $query_finished[0]->STATUS_APPROVAL == "FINISHED") ? true : false;
            }else{
                $is_finish_approve_ext =false;
            }
            // =============

            // logic apakah save first adjustment ini merupakan yang pertama kali save, atau setelah adanya approval extended
            if($is_finish_approve_ext){
                //hanya mengupdate status adjustment first
                $update=DB::connection('dbintranet')
                ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
                ->where('FORM_ID',$ca_real_number)
                ->update(
                    [
                        "STATUS_ADJUSTMENT_FIRST" => "YES",
                        "STATUS_BACKUP" => 'YES'
                    ]
                );
            }else{
                //di bawah ini koding untuk proses save adjustment yang pertama kali
                $company_code=$data['company_code'];
                $cash_journal=isset($ca_properties[0])? $ca_properties[0]->CURRENT_CA_CASH_JOURNAL : NULL;
                $posting_number=isset($ca_properties[0])? $ca_properties[0]->CURRENT_CA_NUMBER : NULL;

                // data adjustment yang akan diolah
                $final_adjustment = $data['final_adjustment'];
                $final_over = $data['final_over'];
                $final_under = $data['final_under'];
                // =============

                // cari dulu untuk cash advance ini maksimal under nya berapa
                $form_plant = $data['Requestor_Plant_ID'];
                $config_plant= DB::connection('dbintranet')->table('CONFIG_FORM_CASH_ADVANCE_PER_PLANT')->where('PLANT_ID',$form_plant)->get();
                $approval_treshold_amount = isset($config_plant[0])? $config_plant[0]->LIMIT_AMOUNT_TO_EXTEND_APPROVAL : 1000000;
                // =============

                $data_json=json_encode($data);

                // jika melebihi dari amount awal maka akan ada approval sampai dept head
                // jika melebihi dari approval treshold maka akan ada approval sampai ke GM
                if($final_under>0){
                    // maka akan dibuatkan record baru untuk proses approval kedua
                    $employee_id=$data['Requestor_Employee_ID'];
                    $type="Request Cash Advance Extended";

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
                        ->where('FORM_ID',$ca_real_number)
                        ->update(
                            [
                                "APPROVAL_EXTENDED_NUMBER" => $uid
                            ]
                        );

                        $level_approval = 1;
                        $notif_link=$this->approval_view_link;
                        $notif_desc="Please approve Cash Advance Extended Form : ".$uid."";
                        $notif_type="info";

                        $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);
                    }
                }else{
                    //jika tidak melebihi approval, maka status adjustment akan selesai
                    $update=DB::connection('dbintranet')
                        ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
                        ->where('FORM_ID',$ca_real_number)
                        ->update(
                            [
                                "STATUS_ADJUSTMENT_FIRST" => "YES",
                                "STATUS_BACKUP" => 'YES'
                            ]
                        );
                }

                //proses update data existing di form
                $update=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_RAW_DATA')
                ->where('UID',$ca_real_number)
                ->update(
                    [
                        "JSON_ENCODE" => $data_json,
                    ]
                );
            }



            $success=true;
            $code = 200;
            $msg = 'Adjustment Has Been Saved';
        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));
    }


    public function save_adjustment_final(Request $request){
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc_dev'));
        }
        $options = [
            'rtrim'=>true,
        ];
        try{
            $data=$request->post();
            unset($data['_token']);
            $uid = $data['ca_number'];
            $data['uid']=$uid;

            $plant=$data['Requestor_Plant_ID'];
            $company_code=$data['company_code'];
            $cash_journal=$data['current_ca_cash_journal'];
            $posting_number=$data['current_ca_number'];
            $year=date('Y',strtotime($data['Request_Date']));

            $cash_journal_updated = $data['cajo_number'];
            $business_trans_updated = $data['business_trans'];
            $ca_category_updated = $data['ca_category'];
            $currency =  isset($data['currency'][0]) ? $data['currency'][0] : '';
            $grand_total_adjustment = $data['final_adjustment'];
            $purpose = $data['purpose'];

            //pertama kali harus memasukkan data yang baru dulu ke FBCJ
            $array_data=[
                'ca_number' => $uid,
                'company_code' => $company_code,
                'plant' => $plant,
                'cajo_number' => $cash_journal_updated,
                'business_trans' => $business_trans_updated,
                'currency' => $currency,
                'purpose' => $purpose,
                'grand_total' => $grand_total_adjustment,
                'ca_category' => $ca_category_updated
            ];

            $sendData=App::call('App\Http\Controllers\Finance\CashAdvance@insert_ca_sap', ['array_data'=> $array_data]); //proses masukin ke SAP disini
            $responseSAP=$sendData->getData();
            // jika sukses insert ke SAP
            if($responseSAP->status=="success"){

                //setelah sukses maka delete existing parked CA di FBCJ
                $array_delete=[
                    'company_code'=>$company_code,
                    'cash_journal'=>$cash_journal,
                    'posting_number'=>$posting_number,
                    'year'=>$year

                ];

                $delete=$this->delete_parked_ca($array_delete)->getData(); // proses delete disini

                // menghilangkan key2 yang tidak perlu (sudah dihandle di CASH_ADVANCE_PROPERTIES)
                unset($data['current_ca_number']);
                unset($data['current_ca_business_transaction']);
                unset($data['cajo_number']);
                unset($data['business_trans']);
                unset($data['ca_category']);
                unset($data['current_ca_cash_journal']);
                // =============


                $data_json=json_encode($data);

                //proses update data existing di INT_FIN_APPR_RAW_DATA
                $update=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_RAW_DATA')
                ->where('UID',$uid)
                ->update(
                    [
                        "JSON_ENCODE" => $data_json,
                    ]
                );

                //proses update status di CASH ADVANCE PROPERTIES
                $update2=DB::connection('dbintranet')
                ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
                ->where('FORM_ID',$uid)
                ->update(
                    [
                        "STATUS_ADJUSTMENT_FINAL" => "YES",
                        "STATUS_SETTLEMENT" => "YES",
                        "FINAL_ADJUSTMENT_DATE"=>date('Y-m-d H:i:s')
                    ]
                );

                $success=true;
                $code = 200;
                $msg = 'Final Adjustment Has Been Saved';

            }else{
                $success=false;
                $code = 400;
                $msg = $responseSAP->message;
            }

        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));
    }

    public function delete_parked_ca($data){
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc_dev'));
        }
        $options = [
            'rtrim'=>true,
        ];

        $company_code = $data['company_code'];
        $cash_journal = $data['cash_journal'];
        $posting_number = $data['posting_number'];
        $year = $data['year'];
        $param = array(
            'I_COMP_CODE'=>$company_code,
            'I_CAJO_NUMBER'=>$cash_journal,
            'I_POSTING_NUMBER'=>$posting_number,
            'I_FISC_YEAR'=>$year
        );
        try{
            $function_type = $rfc->getFunction('ZFM_FI_FCJ_DELETE_DOCUMENT');
            $delete= $function_type->invoke($param, $options);
            if(isset($delete['IT_RETURN'][0]) && $delete['IT_RETURN'][0]['TYPE']=='S'){
                $return_code=200;
                $return_status="success";
                $return_message="Parked Cash Advance is successfuly deleted";
                $return_data=[];
            }else{
                $return_code=200;
                $return_status="error";
                $return_message="Parked Cash Advance is failed to delete";
                $return_data=$delete;
            }
        }catch(SapException $e){
            $return_code=200;
            $return_status="error";
            $return_message="Parked Cash Advance is failed to delete";
            $return_data=$e->getMessage();
        }

        return response()->json(['status_code'=>$return_code, 'status'=>$return_status, 'message'=>$return_message, 'data' => $return_data]);

    }

    public function getHistoryApprovalExtended($form_id){
        $rest = new ZohoFormModel();
        $result = $rest->getHistoryApprovalCAExtended($form_id,$this->form_view_extended);
        return $result['data'];
    }

    public function modal_category(Request $request)
    {

        $uid=$request->input('id');
        $data_form=NULL;
        $data_json=NULL;
        $data_file=NULL;
        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
                    ->table($this->form_view)
                    ->where('UID',$uid)
                    ->get();
            $data_json = json_decode($data_form[0]->JSON_ENCODE);
        }
        $data=array(
            'data_form'=>$data_form[0],
            'data_json'=>$data_json
        );

        return View::make('pages.finance.cash-advance-gc.modal-category')->with('data', $data)->render();
    }

    public function modal_backup(Request $request)
    {

        $uid=$request->input('id');
        $action=(!empty($request->input('action')))? $request->input('action') : 'view'; // flag action, gunanya adalah ketika di modal detail supaya bisa kasi validasi apakah harus kasi tombol approve & reject di modal atau tidak (tapi berbeda dengan modal approve detail, ini hanya untuk approval tanpa inputan apapun)
        $data_form=NULL;
        $data_json=NULL;
        $data_file=NULL;
        $form_extended=NULL;
        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
                    ->table($this->form_view)
                    ->where('UID',$uid)
                    ->get();
            $data_json = json_decode($data_form[0]->JSON_ENCODE);
        }


        $plant_requestor = isset($data_form[0])? $data_form[0]->PLANT_ID : '';
        $config_form = DB::connection('dbintranet')
                        ->select("SELECT * FROM CONFIG_FORM_CASH_ADVANCE_PER_PLANT WHERE PLANT_ID ='$plant_requestor'");

        $limit_treshold_adjustment = isset($config_form[0])? $config_form[0]->LIMIT_AMOUNT_TO_EXTEND_APPROVAL : NULL;

        //cari apakah sudah pernah park sebelumnya di SAP
        $park_fbcj = DB::connection('dbintranet')->table('INT_FORM_CASH_ADVANCE_PROPERTIES')->where('FORM_ID',$uid)->get();

        //cari history approval untuk yang extended
        $approval_extended_history=NULL;
        if(isset($park_fbcj[0]->APPROVAL_EXTENDED_NUMBER) && !empty($park_fbcj[0]->APPROVAL_EXTENDED_NUMBER)){
            $form_extended=$park_fbcj[0]->APPROVAL_EXTENDED_NUMBER;
            $approval_extended_history = $this->getHistoryApprovalExtended($form_extended, $this->form_view_extended);
        }

        //flag untuk nge mute adjustment jika ada approval yang sedang berjalan, supaya tidak bisa dirubah angkanya ketika proses approval
        $mute_adjustment=($approval_extended_history) ? true : false;

        //flag apakah form yang extended sudah finish atau belum
        $query_finished=DB::connection('dbintranet')->table('INT_FIN_APPR_LIST')->where('FORM_ID',$form_extended)->select('STATUS_APPROVAL')->get();
        $is_finish_approve_ext = (isset($query_finished[0]) && $query_finished[0]->STATUS_APPROVAL == "FINISHED") ? true : false;


        // dd($data_form[0], $data_json);
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

        $data=array(
            'data_form'=>$data_form[0],
            'data_json'=>$data_json,
            'action'=>$action,
            'company_code'=>$company_code,
            'limit_treshold_adjustment'=>$limit_treshold_adjustment,
            'park_fbcj'=>$park_fbcj,
            'approval_extended_history'=>$approval_extended_history,
            'mute_adjustment'=>$mute_adjustment,
            'is_finish_approve_ext'=>$is_finish_approve_ext
        );

        return View::make('pages.finance.cash-advance-gc.modal-backup')->with('data', $data)->render();
    }

    public function modal_settlement(Request $request)
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
                    ->get();
            $data_json = json_decode($data_form[0]->JSON_ENCODE);
        }


        $plant_requestor = isset($data_form[0])? $data_form[0]->PLANT_ID : '';
        $config_form = DB::connection('dbintranet')
                        ->select("SELECT * FROM CONFIG_FORM_CASH_ADVANCE_PER_PLANT WHERE PLANT_ID ='$plant_requestor'");

        $limit_treshold_adjustment = isset($config_form[0])? $config_form[0]->LIMIT_AMOUNT_TO_EXTEND_APPROVAL : NULL;

        //cari apakah sudah pernah park sebelumnya di SAP
        $park_fbcj = DB::connection('dbintranet')->table('INT_FORM_CASH_ADVANCE_PROPERTIES')->where('FORM_ID',$uid)->get();



        // dd($data_form[0], $data_json);
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

        $data=array(
            'data_form'=>$data_form[0],
            'data_json'=>$data_json,
            'action'=>$action,
            'company_code'=>$company_code,
            'limit_treshold_adjustment'=>$limit_treshold_adjustment,
            'park_fbcj'=>$park_fbcj
        );

        return View::make('pages.finance.cash-advance-gc.modal-settlement')->with('data', $data)->render();
    }

    public function modal_detail(Request $request)
    {

        $uid=$request->input('id');
        $data_form=NULL;
        $data_json=NULL;
        $data_file=NULL;
        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
                    ->table($this->form_view)
                    ->where('UID',$uid)
                    ->get();
            $data_json = json_decode($data_form[0]->JSON_ENCODE);
        }

        // cari company code dari request
        $plant = $data_form[0]->PLANT_ID;
        $company_code = DB::connection('dbintranet')->select("SELECT COMPANY_CODE FROM INT_BUSINESS_PLANT WHERE SAP_PLANT_ID='$plant'");
        $company_code = isset($company_code[0]) ? $company_code[0]->COMPANY_CODE : NULL;
        // ---

        //cari apakah sudah pernah park sebelumnya di SAP
        $park_fbcj = DB::connection('dbintranet')->table('INT_FORM_CASH_ADVANCE_PROPERTIES')->where('FORM_ID',$uid)->get();

        //flag apakah form hanya readonly
        $readonly=!empty($request->input('readonly')) ? true : false;

        $data=array(
            'data_form'=>$data_form[0],
            'data_json'=>$data_json,
            'company_code'=>$company_code,
            'park_fbcj'=>$park_fbcj,
            'readonly' => $readonly
        );

        return View::make('pages.finance.cash-advance-gc.modal-detail')->with('data', $data)->render();
    }

    public function modal_detail_list_reimbursement(Request $request)
    {

        $uid=$request->input('id');
        $data_form=NULL;
        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
                    ->table($this->reimbursement_view)
                    ->where('REIMBURSEMENT_ID',$uid)
                    ->get();
            $data_form = $this->getDatasetReimbursement($data_form);

            $data_properties=DB::connection('dbintranet')->table('INT_FORM_CASH_ADVANCE_PROPERTIES')->where('REIMBURSEMENT_NUMBER',$uid)->first();

            //cek apakah semua CA nya di reimbursement ini sudah posted atau belum
            $cek = DB::connection('dbintranet')->select("SELECT STATUS_POSTING FROM INT_FORM_CASH_ADVANCE_PROPERTIES WHERE REIMBURSEMENT_NUMBER ='".$uid."' AND STATUS_POSTING IS NULL");

            $boleh_pay = (count($cek)>0)? false : true;



            $data=array(
                'data_form'=>$data_form,
                'data_properties'=>$data_properties,
                'boleh_pay'=> $boleh_pay
            );

            return View::make('pages.finance.cash-advance-gc.modal-detail-list-reimbursement')->with('data', $data)->render();
        }
    }

    public function modal_detail_ca_reimbursement(Request $request)
    {

        $uid=$request->input('id');
        $data_form=NULL;
        $data_json=NULL;
        $data_file=NULL;
        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
                    ->table($this->form_view)
                    ->where('UID',$uid)
                    ->get();
            $data_json = json_decode($data_form[0]->JSON_ENCODE);
        }

        // cari company code dari request
        $plant = $data_form[0]->PLANT_ID;
        $company_code = DB::connection('dbintranet')->select("SELECT COMPANY_CODE FROM INT_BUSINESS_PLANT WHERE SAP_PLANT_ID='$plant'");
        $company_code = isset($company_code[0]) ? $company_code[0]->COMPANY_CODE : NULL;
        // ---

        //cari apakah sudah pernah park sebelumnya di SAP
        $park_fbcj = DB::connection('dbintranet')->table('INT_FORM_CASH_ADVANCE_PROPERTIES')->where('FORM_ID',$uid)->get();

        //flag apakah form hanya readonly
        $readonly=!empty($request->input('readonly')) ? true : false;

        //cek posted status

        $data=array(
            'data_form'=>$data_form[0],
            'data_json'=>$data_json,
            'company_code'=>$company_code,
            'park_fbcj'=>$park_fbcj,
            'readonly' => $readonly
        );

        return View::make('pages.finance.cash-advance-gc.modal-detail-ca-reimbursement')->with('data', $data)->render();
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

    public function add_reimbursement_batch(Request $request)
    {
        $FormID=imuneString($request->input('form_id'));
        $data = explode(";" , $FormID);
        $EmployeID=imuneString($request->input('employe_id'));

        $totalData=0;
        $success=0;
        $failed=0;

        //buat data reimbursement dulu
        $last_seq=DB::connection('dbintranet')->select("SELECT TOP 1 CASE WHEN SEQ_ID IS NULL THEN NULL ELSE SEQ_ID END AS LAST_SEQ FROM INT_FORM_CASH_ADVANCE_REIMBURSEMENT ORDER BY SEQ_ID DESC ");
        if(!empty($last_seq[0]->LAST_SEQ)){
            $explode_uid=explode('-',$last_seq[0]->LAST_SEQ);
            $nomor_akhir=(int)end($explode_uid);
        }else{
            $nomor_akhir=0;
        }
        $new_seq = $nomor_akhir + 1;
        $new_seq = sprintf("%010d", $new_seq);
        $uid = $new_seq;

        $insert=DB::connection('dbintranet')
        ->table('INT_FORM_CASH_ADVANCE_REIMBURSEMENT')
        ->insert([
            'REIMBURSEMENT_ID'=>$uid,
            'INSERT_DATE'=>date('Y-m-d H:i:s')
        ]);

        if($insert){
            foreach ($data as $key => $dataId) {

                $result = $this->add_reimbursement_proses($dataId,$uid);

                if ($result['code']==200){
                    $success++;
                }else{
                    $failed++;
                }

                $totalData++;
            }
        }


        $hasil["Total_Data"]=$totalData;
        $hasil["Total_Success"]=$success;
        $hasil["Total_Failed"]=$failed;

        return $hasil;
    }

    public function add_reimbursement_proses($form_id,$reimburse_id)
    {

        try {
            $connection = DB::connection('dbintranet');

            $connection->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
            ->where('FORM_ID',$form_id)
            ->update([
                'STATUS_REIMBURSEMENT'=>'READY',
                'REIMBURSEMENT_NUMBER'=>$reimburse_id
            ]);

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

    public function getDatasetReimbursement($row){
        $data=array();
        foreach($row as $key=>$value){
            $ca_id=$value->FORM_ID;
            $data_json=DB::connection('dbintranet')
                ->select("SELECT JSON_ENCODE AS DATA_JSON FROM INT_FIN_APPR_RAW_DATA WHERE UID ='".$ca_id."' ");
            $amount_parse=json_decode($data_json[0]->DATA_JSON)->amount_parse;
            $amount=json_decode($data_json[0]->DATA_JSON)->amount;
            $description=json_decode($data_json[0]->DATA_JSON)->description;
            $currency=json_decode($data_json[0]->DATA_JSON)->currency;

            $data_properties = DB::connection('dbintranet')->select("SELECT STATUS_POSTING, FINAL_ADJUSTMENT_DATE from INT_FORM_CASH_ADVANCE_PROPERTIES WHERE FORM_ID ='".$ca_id."'");
            $status_posting = $data_properties[0]->STATUS_POSTING;
            $final_settlement_date = $data_properties[0]->FINAL_ADJUSTMENT_DATE;

            for($i=0;$i<count($amount);$i++){
                $data_push = (array) $value;
                $data_push['CA_ITEM_AMOUNT'] = $amount[$i];
                $data_push['CA_ITEM_AMOUNT_PARSE'] = $amount_parse[$i];
                $data_push['CA_ITEM_DESC'] = $description[$i];
                $data_push['CA_CURRENCY'] = $currency[$i];
                $data_push['CA_STATUS_POSTING'] = $status_posting;
                $data_push['CA_FINAL_SETTLEMENT_DATE'] = $final_settlement_date;
                $data[]=$data_push;

            }
        }

        return $data;
    }

    public function update_reimbursement(Request $request){
        $id=$request->input('id');
        $desc=$request->input('desc');
        $os=$request->input('os');

        if(!empty($id)){
            try{
                DB::connection('dbintranet')
                ->table('INT_FORM_CASH_ADVANCE_REIMBURSEMENT')
                ->where('REIMBURSEMENT_ID',$id)
                ->update([
                    'DESCRIPTION'=>$desc,
                    'OS'=>$os
                ]);

                $result['status']='success';
                $result['message']='Update Reimbursement Success';
                $result['data']=$id;

            }catch(QueryException $e){
                $result['status']='error';
                $result['message']='Update Reimbursement Failed';
                $result['data']=$e->getMessage();
            }
        }else{
            $result['status']='error';
            $result['message']='Pay Reimbursement Failed';
            $result['data']='';
        }

        return $result;
    }

    public function pay_reimbursement(Request $request){
        $id=$request->input('id');
        if(!empty($id)){
            try{
                DB::connection('dbintranet')->transaction(function ($data) use ($id) {
                    DB::connection('dbintranet')
                    ->table('INT_FORM_CASH_ADVANCE_REIMBURSEMENT')
                    ->where('REIMBURSEMENT_ID',$id)
                    ->update([
                        'STATUS'=>'PAID',
                        'PAID_DATE'=>date('Y-m-d H:i:s'),
                        'LAST_UPDATE'=>date('Y-m-d H:i:s')
                    ]);

                    $list_ca=DB::connection('dbintranet')->select("SELECT * FROM INT_FORM_CASH_ADVANCE_PROPERTIES WHERE REIMBURSEMENT_NUMBER = '$id'");

                    foreach($list_ca as $ca){
                        DB::connection('dbintranet')
                        ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
                        ->where('REIMBURSEMENT_NUMBER',$id)
                        ->update([
                            'STATUS_REIMBURSEMENT'=>'PAID'
                        ]);
                    }
                });
                $result['status']='success';
                $result['message']='Pay Reimbursement Success';
                $result['data']=$id;
            }catch(\Exception $e){
                $result['status']='error';
                $result['message']='Pay Reimbursement Failed';
                $result['data']=$e->getMessage();
            }
        }else{
            $result['status']='error';
            $result['message']='Pay Reimbursement Failed';
            $result['data']='';
        }
        return $result;
    }

    public function cancel_reimbursement(Request $request){
        $id=$request->input('id');
        if(!empty($id)){
            try{
                DB::connection('dbintranet')->transaction(function ($data) use ($id) {
                    DB::connection('dbintranet')
                    ->table('INT_FORM_CASH_ADVANCE_REIMBURSEMENT')
                    ->where('REIMBURSEMENT_ID',$id)
                    ->update([
                        'STATUS'=>'CANCELED',
                        'LAST_UPDATE'=>date('Y-m-d H:i:s')
                    ]);

                    $list_ca=DB::connection('dbintranet')->select("SELECT * FROM INT_FORM_CASH_ADVANCE_PROPERTIES WHERE REIMBURSEMENT_NUMBER = '$id'");

                    foreach($list_ca as $ca){
                        DB::connection('dbintranet')
                        ->table('INT_FORM_CASH_ADVANCE_PROPERTIES')
                        ->where('REIMBURSEMENT_NUMBER',$id)
                        ->update([
                            'STATUS_REIMBURSEMENT'=>NULL,
                            'REIMBURSEMENT_NUMBER'=>NULL
                        ]);
                    }
                });
                $result['status']='success';
                $result['message']='Pay Reimbursement Success';
                $result['data']=$id;
            }catch(\Exception $e){
                $result['status']='error';
                $result['message']='Pay Reimbursement Failed';
                $result['data']=$e->getMessage();
            }
        }else{
            $result['status']='error';
            $result['message']='Pay Reimbursement Failed';
            $result['data']='';
        }
        return $result;
    }



}
