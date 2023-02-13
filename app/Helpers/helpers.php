<?php // Code within app\Helpers\Helper.php

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string $path
     * @return string
     */
     function public_path($path = '')
     {
         return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
     }
 }

function imuneString($request)
{
	$data = addslashes(trim((string)$request));
	return $data;
}

function cleanString($string){
  return preg_replace('/[^A-Za-z0-9\-()_.]/', ' ', $string);
}

function highlight_keywords($keyword, $string) {
    return preg_replace("/(\p{L}*?)(".preg_quote($keyword,'/').")(\p{L}*)/ui", "$1<span class=\"h\">$2</span>$3", $string);
}

function no_has($request,$input_name){
    $res['code'] = 500;
    $res['message'] = [];
    $res['data'] = (object) array();
    $error=false;
       for($a=0;$a<count($input_name);$a++){
            if(!$request->has($input_name[$a])){
                $res['message'][] = "Failed .please insert ".$input_name[$a]." parameter ";
                $error=true;
            }
       }
       if($error){
        return $res;
       }


}
 function no_num($request,$input_name){
    $res['code'] = 500;
    $res['message'] = [];
    $res['data'] = (object) array();
    $error=false;
       for($a=0;$a<count($input_name);$a++){
            if($request->has($input_name[$a])){
                if(is_string($request->input($input_name[$a]))){
                    $res['message'][] = "Failed .".$input_name[$a]." must be numeric ";
                    $error=true;
                }
            }
       }
       if($error){
        return $res;
       }
}
function no_string($request,$input_name){
    $res['code'] = 500;
    $res['message'] = [];
    $res['data'] = (object) array();
    $error=false;
       for($a=0;$a<count($input_name);$a++){
            if($request->has($input_name[$a])){
                if(!is_string($request->input($input_name[$a]))){
                    $res['message'][] = "Failed .".$input_name[$a]." must be string ";
                    $error=true;
                }
            }
       }
       if($error){
        return $res;
       }
}
function no_bool($request,$input_name){
    $res['code'] = 500;
    $res['message'] = [];
    $res['data'] = (object) array();
    $error=false;
       for($a=0;$a<count($input_name);$a++){
            if($request->has($input_name[$a])){
                if(!is_bool($request->input($input_name[$a]))){
                    $res['message'][] = "Failed .".$input_name[$a]." must be boolean ";
                    $error=true;
                }
            }
       }
       if($error){
        return $res;
       }
}
function length_all($request,$input_name,$len){
    $res['code'] = 500;
    $res['message'] = [];
    $res['data'] = (object) array();
    $error=false;
       for($a=0;$a<count($input_name);$a++){
            if($request->has($input_name[$a])){
                if(strlen($input_name[$a])<=$len){
                    $res['message'][] = "Failed .please input ".$input_name[$a]." less than ".$len;
                    $error=true;
                }
            }
       }
       if($error){
        return $res;
       }
}
function length($request,$input_name){
    $res['code'] = 500;
    $res['message'] = [];
    $res['data'] = (object) array();
    $error=false;
       for($a=0;$a<count($input_name);$a++){
            if($request->has($input_name[$a]['name'])){
                if(strlen($input_name[$a]['name'])<=$input_name[$a]['len']){
                    $res['message'][] = "Failed .".$input_name[$a]['name']." less than ".$input_name[$a]['len'];
                    $error=true;
                }
            }
       }
       if($error){
        return $res;
       }
}
function imuneInt($request)
{
	$data = addslashes(trim((int)$request));
	return $data;
}


function stringToHex($string)
{
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}

function aes128Encrypt($plaintext) {
    $key = 'EL12ec@REteLe(0M';
//     if(16 !== strlen($key)) $key = hash('MD5', $key, true);
//     $padding = 16 - (strlen($data) % 16);
//     $data .= str_repeat(chr($padding), $padding);
//     return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16)));
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-ECB");
    $iv = openssl_random_pseudo_bytes($ivlen);
    // $iv = 0;
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    // $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    $ciphertext = base64_encode( $ciphertext_raw );
    // dd($ciphertext);
    return $ciphertext;
}

function rupiah($angka){
    $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
    return $hasil_rupiah;
}


function getLonglatByZipcode($id){
    try{
        $rawlonglat = file_get_contents('https://us1.locationiq.com/v1/search.php?key=f47298eb617963&country=Indonesia&postalcode='.$id.'&format=json');
        $datalonglat = json_decode($rawlonglat);
        $lat = $datalonglat[0]->lat;
        $lon = $datalonglat[0]->lon;
        $longlat = $lat . ' - ' .$lon;
        $data = '<a href="https://maps.google.com/?q='.$lat.','.$lon.'" target="_blank">'.$longlat.'</a>';
    }catch(\Exception $ex){
        $data = '-';
    }
    return $data;
}



