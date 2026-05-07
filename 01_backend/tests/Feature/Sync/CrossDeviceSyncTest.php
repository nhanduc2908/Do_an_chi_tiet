<?php

namespace Tests\Feature\Sync;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrossDeviceSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_changes_from_one_device_sync_to_another()
    {
        $device1 = DeviceConnection::factory()->create([
            'user_id' => $this->user->id,
            'device_id' => 'DEVICE_001',
        ]);
        
        $device2 = DeviceConnection::factory()->create([
            'user_id' => $this->user->id,
            'device_id' => 'DEVICE_002',
        ]);
        
        $token = $this->user->createToken('auth_token')->plainTextToken;

        // Push from device 1
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/sync/push', [
                 'device_id' => 'DEVICE_001',
                 'items' => [
                     ['type' => 'evaluation', 'data' => ['title' => 'Cross Device Test']],
                 ],
             ]);
        
        // Pull from device 2
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'DEVICE_002',
                         ]);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('items'));
    }

    public function test_cross_device_session_maintains_consistency()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        // Create cross-device session
        $sessionResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                                ->postJson('/api/sync/cross-device/session', [
                                    'devices' => ['DEVICE_001', 'DEVICE_002'],
                                ]);

        $sessionResponse->assertStatus(201);
        $sessionId = $sessionResponse->json('session_id');
        
        // Send message between devices
        $messageResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                                 ->postJson('/api/sync/cross-device/message', [
                                     'session_id' => $sessionId,
                                     'from_device' => 'DEVICE_001',
                                     'to_device' => 'DEVICE_002',
                                     'payload' => ['type' => 'sync', 'data' => 'test'],
                                 ]);

        $messageResponse->assertStatus(200);
    }
}