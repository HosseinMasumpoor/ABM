<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\Comment\GetAllCommentsRequest;
use App\Http\Requests\User\ChagnePasswordRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\BookmarkResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Http\Responses\ErrorResponse;
use App\Models\User;
use App\Notifications\EmailChangeNotification;
use Hash;
use Illuminate\Http\Request;
use Notification;

class UserController extends Controller
{

    public function setPassword(SetPasswordRequest $request)
    {
        $user = auth()->user();
        // dd($request->all());
        try {
            $user = $user->update([
                'password' => $request->password,
                'is_active' => true
            ]);

            return response([
                'message' => 'رمز عبور با موفقیت تعیین شد'
            ]);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, "رمز عبور با موفقیت تعیین نشد", 500);
        }
    }

    public function changePassword(ChagnePasswordRequest $request)
    {
        $user = auth()->user();

        if ($request->code) {
            if (empty($user->otp_code) || $user->otp_expires_at < now()) {

                return response([
                    'message' => 'لطفا درخواست رمز یکبار مصرف بدهید'
                ], 400);
            }
            if ($request->code != $user->otp_code) {
                return response([
                    'message' => 'رمز یک بار مصرف وارد شده اشتباه است'
                ], 400);
            }
        } else {

            if (!Hash::check($request->old_password, $user->password)) {
                return response([
                    'message' => 'رمز عبور وارد شده اشتباه است'
                ], 401);
            }
        }
        try {
            $user->update([
                'password' => $request->password,
                'otp_code' => null
            ]);

            return response([
                'message' => 'رمز عبور شما با موفقیت تغییر کرد'
            ], 200);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'رمز عبور با موفقیت تغییر نکرد', 500);
        }
    }

    public function changeEmail(ChangeEmailRequest $request)
    {
        $user = auth()->user();
        try {
            Notification::route('mail', $request->email)->notify(new EmailChangeNotification(auth()->id()));
            return response([
                'message' => 'ایمیل تأیید برای شما ارسال شد'
            ]);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'ایمیل با موفقیت تغییر نکرد', 500);
        }
    }

    public function changeEmailVerify(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:users,email'
        ]);

        $user = auth()->user();

        $user->update([
            'email' => $request->email,
            'email_verified_at' => now()
        ]);

        return response([
            'message' => 'ایمیل با موفقیت تغییر پیدا کرد',
            'email' => $request->email
        ]);


        try {
        } catch (\Throwable $th) {
            return new ErrorResponse($th, "تغییر ایمیل موفقیت آمیز نبود", 500);
        }
    }


    public function information()
    {
        $user = auth()->user();
        $user = new UserResource($user);
        return $user;
    }

    public function showOrders(Request $request)
    {
        $user = auth()->user();
        $orders = OrderResource::collection($user->orders()->paginate($request->items_perpage ?? 10));
        return $orders;
    }

    public function showBookmarks(Request $request)
    {
        $user = auth()->user();
        return BookmarkResource::collection($user->bookmarks()->paginate($request->items_perpage ?? 10));
    }

    public function showAddresses(Request $request)
    {
        $user = auth()->user();
        $addresses = AddressResource::collection($user->addresses()->paginate($request->items_perpage ?? 10));
        return $addresses;
    }

    public function showComments(GetAllCommentsRequest $request)
    {
        $user = auth()->user();
        return CommentResource::collection($user->comments()->filter()->latest()->paginate($request->items_perpage ?? 10));
    }
}
