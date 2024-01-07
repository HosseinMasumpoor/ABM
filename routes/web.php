<?php

use App\Mail\OTPCodeMail;
use App\Mail\Verify;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test-email', function () {
//     // return new OTPCodeMail('123456');
//     $url = "http://abm.me/user/change-email-verify?email=ahoseinmasumpooraa%40gmail.com&expires=1704575753&user=14&signature=35e718c12fe549dba2ea85ace31dff282db04332dc7caded591b37cf4c81b358";
//     return new Verify($url);
// });
