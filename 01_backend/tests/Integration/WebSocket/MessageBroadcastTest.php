<?php

namespace Tests\Integration\WebSocket;

use Tests\TestCase;
use App\Models\User;
use App\Services\WebSocket\MessageBroadcaster;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageBroadcastTest extends TestCase
{
    use RefreshDatabase;

    protected MessageBroadcaster $broadcaster;

    protected function setUp(): void
    {
        parent::setUp();
        $this->broadcaster = new MessageBroadcaster();
    }

    public function test_send_message_to_device()
    {
        $user = User::factory()->create();
        $deviceId = 'test_device_001';
        
        $result = $this->broadcaster->sendToDevice($deviceId, [
            'type' => 'test',
            'data' => ['message' => 'Hello'],
        ]);
        
        $this->assertTrue($result);
    }

    public function test_broadcast_to_channel()
    {
        $result = $this->broadcaster->broadcast('test-channel', [
            'event' => 'test',
            'data' => ['message' => 'Broadcast'],
        ]);
        
        $this->assertTrue($result);
    }

    public function test_send_to_multiple_devices()
    {
        $devices = ['device_001', 'device_002', 'device_003'];
        
        $result = $this->broadcaster->sendToDevices($devices, [
            'type' => 'bulk',
            'data' => ['message' => 'To multiple devices'],
        ]);
        
        $this->assertTrue($result);
    }
}