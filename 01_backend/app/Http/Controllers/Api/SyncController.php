<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SyncQueue;
use App\Models\DeviceConnection;
use App\Services\Sync\QueueManager;
use App\Services\Sync\ConflictResolver;
use App\Services\Sync\VersionVectorManager;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    protected QueueManager $queueManager;
    protected ConflictResolver $conflictResolver;
    protected VersionVectorManager $versionManager;

    public function __construct(
        QueueManager $queueManager,
        ConflictResolver $conflictResolver,
        VersionVectorManager $versionManager
    ) {
        $this->queueManager = $queueManager;
        $this->conflictResolver = $conflictResolver;
        $this->versionManager = $versionManager;
    }

    public function pull(Request $request)
    {
        $request->validate([
            'last_sync' => 'nullable|date',
            'device_id' => 'required|string',
            'limit' => 'nullable|integer|min:1|max:1000',
        ]);

        $device = DeviceConnection::where('device_id', $request->device_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $lastSync = $request->last_sync ? new \DateTime($request->last_sync) : null;
        $limit = $request->limit ?? 100;

        // Get changes since last sync
        $changes = $this->getChangesSince($request->user()->id, $lastSync, $limit);
        
        $versionVector = $this->versionManager->getVector($request->user()->id);

        return response()->json([
            'items' => $changes,
            'version_vector' => $versionVector,
            'has_more' => count($changes) >= $limit,
        ]);
    }

    public function push(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'device_id' => 'required|string',
            'version_vector' => 'nullable|array',
        ]);

        $device = DeviceConnection::where('device_id', $request->device_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }

        $conflicts = [];
        $synced = 0;

        foreach ($request->items as $item) {
            $result = $this->processSyncItem($item, $request->user()->id);
            
            if ($result['conflict']) {
                $conflicts[] = $result['conflict'];
            } else {
                $synced++;
            }
        }

        // Update device sync info
        $device->update(['last_sync_at' => now()]);

        // Queue for processing
        $this->queueManager->push('sync', [
            'user_id' => $request->user()->id,
            'device_id' => $request->device_id,
            'items_count' => $synced,
        ]);

        return response()->json([
            'synced' => $synced,
            'conflicts' => $conflicts,
        ]);
    }

    public function resolveConflict(Request $request)
    {
        $request->validate([
            'conflict_id' => 'required|string',
            'resolution' => 'required|in:server,client,manual',
            'data' => 'nullable|array',
        ]);

        $resolved = $this->conflictResolver->resolve(
            $request->conflict_id,
            $request->resolution,
            $request->data
        );

        return response()->json(['resolved' => $resolved]);
    }

    public function status(Request $request)
    {
        $device = DeviceConnection::where('device_id', $request->device_id)
            ->where('user_id', $request->user()->id)
            ->first();

        $pendingSync = SyncQueue::where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->count();

        return response()->json([
            'last_sync' => $device?->last_sync_at,
            'pending_items' => $pendingSync,
            'version_vector' => $this->versionManager->getVector($request->user()->id),
        ]);
    }

    protected function getChangesSince($userId, $lastSync, $limit)
    {
        $query = SyncQueue::where('user_id', $userId)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc');

        if ($lastSync) {
            $query->where('created_at', '>', $lastSync);
        }

        return $query->limit($limit)->get();
    }

    protected function processSyncItem($item, $userId)
    {
        // Process single sync item
        return ['conflict' => null, 'synced' => true];
    }
}