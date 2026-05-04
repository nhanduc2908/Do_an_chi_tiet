<?php

namespace App\Console\Commands\Queue;

use Illuminate\Console\Command;

class RetryFailedJobs extends Command
{
    protected $signature = 'queue:retry-all';
    protected $description = 'Thử lại tất cả jobs thất bại';

    public function handle()
    {
        $this->call('queue:retry', ['id' => 'all']);
        $this->info('✅ Đã thử lại tất cả jobs thất bại');
    }
}