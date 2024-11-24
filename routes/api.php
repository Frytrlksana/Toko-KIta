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
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->group(function () {
    // Rute Umum
    Route::get('logout', [AuthController::class, 'logout']);


    // Rute untuk Admin
    Route::middleware(['auth:sanctum', 'auth.user'])->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('index', [ProductsController::class, 'index']);
            Route::post('addProduct', [ProductsController::class, 'addProduct']);
            Route::post('products', [ProductsController::class, 'store']);
            Route::get('products/{id}/edit', [ProductsController::class, 'edit']);
            Route::put('products/{id}', [ProductsController::class, 'update']);
            Route::delete('products/{id}', [ProductsController::class, 'destroy']);
        });
    });

    // Rute untuk User
    Route::middleware('role:user')->group(function () {

    });
});