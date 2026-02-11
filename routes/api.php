<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductVariantController;
use App\Http\Controllers\Api\V1\RefundController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Auth
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        // Auth
        
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);

        // Product
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{product}', [ProductController::class, 'show']);

        // ProductVariant
        Route::get('products/{product}/variants', [ProductVariantController::class, 'index']);
        Route::get('products/{product}/variants/{productVariant}', [ProductVariantController::class, 'show']);

        // Category
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{category}', [CategoryController::class, 'show']);

        // Brand
        Route::get('brands', [BrandController::class, 'index']);
        Route::get('brands/{brand}', [BrandController::class, 'show']);

        // Refund
        Route::get('refunds', [RefundController::class, 'index']);
        Route::get('refunds/{refund}', [RefundController::class, 'show']);
    });

    Route::middleware(['auth:api', 'role:admin|seller'])->group(function () {
        // Product
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);

        // ProductVariant
        Route::post('products/{product}/variants', [ProductVariantController::class, 'store']);
        Route::put('products/{product}/variants/{productVariant}', [ProductVariantController::class, 'update']);
        Route::delete('products/{product}/variants/{productVariant}', [ProductVariantController::class, 'destroy']);

        // Category
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{category}', [CategoryController::class, 'update']);
        Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

        // Brand
        Route::post('brands', [BrandController::class, 'store']);
        Route::put('brands/{brand}', [BrandController::class, 'update']);
        Route::delete('brands/{brand}', [BrandController::class, 'destroy']);

        // Refund
        Route::post('refunds', [RefundController::class, 'store']);
        Route::put('refunds/{refund}', [RefundController::class, 'update']);
        Route::delete('refunds/{refund}', [RefundController::class, 'destroy']);
    });
});
