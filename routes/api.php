<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\User;
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


Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/show/{product}', [ProductController::class, 'show']);
    Route::get('/show/{product}/comments', [ProductController::class, 'showComments']);

    Route::get('/filter/{category}', [ProductController::class, 'filter']);
    Route::get('/getfilters/{category}', [ProductController::class, 'getFilters']);
    Route::get('/discounts', [ProductController::class, 'getDiscounts']);
    Route::get('/top-orders', [ProductController::class, 'topOrders']);
});


Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryController::class, 'showAll']);
    Route::get('/{category}', [CategoryController::class, 'show']);
});

Route::prefix('/profile')->group(function () {
    Route::get('/information', [UserController::class, 'information']);
    Route::get('/orders', [UserController::class, 'showOrders']);
    Route::get('/addresses', [UserController::class, 'showAddresses']);
    Route::get('/bookmarks', [UserController::class, 'showBookmarks']);
    Route::get('/comments', [UserController::class, 'showComments']);
});

Route::prefix('/admin')->group(function () {
    Route::apiResource('sliders', SliderController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('brands', BrandController::class);
});


Route::get('/homepage/categories', [CategoryController::class, 'getHomepageCategories']);

Route::get('/sliders', [SliderController::class, 'index']);


Route::get('/test', function () {
});
