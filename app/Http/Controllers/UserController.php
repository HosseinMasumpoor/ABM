<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChagnePasswordRequest;
use App\Http\Requests\User\SetPasswordRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\BookmarkResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Http\Responses\ErrorResponse;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

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

        if (!Hash::check($request->old_password, $user->password)) {
            return response([
                'message' => 'رمز عبور وارد شده اشتباه است'
            ], 401);
        }
        try {
            $user->update([
                'password' => $request->password
            ]);

            return response([
                'message' => 'رمز عبور شما با موفقیت تغییر کرد'
            ], 200);
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'رمز عبور با موفقیت تغییر نکرد', 500);
        }
    }

    public function information()
    {
        $user = auth()->user() ?? User::find(3);
        $user = new UserResource($user);
        return $user;
    }

    public function showOrders(Request $request)
    {
        $user = auth()->user() ?? User::find(3);
        $orders = OrderResource::collection($user->orders()->paginate($request->items_perpage ?? 10));
        return $orders;
    }

    public function showBookmarks(Request $request)
    {
        $user = auth()->user() ?? User::find(3);
        return BookmarkResource::collection($user->bookmarks()->paginate($request->items_perpage ?? 10));
    }

    public function showAddresses(Request $request)
    {
        $user = auth()->user() ?? User::find(3);
        $addresses = AddressResource::collection($user->addresses()->paginate($request->items_perpage ?? 10));
        return $addresses;
    }

    public function showComments(Request $request)
    {
        $user = auth()->user() ?? User::find(3);
        return CommentResource::collection($user->comments()->paginate($request->items_perpage ?? 10));
    }
}
