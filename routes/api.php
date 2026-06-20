<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeliverySettingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products/total-productos', [ProductController::class, 'count']);
Route::get('/products/count-categorias', [ProductController::class, 'countCategorias']);
Route::put('/orders/{order}', [OrderController::class, 'update']);
Route::apiResource(
    'products',
    ProductController::class
);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders', [OrderController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get(
    '/admin/delivery-settings',
    [DeliverySettingController::class, 'show']
);

Route::put(
    '/admin/delivery-settings',
    [DeliverySettingController::class, 'update']
);
