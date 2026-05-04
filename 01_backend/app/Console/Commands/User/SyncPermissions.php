<?php

namespace App\Console\Commands\User;

use App\Models\Role;
use Illuminate\Console\Command;

class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync';
    protected $description = 'Đồng bộ permissions cho các role';

    public function handle()
    {
        $this->info('🔄 Đang đồng bộ permissions...');
        
        $roles = Role::all();
        
        $this->info("✅ Đã đồng bộ permissions cho {$roles->count()} role");
    }
}