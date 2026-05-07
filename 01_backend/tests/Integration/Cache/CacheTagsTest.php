<?php

namespace Tests\Integration\Cache;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CacheTagsTest extends TestCase
{
    public function test_cache_tags()
    {
        if (!method_exists(Cache::store(), 'tags')) {
            $this->markTestSkipped('Cache tags not supported');
        }
        
        Cache::tags(['users', 'active'])->put('user_1', 'active_user', 60);
        Cache::tags(['users'])->put('user_2', 'inactive_user', 60);
        
        $activeUser = Cache::tags(['users', 'active'])->get('user_1');
        $inactiveUser = Cache::tags(['users'])->get('user_2');
        
        $this->assertEquals('active_user', $activeUser);
        $this->assertEquals('inactive_user', $inactiveUser);
    }

    public function test_cache_tags_flush()
    {
        if (!method_exists(Cache::store(), 'tags')) {
            $this->markTestSkipped('Cache tags not supported');
        }
        
        Cache::tags(['temp'])->put('temp_key', 'temp_value', 60);
        Cache::tags(['temp'])->flush();
        
        $value = Cache::tags(['temp'])->get('temp_key');
        $this->assertNull($value);
    }
}