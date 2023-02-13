<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;

class TestController extends Controller
{
    public function test(Request $request) 
    {
    	$model = new Test();
        $data = $model->test($request);
        return response()->json($data);
    }
}