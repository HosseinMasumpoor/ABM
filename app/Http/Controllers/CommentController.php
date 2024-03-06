<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\ChangeCommentStatusRequest;
use App\Http\Requests\Comment\GetAllCommentsRequest;
use App\Http\Requests\User\CommentStoreRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentResourceCollection;
use App\Http\Responses\ErrorResponse;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllCommentsRequest $request)
    {
        $comments = Comment::filter()->latest()->paginate($request->items_perpage ?? 8);
        return new CommentResourceCollection($comments);
    }

    public function changeStatus(ChangeCommentStatusRequest $request, Comment $comment)
    {
        try {
            $comment->update($request->validated());
        } catch (\Throwable $th) {
            $message = 'وضعیت نظر با موفقیت تغییر نکرد';
            return new ErrorResponse($th, $message);
        }
        return response([
            'message' => 'وضعیت نظر با موفقیت تغییر کرد',
            'data' => $comment
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request)
    {
        $user = auth()->user();

        try {
            $comment = $user->comments()->create($request->validated());
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
        return new CommentResource($comment);
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
        } catch (\Throwable $th) {
            return new ErrorResponse($th, 'حذف نظر با موفقیت انجام نشد');
        }
        return response([
            'message' => 'نظر با موفقیت حذف شد'
        ]);
    }
}
