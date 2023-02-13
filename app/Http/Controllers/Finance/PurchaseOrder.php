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
use App\Models\HumanResource\CompanyModel;
use App\Models\Zoho\ZohoFormModel;
use Log;
use Cookie;
use DataTables;
use Carbon\Carbon;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;
use App\Models\HumanResource\BusinessPlantModel as PlantModel;
use App\Models\HumanResource\CostCenterModel as CostCenter;

class PurchaseOrder extends Controller{

    private $view_approval;
    public $data_po = [];

    public function __construct()
    {
        $this->view_approval="";
    }

    public function list(Request $request){
        //init RFC
        $data['po'] = [];
        try {
            $filter_created_from = null;
            $filter_created_to = null;

            if($request->get('created_date_from', '')){
                $filter_created_from = $request->get('created_date_from', '');
                try {
                    $filter_created_from =  date('Y-m-d',strtotime($filter_created_from));
                    $filter_created_from = Carbon::createFromFormat('!Y-m-d', $filter_created_from);
                } catch(\Exception $e) {}
            }
            if($request->get('created_date_to', '')){
                $filter_created_to = $request->get('created_date_to', '');
                try {
                    $filter_created_to =  date('Y-m-d',strtotime($filter_created_to));
                    $filter_created_to = Carbon::createFromFormat('!Y-m-d', $filter_created_to);
                } catch(\Exception $e) {}

            }

            try {
                $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
                $is_production = config('intranet.is_production');
                //===
                $show_approval_po = DB::connection('dbintranet')
                ->table('dbo.SAP_PO_RELEASE_GROUP_APPROVAL_MAPPING')
                ->select('RELEASE_GROUP_CODE', 'RELEASE_CODE')
                ->get();
                $po_number_collected = [];
                foreach($show_approval_po as $keydata => $value){
                    $rel_group = isset($value->RELEASE_GROUP_CODE) ? $value->RELEASE_GROUP_CODE : '';
                    $rel_code = isset($value->RELEASE_CODE) ? $value->RELEASE_CODE : '';
                    if($is_production){
                        $rfc = new SapConnection(config('intranet.rfc_prod'));
                    }else{
                        $rfc = new SapConnection(config('intranet.rfc'));
                    }

                    $options = [
                        'rtrim'=>true,
                    ];
                    // $param = array(
                    //     'REL_GROUP'=>$rel_group,
                    //     'REL_CODE'=>$rel_code,
                    //     'ITEMS_FOR_RELEASE'=>'X'
                    // );
                    // $function_type = $rfc->getFunction('BAPI_PO_GET_LIST');
                    // $po_list= $function_type->invoke($param, $options);

                    $cek_approver_po = DB::connection('dbintranet')
                    ->select(
                        "SELECT DISTINCT p.RELEASE_CODE,
                        ISNULL(s.COMPANY_CODE, NULL) AS COMPANY_CODE, t.RELEASE_GROUP_CODE
                        FROM dbo.SAP_PO_RELEASE_CODE_NEW p
                        LEFT JOIN dbo.INT_SAP_COST_CENTER q ON p.COSTCENTER = q.SAP_COST_CENTER_ID
                        LEFT JOIN dbo.INT_TERRITORY r ON q.TERRITORY_ID = r.TERRITORY_ID
                        LEFT JOIN dbo.INT_BUSINESS_PLANT s ON r.SAP_PLANT_ID = s.SAP_PLANT_ID
                        LEFT JOIN dbo.SAP_PO_RELEASE_GROUP_APPROVAL_MAPPING t ON s.COMPANY_CODE = t.RELEASE_CODE_COMPANY_CODE
                        AND p.RELEASE_CODE = t.RELEASE_CODE
                        WHERE t.RELEASE_GROUP_CODE = '$rel_group' AND t.RELEASE_CODE = '$rel_code' AND p.APP_EMPLOYEE_ID = '$employee_id'
                        OR
                        t.RELEASE_GROUP_CODE = '$rel_group' AND t.RELEASE_CODE = '$rel_code' AND p.ALT_EMPLOYEE_ID = '$employee_id'"
                    );

                    if(count($cek_approver_po)){
                        for($i=0;$i<count($cek_approver_po);$i++){
                            // if($rel_code == 'F0')
                            //     $rel_code = '';

                            //$param = array(
                            //    'P_COMPANY'=>$cek_approver_po[$i]->COMPANY_CODE,
                            //    'P_TRACKING_NO'=>$cek_approver_po[$i]->COSTCENTER,
                            //    'P_RELEASE_CODE'=>"$rel_code",
                            //    'P_RELEASE_INDICATOR'=>''
                            //);
                            //$function_type = $rfc->getFunction('ZFM_MM_PO_LIST');
                            //$po_data_no_mrp= $function_type->invoke($param, $options);
                            //if(isset($po_data_no_mrp['IT_PO']) && count($po_data_no_mrp))
                            //    $po_data_no_mrp = collect($po_data_no_mrp['IT_PO']);
                            //else 
                                $po_data_no_mrp = collect([]);

                            $param2 = array(
                                'P_COMPANY'=>$cek_approver_po[$i]->COMPANY_CODE,
                                // 'P_TRACKING_NO'=>$cek_approver_po[$i]->COSTCENTER,
                                'P_RELEASE_CODE'=>"$rel_code",
                                'P_RELEASE_INDICATOR'=>''
                            );
                            $function_type = $rfc->getFunction('ZFM_MM_PO_LIST');
                            $po_data_mrp= $function_type->invoke($param2, $options);
                            if(isset($po_data_mrp['IT_PO']) && count($po_data_mrp))
                                $po_data_mrp = collect($po_data_mrp['IT_PO']);
                            else 
                                $po_data_mrp = collect([]);

                            $po_data['IT_PO'] = $po_data_mrp->concat($po_data_no_mrp)->toArray();
                            if(isset($po_data['IT_PO']) && count($po_data['IT_PO'])){
                                $po_need_approve = collect($po_data['IT_PO'])->pluck('EBELN')->toArray();
                                for($j=0;$j<count($po_need_approve);$j++){
                                    $param = array(
                                        'PURCHASEORDER' =>isset($po_need_approve[$j]) ? $po_need_approve[$j] : ''
                                    );

                                    $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                                    $po_list = $function_type->invoke($param, $options);

                                    if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                                        $data_po_detail = $po_list['PO_ITEMS'];
                                        // $data_po = collect($po_list['PO_HEADERS'])->groupBy('PO_NUMBER')->mapWithKeys(function($item, $key) use (&$data, $rel_group, $rel_code, $employee_id, $rfc, $options, &$po_number_collected, $data_po_detail) {
                                                // $real_header = isset($item[0]) ? $item[0] : [];
                                                $real_header = isset($po_list['PO_HEADER']) ? $po_list['PO_HEADER'] : [];
                                                $key = isset($po_list['PO_HEADER']['PO_NUMBER']) ? $po_list['PO_HEADER']['PO_NUMBER'] : [];


                                                // $param = array(
                                                //     'PURCHASEORDER' =>isset($real_header['PO_NUMBER']) ? $real_header['PO_NUMBER'] : ''
                                                // );

                                                // $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                                                // $po_list = $function_type->invoke($param, $options);

                                                // if(isset($po_list['PO_ITEMS']) && count($po_list['PO_ITEMS'])){
                                                // $detail_po = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function ($value, $key) {
                                                //     $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                                                //     return !empty($delete_ind);
                                                // })->groupBy('PO_NUMBER')->toArray() : [];

                                                $detail_po = isset($data_po_detail) ? collect($data_po_detail)->reject(function ($value, $key) {
                                                    $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                                                    return !empty($delete_ind);
                                                })->groupBy('PO_NUMBER')->toArray() : [];

                                                $real_header['PO_DETAILS'] = isset($detail_po[$key]) ? $detail_po[$key] : [];
                                                // $real_header['TARGET_VAL'] = isset($detail_po[$key][0]['EFF_VALUE']) ? $detail_po[$key][0]['EFF_VALUE'] : 0;
                                                $real_header['TARGET_VAL'] = collect($detail_po[$key])->reduce(function($carry, $item){
                                                    $vat = isset($item['NOND_ITAX']) ? (float)$item['NOND_ITAX'] : 0;
                                                    if(!$vat > 0){
                                                        $vat = taxCodeCalculation( isset($item['TAX_CODE']) ? $item['TAX_CODE'] : '', isset($item['NET_VALUE']) ? $item['NET_VALUE'] : 0 );
                                                    }
                                                    $fix_value = isset($item['NET_VALUE']) ? ((float)$item['NET_VALUE']) + $vat : 0;
                                                    return $carry + $fix_value;
                                                },0);

                                                $real_header['RELEASE_VALUE'] = (isset($real_header['PO_NUMBER']) ? $real_header['PO_NUMBER'] : '0')."-".$rel_code."-".(isset($real_header['REL_GROUP']) ? $real_header['REL_GROUP'] : '0')."-".(isset($real_header['REL_STRAT']) ? $real_header['REL_STRAT'] : '0');
                                                $real_header['CREATED_ON'] = date('Y-m-d', strtotime($real_header['CREATED_ON']));
                                                $real_header['RELEASE_CODE'] = $rel_code;
                                                $real_header['COST_CENTER'] = isset($detail_po[$key][0]['TRACKINGNO']) ? $detail_po[$key][0]['TRACKINGNO'] : '';
                                                $real_header['COST_CENTER_DESC'] = '';
                                                // $real_header['REQ_NO'] = isset($detail_po[$key][0]['PREQ_NO']) ? $detail_po[$key][0]['PREQ_NO'] : '';
                                                $real_header['REQ_NO'] = collect($detail_po[$key])->filter(function($item, $key){
                                                    return !empty($item['PREQ_NO']);
                                                })->values()->all();
                                                $real_header['REQ_NO'] = isset($real_header['REQ_NO'][0]['PREQ_NO']) ? $real_header['REQ_NO'][0]['PREQ_NO'] : '';

                                                // Cek apakah PO datang dengan MIDJOB atau COSTCENTER tertentu
                                                $param = array(
                                                    'GV_NUMBER'=>$real_header['REQ_NO']
                                                );

                                                $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                                                $data_form= $function_type->invoke($param, $options);

                                                // if(isset($data_form['GI_ITEMS']) && count($data_form['GI_ITEMS']) <= 0)
                                                //     return [];

                                                // validasi jika Tracking Number Kosong,
                                                // maka akan mengambil cost center dari tabel custom
                                                // ini dilakukan biasanya untuk PO yang digenerate dari MRP

                                                if(empty($real_header['COST_CENTER'])){
                                                    //ambil nama PR dari requisitioner di SAP untuk query
                                                    $mrp_requisitioner=isset($data_form['GI_ITEMS'][0]['PREQ_NAME']) ? $data_form['GI_ITEMS'][0]['PREQ_NAME'] : NULL;

                                                    //query ke tabel custom
                                                    $cost_center_mrp=DB::connection('dbintranet')
                                                    ->select("SELECT COSTCENTER FROM SAP_PO_MRP_COSTCENTER WHERE MRP_USERNAME='$mrp_requisitioner'");

                                                    $cost_center_mrp=count($cost_center_mrp)? $cost_center_mrp[0]->COSTCENTER : NULL;
                                                    $real_header['COST_CENTER']=$cost_center_mrp;
                                                }

                                                $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                                                $real_header['REQUESTOR_ID'] = $PR_employee;

                                                // Mencari midjob dari requestor
                                                if(!empty($PR_employee)){
                                                    $requestor_midjob = DB::connection('dbintranet')
                                                    ->table('INT_EMPLOYEE_ASSIGNMENT')
                                                    ->where('EMPLOYEE_ID', $PR_employee)
                                                    ->select('MIDJOB_TITLE_ID')
                                                    ->get()->pluck('MIDJOB_TITLE_ID')->first();
                                                }
                                                else {
                                                    $requestor_midjob = null;
                                                }

                                                /* JIka midjob requestor tidak ketemu, maka ignore PO */
                                                // if(!$requestor_midjob)
                                                //     return [];
                                                $cek_approval = DB::connection('dbintranet')
                                                ->table('dbo.SAP_PO_RELEASE_CODE_NEW')
                                                // APPROVAL MENGGUNAKAN COST CENTER DAN MIDJOB
                                                ->where(['RELEASE_CODE'=>$real_header['RELEASE_CODE'], 'MIDJOB_TITLE'=>$requestor_midjob, 'APP_EMPLOYEE_ID'=>$employee_id, 'COSTCENTER'=>$real_header['COST_CENTER']])
                                                ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $rel_code){
                                                    $query->where(['RELEASE_CODE'=>$real_header['RELEASE_CODE'], 'MIDJOB_TITLE'=>$requestor_midjob, 'ALT_EMPLOYEE_ID'=>$employee_id, 'COSTCENTER'=>$real_header['COST_CENTER']]);
                                                })
                                                ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $rel_code){
                                                    $query->where(['RELEASE_CODE'=>$real_header['RELEASE_CODE'], 'APP_EMPLOYEE_ID'=>$employee_id, 'COSTCENTER'=>$real_header['COST_CENTER']])->whereNull('MIDJOB_TITLE');
                                                })
                                                ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $rel_code){
                                                    $query->where(['RELEASE_CODE'=>$real_header['RELEASE_CODE'], 'ALT_EMPLOYEE_ID'=>$employee_id, 'COSTCENTER'=>$real_header['COST_CENTER']])->whereNull('MIDJOB_TITLE');
                                                })
                                                ->get()->toArray();

                                                if(count($cek_approval)){
                                                    try {
                                                        // dd($real_header['REL_IND']);
                                                        $get_doc_type_desc = DB::connection('dbintranet')
                                                        ->table('INT_SAP_PO_DOCUMENT_TYPE')
                                                        ->where('PURC_DOC_TYPE', $real_header['DOC_TYPE'])
                                                        ->select('PURC_DOC_TYPE_DESC')
                                                        ->get()->pluck('PURC_DOC_TYPE_DESC')->first();
                                                        if($get_doc_type_desc)
                                                            $real_header['DOC_TYPE'] = $get_doc_type_desc;
                                                    } catch(\Exception $e){}

                                                    try {
                                                        // dd($real_header['REL_IND']);
                                                        $get_cost_center_name = DB::connection('dbintranet')
                                                        ->table('INT_SAP_COST_CENTER')
                                                        ->where('SAP_COST_CENTER_ID', $real_header['COST_CENTER'])
                                                        ->select('SAP_COST_CENTER_DESCRIPTION')
                                                        ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                                                        if($get_doc_type_desc)
                                                            $real_header['COST_CENTER_DESC'] = $get_cost_center_name;
                                                    } catch(\Exception $e){}
                                                    if(!in_array($key, $po_number_collected)){
                                                        array_push($data['po'], $real_header);
                                                        array_push($po_number_collected, $key);
                                                    }
                                                    else 
                                                        continue;
                                                }
                                                // }

                                        //     if(isset($real_header))
                                        //         return [$key=>$real_header];
                                        //     else
                                        //         return [$key=>$item];
                                        // })->toArray();
                                    } else
                                        continue;
                                }
                                // ENDFOR JOIN
                            } else
                                continue;
                        }
                        // ENDFOR CHECK PO PER COST CENTER
                    } else
                        continue;
                }

                if($filter_created_to && $filter_created_from){
                    $data['po'] = collect($data['po'])->filter(function($item, $key) use ($filter_created_to, $filter_created_from){
                        try{
                            $po_date = Carbon::createFromFormat('!Y-m-d', $item['CREATED_ON']);
                            return $po_date->gte($filter_created_from) && $po_date->lte($filter_created_to);
                        } catch(\Exception $e){
                            return $item;
                        }
                    })->toArray();
                }
                // return response()->json(['data'=>$data, 'message'=>'Success Retriving Data PO', 'type'=>'success'], 200);
            } catch(SAPFunctionException $e){
                $request->session()->flash('error_po_approval', 'Something went wrong when trying to get data from SAP, please try again later');
            } catch(\Exception $e){
                $request->session()->flash('error_po_approval', 'Something went wrong with the query, please try again later');
            }
        } catch(\Exception $e){
            $request->session()->flash('error_po_approval', 'Something went wrong, please try again later');
        }

        $data['created_date_from'] = $filter_created_from;
        $data['created_date_to'] = $filter_created_to;

        // Return normal page if not ajax call
        return view('pages.finance.purchase-order.approval', ['data' => $data]);
    }

    public function list_old_lambat(Request $request){
        //init RFC
        $data['po'] = [];
        try {
            $filter_created_from = null;
            $filter_created_to = null;

            if($request->get('created_date_from', '')){
                $filter_created_from = $request->get('created_date_from', '');
                try {
                    $filter_created_from =  date('Y-m-d',strtotime($filter_created_from));
                    $filter_created_from = Carbon::createFromFormat('!Y-m-d', $filter_created_from);
                } catch(\Exception $e) {}
            }
            if($request->get('created_date_to', '')){
                $filter_created_to = $request->get('created_date_to', '');
                try {
                    $filter_created_to =  date('Y-m-d',strtotime($filter_created_to));
                    $filter_created_to = Carbon::createFromFormat('!Y-m-d', $filter_created_to);
                } catch(\Exception $e) {}

            }

            try {
                $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
                $is_production = config('intranet.is_production');
                //===
                $show_approval_po = DB::connection('dbintranet')
                ->table('dbo.SAP_PO_RELEASE_GROUP_APPROVAL_MAPPING')
                ->select('RELEASE_GROUP_CODE', 'RELEASE_CODE')
                ->get();

                $relcode_relgroup_pair = [];
                foreach($show_approval_po as $keydata => $value){
                    $rel_group = isset($value->RELEASE_GROUP_CODE) ? $value->RELEASE_GROUP_CODE : '';
                    $rel_code = isset($value->RELEASE_CODE) ? $value->RELEASE_CODE : '';
                    if($is_production){
                        $rfc = new SapConnection(config('intranet.rfc_prod'));
                    }else{
                        $rfc = new SapConnection(config('intranet.rfc'));
                    }
                    $options = [
                        'rtrim'=>true,
                    ];
                    $param = array(
                        'REL_GROUP'=>$rel_group,
                        'REL_CODE'=>$rel_code,
                        'ITEMS_FOR_RELEASE'=>'X'
                    );
                    $function_type = $rfc->getFunction('BAPI_PO_GET_LIST');
                    $po_list= $function_type->invoke($param, $options);
                    // if($rel_group=="M0" && $rel_code=="F0"){
                    //     echo json_encode($po_list);
                    //     die;
                    // }
                    if(isset($po_list['PO_HEADERS']) && count($po_list['PO_HEADERS'])){
                        $data_po = collect($po_list['PO_HEADERS'])->groupBy('PO_NUMBER')->mapWithKeys(function($item, $key) use (&$data, $rel_group, $rel_code, $employee_id, $rfc, $options, &$relcode_relgroup_pair) {
                            // if(!in_array($rel_group.'-'.$rel_code, $relcode_relgroup_pair))
                            // {
                                $real_header = isset($item[0]) ? $item[0] : [];
                                $param = array(
                                    'PURCHASEORDER' =>isset($real_header['PO_NUMBER']) ? $real_header['PO_NUMBER'] : ''
                                );
                                $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                                $po_list = $function_type->invoke($param, $options);

                                if(isset($po_list['PO_ITEMS']) && count($po_list['PO_ITEMS'])){

                                    // $detail_po = isset($po_list['PO_ITEMS']) ? $po_list['PO_ITEMS'] : [];
                                    $detail_po = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function ($value, $key) {
                                        $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                                        return !empty($delete_ind);
                                    })->groupBy('PO_NUMBER')->toArray() : [];

                                    $real_header['PO_DETAILS'] = isset($detail_po[$key]) ? $detail_po[$key] : [];
                                    // $real_header['TARGET_VAL'] = isset($detail_po[$key][0]['EFF_VALUE']) ? $detail_po[$key][0]['EFF_VALUE'] : 0;
                                    $real_header['TARGET_VAL'] = collect($detail_po[$key])->reduce(function($carry, $item){
                                        $vat = isset($item['NOND_ITAX']) ? (float)$item['NOND_ITAX'] : 0;
                                        if(!$vat > 0){
                                            $vat = taxCodeCalculation( isset($item['TAX_CODE']) ? $item['TAX_CODE'] : '', isset($item['NET_VALUE']) ? $item['NET_VALUE'] : 0 );
                                        }
                                        $fix_value = isset($item['NET_VALUE']) ? ((float)$item['NET_VALUE']) + $vat : 0;
                                        return $carry + $fix_value;
                                    },0);

                                    $real_header['RELEASE_VALUE'] = (isset($real_header['PO_NUMBER']) ? $real_header['PO_NUMBER'] : '0')."-".$rel_code."-".(isset($real_header['REL_GROUP']) ? $real_header['REL_GROUP'] : '0')."-".(isset($real_header['REL_STRAT']) ? $real_header['REL_STRAT'] : '0');
                                    $real_header['CREATED_ON'] = date('Y-m-d', strtotime($real_header['CREATED_ON']));
                                    $real_header['RELEASE_CODE'] = $rel_code;
                                    $real_header['COST_CENTER'] = isset($detail_po[$key][0]['TRACKINGNO']) ? $detail_po[$key][0]['TRACKINGNO'] : '';
                                    $real_header['COST_CENTER_DESC'] = '';
                                    // $real_header['REQ_NO'] = isset($detail_po[$key][0]['PREQ_NO']) ? $detail_po[$key][0]['PREQ_NO'] : '';
                                    $real_header['REQ_NO'] = collect($detail_po[$key])->filter(function($item, $key){
                                        return !empty($item['PREQ_NO']);
                                    })->values()->all();
                                    $real_header['REQ_NO'] = isset($real_header['REQ_NO'][0]['PREQ_NO']) ? $real_header['REQ_NO'][0]['PREQ_NO'] : '';

                                    // Cek apakah PO datang dengan MIDJOB atau COSTCENTER tertentu
                                    $param = array(
                                        'GV_NUMBER'=>$real_header['REQ_NO']
                                    );

                                    $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                                    $data_form= $function_type->invoke($param, $options);
                                    if(isset($data_form['GI_ITEMS']) && count($data_form['GI_ITEMS']) <= 0)
                                        return [];

                                    // validasi jika Tracking Number Kosong,
                                    // maka akan mengambil cost center dari tabel custom
                                    // ini dilakukan biasanya untuk PO yang digenerate dari MRP

                                    if(empty($real_header['COST_CENTER'])){
                                        //ambil nama PR dari requisitioner di SAP untuk query
                                        $mrp_requisitioner=isset($data_form['GI_ITEMS'][0]['PREQ_NAME']) ? $data_form['GI_ITEMS'][0]['PREQ_NAME'] : NULL;

                                        //query ke tabel custom
                                        $cost_center_mrp=DB::connection('dbintranet')
                                        ->select("SELECT COSTCENTER FROM SAP_PO_MRP_COSTCENTER WHERE MRP_USERNAME='$mrp_requisitioner'");

                                        $cost_center_mrp=count($cost_center_mrp)? $cost_center_mrp[0]->COSTCENTER : NULL;
                                        $real_header['COST_CENTER']=$cost_center_mrp;
                                    }

                                    $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                                    $real_header['REQUESTOR_ID'] = $PR_employee;

                                    // Mencari midjob dari requestor
                                    if(!empty($PR_employee)){
                                        $requestor_midjob = DB::connection('dbintranet')
                                        ->table('INT_EMPLOYEE_ASSIGNMENT')
                                        ->where('EMPLOYEE_ID', $PR_employee)
                                        ->select('MIDJOB_TITLE_ID')
                                        ->get()->pluck('MIDJOB_TITLE_ID')->first();
                                    }
                                    else {
                                        $requestor_midjob = null;
                                    }

                                    /* JIka midjob requestor tidak ketemu, maka ignore PO */
                                    // if(!$requestor_midjob)
                                    //     return [];
                                    $cek_approval = DB::connection('dbintranet')
                                    ->table('dbo.SAP_PO_RELEASE_CODE_NEW')

                                    /* APPROVAL MENGGUNAKAN COST CENTER DAN MIDJOB */
                                    ->where(['RELEASE_CODE'=>$rel_code, 'COSTCENTER'=>$real_header['COST_CENTER'], 'MIDJOB_TITLE'=>$requestor_midjob, 'APP_EMPLOYEE_ID'=>$employee_id])
                                    ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $rel_code){
                                        $query->where(['RELEASE_CODE'=>$rel_code, 'COSTCENTER'=>$real_header['COST_CENTER'], 'MIDJOB_TITLE'=>$requestor_midjob, 'ALT_EMPLOYEE_ID'=>$employee_id]);
                                    })
                                    ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $rel_code){
                                        $query->where(['RELEASE_CODE'=>$rel_code, 'COSTCENTER'=>$real_header['COST_CENTER'], 'APP_EMPLOYEE_ID'=>$employee_id])
                                        ->whereNull('MIDJOB_TITLE');
                                    })
                                    ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $rel_code){
                                        $query->where(['RELEASE_CODE'=>$rel_code, 'COSTCENTER'=>$real_header['COST_CENTER'], 'ALT_EMPLOYEE_ID'=>$employee_id])
                                        ->whereNull('MIDJOB_TITLE');
                                    })
                                    ->get()->count();

                                    if($cek_approval){
                                        try {
                                            // dd($real_header['REL_IND']);
                                            $get_doc_type_desc = DB::connection('dbintranet')
                                            ->table('INT_SAP_PO_DOCUMENT_TYPE')
                                            ->where('PURC_DOC_TYPE', $real_header['DOC_TYPE'])
                                            ->select('PURC_DOC_TYPE_DESC')
                                            ->get()->pluck('PURC_DOC_TYPE_DESC')->first();
                                            if($get_doc_type_desc)
                                                $real_header['DOC_TYPE'] = $get_doc_type_desc;
                                        } catch(\Exception $e){}

                                        try {
                                            // dd($real_header['REL_IND']);
                                            $get_cost_center_name = DB::connection('dbintranet')
                                            ->table('INT_SAP_COST_CENTER')
                                            ->where('SAP_COST_CENTER_ID', $real_header['COST_CENTER'])
                                            ->select('SAP_COST_CENTER_DESCRIPTION')
                                            ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                                            if($get_doc_type_desc)
                                                $real_header['COST_CENTER_DESC'] = $get_cost_center_name;
                                        } catch(\Exception $e){}
                                        array_push($data['po'], $real_header);
                                    }
                                }
                                // array_push($relcode_relgroup_pair, $rel_group.'-'.$rel_code);
                            // }
                            if(isset($real_header))
                                return [$key=>$real_header];
                            else
                                return [$key=>$item];
                        })->toArray();
                    }
                }

                if($filter_created_to && $filter_created_from){
                    $data['po'] = collect($data['po'])->filter(function($item, $key) use ($filter_created_to, $filter_created_from){
                        try{
                            $po_date = Carbon::createFromFormat('!Y-m-d', $item['CREATED_ON']);
                            return $po_date->gte($filter_created_from) && $po_date->lte($filter_created_to);
                        } catch(\Exception $e){
                            return $item;
                        }
                    })->toArray();
                }
                // return response()->json(['data'=>$data, 'message'=>'Success Retriving Data PO', 'type'=>'success'], 200);
            } catch(SAPFunctionException $e){
                $request->session()->flash('error_po_approval', 'Something went wrong when trying to get data from SAP, please try again later');
            } catch(\Exception $e){
                // dd($e);
                $request->session()->flash('error_po_approval', 'Something went wrong with the query, please try again later');
            }
        } catch(\Exception $e){
            $request->session()->flash('error_po_approval', 'Something went wrong, please try again later');
        }

        $data['created_date_from'] = $filter_created_from;
        $data['created_date_to'] = $filter_created_to;

        // Return normal page if not ajax call
        return view('pages.finance.purchase-order.approval', ['data' => $data]);
    }

    public function list_new_method(Request $request){
        //init RFC
        $data['po'] = [];
        $po_number = [];
        try {
            $filter_created_from = null;
            $filter_created_to = null;

            if($request->get('created_date_from', '')){
                $filter_created_from = $request->get('created_date_from', '');
                try {
                    $filter_created_from =  date('Y-m-d',strtotime($filter_created_from));
                    $filter_created_from = Carbon::createFromFormat('!Y-m-d', $filter_created_from);
                } catch(\Exception $e) {}
            }
            if($request->get('created_date_to', '')){
                $filter_created_to = $request->get('created_date_to', '');
                try {
                    $filter_created_to =  date('Y-m-d',strtotime($filter_created_to));
                    $filter_created_to = Carbon::createFromFormat('!Y-m-d', $filter_created_to);
                } catch(\Exception $e) {}

            }

            try {
                $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
                $is_production = config('intranet.is_production');
                //===
                // $show_approval_po = DB::connection('dbintranet')
                // ->table('dbo.SAP_PO_RELEASE_GROUP_APPROVAL_MAPPING')
                // ->select('RELEASE_GROUP_CODE', 'RELEASE_CODE')
                // ->get();

                $cek_approver_po = DB::connection('dbintranet')
                ->select(
                    "SELECT DISTINCT p.RELEASE_CODE, p.COSTCENTER,
                    ISNULL(s.COMPANY_CODE, NULL) AS COMPANY_CODE, t.RELEASE_GROUP_CODE
                    FROM dbo.SAP_PO_RELEASE_CODE_NEW p
                    LEFT JOIN dbo.INT_SAP_COST_CENTER q ON p.COSTCENTER = q.SAP_COST_CENTER_ID
                    LEFT JOIN dbo.INT_TERRITORY r ON q.TERRITORY_ID = r.TERRITORY_ID
                    LEFT JOIN dbo.INT_BUSINESS_PLANT s ON r.SAP_PLANT_ID = s.SAP_PLANT_ID
                    LEFT JOIN dbo.SAP_PO_RELEASE_GROUP_APPROVAL_MAPPING t ON s.COMPANY_CODE = t.RELEASE_CODE_COMPANY_CODE
                    AND p.RELEASE_CODE = t.RELEASE_CODE
                    WHERE p.APP_EMPLOYEE_ID = '$employee_id' OR p.ALT_EMPLOYEE_ID = '$employee_id'"
                );

                foreach($cek_approver_po as $keydata => $value){
                    // $rel_group = isset($value->RELEASE_GROUP_CODE) ? $value->RELEASE_GROUP_CODE : '';
                    // $rel_code = isset($value->RELEASE_CODE) ? $value->RELEASE_CODE : '';

                    if($is_production){
                        $rfc = new SapConnection(config('intranet.rfc_prod'));
                    }else{
                        $rfc = new SapConnection(config('intranet.rfc'));
                    }

                    $options = [
                        'rtrim'=>true,
                    ];

                    $param = array(
                        'P_COMPANY'=>$value->COMPANY_CODE,
                        'P_TRACKING_NO'=>$value->COSTCENTER,
                        'P_RELEASE_INDICATOR'=>''
                    );
                    $function_type = $rfc->getFunction('ZFM_MM_PO_LIST');
                    $po_list= $function_type->invoke($param, $options);

                    if(isset($po_list['IT_PO']) && count($po_list['IT_PO'])){
                        $chunk_po = array_chunk($po_list['IT_PO'], 10);
                        // // RELEASE_INDICACTOR
                        for($po=0;$po<count($chunk_po);$po++){
                            $po_number_collect = $chunk_po[$po];
                            for($count=0;$count<count($po_number_collect);$count++){
                                $release_ind = isset($po_number_collect[$count]['RELEASE_INDICACTOR']) ? $po_number_collect[$count]['RELEASE_INDICACTOR'] : null;
                                if($release_ind != null && strtoupper($release_ind) != 'R'){
                                    $param = array(
                                        'PURCHASEORDER'=> isset($po_number_collect[$count]['EBELN']) ? $po_number_collect[$count]['EBELN'] : ''
                                    );
                                    $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
                                    $last_approve_status = $function_type->invoke($param, $options);

                                    // Cek semua release yg sudah approve / posted
                                    $prior_release_approve = array_values(collect(isset($last_approve_status['RELEASE_ALREADY_POSTED']) ? $last_approve_status['RELEASE_ALREADY_POSTED'] : [])->filter(function($item, $key){
                                        // take only description of release strategy code
                                        return substr($key, 0, 8) == 'REL_CODE';
                                    })->filter()->toArray());

                                    $release_code = array_values(collect(isset($last_approve_status['RELEASE_FINAL'][0]) ? $last_approve_status['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                                        // take only description of release strategy code
                                        return substr($key, 0, 8) == 'REL_CODE';
                                    })->filter()->toArray());

                                    $now_release_approve = '';
                                    foreach ($release_code as $key => $value) {
                                        if (!in_array($value, $prior_release_approve)) {
                                            $now_release_approve = $value;
                                            break;
                                        }
                                    }
                                    if(!empty($now_release_approve)) {
                                        $param = array(
                                            'PURCHASEORDER'=> isset($po_number_collect[$count]['EBELN']) ? $po_number_collect[$count]['EBELN'] : ''
                                        );
                                        $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                                        $po_list = $function_type->invoke($param, $options);
                                        if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER']))
                                        {

                                            $real_header = isset($po_list['PO_HEADER']) ? $po_list['PO_HEADER'] : [];
                                            $detail_po = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function ($value, $key) {
                                                $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                                                return !empty($delete_ind);
                                            })->toArray() : [];

                                            $real_header['PO_DETAILS'] = isset($detail_po) ? $detail_po : [];
                                            // $real_header['TARGET_VAL'] = isset($detail_po[0]['EFF_VALUE']) ? $detail_po[0]['EFF_VALUE'] : 0;
                                            $real_header['TARGET_VAL'] = collect($detail_po)->reduce(function($carry, $item){
                                                $vat = isset($item['NOND_ITAX']) ? (float)$item['NOND_ITAX'] : 0;
                                                if(!$vat > 0){
                                                    $vat = taxCodeCalculation( isset($item['TAX_CODE']) ? $item['TAX_CODE'] : '', isset($item['NET_VALUE']) ? $item['NET_VALUE'] : 0 );
                                                }
                                                $fix_value = isset($item['NET_VALUE']) ? ((float)$item['NET_VALUE']) + $vat : 0;
                                                return $carry + $fix_value;
                                            },0);

                                            $real_header['RELEASE_VALUE'] = (isset($real_header['PO_NUMBER']) ? $real_header['PO_NUMBER'] : '0')."-".$now_release_approve."-".(isset($real_header['REL_GROUP']) ? $real_header['REL_GROUP'] : '0')."-".(isset($real_header['REL_STRAT']) ? $real_header['REL_STRAT'] : '0');
                                            $real_header['CREATED_ON'] = date('Y-m-d', strtotime($real_header['CREATED_ON']));
                                            $real_header['RELEASE_CODE'] = $now_release_approve;
                                            $real_header['COST_CENTER'] = isset($detail_po[0]['TRACKINGNO']) ? $detail_po[0]['TRACKINGNO'] : '';
                                            $real_header['COST_CENTER_DESC'] = '';
                                            // $real_header['REQ_NO'] = isset($detail_po[0]['PREQ_NO']) ? $detail_po[0]['PREQ_NO'] : '';
                                            $real_header['REQ_NO'] = collect($detail_po)->filter(function($item, $key){
                                                return !empty($item['PREQ_NO']);
                                            })->values()->all();
                                            $real_header['REQ_NO'] = isset($real_header['REQ_NO'][0]['PREQ_NO']) ? $real_header['REQ_NO'][0]['PREQ_NO'] : '';

                                            // Cek apakah PO datang dengan MIDJOB atau COSTCENTER tertentu
                                            $param = array(
                                                'GV_NUMBER'=>$real_header['REQ_NO']
                                            );

                                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                                            $data_form= $function_type->invoke($param, $options);
                                            // if(isset($data_form['GI_ITEMS']) && count($data_form['GI_ITEMS']) <= 0)
                                            //     return [];

                                            // validasi jika Tracking Number Kosong,
                                            // maka akan mengambil cost center dari tabel custom
                                            // ini dilakukan biasanya untuk PO yang digenerate dari MRP

                                            if(empty($real_header['COST_CENTER'])){
                                                //ambil nama PR dari requisitioner di SAP untuk query
                                                $mrp_requisitioner=isset($data_form['GI_ITEMS'][0]['PREQ_NAME']) ? $data_form['GI_ITEMS'][0]['PREQ_NAME'] : NULL;

                                                //query ke tabel custom
                                                $cost_center_mrp=DB::connection('dbintranet')
                                                ->select("SELECT COSTCENTER FROM SAP_PO_MRP_COSTCENTER WHERE MRP_USERNAME='$mrp_requisitioner'");

                                                $cost_center_mrp=count($cost_center_mrp)? $cost_center_mrp[0]->COSTCENTER : NULL;
                                                $real_header['COST_CENTER']=$cost_center_mrp;
                                            }

                                            $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                                            $real_header['REQUESTOR_ID'] = $PR_employee;

                                            // Mencari midjob dari requestor
                                            if(!empty($PR_employee)){
                                                $requestor_midjob = DB::connection('dbintranet')
                                                ->table('INT_EMPLOYEE_ASSIGNMENT')
                                                ->where('EMPLOYEE_ID', $PR_employee)
                                                ->select('MIDJOB_TITLE_ID')
                                                ->get()->pluck('MIDJOB_TITLE_ID')->first();
                                            }
                                            else {
                                                $requestor_midjob = null;
                                            }
                                            /* JIka midjob requestor tidak ketemu, maka ignore PO */
                                            // if(!$requestor_midjob)
                                            //     return [];
                                            $cek_approval = DB::connection('dbintranet')
                                            ->table('dbo.SAP_PO_RELEASE_CODE_NEW')
                                            /* APPROVAL MENGGUNAKAN COST CENTER DAN MIDJOB */
                                            ->where(['RELEASE_CODE'=>$now_release_approve, 'COSTCENTER'=>$real_header['COST_CENTER'], 'MIDJOB_TITLE'=>$requestor_midjob, 'APP_EMPLOYEE_ID'=>$employee_id])
                                            ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $now_release_approve){
                                                $query->where(['RELEASE_CODE'=>$now_release_approve, 'COSTCENTER'=>$real_header['COST_CENTER'], 'MIDJOB_TITLE'=>$requestor_midjob, 'ALT_EMPLOYEE_ID'=>$employee_id]);
                                            })
                                            ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $now_release_approve){
                                                $query->where(['RELEASE_CODE'=>$now_release_approve, 'COSTCENTER'=>$real_header['COST_CENTER'], 'APP_EMPLOYEE_ID'=>$employee_id])
                                                ->whereNull('MIDJOB_TITLE');
                                            })
                                            ->orWhere(function($query) use ($real_header, $employee_id, $requestor_midjob, $now_release_approve){
                                                $query->where(['RELEASE_CODE'=>$now_release_approve, 'COSTCENTER'=>$real_header['COST_CENTER'], 'ALT_EMPLOYEE_ID'=>$employee_id])
                                                ->whereNull('MIDJOB_TITLE');
                                            })
                                            ->get()->count();

                                            if($cek_approval){
                                                try {
                                                    // dd($real_header['REL_IND']);
                                                    $get_doc_type_desc = DB::connection('dbintranet')
                                                    ->table('INT_SAP_PO_DOCUMENT_TYPE')
                                                    ->where('PURC_DOC_TYPE', $real_header['DOC_TYPE'])
                                                    ->select('PURC_DOC_TYPE_DESC')
                                                    ->get()->pluck('PURC_DOC_TYPE_DESC')->first();
                                                    if($get_doc_type_desc)
                                                        $real_header['DOC_TYPE'] = $get_doc_type_desc;
                                                } catch(\Exception $e){}

                                                try {
                                                    // dd($real_header['REL_IND']);
                                                    $get_cost_center_name = DB::connection('dbintranet')
                                                    ->table('INT_SAP_COST_CENTER')
                                                    ->where('SAP_COST_CENTER_ID', $real_header['COST_CENTER'])
                                                    ->select('SAP_COST_CENTER_DESCRIPTION')
                                                    ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                                                    if($get_doc_type_desc)
                                                        $real_header['COST_CENTER_DESC'] = $get_cost_center_name;
                                                } catch(\Exception $e){}
                                                array_push($data['po'], $real_header);
                                            }
                                        }
                                    }
                                    else
                                        continue;
                                } else
                                    continue;
                            }
                        }
                    } else
                        continue;
                }

                if($filter_created_to && $filter_created_from){
                    $data['po'] = collect($data['po'])->filter(function($item, $key) use ($filter_created_to, $filter_created_from){
                        try{
                            $po_date = Carbon::createFromFormat('!Y-m-d', $item['CREATED_ON']);
                            return $po_date->gte($filter_created_from) && $po_date->lte($filter_created_to);
                        } catch(\Exception $e){
                            return $item;
                        }
                    })->toArray();
                }
                // return response()->json(['data'=>$data, 'message'=>'Success Retriving Data PO', 'type'=>'success'], 200);
            } catch(SAPFunctionException $e){
                dd($e);
                $request->session()->flash('error_po_approval', 'Something went wrong when trying to get data from SAP, please try again later');
            } catch(\Exception $e){
                dd($e);
                $request->session()->flash('error_po_approval', 'Something went wrong with the query, please try again later');
            }
        } catch(\Exception $e){
            dd($e);
            $request->session()->flash('error_po_approval', 'Something went wrong, please try again later');
        }

        $data['created_date_from'] = $filter_created_from;
        $data['created_date_to'] = $filter_created_to;

        // Return normal page if not ajax call
        return view('pages.finance.purchase-order.approval', ['data' => $data]);
    }

    // Hanya menampilkan PO yg pernah di approve
    public function report(Request $request){
        //init RFC
        $data['po'] = [];
        try {
            if($request->get('created_date_from', ''))
                $data['created_date_from'] = date('m/d/Y', strtotime($request->get('created_date_from', '')));
            if($request->get('created_date_to', ''))
                $data['created_date_to'] = date('m/d/Y', strtotime($request->get('created_date_to', '')));
        } catch(\Exception $e){}

        try {
            if($request->ajax()){
                $filter_created_from = null;
                $filter_created_to = null;

                if($request->get('created_date_from', '')){
                    $filter_created_from = $request->get('created_date_from', '');
                    try {
                        $filter_created_from =  date('Y-m-d',strtotime($filter_created_from));
                        $filter_created_from = Carbon::createFromFormat('!Y-m-d', $filter_created_from);
                    } catch(\Exception $e) {}
                }
                if($request->get('created_date_to', '')){
                    $filter_created_to = $request->get('created_date_to', '');
                    try {
                        $filter_created_to =  date('Y-m-d',strtotime($filter_created_to));
                        $filter_created_to = Carbon::createFromFormat('!Y-m-d', $filter_created_to);
                    } catch(\Exception $e) {}

                }

                try {
                    $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
                    $is_production = config('intranet.is_production');
                    if($is_production){
                        $rfc = new SapConnection(config('intranet.rfc_prod'));
                    }else{
                        $rfc = new SapConnection(config('intranet.rfc'));
                    }
                    $options = [
                        'rtrim'=>true,
                    ];

                    // Cek data PO yg pernah di approve di Intranet
                    $cek_release = DB::connection('dbintranet')
                    ->table('dbo.SAP_PO_RELEASE')
                    ->where('RELEASE_CODE_NAME_ID', $employee_id)
                    ->select('PO_NUMBER')
                    ->distinct()
                    ->get()->pluck('PO_NUMBER')->toArray();



                    foreach($cek_release as $key => $value){
                        $param = array(
                            'PURCHASEORDER' =>$value
                        );
                        $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                        $po_list = $function_type->invoke($param, $options);
                        if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                            $detail_po = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function ($value, $key) {
                                $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                                return !empty($delete_ind);
                            })->toArray() : [];
                            $po_list['PO_HEADER']['PO_DETAILS'] = $detail_po;
                            // $po_list['PO_HEADER']['TARGET_VAL'] = isset($detail_po[0]['EFF_VALUE']) ? $detail_po[0]['EFF_VALUE'] : 0;
                            $po_list['PO_HEADER']['TARGET_VAL'] = collect($detail_po)->reduce(function($carry, $item){
                                $vat = isset($item['NOND_ITAX']) ? (float)$item['NOND_ITAX'] : 0;
                                if(!$vat > 0){
                                    $vat = taxCodeCalculation( isset($item['TAX_CODE']) ? $item['TAX_CODE'] : '', isset($item['NET_VALUE']) ? $item['NET_VALUE'] : 0 );
                                }
                                $fix_value = isset($item['NET_VALUE']) ? ((float)$item['NET_VALUE']) + $vat : 0;
                                return $carry + $fix_value;
                            },0);
                            $po_list['PO_HEADER']['CREATED_ON'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['CREATED_ON']));
                            $po_list['PO_HEADER']['COST_CENTER'] = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                            $po_list['PO_HEADER']['COST_CENTER_DESC'] = '';
                            // $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_ITEMS'][0]['PREQ_NO']) ? $po_list['PO_ITEMS'][0]['PREQ_NO'] : '';
                            $po_list['PO_HEADER']['REQ_NO'] = collect($detail_po)->filter(function($item, $key){
                                return !empty($item['PREQ_NO']);
                            })->values()->all();
                            $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_HEADER']['REQ_NO'][0]['PREQ_NO']) ? $po_list['PO_HEADER']['REQ_NO'][0]['PREQ_NO'] : '';
                            $po_list['PO_HEADER']['PR_REQ_NAME']='-';

                            //cari detail PR
                            $param = array(
                                'GV_NUMBER'=>$po_list['PO_HEADER']['REQ_NO']
                            );
                            $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                            $data_form= $function_type->invoke($param, $options);
                            if(isset($data_form['GI_ITEMS'][0]['PREQ_ID'])){
                                $data_pr_requester=DB::connection('dbintranet')
                                ->select("SELECT EMPLOYEE_NAME FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID='".$data_form['GI_ITEMS'][0]['PREQ_ID']."'");
                                if(isset($data_pr_requester[0])){
                                    $po_list['PO_HEADER']['PR_REQ_NAME']=$data_pr_requester[0]->EMPLOYEE_NAME;
                                }
                            }


                            $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';

                            try {
                                // dd($real_header['REL_IND']);
                                $get_doc_type_desc = DB::connection('dbintranet')
                                ->table('INT_SAP_PO_DOCUMENT_TYPE')
                                ->where('PURC_DOC_TYPE', $po_list['PO_HEADER']['DOC_TYPE'])
                                ->select('PURC_DOC_TYPE_DESC')
                                ->get()->pluck('PURC_DOC_TYPE_DESC')->first();
                                if($get_doc_type_desc)
                                    $po_list['PO_HEADER']['DOC_TYPE'] = $get_doc_type_desc;
                            } catch(\Exception $e){}

                            try {
                                // dd($real_header['REL_IND']);
                                $cost_center_requestor = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                                $get_cost_center_name = DB::connection('dbintranet')
                                ->table('INT_SAP_COST_CENTER')
                                ->where('SAP_COST_CENTER_ID', $cost_center_requestor)
                                ->select('SAP_COST_CENTER_DESCRIPTION')
                                ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                                if($get_doc_type_desc)
                                    $po_list['PO_HEADER']['COST_CENTER_DESC'] = $get_cost_center_name;
                            } catch(\Exception $e){}
                            // push data po ke penampungan array
                            array_push($data['po'], $po_list['PO_HEADER']);
                        }
                    }

                    if($filter_created_to && $filter_created_from){
                        $data['po'] = collect($data['po'])->filter(function($item, $key) use ($filter_created_to, $filter_created_from){
                            try{
                                $po_date = Carbon::createFromFormat('!Y-m-d', $item['CREATED_ON']);
                                return $po_date->gte($filter_created_from) && $po_date->lte($filter_created_to);
                            } catch(\Exception $e){
                                return $item;
                            }
                        })->toArray();
                    }
                    return DataTables::of($data['po'])->make(true);
                } catch(SAPFunctionException $e){
                    return response()->json(['data'=>$data, 'message'=>$e->getMessage(), 'type'=>'error'], 200);
                } catch(\Exception $e){
                    return response()->json(['data'=>$data, 'message'=>$e->getMessage(), 'type'=>'error'], 200);
                }
            }
        } catch(\Exception $e){}

        // Return normal page if not ajax call
        return view('pages.finance.purchase-order.report', ['data' => $data]);
    }

    public function submitApprovalPO(Request $request)
    {
        $approval_id=imuneString($request->input('approval_id'));
        $data_approval = explode(";" , $approval_id);
        $status_approval=imuneString($request->input('status_approval'));
        $reason_approval=imuneString($request->input('reason'));

        $totalData=0;
        $success=0;
        $failed=0;

        foreach ($data_approval as $key => $approval) {
            $result = $this->approve_batch($approval, $status_approval, $reason_approval);
            if ($result['code']==200){
                $success++;
            }else{
                $failed++;
            }
            $totalData++;
        }

        $hasil["Total_Data"]=$totalData;
        $hasil["Total_Success"]=$success;
        $hasil["Total_Failed"]=$failed;
        return $hasil;
    }

    public function approve_batch($approval_id, $status_approval, $reason)
    {

        //init RFC
        $is_production = config('intranet.is_production');
        if($is_production){
            $rfc = new SapConnection(config('intranet.rfc_prod'));
        }else{
            $rfc = new SapConnection(config('intranet.rfc'));
        }
        $options = [
            'rtrim'=>true
        ];
        //===

        try {
            $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
            $connection = DB::connection('dbintranet');
            $connection->beginTransaction();

            // Define order of data sent by ajax
            $raw_data = explode('-', $approval_id);
            $po_number=isset($raw_data[0]) ? $raw_data[0] : '';
            $release_code=isset($raw_data[1]) ? $raw_data[1] : '';
            $release_group=isset($raw_data[2]) ? $raw_data[2] : '';
            $release_strategy=isset($raw_data[3]) ? $raw_data[3] : '';
            $reason = $reason;

            if($status_approval=="APPROVED"){
                $param = array(
                    'PURCHASEORDER'=>$po_number,
                    'PO_REL_CODE'=>$release_code,
                    'USE_EXCEPTIONS'=>'X'
                );

                $function_type = $rfc->getFunction('BAPI_PO_RELEASE');
                $approve= $function_type->invoke($param, $options);
                $new_indicator=isset($approve['REL_INDICATOR_NEW']) ? $approve['REL_INDICATOR_NEW'] : '';
                $new_status=isset($approve['REL_STATUS_NEW']) ? $approve['REL_STATUS_NEW'] : '';
                // Ini gatau buat apa, tapi disimpen aja dulu karena return dari SAP kaya gni
                $ret_code=isset($approve['RET_CODE']) ? $approve['RET_CODE'] : '';

                if(!empty($new_status)) {// jika GV_REL_STATUS_NEW = X maka artinya approve success

                    //cek apakah dia sudah finish atau masih lanjut
                    if($new_indicator == 'R'){
                        $last_approve_status="FINISHED";
                    }else{
                        $last_approve_status="APPROVED";
                    }

                    try {
                        $data_to_insert = [
                            "RELEASE_CODE"=>$release_code,
                            "RELEASE_CODE_NAME_ID"=>$employee_id,
                            "STATUS"=>$last_approve_status,
                            "RELEASE_DATE"=>date('Y-m-d H:i:s'),
                            "PO_NUMBER"=>$po_number,
                            "REASON"=>$reason,
                            "RELEASE_GROUP_CODE"=>$release_group,
                        ];
                        DB::connection('dbintranet')
                        ->table('SAP_PO_RELEASE')
                        ->insert($data_to_insert);
                    } catch(\Exception $e){
                        // Batal approve di SAP ketika error DB warehouse
                        try {
                            $param = array(
                                'PURCHASEORDER'=>$po_number,
                                'PO_REL_CODE'=>$release_code,
                                'USE_EXCEPTIONS'=>'X'
                            );
                            $function_type = $rfc->getFunction('BAPI_PO_RESET_RELEASE');
                            $approve= $function_type->invoke($param, $options);
                        } catch(\Exception $e){}
                    }
                }

            } else if($status_approval=="REJECTED"){
                // Check last status approval PO
                // $param = array(
                //     'PURCHASEORDER'=>$po_number,
                //     'PO_REL_CODE'=>'',
                // );
                // $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
                // $last_approve_status = $function_type->invoke($param, $options);
                // $last_status = isset($last_approve_status['GENERAL_RELEASE_INFO']['REL_IND']) ? $last_approve_status['GENERAL_RELEASE_INFO']['REL_IND'] : '';
                // $cek_sudah_approve = collect($last_approve_status['RELEASE_ALREADY_POSTED'])->filter(function($item, $key){
                //     return substr($key, 0, 8) == 'REL_CODE';
                // });
                // dd($last_status, $cek_sudah_approve);

                $param = array(
                    'PURCHASEORDER'=>$po_number,
                    'PO_REL_CODE'=>$release_code,
                    'USE_EXCEPTIONS'=>'X'
                );

                $function_type = $rfc->getFunction('BAPI_PO_RESET_RELEASE');
                $approve= $function_type->invoke($param, $options);
                $new_indicator=isset($approve['REL_INDICATOR_NEW']) ? $approve['REL_INDICATOR_NEW'] : '';
                $new_status=isset($approve['REL_STATUS_NEW']) ? $approve['REL_STATUS_NEW'] : '';

                // Ini gatau buat apa, tapi disimpen aja dulu karena return dari SAP kaya gni
                $ret_code=isset($approve['RET_CODE']) ? $approve['RET_CODE'] : '';
                if(!empty($new_status)){// jika GV_REL_STATUS_NEW = X maka artinya approve success
                    // try {
                    //     $last_approve_status="CANCELLED";
                    //     $data_to_insert = [
                    //         "RELEASE_CODE"=>$release_code,
                    //         "RELEASE_CODE_NAME_ID"=>$employee_id,
                    //         "STATUS"=>$last_approve_status,
                    //         "RELEASE_DATE"=>date('Y-m-d H:i:s'),
                    //         "PO_NUMBER"=>$po_number,
                    //         "REASON"=>$reason,
                    //         "RELEASE_GROUP_CODE"=>$release_group,
                    //     ];
                    //     DB::connection('dbintranet')
                    //     ->table('SAP_PO_RELEASE')
                    //     ->insert($data_to_insert);
                    // } catch(\Exception $e){}
                }
            }

            $data['code'] = 200;
            $data['message'] = 'Success';
            $connection->commit();
        }
        catch(SAPFunctionException $e) {
            $data['code'] = 401;
            $data['message'] = isset($e->errorInfo['key']) ? $e->errorInfo['key'] : $e->getMessage();
            $connection->rollback();
        }
        catch(SapException $e) {
            $data['code'] = 401;
            $data['message'] = isset($e->errorInfo['key']) ? $e->errorInfo['key'] : $e->getMessage();
            $connection->rollback();
        }
        catch(QueryException $e) {
            $data['code'] = 401;
            $data['message'] = isset($e->errorInfo['key']) ? $e->errorInfo['key'] : $e->getMessage();
            $connection->rollback();
        }
        return $data;
    }

    public function approve(Request $request){
        // =============
        // cari data sequence dari FORM
        try{
            $po_number=$request->input('po_number');
            $release_code=$request->input('relcode');
            $release_group=$request->input('relgroup');
            $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;

            if(!empty($po_number) && !empty($release_code) && !empty($release_group)){
                 //init RFC
                $is_production = config('intranet.is_production');
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc'));
                }

                $options = [
                    'rtrim'=>true
                ];
                //===
                $param = array(
                    'PURCHASEORDER'=>$po_number,
                    'PO_REL_CODE'=>$release_code,
                    'USE_EXCEPTIONS'=>'X'
                );

                $function_type = $rfc->getFunction('BAPI_PO_RELEASE');
                $approve= $function_type->invoke($param, $options);
                $new_indicator=isset($approve['REL_INDICATOR_NEW']) ? $approve['REL_INDICATOR_NEW'] : '';
                $new_status=isset($approve['REL_STATUS_NEW']) ? $approve['REL_STATUS_NEW'] : '';
                // Ini gatau buat apa, tapi disimpen aja dulu karena return dari SAP kaya gni
                $ret_code=isset($approve['RET_CODE']) ? $approve['RET_CODE'] : '';
                if(!empty($new_status)) {// jika GV_REL_STATUS_NEW = X maka artinya approve success

                    //cek apakah dia sudah finish atau masih lanjut
                    if($new_indicator == 'R'){
                        $last_approve_status="FINISHED";
                    }else{
                        $last_approve_status="APPROVED";
                    }

                    try {
                        $data_to_insert = [
                            "RELEASE_CODE"=>$release_code,
                            "RELEASE_CODE_NAME_ID"=>$employee_id,
                            "STATUS"=>$last_approve_status,
                            "RELEASE_DATE"=>date('Y-m-d H:i:s'),
                            "PO_NUMBER"=>$po_number,
                            "REASON"=>'Approved after read detail information',
                            "RELEASE_GROUP_CODE"=>$release_group,
                        ];
                        DB::connection('dbintranet')
                        ->table('SAP_PO_RELEASE')
                        ->insert($data_to_insert);
                    } catch(\Exception $e){
                        try{
                            $param = array(
                                'PURCHASEORDER'=>$po_number,
                                'PO_REL_CODE'=>$release_code,
                                'USE_EXCEPTIONS'=>'X'
                            );
                            $function_type = $rfc->getFunction('BAPI_PO_RESET_RELEASE');
                            $approve= $function_type->invoke($param, $options);
                        } catch(\Exception $e){}
                    }
                    if( $last_approve_status=="APPROVED"){
                        // START -- MENCARI EMPLOYEE ID UNTUK APPROVER SELANJUTNYA UNTUK NOTIFIKASI
                        $param = array(
                            'PURCHASEORDER'=>$po_number,
                            'PO_REL_CODE'=>'',
                        );
                        $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
                        $last_approve_status = $function_type->invoke($param, $options);
                        $release_info = collect(isset($last_approve_status['RELEASE_FINAL'][0]) ? $last_approve_status['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                            // take only description of release strategy code
                            return substr($key, 0, 8) == 'REL_CODE';
                        })->filter()->toArray();


                        $prior_release_approve = array_values(collect(isset($last_approve_status['RELEASE_ALREADY_POSTED']) ? $last_approve_status['RELEASE_ALREADY_POSTED'] : [])->filter(function($item, $key){
                            // take only description of release strategy code
                            return substr($key, 0, 8) == 'REL_CODE';
                        })->filter()->toArray());
                        $release_code = array_values($release_info);

                        // Cek yang seharusnya approve selanjutnya
                        $now_release_approve = '';
                        foreach ($release_code as $key => $value) {
                            if (!in_array($value, $prior_release_approve)) {
                                $now_release_approve = $value;
                                break;
                            }
                        }

                        $param = array(
                            'PURCHASEORDER' =>$po_number
                        );
                        $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                        $po_list = $function_type->invoke($param, $options);

                        if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                            $po_list['PO_HEADER']['COST_CENTER'] = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                            $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_ITEMS'][0]['PREQ_NO']) ? $po_list['PO_ITEMS'][0]['PREQ_NO'] : '';
                            $data['po']=$po_list['PO_HEADER'];

                            $PR_employee = '';
                            // mencari midjob
                            try {
                                $param = array(
                                    'GV_NUMBER'=>$po_list['PO_HEADER']['REQ_NO']
                                );
                                // $function_type = $rfc->getFunction('ZFM_PR_GET_DETAIL_INTRA');
                                $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                                $data_form= $function_type->invoke($param, $options);
                                if(isset($data_form['GI_ITEMS']) && count($data_form['GI_ITEMS'])){
                                    $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                                }
                            } catch(\Exception $e){}

                            // validasi jika Tracking Number Kosong,
                            // maka akan mengambil cost center dari tabel custom
                            // ini dilakukan biasanya untuk PO yang digenerate dari MRP

                            if(empty($data['po']['COST_CENTER'])){
                                //ambil nama PR dari requisitioner di SAP untuk query
                                $mrp_requisitioner=isset($data_form['GI_ITEMS'][0]['PREQ_NAME']) ? $data_form['GI_ITEMS'][0]['PREQ_NAME'] : NULL;

                                //query ke tabel custom
                                $cost_center_mrp=DB::connection('dbintranet')
                                ->select("SELECT COSTCENTER FROM SAP_PO_MRP_COSTCENTER WHERE MRP_USERNAME='$mrp_requisitioner'");

                                $cost_center_mrp=count($cost_center_mrp)? $cost_center_mrp[0]->COSTCENTER : NULL;
                                $data['po']['COST_CENTER']=$cost_center_mrp;
                            }


                            $cost_center = isset($data['po']['COST_CENTER']) ? $data['po']['COST_CENTER'] : '';

                            $requestor_midjob = DB::connection('dbintranet')
                            ->table('INT_EMPLOYEE_ASSIGNMENT')
                            ->where('EMPLOYEE_ID', $PR_employee)
                            ->select('MIDJOB_TITLE_ID')
                            ->get()->pluck('MIDJOB_TITLE_ID')->first();

                            $release_mapping = DB::connection('dbintranet')
                            ->table('dbo.SAP_PO_RELEASE_CODE_NEW AS ap')
                            ->where(['ap.COSTCENTER'=>$cost_center, 'ap.MIDJOB_TITLE'=>$requestor_midjob])
                            ->orWhere(function($query) use ($cost_center){
                                $query->where('ap.COSTCENTER', $cost_center)
                                ->whereNull('ap.MIDJOB_TITLE');
                            })
                            ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                                $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
                            })
                            ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                                $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
                            })
                            ->select('ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
                            ->distinct()
                            ->get()->groupBy('RELEASE_CODE')->toArray();

                            if(!empty($release_mapping[$now_release_approve])){
                                $employee_data_next=$release_mapping[$now_release_approve][0];
                                $employee_next=(!empty($employee_data_next->EMPLOYEE_ID)) ? $employee_data_next->EMPLOYEE_ID : $employee_data_next->ALT_EMPLOYEE;

                                //skrip kirim notifikasi disini
                                if(!empty($employee_next)){
                                    $notif_link="/finance/purchase-order/detail/".$po_number;
                                    $notif_desc="Please approve Purchase Order : ".$po_number." ";
                                    $notif_type="info";
                                    insertNotification($employee_next, $notif_link, $notif_desc, $notif_type);
                                }
                            }
                        }
                        // END -- MENCARI EMPLOYEE ID UNTUK APPROVER SELANJUTNYA UNTUK NOTIFIKASI
                    }else if($last_approve_status=="FINISHED"){
                        // START -- MENCARI EMPLOYEE ID UNTUK REQUESTOR JIKA FINISH
                        $param = array(
                            'PURCHASEORDER'=>$po_number,
                            'PO_REL_CODE'=>'',
                        );
                        $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
                        $last_approve_status = $function_type->invoke($param, $options);
                        $release_info = collect(isset($last_approve_status['RELEASE_FINAL'][0]) ? $last_approve_status['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                            // take only description of release strategy code
                            return substr($key, 0, 8) == 'REL_CODE';
                        })->filter()->toArray();


                        $prior_release_approve = array_values(collect(isset($last_approve_status['RELEASE_ALREADY_POSTED']) ? $last_approve_status['RELEASE_ALREADY_POSTED'] : [])->filter(function($item, $key){
                            // take only description of release strategy code
                            return substr($key, 0, 8) == 'REL_CODE';
                        })->filter()->toArray());
                        $release_code = array_values($release_info);

                        // Cek yang seharusnya approve selanjutnya
                        $now_release_approve = '';
                        foreach ($release_code as $key => $value) {
                            if (!in_array($value, $prior_release_approve)) {
                                $now_release_approve = $value;
                                break;
                            }
                        }

                        $param = array(
                            'PURCHASEORDER' =>$po_number
                        );
                        $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                        $po_list = $function_type->invoke($param, $options);

                        if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                            $po_list['PO_HEADER']['COST_CENTER'] = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                            $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_ITEMS'][0]['PREQ_NO']) ? $po_list['PO_ITEMS'][0]['PREQ_NO'] : '';
                            $data['po']=$po_list['PO_HEADER'];
                            $cost_center = isset($data['po']['COST_CENTER']) ? $data['po']['COST_CENTER'] : '';


                            // mencari midjob
                            try {
                                $param = array(
                                    'GV_NUMBER'=>$po_list['PO_HEADER']['REQ_NO']
                                );
                                // $function_type = $rfc->getFunction('ZFM_PR_GET_DETAIL_INTRA');
                                $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                                $data_form= $function_type->invoke($param, $options);
                                $requestor=(!empty($data_form['GI_ITEMS'][0]['PREQ_ID']))? $data_form['GI_ITEMS'][0]['PREQ_ID'] : NULL;
                                if(!empty($requestor)){
                                    //insert script notifikasi disini
                                    $notif_link="/finance/purchase-order/detail/".$po_number;
                                    $notif_desc="Your Purchase Order : ".$po_number." from PR Number ".$po_list['PO_HEADER']['REQ_NO']."  is approved";
                                    $notif_type="approve";
                                    insertNotification($requestor, $notif_link, $notif_desc, $notif_type);
                                }

                            } catch(\Exception $e){}

                        }

                        // END -- MENCARI EMPLOYEE ID UNTUK REQUESTOR JIKA FINISH
                    }


                    $success=true;
                    $code = 200;
                    $msg = 'Purchase Order has been successfully Approved';
                }
                else {
                    $success=false;
                    $code = 403;
                    $msg = 'Status for approval is unknown, please try again in a moment';
                }

            } else {
                $success=false;
                $code = 403;
                $msg = 'PO number, Release Code and Release Group cannot be empty, please check the data';
            }
        } catch(SAPFunctionException $e) {
            $success=false;
            $code = 403;
            $msg = isset($e->getErrorInfo()['key']) ? $e->getErrorInfo()['key'] : $e->getMessage();
        } catch(QueryException $e) {
            $success=false;
            $code = 403;
            $msg = $e->errorInfo;
        }

        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));
    }

    public function reject(Request $request){
    }


    function checkReleaseMappingOrder($a, $b)
    {
        $awal = isset($a->RELEASE_CODE) ? (int)substr($a->RELEASE_CODE,1) : '';
        $selanjutnya = isset($b->RELEASE_CODE) ? (int)substr($b->RELEASE_CODE, 1) : '';
        if ($awal == $selanjutnya) {
            return 0;
        }
        elseif ($awal == 0) {
            return -1;
        }
        // Jika F4 Return true karena tidak ada F1 di KMS
        elseif ($awal == 4 || $selanjutnya == 4) {
            return 1;
        }
        return ($awal < $selanjutnya) ? -1 : 1;
    }

    public function detail($id = null, Request $request)
    {
        //init RFC
        if(empty($id))
            $id = $request->get('id', 0);

        $data['po'] = [];
        $release_mapping = [];
        $prior_release_approve = [];
        $release_indicator = null;
        $release_history = [];
        $PR_employee = null;
        try {
            $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
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

            $param = array(
                'PURCHASEORDER' =>$id,
                'ITEM_TEXTS'=>'X',
                'HEADER_TEXTS'=>'X'
            );
            $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
            $po_list = $function_type->invoke($param, $options);
            // dd($po_list);
            if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                try{
                    $param = array(
                        'VENDORNO' => isset($po_list['PO_HEADER']['VENDOR']) ? $po_list['PO_HEADER']['VENDOR'] : ''
                    );
                    $function_type = $rfc->getFunction('BAPI_VENDOR_GETDETAIL');
                    $vendor_detail = $function_type->invoke($param, $options);
                    if(isset($vendor_detail['GENERALDETAIL']['NAME'])){
                        $po_list['PO_HEADER']['VEND_NAME'] = strtoupper($vendor_detail['GENERALDETAIL']['NAME']);
                        $po_list['PO_HEADER']['VEND_CITY'] = strtoupper($vendor_detail['GENERALDETAIL']['CITY']);
                        $po_list['PO_HEADER']['VEND_ADDRESS'] = strtoupper($vendor_detail['GENERALDETAIL']['STREET']);
                        $po_list['PO_HEADER']['VEND_COUNTRY'] = strtoupper($vendor_detail['GENERALDETAIL']['COUNTRY']);
                        $po_list['PO_HEADER']['VEND_TEL'] = strtoupper($vendor_detail['GENERALDETAIL']['TELEPHONE']);
                    }

                } catch(\Exception $e){}

                // mendapatkan data detail item dengan BAPI lain untuk mencari requisitioner per item
                try{
                    $param = array(
                        'PURCHASEORDER' => $id
                    );
                    $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL1');
                    $item_detail = $function_type->invoke($param, $options);
                    $po_list['PO_HEADER']['ITEM_ALT'] = (isset($item_detail['POITEM'][0])) ? $item_detail['POITEM'] : array() ;
                } catch(\Exception $e){}
                // end

                try {
                    $po_list['PO_ITEMS'] = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function($value, $key){
                        $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                        return !empty($delete_ind);
                    })->values()->all() : [];
                } catch(\Exception $e){}

                $po_list['PO_HEADER']['CREATED_ON'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['CREATED_ON']));
                $po_list['PO_HEADER']['DOC_DATE'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['DOC_DATE']));
                $po_list['PO_HEADER']['COST_CENTER'] = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_ITEMS'][0]['PREQ_NO']) ? $po_list['PO_ITEMS'][0]['PREQ_NO'] : '';
                // mencari header text
                $header_text ='';
                if(isset($po_list['PO_HEADER_TEXTS'][0]) && !empty($po_list['PO_HEADER_TEXTS'][0])){

                    foreach($po_list['PO_HEADER_TEXTS'] as $loop_header){
                        $header_text .= ' - '.$loop_header['TEXT_LINE'];
                    }
                    $header_text=substr($header_text,3);
                }

                $po_list['PO_HEADER']['PO_HEADER_TEXTS']= $header_text;

                try {
                    // dd($real_header['REL_IND']);
                    $get_doc_type_desc = DB::connection('dbintranet')
                    ->table('INT_SAP_PO_DOCUMENT_TYPE')
                    ->where('PURC_DOC_TYPE', $po_list['PO_HEADER']['DOC_TYPE'])
                    ->select('PURC_DOC_TYPE_DESC')
                    ->get()->pluck('PURC_DOC_TYPE_DESC')->first();
                    if($get_doc_type_desc)
                        $po_list['PO_HEADER']['DOC_TYPE'] = $get_doc_type_desc;
                } catch(\Exception $e){}

                try {
                    // dd($real_header['REL_IND']);
                    $cost_center_requestor = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                    $get_cost_center_name = DB::connection('dbintranet')
                    ->table('INT_SAP_COST_CENTER')
                    ->where('SAP_COST_CENTER_ID', $cost_center_requestor)
                    ->select('SAP_COST_CENTER_DESCRIPTION')
                    ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                    if($get_doc_type_desc)
                        $po_list['PO_HEADER']['REQUESTOR_COST_CENTER'] = $get_cost_center_name;
                } catch(\Exception $e){}

                try {
                    $param = array(
                        'GV_NUMBER'=>$po_list['PO_HEADER']['REQ_NO']
                    );
                    // $function_type = $rfc->getFunction('ZFM_PR_GET_DETAIL_INTRA');
                    $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                    $data_form= $function_type->invoke($param, $options);
                    if(isset($data_form['GI_ITEMS']) && count($data_form['GI_ITEMS'])){
                        $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                    }
                } catch(\Exception $e){}

                $data['po'] = $po_list['PO_HEADER'];
                $detail_po = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function($value, $key){
                    $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                    return !empty($delete_ind);
                })->toArray() : [];
                $data['po']['PO_DETAILS'] = $detail_po;

                // start mengelompokkan item text / reason

                $item_texts = collect($po_list['PO_ITEM_TEXTS'])->groupBy('PO_ITEM');

                $data['po']['PO_ITEM_TEXTS'] = $item_texts;

                // validasi jika Tracking Number Kosong,
                // maka akan mengambil cost center dari tabel custom
                // ini dilakukan biasanya untuk PO yang digenerate dari MRP

                if(empty($data['po']['COST_CENTER'])){
                    //ambil nama PR dari requisitioner di SAP untuk query
                    $mrp_requisitioner=isset($data_form['GI_ITEMS'][0]['PREQ_NAME']) ? $data_form['GI_ITEMS'][0]['PREQ_NAME'] : NULL;

                    //query ke tabel custom
                    $cost_center_mrp=DB::connection('dbintranet')
                    ->select("SELECT COSTCENTER FROM SAP_PO_MRP_COSTCENTER WHERE MRP_USERNAME='$mrp_requisitioner'");

                    $cost_center_mrp=count($cost_center_mrp)? $cost_center_mrp[0]->COSTCENTER : NULL;
                    $data['po']['COST_CENTER']=$cost_center_mrp;
                }

            }


            // Now check approval / release strategy
            // try {
            $param = array(
                'PURCHASEORDER'=>$id,
                'PO_REL_CODE'=>'',
            );
            $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
            $last_approve_status = $function_type->invoke($param, $options);

            $release_info = collect(isset($last_approve_status['RELEASE_FINAL'][0]) ? $last_approve_status['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                // take only description of release strategy code
                return substr($key, 0, 8) == 'REL_CODE';
            })->filter()->toArray();

            $release_group = isset($data['po']['REL_GROUP']) ? $data['po']['REL_GROUP'] : '';
            $cost_center = isset($data['po']['COST_CENTER']) ? $data['po']['COST_CENTER'] : '';
            $prior_release_approve = array_values(collect(isset($last_approve_status['RELEASE_ALREADY_POSTED']) ? $last_approve_status['RELEASE_ALREADY_POSTED'] : [])->filter(function($item, $key){
                // take only description of release strategy code
                return substr($key, 0, 8) == 'REL_CODE';
            })->filter()->toArray());
            $release_code = array_values($release_info);

            // Cek yang seharusnya approve selanjutnya
            $now_release_approve = '';
            foreach ($release_code as $key => $value) {
                if (!in_array($value, $prior_release_approve)) {
                    $now_release_approve = $value;
                    break;
                }
            }

            $release_indicator = isset($data['po']['REL_IND']) ? $data['po']['REL_IND'] : null;

            /* MENGGUNAKAN COST CENTER DAN MIDJOB */
            $requestor_midjob = DB::connection('dbintranet')
            ->table('INT_EMPLOYEE_ASSIGNMENT')
            ->where('EMPLOYEE_ID', $PR_employee)
            ->select('MIDJOB_TITLE_ID')
            ->get()->pluck('MIDJOB_TITLE_ID')->first();

            // Mencari release untuk PO dengan cost center dan midjob tertentu (Siapa saja yg bisa approve)
            $release_mapping = DB::connection('dbintranet')
            ->table('dbo.SAP_PO_RELEASE_CODE_NEW AS ap')
            ->where(['ap.COSTCENTER'=>$cost_center, 'ap.MIDJOB_TITLE'=>$requestor_midjob])
            ->orWhere(function($query) use ($cost_center){
                $query->where('ap.COSTCENTER', $cost_center)
                ->whereNull('ap.MIDJOB_TITLE');
            })
            ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
            })
            ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
            })
            ->select('ap.SEQ_ID', 'ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
            ->distinct()
            ->get()->toArray();

            // Sort Release Strategy mapping
            $release_mapping = collect($release_mapping)->sortBy('SEQ_ID')->toArray();
            // usort($release_mapping, array( $this, 'checkReleaseMappingOrder' ));

            // Cek history approval di DB warehouse
            // $release_history = DB::connection('dbintranet')
            // ->table('dbo.SAP_PO_RELEASE')
            // ->where('PO_NUMBER', $id)
            // ->select('RELEASE_CODE_NAME_ID','RELEASE_DATE')
            // ->get()->pluck('RELEASE_DATE','RELEASE_CODE_NAME_ID')->toArray();
            /// Cek history approval di DB warehouse
            $release_history = DB::connection('dbintranet')
            ->table('dbo.SAP_PO_RELEASE')
            ->where('PO_NUMBER', $id)
            ->select('RELEASE_DATE', DB::raw("CONCAT(RELEASE_CODE,'-',RELEASE_CODE_NAME_ID) AS RELEASE_CODE_NAME_ID"))
            ->get()->pluck('RELEASE_DATE','RELEASE_CODE_NAME_ID')->toArray();
            // } catch(\Exception $e){}

        } catch(SAPFunctionException $e){
            if($request->ajax()){
                return response()->json(['type'=>'error', 'message'=>$e->getMessage()]);
            } else {
                $request->session()->flash('error_po_approval', 'Something went wrong when trying to get data from SAP');
                return redirect()->route('finance.purchase-order.list');
            }
        } catch(\Exception $e){


            if($request->ajax()){
                return response()->json(['type'=>'error', 'message'=>$e->getMessage()]);
            } else {
                $request->session()->flash('error_po_approval', 'Something went wrong, please try again in a moment');
                return redirect()->route('finance.purchase-order.list');
            }
        }

        if($data['po']){
            // Return normal page if not ajax call
            if($request->ajax()){
                return View::make('pages.finance.purchase-order.modal-detail')->with([
                    'data' => $data['po'],
                    'release_strategy'=>$release_mapping,
                    'prior_release_approve'=>$prior_release_approve,
                    'now_release_approve'=>$now_release_approve,
                    'release_indicator'=>$release_indicator,
                    'current_login_employee'=>$employee_id,
                    'release_history'=>$release_history,
                    'release_code_collected'=>$release_code
                ])->render();
            }
            else {
                // dd(['MAPPING'=>$release_mapping, 'PRIOR_RELEAE'=>$prior_release_approve, 'NOW_RELEASE'=>$now_release_approve, 'RELEASE_HISTORY'=>$release_history, 'RELEASE_CODE_SAP'=>$release_code]);
                return view('pages.finance.purchase-order.detail', ['data' => $data['po'], 'release_strategy'=>$release_mapping, 'prior_release_approve'=>$prior_release_approve, 'now_release_approve'=>$now_release_approve, 'release_indicator'=>$release_indicator, 'current_login_employee'=>$employee_id, 'release_history'=>$release_history, 'release_code_collected'=>$release_code]);
            }
        }
        else {
            $request->session()->flash('error_po_approval', 'PO detail cannot be shown due to approved already or not found. Go to report PO if you want to see approval history');
            return redirect()->route('finance.purchase-order.list');
        }
    }

    public function detail_approved($id, Request $request)
    {
        //init RFC
        $data['po'] = [];
        $release_mapping = [];
        $prior_release_approve = [];
        $release_indicator = null;
        $release_history = [];
        $PR_employee = null;
        try {
            $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
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
            $param = array(
                'PURCHASEORDER' =>$id,
                'ITEM_TEXTS'=>'X',
                'HEADER_TEXTS'=>'X'
            );
            $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
            $po_list = $function_type->invoke($param, $options);

            if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                // mendapatkan data vendor
                try{
                    $param = array(
                        'VENDORNO' => isset($po_list['PO_HEADER']['VENDOR']) ? $po_list['PO_HEADER']['VENDOR'] : ''
                    );
                    $function_type = $rfc->getFunction('BAPI_VENDOR_GETDETAIL');
                    $vendor_detail = $function_type->invoke($param, $options);
                    if(isset($vendor_detail['GENERALDETAIL']['NAME'])){
                        $po_list['PO_HEADER']['VEND_NAME'] = strtoupper($vendor_detail['GENERALDETAIL']['NAME']);
                        $po_list['PO_HEADER']['VEND_NAME'] = strtoupper($vendor_detail['GENERALDETAIL']['NAME']);
                        $po_list['PO_HEADER']['VEND_CITY'] = strtoupper($vendor_detail['GENERALDETAIL']['CITY']);
                        $po_list['PO_HEADER']['VEND_ADDRESS'] = strtoupper($vendor_detail['GENERALDETAIL']['STREET']);
                        $po_list['PO_HEADER']['VEND_COUNTRY'] = strtoupper($vendor_detail['GENERALDETAIL']['COUNTRY']);
                        $po_list['PO_HEADER']['VEND_TEL'] = strtoupper($vendor_detail['GENERALDETAIL']['TELEPHONE']);
                    }

                } catch(\Exception $e){}
                // end
                // mendapatkan data detail item dengan BAPI lain untuk mencari requisitioner per item
                try{
                    $param = array(
                        'PURCHASEORDER' => $id
                    );
                    $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL1');
                    $item_detail = $function_type->invoke($param, $options);
                    $po_list['PO_HEADER']['ITEM_ALT'] = (isset($item_detail['POITEM'][0])) ? $item_detail['POITEM'] : array() ;
                } catch(\Exception $e){}
                // end

                try {
                    $po_list['PO_ITEMS'] = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function($value, $key){
                        $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                        return !empty($delete_ind);
                    })->values()->all() : [];
                } catch(\Exception $e){}

                $po_list['PO_HEADER']['CREATED_ON'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['CREATED_ON']));
                $po_list['PO_HEADER']['DOC_DATE'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['DOC_DATE']));
                $po_list['PO_HEADER']['COST_CENTER'] = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_ITEMS'][0]['PREQ_NO']) ? $po_list['PO_ITEMS'][0]['PREQ_NO'] : '';

                // mencari header text
                $header_text ='';
                if(isset($po_list['PO_HEADER_TEXTS'][0]) && !empty($po_list['PO_HEADER_TEXTS'][0])){

                    foreach($po_list['PO_HEADER_TEXTS'] as $loop_header){
                        $header_text .= ' - '.$loop_header['TEXT_LINE'];
                    }
                    $header_text=substr($header_text,3);
                }

                $po_list['PO_HEADER']['PO_HEADER_TEXTS']= $header_text;

                try {
                    // dd($real_header['REL_IND']);
                    $get_doc_type_desc = DB::connection('dbintranet')
                    ->table('INT_SAP_PO_DOCUMENT_TYPE')
                    ->where('PURC_DOC_TYPE', $po_list['PO_HEADER']['DOC_TYPE'])
                    ->select('PURC_DOC_TYPE_DESC')
                    ->get()->pluck('PURC_DOC_TYPE_DESC')->first();
                    if($get_doc_type_desc)
                        $po_list['PO_HEADER']['DOC_TYPE'] = $get_doc_type_desc;
                } catch(\Exception $e){}

                try {
                    // dd($real_header['REL_IND']);
                    $cost_center_requestor = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                    $get_cost_center_name = DB::connection('dbintranet')
                    ->table('INT_SAP_COST_CENTER')
                    ->where('SAP_COST_CENTER_ID', $cost_center_requestor)
                    ->select('SAP_COST_CENTER_DESCRIPTION')
                    ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                    if($get_doc_type_desc)
                        $po_list['PO_HEADER']['REQUESTOR_COST_CENTER'] = $get_cost_center_name;
                } catch(\Exception $e){}

                try {
                    $param = array(
                        'GV_NUMBER'=>$po_list['PO_HEADER']['REQ_NO']
                    );
                    // $function_type = $rfc->getFunction('ZFM_PR_GET_DETAIL_INTRA');
                    $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                    $data_form= $function_type->invoke($param, $options);
                    if(isset($data_form['GI_ITEMS']) && count($data_form['GI_ITEMS'])){
                        $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                    }

                } catch(\Exception $e){}

                $data['po'] = $po_list['PO_HEADER'];
                $detail_po = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function($value, $key){
                    $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                    return !empty($delete_ind);
                })->toArray() : [];
                $data['po']['PO_DETAILS'] = $detail_po;

                // start mengelompokkan item text / reason
                $item_texts = collect($po_list['PO_ITEM_TEXTS'])->groupBy('PO_ITEM');
                $data['po']['PO_ITEM_TEXTS'] = $item_texts;

                  // validasi jika Tracking Number Kosong,
                // maka akan mengambil cost center dari tabel custom
                // ini dilakukan biasanya untuk PO yang digenerate dari MRP

                if(empty($data['po']['COST_CENTER'])){
                    //ambil nama PR dari requisitioner di SAP untuk query
                    $mrp_requisitioner=isset($data_form['GI_ITEMS'][0]['PREQ_NAME']) ? $data_form['GI_ITEMS'][0]['PREQ_NAME'] : NULL;

                    //query ke tabel custom
                    $cost_center_mrp=DB::connection('dbintranet')
                    ->select("SELECT COSTCENTER FROM SAP_PO_MRP_COSTCENTER WHERE MRP_USERNAME='$mrp_requisitioner'");

                    $cost_center_mrp=count($cost_center_mrp)? $cost_center_mrp[0]->COSTCENTER : NULL;
                    $data['po']['COST_CENTER']=$cost_center_mrp;
                }
            }

            // Now check approval / release strategy
            // try {
            $param = array(
                'PURCHASEORDER'=>$id,
                'PO_REL_CODE'=>'',
            );
            $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
            $last_approve_status = $function_type->invoke($param, $options);
            $release_info = collect(isset($last_approve_status['RELEASE_FINAL'][0]) ? $last_approve_status['RELEASE_FINAL'][0] : [])->filter(function($item, $key){
                // take only description of release strategy code
                return substr($key, 0, 8) == 'REL_CODE';
            })->filter()->toArray();

            $release_group = isset($data['po']['REL_GROUP']) ? $data['po']['REL_GROUP'] : '';
            $cost_center = isset($data['po']['COST_CENTER']) ? $data['po']['COST_CENTER'] : '';
            $prior_release_approve = array_values(collect(isset($last_approve_status['RELEASE_ALREADY_POSTED']) ? $last_approve_status['RELEASE_ALREADY_POSTED'] : [])->filter(function($item, $key){
                // take only description of release strategy code
                return substr($key, 0, 8) == 'REL_CODE';
            })->filter()->toArray());
            $release_code = array_values($release_info);

            // Cek yang seharusnya approve selanjutnya
            $now_release_approve = '';
            foreach ($release_code as $key => $value) {
                if (!in_array($value, $prior_release_approve)) {
                    $now_release_approve = $value;
                    break;
                }
            }

            $release_indicator = isset($data['po']['REL_IND']) ? $data['po']['REL_IND'] : null;

            /* MENGGUNAKAN COST CENTER DAN MIDJOB */
            $requestor_midjob = DB::connection('dbintranet')
            ->table('INT_EMPLOYEE_ASSIGNMENT')
            ->where('EMPLOYEE_ID', $PR_employee)
            ->select('MIDJOB_TITLE_ID')
            ->get()->pluck('MIDJOB_TITLE_ID')->first();

            // Mencari release untuk PO dengan cost center dan midjob tertentu (Siapa saja yg bisa approve)
            $release_mapping = DB::connection('dbintranet')
            ->table('dbo.SAP_PO_RELEASE_CODE_NEW AS ap')
            ->where(['ap.COSTCENTER'=>$cost_center, 'ap.MIDJOB_TITLE'=>$requestor_midjob])
            ->orWhere(function($query) use ($cost_center){
                $query->where('ap.COSTCENTER', $cost_center)
                ->whereNull('ap.MIDJOB_TITLE');
            })
            ->leftJoin('INT_EMPLOYEE AS emp', function($join){
                $join->on('ap.APP_EMPLOYEE_ID','=','emp.EMPLOYEE_ID');
            })
            ->leftJoin('INT_EMPLOYEE AS alt', function($join){
                $join->on('ap.ALT_EMPLOYEE_ID', '=', 'alt.EMPLOYEE_ID');
            })
            ->select('ap.SEQ_ID', 'ap.RELEASE_CODE', 'ap.APP_EMPLOYEE_ID AS EMPLOYEE_ID', 'ap.ALT_EMPLOYEE_ID', 'emp.EMPLOYEE_NAME AS MAIN_EMPLOYEE', 'alt.EMPLOYEE_NAME AS ALT_EMPLOYEE')
            ->distinct()
            ->get()->toArray();

            // Sort Release Strategy mapping
            $release_mapping = collect($release_mapping)->sortBy('SEQ_ID')->toArray();
            // usort($release_mapping, array( $this, 'checkReleaseMappingOrder' ));

            // Cek history approval di DB warehouse
            // $release_history = DB::connection('dbintranet')
            // ->table('dbo.SAP_PO_RELEASE')
            // ->where('PO_NUMBER', $id)
            // ->select('RELEASE_CODE_NAME_ID','RELEASE_DATE')
            // ->get()->pluck('RELEASE_DATE','RELEASE_CODE_NAME_ID')->toArray();
            // Cek history approval di DB warehouse
            $release_history = DB::connection('dbintranet')
            ->table('dbo.SAP_PO_RELEASE')
            ->where('PO_NUMBER', $id)
            ->select('RELEASE_DATE', DB::raw("CONCAT(RELEASE_CODE,'-',RELEASE_CODE_NAME_ID) AS RELEASE_CODE_NAME_ID"))
            ->get()->pluck('RELEASE_DATE','RELEASE_CODE_NAME_ID')->toArray();
            // } catch(\Exception $e){}

        } catch(SAPFunctionException $e){
            if($request->ajax()){
                return response()->json(['type'=>'error', 'message'=>$e->getMessage()]);
            } else {
                $request->session()->flash('error_po_approval', 'Something went wrong when trying to get data from SAP');
                return redirect()->route('finance.purchase-order.report');
            }
        } catch(\Exception $e){
            if($request->ajax()){
                return response()->json(['type'=>'error', 'message'=>$e->getMessage()]);
            } else {
                $request->session()->flash('error_po_approval', 'Something went wrong, please try again in a moment');
                return redirect()->route('finance.purchase-order.report');
            }
        }

        // Return normal page if not ajax call
        if($data['po'])
            return view('pages.finance.purchase-order.detail_approved', ['data' => $data['po'], 'release_strategy'=>$release_mapping, 'prior_release_approve'=>$prior_release_approve, 'now_release_approve'=>$now_release_approve, 'release_indicator'=>$release_indicator, 'current_login_employee'=>$employee_id, 'release_history'=>$release_history, 'release_code_collected'=>$release_code]);
        else
            $request->session()->flash('error_po_approval', 'PO detail cannot be shown due to approved already or not found. Go to report PO if you want to see approval history');
            return redirect()->route('finance.purchase-order.report');
    }

    public function export_old(Request $request){
        try {
            $data['po'] = [];
            if(Cache::store('file')->has('list_po_number')){
                $chunks = array_chunk(Cache::store('file')->get('list_po_number'), 10);
                $chunks = [$chunks[0], $chunks[1]];
                for($c_loop=0;$c_loop<count($chunks);$c_loop++){
                    foreach($chunks[$c_loop] as $val){
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
                        $param = array(
                            'PURCHASEORDER' =>$val,
                            'ITEM_TEXTS'=>'X',
                            'HEADER_TEXTS'=>'X'
                        );
                        $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                        $po_list = $function_type->invoke($param, $options);

                        if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                            // mendapatkan data vendor
                            try{
                                $param = array(
                                    'VENDORNO' => isset($po_list['PO_HEADER']['VENDOR']) ? $po_list['PO_HEADER']['VENDOR'] : ''
                                );
                                $function_type = $rfc->getFunction('BAPI_VENDOR_GETDETAIL');
                                $vendor_detail = $function_type->invoke($param, $options);
                                if(isset($vendor_detail['GENERALDETAIL']['NAME'])){
                                    $po_list['PO_HEADER']['VEND_NAME'] = strtoupper($vendor_detail['GENERALDETAIL']['NAME']);
                                    $po_list['PO_HEADER']['VEND_NAME'] = strtoupper($vendor_detail['GENERALDETAIL']['NAME']);
                                    $po_list['PO_HEADER']['VEND_CITY'] = strtoupper($vendor_detail['GENERALDETAIL']['CITY']);
                                    $po_list['PO_HEADER']['VEND_ADDRESS'] = strtoupper($vendor_detail['GENERALDETAIL']['STREET']);
                                    $po_list['PO_HEADER']['VEND_COUNTRY'] = strtoupper($vendor_detail['GENERALDETAIL']['COUNTRY']);
                                    $po_list['PO_HEADER']['VEND_TEL'] = strtoupper($vendor_detail['GENERALDETAIL']['TELEPHONE']);
                                }

                            } catch(\Exception $e){}
                            // end
                            // mendapatkan data detail item dengan BAPI lain untuk mencari requisitioner per item
                            try{
                                $param = array(
                                    'PURCHASEORDER' => $id
                                );
                                $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL1');
                                $item_detail = $function_type->invoke($param, $options);
                                $po_list['PO_HEADER']['ITEM_ALT'] = (isset($item_detail['POITEM'][0])) ? $item_detail['POITEM'] : array() ;
                            } catch(\Exception $e){}
                            // end

                            try {
                                $po_list['PO_ITEMS'] = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function($value, $key){
                                    $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                                    return !empty($delete_ind);
                                })->values()->all() : [];
                            } catch(\Exception $e){}

                            $po_list['PO_HEADER']['CREATED_ON'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['CREATED_ON']));
                            $po_list['PO_HEADER']['DOC_DATE'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['DOC_DATE']));
                            $po_list['PO_HEADER']['COST_CENTER'] = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                            $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_ITEMS'][0]['PREQ_NO']) ? $po_list['PO_ITEMS'][0]['PREQ_NO'] : '';

                            // mencari header text
                            $header_text ='';
                            if(isset($po_list['PO_HEADER_TEXTS'][0]) && !empty($po_list['PO_HEADER_TEXTS'][0])){
                                foreach($po_list['PO_HEADER_TEXTS'] as $loop_header){
                                    $header_text .= ' - '.$loop_header['TEXT_LINE'];
                                }
                                $header_text=substr($header_text,3);
                            }

                            $po_list['PO_HEADER']['PO_HEADER_TEXTS']= $header_text;

                            try {
                                // dd($real_header['REL_IND']);
                                $get_doc_type_desc = DB::connection('dbintranet')
                                ->table('INT_SAP_PO_DOCUMENT_TYPE')
                                ->where('PURC_DOC_TYPE', $po_list['PO_HEADER']['DOC_TYPE'])
                                ->select('PURC_DOC_TYPE_DESC')
                                ->get()->pluck('PURC_DOC_TYPE_DESC')->first();
                                if($get_doc_type_desc)
                                    $po_list['PO_HEADER']['DOC_TYPE'] = $get_doc_type_desc;
                            } catch(\Exception $e){}

                            try {
                                // dd($real_header['REL_IND']);
                                $cost_center_requestor = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                                $get_cost_center_name = DB::connection('dbintranet')
                                ->table('INT_SAP_COST_CENTER')
                                ->where('SAP_COST_CENTER_ID', $cost_center_requestor)
                                ->select('SAP_COST_CENTER_DESCRIPTION')
                                ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                                if($get_doc_type_desc)
                                    $po_list['PO_HEADER']['REQUESTOR_COST_CENTER'] = $get_cost_center_name;
                            } catch(\Exception $e){}

                            try {
                                $param = array(
                                    'GV_NUMBER'=>$po_list['PO_HEADER']['REQ_NO']
                                );
                                // $function_type = $rfc->getFunction('ZFM_PR_GET_DETAIL_INTRA');
                                $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                                $data_form= $function_type->invoke($param, $options);
                                if(isset($data_form['GI_ITEMS']) && count($data_form['GI_ITEMS'])){
                                    $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                                }

                            } catch(\Exception $e){}

                            $po_list['PO_HEADER']['COST_CENTER_DESC'] = '';
                            try {
                                // dd($real_header['REL_IND']);
                                $cost_center_requestor = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                                $get_cost_center_name = DB::connection('dbintranet')
                                ->table('INT_SAP_COST_CENTER')
                                ->where('SAP_COST_CENTER_ID', $cost_center_requestor)
                                ->select('SAP_COST_CENTER_DESCRIPTION')
                                ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                                if($get_doc_type_desc)
                                    $po_list['PO_HEADER']['COST_CENTER_DESC'] = $get_cost_center_name;
                            } catch(\Exception $e){}

                            $header = $po_list['PO_HEADER'];
                            $detail_po = isset($po_list['PO_ITEMS']) ? $po_list['PO_ITEMS'] : [];
                            $header['PO_DETAILS'] = $detail_po;

                            // start mengelompokkan item text / reason
                            $item_texts = collect($po_list['PO_ITEM_TEXTS'])->groupBy('PO_ITEM');
                            $header['PO_ITEM_TEXTS'] = $item_texts;
                            array_push($data['po'], $header);
                        }

                        $rfc->close();
                    }
                    // Endforeach
                }
                // EndFor
            }
            // Endif

            return DataTables::of($data['po'])->make(true);
        } catch(SAPFunctionException $e){
            Log::error($e);
            return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_message'=>(String)$e], 400);
        } catch(\Exception $e){
            Log::error($e);
            return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_message'=>(String)$e], 400);
        }
    }

    public function export(Request $request){
        $data['po'] = [];
        if(Cache::store('file')->has('list_po_number')){
            // return response()->json(['data'=>Cache::store('file')->get('list_po_number')[0]], 400);
            $cache_data = Cache::store('file')->get('list_po_number');
            for($c_loop=0;$c_loop<count($cache_data);$c_loop++){
                // Loop through items
                for($i_loop=0;$i_loop<count($cache_data[$c_loop]['PO_DETAILS']);$i_loop++){
                    $VAT = isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NOND_ITAX']) && $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NOND_ITAX'] > 0 ? (float)$cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NOND_ITAX'] : 0;
                    if(!$VAT > 0){
                        $VAT = taxCodeCalculation( isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['TAX_CODE']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['TAX_CODE'] : '', isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NET_VALUE']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NET_VALUE'] : 0 );
                    }
                    $subtotal = isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NET_VALUE']) ? ((float)$cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NET_VALUE']) + $VAT : 0;

                    $data['po'][] = array(
                        'PO_NUMBER'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['PO_NUMBER']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['PO_NUMBER'] : '',
                        'REQ_NO'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['PREQ_NO']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['PREQ_NO'] : '',
                        'CREATED_ON'=>isset($cache_data[$c_loop]['CREATED_ON']) ? $cache_data[$c_loop]['CREATED_ON'] : '',
                        'PO_HEADER_TEXTS'=>isset($cache_data[$c_loop]['PO_HEADER_TEXTS']) ? $cache_data[$c_loop]['PO_HEADER_TEXTS'] : '',
                        'VEND_NAME'=>isset($cache_data[$c_loop]['VEND_NAME']) ? $cache_data[$c_loop]['VEND_NAME'] : '',
                        'COST_CENTER_DESC'=>isset($cache_data[$c_loop]['COST_CENTER_DESC']) ? $cache_data[$c_loop]['COST_CENTER_DESC'] : '',
                        'TRACKING_NO'=> isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['TRACKINGNO']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['TRACKINGNO'] : '',
                        'MATERIAL'=> isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['MATERIAL']) ? ltrim($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['MATERIAL'], '0') : '',
                        'MATERIAL_DESC'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['SHORT_TEXT']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['SHORT_TEXT'] : '',
                        'REQUISITIONER'=>isset($cache_data[$c_loop]['PR_REQ_NAME']) ? $cache_data[$c_loop]['PR_REQ_NAME'] : '',
                        'ITEM_REASON'=>isset($cache_data[$c_loop]['PO_ITEM_TEXTS'][$cache_data[$c_loop]['PO_DETAILS'][$i_loop]['PO_ITEM']][0]['TEXT_LINE']) ? $cache_data[$c_loop]['PO_ITEM_TEXTS'][$cache_data[$c_loop]['PO_DETAILS'][$i_loop]['PO_ITEM']][0]['TEXT_LINE'] : '',
                        'PO_QTY'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['QUANTITY']) ? number_format($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['QUANTITY'], 2) : '',
                        'UOM'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['ORDERPR_UN']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['ORDERPR_UN'] : '',
                        'SLOC'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['STORE_LOC']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['STORE_LOC'] : '',
                        'PLANT'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['PLANT']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['PLANT'] : '',
                        'CURRENCY'=>isset($cache_data[$c_loop]['CURRENCY']) ? $cache_data[$c_loop]['CURRENCY'] : '',
                        'NET_VALUE'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NET_VALUE']) ? number_format($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NET_VALUE'], 2) : '',
                        'TAX'=>isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NOND_ITAX']) && $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NOND_ITAX'] > 0 ? number_format($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NOND_ITAX'], 2) : number_format(taxCodeCalculation( isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['TAX_CODE']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['TAX_CODE'] : '', isset($cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NET_VALUE']) ? $cache_data[$c_loop]['PO_DETAILS'][$i_loop]['NET_VALUE'] : 0 ), 2),
                        'SUBTOTAL'=>number_format($subtotal, 2),

                        // Need to be sent even not used
                        'COST_CENTER'=>'',
                        'DOC_TYPE'=>'',
                        'GRAND_TOTAL'=>'',
                    );
                }
            }
        }
        return DataTables::of($data['po'])->make(true);
    }

    // Page untuk search PO berdasarkan cost center
    public function list_po(Request $request){

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



        //init RFC
        $filter_created_from = !empty($request->get('created_date_from')) ? date('Ymd',strtotime($request->get('created_date_from'))) : '';
        $filter_created_to = !empty($request->get('created_date_to')) ? date('Ymd',strtotime($request->get('created_date_to'))) : '';
        $filter_cost_center = !empty($request->get('cost_center')) ? $request->get('cost_center') : '';
        $filter_plant = !empty($request->get('plant_filter')) ? $request->get('plant_filter') : '';

        $data['created_date_to']=$filter_created_to;
        $data['created_date_from']=$filter_created_from;
        $data['cost_center']=$filter_cost_center;
        $data['plant_filter']=$filter_plant;


        try {
            if($request->ajax()){
                if($request->get('cost_center_lookup')){
                    $data_cost_center = [];
                    try {
                        $plant = $request->get('cost_center_lookup');
                        $data_cost_center = CostCenter::whereRaw("LEFT(SAP_COST_CENTER_NAME, 4) = ?", [$plant])
                        ->select('SAP_COST_CENTER_ID', 'SAP_COST_CENTER_DESCRIPTION')->get()->toArray();
                        if(count($data_cost_center)){
                            return response()->json(['status'=>'success', 'data'=>$data_cost_center, 'message'=>'Success loading data'], 200);
                        }
                        else {
                            return response()->json(['status'=>'success', 'data'=>$data_cost_center, 'message'=>sprintf('No Cost Center found for Plant %s', $plant)], 400);
                        }
                    } catch(\Exception $e){
                        Log::error('ERROR LOAD COST CENTER LIST PO | '.$e->getMessage());
                        return response()->json(['status'=>'failed', 'data'=>$data_cost_center, 'message'=>$e->getMessage()], 401);
                    }
                }

                $data['po'] = [];
                $filter_created_from = !empty($request->get('created_date_from')) ? date('Ymd',strtotime($request->get('created_date_from'))) : '';
                $filter_created_to = !empty($request->get('created_date_to')) ? date('Ymd',strtotime($request->get('created_date_to'))) : '';
                $filter_cost_center = !empty($request->get('cost_center')) ? $request->get('cost_center') : '';
                $filter_plant = !empty($request->get('plant_filter')) ? $request->get('plant_filter') : '';
                $company_filter = substr($filter_plant, 0, 3);
                $req_name = !empty($request->get('PR_REQ_NAME')) ? $request->get('PR_REQ_NAME') : '';

                try {
                    $employee_id = Session::has('user_id') ? Session::get('user_id') : 0;
                    // RFC BARU UNTUK YCPX
                    $param = array(
                        'P_COMPANY'=>"$company_filter",
                        'P_PLANT'=>"$filter_plant",
                        'P_DOC_DATE_FROM'=>"$filter_created_from",
                        'P_DOC_DATE_TO'=>"$filter_created_to",
                        'P_DOC_TYPE'=>'YCPX',
                        'P_RELEASE_STATUS'=>'R'
                    );
                    if($req_name)
                        $param['P_PREQ_NAME'] = $req_name;
                    if($filter_cost_center)
                        $param['P_TRACKINGNO'] = "$filter_cost_center";

                    $function_type = $rfc->getFunction('ZFM_MM_MD_PO_LIST');
                    $sap1 = $function_type->invoke($param, $options);
                    $sap_result1=collect($sap1['IT_PO_LIST']);

                    // RFC BARU UNTUK YOPX
                    $param = array(
                        'P_COMPANY'=>"$company_filter",
                        'P_PLANT'=>"$filter_plant",
                        'P_DOC_DATE_FROM'=>"$filter_created_from",
                        'P_DOC_DATE_TO'=>"$filter_created_to",
                        'P_DOC_TYPE'=>'YOPX',
                        'P_RELEASE_STATUS'=>'R'
                    );
                    if($req_name)
                        $param['P_PREQ_NAME'] = $req_name;
                    if($filter_cost_center)
                        $param['P_TRACKINGNO'] = "$filter_cost_center";
                    
                    $function_type = $rfc->getFunction('ZFM_MM_MD_PO_LIST');
                    $sap2 = $function_type->invoke($param, $options);
                    $sap_result2=collect($sap2['IT_PO_LIST']);
                    $sap_result_combined=$sap_result2->concat($sap_result1);
                    // Simpan no PO yang sama karena 1 po terdapat beberapa item
                    // tapi yg diambil hanya informasi umumnya saja
                    $no_duplicate_po = [];
                    $sap_result_combined = $sap_result_combined->filter(function($item, $key) use (&$no_duplicate_po){
                        $po_num = isset($item['PO_NUMBER']) ? $item['PO_NUMBER'] : '';
                        if(!empty($po_num) && !in_array($po_num, $no_duplicate_po)){
                            array_push($no_duplicate_po, $po_num);
                            return true;
                        }
                    })->values()->all();
                    // dd(collect($sap_result_combined)->pluck('PO_NUMBER'));
                    foreach($sap_result_combined as $key => $value){
                        $po_number_loop=$value['PO_NUMBER'];
                        $requestor_sap=isset($value['PREQ_NAME']) ? $value['PREQ_NAME'] : '';
                        $requestor_id=isset($value['PREQ_NIK']) ? $value['PREQ_NIK'] : '';

                        // cek dulu apakah dia sudah final release
                        /*
                        $param = array(
                            'PURCHASEORDER'=>$po_number_loop,
                            'PO_REL_CODE'=>'',
                        );
                        $function_type = $rfc->getFunction('BAPI_PO_GETRELINFO');
                        $last_approve_status = $function_type->invoke($param, $options);
                        */

                        // filter final release = R
                        // if(isset($last_approve_status['GENERAL_RELEASE_INFO']['REL_IND']) && $last_approve_status['GENERAL_RELEASE_INFO']['REL_IND'] == "R"){
                            $param = array(
                                'PURCHASEORDER' =>$po_number_loop,
                                'ITEM_TEXTS'=>'X',
                                'HEADER_TEXTS'=>'X'
                            );
                            $function_type = $rfc->getFunction('BAPI_PO_GETDETAIL');
                            $po_list = $function_type->invoke($param, $options);
                            if(isset($po_list['PO_HEADER']) && count($po_list['PO_HEADER'])){
                                $detail_po = isset($po_list['PO_ITEMS']) ? collect($po_list['PO_ITEMS'])->reject(function ($value, $key) {
                                    $delete_ind = isset($value['DELETE_IND']) ? $value['DELETE_IND'] : '';
                                    return !empty($delete_ind);
                                })->values()->all() : [];

                                $po_list['PO_HEADER']['VEND_CITY'] = '-';
                                $po_list['PO_HEADER']['VEND_ADDRESS'] = '-';
                                $po_list['PO_HEADER']['VEND_COUNTRY'] = '-';
                                $po_list['PO_HEADER']['VEND_TEL'] = '-';
                                try{
                                    $param = array(
                                        'VENDORNO' => isset($po_list['PO_HEADER']['VENDOR']) ? $po_list['PO_HEADER']['VENDOR'] : ''
                                    );
                                    $function_type = $rfc->getFunction('BAPI_VENDOR_GETDETAIL');
                                    $vendor_detail = $function_type->invoke($param, $options);
                                    if(isset($vendor_detail['GENERALDETAIL']['NAME'])){
                                        $po_list['PO_HEADER']['VEND_CITY'] = strtoupper($vendor_detail['GENERALDETAIL']['CITY']);
                                        $po_list['PO_HEADER']['VEND_ADDRESS'] = strtoupper($vendor_detail['GENERALDETAIL']['STREET']);
                                        $po_list['PO_HEADER']['VEND_COUNTRY'] = strtoupper($vendor_detail['GENERALDETAIL']['COUNTRY']);
                                        $po_list['PO_HEADER']['VEND_TEL'] = strtoupper($vendor_detail['GENERALDETAIL']['TELEPHONE']);
                                    }
                                } catch(\Exception $e){}

                                $po_list['PO_HEADER']['PO_DETAILS'] = $detail_po;
                                // $po_list['PO_HEADER']['TARGET_VAL'] = isset($detail_po[0]['EFF_VALUE']) ? $detail_po[0]['EFF_VALUE'] : 0;
                                $po_list['PO_HEADER']['TARGET_VAL'] = collect($detail_po)->reduce(function($carry, $item){
                                    $vat = isset($item['NOND_ITAX']) ? (float)$item['NOND_ITAX'] : 0;
                                    if(!$vat > 0){
                                        $vat = taxCodeCalculation( isset($item['TAX_CODE']) ? $item['TAX_CODE'] : '', isset($item['NET_VALUE']) ? $item['NET_VALUE'] : 0 );
                                    }
                                    $fix_value = isset($item['NET_VALUE']) ? ((float)$item['NET_VALUE']) + $vat : 0;
                                    return $carry + $fix_value;
                                },0);
                                $po_list['PO_HEADER']['CREATED_ON'] = date('Y-m-d', strtotime($po_list['PO_HEADER']['CREATED_ON']));
                                $po_list['PO_HEADER']['COST_CENTER'] = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                                $po_list['PO_HEADER']['COST_CENTER_DESC'] = '';
                                // $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_ITEMS'][0]['PREQ_NO']) ? $po_list['PO_ITEMS'][0]['PREQ_NO'] : '';
                                $po_list['PO_HEADER']['REQ_NO'] = collect($detail_po)->filter(function($item, $key){
                                    return !empty($item['PREQ_NO']);
                                })->values()->all();
                                $po_list['PO_HEADER']['REQ_NO'] = isset($po_list['PO_HEADER']['REQ_NO'][0]['PREQ_NO']) ? $po_list['PO_HEADER']['REQ_NO'][0]['PREQ_NO'] : '';
                                $po_list['PO_HEADER']['PR_REQ_NAME']='-';
                                $po_list['PO_HEADER']['PREQ_NAME_SAP']=$requestor_sap;

                                //cari detail PR
                                if(!empty($requestor_id)){
                                    $param = array(
                                        'GV_NUMBER'=>$po_list['PO_HEADER']['REQ_NO']
                                    );
                                    $function_type = $rfc->getFunction('ZFM_MID_PR_GET_DETAIL_INTRA');
                                    $data_form= $function_type->invoke($param, $options);
                                    if(isset($data_form['GI_ITEMS'][0]['PREQ_ID'])){
                                        $data_pr_requester=DB::connection('dbintranet')
                                        ->select("SELECT EMPLOYEE_NAME FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID='".$data_form['GI_ITEMS'][0]['PREQ_ID']."'");
                                        if(isset($data_pr_requester[0])){
                                            $po_list['PO_HEADER']['PR_REQ_NAME']=$data_pr_requester[0]->EMPLOYEE_NAME;
                                        } else {
                                            $po_list['PO_HEADER']['PR_REQ_NAME']=$requestor_sap;
                                        }
                                    }
                                } else {
                                    $data_pr_requester=DB::connection('dbintranet')
                                    ->select("SELECT EMPLOYEE_NAME FROM VIEW_EMPLOYEE WHERE EMPLOYEE_ID='".$requestor_id."'");
                                    if(isset($data_pr_requester[0])){
                                        $po_list['PO_HEADER']['PR_REQ_NAME']=$data_pr_requester[0]->EMPLOYEE_NAME;
                                    } else {
                                        $po_list['PO_HEADER']['PR_REQ_NAME']=$requestor_sap;
                                    }
                                }

                                $PR_employee = isset($data_form['GI_ITEMS'][0]['PREQ_ID']) ? $data_form['GI_ITEMS'][0]['PREQ_ID'] : '';
                                $po_list['PO_HEADER']['PREQ_ID'] = $PR_employee;

                                try {
                                    // dd($real_header['REL_IND']);
                                    $get_doc_type_desc = DB::connection('dbintranet')
                                    ->table('INT_SAP_PO_DOCUMENT_TYPE')
                                    ->where('PURC_DOC_TYPE', $po_list['PO_HEADER']['DOC_TYPE'])
                                    ->select('PURC_DOC_TYPE_DESC')
                                    ->get()->pluck('PURC_DOC_TYPE_DESC')->first();
                                    if($get_doc_type_desc)
                                        $po_list['PO_HEADER']['DOC_TYPE'] = $get_doc_type_desc;
                                } catch(\Exception $e){}

                                try {
                                    // dd($real_header['REL_IND']);
                                    $cost_center_requestor = isset($po_list['PO_ITEMS'][0]['TRACKINGNO']) ? $po_list['PO_ITEMS'][0]['TRACKINGNO'] : '';
                                    $get_cost_center_name = DB::connection('dbintranet')
                                    ->table('INT_SAP_COST_CENTER')
                                    ->where('SAP_COST_CENTER_ID', $cost_center_requestor)
                                    ->select('SAP_COST_CENTER_DESCRIPTION')
                                    ->get()->pluck('SAP_COST_CENTER_DESCRIPTION')->first();
                                    if($get_doc_type_desc)
                                        $po_list['PO_HEADER']['COST_CENTER_DESC'] = $get_cost_center_name;
                                } catch(\Exception $e){}

                                // mencari header text
                                $header_text ='';
                                if(isset($po_list['PO_HEADER_TEXTS'][0]) && !empty($po_list['PO_HEADER_TEXTS'][0])){
                                    foreach($po_list['PO_HEADER_TEXTS'] as $loop_header){
                                        $header_text .= ' - '.$loop_header['TEXT_LINE'];
                                    }
                                    $header_text=substr($header_text,3);
                                }
                                $po_list['PO_HEADER']['PO_HEADER_TEXTS'] = ucwords(strtolower($header_text));

                                // Mencari Item Text
                                $item_texts = collect($po_list['PO_ITEM_TEXTS'])->groupBy('PO_ITEM')->toArray();
                                $po_list['PO_HEADER']['PO_ITEM_TEXTS'] = $item_texts;

                                // push data po ke penampungan array
                                array_push($data['po'], $po_list['PO_HEADER']);
                                array_push($this->data_po, $po_list['PO_HEADER']);
                            }
                        // }
                    }

                    if($filter_created_to && $filter_created_from){
                        $data['po'] = collect($data['po'])->filter(function($item, $key) use ($filter_created_to, $filter_created_from){
                            try{
                                $po_date = Carbon::createFromFormat('!Y-m-d', $item['CREATED_ON']);
                                return $po_date->gte(date('Y-m-d', strtotime($filter_created_from))) && $po_date->lte(date('Y-m-d', strtotime($filter_created_to)));
                            } catch(\Exception $e){
                                return $item;
                            }
                        })->toArray();
                    }

                } catch(SAPFunctionException $e){
                    Log::error('LIST PO SAP ERROR | '. $e->getMessage());
                    // return response()->json(['data'=>$data, 'message'=>$e->getMessage(), 'type'=>'error'], 200);
                } catch(\Exception $e){
                    Log::error('LIST PO GENERAL ERROR | '. $e->getMessage());
                    // return response()->json(['data'=>$data, 'message'=>$e->getMessage(), 'type'=>'error'], 200);
                }

                // Put all po to cache when export to load faster
                if(Cache::store('file')->has('list_po_number')){
                    Cache::store('file')->forget('list_po_number');
                    Cache::store('file')->put('list_po_number', $this->data_po);
                }
                else{
                    Cache::store('file')->put('list_po_number', $this->data_po);
                }

                return DataTables::of($data['po'])->make(true);
            }
        } catch(\Exception $e){}

        $cek_permission_view_all_po = 'view_'. (String)route('finance.purchase-order.list_po',[],false);
        if(isset($request->session()->get('user_data')->IS_SUPERUSER) && $request->session()->get('user_data')->IS_SUPERUSER || session()->get('permission_menu')->has($cek_permission_view_all_po)){
            $data['plant_list'] = PlantModel::select('SAP_PLANT_ID', 'SAP_PLANT_NAME')->get();
            $list_cost_center=DB::connection('dbintranet')
            ->table('INT_SAP_COST_CENTER')
            ->select("SELECT SAP_COST_CENTER_ID, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER");
        }
        else {
            $data['plant_list'] = PlantModel::where('SAP_PLANT_ID', $plant)->select('SAP_PLANT_ID', 'SAP_PLANT_NAME')->get();
            $list_cost_center=DB::connection('dbintranet')
            ->table('INT_SAP_COST_CENTER')
            ->select("SELECT SAP_COST_CENTER_ID, SAP_COST_CENTER_DESCRIPTION FROM INT_SAP_COST_CENTER WHERE LEFT(SAP_DEPARTMENT_CODE, 4) = '$plant'");
        }


        // Return normal page if not ajax call
        return view('pages.finance.purchase-order.list-po', [
            'data' => $data,
            'filtered_cost_center'=>$filter_cost_center,
            'filtered_plant'=>$filter_plant,
            'list_cost_center'=>$list_cost_center
        ]);
    }


}




