<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottle;

class ThrottleRequests extends BaseThrottle
{
    protected function resolveRequestSignature($request)
    {
        return sha1($request->method() . '|' . $request->path() . '|' . ($request->user()?->id ?? $request->ip()));
    }
}