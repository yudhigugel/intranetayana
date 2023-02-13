<?php

namespace App\Http\Controllers\Finance;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\HumanResource\CompanyModel;
use App\Models\Zoho\ZohoFormModel;
use Carbon\Carbon;
Use Cookie;
use DataTables;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use Log;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use App\Http\Controllers\Traits\IntranetTrait;
use PDF;


class PurchaseRequisitionMarketList extends Controller{
    use IntranetTrait;
    protected $iterable = 0;

    public function __construct()
    {
        $this->form_number = "ML";
        $this->form_view="VIEW_FORM_REQUEST_PURCHASE_REQUISITION_MARKETLIST";
        $this->approval_view="VIEW_APPROVAL_FORM_REQUEST_PURCHASE_REQUISITION_MARKETLIST";
        $this->approval_view_link="/finance/purchase-requisition-marketlist/approval";
        $this->link_request = "/finance/purchase-requisition-marketlist/request";
    }

    public function request(Request $request){

        // request if ajax / any async
        if($this->wantsJson($request)) {
            $data = [];
            $return = array('status'=>'success', 'message'=>'Unknown operation.', 'data'=>$data);
            $status = 200;

            if($request->get('type') && strtolower($request->get('type')) == 'recipe-template'){
                $emp_id = $request->input('employee_id', false);
                if($emp_id){
                    try {
                        $template = DB::connection('dbintranet')
                        ->table('dbo.INT_EMP_ML_TEMPLATE')
                        ->where('EMPLOYEE_ID', $emp_id)
                        ->get()->pluck('ML_TEMPLATE', 'ML_TEMPLATE')->values()->all();
                        $data = $template;
                        $return = array('status'=>'success', 'message'=>'Data has been successfully loaded', 'data'=>$data);
                        $status = 200;
                    } catch(\Exception $e){
                        Log::error('ERROR ON LOADING MARKETLIST TEMPLATE | '. $e->getMessage());
                        $error = $e->getMessage();
                        $return = array('status'=>'error', 'message'=>"Cannot load template, pleae check your connection and try again | ${error}", 'data'=>$data);
                        $status = 401;
                    }
                }
            } else if($request->get('type') && strtolower($request->get('type')) == 'recipe-template-item'){
                try {
                    $template_name = $request->get('template');
                    $show_all = $request->get('show_inactive');
                    $template_data = [];
                    if($show_all){
                        if($template_name){
                            $template_data = DB::connection('dbintranet')
                            ->table('dbo.INT_MASTER_ML_TEMPLATE')
                            ->where(['TEMPLATE'=>$template_name])
                            ->select('SAPMATERIALCODE', 'MATERIALNAME', 'UOM', 'STATUS', 'PURCH_GROUP')
                            ->get()->toArray();
                        }
                    } else {
                        if($template_name){
                            $template_data = DB::connection('dbintranet')
                            ->table('dbo.INT_MASTER_ML_TEMPLATE')
                            ->where(['TEMPLATE'=>$template_name, 'STATUS'=>1])
                            ->select('SAPMATERIALCODE', 'MATERIALNAME', 'UOM', 'STATUS', 'PURCH_GROUP')
                            ->get()->toArray();
                        }
                    }
                    $data = $template_data;
                    $return = array('status'=>'success', 'message'=>'Data has been successfully loaded', 'data'=>$data);
                    $status = 200;
                } catch(\Exception $e){
                    Log::error('ERROR ON LOADING MARKETLIST TEMPLATE ITEM LIST | '. $e->getMessage());
                    $return = array('status'=>'error', 'message'=>'Cannot load template, pleae check your connection and try again', 'data'=>$data);
                    $status = 401;
                }
            } else if($request->get('type', false) && strtolower($request->get('type')) == 'material'){
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
                //===

                $data_material = [];
                $keyword = strtoupper($request->get('searchTerm', ''));
                $plant_code = $request->get('plant', '');
                $qty = $request->get('qty', 1);

                $param = array(
                    'GV_MAKTX'=>"*".$keyword."*",
                    'GV_ACCTASSCAT'=>'1',
                    'GV_WERKS'=>$plant_code,
                    'GV_MAX_ROWS'=>30
                );

                $function_type = $rfc->getFunction('ZFM_POPUP_MAT_BDT_INTRA_MONTH');
                $list_material= $function_type->invoke($param, $options);
                $list_material = isset($list_material['GI_HEADER']) ? $list_material['GI_HEADER'] : [];
                // dd($request->keys());
                if(in_array('searchPurchasingGroup', $request->keys())) {
                    $search_purchasing_group = filter_var($request->get('searchPurchasingGroup'), FILTER_VALIDATE_BOOLEAN); 
                    if($search_purchasing_group){
                        $pur_group_sent = $request->get('purGroup');

                        if(!$pur_group_sent){
                            $return = array('status'=>'error', 'message'=>"No purchasing group being sent, cannot process request", 'data'=>$data);
                            return response()->json($return, 400);
                        }

                        $list_material = collect($list_material)->map(function($item, $key) use ($pur_group_sent, $rfc, $plant_code, $options){
                            /**
                             * Begin check purchasing group
                             * Based on template mapping
                             * or first item selected
                             */
                            $purchasing_group_found = '';
                            $item['PUR_GROUP'] = '';
                            try {
                                $param = array(
                                    'MATERIAL'=>isset($item['MATNR']) ? $item['MATNR'] : '',
                                    'PLANT'=>$plant_code
                                );

                                $function_type = $rfc->getFunction('BAPI_MATERIAL_GET_DETAIL');
                                $material_detail = $function_type->invoke($param, $options);
                                $purchasing_group_found = isset($material_detail['MATERIALPLANTDATA']['PUR_GROUP']) ? $material_detail['MATERIALPLANTDATA']['PUR_GROUP'] : '';
                                if($purchasing_group_found == $pur_group_sent){
                                    $item['PUR_GROUP'] = $purchasing_group_found;
                                }
                            } catch(SAPFunctionException $e){
                                Log::error('FAILED WHEN CHECKING PURCHASING GROUP, CAUSE : '.isset($e->getErrorInfo()['message']) ? $e->getErrorInfo()['message'] : $e->getMessage());
                                Log::error($e);
                            } catch(\Exception $e){
                                Log::error('FAILED WHEN CHECKING PURCHASING GROUP, CAUSE : '.$e->getMessage());
                            }
                            return $item;
                            // finally return
                            // onlt material with same intended purchasing group
                            // return $purchasing_group_found == $pur_group_sent;
                        })->filter(function($item, $key) use ($pur_group_sent){
                            return isset($item['PUR_GROUP']) && $item['PUR_GROUP'] == $pur_group_sent;
                        })->values()->all();
                    } else {
                        $list_material = collect($list_material)->map(function($item, $key) use ($rfc, $plant_code, $options){
                            /**
                             * Begin check purchasing group
                             * Based on template mapping
                             * or first item selected
                             */
                            $purchasing_group_found = '';
                            try {
                                $param = array(
                                    'MATERIAL'=>isset($item['MATNR']) ? $item['MATNR'] : '',
                                    'PLANT'=>$plant_code
                                );

                                $function_type = $rfc->getFunction('BAPI_MATERIAL_GET_DETAIL');
                                $material_detail = $function_type->invoke($param, $options);
                                $purchasing_group_found = isset($material_detail['MATERIALPLANTDATA']['PUR_GROUP']) ? $material_detail['MATERIALPLANTDATA']['PUR_GROUP'] : '';
                                $item['PUR_GROUP'] = $purchasing_group_found;
                            } catch(SAPFunctionException $e){
                                Log::error('FAILED WHEN CHECKING PURCHASING GROUP, CAUSE : '.isset($e->getErrorInfo()['message']) ? $e->getErrorInfo()['message'] : $e->getMessage());
                                Log::error($e);
                            } catch(\Exception $e){
                                Log::error('FAILED WHEN CHECKING PURCHASING GROUP, CAUSE : '.$e->getMessage());
                            }
                            return $item;
                        })->values()->all();
                    }
                }
                // Begin check last price
                foreach ($list_material as $key => $value) {
                    $param = array(
                        'WERKS'=>$plant_code,
                        'IT_MATERIAL'=>array(
                            [
                                'MATNR'=>$value['MATNR'],
                                'MEINS'=>$value['MEINS'],
                                'MENGE'=>(float)$qty
                            ]
                        )
                    );
                    try {
    
                        $function_type = $rfc->getFunction('YFM_GET_MAT_COST_3');
                        $last_price= $function_type->invoke($param, $options);
                        $last_price = isset($last_price['MAT_PRICE'][0]['DMBTR']) ? $last_price['MAT_PRICE'][0]['DMBTR'] : 0;
                        $data_material[] = array("id"=>isset($value['MATNR']) ? $value['MATNR'] : '', "text"=>isset($value['MAKTX']) ? $value['MAKTX'] : 'Unknown Material', "html"=>isset($value['MAKTX']) ? "<div>".$value['MAKTX']."</div>" : '<div>Unknown Material</div>', 'unit'=>isset($value['MEINS']) ? $value['MEINS'] : '', 'last_price'=>number_format($last_price, 2), 'last_price_plain'=>$last_price, 'title'=>isset($value['MAKTX']) ? $value['MAKTX'] : 'Unknown Material', 'fipex'=>isset($value['FIPEX']) ? trim($value['FIPEX']) : '', 'fund_curr'=>isset($value['FUNDS_CURR']) ? trim($value['FUNDS_CURR']) : '', 'amount_txt'=>isset($value['AMOUNT_TXT']) ? trim($value['AMOUNT_TXT']) : '', 'amount_year_txt'=>isset($value['AMOUNT_TXT_YEAR']) ? trim($value['AMOUNT_TXT_YEAR']) : '', 'cmmtItemText'=>isset($value['TEXT1']) ? trim($value['TEXT1']) : '', 'cmmtBapi'=>isset($value['VERPR_TXT']) ? trim(str_replace('.', '', $value['VERPR_TXT'])) : 0, 'PUR_GROUP'=>isset($value['PUR_GROUP']) ? $value['PUR_GROUP'] : '');

                    } catch(SAPFunctionException $e){
                        $message = isset($e->getErrorInfo()['message']) ? $e->getErrorInfo()['message'] : $e->getMessage();
                        Log::error($message);
                    } catch(\Throwable $e){
                        $message = $e->getMessage();
                        Log::error($message);
                    }
                }
                return response()->json($data_material);
            }
            
            return response()->json($return, $status);
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
        //===

        $employee_id=Session::get('user_id');

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";
            $midjob_id = "SYSADMIN";

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
            $midjob_id = Session::get('assignment')[0]->MIDJOB_TITLE_ID;
        }

        //memberikan plant default untuk user
        $allowed_plant=[];

        //mencari plant yang diassign untuk user tersebut selain plant default
        $list_plant_sap=DB::connection('dbintranet')
        ->table('dbo.INT_BUSINESS_PLANT as p')
        ->leftJoin('dbo.SAP_PR_EMP_COSTCENTER_MAPPING as map','map.PLANT_ID','=','p.SAP_PLANT_ID')
        ->select('p.SAP_PLANT_ID','p.SAP_PLANT_NAME')
        ->where('EMPLOYEE_ID',$employee_id)
        ->orWhere('p.SAP_PLANT_ID',$plant)
        ->orderBy('SAP_PLANT_ID','ASC')
        ->distinct()->get()->toArray();

        $list_plant=$list_plant_sap;
        // done cari plant default
        // start -- cari cost center yang diperbolehkan
        $cc_where="SAP_COST_CENTER_ID = '$cost_center_id'";
        $i=0;


        DB::enableQueryLog();
        $check_multiple_cost_center = DB::connection('dbintranet')
        ->table('SAP_PR_EMP_COSTCENTER_MAPPING AS cm')
        ->join('INT_SAP_COST_CENTER AS cc', 'cm.SAP_COSTCENTER_ID', '=', 'cc.SAP_COST_CENTER_ID')
        ->where('cm.EMPLOYEE_ID', $employee_id)
        ->select('cm.EMPLOYEE_ID', 'cc.SAP_COST_CENTER_DESCRIPTION', 'cm.SAP_COSTCENTER_ID AS SAP_COST_CENTER_ID', 'cc.SAP_COST_CENTER_NAME', 'cc.DIVISION_NAME', 'cc.DEPARTMENT_NAME')
        ->get();

        // Jika cost center requestor ada di mapping (Lebih dari 1 cost center)
        if($check_multiple_cost_center->count() > 0){
            $list_cost_center = $check_multiple_cost_center;
        }
        else {
            $list_cost_center=DB::connection('dbintranet')
            ->select("SELECT * FROM INT_SAP_COST_CENTER WHERE $cc_where ");
        }

        $list_sloc = [];
        $check_mapping_sloc = DB::connection('dbintranet')
        ->table('dbo.SAP_ML_EMP_SLOC_MAPPING AS cm')
        ->where('cm.EMPLOYEE_ID', $employee_id)
        ->get();

        try {
            $param_sloc = [
                'P_COMPANY'=>"",
                'P_PLANT'=>$plant
            ];

            $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
            $result= $function->invoke($param_sloc, $options);
            $list_sloc = isset($result['IT_SLOC']) ? $result['IT_SLOC'] : [];
            if($check_mapping_sloc->count() > 0){
                $sloc_map = $check_mapping_sloc->pluck('SLOC_ID', 'SLOC_ID')->values()->all();
                $list_sloc = collect($list_sloc)->filter(function($item, $key) use ($sloc_map){
                    return in_array($item['STORAGE_LOCATION'], $sloc_map);
                })->values()->all();
            }

        } catch(SAPFunctionException $e){
            Log::error('SLOC PR MARKETLIST SAP ERROR | '. $e->getMessage());
        } catch(\Exception $e){
            Log::error('SLOC PR MARKETLIST GENERAL ERROR | '. $e->getMessage());
        }

        // end -- cari cost center yang diperbolehkan
        $employee_name=Session::get('user_data')->EMPLOYEE_NAME;
        $request_date_from = (!empty($request->input('date_from')))?  $request->input('date_from') : NULL ;
        $request_date_to = (!empty($request->input('date_to')))?  $request->input('date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;
        $filter_date_type= (!empty($request->input('filter_type')))? strtoupper($request->input('filter_type')) : NULL ;

        // START -- CHECK APAKAH SI USER BISA MELAKUKAN REQUEST
        $cek_alur_approval=DB::connection('dbintranet')
        ->table('INT_FORM_APPROVAL_MAPPING')
        ->where('FORMAPPROVAL_REQUESTOR_COSTCENTER', $cost_center_id)
        ->where('FORMAPPROVAL_REQUESTOR_MIDJOB', $midjob_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'ML')
        ->orWhere('FORMAPPROVAL_REQUESTOR_EMPLOYEE_ID',$employee_id)
        ->where('FORMAPPROVAL_REQUESTOR_TYPE_FORM', 'ML')
        ->get();
        
        $itung_alur_approval=count($cek_alur_approval);
        $allow_add_request=($itung_alur_approval>0)? true : false;

        $data=array(
            'company_code'=>$company_code,
            'plant'=>$plant,
            'plant_name'=>$plant_name,
            'employee_id'=>$employee_id,
            'employee_name'=>$employee_name,
            'division'=>$division,
            'department'=>$department,
            'cost_center_id'=>$cost_center_id,
            'status'=>$status,
            'territory_id'=>$territory_id,
            'territory_name'=>$territory_name,
            'job_title'=>$job_title,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
            'form_code'=>$this->form_number,
            'list_plant'=>$list_plant,
            'list_cost_center'=>$list_cost_center,
            'list_sloc'=>$list_sloc,
            'filter_date_type'=>$filter_date_type,
            'allow_add_request'=>$allow_add_request
        );

        return view('pages.finance.purchase-requisition-marketlist.request', ['data' => $data]);
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

        $request_date_from = (!empty($request->input('date_from')))?  $request->input('date_from') : NULL ;
        $request_date_to = (!empty($request->input('date_to')))?  $request->input('date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;
        $filter_date_type= (!empty($request->input('filter_type')))? strtoupper($request->input('filter_type')) : NULL ;

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
            'filter_date_type'=>$filter_date_type,
            'form_code'=>$this->form_number
        );

        return view('pages.finance.purchase-requisition-marketlist.approval', ['data' => $data]);
    }

    public function report(Request $request){
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

        $request_date_from = (!empty($request->input('date_from')))?  $request->input('date_from') : NULL ;
        $request_date_to = (!empty($request->input('date_to')))?  $request->input('date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;
        $filter_date_type= (!empty($request->input('filter_type')))? strtoupper($request->input('filter_type')) : NULL ;

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
            'filter_date_type'=>$filter_date_type,
            'form_code'=>$this->form_number
        );

        return view('pages.finance.purchase-requisition-marketlist.report', ['data' => $data]);
    }

    public function request_getData(Request $request){
        try{
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

            $employee_id=$request->input('employee_id');
            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('date_from');
            $insert_date_to=$request->input('date_to');
            $status=strtoupper($request->input('status'));
            $request_type=strtoupper($request->input('request_type'));
            $filter_cc = $request->input('SHIP_TO_COST_CENTER');
            $filter_sloc = $request->input('SHIP_TO_SLOC');
            $filter_date_type = $request->input('filter_type');

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
                if($filter_date_type){
                    $filter_date_type = strtoupper($filter_date_type);
                    $where = $where." and ${filter_date_type} between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                } else {
                    $where = $where." and INSERT_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                }
            }

            if ($status != null or $status !=""){
                if($status=="WAITING"){
                    $where = $where." and (STATUS_APPROVAL = 'REQUESTED' OR STATUS_APPROVAL = 'APPROVED') ";
                }else{
                    $where = $where." and STATUS_APPROVAL = '".$status."'";
                }
            }

            if($filter_cc != null){
                $where = $where." and SHIP_TO_COST_CENTER = '".$filter_cc."'";
            }

            if($filter_sloc != null){
                $where = $where." and SHIP_TO_SLOC = '".$filter_sloc."'";
            }

            DB::connection('dbintranet')->enableQueryLog();
            $data = DB::connection('dbintranet')
            ->table(DB::raw($this->form_view))
            ->whereraw(DB::raw($where))->get();

            $result=array();
            $current_loggedin_employee_id=Session::get('user_id');
            foreach($data as $key=>$value){
                $data_form=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_RAW_DATA')
                ->leftJoin('INT_FIN_APPR_LIST','INT_FIN_APPR_RAW_DATA.UID','=','INT_FIN_APPR_LIST.FORM_ID')
                ->where('INT_FIN_APPR_RAW_DATA.UID',$value->UID)
                ->get();

                $data_json = isset($data_form[0]->JSON_ENCODE) ? json_decode($data_form[0]->JSON_ENCODE) : [];
                $purpose = isset($data_json->purpose) ? $data_json->purpose : '';
                $grandTotal = isset($data_json->grandTotal) ? preg_replace('/[^\d. ]/', '', $data_json->grandTotal) : 0;
                $uid = isset($data_json->PR_NUMBER) ? $data_json->PR_NUMBER : '';
                $form_number = $value->UID;
                $delivery_date = isset($data_json->Delivery_Date) && date('Y-m-d', strtotime($data_json->Delivery_Date)) != '1970-01-01' ?  date('Y-m-d', strtotime($data_json->Delivery_Date)) : '-';
                $doc_type_desc = isset($data_json->doc_type) ? $data_json->doc_type : '';
                if($doc_type_desc){
                    try {
                        $doc_type_desc = DB::connection('dbintranet')
                        ->table('TBL_PR_DOCTYPE')
                        ->where('PRDOCTYPE_CODE', $doc_type_desc)
                        ->first()->PRDOCTYPE_DESCRIPTION;
                    } catch(\Exception $e){}
                }

                $cost_center_desc = '-';
                $cost_center = '';
                try {
                    $cost_center = isset($data_json->cost_center) ? $data_json->cost_center : '';
                    $data_costcenter=DB::connection('dbintranet')
                        ->select("SELECT TERRITORY_NAME, DEPARTMENT_NAME, DIVISION_NAME, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID = '".$cost_center."'");
                    $cost_center_desc = (!empty($data_costcenter)) ? $data_costcenter[0]->SAP_COST_CENTER_DESCRIPTION : '';
                } catch(\Exception $e){}

                $data_requestor=DB::connection('dbintranet')
                    ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");
                $data_pr_detail = [];

                if($uid){
                    try{
                        $param = array(
                            // 'GV_NUMBER'=>$uid
                            'NUMBER'=>$uid
                        );
                        // $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                        $function_type = $rfc->getFunction('BAPI_REQUISITION_GETDETAIL');
                        $data_pr_detail= $function_type->invoke($param, $options);
                    }catch(SAPFunctionException $e){}
                }

                // filter jika sudah ada nomor PO
                $nomor_po = (isset($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER']) && !empty($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'])) ? $data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'] : '';

                $sloc_desc = '-';
                $sloc = '';
                try {
                    $plant = count(explode('-', $data_json->plant)) > 0 ? strtoupper(trim(explode('-', $data_json->plant)[0])) : '';
                    $sloc = isset($data_json->sloc) ? $data_json->sloc : '';
                    $param_sloc = [
                        'P_COMPANY'=>"",
                        'P_PLANT'=>$plant
                    ];
                    $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
                    $sloc_res = $function->invoke($param_sloc, $options);
                    $sap_sloc = isset($sloc_res['IT_SLOC']) ? $sloc_res['IT_SLOC'] : [];
                    if(count($sap_sloc)){
                        $sloc_desc = collect($sap_sloc)->filter(function($item, $key) use ($sloc){
                            return $item['STORAGE_LOCATION'] == $sloc;
                        })->first()['STORAGE_LOCATION_DESC'];
                    }
                } catch(\Exception $e){}

                $result[]=array(
                    'UID'=>$uid,
                    'FORM_NUMBER'=>$form_number,
                    'PO_NUMBER'=>$nomor_po,
                    'DOC_TYPE'=>$doc_type_desc,
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                    'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                    'REQ_BY_COSTCENTER'=>isset($data_requestor[0]->JSON_ENCODE) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                    'REQ_DATE'=>$value->INSERT_DATE,
                    'DELIVERY_DATE'=>$delivery_date,
                    'GRANDTOTAL'=>$grandTotal,
                    'PURPOSE'=>$purpose,
                    'COSTCENTER'=>$cost_center_desc,
                    'COSTCENTERID'=>$cost_center,
                    'SLOC'=>$sloc_desc,
                    'SLOC_ID'=>$sloc
                );

            }

            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function approval_getData(Request $request)
    {
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
            $insert_date_from=$request->input('date_from');
            $insert_date_to=$request->input('date_to');
            $requestor_name =$request->input('REQUESTOR_NAME', false);
            $filter_cc =$request->input('SHIP_TO_COST_CENTER');
            $filter_sloc =$request->input('SHIP_TO_SLOC');
            $filter_date_type = $request->input('filter_type');

            $where="";
            if($requestor_name){
                $where = $where."REQUESTOR_NAME='${requestor_name}' AND";
            }

            // Filter cost center
            $where_cc = '';
            if($filter_cc != null){
                $where_cc = " and SHIP_TO_COST_CENTER = '".$filter_cc."'";
            }

            // Filter sloc
            $where_sloc = '';
            if($filter_sloc != null){
                $where_sloc = " and SHIP_TO_SLOC = '".$filter_sloc."'";
            }

            $filter_date = '';
            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                if($filter_date_type){
                    $filter_date_type = strtoupper($filter_date_type);
                    $filter_date = "and ${filter_date_type} between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                } else {
                    $filter_date = "and REQUEST_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                }
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
                    ${filter_date} ${where_cc} ${where_sloc}
                    OR REQUESTOR_NAME='".$requestor_name."' AND APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                    AND APPROVAL_LEVEL = ".$i." AND STATUS_APPROVAL <> 'REJECTED' ${filter_date} ${where_cc} ${where_sloc}
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
                    ${filter_date} ${where_cc} ${where_sloc}
                    OR APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."'
                    AND APPROVAL_LEVEL = ".$i." AND STATUS_APPROVAL <> 'REJECTED' ${filter_date} ${where_cc} ${where_sloc}
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
                $purpose = isset($data_json->purpose) ? $data_json->purpose : '';
                $grandTotal = isset($data_json->grandTotal) ? preg_replace('/[^\d. ]/', '', $data_json->grandTotal) : 0;
                $uid = isset($data_json->PR_NUMBER) ? $data_json->PR_NUMBER : '';
                $form_number = $value->UID;
                $delivery_date = isset($data_json->Delivery_Date) && date('Y-m-d', strtotime($data_json->Delivery_Date)) != '1970-01-01' ?  date('Y-m-d', strtotime($data_json->Delivery_Date)) : '-';
                $doc_type_desc = isset($data_json->doc_type) ? $data_json->doc_type : '';
                if($doc_type_desc){
                    try {
                        $doc_type_desc = DB::connection('dbintranet')
                        ->table('TBL_PR_DOCTYPE')
                        ->where('PRDOCTYPE_CODE', $doc_type_desc)
                        ->first()->PRDOCTYPE_DESCRIPTION;
                    } catch(\Exception $e){}
                }

                $cost_center_desc = '-';
                $cost_center = '';
                try {
                    $cost_center = isset($data_json->cost_center) ? $data_json->cost_center : '';
                    $data_costcenter=DB::connection('dbintranet')
                        ->select("SELECT TERRITORY_NAME, DEPARTMENT_NAME, DIVISION_NAME, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID = '".$data_json->cost_center."'");
                    $cost_center_desc = (!empty($data_costcenter)) ? $data_costcenter[0]->SAP_COST_CENTER_DESCRIPTION : '';
                } catch(\Exception $e){}

                $data_requestor=DB::connection('dbintranet')
                    ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");
                $data_pr_detail = [];
                if($uid){
                    try{
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

                        $param = array(
                            // 'GV_NUMBER'=>$uid
                            'NUMBER'=>$uid
                        );
                        // $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                        $function_type = $rfc->getFunction('BAPI_REQUISITION_GETDETAIL');
                        $data_pr_detail= $function_type->invoke($param, $options);
                    }catch(SAPFunctionException $e){}
                }

                // filter jika sudah ada nomor PO
                $nomor_po = (isset($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER']) && !empty($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'])) ? $data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'] : '';

                $sloc_desc = '-';
                $sloc = '';
                try {
                    $plant = count(explode('-', $data_json->plant)) > 0 ? strtoupper(trim(explode('-', $data_json->plant)[0])) : '';
                    $sloc = isset($data_json->sloc) ? $data_json->sloc : '';
                    $param_sloc = [
                        'P_COMPANY'=>"",
                        'P_PLANT'=>$plant
                    ];
                    $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
                    $sloc_res = $function->invoke($param_sloc, $options);
                    $sap_sloc = isset($sloc_res['IT_SLOC']) ? $sloc_res['IT_SLOC'] : [];
                    if(count($sap_sloc)){
                        $sloc_desc = collect($sap_sloc)->filter(function($item, $key) use ($sloc){
                            return $item['STORAGE_LOCATION'] == $sloc;
                        })->first()['STORAGE_LOCATION_DESC'];
                    }
                } catch(\Exception $e){}

                $result[]=array(
                    'UID'=>$uid,
                    'FORM_NUMBER'=>$form_number,
                    'PO_NUMBER'=>$nomor_po,
                    'DOC_TYPE'=>$doc_type_desc,
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                    'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                    'REQ_BY_COSTCENTER'=>isset($data_requestor[0]->JSON_ENCODE) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                    'REQ_DATE'=>$value->INSERT_DATE,
                    'NAME'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                    'ALIAS'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                    'APPROVAL_LEVEL'=>$value->APPROVAL_LEVEL,
                    'DELIVERY_DATE'=>$delivery_date,
                    'GRANDTOTAL'=>$grandTotal,
                    'PURPOSE'=>$purpose,
                    'COSTCENTER'=>$cost_center_desc,
                    'COSTCENTERID'=>$cost_center,
                    'SLOC'=>$sloc_desc,
                    'SLOC_ID'=>$sloc
                );
            }
            // return DataTables::of($result)->make(true);
        } catch(\Exception $e){
            // return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
            Log::error('APPROVAL GET DATA PR MARKETLIST ERROR | '.(string)$e);
        }

        return DataTables::of($result)->make(true);
    }

