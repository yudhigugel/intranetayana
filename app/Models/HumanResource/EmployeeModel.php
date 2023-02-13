<?php

namespace App\Models\HumanResource;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Log;
use Validator;


class EmployeeModel extends Model
{
    use HasFactory;
    protected $table = 'INT_EMPLOYEE';
    protected $primaryKey = 'EMPLOYEE_ID';
    public $incrementing = false; // PENTING supaya tau kalau primary key nya itu bukan auto increment
    // public $timestamps = false;
    const CREATED_AT = 'DATE_CREATED';
    const UPDATED_AT = 'DATE_MODIFIED';
    protected $connection = 'dbintranet'; // set connection default untuk model ini
    protected $guarded = [];

    public function custom_plant_access(){
        $relation = $this->belongsToMany('App\Models\HumanResource\EmployeeModel', 'INT_CUSTOM_ACCESS', 'EMPLOYEE_ID', 'EMPLOYEE_ID');
        return $relation;
    }

    public function getAllEmployee(){
        try {
            $data = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE');
            return $data;

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

    public function getCompany(){
        try {
            $data = DB::connection('dbintranet')
            ->table('INT_COMPANY')
            ->select('COMPANY_CODE', 'COMPANY_NAME', 'STATUS')
            ->get();
            return $data;

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

    public function getPlant($company_code){
        try {
            $data = DB::connection('dbintranet')
            ->table('INT_BUSINESS_PLANT')
            ->select('COMPANY_CODE','SAP_PLANT_ID AS BUSINESS_UNIT_CODE', 'SAP_PLANT_NAME AS BUSINESS_UNIT_NAME', 'STATUS')
            ->where('COMPANY_CODE', $company_code)
            ->get();
            return $data;

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

    public function getTerritory($plant_code){
        try {
            $data = DB::connection('dbintranet')
            ->table('INT_TERRITORY')
            ->select('TERRITORY_ID','TERRITORY_NAME', 'TERRITORY_CODE', 'STATUS')
            ->where(['SAP_PLANT_ID'=>$plant_code])
            ->get();
            return $data;

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

    public function employeeSearch($data_array=array())
    {
        try {            
            $data = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE')
            // ->where(DB::raw('lower(EMPLOYEE_STATUS_TALENTA)'), 'active')
            ->where($data_array)
            ->get();
            // $data = DB::connection('dbintranet')
            // ->table('dbo.VIEW_EMPLOYEE_WITH_SUPERIOR')
            // ->where($data_array)
            // ->get();

            return $data;

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

    public function employeeSearchWithSuperior($data_array=array())
    {
        try {            
            $data = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE_WITH_SUPERIOR')
            ->where($data_array)
            ->get();

            return $data;

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

    public function employeeSearchAssignment($data_array=array())
    {
        try {            
            $data = DB::connection('dbintranet')
            ->table('dbo.INT_EMPLOYEE')
            ->leftJoin('dbo.VIEW_EMPLOYEE', function($join){
                $join->on('dbo.INT_EMPLOYEE.EMPLOYEE_ID','=','dbo.VIEW_EMPLOYEE.EMPLOYEE_ID');
            })->select('dbo.VIEW_EMPLOYEE.COMPANY_CODE', 'dbo.VIEW_EMPLOYEE.SAP_PLANT_ID')
            ->where($data_array)
            ->get()->pluck('SAP_PLANT_ID', 'COMPANY_CODE')->filter()->toArray();

            return $data;

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

    public function employeeGet($id, $sap_cost_center_id)
    {
        try {            
            $data = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE')
            ->where(['EMPLOYEE_ID'=>$id,'SAP_COST_CENTER_ID'=>$sap_cost_center_id])
            ->get();

            return $data;

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

    public function getEmployeeTerritory($territory_code){
        try {
            $data = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE')
            ->where($territory_code)
            ->get();
            return $data;

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

    public function getEmployeeCostCenter($cost_center_id){
        try {
            $data = DB::connection('dbintranet')
            ->table('dbo.VIEW_EMPLOYEE')
            ->where(['SAP_COST_CENTER_ID'=>$territory_code])
            ->get();
            return $data;

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

    // The rest code below is deprecated
    public function employeeList($selectData=array('*'), $companyCode=array()){
    	try {
    		if($companyCode){
		    	$data = DB::connection('dbintranet')
		    	->table('INT_EMPLOYEE_DETAIL')
                ->select($selectData)
                ->leftJoin('INT_MAP_GRADING_MIDJOB_OLD', 'INT_EMPLOYEE_DETAIL.MIDJOB_TITLE_ID', '=', 'INT_MAP_GRADING_MIDJOB_OLD.OLD_MIDJOB_ID')
                ->leftJoin('INT_MAP_COSTCENTER', 'INT_EMPLOYEE_DETAIL.SAP_COSTCENTER_ID', '=', 'INT_MAP_COSTCENTER.SAP_COST_CENTER_ID')
                ->leftJoin('INT_MAP_TERRITORY', 'INT_MAP_TERRITORY.TERRITORY_ID', '=', 'INT_MAP_COSTCENTER.TERRITORY_ID')
                ->leftJoin('INT_MAP_BUSINESS_PLANT', 'INT_EMPLOYEE_DETAIL.COMPANY_CODE', '=', 'INT_MAP_BUSINESS_PLANT.SAP_PLANT_ID')
		    	->where('INT_EMPLOYEE_DETAIL.EMPLOYEE_STATUS', 1)
                ->where($companyCode)
                ->orderBy('INT_MAP_GRADING_MIDJOB_OLD.OLD_MIDJOB_ID', 'ASC')
		    	->get();
	    	}
	    	else{
	    		$data = DB::connection('dbintranet')
		    	->table('INT_EMPLOYEE_DETAIL')
                ->select($selectData)
                ->leftJoin('INT_MAP_GRADING_MIDJOB_OLD', 'INT_EMPLOYEE_DETAIL.MIDJOB_TITLE_ID', '=', 'INT_MAP_GRADING_MIDJOB_OLD.OLD_MIDJOB_ID')
                ->leftJoin('INT_MAP_COSTCENTER', 'INT_EMPLOYEE_DETAIL.SAP_COSTCENTER_ID', '=', 'INT_MAP_COSTCENTER.SAP_COST_CENTER_ID')
                ->leftJoin('INT_MAP_TERRITORY', 'INT_MAP_TERRITORY.TERRITORY_ID', '=', 'INT_MAP_COSTCENTER.TERRITORY_ID')
                ->leftJoin('INT_MAP_BUSINESS_PLANT', 'INT_EMPLOYEE_DETAIL.COMPANY_CODE', '=', 'INT_MAP_BUSINESS_PLANT.SAP_PLANT_ID')
                ->where('INT_EMPLOYEE_DETAIL.EMPLOYEE_STATUS', 1)
                ->orderBy('INT_MAP_GRADING_MIDJOB_OLD.OLD_MIDJOB_ID', 'ASC')
		    	->get();
	    	}
	    	
	    	return $data;

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
