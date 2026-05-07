<?php

namespace App\Services\Sync;

class ConflictResolver
{
    public function resolve(array $serverData, array $clientData, string $strategy = 'server'): array
    {
        return $strategy === 'server' ? $serverData : $clientData;
    }
}