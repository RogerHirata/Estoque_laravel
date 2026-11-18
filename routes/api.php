<?php

use App\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Http\Controllers\Api\CustomerController as ApiCustomerController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'nome' => 'API — Sistema de Estoque',
        'versao' => 'v1',
        'base' => url('/api/v1'),
        'recursos' => [
            'categories' => url('/api/v1/categories'),
            'products' => url('/api/v1/products'),
            'customers' => url('/api/v1/customers'),
            'orders' => url('/api/v1/orders'),
        ],
        'dica' => 'Use GET/POST etc. nos endereços acima (Postman ou navegador só em GET).',
    ]);
});

Route::prefix('v1')->name('api.')->group(function () {
    Route::apiResource('categories', ApiCategoryController::class);
    Route::apiResource('products', ApiProductController::class);
    Route::apiResource('customers', ApiCustomerController::class);
    Route::apiResource('orders', ApiOrderController::class);
});
