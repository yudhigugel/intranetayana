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

class ReportMRP extends Controller{
    private $form_number;

    public function __construct()
    {
        $this->form_number = "";
    }

    function index(Request $request) {
        $data=array();

        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc_dev'));

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
        $default_plant = (empty($request->input('plant')) && $filter_company=="KMS")? 'KMS1' : $plant;

        $filter_plant=strtoupper((empty($request->input('plant')))? $default_plant : $request->input('plant'));
        $filter_sloc=(empty($request->input('sloc')))? '' : $request->input('sloc');
        $filter_matgroup=strtoupper((empty($request->input('matgroup')))? '' : $request->input('matgroup'));


        try{
            $inventory = array();
            $param_sloc = [
                'P_COMPANY'=>$filter_company,
                'P_PLANT'=>$filter_plant
            ];

            $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
            $result= $function->invoke($param_sloc, $options);
            $sap_sloc = $result['IT_SLOC'];


            if(empty($filter_sloc)){
                $data_sloc = collect($sap_sloc);
            }else{
                $data_sloc = collect($sap_sloc)->filter(function ($value, $key) use ($filter_sloc) {
                    return in_array($value['STORAGE_LOCATION'],$filter_sloc);
                    // $value['STORAGE_LOCATION'] == $filter_sloc;
                });
            }

            // echo json_encode($data_sloc);
            // die;
            // grup by plant


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

            // $list_sloc_wh = collect(collect($plant_sloc)->pluck('warehouse')->first());
            // $list_sloc_outlet = collect(collect($plant_sloc)->pluck('sloc')->first());

            $list_sloc_wh= collect($sap_sloc)->filter(function ($value, $key) {
                return substr($value['STORAGE_LOCATION'], 0, 1 ) == "W";
            });

            $list_sloc_outlet= collect($sap_sloc)->filter(function ($value, $key) {
                return substr($value['STORAGE_LOCATION'], 0, 1 ) !== "W";
            });


            $data['list_sloc'] = $list_sloc_wh->merge($list_sloc_outlet);



            $param_matgroup = [
            ];

            $function = $rfc->getFunction('ZFM_MATERIAL_GROUP');
            $result= $function->invoke($param_matgroup, $options);
            $data['list_matgroup']= $result['ZTA_T023'];

        }catch(SAPFunctionException $e){
            dd($e);
            die;
        }


        $param=array(
            'P_PLANT'=>''.$filter_plant.'',
            'P_MATERIAL'=>''
            // 'P_SLOC'=>''.$filter_sloc.''
        );


        $data_mrp_sort_new=array();
        $harus_pilih_dulu=true;
        if(!empty($request->input('company')) && !empty($request->input('plant'))){
            $harus_pilih_dulu=false;
            try{
                $function_type = $rfc->getFunction('ZFM_MID_MM_MRP_REPORT');
                $sap_mrp= $function_type->invoke($param, $options);
                $data_mrp = $sap_mrp['IT_MRP'];

                if(!empty($filter_sloc)){
                    $data_mrp=collect($data_mrp)->filter(function ($value, $key) use ($filter_sloc) {
                        return in_array($value['SLOC'],$filter_sloc);
                    });
                }

                $data_mrp_sort_by_material = collect($data_mrp)->groupBy('SAP_MATERIAL_CODE');




                foreach($data_mrp_sort_by_material AS $key => $item){

                    $data_sort_by_sloc = collect($item)->groupBy('SLOC');
                    $data_mrp_sort_new[$key]['MATERIAL']=$item[0]['SAP_MATERIAL_CODE'];
                    $data_mrp_sort_new[$key]['DESCRIPTION']=$item[0]['SAP_MATERIAL_DESCRIPTION'];
                    $data_mrp_sort_new[$key]['UOM']=$item[0]['UOM'];
                    $data_mrp_sort_new[$key]['SLOC']=$data_sort_by_sloc;

                }

            }catch(SAPFunctionException $e){
                dd($e);
                die;
            }
        }

        return view('pages.sap.report-mrp.index', ['result'=>$data_mrp_sort_new, 'data'=>$data, 'plant_sloc'=>$plant_sloc, 'inventory'=> $inventory, 'filter_company'=>$filter_company, 'filter_plant'=> $filter_plant, 'filter_matgroup' => $filter_matgroup, 'filter_sloc' => $filter_sloc, 'harus_pilih_dulu' => $harus_pilih_dulu ]);
    }

}



