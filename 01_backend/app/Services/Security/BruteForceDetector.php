<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Cache;

class BruteForceDetector
{
    protected int $maxAttempts = 5;
    protected int $decayMinutes = 15;

    public function recordAttempt(string $key): int
    {
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addMinutes($this->decayMinutes));
        return $attempts;
    }

    public function isLocked(string $key): bool
    {
        $attempts = Cache::get($key, 0);
        return $attempts >= $this->maxAttempts;
    }

    public function clearAttempts(string $key): void
    {
        Cache::forget($key);
    }
}