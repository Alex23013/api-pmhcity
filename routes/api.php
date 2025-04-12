<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;


use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SubcategoryController;
use App\Http\Controllers\API\MetropoleController;
use App\Http\Controllers\API\ColorController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\ReservationStatusController;
use App\Http\Controllers\API\SizeController;
use App\Http\Controllers\API\StatusProductController;
use App\Http\Controllers\API\MaterialController;
use App\Http\Controllers\API\PhoneTokenController;

use App\Http\Controllers\CityController;
use App\Http\Controllers\API\StoreController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
         
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class)->except(['update']);
    Route::post('products/edit', [ProductController::class, 'update']);
    Route::resource('notifications', NotificationController::class)->except(['destroy']);
    
    Route::get('profile', [UserController::class, 'profile']);
    Route::get('categories/names', [CategoryController::class, 'listNames']);

    Route::resource('categories', CategoryController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('reservation-statuses', ReservationStatusController::class);
    Route::resource('status-products', StatusProductController::class);
    Route::resource('sizes', SizeController::class);
    Route::get('sizes/{id}/subcategory', [SizeController::class, 'listSizesBySubcategory']);
    Route::resource('materials', MaterialController::class);

    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('cities', CityController::class);

    Route::put('stores/{id}/verified', [StoreController::class, 'markAsVerifiedStore']);
    Route::post('stores/verification', [StoreController::class, 'verifyStore']);
    Route::post('stores/{id}/edit', [StoreController::class, 'update']);
    
    Route::get('reservations/{id}/details', [ReservationController::class, 'reservationDetails']);
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::post('reservations/update-status', [ReservationController::class, 'updateStatus']);

    Route::post('/phone/verify', [PhoneTokenController::class, 'verifyTokenInProfile']);
    Route::post('/upload-products', [ProductController::class, 'uploadProductsCSV']);
});

Route::post('/phone-tokens/generate', [PhoneTokenController::class, 'generateToken']);
Route::post('/phone-tokens/verify', [PhoneTokenController::class, 'verifyToken']);

Route::resource('metropoles', MetropoleController::class);
Route::get('metropoles/names', [MetropoleController::class, 'listNames']);
Route::get('metropoles/{id}/cities', [CityController::class, 'getCitiesByMetropole']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/users/{id}/edit', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::post('/forgot-password', [UserController::class, 'sendResetToken']);

# Marketplace
Route::get('stores', [StoreController::class, 'listStores']);
Route::get('stores/{id}/products', [StoreController::class, 'listProductsByStore']);
Route::get('/v2/products/{id}', [ProductController::class, 'show']);
Route::get('/v2/categories', [CategoryController::class, 'index']);


