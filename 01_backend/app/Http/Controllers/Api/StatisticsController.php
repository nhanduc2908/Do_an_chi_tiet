<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\User;
use App\Models\Domain;
use App\Models\LoginHistory;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function overview(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $stats = [
            'evaluations' => [
                'total' => Evaluation::count(),
                'this_period' => Evaluation::where('created_at', '>=', $startDate)->count(),
                'avg_score' => round(Evaluation::avg('percentage'), 2),
                'trend' => $this->getEvaluationTrend($startDate),
            ],
            'users' => [
                'total' => User::count(),
                'active' => User::where('last_login_at', '>=', $startDate)->count(),
                'new_this_period' => User::where('created_at', '>=', $startDate)->count(),
            ],
            'domains' => [
                'total' => Domain::count(),
                'avg_score_by_domain' => Domain::with('evaluations')
                    ->get()
                    ->map(fn($d) => [
                        'name' => $d->name,
                        'avg_score' => round($d->evaluations->avg('percentage'), 2),
                    ]),
            ],
            'login_stats' => [
                'total_logins' => LoginHistory::count(),
                'success_rate' => round(LoginHistory::where('status', 'success')->count() / max(LoginHistory::count(), 1) * 100, 2),
                'failed_attempts' => LoginHistory::where('status', 'failed')->count(),
            ],
        ];
        
        return response()->json($stats);
    }

    public function evaluationTrend(Request $request)
    {
        $days = $request->get('days', 30);
        $trend = Evaluation::selectRaw('DATE(created_at) as date, COUNT(*) as count, AVG(percentage) as avg_score')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return response()->json($trend);
    }

    public function userActivity(Request $request)
    {
        $days = $request->get('days', 30);
        
        $activity = [
            'daily_active_users' => LoginHistory::selectRaw('DATE(login_at) as date, COUNT(DISTINCT user_id) as count')
                ->where('login_at', '>=', now()->subDays($days))
                ->groupBy('date')
                ->get(),
            'top_users' => LoginHistory::select('user_id', \DB::raw('COUNT(*) as login_count'))
                ->with('user:id,name')
                ->where('login_at', '>=', now()->subDays($days))
                ->groupBy('user_id')
                ->orderBy('login_count', 'desc')
                ->limit(10)
                ->get(),
        ];
        
        return response()->json($activity);
    }

    public function domainPerformance()
    {
        $performance = Domain::withCount('evaluations')
            ->withAvg('evaluations', 'percentage')
            ->get()
            ->map(fn($d) => [
                'id' => $d->id,
                'name' => $d->name,
                'evaluation_count' => $d->evaluations_count,
                'avg_score' => round($d->evaluations_avg_percentage ?? 0, 2),
            ]);
        
        return response()->json($performance);
    }

    protected function getStartDate($period)
    {
        return match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };
    }

    protected function getEvaluationTrend($startDate)
    {
        $weeks = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subWeeks($i);
            $weeks[] = [
                'week' => $date->format('W/Y'),
                'count' => Evaluation::whereBetween('created_at', [$date->startOfWeek(), $date->endOfWeek()])->count(),
            ];
        }
        return $weeks;
    }
}