<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $admin = User::where('role', 'admin')->first();

        $incidents = [
            [
                'title' => 'Phát hiện malware trên máy trạm',
                'description' => 'Phát hiện file độc hại trên máy tính phòng IT',
                'severity' => 'high',
                'type' => 'malware',
                'status' => 'investigating',
            ],
            [
                'title' => 'Tấn công phishing vào nhân viên',
                'description' => 'Nhiều nhân viên báo cáo email lừa đảo',
                'severity' => 'medium',
                'type' => 'attack',
                'status' => 'contained',
            ],
            [
                'title' => 'Rò rỉ dữ liệu khách hàng',
                'description' => 'Phát hiện dữ liệu khách hàng bị truy cập trái phép',
                'severity' => 'critical',
                'type' => 'breach',
                'status' => 'open',
            ],
            [
                'title' => 'Cấu hình sai firewall',
                'description' => 'Firewall cấu hình sai gây lỗi kết nối',
                'severity' => 'low',
                'type' => 'misconfiguration',
                'status' => 'resolved',
            ],
            [
                'title' => 'Mất thiết bị chứa dữ liệu',
                'description' => 'Mất laptop chứa dữ liệu nội bộ',
                'severity' => 'high',
                'type' => 'breach',
                'status' => 'investigating',
            ],
        ];

        foreach ($incidents as $incident) {
            Incident::create([
                'title' => $incident['title'],
                'description' => $incident['description'],
                'severity' => $incident['severity'],
                'type' => $incident['type'],
                'status' => $incident['status'],
                'detected_at' => now()->subDays(rand(1, 30)),
                'reported_by' => $admin->id,
                'assigned_to' => $users->random()->id,
            ]);
        }
    }
}