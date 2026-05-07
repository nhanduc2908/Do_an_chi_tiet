<?php

namespace App\Services\WebSocket;

class MessageBroadcaster
{
    protected WebSocketManager $wsManager;
    protected DeviceConnectionManager $deviceManager;

    public function __construct()
    {
        $this->wsManager = app(WebSocketManager::class);
        $this->deviceManager = app(DeviceConnectionManager::class);
    }

    public function broadcast(string $channel, array $message): void
    {
        // Broadcast to channel
    }

    public function sendToDevice(string $deviceId, array $message): void
    {
        $device = $this->deviceManager->getDeviceConnection($deviceId);
        if ($device) {
            $this->wsManager->sendToUser($device['user_id'], $message);
        }
    }

    public function sendToUser(string $userId, array $message): void
    {
        $this->wsManager->sendToUser($userId, $message);
    }
}