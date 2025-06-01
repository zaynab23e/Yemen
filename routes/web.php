<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\PaymentConrtoller;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/payment-success', [PaymentConrtoller::class, 'success'])->name('payment.success');
Route::get('/payment-failed', [PaymentConrtoller::class, 'failed'])->name('payment.failed');