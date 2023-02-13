<?php

namespace App\Http\Controllers\AccessManagement;

use App\Http\Controllers\Controller;
use App\Models\HumanResource\EmployeeModel as DataEmployee;
use App\Models\HumanResource\CustomAccess as AccessPlant;
use App\Models\HumanResource\MasterMenu as DataMenu;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;
use DataTables;
use App\Http\Controllers\Traits\IntranetTrait;

class CustomAccess extends Controller
{
    use IntranetTrait;
	protected $iterable = 0;

    function index(Request $request){
        // $current_path = '/'.$request->path();
        // $cek_menu_id = DataMenu::where('PATH', $current_path)->get()->first();
        // dd($cek_menu_id);
        return view('pages.access_management.master_custom_access.list_custom_access');
    }

    function getData(Request $request){
        $data_custom_access = AccessPlant::select('INT_CUSTOM_ACCESS.SEQ_ID','INT_CUSTOM_ACCESS.EMPLOYEE_ID', 'INT_CUSTOM_ACCESS.PLANT_ID', 'INT_EMPLOYEE.EMPLOYEE_NAME', 'INT_CUSTOM_ACCESS.MENU_ID', 'INT_MASTER_MENU.MENU_NAME')
            ->leftJoin('INT_MASTER_MENU', 'INT_CUSTOM_ACCESS.MENU_ID', '=', 'INT_MASTER_MENU.SEQ_ID')
            ->leftJoin('INT_EMPLOYEE', 'INT_CUSTOM_ACCESS.EMPLOYEE_ID', '=', 'INT_EMPLOYEE.EMPLOYEE_ID')
            ->get()->groupBy(['EMPLOYEE_ID', 'MENU_NAME'])
            ->mapWithKeys(function($item, $key){
                $emp_name = '';
                $item_new = collect($item)->mapWithKeys(function($item, $key) use (&$emp_name){
                    if(!$emp_name)
                        $emp_name = isset($item[0]->EMPLOYEE_NAME) ? $item[0]->EMPLOYEE_NAME : '';

                    $menu_id = isset($item[0]->MENU_ID) ? $item[0]->MENU_ID : 0;
                    $plant_id = count($item->pluck('PLANT_ID')->values()->all()) > 0 ? implode(', ', $item->pluck('PLANT_ID')->values()->all()) : '';
                    $custom_access = sprintf("%s (%s)", $key ,$plant_id);
                    return [$key=>array('CUSTOM_ACCESS_NAME'=>$custom_access, 'MENU_ID'=>$menu_id)];

                })->values()->all();
                return [$key=>array('EMPLOYEE_ID'=>$key, 'EMPLOYEE_NAME'=>$emp_name, 'PLANT_ID'=>$item_new)];
            })->values()->all();

        return DataTables::of($data_custom_access)
        ->addColumn('NUM_ORDER', function ($json) {
            $this->iterable = $this->iterable + 1;
            return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
        })
        ->addColumn('PLANT', function ($json) {
            $data_plant = '-';
            $emp_id = isset($json['EMPLOYEE_ID']) ? $json['EMPLOYEE_ID'] : 0;
            if(isset($json['PLANT_ID']) && count($json['PLANT_ID'])){
                $data_plant = '';
                foreach($json['PLANT_ID'] as $key => $plant){
                    $access_name = isset($plant['CUSTOM_ACCESS_NAME']) && !empty($plant['CUSTOM_ACCESS_NAME']) ? $plant['CUSTOM_ACCESS_NAME'] : '-';
                    $menu_id = isset($plant['MENU_ID']) ? $plant['MENU_ID'] : 0;
                    // if(Session::get('permission_menu')->has("update_".route('custom-plant-access.list', array(), false))){
                        $data_plant .= "<div class='d-block' style='cursor:pointer'>
                            <a href='".route('custom-plant-access.update', [$emp_id, $menu_id])."' class='btn-edit' style='text-decoration: none'>
                                <span class='mb-1 mr-1'>".($key + 1).". </span><span>".$access_name."</span>
                            </a>
                        </div>";
                    // } else {
                    //     $data_plant .= "<div class='d-block' style='cursor:pointer'>
                    //         <span class='mb-1 mr-1'>".($key + 1).". </span><span>".$plant."</span>
                    //     </div>";
                    // }
                }
            }
            return $data_plant;
        })
        ->rawColumns(['NUM_ORDER', 'PLANT'])
        ->make(true);
    }

    function add(Request $request){
        $data['employee'] = DataEmployee::select('EMPLOYEE_NAME', 'EMPLOYEE_ID')
        ->get()->toArray();
        $data['menu'] = DataMenu::where([['IS_ACTIVE','=','1'],['IS_PARENT_MENU','=','1'], ['PARENT_ID','<>','0'], ['PATH','<>','#']])
        ->orWhere([['IS_ACTIVE','=','1'],['IS_PARENT_MENU','=','0'], ['PARENT_ID','<>','0']])
        ->orderByRaw("SEQ_ID ASC, MENU_SORT ASC")
        ->select('SEQ_ID', 'MENU_NAME', 'IS_PARENT_MENU', 'PARENT_ID')
        ->get()->toArray();        
        $data['plant'] = DB::connection('dbintranet')
        ->table('dbo.INT_BUSINESS_PLANT')
        ->where('STATUS', 1)
        ->select('SAP_PLANT_ID', 'SAP_PLANT_NAME')
        ->get()->toArray();
        return view('pages.access_management.master_custom_access.add_custom_access', ['data'=>$data]);
    }

