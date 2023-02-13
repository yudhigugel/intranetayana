<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Log;
use Mail;
use App\Models\Zapier\ZapierVCCLog;
use App\Models\Zapier\ZapierVCCParse;
use SoapClient;
use SoapHeader;

class ExternalReference {
    private $ReferenceNumber;
    private $LegNumber;
    private $ReferenceType;
    public function __construct($refNumber, $legNum, $refType)
    {
        $this->ReferenceNumber = $refNumber;
        $this->LegNumber = $legNum;
        $this->ReferenceType = $refType;
    }
}

class Posting{
    private $LongInfo;
    private $Charge;
    private $FolioViewNo;
    private $ReservationRequestBase;
    public function __construct($LongInfo, $Charge, $FolioViewNo, $ReservationRequestBase)
    {
        $this->LongInfo = $LongInfo;
        $this->Charge = $Charge;
        $this->FolioViewNo = $FolioViewNo;
        $this->ReservationRequestBase = $ReservationRequestBase;
    }
}

class ReservationRequestBase{
    private $HotelReference;
    private $ReservationID;
    public function __construct($HotelReference, $ReservationID){
        $this->HotelReference = $HotelReference;
        $this->ReservationID = $ReservationID;
    }
}

class HotelReference{
    private $chainCode;
    private $hotelCode;
    public function __construct($chainCode, $hotelCode){
        $this->chainCode = $chainCode;
        $this->hotelCode = $hotelCode;
    }
}


class CreditCardInfo{
    private $CreditCard;
    public function __construct($CreditCard){
        $this->CreditCard = $CreditCard;
    }
}

class CreditCard{
    private $cardType;
    private $cardHolderName;
    private $expirationDate;
    private $cardNumber;
    public function __construct($cardType, $cardHolderName, $expirationDate, $cardNumber){
        $this->cardType = $cardType;
        $this->cardHolderName = $cardHolderName;
        $this->expirationDate = $expirationDate;
        $this->cardNumber = $cardNumber;
    }
}


class Zapier extends Controller
{

    private $opera_username;
    private $opera_password;

    public function __construct()
    {
        $this->opera_username='VCC.IFC';
        $this->opera_password='INTERFACE@2022#';
        // $this->opera_username='OTA.BIZNET';
        // $this->opera_password='OPERA123$';
    }

