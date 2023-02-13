<?php

namespace App\Http\Controllers\AccessManagement;
use App\Models\HumanResource\MasterRole as DataRole;
use App\Models\HumanResource\MasterMenu as DataMenu;
use App\Models\HumanResource\RoleMenu as DataRoleMenu;
use App\Models\HumanResource\PermissionModel as Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Log;
use DataTables;


class RoleMenu extends Controller
{
	protected $iterable = 0;
    function index(Request $request){
    	return view('pages.access_management.master_rolemenu.list_rolemenu');
    }

    function getData(Request $request){
    	try{  
	        $data_master=DataRoleMenu::join('INT_MASTER_ROLE', function($join){
	        	$join->on('INT_ROLE_MENU.ROLE_ID','=','INT_MASTER_ROLE.SEQ_ID');
	        })->join('INT_MASTER_MENU', function($join){
	        	$join->on('INT_ROLE_MENU.MENU_ID','=','INT_MASTER_MENU.SEQ_ID');
	        })->select('INT_ROLE_MENU.ROLE_ID', 'INT_ROLE_MENU.IS_ACTIVE', 'INT_MASTER_ROLE.ROLE_NAME', 'INT_ROLE_MENU.MENU_ID', 'INT_MASTER_MENU.MENU_NAME')->get()->groupBy('ROLE_ID');

	        $data_new_master = $data_master->mapWithKeys(function($data, $key){
	        	$data_count = $data->toArray();
	        	$menu_active = $data->filter(function($item, $key){
	        		return $item->IS_ACTIVE > 0;
	        	})->values();
	        	$ROLE_NAME = isset($data_count[0]['ROLE_NAME']) ? $data_count[0]['ROLE_NAME'] : 'Unknown';
	        	$ROLE_ID = isset($data_count[0]['ROLE_ID']) ? $data_count[0]['ROLE_ID'] : 'Unknown';

	        	return [$key=>array('ROLE_ID'=>$ROLE_ID, 'ROLE_NAME'=>$ROLE_NAME, 'DATA'=>$data, 'ACTIVE_MENU'=>count($menu_active))];
	        });

	        return DataTables::of($data_new_master)
	        ->addColumn('NUM_ORDER', function ($json) {
                $this->iterable = $this->iterable + 1;
                return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
            })
            ->addColumn('MENU_AVAILABLE', function ($json) {
                return "<a class='text-primary' style='cursor:pointer'>".count($json['DATA'])."</a>";
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
           	->addColumn('ACTION', function ($json) {	        	
        		// $type_button = 'btn-danger text-white';
        		// $menu_icon = 'mdi mdi-lock';
        		if($json['ACTIVE_MENU']) {
	        		$type_button = 'btn-danger text-white';
	        		$menu_icon = 'mdi mdi-lock';
	        	}
	        	else {
	        		$type_button = 'btn-success text-white';
	        		$menu_icon = 'mdi mdi-lock-open';
	        	}
        		$action = '';
                if(Session::get('permission_menu')->has("update_".route('masterrolemenu.list', array(), false)))
                    $action .= '<a href="'.route('masterrolemenu.update', $json['ROLE_ID']).'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-edit"><i class="mdi mdi-pencil"></i></a>';

                if(Session::get('permission_menu')->has("delete_".route('masterrolemenu.list', array(), false)))
                    $action .= '<a href="#" data-url-delete="'.route('masterrolemenu.delete', $json['ROLE_ID']).'" class="btn pl-2 pr-2 '.$type_button.' ml-1 mr-1 btn-delete"><i class="'.$menu_icon.'"></i></a>';

                if(!$action)
                    $action = '<small class="text-muted">NO ACCESS</small>';

	        	return $action; 
            })
	        ->rawColumns(['ACTION', 'NUM_ORDER', 'MENU_AVAILABLE', 'STATUS_MENU'])
	        ->make(true);
	    }
	    catch(\Exception $e){
	        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
	    }
    }

    function add_rolemenu(Request $request){
    	$data_role = DataRole::where('STATUS', 1)->get();
    	$data_menu = DataMenu::where(['IS_PARENT_MENU'=>1,'IS_ACTIVE'=>1, 'PARENT_ID'=>0])->get();
    	$data_menu_map = $data_menu->mapWithKeys(function($data, $key){
    		$data_child_menu = DataMenu::where(['IS_PARENT_MENU'=>1, 'IS_ACTIVE'=>1, 'PARENT_ID'=>$data->SEQ_ID])->get();
    		$data_child_menu_map = $data_child_menu->mapWithKeys(function($obj, $key){
    			$data_child_lv_2_menu = DataMenu::where(['IS_PARENT_MENU'=>0, 'IS_ACTIVE'=>1, 'PARENT_ID'=>$obj->SEQ_ID])->get();
    			$data_child_lv_2_menu = $data_child_lv_2_menu->mapWithKeys(function($data_child, $key){
    				return [$key=>array('type'=>'CHILD_PARENT_CHILD','PARENT_ID'=>$data_child->PARENT_ID,'LEVEL_MENU'=>3,'SEQ_ID'=>$data_child->SEQ_ID, 'MENU_NAME'=>$data_child->MENU_NAME)];
    			});
    			return [$key=>array('type'=>'CHILD_PARENT','PARENT_ID'=>$obj->PARENT_ID,'LEVEL_MENU'=>2,'SEQ_ID'=>$obj->SEQ_ID, 'MENU_NAME'=>$obj->MENU_NAME, 'child'=>$data_child_lv_2_menu)];
    		});
    		return [$key=>array('type'=>'PARENT','LEVEL_MENU'=>1,'SEQ_ID'=>$data->SEQ_ID, 'MENU_NAME'=>$data->MENU_NAME, 'child'=>$data_child_menu_map)];
    	});
   		return view('pages.access_management.master_rolemenu.add_rolemenu', ['data_role'=>$data_role,'data_menu'=>$data_menu, 'data_menu_map'=>$data_menu_map]);
    }

    function add_rolemenu_permission(Request $request){
    	$data_permission = Permission::all();
    	$data_role = DataRole::where('STATUS', 1)->get();
    	$data_menu = DataMenu::where(['IS_PARENT_MENU'=>1,'IS_ACTIVE'=>1, 'PARENT_ID'=>0])->get();
    	$data_menu_map = $data_menu->mapWithKeys(function($data, $key){
    		$data_child_menu = DataMenu::where(['IS_PARENT_MENU'=>1, 'IS_ACTIVE'=>1, 'PARENT_ID'=>$data->SEQ_ID])->get();
    		$data_child_menu_map = $data_child_menu->mapWithKeys(function($obj, $key){
    			$data_child_lv_2_menu = DataMenu::where(['IS_PARENT_MENU'=>0, 'IS_ACTIVE'=>1, 'PARENT_ID'=>$obj->SEQ_ID])->get();
    			$data_child_lv_2_menu = $data_child_lv_2_menu->mapWithKeys(function($data_child, $key){
    				return [$key=>array('type'=>'CHILD_PARENT_CHILD','PARENT_ID'=>$data_child->PARENT_ID,'LEVEL_MENU'=>3,'SEQ_ID'=>$data_child->SEQ_ID, 'MENU_NAME'=>$data_child->MENU_NAME)];
    			});
    			return [$key=>array('type'=>'CHILD_PARENT','PARENT_ID'=>$obj->PARENT_ID,'LEVEL_MENU'=>2,'SEQ_ID'=>$obj->SEQ_ID, 'MENU_NAME'=>$obj->MENU_NAME, 'child'=>$data_child_lv_2_menu)];
    		});
    		return [$key=>array('type'=>'PARENT','LEVEL_MENU'=>1,'SEQ_ID'=>$data->SEQ_ID, 'MENU_NAME'=>$data->MENU_NAME, 'child'=>$data_child_menu_map, 'SORT'=>$data->MENU_SORT)];
    	})->sortBy('SORT');
   		return view('pages.access_management.role_permission.add_role_permission', ['data_role'=>$data_role,'data_menu'=>$data_menu, 'data_menu_map'=>$data_menu_map, 'data_permission'=>$data_permission]);
    }

    function create(Request $request){
    	$status=null;
    	try{
		    $data_to_insert = [];
		    if(!$request->post('role', false) || !$request->post('menu', false)){
		    	throw new \Exception("Role or Menu have to be selected at least one");
		    }

		    $role = $request->post('role', 0);
		    $data_role_menu = DataRoleMenu::where('ROLE_ID', $role)->pluck('MENU_ID')->toArray();

		    $menu = collect($request->post('menu', []));
		    $menu_map = $menu->filter(function($item, $data) use ($data_role_menu){
		    	return !in_array($item, $data_role_menu);
		    });

		    foreach ($menu_map as $menu_id) {
			    $data_to_insert[] = [
			        'ROLE_ID'	=> $role,
			        'MENU_ID'	=> $menu_id,
			    ];
			}
			
			if($data_to_insert){
				$insert_data = DB::connection('dbintranet')->table('INT_ROLE_MENU')->insert($data_to_insert);
				$status = array('msg'=>"Assign Role and Menu has been successfully added", 'type'=>'success');
			} else {
				$status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
			}

    	} catch(\Exception $e){
    		Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
    	}

    	return redirect()->route('masterrolemenu.list')->with('message', $status);
    }

    function create_rolemenu_permission(Request $request){
    	$status=null;
    	try {
	    	if(!$request->post('role', false) || !$request->post('menu', false)){
			    throw new \Exception("Role or Menu have to be selected at least one");
			}

		    $data_to_insert_role_menu = [];
		    $data_to_insert_role_permission = [];
			// Cek apakah role ini sudah punya menu tertentu
			$role = $request->post('role', 0);
		    $data_role_menu = DataRoleMenu::where('ROLE_ID', $role)->pluck('MENU_ID')->toArray();

		    // Mapping menu agar rapi saat insert
		    $menu = collect($request->post('menu', []));
		    $menu_map = $menu->filter(function($item, $data) use ($data_role_menu){
		    	return !in_array($item, $data_role_menu);
		    });

		    $permission = collect($request->post('permission', []));
		    $permission_map = $permission->groupBy(function($item, $key) {
		    	$separate_menu_permission = explode('.', $item);
		    	return isset($separate_menu_permission[0]) ? $separate_menu_permission[0] : [];
		    })->mapWithKeys(function($item, $key){
		    	$new_item = collect($item)->mapWithKeys(function($item_permission, $order){
		    		$permission_data = isset(explode('.', $item_permission)[1]) ? explode('.', $item_permission)[1] : '';
		    		return [$order=>$permission_data];	
		    	})->filter();
		    	return [$key=>$new_item];
		    })->toArray();

		    // Insert Role Menu
		    foreach ($menu_map as $menu_id) {
			    $data_to_insert_role_menu[] = [
			        'ROLE_ID'	=> $role,
			        'MENU_ID'	=> $menu_id,
			    ];
			}

			// Insert Role Permission
			foreach ($permission_map as $key => $permission_id) {
				foreach ($permission_id as $permission) {
					$data_to_insert_role_permission[] = [
				        'ROLE_ID'		=> $role,
				        'MENU_ID'		=> $key,
				        'PERMISSION_ID'	=> $permission
			    	];
				}
			}
			// dd($data_to_insert_role_menu, $data_to_insert_role_permission);
			if($data_to_insert_role_menu){
				$affected_row =  DB::connection('dbintranet')->transaction(function() use ($data_to_insert_role_menu, $data_to_insert_role_permission) {
					DB::connection('dbintranet')->table('INT_ROLE_MENU')->insert($data_to_insert_role_menu);
					DB::connection('dbintranet')->table('INT_ROLE_MENU_PERMISSION')->insert($data_to_insert_role_permission);
	    			return true;
	            }, 3);
				if($affected_row)
					$status = array('msg'=>"Assign Role, Menu, and Permission has been successfully added", 'type'=>'success');
				else 
					$status = array('msg'=>"Operation terminated since no data affected, please try again", 'type'=>'info');

			} else {
				$status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
			}

		} catch(\Exception $e){
    		Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
    	}

    	return redirect()->route('masterrolemenu.list')->with('message', $status);
    }

    function delete(Request $request, $id) {
    	$status = null;
    	try {
    		$data = DataRoleMenu::where('ROLE_ID', $id);
    		$affected_row = 0;
    		if($data) {
	    		$data_menu = DataRoleMenu::where('ROLE_ID', $id)->get();
	    		foreach ($data_menu as $key => $value) {
	    			if($value->IS_ACTIVE == 1)
	    				$value->IS_ACTIVE = 0;
	    			else
	    				$value->IS_ACTIVE = 1;
	    			$value->save();
	    			$affected_row++;
	    		}
	    	}

	    	// If any affected row with changes
    		if($affected_row)
				$status = array('msg'=>"RoleMenu state has been successfully changed", 'type'=>'success');
			else 
				$status = array('msg'=>"No state has been changed / already changed", 'type'=>'info');
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('masterrolemenu.list')->with('message', $status);
    }

    function remove(Request $request, $id) {
    	$status = null;
    	try {
    		$data = DataRoleMenu::where('ROLE_ID', $id)->get();
    		if(count($data)) {
		    	// If any affected row with changes
		    	$affected_row =  DB::connection('dbintranet')->transaction(function() use ($id) {
	                //DataRole::where('SEQ_ID', $id)
	    			//->delete();
	    			DataRoleMenu::where('ROLE_ID', $id)
	    			->delete();
	    			//DataAccessRole::where('ROLE_ID', $id)
	    			//->delete();
	    			DB::connection('dbintranet')->table('INT_ROLE_MENU_PERMISSION')
						->where('ROLE_ID', $id)->delete();
	    			return true;
	            }, 3);  
		    	
	    		if($affected_row)
					$status = array('msg'=>"RoleMenu has been successfully deleted", 'type'=>'success');
				else 
					$status = array('msg'=>"No rolemenu affected, please try again", 'type'=>'info');
			}
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('masterrolemenu.list')->with('message', $status);
    }

    function update(Request $request, $role_id){
    	$status = null;
    	try {
	    	if($request->method() == 'GET') {
	    		$check_role_menu = DataRoleMenu::where('ROLE_ID', $role_id)->get();
	    		if(!count($check_role_menu))
	    			throw new \Exception("Cannot find any Role ID saved");

	    		// Prepare all data menu
	    		$data_role = DataRole::where(['STATUS'=>1, 'SEQ_ID'=>$role_id])->get();
	    		if(!count($data_role)) {
	    			$status = array('msg'=>sprintf('Selected Group Role is not active thus cannot edit. Please enable it first'), 'type'=>'danger');
	    			return redirect()->route('masterrolemenu.list')->with('message', $status);
	    		}
		    	$data_menu = DataMenu::where(['IS_PARENT_MENU'=>1,'IS_ACTIVE'=>1, 'PARENT_ID'=>0])->get();
		    	$data_menu_map = $data_menu->mapWithKeys(function($data, $key){
		    		$data_child_menu = DataMenu::where(['IS_PARENT_MENU'=>1, 'IS_ACTIVE'=>1, 'PARENT_ID'=>$data->SEQ_ID])->get();
		    		$data_child_menu_map = $data_child_menu->mapWithKeys(function($obj, $key){
		    			$data_child_lv_2_menu = DataMenu::where(['IS_PARENT_MENU'=>0, 'IS_ACTIVE'=>1, 'PARENT_ID'=>$obj->SEQ_ID])->get();
		    			$data_child_lv_2_menu = $data_child_lv_2_menu->mapWithKeys(function($data_child, $key){
		    				return [$key=>array('type'=>'CHILD_PARENT_CHILD','PARENT_ID'=>$data_child->PARENT_ID,'LEVEL_MENU'=>3,'SEQ_ID'=>$data_child->SEQ_ID, 'MENU_NAME'=>$data_child->MENU_NAME)];
		    			});
		    			return [$key=>array('type'=>'CHILD_PARENT','PARENT_ID'=>$obj->PARENT_ID,'LEVEL_MENU'=>2,'SEQ_ID'=>$obj->SEQ_ID, 'MENU_NAME'=>$obj->MENU_NAME, 'child'=>$data_child_lv_2_menu)];
		    		});
		    		return [$key=>array('type'=>'PARENT','LEVEL_MENU'=>1,'SEQ_ID'=>$data->SEQ_ID, 'MENU_NAME'=>$data->MENU_NAME, 'child'=>$data_child_menu_map)];
		    	});

		    	// Pre Populate menu that has been assigned
	    		$data_menu_assigned = $check_role_menu->pluck('MENU_ID')->toArray();

	    		return view('pages.access_management.master_rolemenu.edit_rolemenu', ['data_role'=>$data_role,'data_menu'=>$data_menu, 'data_menu_map'=>$data_menu_map, 'assigned_rolemenu'=>$data_menu_assigned, 'role_selected'=>$role_id]);

	    	}
	    	else if($request->method() == 'POST'){
	    		$data_to_insert = [];
			    if(!$request->post('role', false) || !$request->post('menu', false)){
			    	throw new \Exception("Role or Menu have to be selected at least one");
			    }

			    $role = $request->post('role', 0);
			    // $data_role_menu = DataRoleMenu::where('ROLE_ID', $role)->pluck('MENU_ID')->toArray();

			    $menu = collect($request->post('menu', []));
			    // $menu_map = $menu->filter(function($item, $data) use ($data_role_menu){
			    // 	return !in_array($item, $data_role_menu);
			    // });

			    foreach ($menu as $menu_id) {
				    $data_to_insert[Str::random(5)] = array('MENU_ID'=>$menu_id);
				}

				// dd($data_to_insert);

				if($data_to_insert){
					$cek_role = DataRole::where('SEQ_ID', $role);
					$cek_role->first()->menu()->sync($data_to_insert);
					$status = array('msg'=>"Menu has been successfully updated", 'type'=>'success');
				}
				else {
					$status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
				}	    		
			}

		} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('masterrolemenu.list')->with('message', $status);

    }

    function update_rolemenu_permission(Request $request, $role_id){
    	$status = null;
    	try {
	    	if($request->method() == 'GET') {
	    		$check_role_menu = DataRoleMenu::where('ROLE_ID', $role_id)->get();
	    		if(!count($check_role_menu))
	    			throw new \Exception("Cannot find any Role ID saved");

	    		// Prepare all data menu
	    		$data_role = DataRole::where(['STATUS'=>1, 'SEQ_ID'=>$role_id])->get();
	    		if(!count($data_role)) {
	    			$status = array('msg'=>sprintf('Selected Group Role is not active thus cannot edit. Please enable it first'), 'type'=>'danger');
	    			return redirect()->route('masterrolemenu.list')->with('message', $status);
	    		}
		    	$data_menu = DataMenu::where(['IS_PARENT_MENU'=>1,'IS_ACTIVE'=>1, 'PARENT_ID'=>0])->get();
		    	$data_menu_map = $data_menu->mapWithKeys(function($data, $key){
		    		$data_child_menu = DataMenu::where(['IS_PARENT_MENU'=>1, 'IS_ACTIVE'=>1, 'PARENT_ID'=>$data->SEQ_ID])->get();
		    		$data_child_menu_map = $data_child_menu->mapWithKeys(function($obj, $key){
		    			$data_child_lv_2_menu = DataMenu::where(['IS_PARENT_MENU'=>0, 'IS_ACTIVE'=>1, 'PARENT_ID'=>$obj->SEQ_ID])->get();
		    			$data_child_lv_2_menu = $data_child_lv_2_menu->mapWithKeys(function($data_child, $key){
		    				return [$key=>array('type'=>'CHILD_PARENT_CHILD','PARENT_ID'=>$data_child->PARENT_ID,'LEVEL_MENU'=>3,'SEQ_ID'=>$data_child->SEQ_ID, 'MENU_NAME'=>$data_child->MENU_NAME)];
		    			});
		    			return [$key=>array('type'=>'CHILD_PARENT','PARENT_ID'=>$obj->PARENT_ID,'LEVEL_MENU'=>2,'SEQ_ID'=>$obj->SEQ_ID, 'MENU_NAME'=>$obj->MENU_NAME, 'child'=>$data_child_lv_2_menu)];
		    		});
		    		return [$key=>array('type'=>'PARENT','LEVEL_MENU'=>1,'SEQ_ID'=>$data->SEQ_ID, 'MENU_NAME'=>$data->MENU_NAME, 'child'=>$data_child_menu_map, 'SORT'=>$data->MENU_SORT)];
		    	})->sortBy('SORT');

		    	// Pre Populate menu that has been assigned
	    		$data_menu_assigned = $check_role_menu->pluck('MENU_ID')->toArray();
    			$data_permission = Permission::all();
    			$data_permission_assigned = DB::connection('dbintranet')
    			->table('INT_ROLE_MENU_PERMISSION')
    			->whereIn('MENU_ID', $data_menu_assigned)
    			->where('ROLE_ID', $role_id)
    			->select(DB::raw("DISTINCT CONCAT(MENU_ID,'.',PERMISSION_ID) AS PERMISSION_DATA"))
    			->get()->pluck('PERMISSION_DATA')->toArray();

	    		return view('pages.access_management.role_permission.edit_role_permission', ['data_role'=>$data_role,'data_menu'=>$data_menu, 'data_menu_map'=>$data_menu_map, 'assigned_rolemenu'=>$data_menu_assigned, 'role_selected'=>$role_id, 'data_permission'=>$data_permission, 'data_permission_assigned'=>$data_permission_assigned]);

	    	}
	    	else if($request->method() == 'POST'){
	    		$data_to_insert_role_menu = [];
	    		$data_to_insert_role_permission = [];

			    if(!$request->post('role', false) || !$request->post('menu', false)){
			    	throw new \Exception("Role or Menu have to be selected at least one");
			    }

			    $role = $request->post('role', 0);
			    // $data_role_menu = DataRoleMenu::where('ROLE_ID', $role)->pluck('MENU_ID')->toArray();
			    $menu = collect($request->post('menu', []));
			    // $menu_map = $menu->filter(function($item, $data) use ($data_role_menu){
			    // 	return !in_array($item, $data_role_menu);
			    // });

			    foreach ($menu as $menu_id) {
				    $data_to_insert_role_menu[Str::random(5)] = array('MENU_ID'=>$menu_id);
				}

				$permission = collect($request->post('permission', []));
			    $permission_map = $permission->groupBy(function($item, $key) {
			    	$separate_menu_permission = explode('.', $item);
			    	return isset($separate_menu_permission[0]) ? $separate_menu_permission[0] : [];
			    })->mapWithKeys(function($item, $key){
			    	$new_item = collect($item)->mapWithKeys(function($item_permission, $order){
			    		$permission_data = isset(explode('.', $item_permission)[1]) ? explode('.', $item_permission)[1] : '';
			    		return [$order=>$permission_data];	
			    	})->filter();
			    	return [$key=>$new_item];
			    })->toArray();

				// Insert Role Permission
				foreach ($permission_map as $key => $permission_id) {
					foreach ($permission_id as $permission) {
						$data_to_insert_role_permission[] = [
					        'ROLE_ID'		=> $role,
					        'MENU_ID'		=> $key,
					        'PERMISSION_ID'	=> $permission
				    	];
					}
				}

				if($data_to_insert_role_menu){
					$affected_row =  DB::connection('dbintranet')->transaction(function() use ($role, 
					$data_to_insert_role_menu, $data_to_insert_role_permission) 
					{
						$cek_role = DataRole::where('SEQ_ID', $role);
						$cek_role->first()->menu()->sync($data_to_insert_role_menu);
						DB::connection('dbintranet')->table('INT_ROLE_MENU_PERMISSION')
						->where('ROLE_ID', $role)->delete();
						DB::connection('dbintranet')->table('INT_ROLE_MENU_PERMISSION')
						->insert($data_to_insert_role_permission);
		    			return true;
		            }, 3);
					if($affected_row)
						$status = array('msg'=>"Role, Menu, and Permission has been successfully updated", 'type'=>'success');
					else 
						$status = array('msg'=>"Operation terminated since no data affected for update, please try again", 'type'=>'info');
				}
				else {
					$status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
				}	    		
			}

		} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('masterrolemenu.list')->with('message', $status);

    }
}
