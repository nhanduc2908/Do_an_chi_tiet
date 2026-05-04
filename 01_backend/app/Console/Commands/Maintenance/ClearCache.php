<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearCache extends Command
{
    protected $signature = 'cache:clear-all';
    protected $description = 'Xóa toàn bộ cache hệ thống';

    public function handle()
    {
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        $this->call('event:clear');
        $this->call('optimize:clear');
        
        $this->info('✅ Toàn bộ cache đã được xóa!');
    }
}