    public function charge_virtualCreditCard_fetch_from_database(){

        $now = Carbon::now();

        // // run script untuk decrypt data-data yang belum di decrypt
        // $this->enkripsi_data_vcc();

        $salt=config('intranet.zapier_vcc_salt');


        //filter dulu kalau ada yang dobel biar dibersihin
        $this->bersihin_data();

        // mendapatkan data yang belum diproses dari databasse
        $data=DB::connection('dbintranet')
        ->table('ZAPIER_VCC_PARSE')
        ->where('is_processed',0)
        ->where('is_encrypted',1)
        ->get();

        $total_data=count($data);
        $success=0;
        $failed=0;

        // start looping untuk masing-masing data VCC diproses
        foreach($data as $d){


            try{
                //cek parsingan dari tabel
                $vcc_decrypt=str_replace($salt,'',$d->vcc);
                $vcc_decrypt_final=Crypt::decryptString($vcc_decrypt);

                $validuntil_decrypt=str_replace($salt,'',$d->validuntil);
                $validuntil_decrypt_final=Crypt::decryptString($validuntil_decrypt);

                $cvc_decrypt=str_replace($salt,'',$d->cvc);
                $cvc_decrypt_final=Crypt::decryptString($cvc_decrypt);


                $validuntil=explode('/',$validuntil_decrypt_final);
                $card_exp_month=$validuntil[0];
                $card_exp_year=$validuntil[1];
                $amount_parse=str_replace(',','',$d->vccamount);
                $amount=(int)$amount_parse;

                $vccdatestart = trim($d->vccdatestart);
                $vccdateend = trim($d->vccdateend);
            }catch(\Exception $e){
                $data_log=array(
                    'date'=>$now,
                    'message'=>$e->getMessage(),
                    'log'=>json_encode($d),
                    'status'=>'error',
                    'data'=>json_encode($d),
                    'id'=>$d->id
                );
                $data_log2=array(
                    'is_processed'=>1,
                    'is_processed_date'=>$now,
                    'is_processed_status'=>'400'
                );

                $this->logDataVCC($data_log);
                $this->updateZapierEmailParse($data_log2,$d->id);
                $this->mailError($d->id, $data_log);
                $failed++;
                continue; // skip ke next iteration
            }

            //parsing tanggal VCC & validasi jika format tanggal VCC sesuai
            try {
                $parse_vccdatestart = new Carbon($vccdatestart);
                $parse_vccdateend = new Carbon($vccdateend);
            } catch (\Exception $e) {
            //log data jika format vcc date start dan date end tidak sesuai
                $data_log=array(
                    'date'=>$now,
                    'message'=>$e->getMessage(),
                    'log'=>json_encode($d),
                    'status'=>'error',
                    'id'=>$d->id
                );
                $data_log2=array(
                    'is_processed'=>1,
                    'is_processed_date'=>$now,
                    'is_processed_status'=>'400'
                );

                $this->logDataVCC($data_log);
                $this->updateZapierEmailParse($data_log2,$d->id);
                $this->mailError($d->id, $data_log);
                $failed++;
                continue; // skip ke next iteration
            }

            //validasi jika tanggal tidak sesuai dengan hari ini
            if($now->between($parse_vccdatestart,$parse_vccdateend)==false){

                //validasi jika tanggal sekarang lebih dari masa expired kartu
                if($now->gt($parse_vccdateend)){

                    $data_log=array(
                        'date'=>date('Y-m-d H:i:s'),
                        'message'=>"VCC usage date is expired",
                        'log'=>json_encode($d),
                        'status'=>'error',
                        'data'=>json_encode($d),
                        'id'=>$d->id
                    );

                    $data_log2=array(
                        'is_processed'=>1,
                        'is_processed_date'=>$now,
                        'is_processed_status'=>'400'
                    );
                    $this->logDataVCC($data_log);
                    $this->updateZapierEmailParse($data_log2,$d->id);
                    $this->mailError($d->id, $data_log);
                }else{
                    $data_log=array(
                        'date'=>date('Y-m-d H:i:s'),
                        'message'=>"VCC usage date is not available yet",
                        'log'=>json_encode($d),
                        'status'=>'pending',
                        'data'=>json_encode($d),
                        'id'=>$d->id
                    );
                    $this->logDataVCC($data_log);
                    // $this->mailError($d->id, $data_log); ga usah email error karena ini bukan error tapi masa aktif yang belum valid
                }

                $failed++;
                continue; // skip ke next iteration
            }


            $param=array(
                'card_number'=>$vcc_decrypt_final,
                'card_exp_month'=>$card_exp_month,
                'card_exp_year'=>$card_exp_year,
                'card_cvv'=>$cvc_decrypt_final,
                'amount'=>$amount,
                'first_name'=>$d->guestname,
                'last_name'=>$d->guestname,
                'email'=>'',
                'phone'=>'',
                'vccdatestart'=>$parse_vccdatestart,
                'vccdateend'=>$parse_vccdateend,
                'reservationid'=>$d->reservationid,
                'ota'=>$d->ota_name
            );

            //start proses cek data di Opera
            //membedakan parameter berdasarkan property name
            $property_name=$d->property_name;
            $ReferenceType=$this->getReferenceType($property_name);

            //saat ini ada 2 parameter reference untuk cek yaitu GUESTID dan GDS_RECORD_LOCATOR
            //metode yang dilakukan adalah mengecek salah satu dari 2 reference tersebut yang mana yang datanya ada
            $cekOpera = $this->cekOperaData($param, $ReferenceType);
            // $cekOpera2 = $this->cekOperaData($param, 'GDS_RECORD_LOCATOR');
            $opera_reservation_id=NULL;
            if($cekOpera['status']=="success"){
                    ZapierVCCParse::where('id',$d->id)
                    ->update([
                        'is_validated_opera'=>1,
                        'opera_reservation_id'=>$cekOpera['data']['opera_reservation_id'],
                        'opera_fetch_json'=>$cekOpera['data']['opera_fetchbooking_json']
                    ]);
                    $opera_reservation_id=$cekOpera['data']['opera_reservation_id'];
            }else{
                $data_log=array(
                    'date'=>date('Y-m-d H:i:s'),
                    'message'=>"Check opera data failed",
                    'log'=>json_encode($cekOpera),
                    'status'=>'error',
                    'data'=>json_encode($d),
                    'id'=>$d->id
                );
                $this->logDataVCC($data_log);

                $data_log2=array(
                    'is_processed'=>1,
                    'is_processed_date'=>$now,
                    'is_processed_status'=>'400'
                );
                $this->updateZapierEmailParse($data_log2,$d->id);
                $this->mailError($d->id, $data_log);
                $failed++;
                continue; // skip next iteration
            }


            //start proses untuk validasi dan charge VCC
            $process = $this->charge_virtualCreditCard_view($param);
            $process_response = json_decode($process->content(),true);

            // jika response sukses maka update di database sebagai penanda
            if(isset($process_response['status_code']) && $process_response['status_code']=="200"){
                ZapierVCCParse::where('id',$d->id)
                    ->update([
                        'is_processed'=>1,
                        'is_processed_date'=>date('Y/m/d'),
                        'is_processed_status'=>$process_response['status_code'],
                        'midtrans_order_id'=>$process_response['order_id'],
                        'midtrans_transaction_status'=>$process_response['transaction_status'],
                        'finish_approval_code'=>$process_response['approval_code'],
                    ]);
                $data_log=array(
                    'date'=>$now,
                    'message'=>"Success Charge VCC",
                    'log'=>json_encode($process_response),
                    'status'=>'success',
                    'data'=>json_encode($d),
                    'id'=>$d->id
                );
                $this->logDataVCC($data_log);

                //====================================
                //posting deposit ke Opera
                $expiration_date=$card_exp_year.'-'.$card_exp_month.'-01'; // generate expiration date
                $expiration_date=date('Y-m-d',strtotime($expiration_date));
                $data_deposit=[
                    'amount'=>$amount,
                    'reservation_id'=>$opera_reservation_id,
                    'ota_name'=>$d->ota_name,
                    'approval_code_midtrans'=>$process_response['approval_code'],
                    'card_holder_name'=>$d->guestname,
                    'expiration_date'=>$expiration_date,
                    'card_number'=>$vcc_decrypt_final
                ];
                $depositOpera=$this->depositOpera($data_deposit);

                if($depositOpera['status']=="success"){
                    ZapierVCCParse::where('id',$d->id)->update(['is_posted_deposit_opera'=>1]);
                    $data_log=array(
                        'date'=>$now,
                        'message'=>"Success Deposit Opera",
                        'log'=>json_encode($depositOpera),
                        'status'=>'success',
                        'data'=>json_encode($d),
                        'id'=>$d->id
                    );
                    $this->logDataVCC($data_log);
                    $success++;
                }else{
                    $data_log=array(
                        'date'=>$now,
                        'message'=>"Failed Deposit Opera",
                        'log'=>json_encode($depositOpera),
                        'status'=>'error',
                        'data'=>json_encode($d),
                        'id'=>$d->id
                    );
                    $this->logDataVCC($data_log);
                    $this->mailError($d->id, $data_log);
                    $failed++;
                }
                //====================================

            }else{
            // jika proses gagal maka update juga di database sebagai penanda
                if(!is_array($process_response)){
                    $process_response= json_decode($process_response,true); // kadang responsnya belum ke convert jadi array JSON, maka dicek dulu
                }
                $validation_message=(isset($process_response['validation_messages'][0])) ? $process_response['validation_messages'][0] : '';

                ZapierVCCParse::where('id',$d->id)
                ->update([
                    'is_processed'=>1,
                    'is_processed_date'=>date('Y/m/d'),
                    'is_processed_status'=>$process_response['status_code'],
                    'midtrans_transaction_status'=>$validation_message
                ]);

                $data_log=array(
                    'date'=>$now,
                    'message'=>$validation_message,
                    'log'=>json_encode($process_response),
                    'status'=>'error',
                    'data'=>json_encode($d),
                    'id'=>$d->id
                );
                $this->logDataVCC($data_log);
                $this->mailError($d->id, $data_log);
                $failed++;
            }
        }

        $result = array(
            'total_data'=>$total_data,
            'success'=>$success,
            'failed'=>$failed
        );

        return response()->json($result);

    }

