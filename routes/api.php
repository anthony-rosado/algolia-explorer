<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Categories\CreateCategoryController;
use App\Http\Controllers\Categories\DeleteCategoryController;
use App\Http\Controllers\Categories\GetCategoriesController;
use App\Http\Controllers\Categories\GetChildrenOfCategoryController;
use App\Http\Controllers\Categories\UpdateCategoryController;
use App\Http\Controllers\Products\CreateProductController;
use App\Http\Controllers\Products\DeleteProductController;
use App\Http\Controllers\Products\GetProductsController;
use App\Http\Controllers\Products\UpdateProductController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/signup', SignupController::class);
Route::post('/auth/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/products')->group(function () {
        Route::get('/', GetProductsController::class);
        Route::post('/', CreateProductController::class);
        Route::put('/{product}', UpdateProductController::class);
        Route::delete('/{product}', DeleteProductController::class);
    });

    Route::prefix('/categories')->group(function () {
        Route::get('/', GetCategoriesController::class);
        Route::post('/', CreateCategoryController::class);

        Route::prefix('/{category}')->group(function () {
            Route::put('/', UpdateCategoryController::class);
            Route::delete('/', DeleteCategoryController::class);
            Route::get('/children', GetChildrenOfCategoryController::class);
        });
    });
});
