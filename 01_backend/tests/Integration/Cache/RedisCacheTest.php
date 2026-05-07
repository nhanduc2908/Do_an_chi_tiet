<?php

namespace Tests\Integration\Cache;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RedisCacheTest extends TestCase
{
    public function test_cache_put_and_get()
    {
        Cache::put('test_key', 'test_value', 60);
        $value = Cache::get('test_key');
        
        $this->assertEquals('test_value', $value);
    }

    public function test_cache_has()
    {
        Cache::put('existing_key', 'value', 60);
        
        $this->assertTrue(Cache::has('existing_key'));
        $this->assertFalse(Cache::has('nonexistent_key'));
    }

    public function test_cache_forget()
    {
        Cache::put('temp_key', 'value', 60);
        Cache::forget('temp_key');
        
        $this->assertFalse(Cache::has('temp_key'));
    }

    public function test_cache_increment()
    {
        Cache::put('counter', 0, 60);
        Cache::increment('counter');
        Cache::increment('counter', 5);
        
        $this->assertEquals(6, Cache::get('counter'));
    }

    public function test_cache_remember()
    {
        $value = Cache::remember('remembered_key', 60, function () {
            return 'computed_value';
        });
        
        $this->assertEquals('computed_value', $value);
        $this->assertTrue(Cache::has('remembered_key'));
    }
}