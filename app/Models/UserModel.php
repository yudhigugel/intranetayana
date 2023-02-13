<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class UserModel extends Model
{

    public function getUserByAssignmentEmail($email)
    {
        try {
            // DB::enableQueryLog();
            $data = DB::connection('dbintranet')
                    ->select(
                        'VIEW_EMPLOYEE_ACCESS.*'
                    )
                    ->where('VIEW_EMPLOYEE_ACCESS.EMAIL',$email)
                    ->get();
            // dd(DB::getQueryLog());
            return $data;

        }
        catch (\Exception $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5001';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5002';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        }
    }

    public function getUserByEmail($email)
    {
        try {
            // DB::enableQueryLog();
            $data = DB::connection('dbintranet')
                    ->table('INT_EMPLOYEE')
                    ->select(
                        'INT_EMPLOYEE.*'
                    )
                    ->where('INT_EMPLOYEE.EMAIL',$email)
                    ->get();
            // dd(DB::getQueryLog());
            return $data;

        }
        catch (\Exception $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5001';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5002';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        }
    }

    public function getUserByWorkEmail($email)
    {
        try {            
            // DB::enableQueryLog();
            $data = DB::connection('dbintranet')
                    ->table('VIEW_EMPLOYEE_ACCESS')
                    ->select(
                        'VIEW_EMPLOYEE_ACCESS.*'
                    )
                    ->where(['VIEW_EMPLOYEE_ACCESS.WORK_EMAIL'=>$email])
                    ->get()->toArray();
            return $data;

        }
        catch (\Exception $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5001';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';
            
            //bikin log
            Log::error(response()->json($msg));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5002';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';
            
            //bikin log
            Log::error(response()->json($msg));
            return $result;
        }
    }

    public function updateUserPasswordByEmail($email,$password)
    {
        try {
            $data = DB::connection('dbintranet')
                    ->table('INT_EMPLOYEE')
                    ->where('EMAIL',$email)
                    ->update(['PASSWORD'=>$password]);

            return $data;

        }
        catch (\Exception $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5001';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5002';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        }
    }

    public function updateUserPasswordByWorkEmail($email,$password)
    {
        try {
            $data = DB::connection('dbintranet')
                    ->table('INT_EMPLOYEE')
                    ->where('WORK_EMAIL',$email)
                    ->update(['PASSWORD'=>$password]);

            return $data;

        }
        catch (\Exception $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5001';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5002';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        }
    }

    public function setExpirationDateByEmail($email,$expired)
    {
        try {
            $data = DB::connection('dbintranet')
                    ->table('INT_EMPLOYEE')
                    ->where('EMAIL',$email)
                    ->update(['DATE_EXPIRED_PASSWORD'=>$expired]);

            return $data;

        }
        catch (\Exception $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5001';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5002';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        }
    }

    public function setExpirationDateByWorkEmail($email,$expired)
    {
        try {
            $data = DB::connection('dbintranet')
                    ->table('INT_EMPLOYEE')
                    ->where('WORK_EMAIL',$email)
                    ->update(['DATE_EXPIRED_PASSWORD'=>$expired]);

            return $data;

        }
        catch (\Exception $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5001';
            $result['status'] = 'failed';
            $result['message'] = 'Internal Server Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        } catch (\Illuminate\Database\QueryException $e) {
            $msg['file'] = $e->getFile();
            $msg['message'] = $e->getMessage();
            $msg['line'] = $e->getLine();

            $result['code'] = '-5002';
            $result['status'] = 'failed';
            $result['message'] = 'Database Error';

            //bikin log
            Log::error(response()->json($msg));
            return $result;
        }
    }
}
