<?php

namespace App\Services\Security;

use App\Models\Notification;
use App\Models\User;

class AlertService
{
    public function sendAlert(string $type, string $message, array $data = [], ?User $user = null): void
    {
        Notification::create([
            'user_id' => $user?->id,
            'type' => $type,
            'title' => $this->getAlertTitle($type),
            'content' => $message,
            'data' => $data,
            'priority' => $this->getAlertPriority($type),
        ]);
    }

    protected function getAlertTitle(string $type): string
    {
        return match($type) {
            'brute_force' => 'Phát hiện tấn công brute force',
            'suspicious_login' => 'Đăng nhập đáng ngờ',
            'data_breach' => 'Rò rỉ dữ liệu',
            default => 'Cảnh báo bảo mật',
        };
    }

    protected function getAlertPriority(string $type): string
    {
        return match($type) {
            'brute_force', 'data_breach' => 'critical',
            'suspicious_login' => 'high',
            default => 'medium',
        };
    }
}