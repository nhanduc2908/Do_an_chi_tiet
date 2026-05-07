<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_batch_sync_processes_multiple_items()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $items = [];
        for ($i = 1; $i <= 10; $i++) {
            $items[] = [
                'type' => 'evaluation',
                'data' => [
                    'title' => "Batch Evaluation {$i}",
                    'domain_id' => 1,
                ],
            ];
        }
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/batch', [
                             'device_id' => 'TEST_DEVICE',
                             'items' => $items,
                         ]);

        $response->assertStatus(200);
        $this->assertEquals(10, $response->json('synced'));
        $this->assertDatabaseCount('evaluations', 10);
    }

    public function test_batch_sync_returns_partial_results_on_failure()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $items = [
            ['type' => 'evaluation', 'data' => ['title' => 'Valid Evaluation', 'domain_id' => 1]],
            ['type' => 'evaluation', 'data' => ['title' => '']], // Invalid
            ['type' => 'evaluation', 'data' => ['title' => 'Another Valid', 'domain_id' => 1]],
        ];
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/batch', [
                             'device_id' => 'TEST_DEVICE',
                             'items' => $items,
                         ]);

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('synced'));
        $this->assertEquals(1, $response->json('failed'));
    }
}