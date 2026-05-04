<?php

namespace App\Console\Commands\Security;

use App\Models\Certificate;
use Illuminate\Console\Command;

class CheckSSLExpiry extends Command
{
    protected $signature = 'ssl:check {--days=30}';
    protected $description = 'Kiểm tra SSL sắp hết hạn';

    public function handle()
    {
        $days = $this->option('days');
        $expiringSoon = Certificate::where('expires_at', '<=', now()->addDays($days))
            ->where('expires_at', '>', now())
            ->get();
        
        foreach ($expiringSoon as $cert) {
            $this->warn("⚠️ Chứng chỉ {$cert->domain} sẽ hết hạn vào {$cert->expires_at}");
        }
        
        if ($expiringSoon->isEmpty()) {
            $this->info("✅ Không có chứng chỉ nào sắp hết hạn trong {$days} ngày");
        }
    }
}