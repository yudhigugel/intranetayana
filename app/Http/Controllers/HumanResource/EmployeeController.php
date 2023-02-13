<?php

namespace App\Http\Controllers\HumanResource;

use Illuminate\Http\Request;
use App\Models\HumanResource\EmployeeModel;
use App\Http\Controllers\Controller;
use DB;
use Log;
use Validator;
use DataTables;

class EmployeeController extends Controller
{
    protected $iterable = 0;
    function index(){
    	return view('pages.human-resource.employee.employee');
    }

    function employeeSearch(){
        $data_company_employee = new EmployeeModel();
        $to_return = $data_company_employee->getCompany();
        if(!is_object($to_return)){
            $to_return = array();
            return view('pages.human-resource.employee.employee_search', ['data_company'=>$to_return, 'data_company_count'=>0])->with('message', 'Something went wrong with database, try refreshing page');
        }
        return view('pages.human-resource.employee.employee_search', ['data_company'=>$to_return, 'data_company_count'=>$to_return->count()]);
    }

    function employeeFilterGetPlant(Request $request){
        $company = $request->get('company_code', null);
        $data_company_employee = new EmployeeModel();
        $to_return = $data_company_employee->getPlant($company);
        if(!is_object($to_return)){
            return response()->json($to_return, 500);
        }
        return $to_return;
    }

    function employeeFilterGetTerritory(Request $request){
        $plant = $request->get('plant_code', null);
        $data_company_employee = new EmployeeModel();
        $to_return = $data_company_employee->getTerritory($plant);
        if(!is_object($to_return)){
            return response()->json($to_return, 500);
        }
        return $to_return;
    }

    function employeeSearchData(Request $request){
        if($request->ajax()){
            $filter = array();
            foreach ($request->input() as $key => $value) {
                if(strtolower($key) != '_token'){
                    if(strtolower($key) != 'search_by' && strtolower($key) != 'search_terms')
                        $filter[$key] = $value;
                }
            }
            if($request->input('search_terms', false))
                array_push($filter, [$request->input('search_by', 'EMPLOYEE_NAME'), 'LIKE', "%".$request->input('search_terms', '')."%"]);

            // Now getting data from model
            try{
                $data_employee = new EmployeeModel();
                $to_return = $data_employee->employeeSearchWithSuperior($filter);
                if(is_array($to_return) && array_key_exists('log', $to_return))
                    return response()->json($to_return, 500);                
                else {
                    return DataTables::of($to_return)
                    ->editColumn('DATE_JOIN_ASSIGNMENT', function ($json) {
                        if(isset($json->DATE_JOIN_ASSIGNMENT) && $json->DATE_JOIN_ASSIGNMENT)
                            return $json->DATE_JOIN_ASSIGNMENT;
                        else
                            return "-";
                    })
                    ->addColumn('NUM_ORDER', function ($json) {
                        $this->iterable = $this->iterable + 1;
                        return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
                    })
                    ->addColumn('EMPLOYEE_STATUS', function ($json) {
                        $status = '-';
                        if(isset($json->EMPLOYEE_STATUS_TALENTA) && trim(strtolower($json->EMPLOYEE_STATUS_TALENTA)) == 'resigned')
                            $status = "<span class='text-danger'>".strtoupper($json->EMPLOYEE_STATUS_TALENTA)."</span>";
                        else if(isset($json->EMPLOYEE_STATUS_TALENTA) && trim(strtolower($json->EMPLOYEE_STATUS_TALENTA)) == 'active')
                            $status = "<span class='text-success'>".strtoupper($json->EMPLOYEE_STATUS_TALENTA)."</span>";
                        return $status;
                    })
                    ->addColumn('PHOTO', function($json) {
                        $image_photo = isset($json->IMAGE_PHOTO) && !empty($json->IMAGE_PHOTO) ? asset('/upload/profile_photo/'.$json->IMAGE_PHOTO) : '';
                        // $explode_avatar = explode('?', isset($json->EMPLOYEE_AVATAR_TALENTA) ? $json->EMPLOYEE_AVATAR_TALENTA : '') ;
                        if(!empty($image_photo))
                            $avatar = $image_photo;
                        // else if(isset($explode_avatar[0]))
                        //     $avatar = $explode_avatar[0];
                        else
                            $avatar = asset('/image/default-avatar.png');
                            // $avatar = 'https://talenta.s3-ap-southeast-1.amazonaws.com/avatar/blank.jpg';
                        return '<img src="https://i.stack.imgur.com/RvfGz.gif" style="width: 25px; height: 25px" data-src="'.$avatar.'"/>';
                    })
                    ->editColumn('EMPLOYEE_NAME', function ($json) {
                      return '<a href="/human-resource/employee-list/employee/view/' . $json->EMPLOYEE_ID . '/' . $json->SAP_COST_CENTER_ID.'"> '. $json->EMPLOYEE_NAME.'</a>'; 
                    })
                    ->rawColumns(['EMPLOYEE_NAME', 'NUM_ORDER', 'EMPLOYEE_STATUS', 'PHOTO'])
                    ->make(true);
                }
            } catch (\Exception $e) {
                $result['log']['file'] = $e->getFile();
                $result['log']['message'] = $e->getMessage();
                $result['log']['line'] = $e->getLine();

                $result['code'] = '500';
                $result['status'] = 'failed';
                $result['message'] = 'Internal Server Error';
                
                //bikin log
                Log::error(response()->json($result['log']));
                return response()->json($result, 500);
            } catch (\Illuminate\Database\QueryException $e) {
                $result['log']['file'] = $e->getFile();
                $result['log']['message'] = $e->getMessage();
                $result['log']['line'] = $e->getLine();

                $result['code'] = '502';
                $result['status'] = 'failed';
                $result['message'] = 'Database Error';
                
                //bikin log
                Log::error(response()->json($result['log']));
                return response()->json($result, 500);
            }
        }
        return response('Operation is forbidden', 401);
    }

