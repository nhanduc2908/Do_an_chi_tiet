<?php

namespace App\Console\Commands\AI;

use App\Services\AI\TrainingPipeline;
use Illuminate\Console\Command;

class TrainAIModels extends Command
{
    protected $signature = 'ai:train {--model=all}';
    protected $description = 'Huấn luyện model AI';

    public function handle(TrainingPipeline $pipeline)
    {
        $model = $this->option('model');
        $this->info('🤖 Đang huấn luyện model AI...');
        
        $result = $pipeline->train($model);
        
        $this->info("✅ Đã huấn luyện model {$model} với độ chính xác {$result['accuracy']}%");
    }
}