<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class CleanupInactiveUsers extends Command
{
    protected $signature = 'user:cleanup {--days=180}';
    protected $description = 'Xóa người dùng không hoạt động';

    public function handle()
    {
        $days = $this->option('days');
        $date = now()->subDays($days);
        
        $inactiveUsers = User::where('last_login_at', '<', $date)
            ->orWhereNull('last_login_at')
            ->count();
        
        $this->info("✅ Có {$inactiveUsers} người dùng không hoạt động trong {$days} ngày");
    }
}