<?php

namespace App\Console\Commands\Queue;

use App\Services\Queue\DeadLetterQueue;
use Illuminate\Console\Command;

class ProcessDeadLetterQueue extends Command
{
    protected $signature = 'queue:dead-letter-process';
    protected $description = 'Xử lý dead letter queue';

    public function handle(DeadLetterQueue $deadLetterQueue)
    {
        $this->info('⚠️ Đang xử lý dead letter queue...');
        
        $processed = $deadLetterQueue->process();
        
        $this->info("✅ Đã xử lý {$processed} items trong dead letter queue");
    }
}