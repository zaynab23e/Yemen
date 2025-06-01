<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\PaymentConrtoller;




Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/all-cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::post('/cart/{item_id}', [CartController::class, 'update']);
    Route::delete('/cart/{item_id}', [CartController::class, 'destroy']);
    // Route::delete('/cart', [CartController::class, 'clearCart']);

Route::post('/payment/process', [PaymentConrtoller::class, 'paymentProcess']);
Route::match(['GET','POST'],'/payment/callback', [PaymentConrtoller::class, 'callBack']);

});
