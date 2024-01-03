<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendOTPRequest;
use App\Http\Responses\ErrorResponse;
use App\Mail\OTPCodeMail;
use App\Models\OTP;
use App\Models\User;
use App\Notifications\SendOTPNotification;
use Cache;
use Carbon\Carbon;
use ErrorException;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Laravel\Passport\Passport;
use Laravel\Passport\RefreshToken;
use Log;
use Mail;
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
                'email' => $email,
                'registered' => true
            ]);
        return response([
            'message' => 'حساب کاربری خود را ایجاد نمایید',
            'email' => $email,
            'registered' => false
        ]);
    }

    public function sendOTP(SendOTPRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        // User Registered Before And Now Want To Login

        if ($user) {
            try {
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

        // User Want to Register

        $code = random_int(100000, 999999);

        $email = $request->email;

        if (Cache::has(config('auth.otp_cache_key_prefix') . $email)) {
            return response([
                'message' => 'کد یک بار مصرف قبلا برای شما ارسال شده است'
            ]);
        }

        try {


            Mail::to($email)->send(new OTPCodeMail($code));
            $expirationTime = now()->addSeconds(config('auth.otp_expiration_time'));

            Log::info('Email sent to user`s email', ['code' => $code]);

            Cache::put(config('auth.otp_cache_key_prefix') . $email, $code, $expirationTime);

            return response([
                'message' => 'کد فعالسازی برای شما ارسال شد',
                'code' => $code
            ]);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'کد یک بار مصرف با موفقیت ارسال نشد', 500);
        }
    }
}
