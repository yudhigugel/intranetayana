<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\HumanResource\EmployeeModel;
use App\Models\UserModel;
use Log;
use Validator;
Use Cookie;
use stdClass;
use CURLFile;
use Exception;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

class ZohoController extends Controller{

  private $grant_type;
  private $client_id;
  private $client_secret;
  private $redirect_uri;


  public function __construct(){
    $this->grant_type='authorization_code';
    $this->client_id='1000.HFMTIGATUXECI59DJNYBPJTUEJOOSA';
    $this->client_secret='79f1f8b4b2792ba3b78ad0d72d475b1fd38290cc21';

    // $this->redirect_uri='http://localhost:8000/zoho_api/redirect';
    $this->redirect_uri='http://intranet-dev.ayana.id/zoho_api/redirect';
  }


  public function index(Request $request){
    var_dump($request);
  }

  public function checkAccessToken(){

  }

  public function generate_authorization_code(){

    $scope="ZohoSign.documents.all";//penting
    $url="https://accounts.zoho.com/oauth/v2/auth?scope=".$scope."&client_id=".$this->client_id."&state=123456789&response_type=code&redirect_uri=".$this->redirect_uri."&access_type=offline";
    return Redirect::to($url);
  }


  public function generate_access_token(Request $request){

    $code=DB::connection('dbintranet')
    ->table('ZOHO_API_TOKEN')
    ->where('type','zoho_sign')
    ->get();

    $auth_code=$code[0]->authorization_code;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://accounts.zoho.com/oauth/v2/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "grant_type=".$this->grant_type."&code=".$auth_code."&client_id=".$this->client_id."&redirect_uri=".$this->redirect_uri."&client_secret=".$this->client_secret);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);


    $post=json_decode($server_output);
    $result=false;
    if(!empty($post->access_token)){
        $access_token=$post->access_token;
        $result=DB::connection('dbintranet')
        ->table('ZOHO_API_TOKEN')
        ->where('type','zoho_sign')
        ->update(['token_access'=>$access_token]);
        echo "new access token : <b>".$access_token."</b><br>";

        if(!empty($post->refresh_token)){
            $result=DB::connection('dbintranet')
            ->table('ZOHO_API_TOKEN')
            ->where('type','zoho_sign')
            ->update(['token_refresh'=>$post->refresh_token]);
            echo "new refresh token : <b>".$post->refresh_token."</b>";
        }

    }

    if(!$result){
        echo $post->error;
    }

  }

  public function refresh_token(){
    $code=DB::connection('dbintranet')
    ->table('ZOHO_API_TOKEN')
    ->where('type','zoho_sign')
    ->get();
    $refresh_token=$code[0]->token_refresh;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://accounts.zoho.com/oauth/v2/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "refresh_token=".$refresh_token."&client_id=".$this->client_id."&client_secret=".$this->client_secret."&grant_type=refresh_token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);

    $post=json_decode($server_output);

    $result=false;
    if(!empty($post->access_token)){
        $access_token=$post->access_token;
        $result=DB::connection('dbintranet')
        ->table('ZOHO_API_TOKEN')
        ->where('type','zoho_sign')
        ->update(['token_access'=>$access_token]);
        $data=array(
            'message'=>'success',
            'access_token'=>$access_token
        );
        return response()->json($data,200);
        // echo "new access token : <b>".$access_token."</b><br>";
    }

    if(!$result){
        $data=array(
            'message'=>'error',
            'data'=>$post->error
        );
        return response()->json($data,200);
    }

  }

  public function redirect(Request $request){
    $code=$request->input('code');

    $result=DB::connection('dbintranet')
    ->table('ZOHO_API_TOKEN')
    ->where('type','zoho_sign')
    ->update(['authorization_code'=>$code]);

    if($result){
      return redirect('/zoho_api/generate_token?code='.$code);
    }else{
      echo "error";
    }

  }

