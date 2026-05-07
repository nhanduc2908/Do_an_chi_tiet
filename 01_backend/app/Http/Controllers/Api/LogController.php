<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\LoginHistory;
use App\Models\SystemLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,auditor');
    }

    public function audit(Request $request)
    {
        $query = AuditLog::with('user');
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        if ($request->has('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        return response()->json($logs);
    }

    public function system(Request $request)
    {
        $query = SystemLog::query();
        
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }
        if ($request->has('channel')) {
            $query->where('channel', $request->channel);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        return response()->json($logs);
    }

    public function clear(Request $request)
    {
        $request->validate(['days' => 'required|integer|min:1']);
        
        $deleted = SystemLog::where('created_at', '<', now()->subDays($request->days))->delete();
        
        return response()->json(['message' => "Deleted {$deleted} logs"]);
    }

    public function levels()
    {
        $levels = SystemLog::select('level', \DB::raw('count(*) as count'))
            ->groupBy('level')
            ->pluck('count', 'level');
        
        return response()->json($levels);
    }
}