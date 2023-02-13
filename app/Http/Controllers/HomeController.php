<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\HumanResource\EmployeeModel;
use App\Models\UserModel;
use Log;
use Validator;
Use Cookie;

class HomeController extends Controller{
  use Traits\IntranetTrait;

  public function getTotalSummaryResort($collection){
    $today_occupancy = 0;
    $mtd_occupancy = 0;
    $ytd_occupancy = 0;
    $today_available = 0;
    $mtd_available = 0;
    $ytd_available = 0;
    $today_occ_pctg = 0;
    $mtd_occ_pctg = 0;
    $ytd_occ_pctg = 0;
    $today_unused = 0;
    $mtd_unused = 0;
    $ytd_unused = 0;
    $today_guest  = 0;
    $mtd_guest = 0;
    $ytd_guest = 0;
    $today_spa_guest  = 0;
    $mtd_spa_guest = 0;
    $ytd_spa_guest = 0;
    $today_room_revenue = 0;
    $mtd_room_revenue = 0;
    $ytd_room_revenue = 0;
    $today_adr = 0;
    $mtd_adr = 0;
    $ytd_adr = 0;
    $today_revpar = 0;
    $mtd_revpar = 0;
    $ytd_revpar = 0;
    $today_physical = 0;
    $mtd_physical = 0;
    $ytd_physical = 0;
    $today_fnb_revenue = 0;
    $mtd_fnb_revenue = 0;
    $ytd_fnb_revenue = 0;
    $today_spa_revenue = 0;
    $mtd_spa_revenue = 0;
    $ytd_spa_revenue = 0;
    $other_total_revenue = 0;
    $mtd_other_total_revenue = 0;
    $ytd_other_total_revenue = 0;
    $total_revenue = 0;
    $mtd_total_revenue = 0;
    $ytd_total_revenue = 0;

    $data_sum_total = collect($collection)->mapWithKeys(function($item, $key) use (
        &$today_available, &$mtd_available, &$ytd_available,
        &$today_occupancy, &$mtd_occupancy, &$ytd_occupancy,
        &$today_occ_pctg, &$mtd_occ_pctg, &$ytd_occ_pctg,
        &$today_unused, &$mtd_unused, &$ytd_unused,
        &$today_physical, &$mtd_physical, &$ytd_physical,
        &$today_guest, &$mtd_guest, &$ytd_guest,
        &$today_spa_guest, &$mtd_spa_guest, &$ytd_spa_guest,
        &$today_room_revenue, &$mtd_room_revenue, &$ytd_room_revenue,
        &$today_fnb_revenue, &$mtd_fnb_revenue, &$ytd_fnb_revenue,
        &$today_spa_revenue, &$mtd_spa_revenue, &$ytd_spa_revenue,
        &$other_total_revenue, &$mtd_other_total_revenue, &$ytd_other_total_revenue,
        &$total_revenue, &$mtd_total_revenue, &$ytd_total_revenue
    ){
        $today_available += isset($item['Today'][0]->TotalAvailability) ? $item['Today'][0]->TotalAvailability : 0;
        $today_occupancy += isset($item['Today'][0]->TotalOccupancy) ? $item['Today'][0]->TotalOccupancy : 0;
        $today_unused += isset($item['Today'][0]->UnUsedRoom) ? $item['Today'][0]->UnUsedRoom : 0;
        $today_physical += isset($item['Today'][0]->PhysicalRoom) ? $item['Today'][0]->PhysicalRoom : 0;

        $mtd_available += isset($item['MTD'][0]->TotalAvailability) ? $item['MTD'][0]->TotalAvailability : 0;
        $mtd_occupancy += isset($item['MTD'][0]->TotalOccupancy) ? $item['MTD'][0]->TotalOccupancy : 0;
        $mtd_unused += isset($item['MTD'][0]->UnUsedRoom) ? $item['MTD'][0]->UnUsedRoom : 0;
        $mtd_physical += isset($item['MTD'][0]->PhysicalRoom) ? $item['MTD'][0]->PhysicalRoom : 0;

        $ytd_available += isset($item['YTD'][0]->TotalAvailability) ? $item['YTD'][0]->TotalAvailability : 0;
        $ytd_occupancy += isset($item['YTD'][0]->TotalOccupancy) ? $item['YTD'][0]->TotalOccupancy : 0;
        $ytd_unused += isset($item['YTD'][0]->UnUsedRoom) ? $item['YTD'][0]->UnUsedRoom : 0;
        $ytd_physical += isset($item['YTD'][0]->PhysicalRoom) ? $item['YTD'][0]->PhysicalRoom : 0;

        $today_guest += isset($item['Today'][0]->FnBTotalGuest) ? $item['Today'][0]->FnBTotalGuest : 0;
        $mtd_guest += isset($item['MTD'][0]->FnBTotalGuest) ? $item['MTD'][0]->FnBTotalGuest : 0;
        $ytd_guest += isset($item['YTD'][0]->FnBTotalGuest) ? $item['YTD'][0]->FnBTotalGuest : 0;

        $today_spa_guest += isset($item['Today'][0]->SpaTotalGuest) ? $item['Today'][0]->SpaTotalGuest : 0;
        $mtd_spa_guest += isset($item['MTD'][0]->SpaTotalGuest) ? $item['MTD'][0]->SpaTotalGuest : 0;
        $ytd_spa_guest += isset($item['YTD'][0]->SpaTotalGuest) ? $item['YTD'][0]->SpaTotalGuest : 0;

        $today_room_revenue += isset($item['Today'][0]->TodayRevenue) ? $item['Today'][0]->TodayRevenue : 0;
        $mtd_room_revenue += isset($item['MTD'][0]->TodayRevenue) ? $item['MTD'][0]->TodayRevenue : 0;
        $ytd_room_revenue += isset($item['YTD'][0]->TodayRevenue) ? $item['YTD'][0]->TodayRevenue : 0;

        $today_fnb_revenue += isset($item['Today'][0]->FnBTotalRevenue) ? $item['Today'][0]->FnBTotalRevenue : 0;
        $mtd_fnb_revenue += isset($item['MTD'][0]->FnBTotalRevenue) ? $item['MTD'][0]->FnBTotalRevenue : 0;
        $ytd_fnb_revenue += isset($item['YTD'][0]->FnBTotalRevenue) ? $item['YTD'][0]->FnBTotalRevenue : 0;

        $today_spa_revenue += isset($item['Today'][0]->SpaTotalRevenue) ? $item['Today'][0]->SpaTotalRevenue : 0;
        $mtd_spa_revenue += isset($item['MTD'][0]->SpaTotalRevenue) ? $item['MTD'][0]->SpaTotalRevenue : 0;
        $ytd_spa_revenue += isset($item['YTD'][0]->SpaTotalRevenue) ? $item['YTD'][0]->SpaTotalRevenue : 0;

        $other_total_revenue += isset($item['Today'][0]->OtherTotalRevenue) ? $item['Today'][0]->OtherTotalRevenue : 0;
        $mtd_other_total_revenue += isset($item['MTD'][0]->OtherTotalRevenue) ? $item['MTD'][0]->OtherTotalRevenue : 0;
        $ytd_other_total_revenue += isset($item['YTD'][0]->OtherTotalRevenue) ? $item['YTD'][0]->OtherTotalRevenue : 0;

        $total_revenue += isset($item['Today'][0]->ResortTotalRevenue) ? $item['Today'][0]->ResortTotalRevenue : 0;
        $mtd_total_revenue += isset($item['MTD'][0]->ResortTotalRevenue) ? $item['MTD'][0]->ResortTotalRevenue : 0;
        $ytd_total_revenue += isset($item['YTD'][0]->ResortTotalRevenue) ? $item['YTD'][0]->ResortTotalRevenue : 0;
        return [];
    });

    if($today_unused > 0)
        $today_occ_pctg = ($today_occupancy / $today_unused) * 100;
    if($mtd_unused > 0)
        $mtd_occ_pctg = ($mtd_occupancy / $mtd_unused) * 100;
    if($ytd_unused > 0)
        $ytd_occ_pctg = ($ytd_occupancy / $ytd_unused) * 100;

    if($today_occupancy > 0)
        $today_adr = ($today_room_revenue / $today_occupancy);
    if($mtd_occupancy > 0)
        $mtd_adr = ($mtd_room_revenue / $mtd_occupancy);
    if($ytd_occupancy > 0)
        $ytd_adr = ($ytd_room_revenue / $ytd_occupancy);

    if($today_physical > 0)
        $today_revpar = ($today_room_revenue / $today_physical);
    if($mtd_physical > 0)
        $mtd_revpar = ($mtd_room_revenue / $mtd_physical);
    if($ytd_physical > 0)
        $ytd_revpar = ($ytd_room_revenue / $ytd_physical);

    $data['TOTAL_REVENUE_SUMMARY'] = [
        'TODAY_PHYSICAL'=>$today_physical,
        'MTD_PHYSICAL'=>$mtd_physical,
        'YTD_PHYSICAL'=>$ytd_physical,
        'TODAY_AVAILABLE'=>$today_available,
        'MTD_AVAILABLE'=>$mtd_available,
        'YTD_AVAILABLE'=>$ytd_available,
        'TODAY_OCCUPANCY'=>$today_occupancy,
        'MTD_OCCUPANCY'=>$mtd_occupancy,
        'YTD_OCCUPANCY'=>$ytd_occupancy,
        'TODAY_OCC_PCTG'=>$today_occ_pctg,
        'MTD_OCC_PCTG'=>$mtd_occ_pctg,
        'YTD_OCC_PCTG'=>$ytd_occ_pctg,
        'TODAY_GUEST'=>$today_guest,
        'MTD_GUEST'=>$mtd_guest,
        'YTD_GUEST'=>$ytd_guest,
        'TODAY_SPA_GUEST'=>$today_spa_guest,
        'MTD_SPA_GUEST'=>$mtd_spa_guest,
        'YTD_SPA_GUEST'=>$ytd_spa_guest,
        'TODAY_ROOM_REVENUE'=>$today_room_revenue,
        'MTD_ROOM_REVENUE'=>$mtd_room_revenue,
        'YTD_ROOM_REVENUE'=>$ytd_room_revenue,
        'TODAY_ADR'=>$today_adr,
        'MTD_ADR'=>$mtd_adr,
        'YTD_ADR'=>$ytd_adr,
        'TODAY_REVPAR'=>$today_revpar,
        'MTD_REVPAR'=>$mtd_revpar,
        'YTD_REVPAR'=>$ytd_revpar,
        'TODAY_FNB_REVENUE'=>$today_fnb_revenue,
        'MTD_FNB_REVENUE'=>$mtd_fnb_revenue,
        'YTD_FNB_REVENUE'=>$ytd_fnb_revenue,
        'TODAY_SPA_REVENUE'=>$today_spa_revenue,
        'MTD_SPA_REVENUE'=>$mtd_spa_revenue,
        'YTD_SPA_REVENUE'=>$ytd_spa_revenue,
        'OTHER_TOTAL_REVENUE'=>$other_total_revenue,
        'MTD_OTHER_TOTAL_REVENUE'=>$mtd_other_total_revenue,
        'YTD_OTHER_TOTAL_REVENUE'=>$ytd_other_total_revenue,
        'TODAY_TOTAL_REVENUE'=>$total_revenue,
        'MTD_TOTAL_REVENUE'=>$mtd_total_revenue,
        'YTD_TOTAL_REVENUE'=>$ytd_total_revenue
    ];

    return $data['TOTAL_REVENUE_SUMMARY'];
  }

