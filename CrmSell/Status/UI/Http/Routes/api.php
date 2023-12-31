<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::prefix('api/v1')
        ->namespace("CrmSell\Status\UI\Http\Controllers")
        ->group(function () {
            Route::get('/status', 'StatusController@getList');
            Route::get('/status/{id}', 'StatusController@getStatusById')->whereUuid('id');
            Route::post('/status', 'StatusController@create');
            Route::put('/status', 'StatusController@edit');
        });
});