    function employeeFilterGetEmployee(Request $request){
        try {
            $territory = $request->get('territory_code', null);
            $data_company_employee = new EmployeeModel();
            $to_return = $data_company_employee->getEmployeeTerritory($territory);
            return $to_return->count();

        } catch (\Exception $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '500';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';
            
            //bikin log
            Log::error(response()->json($result['log']));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '502';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';
            
            //bikin log
            Log::error(response()->json($result['log']));
            return $result;
        }

    }

    function employeeGetData(Request $request){
    	try{
            $filter = array();
            $territory = $request->get('TERRITORY_ID', null);
            $cost_center = $request->get('COST_CENTER_ID', null);

            if($territory)
                $filter['TERRITORY_ID'] = $territory;
            if($cost_center)
                $filter['SAP_COST_CENTER_ID'] = $cost_center;

            $data_company_employee = new EmployeeModel();
            $to_return = $data_company_employee->getEmployeeTerritory($filter);

            // Check apakah ada error dari model, jika ada return error array
            if(is_array($to_return) && in_array('code', array_keys($to_return)) && $to_return['status'] == 'failed'){
                return response()->json($to_return, 500);
            }
            // Jika tidak ada error
            else {
                // Return data as datatable format
    	    	return DataTables::of($to_return)
                ->editColumn('EMAIL', function ($json) {
                    if(isset($json->EMAIL) && $json->EMAIL)
                        return $json->EMAIL;
                    else
                        return "-";
                })
                ->editColumn('MOBILE_NUMBER_1', function ($json) {
                    if(isset($json->MOBILE_NUMBER_1) && $json->MOBILE_NUMBER_1)
                        return $json->MOBILE_NUMBER_1;
                    else
                        return "-";
                })
                ->addColumn('NUM_ORDER', function ($json) {
                    $this->iterable = $this->iterable + 1;
                    return $this->iterable;
                })
                ->addColumn('JOB_TITLE_NAME', function ($json) {
                   if(isset($json->JOB_TITLE) && $json->JOB_TITLE)
                        return $json->JOB_TITLE;
                    else
                        return "-";
                })
                ->addColumn('COST_CENTER', function ($json) {
                   if(isset($json->SAP_COST_CENTER_DESCRIPTION) && $json->SAP_COST_CENTER_DESCRIPTION)
                        return $json->SAP_COST_CENTER_DESCRIPTION;
                    else
                        return "-";
                })
                ->addColumn('TERRITORY', function ($json) {
                   if(isset($json->TERRITORY_NAME) && $json->TERRITORY_NAME)
                        return $json->TERRITORY_NAME;
                    else
                        return "-";
                })
                ->editColumn('EMPLOYEE_NAME', function ($json) {
                  return '<a href="/human-resource/employee-list/employee/view/' . $json->EMPLOYEE_ID . '/' . $json->SAP_COST_CENTER_ID.'"> '. $json->EMPLOYEE_NAME.'</a>'; 
                })
                ->rawColumns(['EMPLOYEE_NAME', 'MOBILE_NUM_1', "EMAIL"])
                ->make(true);
            }

    	} catch (\Exception $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '500';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';
            
            //bikin log
            Log::error(response()->json($result['log']));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '502';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';
            
            //bikin log
            Log::error(response()->json($result['log']));
            return $result;
        }
    }

