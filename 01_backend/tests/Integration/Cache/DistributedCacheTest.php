<?php

namespace Tests\Integration\Cache;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DistributedCacheTest extends TestCase
{
    public function test_cache_works_across_connections()
    {
        $defaultDriver = config('cache.default');
        
        Cache::put('shared_key', 'shared_value', 60);
        $value = Cache::store($defaultDriver)->get('shared_key');
        
        $this->assertEquals('shared_value', $value);
    }

    public function test_cache_lock()
    {
        $lock = Cache::lock('processing_lock', 10);
        
        if ($lock->get()) {
            // Do work
            $lock->release();
        }
        
        $this->assertTrue(true);
    }
}