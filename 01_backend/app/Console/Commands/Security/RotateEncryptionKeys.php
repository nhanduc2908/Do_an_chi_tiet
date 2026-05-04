<?php

namespace App\Console\Commands\Security;

use App\Services\Security\KeyRotationService;
use Illuminate\Console\Command;

class RotateEncryptionKeys extends Command
{
    protected $signature = 'keys:rotate';
    protected $description = 'Xoay vòng khóa mã hóa';

    public function handle(KeyRotationService $keyRotation)
    {
        $this->info('🔐 Đang xoay vòng khóa mã hóa...');
        
        $keyRotation->rotateKeys();
        
        $this->info('✅ Khóa mã hóa đã được xoay vòng thành công!');
    }
}