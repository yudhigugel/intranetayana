<?php

namespace App\Http\Controllers\SAP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\IntranetTrait;
use App\Models\HumanResource\CostCenterModel as CostCenter;
use Log;
use Cookie;
use DataTables;

class MenuEngineering extends Controller{
    use IntranetTrait;

    function index(Request $request){
        $filtered = [];
        $plant = NULL;
        $cost_center = NULL;
        $date_start = date('Y-m-01');
        $date_end = date('Y-m-d');
        $data_menu = [];

        if($request->get('plant') && $request->get('daterange') && $request->get('cost_center')){
            $plant = $request->get('plant');
            $cost_center = $request->get('cost_center');
            $date = explode('-', $request->get('daterange'));
            $date_start = isset($date[0]) ? date('Y-m-d', strtotime(trim($date[0]))) : date('Y-m-01');
            $date_end = isset($date[1]) ? date('Y-m-d', strtotime(trim($date[1]))) : date('Y-m-d');
            $filtered = [
                'date_start'=>date('m/d/Y', strtotime($date_start)),
                'date_end'=>date('m/d/Y', strtotime($date_end)),
                'plant'=>$plant,
                'cost_center'=>$cost_center
            ];
        }

        // Lookup cost center
        try {
            if($request->ajax()){
                if($request->get('cost_center_lookup')){
                    $data_cost_center = [];
                    try {
                        $plant = $request->get('cost_center_lookup');
                        $data_cost_center = CostCenter::whereRaw("LEFT(SAP_COST_CENTER_NAME, 4) = ?", [$plant])
                        ->select('SAP_COST_CENTER_ID', 'SAP_COST_CENTER_DESCRIPTION')->get()->toArray();
                        if(count($data_cost_center)){
                            return response()->json(['status'=>'success', 'data'=>$data_cost_center, 'message'=>'Success loading data'], 200);
                        }
                        else {
                            return response()->json(['status'=>'success', 'data'=>$data_cost_center, 'message'=>sprintf('No Cost Center found for Plant %s', $plant)], 400);
                        }
                    } catch(\Exception $e){
                        Log::error('ERROR LOAD COST CENTER LIST PO | '.$e->getMessage());
                        return response()->json(['status'=>'failed', 'data'=>$data_cost_center, 'message'=>$e->getMessage()], 401);
                    }
                }

                // for datatable header
                if($request->get('table_header')){
                    $data_header = DB::connection('dbayana-stg')
                    ->select('EXEC dbo.MenuEngineering_Header ?, ?, ?, ?', array($date_start, $date_end, $cost_center, $plant));
                    return DataTables::of($data_header)->make(true);
                }

                if($request->get('table_detail')){
                    $data_detail = DB::connection('dbayana-stg')
                    ->select('EXEC dbo.MenuEngineering_Detail ?, ?, ?, ?', array($date_start, $date_end, $cost_center, $plant));
                    return DataTables::of($data_detail)->make(true);
                }
            }
        } catch(\Exception $e){
            return response()->json(['status'=>'failed', 'data'=>[], 'message'=>$e->getMessage()], 401);
        }

        $check_custom_plant = self::customPlantAccess();
        if(count($check_custom_plant)){
            $plant_list = DB::connection('dbintranet')
            ->table('dbo.INT_BUSINESS_PLANT')
            ->whereIn('SAP_PLANT_ID', $check_custom_plant)->get()->pluck('SAP_PLANT_ID','SAP_PLANT_ID')
            ->toArray();
        } else {
            $cek_permission_view_all_pnl = 'view_'. (String)route('sap.menu-engineering.report',[],false);
            if(isset($request->session()->get('user_data')->IS_SUPERUSER) && $request->session()->get('user_data')->IS_SUPERUSER || session()->get('permission_menu')->has($cek_permission_view_all_pnl)){
                $plant_list = DB::connection('dbintranet')
                ->table('dbo.INT_BUSINESS_PLANT')
                ->get()->pluck('SAP_PLANT_ID', 'SAP_PLANT_ID')
                ->toArray();

            } else {
                $user_id = isset(session()->get('user_data')->EMPLOYEE_ID) ? session()->get('user_data')->EMPLOYEE_ID : '';
                $plant_list = DB::connection('dbintranet')
                ->table('dbo.VIEW_EMPLOYEE_ACCESS')
                ->where('EMPLOYEE_ID', $user_id)
                ->select('SAP_PLANT_ID', 'COMPANY_CODE')
                ->get()->pluck('SAP_PLANT_ID', 'SAP_PLANT_ID')->filter()->toArray();
            }
        }

        try {
            $plant = DB::connection('dbintranet')
            ->table('dbo.INT_BUSINESS_PLANT')
            ->where('SAP_PLANT_ID', $plant)
            ->select('SAP_PLANT_NAME')
            ->get()->first();
            if(!$plant)
                $plant = 'Unknown Plant Name';
            else
                $plant = $plant->SAP_PLANT_NAME;
        } catch(\Exception $e){}

        try {
            $cost_center = DB::connection('dbintranet')
            ->table('dbo.INT_SAP_COST_CENTER')
            ->where('SAP_COST_CENTER_ID', $cost_center)
            ->select('SAP_COST_CENTER_DESCRIPTION')
            ->get()->first();
            if(!$cost_center)
                $cost_center = 'Unknown Cost Center Name';
            else 
                $cost_center = $cost_center->SAP_COST_CENTER_DESCRIPTION;
        } catch(\Exception $e){}
        
        return view('pages.report.menu_engineering.index', 
        [
            'data_menu'=>$data_menu, 
            'data_plant'=>$plant_list,
            'plant_name'=>$plant,
            'cost_center_name'=>$cost_center,
            'filtered'=>$filtered, 
            'date_start'=>$date_start,
            'date_end'=>$date_end
        ]);
        
    }
}
