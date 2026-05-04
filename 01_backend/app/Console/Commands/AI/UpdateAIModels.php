<?php

namespace App\Console\Commands\AI;

use App\Services\AI\ModelManager;
use Illuminate\Console\Command;

class UpdateAIModels extends Command
{
    protected $signature = 'ai:update-models';
    protected $description = 'Cập nhật model AI mới';

    public function handle(ModelManager $manager)
    {
        $this->info('🔄 Đang cập nhật model AI...');
        
        $count = $manager->updateModels();
        
        $this->info("✅ Đã cập nhật {$count} model AI");
    }
}