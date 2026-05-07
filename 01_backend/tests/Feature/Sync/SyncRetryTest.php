<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\SyncQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SyncRetryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_failed_sync_items_are_retried()
    {
        $failedItem = SyncQueue::create([
            'user_id' => $this->user->id,
            'type' => 'evaluation',
            'data' => ['title' => 'Failed Item'],
            'status' => 'failed',
            'attempts' => 1,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/retry', [
                             'device_id' => 'TEST_DEVICE',
                             'item_ids' => [$failedItem->id],
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sync_queues', [
            'id' => $failedItem->id,
            'attempts' => 2,
            'status' => 'pending',
        ]);
    }

    public function test_max_retries_exceeded_moves_to_dead_letter()
    {
        $failedItem = SyncQueue::create([
            'user_id' => $this->user->id,
            'type' => 'evaluation',
            'data' => ['title' => 'Failed Item'],
            'status' => 'failed',
            'attempts' => 5, // Max retries
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/retry', [
                             'device_id' => 'TEST_DEVICE',
                             'item_ids' => [$failedItem->id],
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sync_queues', [
            'id' => $failedItem->id,
            'status' => 'dead_letter',
        ]);
    }

    public function test_retry_all_failed_items()
    {
        SyncQueue::create([
            'user_id' => $this->user->id,
            'type' => 'evaluation',
            'data' => ['title' => 'Failed 1'],
            'status' => 'failed',
            'attempts' => 1,
        ]);
        
        SyncQueue::create([
            'user_id' => $this->user->id,
            'type' => 'evaluation',
            'data' => ['title' => 'Failed 2'],
            'status' => 'failed',
            'attempts' => 1,
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/retry-all', [
                             'device_id' => 'TEST_DEVICE',
                         ]);

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('retried'));
    }
}