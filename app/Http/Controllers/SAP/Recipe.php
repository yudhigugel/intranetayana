<?php

namespace App\Http\Controllers\SAP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;
use Log;
use App\Models\Zoho\ZohoFormModel;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use App\Models\HumanResource\CostCenterModel as CostCenter;
use App\Http\Controllers\Traits\IntranetTrait;
use Illuminate\Support\Facades\View;

class Recipe extends Controller
{
    // only allow movement type code 311 and starts with Y
    // private $allowed_movement_type = ['311', 'Y'];
    use IntranetTrait;

    public function __construct()
    {
        $this->form_number = "RCP";
        $this->form_view="VIEW_FORM_REQUEST_ADD_RECIPE";
        $this->approval_view="VIEW_APPROVAL_FORM_REQUEST_ADD_RECIPE";
        $this->approval_view_link="/finance/add-recipe/approval";
        $this->link_request = "/finance/add-recipe/request";
    }

    public function insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type){
        $data_approval = DB::connection('dbintranet')
            ->table(DB::raw($this->approval_view))
            ->where('UID',$uid)
            ->get();

        if(!$data_approval->isEmpty()){
            $data_approval=collect($data_approval[0])->toArray();

            $i = $level_approval; // mencari approval selanjutnya untuk diberikan notif
            $appr_midjob=isset($data_approval['APPROVAL_'.$i.'_MIDJOB_ID']) ? $data_approval['APPROVAL_'.$i.'_MIDJOB_ID'] : NULL;
            $appr_employeeId=isset($data_approval['APPROVAL_'.$i.'_EMPLOYEE_ID']) ? $data_approval['APPROVAL_'.$i.'_EMPLOYEE_ID'] : NULL;

            // cek dulu apakah di tabel approval mapping untuk tingkatan itu ada Employee ID
            // karena employee ID akan override dari semua field lain, maka dahulukan dulu cek employee ID pada tingkatan tersebut
            if(!empty($appr_employeeId)){
                $select = "SELECT EMPLOYEE_ID FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$appr_employeeId."' ";
            }else if(!empty($appr_midjob)){
                $select = "SELECT EMPLOYEE_ID FROM VIEW_EMPLOYEE WHERE MIDJOB_TITLE_ID ='".$appr_midjob."' ";
                $appr_plant=($data_approval['APPROVAL_'.$i.'_PLANT_ID']) ? " AND SAP_PLANT_ID='".$data_approval['APPROVAL_'.$i.'_PLANT_ID']."'" : NULL;
                $appr_territory=($data_approval['APPROVAL_'.$i.'_TERRITORY_ID']) ? " AND TERRITORY_ID='".$data_approval['APPROVAL_'.$i.'_TERRITORY_ID']."'" : NULL;
                $select .= $appr_plant.$appr_territory;
            }

            if(!empty($select)){
                $emp_appr=DB::connection('dbintranet')
                ->select($select);
                foreach($emp_appr AS $notif_appr){
                    $notif_employee_id=$notif_appr->EMPLOYEE_ID;
                    $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type); // insert notif
                }
            }

        }
    }

    public function request(Request $request){
        //init RFC
        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc'));
        $options = [
            'rtrim'=>true,
            'use_function_desc_cache'=>false
        ];
        // SapConnection::setTraceLevel(3);
        //===

        /* Jika request berupa ajax untuk mencari material */
        if($this->wantsJson($request)) {
            try {
                if($request->get('type', false) && strtolower($request->get('type')) == 'material'){
                    $data_material = [];
                    $keyword = strtoupper($request->get('searchTerm', ''));
                    $plant_code = $request->get('plant', '');
                    $sloc = $request->get('sloc', '');
                    $company_code = '';

                    $param = array(
                        'GV_MAKTX'=>"*".$keyword."*",
                        'GV_ACCTASSCAT'=>'',
                        'GV_WERKS'=>$plant_code,
                        'GV_MAX_ROWS'=>30
                    );

                    $function_type = $rfc->getFunction('ZFM_POPUP_MAT_BDT_INTRA_MONTH');
                    $list_material= $function_type->invoke($param, $options);
                    $list_material = isset($list_material['GI_HEADER']) ? $list_material['GI_HEADER'] : [];
                    if(count($list_material) < 1){
                        $param = array(
                            'GV_MAKTX'=>"*".$keyword."*",
                            'GV_ACCTASSCAT'=>'1',
                            'GV_WERKS'=>$plant_code,
                            'GV_MAX_ROWS'=>30
                        );

                        $function_type = $rfc->getFunction('ZFM_POPUP_MAT_BDT_INTRA_MONTH');
                        $list_material= $function_type->invoke($param, $options);
                        $list_material = isset($list_material['GI_HEADER']) ? $list_material['GI_HEADER'] : [];
                    }

                    $check_company = DB::connection('dbintranet')
                    ->table('INT_BUSINESS_PLANT')
                    ->where('SAP_PLANT_ID', $plant_code)
                    ->select('COMPANY_CODE')->get()->first();
                    if($check_company){
                        $company_code = $check_company->COMPANY_CODE;
                    }
                    else{
                        Log::error("MATERIAL SEARCH ERROR RECIPE COST | No Company Code Found for plant ${plant_code}");
                    }

                    $material_type = [];
                    try {
                        $param = array();
                        $function_type = $rfc->getFunction('ZFM_MM_MD_MATERIAL_TYPE');
                        $material_type = $function_type->invoke($param, $options)['GT_REPORT'];
                    } catch(\Exception $e){}

                    $material_group_master = [];
                    try {
                        $param = array();
                        $function_type = $rfc->getFunction('ZFM_MATERIAL_GROUP');
                        $material_group_master = $function_type->invoke($param, $options)['ZTA_T023'];
                    } catch(\Exception $e){}

                    foreach ($list_material as $key => $value) {
                        $param = [
                            'P_COMPANY'=>$company_code,
                            'P_PLANT'=>$plant_code,
                            'P_MATERIAL'=>$value['MATNR'],
                            // 'P_SLOG'=>$sloc
                        ];

                        try {
                            $material_type_search = [];
                            $mat_group = 'Unknown';
                            $is_recipe = false;
                           if(substr($value['MATNR'], 9, 2) == '31'){
                               $param = json_encode(array('WERKS'=>$plant_code, 'MATNR'=>$value['MATNR'], 'RSTAT'=>'', 'AEDAT'=>''));
                               $param=urlencode($param);
                               $url = config('intranet.RECIPE_COST_URL')."".$param;
                               $client = new \GuzzleHttp\Client();
                               $header = ['headers' => []];
                               $res = $client->get($url, $header);
                               $data_cost = json_decode($res->getBody()->getContents(), true);
                               $value['MEINS'] = isset($data_cost[0]['MEINS']) ? $data_cost[0]['MEINS'] : $value['MEINS'];
                               $m_type = isset($data_cost[0]['MTART']) ? $data_cost[0]['MTART'] : '';
                               $material_type_search = collect($material_type)->filter(function($item, $key) use ($m_type){
                                  return $item['MATERIAL_TYPE'] == $m_type;
                               })->first();
                               $mat_group = $data_cost[0]['MATKL'].' - '.$data_cost[0]['WGBEZ'];
                               $is_recipe = true;

                           } else {
                               $function_type = $rfc->getFunction('ZFM_MM_MATERIAL_STOCK');
                               $invoke = $function_type->invoke($param, $options);
                               $is_data_available = isset($invoke['IT_DATA']) ? $invoke['IT_DATA'] : [];
                               
                               if(count($is_data_available) > 0){
                                  $param = [
                                    'MATERIAL'=>$value['MATNR'],
                                  ];
                                  $function_type = $rfc->getFunction('BAPI_MATERIAL_GET_DETAIL');
                                  $invoke = $function_type->invoke($param, $options);
                                  $m_type = isset($invoke['MATERIAL_GENERAL_DATA']['MATL_TYPE']) ? $invoke['MATERIAL_GENERAL_DATA']['MATL_TYPE'] : '';
                                  $material_type_search = collect($material_type)->filter(function($item, $key) use ($m_type){
                                      return $item['MATERIAL_TYPE'] == $m_type;
                                  })->first();
                                  $mat_group = $is_data_available[0]['MATERIAL_GROUP'].' - '.$is_data_available[0]['MATERIAL_GROUP_DESC'];
                               } else {
                                  $param = [
                                    'PLANT'=>$plant_code,
                                    'MATERIAL'=>$value['MATNR'],
                                    // 'P_SLOG'=>$sloc
                                  ];
                                  $function_type = $rfc->getFunction('BAPI_MATERIAL_GET_ALL');
                                  $invoke = $function_type->invoke($param, $options);
                                  $mat_group_material = isset($invoke['CLIENTDATA']['MATL_GROUP']) ? $invoke['CLIENTDATA']['MATL_GROUP'] : '';
                                  $m_type = isset($invoke['CLIENTDATA']['MATL_TYPE']) ? $invoke['CLIENTDATA']['MATL_TYPE'] : '';
                                  $material_type_search = collect($material_type)->filter(function($item, $key) use ($m_type){
                                      return $item['MATERIAL_TYPE'] == $m_type;
                                  })->first();
                                  $mat_group = collect($material_group_master)->filter(function($item, $key) use ($mat_group_material){
                                    return $item['MATKL'] == $mat_group_material;
                                  })->first();
                                  if($mat_group)
                                    $mat_group = $mat_group['MATKL'].' - '.$mat_group['WGBEZ'];
                               }
                            }

                           $data_material[] = array("id"=>isset($value['MATNR']) ? $value['MATNR'] : '', "text"=>isset($value['MAKTX']) ? $value['MAKTX'] : 'Unknown Material', "html"=>isset($value['MAKTX']) ? "<div>".$value['MAKTX']."</div>" : '<div>Unknown Material</div>', 'unit'=>isset($value['MEINS']) ? $value['MEINS'] : '', 'title'=>isset($value['MAKTX']) ? $value['MAKTX'] : 'Unknown Material', 'mat_group'=>$mat_group, 'mat_type'=>$material_type_search, 'is_recipe'=>$is_recipe);

                        } catch(SAPFunctionException $e){
                            $message = isset($e->getErrorInfo()['message']) ? $e->getErrorInfo()['message'] : $e->getMessage();
                            Log::error($message);
                        }
                    }
                    return response()->json($data_material);
                }

                else if($request->get('type', false) && strtolower($request->get('type')) == 'material_last_price'){
                    $return_data = ['status'=>'no_status', 'last_price'=>0, 'last_price_plain'=>0];
                    try {
                        $material = $request->get('material', '0000');
                        $unit = $request->get('unit', 'PC');
                        $qty = $request->get('qty', 1);
                        $plant_code = $request->get('plant');

                        if(substr($material, 9, 2) == '31'){
                           $param = json_encode(array('WERKS'=>$plant_code, 'MATNR'=>$material, 'RSTAT'=>'', 'AEDAT'=>''));
                           $param=urlencode($param);
                           $url = config('intranet.RECIPE_COST_URL')."".$param;
                           $client = new \GuzzleHttp\Client();
                           $header = ['headers' => []];
                           $res = $client->get($url, $header);
                           $data_cost = json_decode($res->getBody()->getContents(), true);
                           $last_price = isset($data_cost[0]['TCOST']) ? $data_cost[0]['TCOST'] : 0;
                           $last_price = $last_price * $qty;
                        } else {
                            $param = array(
                                'WERKS'=>$plant_code,
                                'IT_MATERIAL'=>array(
                                    [
                                        'MATNR'=>$material,
                                        'MEINS'=>$unit,
                                        'MENGE'=>(float)$qty
                                    ]
                                )
                            );
                            $function_type = $rfc->getFunction('YFM_GET_MAT_COST_3');
                            $last_price= $function_type->invoke($param, $options);
                            $last_price = isset($last_price['MAT_PRICE'][0]['DMBTR']) ? $last_price['MAT_PRICE'][0]['DMBTR'] : 0;
                        }
                        $return_data['status'] = 'success';
                        $return_data['last_price'] = number_format($last_price, 2);
                        $return_data['last_price_plain'] = $last_price;
                    } catch(SAPFunctionException $e){
                        Log::error($e);
                        $return_data['status'] = 'failed';
                        $return_data['error'] = $e->getMessage();
                        return response()->json($return_data, 405);
                    } catch(\Exception $e){
                        $return_data['status'] = 'failed';
                        $return_data['error'] = $e->getMessage();
                        return response()->json($return_data, 403);
                    }
                    return response()->json($return_data, 200);
                }

                else if($request->get('type', false) && strtolower($request->get('type')) == 'sloc'){
                    $sap_sloc = [];
                    try {
                        $param_sloc = [
                            'P_COMPANY'=>"",
                            'P_PLANT'=>$request->get('plant_code', false)
                        ];

                        $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
                        $result= $function->invoke($param_sloc, $options);
                        $sap_sloc = isset($result['IT_SLOC']) ? $result['IT_SLOC'] : [];
                        return response()->json(['status'=>'success', 'sloc'=>$sap_sloc], 200);
                    } catch(SAPFunctionException $e){
                        Log::error('SLOC RESERVATION REQUEST SAP ERROR | '. $e->getMessage());
                        return response()->json(['status'=>'error', 'error'=>$e->getMessage()], 402);
                    } catch(\Exception $e){
                        Log::error('SLOC RESERVATION REQUEST GENERAL ERROR | '. $e->getMessage());
                        return response()->json(['status'=>'error', 'error'=>$e->getMessage()], 402);
                    }
                }

                else if($request->get('type', false) && strtolower($request->get('type')) == 'cost_center'){
                    $plant = $request->get('plant_lookup');
                    $data_cost_center = CostCenter::whereRaw("LEFT(SAP_COST_CENTER_NAME, 4) = ?", [$plant])
                    ->select('SAP_COST_CENTER_ID', 'SAP_COST_CENTER_DESCRIPTION')->get()->toArray();
                    if(count($data_cost_center)){
                        return response()->json(['status'=>'success', 'data'=>$data_cost_center, 'message'=>'Success loading data'], 200);
                    }
                    else {
                        return response()->json(['status'=>'success', 'data'=>$data_cost_center, 'message'=>sprintf('No Cost Center found for Plant %s', $plant)], 400);
                    }
                }
            }
            // End Try
            catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>sprintf('Something went wrong | %s', $e->getMessage())], 400);
            }
        }

        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $cost_center_name = "SYSADMIN";
            $job_title ="SYSADMIN";
            $midjob_id ="SYSADMIN";
        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $cost_center_name = Session::get('assignment')[0]->SAP_COST_CENTER_DESCRIPTION;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
            $midjob_id =Session::get('assignment')[0]->MIDJOB_TITLE_ID;
        }

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        // START -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST
        $cek_alur_approval=DB::connection('dbintranet')
        ->table('INT_FORM_APPROVAL_MAPPING')
        ->where('FORMAPPROVAL_REQUESTOR_COSTCENTER', $cost_center_id)
        ->where('FORMAPPROVAL_REQUESTOR_MIDJOB', $midjob_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'RSV')
        ->orWhere('FORMAPPROVAL_REQUESTOR_EMPLOYEE_ID',$employee_id)
        ->get();
        
        $itung_alur_approval=count($cek_alur_approval);
        $allow_add_request=($itung_alur_approval>0)? true : false;
        $check_cross_plant_user = DB::connection('dbintranet')->table('INT_FORM_APPROVAL_MAPPING_CROSS_USER')->where('EMPLOYEE_ID',$employee_id)->where('TYPE_FORM','RSV')->count();
        $is_cross_plant_user = ($check_cross_plant_user>0)? true : false;
        // END -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST

        $plant_list = [];
        $check_custom_plant = self::customPlantAccess();
        if(count($check_custom_plant)){
            $plant_list = DB::connection('dbintranet')
            ->table('dbo.INT_BUSINESS_PLANT')
            ->whereIn('SAP_PLANT_ID', $check_custom_plant)->get()->pluck('SAP_PLANT_NAME','SAP_PLANT_ID')
            ->toArray();
        } else {
            $cek_permission_view_all_pnl = 'view_'. (String)route('sap.add-recipe.request',[],false);
            if(isset($request->session()->get('user_data')->IS_SUPERUSER) && $request->session()->get('user_data')->IS_SUPERUSER || session()->get('permission_menu')->has($cek_permission_view_all_pnl)){
                $plant_list = DB::connection('dbintranet')
                ->table('dbo.INT_BUSINESS_PLANT')
                ->get()->pluck('SAP_PLANT_NAME', 'SAP_PLANT_ID')
                ->toArray();

            } else {
                $user_id = isset(session()->get('user_data')->EMPLOYEE_ID) ? session()->get('user_data')->EMPLOYEE_ID : '';
                $plant_list = DB::connection('dbintranet')
                ->table('dbo.VIEW_EMPLOYEE_ACCESS')
                ->where('EMPLOYEE_ID', $user_id)
                ->select('SAP_PLANT_ID', 'COMPANY_CODE', 'SAP_PLANT_NAME')
                ->get()->pluck('SAP_PLANT_NAME', 'SAP_PLANT_ID')->filter()->toArray();
            }
        }

        $material_uom = [];
        try {
            // $param = array();
            // $function_measurement = $rfc->getFunction('ZFM_MD_UOM_LIST');
            // $material_uom = $function_measurement->invoke($param, $options)['IT_DATA'];
            $material_uom = self::generalUnitMeasurement();
        } catch(SAPFunctionException $e){}

        $data=array(
            'company_code'=>$company_code,
            'plant'=>$plant,
            'plant_list'=>$plant_list,
            'plant_name'=>$plant_name,
            'employee_id'=>$employee_id,
            'employee_name'=>$employee_name,
            'division'=>$division,
            'department'=>$department,
            'cost_center_id'=>$cost_center_id,
            'cost_center_name'=>$cost_center_name,
            'status'=>$status,
            'job_title'=>$job_title,
            'territory_id'=>$territory_id,
            'territory_name'=>$territory_name,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
            'form_code'=>$this->form_number,
            'allow_add_request'=>$allow_add_request,
            'is_cross_plant_user'=>$is_cross_plant_user,
            'material_unit'=>$material_uom
        );

        return view('pages.finance.add-recipe.request', ['data'=>$data]);
    }
}
