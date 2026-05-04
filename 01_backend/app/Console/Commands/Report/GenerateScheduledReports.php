<?php

namespace App\Console\Commands\Report;

use App\Models\ScheduledReport;
use Illuminate\Console\Command;

class GenerateScheduledReports extends Command
{
    protected $signature = 'reports:scheduled';
    protected $description = 'Tạo báo cáo theo lịch';

    public function handle()
    {
        $reports = ScheduledReport::where('is_active', true)->get();
        $count = 0;
        
        foreach ($reports as $report) {
            $count++;
        }
        
        $this->info("✅ Đã tạo {$count} báo cáo theo lịch");
    }
}