    function employeeGetDataByCostCenter(Request $request){
        try{
            $cc = $request->get('cost_center', null);
            $data_company_employee = new EmployeeModel();
            $to_return = $data_company_employee->getEmployeeCostCenter($cc);

            // Check apakah ada error dari model, jika ada return error array
            if(is_array($to_return) && in_array('code', array_keys($to_return)) && $to_return['status'] == 'failed'){
                return response()->json($to_return, 500);
            }
            // Jika tidak ada error
            else {
                // Return data as datatable format
                return DataTables::of($to_return)
                ->editColumn('EMAIL', function ($json) {
                    if(isset($json->EMAIL) && $json->EMAIL)
                        return $json->EMAIL;
                    else
                        return "-";
                })
                ->editColumn('MOBILE_NUMBER_1', function ($json) {
                    if(isset($json->MOBILE_NUMBER_1) && $json->MOBILE_NUMBER_1)
                        return $json->MOBILE_NUMBER_1;
                    else
                        return "-";
                })
                ->addColumn('NUM_ORDER', function ($json) {
                    $this->iterable = $this->iterable + 1;
                    return $this->iterable;
                })
                ->addColumn('JOB_TITLE_NAME', function ($json) {
                   if(isset($json->JOB_TITLE) && $json->JOB_TITLE)
                        return $json->JOB_TITLE;
                    else
                        return "-";
                })
                ->addColumn('COST_CENTER', function ($json) {
                   if(isset($json->SAP_COST_CENTER_DESCRIPTION) && $json->SAP_COST_CENTER_DESCRIPTION)
                        return $json->SAP_COST_CENTER_DESCRIPTION;
                    else
                        return "-";
                })
                ->addColumn('TERRITORY', function ($json) {
                   if(isset($json->TERRITORY_NAME) && $json->TERRITORY_NAME)
                        return $json->TERRITORY_NAME;
                    else
                        return "-";
                })
                ->addColumn('ACTION', function ($json) {
                  return '<a href="/human-resource/employee-list/employee/view/' . $json->EMPLOYEE_ID . '/' . $json->SAP_COST_CENTER_ID.'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1"><i class="mdi mdi-eye"></i></a>'; 
                })
                ->rawColumns(['ACTION', 'MOBILE_NUM_1', "EMAIL"])
                ->make(true);
            }

        } catch (\Exception $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '500';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';
            
            //bikin log
            Log::error(response()->json($result['log']));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '502';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';
            
            //bikin log
            Log::error(response()->json($result['log']));
            return $result;
        }
    }

    public function employeeEdit($id, $sap_cost_center){
        try{   
            $data_edit = new EmployeeModel();
            $data = $data_edit->employeeGet($id, $sap_cost_center);
            return view('pages.human-resource.employee.employee_edit', ['data' => $data->first(), 'data_found'=> $data->count()]);

        } catch (\Exception $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '500';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';
            
            //bikin log
            Log::error(response()->json($result['log']));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $result['log']['file'] = $e->getFile();
            $result['log']['message'] = $e->getMessage();
            $result['log']['line'] = $e->getLine();

            $result['code'] = '502';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';
            
            //bikin log
            Log::error(response()->json($result['log']));
            return $result;
        }
    }

}
