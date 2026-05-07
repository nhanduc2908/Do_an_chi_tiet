<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Domain;
use App\Models\User;
use App\Models\Report;
use App\Models\LoginHistory;
use App\Models\SecurityViolation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        // Thống kê cơ bản
        $stats = [
            'total_evaluations' => Evaluation::count(),
            'total_domains' => Domain::count(),
            'total_users' => User::count(),
            'total_reports' => Report::count(),
        ];

        // Thống kê theo role
        $roleStats = [
            'avg_score' => round(Evaluation::avg('percentage'), 2),
            'pending_approvals' => Evaluation::where('status', 'submitted')->count(),
            'completed' => Evaluation::where('status', 'approved')->count(),
            'high_risk' => Evaluation::where('percentage', '<', 50)->count(),
        ];

        // Biểu đồ điểm theo domain
        $domainScores = Domain::with('evaluations')
            ->get()
            ->map(fn($domain) => [
                'name' => $domain->name,
                'avg_score' => round($domain->evaluations->avg('percentage'), 2),
            ]);

        // Xu hướng đánh giá 30 ngày gần nhất
        $trend = Evaluation::selectRaw('DATE(created_at) as date, COUNT(*) as total, AVG(percentage) as avg_score')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Hoạt động gần đây
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

        // Cảnh báo bảo mật
        $alerts = SecurityViolation::whereNull('resolved_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Thông báo chưa đọc
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Dữ liệu riêng theo role
        $roleSpecific = $this->getRoleSpecificData($user);

        return view('dashboard.index', compact(
            'stats',
            'roleStats',
            'domainScores',
            'trend',
            'recentActivities',
            'alerts',
            'roleSpecific',
            'role',
            'unreadNotifications'
        ));
    }

    public function refresh(Request $request)
    {
        $user = $request->user();

        $data = [
            'stats' => [
                'total_evaluations' => Evaluation::count(),
                'pending_approvals' => Evaluation::where('status', 'submitted')->count(),
                'avg_score' => round(Evaluation::avg('percentage'), 2),
            ],
            'recent_activities' => LoginHistory::with('user')
                ->orderBy('login_at', 'desc')
                ->limit(5)
                ->get()
                ->map(fn($log) => [
                    'user' => $log->user?->name,
                    'time' => $log->login_at->diffForHumans(),
                ]),
            'notifications' => Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->limit(5)
                ->get(),
        ];

        return response()->json($data);
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
                'active_users' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
            ],
            'manager' => [
                'team_performance' => Evaluation::where('company_id', $user->company_id)
                    ->selectRaw('user_id, AVG(percentage) as avg_score, COUNT(*) as total')
                    ->with('user:id,name')
                    ->groupBy('user_id')
                    ->get(),
                'pending_approvals' => Evaluation::where('company_id', $user->company_id)
                    ->where('status', 'submitted')
                    ->count(),
                'team_members' => User::where('company_id', $user->company_id)->count(),
            ],
            'ciso' => [
                'risk_summary' => [
                    'critical' => Evaluation::where('percentage', '<', 30)->count(),
                    'high' => Evaluation::whereBetween('percentage', [30, 50])->count(),
                    'medium' => Evaluation::whereBetween('percentage', [50, 70])->count(),
                    'low' => Evaluation::where('percentage', '>=', 70)->count(),
                ],
                'compliance_rate' => round(Evaluation::where('percentage', '>=', 80)->count() / max(Evaluation::count(), 1) * 100, 2),
                'top_issues' => $this->getTopIssues(),
            ],
            'auditor' => [
                'audit_findings' => SecurityViolation::where('severity', 'high')
                    ->whereNull('resolved_at')
                    ->count(),
                'compliance_score' => round(Evaluation::avg('percentage'), 2),
                'audit_trail_count' => \App\Models\AuditLog::count(),
            ],
            default => [
                'my_evaluations' => Evaluation::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
                'my_avg_score' => round(Evaluation::where('user_id', $user->id)->avg('percentage'), 2),
                'pending_tasks' => Evaluation::where('user_id', $user->id)
                    ->where('status', 'draft')
                    ->count(),
            ],
        };
    }

    protected function getSystemHealth(): array
    {
        return [
            'database_size' => $this->getDatabaseSize(),
            'cache_hit_rate' => $this->getCacheHitRate(),
            'queue_size' => DB::table('jobs')->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
            'last_backup' => \App\Models\BackupHistory::latest()->first()?->created_at,
            'server_load' => sys_getloadavg()[0] ?? 0,
        ];
    }

    protected function getDatabaseSize(): string
    {
        $size = DB::select('SELECT SUM(data_length + index_length) as size 
            FROM information_schema.tables 
            WHERE table_schema = ?', [env('DB_DATABASE')])[0]->size ?? 0;
        
        return round($size / 1024 / 1024, 2) . ' MB';
    }

    protected function getCacheHitRate(): float
    {
        // Giả lập, thực tế cần lấy từ Redis INFO
        return 85.5;
    }

    protected function getTopIssues(): array
    {
        return Evaluation::select('domain_id', DB::raw('AVG(percentage) as avg_score'))
            ->with('domain')
            ->groupBy('domain_id')
            ->orderBy('avg_score', 'asc')
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'domain' => $item->domain->name,
                'score' => round($item->avg_score, 2),
            ]);
    }
}