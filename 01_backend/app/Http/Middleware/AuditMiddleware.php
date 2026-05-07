<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Audit\AuditLogger;

class AuditMiddleware
{
    protected AuditLogger $auditLogger;

    public function __construct(AuditLogger $auditLogger)
    {
        $this->auditLogger = $auditLogger;
    }

    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        // Log request
        $this->auditLogger->log(
            'api_request',
            "{$request->method()} {$request->path()}",
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'duration_ms' => $duration,
                'status_code' => $response->getStatusCode(),
            ]
        );

        $response->headers->set('X-Request-Duration', $duration . 'ms');

        return $response;
    }
}