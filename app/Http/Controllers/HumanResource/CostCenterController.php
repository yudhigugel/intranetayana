<?php

namespace App\Http\Controllers\HumanResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\HumanResource\CostCenterModel;
Use Cookie;
use DataTables;

class CostCenterController extends Controller{
  public function index(Request $request){   
        $data=CostCenterModel::all();
        return view('pages.human-resource.master-data.cost-center', ['data' => $data]);
  }

  public function edit($id){   
        $data=CostCenterModel::find($id);
        return view('pages.human-resource.master-data.cost-center_edit', ['data' => $data]);
  }

  public function getData(Request $request){ 
    try{  
        
        $data=CostCenterModel::leftJoin('INT_TERRITORY','INT_SAP_COST_CENTER.TERRITORY_ID','=','INT_TERRITORY.TERRITORY_ID')
                              ->leftJoin('INT_BUSINESS_PLANT', 'INT_TERRITORY.SAP_PLANT_ID', '=', 'INT_BUSINESS_PLANT.SAP_PLANT_ID')
                              ->leftJoin('INT_EMPLOYEE_ASSIGNMENT', 'INT_SAP_COST_CENTER.SAP_COST_CENTER_ID', '=', 'INT_EMPLOYEE_ASSIGNMENT.SAP_COST_CENTER_ID')->select('INT_SAP_COST_CENTER.SAP_COST_CENTER_ID', 'INT_SAP_COST_CENTER.SAP_COST_CENTER_NAME', 'INT_SAP_COST_CENTER.SAP_COST_CENTER_DESCRIPTION', 'INT_SAP_COST_CENTER.TERRITORY_ID','INT_TERRITORY.TERRITORY_CODE', 'INT_BUSINESS_PLANT.SAP_PLANT_ID as PLANT_ID', 'INT_BUSINESS_PLANT.COMPANY_CODE as COMPANY', 'INT_BUSINESS_PLANT.SAP_PLANT_NAME as PLANT_NAME', DB::raw('count(INT_EMPLOYEE_ASSIGNMENT.SAP_COST_CENTER_ID) as TOTAL_EMPLOYEE'))
                              ->groupBy(['INT_SAP_COST_CENTER.SAP_COST_CENTER_ID', 'INT_SAP_COST_CENTER.TERRITORY_ID', 'INT_SAP_COST_CENTER.SAP_COST_CENTER_NAME', 'INT_SAP_COST_CENTER.SAP_COST_CENTER_DESCRIPTION', 'INT_TERRITORY.TERRITORY_CODE', 'INT_BUSINESS_PLANT.SAP_PLANT_ID', 'INT_BUSINESS_PLANT.COMPANY_CODE', 'INT_BUSINESS_PLANT.SAP_PLANT_NAME'])
                              ->orderBy('TOTAL_EMPLOYEE', 'desc')
                              ->get();

        $result=array();
        foreach($data as $key=>$value){
          $result[]=array(
            'COMPANY'=>$value->COMPANY,
            'PLANT'=>$value->PLANT_ID,
            'TERRITORY_ID'=>$value->TERRITORY_ID,
            'COST_CENTER_ID'=>$value->SAP_COST_CENTER_ID,
            'COST_CENTER_NAME'=>$value->SAP_COST_CENTER_NAME,
            'DESCRIPTION'=>$value->SAP_COST_CENTER_DESCRIPTION,
            'TOTAL_EMPLOYEE'=>(int)$value->TOTAL_EMPLOYEE
          );
          
        }
        return DataTables::of($result)
        ->editColumn('TOTAL_EMPLOYEE', function ($id) {
              return '<a href="/human-resource/employee-list/employee/filter?COST_CENTER_ID=' . $id['COST_CENTER_ID'] . '" >'.$id['TOTAL_EMPLOYEE'].'</a>'; 
              })
        ->rawColumns(['TOTAL_EMPLOYEE'])
        ->make(true);
    }
    catch(\Exception $e){
        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
    }
  }

  public function act_edit(Request $request){
    try{  
      $CostCenterModel = new CostCenterModel();
      
      if ($request->has(['COMPANY_NAME', 'COMPANY_DESCRIPTION'])) {
        $id=$request->COMPANY_ID;
        $data=array(
          'COMPANY_NAME'=>$request->COMPANY_NAME,
        );
        $result=$CostCenterModel->updateData($data,$id);
        if($result){
          //set expiration date
          $success=true;
          $msg="Data has been updated";
          $code='200';
        }else{
          $success=false;
          $msg="Something's not right, please try again later";
          $code='400'; 
        }
      }else{
        $success=false;
        $msg="Please complete your form";
        $code='400'; 
      }
       
      return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));

    }
    catch(\Exception $e){
        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
    }

  }

  public function act_delete($id){
   try{  
      $CostCenterModel = new CostCenterModel();
      
      
      $data=array(
        'STATUS'=>0
      );
      $result=$CostCenterModel->updateData($data,$id);
      if($result){
        //set expiration date
        $success=true;
        $msg="Data has been deleted";
        $code='200';
      }else{
        $success=false;
        $msg="Something's not right, please try again later";
        $code='400'; 
      }
      
       
      return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));

    }
    catch(\Exception $e){
        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
    }
  }


}




        