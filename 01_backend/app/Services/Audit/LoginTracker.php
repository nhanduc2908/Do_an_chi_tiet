<?php

namespace App\Services\Audit;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;

class LoginTracker
{
    public static function record(int $userId, string $status, string $ipAddress, ?string $deviceId = null): LoginHistory
    {
        return LoginHistory::create([
            'user_id' => $userId > 0 ? $userId : null,
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent(),
            'device_name' => self::getDeviceName(),
            'status' => $status,
            'login_at' => now(),
            'session_id' => session()->getId(),
        ]);
    }

    public static function updateLogout(int $userId): void
    {
        $log = LoginHistory::where('user_id', $userId)
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first();
        
        if ($log) {
            $log->update(['logout_at' => now()]);
        }
    }

    public static function recordPasswordChange(int $userId): void
    {
        self::record($userId, 'password_changed', request()->ip());
    }

    protected static function getDeviceName(): string
    {
        $agent = request()->userAgent();
        if (str_contains($agent, 'iPhone')) return 'iPhone';
        if (str_contains($agent, 'Android')) return 'Android';
        if (str_contains($agent, 'Windows')) return 'Windows';
        if (str_contains($agent, 'Mac')) return 'Mac';
        return 'Unknown';
    }
}