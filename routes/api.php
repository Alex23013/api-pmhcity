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
use App\Http\Controllers\API\SizeController;
use App\Http\Controllers\API\StatusProductController;
use App\Http\Controllers\API\MaterialController;

use App\Http\Controllers\CityController;
use App\Http\Controllers\API\StoreController;
use App\Models\StatusProduct;

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
    Route::get('metropoles/names', [MetropoleController::class, 'listNames']);
    Route::get('metropoles/{id}/cities', [CityController::class, 'getCitiesByMetropole']);

    Route::resource('categories', CategoryController::class);
    Route::resource('metropoles', MetropoleController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('status-products', StatusProductController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('materials', MaterialController::class);

    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('cities', CityController::class);

    Route::put('stores/{id}/verified', [StoreController::class, 'markAsVerifiedStore']);
    Route::post('stores/verification', [StoreController::class, 'verifyStore']);
    Route::get('stores', [StoreController::class, 'listStores']);
    Route::get('stores/{id}/products', [StoreController::class, 'listProductsByStore']);
    Route::post('stores/{id}/edit', [StoreController::class, 'update']);
    
    Route::get('reservations/{id}/details', [ReservationController::class, 'reservationDetails']);
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::post('reservations/update-status', [ReservationController::class, 'updateStatus']);

});

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/users/{id}/edit', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);