    function create(Request $request){
        $status=null;
        try{    
            if(!$request->post('menu', false) || !$request->post('employee', false)){
                throw new \Exception("Employee or Menu have to be selected at least one");
            }
            $employee_id = $request->post('employee', 0);
            $menu_id = $request->post('menu', 0);

            // Check unique employee and menu id
            $messages = [
                'employee.unique' => 'Given employee and menu are already in the database, update it instead',
            ];
            $rule = array('employee' => [
                'required',
                Rule::unique('dbintranet.INT_CUSTOM_ACCESS', 'EMPLOYEE_ID')->where(function ($query) use ($menu_id) {
                    return $query->where('MENU_ID', $menu_id);
                }),
            ]);
            $validator = Validator::make($request->all(), $rule, $messages);
            if($validator->fails()) {
                return \Redirect::back()->withErrors($validator);
            }

            $data_to_insert = [];
            $data_custom_access = AccessPlant::where(['EMPLOYEE_ID'=>$employee_id, 'MENU_ID'=>$menu_id])->pluck('PLANT_ID', 'PLANT_ID')->values()->all();
            $plant = collect($request->post('plant', []));

            if(count($data_custom_access)){
                $plant_map = $plant->filter(function($item, $data) use ($data_custom_access){
                    return !in_array($item, $data_custom_access);
                });
            } else {
                $plant_map = $plant;
            }

            foreach ($plant_map as $plant_id) {
                $data_to_insert[] = [
                    'EMPLOYEE_ID'=> $employee_id,
                    'MENU_ID'    => $menu_id,
                    'PLANT_ID'   => $plant_id
                ];
            }
            
            if($data_to_insert){
                $insert_data = AccessPlant::insert($data_to_insert);
                $status = array('msg'=>"Custom Plant Access has been successfully added", 'type'=>'success');
            } else {
                $status = array('msg'=>"No data to insert since it is already added", 'type'=>'info');
            }

        } catch(\Exception $e){ 
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
            return redirect()->route('custom-plant-access.add')->with('message', $status);
        }

        return redirect()->route('custom-plant-access.list')->with('message', $status);
    }

    function delete(Request $request, $employee_id, $menu_id) {
        $status = null;
        try {
            $data = AccessPlant::where(['EMPLOYEE_ID'=>$employee_id, 'MENU_ID'=>$menu_id]);
            if($data->get()->count()){
                // If any affected row with changes
                $data->delete();
                $affected_row = true;
                if($affected_row)
                    $status = array('msg'=>"Data has been successfully deleted", 'type'=>'success');
                else 
                    $status = array('msg'=>"No data affected, please try again", 'type'=>'info');
            }
        } catch(\Exception $e){
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
        }

        return redirect()->route('custom-plant-access.list')->with('message', $status);
    }

    function update(Request $request, $employee_id, $menu_id) {
        $status = null;
        try {
            if($request->method() == 'GET') {
                $get_data = AccessPlant::where(['EMPLOYEE_ID'=>$employee_id, 'MENU_ID'=>$menu_id])->get();
                if($get_data->count()){
                    $data['employee'] = DataEmployee::where('EMPLOYEE_ID', $employee_id)
                    ->select('EMPLOYEE_ID', 'EMPLOYEE_NAME')->get()->toArray();
                    $data['menu'] = DataMenu::where('SEQ_ID', $menu_id)
                    ->select('SEQ_ID','MENU_NAME')->get()->toArray();
                    $data['plant_selected'] = $get_data->pluck('PLANT_ID', 'PLANT_ID')->values()->all();
                    $data['plant'] = DB::connection('dbintranet')
                    ->table('dbo.INT_BUSINESS_PLANT')
                    ->where('STATUS', 1)
                    ->select('SAP_PLANT_ID', 'SAP_PLANT_NAME')
                    ->get()->toArray();

                    return view('pages.access_management.master_custom_access.edit_custom_access', ['data'=>$data, 'employee_id'=>$employee_id, 'menu_id'=>$menu_id]);
                }
                else
                    throw new \Exception("Cannot find data, please try again later");
            }

            else if($request->method() == 'POST'){
                $rule = array('plant' => [
                    'required',
                ]);

                $validator = Validator::make($request->all(), $rule);
                if($validator->fails()) {
                    return \Redirect::back()->withErrors($validator);
                }

                $employee_check = DataEmployee::where('EMPLOYEE_ID', $employee_id)->get()->first();
                $sync_data_update = [];
                $edited_plant = $request->post('plant');
                for($i=0;$i<count($edited_plant);$i++){
                    $sync_data_update[Str::random(5)] = array(
                        'MENU_ID'=>$menu_id,
                        'PLANT_ID'=>$edited_plant[$i],
                        'EMPLOYEE_ID'=>$employee_id
                    );
                }

                $employee_check->custom_plant_access()->sync($sync_data_update);
                $status = array('msg'=>"Data has been successfully updated", 'type'=>'success');
            }

        } catch(\Exception $e){
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
        }

        return redirect()->route('custom-plant-access.list')->with('message', $status);
    }
}