    public function charge_virtualCreditCard_view($data){
        $token_id=NULL;
        $amount=$data['amount'];
        $first_name = !empty($data['first_name']) ? $data['first_name'] : NULL;
        $last_name = !empty($data['last_name']) ? $data['last_name'] : NULL;
        $email = !empty($data['email']) ? $data['email'] : NULL ;
        $phone = !empty($data['phone']) ? $data['phone'] : NULL ;
        $card_number = $data['card_number'];
        $card_number = str_replace(' ', '', $card_number);
        $card_exp_month=$data['card_exp_month'];
        $card_exp_year=$data['card_exp_year'];
        $card_cvv = $data['card_cvv'];

        //check apakah tanggal start date dan end date sesuai
        $today=date('Y-m-d');


        //proses mendapatkan token ID
        $get_token= $this->getCardToken($card_number, $card_exp_month, $card_exp_year, $card_cvv);
        $get_token_decode= json_decode($get_token->content(),true);

        if(isset($get_token_decode['status_code']) && $get_token_decode['status_code'] == "200" ){
            if(isset($get_token_decode['token_id'])){
                $token_id=$get_token_decode['token_id'];
            }
            // proses charge VCC menggunakan token ID
            if(!empty($token_id)){
                $data=array(
                    'token_id'=>$token_id,
                    'card_number'=>$card_number,
                    'amount'=>$amount,
                    'first_name'=>$first_name,
                    'last_name'=>$last_name,
                    'email'=>$email,
                    'phone'=>$phone
                );

                $charge=$this->charge_virtualCreditCard_process($data);
                return $charge;
            }

        }else{
            return $get_token;
        }


    }

