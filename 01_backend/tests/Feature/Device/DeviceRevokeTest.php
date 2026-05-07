<?php

namespace Tests\Feature\Device;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceRevokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_revoke_device()
    {
        $user = User::factory()->create();
        $device = $user->devices()->create([
            'device_id' => 'DEVICE_123',
            'device_name' => 'iPhone 14',
            'device_type' => 'ios',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/device/{$device->device_id}");
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_devices', ['device_id' => 'DEVICE_123']);
    }

    public function test_revoked_device_cannot_sync()
    {
        $user = User::factory()->create();
        $device = $user->devices()->create([
            'device_id' => 'DEVICE_123',
            'device_name' => 'iPhone 14',
            'device_type' => 'ios',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->deleteJson("/api/device/{$device->device_id}");
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/sync/pull', [
                             'device_id' => 'DEVICE_123',
                         ]);
        
        $response->assertStatus(404);
    }
}