<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\LoginHistory;
use App\Models\DataChangeLog;
use App\Models\SecurityViolation;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,auditor');
    }

    public function index(Request $request)
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

    public function loginHistory(Request $request)
    {
        $query = LoginHistory::with('user');
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $history = $query->orderBy('login_at', 'desc')->paginate(50);
        return response()->json($history);
    }

    public function dataChanges(Request $request)
    {
        $query = DataChangeLog::with('user');
        
        if ($request->has('table_name')) {
            $query->where('table_name', $request->table_name);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $changes = $query->orderBy('created_at', 'desc')->paginate(50);
        return response()->json($changes);
    }

    public function securityViolations(Request $request)
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
        
        $violations = $query->orderBy('created_at', 'desc')->paginate(50);
        return response()->json($violations);
    }

    public function dashboard(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = now()->subDays($days);
        
        return response()->json([
            'total_logs' => AuditLog::where('created_at', '>=', $startDate)->count(),
            'total_logins' => LoginHistory::where('login_at', '>=', $startDate)->count(),
            'failed_logins' => LoginHistory::where('status', 'failed')->where('login_at', '>=', $startDate)->count(),
            'data_changes' => DataChangeLog::where('created_at', '>=', $startDate)->count(),
            'security_violations' => SecurityViolation::where('created_at', '>=', $startDate)->count(),
        ]);
    }
}