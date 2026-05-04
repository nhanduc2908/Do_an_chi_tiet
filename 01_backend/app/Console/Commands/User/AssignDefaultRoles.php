<?php

namespace App\Console\Commands\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AssignDefaultRoles extends Command
{
    protected $signature = 'roles:assign-default';
    protected $description = 'Gán role mặc định cho người dùng';

    public function handle()
    {
        $this->info('👥 Đang gán role mặc định...');
        
        $users = User::whereNull('role')->get();
        
        foreach ($users as $user) {
            $user->update(['role' => 'dev']);
        }
        
        $this->info("✅ Đã gán role mặc định cho {$users->count()} người dùng");
    }
}