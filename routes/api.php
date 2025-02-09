<?php

use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\OrdersController;
use App\Http\Controllers\api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Customer
Route::get('/customers', [CustomerController::class, 'index'])->name('api.customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('api.customers.store');
Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('api.customers.show');
Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('api.customers.update');
Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('api.customers.destroy');

// Products
Route::get('/products', [ProductsController::class, 'index'])->name('api.products.index');
Route::post('/products', [ProductsController::class, 'store'])->name('api.products.store');
Route::get('/products/{id}', [ProductsController::class, 'show'])->name('api.products.show');
Route::put('/products/{id}', [ProductsController::class, 'update'])->name('api.products.update');
Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('api.products.destroy');

// Orders
Route::get('/orders', [OrdersController::class, 'index'])->name('api.orders.index');
Route::post('/orders', [OrdersController::class, 'store'])->name('api.orders.store');
// Route::get('/orders/{id}', [OrdersController::class, 'show'])->name('api.orders.show');
// Route::put('/orders/{id}', [OrdersController::class, 'update'])->name('api.orders.update');
// Route::delete('/orders/{id}', [OrdersController::class, 'destroy'])->name('api.orders.destroy');