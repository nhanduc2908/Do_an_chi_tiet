<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $deviceId;
    public string $ipAddress;

    public function __construct(User $user, string $deviceId, string $ipAddress)
    {
        $this->user = $user;
        $this->deviceId = $deviceId;
        $this->ipAddress = $ipAddress;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("user.{$this->user->id}"),
            new Channel("login-events"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.logged-in';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'role' => $this->user->role,
            'device_id' => $this->deviceId,
            'ip_address' => $this->ipAddress,
            'login_time' => now()->toIso8601String(),
        ];
    }
}