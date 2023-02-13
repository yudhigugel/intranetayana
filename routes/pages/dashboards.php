<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboards\Warehouse\OrderController;
use App\Http\Controllers\Dashboards\Warehouse\StockController;

Route::group(['prefix' => 'warehouse'], function () {
    /**
     * Order Route dashboards
     */
    Route::get('orders/{plan_code}', [OrderController::class,'index']);

    Route::get('orders', [OrderController::class,'all']);

    Route::get('stocks', [StockController::class,'index']);
});
