<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Check if the request path is inside the cache. If it is the cache key is return to avoid expensive db queries.
 * If it isn't in the cache the request response json content is stored in cache for future calls.
 */
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
