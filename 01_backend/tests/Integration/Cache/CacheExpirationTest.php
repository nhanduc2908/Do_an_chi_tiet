<?php

namespace Tests\Integration\Cache;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CacheExpirationTest extends TestCase
{
    public function test_cache_expires_after_ttl()
    {
        Cache::put('expiring_key', 'value', 1);
        
        $this->assertEquals('value', Cache::get('expiring_key'));
        
        $this->travel(2)->seconds();
        
        $this->assertNull(Cache::get('expiring_key'));
    }

    public function test_cache_with_forever()
    {
        Cache::forever('forever_key', 'forever_value');
        
        $this->assertEquals('forever_value', Cache::get('forever_key'));
        
        Cache::forget('forever_key');
        $this->assertNull(Cache::get('forever_key'));
    }

    public function test_cache_add_only_if_not_exists()
    {
        Cache::add('unique_key', 'first_value', 60);
        Cache::add('unique_key', 'second_value', 60);
        
        $this->assertEquals('first_value', Cache::get('unique_key'));
    }
}