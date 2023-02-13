<?php

namespace App\Http\Controllers\Dashboards\Menu_Outlet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use DataTables;
Use Cookie;

class Menu_Outlet extends Controller
{
    public function index(){
    	$result = DB::connection('dbopera')
                ->table('TB_EXAMPLE_ROCKBAR_MENU')
                ->get();

        $data['status'] = 'success';
        $data['count_data']  = $result->count();
        $data['result'] = $result;
        return response()->json($data, 200);
    }
}
