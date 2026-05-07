<?php

namespace Tests\Feature\Device;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceHeartbeatTest extends TestCase
{
    use RefreshDatabase;

    public function test_device_heartbeat_updates_connection()
    {
        $user = User::factory()->create();
        $device = DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'TEST_DEVICE',
            'last_connected_at' => now()->subHour(),
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/device/{$device->device_id}/heartbeat");
        
        $response->assertStatus(200);
        
        $device->refresh();
        $this->assertTrue($device->last_connected_at->diffInMinutes(now()) < 1);
    }

    public function test_device_without_heartbeat_marked_offline()
    {
        $user = User::factory()->create();
        $device = DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'TEST_DEVICE',
            'is_connected' => true,
            'last_connected_at' => now()->subHours(2),
        ]);
        
        // Run cleanup job
        \Artisan::call('device:cleanup');
        
        $device->refresh();
        $this->assertFalse($device->is_connected);
    }
}