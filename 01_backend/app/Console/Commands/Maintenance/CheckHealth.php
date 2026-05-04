<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CheckHealth extends Command
{
    protected $signature = 'system:health';
    protected $description = 'Kiểm tra sức khỏe hệ thống';

    public function handle()
    {
        $this->info('🔍 Kiểm tra sức khỏe hệ thống...');
        
        try {
            DB::connection()->getPdo();
            $this->info('✅ Database: OK');
        } catch (\Exception $e) {
            $this->error('❌ Database: FAILED');
        }
        
        try {
            Cache::store('redis')->put('health_check', 'ok', 10);
            $this->info('✅ Cache: OK');
        } catch (\Exception $e) {
            $this->error('❌ Cache: FAILED');
        }
        
        $this->info('🏁 Kết thúc kiểm tra!');
    }
}