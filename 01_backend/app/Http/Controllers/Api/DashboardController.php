<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Domain;
use App\Models\User;
use App\Models\LoginHistory;
use App\Models\SecurityViolation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        $stats = [
            'total_evaluations' => Evaluation::count(),
            'total_domains' => Domain::count(),
            'total_users' => User::count(),
            'avg_score' => round(Evaluation::avg('percentage'), 2),
            'pending_approvals' => Evaluation::where('status', 'submitted')->count(),
            'completed' => Evaluation::where('status', 'approved')->count(),
            'high_risk' => Evaluation::where('percentage', '<', 50)->count(),
        ];

        $domainScores = Domain::with('evaluations')
            ->get()
            ->map(fn($domain) => [
                'name' => $domain->name,
                'avg_score' => round($domain->evaluations->avg('percentage'), 2),
            ]);

        $recentActivities = LoginHistory::with('user')
            ->orderBy('login_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($log) => [
                'user' => $log->user?->name,
                'action' => 'login',
                'status' => $log->status,
                'time' => $log->login_at,
            ]);

        $alerts = SecurityViolation::whereNull('resolved_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $roleSpecific = $this->getRoleSpecificData($user);

        return response()->json([
            'stats' => $stats,
            'domain_scores' => $domainScores,
            'recent_activities' => $recentActivities,
            'alerts' => $alerts,
            'role_specific' => $roleSpecific,
        ]);
    }

    protected function getRoleSpecificData($user)
    {
        return match($user->role) {
            'admin' => [
                'system_health' => $this->getSystemHealth(),
                'user_growth' => User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('date')
                    ->get(),
            ],
            'manager' => [
                'team_performance' => Evaluation::where('company_id', $user->company_id)
                    ->selectRaw('user_id, AVG(percentage) as avg_score')
                    ->with('user:id,name')
                    ->groupBy('user_id')
                    ->get(),
                'pending_approvals' => Evaluation::where('company_id', $user->company_id)
                    ->where('status', 'submitted')
                    ->count(),
            ],
            'ciso' => [
                'risk_summary' => [
                    'critical' => Evaluation::where('percentage', '<', 30)->count(),
                    'high' => Evaluation::whereBetween('percentage', [30, 50])->count(),
                    'medium' => Evaluation::whereBetween('percentage', [50, 70])->count(),
                    'low' => Evaluation::where('percentage', '>=', 70)->count(),
                ],
                'compliance_rate' => round(Evaluation::where('percentage', '>=', 80)->count() / max(Evaluation::count(), 1) * 100, 2),
            ],
            default => [
                'my_evaluations' => Evaluation::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
                'my_avg_score' => round(Evaluation::where('user_id', $user->id)->avg('percentage'), 2),
            ],
        };
    }

    protected function getSystemHealth(): array
    {
        return [
            'database_size' => '50 MB',
            'cache_hit_rate' => '85.5%',
            'queue_size' => 0,
            'failed_jobs' => 0,
            'last_backup' => now()->subDay(),
        ];
    }
}