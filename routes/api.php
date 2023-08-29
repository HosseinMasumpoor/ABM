<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/products')->group(function(){
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/show/{product}', [ProductController::class, 'show']);
    Route::get('/filter/{category}', [ProductController::class, 'filter']);
    Route::get('/getfilters/{category}', [ProductController::class, 'getFilters']);
    Route::get('/discounts', [ProductController::class, 'getDiscounts']);
    Route::get('/top-orders', [ProductController::class, 'topOrders']);
});


Route::prefix('/categories')->group(function(){
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category}', [CategoryController::class, 'show']);
});

Route::get('/homepage/categories', [CategoryController::class, 'getHomepageCategories']);

Route::get('/sliders', [SliderController::class, 'index']);







