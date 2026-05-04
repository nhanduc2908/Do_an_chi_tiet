<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;

class RestoreDatabase extends Command
{
    protected $signature = 'db:restore {--file= : File backup cần phục hồi}';
    protected $description = 'Phục hồi database từ file backup';

    public function handle()
    {
        $file = $this->option('file');
        $path = storage_path("app/backups/{$file}");
        
        if (!file_exists($path)) {
            $this->error("❌ File backup không tồn tại: {$path}");
            return 1;
        }

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $command = sprintf(
            'mysql -u%s -p%s %s < %s',
            $username,
            $password,
            $database,
            $path
        );

        exec($command);
        
        $this->info("✅ Database đã được phục hồi từ: {$file}");
    }
}