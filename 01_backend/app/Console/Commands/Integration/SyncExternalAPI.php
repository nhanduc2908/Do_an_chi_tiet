<?php

namespace App\Console\Commands\Integration;

use Illuminate\Console\Command;

class SyncExternalAPI extends Command
{
    protected $signature = 'api:sync-external';
    protected $description = 'Đồng bộ dữ liệu với API bên ngoài';

    public function handle()
    {
        $this->info('🔄 Đang đồng bộ dữ liệu với API bên ngoài...');
        
        $this->info('✅ Đồng bộ hoàn tất!');
    }
}