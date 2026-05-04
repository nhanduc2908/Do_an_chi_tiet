<?php

namespace App\Console\Commands\Report;

use App\Models\Evaluation;
use Illuminate\Console\Command;

class GenerateMetrics extends Command
{
    protected $signature = 'metrics:generate';
    protected $description = 'Tạo metrics hệ thống';

    public function handle()
    {
        $this->info('📈 Đang thu thập metrics...');
        
        $totalEvaluations = Evaluation::count();
        $avgScore = Evaluation::avg('percentage');
        
        $metrics = [
            ['Metric' => 'Tổng số đánh giá', 'Value' => $totalEvaluations],
            ['Metric' => 'Điểm trung bình', 'Value' => round($avgScore, 2) . '%'],
        ];
        
        $this->info("✅ Metrics đã được tạo");
        $this->table(['Metric', 'Value'], $metrics);
    }
}