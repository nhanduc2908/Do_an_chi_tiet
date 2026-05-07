<?php

namespace App\Jobs;

use App\Models\DeviceConnection;
use App\Models\Evaluation;
use App\Models\SyncQueue;
use App\Events\SyncCompleted;
use App\Services\Sync\ConflictResolver;
use App\Services\Sync\VersionVectorManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncDeviceDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public int $userId;
    public string $deviceId;
    public array $data;
    public array $versionVector;
    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(int $userId, string $deviceId, array $data, array $versionVector)
    {
        $this->userId = $userId;
        $this->deviceId = $deviceId;
        $this->data = $data;
        $this->versionVector = $versionVector;
    }

    public function handle(ConflictResolver $conflictResolver, VersionVectorManager $versionManager): void
    {
        $startTime = microtime(true);
        $syncedItems = 0;
        $conflicts = [];

        try {
            DB::beginTransaction();

            $device = DeviceConnection::where('user_id', $this->userId)
                ->where('device_id', $this->deviceId)
                ->first();

            if (!$device) {
                throw new \Exception("Device {$this->deviceId} not found");
            }

            foreach ($this->data['items'] as $item) {
                $result = $this->processSyncItem($item, $conflictResolver, $versionManager);
                
                if ($result['synced']) {
                    $syncedItems++;
                }
                
                if ($result['conflict']) {
                    $conflicts[] = $result['conflict'];
                }
            }

            $newVersionVector = $versionManager->increment($this->deviceId, $this->userId);
            
            $device->update([
                'last_sync_at' => now(),
                'sync_version' => json_encode($newVersionVector),
            ]);

            SyncQueue::create([
                'type' => 'device_sync',
                'data' => [
                    'user_id' => $this->userId,
                    'device_id' => $this->deviceId,
                    'synced_items' => $syncedItems,
                    'conflicts' => $conflicts,
                ],
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            DB::commit();

            $duration = (microtime(true) - $startTime) * 1000;
            event(new SyncCompleted($this->userId, $this->deviceId, $syncedItems, (int)$duration));

            Log::info("Device sync completed for user {$this->userId}, device {$this->deviceId}, items: {$syncedItems}, conflicts: " . count($conflicts));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Device sync failed: {$e->getMessage()}");
            
            SyncQueue::create([
                'type' => 'device_sync',
                'data' => [
                    'user_id' => $this->userId,
                    'device_id' => $this->deviceId,
                    'error' => $e->getMessage(),
                ],
                'status' => 'failed',
                'attempts' => $this->attempts,
            ]);
            
            throw $e;
        }
    }

    protected function processSyncItem(array $item, ConflictResolver $conflictResolver, VersionVectorManager $versionManager): array
    {
        $result = ['synced' => false, 'conflict' => null];

        switch ($item['type']) {
            case 'evaluation':
                $result = $this->syncEvaluation($item['data'], $conflictResolver);
                break;
            case 'report':
                $result = $this->syncReport($item['data'], $conflictResolver);
                break;
            case 'profile':
                $result = $this->syncProfile($item['data'], $conflictResolver);
                break;
            default:
                Log::warning("Unknown sync item type: {$item['type']}");
        }

        return $result;
    }

    protected function syncEvaluation(array $data, ConflictResolver $resolver): array
    {
        $existing = Evaluation::where('id', $data['id'] ?? null)
            ->where('user_id', $this->userId)
            ->first();

        if ($existing) {
            if (isset($data['updated_at']) && $existing->updated_at > $data['updated_at']) {
                $resolved = $resolver->resolve($existing->toArray(), $data, 'server');
                return ['synced' => false, 'conflict' => ['type' => 'evaluation', 'id' => $existing->id, 'resolved' => $resolved]];
            }
            
            $existing->update($data);
            return ['synced' => true, 'conflict' => null];
        }

        Evaluation::create(array_merge($data, ['user_id' => $this->userId]));
        return ['synced' => true, 'conflict' => null];
    }

    protected function syncReport(array $data, ConflictResolver $resolver): array
    {
        return ['synced' => true, 'conflict' => null];
    }

    protected function syncProfile(array $data, ConflictResolver $resolver): array
    {
        return ['synced' => true, 'conflict' => null];
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SyncDeviceDataJob failed permanently for user {$this->userId}, device {$this->deviceId}: {$exception->getMessage()}");
        
        Notification::create([
            'user_id' => $this->userId,
            'type' => 'sync_failed',
            'title' => 'Đồng bộ dữ liệu thất bại',
            'content' => 'Có lỗi xảy ra khi đồng bộ dữ liệu giữa các thiết bị. Vui lòng thử lại sau.',
            'priority' => 'medium',
        ]);
    }
}