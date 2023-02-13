<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;

class FnbBomCost extends Controller
{
    public function index(Request $request){
        // if(Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl', array(), false)) || isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER > 0){
            $last_change = $request->get('last_change', false);
            $plant = $request->get('plant', "");
            $status = $request->get('status', "");
            $material = $request->get('material', "");

            try {
                if($last_change)
                    $last_change = date('Y-m-d', strtotime($last_change));
                else 
                    $last_change = date('Y-m-d');

                $data_flash_cost = [];
                if(!$plant)
                    return response()->json(['status'=>'warning', 'message'=>'Please provide a plant (KMS1, PPC1, etc)!'], 400);
                /* Old Param
                $param = json_encode(array('WERKS'=>$plant,'MATNR'=>$material, 'RSTAT'=>$status, 'AEDAT'=>$last_change));
                */
                
                $check_material = DB::connection('dbayana-stg')
                ->select('dbo.GetMenuDailyTotalMidMenu ?', [$plant]);
                if(is_array($check_material) && count($check_material)){
                    $not_found = 1;
                    for($mtrl=0;$mtrl<count($check_material);$mtrl++){
                        $matnr = isset($check_material[$mtrl]->SAPMATERIALCODE) ? $check_material[$mtrl]->SAPMATERIALCODE : '';
                        $param = json_encode(array('WERKS'=>$plant, 'MATNR'=>$matnr, 'RSTAT'=>'', 'AEDAT'=>''));
                        $param=urlencode($param);
                        $url = config('intranet.RECIPE_COST_URL')."".$param;
                        $client = new \GuzzleHttp\Client();
                        $header = ['headers' => []];
                        $res = $client->get($url, $header);
                        $data_flash_cost = json_decode($res->getBody()->getContents(), true);
                        if(is_array($data_flash_cost) && count($data_flash_cost)){
                            if(isset($data_flash_cost[0]['RSTAT']) && strtoupper($data_flash_cost[0]['RSTAT']) != 'D'){
                                try {
                                    $tcost_header = isset($data_flash_cost[0]['TCOST']) ? $data_flash_cost[0]['TCOST'] : 0;
                                    $duplicate_data_per_day_header = DB::connection('dbayana-stg')->table('RECIPE_COST_HEADER')->whereRaw("WERKS = ? AND MATNR = ?", [$plant, $matnr]);
                                    $check_t_cost = $duplicate_data_per_day_header->get()->pluck('TCOST', 'TCOST')->values()->all();
                                    if(is_array($check_t_cost) && !in_array($tcost_header, $check_t_cost) || !$check_t_cost){
                                        $data_flash_cost[0]['LAST_UPDATE'] = date('Y-m-d H:i:s');
                                        $database = $data_flash_cost[0];
                                        $item = array_map(function($val) { $val['LAST_UPDATE'] = date('Y-m-d H:i:s'); return $val; }, isset($data_flash_cost[0]['ITEMS']) ? $data_flash_cost[0]['ITEMS'] : []);
                                        try {
                                            unset($database['ITEMS']);
                                        } catch(\Exception $e){}

                                        DB::connection('dbayana-stg')->beginTransaction();
                                        try {
                                            $data_id = DB::connection('dbayana-stg')->table('RECIPE_COST_HEADER')->insertGetId($database);
                                            $item_new = array_map(function($val) use ($data_id) { $val['HEADER_ID'] = (String)$data_id; $val['WENGE'] = (float)$val['WENGE']; $val['PCOST'] = (float)$val['PCOST']; return $val; }, $item);
                                            DB::connection('dbayana-stg')->table('RECIPE_COST_ITEM')->insert($item_new);
                                            DB::connection('dbayana-stg')->commit();
                                            // all good
                                        } catch (\Exception $e) {
                                            DB::connection('dbayana-stg')->rollback();
                                            throw new \Exception($e->getMessage());
                                            // something went wrong
                                        }
                                    }
                                } catch(\Exception $e){
                                    throw new \Exception($e->getMessage());
                                }
                            }
                        } else {
                            Log::info("${not_found}. Material ${matnr} data not found in recipe cost API <br> \n\r");
                            $not_found++;
                        }
                    }
                    return response()->json(['status'=>'success', 'message'=>'Data inserted successfully, all Draft status will be rejected if any'], 200);
                } else {
                    return response()->json(['status'=>'warning', 'message'=>"No material available for plant ${plant}", 'data_recipe_cost'=>$data_flash_cost], 200);
                }
            } catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
            }
        // } else {
            // return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        // }
    }
}
