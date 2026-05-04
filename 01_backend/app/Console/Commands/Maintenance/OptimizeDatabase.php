<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OptimizeDatabase extends Command
{
    protected $signature = 'db:optimize';
    protected $description = 'Tối ưu database';

    public function handle()
    {
        $this->info('🔄 Đang tối ưu database...');
        
        DB::statement('OPTIMIZE TABLE users');
        DB::statement('OPTIMIZE TABLE evaluations');
        DB::statement('OPTIMIZE TABLE evaluation_details');
        DB::statement('OPTIMIZE TABLE audit_logs');
        
        $this->info('✅ Database đã được tối ưu!');
    }
}