<?php

namespace App\Console\Commands\Report;

use App\Services\Report\ReportGenerator;
use Illuminate\Console\Command;

class GenerateReports extends Command
{
    protected $signature = 'reports:generate {--type=all}';
    protected $description = 'Tạo báo cáo hệ thống';

    public function handle(ReportGenerator $generator)
    {
        $type = $this->option('type');
        $this->info('📊 Đang tạo báo cáo...');
        
        $result = $generator->generate($type);
        
        $this->info("✅ Đã tạo {$result['count']} báo cáo");
    }
}