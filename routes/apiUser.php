<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\User\OrderController;


Route::apiResource('cart',CartController::class)->except('show');

Route::apiResource('orders',OrderController::class);
Route::get('order/user/last', [OrderController::class, 'lastOrder'])->name('orders.user.last');
Route::get('order/{order}/pay', [OrderController::class, 'pay']);
Route::post('paymob/callback', [OrderController::class, 'paymentCallback']);
