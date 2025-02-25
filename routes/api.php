<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SubcategoryController;
use App\Http\Controllers\API\MetropoleController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\API\StoreController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
         
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
    Route::resource('notifications', NotificationController::class);
    
    Route::get('profile', [UserController::class, 'profile']);
    Route::get('categories/names', [CategoryController::class, 'listNames']);
    Route::get('metropoles/names', [MetropoleController::class, 'listNames']);
    Route::get('metropoles/{id}/cities', [CityController::class, 'getCitiesByMetropole']);

    Route::resource('categories', CategoryController::class);
    Route::resource('metropoles', MetropoleController::class);
    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('cities', CityController::class);

    Route::put('stores/{id}/verified', [StoreController::class, 'markAsVerifiedStore']);
    Route::post('stores/verification', [StoreController::class, 'verifyStore']);
});

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);


