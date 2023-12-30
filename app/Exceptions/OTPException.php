<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class OTPException extends Exception
{
    public function render($request)
    {
        return response([
            'message' => 'کد یکبار مصرف وارد شده اشتباه است'
        ], 403);
    }
}
