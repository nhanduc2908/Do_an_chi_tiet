<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_connection_is_working()
    {
        $result = DB::select('SELECT 1 as connection_test');
        $this->assertEquals(1, $result[0]->connection_test);
    }

    public function test_multiple_connections()
    {
        $connections = ['mysql'];
        foreach ($connections as $connection) {
            $result = DB::connection($connection)->select('SELECT 1');
            $this->assertNotEmpty($result);
        }
    }

    public function test_connection_pooling()
    {
        $startTime = microtime(true);
        
        for ($i = 0; $i < 100; $i++) {
            DB::select('SELECT 1');
        }
        
        $duration = microtime(true) - $startTime;
        $this->assertLessThan(5, $duration);
    }
}