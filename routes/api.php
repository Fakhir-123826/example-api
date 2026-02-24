<?php
// routes/api.php

use App\Http\Controllers\Api\MagentoOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/magento/orders', [MagentoOrderController::class, 'getOrders']);
Route::get('/magento/getProductsFromApi', [MagentoOrderController::class, 'getProductsFromApi']);
Route::get('/magento/getOrdersFormApi', [MagentoOrderController::class, 'getOrdersFormApi']);
Route::get('/magento/products', [MagentoOrderController::class, 'getProducts']);




// Route::get('/magento/orders/{id}', [MagentoOrderController::class, 'getOrder']);