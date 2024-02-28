<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bookmark\AddBookmarkRequest;
use App\Http\Responses\ErrorResponse;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
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
    public function store(AddBookmarkRequest $request)
    {

        $user = auth()->user();

        try {
            $user->bookmarks()->create($request->all());
        } catch (\Throwable $th) {
            $message = 'محصول به لیست علاقه مندی ها اضافه نشد';
            return new ErrorResponse($th, $message);
        }

        return response([
            'message' => 'محصول به لیست علاقه مندی های شما افزوده شد'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookmark $bookmark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bookmark $bookmark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookmark $bookmark)
    {
        //
    }
}
