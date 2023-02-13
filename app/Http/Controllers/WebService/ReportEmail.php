<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Mail;
Use Log;
use Mail;
use App\Mail\ReportSummary;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;

class ReportEmail extends Controller
{
    public $show_company = ['KMS1', 'PPC1', 'WKK1', 'PAD1'];
    public $apartment_data = ['WKK'];
    public $other_revenue = ['WKK1', 'PAD1', 'PPC1', 'KMS1'];

	function outlet_revenue(Request $request){
		$date = date('Y-m-d');
        $data['date'] = $date;
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

		$data['FNB'] = DB::connection('dbayana-dw')
        ->select("EXEC dbo.usp_RptRevenueOutletEmail ?", array($data['date']));
        if(count($data['FNB'])){
        	$data['FNB'] = collect($data['FNB'])
        	->reject(function ($dataquery) {
			    // return $dataquery->Resort != 'KMS1' && $dataquery->Resort != 'PPC1';
                return !in_array($dataquery->Resort, $this->show_company); 
			})
        	->groupBy(function($dataquery){
                if(strtoupper($dataquery->LOCATIONNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($dataquery->LOCATIONNAME) == 'AYANA BALI')
                     $dataquery->LOCATIONNAME = 'Ayana Rimba Bali';
                else 
                     $dataquery->LOCATIONNAME = ucwords(strtolower($dataquery->LOCATIONNAME));
                return strtoupper($dataquery->Resort)." - ".$dataquery->LOCATIONNAME;
            })->toArray();

        	$data['TOTAL_REVENUE'] = collect($data['FNB'])->mapWithKeys(function($item, $key){
        		// TODAY TOTAL PER COMPANY
        		$today_revenue = collect($item)->sum('REVENUE');
        		$mtd_revenue = collect($item)->sum('Revenue_MTD');
        		$ytd_revenue = collect($item)->sum('Revenue_YTD');

        		$today_guest = collect($item)->sum('GUEST');
        		$mtd_guest = collect($item)->sum('GUEST_MTD');
        		$ytd_guest = collect($item)->sum('GUEST_YTD');
        		return [$key=>[
        			'TODAY_REVENUE'=>$today_revenue,
        			'MTD_REVENUE'=>$mtd_revenue,
        			'YTD_REVENUE'=>$ytd_revenue,
        			'TODAY_GUEST'=>$today_guest,
        			'MTD_GUEST'=>$mtd_guest,
        			'YTD_GUEST'=>$ytd_guest,
        		]];
        	})->toArray();
        }
        else 
        	$data['FNB'] = [];

    	return view('pages.report.email.report_outlet', ['data'=>$data]);
	}

	function all(Request $request){
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

	    // By Summary
        $data['summary'] = DB::connection('dbayana-dw')
        ->select("EXEC dbo.usp_RptRevenueResortEmailV2 ?", array($data['date']));
        if(count($data['summary'])){
        	$data['summary'] = collect($data['summary'])
        	->reject(function ($dataquery) {
        		// Exclude selain KMS1 dan PPC1
			    // return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1' && $dataquery->CompanyCode != 'WKK1';
                return !in_array($dataquery->CompanyCode, $this->show_company);
			})->map(function($item, $key){
                if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
                     $item->CompanyName = 'Ayana Rimba Bali';
                else 
                     $item->CompanyName = ucwords(strtolower($item->CompanyName));
                $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
                return $item;
            })
        	->groupBy(['COMPANY_CONCAT', 'Type'])->toArray();
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

        	$data_sum_total = collect($data['summary'])->mapWithKeys(function($item, $key) use (
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
        } else
        	$data['summary'] = [];

	    // By Room Class
		$data['room_class'] = DB::connection('dbayana-dw')
        // ->select("EXEC dbo.usp_RptRevenueRoomClassEmail ?", array($data['date']));
        ->select("EXEC dbo.usp_RptRevenueRoomClassEmailV2 ?", array($data['date']));
        if(count($data['room_class'])){
        	$data['room_class'] = collect($data['room_class'])
        	->reject(function ($dataquery) {
        		// Exclude selain KMS1 dan PPC1
			    // return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1';
                return !in_array($dataquery->CompanyCode, $this->show_company); 
			})->map(function($item, $key){
                if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
                     $item->CompanyName = 'Ayana Rimba Bali';
                else 
                     $item->CompanyName = ucwords(strtolower($item->CompanyName));
                $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
                return $item;
            })
            ->groupBy('COMPANY_CONCAT')->toArray();
        	$data['TOTAL_REVENUE_ROOM_CLASS'] = collect($data['room_class'])->mapWithKeys(function($item, $key){
        		// TODAY TOTAL PER COMPANY
        		$today_available = collect($item)->sum('TodayAvailability');
        		$today_occ = collect($item)->sum('TodayOccupancy');
                $today_unused = collect($item)->sum('UnusedRoom');
                $today_tih = collect($item)->sum('TotRoom');
        		// $today_occ_pctg_more_than_zero = collect($item)->filter(function($pctg, $key){
        		// 	return $pctg->TodayOccupancyPctg > 0;
        		// });
        		// $today_occ_pctg = (collect($item)->sum('TodayOccupancyPctg')) / count($today_occ_pctg_more_than_zero);
                $today_occ_pctg = 0;
                if($today_tih > 0) 
                    $today_occ_pctg = ($today_occ / $today_tih) * 100;
        		$today_revenue = collect($item)->sum('TodayRevenue');

        		// MTD TOTAL PER COMPANY
        		$mtd_available = collect($item)->sum('AvailabilityMTD');
        		$mtd_occ = collect($item)->sum('OccupancyMTD');
                $mtd_unused = collect($item)->sum('UnusedMTD');
                $mtd_tih = collect($item)->sum('TotRoomMTD');

        		// $mtd_occ_pctg_more_than_zero = collect($item)->filter(function($pctg, $key){
        		// 	return $pctg->OccupancyMTDPctg > 0;
        		// });
        		// $mtd_occ_pctg = (collect($item)->sum('OccupancyMTDPctg')) / count($mtd_occ_pctg_more_than_zero);
                $mtd_occ_pctg = 0;
                if($mtd_tih > 0)
                    $mtd_occ_pctg = ($mtd_occ / $mtd_tih) * 100;
        		$mtd_revenue = collect($item)->sum('RevenueMTD');

        		// YTD TOTAL PER COMPANY
        		$ytd_available = collect($item)->sum('AvailabilityYTD');
        		$ytd_occ = collect($item)->sum('OccupancyYTD');
                $ytd_unused = collect($item)->sum('UnusedYTD');
                $ytd_tih = collect($item)->sum('TotRoomYTD');
        		// $ytd_occ_pctg_more_than_zero = collect($item)->filter(function($pctg, $key){
        		// 	return $pctg->OccupancyYTDPctg > 0;
        		// });
        		// $ytd_occ_pctg = (collect($item)->sum('OccupancyYTDPctg')) / count($ytd_occ_pctg_more_than_zero);
                $ytd_occ_pctg = 0;
                if($ytd_tih > 0)
                    $ytd_occ_pctg = ($ytd_occ / $ytd_tih) * 100; 
        		$ytd_revenue = collect($item)->sum('RevenueYTD');

        		return [$key=>[
        			'TODAY_AVAILABILITY'=>$today_available, 
        			'TODAY_OCCUPANCY'=>$today_occ, 
        			'TODAY_OCCUPANCY_PCTG'=>$today_occ_pctg,
        			'TODAY_REVENUE'=>$today_revenue,

        			'MTD_AVAILABILITY'=>$mtd_available, 
        			'MTD_OCCUPANCY'=>$mtd_occ, 
        			'MTD_OCCUPANCY_PCTG'=>$mtd_occ_pctg,
        			'MTD_REVENUE'=>$mtd_revenue,

        			'YTD_AVAILABILITY'=>$ytd_available, 
        			'YTD_OCCUPANCY'=>$ytd_occ, 
        			'YTD_OCCUPANCY_PCTG'=>$ytd_occ_pctg,
        			'YTD_REVENUE'=>$ytd_revenue,
        		]];
        	})->toArray();
        }
        else 
        	$data['room_class'] = [];

        // By Market
        $data['market'] = DB::connection('dbayana-dw')
        ->select("EXEC dbo.usp_RptRevenueMarketEmail ?", array($data['date']));
        if(count($data['market'])){
        	$data['market'] = collect($data['market'])
        	->reject(function ($dataquery) {
        		// Exclude selain KMS1 dan PPC1
			    // return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1';
                return !in_array($dataquery->CompanyCode, $this->show_company); 
			})->map(function($item, $key){
                if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
                     $item->CompanyName = 'Ayana Rimba Bali';
                else 
                     $item->CompanyName = ucwords(strtolower($item->CompanyName));
                $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
                return $item;
            })
            ->groupBy('COMPANY_CONCAT')->toArray();
        	$data['TOTAL_REVENUE_MARKET'] = collect($data['market'])->mapWithKeys(function($item, $key){
        		// TODAY TOTAL PER COMPANY
        		$number_rooms = collect($item)->sum('NumberOfRooms');
        		$number_rooms_mtd = collect($item)->sum('NumberOfRoomsMTD');
        		$number_rooms_ytd = collect($item)->sum('NumberOfRoomsYTD');

        		$today_revenue = collect($item)->sum('TodayRevenue');
        		$mtd_revenue = collect($item)->sum('RevenueMTD');
        		$ytd_revenue = collect($item)->sum('RevenueYTD');
        		return [$key=>[
        			'NUMBER_ROOMS'=>$number_rooms,
        			'MTD_NUMBER_ROOMS'=>$number_rooms_mtd,
        			'YTD_NUMBER_ROOMS'=>$number_rooms_ytd,
        			'TODAY_REVENUE'=>$today_revenue,
        			'MTD_REVENUE'=>$mtd_revenue,
        			'YTD_REVENUE'=>$ytd_revenue
        		]];
        	})->toArray();
        } else
        	$data['market'] = [];

        // By Outlet Name
        $data['FNB'] = DB::connection('dbayana-dw')
        ->select("EXEC dbo.usp_RptRevenueOutletEmail ?", array($data['date']));
        if(count($data['FNB'])){
        	$data['FNB'] = collect($data['FNB'])
        	->reject(function ($dataquery) {
        		// Exclude selain KMS1 dan PPC1
			    // return $dataquery->Resort != 'KMS1' && $dataquery->Resort != 'PPC1';
                return !in_array($dataquery->Resort, $this->show_company);
			})
            ->filter(function($dataquery){
                // Filter FB aja
                $is_type_fb = isset($dataquery->FB_YN) ? $dataquery->FB_YN : '';
                return $is_type_fb == 'Y';
            })
            ->groupBy(function($dataquery){
                if(strtoupper($dataquery->LOCATIONNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($dataquery->LOCATIONNAME) == 'AYANA BALI')
                     $dataquery->LOCATIONNAME = 'Ayana Rimba Bali';
                else 
                     $dataquery->LOCATIONNAME = ucwords(strtolower($dataquery->LOCATIONNAME));
                return strtoupper($dataquery->Resort)." - ".$dataquery->LOCATIONNAME;
            })->toArray();
        	$data['TOTAL_REVENUE_FNB'] = collect($data['FNB'])->mapWithKeys(function($item, $key){
        		// TODAY TOTAL PER COMPANY
        		$today_revenue = collect($item)->sum('REVENUE');
        		$mtd_revenue = collect($item)->sum('Revenue_MTD');
        		$ytd_revenue = collect($item)->sum('Revenue_YTD');

        		$today_guest = collect($item)->sum('GUEST');
        		$mtd_guest = collect($item)->sum('GUEST_MTD');
        		$ytd_guest = collect($item)->sum('GUEST_YTD');
        		return [$key=>[
        			'TODAY_REVENUE'=>$today_revenue,
        			'MTD_REVENUE'=>$mtd_revenue,
        			'YTD_REVENUE'=>$ytd_revenue,
        			'TODAY_GUEST'=>$today_guest,
        			'MTD_GUEST'=>$mtd_guest,
        			'YTD_GUEST'=>$ytd_guest,
        		]];
        	})->toArray();
        }
        else 
        	$data['FNB'] = [];

        $show_apartment = $this->apartment_data;
        $data['apartment'] = [];
        for($i=0;$i<count($show_apartment);$i++){
            $apartment_check = $this->summary_and_rental($show_apartment[$i]);
            if(isset($apartment_check['data'])){
                $data['apartment'][$show_apartment[$i]] = $apartment_check['data'];
            }            
        }

        $show_other_revenue = $this->other_revenue;
        $data['other_revenue'] = [];
        for($i=0;$i<count($show_other_revenue);$i++){
            $check_data_other = DB::connection('dbayana-stg')
            ->select("EXEC dbo.other_revenue_detail ?, ?", array($data['date'], $show_other_revenue[$i])); 
            if($check_data_other){
                $data['other_revenue'][$show_other_revenue[$i]] = collect($check_data_other)->groupBy('group_report')->mapWithKeys(function($item, $key){
                    $rev_today = $item->sum('RevenueToday');
                    $rev_mtd = $item->sum('RevenueMTD');
                    $rev_ytd = $item->sum('RevenueYTD');
                    return [$key=>['DATA'=>$item->toArray(), 'SUM_REV'=>array(
                        'TODAY'=>$rev_today,
                        'MTD'=>$rev_mtd,
                        'YTD'=>$rev_ytd
                    )]];
                })->toArray(); 
            }
        }

        // dd($data);
    	return view('pages.report.email.report_all', ['data'=>$data]);
	}

	function summary(Request $request){
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

	    // By Summary
        $data['summary'] = DB::connection('dbayana-dw')
        // ->select("EXEC dbo.usp_RptRevenueResortEmail ?", array($data['date']));
        ->select("EXEC dbo.usp_RptRevenueResortEmailSubV2 ?", array($data['date']));
        if(count($data['summary'])){
            $data['summary'] = collect($data['summary'])
            ->reject(function ($dataquery) {
                // Exclude selain KMS1 dan PPC1
                // return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1' && ;
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
        } else
            $data['summary'] = [];
        // dd($data);
    	return view('pages.report.email.report_summary', ['data'=>$data]);
	}

    function send_separate(Request $request){
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

		    // By Summary
	        $data['summary'] = DB::connection('dbayana-dw')
	        ->select("EXEC dbo.usp_RptRevenueResortEmailV2 ?", array($data['date']));
	        if(count($data['summary'])){
	        	$data['summary'] = collect($data['summary'])
	        	->reject(function ($dataquery) {
	        		// Exclude selain KMS1 dan PPC1
				    // return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1';
                    return !in_array($dataquery->CompanyCode, $this->show_company);
				})->map(function($item, $key){
                    if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
                         $item->CompanyName = 'Ayana Rimba Bali';
                    else 
                         $item->CompanyName = ucwords(strtolower($item->CompanyName));
                    $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
                    return $item;
                })
                ->groupBy(['COMPANY_CONCAT', 'Type'])->toArray();

                $show_apartment = $this->apartment_data;
                $data['apartment'] = [];
                for($i=0;$i<count($show_apartment);$i++){
                    $apartment_check = $this->summary_and_rental($show_apartment[$i]);
                    if(isset($apartment_check['data'])){
                        $data['apartment'][$show_apartment[$i]] = $apartment_check['data'];
                    }            
                }

                $show_other_revenue = $this->other_revenue;
                $data['other_revenue'] = [];
                for($i=0;$i<count($show_other_revenue);$i++){
                    $check_data_other = DB::connection('dbayana-stg')
                    ->select("EXEC dbo.other_revenue_detail ?, ?", array($data['date'], $show_other_revenue[$i])); 
                    if($check_data_other){
                        $data['other_revenue'][$show_other_revenue[$i]] = collect($check_data_other)->groupBy('group_report')->mapWithKeys(function($item, $key){
                            $rev_today = $item->sum('RevenueToday');
                            $rev_mtd = $item->sum('RevenueMTD');
                            $rev_ytd = $item->sum('RevenueYTD');
                            return [$key=>['DATA'=>$item->toArray(), 'SUM_REV'=>array(
                                'TODAY'=>$rev_today,
                                'MTD'=>$rev_mtd,
                                'YTD'=>$rev_ytd
                            )]];
                        })->toArray(); 
                    }
                }


	        	$resort =  array_filter(array_keys($data['summary']));
	        	for($i=0;$i<count($resort);$i++){
	        		$new_data = [];

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

		        	$item = $data['summary'][$resort[$i]];
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
		        	
		        	// Reinitialize data_summary
		    		$new_data['summary'][$resort[$i]] = $data['summary'][$resort[$i]];
		    		$new_data['TOTAL_REVENUE_SUMMARY'] = $data['TOTAL_REVENUE_SUMMARY'];

				    // By Room Class
					$data['room_class'] = DB::connection('dbayana-dw')
			        ->select("EXEC dbo.usp_RptRevenueRoomClassEmailV2 ?", array($data['date']));
			        if(count($data['room_class'])){
			        	$data['room_class'] = collect($data['room_class'])
			        	->reject(function ($dataquery) {
			        		// Exclude selain KMS1 dan PPC1
						    // return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1';
                            return !in_array($dataquery->CompanyCode, $this->show_company);
						})->map(function($item, $key){
                            if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
                                 $item->CompanyName = 'Ayana Rimba Bali';
                            else 
                                 $item->CompanyName = ucwords(strtolower($item->CompanyName));
                            $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
                            return $item;
                        })
                        ->groupBy('COMPANY_CONCAT')->toArray();

                        if(isset($data['room_class'][$resort[$i]])) {
    			        	// Reinitialize data
    			        	$new_data['room_class'][$resort[$i]] = $data['room_class'][$resort[$i]];

    			        	// TODAY TOTAL PER COMPANY
    		        		$today_available = collect($data['room_class'][$resort[$i]])->sum('TodayAvailability');
    		        		$today_occ = collect($data['room_class'][$resort[$i]])->sum('TodayOccupancy');
                            $today_tih = collect($data['room_class'][$resort[$i]])->sum('TotRoom');

    		        		// $today_occ_pctg_more_than_zero = collect($data['room_class'][$resort[$i]])->filter(function($pctg, $key){
    		        		// 	return $pctg->TodayOccupancyPctg > 0;
    		        		// });
    		        		// $today_occ_pctg = (collect($data['room_class'][$resort[$i]])->sum('TodayOccupancyPctg')) / count($today_occ_pctg_more_than_zero);
                            $today_occ_pctg = 0;
                            if($today_tih > 0) 
                                $today_occ_pctg = ($today_occ / $today_tih) * 100; 
    		        		$today_revenue = collect($data['room_class'][$resort[$i]])->sum('TodayRevenue');

    		        		// MTD TOTAL PER COMPANY
    		        		$mtd_available = collect($data['room_class'][$resort[$i]])->sum('AvailabilityMTD');
    		        		$mtd_occ = collect($data['room_class'][$resort[$i]])->sum('OccupancyMTD');
                            $mtd_tih = collect($data['room_class'][$resort[$i]])->sum('TotRoomMTD');
    		        		// $mtd_occ_pctg_more_than_zero = collect($data['room_class'][$resort[$i]])->filter(function($pctg, $key){
    		        		// 	return $pctg->OccupancyMTDPctg > 0;
    		        		// });
    		        		// $mtd_occ_pctg = (collect($data['room_class'][$resort[$i]])->sum('OccupancyMTDPctg')) / count($mtd_occ_pctg_more_than_zero);
                            $mtd_occ_pctg = 0;
                            if($mtd_tih > 0)
                                $mtd_occ_pctg = ($mtd_occ / $mtd_tih) * 100;
    		        		$mtd_revenue = collect($data['room_class'][$resort[$i]])->sum('RevenueMTD');

    		        		// YTD TOTAL PER COMPANY
    		        		$ytd_available = collect($data['room_class'][$resort[$i]])->sum('AvailabilityYTD');
    		        		$ytd_occ = collect($data['room_class'][$resort[$i]])->sum('OccupancyYTD');
                            $ytd_tih = collect($data['room_class'][$resort[$i]])->sum('TotRoomYTD');
    		        		// $ytd_occ_pctg_more_than_zero = collect($data['room_class'][$resort[$i]])->filter(function($pctg, $key){
    		        		// 	return $pctg->OccupancyYTDPctg > 0;
    		        		// });
    		        		// $ytd_occ_pctg = (collect($data['room_class'][$resort[$i]])->sum('OccupancyYTDPctg')) / count($ytd_occ_pctg_more_than_zero);
                            $ytd_occ_pctg = 0;
                            if($ytd_tih > 0)
                                $ytd_occ_pctg = ($ytd_occ / $ytd_tih) * 100;
    		        		$ytd_revenue = collect($data['room_class'][$resort[$i]])->sum('RevenueYTD');

    		        		$new_data['TOTAL_REVENUE_ROOM_CLASS'][$resort[$i]] = [
    		        			'TODAY_AVAILABILITY'=>$today_available, 
    		        			'TODAY_OCCUPANCY'=>$today_occ, 
    		        			'TODAY_OCCUPANCY_PCTG'=>$today_occ_pctg,
    		        			'TODAY_REVENUE'=>$today_revenue,

    		        			'MTD_AVAILABILITY'=>$mtd_available, 
    		        			'MTD_OCCUPANCY'=>$mtd_occ, 
    		        			'MTD_OCCUPANCY_PCTG'=>$mtd_occ_pctg,
    		        			'MTD_REVENUE'=>$mtd_revenue,

    		        			'YTD_AVAILABILITY'=>$ytd_available, 
    		        			'YTD_OCCUPANCY'=>$ytd_occ, 
    		        			'YTD_OCCUPANCY_PCTG'=>$ytd_occ_pctg,
    		        			'YTD_REVENUE'=>$ytd_revenue,
    		        		];
                        } else {
                            $new_data['room_class'][$resort[$i]] = [];
                            $new_data['TOTAL_REVENUE_ROOM_CLASS'][$resort[$i]] = [];
                        }
			        }
			        else {
			        	$new_data['room_class'][$resort[$i]] = [];
			        	$new_data['TOTAL_REVENUE_ROOM_CLASS'][$resort[$i]] = [];
			        }

			        // By Market
			        $data['market'] = DB::connection('dbayana-dw')
			        ->select("EXEC dbo.usp_RptRevenueMarketEmail ?", array($data['date']));
			        if(count($data['market'])){
			        	$data['market'] = collect($data['market'])
			        	->reject(function ($dataquery) {
			        		// Exclude selain KMS1 dan PPC1
						    // return $dataquery->CompanyCode != 'KMS1' && $dataquery->CompanyCode != 'PPC1';
                            return !in_array($dataquery->CompanyCode, $this->show_company);
						})->map(function($item, $key){
                            if(strtoupper($item->CompanyName) == 'AYANA RESORT AND SPA BALI' || strtoupper($item->CompanyName) == 'AYANA BALI')
                                 $item->CompanyName = 'Ayana Rimba Bali';
                            else 
                                 $item->CompanyName = ucwords(strtolower($item->CompanyName));
                            $item->COMPANY_CONCAT = strtoupper($item->CompanyCode)." - ".$item->CompanyName;
                            return $item;
                        })
                        ->groupBy('COMPANY_CONCAT')->toArray();

                        if(isset($data['market'][$resort[$i]])){
    			        	// Reinitialize data
    			        	$new_data['market'][$resort[$i]] = $data['market'][$resort[$i]];

    		        		// TODAY TOTAL PER COMPANY
    		        		$number_rooms = collect($data['market'][$resort[$i]])->sum('NumberOfRooms');
    		        		$number_rooms_mtd = collect($data['market'][$resort[$i]])->sum('NumberOfRoomsMTD');
    		        		$number_rooms_ytd = collect($data['market'][$resort[$i]])->sum('NumberOfRoomsYTD');

    		        		$today_revenue = collect($data['market'][$resort[$i]])->sum('TodayRevenue');
    		        		$mtd_revenue = collect($data['market'][$resort[$i]])->sum('RevenueMTD');
    		        		$ytd_revenue = collect($data['market'][$resort[$i]])->sum('RevenueYTD');

    		        		$new_data['TOTAL_REVENUE_MARKET'][$resort[$i]] = 
    		        		[
    		        			'NUMBER_ROOMS'=>$number_rooms,
    		        			'MTD_NUMBER_ROOMS'=>$number_rooms_mtd,
    		        			'YTD_NUMBER_ROOMS'=>$number_rooms_ytd,
    		        			'TODAY_REVENUE'=>$today_revenue,
    		        			'MTD_REVENUE'=>$mtd_revenue,
    		        			'YTD_REVENUE'=>$ytd_revenue
    		        		];
                        } else {
                            $new_data['market'][$resort[$i]] = [];
                            $new_data['TOTAL_REVENUE_MARKET'][$resort[$i]] = [];
                        }

			        } else {
			        	$new_data['market'][$resort[$i]] = [];
			        	$new_data['TOTAL_REVENUE_MARKET'][$resort[$i]] = [];
			        }

			        // By Outlet Name
			        $data['FNB'] = DB::connection('dbayana-dw')
			        ->select("EXEC dbo.usp_RptRevenueOutletEmail ?", array($data['date']));
			        if(count($data['FNB'])){
			        	$data['FNB'] = collect($data['FNB'])
			        	->reject(function ($dataquery) {
			        		// Exclude selain KMS1 dan PPC1
						    // return $dataquery->Resort != 'KMS1' && $dataquery->Resort != 'PPC1';
                            return !in_array($dataquery->Resort, $this->show_company);
						})
                        ->filter(function($dataquery){
                            // Filter FB aja
                            $is_type_fb = isset($dataquery->FB_YN) ? $dataquery->FB_YN : '';
                            return $is_type_fb == 'Y';
                        })
			        	->groupBy(function($dataquery){
                            if(strtoupper($dataquery->LOCATIONNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($dataquery->LOCATIONNAME) == 'AYANA BALI')
                                 $dataquery->LOCATIONNAME = 'Ayana Rimba Bali';
                            else 
                                 $dataquery->LOCATIONNAME = ucwords(strtolower($dataquery->LOCATIONNAME));
                            return strtoupper($dataquery->Resort)." - ".$dataquery->LOCATIONNAME;
                        })->toArray();

                        if(isset($data['FNB'][$resort[$i]])) {
    			        	// Reinitialize data
    			        	$new_data['FNB'][$resort[$i]] = $data['FNB'][$resort[$i]];
    		        		// TODAY TOTAL PER COMPANY
    		        		$today_revenue = collect($data['FNB'][$resort[$i]])->sum('REVENUE');
    		        		$mtd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_MTD');
    		        		$ytd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_YTD');

    		        		$today_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST');
    		        		$mtd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_MTD');
    		        		$ytd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_YTD');
    		        		$new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = 
    		        		[
    		        			'TODAY_REVENUE'=>$today_revenue,
    		        			'MTD_REVENUE'=>$mtd_revenue,
    		        			'YTD_REVENUE'=>$ytd_revenue,
    		        			'TODAY_GUEST'=>$today_guest,
    		        			'MTD_GUEST'=>$mtd_guest,
    		        			'YTD_GUEST'=>$ytd_guest,
    		        		];
                        } else {
                            $new_data['FNB'][$resort[$i]] = [];
                            $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = [];
                        }
			        }
			        else {
			        	$new_data['FNB'][$resort[$i]] = [];
			        	$new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = [];
			        }
			        $new_data['date'] = $data['date'];
                    $new_data['apartment'] = $data['apartment'];
                    $new_data['other_revenue'] = $data['other_revenue'];

			        Mail::send('pages.report.email.report_all', ['data'=>$new_data], function ($message) use ($data, $new_data, $resort, $i) {
                        $company_code = explode('-', $resort[$i]);
                        $company_code = isset($company_code[0]) ? trim($company_code[0]) : '';

                        $sendto = DB::connection('dbintranet')
                        ->table('dbo.VIEW_REPORT_EMAIL_DAILY_INTRANET')
                        ->where('GROUP_TYPE', $company_code)
                        ->get()->pluck('EMAIL', 'EMAIL')->filter()->toArray();
                        
		            	$subject = $resort[$i]." - Daily Revenue - ".date('d M Y',strtotime($data['date']));	        	
		            	$message->subject($subject);
					    $message->from('ayanareport@ayana.com', config('intranet.MAIL_FROM'));
                        $message->to($sendto);
                        // $message->to([]);
                        $message->bcc([
                          'mahendra_permana@biznetnetworks.com',
                          // 'test.akun@ayanaresort.com',
                          'muhammad_ishaq@biznetnetworks.com',
                          'ramlan_chandra@biznetnetworks.com',
                          'andy.susanto@midplaza.com',
                          'yudhi.arimbawan@midplaza.com'
                        ]);
					});
		    	}

		    } else
	        	throw new \Exception("No Revenue found on selected date");

            return response()->json(['status'=>200,'message'=>"Success send email"]);
        } catch(\Exception $e){
            return response()->json(['status'=>500, 'messages'=>$e->getMessage()]);
        }
    }

    function send_outlet_separate(Request $request){
        try {
        	$date = date('Y-m-d');
            $data['date'] = $date;
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

			$data['FNB'] = DB::connection('dbayana-dw')
	        ->select("EXEC dbo.usp_RptRevenueOutletEmail ?", array($data['date']));
	        if(count($data['FNB'])){
	        	$data['FNB'] = collect($data['FNB'])
	        	->reject(function ($dataquery) {
				    // return $dataquery->Resort != 'KMS1' && $dataquery->Resort != 'PPC1';
                    return !in_array($dataquery->Resort, $this->show_company);
				})
	        	->groupBy(function($dataquery){
                    if(strtoupper($dataquery->LOCATIONNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($dataquery->LOCATIONNAME) == 'AYANA BALI')
                         $dataquery->LOCATIONNAME = 'Ayana Rimba Bali';
                    else 
                         $dataquery->LOCATIONNAME = ucwords(strtolower($dataquery->LOCATIONNAME));
                    return strtoupper($dataquery->Resort)." - ".$dataquery->LOCATIONNAME;
                })->toArray();

	        	$resort = array_filter(array_keys($data['FNB']));
	        	for($i=0;$i<count($resort);$i++){
	        		$new_data = [];
	        		// TODAY TOTAL PER COMPANY
	        		$today_revenue = collect($data['FNB'][$resort[$i]])->sum('REVENUE');
	        		$mtd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_MTD');
	        		$ytd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_YTD');

	        		$today_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST');
	        		$mtd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_MTD');
	        		$ytd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_YTD');

	        		$data['TOTAL_REVENUE'][$resort[$i]] = 
	        		[
	        			'TODAY_REVENUE'=>$today_revenue,
	        			'MTD_REVENUE'=>$mtd_revenue,
	        			'YTD_REVENUE'=>$ytd_revenue,
	        			'TODAY_GUEST'=>$today_guest,
	        			'MTD_GUEST'=>$mtd_guest,
	        			'YTD_GUEST'=>$ytd_guest,
	        		];

	        		// Reiinitialize data FNB after separate each company
	        		$new_data[$resort[$i]] = $data['FNB'][$resort[$i]];
		        	Mail::send('pages.report.email.report_outlet', ['data'=>array('FNB'=>$new_data, 'TOTAL_REVENUE'=>$data['TOTAL_REVENUE'], 'date'=>$data['date']) ], function ($message) use ($data, $resort, $i) {
                        $company_code = explode('-', $resort[$i]);
                        $company_code = isset($company_code[0]) ? trim($company_code[0]) : '';

                        $sendto = DB::connection('dbintranet')
                        ->table('dbo.VIEW_REPORT_EMAIL_DAILY_INTRANET')
                        ->where('GROUP_TYPE', $company_code)
                        ->get()->pluck('EMAIL', 'EMAIL')->filter()->toArray();

		            	$subject = $resort[$i]." - Daily Outlet Revenue - ".date('d M Y',strtotime($data['date'])).' - '.date('H:i');
		            	$message->subject($subject);
					    $message->from('ayanareport@ayana.com', config('intranet.MAIL_FROM'));
                        $message->to($sendto);
                        $message->bcc([
                          'mahendra_permana@biznetnetworks.com',
                          'test.akun@ayanaresort.com',
                          'muhammad_ishaq@biznetnetworks.com',
                          'ramlan_chandra@biznetnetworks.com',
                          'apriadi_kurniawan@biznetnetworks.com',
                          'yudhi.arimbawan@midplaza.com',
                          'andy.susanto@midplaza.com'
                        ]);
					});
				}
	        }
	        else 
	        	throw new \Exception("No FNB Revenue found on selected date");
	        	
            return response()->json(['status'=>200,'message'=>"Success send email"]);
        } catch(\Exception $e){
            return response()->json(['status'=>500, 'messages'=>$e->getMessage()]);
        }
    }

    function send_summary(Request $request){
        try {
            // Initialize send to
            $sendto = DB::connection('dbintranet')
            ->table('dbo.VIEW_REPORT_EMAIL_DAILY_INTRANET')
            ->where('GROUP_TYPE', 'SUMMARY')
            ->get()->pluck('EMAIL', 'EMAIL')->filter()->toArray();
 
            Mail::to($sendto)
            // Mail::to([])
            ->bcc([
              'mahendra_permana@biznetnetworks.com',
              'muhammad_ishaq@biznetnetworks.com',
              'ramlan_chandra@biznetnetworks.com',
              'andy.susanto@midplaza.com',
              'yudhi.arimbawan@midplaza.com'
            ])
            ->send(new ReportSummary());
            return response()->json(['status'=>200,'message'=>"Success send email"]);
        } catch(\Exception $e){
            return response()->json(['status'=>500, 'messages'=>$e->getMessage()]);
        }
    }

    function pnb1_revenue(Request $request){
        // Khusus PNB1 Revenue
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

            $pnb1_revenue = DB::connection('dbayana-dw')
            ->select('EXEC usp_RptPnbRevenue_GetData ?', array($data['date']));
            if($pnb1_revenue){
                $data['pnb1_revenue'] = $pnb1_revenue;
            }
            return view('pages.report.email.report_pnb1', ['data'=>$data]);
        } catch(\Exception $e) {
            return response()->json(['status'=>'error', 'messages'=>$e->getMessage()]);
        }
    }

    function send_pnb1_revenue(Request $request){
        // Khusus PNB1 Revenue
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

            $pnb1_revenue = DB::connection('dbayana-dw')
            ->select('EXEC usp_RptPnbRevenue_GetData ?', array($data['date']));
            if($pnb1_revenue){
                $data['pnb1_revenue'] = $pnb1_revenue;
                Mail::send('pages.report.email.report_pnb1', ['data'=>array('pnb1_revenue'=>$data['pnb1_revenue'], 'date'=>$data['date']) ], function ($message) use ($data) {

                    $sendto = DB::connection('dbintranet')
                    ->table('dbo.VIEW_REPORT_EMAIL_DAILY_INTRANET')
                    ->where('GROUP_TYPE', 'PNB1')
                    ->get()->pluck('EMAIL', 'EMAIL')->filter()->toArray();

                    $subject = "PNB1 - Ayana Cruises - Daily Revenue - ".date('d M Y',strtotime($data['date'])).' - '.date('H:i');
                    $message->subject($subject);
                    $message->from('ayanareport@ayana.com', config('intranet.MAIL_FROM'));
                    $message->to($sendto);
                    // $message->to([]);
                    $message->bcc([
                      'mahendra_permana@biznetnetworks.com',
                      'andy.susanto@midplaza.com',
                      'yudhi.arimbawan@midplaza.com',
                      'roy_putra@biznetnetworks.com'
                    ]);
                });
                // return view('pages.report.email.report_pnb1', ['data'=>$data]);
                return response()->json(['status'=>200,'message'=>"Success send email PNB1"]);
            } else {
                return response()->json(['status'=>'nodata', 'messages'=>'No revenue data found for PNB1 - Ayana Cruises']);
            }
        } catch(\Exception $e) {
            return response()->json(['status'=>'error', 'messages'=>$e->getMessage()]);
        }
    }

    function pad3_revenue(Request $request){
        try {
            $new_data = [];
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

            $new_data['date'] = $data['date'];
            $data['FNB'] = DB::connection('dbayana-dw')
            ->select("EXEC dbo.usp_RptRevenueOutletEmail ?", array($data['date']));
            if(count($data['FNB'])){
                $data['FNB'] = collect($data['FNB'])
                ->reject(function ($dataquery) {
                    // Exclude selain KMS1 dan PPC1
                    // return $dataquery->Resort != 'KMS1' && $dataquery->Resort != 'PPC1';
                    return !in_array($dataquery->Resort, ['PAD3']);
                })
                ->filter(function($dataquery){
                    // Filter FB aja
                    $is_type_fb = isset($dataquery->FB_YN) ? $dataquery->FB_YN : '';
                    return $is_type_fb == 'Y';
                })
                ->groupBy(function($dataquery){
                    if(strtoupper($dataquery->LOCATIONNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($dataquery->LOCATIONNAME) == 'AYANA BALI')
                         $dataquery->LOCATIONNAME = 'Ayana Rimba Bali';
                    else 
                         $dataquery->LOCATIONNAME = ucwords(strtolower($dataquery->LOCATIONNAME));
                    return strtoupper($dataquery->Resort)." - ".$dataquery->LOCATIONNAME;
                })->toArray();
                $resort = array_keys($data['FNB']);
                for($i=0;$i<count($resort);$i++){
                    if(isset($data['FNB'][$resort[$i]])) {
                        // Reinitialize data
                        $new_data['FNB'][$resort[$i]] = $data['FNB'][$resort[$i]];
                        // TODAY TOTAL PER COMPANY
                        $today_revenue = collect($data['FNB'][$resort[$i]])->sum('REVENUE');
                        $mtd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_MTD');
                        $ytd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_YTD');

                        $today_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST');
                        $mtd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_MTD');
                        $ytd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_YTD');
                        $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = 
                        [
                            'TODAY_REVENUE'=>$today_revenue,
                            'MTD_REVENUE'=>$mtd_revenue,
                            'YTD_REVENUE'=>$ytd_revenue,
                            'TODAY_GUEST'=>$today_guest,
                            'MTD_GUEST'=>$mtd_guest,
                            'YTD_GUEST'=>$ytd_guest,
                        ];
                    } else {
                        $new_data['FNB'][$resort[$i]] = [];
                        $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = [];
                    }
                }
            }
            
            return view('pages.report.email.report_pad3', ['data'=>$new_data]);
        } catch(\Exception $e){
            return response()->json(['status'=>'failed', 'error'=>$e->getMessage(), 'message'=>'Failed to send PAD3 Revenue, please check the data and try again']);
        }
    }

    function send_pad3_revenue(Request $request){
        try {
            $new_data = [];
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

            $new_data['date'] = $data['date'];
            $data['FNB'] = DB::connection('dbayana-dw')
            ->select("EXEC dbo.usp_RptRevenueOutletEmail ?", array($data['date']));
            if(count($data['FNB'])){
                $data['FNB'] = collect($data['FNB'])
                ->reject(function ($dataquery) {
                    // Exclude selain KMS1 dan PPC1
                    // return $dataquery->Resort != 'KMS1' && $dataquery->Resort != 'PPC1';
                    return !in_array($dataquery->Resort, ['PAD3']);
                })
                ->filter(function($dataquery){
                    // Filter FB aja
                    $is_type_fb = isset($dataquery->FB_YN) ? $dataquery->FB_YN : '';
                    return $is_type_fb == 'Y';
                })
                ->groupBy(function($dataquery){
                    if(strtoupper($dataquery->LOCATIONNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($dataquery->LOCATIONNAME) == 'AYANA BALI')
                         $dataquery->LOCATIONNAME = 'Ayana Rimba Bali';
                    else 
                         $dataquery->LOCATIONNAME = ucwords(strtolower($dataquery->LOCATIONNAME));
                    return strtoupper($dataquery->Resort)." - ".$dataquery->LOCATIONNAME;
                })->toArray();
                $resort = array_keys($data['FNB']);
                for($i=0;$i<count($resort);$i++){
                    if(isset($data['FNB'][$resort[$i]])) {
                        // Reinitialize data
                        $new_data['FNB'][$resort[$i]] = $data['FNB'][$resort[$i]];
                        // TODAY TOTAL PER COMPANY
                        $today_revenue = collect($data['FNB'][$resort[$i]])->sum('REVENUE');
                        $mtd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_MTD');
                        $ytd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_YTD');

                        $today_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST');
                        $mtd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_MTD');
                        $ytd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_YTD');
                        $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = 
                        [
                            'TODAY_REVENUE'=>$today_revenue,
                            'MTD_REVENUE'=>$mtd_revenue,
                            'YTD_REVENUE'=>$ytd_revenue,
                            'TODAY_GUEST'=>$today_guest,
                            'MTD_GUEST'=>$mtd_guest,
                            'YTD_GUEST'=>$ytd_guest,
                        ];
                    } else {
                        $new_data['FNB'][$resort[$i]] = [];
                        $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = [];
                    }
                }

                if(is_array($new_data) && count($new_data)){
                    Mail::send('pages.report.email.report_pad3', ['data'=>$new_data], function ($message) use ($data) {
                        // $sendto = DB::connection('dbintranet')
                        // ->table('dbo.VIEW_REPORT_EMAIL_DAILY_INTRANET')
                        // ->where('GROUP_TYPE', 'PNB1')
                        // ->get()->pluck('EMAIL', 'EMAIL')->filter()->toArray();

                        $subject = "PAD3 - Liu Li Palace Seafood Restaurant - Daily Revenue - ".date('d M Y',strtotime($data['date'])).' - '.date('H:i');
                        $message->subject($subject);
                        $message->from('ayanareport@ayana.com', config('intranet.MAIL_FROM'));
                        // $message->to($sendto);
                        $message->to([]);
                        $message->bcc([
                          'mahendra_permana@biznetnetworks.com'
                          // 'andy.susanto@midplaza.com',
                          // 'yudhi.arimbawan@midplaza.com',
                          // 'roy_putra@biznetnetworks.com'
                        ]);
                    });
                    // return view('pages.report.email.report_pnb1', ['data'=>$data]);
                    return response()->json(['status'=>200,'message'=>"Success send email PAD3"]);
                } else {
                    return response()->json(['status'=>'nodata', 'messages'=>'No revenue data found for PAD3 - Liu Li Palace Seafood Restaurant']);
                }
            }
            else {
                return response()->json(['status'=>'nodata', 'messages'=>'No Revenue found on selected date, please check your data and try again.']);
            }
        } catch(\Exception $e){
            return response()->json(['status'=>'failed', 'error'=>$e->getMessage(), 'message'=>'Failed to send PAD3 Revenue, please check the data and try again']);
        }
    }

    function rental_object(Request $request){
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
        try {
            $company_to_lookup = $this->apartment_data;
            $data['RENTAL_OBJECT_SUMMARY'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'] = [];
            $data['RENTAL_OBJECT_TOTAL_REVENUE'] = [];
            $data['RENTAL_OBJECT_OCCUPIED'] = [];

            for($i=0;$i<count($company_to_lookup);$i++){
                // Mencari Rental object yang tidak ada kontrak di DB Warehouse
                $data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]] = [];
                $data['RENTAL_OBJECT_NO_CONTRACT_UNIT'][$company_to_lookup[$i]] = [];
                $rental_no_contract_revenue = 0;
                $rental_no_contract = 0;

                try {
                    $data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]] = DB::connection('dbayana-dw')
                    ->select("EXEC dbo.usp_RptRentalObjectNoContract_GetData ?, ?", array($company_to_lookup[$i], $data['date']));
                    if(count($data['RENTAL_OBJECT_NO_CONTRACT'])){
                        $data['RENTAL_OBJECT_NO_CONTRACT_UNIT'][$company_to_lookup[$i]] = collect($data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]])->pluck('RENTAL_UNIT', 'RENTAL_UNIT')
                        ->values()->all();
                        $rental_no_contract += count($data['RENTAL_OBJECT_NO_CONTRACT_UNIT'][$company_to_lookup[$i]]);
                        $rental_no_contract_revenue += collect($data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]])->sum('REVENUE');
                    }
                } catch(\Exception $e){}

                // Mulai mencari data kontrak dari SAP RFC
                $is_production = config('intranet.is_production');
                if($is_production)
                    $connection = new SapConnection(config('intranet.rfc_prod'));
                else
                    $connection = new SapConnection(config('intranet.rfc_dev'));

                $options = [
                    'rtrim'=>true,
                ];

                $filter_list = [
                    'P_RENT_OBJ' => "",
                    'P_CONTRACT_NUM' => "",
                    'P_COMPANY' => $company_to_lookup[$i],
                    'P_BUSINES_ENTITY_NUM' => "",
                ];
                $data['FILTER'] = $filter_list;
                
                $function = $connection->getFunction('ZFM_MM_IM_RE_FX_REPORT_V2');
                $result= $function->invoke($filter_list, $options);
                $measurement_data = isset($result['GT_MEASUREMENT_RENTAL']) ? collect($result['GT_MEASUREMENT_RENTAL'])->groupBy(['OBJECT_ID','OBJECT_TYPE']) : [];
                $excluded_rental = [
                    'W2010131',
                    'W2010132',
                    'W2010133',
                    'W2010134',
                    'W2010135',
                    'W2010137',
                    'W2020131',
                    'W2020132',
                    'W2020133',
                    'W2020134'
                ];
                    
                if(count(array_keys($result)) > 0 && in_array('GT_REPORT', array_keys($result))){
                    collect($result['GT_REPORT'])->groupBy('BUSINESSENTITYNUMBER')->mapWithKeys(function($item, $keyPlant) use ($measurement_data, &$data, $excluded_rental, $company_to_lookup, $i){
                        $newKeyPlant = $keyPlant;
                        try {
                            $plant_desc = DB::connection('dbintranet')
                            ->table('dbo.INT_BUSINESS_PLANT')
                            ->where('SAP_PLANT_ID', $keyPlant)
                            ->select('SAP_PLANT_NAME')
                            ->get()->first();
                            if($plant_desc){
                                $newKeyPlant = $keyPlant." - ".ucwords(strtolower($plant_desc->SAP_PLANT_NAME));
                            }

                        } catch(\Exception $e){}

                        // Exclude yang bukan merupakan commercial space
                        $sort_data = $item->groupBy(['SAPRENTALOBJECTID','OBJECT_ID','CONDITIONTYPE'])
                        ->reject(function($dataRental, $key) use ($excluded_rental){
                            return in_array($key, $excluded_rental);
                        })->toArray();

                        $data['RENTAL_OBJECT_DETAIL'][$company_to_lookup[$i]][$newKeyPlant] = $sort_data;
                        return [$newKeyPlant=>$sort_data];
                    })->toArray();

                    collect($data['RENTAL_OBJECT_DETAIL'][$company_to_lookup[$i]])->mapWithKeys(function($itemParent, $key) use (&$data, $company_to_lookup, $i, $rental_no_contract, $rental_no_contract_revenue){
                        $date = $data['date'];
                        $plant_lookup = substr($key, 0, 4);
                        $occupied_apartment = DB::connection('dbayana-stg')
                        ->select("
                            SELECT ROOM_NO, UDFC09 AS CONTRACT_NO
                            FROM 
                            ( 
                                SELECT ROOM_NO, UDFC09,
                                             ROW_NUMBER() OVER (PARTITION BY room_no order by udfc09 DESC) --- ORDER BY NULL
                                               AS rn                                   --- for example
                                      FROM reservationdailystat
                                WHERE RESV_STATUS = 'CHECKED IN' AND BUSINESS_DATE='$date' AND resort=CONCAT(LEFT('$plant_lookup', 3),'',1) AND ROOM_CLASS='ZZAPT' OR RESV_STATUS='CHECKED OUT' AND DUE_OUT_YN<>'N' AND BUSINESS_DATE='$date' AND resort=CONCAT(LEFT('$plant_lookup', 3),'',1) AND ROOM_CLASS='ZZAPT'
                            ) tmp 
                            WHERE rn = 1 AND UDFC09 IS NOT NULL
                            ORDER BY ROOM_NO ASC
                        ");
                        try {
                            $detail_occupied = DB::connection('dbayana-dw')
                            ->select("EXEC dbo.usp_RptRentalObject_GetData ?, ?", array($plant_lookup, $date));
                            if($detail_occupied){
                                $data['RENTAL_OBJECT_TOTAL_REVENUE'][$key] = collect($detail_occupied)->sum('REVENUE');
                                $data['RENTAL_OBJECT_TOTAL_REVENUE'][$key] += $rental_no_contract_revenue;

                                $data['RENTAL_OBJECT_OCCUPIED'][$key] = collect($detail_occupied)->groupBy(function($item) use (&$data, $key, 
                                    $company_to_lookup, $i){
                                    $group_contract = $item->CONTRACT_TYPE_DESC;
                                    if($item->CONTRACT_TYPE == 'Z002')
                                        $group_contract = 'Apartment / Residential';
                                    else if($item->CONTRACT_TYPE = 'Z001')
                                        $group_contract = 'Office / Retail Space';
                                    return $group_contract;
                                })->toArray();

                                if(!in_array('Office / Retail Space', array_keys($data['RENTAL_OBJECT_OCCUPIED'][$key]))){
                                    $data_office_rental = DB::connection('dbayana-stg')
                                    ->table('dbo.Amortisation')
                                    ->where(['PROFIT_CENTER'=>$plant_lookup, 'CONTRACT_TYPE'=>'Z001', 'CONDITION_TYPE'=>'Z115'])
                                    ->whereRaw("(CAST('$date' AS DATE) >= VALID_FROM AND CAST('$date' AS DATE) <= VALID_TO)")
                                    ->select(DB::raw('RENTAL_UNIT, CONTRACT, RENT_UNIT_DESC, BP_DESC, \'-\' AS ROOM_NO,
                                        SUM(
                                            CASE WHEN FREQUENCY_UNIT = 0
                                                THEN (REVENUE_AMOUNT/30)
                                            ELSE
                                                REVENUE_AMOUNT
                                            END
                                        )  AS REVENUE'
                                    ))->groupByRaw('RENTAL_UNIT, CONTRACT, RENT_UNIT_DESC, BP_DESC')->get()
                                    ->toArray();
                                    if($data_office_rental)
                                        $data['RENTAL_OBJECT_OCCUPIED'][$key]['Office / Retail Space'] = $data_office_rental;
                                }
                                else {
                                    $data_office_rental = DB::connection('dbayana-stg')
                                    ->table('dbo.Amortisation')
                                    ->where(['PROFIT_CENTER'=>$plant_lookup, 'CONTRACT_TYPE'=>'Z001', 'CONDITION_TYPE'=>'Z115'])
                                    ->whereRaw("(CAST('$date' AS DATE) >= VALID_FROM AND CAST('$date' AS DATE) <= VALID_TO)")
                                    ->select(DB::raw('RENTAL_UNIT, CONTRACT, RENT_UNIT_DESC, BP_DESC, \'-\' AS ROOM_NO,
                                        SUM(
                                            CASE WHEN FREQUENCY_UNIT = 0
                                                THEN (REVENUE_AMOUNT/30)
                                            ELSE
                                                REVENUE_AMOUNT
                                            END
                                        )  AS REVENUE'
                                    ))->groupByRaw('RENTAL_UNIT, CONTRACT, RENT_UNIT_DESC, BP_DESC')->get()
                                    ->toArray();
                                    if($data_office_rental)
                                        collect($data['RENTAL_OBJECT_OCCUPIED'][$key]['Office / Retail Space'])->concat($data_office_rental)->toArray();
                                }

                                // insert Rental object no contract to array
                                $data['RENTAL_OBJECT_OCCUPIED'][$key]['Apartment Non Contract / FIT'] = isset($data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]]) ? $data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]] : [];

                                $data['RENTAL_OBJECT_OCCUPIED'][$key] = collect($data['RENTAL_OBJECT_OCCUPIED'][$key])->mapWithKeys(function($item, $key){
                                    $sort = 0;
                                    if(strtolower($key) == 'apartment / residential')
                                        $sort = 1;
                                    else if(strtolower($key) == 'office / retail space')
                                        $sort = 3;
                                    else if(strtolower($key) == 'apartment non contract / fit')
                                        $sort = 2;
                                    return [$key=>array('SORT'=>$sort,'ITEM'=>$item)];
                                })->sortBy(function ($itemSort, $key) {
                                    return $itemSort['SORT'];
                                })->mapWithKeys(function ($item, $key){
                                    // Strip sort key and put item only
                                    return [$key=>$item['ITEM']];
                                })->toArray();
                            }
                        } catch(\Exception $e){
                            throw new Exception($e->getMessage());
                        }

                        $total_all = count($itemParent);
                        $total_occupied = count($occupied_apartment) + $rental_no_contract;
                        $total_vacant = $total_all - $total_occupied;
                        $total_occupancy_rate = ((int)$total_occupied / (int)$total_all) * 100; 
                        $summary = array(
                            'TOTAL_ALL'=>$total_all,
                            'TOTAL_VACANT'=>$total_vacant,
                            'TOTAL_OCCUPIED'=>$total_occupied,
                            'TOTAL_OCCUPANCY_RATE'=>$total_occupancy_rate,
                        );

                        $data['RENTAL_OBJECT_SUMMARY'][$key] = $summary;
                        return [$key=>$summary];
                    })->toArray();
                }
            }

            // Untuk report revenue PAD3
            $new_data = [];
            $new_data['date'] = $data['date'];
            $data['FNB'] = DB::connection('dbayana-dw')
            ->select("EXEC dbo.usp_RptRevenueOutletEmail ?", array($data['date']));
            if(count($data['FNB'])){
                $data['FNB'] = collect($data['FNB'])
                ->reject(function ($dataquery) {
                    // Exclude selain KMS1 dan PPC1
                    // return $dataquery->Resort != 'KMS1' && $dataquery->Resort != 'PPC1';
                    return !in_array($dataquery->Resort, ['PAD3']);
                })
                ->filter(function($dataquery){
                    // Filter FB aja
                    $is_type_fb = isset($dataquery->FB_YN) ? $dataquery->FB_YN : '';
                    return $is_type_fb == 'Y';
                })
                ->groupBy(function($dataquery){
                    if(strtoupper($dataquery->LOCATIONNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($dataquery->LOCATIONNAME) == 'AYANA BALI')
                         $dataquery->LOCATIONNAME = 'Ayana Rimba Bali';
                    else 
                         $dataquery->LOCATIONNAME = ucwords(strtolower($dataquery->LOCATIONNAME));
                    return strtoupper($dataquery->Resort)." - ".$dataquery->LOCATIONNAME;
                })->toArray();
                $resort = array_keys($data['FNB']);
                for($i=0;$i<count($resort);$i++){
                    if(isset($data['FNB'][$resort[$i]])) {
                        // Reinitialize data
                        $new_data['FNB'][$resort[$i]] = $data['FNB'][$resort[$i]];
                        // TODAY TOTAL PER COMPANY
                        $today_revenue = collect($data['FNB'][$resort[$i]])->sum('REVENUE');
                        $mtd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_MTD');
                        $ytd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_YTD');

                        $today_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST');
                        $mtd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_MTD');
                        $ytd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_YTD');
                        $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = 
                        [
                            'TODAY_REVENUE'=>$today_revenue,
                            'MTD_REVENUE'=>$mtd_revenue,
                            'YTD_REVENUE'=>$ytd_revenue,
                            'TODAY_GUEST'=>$today_guest,
                            'MTD_GUEST'=>$mtd_guest,
                            'YTD_GUEST'=>$ytd_guest,
                        ];
                    } else {
                        $new_data['FNB'][$resort[$i]] = [];
                        $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = [];
                    }
                }
            }

        } catch(SapException $e){
            Log::error($e->getMessage());
            return response()->json(['status'=>'error', 'exception_type'=>'SAP Exception', 'message'=>$e->getMessage(), 'trace'=>$e]);
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json(['status'=>'error', 'exception_type'=>'General Exception', 'message'=>$e->getMessage(), 'trace'=>$e]);
        }

        // dd($data);
        return view('pages.report.email.report_rental_object', ['data'=>$data, 'new_data_fnb'=>$new_data]);
    }

    function send_rental_object(Request $request){
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

        $company_to_lookup = $this->apartment_data;

        for($i=0;$i<count($company_to_lookup);$i++){
            $data['RENTAL_OBJECT_SUMMARY'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'] = [];
            $data['RENTAL_OBJECT_TOTAL_REVENUE'] = [];
            $data['RENTAL_OBJECT_OCCUPIED'] = [];

            try {
                // Mencari Rental object yang tidak ada kontrak di DB Warehouse
                $data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]] = [];
                $data['RENTAL_OBJECT_NO_CONTRACT_UNIT'][$company_to_lookup[$i]] = [];
                $rental_no_contract_revenue = 0;
                $rental_no_contract = 0;

                try {
                    $data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]] = DB::connection('dbayana-dw')
                    ->select("EXEC dbo.usp_RptRentalObjectNoContract_GetData ?, ?", array($company_to_lookup[$i], $data['date']));
                    if(count($data['RENTAL_OBJECT_NO_CONTRACT'])){
                        $data['RENTAL_OBJECT_NO_CONTRACT_UNIT'][$company_to_lookup[$i]] = collect($data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]])->pluck('RENTAL_UNIT', 'RENTAL_UNIT')
                        ->values()->all();
                        $rental_no_contract += count($data['RENTAL_OBJECT_NO_CONTRACT_UNIT'][$company_to_lookup[$i]]);
                        $rental_no_contract_revenue += collect($data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]])->sum('REVENUE');
                    }
                } catch(\Exception $e){}

                // Mulai mencari data kontrak dari SAP RFC
                $is_production = config('intranet.is_production');
                if($is_production)
                    $connection = new SapConnection(config('intranet.rfc_prod'));
                else
                    $connection = new SapConnection(config('intranet.rfc_dev'));

                $options = [
                    'rtrim'=>true,
                ];

                $filter_list = [
                    'P_RENT_OBJ' => "",
                    'P_CONTRACT_NUM' => "",
                    'P_COMPANY' => $company_to_lookup[$i],
                    'P_BUSINES_ENTITY_NUM' => "",
                ];
                $data['FILTER'] = $filter_list;
                
                $function = $connection->getFunction('ZFM_MM_IM_RE_FX_REPORT_V2');
                $result= $function->invoke($filter_list, $options);
                $measurement_data = isset($result['GT_MEASUREMENT_RENTAL']) ? collect($result['GT_MEASUREMENT_RENTAL'])->groupBy(['OBJECT_ID','OBJECT_TYPE']) : [];
                $excluded_rental = [
                    'W2010131',
                    'W2010132',
                    'W2010133',
                    'W2010134',
                    'W2010135',
                    'W2010137',
                    'W2020131',
                    'W2020132',
                    'W2020133',
                    'W2020134'
                ];
                    
                if(count(array_keys($result)) > 0 && in_array('GT_REPORT', array_keys($result))){
                    collect($result['GT_REPORT'])->groupBy('BUSINESSENTITYNUMBER')->mapWithKeys(function($item, $keyPlant) use ($measurement_data, &$data, $excluded_rental, $company_to_lookup, $i){
                        $newKeyPlant = $keyPlant;
                        try {
                            $plant_desc = DB::connection('dbintranet')
                            ->table('dbo.INT_BUSINESS_PLANT')
                            ->where('SAP_PLANT_ID', $keyPlant)
                            ->select('SAP_PLANT_NAME')
                            ->get()->first();
                            if($plant_desc){
                                $newKeyPlant = $keyPlant." - ".ucwords(strtolower($plant_desc->SAP_PLANT_NAME));
                            }

                        } catch(\Exception $e){}

                        // Exclude yang bukan merupakan commercial space
                        $sort_data = $item->groupBy(['SAPRENTALOBJECTID','OBJECT_ID','CONDITIONTYPE'])
                        ->reject(function($dataRental, $key) use ($excluded_rental){
                            return in_array($key, $excluded_rental);
                        })->toArray();

                        $data['RENTAL_OBJECT_DETAIL'][$company_to_lookup[$i]][$newKeyPlant] = $sort_data;
                        return [$newKeyPlant=>$sort_data];
                    })->toArray();

                    collect($data['RENTAL_OBJECT_DETAIL'][$company_to_lookup[$i]])->mapWithKeys(function($itemParent, $key) use (&$data, $company_to_lookup, $i, $rental_no_contract, $rental_no_contract_revenue){
                        $date = $data['date'];
                        $plant_lookup = substr($key, 0, 4);
                        $occupied_apartment = DB::connection('dbayana-stg')
                        ->select("
                            SELECT ROOM_NO, UDFC09 AS CONTRACT_NO
                            FROM 
                            ( 
                                SELECT ROOM_NO, UDFC09,
                                             ROW_NUMBER() OVER (PARTITION BY room_no order by udfc09 DESC) --- ORDER BY NULL
                                               AS rn                                   --- for example
                                      FROM reservationdailystat
                                WHERE RESV_STATUS = 'CHECKED IN' AND BUSINESS_DATE='$date' AND resort=CONCAT(LEFT('$plant_lookup', 3),'',1) AND ROOM_CLASS='ZZAPT' OR RESV_STATUS='CHECKED OUT' AND DUE_OUT_YN<>'N' AND BUSINESS_DATE='$date' AND resort=CONCAT(LEFT('$plant_lookup', 3),'',1) AND ROOM_CLASS='ZZAPT'
                            ) tmp 
                            WHERE rn = 1 AND UDFC09 IS NOT NULL
                            ORDER BY ROOM_NO ASC
                        ");
                        try {
                            $detail_occupied = DB::connection('dbayana-dw')
                            ->select("EXEC dbo.usp_RptRentalObject_GetData ?, ?", array($plant_lookup, $date));
                            if($detail_occupied){
                                $data['RENTAL_OBJECT_TOTAL_REVENUE'][$key] = collect($detail_occupied)->sum('REVENUE');
                                $data['RENTAL_OBJECT_TOTAL_REVENUE'][$key] += $rental_no_contract_revenue;

                                $data['RENTAL_OBJECT_OCCUPIED'][$key] = collect($detail_occupied)->groupBy(function($item) use (&$data, $key, 
                                    $company_to_lookup, $i){
                                    $group_contract = $item->CONTRACT_TYPE_DESC;
                                    if($item->CONTRACT_TYPE == 'Z002')
                                        $group_contract = 'Apartment / Residential';
                                    else if($item->CONTRACT_TYPE = 'Z001')
                                        $group_contract = 'Office / Retail Space';
                                    return $group_contract;
                                })->toArray();

                                if(!in_array('Office / Retail Space', array_keys($data['RENTAL_OBJECT_OCCUPIED'][$key]))){
                                    $data_office_rental = DB::connection('dbayana-stg')
                                    ->table('dbo.Amortisation')
                                    ->where(['PROFIT_CENTER'=>$plant_lookup, 'CONTRACT_TYPE'=>'Z001', 'CONDITION_TYPE'=>'Z115'])
                                    ->whereRaw("(CAST('$date' AS DATE) >= VALID_FROM AND CAST('$date' AS DATE) <= VALID_TO)")
                                    ->select(DB::raw('RENTAL_UNIT, CONTRACT, RENT_UNIT_DESC, BP_DESC, \'-\' AS ROOM_NO,
                                        SUM(
                                            CASE WHEN FREQUENCY_UNIT = 0
                                                THEN (REVENUE_AMOUNT/30)
                                            ELSE
                                                REVENUE_AMOUNT
                                            END
                                        )  AS REVENUE'
                                    ))->groupByRaw('RENTAL_UNIT, CONTRACT, RENT_UNIT_DESC, BP_DESC')->get()
                                    ->toArray();
                                    if($data_office_rental)
                                        $data['RENTAL_OBJECT_OCCUPIED'][$key]['Office / Retail Space'] = $data_office_rental;
                                }
                                else {
                                    $data_office_rental = DB::connection('dbayana-stg')
                                    ->table('dbo.Amortisation')
                                    ->where(['PROFIT_CENTER'=>$plant_lookup, 'CONTRACT_TYPE'=>'Z001', 'CONDITION_TYPE'=>'Z115'])
                                    ->whereRaw("(CAST('$date' AS DATE) >= VALID_FROM AND CAST('$date' AS DATE) <= VALID_TO)")
                                    ->select(DB::raw('RENTAL_UNIT, CONTRACT, RENT_UNIT_DESC, BP_DESC, \'-\' AS ROOM_NO,
                                        SUM(
                                            CASE WHEN FREQUENCY_UNIT = 0
                                                THEN (REVENUE_AMOUNT/30)
                                            ELSE
                                                REVENUE_AMOUNT
                                            END
                                        )  AS REVENUE'
                                    ))->groupByRaw('RENTAL_UNIT, CONTRACT, RENT_UNIT_DESC, BP_DESC')->get()
                                    ->toArray();
                                    if($data_office_rental)
                                        collect($data['RENTAL_OBJECT_OCCUPIED'][$key]['Office / Retail Space'])->concat($data_office_rental)->toArray();
                                }

                                // insert Rental object no contract to array
                                $data['RENTAL_OBJECT_OCCUPIED'][$key]['Apartment Non Contract / FIT'] = isset($data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]]) ? $data['RENTAL_OBJECT_NO_CONTRACT'][$company_to_lookup[$i]] : [];

                                $data['RENTAL_OBJECT_OCCUPIED'][$key] = collect($data['RENTAL_OBJECT_OCCUPIED'][$key])->mapWithKeys(function($item, $key){
                                    $sort = 0;
                                    if(strtolower($key) == 'apartment / residential')
                                        $sort = 1;
                                    else if(strtolower($key) == 'office / retail space')
                                        $sort = 3;
                                    else if(strtolower($key) == 'apartment non contract / fit')
                                        $sort = 2;
                                    return [$key=>array('SORT'=>$sort,'ITEM'=>$item)];
                                })->sortBy(function ($itemSort, $key) {
                                    return $itemSort['SORT'];
                                })->mapWithKeys(function ($item, $key){
                                    // Strip sort key and put item only
                                    return [$key=>$item['ITEM']];
                                })->toArray();
                            }
                        } catch(\Exception $e){
                            throw new Exception($e->getMessage());
                        }

                        $total_all = count($itemParent);
                        $total_occupied = count($occupied_apartment) + $rental_no_contract;
                        $total_vacant = $total_all - $total_occupied;
                        $total_occupancy_rate = ((int)$total_occupied / (int)$total_all) * 100; 
                        $summary = array(
                            'TOTAL_ALL'=>$total_all,
                            'TOTAL_VACANT'=>$total_vacant,
                            'TOTAL_OCCUPIED'=>$total_occupied,
                            'TOTAL_OCCUPANCY_RATE'=>$total_occupancy_rate,
                        );

                        $data['RENTAL_OBJECT_SUMMARY'][$key] = $summary;
                        return [$key=>$summary];
                    })->toArray();
                    
                    // Mulai mengirim email
                    foreach(array_keys($data['RENTAL_OBJECT_SUMMARY']) as $key_rental){
                        // Start PAD3 revenue
                        $new_data = [];
                        if(strtoupper(substr($key_rental, 0, 4)) == 'PAD2'){
                            // Untuk report revenue PAD3
                            $new_data['date'] = $data['date'];
                            $data['FNB'] = DB::connection('dbayana-dw')
                            ->select("EXEC dbo.usp_RptRevenueOutletEmail ?", array($data['date']));
                            if(count($data['FNB'])){
                                $data['FNB'] = collect($data['FNB'])
                                ->reject(function ($dataquery) {
                                    // Exclude selain KMS1 dan PPC1
                                    // return $dataquery->Resort != 'KMS1' && $dataquery->Resort != 'PPC1';
                                    return !in_array($dataquery->Resort, ['PAD3']);
                                })
                                ->filter(function($dataquery){
                                    // Filter FB aja
                                    $is_type_fb = isset($dataquery->FB_YN) ? $dataquery->FB_YN : '';
                                    return $is_type_fb == 'Y';
                                })
                                ->groupBy(function($dataquery){
                                    if(strtoupper($dataquery->LOCATIONNAME) == 'AYANA RESORT AND SPA BALI' || strtoupper($dataquery->LOCATIONNAME) == 'AYANA BALI')
                                         $dataquery->LOCATIONNAME = 'Ayana Rimba Bali';
                                    else 
                                         $dataquery->LOCATIONNAME = ucwords(strtolower($dataquery->LOCATIONNAME));
                                    return strtoupper($dataquery->Resort)." - ".$dataquery->LOCATIONNAME;
                                })->toArray();
                                $resort = array_keys($data['FNB']);
                                for($i=0;$i<count($resort);$i++){
                                    if(isset($data['FNB'][$resort[$i]])) {
                                        // Reinitialize data
                                        $new_data['FNB'][$resort[$i]] = $data['FNB'][$resort[$i]];
                                        // TODAY TOTAL PER COMPANY
                                        $today_revenue = collect($data['FNB'][$resort[$i]])->sum('REVENUE');
                                        $mtd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_MTD');
                                        $ytd_revenue = collect($data['FNB'][$resort[$i]])->sum('Revenue_YTD');

                                        $today_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST');
                                        $mtd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_MTD');
                                        $ytd_guest = collect($data['FNB'][$resort[$i]])->sum('GUEST_YTD');
                                        $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = 
                                        [
                                            'TODAY_REVENUE'=>$today_revenue,
                                            'MTD_REVENUE'=>$mtd_revenue,
                                            'YTD_REVENUE'=>$ytd_revenue,
                                            'TODAY_GUEST'=>$today_guest,
                                            'MTD_GUEST'=>$mtd_guest,
                                            'YTD_GUEST'=>$ytd_guest,
                                        ];
                                    } else {
                                        $new_data['FNB'][$resort[$i]] = [];
                                        $new_data['TOTAL_REVENUE_FNB'][$resort[$i]] = [];
                                    }
                                }
                            }
                        }
                        // End untuk PAD3 revenue

                        Mail::send('pages.report.email.report_rental_object', ['data'=>
                            [
                                'RENTAL_OBJECT_SUMMARY' => array($key_rental=>isset($data['RENTAL_OBJECT_SUMMARY'][$key_rental]) ? $data['RENTAL_OBJECT_SUMMARY'][$key_rental] : []),
                                'RENTAL_OBJECT_TOTAL_REVENUE' => array($key_rental=>isset($data['RENTAL_OBJECT_TOTAL_REVENUE'][$key_rental]) ? $data['RENTAL_OBJECT_TOTAL_REVENUE'][$key_rental] : []),
                                'RENTAL_OBJECT_OCCUPIED' => array($key_rental=>isset($data['RENTAL_OBJECT_OCCUPIED'][$key_rental]) ? $data['RENTAL_OBJECT_OCCUPIED'][$key_rental] : []),
                                'date'=>$data['date'],

                            ],
                            'new_data_fnb'=>$new_data
                        ], function ($message) use ($data, $key_rental) {
                            $plant_lookup = substr($key_rental, 0, 4);
                            $sendto = DB::connection('dbintranet')
                            ->table('dbo.VIEW_REPORT_EMAIL_DAILY_INTRANET')
                            ->where('GROUP_TYPE', $plant_lookup)
                            ->get()->pluck('EMAIL', 'EMAIL')->filter()->toArray();

                            $subject = $key_rental." - Daily Revenue - ".date('d M Y',strtotime($data['date'])).' - '.date('H:i');
                            $message->subject($subject);
                            $message->from('ayanareport@ayana.com', config('intranet.MAIL_FROM'));
                            $message->to($sendto);
                            // $message->to([]);
                            $message->bcc([
                              'mahendra_permana@biznetnetworks.com',
                              'andy.susanto@midplaza.com',
                              'yudhi.arimbawan@midplaza.com',
                              'roy_putra@biznetnetworks.com'
                            ]);
                            echo "Sukses kirim rental object Company '$key_rental'<br>\n\n";
                        });
                    }

                } else {
                    echo "Tidak ada data untuk Company '$company_to_lookup[$i]'<br>\n\n";
                }
            } catch(SapException $e){
                Log::error($e->getMessage());
                return response()->json(['status'=>'error', 'exception_type'=>'SAP Exception', 'message'=>$e->getMessage(), 'trace'=>$e]);
                // dd($e->getMessage(), $filter_list);
            } catch(\Exception $e){
                Log::error($e->getMessage());
                return response()->json(['status'=>'error', 'exception_type'=>'General Exception', 'message'=>$e->getMessage(), 'trace'=>$e]);
                // dd($e->getMessage());
            }
        }
    }

    function summary_and_rental($company_code=''){
        $date = date('Y-m-d');
        $data['date'] = date('Y-m-d',strtotime ( '-1 day' , strtotime ($date) ));
        try {
            $is_production = config('intranet.is_production');
            if($is_production)
                $connection = new SapConnection(config('intranet.rfc_prod'));
            else
                $connection = new SapConnection(config('intranet.rfc_dev'));

            $options = [
                'rtrim'=>true,
            ];

            $filter_list = [
                'P_RENT_OBJ' => "",
                'P_CONTRACT_NUM' => "",
                'P_COMPANY' => "$company_code",
                'P_BUSINES_ENTITY_NUM' => "",
            ];

            $data['RENTAL_OBJECT_SUMMARY'] = [];
            $data['RENTAL_OBJECT_OCCUPIED'] = [];
            $data['RENTAL_OBJECT_TOTAL_REVENUE'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED'] = [];
            $data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'] = [];
            
            $data['FILTER'] = $filter_list;
            
            $function = $connection->getFunction('ZFM_MM_IM_RE_FX_REPORT_V2');
            $result= $function->invoke($filter_list, $options);
            $measurement_data = isset($result['GT_MEASUREMENT_RENTAL']) ? collect($result['GT_MEASUREMENT_RENTAL'])->groupBy(['OBJECT_ID','OBJECT_TYPE']) : [];
                
            if(count(array_keys($result)) > 0 && in_array('GT_REPORT', array_keys($result))){
                $excluded_rental = [
                    'W2010131',
                    'W2010132',
                    'W2010133',
                    'W2010134',
                    'W2010135',
                    'W2010137',
                    'W2020131',
                    'W2020132',
                    'W2020133',
                    'W2020134'
                ];

                $data['RENTAL_OBJECT_DETAIL'] = collect($result['GT_REPORT'])->groupBy('BUSINESSENTITYNUMBER')->mapWithKeys(function($item, $keyPlant) use ($measurement_data, &$data, $excluded_rental){
                    $newKeyPlant = $keyPlant;
                    try {
                        $plant_desc = DB::connection('dbintranet')
                        ->table('dbo.INT_BUSINESS_PLANT')
                        ->where('SAP_PLANT_ID', $keyPlant)
                        ->select('SAP_PLANT_NAME')
                        ->get()->first();
                        if($plant_desc){
                            $newKeyPlant = $keyPlant." - ".ucwords(strtolower($plant_desc->SAP_PLANT_NAME));
                        }

                    } catch(\Exception $e){}

                    // Exclude yang bukan merupakan commercial space
                    $sort_data = $item->groupBy(['SAPRENTALOBJECTID','OBJECT_ID','CONDITIONTYPE'])
                    ->reject(function($dataRental, $key) use ($excluded_rental){
                        return in_array($key, $excluded_rental);
                    })->toArray();

                    return [$newKeyPlant=>$sort_data];
                })->toArray();

                $data['RENTAL_OBJECT_SUMMARY'] = collect($data['RENTAL_OBJECT_DETAIL'])->mapWithKeys(function($itemParent, $key) use (&$data){
                    $date = $data['date'];
                    $plant_lookup = substr($key, 0, 4);
                    $occupied_apartment = DB::connection('dbayana-stg')
                    ->select("
                        SELECT ROOM_NO, UDFC09 AS CONTRACT_NO
                        FROM 
                        ( 
                            SELECT ROOM_NO, UDFC09,
                                         ROW_NUMBER() OVER (PARTITION BY room_no order by udfc09 DESC)     --- ORDER BY NULL
                                           AS rn                                   --- for example
                                  FROM reservationdailystat
                            WHERE RESV_STATUS = 'CHECKED IN' AND BUSINESS_DATE='$date' AND resort=CONCAT(LEFT('$plant_lookup', 3),'',1) AND ROOM_CLASS='ZZAPT' OR RESV_STATUS='CHECKED OUT' AND DUE_OUT_YN<>'N' AND BUSINESS_DATE='$date' AND resort=CONCAT(LEFT('$plant_lookup', 3),'',1) AND ROOM_CLASS='ZZAPT'
                        ) tmp 
                        WHERE rn = 1 AND UDFC09 IS NOT NULL
                        ORDER BY ROOM_NO ASC
                    ");
                    try {
                        $detail_occupied = DB::connection('dbayana-dw')
                        ->select("EXEC dbo.usp_RptRentalObject_GetData ?, ?", array($plant_lookup, $date));
                        if($detail_occupied){
                            $data['RENTAL_OBJECT_TOTAL_REVENUE'][$key] = collect($detail_occupied)->sum('REVENUE');
                            $data['RENTAL_OBJECT_OCCUPIED'][$key] = collect($detail_occupied)->groupBy(function($item){
                                $group_contract = $item->CONTRACT_TYPE_DESC;
                                if($item->CONTRACT_TYPE == 'Z002')
                                    $group_contract = 'Apartment / Residential';
                                else if($item->CONTRACT_TYPE = 'Z001')
                                    $group_contract = 'Office / Retail Space';
                                return $group_contract;
                            })->toArray();

                            if(!in_array('Office / Retail Space', array_keys($data['RENTAL_OBJECT_OCCUPIED'][$key]))){
                                $data_office_rental = DB::connection('dbayana-stg')
                                ->table('dbo.Amortisation')
                                ->where(['PROFIT_CENTER'=>$plant_lookup, 'CONTRACT_TYPE'=>'Z001', 'CONDITION_TYPE'=>'Z115'])
                                ->whereRaw('(GETDATE() >= VALID_FROM AND GETDATE() <= VALID_TO)')
                                ->select('RENTAL_UNIT', 'CONTRACT', 'RENT_UNIT_DESC', 'BP_DESC', 'REVENUE_AMOUNT AS REVENUE', DB::raw('\'-\' AS ROOM_NO'))->get()->toArray();
                                if($data_office_rental)
                                    $data['RENTAL_OBJECT_OCCUPIED'][$key]['Office / Retail Space'] = $data_office_rental;
                            }
                            else {
                                $data_office_rental = DB::connection('dbayana-stg')
                                ->table('dbo.Amortisation')
                                ->where(['PROFIT_CENTER'=>$plant_lookup, 'CONTRACT_TYPE'=>'Z001', 'CONDITION_TYPE'=>'Z115'])
                                ->whereRaw('(GETDATE() >= VALID_FROM AND GETDATE() <= VALID_TO)')
                                ->select('RENTAL_UNIT', 'CONTRACT', 'RENT_UNIT_DESC', 'BP_DESC', 'REVENUE_AMOUNT AS REVENUE', DB::raw('\'-\' AS ROOM_NO'))->get()->toArray();
                                if($data_office_rental)
                                    collect($data['RENTAL_OBJECT_OCCUPIED'][$key]['Office / Retail Space'])->concat($data_office_rental)->toArray();
                            }
                        }
                    } catch(\Exception $e){}
                    // dd($occupied_apartment, $plant_lookup);

                    $total_all = count($itemParent);
                    $total_occupied = count($occupied_apartment);
                    $total_vacant = $total_all - $total_occupied;
                    $total_occupancy_rate = ((int)$total_occupied / (int)$total_all) * 100; 
                    $summary = array(
                        'TOTAL_ALL'=>$total_all,
                        'TOTAL_VACANT'=>$total_vacant,
                        'TOTAL_OCCUPIED'=>$total_occupied,
                        'TOTAL_OCCUPANCY_RATE'=>$total_occupancy_rate
                    );
                    array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL'], $total_all);
                    array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT'], $total_vacant);
                    array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED'], $total_occupied);
                    if($total_occupancy_rate && $total_occupancy_rate != 0)
                        array_push($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'], $total_occupancy_rate);
                    return [$key=>$summary];
                })->toArray();
                // dd($data);
            }   
        } catch(SapException $e){
            Log::error($e->getMessage());
            return ['status'=>'error', 'exception_type'=>'SAP Exception', 'message'=>$e->getMessage(), 'trace'=>$e];
        } catch(\Exception $e){
            Log::error($e->getMessage());
            return ['status'=>'error', 'exception_type'=>'General Exception', 'message'=>$e->getMessage(), 'trace'=>$e];
        }

        // dd($data);
        return ['status'=>'success', 'message'=>'Success retrieving data', 'data'=>$data];
    }
}
