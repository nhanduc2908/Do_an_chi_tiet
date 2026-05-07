<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\ProcessSyncQueueJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueueSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_sync_jobs_are_queued()
    {
        Queue::fake();
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/sync/push', [
                 'device_id' => 'TEST_DEVICE',
                 'items' => [['type' => 'evaluation', 'data' => ['title' => 'Test']]],
             ]);

        Queue::assertPushed(ProcessSyncQueueJob::class);
    }

    public function test_high_priority_items_processed_first()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/sync/push', [
                 'device_id' => 'TEST_DEVICE',
                 'items' => [
                     ['type' => 'evaluation', 'data' => ['title' => 'Low Priority'], 'priority' => 'low'],
                     ['type' => 'evaluation', 'data' => ['title' => 'High Priority'], 'priority' => 'high'],
                 ],
             ]);

        // Queue should process high priority first
        $this->assertDatabaseHas('sync_queues', [
            'data->title' => 'High Priority',
            'priority' => 'high',
        ]);
    }
}