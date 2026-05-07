<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_device()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/register', [
                             'device_id' => 'device_123',
                             'device_name' => 'iPhone 14',
                             'device_type' => 'ios',
                             'push_token' => 'push_token_123',
                         ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_devices', [
            'device_id' => 'device_123',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_view_registered_devices()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $user->devices()->create([
            'device_id' => 'device_123',
            'device_name' => 'iPhone 14',
            'device_type' => 'ios',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/device');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function test_user_can_unregister_device()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $device = $user->devices()->create([
            'device_id' => 'device_123',
            'device_name' => 'iPhone 14',
            'device_type' => 'ios',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/device/{$device->device_id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_devices', ['device_id' => 'device_123']);
    }

    public function test_device_heartbeat_updates_last_connected()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $device = $user->devices()->create([
            'device_id' => 'device_123',
            'device_name' => 'iPhone 14',
            'device_type' => 'ios',
            'last_connected_at' => now()->subDay(),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/device/{$device->device_id}/heartbeat");

        $response->assertStatus(200);
        
        $device->refresh();
        $this->assertTrue($device->last_connected_at->diffInMinutes(now()) < 1);
    }
}