    public function getCardToken($card_number, $card_exp_month, $card_exp_year, $card_cvv){

        $is_production = config('intranet.is_production');
        if($is_production){
            \Midtrans\Config::$isProduction = true;
            $client_key=config('intranet.midtrans_client_key.production.kms');
        }else{
            \Midtrans\Config::$isProduction = false;
            $client_key=config('intranet.midtrans_client_key.sandbox.kms');
        }


        $path = "/token?card_number=" . $card_number
            . "&card_exp_month=" . $card_exp_month
            . "&card_exp_year=" . $card_exp_year
            . "&card_cvv=" . $card_cvv
            . "&client_key=" . $client_key;
        try{
            $get_token =  \Midtrans\ApiRequestor::get(
                \Midtrans\Config::getBaseUrl() ."/v2". $path,
                $client_key,
                false
            );
        }catch(\Exception $e){
            $log=$e->getMessage();

            $response_cut =  preg_replace('~\{(?:[^{}]|(?R))*\}~', '', $e->getMessage());
            $response = str_replace($response_cut,'',$e->getMessage());

            $data_log=array(
                'date'=>date('m/d/Y H:i:s'),
                'message'=>'Failed to get Card Token',
                'log'=>$log,
                'status'=>'error',
                'data'=>$path
            );
            $this->logDataVCC($data_log);

            return response()->json($response);
        }

        return response()->json($get_token);
    }

    public function charge_virtualCreditCard_process($data){
        $token_id=$data['token_id'];
        $card_number = $data['card_number'];
        $amount=$data['amount'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $phone = $data['phone'];

        $order_id="AYANA-".date('YmdHis').'-'.rand(0,100);

        $is_production = config('intranet.is_production');
        if($is_production){
            \Midtrans\Config::$isProduction = true;
            \Midtrans\Config::$serverKey = config('intranet.midtrans_server_key.production.kms');
        }else{
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$serverKey = config('intranet.midtrans_server_key.sandbox.kms');
        }



        $params = array(
            'transaction_details' => array(
                'order_id' => $order_id,
                'gross_amount' => $amount,
            ),
            'payment_type' => 'credit_card',
            'credit_card'  => array(
                'token_id'      => $token_id,
                'authentication'=> false,
                'bank'          => 'mandiri',
            ),
            'customer_details' => array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
            ),
        );

        try{
            $response = \Midtrans\CoreApi::charge($params);
            return response()->json($response);
        }catch(\Exception $e){

            $log=implode(". ",$data);
            $log.='params : '.json_encode($params).'.';
            $log.=$e->getMessage();


            $response_cut =  preg_replace('~\{(?:[^{}]|(?R))*\}~', '', $e->getMessage());
            $response = str_replace($response_cut,'',$e->getMessage());

            $data_log=array(
                'date'=>date('m/d/Y H:i:s'),
                'message'=>'Failed to charge card',
                'log'=>$log,
                'status'=>'error',
                'data'=>json_encode($data)
            );
            $this->logDataVCC($data_log);

            return response()->json($response);
        }
    }

