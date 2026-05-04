<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeOldSessions extends Command
{
    protected $signature = 'session:purge {--hours=24}';
    protected $description = 'Xóa session cũ';

    public function handle()
    {
        $hours = $this->option('hours');
        $expired = now()->subHours($hours)->timestamp;
        
        $deleted = DB::table('sessions')->where('last_activity', '<', $expired)->delete();
        
        $this->info("✅ Đã xóa {$deleted} session cũ hơn {$hours} giờ");
    }
}