function getTokenTiki()
{
    $jsonReq=config('apiconfig.usrpwdtiki');
    $url = curl_init(config('apiconfig.apitiki').'/user/auth');
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url, CURLOPT_POSTFIELDS , $jsonReq);
    //curl_setopt($url, CURLOPT_PORT, 80);
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_HTTPHEADER, array
        (
            'Content-Type: application/json',
        )
    );
    //execute post
    $result= curl_exec($url);

    curl_close($url);
    $obj['resp'] = json_decode($result,true);
    $obj['req'] = $jsonReq;
    return $obj;
}

function getTokenZoho()
{
    $url = curl_init("https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.a632cf879b3095f7f0dbafce88bab022.d174cb9af21ccfff8ec58d43f91c42c6&client_id=1000.6570ZM80ANS1AA8APR7XCDDRJZHVTC&client_secret=a682ac852607345f2228a6adfc7b087f7b249ec366&grant_type=refresh_token");
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    // curl_setopt($url, CURLOPT_POSTFIELDS , $jsonReq);
    //curl_setopt($url, CURLOPT_PORT, 80);
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_HTTPHEADER, array
        (
            'Content-Type: application/json',
        )
    );
    //execute post
    $result= curl_exec($url);
    curl_close($url);
    $obj = json_decode($result,true);
    $res = array();
    if(array_key_exists('access_token',$obj)){
        $res['code'] = 1;
        $res['token'] = $obj['access_token'];
    }else{
        $res['code'] = 0;
        $res['response'] = $result;
    }
    return $res;
}

function scan_dir($dir) {
    try{
        $ignored = array('.', '..');

        $files = array();
        foreach (scandir($dir) as $file) {
            if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime($dir . '/' . $file);
        }
        arsort($files);
        $files = array_keys($files);
    }catch(\Exception $ex){
        $files = $ex->getMessage();
    }
    return ($files) ? $files : false;
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function fixingNumber($phoneold){
    $phonenew = "";
    if (preg_match("/626208/", substr($phoneold, 0, 6))) {
        $length = strlen($phoneold);
        $lastNumber = substr($phoneold, 6);
        $phonenew = '+628' . $lastNumber;
    }

    if (preg_match("/6208/", substr($phoneold, 0, 4))) {
        $length = strlen($phoneold);
        $lastNumber = substr($phoneold, 4);
        $phonenew = '+628' . $lastNumber;
    }

    if (preg_match("/628/", substr($phoneold, 0, 3))) {
        $length = strlen($phoneold);
        $lastNumber = substr($phoneold, 3);
        $phonenew = '+628' . $lastNumber;
    }

    if (preg_match("/8/", substr($phoneold, 0, 1))) {
        $length = strlen($phoneold);
        $lastNumber = substr($phoneold, 1);
        $phonenew = '+628' . $lastNumber;
    }

    if (preg_match("/08/", substr($phoneold, 0, 2))) {
        $length = strlen($phoneold);
        $lastNumber = substr($phoneold, 2);
        $phonenew = '+628' . $lastNumber;
    }

    if($phonenew === "" ){$phonenew = $phoneold;}
    return $phonenew;
}

function getFlagNum($id){
    $url = curl_init('http://192.168.8.79/flagAutoDebt/'.$id);
    curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url, CURLOPT_HTTPHEADER, array
        (
            'Content-Type: application/json',
            'Authorization: Basic Y3VzdG9tZXJtb25pdG9yaW5nY2VudGVyOg=='
        )
    );
    $result = curl_exec($url);
    curl_close($url);
    $obj = json_decode($result,true);
    return $obj;
}

function insertNotification($employee_id, $link, $desc, $type){
    if(!empty($employee_id) && !empty($link) && !empty($desc)){
        try{
            $result=DB::connection('dbintranet')
            ->table('TBL_NOTIFICATIONS')
            ->insert([
                "NOTIF_EMPLOYEE_ID"=>$employee_id,
                "NOTIF_LINK"=>$link,
                "NOTIF_DESC"=>$desc,
                "NOTIF_ISREAD"=>0,
                "NOTIF_DATE_CREATED"=>date("Y-m-d H:i:s"),
                "NOTIF_ICON_TYPE"=>$type
            ]);
            if($result){
                $success=true;
                $code = 200;
                $log="";
                $msg = "Notification added successfully";
            }
        }catch(QueryException $e) {
            $success=false;
            $code = 403;
            $log="";
            $msg = $e->errorInfo;
        }
        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 'log' => $log, 200));
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function toThousand($count){
    if ($count >= 1000) {
        return round($count/1000, 1);
    } elseif($count < 0 && $count <= -1000){
        return round($count/1000, 1);
    } else {
        return $count;
    }
}

function taxCodeCalculation($taxcode, $number){
    switch (strtoupper($taxcode)) {
        case 'Y1': case 'L2' :
            // Dikali 10 persen
            $return = ($number * 0.1);
            break;
        case 'Y4':
            // Dikali 1 persen
            $return = ($number * 0.01);
            break;
        case 'O1' : case 'O2' :
            // Dikali 11 persen
            $return = ($number * 0.11);
            break;
        case 'O5' :
            // Dikasi 1.1 persen
            $return = ($number * 0.011);
            break;
        default:
            $return = 0;
            break;
    }
    return $return;
}