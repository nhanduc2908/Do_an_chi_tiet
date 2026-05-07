<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public function log(string $eventType, string $description, array $oldValues = [], array $newValues = [], ?int $userId = null): void
    {
        AuditLog::create([
            'user_id' => $userId ?? Auth::id(),
            'event_type' => $eventType,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function logRequest(Request $request, $response): void
    {
        if ($this->shouldLog($request)) {
            $this->log('api_request', "{$request->method()} {$request->path()}", [
                'payload' => $request->except(['password', 'token']),
                'response_status' => $response->status(),
            ]);
        }
    }

    protected function shouldLog(Request $request): bool
    {
        $excludePaths = ['health', 'metrics', 'ping'];
        return !in_array($request->path(), $excludePaths);
    }
}