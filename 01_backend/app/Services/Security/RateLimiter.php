<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Cache;

class RateLimiter
{
    public function limit(string $key, int $maxAttempts, int $decaySeconds = 60): bool
    {
        $attempts = Cache::get($key, 0);
        if ($attempts >= $maxAttempts) return false;
        Cache::put($key, $attempts + 1, now()->addSeconds($decaySeconds));
        return true;
    }
}