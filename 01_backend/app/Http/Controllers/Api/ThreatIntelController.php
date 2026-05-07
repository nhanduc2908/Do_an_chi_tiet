<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThreatIntel;
use App\Models\DarkWebMonitor;
use App\Services\AI\ThreatIntelligenceAI;
use Illuminate\Http\Request;

class ThreatIntelController extends Controller
{
    protected ThreatIntelligenceAI $threatAI;

    public function __construct(ThreatIntelligenceAI $threatAI)
    {
        $this->threatAI = $threatAI;
    }

    public function indicators(Request $request)
    {
        $query = ThreatIntel::where('is_active', true);
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }
        
        $indicators = $query->orderBy('last_seen', 'desc')->paginate(20);
        return response()->json($indicators);
    }

    public function checkIndicator(Request $request)
    {
        $request->validate([
            'indicator' => 'required|string',
        ]);

        $result = $this->threatAI->searchIoC($request->indicator);
        
        return response()->json($result);
    }

    public function darkWeb(Request $request)
    {
        $query = DarkWebMonitor::orderBy('detected_at', 'desc');
        
        if ($request->has('keyword')) {
            $query->where('keyword', 'like', "%{$request->keyword}%");
        }
        
        $results = $query->paginate(20);
        return response()->json($results);
    }

    public function addDarkWebKeyword(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|unique:dark_web_monitors',
        ]);

        $monitor = DarkWebMonitor::create([
            'keyword' => $request->keyword,
            'source' => 'manual',
            'is_reviewed' => false,
        ]);

        return response()->json($monitor, 201);
    }

    public function reviewDarkWeb($id)
    {
        $item = DarkWebMonitor::findOrFail($id);
        $item->update(['is_reviewed' => true]);
        return response()->json(['message' => 'Marked as reviewed']);
    }

    public function analyze($indicator)
    {
        $analysis = $this->threatAI->analyzeThreat($indicator);
        return response()->json($analysis);
    }

    public function dashboard()
    {
        $stats = [
            'total_indicators' => ThreatIntel::count(),
            'active_threats' => ThreatIntel::where('is_active', true)->count(),
            'by_severity' => ThreatIntel::selectRaw('severity, COUNT(*) as count')
                ->groupBy('severity')
                ->pluck('count', 'severity'),
            'dark_web_alerts' => DarkWebMonitor::where('is_reviewed', false)->count(),
            'recent_indicators' => ThreatIntel::orderBy('created_at', 'desc')->limit(10)->get(),
        ];
        
        return response()->json($stats);
    }
}