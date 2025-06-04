<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\PaymentConrtoller;
use App\Http\Controllers\user\OrderController;
use App\Http\Controllers\user\PaymentController;




Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/all-cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::post('/cart/{item_id}', [CartController::class, 'update']);
    Route::delete('/cart/{item_id}', [CartController::class, 'destroy']);
    // Route::delete('/cart', [CartController::class, 'clearCart']);
Route::post('/{user}/Orders/', [OrderController::class, 'placeOrder']);
    Route::get('/Orders/', [OrderController::class, 'index']);

Route::post('/{user}/payment/process', [PaymentController::class, 'paymentProcess']);
Route::match(['GET','POST'],'/payment/callback', [PaymentController::class, 'callBack']);

});
