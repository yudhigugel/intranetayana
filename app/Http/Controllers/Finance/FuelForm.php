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

class FuelForm extends Controller{
  private $form_number;

  public function __construct()
  {
      $this->form_number = "FORM/ENG/FUEL/";
  }

  public function request(Request $request){

    $is_production = config('intranet.is_production');
    if($is_production)
        $rfc = new SapConnection(config('intranet.rfc_prod'));
    else
        $rfc = new SapConnection(config('intranet.rfc'));

    $options = [
        'rtrim'=>true,
    ];

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
        $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
        $midjob_id =Session::get('assignment')[0]->MIDJOB_TITLE_ID;
    }

    $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
    $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
    $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

    $allow_add_request = true;

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
        'form_code'=>$this->form_number,
        'allow_add_request'=>$allow_add_request
        );


    return view('pages.finance.fuel.request', ['data'=>$data]);
  }
}




