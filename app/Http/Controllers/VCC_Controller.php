<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use App\Models\HumanResource\EmployeeModel;
use App\Models\UserModel;
use Log;
use Validator;
Use Cookie;
use DataTables;

class VCC_Controller extends Controller{
    public function index(Request $request){

        $request_date_from = (!empty($request->input('request_date_from')))?  $request->input('request_date_from') : NULL ;
        $request_date_to = (!empty($request->input('request_date_to')))?  $request->input('request_date_to') : NULL ;
        $status= (!empty($request->input('status')))?  $request->input('status') : NULL ;

        $data=array(
            'status'=>$status,
            'request_date_from'=>$request_date_from,
            'request_date_to'=>$request_date_to,
        );

        return view('pages.vcc-parsing.index', ['data' => $data]);
    }

    public function index_getData(Request $request){
        try{
            $filter=strtoupper($request->input('search_filter'));
            $value=strtoupper($request->input('value'));
            $insert_date_from=$request->input('insert_date_from');
            $insert_date_to=$request->input('insert_date_to');
            $status=strtoupper($request->input('status'));
            $request_type=strtoupper($request->input('request_type'));

            $where='1=1';
            if (($insert_date_from != null or $insert_date_from !="")&&($insert_date_to != null or $insert_date_to !="") ){
                $where = $where." and date_fetched_from_mail between '".$insert_date_from." 00:00:00' and '".$insert_date_to." 23:59:59' ";
            }



            if(!empty($status)){
                $is_processed=($status=='UNPROCESSED')? 0 : 1 ;
                $where = $where." AND is_processed = ".$is_processed."";
            }

            $where = $where." AND (is_processed_status <> 'duplicate'  OR is_processed_status IS NULL)";





            $data = DB::connection('dbintranet')
                    ->table('ZAPIER_VCC_PARSE')
                    ->whereraw(DB::raw($where))->get();



            $result=array();
            foreach($data as $key=>$value){
                $data_array = (array)$value;
                $result[]=$data_array;
            }
            return DataTables::of($result)->make(true);
        }
        catch(\Exception $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }
    }

    public function modal_detail(Request $request)
    {
        $data=NULL;
        $id=$request->input('id');
        if(!empty($id)){
            $data=DB::connection('dbintranet')
                    ->table('ZAPIER_VCC_PARSE')
                    ->where('id',$id)
                    ->get();

            $salt=config('intranet.zapier_vcc_salt');
            try{
                $vcc_decrypt=str_replace($salt,'',$data[0]->vcc);
                $vcc_decrypt_final=Crypt::decryptString($vcc_decrypt);


                $validuntil_decrypt=str_replace($salt,'',$data[0]->validuntil);
                $validuntil_decrypt_final=Crypt::decryptString($validuntil_decrypt);

                $cvc_decrypt=str_replace($salt,'',$data[0]->cvc);
                $cvc_decrypt_final=Crypt::decryptString($cvc_decrypt);
            }catch(\Exception $e){
                $vcc_decrypt_final=$data[0]->vcc;
                $validuntil_decrypt_final=$data[0]->validuntil;
                $cvc_decrypt_final=$data[0]->cvc;
            }

            $data[0]->vcc = $vcc_decrypt_final;
            $data[0]->cvc = $cvc_decrypt_final;
            $data[0]->validuntil = $validuntil_decrypt_final;

            $data=(empty($data))? $data : $data[0];

            $log=DB::connection('dbintranet')->table('ZAPIER_VCC_LOG')->where('ID_VCC',$id)->where('STATUS','!=','pending')->orderBy('SEQ_ID','desc')->get();
        }

        return View::make('pages.vcc-parsing.modal-detail')->with(['data' => $data, 'log' => $log])->render();
    }

    public function update(Request $request){

        $data=$request->all();
        $id=$data['id'];


        unset($data['id']);
        unset($data['_token']);

        $salt=config('intranet.zapier_vcc_salt');

        $vcc =str_replace(' ', '', $data['vcc']);
        $validuntil= str_replace(' ', '', $data['validuntil']);
        $cvc =str_replace(' ', '', $data['cvc']);

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

        $data['vcc']=$vcc_crypt_final;
        $data['cvc']=$cvc_crypt_final;
        $data['validuntil']=$validuntil_final;

        try{

            DB::connection('dbintranet')->table('ZAPIER_VCC_PARSE')->where('id',$id)->update($data);
            $success=true;
            $code = 200;
            $msg = "Update data VCC success";
        }catch(\Exception $e){
            $success=false;
            $code = 403;
            $msg = $e->getMessage();
        }

        return response()->json(array('success' => $success, 'message' => $msg, 'code' => $code, 200));
    }
}