  public function index(Request $request){
    // Exclude Ayana Residence from opera data
    $exclude_sub_resort = ['AYZR'];

    // Mulai cek assignment user login
    if(empty(Session::get('assignment')[0])){
        $company_code="SYSADMIN";
        $plant="SYSADMIN";
        $division="SYSADMIN";
        $department="SYSADMIN";
    }else{
        $division=Session::get('assignment')[0]->DIVISION_NAME;
        $department=Session::get('assignment')[0]->DEPARTMENT_NAME;
        $company_code=Session::get('assignment')[0]->COMPANY_CODE;
        $plant=Session::get('assignment')[0]->SAP_PLANT_ID;
    }

    /**
     * Karena kebutuhan date berbeda tiap tipe report
     * Misal (Forecast memakai tgl hari ini, kemudian vip memakai tgl h+1)
     * Maka dari itu dipisah variable datenya
     */
    $date=date('Y-m-d');
    $date_resort_vip_temporary = date("Y-m-d", strtotime('-1 day', strtotime($date)));
    $date_resort_temporary = date("Y-m-d", strtotime('-1 day', strtotime($date)) );
    $date_forecast_temporary = date("Y-m-d", strtotime ($date) );
    if($request->get('business_date')){
        try {
            $date = date('Y-m-d', strtotime($request->get('business_date')));
            if($date == date('Y-m-d', strtotime('1970-01-01')))
                $date = date('Y-m-d');
        } catch(\Exception $e){
            $date = date('Y-m-d');
        }
        $date_resort_vip_temporary = date("Y-m-d", strtotime($date));
        $date_resort_temporary = date("Y-m-d", strtotime($date));
        $date_forecast_temporary = date("Y-m-d", strtotime($date));
    }

    $date_start=date('Y-m-01', strtotime($date));
    $report=(!empty($request->get('report')))? $request->get('report') : NULL;
    $resort=(!empty($request->get('resort')))? $request->get('resort') : '';

    $data=array(
        'company_code'=>$company_code,
        'plant'=>$plant,
        'division'=>$division,
        'department'=>$department,
        'resort'=>$resort,
        'report'=>$report,
    );
    $data['date_resort'] = $date_resort_temporary;
    $data['date_forecast'] = $date_forecast_temporary;
    $data['date_vip_details'] = $date_resort_vip_temporary;
    $data['date_fnb'] = $date;

    /**
     * All Request from Vue
     * or Application JSON, Async
     * goes here below
     */
    if(self::wantsJson($request)){
        try {
            $report=(!empty($request->get('report')))? $request->get('report') : NULL;
            /**
             * 14 Days Forecast
             * Room Summary
             */
            if($request->get('report') && $request->get('report') == '14_DAYS_ROOM_FORECAST_SUMMARY'){
                try {
                    $forecast = DB::connection('dbayana-dw')
                    // ->select('SET NOCOUNT ON;EXEC AyanaDw..usp_RptRoomForecastSummary_GetDataV2 ?,?', array($date_forecast_temporary, $resort));
                    ->select('SET NOCOUNT ON;EXEC AyanaDw..usp_RptRoomForecastSummary_GetDataV3 ?,?', array($date_forecast_temporary, $resort));
                    $forecast = collect($forecast)->filter(function($item, $key) use ($exclude_sub_resort){
                        return in_array($item->SubResort, $exclude_sub_resort) == false;
                    })
                    // ubah jika nama company Ayana Resort / Ayana Bali -> Ayana Rimba agar mewakili semua Property
                    ->map(function($item, $key){
                        if(strtoupper($item->PROPERTYNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->PROPERTYNAME) == 'AYANA BALI')
                             $item->CompanyName = 'Ayana Rimba Bali';
                        else
                             $item->CompanyName = ucwords(strtolower($item->PROPERTYNAME));
                        $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->PROPERTYNAME;
                        return $item;
                    })
                    ->groupBy(['COMPANY_CONCAT','SUB_RESORT_NAME'])
                    ->mapWithKeys(function($item, $key) use ($date_forecast_temporary){
                        $company_code = explode('-', $key);
                        $company_code = isset($company_code[0]) ? trim($company_code[0]) : 'ALL';
                        try {
                            $cek_total_all = DB::connection('dbayana-dw')
                            ->select('SET NOCOUNT ON;EXEC AyanaDw..usp_RptRoomForecastSummary_GetDataV2 ?,?', array($date_forecast_temporary, $company_code));
                        } catch(\Exception $e){
                            $cek_total_all = [];
                        }
                        return [$key=>array('DATA_SUBRESORT'=>$item->toArray(), 'DATA_TOTAL'=>$cek_total_all)];
                    })
                    ->toArray();
                    $data['forecast_7_days_rooms'] = $forecast;
                } catch(\Throwable $e){
                    Log::error('Error in Dashboard 14 Forecast Room Summary | Error messages : '. $e->getMessage());
                    $data['forecast_7_days_rooms'] = [];
                }
                $rawHtmlForecast = view('pages.dashboards.part.14_days_room_forecast_summary', ['data'=>$data])->render();
                return $rawHtmlForecast;
            }
            /**
             * END 14 Days Forecast
             * Room Summary
             */
            
            /**
             * Resort Summary
             */
            else if($request->get('report') && $request->get('report') == 'RESORT_SUMMARY'){
                try {
                    $resort_summary = DB::connection('dbayana-dw')
                    ->select('EXEC AyanaDw..usp_RptResortSummaryNew_GetDataV2 ?,?', array($date_resort_temporary, $resort));

                    $resort_summary = collect($resort_summary)->filter(function($item, $key) use ($exclude_sub_resort){
                        return in_array($item->SubCompanyCode, $exclude_sub_resort) == false;
                    })
                    // ubah jika nama company Ayana Resort / Ayana Bali -> Ayana Rimba agar mewakili semua Property
                    ->map(function($item, $key){
                        if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
                             $item->CompanyName = 'Ayana Rimba Bali';
                        else
                             $item->CompanyName = ucwords(strtolower($item->CompanyName));
                        $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
                        return $item;
                    })
                    ->groupBy(['COMPANY_CONCAT','SubCompanyName', 'Type'])
                    ->toArray();

                    $obj_var = $this;
                    $resort_summary = collect($resort_summary)->mapWithKeys(function($item, $key) use ($obj_var){
                        $get_summary = $obj_var->getTotalSummaryResort($item);
                        return [$key=>array("PROPERTY"=>$item, 'TOTAL_SUMMARY'=>$get_summary)];
                    })->toArray();

                    $data['resort_summary'] = $resort_summary;
                } catch(\Throwable $e){
                    Log::error('Error in Dashboard Resort Summary | Error messages : '. $e->getMessage());
                    $data['resort_summary'] = [];
                }
                $rawHtmlResortSummary = view('pages.dashboards.part.resort_summary', ['data'=>$data])->render();
                return $rawHtmlResortSummary;
            }
            /**
             * END Resort Summary
             */
            
            /**
             * 7 Days
             * Breakfast Forecast
             */
            else if($request->get('report') && $request->get('report') == '7_DAYS_BREAKFAST_FORECAST'){
                try {
                    $filter_bf_resort=(!empty($resort))? "AND RESORT ='".$resort."'" : "";
                    $data['7days_breakfast']=DB::connection('dbayana-stg')
                    ->select("
                        SELECT ADULT.BREAKFAST_OUTLET,
                        ADULT.DAY_1 AS DAY1_ADULT, CHILDREN.DAY_1 AS DAY1_CHILDREN,
                        ADULT.DAY_2 AS DAY2_ADULT, CHILDREN.DAY_2 AS DAY2_CHILDREN,
                        ADULT.DAY_3 AS DAY3_ADULT, CHILDREN.DAY_3 AS DAY3_CHILDREN,
                        ADULT.DAY_4 AS DAY4_ADULT, CHILDREN.DAY_4 AS DAY4_CHILDREN,
                        ADULT.DAY_5 AS DAY5_ADULT, CHILDREN.DAY_5 AS DAY5_CHILDREN,
                        ADULT.DAY_6 AS DAY6_ADULT, CHILDREN.DAY_6 AS DAY6_CHILDREN,
                        ADULT.DAY_7 AS DAY7_ADULT, CHILDREN.DAY_7 AS DAY7_CHILDREN
                        FROM(
                            SELECT BREAKFAST_OUTLET, SUM([DAY 1]) AS DAY_1, SUM([DAY 2]) AS DAY_2, SUM([DAY 3]) AS DAY_3, SUM([DAY 4]) AS DAY_4, SUM([DAY 5]) AS DAY_5, SUM([DAY 6]) AS DAY_6, SUM([DAY 7]) AS DAY_7
                            FROM (
                                SELECT * FROM BreakfastForecast WHERE QUERY_DATE = '".$date_forecast_temporary."' ".$filter_bf_resort."
                            ) AS SourceTable
                            PIVOT(
                                AVG(SourceTable.PKG_ADULTS)
                                FOR SourceTable.DAY_NO IN ([DAY 1], [DAY 2], [DAY 3], [DAY 4], [DAY 5], [DAY 6], [DAY 7])
                            ) AS PivotTable
                            GROUP BY BREAKFAST_OUTLET
                        ) AS ADULT,
                        (
                            SELECT BREAKFAST_OUTLET, SUM([DAY 1]) AS DAY_1, SUM([DAY 2]) AS DAY_2, SUM([DAY 3]) AS DAY_3, SUM([DAY 4]) AS DAY_4, SUM([DAY 5]) AS DAY_5, SUM([DAY 6]) AS DAY_6, SUM([DAY 7]) AS DAY_7
                            FROM (
                                SELECT * FROM BreakfastForecast WHERE QUERY_DATE = '".$date_forecast_temporary."' ".$filter_bf_resort."
                            ) AS SourceTable
                            PIVOT(
                                AVG(SourceTable.PKG_CHILDREN)
                                FOR SourceTable.DAY_NO IN ([DAY 1], [DAY 2], [DAY 3], [DAY 4], [DAY 5], [DAY 6], [DAY 7])
                            ) AS PivotTable
                            GROUP BY BREAKFAST_OUTLET
                        ) AS CHILDREN
                        WHERE ADULT.BREAKFAST_OUTLET = CHILDREN.BREAKFAST_OUTLET
                    ");
                } catch(\Throwable $e){ 
                    Log::error('Error in Dashboard Breakfast Forecast | Error messages : '. $e->getMessage());
                    $data['7days_breakfast'] = []; 
                }
                $rawHtml7DaysForecastBreakfast = view('pages.dashboards.part.7_days_breakfast_forecast', ['data'=>$data])->render();
                return $rawHtml7DaysForecastBreakfast;
            }
            /**
             * END 7 Days
             * Breakfast Forecast
             */
            
            /**
             * Top Most Selling 
             * FNB Item
             */
            else if($request->get('report') && $request->get('report') == 'TOP_MOST_SELLING_FNB_ITEM'){
                try {
                    if(strtotime($date) < strtotime(date('Y-m-d'))) {
                        $data['top5_food']=DB::connection('dbayana-stg')
                        ->select("
                            SELECT TOP 5 * FROM
                            (
                                SELECT SAPMATERIALCODE, MENUITEMNAME,
                                SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' AND MAJORGROUPNAME IN ('MID MENU FOOD','MID FOOD') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID' THEN (ITEMREVENUE+TAX1TOTAL) ELSE 0 END) AS TODAY_REV,
                                SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' AND MAJORGROUPNAME IN ('MID MENU FOOD','MID FOOD') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID' THEN QUANTITY ELSE 0 END) AS TODAY_QTY,
                                SUM((ITEMREVENUE+TAX1TOTAL)) AS MTD_REV, SUM(QUANTITY) AS MTD_QTY
                                FROM (
                                    SELECT CLOSEBUSINESSDATE, QUANTITY, SAPMATERIALCODE, MENUITEMNAME, ITEMREVENUE, TAX1TOTAL, MAJORGROUPNAME, STATUSINFO, FAMILYGROUPID FROM dbo.CheckLineItemHist WHERE CLOSEBUSINESSDATE BETWEEN '$date_start' AND '$date'
                                ) DATA
                                WHERE MAJORGROUPNAME IN ('MID MENU FOOD','MID FOOD') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID'
                                GROUP BY SAPMATERIALCODE, MENUITEMNAME
                            ) DATA
                            WHERE TODAY_REV > 0
                            ORDER BY TODAY_QTY DESC
                        ");

                        $data['top5_beverage']=DB::connection('dbayana-stg')
                        ->select("
                            SELECT TOP 5 * FROM
                            (
                                SELECT SAPMATERIALCODE, MENUITEMNAME,
                                SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' AND MAJORGROUPNAME IN ('MID MENU BEVERAGE','MID BEVERAGE') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID' THEN (ITEMREVENUE+TAX1TOTAL) ELSE 0 END) AS TODAY_REV,
                                SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' AND MAJORGROUPNAME IN ('MID MENU BEVERAGE','MID BEVERAGE') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID' THEN QUANTITY ELSE 0 END) AS TODAY_QTY,
                                SUM((ITEMREVENUE+TAX1TOTAL)) AS MTD_REV, SUM(QUANTITY) AS MTD_QTY
                                FROM (
                                    SELECT CLOSEBUSINESSDATE, QUANTITY, SAPMATERIALCODE, MENUITEMNAME, ITEMREVENUE, TAX1TOTAL, MAJORGROUPNAME, STATUSINFO, FAMILYGROUPID FROM dbo.CheckLineItemHist WHERE CLOSEBUSINESSDATE BETWEEN '$date_start' AND '$date'
                                ) DATA
                                WHERE MAJORGROUPNAME IN ('MID MENU BEVERAGE','MID BEVERAGE') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID'
                                GROUP BY SAPMATERIALCODE, MENUITEMNAME
                            ) DATA
                            WHERE TODAY_REV > 0
                            ORDER BY TODAY_QTY DESC
                        ");
                    } else {
                        $data['top5_food']=DB::connection('dbayana-stg')
                        ->select("
                            WITH DataSourceFood AS (
                                SELECT CLOSEBUSINESSDATE, QUANTITY, SAPMATERIALCODE, MENUITEMNAME, ITEMREVENUE, TAX1TOTAL, MAJORGROUPNAME, STATUSINFO, FAMILYGROUPID FROM dbo.CheckLineItemToday WHERE CLOSEBUSINESSDATE BETWEEN '$date_start' AND '$date' AND MAJORGROUPNAME IN ('MID MENU FOOD','MID FOOD') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID'
                                UNION ALL
                                SELECT CLOSEBUSINESSDATE, QUANTITY, SAPMATERIALCODE, MENUITEMNAME, ITEMREVENUE, TAX1TOTAL, MAJORGROUPNAME, STATUSINFO, FAMILYGROUPID FROM dbo.CheckLineItemHist WHERE CLOSEBUSINESSDATE BETWEEN '$date_start' AND '$date' AND MAJORGROUPNAME IN ('MID MENU FOOD','MID FOOD') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID'

                            ),
                            DataFixFood AS (
                                SELECT SAPMATERIALCODE, MENUITEMNAME,
                                SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' THEN (ITEMREVENUE+TAX1TOTAL) ELSE 0 END) AS TODAY_REV,
                                SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' THEN QUANTITY ELSE 0 END) AS TODAY_QTY,
                                SUM((ITEMREVENUE+TAX1TOTAL)) AS MTD_REV, SUM(QUANTITY) AS MTD_QTY
                                FROM DataSourceFood
                                GROUP BY SAPMATERIALCODE, MENUITEMNAME
                                HAVING SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' THEN (ITEMREVENUE+TAX1TOTAL) ELSE 0 END) > 0
                            )
                            SELECT TOP 5 * FROM DataFixFood
                            ORDER BY TODAY_QTY DESC
                        ");

                        $data['top5_beverage']=DB::connection('dbayana-stg')
                        ->select("
                            WITH DataSourceBeverage AS (
                                SELECT CLOSEBUSINESSDATE, QUANTITY, SAPMATERIALCODE, MENUITEMNAME, ITEMREVENUE, TAX1TOTAL, MAJORGROUPNAME, STATUSINFO, FAMILYGROUPID FROM dbo.CheckLineItemToday WHERE CLOSEBUSINESSDATE BETWEEN '$date_start' AND '$date' AND MAJORGROUPNAME IN ('MID MENU BEVERAGE','MID BEVERAGE') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID'
                                UNION ALL
                                SELECT CLOSEBUSINESSDATE, QUANTITY, SAPMATERIALCODE, MENUITEMNAME, ITEMREVENUE, TAX1TOTAL, MAJORGROUPNAME, STATUSINFO, FAMILYGROUPID FROM dbo.CheckLineItemHist WHERE CLOSEBUSINESSDATE BETWEEN '$date_start' AND '$date' AND MAJORGROUPNAME IN ('MID MENU BEVERAGE','MID BEVERAGE') AND FAMILYGROUPID NOT IN ('14094') AND STATUSINFO!='VOID'
                            ),
                            DataFixBeverage AS (
                                SELECT SAPMATERIALCODE, MENUITEMNAME,
                                SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' THEN (ITEMREVENUE+TAX1TOTAL) ELSE 0 END) AS TODAY_REV,
                                SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' THEN QUANTITY ELSE 0 END) AS TODAY_QTY,
                                SUM((ITEMREVENUE+TAX1TOTAL)) AS MTD_REV, SUM(QUANTITY) AS MTD_QTY
                                FROM DataSourceBeverage
                                GROUP BY SAPMATERIALCODE, MENUITEMNAME
                                HAVING SUM(CASE WHEN FORMAT(CLOSEBUSINESSDATE, 'yyyy-MM-dd') = '$date' THEN (ITEMREVENUE+TAX1TOTAL) ELSE 0 END) > 0
                            )
                            SELECT TOP 5 * FROM DataFixBeverage
                            ORDER BY TODAY_QTY DESC
                        ");
                    }
                } catch(\Throwable $e){
                    Log::error('Error in Dashboard Top Most Selling FNB Item | Error messages : '. $e->getMessage());
                    $data['top5_food'] = [];
                    $data['top5_beverage'] = [];
                }
                $rawHtmlTopMostSellingFNBItem = view('pages.dashboards.part.top_most_selling_fnb_item', ['data'=>$data])->render();
                return $rawHtmlTopMostSellingFNBItem;
            }
            /**
             * END Top Most Selling 
             * FNB Item
             */
            
            /**
             * VIP Guest List 
             */
            else if($request->get('report') && $request->get('report') == 'VIP_GUEST_LIST'){
                try{
                    $guestVIP_details = DB::connection('dbayana-dw')
                    ->select('EXEC AyanaDw..usp_RptVIPGuestList_GetData ?,?', array($date_resort_vip_temporary, $resort));
                    $data['guestVIP_details'] = $guestVIP_details;
                } catch(\Throwable $e){ 
                    Log::error('Error in Dashboard VIP Guest | Error messages : '. $e->getMessage());
                    $data['guestVIP_details'] = []; 
                }
                $rawHtmlVIPGuest = view('pages.dashboards.part.vip_guest', ['data'=>$data])->render();
                return $rawHtmlVIPGuest;
            }
            /**
             * END VIP Guest List 
             */

            /**
             * Cancellation Summary 
             */
            else if($request->get('report') && $request->get('report') == 'CANCELLATION_SUMMARY'){
                try{
                    $cancellation_summary = DB::connection('dbayana-dw')
                    ->select('EXEC AyanaDw..usp_RptCancellationResortSummary_GetData ?,?', array($date_resort_temporary, $resort));
                    $data['cancellation_summary'] = $cancellation_summary;
                } catch(\Exception $e){
                    Log::error('Error in Dashboard Cancellation Summary | Error messages : '. $e->getMessage()); 
                    $data['cancellation_summary'] = []; 
                }
                $rawHtmlCancellationSummary = view('pages.dashboards.part.cancellation_summary', ['data'=>$data])->render();
                return $rawHtmlCancellationSummary;
            }
            /**
             * END Cancellation Summary 
             */

            /**
             * Cancellation MTD
             */
            else if($request->get('report') && $request->get('report') == 'CANCELLATION_MTD'){
                try{
                    $cancellation_mtd_details = DB::connection('dbayana-dw')
                    ->select('EXEC AyanaDw..usp_RptCancellationDetailsMTD_GetData ?,?', array($date_resort_temporary, $resort));
                    $data['cancellation_mtd_details'] = $cancellation_mtd_details;
                } catch(\Exception $e){
                    Log::error('Error in Dashboard Cancellation MTD | Error messages : '. $e->getMessage());
                    $data['cancellation_summary'] = []; 
                }
                $rawHtmlCancellationMTD = view('pages.dashboards.part.cancellation_mtd', ['data'=>$data])->render();
                return $rawHtmlCancellationMTD;
            }
            /**
             * END Cancellation MTD 
             */
            
            /**
             * PNB1 Revenue
             * Ayana Cruises
             */
            else if($request->get('report') && $request->get('report') == 'PNB1_REVENUE'){
                try{
                    $pnb1_revenue = DB::connection('dbayana-dw')
                    ->select('EXEC usp_RptPnbRevenue_GetData ?', array($date_resort_temporary));
                    $data['pnb1_revenue'] = $pnb1_revenue;
                } catch(\Exception $e){
                    Log::error('Error in Dashboard PNB1 Revenue | Error messages : '. $e->getMessage());
                    $data['pnb1_revenue'] = []; 
                }
                $rawHtmlPNB1Revenue = view('pages.dashboards.part.pnb1_revenue', ['data'=>$data])->render();
                return $rawHtmlPNB1Revenue;
            }
            /**
             * END PNB1 Revenue
             * Ayana Cruises
             */
            
            // If no data available
            // or parameter is set
            // return default
            else {
                return '<h6 class="text-center text-danger">Error fetching data, no parameter is set.</h6>';
            }
        } catch(\Exception $e){
            Log::error('Error in Dashboard | Error messages : '. $e->getMessage());
            return response()->json([
                'status'=> 'error',
                'message'=> $e->getMessage(),
                'code'=> 500
            ], 500);
        }

        
    }

    // $is_production = config('intranet.is_production');
    // if($is_production)
    //     return view('pages.dashboards.index_fixed', ['data' =>$data]);
    // else
    return view('pages.dashboards.index_mixed', ['data' =>$data]);
        
  }

  public function credit_card_validator(){
    $data=[];
    return view('pages.dashboards.credit_card_validator', ['data' =>$data]);
  }

  public function jotform(){
    $data = [];
    return view('pages.dashboards.jotform_capex', ['data'=>$data]);
  }

  public function jotform_table_capex(){
    $data = [];
    return view('pages.dashboards.jotform_table_capex', ['data'=>$data]);
  }
}




