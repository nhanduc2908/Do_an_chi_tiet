<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;

class SeedTestData extends Command
{
    protected $signature = 'seed:test {--count=100}';
    protected $description = 'Tạo dữ liệu test cho hệ thống';

    public function handle()
    {
        $count = $this->option('count');
        $this->info("📊 Đang tạo {$count} dữ liệu test...");
        
        $this->call('db:seed', ['--class' => 'TestDataSeeder']);
        
        $this->info("✅ Đã tạo {$count} dữ liệu test!");
    }
}