<?php

namespace App\Http\Controllers\AccessManagement;

use App\Http\Controllers\Controller;
use App\Models\HumanResource\EmployeeModel as DataEmployee;
use App\Models\HumanResource\MasterRole as DataRole;
use App\Models\HumanResource\MasterMenuAccess as DataMenuAccess;
use App\Models\HumanResource\PermissionModel as DataPermission;
use App\Models\HumanResource\MenuAccessRole as DataAccessRole;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;
use DataTables;

class MenuAccessRole extends Controller
{
	protected $iterable = 0;

    function index(Request $request){
        return view('pages.access_management.master_role_access.list_role_access');
    }

    function getData(Request $request){
        try{  
            $data_master=DataAccessRole::join('INT_MASTER_MENU_ACCESS', function($join){
                $join->on('INT_ROLE_HAS_ACCESS.MENU_ACCESS_ID','=','INT_MASTER_MENU_ACCESS.SEQ_ID');
            })->join('INT_MASTER_ROLE', function($join){
                $join->on('INT_ROLE_HAS_ACCESS.ROLE_ID','=','INT_MASTER_ROLE.SEQ_ID');
            })->select('INT_ROLE_HAS_ACCESS.SEQ_ID', 'INT_ROLE_HAS_ACCESS.REFER_TO_ID', 'INT_ROLE_HAS_ACCESS.MENU_ACCESS_ID', 'INT_MASTER_MENU_ACCESS.MENU_ACCESS_NAME', 'INT_MASTER_ROLE.ROLE_NAME', DB::raw("CONCAT(MENU_ACCESS_ID,'', ROLE_ID) AS GROUPKEY"), 'INT_MASTER_ROLE.SEQ_ID AS ROLE_ID','INT_ROLE_HAS_ACCESS.IS_ACTIVE')
            ->get()->groupBy('GROUPKEY');

            $data_new_master = $data_master->mapWithKeys(function($data, $key){
                $access_name = isset($data[0]['MENU_ACCESS_NAME']) ? $data[0]['MENU_ACCESS_NAME'] : 'Unknown Access';
                $access_id = isset($data[0]['MENU_ACCESS_ID']) ? $data[0]['MENU_ACCESS_ID'] : 0;
                $role_name = isset($data[0]['ROLE_NAME']) ? $data[0]['ROLE_NAME'] : '';
                $role_id = isset($data[0]['ROLE_ID']) ? $data[0]['ROLE_ID'] : '';

                $menu_active = $data->filter(function($item, $key){
                    return $item->IS_ACTIVE > 0;
                })->values();

                return [$key=>array('MENU_ACCESS_NAME'=>$access_name, 'MENU_ACCESS_ID'=>$access_id, 'DATA_REFER_COUNT'=>count($data), 'DATA'=>$data, 'ROLE_NAME'=>$role_name, 'ROLE_ID'=>$role_id, 'ACTIVE_MENU'=>count($menu_active))];
            });


            return DataTables::of($data_new_master)
            ->addColumn('NUM_ORDER', function ($json) {
                $this->iterable = $this->iterable + 1;
                return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
            })
            ->addColumn('ASSIGNED_ROLE_TO', function ($json) {
                return "<a class='text-primary' style='cursor:pointer'>".$json['DATA_REFER_COUNT']."</a>";
            })
            ->addColumn('ACTION', function ($json) {                
                if($json['ACTIVE_MENU']) {
                    $type_button = 'btn-danger text-white';
                    $menu_icon = 'mdi mdi-lock';
                }
                else {
                    $type_button = 'btn-success text-white';
                    $menu_icon = 'mdi mdi-lock-open';
                }

                $action = '';
                if(Session::get('permission_menu')->has("update_".route('masteraccessrole.list', array(), false))){
                    $action .= '<a href="'.route('masteraccessrole.update', [$json['MENU_ACCESS_ID'], $json['ROLE_ID']]).'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-edit"><i class="mdi mdi-pencil"></i></a>';
                    $action .= '<a href="javascript:void(0)" data-url-delete="'.route('masteraccessrole.delete', [$json['MENU_ACCESS_ID'], $json['ROLE_ID']]).'" class="btn pl-2 pr-2 '.$type_button.' ml-1 mr-1 btn-delete"><i class="'.$menu_icon.'"></i></a>';
                }

                /* Dont need delete right now */
                // if(Session::get('permission_menu')->has("delete_".route('userpermission.list', array(), false)))
                //     $action = '<a href="#" data-url-delete="'.route('userpermission.delete', [$json['EMPLOYEE_ID']]).'" class="btn pl-2 pr-2 '.$type_button.' ml-1 mr-1 btn-delete"><i class="'.$menu_icon.'"></i></a>';
                if(!$action)
                    $action = '<small class="text-muted">NO ACCESS</small>';

                return $action; 
            })
            ->addColumn('STATUS_MENU', function ($json) {
                if(isset($json['ACTIVE_MENU'])){
                    if($json['ACTIVE_MENU'])
                        return 'Active';
                    else
                        return '<span class="text-danger">Not Active</span>';
                }
                else
                    return "Unknown";
            })
            ->rawColumns(['ACTION', 'NUM_ORDER', 'ASSIGNED_ROLE_TO', 'STATUS_MENU'])
            ->make(true);

        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

	function add_accessrole(Request $request){
    	$data_role = DataRole::where('STATUS', 1)->get();
    	$data_access = DataMenuAccess::where(['STATUS'=>1])->get();
        $data_permission = DataPermission::all();
        if($request->ajax()){
            try {
            $access_lookup = $request->post('access_type', 0);
            $lookup_param = array('STATUS'=>1, 'SEQ_ID'=>$access_lookup);
            $data_access = DataMenuAccess::where($lookup_param)->get();
        	$data_new_access = $data_access->map(function($data, $key){
                switch (str_replace(" ", '', strtolower($data->MENU_ACCESS_NAME))) {
                    case 'plant':
                        $plant = DB::connection('dbintranet')
                        ->table('dbo.INT_BUSINESS_PLANT')
                        ->select('SAP_PLANT_ID AS SEQ_ID', 'SAP_PLANT_ID AS TITLE', 'SAP_PLANT_NAME AS DESCRIPTION')
                        ->distinct()
                        ->get();
                        $data_bind_access = $plant->toArray();
                        break;
                    case 'territory':
                        $territory = DB::connection('dbintranet')
                        ->table('dbo.INT_TERRITORY')
                        ->select('TERRITORY_ID AS SEQ_ID', DB::raw("SAP_PLANT_ID AS TITLE"), 'TERRITORY_NAME AS DESCRIPTION')
                        ->distinct()
                        ->get();
                        $data_bind_access = $territory->toArray();
                        break;
                    case 'employee':
                        $employee = DB::connection('dbintranet')
                        ->table('dbo.VIEW_EMPLOYEE_ACCESS')
                        ->select('SEQ_ID', 'EMPLOYEE_ID AS TITLE', 'EMPLOYEE_NAME AS DESCRIPTION')
                        ->distinct()
                        ->get();
                        $data_bind_access = $employee->toArray();
                        break;
                    case 'department':
                        $department = DB::connection('dbintranet')
                        ->table('dbo.INT_MASTER_DEPARTMENT')
                        ->select('SEQ_ID', 'DEPARTMENT_NAME AS TITLE')
                        ->distinct()
                        ->get();
                        $data_bind_access = $department->toArray();
                        break;
                    case 'division':
                        $division = DB::connection('dbintranet')
                        ->table('dbo.INT_MASTER_DIVISION')
                        ->select('SEQ_ID', 'DEPARTMENT_NAME AS TITLE', 'DIVISION_NAME AS DESCRIPTION')
                        ->distinct()
                        ->get();
                        $data_bind_access = $division->toArray();
                        break;
                    case 'midjob':
                        $midjob = DB::connection('dbintranet')
                        ->table('dbo.INT_MIDJOB_GRADING')
                        ->select('MIDJOB_TITLE_ID AS SEQ_ID', 'MIDJOB_TITLE_NAME AS TITLE')
                        ->distinct()
                        ->get();
                        $data_bind_access = $midjob->toArray();
                        break;
                    case 'companycode':
                        $company = DB::connection('dbintranet')
                        ->table('dbo.INT_COMPANY')
                        ->select('COMPANY_CODE AS SEQ_ID', DB::raw("CONCAT(COMPANY_CODE, ' - ', COMPANY_NAME) AS TITLE"))
                        ->distinct()
                        ->get();
                        $data_bind_access = $company->toArray();
                        break;
                    case 'costcenter': case 'sapcostcenter':
                        $cost_center = DB::connection('dbintranet')
                        ->table('dbo.INT_SAP_COST_CENTER')
                        ->select('SAP_COST_CENTER_ID AS SEQ_ID', DB::raw("CONCAT(SAP_COST_CENTER_ID, ' - ', SAP_COST_CENTER_DESCRIPTION) AS TITLE"))
                        ->distinct()
                        ->get();
                        $data_bind_access = $cost_center->toArray();
                        break;
                    default:
                        $data_bind_access = [];
                        break;
                }
                return $data_bind_access;
        	});
                return response()->json(['status'=>'success', 'message'=>'Data is retrieved', 'data'=>$data_new_access], 200);
            } catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>'Error occured, '.$e->getMessage()], 500);
            }
        }
   		return view('pages.access_management.master_role_access.add_role_access', [
            'data_role'=>$data_role,
            'data_access'=>$data_access,
            'data_permission'=>$data_permission
        ]);
    }

    function create(Request $request){
        // dd($request->post());
        try {
            $validated = $request->validate([
                'access' => 'required',
                'access_selected' => 'required|array|min:1',
                'access_selected.*' => 'required|distinct',
                'role'=> 'required'
            ]);
            $status = null;

            $data_to_insert = [];
            $access_selected = $request->post('access_selected');
            $access_type = $request->post('access');
            $role_selected = $request->post('role');

            $cekDataAccessRole = DataAccessRole::where(['MENU_ACCESS_ID'=>$access_type, 'ROLE_ID'=>$role_selected])->pluck('REFER_TO_ID')->toArray();
            if(is_array($access_selected)){
                foreach($access_selected as $key => $ac){
                    if(!in_array($ac, $cekDataAccessRole)){
                        $data_to_insert[] = [
                            'REFER_TO_ID'   => $ac,
                            'MENU_ACCESS_ID'   => $access_type,
                            'ROLE_ID'   => $role_selected
                        ];
                    }
                }
            }
            else 
                throw new \Exception('Selected access must be type of array, Plain text given.');

            if($data_to_insert){
                $insert_data = DB::connection('dbintranet')->table('INT_ROLE_HAS_ACCESS')->insert($data_to_insert);
                $status = array('msg'=>"Role Has Access has been successfully added", 'type'=>'success');
            } else {
                $status = array('msg'=>"No data to insert, already assigned or please try again", 'type'=>'info');
            }

        } catch(\Exception $e){
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
        }

        return redirect()->route('masteraccessrole.list')->with('message', $status);
    }

    function delete(Request $request, $id_menu_access, $id_role) {
        $status = null;
        try {
            $data = DataAccessRole::where(['MENU_ACCESS_ID'=>$id_menu_access, 'ROLE_ID'=>$id_role]);
            $affected_row = 0;
            if($data) {
                $data_access = $data->get();
                $length_access = count($data_access);
                if($length_access){
                    $check_active = isset($data_access[0]->IS_ACTIVE) && $data_access[0]->IS_ACTIVE ? $data_access[0]->IS_ACTIVE : 0;
                    switch ($check_active) {
                        case '0':
                            $check_active = 1;
                            break;
                        case '1':
                            $check_active = 0;
                            break;
                        default:
                            $check_active = null;
                            break;
                    }
                    DataAccessRole::where(['MENU_ACCESS_ID'=>$id_menu_access, 'ROLE_ID'=>$id_role])
                    ->update(['IS_ACTIVE'=>$check_active]);
                    $affected_row++;
                }
            }

            // If any affected row with changes
            if($affected_row)
                $status = array('msg'=>"Access Role state has been successfully changed", 'type'=>'success');
            else 
                $status = array('msg'=>"No state has been changed / already changed", 'type'=>'info');
        } catch(\Exception $e){
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
        }

        return redirect()->route('masteraccessrole.list')->with('message', $status);
    }

    function remove(Request $request, $id_menu_access, $id_role) {
        $status = null;
        try {
            $data = DataAccessRole::where(['MENU_ACCESS_ID'=>$id_menu_access, 'ROLE_ID'=>$id_role]);
            $affected_row = 0;
            if($data) {
                $data_access = DataAccessRole::where(['MENU_ACCESS_ID'=>$id_menu_access, 'ROLE_ID'=>$id_role])->get();
                foreach ($data_access as $key => $value) {
                    // if($value->IS_ACTIVE == 1)
                    //     $value->IS_ACTIVE = 0;
                    // else
                    //     $value->IS_ACTIVE = 1;
                    $value->delete();
                    $affected_row++;
                }
            }

            // If any affected row with changes
            if($affected_row)
                $status = array('msg'=>"Access Role has been successfully deleted", 'type'=>'success');
            else 
                $status = array('msg'=>"No Access Role affected, please try again", 'type'=>'info');
        } catch(\Exception $e){
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
        }

        return redirect()->route('masteraccessrole.list')->with('message', $status);
    }

    function update(Request $request, $menu_access_id, $id_role){
        $status = null;
        try {
            if($request->method() == 'GET'){
                $data_access = DataMenuAccess::where('SEQ_ID', $menu_access_id)->get();
                if($data_access->count() < 1)
                    throw new \Exception("Cannot find data with provided ID");

                // Populate prior selected data
                $data_role_selected = DataAccessRole::where(['MENU_ACCESS_ID'=>$menu_access_id, 'ROLE_ID'=>$id_role])->get();
                $data_referer_selected = $data_role_selected->pluck('REFER_TO_ID')->toArray();
                $data_menu_access_selected = $data_role_selected->pluck('MENU_ACCESS_ID')->first() ?? 0;
                $data_role_selected = $data_role_selected->pluck('ROLE_ID')->first() ?? 0;
                // Populate Role
                $data_role = DataRole::where('STATUS', 1)->get();

                return view('pages.access_management.master_role_access.edit_role_access', ['data_role'=>$data_role,'data_access'=>$data_access, 'role_selected'=>$data_role_selected, 'access_selected'=>$data_menu_access_selected, 'refer_to_id'=>json_encode($data_referer_selected)]);
            }
            else if($request->method() == 'POST'){
                $validated = $request->validate([
                    'access' => 'required',
                    'access_selected' => 'required|array|min:1',
                    'access_selected.*' => 'required|distinct',
                    'role'=> 'required'
                ]);
                $data_to_insert = [];
                $access_selected = $request->post('access_selected');
                $access_type = $request->post('access');
                $role_selected = $request->post('role');

                if($role_selected != $id_role){
                    $check_same_access_in_db = DataAccessRole::where(['MENU_ACCESS_ID'=>$access_type, 'ROLE_ID'=>$role_selected])->exists();
                    if($check_same_access_in_db){
                        $status = 'There is a data within the same access and role, cannot edit this access role';
                        return redirect()->route('masteraccessrole.update', [$menu_access_id, $id_role])->with('message', $status);
                    }
                }

                $cekDataAccessRole = DataAccessRole::where(['MENU_ACCESS_ID'=>$access_type, 'ROLE_ID'=>$role_selected])->pluck('REFER_TO_ID')->toArray();

                if(is_array($access_selected)){
                    foreach($access_selected as $key => $ac){
                        $data_to_insert[] = [
                            'REFER_TO_ID'   => $ac,
                            'MENU_ACCESS_ID'   => $access_type,
                            'ROLE_ID'   => $role_selected
                        ];
                    }
                }
                else 
                    throw new \Exception('Selected access must be type of array, Plain text given.');

                if($data_to_insert){
                    try {
                        DataAccessRole::where(['MENU_ACCESS_ID'=>$access_type, 'ROLE_ID'=>$role_selected])->delete();
                        DataAccessRole::where(['MENU_ACCESS_ID'=>$access_type, 'ROLE_ID'=>$id_role])->delete();
                    } catch(\Exception $e){}
                    $insert_data = DB::connection('dbintranet')->table('INT_ROLE_HAS_ACCESS')->insert($data_to_insert);
                    $status = array('msg'=>"Role Has Access has been successfully updated", 'type'=>'success');
                } else {
                    $status = array('msg'=>"No data to insert, already assigned or please try again", 'type'=>'info');
                }

            }

        } catch(\Exception $e){
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
        }

        return redirect()->route('masteraccessrole.list')->with('message', $status);
    }
}
