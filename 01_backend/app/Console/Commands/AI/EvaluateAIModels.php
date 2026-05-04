<?php

namespace App\Console\Commands\AI;

use App\Services\AI\ModelManager;
use Illuminate\Console\Command;

class EvaluateAIModels extends Command
{
    protected $signature = 'ai:evaluate';
    protected $description = 'Đánh giá hiệu suất model AI';

    public function handle(ModelManager $manager)
    {
        $this->info('📊 Đang đánh giá model AI...');
        
        $results = $manager->evaluateAll();
        
        $this->table(['Model', 'Accuracy', 'Precision', 'Recall'], $results);
    }
}