<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;
Use Cookie;
use DataTables;
use Log;
use DNS1D;

class Folio extends Controller
{
    function index(){
		return view('pages.folio.folio');
	}

    function categorized_folio(){
        return view('pages.folio.folio_categorized');
    }

    function folio_reference(){
        return view('pages.folio.folio_reference');
    }

	function GetDataFolio(Request $request){
		try{
			$row_fetched = array('Request Not Allowed');

			if($request->ajax()){
				$query = DB::connection('operadbms')->select("SELECT c.RESORT_NAME ,f.RESORT, f.BUSINESS_DATE, f.FOLIO_NO, f.INVOICE_NO, f.GUEST_NAME FROM (SELECT TOP 100 INVOICE_NO, RESORT, BUSINESS_DATE, FOLIO_NO, GUEST_NAME
			    	FROM dbo.TB_FOLIO_HIST) as f
			      	LEFT JOIN (SELECT RESORT_CODE, RESORT_NAME FROM dbo.TB_MAP_RESORT) as c on c.RESORT_CODE = f.RESORT
			      	GROUP BY c.RESORT_NAME, f.RESORT, f.BUSINESS_DATE, f.FOLIO_NO, f.GUEST_NAME, f.INVOICE_NO
			      	ORDER BY DATENAME(YEAR, f.BUSINESS_DATE), f.FOLIO_NO, f.GUEST_NAME DESC");

				foreach($data_folio = $query as $data_folio) {
					$resort = $data_folio->RESORT." - ".$data_folio->RESORT_NAME;
	    			$invoice_no = $data_folio->INVOICE_NO ? (int)$data_folio->INVOICE_NO : "-";
	    			$row_fetched['data'][] = array('folio_no'=>"<a style='cursor:pointer'>".(String)(int)$data_folio->FOLIO_NO."</a>", 'invoice_no'=>$invoice_no, 'resort_name'=>$resort, 'business_date'=>date('Y-m-d', strtotime($data_folio->BUSINESS_DATE)), 'guest_name'=>$data_folio->GUEST_NAME);
				}
			}

			return json_encode($row_fetched);

		} catch(\Exception $e){
            return json_encode(['data'=>[], 'message'=>(String)$e->getMessage()]);
		}
	}

    function checkquery(){
        $query = DB::connection('operadbms')
        ->table('dbo.TB_FOLIO_HEADER')
        ->select('company_code', 'resort', 'conf_no', 'arrival', 'departure', 'title', 'first_name', 'last_name')
        ->distinct()
        ->orderBy('conf_no', 'ASC')
        ->skip(0)
        ->take(15)
        ->get();
    }

