<?php

use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\GreetingController;
use App\Http\Controllers\Api\ApiWarehouseController;
use App\Http\Controllers\Api\ApiRateController;
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\FrontController;

// Route::post('/token-generate', [ApiLoginController::class, 'shoplinelogin']);
// Route::get('/greeting', [GreetingController::class, 'showGreeting']);
// Route::post('/rate/calculate',[ApiRateController::class,'calculate']);
// Route::post('/active/courier',[ApiRateController::class,'activecourier']);
// Route::post('/create/order',[ApiOrderController::class,'createorder']);
// Route::post('/manifest/order',[ApiOrderController::class,'manifestorder']);
// Route::get('/generatelabel/{id}',[ApiOrderController::class,'generatelabel']);
// Route::post('/view/order',[ApiOrderController::class,'view_order']);
// Route::post('/update/order/{id}',[ApiOrderController::class,'updateorder']);
// Route::delete('/delete/order/{id}',[ApiOrderController::class,'deleteorder']);
// Route::post('/cancel/order/{id}',[ApiOrderController::class,'cancelorder']);
// Route::get('/track/order/{id}',[ApiOrderController::class,'trackorder']);
// Route::post('/create/orderawb',[ApiOrderController::class,'orderawb']);
// Route::post('/warehouse',[ApiWarehouseController::class,'warehouse_save']);
// Route::delete('/warehouse/delete/{id}',[ApiWarehouseController::class,'deleteWarehouse']);
Route::post('/addquery', [FrontController::class, 'addquery']);
Route::post('/addvisitedguest', [FrontController::class, 'addvisitedguest']);
Route::post('/calculaterate', [FrontController::class, 'calculaterate']);
//warehouse
Route::post('/addWarehouse', [FrontController::class, 'addWarehouse']);
Route::get('/getWarehouse', [FrontController::class, 'getWarehouse']);
Route::post('/delete-warehouse/{id}', [FrontController::class, 'deleteWarehouse']);
//order
Route::post('/bulk-order-upload', [FrontController::class, 'bulkOrderApi']);
Route::get('/get-order', [FrontController::class, 'getorders']);
Route::post('/delete-order/{id}', [FrontController::class, 'deleteOrder']);

Route::post('/razorpay/webhook', [App\Http\Controllers\Admin\PaymentController::class, 'razorpayWebhook']);

Route::get('/msg', function () {
    return 'Hello World';
});
