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
use App\Models\Zoho\ZohoFormModel;
use App\Models\UserModel;
use Log;
use Validator;
Use Cookie;
use stdClass;
use CURLFile;
use Exception;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

class ZohoAPIController extends Controller{

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

  public function formRequestEntertainment(Request $request){
    $model = new zohoFormModel();
    $data = array();


    $res = $model->formRequestEntertainment($request);

    $data['req'] = $request->all();
    $data['resp'] = $res;
    $data['url'] = 'formRequestEntertainment';
    $setLog = $model->insLog($data);
    return $res;
  }






}




