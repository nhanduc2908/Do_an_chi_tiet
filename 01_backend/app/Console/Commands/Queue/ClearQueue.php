<?php

namespace App\Console\Commands\Queue;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ClearQueue extends Command
{
    protected $signature = 'queue:clear {--queue=default}';
    protected $description = 'Xóa toàn bộ queue';

    public function handle()
    {
        $queue = $this->option('queue');
        Redis::del("queues:{$queue}");
        
        $this->info("✅ Queue {$queue} đã được xóa");
    }
}