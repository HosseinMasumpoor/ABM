<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendOTPRequest;
use App\Http\Responses\ErrorResponse;
use App\Models\OTP;
use App\Models\User;
use App\Notifications\SendOTPNotification;
use Carbon\Carbon;
use ErrorException;
use Hash;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Laravel\Passport\RefreshToken;
use Log;
use Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
    }

    public function checkEmail(CheckEmailRequest $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if ($user)
            return response([
                'message' => 'وارد حساب کاربری خود شوید',
                'registered' => true
            ]);
        return response([
            'message' => 'حساب کاربری خود را ایجاد نمایید',
            'registered' => false
        ]);
    }

    public function sendOTP(SendOTPRequest $request)
    {

        try {
            $user = User::where('email', $request->email)->firstOrFail();
            $otp = $user->otp_code;
            if ($otp) {
                return response([
                    'message' => 'کد یک بار مصرف قبلا برای شما ارسال شده است'
                ]);
            }
            $code = random_int(100000, 999999);
            $user->notify(new SendOTPNotification($code));
            Log::info('Email sent to user`s email', ['code' => $code]);

            $user->update([
                'otp_code' => $code,
                'otp_expires_at' => now()->addSeconds(config('auth.otp_expiration_time'))
            ]);

            return response([
                'message' => 'کد فعالسازی برای شما ارسال شد',
                'code' => $code
            ]);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'کد یک بار مصرف با موفقیت ارسال نشد', 500);
        }
    }
}
