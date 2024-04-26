<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Api\Auth\UserAuthApiController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Item\GetCategoryController;
use App\Http\Controllers\Api\Item\GetVendorController;
use App\Http\Controllers\Api\Item\ItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\PaymentController;

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


Route::get('/get-categories',               GetCategoryController::class);
Route::get('/get-vendors',                  GetVendorController::class);

Route::apiResource('/users',                UserController::class);


Route::get('/dashboard',                    [DashboardController::class, 'index']);

Route::get('/cart',                         [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{id}',                   [CartController::class, 'store'])->name('cart.addToCart');
Route::delete('/cart/{id}',                 [CartController::class, 'destroy']);
Route::post('/cart/increase-quantity',      [CartController::class, 'increaseItemQuantity'])->name('cart.increase');
Route::post('/cart/decrease-quantity',      [CartController::class, 'decreaseItemQuantity'])->name('cart.decrease');


Route::group(['middleware' => 'LocalizationMiddleware'], function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('/categories',       CategoryController::class)->only(['store', 'update', 'destroy']);
        Route::get('/categories/get/count',     [CategoryController::class, 'count'])->name('categories.count');
        Route::get('/categories/get/count',     [CategoryController::class, 'count'])->name('categories.count');

        Route::apiResource('/items',        ItemController::class);
        Route::get('/items/get/all',        [ItemController::class, 'all'])->name('items.all');
        Route::get('/items/get/count',   [ItemController::class, 'count'])->name('items.count');


        Route::apiResource('/orders',           OrderController::class)->only(['store', 'index', 'update', 'show']);
        Route::get('/orders/get/count',         [OrderController::class, 'count'])->name('orders.count');

        Route::apiResource('/cart',                     CartController::class);
        // Route::post('/cart/{id}',                   [CartController::class,'store'])->name('cart.addToCart');
        // Route::delete('/cart/{id}',                 [CartController::class,'destroy']);
        // Route::post('/cart/increase-quantity',      [CartController::class, 'increaseItemQuantity'])->name('cart.increase');
        // Route::post('/cart/decrease-quantity',      [CartController::class, 'decreaseItemQuantity'])->name('cart.decrease');

        // categories
        Route::get('/categories/get/all',   [CategoryController::class, 'all'])->name('categories.all');
        Route::apiResource('/categories',   CategoryController::class)->except(['store', 'update', 'destroy']);

        // Payments
        Route::get('/payments/get/all',     [PaymentController::class, 'all'])->name('payments.all');
    });


});

Route::prefix('auth/')->group(function () {

    // Route::post('/register',        [UserAuthApiController::class, 'register'])->name('register');
    Route::post('login',            [UserAuthApiController::class, 'login'])->name('login');
    Route::post('logout',           [UserAuthApiController::class, 'logout'])->name('logout')
        ->middleware('auth:sanctum');

    Route::post('/forget-password',     [UserAuthApiController::class, 'forget_password'])->name('forget_password');
    Route::post('/reset-password',      [UserAuthApiController::class, 'reset_password'])->name('reset_password');

    Route::get('profile',           [UserAuthApiController::class, 'profile'])->name('profile')->middleware('auth:sanctum');
});
