<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ServiceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/provinces', [ProvinceController::class, 'index']); 

Route::get('/cities/{province_id}', [CityController::class, 'getCities']);

Route::post('/service/create', [ServiceController::class, 'store'])->middleware('auth:sanctum');
Route::post('/product/create', [ProductController::class, 'store'])->middleware('auth:sanctum');
Route::get('/products', [ProductController::class, 'index']);
Route::get('/myProducts', [ProductController::class, 'myProducts'])->middleware('auth:sanctum');
Route::get('/products/{product:slug}', [ProductController::class, 'show']);
Route::post('/product/update/{product:slug}', [ProductController::class, 'update'])->middleware('auth:sanctum');

Route::get('categories', [CategoryController::class, 'indexCategory']);

Route::post('/password/request-otp', [ForgotPasswordController::class, 'requestOtp']);

Route::post('/password/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);

Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword']);

Route::post('/verify-login-otp', [AuthController::class, 'verifyLoginOtp']);

Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware('auth:sanctum');