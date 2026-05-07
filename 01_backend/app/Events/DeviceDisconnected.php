<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceDisconnected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $deviceId;
    public string $deviceName;

    public function __construct(User $user, string $deviceId, string $deviceName)
    {
        $this->user = $user;
        $this->deviceId = $deviceId;
        $this->deviceName = $deviceName;
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
        return 'device.disconnected';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'device_id' => $this->deviceId,
            'device_name' => $this->deviceName,
            'disconnected_at' => now()->toIso8601String(),
        ];
    }
}