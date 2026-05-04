<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;

class CleanupOldBackups extends Command
{
    protected $signature = 'backup:cleanup {--days=30}';
    protected $description = 'Xóa các file backup cũ';

    public function handle()
    {
        $days = $this->option('days');
        $backupPath = storage_path('app/backups');
        $files = glob($backupPath . '/*.sql');
        
        $deleted = 0;
        $now = time();
        
        foreach ($files as $file) {
            if ($now - filemtime($file) > $days * 86400) {
                unlink($file);
                $deleted++;
            }
        }
        
        $this->info("✅ Đã xóa {$deleted} file backup cũ hơn {$days} ngày");
    }
}