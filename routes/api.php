<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\CategoryController;

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


    Route::middleware(['auth:sanctum', 'auth.user'])->group(function () {
        // Category Admin Access
        Route::prefix('categories')->group(function () {
            Route::get('getCategory', [CategoryController::class, 'getCategory']);
            Route::get('detailCategory/{id}', [CategoryController::class, 'detailCategory']);
            Route::post('addCategory', [CategoryController::class, 'addCategory']);
            Route::post('deleteCategory/{id}', [CategoryController::class, 'deleteCategory']);
        });

        // Products Admin Access
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
    Route::post('logout', [AuthController::class, 'userLogout'])->middleware('auth:sanctum');


    Route::middleware(['auth:sanctum'])->group(function () {
        // Category User Access
        Route::prefix('categories')->group(function () {
            Route::get('getCategory', [CategoryController::class, 'getCategory']);
            Route::get('detailCategory/{id}', [CategoryController::class, 'detailCategory']);
        });

        // Products User Access
        Route::get('getProduct', [ProductsController::class, 'getProduct']);
        Route::get('detailProduct/{id}', [ProductsController::class, 'detailProduct']);
    });
});
