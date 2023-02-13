<?php

namespace App\Http\Controllers\SAP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;
use Log;
use App\Models\Zoho\ZohoFormModel;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use App\Http\Controllers\Traits\IntranetTrait;
use Illuminate\Support\Facades\View;

class Reservation extends Controller
{
	// only allow movement type code 311 and starts with Y
	// private $allowed_movement_type = ['311', 'Y'];
	use IntranetTrait;

	public function __construct()
    {
        $this->form_number = "RSV";
        $this->form_view="VIEW_FORM_REQUEST_ADD_RESERVATION";
        $this->approval_view="VIEW_APPROVAL_FORM_REQUEST_ADD_RESERVATION";
        $this->approval_view_link="/finance/add-reservation/approval";
        $this->link_request = "/finance/add-reservation/request";
    }

    public function insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type){
        $data_approval = DB::connection('dbintranet')
            ->table(DB::raw($this->approval_view))
            ->where('UID',$uid)
            ->get();

        if(!$data_approval->isEmpty()){
            $data_approval=collect($data_approval[0])->toArray();

            $i = $level_approval; // mencari approval selanjutnya untuk diberikan notif
            $appr_midjob=isset($data_approval['APPROVAL_'.$i.'_MIDJOB_ID']) ? $data_approval['APPROVAL_'.$i.'_MIDJOB_ID'] : NULL;
            $appr_employeeId=isset($data_approval['APPROVAL_'.$i.'_EMPLOYEE_ID']) ? $data_approval['APPROVAL_'.$i.'_EMPLOYEE_ID'] : NULL;

            // cek dulu apakah di tabel approval mapping untuk tingkatan itu ada Employee ID
            // karena employee ID akan override dari semua field lain, maka dahulukan dulu cek employee ID pada tingkatan tersebut
            if(!empty($appr_employeeId)){
                $select = "SELECT EMPLOYEE_ID FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID ='".$appr_employeeId."' ";
            }else if(!empty($appr_midjob)){
                $select = "SELECT EMPLOYEE_ID FROM VIEW_EMPLOYEE WHERE MIDJOB_TITLE_ID ='".$appr_midjob."' ";
                $appr_plant=($data_approval['APPROVAL_'.$i.'_PLANT_ID']) ? " AND SAP_PLANT_ID='".$data_approval['APPROVAL_'.$i.'_PLANT_ID']."'" : NULL;
                $appr_territory=($data_approval['APPROVAL_'.$i.'_TERRITORY_ID']) ? " AND TERRITORY_ID='".$data_approval['APPROVAL_'.$i.'_TERRITORY_ID']."'" : NULL;
                $select .= $appr_plant.$appr_territory;
            }

            if(!empty($select)){
                $emp_appr=DB::connection('dbintranet')
                ->select($select);
                foreach($emp_appr AS $notif_appr){
                    $notif_employee_id=$notif_appr->EMPLOYEE_ID;
                    $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type); // insert notif
                }
            }

        }
    }

    public function request(Request $request){
    	//init RFC
        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc'));
        $options = [
            'rtrim'=>true,
            'use_function_desc_cache'=>false
        ];
        // SapConnection::setTraceLevel(3);
        //===

        /* Jika request berupa ajax untuk mencari material */
        if($this->wantsJson($request)) {
        	if($request->get('type', false) && strtolower($request->get('type')) == 'material'){
        		$data_material = [];
        		$keyword = strtoupper($request->get('searchTerm', ''));
        		$plant_code = $request->get('plant', '');
        		$sloc = $request->get('sloc', '');
        		$company_code = '';

		        $param = array(
		            'GV_MAKTX'=>"*".$keyword."*",
		            'GV_ACCTASSCAT'=>'',
		            'GV_WERKS'=>$plant_code,
		            'GV_MAX_ROWS'=>30
		        );

		        $function_type = $rfc->getFunction('ZFM_POPUP_MAT_BDT_INTRA_MONTH');
		        $list_material= $function_type->invoke($param, $options);
		        $list_material = isset($list_material['GI_HEADER']) ? $list_material['GI_HEADER'] : [];
		        // dd($list_material);

                $check_company = DB::connection('dbintranet')
                ->table('INT_BUSINESS_PLANT')
                ->where('SAP_PLANT_ID', $plant_code)
                ->select('COMPANY_CODE')->get()->first();
                if($check_company){
                    $company_code = $check_company->COMPANY_CODE;
                }
                else{
                    Log::error("MATERIAL SEARCH ERROR RESERVATION | No Company Code Found for plant ${plant_code}");
                }

		        foreach ($list_material as $key => $value) {
			        $param = [
			            'P_COMPANY'=>$company_code,
			            'P_PLANT'=>$plant_code,
			            'P_MATERIAL'=>$value['MATNR'],
			            'P_SLOG'=>$sloc
			        ];
                    try {
			           $function_type = $rfc->getFunction('ZFM_MM_MATERIAL_STOCK');
			           $invoke = $function_type->invoke($param, $options);
			           $is_data_available = isset($invoke['IT_DATA']) ? $invoke['IT_DATA'] : [];
                       $last_price = 0;
                       $stock_saat_ini = number_format(isset($is_data_available[0]['QTY']) ? $is_data_available[0]['QTY'] : 0, 2). "  ".$value['MEINS'];
                       if(count($is_data_available) > 0){
                            $is_sloc_dest = false;
                            try {
                                $param = array(
                                    'WERKS'=>$plant_code,
                                    'IT_MATERIAL'=>array(
                                        [
                                            'MATNR'=>$value['MATNR'],
                                            'MEINS'=>$value['MEINS'],
                                            'MENGE'=>1
                                        ]
                                    )
                                );

                                $function_type = $rfc->getFunction('YFM_GET_MAT_COST_3');
                                $last_price= $function_type->invoke($param, $options);
                                $last_price = isset($last_price['MAT_PRICE'][0]['DMBTR']) ? $last_price['MAT_PRICE'][0]['DMBTR'] : 0;

                                if($request->get('mv_type') && $request->get('mv_type') == '311'){
                                    $dest_sloc = isset(explode('-', $request->get('destination_sloc'))[0]) ? trim(explode('-', $request->get('destination_sloc'))[0]) : '';
                                    $param_sloc_dest = [
                                        'P_COMPANY'=>$company_code,
                                        'P_PLANT'=>$plant_code,
                                        'P_MATERIAL'=>$value['MATNR'],
                                        'P_SLOG'=>$dest_sloc
                                    ];
                                    $function_type = $rfc->getFunction('ZFM_MM_MATERIAL_STOCK');
                                    $invoke = $function_type->invoke($param_sloc_dest, $options);
                                    $is_available_sloc_dest = isset($invoke['IT_DATA']) ? $invoke['IT_DATA'] : [];
                                    if(count($is_available_sloc_dest) > 0){
                                        $is_sloc_dest = true;
                                    }
                                }

                                $data_material[] = array("id"=>isset($value['MATNR']) ? $value['MATNR'] : '', "text"=>isset($value['MAKTX']) ? $value['MAKTX'] : 'Unknown Material', "html"=>isset($value['MAKTX']) ? "<div>".$value['MAKTX']."</div><div><small style='font-size:11px;'>Available Stock : ".$stock_saat_ini."</small></div>" : '<div>Unknown Material</div>', 'unit'=>isset($value['MEINS']) ? $value['MEINS'] : '', 'last_price'=>number_format($last_price, 2), 'last_price_plain'=>$last_price, 'title'=>isset($value['MAKTX']) ? $value['MAKTX'] : 'Unknown Material', 'is_available_sloc_destination'=>$is_sloc_dest);
                            } catch(SAPFunctionException $e){}
                        }

                    } catch(SAPFunctionException $e){
                        $message = isset($e->getErrorInfo()['message']) ? $e->getErrorInfo()['message'] : $e->getMessage();
                        Log::error($message);
                    }
		        }
		        return response()->json($data_material);
	    	}

	    	else if($request->get('type', false) && strtolower($request->get('type')) == 'material_last_price'){
	    		$return_data = ['status'=>'no_status', 'last_price'=>0, 'last_price_plain'=>0];
	    		try {
		    		$material = $request->get('material', '0000');
		    		$unit = $request->get('unit', 'PC');
		    		$qty = $request->get('qty', 1);
		    		$plant_code = $request->get('plant');

		    		$param = array(
						'WERKS'=>$plant_code,
						'IT_MATERIAL'=>array(
							[
								'MATNR'=>$material,
								'MEINS'=>$unit,
								'MENGE'=>(float)$qty
							]
						)
					);
					$function_type = $rfc->getFunction('YFM_GET_MAT_COST_3');
					$last_price= $function_type->invoke($param, $options);
					$last_price = isset($last_price['MAT_PRICE'][0]['DMBTR']) ? $last_price['MAT_PRICE'][0]['DMBTR'] : 0;
					$return_data['status'] = 'success';
					$return_data['last_price'] = number_format($last_price, 2);
					$return_data['last_price_plain'] = $last_price;


				} catch(SAPFunctionException $e){
					$return_data['status'] = 'failed';
					$return_data['error'] = $e->getMessage();
					return response()->json($return_data, 405);
				} catch(\Exception $e){
					$return_data['status'] = 'failed';
					$return_data['error'] = $e->getMessage();
					return response()->json($return_data, 403);
				}
				return response()->json($return_data, 200);
	    	}

            else if($request->get('type', false) && strtolower($request->get('type')) == 'sloc'){
                $sap_sloc = [];
                try {
                    $param_sloc = [
                        'P_COMPANY'=>"",
                        'P_PLANT'=>$request->get('plant_code', false)
                    ];

                    $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
                    $result= $function->invoke($param_sloc, $options);
                    $sap_sloc = isset($result['IT_SLOC']) ? $result['IT_SLOC'] : [];
                    return response()->json(['status'=>'success', 'sloc'=>$sap_sloc], 200);
                } catch(SAPFunctionException $e){
                    Log::error('SLOC RESERVATION REQUEST SAP ERROR | '. $e->getMessage());
                    return response()->json(['status'=>'error', 'error'=>$e->getMessage()], 402);
                } catch(\Exception $e){
                    Log::error('SLOC RESERVATION REQUEST GENERAL ERROR | '. $e->getMessage());
                    return response()->json(['status'=>'error', 'error'=>$e->getMessage()], 402);
                }
            }

            else if($request->get('type', false) && strtolower($request->get('type')) == 'material_sloc_existence'){
                $plant_code = $request->get('plant', '');
                $sloc = $request->get('sloc', '');
                $material = $request->get('material_no', '');
                $company_code = '';
                $check_company = DB::connection('dbintranet')
                ->table('INT_BUSINESS_PLANT')
                ->where('SAP_PLANT_ID', $plant_code)
                ->select('COMPANY_CODE')->get()->first();
                if($check_company){
                    $company_code = $check_company->COMPANY_CODE;
                }
                else{
                    Log::error("MATERIAL SLOC EXISTENCE CHECK ERROR RESERVATION | No Company Code Found for plant ${plant_code}");
                }

                $param = [
                    'P_COMPANY'=>$company_code,
                    'P_PLANT'=>$plant_code,
                    'P_MATERIAL'=>$material,
                    'P_SLOG'=>$sloc
                ];
                try {
                   $function_type = $rfc->getFunction('ZFM_MM_MATERIAL_STOCK');
                   $invoke = $function_type->invoke($param, $options);
                   $is_data_available = isset($invoke['IT_DATA']) ? $invoke['IT_DATA'] : [];
                   if(count($is_data_available) > 0){
                    return response()->json(['status'=>'success', 'is_available'=>true], 200);
                   } else {
                    return response()->json(['status'=>'success', 'is_available'=>false], 200);
                   }
                } catch(SAPFunctionException $e){
                    Log::error('MATERIAL SLOC EXISTENCE CHECK RESERVATION SAP ERROR | '. $e->getMessage());
                    return response()->json(['status'=>'error', 'error'=>$e->getMessage()], 402);
                } catch(\Exception $e){
                    Log::error('MATERIAL SLOC EXISTENCE CHECK RESERVATION GENERAL ERROR | '. $e->getMessage());
                    return response()->json(['status'=>'error', 'error'=>$e->getMessage()], 402);
                }
            }
        }

    	$employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $cost_center_name = "SYSADMIN";
            $job_title ="SYSADMIN";
            $midjob_id ="SYSADMIN";
        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $cost_center_name = Session::get('assignment')[0]->SAP_COST_CENTER_DESCRIPTION;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
            $midjob_id =Session::get('assignment')[0]->MIDJOB_TITLE_ID;
        }

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        // START -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST
        $cek_alur_approval=DB::connection('dbintranet')
        ->table('INT_FORM_APPROVAL_MAPPING')
        ->where('FORMAPPROVAL_REQUESTOR_COSTCENTER', $cost_center_id)
        ->where('FORMAPPROVAL_REQUESTOR_MIDJOB', $midjob_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'RSV')
        ->orWhere('FORMAPPROVAL_REQUESTOR_EMPLOYEE_ID',$employee_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'RSV')
        ->get();
        
        $itung_alur_approval=count($cek_alur_approval);
        $allow_add_request=($itung_alur_approval>0)? true : false;
        $check_cross_plant_user = DB::connection('dbintranet')->table('INT_FORM_APPROVAL_MAPPING_CROSS_USER')->where('EMPLOYEE_ID',$employee_id)->where('TYPE_FORM','RSV')->count();
        $is_cross_plant_user = ($check_cross_plant_user>0)? true : false;
        // END -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST

        // Load all sloc berdasarkan plant
        $param_sloc = [
            'P_COMPANY'=>"",
            'P_PLANT'=>$plant
        ];

        $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
        $result= $function->invoke($param_sloc, $options);
        $sap_sloc = isset($result['IT_SLOC']) ? $result['IT_SLOC'] : [];
        // End load sloc

        // load movement type master
        $mv_type = [];
        try {
        	$mv_type = DB::connection('dbintranet')
        	->table('dbo.INT_MASTER_MOVEMENT_TYPE')
        	->select('MV_TYPE', 'MV_DESCRIPTION')
        	->get()->toArray();
        } catch(\Exception $e){}
        // end movement type master

        // Check for custom cost center
        $custom_cost_center = [];
        try {
        	$custom_cost_center = DB::connection('dbintranet')
        	->table('dbo.SAP_RSV_EMP_COSTCENTER_MAPPING AS cmp')
        	->where(['cmp.EMPLOYEE_ID'=>$employee_id, 'cmp.PLANT_ID'=>$plant])
        	->leftJoin('dbo.INT_SAP_COST_CENTER AS cc', 'cmp.SAP_COSTCENTER_ID', '=', 'cc.SAP_COST_CENTER_ID')
        	->select('cmp.SAP_COSTCENTER_ID', 'cc.SAP_COST_CENTER_DESCRIPTION')
        	->get()->toArray();
    	} catch(\Exception $e){}

        $plant_list = [];
        try {
            $plant_list=DB::connection('dbintranet')
            ->table('dbo.INT_BUSINESS_PLANT as p')
            ->select('p.SAP_PLANT_ID', 'p.SAP_PLANT_NAME')
            ->orderBy('SAP_PLANT_ID','ASC')
            ->distinct()->get()->toArray();
        } catch(\Exception $e){}

        $material_uom = [];
        // try {
        //     $param = array();
        //     $function_measurement = $rfc->getFunction('ZFM_MD_UOM_LIST');
        //     $material_uom = $function_measurement->invoke($param, $options)['IT_DATA'];
        // } catch(SAPFunctionException $e){}

        $data=array(
	        'company_code'=>$company_code,
	        'plant'=>$plant,
            'plant_list'=>$plant_list,
	        'plant_name'=>$plant_name,
	        'employee_id'=>$employee_id,
	        'employee_name'=>$employee_name,
	        'division'=>$division,
	        'department'=>$department,
	        'cost_center_id'=>$cost_center_id,
	        'cost_center_name'=>$cost_center_name,
	        'custom_cost_center'=>$custom_cost_center,
	        'status'=>$status,
	        'job_title'=>$job_title,
	        'territory_id'=>$territory_id,
	        'territory_name'=>$territory_name,
	        'request_date_from'=>$request_date_from,
	        'request_date_to'=>$request_date_to,
	        'form_code'=>$this->form_number,
	        'allow_add_request'=>$allow_add_request,
	        'is_cross_plant_user'=>$is_cross_plant_user,
	        's_loc'=>$sap_sloc,
	        'movement_type'=>$mv_type,
            'material_unit'=>$material_uom
        );

    	return view('pages.finance.reservation.request', ['data'=>$data]);
    }

    public function request_getData(Request $request){
        try{
            $employee_id=$request->input('employee_id');
            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $status=strtoupper($request->input('status'));
            $request_type=strtoupper($request->input('request_type'));

            if ($request_type == 'REPORT'){
                $where = "ID <> '' ";
            }else{
                $where = "REQUESTOR_ID = '".$employee_id."' ";
            }

            if (($filter != null or $filter !="")&&($value != null or $value !="")){
                if ($filter == "All"){
                    $where = $where." and (SUBJECT like '%".$value."%'";
                    $where = $where." or REQUESTOR_NAME like '%".$value."%')";
                }
                else
                {
                    $where = $where." and ".$filter." like '%".$value."%'";
                }
            }

            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                $where = $where." and REQUEST_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
            }

            if ($status != null or $status !=""){
                $where = $where." and STATUS_APPROVAL = '".$status."'";
            }

            DB::connection('dbintranet')->enableQueryLog();
            $data = DB::connection('dbintranet')
            ->table(DB::raw($this->form_view))
            ->whereraw(DB::raw($where))
            ->orderBy('INSERT_DATE', 'DESC')->get();

            $result=array();
            foreach($data as $key=>$value){
                $data_json=json_decode($value->JSON_ENCODE);
            	$rcv_sloc_cc = '-';
            	$cek_movement = isset($data_json->MovementType) ? $data_json->MovementType : '';
            	try {
            		$cek_movement = DB::connection('dbintranet')
            		->table('dbo.INT_MASTER_MOVEMENT_TYPE')
            		->where('MV_TYPE', $cek_movement)
            		->select('MV_TYPE', 'MV_DESCRIPTION')
            		->get()->first();
            		if($cek_movement)
            			$cek_movement = $cek_movement->MV_TYPE." - (".$cek_movement->MV_DESCRIPTION.")";

            	} catch(\Exception $e){}

            	if(isset($data_json->rsvReceivingSLOC)){
            		$rcv_sloc_cc = $data_json->rsvReceivingSLOC;
            	} else if(isset($data_json->rsvCostCenterExpense)){
            		$rcv_sloc_cc = $data_json->rsvCostCenterExpense;
            	} else if(isset($data_json->rsvReceivingSLOCDesc)){
                    $rcv_sloc_cc = $data_json->rsvReceivingSLOCDesc;
                }

                $result[]=array(
                    'UID'=>$value->UID,
                    'SAP_RSV_NO'=>isset($data_json->RESERVATION_NO_SAP) ? ltrim($data_json->RESERVATION_NO_SAP, "0") : ' - ',
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'INSERT_DATE'=>$value->INSERT_DATE,
                    'CREATED_FOR_DATE'=>isset($data_json->CreatedDate) ? date('Y-m-d', strtotime($data_json->CreatedDate)) : ' - ',
                    'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                    'MOVEMENT_TYPE'=>$cek_movement,
                    'RCV_SLOC_CC'=>$rcv_sloc_cc,
                    'GRAND_TOTAL'=>isset($data_json->rsvGrandTotal) ? number_format($data_json->rsvGrandTotal, 2) : 0
                );
            }

            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function report(Request $request){
    	//init RFC
        $is_production = config('intranet.is_production');
        if($is_production)
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        else
            $rfc = new SapConnection(config('intranet.rfc'));
        $options = [
            'rtrim'=>true,
        ];
        //===

    	$employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $department_id="SYSADMIN";
            $company_code="SYSADMIN";
            $plant_name="SYSADMIN";
            $plant="SYSADMIN";
            $midjob_id="SYSADMIN";
            $costcenter="SYSADMIN";
            $territory_id="SYSADMIN";
            $territory_name="SYSADMIN";
            $cost_center_id="SYSADMIN";
            $cost_center_name="SYSADMIN";
        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $department_id=Session::get('assignment')[0]->DEPARTMENT_ID;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $midjob_id=Session::get('assignment')[0]->MIDJOB_TITLE_ID;
            $costcenter=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
            $cost_center_name = Session::get('assignment')[0]->SAP_COST_CENTER_DESCRIPTION;
        }

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        // START -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST
        $cek_alur_approval=DB::connection('dbintranet')
        ->table('INT_FORM_APPROVAL_MAPPING')
        ->where('FORMAPPROVAL_REQUESTOR_COSTCENTER', $cost_center_id)
        ->where('FORMAPPROVAL_REQUESTOR_MIDJOB', $midjob_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'BP')
        ->orWhere('FORMAPPROVAL_REQUESTOR_EMPLOYEE_ID',$employee_id)
        ->get();
        
        $itung_alur_approval=count($cek_alur_approval);
        $allow_add_request=($itung_alur_approval>0)? true : false;
        $check_cross_plant_user = DB::connection('dbintranet')->table('INT_FORM_APPROVAL_MAPPING_CROSS_USER')->where('EMPLOYEE_ID',$employee_id)->where('TYPE_FORM','RSV')->count();
        $is_cross_plant_user = ($check_cross_plant_user>0)? true : false;
        // END -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST

        $param_sloc = [
            'P_COMPANY'=>"",
            'P_PLANT'=>$plant
        ];

        $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
        $result= $function->invoke($param_sloc, $options);
        $sap_sloc = isset($result['IT_SLOC']) ? $result['IT_SLOC'] : [];

        $mv_type = [];
        try {
        	$mv_type = DB::connection('dbintranet')
        	->table('dbo.INT_MASTER_MOVEMENT_TYPE')
        	->select('MV_TYPE', 'MV_DESCRIPTION')
        	->get()->toArray();
        } catch(\Exception $e){}

        $data=array(
	        'company_code'=>$company_code,
            'plant'=>$plant,
            'plant_name'=>$plant_name,
            'employee_id'=>$employee_id,
            'employee_name'=>$employee_name,
            'division'=>$division,
            'department'=>$department,
            'department_id'=>$department_id,
            'midjob_id'=>$midjob_id,
            'costcenter'=>$costcenter,
            'status'=>$status,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
            'form_code'=>$this->form_number,
	        's_loc'=>$sap_sloc,
	        'movement_type'=>$mv_type
        );

    	return view('pages.finance.reservation.report', ['data'=>$data]);
    }

    public function report_getData(Request $request){
        $result=array();
        try{

            $deptId=$request->input('deptId');
            $employeeId=$request->input('employeeId');
            $midjobId=$request->input('midjobId');
            $costcenter=$request->input('costcenter');
            $plant=isset(Session::get('assignment')[0]->SAP_PLANT_ID) ? Session::get('assignment')[0]->SAP_PLANT_ID : ''; //dapetin plant user yang login skrg
            $territory=isset(Session::get('assignment')[0]->TERRITORY_ID) ? Session::get('assignment')[0]->TERRITORY_ID : ''; //dapetin plant user yang login skrg

            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $status=strtoupper($request->input('status'));
            $requestor_name=$request->input('REQUESTOR_NAME', false);

            if($requestor_name){
                $where = "REQUESTOR_NAME='${requestor_name}' AND (";
            } else {
                $where="( ";
            }
            //start looping approval
            for ($i=0; $i<=7;$i++){
                $j=$i+1; // init variable untuk level approval sebenernya
                $prepend="";
                $append="";
                if($i>0){
                    $prepend="OR (";
                    $append=")";
                }
                // approval untuk superior, approval level 0 berarti belum ada approval sama sekali
                $where .=$prepend." APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                AND  APPROVAL_".$j."_TERRITORY_ID =
                CASE WHEN (
                    SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->form_view."
                    where APPROVAL_".$j."_PLANT_ID='".$plant."'
                    AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                ) = '0' THEN '0'
                WHEN (
                    SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->form_view."
                    where APPROVAL_".$j."_PLANT_ID='".$plant."'
                    AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                ) <> '0' THEN '".$territory."'
                END
                OR APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                ".$append."
                ";
            }
            $where = $where . " ) ";

            if (($filter != null or $filter !="")&&($value != null or $value !="")){
                if ($filter == "All"){
                    $where = $where." and (SUBJECT like '%".$value."%'";
                    $where = $where." or REQUESTOR_NAME like '%".$value."%')";
                }
                else
                {
                    $where = $where." and ".$filter." like '%".$value."%'";
                }
            }

            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                $where = $where." and REQUEST_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
            }

            if(!empty($status)){
                $where = $where . " AND STATUS_APPROVAL ='$status' ";
            }

            $data = DB::connection('dbintranet')
                ->table(DB::raw($this->approval_view))
                ->whereraw(DB::raw($where))
                ->get()->unique('UID');

            foreach($data as $key=>$value){
                $data_json=json_decode($value->JSON_ENCODE);
                $rcv_sloc_cc = '-';
                $cek_movement = isset($data_json->MovementType) ? $data_json->MovementType : '';
                try {
                    $cek_movement = DB::connection('dbintranet')
                    ->table('dbo.INT_MASTER_MOVEMENT_TYPE')
                    ->where('MV_TYPE', $cek_movement)
                    ->select('MV_TYPE', 'MV_DESCRIPTION')
                    ->get()->first();
                    if($cek_movement)
                        $cek_movement = $cek_movement->MV_TYPE." - (".$cek_movement->MV_DESCRIPTION.")";

                } catch(\Exception $e){}

                if(isset($data_json->rsvReceivingSLOC)){
                    $rcv_sloc_cc = $data_json->rsvReceivingSLOC;
                } else if(isset($data_json->rsvCostCenterExpense)){
                    $rcv_sloc_cc = $data_json->rsvCostCenterExpense;
                } else if(isset($data_json->rsvReceivingSLOCDesc)){
                    $rcv_sloc_cc = $data_json->rsvReceivingSLOCDesc;
                }
                
                $result[]=array(
                    'UID'=>$value->UID,
                    'SAP_RSV_NO'=>isset($data_json->RESERVATION_NO_SAP) ? ltrim($data_json->RESERVATION_NO_SAP, "0") : ' - ',
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'INSERT_DATE'=>$value->INSERT_DATE,
                    'CREATED_FOR_DATE'=>isset($data_json->CreatedDate) ? date('Y-m-d', strtotime($data_json->CreatedDate)) : ' - ',
                    'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                    'MOVEMENT_TYPE'=>$cek_movement,
                    'RCV_SLOC_CC'=>$rcv_sloc_cc,
                    'APPROVAL_LEVEL'=>$value->APPROVAL_LEVEL,
                    'NAME'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                    'ALIAS'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                    'GRAND_TOTAL'=>isset($data_json->rsvGrandTotal) ? number_format($data_json->rsvGrandTotal, 2) : 0
                );
            }

            // return DataTables::of($result)->make(true);
        } catch(\Exception $e){
            Log::error('ADA ERROR DI RESERVATION REPORT GETDATA | '.(string)$e);
            // return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
        return DataTables::of($result)->make(true);
    }

    public function approval(Request $request){
        $employee_id=Session::get('user_id');
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;

        if(empty(Session::get('assignment')[0])){
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $division="SYSADMIN";
            $department="SYSADMIN";
            $department_id="";
            $midjob_id="";
            $costcenter="";
        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $department_id=Session::get('assignment')[0]->DEPARTMENT_ID;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $midjob_id=Session::get('assignment')[0]->MIDJOB_TITLE_ID;
            $costcenter=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
        }

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        $data=array(
            'company_code'=>$company_code,
            'plant'=>$plant,
            'employee_id'=>$employee_id,
            'employee_name'=>$employee_name,
            'division'=>$division,
            'department_id'=>$department_id,
            'department'=>$department,
            'midjob_id'=>$midjob_id,
            'costcenter'=>$costcenter,
            'status'=>$status,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
            'form_code'=>$this->form_number
        );

        return view('pages.finance.reservation.approval', ['data' => $data]);
    }

    public function approval_getData(Request $request)
    {
        $result=array();
        try{
            DB::enableQueryLog();
            $deptId=$request->input('deptId');
            $employeeId=$request->input('employeeId');
            $midjobId=$request->input('midjobId');
            $costcenter=$request->input('costcenter');
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID; //dapetin plant user yang login skrg
            $territory=Session::get('assignment')[0]->TERRITORY_ID; //dapetin plant user yang login skrg

            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $requestor_name =$request->input('REQUESTOR_NAME', false);

            $where="";
            if($requestor_name){
	        	$where = $where."REQUESTOR_NAME='${requestor_name}' AND";
	        }

            $filter_date = '';
            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                $filter_date = "and REQUEST_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
            }

            // start looping approval
            for ($i=0; $i<=7;$i++){
                $j=$i+1; // init variable untuk level approval sebenernya
                $prepend="";
                $append="";
                if($i>0){
                    $prepend="OR (";
                    $append=")";
                }

                // query filter untuk approval
                if($requestor_name){
                    $where .=$prepend."
                    REQUESTOR_NAME='".$requestor_name."' AND APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    AND STATUS_APPROVAL <> 'REJECTED' AND  APPROVAL_".$j."_TERRITORY_ID =
                    CASE WHEN (
                        SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->approval_view."
                        where APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."'
                        AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    ) = '0' THEN '0'
                    WHEN (
                        SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->approval_view."
                        where APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."'
                        AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    ) <> '0' THEN '".$territory."'
                    END
                    ${filter_date}
                    OR REQUESTOR_NAME='".$requestor_name."' AND APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                    AND APPROVAL_LEVEL = ".$i." AND STATUS_APPROVAL <> 'REJECTED' ${filter_date}
                    ".$append."
                    ";
                } else {
                    $where .=$prepend."
                    APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    AND STATUS_APPROVAL <> 'REJECTED' AND  APPROVAL_".$j."_TERRITORY_ID =
                    CASE WHEN (
                        SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->approval_view."
                        where APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."'
                        AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    ) = '0' THEN '0'
                    WHEN (
                        SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->approval_view."
                        where APPROVAL_LEVEL = ".$i." AND APPROVAL_".$j."_PLANT_ID='".$plant."'
                        AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    ) <> '0' THEN '".$territory."'
                    END
                    ${filter_date}
                    OR APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                    AND APPROVAL_LEVEL = ".$i." AND STATUS_APPROVAL <> 'REJECTED' ${filter_date}
                    ".$append."
                    ";
                }
            }


            //=============================================================

	        if (($filter != null or $filter !="")&&($value != null or $value !="")){
	            if ($filter == "All"){
	                $where = $where." and (SUBJECT like '%".$value."%'";
	                $where = $where." or REQUESTOR_NAME like '%".$value."%')";
	            }
	            else
	            {
	                $where = $where." and ".$filter." like '%".$value."%'";
	            }
	        }

            $data = DB::connection('dbintranet')
                ->table(DB::raw($this->approval_view))
                ->whereraw(DB::raw($where))
                ->orderBy('INSERT_DATE', 'ASC')->get();

            foreach($data as $key=>$value){
                $data_json=json_decode($value->JSON_ENCODE);
            	$rcv_sloc_cc = '-';
            	$cek_movement = isset($data_json->MovementType) ? $data_json->MovementType : '';
            	try {
            		$cek_movement = DB::connection('dbintranet')
            		->table('dbo.INT_MASTER_MOVEMENT_TYPE')
            		->where('MV_TYPE', $cek_movement)
            		->select('MV_TYPE', 'MV_DESCRIPTION')
            		->get()->first();
            		if($cek_movement)
            			$cek_movement = $cek_movement->MV_TYPE." - (".$cek_movement->MV_DESCRIPTION.")";

            	} catch(\Exception $e){}

            	if(isset($data_json->rsvReceivingSLOC)){
            		$rcv_sloc_cc = $data_json->rsvReceivingSLOC;
            	} else if(isset($data_json->rsvCostCenterExpense)){
            		$rcv_sloc_cc = $data_json->rsvCostCenterExpense;
            	} else if(isset($data_json->rsvReceivingSLOCDesc)){
                    $rcv_sloc_cc = $data_json->rsvReceivingSLOCDesc;
                }

                $result[]=array(
                    'UID'=>$value->UID,
                    'SAP_RSV_NO'=>isset($data_json->RESERVATION_NO_SAP) ? ltrim($data_json->RESERVATION_NO_SAP, "0") : ' - ',
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'INSERT_DATE'=>$value->INSERT_DATE,
                    'CREATED_FOR_DATE'=>isset($data_json->CreatedDate) ? date('Y-m-d', strtotime($data_json->CreatedDate)) : ' - ',
                    'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                    'MOVEMENT_TYPE'=>$cek_movement,
                    'RCV_SLOC_CC'=>$rcv_sloc_cc,
                    'APPROVAL_LEVEL'=>$value->APPROVAL_LEVEL,
                    'NAME'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                    'ALIAS'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                    'GRAND_TOTAL'=>isset($data_json->rsvGrandTotal) ? number_format($data_json->rsvGrandTotal, 2) : 0
                );
            }
            // return DataTables::of($result)->make(true);
        } catch(\Exception $e){
            // return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
            Log::error('APPROVAL GET DATA RESERVATION ERROR | '.(string)$e);
        }

        return DataTables::of($result)->make(true);
    }

    public function list_reservation(Request $request){
        $is_superuser = isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER ? true : false;
        if($request->ajax()){
            try{
                $is_production = config('intranet.is_production');
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc'));
                }
                $options = [
                    'rtrim'=>true,
                ];

                // Datatable server-side query
                $limit = $request->get('length');
                $start = $request->get('start');
                $search = isset($request->get('search')['value']) ? $request->get('search')['value'] : NULL;

                // Datatable custom parameter
                $insert_date_from=$request->input('date_from');
                $insert_date_to=$request->input('date_to');
                $status=strtoupper($request->input('status'));
                $cost_center=$request->input('cost_center');
                $filter_date_type = $request->input('filter_type');
                $reservation_no_sap = $request->input('RESERVATION_NO_SAP', false);
                $movement_type = $request->input('MOVEMENT_TYPE', false);
                $requestor_name = $request->input('REQUESTOR_NAME', false);


                // Get all PR with not null ID (PR-NUMBER)
                $where = "ID <> '' ";
                if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                    if($filter_date_type){
                        $filter_date_type = strtoupper($filter_date_type);
                        $where = $where." and ${filter_date_type} between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                    } else {
                        $where = $where." and INSERT_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                    }

                    
                }

                if(!$is_superuser or $is_superuser && $cost_center){
                    $where = $where." and COST_CENTER_ID = '$cost_center'";
                }

                if($reservation_no_sap){
                    if($reservation_no_sap == '\-' || $reservation_no_sap == '-'){
                        $where = $where." and RESERVATION_NO_SAP IS NULL";
                    } else {
                        $where = $where." and RESERVATION_NO_SAP = '$reservation_no_sap'";
                    }
                }

                if($movement_type){
                    $where = $where." and MOVEMENT_TYPE = '$movement_type'";
                }

                if($requestor_name){
                    $where = $where." and REQUESTOR_NAME = '$requestor_name'";
                }

                if ($status != null or $status !=""){
                    if($status=="WAITING"){
                        $where = $where." and (STATUS_APPROVAL = 'REQUESTED' OR STATUS_APPROVAL = 'APPROVED') ";
                    }else{
                        $where = $where." and STATUS_APPROVAL = '".$status."'";
                    }
                }

                $result=array();
                $current_loggedin_employee_id=Session::get('user_id');
                DB::connection('dbintranet')->enableQueryLog();

                $data = DB::connection('dbintranet')
                ->table(DB::raw($this->form_view))
                ->whereraw(DB::raw($where));
                $recordsTotal = $data->count();

                if(!empty($search)){
                    $where = $where." and LAST_APPROVAL_NAME LIKE '%$search%' OR ".$where." and UID LIKE '%$search%'";
                    $data = $data->whereRaw(DB::raw($where));
                }

                $recordsFiltered = $data->count();
                $data = $data->orderBy('ID', 'DESC');
                $data = $data->offset($start)->limit($limit)->get();
                foreach($data as $key=>$value){
                    $data_json=json_decode($value->JSON_ENCODE);
                    $rcv_sloc_cc = '-';
                    $cek_movement = isset($data_json->MovementType) ? $data_json->MovementType : '';
                    try {
                        $cek_movement = DB::connection('dbintranet')
                        ->table('dbo.INT_MASTER_MOVEMENT_TYPE')
                        ->where('MV_TYPE', $cek_movement)
                        ->select('MV_TYPE', 'MV_DESCRIPTION')
                        ->get()->first();
                        if($cek_movement)
                            $cek_movement = $cek_movement->MV_TYPE." - (".$cek_movement->MV_DESCRIPTION.")";

                    } catch(\Exception $e){}

                    if(isset($data_json->rsvReceivingSLOC)){
                        $rcv_sloc_cc = $data_json->rsvReceivingSLOC;
                    } else if(isset($data_json->rsvCostCenterExpense)){
                        $rcv_sloc_cc = $data_json->rsvCostCenterExpense;
                    } else if(isset($data_json->rsvReceivingSLOCDesc)){
                        $rcv_sloc_cc = $data_json->rsvReceivingSLOCDesc;
                    }

                    $result[]=array(
                        'UID'=>$value->UID,
                        'SAP_RSV_NO'=>isset($data_json->RESERVATION_NO_SAP) ? ltrim($data_json->RESERVATION_NO_SAP, "0") : ' - ',
                        'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                        'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                        'INSERT_DATE'=>$value->INSERT_DATE,
                        'CREATED_FOR_DATE'=>isset($data_json->CreatedDate) ? date('Y-m-d', strtotime($data_json->CreatedDate)) : ' - ',
                        'LAST_APPROVAL_DATE'=>$value->LAST_APPROVAL_DATE,
                        'MOVEMENT_TYPE'=>$cek_movement,
                        'RCV_SLOC_CC'=>$rcv_sloc_cc,
                        'NAME'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                        'ALIAS'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                        'GRAND_TOTAL'=>isset($data_json->rsvGrandTotal) ? number_format($data_json->rsvGrandTotal, 2) : 0
                    );
                }

                // $datatable = DataTables::of($result)->make(true);
                // return $datatable;
                return response()->json([
                    "draw" => intval($request->get('draw')),  
                    "recordsTotal" => intval($recordsTotal),  
                    "recordsFiltered" => intval($recordsFiltered),
                    "data" => $result
                ]);
            }
            catch(\Exception $e){
                return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
            }
        } else {
            try {
                $is_production = config('intranet.is_production');
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc'));
                }
                $options = [
                    'rtrim'=>true,
                ];

                if(empty(Session::get('assignment')[0])){
                    $division="SYSADMIN";
                    $department="SYSADMIN";
                    $company_code="SYSADMIN";
                    $plant="SYSADMIN";
                    $plant_name="SYSADMIN";
                    $territory_id = "SYSADMIN";
                    $territory_name = "SYSADMIN";
                    $cost_center_id="SYSADMIN";
                    $job_title="SYSADMIN";

                }else{
                    $division=Session::get('assignment')[0]->DIVISION_NAME;
                    $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
                    $company_code=Session::get('assignment')[0]->COMPANY_CODE;
                    $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
                    $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
                    $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
                    $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
                    $cost_center_id=Session::get('assignment')[0]->SAP_COST_CENTER_ID;
                    $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
                }

                $filter_created_from = !empty($request->get('date_from')) ? date('Ymd',strtotime($request->get('date_from'))) : '';
                $filter_created_to = !empty($request->get('date_to')) ? date('Ymd',strtotime($request->get('date_to'))) : '';
                $filter_cost_center = !empty($request->get('cost_center')) ? $request->get('cost_center') : $cost_center_id;
                $status = !empty($request->get('status')) ? $request->get('status') : '';
                $filter_date_type = $request->get('filter_type');

                $data['created_date_to']=$filter_created_to;
                $data['created_date_from']=$filter_created_from;
                $data['cost_center']=$filter_cost_center;
                $data['status']=$status;

                if($is_superuser || Session::get('permission_menu') && Session::get('permission_menu')->has("view_".route('finance.add-reservation.list_reservation', array(), false))) {
                    $list_cost_center=DB::connection('dbintranet')
                    ->select("SELECT SAP_COST_CENTER_ID, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER");
                } else {
                    $list_cost_center=DB::connection('dbintranet')
                    ->select("SELECT SAP_COST_CENTER_ID, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE LEFT(SAP_DEPARTMENT_CODE, 4) = '$plant'");
                }

            } catch(\Exception $e){
                $data = [];
                $filter_cost_center = NULL;
                $list_cost_center = [];
            }

            return view('pages.finance.reservation.list-reservation', [
                'data' => $data,
                'filtered_cost_center'=>$filter_cost_center,
                'list_cost_center'=>$list_cost_center,
                'filter_date_type'=>$filter_date_type
            ]);
        }
    }

    public function save(Request $request) {
        // dd($request->post());
    	try {
            $payload = ['status'=>'not_set', 'message'=>'Unknown operation, No data changes has been made'];

            $check_created_date = date('Y-m-d',strtotime($request->post('CreatedDate')));
            if($check_created_date == '1970-01-01'){
                $payload['status'] = 'error';
                $payload['message'] = 'Invalid date selected, please check the date format and try again';
                return response()->json($payload, 400);
            } else if(strtotime($check_created_date) < strtotime(date('Ymd'))){
                $payload['status'] = 'error';
                $payload['message'] = 'The date selected less than today or now date is disallowed, please select now or future date';
                return response()->json($payload, 400);
            }

	    	// dd($request->all());
	    	$type_form=$this->form_number;
	    	$year = date('Y');

	    	// START -- CARI LAST SEQUENCE FORM
	        $last_seq=DB::connection('dbintranet')
	                    ->select("SELECT TOP 1 CASE WHEN UID IS NULL THEN NULL ELSE UID END AS LAST_SEQ FROM INT_FIN_APPR_RAW_DATA WHERE TYPE_FORM ='$type_form'  ORDER BY ID DESC ");
	        if(!empty($last_seq[0]->LAST_SEQ)){
	            $explode_uid=explode('-',$last_seq[0]->LAST_SEQ);
	            $nomor_akhir=(int)end($explode_uid);
	        }else{
	            $nomor_akhir=0;
	        }
	        $new_seq = $nomor_akhir + 1;
	        $new_seq = sprintf("%010d", $new_seq);
	        $uid=$type_form.'-'.$new_seq;
	        // END -- CARI LAST SEQUENCE FORM

	        $data=$request->post();
            if(!isset($data['rsvGrandTotal'])){
                throw new \Exception("Cannot read Grand Total sent, please check your data and try again");
            }

	        unset($data['_token']);
	        $data['uid']=$uid;

	        // START -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA
	        $data_json=json_encode($data);
	        $employee_id=$data['Requestor_Employee_ID'];
	        $type="Request Reservation";

	        DB::connection('dbintranet')->beginTransaction();
	        try {
		        DB::connection('dbintranet')
		        ->table('INT_FIN_APPR_RAW_DATA')
		        ->insert(
		            [
		                "JSON_ENCODE" => $data_json,
		                "TYPE" => $type,
		                "INSERT_DATE" => date('Y-m-d H:i:s'),
		                "UID" => $uid,
		                "EMPLOYEE_ID" => $employee_id,
		                "TYPE_FORM" => $type_form,
		            ]
		        );
		        DB::connection('dbintranet')->commit();

		        // Insert Notif
                $level_approval = 1;
                $notif_link=$this->approval_view_link;
                $notif_desc="Please approve Reservation Form : ".$uid."";
                $notif_type="info";
                $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);

                // Set payload
                $payload['status'] = 'success';
            	$payload['message'] = 'Your request has been sent';
	       	} catch (\Exception $e) {
		        DB::connection('dbayana-stg')->rollback();
		        throw new \Exception($e->getMessage());
		        // something went wrong
		    }
	        // END -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA    
	    } catch(\Exception $e){
	    	$payload['message'] = $e->getMessage();
	    	return response()->json($payload, 410);
	    }
	    return response()->json($payload, 200);
    }

    public function update(Request $request) {
        try {
            $payload = ['status'=>'warning', 'message'=>'No data changes has been made'];

            $check_approval = DB::connection('dbintranet')
            ->table('dbo.VIEW_APPROVAL_FORM_REQUEST_ADD_RESERVATION')
            ->where('UID', $request->post('FormNumber', 0))
            ->select('APPROVAL_LEVEL', 'STATUS_APPROVAL', 'JSON_ENCODE')
            ->get()->first();
            $existing_created_date = date('Y-m-d', strtotime(json_decode($check_approval->JSON_ENCODE)->CreatedDate));
            $check_created_date = date('Y-m-d',strtotime($request->post('CreatedDate')));
            if($check_created_date == '1970-01-01'){
                $payload['status'] = 'error';
                $payload['message'] = 'Invalid date selected, please check the date format and try again';
                return response()->json($payload, 400);
            } else if(strtotime($existing_created_date) != strtotime($check_created_date) && strtotime($check_created_date) < strtotime(date('Ymd'))){
                $payload['status'] = 'error';
                $payload['message'] = 'The date selected less than today or now date is disallowed, please select now or future date';
                return response()->json($payload, 400);
            }

            if(isset($check_approval->APPROVAL_LEVEL) && isset($check_approval->STATUS_APPROVAL) && $check_approval->APPROVAL_LEVEL == 0 && strtoupper($check_approval->STATUS_APPROVAL) == 'REQUESTED'){
                $old_data = isset($check_approval->JSON_ENCODE) ? json_decode($check_approval->JSON_ENCODE) : (object)[];
                $data=$request->post();
                unset($data['_token']);
                
                // if(isset($old_data->OLD_REQUEST_DATA))
                //     $data['OLD_REQUEST_DATA'] = $old_data->OLD_REQUEST_DATA;
                // else
                //     $data['OLD_REQUEST_DATA'] = $old_data;
                if(isset($old_data->OLD_REQUEST_DATA)){
                    unset($old_data->OLD_REQUEST_DATA);
                    $data['OLD_REQUEST_DATA'] = $old_data;
                }
                else{
                    $data['OLD_REQUEST_DATA'] = $old_data;
                }

                if(!isset($data['rsvGrandTotal'])){
                    throw new \Exception("Cannot read Grand Total sent, please check your data and try again");
                }

                // START -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA
                $data_json=json_encode($data);
                $employee_id=$data['Requestor_Employee_ID'];
                $type="Request Reservation";

                DB::connection('dbintranet')->beginTransaction();
                try {
                    $updated_data = DB::connection('dbintranet')
                    ->table('INT_FIN_APPR_RAW_DATA')
                    ->where('UID', $request->post('FormNumber', 0))
                    ->update(["JSON_ENCODE" => $data_json]);
                    DB::connection('dbintranet')->commit();

                    if($updated_data > 0){
                        // Set payload
                        $payload['status'] = 'success';
                        $payload['message'] = 'Your changes has been saved, please review the data to make sure it is appropriate';
                    }
                } catch (\Exception $e) {
                    DB::connection('dbayana-stg')->rollback();
                    throw new \Exception($e->getMessage());
                    // something went wrong
                }
                // END -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA    \
            } else {
                throw new \Exception("This reservation data has been approved, failed to make any changes.");
            }
        } catch(\Exception $e){
            $payload['message'] = $e->getMessage();
            $payload['status'] = 'error';
            return response()->json($payload, 410);
        }
        return response()->json($payload, 200);
    }

    public function getHistoryApproval(Request $request){
        $rest = new ZohoFormModel();
        $result = $rest->getHistoryApproval($request,$this->form_view, $this->approval_view);
        return response($result);
    }

    public function modal_detail(Request $request)
    {
        $uid=$request->input('id');
        $status_approval = '';
        $action=(!empty($request->input('action')))? $request->input('action') : 'view'; // flag action, gunanya adalah ketika di modal detail supaya bisa kasi validasi apakah harus kasi tombol approve & reject di modal atau tidak (tapi berbeda dengan modal approve detail, ini hanya untuk approval tanpa inputan apapun)
        $data_form=NULL;
        $data_json=NULL;
        $data_file=NULL;
        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
                    ->table($this->form_view)
                    ->where('UID',$uid)
                    ->get();
            $status_approval = $data_form[0]->APPROVAL_LEVEL > 0 ? true : false;
            $data_json = json_decode($data_form[0]->JSON_ENCODE);
        }

        $sap_sloc = [];
        $sap_sloc_receive = [];
        $plant_receiving = '';
        try {
        	if(empty(Session::get('assignment')[0])){
                if(isset($data_json->isPlantToPlantReceive) && strtoupper($data_json->isPlantToPlantReceive) == 'Y'){
                    $plant=isset($data_json->rsvOriginPlant[0]) ? $data_json->rsvOriginPlant[0] : '';
                    $plant_receiving=isset($data_json->rsvReceivingPlant) ? $data_json->rsvReceivingPlant : '';
                    $company_code="SYSADMIN";
                } else if(isset($data_json->Requestor_Plant_ID)){
                    $plant = strtoupper($data_json->Requestor_Plant_ID);
                    $company_code="SYSADMIN";
                } else {
                   $plant="SYSADMIN";
                   $company_code="SYSADMIN";
                }
	            
	        }else{
                $company_code=Session::get('assignment')[0]->COMPANY_CODE;
                if(isset($data_json->isPlantToPlantReceive) && strtoupper($data_json->isPlantToPlantReceive) == 'Y'){
                    $plant=isset($data_json->rsvOriginPlant[0]) ? $data_json->rsvOriginPlant[0] : '';
                    $plant_receiving=isset($data_json->rsvReceivingPlant) ? $data_json->rsvReceivingPlant : '';
                } else if(isset($data_json->Requestor_Plant_ID)){
                    $plant = strtoupper($data_json->Requestor_Plant_ID);
                } else {
	               $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
                }
	        }

        	//init RFC
	        $is_production = config('intranet.is_production');
	        if($is_production){
	            $rfc = new SapConnection(config('intranet.rfc_prod'));
	        }else{
	            $rfc = new SapConnection(config('intranet.rfc'));
	        }
	        $options = [
	            'rtrim'=>true,
	        ];

	        $param_sloc = [
	            'P_COMPANY'=>"",
	            'P_PLANT'=>$plant
	        ];

	        $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
	        $result= $function->invoke($param_sloc, $options);
	        $sap_sloc = isset($result['IT_SLOC']) ? $result['IT_SLOC'] : [];

            // Load plant receive if only
            // reservation type is plant to plant
            if($plant_receiving){
                $param_sloc_receiving = [
                    'P_COMPANY'=>"",
                    'P_PLANT'=>$plant_receiving
                ];

                $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
                $result= $function->invoke($param_sloc_receiving, $options);
                $sap_sloc_receive = isset($result['IT_SLOC']) ? $result['IT_SLOC'] : [];
            }

	        $mv_type = [];
	        try {
	        	$mv_type = DB::connection('dbintranet')
	        	->table('dbo.INT_MASTER_MOVEMENT_TYPE')
	        	->select('MV_TYPE', 'MV_DESCRIPTION')
	        	->where('MV_TYPE', isset($data_json->MovementType) ? $data_json->MovementType : '')
	        	->get();
	        } catch(\Exception $e){}

            $material_uom = [];
            // try {
            //     $param = array();
            //     $function_measurement = $rfc->getFunction('ZFM_MD_UOM_LIST');
            //     $material_uom = $function_measurement->invoke($param, $options)['IT_DATA'];
            // } catch(SAPFunctionException $e){}
    	} catch(\Exception $e){}

        $plant_list = [];
        try {
            $plant_list=DB::connection('dbintranet')
            ->table('dbo.INT_BUSINESS_PLANT as p')
            ->select('p.SAP_PLANT_ID', 'p.SAP_PLANT_NAME')
            ->orderBy('SAP_PLANT_ID','ASC')
            ->distinct()->get()->toArray();
        } catch(\Exception $e){}

        // Check for custom cost center
        $employee_id=Session::get('user_id');
        $custom_cost_center = [];
        try {
            $custom_cost_center = DB::connection('dbintranet')
            ->table('dbo.SAP_RSV_EMP_COSTCENTER_MAPPING AS cmp')
            ->where(['cmp.EMPLOYEE_ID'=>$employee_id, 'cmp.PLANT_ID'=>$plant])
            ->leftJoin('dbo.INT_SAP_COST_CENTER AS cc', 'cmp.SAP_COSTCENTER_ID', '=', 'cc.SAP_COST_CENTER_ID')
            ->select('cmp.SAP_COSTCENTER_ID', 'cc.SAP_COST_CENTER_DESCRIPTION')
            ->get()->toArray();
        } catch(\Exception $e){}

    	$data=array(
            'data_form'=>isset($data_form[0]) ? $data_form[0] : [],
            'data_json'=>$data_json,
            'plant_list'=>$plant_list,
            'custom_cost_center'=>$custom_cost_center,
            's_loc'=>$sap_sloc,
            's_loc_receiving'=>$sap_sloc_receive,
            'action'=>$action,
            'status_approval'=>$status_approval,
            'movement_type'=>$mv_type,
            'material_unit'=>$material_uom,
            'company_code'=>$company_code
        );
        if($request->input('reservation_type') == 'report'){
            return View::make('pages.finance.reservation.modal-detail-report')->with('data', $data)->render();
        } else {
            return View::make('pages.finance.reservation.modal-detail')->with('data', $data)->render();
        }
    }

    public function submitApprovalForm(Request $request)
    {
        $FormID=imuneString($request->input('form_id'));
        $data = explode(";" , $FormID);
        $EmployeID=imuneString($request->input('employe_id'));
        $StatusApproval=imuneString($request->input('status_approval'));
        $TypeForm=imuneString($request->input('type_form'));
        $Reason=imuneString($request->input('reason'));
        $type=$request->post('type', false);

        // Jika tipe quick approve (menggunakan checkbox / centang sekaligus)
        // maka jangan update data
        if(!$type){
            try {
                $data_update = $this->update($request);
            } catch(\Exception $e){
                Log::error('ERROR UPDATE DATA ON SUBMIT APPROVAL RESERVATION | '.$e->getMessage());
            }
        }

        $totalData=0;
        $success=0;
        $failed=0;
        foreach ($data as $key => $dataId) {

            $detail='';
            $dataDetail=explode("#",$dataId);
            $idform=$dataDetail[0];
            $formlevel=isset($dataDetail[1]) ? $dataDetail[1] : 0 ;

            $approvalLevel=$formlevel;
            $approvalLevel=$approvalLevel+1;

            $result = $this->approve($request, $idform, $approvalLevel, $EmployeID, $StatusApproval,$TypeForm,$Reason);
            $result = $result->getData();
            if (isset($result->code) && $result->code == 200){
                $success++;
            }else{
                return response()->json(['exception'=>true, 'message'=>isset($result->message) ? $result->message : "Something went wrong with Form ${idform}"], $result->code);
                // $failed++;
            }

            $totalData++;
        }

        $hasil["Total_Data"]=$totalData;
        $hasil["Total_Success"]=$success;
        $hasil["Total_Failed"]=$failed;

        return $hasil;
    }

    public function approve(Request $request,$FormID,$ApprovalLevel,$LastApprovalID,$StatusApproval,$TypeForm,$Reason)
    {
        try {
            $connection = DB::connection('dbintranet');
            $connection->beginTransaction();
            $CountData=DB::connection('dbintranet')->table('INT_FIN_APPR_LIST')
            ->where('FORM_ID',$FormID)
            ->count();

            // validasi jika form sudah sampai di tahap terakhir, maka status akan jadi finished
            $cek_finished_approval=DB::connection('dbintranet')->select("SELECT MAX(LEVEL_APPROVAL) AS MAX_APPROVAL FROM INT_FIN_APPR_RAW_DATA A INNER JOIN INT_FIN_APPR_ROLE B ON A.TYPE_FORM = B.FORM_NUMBER
            WHERE A.UID = '".$FormID."'");
            $max_approval = isset($cek_finished_approval[0]->MAX_APPROVAL) ? $cek_finished_approval[0]->MAX_APPROVAL : false;
            if(!$max_approval){
            	$data['code'] = 401;
            	$data['message'] = "Approval Level is not found";
            	Log::warning('Approve Function Reservation, Approval Level not found !!!!!');
            	return response()->json($data, $data['code']);;
            }


            if($ApprovalLevel==$max_approval && $StatusApproval=="APPROVED"){
                $StatusApproval ='FINISHED';                
            }
            //======================
            if ($CountData == 0){ // jika blm ada di tabel list (dimana tabel list ini nyimpen progress dari approval)  makan akan create record dengan approval terakhir
                $SaveApproval=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_LIST')
                ->insert(
                    [
                        "FORM_ID" => $FormID,
                        "APPROVAL_LEVEL" => $ApprovalLevel,
                        "LAST_APPROVAL_ID" => $LastApprovalID,
                        "LAST_APPROVAL_DATE" => DB::raw("GETDATE()"),
                        "STATUS_APPROVAL" => $StatusApproval,
                        "TYPE_FORM" => $TypeForm,
                        "REASON" => $Reason
                    ]
                );
            }else{ // jika sudah ada di tabel list (dimana tabel list ini nyimpen progress dari approval) maka akan update datanya dengan approval terakhir
                $UpdateApproval=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_LIST')
                ->where('FORM_ID',$FormID)
                ->update(
                    [
                        "APPROVAL_LEVEL" => $ApprovalLevel,
                        "LAST_APPROVAL_ID" => $LastApprovalID,
                        "LAST_APPROVAL_DATE" => DB::raw("GETDATE()"),
                        "STATUS_APPROVAL" => $StatusApproval,
                        "TYPE_FORM" => $TypeForm,
                        "REASON" => $Reason
                    ]
                );
            }

            $CountDataHistory=DB::connection('dbintranet')
            ->table('INT_FIN_APPR_HISTORY')
            ->where('FORM_ID',$FormID)
            ->where('APPROVAL_LEVEL',$ApprovalLevel)
            ->count();

            if($CountDataHistory == 0){ //jika belum ada data history
                $InsertLog=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_HISTORY')
                ->insert(
                    [
                        "FORM_ID" => $FormID,
                        "APPROVAL_ID" => $LastApprovalID,
                        "APPROVAL_DATE" => DB::raw("GETDATE()"),
                        "STATUS_APPROVAL" => $StatusApproval,
                        "APPROVAL_LEVEL" => $ApprovalLevel,
                        "TYPE_FORM" => $TypeForm,
                        "REASON" => $Reason
                    ]
                );
            }else{ // update data approval history
                $UpdateLog=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_HISTORY')
                ->where('FORM_ID',$FormID)
                ->where('APPROVAL_LEVEL',$ApprovalLevel)
                ->update(
                    [
                        "APPROVAL_LEVEL" => $ApprovalLevel,
                        "APPROVAL_ID" => $LastApprovalID,
                        "APPROVAL_DATE" => DB::raw("GETDATE()"),
                        "STATUS_APPROVAL" => $StatusApproval,
                        "TYPE_FORM" => $TypeForm,
                        "REASON" => $Reason
                    ]
                );
            }

            $uid = $FormID;
            //insert ke notifikasi untuk approver
            if($StatusApproval!=="FINISHED" && $StatusApproval=="APPROVED"){
                // jika belum finish, maka kirim notif ke approver
                $level_approval = $ApprovalLevel+1;
                $notif_desc = "Please approve Reservation Form : ".$FormID."";
                $notif_type="info";
                $notif_link=$this->approval_view_link;
                $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);

            }else if($StatusApproval=="REJECTED"){
                //jika reject, maka kirim notif ke requestor
                $data_approval = DB::connection('dbintranet')
                ->table(DB::raw($this->approval_view))
                ->where('UID',$uid)
                ->get();

                $notif_employee_id=$data_approval[0]->REQUESTOR_ID;
                $notif_link=$this->link_request;
                $notif_desc="Your reservation request : ".$uid." is rejected";
                $notif_type="reject";
                $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type);
            }
            if($StatusApproval=="FINISHED"){
            	try {
	                $form_data = DB::connection('dbintranet')
	                ->table($this->form_view)
	                ->where('UID', $FormID)
	                ->get()->first();

	                $is_production = config('intranet.is_production');
			        if($is_production)
			            $rfc = new SapConnection(config('intranet.rfc_prod'));
			        else
			            $rfc = new SapConnection(config('intranet.rfc'));
			        $options = [
			            'rtrim'=>true,
			        ];

			        // Check data obtained
	                $check_movement_type = isset($form_data->JSON_ENCODE) ? json_decode($form_data->JSON_ENCODE) : (object)[];

	                // Prepare data to send
	                $reservation_header = array(
			        	'RES_DATE'=>date('Ymd', strtotime($check_movement_type->CreatedDate)),
			        	'MOVE_TYPE'=>$check_movement_type->MovementType,
			        	'GR_RCPT'=>$check_movement_type->Recipient,
				    );
				    $reservation_items = array();

				    // Check movement type for additional params
	                $reservation_header['PLANT']=$form_data->PLANT_ID;
	                if(isset($check_movement_type->rsvCostCenterExpense) && $check_movement_type->rsvCostCenterExpense){
				        $reservation_header['COST_CTR']=$check_movement_type->rsvCostCenterExpense;
	                } else if(isset($check_movement_type->rsvReceivingSLOC) && $check_movement_type->rsvReceivingSLOC){
	                	$reservation_header['MOVE_STLOC']=$check_movement_type->rsvReceivingSLOC;
	                	$reservation_header['MOVE_PLANT']=$form_data->PLANT_ID;
	                } else if(isset($check_movement_type->isPlantToPlantReceive) && strtoupper($check_movement_type->isPlantToPlantReceive) == 'Y'){
                        $reservation_header['MOVE_STLOC']=isset($check_movement_type->rsvReceivingPlantSLOC) ? $check_movement_type->rsvReceivingPlantSLOC : '';
                        $reservation_header['MOVE_PLANT']=isset($check_movement_type->rsvReceivingPlant) ? $check_movement_type->rsvReceivingPlant : '';
                    }

	                // Loop the items
                    $check_quick_approve = $request->post('rsvMaterials') ? true : false; 
                    if(isset($check_movement_type->isPlantToPlantReceive) && strtoupper($check_movement_type->isPlantToPlantReceive) == 'Y'){ 
    	                for($data_loop=0;$data_loop<count($check_movement_type->rsvItem);$data_loop++){
                            if($check_quick_approve){
                                try {
                                    $param_measurement = [
                                        'MATERIAL'=> $request->post('rsvMaterials')[$data_loop]
                                    ];
                                    $function_invoke = $rfc->getFunction('BAPI_MATERIAL_GET_DETAIL');
                                    $measurement = $function_invoke->invoke($param_measurement, $options);
                                    $measurement = $measurement['MATERIAL_GENERAL_DATA']['BASE_UOM'];
                                } catch(SAPFunctionException $e){
                                    throw new \Exception($e->getMessage());
                                    // $measurement = $request->post('rsvMeasurement')[$data_loop];
                                }

        	                	$reservation_items[] = array(
        	                		'MATERIAL'=>$request->post('rsvMaterials')[$data_loop],
        	                		'PLANT'=>$request->post('rsvOriginPlant')[$data_loop],
        	                		'STORE_LOC'=>$request->post('rsvSLOC')[$data_loop],
                                    'UNIT'=>$measurement,
        	                		'QUANTITY'=>(float)$request->post('rsvQuantity')[$data_loop],
        	                		'MOVEMENT'=>'X'
        	                	);
                            } else {
                                // For Quick approve
                                try {
                                    $param_measurement = [
                                        'MATERIAL'=> $check_movement_type->rsvMaterials[$data_loop]
                                    ];
                                    $function_invoke = $rfc->getFunction('BAPI_MATERIAL_GET_DETAIL');
                                    $measurement = $function_invoke->invoke($param_measurement, $options);
                                    $measurement = $measurement['MATERIAL_GENERAL_DATA']['BASE_UOM'];
                                } catch(SAPFunctionException $e){
                                    throw new \Exception($e->getMessage());
                                    // $measurement = $request->post('rsvMeasurement')[$data_loop];
                                }

                                $reservation_items[] = array(
                                    'MATERIAL'=>$check_movement_type->rsvMaterials[$data_loop],
                                    'PLANT'=>$check_movement_type->rsvOriginPlant[$data_loop],
                                    'STORE_LOC'=>$check_movement_type->rsvSLOC[$data_loop],
                                    'UNIT'=>$measurement,
                                    'QUANTITY'=>(float)$check_movement_type->rsvQuantity[$data_loop],
                                    'MOVEMENT'=>'X'
                                );
                            }
    	                }
                    } else {
                        for($data_loop=0;$data_loop<count($check_movement_type->rsvItem);$data_loop++){
                            if($check_quick_approve){
                                try {
                                    $param_measurement = [
                                        'MATERIAL'=> $request->post('rsvMaterials')[$data_loop]
                                    ];
                                    $function_invoke = $rfc->getFunction('BAPI_MATERIAL_GET_DETAIL');
                                    $measurement = $function_invoke->invoke($param_measurement, $options);
                                    $measurement = $measurement['MATERIAL_GENERAL_DATA']['BASE_UOM'];
                                } catch(SAPFunctionException $e){
                                    throw new \Exception($e->getMessage());
                                    // $measurement = $request->post('rsvMeasurement')[$data_loop];
                                }

                                $reservation_items[] = array(
                                    'MATERIAL'=>$request->post('rsvMaterials')[$data_loop],
                                    'PLANT'=>$form_data->PLANT_ID,
                                    'STORE_LOC'=>$request->post('rsvSLOC')[$data_loop],
                                    'UNIT'=>$measurement,
                                    'QUANTITY'=>(float)$request->post('rsvQuantity')[$data_loop],
                                    'MOVEMENT'=>'X'
                                );
                            } else {
                                // For Quick Approve
                                try {
                                    $param_measurement = [
                                        'MATERIAL'=> $check_movement_type->rsvMaterials[$data_loop]
                                    ];
                                    $function_invoke = $rfc->getFunction('BAPI_MATERIAL_GET_DETAIL');
                                    $measurement = $function_invoke->invoke($param_measurement, $options);
                                    $measurement = $measurement['MATERIAL_GENERAL_DATA']['BASE_UOM'];
                                } catch(SAPFunctionException $e){
                                    throw new \Exception($e->getMessage());
                                    // $measurement = $request->post('rsvMeasurement')[$data_loop];
                                }

                                $reservation_items[] = array(
                                    'MATERIAL'=>$check_movement_type->rsvMaterials[$data_loop],
                                    'PLANT'=>$form_data->PLANT_ID,
                                    'STORE_LOC'=>$check_movement_type->rsvSLOC[$data_loop],
                                    'UNIT'=>$measurement,
                                    'QUANTITY'=>(float)$check_movement_type->rsvQuantity[$data_loop],
                                    'MOVEMENT'=>'X'
                                );
                            }
                        }
                    }

	                // Prepare the parameter
	                $param = [
	                	'RESERVATION_HEADER'=>$reservation_header,
	                	'RESERVATION_ITEMS'=>$reservation_items
	                ];

	                // Finally invoke function
	                $function_type = $rfc->getFunction('BAPI_RESERVATION_CREATE');
		        	$insert_reservation = $function_type->invoke($param, $options);
		        	if(isset($insert_reservation['RETURN']) && count($insert_reservation['RETURN']) > 0){
		        		$max_error = max(array_keys($insert_reservation['RETURN']));
		        		$msg = isset($insert_reservation['RETURN'][$max_error]['MESSAGE']) ? $insert_reservation['RETURN'][$max_error]['MESSAGE'] : 'Something went wrong when trying to insert data to SAP, please try again in a moment';
		        		Log::error("Error in Reservation BAPI_RESERVATION_CREATE APPROVAL | ". $msg);
		        		throw new \Exception($msg);
		        	} else {
		        		$check_movement_type->RESERVATION_NO_SAP = isset($insert_reservation['RESERVATION']) ? $insert_reservation['RESERVATION'] : '';
		        		$new_encode_data = json_encode($check_movement_type);
		        		try {
		        			$data_approval = DB::connection('dbintranet')
			                ->table('INT_FIN_APPR_RAW_DATA')
			                ->where('UID',$uid)
			                ->update(['JSON_ENCODE'=>$new_encode_data]);
		        		} catch(\Exception $e){}
		        	}
            	} catch(SAPFunctionException $e){
            		Log::error("SAP FUNCTION Error in Reservation APPROVAL | ". $e->getMessage());
            		throw new \Exception("Form No. ${FormID} error | ". $e->getMessage());
            	} catch(\Exception $e){
            		Log::error("General Error in Reservation APPROVAL | ". $e->getMessage());
            		throw new \Exception("Form No. ${FormID} error | ". $e->getMessage());
            	}

                //jika sudah finish, maka kirim notif ke requestor
                $data_approval = DB::connection('dbintranet')
                ->table(DB::raw($this->approval_view))
                ->where('UID',$uid)
                ->get();

                $notif_employee_id=$data_approval[0]->REQUESTOR_ID;
                $notif_link=$this->link_request;
                $notif_desc="Your reservation request : ".$uid." is approved";
                $notif_type="approve";
                $insert_notif=insertNotification($notif_employee_id, $notif_link, $notif_desc, $notif_type);
            }

            $data['code'] = 200;
            $data['message'] = 'Success';
            $connection->commit();
        } catch(QueryException $e) {
            $data['code'] = 401;
            $data['message'] = $e->getMessage();
            $connection->rollback();
        } catch(\Exception $e){
            $data['code'] = 401;
            $data['message'] = $e->getMessage();
            $connection->rollback();
        } 
        return response()->json($data, $data['code']);
    }
}
