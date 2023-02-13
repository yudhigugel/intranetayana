<?php

namespace App\Http\Controllers\SAP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use PDF;
Use Cookie;
use DataTables;
use Log;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use App\Http\Controllers\Traits\IntranetTrait;

class Inventory extends Controller{

    function index(Request $request) {
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



        $filter_company=strtoupper((empty($request->input('company')))? $company_code : $request->input('company'));

        //filter kalau KMS supaya ngambil KM01 defaultnya
        $default_plant = (empty($request->input('plant')) && $filter_company=="KMS")? 'KM01' : '';

        $filter_plant=strtoupper((empty($request->input('plant')))? $default_plant : $request->input('plant'));
        $filter_sloc=strtoupper((empty($request->input('sloc')))? '' : $request->input('sloc'));
        $filter_matgroup=strtoupper((empty($request->input('matgroup')))? '' : $request->input('matgroup'));



        $param = [
            'P_COMPANY'=>$filter_company,
            'P_PLANT'=>$filter_plant,
            'P_MATERIAL'=>'',
            'P_MATERIAL_GROUP'=>$filter_matgroup,
            'P_SLOG'=>$filter_sloc
        ];




        try{

            $function = $rfc->getFunction('ZFM_MM_MATERIAL_STOCK');
            $result= $function->invoke($param, $options);
            $sap_inventory = $result['IT_DATA'];

            $total_row=count($sap_inventory);

            //feature untuk limit row sementara
            // $sap_inventory_trimmed = array();
            // if($total_row>5000){
            //     for($i=0;$i<5000;$i++){
            //         $sap_inventory_trimmed[$i]=$sap_inventory[$i];
            //     }
            //     $sap_inventory=$sap_inventory_trimmed;
            // }else{
            //     $sap_inventory=$sap_inventory;
            // }

            $data_inventory = collect($sap_inventory);



            $row_sortByItem = $data_inventory->groupBy('MATERIAL'); // sort by material
            $row_sortByItemNew = array();

            foreach($row_sortByItem AS $key => $item){
                $row_sortByPlant = $item->groupBy('PLANT'); // sort by plant
                //di dalam plant, sort lagi by sloc
                $row_sortByPlant_new=array();
                $grand_total_item = 0;
                foreach($row_sortByPlant AS $plant => $item){

                        $row_sortBySloc = $item->groupBy('STORAGE_LOCATION');
                        $row_sortBySloc_new=array();
                        foreach($row_sortBySloc AS $sloc_key => $sloc_item){
                            $jumlah = 0;
                            $BATCH = array();
                            foreach($sloc_item AS $sloc_item_key => $sloc_item_child){
                                $jumlah += $sloc_item_child['QTY'];

                                $BATCH[] = array(
                                    'BATCH' => $sloc_item_child['BATCH'],
                                    'QTY'=> $sloc_item_child['QTY']
                                );
                            }
                            $grand_total_item +=$jumlah;
                            $row_sortBySloc_new[$sloc_key]['TOTAL'] = $jumlah;
                            $row_sortBySloc_new[$sloc_key]['BATCH'] = $BATCH;
                            $row_sortBySloc_new[$sloc_key]['STORAGE_LOCATION_DESC']   =$sloc_item_child['STORAGE_LOCATION_DESC'];
                        };

                        $row_sortByPlant_new['PLANT'][$plant]           =$row_sortBySloc_new;
                        $row_sortByPlant_new['MATERIAL_DESC']           =$item[0]['MATERIAL_DESC'];
                        $row_sortByPlant_new['MATERIAL_GROUP']          =$item[0]['MATERIAL_GROUP'];
                        $row_sortByPlant_new['MATERIAL_GROUP_DESC']     =$item[0]['MATERIAL_GROUP_DESC'];
                        $row_sortByPlant_new['UOM']                     =$item[0]['UOM'];
                        $row_sortByPlant_new['GRAND_TOTAL']             =$grand_total_item;

                }
                $row_sortByItemNew[$key]=$row_sortByPlant_new;
            }
            $inventory = $row_sortByItemNew; // init var

            $param_sloc = [
                'P_COMPANY'=>$filter_company,
                'P_PLANT'=>$filter_plant,
            ];

            $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
            $result= $function->invoke($param_sloc, $options);
            $sap_sloc = $result['IT_SLOC'];

            // grup by plant
            $data_sloc = collect($sap_sloc);

            $sloc_groupByPlant = $data_sloc->groupBy('PLANT');

            $sloc_groupByPlant_new = array();
            foreach($sloc_groupByPlant AS $plant => $sloc){

                $slocgroup['warehouse']=array();
                $slocgroup['sloc']=array();
                $total_sloc = 0;
                foreach($sloc AS $location){
                    $huruf_depan_sloc=substr($location['STORAGE_LOCATION'], 0, 1 );
                    if($huruf_depan_sloc=="W"){ // cek apakah sloc warehouse
                        //masukkan ke sloc warehouse
                        $total_sloc++;
                        $input=array(
                            'code'=>$location['STORAGE_LOCATION'],
                            'name'=>$location['STORAGE_LOCATION_DESC'],
                            'plant'=>$location['PLANT']

                        );
                        $slocgroup['warehouse'][]=$input;
                    }else{
                        //masukkan ke sloc selain warehouse
                        $total_sloc++;
                        $input=array(
                            'code'=>$location['STORAGE_LOCATION'],
                            'name'=>$location['STORAGE_LOCATION_DESC'],
                            'plant'=>$location['PLANT']
                        );
                        $slocgroup['sloc'][]=$input;
                    }
                }
                $slocgroup['total_sloc']= $total_sloc;

                $sloc_groupByPlant_new[$plant]=$slocgroup;
            }
            $plant_sloc = $sloc_groupByPlant_new;

            // ===============================================

            //kebutuhan untuk filter


            $data['list_company']=DB::connection('dbintranet')
                                ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
            $data['list_plant']=DB::connection('dbintranet')
                                ->select("SELECT * FROM INT_BUSINESS_PLANT WHERE COMPANY_CODE ='".$filter_company."' ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");

            $param_matgroup = [
            ];

            $function = $rfc->getFunction('ZFM_MATERIAL_GROUP');
            $result= $function->invoke($param_matgroup, $options);
            $data['list_matgroup']= $result['ZTA_T023'];

        }catch(SAPFunctionException $e){
            dd($e);
            die;
        }





        return view('pages.sap.inventory.index', ['data'=>$data, 'plant_sloc'=>$plant_sloc, 'inventory'=> $inventory, 'filter_company'=>$filter_company, 'filter_plant'=> $filter_plant, 'filter_matgroup' => $filter_matgroup]);
    }


