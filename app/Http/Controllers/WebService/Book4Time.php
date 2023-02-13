<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;

class Book4Time extends Controller
{
    public function summary(Request $request){
        // if(Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl', array(), false)) || isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER > 0){
            // $last_change = $request->get('last_change', false);
            // $plant = $request->get('plant', "");
            // $status = $request->get('status', "");
            // $material = $request->get('material', "");

            try {
                // if($last_change)
                     //$last_change = date('Y-m-d', strtotime($last_change));
                // else 
                //     $last_change = date('Y-m-d');

                $data_book4time = [];
                $date_query = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
                 //$param = json_encode(array('WERKS'=>$plant,'MATNR'=>$material, 'RSTAT'=>$status, 'AEDAT'=>$last_change));

                // $param=urlencode($param);
                $url = config('intranet.BOOK4TIME')."?startDate=${date_query}&endDate=${date_query}&transactionTypes=";
                $client = new \GuzzleHttp\Client();
                $header = ['headers' => [
                    "AccountToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_ACCOUNT_TOKEN'),
                    "ApiToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_API_TOKEN')
                ]];
                //dd($header);
                $res = $client->get($url, $header);
                $data_book4time = json_decode($res->getBody()->getContents(), true);
                //dd($data_book4time);
                if(is_array($data_book4time) && count($data_book4time)){
                    // Insert data 100 / total;
                     //$chunk_data_json = array_chunk($data_book4time, 100);
                     $countdata=1;
                    try {
                        //DB::connection('dbayana-stg')->table('RECIPE_COST_HEADER')->where(['AEDAT'=>$last_change, 'WERKS'=>$plant, 'MATNR'=>$material])->delete();
                       $data_insert=[];
                        for($i=0;$i<count($data_book4time);$i++){
                            for ($j=0;$j<count($data_book4time[$i]['tenders']);$j++){
                                //$data_book4time[$i]['insert_date'] = date('Y-m-d H:i:s');
                                $data_insert['CHECKNUM']= $data_book4time[$i]['transaction_id'];
                                $data_insert['LOCATION_ID']= $data_book4time[$i]['location_id'];
                                $data_insert['TRANSACTION_TYPE']= $data_book4time[$i]['transaction_type'];
                                $data_insert['CUSTOMER_ID']= $data_book4time[$i]['customer_id'];
                                $data_insert['BUSINESSDATE']= $data_book4time[$i]['date'];
                                $data_insert['CURRENCY']= $data_book4time[$i]['currency'];
                                $data_insert['REVENUE_CENTER_CODE']= $data_book4time[$i]['revenue_center_code'];
                                $data_insert['UPDATED_BY']= $data_book4time[$i]['updatedBy'];
                                $data_insert['TENDER_NAME']= $data_book4time[$i]['tenders'][$j]['tender_name'];
                                $data_insert['TOTAL_SALE']= $data_book4time[$i]['tenders'][$j]['tender_amount'];
                                $data_insert['BOF_CODE']= $data_book4time[$i]['tenders'][$j]['bof_code'];
                                $data_insert['INSERT_DATE']= date('Y-m-d');

                                //dd(count($data_insert));
                                                                
                                DB::connection('dbayana-stg')->beginTransaction();
                                try {
                                    $data_id = DB::connection('dbayana-stg')->table('Book4TimeHeader')->insertGetId($data_insert);
                                    // $item_new = array_map(function($val) use ($data_id) { $val['HEADER_ID'] = (String)$data_id; $val['WENGE'] = (float)$val['WENGE']; $val['PCOST'] = (float)$val['PCOST']; return $val; }, $item);
                                    // DB::connection('dbayana-stg')->table('RECIPE_COST_ITEM')->insert($item_new);
                                    DB::connection('dbayana-stg')->commit();
                                    // all good
                                } catch (\Exception $e) {
                                    DB::connection('dbayana-stg')->rollback();
                                    throw new \Exception($e->getMessage());
                                    // something went wrong
                                }
                                // DB::connection('dbayana-stg')->transaction(function() use (&$database, &$item) {
                                // });
                            }
                             $countdata+=1;
                        }

                    } catch(\Exception $e){
                        throw new \Exception($e->getMessage());
                    }

                    return response()->json(['status'=>'success', 'message'=>'Data inserted successfully','row_inserted'=>$countdata], 200);
                } else {
                    return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_boo4time'=>$data_book4time], 200);
                }

            } catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
            }
        // } else {
            // return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        // }
    }

    public function detail(Request $request){
        ini_set('max_execution_time', 2000);
        // if(Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl', array(), false)) || isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER > 0){
            // $last_change = $request->get('last_change', false);
            // $plant = $request->get('plant', "");
            // $status = $request->get('status', "");
            // $material = $request->get('material', "");

            try {
                // if($last_change)
                     //$last_change = date('Y-m-d', strtotime($last_change));
                // else 
                //     $last_change = date('Y-m-d');

                $data_book4time = [];
                //$date_query = date('2022-09-01');
                
                $date_query = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
                 //$param = json_encode(array('WERKS'=>$plant,'MATNR'=>$material, 'RSTAT'=>$status, 'AEDAT'=>$last_change));

                // $param=urlencode($param);
                $url = config('intranet.BOOK4TIME')."?startDate=${date_query}&endDate=${date_query}&transactionTypes=";
                $client = new \GuzzleHttp\Client();
                $header = ['headers' => [
                    "AccountToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_ACCOUNT_TOKEN'),
                    "ApiToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_API_TOKEN')
                ]];
                //dd($header);
                $res = $client->get($url, $header);
                $data_book4time = json_decode($res->getBody()->getContents(), true);
                //dd($data_book4time);
                if(is_array($data_book4time) && count($data_book4time)){
                    // Insert data 100 / total;
                    // $chunk_data_json = array_chunk($data_book4time, 100);
                    $countdata=1;
                    try {
                        //DB::connection('dbayana-stg')->table('RECIPE_COST_HEADER')->where(['AEDAT'=>$last_change, 'WERKS'=>$plant, 'MATNR'=>$material])->delete();
                       $data_insert=[];
                        for($i=0;$i<count($data_book4time);$i++){
                            for ($j=0;$j<count($data_book4time[$i]['lines']);$j++){
                                
                                    $data_insert['CHECKNUM']= $data_book4time[$i]['transaction_id'];
                                    $data_insert['SAPMATERIAL_CODE']= $data_book4time[$i]['lines'][$j]['sku'];
                                    $data_insert['DESCRIPTION']= $data_book4time[$i]['lines'][$j]['description'];
                                    $data_insert['PRODUCT_CLASS']= $data_book4time[$i]['lines'][$j]['product_class'];
                                    $data_insert['PRODUCT_SUBCLASS']= $data_book4time[$i]['lines'][$j]['product_subclass'];
                                    $data_insert['PROVIDER_ID']= $data_book4time[$i]['lines'][$j]['provider_id'];
                                    $data_insert['PROVIDER_NAME']= $data_book4time[$i]['lines'][$j]['provider_name'];
                                    $data_insert['QTY']= $data_book4time[$i]['lines'][$j]['quantity'];
                                    $data_insert['PRICE']= $data_book4time[$i]['lines'][$j]['sold_price'];
                                    $data_insert['COMMISION']= $data_book4time[$i]['lines'][$j]['total_commission'];
                                    $data_insert['DISCOUNT']= $data_book4time[$i]['lines'][$j]['discountAmount'];
                                    $data_insert['DISCOUNT_DESC']= $data_book4time[$i]['lines'][$j]['discountDescription'];
                                    $data_insert['DISCOUNT_ID']= $data_book4time[$i]['lines'][$j]['discountId'];
                                    $data_insert['SERVICE_ID']= $data_book4time[$i]['lines'][$j]['serviceId'];
                                    $data_insert['ORIGINAL_PRICE']= $data_book4time[$i]['lines'][$j]['originalPrice'];
                                    $data_insert['PRODUCT_ID']= $data_book4time[$i]['lines'][$j]['productId'];
                                    $data_insert['COUPON_ID']= $data_book4time[$i]['lines'][$j]['couponId'];
                                    $data_insert['COUPON_NAME']= $data_book4time[$i]['lines'][$j]['couponName'];
                                    $data_insert['CHECK_INFO']= $data_book4time[$i]['lines'][$j]['promptInformation'];
                                    $data_insert['INSERT_DATE']= date('Y-m-d');
                                   
                                //dd(count($data_insert));
                                
                                
                                DB::connection('dbayana-stg')->beginTransaction();
                                try {
                                    $data_id = DB::connection('dbayana-stg')->table('Book4TimeDetail')->insertGetId($data_insert);
                                    // $item_new = array_map(function($val) use ($data_id) { $val['HEADER_ID'] = (String)$data_id; $val['WENGE'] = (float)$val['WENGE']; $val['PCOST'] = (float)$val['PCOST']; return $val; }, $item);
                                    // DB::connection('dbayana-stg')->table('RECIPE_COST_ITEM')->insert($item_new);
                                    DB::connection('dbayana-stg')->commit();
                                    // all good
                                } catch (\Exception $e) {
                                    DB::connection('dbayana-stg')->rollback();
                                    throw new \Exception($e->getMessage());
                                    // something went wrong
                                }
                                // DB::connection('dbayana-stg')->transaction(function() use (&$database, &$item) {
                                // });
                                $countdata+=1;
                                
                            }
                            
                        }
                    } catch(\Exception $e){
                        throw new \Exception($e->getMessage());
                    }

                    return response()->json(['status'=>'success', 'message'=>'Data Detail inserted successfully','row_inserted'=>$countdata], 200);
                } else {
                    return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_boo4time'=>$data_book4time], 200);
                }

            } catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
            }
        // } else {
            // return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        // }
    }

    public function tax(Request $request){
        ini_set('max_execution_time', 2000);
        // if(Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl', array(), false)) || isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER > 0){
            // $last_change = $request->get('last_change', false);
            // $plant = $request->get('plant', "");
            // $status = $request->get('status', "");
            // $material = $request->get('material', "");

            try {
                // if($last_change)
                     //$last_change = date('Y-m-d', strtotime($last_change));
                // else 
                //     $last_change = date('Y-m-d');

                $data_book4time = [];
                //$date_query = date('2022-09-01');
                
                $date_query = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
                 //$param = json_encode(array('WERKS'=>$plant,'MATNR'=>$material, 'RSTAT'=>$status, 'AEDAT'=>$last_change));

                // $param=urlencode($param);
                $url = config('intranet.BOOK4TIME')."?startDate=${date_query}&endDate=${date_query}&transactionTypes=";
                $client = new \GuzzleHttp\Client();
                $header = ['headers' => [
                    "AccountToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_ACCOUNT_TOKEN'),
                    "ApiToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_API_TOKEN')
                ]];
                //dd($header);
                $res = $client->get($url, $header);
                $data_book4time = json_decode($res->getBody()->getContents(), true);
                //dd($data_book4time);
                if(is_array($data_book4time) && count($data_book4time)){
                    // Insert data 100 / total;
                    // $chunk_data_json = array_chunk($data_book4time, 100);
                   // $countdata=1;
                    try {
                        //DB::connection('dbayana-stg')->table('RECIPE_COST_HEADER')->where(['AEDAT'=>$last_change, 'WERKS'=>$plant, 'MATNR'=>$material])->delete();
                       $data_insert=[];
                        for($i=0;$i<count($data_book4time);$i++){
                            for ($j=0;$j<count($data_book4time[$i]['lines']);$j++){
                                for ($k=0;$k<count($data_book4time[$i]['lines'][$j]['taxes']);$k++){
                                    if($data_book4time[$i]['lines'][$j]['taxes'][$k]['amount']>0){
                                        $data_insert['CHECKNUM']= $data_book4time[$i]['transaction_id'];
                                        $data_insert['TAX']= $data_book4time[$i]['lines'][$j]['taxes'][$k]['amount'];
                                        $data_insert['TAX_DESC']= $data_book4time[$i]['lines'][$j]['taxes'][$k]['bof_code'];
                                        $data_insert['INSERT_DATE']= date('Y-m-d');
                                                                    
                                            
                                        DB::connection('dbayana-stg')->beginTransaction();
                                        try {
                                            $data_id = DB::connection('dbayana-stg')->table('Book4TimeTaxDetail')->insertGetId($data_insert);
                                            // $item_new = array_map(function($val) use ($data_id) { $val['HEADER_ID'] = (String)$data_id; $val['WENGE'] = (float)$val['WENGE']; $val['PCOST'] = (float)$val['PCOST']; return $val; }, $item);
                                            // DB::connection('dbayana-stg')->table('RECIPE_COST_ITEM')->insert($item_new);
                                            DB::connection('dbayana-stg')->commit();
                                            // all good
                                        } catch (\Exception $e) {
                                            DB::connection('dbayana-stg')->rollback();
                                            throw new \Exception($e->getMessage());
                                            // something went wrong
                                        }
                                        // DB::connection('dbayana-stg')->transaction(function() use (&$database, &$item) {
                                        // });
                                        
                                    }
                                    //$countdata+=1;
                                }
                            }
                            
                        }
                    } catch(\Exception $e){
                        throw new \Exception($e->getMessage());
                    }

                    return response()->json(['status'=>'success', 'message'=>'Data Detail inserted successfully'], 200);
                } else {
                    return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_boo4time'=>$data_book4time], 200);
                }

            } catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
            }
        // } else {
            // return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        // }
    }

    public function appointment(Request $request){
            ini_set('max_execution_time', 2000);

            try {
                // if($last_change)
                     //$last_change = date('Y-m-d', strtotime($last_change));
                // else 
                //     $last_change = date('Y-m-d');

                $data_book4time = [];
                $max_date=30;
                $countdata=1;
                for($u=0;$u<$max_date;$u++){
                    // if($u==1)   
                    //     dd($u);
                   $date_query = mktime(0,0,0,date("m"),date("d")+$u,date("Y"));
                   $date_query=date("Y-m-d", $date_query);  
                    //$date_query='2022-09-18';                 
                    $location_id=8259675;
                    //$location_id='8677657';
                    //$param = json_encode(array('WERKS'=>$plant,'MATNR'=>$material, 'RSTAT'=>$status, 'AEDAT'=>$last_change));

                    // $param=urlencode($param);
                    $url = config('intranet.BOOK4TIME_APPOINTMENT')."?date=${date_query}&locationId=${location_id}";
                    $client = new \GuzzleHttp\Client();
                    $header = ['headers' => [
                        "AccountToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_ACCOUNT_TOKEN'),
                        "ApiToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_API_TOKEN'),
                        "UserToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_USER_TOKEN')
                    ]];
                    //dd($url);
                    $res = $client->get($url, $header);
                    $data_book4time = json_decode($res->getBody()->getContents(), true);
                    
                    
                    if(is_array($data_book4time) && count($data_book4time)){
                        
                        
                        try {
                            DB::connection('dbayana-stg')->table('Book4TimeAppointment')->where(['SCHEDULLER_DATE'=>$date_query, 'LOCATIONID'=>$location_id])->delete();
                        $data_insert=[];
                            for($i=0;$i<count($data_book4time);$i++){
                            
                                    $data_insert['INSERT_DATE']= date('Y-m-d H:i:s');
                                    $data_insert['APPOINTMENT_ID']= $data_book4time[$i]['appointmentId'];
                                    $data_insert['SCHEDULLER_DATE']= $data_book4time[$i]['schedulerDate'];
                                    $data_insert['BOOKED_DATE']= $data_book4time[$i]['bookedDate'];
                                    $data_insert['CUSTOMER_ID']= $data_book4time[$i]['customerId'];
                                    $data_insert['PREFIX']= $data_book4time[$i]['prefix'];
                                    $data_insert['LASTNAME']= $data_book4time[$i]['lastname'];
                                    $data_insert['FIRSTNAME']= $data_book4time[$i]['firstname'];
                                    $data_insert['FULL_NAME']= $data_book4time[$i]['customerFullName'];
                                    $data_insert['SEX']= $data_book4time[$i]['sex'];
                                    $data_insert['BIRTHDATE']= $data_book4time[$i]['birthdate'];
                                    $data_insert['GUEST_TYPE_SHORTCODE']= $data_book4time[$i]['guestTypeShortCode'];
                                    $data_insert['TECHNICIAN_ID']= $data_book4time[$i]['technicianId'];
                                    $data_insert['SERVICE_TYPE_NAME']= $data_book4time[$i]['serviceTypeName'];
                                    $data_insert['SERVICE_ID']= $data_book4time[$i]['serviceId'];
                                    $data_insert['PRICE']= $data_book4time[$i]['price'];
                                    $data_insert['STATUS']= $data_book4time[$i]['status'];
                                    $data_insert['REQUEST_TYPE_ID']= $data_book4time[$i]['requestTypeId'];
                                    $data_insert['DURATION']= $data_book4time[$i]['duration'];
                                    $data_insert['NOTE']= $data_book4time[$i]['note'];
                                    $data_insert['INTERNAL_NOTE']= $data_book4time[$i]['internalNotes'];
                                    $data_insert['FLAGS']= $data_book4time[$i]['flags'];
                                    $data_insert['IS_GROUP_BOOKING']= $data_book4time[$i]['isGroupBooking'];
                                    $data_insert['IS_RESERVATION']= $data_book4time[$i]['isReservation'];
                                    $data_insert['HAS_FACILITY']= $data_book4time[$i]['hasFacility'];
                                    $data_insert['FACILITY_NAME']= $data_book4time[$i]['facilityName'];
                                    $data_insert['IS_FACILITY_LOCKED']= $data_book4time[$i]['isFacilityLocked'];
                                    $data_insert['IS_PACKAGE_BOOKING']= $data_book4time[$i]['isPackageBooking'];
                                    $data_insert['CUSTOMER_HAS_MEMBERSHIP']= $data_book4time[$i]['customerHasMembership'];
                                    $data_insert['IS_RECURRENT_BOOKING']= $data_book4time[$i]['isRecurrentBooking'];
                                    $data_insert['IS_HOTEL_GUEST']= $data_book4time[$i]['isHotelGuest'];
                                    $data_insert['IS_RECONFIRMED']= $data_book4time[$i]['isReconfirmed'];
                                    $data_insert['IS_CANCELED']= $data_book4time[$i]['isCanceled'];
                                    $data_insert['IS_CONFIRMED']= $data_book4time[$i]['isConfirmed'];
                                    $data_insert['IS_CLOSED']= $data_book4time[$i]['isClosed'];
                                    $data_insert['IS_BOOKED_ONLINE']= $data_book4time[$i]['isBookedOnline'];
                                    $data_insert['LOCATIONID']= $data_book4time[$i]['locationId'];
                                    $data_insert['IS_GUARANTEED']= $data_book4time[$i]['isGuaranteed'];
                                    //dd(count($data_insert));
                                    
                                    
                                    DB::connection('dbayana-stg')->beginTransaction();
                                    try {
                                        $data_id = DB::connection('dbayana-stg')->table('Book4TimeAppointment')->insertGetId($data_insert);
                                        // $item_new = array_map(function($val) use ($data_id) { $val['HEADER_ID'] = (String)$data_id; $val['WENGE'] = (float)$val['WENGE']; $val['PCOST'] = (float)$val['PCOST']; return $val; }, $item);
                                        // DB::connection('dbayana-stg')->table('RECIPE_COST_ITEM')->insert($item_new);
                                        DB::connection('dbayana-stg')->commit();
                                        // all good
                                    } catch (\Exception $e) {
                                        DB::connection('dbayana-stg')->rollback();
                                        throw new \Exception($e->getMessage());
                                        // something went wrong
                                    }
                                    // DB::connection('dbayana-stg')->transaction(function() use (&$database, &$item) {
                                    // });
                                
                                $countdata+=1;
                            }
                            
                        } catch(\Exception $e){
                            dd($e);
                            throw new \Exception($e->getMessage());
                        }

                        
                    } 
                        
                    }
                    
                        return response()->json(['status'=>'success', 'message'=>'Data inserted successfully','row_inserted'=>$countdata], 200);
                        //return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_boo4time'=>$data_book4time], 200);

                    }   catch(\Exception $e){
                        dd($e);
                        return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
                    }
            
        // } else {
            // return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        // }
    }

    
    
    public function MasterGuestType(Request $request){
        // if(Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl', array(), false)) || isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER > 0){
            // $last_change = $request->get('last_change', false);
            // $plant = $request->get('plant', "");
            // $status = $request->get('status', "");
            // $material = $request->get('material', "");

            try {
                // if($last_change)
                     //$last_change = date('Y-m-d', strtotime($last_change));
                // else 
                //     $last_change = date('Y-m-d');

                $data_book4time = [];
                $date_query = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));
                 //$param = json_encode(array('WERKS'=>$plant,'MATNR'=>$material, 'RSTAT'=>$status, 'AEDAT'=>$last_change));

                // $param=urlencode($param);
                $url = config('intranet.BOOK4TIME_GUEST_TYPE');
                $client = new \GuzzleHttp\Client();
                $header = ['headers' => [
                    "AccountToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_ACCOUNT_TOKEN'),
                    "ApiToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_API_TOKEN')
                    
                ]];
                //dd($url);
                $res = $client->get($url, $header);
                $data_book4time = json_decode($res->getBody()->getContents(), true);
                //dd($data_book4time);
                if(is_array($data_book4time) && count($data_book4time)){
                    // Insert data 100 / total;
                     //$chunk_data_json = array_chunk($data_book4time, 100);
                     $countdata=1;
                    try {
                        DB::connection('dbayana-stg')->table('Book4TimeGuestType')->delete();
                       $data_insert=[];
                        for($i=0;$i<count($data_book4time);$i++){
                                                           
                            $data_insert['GUEST_TYPE_ID']= $data_book4time[$i]['guestTypeId'];
                            $data_insert['DESCRIPTION']= $data_book4time[$i]['description'];
                            $data_insert['IS_DEFAULT']= $data_book4time[$i]['isDefault'];
                            $data_insert['SHORT_CODE']= $data_book4time[$i]['shortCode'];
                            
                            //dd(count($data_insert));
                                                            
                            DB::connection('dbayana-stg')->beginTransaction();
                            try {
                                $data_id = DB::connection('dbayana-stg')->table('Book4TimeGuestType')->insertGetId($data_insert);
                                // $item_new = array_map(function($val) use ($data_id) { $val['HEADER_ID'] = (String)$data_id; $val['WENGE'] = (float)$val['WENGE']; $val['PCOST'] = (float)$val['PCOST']; return $val; }, $item);
                                // DB::connection('dbayana-stg')->table('RECIPE_COST_ITEM')->insert($item_new);
                                DB::connection('dbayana-stg')->commit();
                                // all good
                            } catch (\Exception $e) {
                                DB::connection('dbayana-stg')->rollback();
                                throw new \Exception($e->getMessage());
                                // something went wrong
                            }
                            // DB::connection('dbayana-stg')->transaction(function() use (&$database, &$item) {
                            // });
                        
                            $countdata+=1;
                        }

                    } catch(\Exception $e){
                        throw new \Exception($e->getMessage());
                    }

                    return response()->json(['status'=>'success', 'message'=>'Data inserted successfully','row_inserted'=>$countdata], 200);
                } else {
                    return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_boo4time'=>$data_book4time], 200);
                }

            } catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
            }
        // } else {
            // return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        // }
    }

    public function MasterCustomer(Request $request){
        ini_set('max_execution_time', 2000);
        // if(Session::get('permission_menu') && Session::get('permission_menu')->has("update_".route('sap.report.pnl', array(), false)) || isset(Session::get('user_data')->IS_SUPERUSER) && Session::get('user_data')->IS_SUPERUSER > 0){
            // $last_change = $request->get('last_change', false);
            // $plant = $request->get('plant', "");
            // $status = $request->get('status', "");
            // $material = $request->get('material', "");

            try {

                $customer_data = DB::connection('dbayana-stg')
                ->select('EXEC dbo.GetBook4TimeCust');
                //dd($customer_data);
                $data_book4time = [];
                for($u=0;$u<count($customer_data);$u++){
                    $customerid=$customer_data[$u]->customer_id;
                    //dd($customerid);    
                    // $param=urlencode($param);
                    $url = config('intranet.BOOK4TIME_CUSTOMER')."?id=${customerid}";
                    
                    
                    $client = new \GuzzleHttp\Client();
                    $header = ['headers' => [
                        "AccountToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_ACCOUNT_TOKEN'),
                        "ApiToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_API_TOKEN'),
                        "UserToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_USER_TOKEN')
                    ]];
                    
                    $res = $client->get($url, $header);
                    $data_book4time = json_decode($res->getBody()->getContents(), true);
                    //dd($data_book4time);                    
                    if(is_array($data_book4time) && count($data_book4time)){
                        // Insert data 100 / total;
                        //$chunk_data_json = array_chunk($data_book4time, 100);
                        $countdata=1;
                        try {
                            DB::connection('dbayana-stg')->table('Book4TimeCustomer')->where(['CUSTOMER_ID'=>$customerid])->delete();
                        $data_insert=[];
                            
                                                            
                                $data_insert['CUSTOMER_ID']= $data_book4time['id'];
                                $data_insert['FIRST_NAME']= $data_book4time['firstname'];
                                $data_insert['LAST_NAME']= $data_book4time['lastname'];
                                $data_insert['FULL_NAME']= $data_book4time['fullname'];
                                $data_insert['SEX']= $data_book4time['sex'];
                                $data_insert['PREFIX']= $data_book4time['prefix'];
                                $data_insert['FIRST_VISIT']= $data_book4time['firstVisit'];
                                $data_insert['LAST_VISIT']= $data_book4time['lastVisit'];
                                $data_insert['GROUP']= $data_book4time['group'];
                                
                                //dd(count($data_insert));
                                                                
                                DB::connection('dbayana-stg')->beginTransaction();
                                try {
                                    $data_id = DB::connection('dbayana-stg')->table('Book4TimeCustomer')->insertGetId($data_insert);
                                    // $item_new = array_map(function($val) use ($data_id) { $val['HEADER_ID'] = (String)$data_id; $val['WENGE'] = (float)$val['WENGE']; $val['PCOST'] = (float)$val['PCOST']; return $val; }, $item);
                                    // DB::connection('dbayana-stg')->table('RECIPE_COST_ITEM')->insert($item_new);
                                    DB::connection('dbayana-stg')->commit();
                                    // all good
                                } catch (\Exception $e) {
                                    DB::connection('dbayana-stg')->rollback();
                                    throw new \Exception($e->getMessage());
                                    // something went wrong
                                }
                                // DB::connection('dbayana-stg')->transaction(function() use (&$database, &$item) {
                                // });
                            
                                $countdata+=1;
                            

                        } catch(\Exception $e){
                            throw new \Exception($e->getMessage());
                        }

                        
                    }
                }   
                return response()->json(['status'=>'success', 'message'=>'Data inserted successfully','row_inserted'=>$countdata], 200);            
            } catch(\Exception $e){
                return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
            }
        // } else {
            // return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        // }
    }


    public function appointmentABR(Request $request){
            ini_set('max_execution_time', 2000);

            try {
                // if($last_change)
                     //$last_change = date('Y-m-d', strtotime($last_change));
                // else 
                //     $last_change = date('Y-m-d');

                $data_book4time = [];
                $max_date=30;
                $countdata=1;
                for($u=0;$u<$max_date;$u++){
                    // if($u==1)   
                    //     dd($u);
                   $date_query = mktime(0,0,0,date("m"),date("d")+$u,date("Y"));
                   $date_query=date("Y-m-d", $date_query);  
                    //$date_query='2022-09-18';                 
                    $location_id='8677657';
                    //$location_id='8677657';
                    //$param = json_encode(array('WERKS'=>$plant,'MATNR'=>$material, 'RSTAT'=>$status, 'AEDAT'=>$last_change));

                    // $param=urlencode($param);
                    $url = config('intranet.BOOK4TIME_APPOINTMENT')."?date=${date_query}&locationId=${location_id}";
                    $client = new \GuzzleHttp\Client();
                    $header = ['headers' => [
                        "AccountToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_ACCOUNT_TOKEN'),
                        "ApiToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_API_TOKEN'),
                        "UserToken" => config('intranet.BOOK4TIME_CREDENTIAL.BOOK4TIME_USER_TOKEN')
                    ]];
                    //dd($url);
                    $res = $client->get($url, $header);
                    $data_book4time = json_decode($res->getBody()->getContents(), true);
                    
                    
                    if(is_array($data_book4time) && count($data_book4time)){
                        
                        
                        try {
                            DB::connection('dbayana-stg')->table('Book4TimeAppointment')->where(['SCHEDULLER_DATE'=>$date_query, 'LOCATIONID'=>$location_id])->delete();
                        $data_insert=[];
                            for($i=0;$i<count($data_book4time);$i++){
                            
                                    $data_insert['INSERT_DATE']= date('Y-m-d H:i:s');
                                    $data_insert['APPOINTMENT_ID']= $data_book4time[$i]['appointmentId'];
                                    $data_insert['SCHEDULLER_DATE']= $data_book4time[$i]['schedulerDate'];
                                    $data_insert['BOOKED_DATE']= $data_book4time[$i]['bookedDate'];
                                    $data_insert['CUSTOMER_ID']= $data_book4time[$i]['customerId'];
                                    $data_insert['PREFIX']= $data_book4time[$i]['prefix'];
                                    $data_insert['LASTNAME']= $data_book4time[$i]['lastname'];
                                    $data_insert['FIRSTNAME']= $data_book4time[$i]['firstname'];
                                    $data_insert['FULL_NAME']= $data_book4time[$i]['customerFullName'];
                                    $data_insert['SEX']= $data_book4time[$i]['sex'];
                                    $data_insert['BIRTHDATE']= $data_book4time[$i]['birthdate'];
                                    $data_insert['GUEST_TYPE_SHORTCODE']= $data_book4time[$i]['guestTypeShortCode'];
                                    $data_insert['TECHNICIAN_ID']= $data_book4time[$i]['technicianId'];
                                    $data_insert['SERVICE_TYPE_NAME']= $data_book4time[$i]['serviceTypeName'];
                                    $data_insert['SERVICE_ID']= $data_book4time[$i]['serviceId'];
                                    $data_insert['PRICE']= $data_book4time[$i]['price'];
                                    $data_insert['STATUS']= $data_book4time[$i]['status'];
                                    $data_insert['REQUEST_TYPE_ID']= $data_book4time[$i]['requestTypeId'];
                                    $data_insert['DURATION']= $data_book4time[$i]['duration'];
                                    $data_insert['NOTE']= $data_book4time[$i]['note'];
                                    $data_insert['INTERNAL_NOTE']= $data_book4time[$i]['internalNotes'];
                                    $data_insert['FLAGS']= $data_book4time[$i]['flags'];
                                    $data_insert['IS_GROUP_BOOKING']= $data_book4time[$i]['isGroupBooking'];
                                    $data_insert['IS_RESERVATION']= $data_book4time[$i]['isReservation'];
                                    $data_insert['HAS_FACILITY']= $data_book4time[$i]['hasFacility'];
                                    $data_insert['FACILITY_NAME']= $data_book4time[$i]['facilityName'];
                                    $data_insert['IS_FACILITY_LOCKED']= $data_book4time[$i]['isFacilityLocked'];
                                    $data_insert['IS_PACKAGE_BOOKING']= $data_book4time[$i]['isPackageBooking'];
                                    $data_insert['CUSTOMER_HAS_MEMBERSHIP']= $data_book4time[$i]['customerHasMembership'];
                                    $data_insert['IS_RECURRENT_BOOKING']= $data_book4time[$i]['isRecurrentBooking'];
                                    $data_insert['IS_HOTEL_GUEST']= $data_book4time[$i]['isHotelGuest'];
                                    $data_insert['IS_RECONFIRMED']= $data_book4time[$i]['isReconfirmed'];
                                    $data_insert['IS_CANCELED']= $data_book4time[$i]['isCanceled'];
                                    $data_insert['IS_CONFIRMED']= $data_book4time[$i]['isConfirmed'];
                                    $data_insert['IS_CLOSED']= $data_book4time[$i]['isClosed'];
                                    $data_insert['IS_BOOKED_ONLINE']= $data_book4time[$i]['isBookedOnline'];
                                    $data_insert['LOCATIONID']= $data_book4time[$i]['locationId'];
                                    $data_insert['IS_GUARANTEED']= $data_book4time[$i]['isGuaranteed'];
                                    //dd(count($data_insert));
                                    
                                    
                                    DB::connection('dbayana-stg')->beginTransaction();
                                    try {
                                        $data_id = DB::connection('dbayana-stg')->table('Book4TimeAppointment')->insertGetId($data_insert);
                                        // $item_new = array_map(function($val) use ($data_id) { $val['HEADER_ID'] = (String)$data_id; $val['WENGE'] = (float)$val['WENGE']; $val['PCOST'] = (float)$val['PCOST']; return $val; }, $item);
                                        // DB::connection('dbayana-stg')->table('RECIPE_COST_ITEM')->insert($item_new);
                                        DB::connection('dbayana-stg')->commit();
                                        // all good
                                    } catch (\Exception $e) {
                                        DB::connection('dbayana-stg')->rollback();
                                        throw new \Exception($e->getMessage());
                                        // something went wrong
                                    }
                                    // DB::connection('dbayana-stg')->transaction(function() use (&$database, &$item) {
                                    // });
                                
                                $countdata+=1;
                            }
                            
                        } catch(\Exception $e){
                            dd($e);
                            throw new \Exception($e->getMessage());
                        }

                        
                    } 
                        
                    }
                    
                        return response()->json(['status'=>'success', 'message'=>'Data inserted successfully','row_inserted'=>$countdata], 200);
                        //return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_boo4time'=>$data_book4time], 200);

                    }   catch(\Exception $e){
                        dd($e);
                        return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
                    }
            
        // } else {
            // return response()->json(['status'=>'warning', 'message'=>"Forbidden!, not enough permission"], 200);
        // }
    }

    
}
