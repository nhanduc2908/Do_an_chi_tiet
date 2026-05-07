<?php

namespace App\Services\WebSocket;

class DeviceConnectionManager
{
    protected array $deviceConnections = [];

    public function register(string $userId, string $deviceId, string $connectionId): void
    {
        $this->deviceConnections[$deviceId] = [
            'user_id' => $userId,
            'connection_id' => $connectionId,
            'connected_at' => now(),
        ];
    }

    public function unregister(string $deviceId): void
    {
        unset($this->deviceConnections[$deviceId]);
    }

    public function getDeviceConnection(string $deviceId): ?array
    {
        return $this->deviceConnections[$deviceId] ?? null;
    }

    public function getDevicesByUser(string $userId): array
    {
        return array_filter($this->deviceConnections, fn($d) => $d['user_id'] === $userId);
    }
}