<?php

namespace App\Console\Commands\Maintenance;

use App\Models\LoginHistory;
use App\Models\SessionLog;
use App\Models\AuditLog;
use Illuminate\Console\Command;

class CleanupExpiredData extends Command
{
    protected $signature = 'cleanup:expired {--days=90}';
    protected $description = 'Xóa dữ liệu hết hạn';

    public function handle()
    {
        $days = $this->option('days');
        $date = now()->subDays($days);
        
        $loginDeleted = LoginHistory::where('login_at', '<', $date)->delete();
        $sessionDeleted = SessionLog::where('login_at', '<', $date)->delete();
        $auditDeleted = AuditLog::where('created_at', '<', $date)->delete();
        
        $this->info("✅ Đã xóa:");
        $this->info("   - {$loginDeleted} bản ghi login history");
        $this->info("   - {$sessionDeleted} bản ghi session log");
        $this->info("   - {$auditDeleted} bản ghi audit log");
    }
}