<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Http\Resources\BookmarkResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function information()
    {
        $user = auth()->user() ?? User::find(3);
        $user = new UserResource($user);
        return $user;
    }

    public function showOrders()
    {
        $user = auth()->user() ?? User::find(3);
        $orders = OrderResource::collection($user->orders);
        return $orders;
    }

    public function showBookmarks()
    {
        $user = auth()->user() ?? User::find(3);
        return BookmarkResource::collection($user->bookmarks);
    }

    public function showAddresses()
    {
        $user = auth()->user() ?? User::find(3);
        $addresses = AddressResource::collection($user->addresses);
        return $addresses;
    }

    public function showComments()
    {
        $user = auth()->user() ?? User::find(3);
        return CommentResource::collection($user->comments->load('product'));
    }
}
