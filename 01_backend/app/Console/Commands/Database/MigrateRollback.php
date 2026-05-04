<?php

namespace App\Console\Commands\Database;

use Illuminate\Console\Command;

class MigrateRollback extends Command
{
    protected $signature = 'migrate:rollback-all {--step=5}';
    protected $description = 'Rollback migrations';

    public function handle()
    {
        $step = $this->option('step');
        $this->call('migrate:rollback', ['--step' => $step]);
        
        $this->info("✅ Đã rollback {$step} bước migration");
    }
}