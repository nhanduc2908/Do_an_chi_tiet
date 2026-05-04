<?php

namespace App\Console\Commands\Security;

use App\Services\Security\VulnerabilityScanner;
use Illuminate\Console\Command;

class RunSecurityScan extends Command
{
    protected $signature = 'security:scan {--full : Quét toàn bộ}';
    protected $description = 'Chạy quét bảo mật hệ thống';

    public function handle(VulnerabilityScanner $scanner)
    {
        $this->info('🛡️ Đang quét bảo mật hệ thống...');
        
        $result = $scanner->scan($this->option('full'));
        
        $this->info("✅ Quét hoàn tất! Phát hiện {$result['vulnerabilities']} lỗ hổng.");
    }
}