    public function report_getData(Request $request){
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
        $result=array();
        
        try{
            DB::enableQueryLog();
            $deptId=$request->input('deptId');
            $employeeId=$request->input('employeeId');
            $midjobId=$request->input('midjobId');
            $status=strtoupper($request->input('status'));
            $costcenter=$request->input('costcenter');
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID; //dapetin plant user yang login skrg
            $territory=Session::get('assignment')[0]->TERRITORY_ID; //dapetin plant user yang login skrg

            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('date_from');
            $insert_date_to=$request->input('date_to');
            $requestor_name =$request->input('REQUESTOR_NAME', false);
            $filter_cc =$request->input('SHIP_TO_COST_CENTER');
            $filter_sloc =$request->input('SHIP_TO_SLOC');
            $filter_date_type = $request->input('filter_type');

            $where="";
            if($requestor_name){
                $where = $where."REQUESTOR_NAME='${requestor_name}' AND";
            }

            // Filter cost center
            $where_cc = '';
            if($filter_cc != null){
                $where_cc = " and SHIP_TO_COST_CENTER = '".$filter_cc."'";
            }

            // Filter sloc
            $where_sloc = '';
            if($filter_sloc != null){
                $where_sloc = " and SHIP_TO_SLOC = '".$filter_sloc."'";
            }

            $filter_date = '';
            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                if($filter_date_type){
                    $filter_date_type = strtoupper($filter_date_type);
                    $filter_date = "and ${filter_date_type} between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                } else {
                    $filter_date = "and REQUEST_DATE between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
                }
            }

            $where_status = '';
            if(!empty($status)){
                $where_status = "AND STATUS_APPROVAL ='$status'";
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
                    REQUESTOR_NAME='".$requestor_name."' AND APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    AND APPROVAL_".$j."_TERRITORY_ID =
                    CASE WHEN (
                        SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->approval_view."
                        where APPROVAL_".$j."_PLANT_ID='".$plant."'
                        AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    ) = '0' THEN '0'
                    WHEN (
                        SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->approval_view."
                        where APPROVAL_".$j."_PLANT_ID='".$plant."'
                        AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    ) <> '0' THEN '".$territory."'
                    END
                    ${filter_date} ${where_cc} ${where_sloc} ${where_status}
                    OR REQUESTOR_NAME='".$requestor_name."' AND APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."' ${filter_date} ${where_cc} ${where_sloc} ${where_status}
                    ".$append."
                    ";
                } else {
                    $where .=$prepend."
                    APPROVAL_".$j."_PLANT_ID='".$plant."' AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    AND APPROVAL_".$j."_TERRITORY_ID =
                    CASE WHEN (
                        SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->approval_view."
                        where APPROVAL_".$j."_PLANT_ID='".$plant."'
                        AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    ) = '0' THEN '0'
                    WHEN (
                        SELECT TOP 1 APPROVAL_".$j."_TERRITORY_ID from ".$this->approval_view."
                        where APPROVAL_".$j."_PLANT_ID='".$plant."'
                        AND APPROVAL_".$j."_MIDJOB_ID='".$midjobId."'
                    ) <> '0' THEN '".$territory."'
                    END
                    ${filter_date} ${where_cc} ${where_sloc} ${where_status}
                    OR APPROVAL_".$j."_EMPLOYEE_ID='".$employeeId."' ${filter_date} ${where_cc} ${where_sloc} ${where_status}
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
                $purpose = isset($data_json->purpose) ? $data_json->purpose : '';
                $grandTotal = isset($data_json->grandTotal) ? preg_replace('/[^\d. ]/', '', $data_json->grandTotal) : 0;
                $uid = isset($data_json->PR_NUMBER) ? $data_json->PR_NUMBER : '';
                $form_number = $value->UID;
                $delivery_date = isset($data_json->Delivery_Date) && date('Y-m-d', strtotime($data_json->Delivery_Date)) != '1970-01-01' ?  date('Y-m-d', strtotime($data_json->Delivery_Date)) : '-';
                $doc_type_desc = isset($data_json->doc_type) ? $data_json->doc_type : '';
                if($doc_type_desc){
                    try {
                        $doc_type_desc = DB::connection('dbintranet')
                        ->table('TBL_PR_DOCTYPE')
                        ->where('PRDOCTYPE_CODE', $doc_type_desc)
                        ->first()->PRDOCTYPE_DESCRIPTION;
                    } catch(\Exception $e){}
                }

                $cost_center_desc = '-';
                $cost_center = '';
                try {
                    $cost_center = isset($data_json->cost_center) ? $data_json->cost_center : '';
                    $data_costcenter=DB::connection('dbintranet')
                        ->select("SELECT TERRITORY_NAME, DEPARTMENT_NAME, DIVISION_NAME, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID = '".$data_json->cost_center."'");
                    $cost_center_desc = (!empty($data_costcenter)) ? $data_costcenter[0]->SAP_COST_CENTER_DESCRIPTION : '';
                } catch(\Exception $e){}

                $data_requestor=DB::connection('dbintranet')
                    ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");
                $data_pr_detail = [];
                if($uid){
                    try{
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

                        $param = array(
                            // 'GV_NUMBER'=>$uid
                            'NUMBER'=>$uid
                        );
                        // $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                        $function_type = $rfc->getFunction('BAPI_REQUISITION_GETDETAIL');
                        $data_pr_detail= $function_type->invoke($param, $options);
                    }catch(SAPFunctionException $e){}
                }

                // filter jika sudah ada nomor PO
                $nomor_po = (isset($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER']) && !empty($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'])) ? $data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'] : '';

                $sloc_desc = '-';
                $sloc = '';
                try {
                    $plant = count(explode('-', $data_json->plant)) > 0 ? strtoupper(trim(explode('-', $data_json->plant)[0])) : '';
                    $sloc = isset($data_json->sloc) ? $data_json->sloc : '';
                    $param_sloc = [
                        'P_COMPANY'=>"",
                        'P_PLANT'=>$plant
                    ];
                    $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
                    $sloc_res = $function->invoke($param_sloc, $options);
                    $sap_sloc = isset($sloc_res['IT_SLOC']) ? $sloc_res['IT_SLOC'] : [];
                    if(count($sap_sloc)){
                        $sloc_desc = collect($sap_sloc)->filter(function($item, $key) use ($sloc){
                            return $item['STORAGE_LOCATION'] == $sloc;
                        })->first()['STORAGE_LOCATION_DESC'];
                    }
                } catch(\Exception $e){}

                $result[]=array(
                    'UID'=>$uid,
                    'FORM_NUMBER'=>$form_number,
                    'PO_NUMBER'=>$nomor_po,
                    'DOC_TYPE'=>$doc_type_desc,
                    'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                    'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                    'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                    'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                    'REQ_BY_COSTCENTER'=>isset($data_requestor[0]->JSON_ENCODE) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                    'REQ_DATE'=>$value->INSERT_DATE,
                    'NAME'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                    'ALIAS'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                    'APPROVAL_LEVEL'=>$value->APPROVAL_LEVEL,
                    'DELIVERY_DATE'=>$delivery_date,
                    'GRANDTOTAL'=>$grandTotal,
                    'PURPOSE'=>$purpose,
                    'COSTCENTER'=>$cost_center_desc,
                    'COSTCENTERID'=>$cost_center,
                    'SLOC'=>$sloc_desc,
                    'SLOC_ID'=>$sloc
                );
            }
        } catch(\Exception $e){
            // return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
            Log::error('APPROVAL GET DATA PR MARKETLIST ERROR | '.(string)$e);
        }

        return DataTables::of($result)->make(true);
    }

    public function save(Request $request) {
        // dd($request->post());
        try {
            $payload = ['status'=>'not_set', 'message'=>'Unknown operation, No data changes has been made'];

            $check_created_date = date('Y-m-d',strtotime($request->post('Delivery_Date')));
            if($check_created_date == '1970-01-01'){
                $payload['status'] = 'error';
                $payload['message'] = 'Invalid delivery date selected, please check the date format and try again';
                return response()->json($payload, 400);
            } else if(strtotime($check_created_date) < strtotime(date('Ymd'))){
                $payload['status'] = 'error';
                $payload['message'] = 'The delivery date selected less than today or now date is disallowed, please select now or future date';
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
            if(!isset($data['grandTotal'])){
                throw new \Exception("Cannot read Grand Total sent, please check your data and try again");
            }

            unset($data['_token']);
            $data['uid']=$uid;

            // START -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA
            $data_json=json_encode($data);
            $employee_id=$data['Requestor_Employee_ID'];
            $type="Request Purchase Requisition Marketlist";

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

                try {
                    // Insert Notif
                    $level_approval = 1;
                    $notif_link=$this->approval_view_link;
                    $notif_desc="Please approve Purchase Requisition Marketlist Form : ".$uid."";
                    $notif_type="info";
                    $insert_notif=$this->insertNotificationApproval($uid, $level_approval, $notif_link, $notif_desc, $notif_type);
                } catch(\Exception $e){
                    Log::error('Error insert notification for Marketlist PR Save | '.$e->getMessage());
                }

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

    public function modal_detail(Request $request)
    {
        $uid=$request->input('id');
        $status_approval = '';
        $action=(!empty($request->input('action')))? $request->input('action') : 'view'; // flag action, gunanya adalah ketika di modal detail supaya bisa kasi validasi apakah harus kasi tombol approve & reject di modal atau tidak (tapi berbeda dengan modal approve detail, ini hanya untuk approval tanpa inputan apapun)
        $type=$request->input('type', false) ? $request->input('type') : '';

        if(empty(Session::get('assignment')[0])){
            $division="SYSADMIN";
            $department="SYSADMIN";
            $company_code="SYSADMIN";
            $plant="SYSADMIN";
            $plant_name="SYSADMIN";
            $territory_id = "SYSADMIN";
            $territory_name = "SYSADMIN";
            $cost_center_id = "SYSADMIN";
            $job_title ="SYSADMIN";

        }else{
            $division=Session::get('assignment')[0]->DIVISION_NAME;
            $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
            $company_code=Session::get('assignment')[0]->COMPANY_CODE;
            $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
            $plant_name=Session::get('assignment')[0]->SAP_PLANT_NAME;
            $territory_id = Session::get('assignment')[0]->TERRITORY_ID;
            $territory_name = Session::get('assignment')[0]->TERRITORY_NAME;
            $job_title =Session::get('assignment')[0]->MIDJOB_TITLE_NAME;
        }

        $data_form=NULL;
        $data_json=NULL;
        $data_file=NULL;
        $requestor_id = '';
        if(!empty($uid)){
            $data_form=DB::connection('dbintranet')
            ->table($this->form_view)
            ->where('UID',$uid)
            ->get();
            $status_approval = $data_form[0]->APPROVAL_LEVEL > 0 ? true : false;
            $data_json = json_decode($data_form[0]->JSON_ENCODE);
            $cost_center_id=isset($data_json->cost_center) ? $data_json->cost_center : '';
            $requestor_id = isset($data_json->Requestor_Employee_ID) ? $data_json->Requestor_Employee_ID : '';
        }

        // Check for custom cost center
        $employee_id=Session::get('user_id');

        //mencari plant yang diassign untuk user tersebut selain plant default
        $plant_list = [];
        $plant_id = '';
        try {
            $plant_id = trim(explode('^-^', $data_json->plant)[0]);
            $plant_id = htmlspecialchars($plant_id);

            if(!$type){
                $plant_list=DB::connection('dbintranet')
                ->table('dbo.INT_BUSINESS_PLANT as p')
                ->leftJoin('dbo.SAP_PR_EMP_COSTCENTER_MAPPING as map','map.PLANT_ID','=','p.SAP_PLANT_ID')
                ->select('p.SAP_PLANT_ID','p.SAP_PLANT_NAME')
                ->where('EMPLOYEE_ID',$employee_id)
                ->orWhere('p.SAP_PLANT_ID',$plant)
                ->orderBy('SAP_PLANT_ID','ASC')
                ->distinct()->get()->toArray();
            } else if($type && strtolower($type) == 'list_ml') {
                $plant_list=DB::connection('dbintranet')
                ->table('dbo.INT_BUSINESS_PLANT as p')
                ->leftJoin('dbo.SAP_PR_EMP_COSTCENTER_MAPPING as map','map.PLANT_ID','=','p.SAP_PLANT_ID')
                ->select('p.SAP_PLANT_ID','p.SAP_PLANT_NAME')
                ->where('p.SAP_PLANT_ID',$plant_id)
                ->orderBy('SAP_PLANT_ID','ASC')
                ->distinct()->get()->toArray();
            }
        } catch(\Exception $e){}

        // done cari plant default
        // start -- cari cost center yang diperbolehkan
        $cc_where="SAP_COST_CENTER_ID = '$cost_center_id'";
        $i=0;

        DB::enableQueryLog();
        $check_multiple_cost_center = DB::connection('dbintranet')
        ->table('SAP_PR_EMP_COSTCENTER_MAPPING AS cm')
        ->join('INT_SAP_COST_CENTER AS cc', 'cm.SAP_COSTCENTER_ID', '=', 'cc.SAP_COST_CENTER_ID')
        ->where('cm.EMPLOYEE_ID', $requestor_id)
        ->select('cm.EMPLOYEE_ID', 'cc.SAP_COST_CENTER_DESCRIPTION', 'cm.SAP_COSTCENTER_ID AS SAP_COST_CENTER_ID', 'cc.SAP_COST_CENTER_NAME', 'cc.DIVISION_NAME', 'cc.DEPARTMENT_NAME')
        ->get();

        // Jika cost center requestor ada di mapping (Lebih dari 1 cost center)
        if($check_multiple_cost_center->count() > 0){
            $custom_cost_center = $check_multiple_cost_center;
        }
        else {
            $custom_cost_center = DB::connection('dbintranet')
            ->select("SELECT * FROM INT_SAP_COST_CENTER WHERE $cc_where ");
        }

        $sap_sloc = [];
        try {
            if(!$type){
                $check_mapping_sloc = DB::connection('dbintranet')
                ->table('dbo.SAP_ML_EMP_SLOC_MAPPING AS cm')
                ->where('cm.EMPLOYEE_ID', $requestor_id)
                ->get();
            } else if($type && strtolower($type) == 'list_ml'){
                $check_mapping_sloc = isset($data_json->sloc) ? [$data_json->sloc] : [null];
            }
        } catch(\Exception $e){}

        try {
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

            $param_sloc = [
                'P_COMPANY'=>"",
                'P_PLANT'=>$plant_id
            ];

            $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
            $result= $function->invoke($param_sloc, $options);
            $sap_sloc = isset($result['IT_SLOC']) ? $result['IT_SLOC'] : [];
            if(!is_array($check_mapping_sloc) && $check_mapping_sloc->count() > 0){
                $sloc_map = $check_mapping_sloc->pluck('SLOC_ID', 'SLOC_ID')->values()->all();
                $sap_sloc = collect($sap_sloc)->filter(function($item, $key) use ($sloc_map){
                    return in_array($item['STORAGE_LOCATION'], $sloc_map);
                })->values()->all();
            } else if(is_array($check_mapping_sloc) && count($check_mapping_sloc) > 0) {
                $sloc_map = $check_mapping_sloc;
                $sap_sloc = collect($sap_sloc)->filter(function($item, $key) use ($sloc_map){
                    return in_array($item['STORAGE_LOCATION'], $sloc_map);
                })->values()->all();
            }

        } catch(SAPFunctionException $e){
            Log::error('SLOC PR MARKETLIST SAP ERROR | '. $e->getMessage());
        } catch(\Exception $e){
            Log::error('SLOC PR MARKETLIST GENERAL ERROR | '. $e->getMessage());
        }

        $data=array(
            'data_form'=>isset($data_form[0]) ? $data_form[0] : [],
            'data_json'=>$data_json,
            'plant_list'=>$plant_list,
            'custom_cost_center'=>$custom_cost_center,
            's_loc'=>$sap_sloc,
            'action'=>$action,
            'status_approval'=>$status_approval,
            'company_code'=>$company_code
        );

        // dd($data);
        if($request->input('marketlist_type') == 'report'){
            return View::make('pages.finance.purchase-requisition-marketlist.modal-detail-report')->with('data', $data)->render();
        } else {
            return View::make('pages.finance.purchase-requisition-marketlist.modal-detail')->with('data', $data)->render();
        }
    }

    public function submitApprovalFormBatch(Request $request)
    {
        $FormID=imuneString($request->input('form_id'));
        $data = explode(";" , $FormID);
        $EmployeID=imuneString($request->input('employe_id'));
        $StatusApproval=imuneString($request->input('status_approval'));
        $TypeForm=imuneString($request->input('type_form'));
        $Reason=imuneString($request->input('reason'));
        $type=$request->input('type', '');

        $totalData=0;
        $success=0;
        $failed=0;
        foreach ($data as $key => $dataId) {
            try {
                $dataDetail=explode("#",$dataId);
                $idform=$dataDetail[0];

                $cek_data_json = DB::connection('dbintranet')
                ->table('INT_FIN_APPR_RAW_DATA')
                ->where('UID', $idform)
                ->select('UID', 'JSON_ENCODE')
                ->first();

                if(!$cek_data_json){
                    $failed++;
                    Log::error("Cannot find ${idform} when trying to approve batch PR Marketlist");
                } else{
                    $data_json = json_decode($cek_data_json->JSON_ENCODE);
                    $request->merge(['form_id'=> $dataId]);
                    $request->request->add([
                        'marketlistItemOrder'=>isset($data_json->marketlistItemOrder) ? $data_json->marketlistItemOrder : [],
                        'marketlistMaterialNumber'=>isset($data_json->marketlistMaterialNumber) ? $data_json->marketlistMaterialNumber : [],
                        'marketlistMaterialName'=>isset($data_json->marketlistMaterialName) ? $data_json->marketlistMaterialName : [],
                        'marketlistMaterialUnit'=>isset($data_json->marketlistMaterialUnit) ? $data_json->marketlistMaterialUnit : [],
                        'marketlistMaterialQty'=>isset($data_json->marketlistMaterialQty) ? $data_json->marketlistMaterialQty : [],
                        'marketlistMaterialNote'=>isset($data_json->marketlistMaterialNote) ? $data_json->marketlistMaterialNote : [],
                        'marketlistMaterialLastPrice'=>isset($data_json->marketlistMaterialLastPrice) ? $data_json->marketlistMaterialLastPrice : [],
                        'marketlistMaterialLastPriceFormatted'=>isset($data_json->marketlistMaterialLastPriceFormatted) ? $data_json->marketlistMaterialLastPriceFormatted : [],
                        'Request_Date'=>date('Y-m-d', strtotime(isset($data_json->Request_Date) ? $data_json->Request_Date : date('Y-m-d'))),
                        'purpose'=>isset($data_json->purpose) ? $data_json->purpose : '',
                        'doc_type'=>isset($data_json->doc_type) ? $data_json->doc_type : '',
                        'Requestor_Employee_ID'=>isset($data_json->Requestor_Employee_ID) ? $data_json->Requestor_Employee_ID : '',
                        'Requestor_Name'=>isset($data_json->Requestor_Name) ? $data_json->Requestor_Name : '',
                        'Requestor_Name'=>isset($data_json->Requestor_Name) ? $data_json->Requestor_Name : '',
                        'plant'=>isset($data_json->plant) ? $data_json->plant : '',
                        'reason'=>$Reason,
                        'status_approval'=>$StatusApproval,
                        'type_form'=>$TypeForm,
                        'cost_center'=>isset($data_json->cost_center) ? $data_json->cost_center : '',
                        'Delivery_Date'=>date('d F Y', strtotime(isset($data_json->Delivery_Date) ? $data_json->Delivery_Date : date('Y-m-d'))),
                        'sloc'=>isset($data_json->sloc) ? $data_json->sloc : '',
                        'type'=>$type
                    ]);

                    $result = $this->submitApprovalForm($request);
                    $result = $result->getData();
                    if (isset($result->code) && $result->code == 200){
                        $success++;
                    }else{
                        return response()->json(['exception'=>true, 'message'=>isset($result->message) ? $result->message : "Something went wrong with Form ${idform}"], $result->code);
                    }
                }
            } catch(\Exception $e){
                $message = $e->getMessage();
                return response()->json(['status'=>'failed', 'exception'=>true, 'message'=>"Something went wrong with Form ${idform}, ${message}"], 400);
            }

            $totalData++;
        }

        $hasil["Total_Data"]=$totalData;
        $hasil["Total_Success"]=$success;
        $hasil["Total_Failed"]=$failed;

        return $hasil;
    }

    public function submitApprovalForm(Request $request)
    {
        try {
            // START -- INIT RFC
            $is_production = config('intranet.is_production');
            if($is_production){
                $rfc = new SapConnection(config('intranet.rfc_prod'));
            }else{
                $rfc = new SapConnection(config('intranet.rfc'));
            }
            $options = [
                'rtrim'=>true
            ];
            // END -- INIT RFC
            
            // START -- KEPERLUAN UNTUK SAVE KE SAP
            $GiItemText = array(); //init variable
            $GiReqAccAss = array(); //init variable
            $GiReqItems = array(); //init variable
            $GiReturn =array(); //init variable

            $employee_id=Session::get('user_id');
            $LastApprovalID = $employee_id;
            $dateRequest = strtoupper($request->input("Request_Date"));
            $purpose = strtoupper($request->input("purpose"));
            $item_count = $request->input('marketlistItemOrder', []);
            $docType = strtoupper($request->input("doc_type"));
            $requestedBy = strtoupper($request->input("Requestor_Employee_ID"));
            $requesterName = strtoupper($request->input("Requestor_Name"));
            $plant = count(explode('-', $request->input("plant", ''))) > 0 ? strtoupper(trim(explode('-', $request->input("plant", ''))[0])) : '';
            $form_number = count(explode('#', $request->input("form_id", ''))) > 0 ? strtoupper(trim(explode('#', $request->input("form_id", ''))[0])) : '';
            $approval_level = count(explode('#', $request->input("form_id", ''))) > 1 ? strtoupper(trim(explode('#', $request->input("form_id", ''))[1])) : '';
            $reason = $request->input('reason');
            $StatusApproval = $request->input('status_approval');
            $type_form = $request->input('type_form');
            $approval_level = (int)$approval_level + 1;

            $check_PR_exist=DB::connection('dbintranet')
            ->table('INT_FIN_APPR_RAW_DATA')
            ->where('UID', $form_number)
            ->first();
            if(!$check_PR_exist)
                throw new \Exception("Cannot find Purchase Request Marketlist existing data in database, terminating process...");

            $decode_data = json_decode($check_PR_exist->JSON_ENCODE);
            $pr_number_created = isset($decode_data->PR_NUMBER) ? $decode_data->PR_NUMBER : null;    
            if($pr_number_created){
                $success=true;
                $code = 200;
                $log= 'This PR Marketlist was already assigned a PR Number and executed more than one';
                $msg = 'PR has been successfully saved and approved in SAP';
                $insert_id=$pr_number_created;
                return response()->json(array('success' => $success, 'message' => $msg, 'code' => $code, 'log' => $log, 'insert_id'=> $insert_id), $code);
            }

            if($StatusApproval == 'APPROVED' || $StatusApproval == 'FINISHED'){
                for($i=0;$i<count($item_count);$i++){
                    // Exclude item with qty equals to Zero
                    if((float)$request->input('marketlistMaterialQty')[$i] > 0){
                        $TextLine=(isset($request->input('marketlistMaterialNote')[$i]) ? strtoupper($request->input('marketlistMaterialNote')[$i]) : 'No Item Note')." - (Info) ".strtoupper($purpose);

                        // Item Text
                        $GiItemText[]=array(
                            'PREQ_NO'=>'',
                            'PREQ_ITEM'=>strtoupper($request->input('marketlistItemOrder')[$i]),
                            'TEXT_ID'=>'B01',
                            'TEXT_FORM'=>'',
                            'TEXT_LINE'=>$TextLine
                        );

                        // Acct Assignment
                        $item_costCenter=(!empty($request->input('cost_center')))? strtoupper($request->input('cost_center')) : '';
                        $GiReqAccAss[]=array(
                            'PREQ_ITEM'=>strtoupper($request->input('marketlistItemOrder')[$i]),
                            'G_L_ACCT'=>'',
                            'BUS_AREA'=>'',
                            'COST_CTR'=>$item_costCenter,
                            'ASSET_NO'=>'',
                            'ORDER_NO'=>'',
                            'SERIAL_NO'=>"01",
                            'PREQ_QTY'=>(float)$request->input('marketlistMaterialQty')[$i],
                        );

                        $deliv_date=$request->input('Delivery_Date');
                        if(empty($deliv_date)){
                            $deliv_date= date('Ymd',strtotime($dateRequest));
                        }else{
                            $deliv_date= date('Ymd',strtotime($request->input('Delivery_Date')));
                        }

                        //validasi untuk document Type YOCN
                        $item_cat=(strtoupper($docType) == "YOCN")? '2' : '';
                        $harga_item=(float)$request->input('marketlistMaterialLastPrice')[$i];
                        $gr_non_val= strtoupper((isset($request->input('acctasscat')[$i]) && $request->input('acctasscat')[$i] =='4' ) ? 'X' : '');

                        if($harga_item > 0){
                            $qty_item = (float)$request->input('marketlistMaterialQty')[$i];
                            if($qty_item > 0){
                                $price_unit = $harga_item / $qty_item;
                            } else{
                                $price_unit = 1;
                            }
                        } else {
                            $price_unit = 1;
                        }

                        $cost_center_type = DB::connection('dbintranet')
                        ->table('dbo.INT_SAP_COST_CENTER')
                        ->where('SAP_COST_CENTER_ID', $item_costCenter)
                        ->select('EXPENSE_TYPE')
                        ->get()->pluck('EXPENSE_TYPE')->first();
                        if(!$cost_center_type){
                            $success=false;
                            $code = 400;
                            $msg = 'Cannot determine type of account assignment for Cost Center '.$item_costCenter.', please check the data and try again';
                            $log='';
                            $insert_id='';
                            return response()->json(array('success' => $success, 'message' => $msg, 'code' => $code, 'log' => $log, 'exception'=>true), $code);
                        }

                        $acctasscat = strtoupper((String)$cost_center_type);
                        $GiReqItems[]=array(
                            'PREQ_ITEM'=>strtoupper($request->input('marketlistItemOrder')[$i]),
                            'DOC_TYPE'=>strtoupper($docType),
                            'PUR_GROUP'=>'',
                            'PREQ_NAME'=>strtoupper($requestedBy),
                            'PREQ_DATE'=>date('Ymd',strtotime($dateRequest)),
                            'MATERIAL'=>strtoupper('000000000'.$request->input('marketlistMaterialNumber')[$i]),
                            'PLANT'=>strtoupper($plant),
                            'MAT_GRP'=>'',
                            'QUANTITY'=>(float)$request->input('marketlistMaterialQty')[$i],
                            'UNIT'=>strtoupper($request->input('marketlistMaterialUnit')[$i]),
                            'STORE_LOC'=>strtoupper($request->input('sloc')),
                            'DELIV_DATE'=>$deliv_date,
                            'C_AMT_BAPI'=>$price_unit,
                            'PRICE_UNIT'=>1,
                            'ACCTASSCAT'=>$acctasscat,
                            // 'ACCTASSCAT'=>'1',
                            // 'FIXED_VEND'=>strtoupper($vendor),
                            'CURRENCY'=>'IDR',
                            'GR_IND'=>'X',
                            'GR_NON_VAL'=>$gr_non_val,
                            'IR_IND'=>'X',
                            'PURCH_ORG'=>'',
                            'VAL_TYPE'=>'',
                            'TRACKINGNO'=>$item_costCenter,
                            'PO_PRICE'=>'',
                            'CMMT_ITEM'=>'',
                            'ITEM_CAT'=>$item_cat
                        );
                    }
                }

                $GiReturn[]=array(
                    'TYPE'=>'',
                    'CODE'=>'',
                    'MESSAGE'=>'',
                    'LOG_NO'=>'',
                    'LOG_MSG_NO'=>'',
                    'MESSAGE_V1'=>'',
                    'MESSAGE_V2'=>'',
                    'MESSAGE_V3'=>'',
                    'MESSAGE_V4'=>''
                );

                $GiHeaderText[] = array(
                    'PREQ_NO'=>'',
                    'PREQ_ITEM'=>'',
                    'TEXT_ID'=>'B01',
                    'TEXT_FORM'=>'',
                    'TEXT_LINE'=>$purpose
                );

                $param = array(
                    'GI_REQ_ITEMS'=>$GiReqItems,
                    'GI_REQ_ACC_ASS'=>$GiReqAccAss,
                    // 'GI_RETURN'=>$GiReturn,
                    'GI_ITEM_TEXT'=>$GiItemText,
                    'GI_HEADER_TEXT'=>$GiHeaderText
                );

                // dd($type_form, $new_seq, $param);
                $function_type = $rfc->getFunction('ZFM_PR_CREATE_INTRA_MID');
                $save_sap= $function_type->invoke($param, $options);
                // END -- KEPERLUAN UNTUK SAVE KE SAP
                 
                $rel_code_found = 0;
                $data_release=isset($save_sap['GW_RELEASE']) ? $save_sap['GW_RELEASE'] : [];
                for($i=1;$i<=8;$i++){
                    if($data_release['REL_CODE'.$i]){
                        $rel_code_found++;
                    }
                }
                // If relcode is not found
                // main cause by different type of purchasing group of item
                // if(!$rel_code_found){
                //     return response()->json(array('success' => false, 'message' => 'PR without release strategy, Please contact IT Administrator', 'code' => 400, 'exception'=>true ), 400);
                // }

                $result_sap = $save_sap['GI_RETURN'];
                if(!empty($save_sap['GV_NUMBER'])){
                    $uid=$save_sap['GV_NUMBER']; // nomor PR yang ter create di SAP

                    // Try to approve both SAP and DB warehouse
                    try {

                        $connection = DB::connection('dbintranet');
                        $connection->beginTransaction();

                        $check_data=DB::connection('dbintranet')
                        ->table('INT_FIN_APPR_RAW_DATA')
                        ->where('UID', $form_number)
                        ->first();
                        // END -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA
                        if($check_data && strtolower($request->input('type')) != 'quick_approve'){
                            $data=$request->post();
                            unset($data['_token']);
                            $data['PR_NUMBER'] = $uid;

                            // Set new order item
                            // if any qty is less than zero on approval
                            try {
                                $new_data_order = [];
                                $order = 1;
                                foreach($request->input('marketlistMaterialQty') as $key => $val){
                                    if((float)$val > 0){
                                        $loop = $order;
                                        if($loop < 10){
                                            $item_order = "000".(string)($loop * 10);
                                        }
                                        else if($loop < 100 && $loop > 9){
                                            $item_order = "00".(string)($loop * 10);
                                        }
                                        else if($loop < 1000 && $loop > 90){
                                            $item_order = "0".(string)($loop * 10);
                                        }
                                        else if($loop < 10000 && $loop > 900){
                                            $item_order = "0".(string)($loop * 10);
                                        }

                                        $new_data_order[] = $item_order;
                                        $order++;
                                    } else {
                                        $new_data_order[] = "0000";
                                    }
                                }
                                $data['marketlistItemOrder'] = $new_data_order;
                            } catch(\Exception $e){}
                            // end set new order item

                            $old_data = isset($check_data->JSON_ENCODE) ? json_decode($check_data->JSON_ENCODE) : (object)[];
                            if(isset($old_data->OLD_REQUEST_DATA)){
                                unset($old_data->OLD_REQUEST_DATA);
                                $data['OLD_REQUEST_DATA'] = $old_data;
                            }
                            else{
                                $data['OLD_REQUEST_DATA'] = $old_data;
                            }

                            $updated_json = json_encode($data);
                            $update_data = DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_RAW_DATA')
                            ->where('UID', $form_number)
                            ->update(['JSON_ENCODE'=>$updated_json]);
                        } else if($check_data && strtolower($request->input('type')) == 'quick_approve'){
                            $data = json_decode($check_data->JSON_ENCODE);
                            $data->PR_NUMBER = $uid;
                            $updated_json = json_encode($data);
                            $update_data = DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_RAW_DATA')
                            ->where('UID', $form_number)
                            ->update(['JSON_ENCODE'=>$updated_json]);
                        }

                        // START -- KEBUTUHAN INSERT DATA KE TABEL PR_RELEASE
                        $data_release=$save_sap['GW_RELEASE'];
                        for($i=1;$i<=8;$i++){
                            if($data_release['REL_CODE'.$i]){
                                $approver = $employee_id;
                            } else {
                                $approver = NULL;
                            }
                            DB::connection('dbintranet')
                            ->table('TBL_PR_RELEASE')
                            ->insert(
                                [
                                    "RELCODE" => $data_release['REL_CODE'.$i],
                                    "RELCODEDESC" => $data_release['REL_CD_TX'.$i],
                                    "RELCODENAME" =>NULL,
                                    "STATUS" => NULL,
                                    "RELEASEDATE" => NULL,
                                    "PRNUMBER" => $uid,
                                    "REASON" =>NULL,
                                    "RELEASE_CODE_NAME_ID"=>$approver,
                                    'FORM_NUMBER'=>$form_number
                                ]
                            );
                        }


                        $cek_release_code = DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->where(['RELEASE_CODE_NAME_ID'=>$employee_id, 'STATUS'=>NULL, 'RELEASEDATE'=>NULL, 'FORM_NUMBER'=>$form_number, 'PRNUMBER'=>$uid])
                        ->first();
                        if(!$cek_release_code)
                            throw new \Exception("Release Code is not found on SAP, please contact IT Administrator");
                            
                        $cek_release_code = $cek_release_code->RELCODE;
                        $param = array(
                            'GV_NUMBER'=>$uid,
                            'GV_REL_CODE'=>$cek_release_code
                        );

                        $function_type = $rfc->getFunction('ZFM_PR_RELEASE_INTRA');
                        $approve= $function_type->invoke($param, $options);

                        $new_indicator=$approve['GV_REL_INDICATOR_NEW'];
                        $new_status=isset($approve['GV_REL_STATUS_NEW']) ? $approve['GV_REL_STATUS_NEW'] : '';
                        $next_approval=$approve['GV_REL_CODE_NEXT'];

                        if(empty($next_approval) || $next_approval==""){
                            $StatusApproval="FINISHED";
                        }else{
                            $StatusApproval="APPROVED";
                        }

                        $CountData=DB::connection('dbintranet')->table('INT_FIN_APPR_LIST')
                        ->where('FORM_ID',$form_number)
                        ->count();
                        
                        //======================
                        if ($CountData == 0){ // jika blm ada di tabel list (dimana tabel list ini nyimpen progress dari approval)  makan akan create record dengan approval terakhir
                            $SaveApproval=DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_LIST')
                            ->insert(
                                [
                                    "FORM_ID" => $form_number,
                                    "APPROVAL_LEVEL" => $approval_level,
                                    "LAST_APPROVAL_ID" => $LastApprovalID,
                                    "LAST_APPROVAL_DATE" => DB::raw("GETDATE()"),
                                    "STATUS_APPROVAL" => $StatusApproval,
                                    "TYPE_FORM" => $type_form,
                                    "REASON" => $reason
                                ]
                            );
                        }else{ // jika sudah ada di tabel list (dimana tabel list ini nyimpen progress dari approval) maka akan update datanya dengan approval terakhir
                            $UpdateApproval=DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_LIST')
                            ->where('FORM_ID',$form_number)
                            ->update(
                                [
                                    "APPROVAL_LEVEL" => $approval_level,
                                    "LAST_APPROVAL_ID" => $LastApprovalID,
                                    "LAST_APPROVAL_DATE" => DB::raw("GETDATE()"),
                                    "STATUS_APPROVAL" => $StatusApproval,
                                    "TYPE_FORM" => $type_form,
                                    "REASON" => $reason
                                ]
                            );
                        }

                        $CountDataHistory=DB::connection('dbintranet')
                        ->table('INT_FIN_APPR_HISTORY')
                        ->where('FORM_ID',$form_number)
                        ->where('APPROVAL_LEVEL',$approval_level)
                        ->count();

                        if($CountDataHistory == 0){ //jika belum ada data history
                            $InsertLog=DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_HISTORY')
                            ->insert(
                                [
                                    "FORM_ID" => $form_number,
                                    "APPROVAL_ID" => $LastApprovalID,
                                    "APPROVAL_DATE" => DB::raw("GETDATE()"),
                                    "STATUS_APPROVAL" => $StatusApproval,
                                    "APPROVAL_LEVEL" => $approval_level,
                                    "TYPE_FORM" => $type_form,
                                    "REASON" => $reason
                                ]
                            );
                        }else{ // update data approval history
                            $UpdateLog=DB::connection('dbintranet')
                            ->table('INT_FIN_APPR_HISTORY')
                            ->where('FORM_ID',$form_number)
                            ->where('APPROVAL_LEVEL',$approval_level)
                            ->update(
                                [
                                    "APPROVAL_LEVEL" => $approval_level,
                                    "APPROVAL_ID" => $LastApprovalID,
                                    "APPROVAL_DATE" => DB::raw("GETDATE()"),
                                    "STATUS_APPROVAL" => $StatusApproval,
                                    "TYPE_FORM" => $type_form,
                                    "REASON" => $reason
                                ]
                            );
                        }

                        // Update PR Release
                        DB::connection('dbintranet')
                        ->table('TBL_PR_RELEASE')
                        ->where(['RELEASE_CODE_NAME_ID'=>$employee_id, 'STATUS'=>NULL, 'RELEASEDATE'=>NULL, 'FORM_NUMBER'=>$form_number, 'PRNUMBER'=>$uid])
                        ->update([
                            "STATUS"=>"APPROVED",
                            "RELEASEDATE"=>date('Y-m-d H:i:s'),
                        ]);

                        $connection->commit();
                        $success=true;
                        $code = 200;
                        $log=$save_sap;
                        $msg = 'PR has been successfully saved and approved in SAP';
                        $insert_id=$uid;

                    } catch(\Exception $e){
                        $success=false;
                        $code = 400;
                        $log=$save_sap;
                        $msg = $e->getMessage();
                        $insert_id='';

                        // Delete PR jika terjadi error 
                        // simpan data DB warehouse
                        $item_to_delete = [];
                        foreach($GiReqItems as $key_item => $val){
                            $item_to_delete[] = [
                                'PREQ_ITEM'=>isset($val['PREQ_ITEM']) ? $val['PREQ_ITEM'] : '',
                                'DELETE_IND'=>'X',
                                'CLOSED'=>'X'
                            ];
                        }
                        try{
                            $param = array(
                                'NUMBER'=>$uid,
                                'REQUISITION_ITEMS_TO_DELETE'=>$item_to_delete
                            );
                            $function_type = $rfc->getFunction('BAPI_REQUISITION_DELETE');
                            $execute=$function_type->invoke($param, $options);
                        } catch(\Exception $e){}

                        $connection->rollback();
                        return response()->json(array('success' => $success, 'message' => $msg, 'code' => $code, 'log' => $log, 'exception'=>true), $code);
                    }
                }else{
                    $success=false;
                    $code = 403;
                    $log=$save_sap;
                    $insert_id="";
                    $msg="";
                    foreach($result_sap as $loop_error){
                        $msg.=(isset($loop_error['MESSAGE']) && !empty($loop_error['MESSAGE']))? $loop_error['MESSAGE'].' ' : '';
                    }
                    if(strpos($msg,"not maintain")){
                        $msg .= ' Please contact IT Administrator';
                    } else if(strpos($msg,"deletion")){
                        $material_number = '';
                        $material_name = '';
                        preg_match_all('!\d+!', $msg, $matches);
                        if(is_array($matches) && count($matches) > 0){
                            $material_number = $matches[0][0];
                            $key = array_search($material_number, array_values($request->input('marketlistMaterialNumber')));
                            $material_name = isset($request->input('marketlistMaterialName')[$key]) ? $request->input('marketlistMaterialName')[$key] : '';
                        }
                        
                        if($material_name)
                            $msg = "Form request ${form_number} error. Material ${material_number} (${material_name}) is not available for request, Please contact IT Administrator";
                        else
                            $msg = $msg;
                    }
                    $msg=(empty($msg))? 'Something wrong with the SAP Integration, please try again later or contact IT Dept' : $msg;
                    return response()->json(array('success' => $success, 'message' => $msg, 'code' => $code, 'log' => $log, 'exception'=>true, 'insert_id'=> $insert_id), $code);
                }

            } else {
                $CountData=DB::connection('dbintranet')->table('INT_FIN_APPR_LIST')
                ->where('FORM_ID',$form_number)
                ->count();
                
                //======================
                if ($CountData == 0){ // jika blm ada di tabel list (dimana tabel list ini nyimpen progress dari approval)  makan akan create record dengan approval terakhir
                    $SaveApproval=DB::connection('dbintranet')
                    ->table('INT_FIN_APPR_LIST')
                    ->insert(
                        [
                            "FORM_ID" => $form_number,
                            "APPROVAL_LEVEL" => $approval_level,
                            "LAST_APPROVAL_ID" => $LastApprovalID,
                            "LAST_APPROVAL_DATE" => DB::raw("GETDATE()"),
                            "STATUS_APPROVAL" => $StatusApproval,
                            "TYPE_FORM" => $type_form,
                            "REASON" => $reason
                        ]
                    );
                }else{ // jika sudah ada di tabel list (dimana tabel list ini nyimpen progress dari approval) maka akan update datanya dengan approval terakhir
                    $UpdateApproval=DB::connection('dbintranet')
                    ->table('INT_FIN_APPR_LIST')
                    ->where('FORM_ID',$form_number)
                    ->update(
                        [
                            "APPROVAL_LEVEL" => $approval_level,
                            "LAST_APPROVAL_ID" => $LastApprovalID,
                            "LAST_APPROVAL_DATE" => DB::raw("GETDATE()"),
                            "STATUS_APPROVAL" => $StatusApproval,
                            "TYPE_FORM" => $type_form,
                            "REASON" => $reason
                        ]
                    );
                }

                $CountDataHistory=DB::connection('dbintranet')
                ->table('INT_FIN_APPR_HISTORY')
                ->where('FORM_ID',$form_number)
                ->where('APPROVAL_LEVEL',$approval_level)
                ->count();

                if($CountDataHistory == 0){ //jika belum ada data history
                    $InsertLog=DB::connection('dbintranet')
                    ->table('INT_FIN_APPR_HISTORY')
                    ->insert(
                        [
                            "FORM_ID" => $form_number,
                            "APPROVAL_ID" => $LastApprovalID,
                            "APPROVAL_DATE" => DB::raw("GETDATE()"),
                            "STATUS_APPROVAL" => $StatusApproval,
                            "APPROVAL_LEVEL" => $approval_level,
                            "TYPE_FORM" => $type_form,
                            "REASON" => $reason
                        ]
                    );
                }else{ // update data approval history
                    $UpdateLog=DB::connection('dbintranet')
                    ->table('INT_FIN_APPR_HISTORY')
                    ->where('FORM_ID',$form_number)
                    ->where('APPROVAL_LEVEL',$approval_level)
                    ->update(
                        [
                            "APPROVAL_LEVEL" => $approval_level,
                            "APPROVAL_ID" => $LastApprovalID,
                            "APPROVAL_DATE" => DB::raw("GETDATE()"),
                            "STATUS_APPROVAL" => $StatusApproval,
                            "TYPE_FORM" => $type_form,
                            "REASON" => $reason
                        ]
                    );
                }

                $success=true;
                $code = 200;
                $log='';
                $msg = 'PR has been successfully rejected';
                $insert_id='';
            }

            return response()->json(array('success' => $success, 'message' => $msg, 'code' => $code, 'log' => $log, 'insert_id'=> $insert_id), $code);
        } catch(SAPFunctionException $e){
            $success=false;
            $msg=$e->getMessage();
            $code=403;
            $log=$e;
            return response()->json(array('success' => $success, 'message' => $msg, 'code' => $code, 'log' => $log, 'exception'=>true), $code);
        } catch(\Exception $e){
            $success=false;
            $msg=$e->getMessage();
            $code=403;
            $log=$e;
            return response()->json(array('success' => $success, 'message' => $msg, 'code' => $code, 'log' => $log, 'exception'=>true), $code);
        }

    }

    public function update(Request $request) {
        try {
            $payload = ['status'=>'warning', 'message'=>'No data changes has been made'];

            $check_created_date = date('Y-m-d',strtotime($request->post('Delivery_Date')));
            $check_insert_date = date('Y-m-d',strtotime($request->post('Request_Date')));

            if($check_created_date == '1970-01-01'){
                $payload['status'] = 'error';
                $payload['message'] = 'Invalid delivery date selected, please check the date format and try again';
                return response()->json($payload, 400);
            } 
            else if(strtotime($check_insert_date) >= strtotime(date('Ymd')) && strtotime($check_created_date) < strtotime(date('Ymd')) ){
                $payload['status'] = 'error';
                $payload['message'] = 'The delivery date selected less than today or now date is disallowed, please select now or future date';
                return response()->json($payload, 400);
            }
            
            $check_approval = DB::connection('dbintranet')
            ->table($this->approval_view)
            ->where('UID', $request->post('uid', 0))
            ->select('APPROVAL_LEVEL', 'STATUS_APPROVAL', 'JSON_ENCODE')
            ->get()->first();

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

                if(!isset($data['grandTotal'])){
                    throw new \Exception("Cannot read Grand Total sent, please check your data and try again");
                }

                // START -- KEBUTUHAN INSERT DATA KE TABEL RAW DATA
                $data_json=json_encode($data);
                $employee_id=$data['Requestor_Employee_ID'];
                $type="Request Purchase Requisition Marketlist";

                DB::connection('dbintranet')->beginTransaction();
                try {
                    $updated_data = DB::connection('dbintranet')
                    ->table('INT_FIN_APPR_RAW_DATA')
                    ->where('UID', $request->post('uid', 0))
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
                throw new \Exception("This Purchase Requisition data has been approved, failed to make any changes.");
            }
        } catch(\Exception $e){
            $payload['message'] = $e->getMessage();
            $payload['status'] = 'error';
            return response()->json($payload, 410);
        }
        return response()->json($payload, 200);
    }

    public function list_ml(Request $request){
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
                    $where = $where." and JSON_ENCODE LIKE '%\"cost_center\":\"$cost_center\"%'";
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
                    $data_form=DB::connection('dbintranet')
                    ->table('INT_FIN_APPR_RAW_DATA')
                    ->leftJoin('INT_FIN_APPR_LIST','INT_FIN_APPR_RAW_DATA.UID','=','INT_FIN_APPR_LIST.FORM_ID')
                    ->where('INT_FIN_APPR_RAW_DATA.UID',$value->UID)
                    ->get();

                    $data_json = isset($data_form[0]->JSON_ENCODE) ? json_decode($data_form[0]->JSON_ENCODE) : [];
                    $purpose = isset($data_json->purpose) ? $data_json->purpose : '';
                    $grandTotal = isset($data_json->grandTotal) ? preg_replace('/[^\d. ]/', '', $data_json->grandTotal) : 0;
                    $uid = isset($data_json->PR_NUMBER) ? $data_json->PR_NUMBER : '';
                    $form_number = $value->UID;
                    $delivery_date = isset($data_json->Delivery_Date) && date('Y-m-d', strtotime($data_json->Delivery_Date)) != '1970-01-01' ?  date('Y-m-d', strtotime($data_json->Delivery_Date)) : '-';
                    $doc_type_desc = isset($data_json->doc_type) ? $data_json->doc_type : '';
                    if($doc_type_desc){
                        try {
                            $doc_type_desc = DB::connection('dbintranet')
                            ->table('TBL_PR_DOCTYPE')
                            ->where('PRDOCTYPE_CODE', $doc_type_desc)
                            ->first()->PRDOCTYPE_DESCRIPTION;
                        } catch(\Exception $e){}
                    }

                    $cost_center_desc = '-';
                    $cost_center = '';
                    try {
                        $cost_center = isset($data_json->cost_center) ? $data_json->cost_center : '';
                        $data_costcenter=DB::connection('dbintranet')
                            ->select("SELECT TERRITORY_NAME, DEPARTMENT_NAME, DIVISION_NAME, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE SAP_COST_CENTER_ID = '".$cost_center."'");
                        $cost_center_desc = (!empty($data_costcenter)) ? $data_costcenter[0]->SAP_COST_CENTER_DESCRIPTION : '';
                    } catch(\Exception $e){}

                    $data_requestor=DB::connection('dbintranet')
                        ->select("SELECT c.MIDJOB_TITLE_ID, b.EMPLOYEE_NAME, a.*  FROM INT_FIN_APPR_RAW_DATA a INNER JOIN INT_EMPLOYEE b  ON a.EMPLOYEE_ID = b.EMPLOYEE_ID LEFT JOIN INT_EMPLOYEE_ASSIGNMENT c ON a.EMPLOYEE_ID = c.EMPLOYEE_ID WHERE UID = '$uid' ");
                    $data_pr_detail = [];

                    if($uid){
                        try{
                            $param = array(
                                // 'GV_NUMBER'=>$uid
                                'NUMBER'=>$uid
                            );
                            // $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $function_type = $rfc->getFunction('BAPI_REQUISITION_GETDETAIL');
                            $data_pr_detail= $function_type->invoke($param, $options);
                        }catch(SAPFunctionException $e){}
                    }

                    // filter jika sudah ada nomor PO
                    $nomor_po = (isset($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER']) && !empty($data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'])) ? $data_pr_detail['REQUISITION_ITEMS'][0]['PO_NUMBER'] : '';

                    $sloc_desc = '-';
                    $sloc = '';
                    try {
                        $plant = count(explode('-', $data_json->plant)) > 0 ? strtoupper(trim(explode('-', $data_json->plant)[0])) : '';
                        $sloc = isset($data_json->sloc) ? $data_json->sloc : '';
                        $param_sloc = [
                            'P_COMPANY'=>"",
                            'P_PLANT'=>$plant
                        ];
                        $function = $rfc->getFunction('ZFM_MM_MD_SLOC_LIST');
                        $sloc_res = $function->invoke($param_sloc, $options);
                        $sap_sloc = isset($sloc_res['IT_SLOC']) ? $sloc_res['IT_SLOC'] : [];
                        if(count($sap_sloc)){
                            $sloc_desc = collect($sap_sloc)->filter(function($item, $key) use ($sloc){
                                return $item['STORAGE_LOCATION'] == $sloc;
                            })->first()['STORAGE_LOCATION_DESC'];
                        }
                    } catch(\Exception $e){}

                    $result[]=array(
                        'UID'=>$uid,
                        'FORM_NUMBER'=>$form_number,
                        'PO_NUMBER'=>$nomor_po,
                        'DOC_TYPE'=>$doc_type_desc,
                        'STATUS_APPROVAL'=>$value->STATUS_APPROVAL,
                        'LAST_APPROVAL_NAME'=>$value->LAST_APPROVAL_NAME,
                        'REQ_BY_ID'=>isset($data_requestor[0]->EMPLOYEE_ID) ? $data_requestor[0]->EMPLOYEE_ID : '',
                        'REQ_BY_MIDJOB'=>isset($data_requestor[0]->MIDJOB_TITLE_ID) ? $data_requestor[0]->MIDJOB_TITLE_ID : '',
                        'REQ_BY_COSTCENTER'=>isset($data_requestor[0]->JSON_ENCODE) ? json_decode($data_requestor[0]->JSON_ENCODE)->cost_center : '',
                        'REQ_DATE'=>$value->INSERT_DATE,
                        'DELIVERY_DATE'=>$delivery_date,
                        'GRANDTOTAL'=>$grandTotal,
                        'NAME'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                        'ALIAS'=>isset($value->REQUESTOR_NAME) ? $value->REQUESTOR_NAME : null,
                        'PURPOSE'=>$purpose,
                        'COSTCENTER'=>$cost_center_desc,
                        'COSTCENTERID'=>$cost_center,
                        'SLOC'=>$sloc_desc,
                        'SLOC_ID'=>$sloc
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

                if($is_superuser || Session::get('permission_menu') && Session::get('permission_menu')->has("view_".route('finance.purchase-requisition-marketlist.list_ml', array(), false))) {
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

            return view('pages.finance.purchase-requisition-marketlist.list-ml', [
                'data' => $data,
                'filtered_cost_center'=>$filter_cost_center,
                'list_cost_center'=>$list_cost_center,
                'filter_date_type'=>$filter_date_type
            ]);
        }
    }

    function add_template(Request $request){
        $data = [];
        try {
            if($this->wantsJson($request)){
                $data_material = [];
                try {
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

                    if($request->get('type', false) && strtolower($request->get('type')) == 'material'){
                        $keyword = strtoupper($request->get('searchTerm', ''));
                        $plant_code = $request->get('plant', '');

                        $param = array(
                            'GV_MAKTX'=>"*".$keyword."*",
                            'GV_ACCTASSCAT'=>'',
                            'GV_WERKS'=>$plant_code,
                            'GV_MAX_ROWS'=>30
                        );

                        $function_type = $rfc->getFunction('ZFM_POPUP_MAT_BDT_INTRA_MONTH');
                        $list_material= $function_type->invoke($param, $options);
                        $list_material = isset($list_material['GI_HEADER']) ? $list_material['GI_HEADER'] : [];
                        foreach ($list_material as $key => $value) {
                            $param = array(
                                'MATERIAL'=>isset($value['MATNR']) ? $value['MATNR'] : '',
                                'PLANT'=>$plant_code
                            );
                            $function_type = $rfc->getFunction('BAPI_MATERIAL_GET_DETAIL');
                            $material_detail = $function_type->invoke($param, $options);
                            $purchasing_group_found = isset($material_detail['MATERIALPLANTDATA']['PUR_GROUP']) ? $material_detail['MATERIALPLANTDATA']['PUR_GROUP'] : '';
                            $data_material[] = array("id"=>isset($value['MATNR']) ? ltrim($value['MATNR'], "0") : '', "text"=>isset($value['MAKTX']) ? $value['MAKTX'] : 'Unknown Material', "html"=>isset($value['MAKTX']) ? "<div>".$value['MAKTX']."</div>" : '<div>Unknown Material</div>', 'unit'=>isset($value['MEINS']) ? $value['MEINS'] : '', 'title'=>isset($value['MAKTX']) ? $value['MAKTX'] : 'Unknown Material', 'purchasing_group'=>$purchasing_group_found);
                        }
                        return response()->json($data_material);
                    }

                    else if($request->get('type', false) && strtolower($request->get('type')) == 'duplicate-template'){
                        $template_post = $request->get('template_name', '');
                        $plant_post = $request->get('plant', '');
                        $validation_template_name = DB::connection('dbintranet')
                        ->table('INT_MASTER_ML_TEMPLATE')
                        ->where(['TEMPLATE'=>$template_post, 'PLANT'=>$plant_post])
                        ->get()->count();
                        return response()->json(['status'=>'success', 'message'=>'Completed to check duplicate template', 'template_found'=>$validation_template_name], 200);
                    }
                } catch(\Exception $e){
                    $message = isset($e->getErrorInfo()['message']) ? $e->getErrorInfo()['message'] : $e->getMessage();
                    Log::error($message);
                    return response()->json(['status'=>'failed', 'message'=>$message], 400);
                }
                return response()->json([], 400);
            }

            // $cek_permission_create = 'create_'. (String)route('marketlisttemplate.list',[],false);
            // if(isset($request->session()->get('user_data')->IS_SUPERUSER) && $request->session()->get('user_data')->IS_SUPERUSER || session()->get('permission_menu')->has($cek_permission_create)){
            if(isset($request->session()->get('user_data')->IS_SUPERUSER) && $request->session()->get('user_data')->IS_SUPERUSER){
                $plant_user = DB::connection('dbintranet')
                ->table('dbo.INT_BUSINESS_PLANT')
                ->where('PLANT_TYPE', 'HOTEL')
                ->select('SAP_PLANT_ID', 'SAP_PLANT_NAME')
                ->get()->pluck('SAP_PLANT_NAME', 'SAP_PLANT_ID')->filter()->toArray();
            }
            else {
                $user_id = isset(session()->get('user_data')->EMPLOYEE_ID) ? session()->get('user_data')->EMPLOYEE_ID : '';
                $plant_user = DB::connection('dbintranet')
                ->table('dbo.VIEW_EMPLOYEE_ACCESS')
                ->where('EMPLOYEE_ID', $user_id)
                ->select('SAP_PLANT_ID', 'SAP_PLANT_NAME')
                ->get()->pluck('SAP_PLANT_NAME', 'SAP_PLANT_ID')->filter()->toArray();
            }
            $data['plant_user'] = $plant_user;
        } catch(\Exception $e){
            Log::error($e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
            return redirect()->route('marketlisttemplate.list')->with('message', $status);
        }

        return view('pages.finance.purchase-requisition-marketlist.template.add', ['data'=>$data]);
    }

    public function create_template(Request $request){
        $status = null;
        $data_to_insert = [];
        $purchasing_group_insert = NULL;
        try {
            $employee_id=Session::get('user_id', '');
            $validator = Validator::make([], []);
            $template_post = $request->post('template_name', '');
            $plant_post = $request->post('plant', '');

            $validation_template_name = DB::connection('dbintranet')
            ->table('INT_MASTER_ML_TEMPLATE')
            ->where(['TEMPLATE'=>$template_post, 'PLANT'=>$plant_post])
            ->get()->count();

            if(!$validation_template_name){
                $marketlistItemOrder = $request->post('marketlistItemOrder', []);
                if(count($marketlistItemOrder)){
                    for($ti=0;$ti<count($marketlistItemOrder);$ti++){
                        $sap_material_code = isset($request->post('marketlistMaterialNumber')[$ti]) ? $request->post('marketlistMaterialNumber')[$ti] : NULL;
                        $sap_material_name = isset($request->post('marketlistMaterialName')[$ti]) ? $request->post('marketlistMaterialName')[$ti] : NULL;
                        $sap_material_unit = isset($request->post('marketlistMaterialUnit')[$ti]) ? $request->post('marketlistMaterialUnit')[$ti] : NULL;
                        $sap_material_purch_group = isset($request->post('marketlistMaterialPurchGroup')[$ti]) ? $request->post('marketlistMaterialPurchGroup')[$ti] : NULL;
                        if(!$sap_material_purch_group || $purchasing_group_insert && $purchasing_group_insert != $sap_material_purch_group)
                            throw new \Exception("Some of the materials have an empty or different purchasing group, please make sure all purchasing groups are the same");
                            
                        $purchasing_group_insert = $sap_material_purch_group;
                        $data_to_insert[] = array(
                            'PLANT'=>strtoupper($plant_post), 
                            'TEMPLATE'=>strtoupper($template_post), 
                            'SAPMATERIALCODE'=>$sap_material_code,
                            'MATERIALNAME'=>strtoupper($sap_material_name),
                            'UOM'=>strtoupper($sap_material_unit),
                            'PURCH_GROUP'=>strtoupper($sap_material_purch_group),
                            'STATUS'=>'1'
                        );
                    }
                }
            } else {
                $validator->getMessageBag()->add('plant', 'This plant is already exist alongwith the template name');
                $validator->getMessageBag()->add('template_name', 'This template name is already exist alongwith the plant selected');
                return redirect()->route('marketlisttemplate.add')->withErrors($validator)->withInput();
            }
            // End validation template name

            // Insert to database
            if($data_to_insert){
                $data_mapping = [
                    'EMPLOYEE_ID'=>$employee_id,
                    'ML_TEMPLATE'=>$template_post
                ];

                DB::connection('dbintranet')->beginTransaction();
                $chunk_update = array_chunk($data_to_insert, 100);
                for($loop_insert=0;$loop_insert<count($chunk_update);$loop_insert++){
                    DB::connection('dbintranet')
                    ->table('dbo.INT_MASTER_ML_TEMPLATE')
                    ->insert($chunk_update[$loop_insert]);
                }

                DB::connection('dbintranet')
                ->table('INT_EMP_ML_TEMPLATE')
                ->insert($data_mapping); 

                DB::connection('dbintranet')->commit();
                $status = array('msg'=>"Template has been successfully added", 'type'=>'success');
            }
            else {
                $status = array('msg'=>"No data to insert, please make sure there is no existing template name or item name in the database", 'type'=>'info');
                // throw new Exception("There is no data to insert, try again");
            }

        } catch(\Exception $e){
            DB::connection('dbintranet')->rollback();
            Log::error("MARKETLIST ERRROR INSERT | ". $e->getMessage());
            $status = array('msg'=>sprintf('Something went wrong, try again in a moment | %s', $e->getMessage()), 'type'=>'danger');
            return redirect()->route('marketlisttemplate.add')->with('message', $status);
        }

        return redirect()->route('marketlisttemplate.list')->with('message', $status);
    }

    public function list_template(Request $request){
        return view('pages.finance.purchase-requisition-marketlist.template.list');
    }

    function getData(Request $request){
        try{  
            $employee_id=Session::get('user_id', '');
            $assigned_template = DB::connection('dbintranet')
            ->table('dbo.INT_EMP_ML_TEMPLATE AS tmp')
            ->where(['tmp.EMPLOYEE_ID'=>$employee_id])
            ->join('VIEW_EMPLOYEE AS emp', 'tmp.EMPLOYEE_ID', '=', 'emp.EMPLOYEE_ID')
            ->select('tmp.EMPLOYEE_ID', 'emp.SAP_PLANT_ID', 'tmp.ML_TEMPLATE')
            ->get()->toArray();

            return DataTables::of($assigned_template)
            ->addColumn('NUM_ORDER', function ($json) {
                $this->iterable = $this->iterable + 1;
                return "<a class='text-primary' style='cursor:pointer'>".$this->iterable."</a>";
            })
            ->addColumn('ACTION', function ($json) {
                $plant = isset($json->SAP_PLANT_ID) ? $json->SAP_PLANT_ID : '';
                $ml_template = isset($json->ML_TEMPLATE) ? $json->ML_TEMPLATE : '';
                $action = '';
                // if(Session::get('permission_menu')->has("update_".route('marketlisttemplate.list', array(), false)))
                    $action .= '<a href="'.route('marketlisttemplate.update', [$plant, $ml_template]).'" class="btn pl-2 pr-2 btn-primary ml-1 mr-1 btn-edit"><i class="mdi mdi-pencil"></i></a>';

                // if(Session::get('permission_menu')->has("delete_".route('marketlisttemplate.list', array(), false)))
                //     $action .= '<a href="#" data-url-delete="'.route('marketlisttemplate.delete', $json['SEQ_ID']).'" class="btn pl-2 pr-2 btn-danger text-white ml-1 mr-1 btn-delete"><i class="mdi mdi-lock"></i></a>';

                if(!$action)
                    $action = '<small class="text-muted">NO ACCESS</small>';
                
                return $action;
            })
            ->rawColumns(['ACTION', 'NUM_ORDER'])
            ->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    function edit_template(Request $request, $plant=NULL, $template_name=NULL){
        $employee_id=Session::get('user_id', '');
        if($request->method() == 'GET'){
            $data = [];
            try {
                if($this->wantsJson($request)){
                    try {
                        if($request->get('type', false) && strtolower($request->get('type')) == 'duplicate-template'){
                            $template_post = $request->get('template_name', '');
                            $old_template_post = $request->get('old_template_name', '');
                            $plant_post = $request->get('plant', '');

                            if(!$template_post || !$old_template_post || !$plant_post)
                                throw new \Exception("Cannot read plant and template name while trying to check duplicate name");
                                
                            if(trim(strtoupper($template_post)) == trim(strtoupper($old_template_post))){
                                return response()->json(['status'=>'success', 'message'=>'Completed to check duplicate template', 'template_found'=>0], 200);
                            } else {
                                $validation_template_name = DB::connection('dbintranet')
                                ->table('INT_MASTER_ML_TEMPLATE')
                                ->where(['TEMPLATE'=>$template_post, 'PLANT'=>$plant_post])
                                ->get()->count();
                                return response()->json(['status'=>'success', 'message'=>'Completed to check duplicate template', 'template_found'=>$validation_template_name], 200);
                            }
                        }
                    } catch(\Exception $e){
                        $message = $e->getMessage();
                        Log::error($message);
                        return response()->json(['status'=>'failed', 'message'=>$message], 400);
                    }
                    return response()->json([], 400);
                }

                $data['material'] = DB::connection('dbintranet')
                ->table('INT_MASTER_ML_TEMPLATE')
                ->where(['PLANT'=>$plant, 'TEMPLATE'=>$template_name])
                ->get()->toArray();
                if(count($data['material']) == 0){
                    $status = array('msg'=>sprintf('No data found within Plant and Template name provided'), 'type'=>'danger');
                    return redirect()->route('marketlisttemplate.list')->with('message', $status);
                } else {
                    if(isset($request->session()->get('user_data')->IS_SUPERUSER) && $request->session()->get('user_data')->IS_SUPERUSER){
                        $plant_user = DB::connection('dbintranet')
                        ->table('dbo.INT_BUSINESS_PLANT')
                        ->where('PLANT_TYPE', 'HOTEL')
                        ->select('SAP_PLANT_ID', 'SAP_PLANT_NAME')
                        ->get()->pluck('SAP_PLANT_NAME', 'SAP_PLANT_ID')->filter()->toArray();
                    }
                    else {
                        $user_id = isset(session()->get('user_data')->EMPLOYEE_ID) ? session()->get('user_data')->EMPLOYEE_ID : '';
                        $plant_user = DB::connection('dbintranet')
                        ->table('dbo.VIEW_EMPLOYEE_ACCESS')
                        ->where('EMPLOYEE_ID', $user_id)
                        ->select('SAP_PLANT_ID', 'SAP_PLANT_NAME')
                        ->get()->pluck('SAP_PLANT_NAME', 'SAP_PLANT_ID')->filter()->toArray();
                    }
                    $data['plant_user'] = $plant_user;
                    $data['plant_selected'] = $plant;
                    $data['template_name'] = $template_name;
                    return view('pages.finance.purchase-requisition-marketlist.template.edit', ['data'=>$data]);
                }
            } catch(\Exception $e){
                Log::error("ERROR IN UPDATE TEMPLATE | ".$e->getMessage());
                $status = array('msg'=>sprintf('Something went wrong, try again'), 'type'=>'danger');
                return redirect()->route('marketlisttemplate.list')->with('message', $status);
            }
        } else if($request->method() == 'POST'){
            $data_to_update = [];
            $purchasing_group_insert = NULL;
            try {
                $item = $request->post('marketlistItemOrder', []);
                $plant = $request->post('plant', null);
                $template_name = $request->post('template_name', null);
                $old_template_name = $request->post('old_template_name', null);
                if(count($item)){
                    for($i=0;$i<count($item);$i++){
                        $sap_material_purch_group = isset($request->post('marketlistMaterialPurchGroup')[$i]['value']) ? $request->post('marketlistMaterialPurchGroup')[$i]['value'] : NULL;
                        if(!$sap_material_purch_group || $purchasing_group_insert && $purchasing_group_insert != $sap_material_purch_group)
                            throw new \Exception("Some of the materials have an empty or different purchasing group, please make sure all purchasing groups are the same");

                        $purchasing_group_insert = $sap_material_purch_group;
                        $data_to_update[] = [
                            'PLANT'=> $plant,
                            'TEMPLATE'=>$template_name,
                            'SAPMATERIALCODE'=>isset($request->post('marketlistMaterialNumber')[$i]['value']) ? $request->post('marketlistMaterialNumber')[$i]['value'] : null,
                            'MATERIALNAME'=>isset($request->post('marketlistMaterialName')[$i]['value']) ? $request->post('marketlistMaterialName')[$i]['value'] : null,
                            'UOM'=>isset($request->post('marketlistMaterialUnit')[$i]['value']) ? $request->post('marketlistMaterialUnit')[$i]['value'] : null,
                            'PURCH_GROUP'=>$sap_material_purch_group,
                            'STATUS'=>1
                        ];
                    }
                    if(count($data_to_update)){
                        $chunk_update = array_chunk($data_to_update, 100);
                        DB::connection('dbintranet')->beginTransaction();
                        DB::connection('dbintranet')
                        ->table('dbo.INT_MASTER_ML_TEMPLATE')
                        ->where(['PLANT'=>$plant, 'TEMPLATE'=>$old_template_name])
                        ->delete();

                        for($loop_insert=0;$loop_insert<count($chunk_update);$loop_insert++){
                            DB::connection('dbintranet')
                            ->table('dbo.INT_MASTER_ML_TEMPLATE')
                            ->insert($chunk_update[$loop_insert]);
                        }

                        DB::connection('dbintranet')
                        ->table('dbo.INT_EMP_ML_TEMPLATE')
                        ->where(['EMPLOYEE_ID'=>$employee_id, 'ML_TEMPLATE'=>$old_template_name])
                        ->update([
                            'ML_TEMPLATE'=>$template_name
                        ]);  

                        DB::connection('dbintranet')->commit();

                        $status = array('msg'=>"Marketlist Template has been successfully updated", 'success'=> true , 'code'=> 200);
                    } else {
                        $status = array('msg'=>"No data has been updated", 'success'=> false, 'code'=> 200);
                    }
                } else 
                    $status = array('msg'=>"No data has been updated, unknown data sent", 'success'=> true, 'code'=> 200);
            } catch(\Exception $e){
                Log::error($e->getMessage());
                DB::connection('dbintranet')->rollback();
                $status = array('msg'=>sprintf('Something went wrong, try again in a moment | %s', $e->getMessage()), 'success'=> false, 'code'=>401);
            }

            // return redirect()->route('marketlisttemplate.list')->with('message', $status);
            return response()->json($status, $status['code']);
        }
    }

    function print(Request $request){
        $marketlist_no = $request->get('marketlist_no');
        $data=[];
        $connection = DB::connection('dbintranet')
        ->table($this->form_view);
        for($i=0;$i<count($marketlist_no);$i++){
            $data['ITEM_DETAILS'][$marketlist_no[$i]] = $connection->where('UID',$marketlist_no[$i])
            ->get()->first();
            $data_json = isset($data['ITEM_DETAILS'][$marketlist_no[$i]]->JSON_ENCODE) ? json_decode($data['ITEM_DETAILS'][$marketlist_no[$i]]->JSON_ENCODE) : [];
            $data['ITEM_DETAILS'][$marketlist_no[$i]]->JSON_ENCODE = $data_json;
            $data['ITEM_DETAILS'][$marketlist_no[$i]]->COST_CENTER_NAME = '';
            $data['ITEM_DETAILS'][$marketlist_no[$i]]->DOC_TYPE_DESC = '';
            try {
                $cost_center = isset($data['ITEM_DETAILS'][$marketlist_no[$i]]->SHIP_TO_COST_CENTER) ? $data['ITEM_DETAILS'][$marketlist_no[$i]]->SHIP_TO_COST_CENTER : '';
                $custom_cost_center = DB::connection('dbintranet')
                ->table('INT_SAP_COST_CENTER')
                ->where('SAP_COST_CENTER_ID', $cost_center)
                ->select('SAP_COST_CENTER_DESCRIPTION')
                ->get()->first();
                $data['ITEM_DETAILS'][$marketlist_no[$i]]->COST_CENTER_NAME = isset($custom_cost_center->SAP_COST_CENTER_DESCRIPTION) ? $custom_cost_center->SAP_COST_CENTER_DESCRIPTION : '';
            } catch(\Exception $e){};

            try {
                $doc_type_desc = DB::connection('dbintranet')
                ->table('TBL_PR_DOCTYPE')
                ->where('PRDOCTYPE_CODE', $data['ITEM_DETAILS'][$marketlist_no[$i]]->JSON_ENCODE->doc_type)
                ->first()->PRDOCTYPE_DESCRIPTION;
                $data['ITEM_DETAILS'][$marketlist_no[$i]]->DOC_TYPE_DESC = $doc_type_desc;
            } catch(\Exception $e){}
        }
        // dd($data);
        $pdf = PDF::loadView('pages.finance.purchase-requisition-marketlist.print', ['data'=>$data]);
        return $pdf->stream(sprintf('Invoice-generate-%s.pdf', 'marketlist'), array("Attachment" => false));
    }
}