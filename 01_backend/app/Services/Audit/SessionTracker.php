<?php

namespace App\Services\Audit;

use App\Models\SessionLog;
use Illuminate\Support\Facades\Auth;

class SessionTracker
{
    public function startSession(int $userId, string $sessionId): void
    {
        SessionLog::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_at' => now(),
            'status' => 'active',
        ]);
    }

    public function endSession(string $sessionId): void
    {
        $log = SessionLog::where('session_id', $sessionId)->whereNull('logout_at')->first();
        if ($log) {
            $log->update(['logout_at' => now(), 'duration' => $log->login_at->diffInSeconds(now())]);
        }
    }

    public function getActiveSessions(int $userId): int
    {
        return SessionLog::where('user_id', $userId)->whereNull('logout_at')->count();
    }
}