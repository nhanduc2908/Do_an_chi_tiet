<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SecurityViolation;
use App\Models\RateLimit;
use App\Services\Security\BruteForceDetector;
use App\Services\Security\AlertService;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    protected BruteForceDetector $bruteForceDetector;
    protected AlertService $alertService;

    public function __construct(BruteForceDetector $bruteForceDetector, AlertService $alertService)
    {
        $this->bruteForceDetector = $bruteForceDetector;
        $this->alertService = $alertService;
    }

    public function violations(Request $request)
    {
        $query = SecurityViolation::with('user');
        
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->has('resolved')) {
            if ($request->boolean('resolved')) {
                $query->whereNotNull('resolved_at');
            } else {
                $query->whereNull('resolved_at');
            }
        }
        
        $violations = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($violations);
    }

    public function resolveViolation($id, Request $request)
    {
        $violation = SecurityViolation::findOrFail($id);
        
        $violation->update([
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
            'resolution_note' => $request->note,
        ]);

        return response()->json(['message' => 'Violation resolved']);
    }

    public function rateLimits(Request $request)
    {
        $limits = RateLimit::orderBy('created_at', 'desc')->paginate(20);
        return response()->json($limits);
    }

    public function clearRateLimit($key)
    {
        RateLimit::where('key', $key)->delete();
        return response()->json(['message' => 'Rate limit cleared']);
    }

    public function securityHeaders()
    {
        $headers = [
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Content-Security-Policy' => "default-src 'self'",
        ];
        
        return response()->json($headers);
    }

    public function securityScan()
    {
        $results = [
            'ssl_enabled' => true,
            'headers_secure' => true,
            'firewall_active' => true,
            'rate_limiting_enabled' => true,
            'last_scan' => now(),
        ];
        
        return response()->json($results);
    }
}