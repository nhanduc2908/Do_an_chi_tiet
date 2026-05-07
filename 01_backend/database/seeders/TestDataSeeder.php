<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoginHistory;
use App\Models\AuditLog;
use App\Models\ScreenLog;
use App\Models\DeviceConnection;
use App\Models\User;
use App\Models\SecurityViolation;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        // Login history (200 records)
        for ($i = 0; $i < 200; $i++) {
            LoginHistory::create([
                'user_id' => $users->random()->id,
                'ip_address' => '192.168.1.' . rand(1, 255),
                'status' => rand(0, 1) ? 'success' : 'failed',
                'login_at' => now()->subDays(rand(0, 90)),
                'device_name' => ['iPhone', 'Android Phone', 'Windows Laptop', 'MacBook'][rand(0, 3)],
            ]);
        }

        // Audit logs (300 records)
        $eventTypes = ['login', 'logout', 'create', 'update', 'delete', 'export', 'import', 'approve', 'reject'];
        for ($i = 0; $i < 300; $i++) {
            AuditLog::create([
                'user_id' => $users->random()->id,
                'event_type' => $eventTypes[rand(0, count($eventTypes) - 1)],
                'description' => 'Người dùng thực hiện hành động ' . $eventTypes[rand(0, count($eventTypes) - 1)],
                'ip_address' => '192.168.1.' . rand(1, 255),
                'created_at' => now()->subDays(rand(0, 90)),
            ]);
        }

        // Screen logs (100 records)
        $sizes = [[1366, 768], [1920, 1080], [2560, 1440], [3840, 2160]];
        for ($i = 0; $i < 100; $i++) {
            $size = $sizes[rand(0, 3)];
            ScreenLog::create([
                'user_id' => $users->random()->id,
                'screen_width' => $size[0],
                'screen_height' => $size[1],
                'orientation' => rand(0, 1) ? 'portrait' : 'landscape',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }

        // Device connections (50 records)
        $deviceTypes = ['web', 'android', 'ios', 'desktop'];
        for ($i = 0; $i < 50; $i++) {
            DeviceConnection::create([
                'user_id' => $users->random()->id,
                'device_id' => 'DEV_' . strtoupper(uniqid()),
                'device_name' => ['iPhone 14', 'Samsung Galaxy', 'Windows Laptop', 'MacBook Pro'][rand(0, 3)],
                'device_type' => $deviceTypes[rand(0, 3)],
                'is_connected' => rand(0, 1),
                'last_connected_at' => now()->subDays(rand(0, 7)),
                'last_sync_at' => now()->subDays(rand(0, 7)),
            ]);
        }

        // Security violations (50 records)
        $violationTypes = ['brute_force', 'suspicious_login', 'sql_injection', 'xss', 'unauthorized_access'];
        $severities = ['critical', 'high', 'medium', 'low'];
        for ($i = 0; $i < 50; $i++) {
            SecurityViolation::create([
                'user_id' => $users->random()->id,
                'type' => $violationTypes[rand(0, 4)],
                'severity' => $severities[rand(0, 3)],
                'description' => 'Phát hiện hành vi đáng ngờ từ IP ' . rand(1, 255) . '.' . rand(1, 255),
                'ip_address' => rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255),
                'resolved_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                'created_at' => now()->subDays(rand(0, 90)),
            ]);
        }
    }
}