<?php

namespace App\Http\Middleware;

use \Carbon\Carbon;

use Closure;

class IsActive
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
        if (! auth()->user()->isActive()) {
            $days = Carbon::now()->diffInDays(Carbon::parse(auth()->user()->status->until));
            $template = $days > 1 ? "more days." : "more day.";
            $message = "You are banned for {$days} {$template}";
            return response(['error' => $message], 403);
        }
        return  $next($request);
    }
}
