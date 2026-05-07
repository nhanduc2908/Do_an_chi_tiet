<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Screen\ScreenDetectionService;

class DeviceDetectionMiddleware
{
    protected ScreenDetectionService $detectionService;

    public function __construct(ScreenDetectionService $detectionService)
    {
        $this->detectionService = $detectionService;
    }

    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->userAgent();
        
        $device = $this->detectDevice($userAgent);
        $request->attributes->set('device', $device);

        // Kiểm tra screen size nếu có trong header
        if ($request->hasHeader('X-Screen-Width')) {
            $screenData = [
                'width' => $request->header('X-Screen-Width'),
                'height' => $request->header('X-Screen-Height'),
            ];
            $detection = $this->detectionService->detect($screenData);
            $request->attributes->set('screen_type', $detection['device']);
        }

        return $next($request);
    }

    protected function detectDevice(string $userAgent): string
    {
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            return 'ios';
        }
        if (str_contains($userAgent, 'Android')) {
            return 'android';
        }
        if (str_contains($userAgent, 'Windows') || str_contains($userAgent, 'Mac')) {
            return 'desktop';
        }
        return 'unknown';
    }
}