<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('customers', CustomerController::class);
Route::resource('orders', OrderController::class);

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/products', [ReportController::class, 'products'])->name('products');
    Route::get('/products/pdf', [ReportController::class, 'productsPdf'])->name('products.pdf');
    Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
    Route::get('/customers/pdf', [ReportController::class, 'customersPdf'])->name('customers.pdf');
    Route::get('/orders', [ReportController::class, 'orders'])->name('orders');
    Route::get('/orders/pdf', [ReportController::class, 'ordersPdf'])->name('orders.pdf');
    Route::get('/low-stock', [ReportController::class, 'lowStock'])->name('low-stock');
    Route::get('/low-stock/pdf', [ReportController::class, 'lowStockPdf'])->name('low-stock.pdf');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
