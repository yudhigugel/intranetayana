<?php

namespace App\Http\Controllers\AccessManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanResource\MasterRole as DataRole;
use App\Models\HumanResource\RoleMenu as DataRoleMenu;
use App\Models\HumanResource\MenuAccessRole as DataAccessRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Log;
use DataTables;

class MasterRole extends Controller
{
	protected $iterable = 0;
    function index(Request $request){
    	return view('pages.access_management.master_role.list_role');
    }

    function add_role(){
    	return view('pages.access_management.master_role.add_role');
    }

    function getData(Request $request){
    	try{  
	        $data_role=DataRole::all();

	        return DataTables::of($data_role)
	        ->addColumn('NUM_ORDER', function ($json) {
                $this->iterable = $this->iterable + 1;
                return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
            })
            ->addColumn('STATUS_ACTIVE', function($json){
            	if(isset($json['STATUS'])){
            		if($json['STATUS'])
            			return 'Active';
            		else
            			return '<span class="text-danger">Not Active</span>';
            	}
            	else
            		return "Unknown"; 
            })
	        ->addColumn('ACTION', function ($json) {
	        	if($json['STATUS']) {
	        		$type_button = 'btn-danger text-white';
	        		$menu_icon = 'mdi mdi-lock';
	        	}
	        	else {
	        		$type_button = 'btn-success text-white';
	        		$menu_icon = 'mdi mdi-lock-open';
	        	}

	        	$action = '';
                if(Session::get('permission_menu')->has("update_".route('masterrole.list', array(), false)))
                    $action .= '<a href="'.route('masterrole.update', $json['SEQ_ID']).'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-edit"><i class="mdi mdi-pencil"></i></a>';

                if(Session::get('permission_menu')->has("delete_".route('masterrole.list', array(), false)))
                    $action .= '<a href="#" data-url-delete="'.route('masterrole.delete', $json['SEQ_ID']).'" class="btn pl-2 pr-2 '.$type_button.' ml-1 mr-1 btn-delete"><i class="'.$menu_icon.'"></i></a>';

                if(!$action)
                    $action = '<small class="text-muted">NO ACCESS</small>';
                
	        	return $action; 
            })
	        ->rawColumns(['ACTION', 'NUM_ORDER', 'STATUS_ACTIVE'])
	        ->make(true);
	    }
	    catch(\Exception $e){
	        return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
	    }
    }


    function create(Request $request){
    	$validated = $request->validate([
	        'role_name' => 'required|unique:dbintranet.INT_MASTER_ROLE',
	    ]);

	    $status = null;
	    try {
		    $data_to_insert = [];
		    $role_name = $request->post('role_name', null);
		    $description = $request->post('description', null);

	    	$data_to_insert = array(
	    		'ROLE_NAME'=>$role_name, 
	    		'DESCRIPTION'=>$description,
	    	);

		    // Insert to database
		    if($data_to_insert){
			    $id = DB::connection('dbintranet')
			    ->table('INT_MASTER_ROLE')
			    ->insertGetId($data_to_insert);	
			    $status = array('msg'=>"Role has been successfully added", 'type'=>'success');
			}
			else {
				$status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
				// throw new Exception("There is no data to insert, try again");
			}

	    } catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    // Return to Role list
	    return redirect()->route('masterrole.list')->with('message', $status);
    }

    function delete(Request $request, $id) {
    	$status = null;
    	try {
    		$data = DataRole::where('SEQ_ID', $id)->first();
    		if($data) {
    			if($data->STATUS){
		    		$affected_row = DataRole::where('SEQ_ID', $id)
		    		->update(['STATUS'=>0]);
		    	}
		    	else{
		    		$affected_row = DataRole::where('SEQ_ID', $id)
		    		->update(['STATUS'=>1]);
		    	}

		    	// If any affected row with changes
	    		if($affected_row)
					$status = array('msg'=>"Role state has been successfully changed", 'type'=>'success');
				else 
					$status = array('msg'=>"No state has been changed / already changed", 'type'=>'info');
			}
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('masterrole.list')->with('message', $status);
    }

    function remove(Request $request, $id) {
    	$status = null;
    	try {
    		$data = DataRole::where('SEQ_ID', $id)->first();
    		if($data) {
		    	// If any affected row with changes
		    	$affected_row =  DB::connection('dbintranet')->transaction(function() use ($id) {
	                DataRole::where('SEQ_ID', $id)
	    			->delete();
	    			DataRoleMenu::where('ROLE_ID', $id)
	    			->delete();
	    			DataAccessRole::where('ROLE_ID', $id)
	    			->delete();
	    			DB::connection('dbintranet')->table('INT_ROLE_MENU_PERMISSION')
						->where('ROLE_ID', $id)->delete();
	    			return true;
	            }, 3);  
		    	
	    		if($affected_row)
					$status = array('msg'=>"Role has been successfully deleted", 'type'=>'success');
				else 
					$status = array('msg'=>"No role affected, please try again", 'type'=>'info');
			}
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('masterrole.list')->with('message', $status);
    }

    function update(Request $request, $id){
    	$status = null;
    	try {
	    	if($request->method() == 'GET') {
	    		$get_data = DataRole::where('SEQ_ID', $id)->get();
	    		if($get_data->count()){
	    			$get_data = $get_data->first();
			    	return view('pages.access_management.master_role.edit_role', ['data_edit'=>$get_data]);
	    		}
	    		else
	    			throw new \Exception("Cannot find data with provided ID");
	    	}

	    	else if($request->method() == 'POST'){
	    		$to_ignore = $id;
		    	$validator = Validator::make($request->all(), [
				    'role_name' => [
				        'required',
				        Rule::unique('dbintranet.INT_MASTER_ROLE', 'ROLE_NAME')->ignore($to_ignore, 'SEQ_ID')
				    ],
				]);

				if($validator->fails()) {
				    return \Redirect::back()->withErrors($validator);
				}

			    $status = null;
			    try {
				    $data_to_insert = [];
				    $role_name = $request->post('role_name', null);
				    $description = $request->post('description', null);

			    	$data_to_insert = array(
			    		'ROLE_NAME'=>$role_name, 
			    		'DESCRIPTION'=>$description,
			    	);

				    // Update to database
				    if($data_to_insert){
					    $affected_row = DB::connection('dbintranet')
					    ->table('INT_MASTER_ROLE')
					    ->where('SEQ_ID', $id)
					    ->update($data_to_insert);	
					    $status = array('msg'=>"Menu has been successfully updated", 'type'=>'success');
					}
					else {
						$status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
						// throw new Exception("There is no data to update, try again");
					}

			    } catch(\Exception $e){
			    	Log::error($e->getMessage());
			    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
			    }
			}

		} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('masterrole.list')->with('message', $status);
    }
}
