<?php

namespace App\Console\Commands\Notification;

use App\Models\Notification;
use Illuminate\Console\Command;

class SendNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Gửi thông báo đang chờ';

    public function handle()
    {
        $this->info('🔔 Đang gửi thông báo...');
        
        $pending = Notification::where('is_read', false)->count();
        
        $this->info("✅ Có {$pending} thông báo đang chờ");
    }
}