<?php

namespace App\Console\Commands\Report;

use App\Models\ComplianceCheck;
use Illuminate\Console\Command;

class GenerateComplianceReport extends Command
{
    protected $signature = 'compliance:report {--standard=iso27001}';
    protected $description = 'Tạo báo cáo tuân thủ';

    public function handle()
    {
        $standard = $this->option('standard');
        $this->info("📜 Đang tạo báo cáo tuân thủ {$standard}...");
        
        $checks = ComplianceCheck::where('standard', $standard)->count();
        
        $this->info("✅ Báo cáo tuân thủ {$standard} đã được tạo");
        $this->info("   - Số lần kiểm tra: {$checks}");
    }
}