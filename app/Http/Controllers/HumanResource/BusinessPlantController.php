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
use Illuminate\Support\Facades\Validator;

use App\Models\HumanResource\BusinessPlantModel;
use App\Models\HumanResource\CompanyModel;
use App\Models\HumanResource\BusinessCompanyModel;
Use Cookie;
use DataTables;

class BusinessPlantController extends Controller{
  public function index(Request $request){   
    $company=NULL;
    if(!empty($request->get('company'))){
      $company=$request->get('company');
    }
    $data=array(
      'company'=>$company
    );
    return view('pages.human-resource.master-data.business-plant', ['data' => $data]);
  }

  public function create(){   
        $data=CompanyModel::all();
        return view('pages.human-resource.master-data.business-plant_create', ['data' => $data]);
  }

  public function edit($id){   
        $data=BusinessPlantModel::findOrFail($id);

        //get company yang dipilih
        $company=BusinessCompanyModel::where('INT_BUSINESS_COMPANY_DETAIL.BUSINESS_UNIT_ID',$id)
                                      ->join('INT_COMPANY','INT_BUSINESS_COMPANY_DETAIL.COMPANY_ID','=','INT_COMPANY.COMPANY_ID')
                                      ->select('INT_COMPANY.*')
                                      ->get();
        $company_parse=array();
        foreach($company as $c){
          $parse=array(
            'id'=>$c->COMPANY_ID,
            'name'=>$c->COMPANY_CODE
          );
          $company_parse[]=$parse;
        }
        // $company_parse=json_encode($company_parse);
        //=================================

        return view('pages.human-resource.master-data.business-plant_edit', ['data' => $data, 'company_parse' => $company_parse]);
  }

  public function getData(Request $request){ 
    $company=$request->input('company'); //filter company
    try{  
      if(!empty($company)){
        $queryWhere=array(
        'INT_BUSINESS_PLANT.STATUS'=>1,
        'INT_BUSINESS_PLANT.COMPANY_CODE'=>$company
        );
      }else{
        $queryWhere=array(
          'INT_BUSINESS_PLANT.STATUS'=>1
        );
      }
      
      $data=BusinessPlantModel::where($queryWhere)
                                ->join('INT_COMPANY','INT_BUSINESS_PLANT.COMPANY_CODE','=','INT_COMPANY.COMPANY_CODE')
                                ->select('INT_BUSINESS_PLANT.*','INT_COMPANY.COMPANY_NAME as NAMA_COMPANY')
                                ->get();
      $result=array();
      foreach($data as $key=>$value){ 
        $result[]=array(
          'COMPANY_CODE'=>$value->COMPANY_CODE,
          'SAP_PLANT_ID'=>$value->SAP_PLANT_ID,
          'NAME'=>$value->SAP_PLANT_NAME
        );
        
      }
      return DataTables::of($result)
                         ->addColumn('ACTION', function ($id) {
              return '<a href="/human-resource/master-data/territory?business-plant=' . $id['SAP_PLANT_ID'] . '" >'.$id['SAP_PLANT_ID'].'</a>'; 
              })
              ->rawColumns(['ACTION'])
              ->make(true);
    }
    catch(\Exception $e){
        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
    }
  }

