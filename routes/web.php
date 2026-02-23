<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MagentoOrderController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/magento/orders', [MagentoOrderController::class, 'getOrdersWithView']);