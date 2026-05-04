<?php

namespace App\Console\Commands\Database;

use Illuminate\Console\Command;

class MigrateFresh extends Command
{
    protected $signature = 'migrate:fresh-all';
    protected $description = 'Xóa và chạy lại tất cả migrations';

    public function handle()
    {
        $this->call('migrate:fresh', ['--force' => true]);
        $this->call('db:seed', ['--force' => true]);
        
        $this->info('✅ Database đã được làm mới!');
    }
}