<?php

namespace Tests\Feature\Device;

use Tests\TestCase;
use App\Models\User;
use App\Models\DeviceConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PushNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_push_notification_to_device()
    {
        $user = User::factory()->create();
        $device = DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'TEST_DEVICE',
            'push_token' => 'test_push_token_123',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/push/send', [
                             'device_id' => 'TEST_DEVICE',
                             'title' => 'Security Alert',
                             'body' => 'New vulnerability detected',
                             'data' => ['type' => 'vulnerability', 'id' => 1],
                         ]);
        
        $response->assertStatus(200);
    }

    public function test_push_notification_fails_for_device_without_token()
    {
        $user = User::factory()->create();
        $device = DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'TEST_DEVICE',
            'push_token' => null,
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/push/send', [
                             'device_id' => 'TEST_DEVICE',
                             'title' => 'Test',
                             'body' => 'Test message',
                         ]);
        
        $response->assertStatus(400);
    }

    public function test_broadcast_push_to_all_user_devices()
    {
        $user = User::factory()->create();
        DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'DEVICE_1',
            'push_token' => 'token_1',
        ]);
        DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'DEVICE_2',
            'push_token' => 'token_2',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/device/push/broadcast', [
                             'title' => 'System Update',
                             'body' => 'New features available',
                         ]);
        
        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('sent_count'));
    }

    public function test_update_push_token()
    {
        $user = User::factory()->create();
        $device = DeviceConnection::factory()->create([
            'user_id' => $user->id,
            'device_id' => 'TEST_DEVICE',
            'push_token' => 'old_token',
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/device/{$device->device_id}/push-token", [
                             'push_token' => 'new_token_456',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('device_connections', [
            'device_id' => 'TEST_DEVICE',
            'push_token' => 'new_token_456',
        ]);
    }
}