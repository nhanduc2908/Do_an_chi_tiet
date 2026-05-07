<?php

namespace Tests\Integration\Cache;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CacheMissTest extends TestCase
{
    public function test_cache_miss_returns_null()
    {
        $value = Cache::get('nonexistent_key');
        
        $this->assertNull($value);
    }

    public function test_cache_miss_returns_default_value()
    {
        $value = Cache::get('nonexistent_key', 'default_value');
        
        $this->assertEquals('default_value', $value);
    }

    public function test_cache_miss_with_callback()
    {
        $callbackExecuted = false;
        
        $value = Cache::get('nonexistent_key', function () use (&$callbackExecuted) {
            $callbackExecuted = true;
            return 'callback_value';
        });
        
        $this->assertTrue($callbackExecuted);
        $this->assertEquals('callback_value', $value);
    }

    public function test_many_cache_misses()
    {
        $startTime = microtime(true);
        
        for ($i = 0; $i < 100; $i++) {
            Cache::get('miss_key_' . $i);
        }
        
        $duration = microtime(true) - $startTime;
        $this->assertLessThan(1, $duration);
    }
}