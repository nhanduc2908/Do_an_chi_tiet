<?php

namespace App\Services\Sync;

class CrossDeviceSyncService
{
    public function sync(string $userId, array $data, string $deviceId): array 
    { 
        return ['synced' => true, 'items' => count($data)]; 
    }
}