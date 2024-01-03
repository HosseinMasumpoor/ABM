<?php

namespace App\Auth;

use App\Exceptions\OTPException;
use App\Http\Responses\ErrorResponse;
use App\Models\User;
use Cache;

class OTPVerifier
{
    public function verify($email, $code)
    {
        $user = User::where('email', $email)->first();

        // User Registered And Want To Login
        if ($user) {
            if ($user->otp_code && $user->otp_code == $code) {
                try {
                    $user->update([
                        'otp_code' => null,
                        'otp_expires_at' => null,
                    ]);
                } catch (\Throwable $th) {
                    throw new OTPException("ورود به حساب کاربری با موفقیت انجام نشد");
                }
                return true;
            }
            return false;
        }

        // User Want To Login


        if (Cache::get(config('auth.otp_cache_key_prefix') . $email) == $code) {
            Cache::delete(config('auth.otp_cache_key_prefix') . $email);
            try {
                User::create([
                    'email' => $email,
                    'is_active' => false,
                    'email_verified_at' => now()
                ]);
            } catch (\Throwable $th) {
                throw new OTPException("ثبت نام با موفقیت انجام نشد");
            }

            return true;
        }
        return false;
    }
}
