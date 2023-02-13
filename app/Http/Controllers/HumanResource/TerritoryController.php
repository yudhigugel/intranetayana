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
use App\Models\HumanResource\TerritoryModel;
Use Cookie;
use DataTables;

class TerritoryController extends Controller{
  public function index(Request $request){   
        $data=TerritoryModel::all();
        $filter=NULL;
        if(!empty($request->get('business-plant'))){
          $filter=$request->get('business-plant');
        }
        $data=array(
          'filter'=>$filter
        );
        return view('pages.human-resource.master-data.territory', ['data' => $data]);
  }

  public function edit($id){   
        $data=TerritoryModel::find($id);
        return view('pages.human-resource.master-data.territory_edit', ['data' => $data]);
  }

  public function getData(Request $request){ 
    $filter=$request->input('business-plant'); //filter
    try{  
        if(!empty($filter)){
        $queryWhere=array(
        'INT_TERRITORY.STATUS'=>1,
        'INT_TERRITORY.SAP_PLANT_ID'=>$filter
        );
      }else{
        $queryWhere=array(
          'INT_TERRITORY.STATUS'=>1
        );
      }
        $data=TerritoryModel::where($queryWhere)
                                  ->join('INT_BUSINESS_PLANT','INT_TERRITORY.SAP_PLANT_ID','=','INT_BUSINESS_PLANT.SAP_PLANT_ID')
                                  ->select('INT_TERRITORY.*','INT_BUSINESS_PLANT.SAP_PLANT_NAME as NAMA_PLANT')
                                  ->get();

        $result=array();
        foreach($data as $key=>$value){
          $result[]=array(
            'PLANT_ID'=>$value->SAP_PLANT_ID,
            'TERRITORY_CODE'=>$value->TERRITORY_CODE,
            'TERRITORY_ID'=>$value->TERRITORY_ID,
            'TERRITORY_NAME'=>$value->TERRITORY_NAME
          );
          
        }
        
        return DataTables::of($result)
                         ->addColumn('ACTION', function ($id) {
              return '<a href="/human-resource/employee-list/employee/filter?TERRITORY_ID=' . $id['TERRITORY_ID'] . '" >'.$id['TERRITORY_ID'].'</a>'; 
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
      $TerritoryModel = new TerritoryModel();
      
      if ($request->has(['COMPANY_NAME', 'COMPANY_DESCRIPTION'])) {
        $id=$request->COMPANY_ID;
        $data=array(
          'COMPANY_NAME'=>$request->COMPANY_NAME,
        );
        $result=$TerritoryModel->updateData($data,$id);
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
      $TerritoryModel = new TerritoryModel();
      
      
      $data=array(
        'STATUS'=>0
      );
      $result=$TerritoryModel->updateData($data,$id);
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




        