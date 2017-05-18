<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheGetOrPut
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $tag)
    {
        $key = $request->path() . str_replace($request->url(), '', $request->fullUrl());
        if (Cache::tags($tag)->has($key)) 
            return response(Cache::tags($tag)->get($key), 200)
                ->header('Content-Type', 'application/json');

        $response = $next($request);
        Cache::tags($tag)->forever($key, $response->getContent());
        return $response;
    }
}