    public function enkripsi_data_vcc(){
        $data=DB::connection('dbintranet')
        ->table('ZAPIER_VCC_PARSE')
        ->where('is_encrypted',0)
        ->get();
        $salt=config('intranet.zapier_vcc_salt');

        $total_data=count($data);
        $success=0;

        foreach($data as $row){
            $vcc =str_replace(' ', '', $row->vcc);
            $validuntil= str_replace(' ', '', $row->validuntil);
            $cvc =str_replace(' ', '', $row->cvc);

            $vcc_crypt=Crypt::encryptString($vcc);
            $validuntil_crypt=Crypt::encryptString($validuntil);
            $cvc_crypt=Crypt::encryptString($cvc);

            $vcc_crypt_1=substr($vcc_crypt,0,50);
            $vcc_crypt_2=substr($vcc_crypt, 50);
            $vcc_crypt_final = $vcc_crypt_1.$salt.$vcc_crypt_2;

            $validuntil_1=substr($validuntil_crypt,0,50);
            $validuntil_2=substr($validuntil_crypt, 50);
            $validuntil_final = $validuntil_1.$salt.$validuntil_2;

            $cvc_crypt_1=substr($cvc_crypt,0,50);
            $cvc_crypt_2=substr($cvc_crypt, 50);
            $cvc_crypt_final = $cvc_crypt_1.$salt.$cvc_crypt_2;

            try{
                $data=DB::connection('dbintranet')
                ->table('ZAPIER_VCC_PARSE')
                ->where('id', $row->id)
                ->update([
                    'vcc'=>$vcc_crypt_final,
                    'validuntil'=>$validuntil_final,
                    'cvc'=>$cvc_crypt_final,
                    'is_encrypted'=>1
                ]);
                $success++;
            }catch(\Exception $e){

            }

        }

        $result=[
            'data_to_encrypt'=>$total_data,
            'data_encrypted'=>$success
        ];

        return response()->json($result);


    }

    public function logDataVCC($data){

        $result = ZapierVCCLog::create([
            'DATE'=>isset($data['date'])? $data['date'] : '',
            'MESSAGE'=>isset($data['message'])? $data['message'] : '',
            'LOG'=>isset($data['log'])? $data['log'] : '',
            'STATUS'=>isset($data['status'])? $data['status'] : '',
            'DATA'=>isset($data['data'])? $data['data'] : '',
            'ID_VCC'=>isset($data['id'])? $data['id'] : NULL,
        ]);
        return $result;
    }

    public function updateZapierEmailParse($data,$id){
        $result = ZapierVCCParse::where('id',$id)->update($data);
        return $result;
    }

    public function soapClient($wsdl=''){
        try {
            $soap = new \SoapClient($wsdl, array("trace" => 1, "exception" => 0));
            return $soap;
        }

        catch ( \Exception $e) {
            return 'Caught Exception in client '. $e->getMessage();
            Log::info('Caught Exception in client'. $e->getMessage());
        }

    }

    public function getReferenceType($property){
        $property=strtoupper($property); // di uppercase biar gak salah sewaktu nyari stringnya kalau ada kecil/besar
        if (strpos($property, 'RIMBA') !== false) {
            return 'GUESTID';
        }else{
            return 'GDS_RECORD_LOCATOR';
        }
    }


