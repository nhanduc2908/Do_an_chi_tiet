<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter as CacheRateLimiter;

class RateLimiter
{
    protected CacheRateLimiter $limiter;

    public function __construct(CacheRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);
        
        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'message' => 'Too many requests',
                'retry_after' => $this->limiter->availableIn($key),
            ], 429);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders($response, $maxAttempts, $this->limiter->attempts($key));
    }

    protected function resolveRequestSignature(Request $request): string
    {
        return sha1($request->ip() . '|' . ($request->user()?->id ?? 'guest'));
    }

    protected function addHeaders($response, $maxAttempts, $remainingAttempts)
    {
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', $remainingAttempts);
        
        return $response;
    }
}