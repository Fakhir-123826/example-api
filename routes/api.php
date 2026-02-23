<?php
// routes/api.php

use App\Http\Controllers\Api\MagentoOrderController;

Route::get('/magento/orders', [MagentoOrderController::class, 'getOrders']);
// Route::get('/magento/orders/{id}', [MagentoOrderController::class, 'getOrder']);