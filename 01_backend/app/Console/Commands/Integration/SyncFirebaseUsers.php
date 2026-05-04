<?php

namespace App\Console\Commands\Integration;

use App\Services\Firebase\FirebaseAuthService;
use Illuminate\Console\Command;

class SyncFirebaseUsers extends Command
{
    protected $signature = 'firebase:sync-users';
    protected $description = 'Đồng bộ người dùng với Firebase';

    public function handle(FirebaseAuthService $firebaseAuth)
    {
        $this->info('🔄 Đang đồng bộ người dùng với Firebase...');
        
        $count = $firebaseAuth->syncUsers();
        
        $this->info("✅ Đã đồng bộ {$count} người dùng với Firebase");
    }
}