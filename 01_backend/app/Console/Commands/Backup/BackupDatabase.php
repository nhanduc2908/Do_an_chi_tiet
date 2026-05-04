<?php

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--filename= : Tên file backup}';
    protected $description = 'Sao lưu database';

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $filename = $this->option('filename') ?? 'backup_' . date('Ymd_His') . '.sql';
        $path = storage_path("app/backups/{$filename}");

        $command = sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s',
            $host,
            $username,
            $password,
            $database,
            $path
        );

        exec($command);
        
        $this->info("✅ Database đã được sao lưu tại: {$path}");
    }
}