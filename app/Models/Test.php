<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Validator;

class Test extends Model
{
    public function test($request)
    {
        try {            
            $data = DB::connection('operadbms')
                    ->table('VW_REVENUE_DASHBOARD')
                    ->whereIn('year', [2020, 2021])
                    ->whereNotIn('room_name', ['MIG - Migration'])
                    ->orderBy('resort_name')
                    ->orderBy('room_name')
                    ->get();
            
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