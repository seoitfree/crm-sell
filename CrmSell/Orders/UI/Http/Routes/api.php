<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::prefix('api/v1')
        ->namespace("CrmSell\Orders\UI\Http\Controllers")
        ->group(function () {
            Route::get('/orders', 'OrdersController@getOrders');
            Route::post('/order', 'OrdersController@create');
            Route::put('/status', 'OrdersController@edit');
            Route::post('/shipment', 'OrdersController@addShipment');
            Route::get('/shipments-history', 'OrdersController@shipmentsHistory');
        });
});