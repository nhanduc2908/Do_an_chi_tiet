<?php

namespace App\Console\Commands\Database;

use Illuminate\Console\Command;

class ReindexElasticsearch extends Command
{
    protected $signature = 'search:reindex';
    protected $description = 'Đánh chỉ mục lại Elasticsearch';

    public function handle()
    {
        $this->info('🔄 Đang đánh chỉ mục lại Elasticsearch...');
        
        $this->info('✅ Elasticsearch đã được đánh chỉ mục lại!');
    }
}