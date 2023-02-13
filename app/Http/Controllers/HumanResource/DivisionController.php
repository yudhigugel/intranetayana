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
use App\Models\HumanResource\DivisionModel;
Use Cookie;
use DataTables;

class DivisionController extends Controller{
  public function index(Request $request){   
        $data=DivisionModel::all();
        return view('pages.human-resource.master-data.division', ['data' => $data]);
  }

  public function edit($id){   
        $data=DivisionModel::find($id);
        return view('pages.human-resource.master-data.division_edit', ['data' => $data]);
  }

  public function getData(Request $request){ 
    try{  
        
        $data=DivisionModel::where('INT_MAP_GRADING_DIVISION.STATUS',1)
                                  ->join('INT_MAP_GRADING_DEPARTMENT','INT_MAP_GRADING_DIVISION.DEPARTMENT_ID','=','INT_MAP_GRADING_DEPARTMENT.DEPARTMENT_ID')
                                  ->select('INT_MAP_GRADING_DIVISION.*','INT_MAP_GRADING_DEPARTMENT.DEPARTMENT_NAME')
                                  ->get();

        $result=array();
        foreach($data as $key=>$value){
          $result[]=array(
            'ID'=>$value->SEQ_ID,
            'DEPARTMENT_ID'=>$value->DEPARTMENT_NAME,
            'DIVISION_ID'=>$value->DIVISION_ID,
            'DIVISION_NAME'=>$value->DIVISION_NAME
          );
          
        }

        return DataTables::of($result)->make(true);
    }
    catch(\Exception $e){
        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
    }
  }

  public function act_edit(Request $request){
    try{  
      $DivisionModel = new DivisionModel();
      
      if ($request->has(['COMPANY_NAME', 'COMPANY_DESCRIPTION'])) {
        $id=$request->COMPANY_ID;
        $data=array(
          'COMPANY_NAME'=>$request->COMPANY_NAME,
        );
        $result=$DivisionModel->updateData($data,$id);
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
      $DivisionModel = new DivisionModel();
      
      
      $data=array(
        'STATUS'=>0
      );
      $result=$DivisionModel->updateData($data,$id);
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




        