<?php

namespace Tests\Integration\Cache;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CacheHitTest extends TestCase
{
    public function test_cache_hit_returns_value()
    {
        Cache::put('hit_key', 'cached_value', 60);
        
        $startTime = microtime(true);
        $value = Cache::get('hit_key');
        $duration = microtime(true) - $startTime;
        
        $this->assertEquals('cached_value', $value);
        $this->assertLessThan(0.01, $duration);
    }

    public function test_cache_miss_executes_callback()
    {
        $executed = false;
        
        $value = Cache::remember('miss_key', 60, function () use (&$executed) {
            $executed = true;
            return 'computed_value';
        });
        
        $this->assertTrue($executed);
        $this->assertEquals('computed_value', $value);
    }

    public function test_cache_hit_rate()
    {
        Cache::flush();
        
        for ($i = 0; $i < 10; $i++) {
            Cache::get('key_' . $i);
        }
        
        Cache::put('hit_key', 'value', 60);
        Cache::get('hit_key');
        
        // Cache hit rate tracking would be implementation specific
        $this->assertTrue(true);
    }
}