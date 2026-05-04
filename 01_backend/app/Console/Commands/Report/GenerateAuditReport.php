<?php

namespace App\Console\Commands\Report;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class GenerateAuditReport extends Command
{
    protected $signature = 'audit:report {--date-from=} {--date-to=}';
    protected $description = 'Tạo báo cáo kiểm toán';

    public function handle()
    {
        $this->info('📋 Đang tạo báo cáo kiểm toán...');
        
        $totalLogs = AuditLog::count();
        
        $this->info("✅ Báo cáo kiểm toán đã được tạo");
        $this->info("   - Tổng số logs: {$totalLogs}");
    }
}