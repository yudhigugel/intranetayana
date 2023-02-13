<?php

namespace App\Http\Controllers\AccessManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HumanResource\MasterMenu as DataMenu;
use App\Models\HumanResource\UserPermission as UserHasPermission;
use App\Models\HumanResource\RoleMenu as DataRoleMenu;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Log;
use DataTables;

class MasterMenu extends Controller
{
	protected $iterable = 0;
    function index(Request $request){
    	return view('pages.access_management.list_menu');
    }

    function add_menu(Request $request){
    	$data_group_menu = DB::connection('dbintranet')
    	->table('INT_MASTER_MENU')
    	->select('SEQ_ID', 'MENU_NAME', 'IS_PARENT_MENU', 'PARENT_ID')
    	->whereRaw('IS_PARENT_MENU = ? AND PARENT_ID <> ? AND IS_ACTIVE = ?', [1, 0, 1])
    	->get();

    	$data_parent_menu = DB::connection('dbintranet')
    	->table('INT_MASTER_MENU')
    	->select('SEQ_ID', 'MENU_NAME', 'IS_PARENT_MENU', 'PARENT_ID')
    	->whereRaw('IS_PARENT_MENU = ? AND PARENT_ID = ? AND IS_ACTIVE = ?', [1, 0, 1])
    	->get();

    	return view('pages.access_management.add_menu', ['data_menu_group'=>$data_group_menu, 'data_menu_parent'=>$data_parent_menu]);
    }

