<?php

namespace Tests\Feature\Device;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrossDeviceSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_cross_device_session()
    {
        $user = User::factory()->create();
        $device1 = $user->devices()->create([
            'device_id' => 'DEVICE_001',
            'device_name' => 'iPhone',
            'device_type' => 'ios',
        ]);
        $device2 = $user->devices()->create([
            'device_id' => 'DEVICE_002',
            'device_name' => 'iPad',
            'device_type' => 'ios',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/session', [
                             'devices' => ['DEVICE_001', 'DEVICE_002'],
                         ]);
        
        $response->assertStatus(201)
                 ->assertJsonStructure(['session_id', 'session_token']);
    }

    public function test_send_message_between_devices()
    {
        $user = User::factory()->create();
        $device1 = $user->devices()->create([
            'device_id' => 'DEVICE_001',
            'device_name' => 'iPhone',
            'device_type' => 'ios',
        ]);
        $device2 = $user->devices()->create([
            'device_id' => 'DEVICE_002',
            'device_name' => 'iPad',
            'device_type' => 'ios',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $sessionResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                                 ->postJson('/api/device/session', [
                                     'devices' => ['DEVICE_001', 'DEVICE_002'],
                                 ]);
        
        $sessionId = $sessionResponse->json('session_id');
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/session/message', [
                             'session_id' => $sessionId,
                             'from_device' => 'DEVICE_001',
                             'to_device' => 'DEVICE_002',
                             'payload' => ['type' => 'notification', 'data' => 'Hello'],
                         ]);
        
        $response->assertStatus(200);
    }

    public function test_end_cross_device_session()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $sessionResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                                 ->postJson('/api/device/session', [
                                     'devices' => ['DEVICE_001', 'DEVICE_002'],
                                 ]);
        
        $sessionId = $sessionResponse->json('session_id');
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/device/session/{$sessionId}");
        
        $response->assertStatus(200);
        
        // Verify session is ended
        $this->assertDatabaseMissing('cross_device_sessions', [
            'session_token' => $sessionId,
            'active' => true,
        ]);
    }
}