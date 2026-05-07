<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScreenMirroringStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $sourceDeviceId;
    public string $targetDeviceId;
    public array $screenConfig;

    public function __construct(User $user, string $sourceDeviceId, string $targetDeviceId, array $screenConfig)
    {
        $this->user = $user;
        $this->sourceDeviceId = $sourceDeviceId;
        $this->targetDeviceId = $targetDeviceId;
        $this->screenConfig = $screenConfig;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("device.{$this->targetDeviceId}"),
            new PrivateChannel("user.{$this->user->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'screen.mirroring-started';
    }

    public function broadcastWith(): array
    {
        return [
            'source_device_id' => $this->sourceDeviceId,
            'screen_width' => $this->screenConfig['width'] ?? 0,
            'screen_height' => $this->screenConfig['height'] ?? 0,
            'orientation' => $this->screenConfig['orientation'] ?? 'landscape',
            'started_at' => now()->toIso8601String(),
        ];
    }
}