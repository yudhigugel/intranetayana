<?php

namespace App\Http\Controllers\Dashboards\Warehouse;

use Illuminate\Http\Request;

class OrderController
{

    /**
     * oder dahboard order
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request, $plan_code)
    {
        return view('pages.dashboards.warehouse.orders.index', ['plan_code' => $plan_code]);
    }

    /**
     * all order dashboards
     *
     * @param Request $request
     * @return void
     */
    public function all(Request $request){
        return view('pages.dashboards.warehouse.orders.index', ['plan_code' => 'all']);
    }
}
