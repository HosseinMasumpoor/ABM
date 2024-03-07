<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkCommentOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $comment = $request->route('comment');
        $userId = auth()->id();
        if ($comment->user_id == $userId)
            return $next($request);
        abort(403, 'نظر انتخاب شده معتبر نیست');
    }
}
