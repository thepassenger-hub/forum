<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Check if the user is an admin to restrict some routes.
 */
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isAdmin) return $next($request);

        return response('Nice try', 403);
    }
}
