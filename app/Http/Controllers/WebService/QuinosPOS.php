<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;

class QuinosPOS extends Controller
{
    public function summary(Request $request){
        try {
            $date = date("Y-m-d", strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date_end = date("Y-m-d", strtotime('-1 day', strtotime(date('Y-m-d'))));
            // Check if there's any parameter for date start
            if($request->get('date')){
                try {
                    $date = date('Y-m-d', strtotime($request->get('date')));
                    if($date == date('Y-m-d', strtotime('1970-01-01')))
                        $date = date('Y-m-d');
                } catch(\Exception $e){
                    $date = date('Y-m-d');
                }
            }
            // Check if there's any parameter for date end
            if($request->get('date_end')){
                try {
                    $date_end = date('Y-m-d', strtotime($request->get('date_end')));
                    if($date_end == date('Y-m-d', strtotime('1970-01-01')))
                        $date_end = date('Y-m-d');
                } catch(\Exception $e){
                    $date_end = date('Y-m-d');
                }
            }

            if(strtotime($date_end) < strtotime($date))
                throw new \Exception('Cannot set date_end lower than date, correct your input!');

            $url = "https://quinoscloud.com/cloud/api/listTransaction/".config('intranet.QUINOS_POS.PROPERTY_TOKEN')."/".$date."/".$date_end."/".config('intranet.QUINOS_POS.PROPERTY_NAME');
            $client = new \GuzzleHttp\Client();
            $header = ['headers' => []];
            $res = $client->get($url, $header);
            $content = json_decode($res->getBody()->getContents());
            // dd($content);
            if($content){
                // Check DB first on corresponding date
                $check_any = DB::connection('dbayana-stg')
                ->table('dbo.QuinosSummary')
                ->where('BUSINESS_DATE', '>=', $date) 
                ->where('BUSINESS_DATE', '<=', $date_end);
                $count_row = $check_any->count();
                // dd($count_row);
                
                $data_to_insert = array();
                for($i=0;$i<count($content);$i++){
                    $data_to_insert[] = array(
                        'TRANSACTION_ID'=> isset($content[$i]->Transaction->id) ? $content[$i]->Transaction->id : NULL,
                        'COMPANY_ID'=> isset($content[$i]->Transaction->company_id) ? $content[$i]->Transaction->company_id : NULL,
                        'STORE_CODE'=> isset($content[$i]->Transaction->store_code) ? $content[$i]->Transaction->store_code : NULL,
                        'BRAND_NAME'=> isset($content[$i]->Transaction->brand) ? $content[$i]->Transaction->brand : NULL,
                        'CHECKNUM'=> isset($content[$i]->Transaction->transaction_id) ? $content[$i]->Transaction->transaction_id : 0,
                        'BUSINESS_DATE'=> isset($content[$i]->Transaction->date) ? $content[$i]->Transaction->date : NULL,
                        'SALES_TYPE'=> isset($content[$i]->Transaction->sales_type) ? $content[$i]->Transaction->sales_type : NULL,
                        'TABLE_NAME'=> isset($content[$i]->Transaction->table_name) ? $content[$i]->Transaction->table_name : NULL,
                        'PAX'=> isset($content[$i]->Transaction->pax) ? $content[$i]->Transaction->pax : NULL,
                        'CUSTOMER_CODE'=> isset($content[$i]->Transaction->customer_code) ? $content[$i]->Transaction->customer_code : NULL,
                        'CUSTOMER_NAME'=> isset($content[$i]->Transaction->customer_name) ? $content[$i]->Transaction->customer_name : NULL,
                        'DELIVERY_ADDRESS'=> isset($content[$i]->Transaction->delivery_address) ? $content[$i]->Transaction->delivery_address : NULL,
                        'PICKUP_TIME'=> isset($content[$i]->Transaction->pickup_time) ? $content[$i]->Transaction->pickup_time : NULL,
                        'DRIVER_CODE'=> isset($content[$i]->Transaction->driver_code) ? $content[$i]->Transaction->driver_code : NULL,
                        'OPEN_STAFF_CODE'=> isset($content[$i]->Transaction->open_staff_code) ? $content[$i]->Transaction->open_staff_code : NULL,
                        'OPEN_TIME'=> isset($content[$i]->Transaction->open_time) ? $content[$i]->Transaction->open_time : NULL,
                        'CASHIER_CODE'=> isset($content[$i]->Transaction->cashier_code) ? $content[$i]->Transaction->cashier_code : NULL,
                        'CLOSE_TIME'=> isset($content[$i]->Transaction->close_time) ? $content[$i]->Transaction->close_time : NULL,
                        'VOID_TRANSACTION'=> isset($content[$i]->Transaction->void_transaction) ? $content[$i]->Transaction->void_transaction : NULL,
                        'VOID_REASON'=> isset($content[$i]->Transaction->void_reason) ? $content[$i]->Transaction->void_reason : NULL,
                        'NO_SALES'=> isset($content[$i]->Transaction->no_sales) ? $content[$i]->Transaction->no_sales : NULL,
                        'SUBTOTAL'=> isset($content[$i]->Transaction->subtotal) ? $content[$i]->Transaction->subtotal : NULL,
                        'DISCOUNT'=> isset($content[$i]->Transaction->discount) ? $content[$i]->Transaction->discount : NULL,
                        'SERVICE_CHARGE'=> isset($content[$i]->Transaction->service_charge) ? $content[$i]->Transaction->service_charge : NULL,
                        'TAX1'=> isset($content[$i]->Transaction->tax1) ? $content[$i]->Transaction->tax1 : NULL,
                        'TAX2'=> isset($content[$i]->Transaction->tax2) ? $content[$i]->Transaction->tax2 : NULL,
                        'ROUNDING'=> isset($content[$i]->Transaction->rounding) ? $content[$i]->Transaction->rounding : NULL,
                        'EXTRA_CHARGE'=> isset($content[$i]->Transaction->extra_charge) ? $content[$i]->Transaction->extra_charge : NULL,
                        'TOTAL'=> isset($content[$i]->Transaction->total) ? $content[$i]->Transaction->total : NULL,
                        'SHIFT'=> isset($content[$i]->Transaction->shift) ? $content[$i]->Transaction->shift : NULL,
                        'SHIFT_NAME'=> isset($content[$i]->Transaction->shift_name) ? $content[$i]->Transaction->shift_name : NULL,
                        'SUBMIT_TIME'=> isset($content[$i]->Transaction->submit_time) ? $content[$i]->Transaction->submit_time : NULL,
                        'NAME'=> isset($content[$i]->Transaction->name) ? $content[$i]->Transaction->name : NULL,
                        'NOTES'=> isset($content[$i]->Transaction->notes) ? $content[$i]->Transaction->notes : NULL,
                        'SUBMIT_POS'=> isset($content[$i]->Transaction->submit_pos) ? $content[$i]->Transaction->submit_pos : NULL,
                        'PENDING'=> isset($content[$i]->Transaction->pending) ? $content[$i]->Transaction->pending : NULL,
                        'ADDITIONAL_CHARGE'=> isset($content[$i]->Transaction->additional_charge) ? $content[$i]->Transaction->additional_charge : NULL,
                        'PAYMENTS_METHOD'=> isset($content[$i]->Transaction->payments) ? $content[$i]->Transaction->payments : NULL
                    );
                }

                if(count($data_to_insert)){
                    if($count_row){
                        $check_any->delete();
                    }

                    $chunk_insert = array_chunk($data_to_insert, 50);
                    for($i=0;$i<count($chunk_insert);$i++){
                        DB::connection('dbayana-stg')
                        ->table('dbo.QuinosSummary')
                        ->insert($chunk_insert[$i]);
                    }
                    return response()->json(['status'=>'success', 'message'=>'Success insert data', 'row_inserted'=>count($data_to_insert)], 200);
                } else {
                    return response()->json(['status'=>'failed', 'message'=>'No data to insert', 'data_to_insert'=>$data_to_insert], 200);
                }
            }
            else
                return response()->json(['status'=>'warning', 'message'=>'No data available', 'summary_transaction_data'=>$content], 200);

        } catch(\Exception $e){
            return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
        }
    }

    public function detail(Request $request){
        try {
            $date = date("Y-m-d", strtotime('-1 day', strtotime(date('Y-m-d'))));
            if($request->get('date')){
                try {
                    $date = date('Y-m-d', strtotime($request->get('date')));
                    if($date == date('Y-m-d', strtotime('1970-01-01')))
                        $date = date('Y-m-d');
                } catch(\Exception $e){
                    $date = date('Y-m-d');
                }
            }

            $url = "https://quinoscloud.com/cloud/api/getTransaction/".config('intranet.QUINOS_POS.PROPERTY_TOKEN')."/".$date."/".config('intranet.QUINOS_POS.PROPERTY_NAME');
            $client = new \GuzzleHttp\Client();
            $header = ['headers' => []];
            $res = $client->get($url, $header);
            $content = json_decode($res->getBody()->getContents());
            if($content){
                // Check DB first on corresponding date
                $check_any = DB::connection('dbayana-stg')
                ->table('dbo.QuinosDetail')
                ->where('BUSINESS_DATE', '=', $date);
                $count_row = $check_any->count();
                // dd($count_row);
                
                $data_to_insert = array();
                for($i=0;$i<count($content);$i++){
                    $content_obj = isset($content[$i]->TransactionLine) ? $content[$i]->TransactionLine : [];
                    $business_date_header = isset($content[$i]->Transaction->date) ? $content[$i]->Transaction->date : NULL;
                    $open_time_header = isset($content[$i]->Transaction->open_time) ? $content[$i]->Transaction->open_time : NULL;
                    $checknum = isset($content[$i]->Transaction->transaction_id) ? $content[$i]->Transaction->transaction_id : NULL;

                    for($j=0;$j<count($content_obj);$j++){
                        $data_to_insert[] = array(
                            'TRANSACTION_ID'=>isset($content_obj[$j]->transaction_id) ? $content_obj[$j]->transaction_id : NULL,
                            'ITEM_CODE'=>isset($content_obj[$j]->item_code) ? $content_obj[$j]->item_code : NULL,
                            'DESCRIPTION'=>isset($content_obj[$j]->description) ? $content_obj[$j]->description : NULL,
                            'CATEGORY_CODE'=> isset($content_obj[$j]->category_code) ? $content_obj[$j]->category_code : NULL,
                            'DEPARTMENT_CODE'=> isset($content_obj[$j]->department_code) ? $content_obj[$j]->department_code : NULL,
                            'DISCOUNT_CODE'=> isset($content_obj[$j]->discount_code) ? $content_obj[$j]->discount_code : NULL,
                            'PAYMENT_CODE'=>isset($content_obj[$j]->payment_code) ? $content_obj[$j]->payment_code : NULL,
                            'UNIT_PRICE'=> isset($content_obj[$j]->unit_price) ? $content_obj[$j]->unit_price : NULL,
                            'AMOUNT'=> isset($content_obj[$j]->amount) ? $content_obj[$j]->amount : NULL,
                            'REMARK'=> isset($content_obj[$j]->remark) ? $content_obj[$j]->remark : NULL,
                            'QUANTITY'=> isset($content_obj[$j]->quantity) ? $content_obj[$j]->quantity : NULL,
                            'BUSINESS_DATE'=>$business_date_header,
                            'OPEN_TIME'=>$open_time_header,
                            'CHECKNUM'=>$checknum
                        );
                    }
                }
                if(count($data_to_insert)){
                    if($count_row){
                        $check_any->delete();
                    }
                    
                    $chunk_insert = array_chunk($data_to_insert, 50);
                    for($i=0;$i<count($chunk_insert);$i++){
                        DB::connection('dbayana-stg')
                        ->table('dbo.QuinosDetail')
                        ->insert($chunk_insert[$i]);
                    }
                    return response()->json(['status'=>'success', 'message'=>'Success insert data', 'row_inserted'=>count($data_to_insert)], 200);
                } else {
                    return response()->json(['status'=>'failed', 'message'=>'No data to insert', 'data_to_insert'=>$data_to_insert], 200);
                }
            }
            else
                return response()->json(['status'=>'warning', 'message'=>'No data available', 'summary_transaction_data'=>$content], 200);

        } catch(\Exception $e){
            return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
        }
    }
}
