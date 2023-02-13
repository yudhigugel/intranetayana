<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\HumanResource\EmployeeModel;
use App\Models\UserModel;
use Log;
use Validator;
Use Cookie;
use Illuminate\Database\QueryException;

class Notifications extends Controller{

    public function ajax_readNotif(Request $request){
        $id=Session::get('user_id');

        try{
            $result=DB::connection('dbintranet')
            ->table('TBL_NOTIFICATIONS')
            ->where('NOTIF_EMPLOYEE_ID',$id)
            ->update([
                "NOTIF_ISREAD"=> 1
            ]);
        }catch(QueryException $e){
            return response()->json(['data'=>[], 'message'=>$e->getMessage()], 200);
        }


        return response()->json(['data'=>[], 'message'=>"success"], 200);

    }

}

