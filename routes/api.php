<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductsController;

/*
|-------------------------------------------------------------------------- 
| API Routes
|-------------------------------------------------------------------------- 
| 
| Here is where you can register API routes for your application. These 
| routes are loaded by the RouteServiceProvider within a group which 
| is assigned the "api" middleware group. Enjoy building your API! 
|
*/

// Rute Registrasi dan Verifikasi OTP
Route::post('register', [AuthController::class, 'register']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

// Rute Admin
Route::prefix('admin')->group(function () {
    // Login dan Logout Admin
    Route::post('login', [AuthController::class, 'adminLogin']);
    Route::post('logout', [AuthController::class, 'adminLogout'])->middleware('auth:sanctum');

    // Menggunakan middleware 'auth.user' untuk memastikan hanya admin yang bisa mengakses
    Route::middleware(['auth:sanctum', 'auth.user'])->group(function () {
        // Admin Routes
        Route::post('addProduct', [ProductsController::class, 'addProduct']);
        Route::get('getProduct', [ProductsController::class, 'getProduct']);
        Route::get('detailProduct/{id}', [ProductsController::class, 'detailProduct']);
        Route::post('updateProduct/{id}', [ProductsController::class, 'updateProduct']);
        Route::post('deleteProduct/{id}', [ProductsController::class, 'deleteProduct']);
    });
});

// Rute User
Route::prefix('user')->group(function () {
    // Login dan Logout User
    Route::post('login', [AuthController::class, 'userLogin']);
    Route::post('logout', [AuthController::class, 'userLogout'])->middleware('auth:sanctum');  // Menggunakan middleware auth:sanctum

    // Menggunakan middleware 'auth:sanctum' untuk memastikan hanya pengguna yang terautentikasi yang bisa mengakses
    Route::middleware(['auth:sanctum'])->group(function () {
        // Rute Product untuk User
        Route::get('getProduct', [ProductsController::class, 'getProduct']);
        Route::get('detailProduct/{id}', [ProductsController::class, 'detailProduct']);
    });
});
