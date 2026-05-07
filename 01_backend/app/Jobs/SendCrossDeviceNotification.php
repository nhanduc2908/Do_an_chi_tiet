<?php

namespace App\Jobs;

use App\Models\DeviceConnection;
use App\Models\Notification;
use App\Services\WebSocket\MessageBroadcaster;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCrossDeviceNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public int $userId;
    public string $fromDeviceId;
    public string $toDeviceId;
    public string $title;
    public string $body;
    public array $data;
    public int $tries = 5;
    public array $backoff = [10, 30, 60, 120, 300];

    public function __construct(int $userId, string $fromDeviceId, string $toDeviceId, string $title, string $body, array $data = [])
    {
        $this->userId = $userId;
        $this->fromDeviceId = $fromDeviceId;
        $this->toDeviceId = $toDeviceId;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    public function handle(MessageBroadcaster $broadcaster): void
    {
        try {
            $targetDevice = DeviceConnection::where('device_id', $this->toDeviceId)
                ->where('is_connected', true)
                ->first();

            if (!$targetDevice) {
                Log::warning("Target device {$this->toDeviceId} not found or disconnected");
                $this->storeOfflineNotification();
                return;
            }

            $broadcaster->sendToDevice($this->toDeviceId, [
                'type' => 'cross_device_notification',
                'from_device_id' => $this->fromDeviceId,
                'title' => $this->title,
                'body' => $this->body,
                'data' => $this->data,
                'timestamp' => now()->toIso8601String(),
            ]);

            Notification::create([
                'user_id' => $this->userId,
                'type' => 'cross_device',
                'title' => $this->title,
                'content' => $this->body,
                'data' => array_merge($this->data, [
                    'from_device_id' => $this->fromDeviceId,
                    'to_device_id' => $this->toDeviceId,
                ]),
                'priority' => 'high',
            ]);

            Log::info("Cross-device notification sent from {$this->fromDeviceId} to {$this->toDeviceId}");

        } catch (\Exception $e) {
            Log::error("Failed to send cross-device notification: {$e->getMessage()}");
            throw $e;
        }
    }

    protected function storeOfflineNotification(): void
    {
        Notification::create([
            'user_id' => $this->userId,
            'type' => 'cross_device_offline',
            'title' => $this->title,
            'content' => $this->body,
            'data' => array_merge($this->data, [
                'from_device_id' => $this->fromDeviceId,
                'to_device_id' => $this->toDeviceId,
                'is_offline' => true,
            ]),
            'priority' => 'medium',
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendCrossDeviceNotification failed permanently: {$exception->getMessage()}");
        
        Notification::create([
            'user_id' => $this->userId,
            'type' => 'cross_device_failed',
            'title' => 'Gửi thông báo thất bại',
            'content' => "Không thể gửi thông báo đến thiết bị {$this->toDeviceId}",
            'data' => ['error' => $exception->getMessage()],
            'priority' => 'low',
        ]);
    }
}