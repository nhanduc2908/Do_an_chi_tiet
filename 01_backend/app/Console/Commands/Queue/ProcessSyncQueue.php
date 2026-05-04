<?php

namespace App\Console\Commands\Queue;

use App\Services\Sync\QueueManager;
use Illuminate\Console\Command;

class ProcessSyncQueue extends Command
{
    protected $signature = 'queue:sync-process {--limit=100}';
    protected $description = 'Xử lý queue đồng bộ';

    public function handle(QueueManager $queueManager)
    {
        $limit = $this->option('limit');
        $this->info("⏳ Đang xử lý {$limit} items trong sync queue...");
        
        $processed = $queueManager->process($limit);
        
        $this->info("✅ Đã xử lý {$processed} items");
    }
}