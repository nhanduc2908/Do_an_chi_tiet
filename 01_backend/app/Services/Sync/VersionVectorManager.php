<?php

namespace App\Services\Sync;

class VersionVectorManager
{
    public function increment(string $key, int $userId): array 
    { 
        return ['key' => $key, 'version' => time()]; 
    }

    public function getVector(int $userId): array 
    { 
        return ['user' => $userId, 'version' => time()]; 
    }

    public function compare(array $v1, array $v2): bool 
    { 
        return $v1['version'] >= $v2['version']; 
    }
}