    function GetFolioAyana(Request $request){
        try{
            $row_fetched = array();
            $query = DB::connection('operadbms')
            ->table('dbo.TB_FOLIO_HEADER')
            ->select('company_code', 'resort', 'conf_no', 'arrival', 'departure', 'title', 'first_name', 'last_name', 'window')
            ->distinct()
            // ->where('window','<=', 1)
            ->orderBy('conf_no', 'ASC')
            ->skip(0)
            ->take(15)
            ->get();
            // dd($query);
            foreach($data_folio = $query as $data_folio) {
                $row_fetched[] = array('COMPANY_CODE'=>strtoupper($data_folio->company_code), 'RESORT'=>strtoupper($data_folio->resort), 'CONF_NO'=>"<a style='cursor:pointer;text-decoration:none;' href='/folio/get_invoice_new?conf_no=".(int)$data_folio->conf_no."&window=".(int)$data_folio->window."'>".(int)$data_folio->conf_no."</a>", 'ARRIVAL'=>date('d M Y', strtotime($data_folio->arrival)), 'DEPARTURE'=>date('d M Y', strtotime($data_folio->departure)), 'GUEST_NAME'=>addslashes($data_folio->title)." ".addslashes($data_folio->first_name)." ".addslashes($data_folio->last_name), "WINDOW"=>$data_folio->window);
            }

            return DataTables::of($row_fetched)
            ->rawColumns(['CONF_NO'])
            ->make(true);

        } catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    function GetFolioAyanaCategorized(Request $request){
        try{
            $row_fetched = array();
            $query = DB::connection('operadbms')
            ->table('dbo.TB_FOLIO_HEADER')
            ->select('company_code', 'resort', 'conf_no', 'arrival', 'departure', 'title', 'first_name', 'last_name')
            ->distinct()
            // ->where('window','<=', 1)
            ->orderBy('conf_no', 'ASC')
            // ->skip(0)
            // ->take(15)
            ->get();
            // dd($query);
            foreach($data_folio = $query as $data_folio) {
                $row_fetched[] = array('COMPANY_CODE'=>strtoupper($data_folio->company_code), 'RESORT'=>strtoupper($data_folio->resort), 'CONF_NO'=>"<a style='cursor:pointer;text-decoration:none;' href='/folio/folio_detail?conf_no=".(int)$data_folio->conf_no."'>".(int)$data_folio->conf_no."</a>", 'ARRIVAL'=>date('d M Y', strtotime($data_folio->arrival)), 'DEPARTURE'=>date('d M Y', strtotime($data_folio->departure)), 'GUEST_NAME'=>addslashes($data_folio->title)." ".addslashes($data_folio->first_name)." ".addslashes($data_folio->last_name));
            }

            return DataTables::of($row_fetched)
            ->rawColumns(['CONF_NO'])
            ->make(true);

        } catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    function GetReferenceFolio(Request $request){
        try{
            $conf_num = $request->get('conf_no', 0);
            return view('pages.folio.folio_reference', ['conf_no'=>$conf_num]);
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    function GetReferenceFolioData(Request $request){
        // Jika request dari blade berupa ajax return sebagai berikut
        if($request->ajax()){
            try {
                $conf_no = $request->get('conf_no', 0);
                $row_fetched["WINDOW"] = array();

                $query = DB::connection('operadbms')
                ->table('dbo.TB_FOLIO_HEADER')
                ->select('conf_no', 'folio_no', 'title', 'first_name', 'last_name', 'address1', 'room_class', 'room', 'cashier', 'window', 'folio_amount')
                ->where('conf_no', $conf_no)
                ->orderBy('folio_no', 'ASC')
                ->get();

                $counter = 1;
                foreach($data_folio = $query as $key => $data_folio) {
                    echo in_array($data_folio->window, $row_fetched["WINDOW"]);
                    if(!in_array($data_folio->window, array_keys($row_fetched["WINDOW"]))){
                        $counter = 1;
                        $address = $data_folio->address1 ? $data_folio->address1 : "-";
                        $row_fetched["WINDOW"][$data_folio->window][] = array("COUNTER"=>$counter, "FOLIO_NO"=>(int)$data_folio->folio_no, "CONF_NO"=>(int)$data_folio->conf_no, 'GUEST_NAME'=>addslashes($data_folio->title)." ".addslashes($data_folio->first_name)." ".addslashes($data_folio->last_name), "ADDRESS"=>$address, "ROOM_CLASS"=>$data_folio->room_class, "ROOM"=>$data_folio->room, "CASHIER"=>$data_folio->cashier, "FOLIO_AMOUNT"=>number_format($data_folio->folio_amount, 0, '.', ','));
                    }
                    else{
                        $counter++;
                        $address = $data_folio->address1 ? $data_folio->address1 : "-";
                        array_push($row_fetched["WINDOW"][$data_folio->window], array("COUNTER"=>$counter, "FOLIO_NO"=>(int)$data_folio->folio_no, "CONF_NO"=>(int)$data_folio->conf_no, 'GUEST_NAME'=>addslashes($data_folio->title)." ".addslashes($data_folio->first_name)." ".addslashes($data_folio->last_name), "ADDRESS"=>$address, "ROOM_CLASS"=>$data_folio->room_class, "ROOM"=>$data_folio->room, "CASHIER"=>$data_folio->cashier, "FOLIO_AMOUNT"=>number_format($data_folio->folio_amount, 0, '.', ',')));
                    }

                }

                return DataTables::of($row_fetched)
                ->rawColumns(['FOLIO_NO'])
                ->make(true);

            } catch(\Exception $e){
                return response()->json($e->getMessage(), 400);
            }
        }
        // END REQUEST AJAX
    }

    function GetFolioDetail(Request $request){
        try{

            date_default_timezone_set('Asia/Jakarta');
            $now_date = date('H:i');
            $folio_num = $request->get('folio_no', 0);
            $conf_num = $request->get('conf_no', 0);
            $window = $request->get('window', 0);
            $room_class = $request->get('room_class', '');

            $row_fetched['data'] = array();
            $query_header = DB::connection('operadbms')
            ->table('dbo.TB_FOLIO_HEADER')
            ->select('resort', 'business_unit', 'company_code', 'title', 'first_name', 'last_name', 'address1', 'address2', 'address3', 'state', 'country', 'agent', 'company', 'groups', 'charge_to', 'conf_no', 'room', 'room_class', 'persons', 'arrival', 'departure', 'room_rate', 'window', 'cashier', 'folio_amount')
            ->where('conf_no', $conf_num)
            ->where('window', $window)
            ->where('room_class', $room_class)
            ->orderBy('conf_no', 'ASC')
            ->get()->first();

            $query = DB::connection('operadbms')
            ->table('dbo.TB_FOLIO_DETAIL')
            ->select('*')
            ->where('conf_no', $conf_num)
            ->where('folio_no', $folio_num)
            ->where('window', $window)
            ->where('room_class', $room_class)
            ->orderBy('trx_date', 'DESC')
            ->get();

            return view('pages.folio.folio_detail', ['data_guest'=>$query_header, 'data_folio'=>$query, 'conf_no'=>$conf_num, 'folio_no'=>$folio_num]);
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    function GetFolioDetailNew(Request $request){
        try{

            date_default_timezone_set('Asia/Jakarta');
            $now_date = date('H:i');
            $folio_num = $request->get('folio_no', 0);
            $conf_num = $request->get('conf_no', 0);
            $window = $request->get('window', 'Unknown');
            $room_class = $request->get('room_class', '');

            $row_fetched['data'] = array();
            $query_header = DB::connection('operadbms')
            ->table('dbo.TB_FOLIO_HEADER')
            ->select('resort', 'business_unit', 'company_code', 'title', 'first_name', 'last_name', 'folio_no', 'address1', 'address2', 'address3', 'state', 'country', 'agent', 'company', 'groups', 'charge_to', 'conf_no', 'room', 'room_class', 'persons', 'arrival', 'departure', 'room_rate', 'window', 'cashier', 'folio_amount')
            ->where('conf_no', $conf_num)
            ->where('window', $window)
            ->orderBy('conf_no', 'ASC')
            ->get();

            $query_header = $query_header->each(function ($item, $key) {
                $collect_invoice =   DB::connection('operadbms')
                ->table('dbo.TB_FOLIO_DETAIL')
                ->select('*')
                ->where('conf_no', $item->conf_no)
                ->where('window', $item->window)
                ->where('room_class', $item->room_class)
                ->orderBy('trx_date', 'DESC')
                ->get();
               $item->details = $collect_invoice;
            });
            // dd($query_header);
            $query = [];

            return view('pages.folio.folio_detail_new', ['data_guest'=>$query_header, 'data_folio'=>$query, 'conf_no'=>$conf_num, 'folio_no'=>$folio_num, 'window'=>$window]);
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }

	public function invoice_payment_template(Request $request){
        return view('pages/invoice/invoice_payment_template');
    }

    public function invoice_payment_template_njp(Request $request){
        return view('pages/invoice/invoice_payment_template_njp');
    }

    public function invoice_payment_template_download(Request $request){
        if($request->ajax()){
            // $data = ['title' => 'PDF Invoice'];
            // $pdf = PDF::loadView('pages/invoice/invoice', $data);
            // return $pdf->download('Invoice-generate.pdf');
            // $data=array();
            $username=config('payment.username');
            $password=config('payment.password');
            $param = '';
            $inv_no = '';
            if($request->get('invoice_no')){
                $param = $request->get('invoice_no');
                $inv_no = $request->get('invoice_no');
            }

            if(!$param && $inv_no){
                echo 'Cannot read any invoice number, please try again';
                die;
            }

            $payload = json_encode( array( "CAANO" => $param ) );
            $param=urlencode($payload);
            $url=config('payment.sap_url.invoice_detail')."".$param;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            $server_output = curl_exec($ch);
            curl_close ($ch);

            $post=json_decode($server_output);
            if(!$post){
                echo 'No Details Found, please try again';
                die;
            }

            switch ($post) {
                case count((array)$post->ITEM_DETAILS_RE) > 0:
                    $new_post = json_decode(json_encode($post), true);
                    $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_RE;
                    try{
                        $new_post['INVTY'] = 'RE-FX';
                        unset($new_post['ITEM_DETAILS_FI']);
                        unset($new_post['ITEM_DETAILS_DP']);
                    } catch(\Exception $e){};
                    break;
                case count((array)$post->ITEM_DETAILS_FI) > 0:
                    $new_post = json_decode(json_encode($post), true);
                    $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_FI;
                    try{
                        $new_post['INVTY'] = 'FI-HOTEL';
                        unset($new_post['ITEM_DETAILS_RE']);
                        unset($new_post['ITEM_DETAILS_DP']);
                    } catch(\Exception $e){};
                    break;
                case count((array)$post->ITEM_DETAILS_DP) > 0:
                    $new_post = json_decode(json_encode($post), true);
                    $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_DP;
                    try{
                        $new_post['INVTY'] = 'DOWN PAYMENT';
                        unset($new_post['ITEM_DETAILS_RE']);
                        unset($new_post['ITEM_DETAILS_FI']);
                    } catch(\Exception $e){};
                    break;
                default:
                    $new_post = json_decode(json_encode($post), true);
                    $new_post['ITEM_DETAILS'] = [];
                    break;
            }

            if(!$new_post){
                echo 'No Details Found, please try again';
                die;
            }
            $new_post = json_decode(json_encode($new_post));
            $data['invoice'] = $new_post;
            $plant_info = [];
            try{
                $plant = $data['invoice']->PRCTR;
                $plant_info = DB::connection('dbintranet')
                ->table('INT_BUSINESS_PLANT')
                ->where('SAP_PLANT_ID', $plant)
                ->get()->first();

            } catch(\Exception $e){}

            $barcode = DNS1D::getBarcodeHTML($inv_no, 'C93', 1, 35, 'black', false);
            // return view('pages/invoice/invoice', ['data'=>$data, 'title'=>'PDF Invoice']);
            $pdf = PDF::loadView('pages/invoice/document_purpose/invoice_custom_new', ['data'=>$data, 'title'=>"Invoice Payment - $inv_no", 'plant_info'=>$plant_info, 'barcode'=>$barcode]);
            return $pdf->download(sprintf('statement-generate-%s.pdf', $inv_no), array("Attachment" => true));
        }
    }

    public function invoice_payment_template_print(Request $request){
        // $data = ['title' => 'PDF Invoice'];
        // $pdf = PDF::loadView('pages/invoice/invoice', $data);
        // return $pdf->stream('Invoice-generate.pdf');
        $data=array();
        $username=config('payment.username');
        $password=config('payment.password');
        $param = '';
        $inv_no = '';
        if($request->get('invoice_no')){
            $param = $request->get('invoice_no');
            $inv_no = $request->get('invoice_no');
        }

        if(!$param && $inv_no){
            echo 'Cannot read any invoice number, please try again';
            die;
        }

        $payload = json_encode( array( "CAANO" => $param ) );
        $param=urlencode($payload);
        $url=config('payment.sap_url.invoice_detail')."".$param;

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $server_output = curl_exec($ch);
        curl_close ($ch);

        $post=json_decode($server_output);
        if(!$post){
            echo 'No Details Found, please try again';
            die;
        }

        switch ($post) {
            case count((array)$post->ITEM_DETAILS_RE) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_RE;
                try{
                    $new_post['INVTY'] = 'RE-FX';
                    unset($new_post['ITEM_DETAILS_FI']);
                    unset($new_post['ITEM_DETAILS_DP']);
                } catch(\Exception $e){};
                break;
            case count((array)$post->ITEM_DETAILS_FI) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_FI;
                try{
                    $new_post['INVTY'] = 'FI-HOTEL';
                    unset($new_post['ITEM_DETAILS_RE']);
                    unset($new_post['ITEM_DETAILS_DP']);
                } catch(\Exception $e){};
                break;
            case count((array)$post->ITEM_DETAILS_DP) > 0:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_DP;
                try{
                    $new_post['INVTY'] = 'DOWN PAYMENT';
                    unset($new_post['ITEM_DETAILS_RE']);
                    unset($new_post['ITEM_DETAILS_FI']);
                } catch(\Exception $e){};
                break;
            default:
                $new_post = json_decode(json_encode($post), true);
                $new_post['ITEM_DETAILS'] = [];
                break;
        }

        if(!$new_post){
            echo 'No Details Found, please try again';
            die;
        }
        $new_post = json_decode(json_encode($new_post));
        $data['invoice'] = $new_post;
        $plant_info = [];
        try{
            $plant = $data['invoice']->PRCTR;
            $plant_info = DB::connection('dbintranet')
            ->table('INT_BUSINESS_PLANT')
            ->where('SAP_PLANT_ID', $plant)
            ->get()->first();

        } catch(\Exception $e){}

        $barcode = DNS1D::getBarcodeHTML($inv_no, 'C93', 1, 35, 'black', false);
        // return view('pages/invoice/invoice', ['data'=>$data, 'title'=>'PDF Invoice']);
        $pdf = PDF::loadView('pages/invoice/document_purpose/invoice_custom_new', ['data'=>$data, 'title'=>"Invoice Payment - $inv_no", 'plant_info'=>$plant_info, 'barcode'=>$barcode]);
        return $pdf->stream(sprintf('statement-generate-%s.pdf', $inv_no), array("Attachment" => false));
    }

    public function invoice_payment_template_pdf_string($no_invoice=''){
        try {
            // $data = ['title' => 'PDF Invoice'];
            // $pdf = PDF::loadView('pages/invoice/invoice', $data);
            // return $pdf->stream('Invoice-generate.pdf');
            $data=array();
            $username=config('payment.username');
            $password=config('payment.password');

            $payload = json_encode( array( "CAANO" => $no_invoice ) );
            $param=urlencode($payload);
            $url=config('payment.sap_url.invoice_detail')."".$param;

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            $server_output = curl_exec($ch);
            curl_close ($ch);

            $post=json_decode($server_output);
            if(!$post){
                throw new \Exception('No Details Found, please try again');
            }

            switch ($post) {
                case count((array)$post->ITEM_DETAILS_RE) > 0:
                    $new_post = json_decode(json_encode($post), true);
                    $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_RE;
                    try{
                        $new_post['INVTY'] = 'RE-FX';
                        unset($new_post['ITEM_DETAILS_FI']);
                        unset($new_post['ITEM_DETAILS_DP']);
                    } catch(\Exception $e){};
                    break;
                case count((array)$post->ITEM_DETAILS_FI) > 0:
                    $new_post = json_decode(json_encode($post), true);
                    $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_FI;
                    try{
                        $new_post['INVTY'] = 'FI-HOTEL';
                        unset($new_post['ITEM_DETAILS_RE']);
                        unset($new_post['ITEM_DETAILS_DP']);
                    } catch(\Exception $e){};
                    break;
                case count((array)$post->ITEM_DETAILS_DP) > 0:
                    $new_post = json_decode(json_encode($post), true);
                    $new_post['ITEM_DETAILS'] = $post->ITEM_DETAILS_DP;
                    try{
                        $new_post['INVTY'] = 'DOWN PAYMENT';
                        unset($new_post['ITEM_DETAILS_RE']);
                        unset($new_post['ITEM_DETAILS_FI']);
                    } catch(\Exception $e){};
                    break;
                default:
                    $new_post = json_decode(json_encode($post), true);
                    $new_post['ITEM_DETAILS'] = [];
                    break;
            }

            if(!$new_post){
                throw new \Exception('No Details Found, please try again');
            }
            $new_post = json_decode(json_encode($new_post));
            $data['invoice'] = $new_post;
            $plant_info = [];
            try{
                $plant = $data['invoice']->PRCTR;
                $plant_info = DB::connection('dbintranet')
                ->table('INT_BUSINESS_PLANT')
                ->where('SAP_PLANT_ID', $plant)
                ->get()->first();

            } catch(\Exception $e){}

            $barcode = DNS1D::getBarcodeHTML($no_invoice, 'C93', 1, 35, 'black', false);
            // return view('pages/invoice/invoice', ['data'=>$data, 'title'=>'PDF Invoice']);
            $pdf = PDF::loadView('pages/invoice/document_purpose/invoice_custom_new', ['data'=>$data, 'title'=>"Statement - $no_invoice", 'plant_info'=>$plant_info, 'barcode'=>$barcode]);
            return $pdf->output();
        } catch(\Exception $e){
            Log::info('FAILED SEND EMAIL'.$e->getMessage());
            return '';
        }
    }

    function send_mail(Request $request){
        try {
            $no_invoice = $request->get('no_invoice');
            $data_template = $this->invoice_payment_template_pdf_string($no_invoice);
            if($data_template){
                Mail::raw('Thank for using our service, here is your receipt attached', function($message) use ($data_template){
                    $message->to('mahendrapermanaidabagus@gmail.com','Mahendra')
                    ->subject('Send Mail from Laravel');
                    $message->from('kms@midplaza.com','PT. Karang Mas Sejahtera');
                    $message->attachData($data_template, 'invoice.pdf');
                });
                return response()->json(['status'=>200,'message'=>"Email sent to customer"]);
            }
            return response()->json(['status'=>400,'message'=>"No data returned"]);
        } catch(\Exception $e){
            return response()->json(['status'=>500, 'messages'=>$e->getMessage()]);
        }
    }
}
