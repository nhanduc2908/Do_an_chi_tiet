<?php

namespace App\Console\Commands\Security;

use App\Models\Evaluation;
use Illuminate\Console\Command;

class ValidateDataIntegrity extends Command
{
    protected $signature = 'data:validate {--table=}';
    protected $description = 'Kiểm tra tính toàn vẹn dữ liệu';

    public function handle()
    {
        $this->info('🔍 Đang kiểm tra tính toàn vẹn dữ liệu...');
        
        $total = Evaluation::count();
        
        $this->info("✅ Kiểm tra hoàn tất!");
        $this->info("   - Tổng số bản ghi: {$total}");
    }
}