    function getData(Request $request){
    	try{  
	        $data_master=DataMenu::where(['IS_PARENT_MENU'=>1, 'PARENT_ID'=>0])->get();
	        
	        $data_new_master = $data_master->mapWithKeys(function($data, $key){
	        	$data_menu = DataMenu::where(['IS_PARENT_MENU'=>1, 'PARENT_ID'=>$data->SEQ_ID])->get();
	        	$data_child_of_parent = $data_menu->mapWithKeys(function($obj, $key){
	        		$data_child = DataMenu::
	        		select('SEQ_ID', 'MENU_NAME', 'IS_PARENT_MENU', 'PARENT_ID', 'IS_ACTIVE', 'PATH', 'ROUTE_NAME', 'ICON AS ICON_CLASS', 'MENU_SORT AS SORT')
	        		->where(['IS_PARENT_MENU'=>0, 'PARENT_ID'=>$obj->SEQ_ID])
	        		->get();
	        		return [$key => array('ORDER'=>($key + 1),'SEQ_ID'=>$obj->SEQ_ID, 'MENU_NAME'=>$obj->MENU_NAME, 'PATH'=>$obj->PATH ? $obj->PATH : '-', 'ICON_CLASS'=>$obj->ICON ? $obj->ICON : '-', 'NAMED_ROUTE'=>$obj->ROUTE_NAME, 'IFRAME_SOURCE'=>$obj->IFRAME_SOURCE, 'IS_PARENT_MENU'=>$obj->IS_PARENT_MENU, 'PARENT_ID'=>$obj->PARENT_ID, 'PARENT_CHILD_MENU'=>$data_child, 'IS_ACTIVE'=>$obj->IS_ACTIVE,'SORT'=>$obj->MENU_SORT)];
	        	})->sortBy('SORT');
	        	return [$key => array('ORDER'=>($key + 1), 'SEQ_ID'=>$data->SEQ_ID, 'MENU_NAME'=>$data->MENU_NAME, 'PATH'=>$data->PATH ? $data->PATH : '-', 'ICON_CLASS'=>$data->ICON ? $data->ICON : '-', 'NAMED_ROUTE'=>$data->ROUTE_NAME, 'IFRAME_SOURCE'=>$data->IFRAME_SOURCE, 'IS_PARENT_MENU'=>$data->IS_PARENT_MENU, 'PARENT_ID'=>$data->PARENT_ID, 'PARENT_CHILD_MENU'=>$data_child_of_parent, 'IS_ACTIVE'=>$data->IS_ACTIVE, 'SORT'=>$data->MENU_SORT)];
	       	})->sortBy('SORT');

	        return DataTables::of($data_new_master)
	        ->addColumn('NUM_ORDER', function ($json) {
                $this->iterable = $this->iterable + 1;
                return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
            })
            ->addColumn('STATUS_ACTIVE', function($json){
            	if(isset($json['IS_ACTIVE'])){
            		if($json['IS_ACTIVE'])
            			return 'Active';
            		else
            			return '<span class="text-danger">Not Active</span>';
            	}
            	else
            		return "Unknown"; 
            })
	        ->addColumn('HAVING_CHILD_MENU', function ($json) {
	        	 if(isset($json['PARENT_CHILD_MENU'])){
	        	 	return "<a class='text-primary' style='cursor:pointer'>".count($json['PARENT_CHILD_MENU'])."</a>&nbsp; <i class='fa fa-caret-down'></i>";
	        	 }
	        	 return "<a class='text-primary' style='cursor:pointer'>0</a>";
	        })
	        ->addColumn('ACTION', function ($json) {
	        	if($json['IS_ACTIVE']) {
	        		$type_button = 'btn-danger text-white';
	        		$menu_icon = 'mdi mdi-lock';
	        	}
	        	else {
	        		$type_button = 'btn-success text-white';
	        		$menu_icon = 'mdi mdi-lock-open';
	        	}

	        	$action = '';
	        	if(Session::get('permission_menu')->has("view_".route('mastermenu.list', array(), false)))
                    $action .= '<a href="'.route('mastermenu.view', $json['SEQ_ID']).'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-view"><i class="mdi mdi-eye"></i></a>';

                if(Session::get('permission_menu')->has("update_".route('mastermenu.list', array(), false)))
                    $action .= '<a href="'.route('mastermenu.update', $json['SEQ_ID']).'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-edit"><i class="mdi mdi-pencil"></i></a>';

                if(Session::get('permission_menu')->has("delete_".route('mastermenu.list', array(), false)))
                    $action .= '<a href="#" data-url-delete="'.route('mastermenu.delete', $json['SEQ_ID']).'" class="btn pl-2 pr-2 '.$type_button.' ml-1 mr-1 btn-delete"><i class="'.$menu_icon.'"></i></a>';

                if(!$action)
                    $action = '<small class="text-muted">NO ACCESS</small>';

	        	return $action; 
            })
	        ->rawColumns(['ACTION', 'NUM_ORDER', 'HAVING_CHILD_MENU', 'STATUS_ACTIVE'])
	        ->make(true);
	    }
	    catch(\Exception $e){
	        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
	    }
    }

