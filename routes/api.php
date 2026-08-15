<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DeliverySettingController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

Route::get('/products/total-productos', [ProductController::class, 'count']);
Route::get('/products/count-categorias', [ProductController::class, 'countCategorias']);

Route::apiResource('products', ProductController::class);

Route::put(
    '/products/{product}/offer',
    [ProductController::class, 'assignOffer']
);


/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/

Route::get('/categories/count', [CategorieController::class, 'count']);

Route::apiResource('categories', CategorieController::class);


/*
|--------------------------------------------------------------------------
| Offers
|--------------------------------------------------------------------------
*/

Route::apiResource('offers', OfferController::class);


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});


/*
|--------------------------------------------------------------------------
| Orders
|--------------------------------------------------------------------------
*/

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{order}', [OrderController::class, 'update']);


/*
|--------------------------------------------------------------------------
| Admin - Delivery Settings
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/delivery-settings',
    [DeliverySettingController::class, 'show']
);

Route::put(
    '/admin/delivery-settings',
    [DeliverySettingController::class, 'update']
);


/*
|--------------------------------------------------------------------------
| Password Reset
|--------------------------------------------------------------------------
*/

Route::post(
    '/forgot-password',
    [PasswordResetController::class, 'forgot']
);

Route::post(
    '/reset-password',
    [PasswordResetController::class, 'reset']
);


/*
|--------------------------------------------------------------------------
| Test Email
|--------------------------------------------------------------------------
*/

// Route::get('/test-email', function () {
//     Mail::to('manuelmezarivas120@gmail.com')
//         ->send(new TestEmail());
//
//     return 'Correo enviado.';
// });
