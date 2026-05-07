<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedOut implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $deviceId;
    public ?string $sessionId;

    public function __construct(User $user, string $deviceId, ?string $sessionId = null)
    {
        $this->user = $user;
        $this->deviceId = $deviceId;
        $this->sessionId = $sessionId;
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
        return 'user.logged-out';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'device_id' => $this->deviceId,
            'logout_time' => now()->toIso8601String(),
        ];
    }
}