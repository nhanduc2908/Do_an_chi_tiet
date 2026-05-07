<?php

namespace Tests\Feature\Device;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_device_sync_updates_last_sync()
    {
        $user = User::factory()->create();
        $device = DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'TEST_DEVICE',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/sync/push', [
                 'device_id' => 'TEST_DEVICE',
                 'items' => [],
             ]);
        
        $device->refresh();
        $this->assertNotNull($device->last_sync_at);
    }

    public function test_device_sync_version_tracking()
    {
        $user = User::factory()->create();
        $device = DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'TEST_DEVICE',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'TEST_DEVICE',
                         ]);
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('version_vector', $response->json());
    }
}