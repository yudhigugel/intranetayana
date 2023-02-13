<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;
use SimpleXMLElement;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;

class MaterialLastPrice extends Controller
{
    public function index(Request $request){
        try {
            $is_production = config('intranet.is_production');
            $plant = $request->get('plant', false);
            if(!$plant)
                return response()->json(['status'=>'error', 'message'=>'Please provide plant to lookup'], 400);

            $data_insert = [];
            $errors = [];
            $check_material = DB::connection('dbayana-stg')
            ->select('EXEC dbo.GetMenuDailyTotalFBMaterial ? ', array($plant));
            if(is_array($check_material) && count($check_material) > 0){
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc'));
                }
                $options = [
                    'rtrim'=>true,
                    'ltrim'=>true
                ];

                /** 
                 * Start cek material detail
                 */
                $business_date = null;
                for($mtrl=0;$mtrl<count($check_material);$mtrl++){
                    $material_code = isset($check_material[$mtrl]->SAPMATERIALCODE) ? (String)$check_material[$mtrl]->SAPMATERIALCODE : '';
                    $business_date = isset($check_material[$mtrl]->BUSINESSDATE) ? $check_material[$mtrl]->BUSINESSDATE : null;
                    $param = array(
                        'GV_MAKTX'=>"*${material_code}*",
                        'GV_ACCTASSCAT'=>'',
                        'GV_WERKS'=>$plant,
                        'GV_MAX_ROWS'=>10
                    );

                    $function_type = $rfc->getFunction('ZFM_POPUP_MAT_BDT_INTRA_MONTH');
                    $material = $function_type->invoke($param, $options);
                    $material = isset($material['GI_HEADER'][0]) ? $material['GI_HEADER'][0] : [];
                    if(count($material)>0){
                        try {
                            $param = array(
                                'WERKS'=>$plant,
                                'IT_MATERIAL'=>array(
                                    [
                                        'MATNR'=>trim($material['MATNR']),
                                        'MEINS'=>trim($material['MEINS']),
                                        'MENGE'=>1
                                    ]
                                )
                            );
                            $function_type = $rfc->getFunction('YFM_GET_MAT_COST_3');
                            $last_price= $function_type->invoke($param, $options);
                            $last_price = isset($last_price['MAT_PRICE'][0]['DMBTR']) ? $last_price['MAT_PRICE'][0]['DMBTR'] : 0;

                            $check_last_cost_mtrl = DB::connection('dbayana-stg')
                            ->table('dbo.MasterItemCost')
                            ->where(['PLANT'=>$plant, 'SAPMATERIALCODE'=>$material_code])
                            ->get()->pluck('COST', 'COST')->values()->all();

                            if(is_array($check_last_cost_mtrl) && !in_array($last_price, $check_last_cost_mtrl) || !$check_last_cost_mtrl){
                                $data_insert[] = array(
                                    'SAPMATERIALCODE'=>$material_code,
                                    'COST'=>(float)$last_price,
                                    'PLANT'=>$plant,
                                    'BUSINESS_DATE'=>$business_date
                                );
                            }
                        } catch(SAPFunctionException $e){}
                    } else{
                        $errors[$plant][] = $material_code;
                    }
                }

                if(count($data_insert) > 0){
                    // dd($data_insert, $errors);
                    DB::connection('dbayana-stg')->beginTransaction();
                    try {
                        // DB::connection('dbayana-stg')
                        // ->table('dbo.MasterItemCost')
                        // ->where(['PLANT'=>$plant, 'BUSINESS_DATE'=>date('Y-m-d', strtotime($business_date))])
                        // ->delete();
                    
                        DB::connection('dbayana-stg')
                        ->table('dbo.MasterItemCost')
                        ->insert($data_insert);
                        DB::connection('dbayana-stg')->commit();
                        
                        // all good
                    } catch (\Exception $e) {
                        DB::connection('dbayana-stg')->rollback();
                        return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
                        // something went wrong
                    }
                    return response()->json(['status'=>'success', 'message'=>'Success insert data cost', 'data_inserted'=>count($data_insert), 'not_found_material_detail_rfc'=>$errors], 200);
                }
                else
                    return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_to_insert'=>$data_insert], 200);
                
            } else
                throw new \Exception("No Material found within the plant ${plant}, please make sure any material is used for transaction within the plant");
                

        } catch(\Exception $e){
            return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
        }
    }
}