    public function ajax_filterCompany(Request $request){
        @$company=$request->input('company');
        try{
            $data=DB::connection('dbintranet')
            ->select("SELECT * FROM INT_BUSINESS_PLANT WHERE COMPANY_CODE = '".$company."' ");

            $success=true;
            $code = 200;
            $msg = 'Query success';
        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }
        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 'data'=>$data, 200));

    }

    public function ajax_filterPlant(Request $request){
        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc_dev'));

        $options = [
            'rtrim'=>true,
        ];

        @$company=$request->input('company');
        @$plant=$request->input('plant');
        try{
             $param_sloc = [
                'P_COMPANY'=>''.$company.'',
                'P_PLANT'=>''.$plant.''
            ];

            $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
            $result= $function->invoke($param_sloc, $options);
            $sap_sloc = $result['IT_SLOC'];

            $list_sloc_wh= collect($sap_sloc)->filter(function ($value, $key) {
                return substr($value['STORAGE_LOCATION'], 0, 1 ) == "W";
            });

            $list_sloc_outlet= collect($sap_sloc)->filter(function ($value, $key) {
                return substr($value['STORAGE_LOCATION'], 0, 1 ) !== "W";
            });

            $data = $list_sloc_wh->merge($list_sloc_outlet);

            $success=true;
            $code = 200;
            $msg = 'Query success';
        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }
        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 'data'=>$data, 200));

    }

    public function barcode_inventory(Request $request){
        $data=array();
        $list_material=NULL;

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


        $keyword=(empty($request->input('keyword')))? '' : strtoupper($request->input('keyword'));

        $filter_company=strtoupper((empty($request->input('company')))? $company_code : $request->input('company'));
        $filter_plant=strtoupper((empty($request->input('plant')))? '' : $request->input('plant'));
        $filter_matgroup=strtoupper((empty($request->input('matgroup')))? '' : $request->input('matgroup'));

        if(!empty($keyword) || !empty($request->input('company')) ){

            try{
                $param = array(
                    'GV_MAKTX'=>"*".$keyword."*",
                    'GV_BUKRS'=>''.$filter_company.'',
                    'GV_RFIKRS'=>''.$filter_company.'',
                    'GV_ACCTASSCAT'=>'',
                    'GV_WERKS'=>''.$filter_plant.'',
                    'GV_MATERIAL_GROUP'=>$filter_matgroup,
                    'GV_MAX_ROWS'=>9999
                );

                // $function_type = $rfc->getFunction('ZFM_POPUP_MAT_BDT_INTRA_MONTH');
                $function_type = $rfc->getFunction('ZFM_MID_MAT_BDT_INTRA_MONTH');
                $sap_material= $function_type->invoke($param, $options);
                $list_material=$sap_material['GI_HEADER'];


                //group by code
                $grup_material = collect($list_material);
                $list_material = $grup_material->groupBy('MATNR');

                //buat masing2 hanya 1 array
                $tampung = array();
                foreach($list_material AS $key => $child){
                    $tampung[$key]=$child[0];
                }
                $list_material_filtered = collect($tampung);

                // group by name
                $list_material_sortbyname = $list_material_filtered->sortBy('MAKTX');

                //finalize
                $list_material = $list_material_sortbyname;

            }catch(SapException $e){
                dd($e);
            }



        }


        $data['list_company']=DB::connection('dbintranet')
        ->select("SELECT * FROM INT_COMPANY ORDER BY COMPANY_CODE ASC ");
        $data['list_plant']=DB::connection('dbintranet')
                ->select("SELECT * FROM INT_BUSINESS_PLANT WHERE COMPANY_CODE ='".$filter_company."' ORDER BY COMPANY_CODE, SAP_PLANT_ID ASC");

        $data['list_matgroup']=DB::connection('dbintranet')
        ->select("SELECT * FROM INT_REPORT_MASTER_BARCODE_MATGROUP ORDER BY MATERIAL_GROUP ASC");

        // $param_matgroup = [
        // ];

        // $function = $rfc->getFunction('ZFM_MATERIAL_GROUP');
        // $result= $function->invoke($param_matgroup, $options);
        // $data['list_matgroup']= $result['ZTA_T023'];


        return view('pages.sap.barcode_inventory.index', ['data'=>$data, 'keyword' => $keyword, 'list_material'=> $list_material, 'filter_company'=>$filter_company, 'filter_plant'=> $filter_plant, 'filter_matgroup' => $filter_matgroup]);
    }

}
