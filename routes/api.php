<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Products\GetProductsController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/signup', SignupController::class);
Route::post('/auth/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', GetProductsController::class);
});