  public function act_create(Request $request){
    try{  
      DB::beginTransaction();
      $validator = Validator::make($request->all(), [
          'BUSINESS_UNIT_CODE' => 'required|unique:App\Models\HumanResource\BusinessPlantModel|max:10',
          'BUSINESS_UNIT_NAME' => 'required|max:25',
          'BUSINESS_UNIT_DESCRIPTION' => 'required|max:25',
      ]);

      if ($validator->fails()) {
        //validasi kalau field salah
        $error=json_decode($validator->errors());
        $msg_error='';
        foreach($error as $err){
          $msg_error.=$err[0];
        }
        
        $success=false;
        $msg=$msg_error;
        $code='400'; 
      }else{

        $data=array(
          'BUSINESS_UNIT_CODE'=>$request->BUSINESS_UNIT_CODE,
          'BUSINESS_UNIT_NAME'=>$request->BUSINESS_UNIT_NAME,
          'BUSINESS_UNIT_DESCRIPTION'=>$request->BUSINESS_UNIT_DESCRIPTION,
          'STATUS'=>1,
          'CREATED_BY'=>$request->session()->get('user_id')
        );

        $companycode=$request->input('COMPANY_CODE');
        $list_companycode=explode(',',$companycode);     

        $result=BusinessPlantModel::create($data); // insert data to table
        $last_id=$result->BUSINESS_UNIT_ID;

        if($result){
          foreach($list_companycode AS $company){
            //start input ke database business company detail
            $data=array(
              'BUSINESS_UNIT_ID'=>$last_id,
              'COMPANY_ID'=>$company
            );
            BusinessCompanyModel::create($data); //insert data to table
          }
          $success=true;
          $msg="Data has been created";
          $code='200';
        }else{
          $success=false;
          $msg="Something's not right, please try again later";
          $code='400'; 
        }
        DB::commit(); //commit transaction
      }
 
     
      return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));

    }
    catch(\Exception $e){
        return response()->json(array(['data'=>[], 'message'=>$e->getMessage()], 200));
    }

  }

  public function act_edit(Request $request){
    try{  
       DB::beginTransaction();

       

      $id=$request->BUSINESS_UNIT_ID;

      $validator = Validator::make($request->all(), [
          'BUSINESS_UNIT_CODE' => 'required|unique:App\Models\HumanResource\BusinessPlantModel,BUSINESS_UNIT_CODE,'.$id.'|max:10',
          'BUSINESS_UNIT_NAME' => 'required|max:25',
          'BUSINESS_UNIT_DESCRIPTION' => 'required|max:25',
      ]);

      if ($validator->fails()) {
        //validasi kalau field salah
        $error=json_decode($validator->errors());
        $msg_error='';
        foreach($error as $err){
          $msg_error.=$err[0];
        }
        
        $success=false;
        $msg=$msg_error;
        $code='400'; 
      }else{

        $data=array(
          'BUSINESS_UNIT_CODE'=>$request->BUSINESS_UNIT_CODE,
          'BUSINESS_UNIT_NAME'=>$request->BUSINESS_UNIT_NAME,
          'BUSINESS_UNIT_DESCRIPTION'=>$request->BUSINESS_UNIT_DESCRIPTION,
          'MODIFIED_BY'=>$request->session()->get('user_id')
        );

        $companycode=$request->input('COMPANY_CODE');
        $list_companycode=explode(',',$companycode);     

        $result=BusinessPlantModel::where('BUSINESS_UNIT_ID',$id)
                                  ->update($data); // update data to tabel
        if($result){
          //delete dulu data existing di dalam tabel child
          BusinessCompanyModel::where('BUSINESS_UNIT_ID',$id)->delete(); 
          

          foreach($list_companycode AS $company){
            //start input ke database business company detail
            $data=array(
              'BUSINESS_UNIT_ID'=>$id,
              'COMPANY_ID'=>$company
            );
            BusinessCompanyModel::create($data); //insert data to table
          }
          $success=true;
          $msg="Data has been updated";
          $code='200';
        }else{
          $success=false;
          $msg="Something's not right, please try again later";
          $code='400'; 
        }
        DB::commit(); //commit transaction
      }
 
     
      return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));

    }
    catch(\Exception $e){
        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
    }

  }

  public function act_delete($id){
   try{  
      
      $data=array(
        'STATUS'=>0
      );
      $result=BusinessPlantModel::where('BUSINESS_UNIT_ID',$id)->update($data); // update data to tabel
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


  public function getCompany(){   
        $data=CompanyModel::all();
        $result=array();
        foreach($data as $data){
          $parse=array(
            'id'=>$data->COMPANY_ID,
            'name'=>$data->COMPANY_CODE
          );
          $result[]=$parse;
        }

        echo json_encode($result);
  }

}




        