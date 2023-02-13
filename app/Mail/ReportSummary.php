<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReportSummary extends Mailable
{
    use Queueable, SerializesModels;
    public $show_company = ['KMS1', 'PPC1', 'WKK1', 'PAD1'];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {
        try {
            $date = date('Y-m-d');
            $data['date'] = date('Y-m-d',strtotime ( '-1 day' , strtotime ($date) ));
            if($request->get('date') && !empty($request->get('date'))){
                try {
                    $date = date('Y-m-d', strtotime($request->get('date')));
                    if($date == date('Y-m-d', strtotime('1970-01-01')))
                        $data['date'] = date('Y-m-d');
                    else 
                        $data['date'] = $date;

                } catch(\Exception $e){
                    $data['date'] = date('Y-m-d');
                }
            }

            // // By Summary
            // $data['summary'] = DB::connection('dbayana-dw')
            // // ->select("EXEC dbo.usp_RptRevenueResortEmail ?", array($data['date']));
            // ->select("EXEC dbo.usp_RptRevenueResortEmailSub ?", array($data['date']));
            // if(count($data['summary'])){
            //     $data['summary'] = collect($data['summary'])
            //     ->reject(function ($dataquery) {
            //         // Exclude selain KMS1 dan PPC1
            //         return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1';
            //     })
            //     ->filter(function($dataquery){
            //         $exclude_list = ['AYZR', 'KMS1'];
            //         return in_array($dataquery->SubCompanyCode, $exclude_list) == false;
            //     })
            //     ->map(function($item, $key){
            //         if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
            //              $item->CompanyName = 'Ayana Rimba Bali';
            //         else 
            //              $item->CompanyName = ucwords(strtolower($item->CompanyName));
            //         $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
            //         return $item;
            //     })
            //     ->groupBy(['CompanyName', 'SubCompanyName', 'Type'])->toArray();
            //     dd($data);
            //     $today_occupancy = 0;
            //     $mtd_occupancy = 0;
            //     $ytd_occupancy = 0;
            //     $today_occ_pctg = 0;
            //     $mtd_occ_pctg = 0;
            //     $ytd_occ_pctg = 0;
            //     $today_unused = 0;
            //     $mtd_unused = 0;
            //     $ytd_unused = 0;
            //     $today_guest  = 0;
            //     $mtd_guest = 0;
            //     $ytd_guest = 0;
            //     $today_room_revenue = 0;
            //     $mtd_room_revenue = 0;
            //     $ytd_room_revenue = 0;
            //     $today_fnb_revenue = 0;
            //     $mtd_fnb_revenue = 0;
            //     $ytd_fnb_revenue = 0;
            //     $total_revenue = 0;
            //     $mtd_total_revenue = 0;
            //     $ytd_total_revenue = 0;

            //     $data_sum_total = collect($data['summary'])->mapWithKeys(function($item, $key) use (
            //         &$today_occupancy, &$mtd_occupancy, &$ytd_occupancy,
            //         &$today_occ_pctg, &$mtd_occ_pctg, &$ytd_occ_pctg,
            //         &$today_unused, &$mtd_unused, &$ytd_unused,
            //         &$today_guest, &$mtd_guest, &$ytd_guest,
            //         &$today_room_revenue, &$mtd_room_revenue, &$ytd_room_revenue,
            //         &$today_fnb_revenue, &$mtd_fnb_revenue, &$ytd_fnb_revenue,
            //         &$total_revenue, &$mtd_total_revenue, &$ytd_total_revenue
            //     ){
            //         $today_occupancy += isset($item['Today'][0]->TotalOccupancy) ? $item['Today'][0]->TotalOccupancy : 0;
            //         $today_unused += isset($item['Today'][0]->UnUsedRoom) ? $item['Today'][0]->UnUsedRoom : 0;

            //         $mtd_occupancy += isset($item['MTD'][0]->TotalOccupancy) ? $item['MTD'][0]->TotalOccupancy : 0;
            //         $mtd_unused += isset($item['MTD'][0]->UnUsedRoom) ? $item['MTD'][0]->UnUsedRoom : 0;

            //         $ytd_occupancy += isset($item['YTD'][0]->TotalOccupancy) ? $item['YTD'][0]->TotalOccupancy : 0;
            //         $ytd_unused += isset($item['YTD'][0]->UnUsedRoom) ? $item['YTD'][0]->UnUsedRoom : 0;

            //         $today_guest += isset($item['Today'][0]->FnBTotalGuest) ? $item['Today'][0]->FnBTotalGuest : 0;
            //         $mtd_guest += isset($item['MTD'][0]->FnBTotalGuest) ? $item['MTD'][0]->FnBTotalGuest : 0;
            //         $ytd_guest += isset($item['YTD'][0]->FnBTotalGuest) ? $item['YTD'][0]->FnBTotalGuest : 0;

            //         $today_room_revenue += isset($item['Today'][0]->TodayRevenue) ? $item['Today'][0]->TodayRevenue : 0;
            //         $mtd_room_revenue += isset($item['MTD'][0]->TodayRevenue) ? $item['MTD'][0]->TodayRevenue : 0;
            //         $ytd_room_revenue += isset($item['YTD'][0]->TodayRevenue) ? $item['YTD'][0]->TodayRevenue : 0;

            //         $today_fnb_revenue += isset($item['Today'][0]->FnBTotalRevenue) ? $item['Today'][0]->FnBTotalRevenue : 0;
            //         $mtd_fnb_revenue += isset($item['MTD'][0]->FnBTotalRevenue) ? $item['MTD'][0]->FnBTotalRevenue : 0;
            //         $ytd_fnb_revenue += isset($item['YTD'][0]->FnBTotalRevenue) ? $item['YTD'][0]->FnBTotalRevenue : 0;

            //         $total_revenue += isset($item['Today'][0]->ResortTotalRevenue) ? $item['Today'][0]->ResortTotalRevenue : 0;
            //         $mtd_total_revenue += isset($item['MTD'][0]->ResortTotalRevenue) ? $item['MTD'][0]->ResortTotalRevenue : 0;
            //         $ytd_total_revenue += isset($item['YTD'][0]->ResortTotalRevenue) ? $item['YTD'][0]->ResortTotalRevenue : 0;
            //         return [];
            //     });

            //     if($today_unused > 0)
            //         $today_occ_pctg = ($today_occupancy / $today_unused) * 100;
            //     if($mtd_unused > 0)
            //         $mtd_occ_pctg = ($mtd_occupancy / $mtd_unused) * 100;
            //     if($ytd_unused > 0)
            //         $ytd_occ_pctg = ($ytd_occupancy / $ytd_unused) * 100;
                
            //     $data['TOTAL_REVENUE_SUMMARY'] = [
            //         'TODAY_OCCUPANCY'=>$today_occupancy,
            //         'MTD_OCCUPANCY'=>$mtd_occupancy,
            //         'YTD_OCCUPANCY'=>$ytd_occupancy,
            //         'TODAY_OCC_PCTG'=>$today_occ_pctg,
            //         'MTD_OCC_PCTG'=>$mtd_occ_pctg,
            //         'YTD_OCC_PCTG'=>$ytd_occ_pctg,
            //         'TODAY_GUEST'=>$today_guest,
            //         'MTD_GUEST'=>$mtd_guest,
            //         'YTD_GUEST'=>$ytd_guest,
            //         'TODAY_ROOM_REVENUE'=>$today_room_revenue,
            //         'MTD_ROOM_REVENUE'=>$mtd_room_revenue,
            //         'YTD_ROOM_REVENUE'=>$ytd_room_revenue,
            //         'TODAY_FNB_REVENUE'=>$today_fnb_revenue,
            //         'MTD_FNB_REVENUE'=>$mtd_fnb_revenue,
            //         'YTD_FNB_REVENUE'=>$ytd_fnb_revenue,
            //         'TODAY_TOTAL_REVENUE'=>$total_revenue,
            //         'MTD_TOTAL_REVENUE'=>$mtd_total_revenue,
            //         'YTD_TOTAL_REVENUE'=>$ytd_total_revenue
            //     ];
            // } else
            //     $data['summary'] = [];
            // By Summary
            $data['summary'] = DB::connection('dbayana-dw')
            // ->select("EXEC dbo.usp_RptRevenueResortEmail ?", array($data['date']));
            ->select("EXEC dbo.usp_RptRevenueResortEmailSubV2 ?", array($data['date']));
            if(count($data['summary'])){
                $data['summary'] = collect($data['summary'])
                ->reject(function ($dataquery) {
                    // Exclude selain KMS1 dan PPC1
                    // return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1';
                    return !in_array($dataquery->CompanyCode, $this->show_company);
                })
                ->filter(function($dataquery){
                    $exclude_list = ['AYZR', 'KMS1'];
                    return in_array($dataquery->SubCompanyCode, $exclude_list) == false;
                })
                ->map(function($item, $key){
                    if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
                         $item->CompanyName = 'Ayana Rimba Bali';
                    else 
                         $item->CompanyName = ucwords(strtolower($item->CompanyName));
                    $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
                    return $item;
                })
                ->groupBy(['COMPANY_CONCAT', 'SubCompanyName', 'Type'])->toArray();

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
                $today_physical = 0;
                $mtd_physical = 0;
                $ytd_physical = 0;

                $data_sum_total = collect($data['summary'])->mapWithKeys(function($item_sub, $key) use (
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
                    foreach ($item_sub as $key_sub => $item) {
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
                    }

                    // if($today_unused > 0)
                    //     $today_occ_pctg = ($today_occupancy / $today_unused) * 100;
                    // if($mtd_unused > 0)
                    //     $mtd_occ_pctg = ($mtd_occupancy / $mtd_unused) * 100;
                    // if($ytd_unused > 0)
                    //     $ytd_occ_pctg = ($ytd_occupancy / $ytd_unused) * 100;
                    if($today_physical > 0)
                        $today_occ_pctg = ($today_occupancy / $today_physical) * 100;
                    if($mtd_physical > 0)
                        $mtd_occ_pctg = ($mtd_occupancy / $mtd_physical) * 100;
                    if($ytd_physical > 0)
                        $ytd_occ_pctg = ($ytd_occupancy / $ytd_physical) * 100;

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
                    $today_physical = 0;
                    $mtd_physical = 0;
                    $ytd_physical = 0;
                
                    return [$key=>array('SUBRESORT'=>$item_sub, 'TOTAL_REVENUE_SUMMARY'=>$data['TOTAL_REVENUE_SUMMARY'])];
                })->toArray();
                $data['summary'] = $data_sum_total;
                $subject = "AYANA Hotel Management - Revenue - ".date('d M Y',strtotime($data['date']));
                return $this->subject($subject)->view('pages.report.email.report_summary',['data'=>$data]);
            } else
                throw new \Exception("No Data Available in table");       

        } catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }
}
