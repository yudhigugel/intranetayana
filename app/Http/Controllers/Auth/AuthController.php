<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\UserModel;
use App\Models\HumanResource\EmployeeModel;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use DateTime;

class AuthController extends Controller
{

    /**
     * login function
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {

        try{
          $userModel= new UserModel();
          $employeeModel= new EmployeeModel();
          $username=$request->username;
          $password=$request->password;
          $expiry_status=array();

          $result=$userModel->getUserByWorkEmail($username);
          if(isset($result['status']) && $result['status'] == 'failed'){
            $exception_message = isset($result['message']) ? $result['message'] : 'Something went wrong when trying to connect to the database, please try again later';
            throw new \Exception($exception_message);
          }

          $count=count($result);
          if($count>0){
            if($count > 1){
              $success=false;
              $msg=sprintf("Found %s account with same credential, please contact your IT administrator", $count);
              $data['code']='401';
            }
            else
            {
              if (Hash::check($password, $result[0]->PASSWORD)) {
                $cek_status = isset($result[0]->EMPLOYEE_STATUS_ASSIGNMENT) ? $result[0]->EMPLOYEE_STATUS_ASSIGNMENT : '';
                if(!$cek_status){
                  throw new \Exception('Not allowed to login. Account status is not active');
                }

                  // The passwords match...
                //   $param=array(
                //     'dbo.INT_EMPLOYEE.EMPLOYEE_ID'=>$result[0]->EMPLOYEE_ID
                //   );
                //   // Cek assignment dari employee ada di company dan plant mana
                //   $assignment_data=$employeeModel->employeeSearchAssignment($param);

                 $param=array(
                    'EMPLOYEE_ID'=>$result[0]->EMPLOYEE_ID
                  );
                  $assignment_data=$employeeModel->employeeSearch($param);

                  $expired_date=$result[0]->DATE_EXPIRED_PASSWORD;
                  $do_expire=$result[0]->IS_EXPIRED;
                  $today=date('Y-m-d');
                  $expiry_treshold=30;

                  //kalkulasi berapa hari lagi expired
                  $date1=new DateTime($today);
                  $date2=new DateTime($expired_date);
                  $different= $date1->diff($date2);

                  $datetoexpire=$different->days;

                  //trap kalau lewat today sama expired date
                  if($date1>$date2){
                    $datetoexpire=0;
                  }

                  $warning_expired=$warning_expired_treshold=false; //set warning expired default false
                  $expiry_status['do_expire']=$do_expire;
                  $expiry_status['expirydate']=$expired_date;
                  $expiry_status['datetoexpire']=$datetoexpire;

                  $success=true;
                  $msg="Login success";
                  $data['code']='200';

                  //trap expiration date
                  if($do_expire=='Y'){
                      if($datetoexpire<1){

                          $success=false;
                          $msg="Your account password has been expired, you have to change your password now";
                          $data['code']='405';
                      }else if($datetoexpire>1 && $datetoexpire<=$expiry_treshold){

                          $warning_expired_treshold=true;
                          $msg="Your account will expired in $datetoexpire day(s), consider to change your password.";
                      }
                  }

                  $expiry_status['warning_expired_treshold']=$warning_expired_treshold;

              }else{
                  $success=false;
                  $msg="The username or password is incorrect";
                  $data['code']='401';
              }
            }
          }
          else{
              $success=false;
              $msg="The username or password is incorrect";
              $data['code']='401';
          }


          $data['expiry_status']=$expiry_status;
          $url_previous = null;

          if ($success || $success == "1") {
            Session::put('logged_in', 1, config('intranet.session_exp'));
            Session::put('user_data', $result[0], config('intranet.session_exp'));
            Session::put('user_id', $result[0]->EMPLOYEE_ID, config('intranet.session_exp'));
            Session::put('assignment',$assignment_data, config('intranet.session_exp'));
            Session::put('username', $request->username, config('intranet.session_exp'));
            if(!session()->has('url.intended'))
            {
                 $url_previous = url()->previous();
            }
            // Call service provider for access menu and permission
            try {
              app()->get('user_login');
            } catch(\Exception $e){}
            return response()->json(array('success' => $success, 'msg' => $msg, 'data' => $data, 'previous'=>$url_previous), 200);
          } else {
            return response()->json(array('success' => $success, 'msg' => $msg, 'data' => $data, 'previous'=>$url_previous), 200);
          }

        } catch (\Exception $e) {
          $result['log']['file'] = $e->getFile();
          $result['log']['message'] = $e->getMessage();
          $result['log']['line'] = $e->getLine();

          $result['code'] = '500';
          $result['status'] = 'failed';
          $result['message'] = sprintf('%s', $e->getMessage());

          //bikin log
          Log::error(response()->json($result));
          return $result;
        } catch (\Illuminate\Database\QueryException $e) {
          //echo "ha2";
          $result['log']['file'] = $e->getFile();
          $result['log']['message'] = $e->getMessage();
          $result['log']['line'] = $e->getLine();

          $result['code'] = '502';
          $result['status'] = 'failed';
          $result['message'] = sprintf('Database Error, %s', $e->getMessage());

          //bikin log
          Log::error(response()->json($result));
          return $result;
        }


    }

    public function login_old(Request $request)
    {

        try{
          $userModel= new UserModel();
          $employeeModel= new employeeModel();
          $username=$request->username;
          $password=$request->password;
          $expiry_status=array();

          $result=$userModel->getUserByEmail($username);

          $count=count($result);
          if($count>0){
            if (Hash::check($password, $result[0]->PASSWORD)) {
                // The passwords match...

                $param=array(
                  'EMPLOYEE_ID'=>$result[0]->EMPLOYEE_ID
                );
                $assignment_data=$employeeModel->employeeSearch($param);


                $expired_date=$result[0]->DATE_EXPIRED_PASSWORD;
                $do_expire=$result[0]->IS_EXPIRED;
                $today=date('Y-m-d');
                $expiry_treshold=30;

                //kalkulasi berapa hari lagi expired
                $date1=new DateTime($today);
                $date2=new DateTime($expired_date);
                $different= $date1->diff($date2);

                $datetoexpire=$different->days;

                //trap kalau lewat today sama expired date
                if($date1>$date2){
                  $datetoexpire=0;
                }

                $warning_expired=$warning_expired_treshold=false; //set warning expired default false
                $expiry_status['do_expire']=$do_expire;
                $expiry_status['expirydate']=$expired_date;
                $expiry_status['datetoexpire']=$datetoexpire;


                $success=true;
                $msg="Login success";
                $data['code']='200';

                //trap expiration date
                if($do_expire=='Y'){
                    if($datetoexpire<1){

                        $success=false;
                        $msg="Your account password has been expired, you have to change your password now";
                        $data['code']='405';
                    }else if($datetoexpire>1 && $datetoexpire<=$expiry_treshold){

                        $warning_expired_treshold=true;
                        $msg="Your account will expired in $datetoexpire day(s), consider to change your password.";
                    }
                }

                $expiry_status['warning_expired_treshold']=$warning_expired_treshold;

            }else{
                $success=false;
                $msg="The username or password is incorrect";
                $data['code']='401';
            }
          }else{
              $success=false;
              $msg="The username or password is incorrect";
              $data['code']='401';
          }


          $data['expiry_status']=$expiry_status;
          $url_previous = null;

          if ($success || $success == "1") {

            Session::put('logged_in', 1, config('intranet.session_exp'));
            Session::put('user_data', $result[0], config('intranet.session_exp'));
            Session::put('user_id', $result[0]->EMPLOYEE_ID, config('intranet.session_exp'));
            Session::put('assignment',$assignment_data, config('intranet.session_exp'));

            Session::put('username', $request->username, config('intranet.session_exp'));
            if(!session()->has('url.intended'))
            {
                 $url_previous = url()->previous();
            }

            return response()->json(array('success' => $success, 'msg' => $msg, 'data' => $data, 'previous'=>$url_previous), 200);
          } else {
            return response()->json(array('success' => $success, 'msg' => $msg, 'data' => $data, 'previous'=>$url_previous), 200);
          }

        } catch (\Exception $e) {
          $result['log']['file'] = $e->getFile();
          $result['log']['message'] = $e->getMessage();
          $result['log']['line'] = $e->getLine();

          $result['code'] = '500';
          $result['status'] = 'failed';
          $result['message'] = 'Internal Server Error';

          //bikin log
          Log::error(response()->json($result));
          return $result;
        } catch (\Illuminate\Database\QueryException $e) {
          //echo "ha2";
          $result['log']['file'] = $e->getFile();
          $result['log']['message'] = $e->getMessage();
          $result['log']['line'] = $e->getLine();

          $result['code'] = '502';
          $result['status'] = 'failed';
          $result['message'] = 'Database Error';

          //bikin log
          Log::error(response()->json($result));
          return $result;
        }


    }

    /**
     * remove all cache or sessions
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        try {
            // $token = Session::get('api_token');
            // $data = [
            //     'web' => 'primamedix',
            // ];
            // Http::withHeaders(config('intranet.headers'))
            //     ->post(config('intranet.api_portal_url') . '/v2/logout', $data);

            // Cache::forget(config('intranet.token_prefix').$token);
            // Cache::forget('api_token', config('intranet.token_prefix') . $token);
            // Session::forget('api_token');
            $request->session()->flush();
            \Artisan::call('cache:clear');
            return redirect('/');
        } catch (\Exception $e) {
            // $request->session()->flush();
            return redirect('/');
        }
    }

    public function forgot_password(Request $request)
    {
        if($request->method() == 'POST'){
          $user_login = $request->session()->get('user_data');
          $new_password=$request->post('new_password');
          $confirm_password=$request->post('confirm_new_password');

          if($new_password!==$confirm_password){
            $success=false;
            $msg="New password does not match with confirm password!";
            $code='406';
          }else{
            $userModel= new UserModel();
            $username=$request->post('email');
            $data=$userModel->getUserByWorkEmail($username);
            if(count($data)>0){
              // Jika work email ditemukan lebih dari 1
              if(count($data) > 1){
                $success=false;
                $msg="Duplicate work email, please check the data and make sure work email is unique";
                $code='400';
              } else{
                if(isset($data[0]->IS_SUPERUSER) && $data[0]->IS_SUPERUSER && isset($user_login->IS_SUPERUSER) && !$user_login->IS_SUPERUSER){
                  return response()->json(array('success' => false, 'msg' => 'Forbidden to change password of highest system level', 'code' => '400', 200));
                } else if(isset($user_login->WORK_EMAIL) && strtolower($user_login->WORK_EMAIL) == strtolower($username)){
                  return response()->json(array('success' => false, 'msg' => 'Cannot change password of your own, please go to your profie and then change password', 'code' => '400', 200));
                }


                $pwd_update=Hash::make($new_password);
                $result=$userModel->updateUserPasswordByWorkEmail($username,$pwd_update);
                if($result){
                  //set expiration date
                  $today=date('Y-m-d H:i:s');
                  $expired=date('Y-m-d H:i:s', strtotime($today. ' + 90 days'));
                  $userModel->setExpirationDateByWorkEmail($username,$expired);
                  try{
                    $success=true;
                    $msg="Password has been successfully changed.";
                    $code='200';
                  } catch(\Exception $e){
                    Log::error("FORGOT PASSWORD ERRROR | ".$e->getMessage());
                    $success=false;
                    $msg="Something's not right, please try again later";
                    $code='400';
                  }
                }else{
                  $success=false;
                  $msg="Something's not right, please try again later";
                  $code='400';
                }
              }
            } else{
              $success=false;
              $msg="User email is not found, please try again";
              $code='400';
            }
          }
          return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));
        }
        else
          return view('pages.auth.forgot_password');
    }

    public function act_change_password(Request $request){
        $userModel= new UserModel();

        $email=$request->email;
        $old_password=$request->password;
        $new_password=$request->new_password;
        $confirm_password=$request->confirm_new_password;

        if($new_password!==$confirm_password){
          $success=false;
          $msg="New password does not match!";
          $code='406';
        }else{

           $data=$userModel->getUserByWorkEmail($email);
           if($data){

                $success=false;
                $msg="Your email / password is incorrect!";
                $code='400';

               if(!empty($data[0])){
                    if (Hash::check($old_password, $data[0]->PASSWORD)) {
                        // The passwords match...
                        //trap jika password lama sama dengan password baru
                        if($old_password==$new_password){
                            $success=false;
                            $msg="Your new password cannot be the same as your current password. Please use another.";
                            $code='400';
                        }else{
                            $pwd_update=Hash::make($new_password);
                            $result=$userModel->updateUserPasswordByWorkEmail($email,$pwd_update);
                            if($result){
                                //set expiration date
                                $today=date('Y-m-d H:i:s');
                                $expired=date('Y-m-d H:i:s', strtotime($today. ' + 90 days'));

                                $userModel->setExpirationDateByWorkEmail($email,$expired);

                                $success=true;
                                $msg="Your password has been changed. Your password will be expired at ".date('d F Y',strtotime($expired));
                                $code='200';
                            }else{
                                $success=false;
                                $msg="Something's not right, please try again later";
                                $code='400';
                            }
                        }
                    }
                }
            }else{
                $success=false;
                $msg="Your email/password is incorrect!";
                $code='400';
            }
        }


        return response()->json(array('success' => $success, 'msg' => $msg, 'code' => $code, 200));

    }
}
