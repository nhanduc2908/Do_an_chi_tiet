<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AppInstall extends Command
{
    protected $signature = 'app:install {--fresh : Xóa database trước khi cài đặt}';
    protected $description = 'Cài đặt ứng dụng lần đầu';

    public function handle()
    {
        $this->info('🚀 Bắt đầu cài đặt hệ thống...');

        if ($this->option('fresh')) {
            $this->call('migrate:fresh', ['--force' => true]);
        } else {
            $this->call('migrate', ['--force' => true]);
        }

        $this->call('db:seed', ['--force' => true]);
        $this->call('storage:link');
        $this->call('optimize:clear');
        
        $this->info('✅ Cài đặt hoàn tất!');
    }
}