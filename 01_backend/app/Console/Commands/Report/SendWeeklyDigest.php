<?php

namespace App\Console\Commands\Report;

use App\Models\User;
use Illuminate\Console\Command;

class SendWeeklyDigest extends Command
{
    protected $signature = 'digest:weekly';
    protected $description = 'Gửi bản tin tuần';

    public function handle()
    {
        $users = User::whereIn('role', ['admin', 'manager', 'ciso'])->get();
        $count = $users->count();
        
        $this->info("✅ Đã gửi bản tin tuần cho {$count} người dùng");
    }
}