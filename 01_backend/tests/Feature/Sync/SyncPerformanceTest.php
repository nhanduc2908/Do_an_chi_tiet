<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SyncPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_sync_handles_large_payloads()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $largeItems = [];
        for ($i = 1; $i <= 500; $i++) {
            $largeItems[] = [
                'type' => 'evaluation',
                'data' => [
                    'title' => "Large Sync Test {$i}",
                    'domain_id' => 1,
                    'notes' => str_repeat('x', 1000),
                ],
            ];
        }
        
        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/batch', [
                             'device_id' => 'TEST_DEVICE',
                             'items' => $largeItems,
                         ]);
        
        $duration = microtime(true) - $startTime;
        
        $response->assertStatus(200);
        $this->assertEquals(500, $response->json('synced'));
        $this->assertLessThan(30, $duration); // Should complete within 30 seconds
    }

    public function test_sync_compresses_data_for_transfer()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $largeData = ['data' => str_repeat('x', 100000)];
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/push', [
                             'device_id' => 'TEST_DEVICE',
                             'items' => [['type' => 'data', 'data' => $largeData]],
                             'compress' => true,
                         ]);

        $response->assertStatus(200);
        $response->assertJson(['compressed' => true]);
    }
}