<?php

namespace App\Services\Sync;

use App\Models\SyncQueue;

class QueueManager
{
    public function push(string $type, array $data, string $priority = 'normal'): SyncQueue
    {
        return SyncQueue::create([
            'type' => $type,
            'data' => $data,
            'priority' => $priority,
            'status' => 'pending',
        ]);
    }

    public function process(int $limit = 100): int
    {
        $items = SyncQueue::where('status', 'pending')
            ->orderBy('priority', 'desc')
            ->limit($limit)
            ->get();
        
        $processed = 0;
        foreach ($items as $item) {
            $item->update(['status' => 'processing']);
            // Process item
            $item->update(['status' => 'completed', 'processed_at' => now()]);
            $processed++;
        }
        
        return $processed;
    }
}