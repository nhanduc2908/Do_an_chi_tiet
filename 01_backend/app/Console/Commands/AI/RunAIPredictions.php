<?php

namespace App\Console\Commands\AI;

use App\Models\Evaluation;
use App\Services\AI\AIScoringEngine;
use Illuminate\Console\Command;

class RunAIPredictions extends Command
{
    protected $signature = 'ai:predict {--limit=100}';
    protected $description = 'Chạy dự đoán AI cho đánh giá';

    public function handle(AIScoringEngine $aiEngine)
    {
        $limit = $this->option('limit');
        $this->info('🔮 Đang chạy dự đoán AI...');
        
        $evaluations = Evaluation::whereNull('ai_score')->limit($limit)->get();
        
        foreach ($evaluations as $evaluation) {
            $result = $aiEngine->predictScore($evaluation);
            $evaluation->update(['ai_score' => $result['predicted_score']]);
        }
        
        $this->info("✅ Đã chạy dự đoán cho {$evaluations->count()} đánh giá");
    }
}