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


class ReportController extends Controller{
  public function cash_advance(Request $request){
    if(empty(Session::get('assignment')[0])){
        $company_code="SYSADMIN";
        $plant="SYSADMIN";
    }else{
        $company_code=Session::get('assignment')[0]->COMPANY_CODE;
        $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
    }
        $data=array(
          'company_code'=>$company_code,
          'plant'=>$plant,
          'employee_id'=>Session::get('user_id')
        );
        return view('pages.finance.report.cash-advance', ['data' => $data]);
  }

  public function refund(Request $request){
    if(empty(Session::get('assignment')[0])){
        $company_code="SYSADMIN";
        $plant="SYSADMIN";
    }else{
        $company_code=Session::get('assignment')[0]->COMPANY_CODE;
        $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
    }
        $data=array(
          'company_code'=>$company_code,
          'plant'=>$plant,
          'employee_id'=>Session::get('user_id')
        );
        return view('pages.finance.report.refund', ['data' => $data]);
  }


  public function fb_flash_cost_report(Request $request){
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

    return view('pages.sap.fb-flash-cost.index', ['data' => $data]);
  }


  


}




