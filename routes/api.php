<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\UserController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->group(function () {
    // Rute Umum
    Route::get('getProduct', [ProductsController::class, 'getProduct']);
    Route::get('detailProduct/{id}', [ProductsController::class, 'detailProduct']);


    // Rute untuk Admin
    Route::middleware(['auth:sanctum', 'auth.user'])->group(function () {
        Route::prefix('admin')->group(function () {
            //auth admin
            Route::post('login', [AuthController::class, 'login']);
            Route::get('logout', [AuthController::class, 'logout']);

            //product admin
            Route::post('addProduct', [ProductsController::class, 'addProduct']);
            Route::get('getProduct', [ProductsController::class, 'getProduct']);
            Route::get('detailProduct/{id}', [ProductsController::class, 'detailProduct']);
            Route::post('updateProduct/{id}', [ProductsController::class, 'updateProduct']);
            Route::post('deleteProduct/{id}', [ProductsController::class, 'deleteProduct']);
        });
    });

    // Rute untuk User
    Route::middleware('role:user')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout']);

        Route::get('getProduct', [ProductsController::class, 'getProduct']);
        Route::get('detailProduct/{id}', [ProductsController::class, 'detailProduct']);

    });
});