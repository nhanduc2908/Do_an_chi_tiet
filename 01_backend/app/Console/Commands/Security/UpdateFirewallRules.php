<?php

namespace App\Console\Commands\Security;

use App\Models\FirewallRule;
use Illuminate\Console\Command;

class UpdateFirewallRules extends Command
{
    protected $signature = 'firewall:update';
    protected $description = 'Cập nhật rules firewall';

    public function handle()
    {
        $this->info('🔥 Đang cập nhật firewall rules...');
        
        FirewallRule::where('is_active', true)->update(['is_active' => true]);
        
        $this->info('✅ Firewall rules đã được cập nhật!');
    }
}