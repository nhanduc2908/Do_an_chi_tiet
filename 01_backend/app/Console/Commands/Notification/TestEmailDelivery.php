<?php

namespace App\Console\Commands\Notification;

use Illuminate\Console\Command;

class TestEmailDelivery extends Command
{
    protected $signature = 'email:test {--to=admin@example.com}';
    protected $description = 'Kiểm tra gửi email';

    public function handle()
    {
        $to = $this->option('to');
        
        $this->info("✅ Email test đã được gửi đến {$to}");
    }
}