<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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
    Route::get('/{category:slug}', [CategoryController::class, 'show']);
});



Route::prefix('/profile')->middleware('auth:api')->group(function () {
    Route::get('/information', [UserController::class, 'information']);
    Route::get('/orders', [UserController::class, 'showOrders']);
    Route::get('/addresses', [UserController::class, 'showAddresses']);
    Route::get('/bookmarks', [UserController::class, 'showBookmarks']);
    Route::get('/comments', [UserController::class, 'showComments']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('comment.owner');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->middleware('comment.owner');
});

Route::prefix('/admin')->group(function () {
    Route::apiResource('sliders', SliderController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('banners', BannerController::class);
    Route::prefix('/comments')->name('comments.')->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('all');
        Route::get('/{comment}', [CommentController::class, 'show'])->name('show');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
        Route::put('/change-status/{comment}', [CommentController::class, 'changeStatus'])->name('status.change');
    });
});

Route::post('check-cart', [CartController::class, 'checkCart'])->name('check-cart');


Route::get('/homepage/categories', [CategoryController::class, 'getHomepageCategories']);

Route::get('/sliders', [SliderController::class, 'showAll']);
Route::get('/brands', [BrandController::class, 'showAll']);
Route::get('/banners', [BannerController::class, 'showBanners']);

Route::post('auth/login', [AuthController::class, 'login'])->name('login');

Route::post('order', [OrderController::class, 'store'])->middleware('auth:api')->name('orders.store');

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('email.check');
    Route::post('/send-otp', [AuthController::class, 'sendOTP'])->name('otp.send');
});

Route::prefix('/user')->middleware('auth:api')->name('user.')->group(function () {
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('password.change');
    // Route::post('/set-password', [UserController::class, 'setPassword'])->name('password.set');
    Route::post('/change-email', [UserController::class, 'changeEmail'])->name('email.change');
    Route::get('/change-email-verify', [UserController::class, 'changeEmailVerify'])->middleware('signed')->name('email.change.verify');
});

Route::prefix('/comment')->middleware('auth:api')->name('comment.')->group(function () {
    Route::post('/', [CommentController::class, 'store'])->name('store');
});

Route::prefix('/bookmark')->middleware('auth:api')->name('bookmark.')->group(function () {
    Route::post('/', [BookmarkController::class, 'store'])->name('store');
});

Route::prefix('/address')->middleware('auth:api')->name('address.')->group(function () {
    Route::post('/', [AddressController::class, 'store'])->name('store');
    Route::put('/{address}', [AddressController::class, 'update'])->name('update');
});


Route::get('/test', function () {
    Order::truncate();
    OrderItem::truncate();
});
