<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceConnected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $deviceId;
    public string $deviceName;
    public string $deviceType;

    public function __construct(User $user, string $deviceId, string $deviceName, string $deviceType)
    {
        $this->user = $user;
        $this->deviceId = $deviceId;
        $this->deviceName = $deviceName;
        $this->deviceType = $deviceType;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->user->id}"),
            new Channel("device-events"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'device.connected';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'device_id' => $this->deviceId,
            'device_name' => $this->deviceName,
            'device_type' => $this->deviceType,
            'connected_at' => now()->toIso8601String(),
        ];
    }
}