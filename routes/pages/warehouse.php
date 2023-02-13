<?php

use Illuminate\Support\Facades\Route;

Route::view('/orders','pages.warehouse.orders.index')->middleware('auth.api');