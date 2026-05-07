<?php

namespace Tests\Integration\WebSocket;

use Tests\TestCase;
use App\Models\User;
use App\Services\WebSocket\WebSocketManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConnectionTest extends TestCase
{
    use RefreshDatabase;

    protected WebSocketManager $wsManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wsManager = new WebSocketManager();
    }

    public function test_user_can_connect_to_websocket()
    {
        $user = User::factory()->create();
        
        $token = $this->wsManager->generateAuthToken($user->id, 'device_001');
        
        $this->assertNotNull($token);
        $this->assertIsString($token);
    }

    public function test_device_connection_can_be_registered()
    {
        $user = User::factory()->create();
        
        $this->wsManager->addConnection((string)$user->id, 'conn_123');
        
        $connections = $this->wsManager->getUserConnections((string)$user->id);
        $this->assertContains('conn_123', $connections);
    }

    public function test_device_can_disconnect()
    {
        $user = User::factory()->create();
        $this->wsManager->addConnection((string)$user->id, 'conn_123');
        $this->wsManager->removeConnection((string)$user->id, 'conn_123');
        
        $connections = $this->wsManager->getUserConnections((string)$user->id);
        $this->assertNotContains('conn_123', $connections);
    }
}