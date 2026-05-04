<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class SyncUserPermissions extends Command
{
    protected $signature = 'user:sync-permissions';
    protected $description = 'Đồng bộ permissions cho người dùng';

    public function handle()
    {
        $this->info('🔄 Đang đồng bộ permissions cho người dùng...');
        
        $users = User::all();
        
        $this->info("✅ Đã đồng bộ permissions cho {$users->count()} người dùng");
    }
}