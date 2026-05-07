<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use App\Models\SyncQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SyncStatusTest extends TestCase
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

    public function test_user_can_get_sync_status()
    {
        // Create pending sync items
        SyncQueue::create([
            'user_id' => $this->user->id,
            'type' => 'evaluation',
            'data' => ['title' => 'Pending'],
            'status' => 'pending',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/sync/status', [
                             'device_id' => 'TEST_DEVICE_001',
                         ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'last_sync',
                     'pending_items',
                     'version_vector',
                 ]);
    }

    public function test_sync_status_shows_pending_count()
    {
        SyncQueue::create([
            'user_id' => $this->user->id,
            'type' => 'evaluation',
            'data' => ['title' => 'Pending 1'],
            'status' => 'pending',
        ]);
        
        SyncQueue::create([
            'user_id' => $this->user->id,
            'type' => 'evaluation',
            'data' => ['title' => 'Pending 2'],
            'status' => 'pending',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/sync/status', [
                             'device_id' => 'TEST_DEVICE_001',
                         ]);

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('pending_items'));
    }
}