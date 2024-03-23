<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/signup', SignupController::class);
Route::post('/auth/login', LoginController::class);
