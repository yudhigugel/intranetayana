<?php

namespace App\Http\Controllers\AccessManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HumanResource\MasterMenuAccess as DataMenuAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Log;
use DataTables;

class MasterMenuAccess extends Controller
{
	protected $iterable = 0;
    function index(Request $request){
    	return view('pages.access_management.master_access.list_access');
    }

    function add_access(){
    	return view('pages.access_management.master_access.add_access');
    }

    function getData(Request $request){
    	try{  
	        $data_access=DataMenuAccess::all();

	        return DataTables::of($data_access)
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
                if(Session::get('permission_menu')->has("update_".route('masteraccess.list', array(), false)))
                    $action .= '<a href="'.route('masteraccess.update', $json['SEQ_ID']).'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-edit"><i class="mdi mdi-pencil"></i></a>';

                if(Session::get('permission_menu')->has("delete_".route('masteraccess.list', array(), false)))
                    $action .= '<a href="#" data-url-delete="'.route('masteraccess.delete', $json['SEQ_ID']).'" class="btn pl-2 pr-2 '.$type_button.' ml-1 mr-1 btn-delete"><i class="'.$menu_icon.'"></i></a>';

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
	        'access_name' => 'required|unique:dbintranet.INT_MASTER_MENU_ACCESS,MENU_ACCESS_NAME',
	    ]);

	    $status = null;
	    try {
		    $data_to_insert = [];
		    $access_name = $request->post('access_name', null);

	    	$data_to_insert = array(
	    		'MENU_ACCESS_NAME'=>strtoupper($access_name), 
	    		'DATE_CREATED' => \Carbon\Carbon::now(),
    			'DATE_UPDATED' => \Carbon\Carbon::now(),
	    	);

		    // Insert to database
		    if($data_to_insert){
			    $id = DB::connection('dbintranet')
			    ->table('INT_MASTER_MENU_ACCESS')
			    ->insertGetId($data_to_insert);	
			    $status = array('msg'=>"Menu Access has been successfully added", 'type'=>'success');
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
	    return redirect()->route('masteraccess.list')->with('message', $status);
    }

    function delete(Request $request, $id) {
    	$status = null;
    	try {
    		$data = DataMenuAccess::where('SEQ_ID', $id)->first();
    		if($data) {
    			if($data->STATUS){
		    		$affected_row = DataMenuAccess::where('SEQ_ID', $id)
		    		->update(['STATUS'=>0]);
		    	}
		    	else{
		    		$affected_row = DataMenuAccess::where('SEQ_ID', $id)
		    		->update(['STATUS'=>1]);
		    	}

		    	// If any affected row with changes
	    		if($affected_row)
					$status = array('msg'=>"Menu Access state has been successfully changed", 'type'=>'success');
				else 
					$status = array('msg'=>"No state has been changed / already changed", 'type'=>'info');
			}
    	} catch(\Exception $e){
	    	Log::error($e->getMessage());
	    	$status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
	    }

	    return redirect()->route('masteraccess.list')->with('message', $status);
    }

    function update(Request $request, $id){
    	$status = null;
    	try {
	    	if($request->method() == 'GET') {
	    		$get_data = DataMenuAccess::where('SEQ_ID', $id)->get();
	    		if($get_data->count()){
	    			$get_data = $get_data->first();
			    	return view('pages.access_management.master_access.edit_access', ['data_edit'=>$get_data]);
	    		}
	    		else
	    			throw new \Exception("Cannot find data with provided ID",);
	    	}

	    	else if($request->method() == 'POST'){
	    		$to_ignore = $id;
		    	$validator = Validator::make($request->all(), [
				    'access_name' => [
				        'required',
				        Rule::unique('dbintranet.INT_MASTER_MENU_ACCESS', 'MENU_ACCESS_NAME')->ignore($to_ignore, 'SEQ_ID')
				    ],
				]);

				if($validator->fails()) {
				    return \Redirect::back()->withErrors($validator);
				}

			    $status = null;
			    try {
				    $data_to_insert = [];
				    $access_name = $request->post('access_name', null);

			    	$data_to_insert = array(
			    		'MENU_ACCESS_NAME'=>strtoupper($access_name), 
			    	);

				    // Update to database
				    if($data_to_insert){
					    $affected_row = DB::connection('dbintranet')
					    ->table('INT_MASTER_MENU_ACCESS')
					    ->where('SEQ_ID', $id)
					    ->update($data_to_insert);	
					    $status = array('msg'=>"Menu Access has been successfully updated", 'type'=>'success');
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

	    return redirect()->route('masteraccess.list')->with('message', $status);
    }
}
