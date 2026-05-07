<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use App\Models\SyncQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OfflineSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $device;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->device = DeviceConnection::factory()->create([
            'user_id' => $this->user->id,
            'device_id' => 'TEST_DEVICE_001',
        ]);
    }

    public function test_offline_changes_are_queued()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/offline/queue', [
                             'device_id' => 'TEST_DEVICE_001',
                             'items' => [
                                 ['type' => 'evaluation', 'data' => ['title' => 'Offline 1']],
                                 ['type' => 'evaluation', 'data' => ['title' => 'Offline 2']],
                             ],
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('sync_queues', 2);
    }

    public function test_offline_changes_are_synced_when_online()
    {
        // Queue offline changes
        SyncQueue::create([
            'user_id' => $this->user->id,
            'type' => 'evaluation',
            'data' => ['title' => 'Queued Evaluation'],
            'status' => 'pending',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/offline/process', [
                             'device_id' => 'TEST_DEVICE_001',
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sync_queues', ['status' => 'completed']);
        $this->assertDatabaseHas('evaluations', ['title' => 'Queued Evaluation']);
    }
}