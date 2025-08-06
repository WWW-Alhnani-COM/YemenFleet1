<?php

use App\Http\Controllers\Admin\SensorDataController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('customers')->group(function () {
    Route::post('/register', [CustomerApiController::class, 'register']);
    Route::post('/login', [CustomerApiController::class, 'login']);
    Route::get('/{id}', [CustomerApiController::class, 'show']);
    Route::put('/{id}', [CustomerApiController::class, 'update']);
    Route::delete('/{id}', [CustomerApiController::class, 'destroy']);
});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index']);
    Route::post('/', [ProductApiController::class, 'store']);
    Route::get('/{id}', [ProductApiController::class, 'show']);
    Route::put('/{id}', [ProductApiController::class, 'update']);
    Route::delete('/{id}', [ProductApiController::class, 'destroy']);
});

use App\Http\Controllers\Api\CustomerReviewController;

Route::group(['prefix' => 'v1'], function() {
    Route::apiResource('customer-reviews', CustomerReviewController::class);

    // روت إضافي للبحث حسب المنتج والتقييم
    Route::get('customer-reviews/search', [CustomerReviewController::class, 'index']);
});


use App\Http\Controllers\Api\CategoryController;

Route::group(['prefix' => 'v1'], function() {
    // روات لعرض الفئات (للعميل)
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);

    // روات الإدارة (تحتاج auth)
    Route::middleware('auth:sanctum')->group(function() {
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    });

    Route::get('categories/{id}/products', [CategoryController::class, 'getCategoryProducts']);
});


use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\API\CompanyOrderApiController;
use App\Http\Controllers\API\DriverAPIController;

Route::apiResource('companies', CompanyController::class);

// Route للحصول على منتجات شركة معينة
Route::get('companies/{company}/products', [CompanyController::class, 'products']);



use App\Http\Controllers\Api\OrderController;

// Route::middleware('auth:api')->group(function () {
    // طلبات العميل
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
    });
// });




// routes/api.php
Route::post('/fetchFromGpsAndStore', [SensorDataController::class, 'fetchFromGpsAndStore']);
// routes/api.php
Route::get('/getGpsData', [SensorDataController::class, 'getGpsData']);

Route::post('/fetch-weather', [SensorDataController::class, 'fetchWeatherAndStore']);
// Route::post('/api/fetch-weather', [SensorDataController::class, 'receiveWeatherFromEsp']);

Route::post('/store-obd-data', [SensorDataController::class, 'storeObdData']);




Route::prefix('drivers')->group(function () {
    Route::post('/login', [\App\Http\Controllers\API\DriverAPIController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\API\DriverAPIController::class, 'logout']);
    Route::get('/profile', [\App\Http\Controllers\API\DriverAPIController::class, 'profile']);

});

Route::get('/trucks/{truck}/sensors', [DriverAPIController::class, 'getSensorsByTruck']);




Route::get('/company-orders/{companyId}', [CompanyOrderApiController::class, 'index']);
Route::get('/company-order/{id}', [CompanyOrderApiController::class, 'show']);
Route::get('company-orders/{companyId}/status-summary', [CompanyOrderApiController::class, 'statusSummary']);


use App\Http\Controllers\API\TaskApiController;

Route::get('/drivers/{driverId}/task-count', [DriverAPIController::class, 'countByDriver']);





