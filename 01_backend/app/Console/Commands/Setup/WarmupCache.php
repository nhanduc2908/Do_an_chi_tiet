<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmupCache extends Command
{
    protected $signature = 'cache:warmup';
    protected $description = 'Khởi tạo cache cho hệ thống';

    public function handle()
    {
        $this->info('🔥 Đang khởi tạo cache...');
        
        Cache::remember('system_config', 3600, function() {
            return config('app');
        });
        
        $this->info('✅ Cache đã được khởi tạo!');
    }
}