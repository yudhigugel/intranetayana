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

class UserController extends Controller{
  public function act_change_password(Request $request){
    $userModel= new UserModel();
    $old_password=$request->old_password;
    $new_password=$request->password;
    $confirm_password=$request->confirm_password;


    if($new_password!==$confirm_password){
      $success=false;
      $msg="New password does not match!";
      $code='406';
    }else{
      $username=Session::get('user_data')->WORK_EMAIL;
      $data=$userModel->getUserByWorkEmail($username);
       if (Hash::check($old_password, $data[0]->PASSWORD)) {
          //trap jika password lama sama dengan password baru
          if($old_password==$new_password){
              $success=false;
              $msg="Your new password cannot be the same as your current password. Please use another.";
              $code='400';
          }else{
              // The passwords match...
              $pwd_update=Hash::make($new_password);
              $result=$userModel->updateUserPasswordByWorkEmail($username,$pwd_update);
              if($result){
                //set expiration date
                $today=date('Y-m-d H:i:s');
                $expired=date('Y-m-d H:i:s', strtotime($today. ' + 90 days'));

                $userModel->setExpirationDateByWorkEmail($username,$expired);
                try{
                  $new_data = $userModel->getUserByWorkEmail($username);
                  Session::put('user_data', $new_data[0]);
                } catch(\Exception $e){}

                $success=true;
                $msg="Your password has been changed.";
                $code='200';
              }else{
                $success=false;
                $msg="Something's not right, please try again later";
                $code='400';
              }
          }

        }else{
          $success=false;
          $msg="Your old password is incorrect!";
          $code='400';
        }
    }


    return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));

  }

  public function change_password(){
    return view('pages.user.change-password');
  }

  public function generate_password(Request $request){
    $text=$request->input('text');
    $result=Hash::make($text);
    echo 'hash dari <b>'.$text.'</b> adalah : <br/><b>'.$result.'<b/>';
  }

  public function change_profile(Request $request){
    $id=$request->session()->get('user_id');

    $data=EmployeeModel::where('EMPLOYEE_ID',$id)->get();

    return view('pages.user.change-profile', ['data' => $data[0]]);
  }

  public function act_change_profile(Request $request){

    try{
      $id=$request->EMPLOYEE_ID;

      $messages = array(
        'EMPLOYEE_NAME.required' => 'The employee name field is required.',
        'EMPLOYEE_NAME.max' => 'The employee name field max character is 150.',

        'BIRTH_DATE.required' => 'The birth date field is required.',
        'CITY.required' => 'The city field is required.',

        'ZIPCODE.required' => 'The zipcode field is required.',
        'ZIPCODE.max' => 'The zipcode field max character is 10.',

        'STREET_1.required' => 'The street 1 field is required.',
        'STREET_1.max' => 'The street 1 field max character is 200.',

        'MOBILE_NUMBER_1.required' => 'The mobile number 1 field is required.',
        'MOBILE_NUMBER_1.max' => 'The mobile number 1 field max character is 15.',
      );

      $validator = Validator::make($request->all(), [
          'EMPLOYEE_NAME' => 'required|max:150',
          'BIRTH_DATE' => 'required',
          'CITY' => 'required',
          'ZIPCODE' => 'required|max:10',
          'STREET_1' => 'required|max:200',
          'MOBILE_NUMBER_1' => 'required|max:15',
      ], $messages);

      if ($validator->fails()) {
        //validasi kalau field salah
        $error=json_decode($validator->errors());
        $msg_error='';
        foreach($error as $err){
          $msg_error.=$err[0];
        }
        $success=false;
        $msg=$msg_error;
        $code='400';
      }else{

         $data=array(
          'EMPLOYEE_NAME' => $request->EMPLOYEE_NAME,
          'BIRTH_DATE' => $request->BIRTH_DATE,
          'CITY' => $request->CITY,
          'ZIPCODE' => $request->ZIPCODE,
          'STREET_1' => $request->STREET_1,
          'STREET_2' => $request->STREET_2,
          'STREET_3' => $request->STREET_3,
          'MOBILE_NUMBER_1' => $request->MOBILE_NUMBER_1,
          'MOBILE_NUMBER_2' => $request->MOBILE_NUMBER_2,
          'USER_MODIFIED'=>1,
          'DATE_LAST_PROFILE_UPDATE'=>date('Y-m-d H:i:s')
        );

        /* upload image function */
        if(!empty($request->image)){
          $imageName = time().'.'.$request->image->extension();
          $request->image->move(public_path('upload/profile_photo'), $imageName);
          $data['IMAGE_PHOTO']=$imageName;

        }
        /* --- */

        $result=EmployeeModel::where('EMPLOYEE_ID',$id)->update($data); // update data to table

        if($result){
          if(!empty($request->session()->get('user_id')) && $request->session()->get('user_id') == $id ){
            //refresh session yang berlaku khusus datanya saja
            app('reset_session_credential');
          }

          $success=true;
          $msg="Data has been updated";
          $code='200';
        }else{
          $success=false;
          $msg="Something's not right, please try again later";
          $code='400';
        }
      }


      return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));

   } catch (\Exception $e) {

      // $result['log']['file'] = $e->getFile();
      // $result['log']['message'] = $e->getMessage();
      // $result['log']['line'] = $e->getLine();

      // $result['code'] = '500';
      // $result['status'] = 'failed';
      // $result['message'] = 'Internal Server Error';

      //bikin log
      Log::error(response()->json($e));
      return $e;
    } catch (\Illuminate\Database\QueryException $e) {

      // $result['log']['file'] = $e->getFile();
      // $result['log']['message'] = $e->getMessage();
      // $result['log']['line'] = $e->getLine();

      // $result['code'] = '502';
      // $result['status'] = 'failed';
      // $result['message'] = 'Database Error';

      //bikin log
      // Log::error(response()->json($result['log']));
      Log::error(response()->json($e));
      return $e;
    }

  }

}