    function create(Request $request){
    	$validated = $request->validate([
	        'menu_name' => 'required|unique:dbintranet.INT_MASTER_MENU',
	    ]);

    	$status = null;
    	try {
		    // Check is Parent
		    $data_to_insert = [];
		    if(!$request->post('is_parent', false) && !$request->post('is_group', false) && !$request->post('is_single', false)){
		    	throw new Exception("No type of menu specified, please select either parent|group|single from the checkbox");
		    }

		    $menu_name = $request->post('menu_name', null);
		    $iframe_source = $request->post('iframe_source', null);
		    $path = $request->post('path', null);
		    $icon_class = $request->post('icon', null);
		    $route_name = $request->post('path_name', null);

		    // If menu is as top level menu (Parent / Title)
		    if($request->post('is_parent', false)){
		    	$data_to_insert = array(
		    		'MENU_NAME'=>ucfirst($menu_name), 
		    		'IS_PARENT_MENU'=>1, 
		    		'PARENT_ID'=>0,
		    		'PATH'=>'#',
		    		'ROUTE_NAME'=>'#',
		    		'ICON'=>$icon_class,
		    		'IFRAME_SOURCE'=>$iframe_source,
		    		'DATE_CREATED'=>date('Y-m-d H:i:s'),
		    		'DATE_MODIFIED'=>date('Y-m-d H:i:s')
		    	);
		    }

		    else if($request->post('is_single', false)){
		    	$parent_id = $request->post('parent_id');
		    	$data_to_insert = array(
		    		'MENU_NAME'=>ucfirst($menu_name), 
		    		'IS_PARENT_MENU'=>1, 
		    		'PARENT_ID'=>$parent_id,
		    		'ICON'=>$icon_class,
		    		'IFRAME_SOURCE'=>$iframe_source,
		    		'PATH'=>$path,
		    		'ROUTE_NAME'=>$route_name,
		    		'DATE_CREATED'=>date('Y-m-d H:i:s'),
		    		'DATE_MODIFIED'=>date('Y-m-d H:i:s')
		    	);
		    }

		    else if($request->post('is_group', false)){
		    	$part_of_group = $request->post('group_id');

		    	try {
		    		$affected = DataMenu::where('SEQ_ID', $part_of_group)
		    		->update(['PATH'=>'#', 'DATE_MODIFIED'=>date('Y-m-d H:i:s')]);
		    	} catch(\Exception $e){}

		    	$data_to_insert = array(
		    		'MENU_NAME'=>ucfirst($menu_name), 
		    		'IS_PARENT_MENU'=>0, 
		    		'PATH'=>$path,
		    		'IFRAME_SOURCE'=>$iframe_source,
		    		'PARENT_ID'=>$part_of_group,
		    		'ICON'=>$icon_class,
		    		'ROUTE_NAME'=>$route_name,
		    		'DATE_CREATED'=>date('Y-m-d H:i:s'),
		    		'DATE_MODIFIED'=>date('Y-m-d H:i:s')
		    	);
		    }

		    // Insert to database
		    if($data_to_insert){
			    $id = DB::connection('dbintranet')
			    ->table('INT_MASTER_MENU')
			    ->insertGetId($data_to_insert);	
			    $status = array('msg'=>"Menu has been successfully added", 'type'=>'success');
			}
			else {
				$status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
				// throw new Exception("There is no data to insert, try again");
			}

	    } catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('mastermenu.list')->with('message', $status);
    }

