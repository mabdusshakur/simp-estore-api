<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::apiResource('wishlists', \App\Http\Controllers\Api\v1\WishlistController::class);
        Route::post('wishlists/destroy-all', [\App\Http\Controllers\Api\v1\WishlistController::class, 'destroyAll']);

        Route::apiResource('carts', \App\Http\Controllers\Api\v1\CartController::class);
        Route::post('carts/destroy-all', [\App\Http\Controllers\Api\v1\CartController::class, 'destroyAll']);

        Route::post('carts/increment/{cart}', [\App\Http\Controllers\Api\v1\CartController::class, 'increment']);
        Route::post('carts/decrement/{cart}', [\App\Http\Controllers\Api\v1\CartController::class, 'decrement']);

        // Admin routes
        Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
            Route::apiResource('categories', \App\Http\Controllers\Api\v1\CategoryController::class);
            Route::apiResource('sub-categories', \App\Http\Controllers\Api\v1\SubCategoryController::class);
            Route::apiResource('products', \App\Http\Controllers\Api\v1\ProductController::class);
        });
    });
});
