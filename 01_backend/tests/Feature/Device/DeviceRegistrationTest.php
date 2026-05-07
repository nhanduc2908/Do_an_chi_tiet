<?php

namespace Tests\Feature\Device;

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
                             'device_id' => 'DEVICE_123',
                             'device_name' => 'iPhone 14',
                             'device_type' => 'ios',
                             'push_token' => 'push_token_123',
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('user_devices', [
            'device_id' => 'DEVICE_123',
            'user_id' => $user->id,
        ]);
    }

    public function test_cannot_register_duplicate_device()
    {
        $user = User::factory()->create();
        $user->devices()->create([
            'device_id' => 'DEVICE_123',
            'device_name' => 'iPhone 14',
            'device_type' => 'ios',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/register', [
                             'device_id' => 'DEVICE_123',
                             'device_name' => 'iPhone 14',
                             'device_type' => 'ios',
                         ]);
        
        $response->assertStatus(422);
    }

    public function test_user_can_view_registered_devices()
    {
        $user = User::factory()->create();
        $user->devices()->create([
            'device_id' => 'DEVICE_123',
            'device_name' => 'iPhone 14',
            'device_type' => 'ios',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/device');
        
        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
    }
}