  public function add_record(){
    $code=DB::connection('dbintranet')
    ->table('ZOHO_API_TOKEN')
    ->where('type','zoho_sign')
    ->get();

    $token_access=$code[0]->token_access;
    $payload=array(
        'data'=>array(
            'Invoice_ID'=>'002',
            "Date_Time"=>"10-Jan-2020 22:12:10",
            "Items"=> array(
                array(
                    "item_id"=>"BARANG1",
                    "item_name"=>"Barang Nomor 1",
                    "item_price"=>"50000"
                ),
                array(
                    "item_id"=>"BARANG2",
                    "item_name"=>"Barang Nomor 2",
                    "item_price"=>"500000"
                ),
                array(
                    "item_id"=>"BARANG3",
                    "item_name"=>"Barang Nomor 3",
                    "item_price"=>"5000000"
                )
            )
        ),
        'result'=>array(
            "fields"=>array(
                "Invoice_ID",
                "Date_Time"
            ),
            "message"=> true,
            "tasks"=> true
        )
    );

    $payload=json_encode($payload);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://creator.zoho.com/api/v2/david_djokopramono_midplaza/test-api/form/Invoice");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Zoho-oauthtoken '.$token_access,
        'Content-Type:application/json'
    ));
    $server_output = curl_exec($ch);
    curl_close ($ch);

    echo $server_output;

  }

    public function upload_document(){
        $code=DB::connection('dbintranet')
        ->table('ZOHO_API_TOKEN')
        ->where('type','zoho_sign')
        ->get();

        $token_access=$code[0]->token_access;

        $tanggal= date('Ymd-His');
        try{
            $file=fopen(''.$_SERVER['DOCUMENT_ROOT'].'/upload/contract/test.docx', 'r');
            $actionsJson=(object)array();
            $documentJson=(object)array();
            $request=(object)array();
            $actionsJson->recipient_name = "Apriadi";
            $actionsJson->recipient_email = "apriadinur.k@gmail.com";
            $actionsJson->action_type = "SIGN";
            $actionsJson->private_notes = "Please get back to us for further queries";
            $actionsJson->signing_order = 0;
            $actionsJson->verify_recipient = true;
            $actionsJson->verification_type = "EMAIL";
            $documentJson->request_name = "Test".$tanggal."";
            $documentJson->expiration_days = 1;
            $documentJson->is_sequential = true;
            $documentJson->email_reminders = true;
            $documentJson->reminder_period = 8;
            $documentJson->actions = array($actionsJson);
            $request->requests = $documentJson;
            $requestData = json_encode($request);

            $file_path = public_path().'/upload/contract/test.docx';


            $client = new \GuzzleHttp\Client();
            $options = [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => Psr7\Utils::tryFopen($file_path, 'r')
                    ],
                    [
                        'name' => 'data',
                        'contents' => $requestData
                    ]
                ],
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken '.$token_access.'',
                ]
            ];
            $response = $client->request('POST', 'https://sign.zoho.com/api/v1/requests', $options);

            echo $response->getBody();

        }catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $error=json_decode($responseBodyAsString);
            $code=$error->code;
            if($code=="9041"){
                return redirect('');
            }




        }

    }

    public function send_document_signature(Request $request){

        $document_id=$request->get('document_id');
        $request_id=$request->get('request_id');
        $action_id=$request->get('action_id');

        $code=DB::connection('dbintranet')
        ->table('ZOHO_API_TOKEN')
        ->where('type','zoho_sign')
        ->get();

        $token_access=$code[0]->token_access;


        $data=new stdClass();
        $actionsJson=(object)array();
        $request=(object)array();
        $fields=array();



        $actionsJson->verify_recipient = false;
        $actionsJson->action_id=$action_id;
        $actionsJson->action_type = "SIGN";
        $actionsJson->private_notes = "Please get back to us for further queries";
        $actionsJson->signing_order = 0;
        // $actionsJson->recipient_name = "Apriadi";
        // $actionsJson->recipient_email = "apriadinur.k@gmail.com";
        // $actionsJson->in_person_name = "Apriadi Nur Kurniawan";
        // $actionsJson->verification_type = "EMAIL";

        $fields[0]['field_type_name'] = "Email";
        $fields[0]['field_category'] = "textfield";
        $fields[0]['field_label'] = "Email";
        $fields[0]['is_mandatory'] = true;
        $fields[0]['page_no'] = 0;
        $fields[0]['field_name'] = "Email";
        $fields[0]['document_id'] = $document_id;
        $fields[0]['description_tooltip'] = "";
        $fields[0]['y_coord'] = 3;
        $fields[0]['abs_width'] = 30;
        $fields[0]['x_coord'] = 4;
        $fields[0]['abs_height'] = 2;

        $fields[1]['field_type_name'] = "Signature";
        $fields[1]['field_category'] = "image";
        $fields[1]['field_label'] = "Signature";
        $fields[1]['is_mandatory'] = true;
        $fields[1]['page_no'] = 0;
        $fields[1]['field_name'] = "Signature";
        $fields[1]['document_id'] = $document_id;
        $fields[1]['description_tooltip'] = "";
        $fields[1]['y_coord'] = 3;
        $fields[1]['abs_width'] = 22;
        $fields[1]['x_coord'] = 42;
        $fields[1]['abs_height'] = 2;

        $fields[2]['field_type_name'] = "Initial";
        $fields[2]['field_category'] = "image";
        $fields[2]['field_label'] = "Initial";
        $fields[2]['is_mandatory'] = true;
        $fields[2]['page_no'] = 0;
        $fields[2]['field_name'] = "Initial";
        $fields[2]['document_id'] = $document_id;
        $fields[2]['description_tooltip'] = "";
        $fields[2]['y_coord'] = 3;
        $fields[2]['abs_width'] = 18;
        $fields[2]['x_coord'] = 71;
        $fields[2]['abs_height'] = 5;

        $fieldsJson=array_values($fields);
        $actionsJson->fields=$fieldsJson;
        $request->actions = array($actionsJson);
        $request->actions[0]->recipient_phonenumber='';
        $request->actions[0]->recipient_countrycode='';
        $request->actions[0]->deleted_fields='';
        $request->deleted_actions = array();
        $request->request_name = "Leave a note test";
        $data->requests=$request;

        $requestData = json_encode($data);

        // echo $requestData;
        // die;

        $client = new \GuzzleHttp\Client();
        $options = [
            'form_params' => [
                'data' => $requestData,
            ],
            'headers' => [
                'Authorization' => 'Zoho-oauthtoken '.$token_access.'',
            ]
        ];
        $response = $client->request('POST', 'https://sign.zoho.com/api/v1/requests/'.$request_id.'/submit', $options);

        echo $response->getBody();


    }

    public function get_document_list(){
        $code=DB::connection('dbintranet')
        ->table('ZOHO_API_TOKEN')
        ->where('type','zoho_sign')
        ->get();

        $token_access=$code[0]->token_access;


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sign.zoho.com/api/v1/requests',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => 'row_count=10&start_index=1&sort_column=created_time&sort_order=ASC',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Zoho-oauthtoken '.$token_access.'',
            'Content-Type: application/x-www-form-urlencoded',
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;



    }




}