    public function cekOperaData($data,$ReferenceType='GDS_RECORD_LOCATOR'){



        $reservation_id=$data['reservationid'];
        $ota=$data['ota'];
        switch($ota){
            case 'traveloka':
                $pre='TRK-';
                break;
            default :
                $pre ="TRK-";
            break;
        }

        $ref_number = $pre.$reservation_id.'-1';
        try{
            $is_production = config('intranet.is_production');
            if($is_production){
                $soapClient = $this->soapClient('https://ows1.ayana.id/OWS_WS_51/reservation.asmx?wsdl');
            }else{
                $soapClient = $this->soapClient('https://ows1-dev.ayana.id/OWS_WS_51/reservation.asmx?wsdl');
            }

            $headerVar = new \SoapVar('<OGHeader transactionID="0009998" primaryLangID="E" timeStamp="2022-03-15T14:48:16.0718750-05:00" xmlns="http://webservices.micros.com/og/4.3/Core/">
            <Origin entityID="OWS" systemType="PMS"/>
            <Destination entityID="OWS" systemType="WEB"/>
            <Authentication>
                <UserCredentials>
                <UserName>'.$this->opera_username.'</UserName>
                <UserPassword>'.$this->opera_password.'</UserPassword>
                <Domain>KMS1</Domain>
                </UserCredentials>
            </Authentication>
            </OGHeader>',
            XSD_ANYXML);
            $header = new \SoapHeader('http://webservices.micros.com/og/4.3/Core/','RequestParams',$headerVar);
            $soapClient->__setSoapHeaders($header);
            // $ReferenceType = 'GDS_RECORD_LOCATOR';
            // $ReferenceType = 'GUESTID';
            $externalSysNum = new ExternalReference($ref_number, 1, $ReferenceType);
            $params = array (
                'ExternalSystemNumber'=>$externalSysNum
            );
            $result = $soapClient->FetchBooking($params);
            $opera_reservation_id=NULL;
            $opera_fetchbooking_json=NULL;
            if( isset($result->Result->resultStatusFlag) && $result->Result->resultStatusFlag =="SUCCESS"){
                $opera_fetchbooking_json=json_encode($result);
                $unique_id_list=isset($result->HotelReservation->UniqueIDList->UniqueID) ?  collect($result->HotelReservation->UniqueIDList->UniqueID) : NULL;
                if(!empty($unique_id_list)){
                    $unique_id_list->filter(function ($value,$key) use (&$opera_reservation_id) {
                        if(isset($value->type) && $value->type=="INTERNAL"){
                            if(isset($value->source) && $value->source=="RESVID"){
                                $opera_reservation_id=$value->_; // dapat reservation ID opera
                                return $value->_;
                            }
                        }
                    });
                }
            }

            if(!empty($opera_reservation_id)){
                $return['status']="success";
                $return['data']=['opera_fetchbooking_json'=>$opera_fetchbooking_json, 'opera_reservation_id'=>$opera_reservation_id];
                $return['message']='Opera reservation found';
            }else{
                $return['status']="error";
                $return['data']=json_encode($result);
                $return['message']='Opera reservation cannot be found';
            }

            return $return;

        } catch(\SoapFault $e){
            $return['status']="error";
            $return['data']=[];
            $return['message']=$e->getMessage();
            return $return;
        }


    }

    public function depositOpera($data){

        $amount=$data['amount'];
        $reservation_id=$data['reservation_id'];
        $ota_name=$data['ota_name'];
        $approval_code_midtrans=$data['approval_code_midtrans'];
        try{

            $is_production = config('intranet.is_production');
            if($is_production){
                $soapClient = $this->soapClient('https://ows1.ayana.id/OWS_WS_51/ResvAdvanced.asmx?wsdl');
            }else{
                $soapClient = $this->soapClient('https://ows1-dev.ayana.id/OWS_WS_51/ResvAdvanced.asmx?wsdl');
            }

            $headerVar = new \SoapVar('<OGHeader transactionID="0009998" primaryLangID="E" timeStamp="2022-03-15T14:48:16.0718750-05:00" xmlns="http://webservices.micros.com/og/4.3/Core/">
            <Origin entityID="OWS" systemType="PMS"/>
            <Destination entityID="OWS" systemType="WEB"/>
            <Authentication>
                <UserCredentials>
                <UserName>'.$this->opera_username.'</UserName>
                <UserPassword>'.$this->opera_password.'</UserPassword>
                <Domain>KMS1</Domain>
                </UserCredentials>
            </Authentication>
            </OGHeader>',
            XSD_ANYXML);
            $header = new \SoapHeader('http://webservices.micros.com/og/4.3/Core/','RequestParams',$headerVar);
            $soapClient->__setSoapHeaders($header);

            //init variable untuk SOAP
            $LongInfo = $approval_code_midtrans.'-'.$ota_name.'-VCC ';
            $Charge = $amount;
            $FolioViewNo = 1;


            //init class untuk SOAP
            $HotelReference = new HotelReference('CHA','KMS1');
            $ReservationID=array(
                'UniqueID'=>$reservation_id
            );
            $ReservationRequestBase = new ReservationRequestBase($HotelReference,$ReservationID);
            $Posting = new Posting($LongInfo, $Charge, $FolioViewNo,$ReservationRequestBase);

            $cardHolderName=$data['card_holder_name'];
            $expirationDate=$data['expiration_date'];
            $cardNumber=$data['card_number'];

            $CreditCard = new CreditCard('BE-MIDTRANS',$cardHolderName, $expirationDate, $cardNumber);
            $CreditCardInfo = new CreditCardInfo($CreditCard);

            $params = array (
                'Posting'=>$Posting,
                'CreditCardInfo'=> $CreditCardInfo,
                'Reference'=>$approval_code_midtrans
            );
            $result = $soapClient->MakePayment($params);


            if($result->Result->resultStatusFlag=="SUCCESS"){
                $return['status']="success";
                $return['data']=$result;
                $return['message']='Success posting deposit into Opera';
            }else{
                $return['status']="error";
                $return['data']=$result;
                $return['message']='Failed posting deposit into Opera';
            }

            return $return;


        } catch(\SoapFault $e){
            $return['status']="error";
            $return['data']=[];
            $return['message']=$e->getMessage();
            return $return;
        }
    }

