<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::prefix('api/v1/')
        ->group(function () {
            Route::get('/user', function (Request $request) {
                return $request->user();
            });
        });
});

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::prefix('api/v1')
        ->namespace("CrmSell\Users\UI\Http\Controllers")
        ->group(function () {
            Route::post('/user/add', 'UsersController@addUser');
            Route::get('/user', 'UsersController@getUser');
        });
});
