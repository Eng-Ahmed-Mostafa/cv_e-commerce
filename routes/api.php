<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;



Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::middleware(['JwtMiddleware'])->group(function () {
    Route::get('user', [JWTAuthController::class, 'getUser']);
    Route::post('logout', [JWTAuthController::class, 'logout']);
    require __DIR__.'/apiAdmin.php';
    require __DIR__.'/apiUser.php';
});

