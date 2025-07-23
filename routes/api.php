<?php

use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\KategoriProdukController;
use App\Http\Controllers\Api\PengaturanTampilanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\ShippingController;
use Illuminate\Support\Facades\Route;

Route::get('produks', [ProdukController::class, 'index']);
Route::get('produks/{id}', [ProdukController::class, 'show']);
Route::get('kategoris', [KategoriProdukController::class, 'index']);
Route::get('banners', [PengaturanTampilanController::class, 'index']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('me', [AuthController::class, 'me']);
Route::middleware('auth:api')->get('my-orders', [OrderController::class, 'myOrders']);
Route::middleware('auth:api')->get('cart', [OrderController::class, 'cart']);
Route::middleware('auth:api')->post('cart', [OrderController::class, 'storeCart']);
Route::middleware('auth:api')->patch('cart/{item}', [OrderController::class, 'updateCartItem']);
Route::middleware('auth:api')->delete('cart/{item}', [OrderController::class, 'deleteCartItem']);
Route::middleware('auth:api')->get('addresses', [AddressController::class, 'index']);
Route::middleware('auth:api')->post('addresses', [AddressController::class, 'store']);
Route::middleware('auth:api')->put('addresses/{id}', [AddressController::class, 'update']);
Route::middleware('auth:api')->delete('addresses/{id}', [AddressController::class, 'destroy']);
Route::middleware('auth:api')->post('checkout', [OrderController::class, 'checkout']);
Route::middleware('auth:api')->get('order-detail/{orderId}', [OrderController::class, 'orderDetail']);
Route::middleware('auth:api')->get('profile-detail', [UserProfileController::class, 'show']);
Route::middleware('auth:api')->post('profile-detail', [UserProfileController::class, 'update']);

// Shipping API
Route::middleware('auth:api')->post('shipping/calculate', [ShippingController::class, 'calculateShipping']);
Route::middleware('auth:api')->get('shipping/couriers', [ShippingController::class, 'getCouriers']);
Route::middleware('auth:api')->post('shipping/update-cart', [ShippingController::class, 'updateCartShipping']);

Route::get('provinces', [WilayahController::class, 'provinces']);
Route::get('regencies', [WilayahController::class, 'regencies']);
Route::get('districts', [WilayahController::class, 'districts']);

// Midtrans Webhook Notification
Route::post('midtrans/notification', [WebhookController::class, 'handle']);

// CORS Test Route
Route::get('cors-test', function () {
    return response()->json([
        'message' => 'CORS is working!',
        'timestamp' => now(),
        'origin' => request()->header('Origin')
    ]);
});

// Fallback OPTIONS route for CORS preflight
Route::options('/{any}', function () {
    return response()->json(['status' => 'ok']);
})->where('any', '.*');