    function delete(Request $request, $id) {
    	$status = null;
    	try {
    		$data = DataMenu::where('SEQ_ID', $id)->first();
    		if($data) {
    			if($data->IS_ACTIVE){
		    		$affected_row = DataMenu::where('SEQ_ID', $id)
		    		->update(['IS_ACTIVE'=>0]);
		    	}
		    	else{
		    		$affected_row = DataMenu::where('SEQ_ID', $id)
		    		->update(['IS_ACTIVE'=>1]);
		    	}

		    	// If any affected row with changes
	    		if($affected_row)
					$status = array('msg'=>"Menu state has been successfully changed", 'type'=>'success');
				else 
					$status = array('msg'=>"No state has been changed / already changed", 'type'=>'info');
			}
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('mastermenu.list')->with('message', $status);
    }

    function remove(Request $request, $id) {
    	$status = null;
    	try {
    		$data = DataMenu::where('SEQ_ID', $id)->first();
    		if($data) {
		    	// If any affected row with changes
    			$affected_row =  DB::connection('dbintranet')->transaction(function() use ($id) {
    				DataMenu::where('SEQ_ID', $id)
	    			->delete();
	    			DataRoleMenu::where('MENU_ID', $id)
	    			->delete();
	    			UserHasPermission::where('MENU_ID', $id)
	    			->delete();
	    			return true;
    			}, 3);

		    	// If any affected row with changes
	    		if($affected_row)
					$status = array('msg'=>"Menu has been successfully deleted", 'type'=>'success');
				else 
					$status = array('msg'=>"No menu affected, please try again", 'type'=>'info');
			}
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('mastermenu.list')->with('message', $status);
    }

    function update(Request $request, $id){
    	$status = null;
    	try {
	    	if($request->method() == 'GET') {
	    		$get_data = DataMenu::where('SEQ_ID', $id)->get();
	    		if($get_data->count()){
	    			$get_data = $get_data->first();
			    	return view('pages.access_management.edit_menu', ['data_edit'=>$get_data]);
	    		}
	    		else
	    			throw new \Exception("Cannot find menu data with provided ID",);
	    	}

	    	else if($request->method() == 'POST'){
	    		// Rule to ignore this id check on update unique
		    	$to_ignore = $id;
		    	$rule = array('menu_name' => [
				        'required',
				        Rule::unique('dbintranet.INT_MASTER_MENU', 'MENU_NAME')->ignore($to_ignore, 'SEQ_ID')
				]);
		    	if(array_key_exists('path', $request->post())){
		    		$rule['path'] = ['required'];
		    	}

		    	$validator = Validator::make($request->all(), $rule);
				if($validator->fails()) {
				    return \Redirect::back()->withErrors($validator);
				}

			    $menu_name = $request->post('menu_name',null);
			    $path = $request->post('path',null);
			    // Force set Path to # if parent menu
			    $get_data = DataMenu::where('SEQ_ID', $id)->get();
			    if($get_data->count()){
	    			$get_data = $get_data->first();
	    			if($get_data->IS_PARENT_MENU && $get_data->PARENT_ID == 0)
	    				$path = '#';
	    		}

			    $path_name = $request->post('path_name',null);
			    $icon = $request->post('icon',null);
			    $iframe = $request->post('iframe_source',null);

			    $data_to_insert = array(
		    		'MENU_NAME'=>ucfirst($menu_name), 
		    		'PATH'=>$path,
		    		'ROUTE_NAME'=>$path_name,
		    		'ICON'=>$icon,
		    		'IFRAME_SOURCE'=>$iframe,
	    			'DATE_MODIFIED'=>date('Y-m-d H:i:s')
		    	);

			    // Update to database
			    if($data_to_insert){
				    $affected_row = DB::connection('dbintranet')
				    ->table('INT_MASTER_MENU')
				    ->where('SEQ_ID', $id)
				    ->update($data_to_insert);	
				    $status = array('msg'=>"Menu has been successfully updated", 'type'=>'success');
				}
				else {
					$status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
					// throw new Exception("There is no data to update, try again");
				}
			}

		} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }
	    return redirect()->route('mastermenu.list')->with('message', $status);
    }

    function view_menu(Request $request, $id){
    	$status = null;
    	try {
    		$get_data = DataMenu::where('SEQ_ID', $id)->get();
    		if($get_data->count()){
    			$get_data = $get_data->first();
		    	return view('pages.access_management.view_menu', ['data_edit'=>$get_data]);
    		}
    		else
    			throw new \Exception("Cannot find menu data with provided ID",);
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('mastermenu.list')->with('message', $status);
    }

    function sort(Request $request){
    	if($request->ajax()){
    		try {
    			$posted_new_sort = collect($request->post('order', []))->mapWithKeys(function($item, $key){
    				$item = collect($item)->mapWithKeys(function($item, $key){
    					return [$key=>(int)$item];
    				});
    				return [$key=>$item];
    			})->toArray();
	    		$affected_row = 0;
			    foreach ($posted_new_sort as $order) {
			    	DB::connection('dbintranet')->transaction(function() use ($order, &$affected_row) {
			    	   $data = DB::connection('dbintranet')
		               ->table('INT_MASTER_MENU')
					   ->where('SEQ_ID', $order['SEQ_ID'])
					   ->update(['MENU_SORT'=>$order['MENU_SORT']]);
					   if($data)
					     $affected_row++;
					}, 3);
	            }

			    if($affected_row)
			    	$status = array('msg'=>"Menu has been successfully updated", 'type'=>'success');
			    else
					$status = array('msg'=>"No data to sort, operation terminated", 'type'=>'info');
			} catch(\Exception $e){
				dd($e->getMessage());
				Log::error($e->getMessage());
	    		$status = array('msg'=>sprintf('Something went wrong while sorting, please try again'), 'type'=>'danger');
			}
			return $status;
    	}
    }
}
