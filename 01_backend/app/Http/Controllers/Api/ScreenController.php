<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScreenLog;
use App\Services\Screen\ScreenDetectionService;
use App\Services\Screen\AdaptiveLayoutService;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    protected ScreenDetectionService $detectionService;
    protected AdaptiveLayoutService $layoutService;

    public function __construct(
        ScreenDetectionService $detectionService,
        AdaptiveLayoutService $layoutService
    ) {
        $this->detectionService = $detectionService;
        $this->layoutService = $layoutService;
    }

    public function detect(Request $request)
    {
        $request->validate([
            'width' => 'required|integer',
            'height' => 'required|integer',
            'device_pixel_ratio' => 'nullable|numeric',
            'orientation' => 'nullable|in:portrait,landscape',
        ]);

        $result = $this->detectionService->detect([
            'width' => $request->width,
            'height' => $request->height,
            'device_pixel_ratio' => $request->device_pixel_ratio ?? 1,
            'orientation' => $request->orientation ?? 'landscape',
        ]);

        ScreenLog::create([
            'user_id' => $request->user()->id,
            'screen_width' => $request->width,
            'screen_height' => $request->height,
            'device_pixel_ratio' => $request->device_pixel_ratio ?? 1,
            'orientation' => $request->orientation ?? 'landscape',
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json($result);
    }

    public function getLayout(Request $request)
    {
        $width = $request->get('width', 1920);
        $detection = $this->detectionService->detect(['width' => $width, 'height' => 1080]);
        $layout = $this->layoutService->getLayout($detection['device']);
        
        return response()->json([
            'device' => $detection['device'],
            'layout' => $layout,
            'breakpoint' => $this->getBreakpoint($width),
        ]);
    }

    public function statistics(Request $request)
    {
        $stats = [
            'total_screens' => ScreenLog::count(),
            'by_device' => ScreenLog::selectRaw('user_agent, COUNT(*) as count')
                ->groupBy('user_agent')
                ->get(),
            'by_orientation' => ScreenLog::selectRaw('orientation, COUNT(*) as count')
                ->groupBy('orientation')
                ->pluck('count', 'orientation'),
            'avg_width' => ScreenLog::avg('screen_width'),
            'avg_height' => ScreenLog::avg('screen_height'),
        ];
        
        return response()->json($stats);
    }

    protected function getBreakpoint($width)
    {
        if ($width < 768) return 'mobile';
        if ($width < 1024) return 'tablet';
        if ($width < 1440) return 'desktop';
        return 'large';
    }
}