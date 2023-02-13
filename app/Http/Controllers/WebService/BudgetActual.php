<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;

class BudgetActual extends Controller
{
    public function index(Request $request){
        if(Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl', array(), false)) || Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl_cost', array(), false)) || isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER > 0){
            $poper = $request->get('period', false);
            $bukrs = $request->get('bukrs', false);
            try {
                try{
                    $current_gjahr = date('Y');
                    $poper = array_filter(explode('/', $request->get('period', false)));
                    if(isset($poper[0]) && isset($poper[1])){
                        $current_gjahr = $poper[0];
                        $poper = $poper[1];
                    } else 
                        return response()->json(['status'=>'warning', 'message'=>'Please make sure to send period Year/Month format!'], 200);
                } catch(\Exception $e){
                    $poper = false;
                }

                $data_budget_actual = [];
                $data_budget_actual_no_GL = [];

                if($poper){
                    if(!$bukrs)
                        return response()->json(['status'=>'warning', 'message'=>'Please provide a company (KMS, PPC, etc)!'], 200);

                    $param = json_encode(array('BUKRS'=>$bukrs,'KOKRS'=>'MID','GJAHR'=>$current_gjahr,'POPER'=>$poper,'CATEG'=>'ZMI','PROFI'=>'ZMID'));

                    $param=urlencode($param);
                    $url = config('intranet.PNL_SAP')."".$param;
                    $url_2 = config('intranet.REFX_SAP')."".$param;

                    $client = new \GuzzleHttp\Client();
                    $header = ['headers' => []];
                    // Request for first API for GL with Cost Center
                    $res = $client->get($url, $header);
                    $data_budget_actual = json_decode($res->getBody()->getContents(), true);

                    // Request for second API for GL without Cost Center (Apartment)
                    $res_2 = $client->get($url_2, $header);
                    $data_budget_actual_no_GL = json_decode($res_2->getBody()->getContents(), true);
                    // return response()->json(['data'=>$data_budget_actual], 200);
                    // dd($data_budget_actual);
                } else 
                    return response()->json(['status'=>'warning', 'message'=>'Please provide a period!'], 200);
                    
                // dd($data_budget_actual, $data_budget_actual_no_GL);
                if(is_array($data_budget_actual) && count($data_budget_actual)){
                    // Insert data 100 / total;
                    $chunk_data_json = array_chunk($data_budget_actual, 100);
                    DB::connection('dbayana-stg')->table('SAP_PNL')->where(['POPER'=>$poper, 'BUKRS'=>$bukrs, 'GJAHR'=>$current_gjahr, 'API_VERSION'=>1])->delete();
                    for($i=0;$i<count($chunk_data_json);$i++){
                        $chunk_data_json[$i] = array_map(function($val) use ($poper) { $val['API_VERSION'] = 1; $val['POPER'] = $poper; $val['LAST_UPDATE'] = date('Y-m-d H:i:s'); return $val; }, $chunk_data_json[$i]);
                        DB::connection('dbayana-stg')->table('SAP_PNL')->insert($chunk_data_json[$i]);
                    }

                    if(is_array($data_budget_actual_no_GL) && count($data_budget_actual_no_GL)){
                        $chunk_data_json_no_GL = array_chunk($data_budget_actual_no_GL, 100);
                        DB::connection('dbayana-stg')->table('SAP_PNL')->where(['POPER'=>$poper, 'BUKRS'=>$bukrs, 'GJAHR'=>$current_gjahr, 'API_VERSION'=>2])->delete();
                        for($i=0;$i<count($chunk_data_json_no_GL);$i++){
                            $chunk_data_json_no_GL[$i] = array_map(function($val) use ($poper) { $val['API_VERSION'] = 2; $val['POPER'] = $poper; $val['LAST_UPDATE'] = date('Y-m-d H:i:s'); return $val; }, $chunk_data_json_no_GL[$i]);
                            DB::connection('dbayana-stg')->table('SAP_PNL')->insert($chunk_data_json_no_GL[$i]);
                        }
                    }

                    return response()->json(['status'=>'success', 'message'=>'Data inserted successfully'], 200);
                } else {
                    return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_budget_actual'=>$data_budget_actual, 'data_budget_actual_no_GL'=>$data_budget_actual_no_GL], 200);
                }

            } catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
            }
        } else {
            return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        }
    }

    public function sync_data_budget(Request $request){
        $format_period = (String)date('Y').'/'.date('n');
        $poper = $format_period;
        $bukrs = $request->get('company', false);
        try {
            try{
                $current_gjahr = date('Y');
                $poper = array_filter(explode('/', $request->get('period', $format_period)));
                if(isset($poper[0]) && isset($poper[1])){
                    $current_gjahr = $poper[0];
                    $poper = $poper[1];
                } else 
                    return response()->json(['status'=>'warning', 'message'=>'Please make sure to send period Year/Month format!'], 200);
            } catch(\Exception $e){
                $poper = false;
            }

            $data_budget_actual = [];
            $data_budget_actual_no_GL = [];

            if($poper){
                if(!$bukrs)
                    return response()->json(['status'=>'warning', 'message'=>'Please provide a company (KMS, PPC, etc)!'], 200);

                $param = json_encode(array('BUKRS'=>$bukrs,'KOKRS'=>'MID','GJAHR'=>$current_gjahr,'POPER'=>$poper,'CATEG'=>'ZMI','PROFI'=>'ZMID'));

                $param=urlencode($param);
                $url = config('intranet.PNL_SAP')."".$param;
                $url_2 = config('intranet.REFX_SAP')."".$param;

                $client = new \GuzzleHttp\Client();
                $header = ['headers' => []];
                // Request for first API for GL with Cost Center
                $res = $client->get($url, $header);
                $data_budget_actual = json_decode($res->getBody()->getContents(), true);

                // Request for second API for GL without Cost Center (Apartment)
                $res_2 = $client->get($url_2, $header);
                $data_budget_actual_no_GL = json_decode($res_2->getBody()->getContents(), true);
                // return response()->json(['data'=>$data_budget_actual], 200);
                // dd($data_budget_actual);
            } else 
                return response()->json(['status'=>'warning', 'message'=>'Please provide a period!'], 200);
                
            // dd($data_budget_actual, $data_budget_actual_no_GL);
            if(is_array($data_budget_actual) && count($data_budget_actual)){
                // Insert data 100 / total;
                $chunk_data_json = array_chunk($data_budget_actual, 100);
                DB::connection('dbayana-stg')->table('SAP_PNL')->where(['POPER'=>$poper, 'BUKRS'=>$bukrs, 'GJAHR'=>$current_gjahr, 'API_VERSION'=>1])->delete();
                for($i=0;$i<count($chunk_data_json);$i++){
                    $chunk_data_json[$i] = array_map(function($val) use ($poper) { $val['API_VERSION'] = 1; $val['POPER'] = $poper; $val['LAST_UPDATE'] = date('Y-m-d H:i:s'); return $val; }, $chunk_data_json[$i]);
                    DB::connection('dbayana-stg')->table('SAP_PNL')->insert($chunk_data_json[$i]);
                }

                if(is_array($data_budget_actual_no_GL) && count($data_budget_actual_no_GL)){
                    $chunk_data_json_no_GL = array_chunk($data_budget_actual_no_GL, 100);
                    DB::connection('dbayana-stg')->table('SAP_PNL')->where(['POPER'=>$poper, 'BUKRS'=>$bukrs, 'GJAHR'=>$current_gjahr, 'API_VERSION'=>2])->delete();
                    for($i=0;$i<count($chunk_data_json_no_GL);$i++){
                        $chunk_data_json_no_GL[$i] = array_map(function($val) use ($poper) { $val['API_VERSION'] = 2; $val['POPER'] = $poper; $val['LAST_UPDATE'] = date('Y-m-d H:i:s'); return $val; }, $chunk_data_json_no_GL[$i]);
                        DB::connection('dbayana-stg')->table('SAP_PNL')->insert($chunk_data_json_no_GL[$i]);
                    }
                }

                return response()->json(['status'=>'success', 'message'=>'Data inserted successfully'], 200);
            } else {
                return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_budget_actual'=>$data_budget_actual, 'data_budget_actual_no_GL'=>$data_budget_actual_no_GL], 200);
            }

        } catch(\Exception $e){
            return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
        }
    }
}
