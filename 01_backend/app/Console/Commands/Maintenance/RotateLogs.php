<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Command;

class RotateLogs extends Command
{
    protected $signature = 'logs:rotate {--keep=7}';
    protected $description = 'Xoay vòng file log';

    public function handle()
    {
        $keep = $this->option('keep');
        $logPath = storage_path('logs');
        $files = glob($logPath . '/*.log');
        
        $deleted = 0;
        $now = time();
        
        foreach ($files as $file) {
            if ($now - filemtime($file) > $keep * 86400) {
                unlink($file);
                $deleted++;
            }
        }
        
        $this->info("✅ Đã xóa {$deleted} file log cũ hơn {$keep} ngày");
    }
}