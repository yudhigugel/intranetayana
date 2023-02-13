<?php

namespace App\Http\Controllers\Dashboards\Warehouse;

use Illuminate\Http\Request;

class StockController
{

    /**
     * oder dahboard order
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        return view('pages.dashboards.warehouse.stocks.index');
    }
}
