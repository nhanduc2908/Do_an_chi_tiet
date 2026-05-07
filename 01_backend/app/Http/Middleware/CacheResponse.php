<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    public function handle(Request $request, Closure $next, $ttl = 60)
    {
        if ($request->isMethod('get') && !$request->user()) {
            $key = 'response_' . md5($request->fullUrl());
            
            return Cache::remember($key, $ttl, function () use ($request, $next) {
                return $next($request);
            });
        }

        return $next($request);
    }
}