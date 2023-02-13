<?php

namespace App\Http\Controllers\AccessManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanResource\PermissionModel as Permission;
use App\Models\HumanResource\UserPermission as UserHasPermission;
use App\Models\HumanResource\MasterMenu as DataMenu;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Log;
use DataTables;

class UserPermission extends Controller
{
    protected $iterable = 0;
    function index(Request $request){
    	return view('pages.access_management.user_permission.list_permission');
    }

    function getData(Request $request){
        try{  
            $data_master = DB::connection('dbintranet')
            ->select('SELECT DISTINCT uhs.PERMISSION_ID, pm.PERMISSION_NAME, uhs.EMPLOYEE_ID, emp.EMPLOYEE_NAME, uhs.MENU_ID, mnu.MENU_NAME FROM dbo.INT_USER_HAS_PERMISSION uhs
                INNER JOIN dbo.INT_EMPLOYEE emp ON uhs.EMPLOYEE_ID = emp.SEQ_ID
                INNER JOIN dbo.INT_PERMISSIONS pm ON uhs.PERMISSION_ID = pm.SEQ_ID
                INNER JOIN dbo.INT_MASTER_MENU mnu ON uhs.MENU_ID = mnu.SEQ_ID');

            $data_master = collect($data_master)->groupBy('EMPLOYEE_ID');
            $data_new_master = $data_master->mapWithKeys(function($item, $key) {
                // Group Item By Permission ID
                $employee_name = isset($item[0]->EMPLOYEE_NAME) ? $item[0]->EMPLOYEE_NAME : 'Unknown';
                $employee_id = isset($item[0]->EMPLOYEE_ID) ? $item[0]->EMPLOYEE_ID : 0;
                $new_item = $item->groupBy('PERMISSION_NAME')->mapWithKeys(function($item, $keys){
                    return [$keys=>count($item)];
                });

                return [$key=>array(
                    'EMPLOYEE_ID'=>$employee_id,
                    'EMPLOYEE_NAME'=>$employee_name,
                    'DATA_PERMISSION'=>$new_item
                )];
            });

            // dd($data_new_master);

            return DataTables::of($data_new_master)
            ->addColumn('NUM_ORDER', function ($json) {
                $this->iterable = $this->iterable + 1;
                return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
            })
            ->addColumn('PERMISSION', function ($json) {
                $data_permission = '';
                foreach($json['DATA_PERMISSION'] as $key => $permission_menu){
                    $data_permission .= "<div class='d-block' style='cursor:pointer'><span class='mb-1 mr-1'>".strtoupper($key)."</span><span class='text-muted'>(".$permission_menu.")</span></div>";
                }
                return $data_permission;
            })
            ->addColumn('ACTION', function ($json) {                
                $type_button = 'btn-danger text-white';
                $menu_icon = 'mdi mdi-lock';
                $action = '';

                if(Session::get('permission_menu')->has("update_".route('userpermission.list', array(), false)))
                    $action .= '<a href="'.route('userpermission.update', $json['EMPLOYEE_ID']).'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-edit"><i class="mdi mdi-pencil"></i></a>';

                if(Session::get('permission_menu')->has("delete_".route('userpermission.list', array(), false)))
                    $action .= '<a href="#" data-url-delete="'.route('userpermission.delete', [$json['EMPLOYEE_ID']]).'" class="btn pl-2 pr-2 '.$type_button.' ml-1 mr-1 btn-delete"><i class="'.$menu_icon.'"></i></a>';

                if(!$action)
                    $action = '<small class="text-muted">NO ACCESS</small>';
                
                return $action; 
            })
            ->rawColumns(['ACTION', 'NUM_ORDER', 'ASSIGNED_ROLE_TO', 'STATUS_MENU', 'PERMISSION_CONCAT', 'PERMISSION'])
            ->make(true);

        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    function add_permission(){
    	$data_permission = Permission::all();

    	$menu_list = DataMenu::select('SEQ_ID', 'MENU_NAME', 'PATH')
    	->where(['IS_ACTIVE'=>1])
    	->where('PARENT_ID', '>', 0)
        ->whereNotIn('PATH', ['#'])
    	->get();

    	$user_list = DB::connection('dbintranet')
        ->table('dbo.VIEW_EMPLOYEE_ACCESS')
        ->select('SEQ_ID', 'EMPLOYEE_ID', 'EMPLOYEE_NAME')
        ->distinct()
        ->get();

    	return view('pages.access_management.user_permission.add_permission', [
    		'data_permission'=>$data_permission, 
    		'data_menu'=>$menu_list,
    		'data_user'=>$user_list
    	]);
    }

    function create(Request $request){
    	$validated = $request->validate([
            'permission' => 'required|array|min:1',
            'permission.*' => 'required|distinct',
            'user_selected' => 'required|array|min:1',
            'user_selected.*' => 'required|distinct',
            'menu_selected' => 'required|array|min:1',
            'menu_selected.*' => 'required|distinct',
        ]);

    	$status = null;
       	$permission = $request->post('permission', []);
       	$user_selected = $request->post('user_selected', []);
       	$menu_selected = $request->post('menu_selected', []);
       	try {
       		$data_to_insert = [];
	       	if($permission && $user_selected && $menu_selected){
	       		foreach($permission as $pid){
	       			foreach($user_selected as $user){
	       				foreach($menu_selected as $menu){
	       					$data_user = UserHasPermission::where(['EMPLOYEE_ID'=>$user, 'PERMISSION_ID'=>$pid, 'MENU_ID'=>$menu])->first();
	       					if(!$data_user){
		       					$data_to_insert[] =  array(
		       						'MENU_ID' => $menu,
		       						'PERMISSION_ID' => $pid,
		       						'EMPLOYEE_ID' => $user
		       					);
		       				}
	       				}
	       			}
	       		}

	       		if($data_to_insert) {
	       			$data_insert = UserHasPermission::insert($data_to_insert);
                	$status = array('msg'=>"Permission has been successfully added", 'type'=>'success');
	       		}
	       		else{
                	$status = array('msg'=>"No data to insert, already assigned or please try again", 'type'=>'info');
	       		}
	       	}
	       	else {
                $status = array('msg'=>"Permission, User, and Menu have to be selected at least one", 'type'=>'info');
	       	}

	    } catch(\Exception $e){
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
        }

        return redirect()->route('userpermission.list')->with('message', $status);
    }


    function update(Request $request, $id){
        if($request->method() == 'GET'){
            $cek_data = UserHasPermission::where('EMPLOYEE_ID', $id)->first();
            if(!$cek_data){
                $status = 'No Permission found with this associated User';
                return redirect()->route('userpermission.list')->with('message', $status);
            }

            $data_permission = Permission::all();
            $selected_menu = UserHasPermission::where('EMPLOYEE_ID', $id)->pluck('MENU_ID')->toArray();
            $selected_permission = UserHasPermission::where('EMPLOYEE_ID', $id)->pluck('PERMISSION_ID', 'PERMISSION_ID')->toArray();

            $menu_list = DataMenu::select('SEQ_ID', 'MENU_NAME', 'PATH')
            ->where(['IS_ACTIVE'=>1])
            ->where('PARENT_ID', '>', 0)
            ->whereNotIn('PATH', ['#'])
            ->get();

            $user_list = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE_ACCESS')
            ->select('SEQ_ID', 'EMPLOYEE_ID', 'EMPLOYEE_NAME')
            ->distinct()
            ->where('SEQ_ID', $id)
            ->get();

            return view('pages.access_management.user_permission.edit_permission', [
                'selected_menu'=>$selected_menu,
                'selected_permission'=>$selected_permission,
                'data_permission'=>$data_permission,
                'data_menu'=>$menu_list,
                'data_user'=>$user_list
            ]);
        }
        else if($request->method() == 'POST'){
            $validated = $request->validate([
                'permission' => 'required|array|min:1',
                'permission.*' => 'required|distinct',
                'user_selected' => 'required|array|min:1',
                'user_selected.*' => 'required|distinct',
                'menu_selected' => 'required|array|min:1',
                'menu_selected.*' => 'required|distinct',
            ]);

            $status = null;
            $permission = $request->post('permission', []);
            $user_selected = $request->post('user_selected', []);
            $menu_selected = $request->post('menu_selected', []);
            try {
                $data_to_insert = [];
                if($permission && $user_selected && $menu_selected){
                    foreach($permission as $pid){
                        foreach($user_selected as $user){
                            foreach($menu_selected as $menu){
                                $data_to_insert[] =  array(
                                    'MENU_ID' => $menu,
                                    'PERMISSION_ID' => $pid,
                                    'EMPLOYEE_ID' => $user
                                );
                            }
                        }
                    }

                    if($data_to_insert) {
                        $sync_data = UserHasPermission::where(['EMPLOYEE_ID'=>$user])->delete();
                        $data_insert = UserHasPermission::insert($data_to_insert);
                        $status = array('msg'=>"Permission has been successfully updated", 'type'=>'success');
                    }
                    else{
                        $status = array('msg'=>"No data to insert, already assigned or please try again", 'type'=>'info');
                    }
                }
                else {
                    $status = array('msg'=>"Permission, User, and Menu have to be selected at least one", 'type'=>'info');
                }

            } catch(\Exception $e){
                Log::error($e->getMessage());
                $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
            }

            return redirect()->route('userpermission.list')->with('message', $status);
        }
    }

    function delete(Request $request, $id) {
    	$status = null;
    	try {
    		$data = UserHasPermission::where('EMPLOYEE_ID', $id)->first();
    		if($data) {
	    		$affected_row = UserHasPermission::where('EMPLOYEE_ID', $id)->delete();
		    	// If any affected row with changes
	    		if($affected_row)
					$status = array('msg'=>"Permission for Employee and Menu has been successfully deleted", 'type'=>'success');
				else 
					$status = array('msg'=>"No state has been changed / already changed", 'type'=>'info');
			}
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('userpermission.list')->with('message', $status);
    }
}
