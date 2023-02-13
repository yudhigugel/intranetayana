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
use App\Models\HumanResource\JobTitleModel;
Use Cookie;
use DataTables;

class JobTitleController extends Controller{
  public function index(Request $request){   
        $data=JobTitleModel::all();
        return view('pages.human-resource.master-data.job-title', ['data' => $data]);
  }

  public function edit($id){   
        $data=JobTitleModel::find($id);
        return view('pages.human-resource.master-data.job-title_edit', ['data' => $data]);
  }

  public function getData(Request $request){ 
    try{  
        
        $data=JobTitleModel::where('INT_MAP_GRADING_MIDJOB_OLD.STATUS',1)->get();

        $result=array();
        foreach($data as $key=>$value){
          $result[]=array(
            'ID'=>$value->SEQ_ID,
            'OLD_MIDJOB_ID'=>$value->OLD_MIDJOB_ID,
            'OLD_MIDJOB_TITLE'=>$value->OLD_MIDJOB_TITLE
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
      $JobTitleModel = new JobTitleModel();
      
      if ($request->has(['COMPANY_NAME', 'COMPANY_DESCRIPTION'])) {
        $id=$request->COMPANY_ID;
        $data=array(
          'COMPANY_NAME'=>$request->COMPANY_NAME,
        );
        $result=$JobTitleModel->updateData($data,$id);
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
      $JobTitleModel = new JobTitleModel();
      
      
      $data=array(
        'STATUS'=>0
      );
      $result=$JobTitleModel->updateData($data,$id);
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




        