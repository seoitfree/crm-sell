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
