<?php

namespace App\Models\Zoho;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class ZohoFormModel extends Model
{

    public function formRequestEntertainment($request){
        $connection = DB::connection('dbintranet');

        try{
            $reqAll = $request->all();
            $data = array();
            //yang baru
            $uid=""; //Request_Number
            $req_name=""; //Requestor_Name
            $req_emp_id= "";
            $req_company=""; //Requestor_Company
            $req_dept = ""; //Requestor_Department
            $comp_affiliate = ""; //Company_Affiliation
            $name=""; //Name
            $ent_reason=""; //Entertainment_Reason
            $potential=""; //Potential
            $ent_type=""; //Entertainment_Type
            $ent_options=""; //Entertainment_Options
            $ent_date = ""; //Entertainment_Date
            $ent_time = ""; //Entertainment_Time
            $loc = ""; //Location
            $type=""; //Type
            $sbu=""; //SBU
            $outlet = ""; //Outlet
            $benefit = "" ; //Benefit_Entitlement
            $no_guest = "" ; //No_Guest
            $limit_est = ""; //Limit_Estimated
            $typeform="";
            $approval = "";
            $statusApproval = "";
            $reason = "";
            $approvallevel = 0;
            $fulfill_zoho = "";
            $type_form_name="Request Entertainment";

            foreach ($reqAll as $key => $value) {
                if(!is_array($value)){
                    $data[$key] = trim(cleanString($value));
                }else{
                    $data[$key] = $value;
                }

                if(strtolower($key) === 'uid'){
                    $uid = trim($value);
                }
                if(strtolower($key) === 'requestor_name'){
                    $req_name = $value;
                }
                if(strtolower($key) === 'requestor_employee_id'){
                    $req_emp_id = $value;
                }
                if(strtolower($key) === 'requestor_company'){
                    $req_company = $value;
                }
                if(strtolower($key) === 'requestor_department'){
                    $req_dept = $value;
                }
                if(strtolower($key) === 'company_affiliation'){
                    $comp_affiliate = $value;
                }
                if(strtolower($key) === 'name'){
                    $name = $value;
                }
                if(strtolower($key) === 'entertainment_reason'){
                    $ent_reason = $value;
                }
                if(strtolower($key) === 'potential'){
                    $potential = $value;
                }
                if(strtolower($key) === 'entertainment_type'){
                    $ent_type = $value;
                }
                if(strtolower($key) === 'entertainment_options'){
                    $ent_options = $value;
                }
                if(strtolower($key) === 'entertainment_date'){
                    $ent_date = $value;
                }
                if(strtolower($key) === 'entertainment_time'){
                    $ent_time = $value;
                }
                if(strtolower($key) === 'location'){
                    $loc = $value;
                }
                if(strtolower($key) === 'type'){
                    $type = $value;
                }
                if(strtolower($key) === 'sbu'){
                    $sbu = $value;
                }
                if(strtolower($key) === 'outlet'){
                    $outlet = $value;
                }
                if(strtolower($key) === 'benefit_entitlement'){
                    $benefit = $value;
                }
                if(strtolower($key) === 'no_guest'){
                    $no_guest = $value;
                }
                if(strtolower($key) === 'limit_estimated'){
                    $limit_est = $value;
                }
                if(strtolower($key) === 'approval'){
                    $approval  = $value;
                }
                if(strtolower($key) === 'status_approval'){
                    $statusApproval  = $value;
                }
                if(strtolower($key) === 'reason'){
                    $reason  = $value;
                }
                if(strtolower($key) === 'approval_level'){
                    $approvallevel  = $value;
                }
                if(strtolower($key) === 'fulfill_zoho'){
                    $fulfill_zoho  = $value;
                }
            }

            $jsonEncode = json_encode($data);
            $connection->beginTransaction();
            $formZohosql = $connection->table('dbo.ZOHO_CREATOR_RAW_DATA')->where('UID',trim($uid));
            $formZohoFinancesql = $connection->table('dbo.INT_FIN_APPR_LIST')->where('FORM_ID',trim($uid));
            if($uid !== ""){
                $pisahuid = explode('-',$uid);
                $countuid = count($pisahuid);
                for ($i=0; $i < $countuid-2; $i++) {
                    $typeform .= $pisahuid[$i].'-';
                }
            }
            if($formZohosql->exists()){
                $insDataFile = $connection->table('dbo.ZOHO_CREATOR_RAW_APPROVAL')
                        ->insert([
                                    'ID_FORM'=>$uid,
                                    'JSON_DATA'=>$jsonEncode,
                                    'APPROVAL'=>$approval
                                ]);
                if($formZohoFinancesql->exists()){
                    $insDatafinance = $connection->table('dbo.INT_FIN_APPR_LIST')
                    ->where('FORM_ID',$uid)
                    ->update([
                                'APPROVAL_LEVEL' => $approvallevel,
                                'LAST_APPROVAL_ID' => $req_emp_id,
                                'LAST_APPROVAL_DATE' => DB::raw('Getdate()'),
                                'STATUS_APPROVAL' => $approval,
                                'TYPE_FORM' =>($typeform === "" ? '' : substr($typeform,0,-1)),
                                'REASON' => $reason,
                                'USER_ZOHO' => 'Y'
                            ]);
                }else{
                    $insDatafinance = $connection->table('dbo.INT_FIN_APPR_LIST')
                    ->insert([
                                'FORM_ID' => $uid,
                                'APPROVAL_LEVEL' => $approvallevel,
                                'LAST_APPROVAL_ID' => $req_emp_id,
                                'LAST_APPROVAL_DATE' => DB::raw('Getdate()'),
                                'STATUS_APPROVAL' => $approval,
                                'TYPE_FORM' =>($typeform === "" ? '' : substr($typeform,0,-1)),
                                'REASON' => $reason,
                                'USER_ZOHO' => 'Y'
                            ]);
                }
                $insDatafinanceHis = $connection->table('dbo.INT_FIN_APPR_HISTORY')
                ->insert([
                            'FORM_ID' => $uid,
                            'APPROVAL_LEVEL' => $approvallevel,
                            'APPROVAL_ID' => $req_emp_id,
                            'APPROVAL_DATE' => DB::raw('Getdate()'),
                            'STATUS_APPROVAL' => $approval,
                            'TYPE_FORM' => $typeform,
                            'REASON' => $reason,
                            'USER_ZOHO' => 'Y'
                        ]);
            }else{
                $insData = $connection->table('dbo.ZOHO_CREATOR_RAW_DATA')
                ->insertGetId([
                            'JSON_ENCODE'=>$jsonEncode,
                            'TYPE'=>$type_form_name,
                            'INSERT_DATE'=>DB::raw('getdate()'),
                            'UID'=>$uid,
                            'EMPLOYEE_ID'=>$req_emp_id,
                            'TYPE_FORM'=>($typeform === "" ? '' : substr($typeform,0,-1)),
                            'FULLFILL_ZOHO'=>$fulfill_zoho
                        ]);
            }
            $connection->commit();
            $res['code'] = 200;
            $res['status'] = true;
            $res['message'] = "Success";
        }catch(\Exception $ex){
            $connection->rollback();
            $res['code'] = 500;
            $res['status'] = false;
            $res['message'] = "Err : " . $ex->getLine() . ' && ' . $ex->getMessage();
        }
        return $res;
    }
    public function insLog($data)
    {
        try{
            $insData = DB::connection('dbintranet')->table('dbo.TBL_LOG_ZOHO')
                    ->insert([
                                'LOG_REQ'=>json_encode($data['req']),
                                'INSERT_DATE'=>DB::raw('getdate()'),
                                'LOG_RES'=>json_encode($data['resp']),
                                'URL'=>$data['url']
                            ]);
            $res['status'] = true;
        }catch(\Exception $ex){
            $res['status'] = true;
            $res['message'] = $ex->getMessage();
        }
        return $res;
    }

