<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CrossDeviceMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $fromDeviceId;
    public string $toDeviceId;
    public string $messageType;
    public array $payload;
    public ?string $sessionId;

    public function __construct(string $fromDeviceId, string $toDeviceId, string $messageType, array $payload, ?string $sessionId = null)
    {
        $this->fromDeviceId = $fromDeviceId;
        $this->toDeviceId = $toDeviceId;
        $this->messageType = $messageType;
        $this->payload = $payload;
        $this->sessionId = $sessionId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("device.{$this->toDeviceId}"),
            new PrivateChannel("session.{$this->sessionId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'cross-device.message';
    }

    public function broadcastWith(): array
    {
        return [
            'from_device_id' => $this->fromDeviceId,
            'message_type' => $this->messageType,
            'payload' => $this->payload,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}