<?php

namespace App\Console\Commands\Security;

use App\Services\Auth\LdapService;
use Illuminate\Console\Command;

class SyncLDAPUsers extends Command
{
    protected $signature = 'ldap:sync';
    protected $description = 'Đồng bộ người dùng từ LDAP';

    public function handle(LdapService $ldap)
    {
        $this->info('🔄 Đang đồng bộ người dùng từ LDAP...');
        
        $count = $ldap->syncUsers();
        
        $this->info("✅ Đã đồng bộ {$count} người dùng từ LDAP");
    }
}