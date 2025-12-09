<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    //product
    Route::get('products', [\App\Http\Controllers\Api\v1\ProductController::class, 'index']);
    Route::get('products/{product}', [\App\Http\Controllers\Api\v1\ProductController::class, 'show']);

    //category
    Route::get('categories', [\App\Http\Controllers\Api\v1\CategoryController::class, 'index']);
    Route::get('categories/{category}', [\App\Http\Controllers\Api\v1\CategoryController::class, 'show']);

    //sub-category
    Route::get('sub-categories', [\App\Http\Controllers\Api\v1\SubCategoryController::class, 'index']);
    Route::get('sub-categories/{subCategory}', [\App\Http\Controllers\Api\v1\SubCategoryController::class, 'show']);
    
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('verify-token', [AuthController::class, 'verifyToken'])->name('verify-token');
        Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('refresh-token');

        //wishlist
        Route::apiResource('wishlists', \App\Http\Controllers\Api\v1\WishlistController::class);
        Route::post('wishlists/destroy-all', [\App\Http\Controllers\Api\v1\WishlistController::class, 'destroyAll']);
        Route::post('wishlist-exists/{productId}', [\App\Http\Controllers\Api\v1\WishlistController::class, 'checkIfWishlistExists']);

        //cart
        Route::apiResource('carts', \App\Http\Controllers\Api\v1\CartController::class);
        Route::post('carts/destroy-all', [\App\Http\Controllers\Api\v1\CartController::class, 'destroyAll']);
        Route::post('carts/increment/{cart}', [\App\Http\Controllers\Api\v1\CartController::class, 'increment']);
        Route::post('carts/decrement/{cart}', [\App\Http\Controllers\Api\v1\CartController::class, 'decrement']);

        //Profile
        Route::get('profile', [\App\Http\Controllers\Api\v1\ProfileController::class, 'show']);
        Route::post('profile', [\App\Http\Controllers\Api\v1\ProfileController::class, 'update']);

        //Order
        Route::get('orders', [\App\Http\Controllers\Api\v1\OrderController::class, 'index']);
        Route::post('orders', [\App\Http\Controllers\Api\v1\OrderController::class, 'store']);

        //Order Stripe Intent Payment Confirmation
        Route::post('orders/confirm-stripe-intent-payment', [\App\Http\Controllers\Api\v1\OrderController::class, 'confirmStripeIntentPayment']);

        // Admin routes
        Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
            Route::apiResource('categories', \App\Http\Controllers\Api\v1\CategoryController::class);
            Route::apiResource('sub-categories', \App\Http\Controllers\Api\v1\SubCategoryController::class);
            Route::apiResource('products', \App\Http\Controllers\Api\v1\ProductController::class);
            Route::post('products/{product}', [\App\Http\Controllers\Api\v1\ProductController::class, 'update']);
            Route::post('delete-product-image', [\App\Http\Controllers\Api\v1\ProductController::class, 'deleteImage']);
            Route::apiResource('orders', \App\Http\Controllers\Api\v1\OrderController::class);
            Route::post('update-order-status/{id}', [\App\Http\Controllers\Api\v1\OrderController::class, 'update']);
        });
    });
});
