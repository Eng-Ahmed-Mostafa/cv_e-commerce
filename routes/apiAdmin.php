<?php

use App\Http\Controllers\Api\Admin\CouponController;
use App\Http\Controllers\Api\Admin\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\CategoryController;


Route::prefix('admin')->middleware(['role:admin,api'])->group(function() {
    Route::apiResource('brands',BrandController::class);
    Route::apiResource('categories',CategoryController::class);
    Route::apiResource('products',ProductController::class);
    Route::apiResource('coupons',CouponController::class);
    Route::apiResource('orders',OrderController::class);
});
