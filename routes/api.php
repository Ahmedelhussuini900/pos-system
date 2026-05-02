<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// ─── Auth (بدون token) ───
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/pin', [AuthController::class, 'loginByPin']);

// ─── محمي بـ Token ───
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::apiResource('categories', \App\Http\Controllers\Api\CategoryController::class);
Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);