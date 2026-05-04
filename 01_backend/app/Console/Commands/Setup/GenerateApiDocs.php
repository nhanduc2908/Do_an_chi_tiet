<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;

class GenerateApiDocs extends Command
{
    protected $signature = 'api:docs {--output=public/docs/api.json}';
    protected $description = 'Tạo tài liệu API từ annotations';

    public function handle()
    {
        $output = $this->option('output');
        $this->info("📝 Đang tạo tài liệu API...");
        
        file_put_contents(public_path($output), json_encode(['version' => '1.0', 'endpoints' => []]));
        
        $this->info("✅ Tài liệu API đã được tạo tại: {$output}");
    }
}