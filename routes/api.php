<?php

use App\Http\Controllers\Admin\AdminsAuthController;
use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\Admin\MenusCategoryController;
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
    Route::apiResource('/menuCategories', MenusCategoryController::class);
    Route::apiResource('/items', ItemsController::class);
    Route::post('items/{id}/', [ItemsController::class, 'updateItem'])->name('items.uploadImage');
    Route::post('/logout', [AdminsAuthController::class, 'logout'])->name('admins.logout');
});