    public function mailError($id, $data){
        $salt=config('intranet.zapier_vcc_salt');
        if(!empty($id)){

            try{
                $data_vcc=DB::connection('dbintranet')->table('ZAPIER_VCC_PARSE')->where('id',$id)->first();
                if($data_vcc){
                    $vcc_decrypt=str_replace($salt,'',$data_vcc->vcc);
                    $vcc_decrypt_final=Crypt::decryptString($vcc_decrypt);
                    $data_vcc->vcc=$vcc_decrypt_final;

                    $validuntil_decrypt=str_replace($salt,'',$data_vcc->validuntil);
                    $validuntil_decrypt_final=Crypt::decryptString($validuntil_decrypt);
                    $data_vcc->validuntil=$validuntil_decrypt_final;

                    $cvc_decrypt=str_replace($salt,'',$data_vcc->cvc);
                    $cvc_decrypt_final=Crypt::decryptString($cvc_decrypt);
                    $data_vcc->cvc=$cvc_decrypt_final;

                    unset($data_vcc->is_processed);
                    unset($data_vcc->is_processed_date);
                    unset($data_vcc->is_processed_status);
                    unset($data_vcc->is_encrypted);

                    $mail = Mail::send('pages.zapier.zapier_vcc_error', ['data_log'=>$data, 'data_vcc'=>$data_vcc], function ($message) use ($data) {
                        $sendto = DB::connection('dbintranet')
                        ->table('dbo.ZAPIER_VCC_EMAIL_RECIPIENTS')
                        ->where('TYPE', 'to')
                        ->get()->pluck('EMAIL', 'EMAIL')->filter()->toArray();

                        $bcc = DB::connection('dbintranet')
                        ->table('dbo.ZAPIER_VCC_EMAIL_RECIPIENTS')
                        ->where('TYPE', 'bcc')
                        ->get()->pluck('EMAIL', 'EMAIL')->filter()->toArray();


                        $subject = "VCC Fetch Error Report";
                        $message->subject($subject);
                        $message->from('ayanareport@ayana.com', "AYANA VCC REPORT");
                        $message->to($sendto);
                        $message->bcc($bcc);
                    });
                }

            }catch(\Exception $e){
                $message=$e->getMessage();
                return false;
            }
        }



    }

    public function bersihin_data(){
        $data=DB::connection('dbintranet')
        ->select('SELECT paymentid, COUNT(id) as jumlah FROM ZAPIER_VCC_PARSE WHERE is_processed = 0 GROUP BY paymentid HAVING COUNT(paymentid) > 1');

        foreach($data as $data){
            $paymentid=$data->paymentid;
            //get data dimulai dari yang terakhir
            $rows=DB::connection('dbintranet')->table('ZAPIER_VCC_PARSE')->select('id')->where('paymentid',$paymentid)->orderBy('id','desc')->get();
            $i=0;
            foreach($rows as $rows){
                // hanya data selain data pertama (paling terbaru) yang kita ubah, maka update row nya di bawah ini
                if($i>0){
                    DB::connection('dbintranet')->table('ZAPIER_VCC_PARSE')->where('id',$rows->id)->update([
                        'is_processed'=>1,
                        'is_processed_status'=>'duplicate'
                    ]);
                }
                $i++;
            }

        }
    }



}
