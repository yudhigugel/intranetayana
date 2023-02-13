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
use App\Models\HumanResource\CompanyModel;
Use Cookie;
use DataTables;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;

class FormController extends Controller{

    public function cash_advance(Request $request){

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
            'form_code'=>$this->form_number,
            'list_plant'=>$list_plant,
            'list_cost_center'=>$list_cost_center
        );
        return view('pages.finance.form.cash-advance', ['data' => $data]);
    }

  public function refund(Request $request){

        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;


        if(empty(Session::get('assignment')[0])){
          $company_code="SYSADMIN";
          $plant="SYSADMIN";
          $division="SYSADMIN";
          $department="SYSADMIN";
        }else{
          $division=Session::get('assignment')[0]->DIVISION_NAME;
          $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
          $company_code=Session::get('assignment')[0]->COMPANY_CODE;
          $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
        }
        $data=array(
          'company_code'=>$company_code,
          'plant'=>$plant,
          'employee_id'=>$employee_id,
          'employee_name'=>$employee_name,
          'division'=>$division,
          'department'=>$department
        );
        return view('pages.finance.form.refund', ['data' => $data]);
  }

  public function req_entertainment(Request $request){

    $employee_id=Session::get('user_id');
    $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

    // dd(Session::get('assignment'));

    if(empty(Session::get('assignment')[0])){
      $company_code="SYSADMIN";
      $plant="SYSADMIN";
      $division="SYSADMIN";
      $department="SYSADMIN";
    }else{
      $division=Session::get('assignment')[0]->DIVISION_NAME;
      $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
      $company_code=Session::get('assignment')[0]->COMPANY_CODE;
      $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
      $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
    }


    $data=array(
      'company_code'=>$company_code,
      'plant'=>$plant,
      'plant_name'=>$plant_name,
      'employee_id'=>$employee_id,
      'employee_name'=>$employee_name,
      'division'=>$division,
      'department'=>$department
    );
    return view('pages.finance.form.entertainment-request', ['data' => $data]);
}


}




