<?php

namespace App\Auth;

use App\Exceptions\OTPException;
use App\Http\Responses\ErrorResponse;
use App\Models\User;

class OTPVerifier
{
    public function verify($email, $code)
    {
        $user = User::where('email', $email)->first();
        if ($user->otp_code && $user->otp_code == $code) {
            try {
                $user->update([
                    'otp_code' => null,
                    'otp_expires_at' => null
                ]);
            } catch (\Throwable $th) {
                throw new OTPException("ورود به حساب کاربری با موفقیت انجام نشد");
            }
            return true;
        }
        return false;
    }
}
