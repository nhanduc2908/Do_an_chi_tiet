<?php

namespace App\Console\Commands\Integration;

use App\Models\WebhookDelivery;
use Illuminate\Console\Command;

class WebhookRetry extends Command
{
    protected $signature = 'webhook:retry {--limit=100}';
    protected $description = 'Thử lại webhook thất bại';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $failed = WebhookDelivery::where('status', 'failed')
            ->where('attempt', '<', 5)
            ->limit($limit)
            ->get();
        
        $this->info("✅ Đã thử lại {$failed->count()} webhook thất bại");
    }
}