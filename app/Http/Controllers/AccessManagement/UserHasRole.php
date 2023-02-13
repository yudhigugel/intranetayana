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

class UserHasRole extends Controller
{
    protected $iterable = 0;
    function index(Request $request){
    	return view('pages.access_management.user_has_role.list_user_role');
    }

    function getData(Request $request){
        try{
            $check_role = [];

            // Pastikan di master ada akses employee
            $data_master_access = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE_WITH_ROLE')
            ->select('EMPLOYEE_NAME', 'ROLE')
            ->orderBy('EMPLOYEE_SEQ_ID', 'ASC')
            ->get()->groupBy('EMPLOYEE_NAME')->mapWithKeys(function($item, $key){
                $emp_name = isset($item[0]->EMPLOYEE_NAME) ? $item[0]->EMPLOYEE_NAME : '';
                $check_role = $item->pluck('ROLE')->toArray();                
                return [$key=>array('EMPLOYEE_NAME'=>$emp_name, 'ROLE'=>$check_role)];
            })->toArray();

            $datatb = DataTables::of($data_master_access)
            ->addColumn('NUM_ORDER', function ($json) {
                $this->iterable = $this->iterable + 1;
                return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
            })
            ->addColumn('ROLE', function ($json) {
                $data_role = '';
                if(count($json['ROLE']) == 1 && empty($json['ROLE'][0])){
                    return "<div class='d-block' style='cursor:pointer'>
                        <span class='mb-1 mr-1'></span><span>-</span>
                    </div>";
                }

                foreach($json['ROLE'] as $key => $role){
                    $role = !empty($role) ? $role : '-';
                    $data_role .= "<div class='d-block' style='cursor:pointer'>
                        <span class='mb-1 mr-1'>".($key + 1).". </span><span>".$role."</span>
                    </div>";
                }
                return $data_role;
            })
            ->rawColumns(['NUM_ORDER', 'ROLE'])
            ->make(true);

            return $datatb;
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    function getData_old(Request $request){
        try{
            $check_role = [];

            // Pastikan di master ada akses employee
            $data_master_access = DB::connection('dbintranet')
            ->table('dbo.INT_MASTER_MENU_ACCESS')
            ->where(DB::raw('lower(MENU_ACCESS_NAME)'), 'employee')
            ->select('SEQ_ID')
            ->get()->first();
            if($data_master_access){
                $master_id = isset($data_master_access->SEQ_ID) ? $data_master_access->SEQ_ID : null;
                if($master_id){
                    $check_role = DB::connection('dbintranet')
                    ->table('dbo.INT_ROLE_HAS_ACCESS AS p')
                    ->join('dbo.INT_EMPLOYEE AS r', 'p.REFER_TO_ID', '=', 'r.SEQ_ID')
                    ->join('dbo.INT_MASTER_ROLE AS q', 'p.ROLE_ID', '=', 'q.SEQ_ID')
                    ->where('p.MENU_ACCESS_ID', $master_id)
                    ->select('p.REFER_TO_ID', 'r.EMPLOYEE_ID', 'r.EMPLOYEE_NAME', 'p.MENU_ACCESS_ID', 'q.ROLE_NAME')
                    ->get()->groupBy(['REFER_TO_ID'])->mapWithKeys(function($values, $key){
                        $employee_name = isset($values[0]->EMPLOYEE_NAME) ? $values[0]->EMPLOYEE_NAME : $key;
                        $employee_id = isset($values[0]->EMPLOYEE_ID) ? $values[0]->EMPLOYEE_ID : '';
                        $employee = $employee_id." - ".$employee_name;
                        return [$key=>array('EMPLOYEE_NAME'=>$employee, 'DATA_ROLE'=>$values->toArray())];
                    });
                }
            }

            return DataTables::of($check_role)
            ->addColumn('NUM_ORDER', function ($json) {
                $this->iterable = $this->iterable + 1;
                return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
            })
            ->addColumn('ROLE', function ($json) {
                $data_role = '';
                foreach($json['DATA_ROLE'] as $key => $role){
                    $data_role .= "<div class='d-block' style='cursor:pointer'>
                        <span class='mb-1 mr-1'>".($key + 1).". </span><span>".$role->ROLE_NAME."</span>
                    </div>";
                }
                return $data_role;
            })
            ->rawColumns(['NUM_ORDER', 'ROLE'])
            ->make(true);

        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }
}
