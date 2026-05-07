<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_insert_performance()
    {
        $users = [];
        for ($i = 0; $i < 1000; $i++) {
            $users[] = [
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => bcrypt('password'),
            ];
        }
        
        $startTime = microtime(true);
        User::insert($users);
        $duration = microtime(true) - $startTime;
        
        $this->assertLessThan(2, $duration);
        $this->assertEquals(1000, User::count());
    }

    public function test_query_with_index_performance()
    {
        Evaluation::factory()->count(10000)->create();
        
        $startTime = microtime(true);
        $result = Evaluation::where('status', 'approved')->get();
        $duration = microtime(true) - $startTime;
        
        $this->assertLessThan(0.5, $duration);
    }

    public function test_join_query_performance()
    {
        $user = User::factory()->create();
        Evaluation::factory()->count(100)->create(['user_id' => $user->id]);
        
        $startTime = microtime(true);
        $result = Evaluation::with('user')->get();
        $duration = microtime(true) - $startTime;
        
        $this->assertLessThan(1, $duration);
    }
}