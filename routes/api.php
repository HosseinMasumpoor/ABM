<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
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
    Route::apiResource('banners', BannerController::class);
});

Route::post('check-cart', [CartController::class, 'checkCart'])->name('check-cart');


Route::get('/homepage/categories', [CategoryController::class, 'getHomepageCategories']);

Route::get('/sliders', [SliderController::class, 'showAll']);
Route::get('/brands', [BrandController::class, 'showAll']);
Route::get('/banners', [BannerController::class, 'showBanners']);

Route::post('auth/login', [AuthController::class, 'login'])->name('login');

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('email.check');
    Route::post('/send-otp', [AuthController::class, 'sendOTP'])->name('otp.send');
});

Route::prefix('/user')->middleware('auth:api')->group(function () {
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('user.password.change');
    Route::post('/set-password', [UserController::class, 'setPassword'])->name('user.password.set');
    // Route::post('/change-email', [UserController::class, 'changeEmail'])->name('user.email.change');
});





// Route::post('/login', function (Request $request) {
//     $user = User::where('email', $request->email)->first();
//     if (!$user) {
//         throw ValidationException::withMessages([
//             'email' => 'کاربری با ایمیل وارد شده پیدا نشد'
//         ]);
//     }
//     if (Hash::check($request->password, $user->password)) {
//         auth()->login($user);
//     } else {
//         throw ValidationException::withMessages([
//             'password' => 'رمز عبور وارد شده اشتباه است'
//         ]);
//     }

//     return response([
//         'message' => 'ورود به حساب کاربری با موفقیت انجام شد',
//         'data' => $user
//     ]);
// });

// Route::post('/logout', function () {
//     auth()->logout();
//     return response([
//         'message' => 'شما با موفقیت خارج شدید'
//     ]);
// });
