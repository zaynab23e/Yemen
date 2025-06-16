<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminsAuthController;
use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\Admin\MenusCategoryController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\UsersAuthController;
use App\Http\Controllers\user\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminsAuthController::class, 'register'])->name('admins.register');
    Route::post('/login', [AdminsAuthController::class, 'login'])->name('admins.login');

});
Route::middleware('admin')->prefix('admin')->group(function () {
    // Routes for MenuCategories
    Route::get('/menuCategories', [MenusCategoryController::class, 'index']);
    Route::post('/menuCategories', [MenusCategoryController::class, 'store']);
    Route::get('/menuCategories/{menuCategory}', [MenusCategoryController::class, 'show']);
    Route::post('/menuCategories/{menuCategory}', [MenusCategoryController::class, 'update']);
    Route::delete('/menuCategories/{menuCategory}', [MenusCategoryController::class, 'destroy']);

    // Routes for Items
    Route::get('/items', [ItemsController::class, 'index']);
    Route::post('/items', [ItemsController::class, 'store']);
    Route::get('/items/{item}', [ItemsController::class, 'show']);
    Route::post('/items/{item}', [ItemsController::class, 'update']);
    Route::delete('/items/{item}', [ItemsController::class, 'destroy']);
    
    Route::post('items/{id}/', [ItemsController::class, 'updateItem'])->name('items.uploadImage');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('dashboard.orders');

    Route::post('/logout', [AdminsAuthController::class, 'logout'])->name('admins.logout');
});
Route::prefix('user')->group(function () {
    Route::post('/register', [UsersAuthController::class, 'register'])->name('users.register');
    Route::post('/login', [UsersAuthController::class, 'login'])->name('users.login');

});
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/all-cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::post('/cart/{item_id}', [CartController::class, 'update']);
    Route::delete('/cart/{item_id}', [CartController::class, 'destroy']);
    // Route::delete('/cart', [CartController::class, 'clearCart']);
    Route::apiResource('/menuCategories', MenusCategoryController::class)->only(['index', 'show']);

    Route::apiResource('/items', ItemsController::class)->only(['index', 'show']);

    Route::post('/Orders/', [OrderController::class, 'placeOrder']);
    Route::get('/Orders/', [OrderController::class, 'index']);
    
    Route::post('/logout', [UsersAuthController::class, 'logout'])->name('users.logout');
});