    public function getHistoryApproval($request,$form_view,$approval_view = '')
    {
        $form_number = $request->input('form_number', 0);
        if(strpos($form_view, 'BUSINESS_PARTNER') > 0 || strpos($form_view, 'MATERIAL_MASTER') > 0 || strpos($form_view, 'RESERVATION') > 0){
            try {
                $query = DB::connection('dbintranet')
                ->select("
                    WITH DataEmployee AS (
                        SELECT EMPLOYEE_ID, EMPLOYEE_NAME, MIDJOB_TITLE_ID, TERRITORY_ID, SAP_PLANT_ID, COMPANY_CODE FROM VIEW_EMPLOYEE
                        WHERE EMPLOYEE_STATUS_ASSIGNMENT = 1
                    )
                    SELECT LEVEL_APPROVAL, TYPE_DESC, APPROVAL_ID, EMPLOYEE_NAME, APPROVAL_DATE, STATUS_APPROVAL, REASON,
                    (CASE WHEN ISNULL(APPROVER_NAME, '') = '' AND ISNULL(APPROVER_ALT_NAME, '') = '' 
                            THEN NULL
                         WHEN ISNULL(APPROVER_NAME, '') = '' AND APPROVER_ALT_NAME IS NOT NULL
                            THEN APPROVER_ALT_NAME
                         WHEN ISNULL(APPROVER_ALT_NAME, '') = '' AND APPROVER_NAME IS NOT NULL
                            THEN APPROVER_NAME
                         ELSE 
                            CONCAT(APPROVER_NAME,',', APPROVER_ALT_NAME)
                    END) AS APPROVER
                    FROM (
                        SELECT CAST(LEVEL_APPROVAL AS NVARCHAR(200)) AS LEVEL_APPROVAL, 
                        CAST(TYPE_DESC AS NVARCHAR(200)) AS TYPE_DESC, CAST(APPROVAL_ID AS NVARCHAR(200)) AS APPROVAL_ID, 
                        CAST(EMPLOYEE_NAME AS NVARCHAR(200)) AS EMPLOYEE_NAME, APPROVAL_DATE, STATUS_APPROVAL, CAST(REASON AS NVARCHAR(200)) AS REASON,
                        string_agg(APPROVER_EMPLOYEE,',') APPROVER_NAME, MAX(APPROVER_ALT_EMPLOYEE) AS APPROVER_ALT_NAME
                        FROM (
                            SELECT ROW_NUMBER() OVER (PARTITION BY LEVEL_APPROVAL order by LEVEL_APPROVAL DESC) AS ROW_OCCURS, A.*
                            , emp.EMPLOYEE_NAME AS APPROVER_EMPLOYEE
                            , em.EMPLOYEE_NAME AS APPROVER_ALT_EMPLOYEE
                            FROM (
                              SELECT C.LEVEL_APPROVAL
                              ,(CASE WHEN C.LEVEL_APPROVAL = '1' THEN F.APPROVAL_1_MIDJOB_ID
                              WHEN C.LEVEL_APPROVAL = '2' THEN F.APPROVAL_2_MIDJOB_ID
                              WHEN C.LEVEL_APPROVAL = '3' THEN F.APPROVAL_3_MIDJOB_ID
                              WHEN C.LEVEL_APPROVAL = '4' THEN F.APPROVAL_4_MIDJOB_ID
                              WHEN C.LEVEL_APPROVAL = '5' THEN F.APPROVAL_5_MIDJOB_ID
                              WHEN C.LEVEL_APPROVAL = '6' THEN F.APPROVAL_6_MIDJOB_ID
                              WHEN C.LEVEL_APPROVAL = '7' THEN F.APPROVAL_7_MIDJOB_ID
                              WHEN C.LEVEL_APPROVAL = '8' THEN F.APPROVAL_8_MIDJOB_ID
                              ELSE NULL 
                              END) AS APPROVAL_MIDJOB

                              ,(CASE WHEN C.LEVEL_APPROVAL = '1' THEN F.APPROVAL_1_TERRITORY_ID
                              WHEN C.LEVEL_APPROVAL = '2' THEN F.APPROVAL_2_TERRITORY_ID
                              WHEN C.LEVEL_APPROVAL = '3' THEN F.APPROVAL_3_TERRITORY_ID
                              WHEN C.LEVEL_APPROVAL = '4' THEN F.APPROVAL_4_TERRITORY_ID
                              WHEN C.LEVEL_APPROVAL = '5' THEN F.APPROVAL_5_TERRITORY_ID
                              WHEN C.LEVEL_APPROVAL = '6' THEN F.APPROVAL_6_TERRITORY_ID
                              WHEN C.LEVEL_APPROVAL = '7' THEN F.APPROVAL_7_TERRITORY_ID
                              WHEN C.LEVEL_APPROVAL = '8' THEN F.APPROVAL_8_TERRITORY_ID
                              ELSE NULL  
                              END) AS APPROVAL_TERRITORY

                              ,(CASE WHEN C.LEVEL_APPROVAL = '1' THEN F.APPROVAL_1_EMPLOYEE_ID
                              WHEN C.LEVEL_APPROVAL = '2' THEN F.APPROVAL_2_EMPLOYEE_ID
                              WHEN C.LEVEL_APPROVAL = '3' THEN F.APPROVAL_3_EMPLOYEE_ID
                              WHEN C.LEVEL_APPROVAL = '4' THEN F.APPROVAL_4_EMPLOYEE_ID
                              WHEN C.LEVEL_APPROVAL = '5' THEN F.APPROVAL_5_EMPLOYEE_ID
                              WHEN C.LEVEL_APPROVAL = '6' THEN F.APPROVAL_6_EMPLOYEE_ID
                              WHEN C.LEVEL_APPROVAL = '7' THEN F.APPROVAL_7_EMPLOYEE_ID
                              WHEN C.LEVEL_APPROVAL = '8' THEN F.APPROVAL_8_EMPLOYEE_ID
                              ELSE NULL 
                              END) AS APPROVAL_ALT_EMPLOYEE

                              ,C.TYPE_DESC , D.APPROVAL_ID,E.EMPLOYEE_NAME , D.APPROVAL_DATE, D.STATUS_APPROVAL, D.REASON 
                              from INT_FIN_APPR_LIST A right join $form_view B on B.UID = A.FORM_ID 
                              right join INT_FIN_APPR_ROLE C on C.FORM_NUMBER = B.TYPE_FORM 
                              left join INT_FIN_APPR_HISTORY D on D.FORM_ID = A.FORM_ID and D.APPROVAL_LEVEL = C.LEVEL_APPROVAL 
                              left join VIEW_EMPLOYEE E on E.EMPLOYEE_ID = D.APPROVAL_ID
                              left join $approval_view F on B.UID = F.UID
                              where B.UID = '$form_number'
                            ) A
                            LEFT JOIN DataEmployee emp ON A.APPROVAL_MIDJOB = emp.MIDJOB_TITLE_ID AND A.APPROVAL_TERRITORY = emp.TERRITORY_ID
                            LEFT JOIN DataEmployee em ON A.APPROVAL_ALT_EMPLOYEE = em.EMPLOYEE_ID
                        ) Source
                        GROUP BY CAST(LEVEL_APPROVAL AS NVARCHAR(200)), CAST(TYPE_DESC AS NVARCHAR(200)), 
                        CAST(APPROVAL_ID AS NVARCHAR(200)), CAST(EMPLOYEE_NAME AS NVARCHAR(200)), APPROVAL_DATE, STATUS_APPROVAL, 
                        CAST(REASON AS NVARCHAR(200))
                    ) C
                    ORDER BY LEVEL_APPROVAL ASC
                ");
            } catch(\Exception $e){
                $query = [];
                Log::error('GAGAL LOAD APPROVAL HISTORY | '.$form_view.' ERROR = '.(String)$e);
            }
        } else {
            /* Ini Query yg lama */
            $query = DB::connection('dbintranet')
            ->table(DB::raw('INT_FIN_APPR_LIST A'))
            ->rightJoin(DB::raw($form_view." B"), DB::raw('B.UID'), '=', DB::raw('A.FORM_ID'))
            ->rightJoin(DB::raw('INT_FIN_APPR_ROLE C'), DB::raw('C.FORM_NUMBER'), '=', DB::raw('B.TYPE_FORM'))
            ->leftJoin(DB::raw('INT_FIN_APPR_HISTORY D'), function ($join) {
                $join->on(DB::raw('D.FORM_ID'), '=', DB::raw('A.FORM_ID'))
                     ->where(DB::raw('D.APPROVAL_LEVEL'), '=', DB::raw('C.LEVEL_APPROVAL'));
            })
            ->leftJoin(DB::raw('VIEW_EMPLOYEE E'), DB::raw('E.EMPLOYEE_ID'), '=', DB::raw('D.APPROVAL_ID'))
            ->select(DB::raw('C.LEVEL_APPROVAL, C.TYPE_DESC , D.APPROVAL_ID,E.EMPLOYEE_NAME , D.APPROVAL_DATE, D.STATUS_APPROVAL, D.REASON'))
            ->where(DB::raw('B.UID'),$request->input('form_number'))
            ->get();
        }


        $result['success']=true;
        $record['code'] = '200';
        $record['message'] = 'Get All Data Success';
        $record['data'] = $query;

        return $record;
    }

    public function getHistoryApprovalWithLimit($request,$form_view)
    {

        DB::enableQueryLog();
        $query = DB::connection('dbintranet')
                ->select("
                SELECT * FROM (
                    SELECT * FROM
                        (select C.LEVEL_APPROVAL, C.TYPE_DESC , D.APPROVAL_ID,E.EMPLOYEE_NAME , D.APPROVAL_DATE, D.STATUS_APPROVAL, D.REASON,
                        L.PAXLIMIT_MINIMUM_PAX AS MINIMUM_PAX, L.PAXLIMIT_MAXIMUM_PAX AS MAXIMUM_PAX, L.PAXLIMIT_APPROVAL_MAXIMUM AS MAX_APPROVAL_LEVEL,
                        JSON_VALUE(CAST(B.JSON_ENCODE AS nvarchar(max)), '$.No_Guest') AS PAX
                        from INT_FIN_APPR_LIST A
                        right join ".$form_view." B on B.UID = A.FORM_ID
                        right join INT_FIN_APPR_ROLE C on C.FORM_NUMBER = B.TYPE_FORM
                        left join INT_FIN_APPR_HISTORY D on D.FORM_ID = A.FORM_ID and D.APPROVAL_LEVEL = C.LEVEL_APPROVAL
                        left join VIEW_EMPLOYEE E on E.EMPLOYEE_ID = D.APPROVAL_ID
                        left join INT_FORM_APPROVAL_PAX_LIMIT L on L.PAXLIMIT_COST_CENTER_ID = B.COST_CENTER_ID
                        where B.UID = '".$request->input('form_number')."' ) AS DATA
                    WHERE PAX >= MINIMUM_PAX AND PAX <= MAXIMUM_PAX
                ) AS DATA_2
                WHERE LEVEL_APPROVAL <= MAX_APPROVAL_LEVEL
                ");


        $result['success']=true;
        $record['code'] = '200';
        $record['message'] = 'Get All Data Success';
        $record['data'] = $query;
        // dd(DB::getQueryLog());

        return $record;
    }

    public function getHistoryApprovalCA($request, $form_view, $approval_view=null)
    {

        // breakdown dulu form CA nya requestor nya siapa
        $form_number=$request->input('form_number');
        $q_plant_requestor=DB::connection('dbintranet')
                            ->select("SELECT JSON_VALUE(CAST(A.JSON_ENCODE AS nvarchar(max)), '$.Requestor_Plant_ID') AS REQUESTOR_PLANT FROM INT_FIN_APPR_RAW_DATA A WHERE UID='$form_number'");
        $plant_requestor=isset($q_plant_requestor[0]->REQUESTOR_PLANT) ?  $q_plant_requestor[0]->REQUESTOR_PLANT : NULL;

        $config_form = DB::connection('dbintranet')
                        ->select("SELECT * FROM CONFIG_FORM_CASH_ADVANCE_PER_PLANT WHERE PLANT_ID ='$plant_requestor'");
        $extend_treshold = isset($config_form[0]->LIMIT_AMOUNT_TO_EXTEND_APPROVAL) ? $config_form[0]->LIMIT_AMOUNT_TO_EXTEND_APPROVAL : NULL;
        $min_approval_level = isset($config_form[0]->MINIMUM_APPROVAL_LEVEL) ? $config_form[0]->MINIMUM_APPROVAL_LEVEL : NULL;
        $max_approval_level = isset($config_form[0]->MAXIMUM_APPROVAL_LEVEL) ? $config_form[0]->MAXIMUM_APPROVAL_LEVEL : NULL;

        if(!empty($extend_treshold)){
            $query = DB::connection('dbintranet')
            ->select("
            SELECT * FROM (
                SELECT *,
                CASE WHEN GRAND_TOTAL > $extend_treshold THEN $max_approval_level
                ELSE $min_approval_level END AS MAX_APPROVAL_LEVEL
                FROM
                (
                    SELECT C.LEVEL_APPROVAL, C.TYPE_DESC , D.APPROVAL_ID,E.EMPLOYEE_NAME , D.APPROVAL_DATE, D.STATUS_APPROVAL, D.REASON,
                    JSON_VALUE(CAST(B.JSON_ENCODE AS nvarchar(max)), '$.grandTotal') AS GRAND_TOTAL
                    from INT_FIN_APPR_LIST A
                    right join ".$form_view." B on B.UID = A.FORM_ID
                    right join INT_FIN_APPR_ROLE C on C.FORM_NUMBER = B.TYPE_FORM
                    left join INT_FIN_APPR_HISTORY D on D.FORM_ID = A.FORM_ID and D.APPROVAL_LEVEL = C.LEVEL_APPROVAL
                    left join VIEW_EMPLOYEE E on E.EMPLOYEE_ID = D.APPROVAL_ID
                    where B.UID = '".$request->input('form_number')."'
                ) AS DATA
            ) AS DATA_2
            WHERE LEVEL_APPROVAL <= MAX_APPROVAL_LEVEL
            ");

        }else{

            $query = DB::connection('dbintranet')
            ->select("
            SELECT * FROM (
                SELECT *,
                CASE WHEN GRAND_TOTAL > 1000000 THEN 4
                ELSE 3 END AS MAX_APPROVAL_LEVEL
                FROM
                (
                    SELECT C.LEVEL_APPROVAL, C.TYPE_DESC , D.APPROVAL_ID,E.EMPLOYEE_NAME , D.APPROVAL_DATE, D.STATUS_APPROVAL, D.REASON,
                    JSON_VALUE(CAST(B.JSON_ENCODE AS nvarchar(max)), '$.grandTotal') AS GRAND_TOTAL
                    from INT_FIN_APPR_LIST A
                    right join ".$form_view." B on B.UID = A.FORM_ID
                    right join INT_FIN_APPR_ROLE C on C.FORM_NUMBER = B.TYPE_FORM
                    left join INT_FIN_APPR_HISTORY D on D.FORM_ID = A.FORM_ID and D.APPROVAL_LEVEL = C.LEVEL_APPROVAL
                    left join VIEW_EMPLOYEE E on E.EMPLOYEE_ID = D.APPROVAL_ID
                    where B.UID = '".$request->input('form_number')."'
                ) AS DATA
            ) AS DATA_2
            WHERE LEVEL_APPROVAL <= MAX_APPROVAL_LEVEL
            ");
        }


        $extend_treshold_settlement = isset($config_form[0]->FINAL_SETTLEMENT_AMOUNT_TRESHOLD) ? $config_form[0]->FINAL_SETTLEMENT_AMOUNT_TRESHOLD : NULL;
        $min_approval_level_settlement = isset($config_form[0]->FINAL_SETTLEMENT_MINIMUM_APPROVAL_LEVEL) ? $config_form[0]->FINAL_SETTLEMENT_MINIMUM_APPROVAL_LEVEL : NULL;
        $max_approval_level_settlement = isset($config_form[0]->FINAL_SETTLEMENT_MAXIMUM_APPROVAL_LEVEL) ? $config_form[0]->FINAL_SETTLEMENT_MAXIMUM_APPROVAL_LEVEL : NULL;

        $check_final_settlement_approval = DB::connection('dbintranet')
        ->select("
        SELECT * FROM (
            SELECT *,
            CASE WHEN GRAND_TOTAL > $extend_treshold_settlement THEN $max_approval_level_settlement
            ELSE $min_approval_level_settlement END AS MAX_APPROVAL_LEVEL
            FROM
            (
                SELECT C.LEVEL_APPROVAL, C.TYPE_DESC , D.APPROVAL_ID,E.EMPLOYEE_NAME , D.APPROVAL_DATE, D.STATUS_APPROVAL, D.REASON,
                JSON_VALUE(CAST(B.JSON_ENCODE AS nvarchar(max)), '$.final_under') AS GRAND_TOTAL
                from INT_FIN_APPR_LIST A
                right join dbo.VIEW_FORM_REQUEST_CASH_ADVANCE_EXT B on B.UID = A.FORM_ID
                right join INT_FIN_APPR_ROLE C on C.FORM_NUMBER = B.TYPE_FORM
                left join INT_FIN_APPR_HISTORY D on D.FORM_ID = A.FORM_ID and D.APPROVAL_LEVEL = C.LEVEL_APPROVAL
                left join VIEW_EMPLOYEE E on E.EMPLOYEE_ID = D.APPROVAL_ID
                left join dbo.INT_FORM_CASH_ADVANCE_PROPERTIES F ON B.UID = F.APPROVAL_EXTENDED_NUMBER
                where F.FORM_ID = '".$form_number."'
            ) AS DATA
        ) AS DATA_2
        WHERE LEVEL_APPROVAL <= MAX_APPROVAL_LEVEL
        ");

        $result['success']=true;
        $record['code'] = '200';
        $record['message'] = 'Get All Data Success';
        $record['data'] = $query;
        $record['data_settlement'] = $check_final_settlement_approval;
        // dd(DB::getQueryLog());

        return $record;
    }

    public function getHistoryApprovalCAExtended($form_number,$form_view, $approval_view=null)
    {

        // breakdown dulu form CA nya requestor nya siapa
        $q_plant_requestor=DB::connection('dbintranet')
                            ->select("SELECT JSON_VALUE(CAST(A.JSON_ENCODE AS nvarchar(max)), '$.Requestor_Plant_ID') AS REQUESTOR_PLANT FROM INT_FIN_APPR_RAW_DATA A WHERE UID='$form_number'");
        $plant_requestor=isset($q_plant_requestor[0]->REQUESTOR_PLANT) ?  $q_plant_requestor[0]->REQUESTOR_PLANT : NULL;

        $config_form = DB::connection('dbintranet')
                        ->select("SELECT * FROM CONFIG_FORM_CASH_ADVANCE_PER_PLANT WHERE PLANT_ID ='$plant_requestor'");
        $extend_treshold = isset($config_form[0]->FINAL_SETTLEMENT_AMOUNT_TRESHOLD) ? $config_form[0]->FINAL_SETTLEMENT_AMOUNT_TRESHOLD : NULL;
        $min_approval_level = isset($config_form[0]->FINAL_SETTLEMENT_MINIMUM_APPROVAL_LEVEL) ? $config_form[0]->FINAL_SETTLEMENT_MINIMUM_APPROVAL_LEVEL : NULL;
        $max_approval_level = isset($config_form[0]->FINAL_SETTLEMENT_MAXIMUM_APPROVAL_LEVEL) ? $config_form[0]->FINAL_SETTLEMENT_MAXIMUM_APPROVAL_LEVEL : NULL;

        $query = DB::connection('dbintranet')
        ->select("
        SELECT * FROM (
            SELECT *,
            CASE WHEN GRAND_TOTAL > $extend_treshold THEN $max_approval_level
            ELSE $min_approval_level END AS MAX_APPROVAL_LEVEL
            FROM
            (
                SELECT C.LEVEL_APPROVAL, C.TYPE_DESC , D.APPROVAL_ID,E.EMPLOYEE_NAME , D.APPROVAL_DATE, D.STATUS_APPROVAL, D.REASON,
                JSON_VALUE(CAST(B.JSON_ENCODE AS nvarchar(max)), '$.final_under') AS GRAND_TOTAL
                from INT_FIN_APPR_LIST A
                right join ".$form_view." B on B.UID = A.FORM_ID
                right join INT_FIN_APPR_ROLE C on C.FORM_NUMBER = B.TYPE_FORM
                left join INT_FIN_APPR_HISTORY D on D.FORM_ID = A.FORM_ID and D.APPROVAL_LEVEL = C.LEVEL_APPROVAL
                left join VIEW_EMPLOYEE E on E.EMPLOYEE_ID = D.APPROVAL_ID
                where B.UID = '".$form_number."'
            ) AS DATA
        ) AS DATA_2
        WHERE LEVEL_APPROVAL <= MAX_APPROVAL_LEVEL
        ");


        $result['success']=true;
        $record['code'] = '200';
        $record['message'] = 'Get All Data Success';
        $record['data'] = $query;
        // dd(DB::getQueryLog());

        return $record;
    }


}


