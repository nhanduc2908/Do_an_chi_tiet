<?php

namespace App\Console\Commands\Notification;

use App\Models\SecurityViolation;
use Illuminate\Console\Command;

class SendPendingAlerts extends Command
{
    protected $signature = 'alerts:send';
    protected $description = 'Gửi cảnh báo đang chờ';

    public function handle()
    {
        $this->info('⚠️ Đang gửi cảnh báo...');
        
        $pending = SecurityViolation::whereNull('resolved_at')->count();
        
        $this->info("✅ Có {$pending} cảnh báo đang chờ xử lý");
    }
}