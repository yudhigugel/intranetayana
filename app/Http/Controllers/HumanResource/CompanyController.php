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
use App\Models\HumanResource\CompanyModel;
Use Cookie;
use DataTables;

class CompanyController extends Controller{
  public function index(Request $request){   
        $data=array();
        return view('pages.human-resource.master-data.company', ['data' => $data]);
  }

  public function edit($id){   
        $data=CompanyModel::find($id);
        return view('pages.human-resource.master-data.company_edit', ['data' => $data]);
  }

  public function getData(Request $request){ 
    try{  
        
        $data=CompanyModel::all();

        $result=array();
        foreach($data as $key=>$value){
          $result[]=array(
            'ID'=>$value->SEQ_ID,
            'CODE'=>$value->COMPANY_CODE,
            'NAME'=>$value->COMPANY_NAME
          );
          
        }

        return DataTables::of($result)
         ->addColumn('ACTION', function ($id) {
              
              return '<a href="/human-resource/master-data/business-plant?company=' . $id['CODE'] . '" >'.$id['CODE'].'</a>'; 
              })
            ->rawColumns(['ACTION'])
            ->make(true);
    }
    catch(\Exception $e){
        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
    }
  }

  public function act_edit(Request $request){
    try{  
      $CompanyModel = new CompanyModel();
      
      if ($request->has(['COMPANY_NAME', 'COMPANY_DESCRIPTION'])) {
        $id=$request->COMPANY_ID;
        $data=array(
          'COMPANY_NAME'=>$request->COMPANY_NAME,
        );
        $result=$CompanyModel->updateData($data,$id);
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
      $CompanyModel = new CompanyModel();
      
      
      $data=array(
        'STATUS'=>0
      );
      $result=$CompanyModel->updateData($data,$id);
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




        