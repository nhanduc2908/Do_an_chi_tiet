<?php

namespace Tests\Integration\WebSocket;

use Tests\TestCase;
use App\Models\User;
use App\Services\WebSocket\WebSocketManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected WebSocketManager $wsManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wsManager = new WebSocketManager();
    }

    public function test_websocket_auth_with_valid_token()
    {
        $user = User::factory()->create();
        $token = $this->wsManager->generateAuthToken($user->id, 'device_001');
        
        $response = $this->postJson('/api/websocket/auth', [
            'token' => $token,
            'device_id' => 'device_001',
        ]);
        
        $response->assertStatus(200);
    }

    public function test_websocket_auth_with_invalid_token()
    {
        $response = $this->postJson('/api/websocket/auth', [
            'token' => 'invalid_token',
            'device_id' => 'device_001',
        ]);
        
        $response->assertStatus(401);
    }

    public function test_websocket_auth_requires_device_id()
    {
        $user = User::factory()->create();
        $token = $this->wsManager->generateAuthToken($user->id, 'device_001');
        
        $response = $this->postJson('/api/websocket/auth', [
            'token' => $token,
        ]);
        
        $response->assertStatus(422);
    }
}