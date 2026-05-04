<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupS3Upload extends Command
{
    protected $signature = 'backup:upload-s3 {--file=}';
    protected $description = 'Upload file backup lên S3';

    public function handle()
    {
        $file = $this->option('file');
        $localPath = storage_path("app/backups/{$file}");
        
        if (!file_exists($localPath)) {
            $this->error("❌ File không tồn tại");
            return 1;
        }

        Storage::disk('s3')->put("backups/{$file}", file_get_contents($localPath));
        
        $this->info("✅ File {$file} đã được upload lên S3");
    }
}