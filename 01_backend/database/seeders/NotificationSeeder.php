<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        $notifications = [
            ['type' => 'info', 'title' => 'Chào mừng', 'content' => 'Chào mừng bạn đến với hệ thống đánh giá bảo mật', 'priority' => 'low'],
            ['type' => 'warning', 'title' => 'Cập nhật bảo mật', 'content' => 'Có bản cập nhật bảo mật mới, vui lòng cập nhật', 'priority' => 'high'],
            ['type' => 'success', 'title' => 'Đánh giá hoàn tất', 'content' => 'Đánh giá của bạn đã được gửi thành công', 'priority' => 'medium'],
            ['type' => 'error', 'title' => 'Lỗi hệ thống', 'content' => 'Phát hiện lỗi cần xử lý', 'priority' => 'critical'],
            ['type' => 'info', 'title' => 'Báo cáo mới', 'content' => 'Báo cáo đánh giá đã sẵn sàng để tải xuống', 'priority' => 'medium'],
        ];

        foreach ($users as $user) {
            foreach ($notifications as $notif) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $notif['type'],
                    'title' => $notif['title'],
                    'content' => $notif['content'],
                    'priority' => $notif['priority'],
                    'is_read' => rand(0, 1),
                ]);
            }
        }
    }
}