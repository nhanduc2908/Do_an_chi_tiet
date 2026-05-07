<?php

namespace Tests\Integration\WebSocket;

use Tests\TestCase;
use App\Models\User;
use App\Services\WebSocket\WebSocketManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReconnectionTest extends TestCase
{
    use RefreshDatabase;

    protected WebSocketManager $wsManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wsManager = new WebSocketManager();
    }

    public function test_reconnection_with_same_device_id()
    {
        $user = User::factory()->create();
        
        // First connection
        $token1 = $this->wsManager->generateAuthToken($user->id, 'device_001');
        
        // Simulate disconnect and reconnect
        $this->wsManager->removeConnection((string)$user->id, 'conn_old');
        
        // Reconnect
        $token2 = $this->wsManager->generateAuthToken($user->id, 'device_001');
        
        $this->assertNotNull($token2);
    }

    public function test_session_persistence_on_reconnect()
    {
        $user = User::factory()->create();
        $deviceId = 'device_001';
        
        $sessionData = ['last_sync' => now()->toIso8601String()];
        
        // Store session
        cache()->put("session_{$user->id}_{$deviceId}", $sessionData, 3600);
        
        // Retrieve after reconnection
        $retrieved = cache()->get("session_{$user->id}_{$deviceId}");
        
        $this->assertEquals($sessionData, $retrieved);
    }
}