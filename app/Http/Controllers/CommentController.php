<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CommentStoreRequest;
use App\Http\Responses\ErrorResponse;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request)
    {
        $user = auth()->user();

        try {
            $comment = $user->comments()->create($request->all());
        } catch (\Throwable $th) {
            $message = 'ثبت نظر با موفقیت انجام نشد';
            return new ErrorResponse($th, $message);
        }

        return response([
            'message' => 'نظر شما با موفقیت ثبت شد',
            'data' => $comment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
