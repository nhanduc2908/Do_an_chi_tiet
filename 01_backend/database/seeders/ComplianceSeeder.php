<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComplianceCheck;
use App\Models\User;

class ComplianceSeeder extends Seeder
{
    public function run(): void
    {
        $auditor = User::where('role', 'auditor')->first();
        if (!$auditor) {
            $auditor = User::where('role', 'admin')->first();
        }

        $standards = ['iso27001', 'soc2', 'gdpr', 'hipaa', 'pci_dss'];
        
        $findings = [
            'iso27001' => ['Cần cải thiện kiểm soát truy cập', 'Đánh giá rủi ro chưa đầy đủ'],
            'soc2' => ['Thiếu log giám sát', 'Chưa có kế hoạch DR'],
            'gdpr' => ['Chưa có quy trình xử lý dữ liệu cá nhân', 'Thiếu chính sách thông báo vi phạm'],
            'hipaa' => ['Mã hóa dữ liệu chưa đầy đủ', 'Log truy cập chưa chi tiết'],
            'pci_dss' => ['Chưa thay đổi mật khẩu mặc định', 'Firewall cấu hình chưa tối ưu'],
        ];

        foreach ($standards as $standard) {
            $score = rand(65, 95);
            $status = $score >= 80 ? 'passed' : 'failed';
            
            ComplianceCheck::create([
                'standard' => $standard,
                'status' => $status,
                'score' => $score,
                'findings' => $findings[$standard],
                'recommendations' => ['Cần khắc phục các điểm yếu đã phát hiện'],
                'checked_by' => $auditor->id,
                'checked_at' => now()->subDays(rand(1, 30)),
                'next_check_date' => now()->addMonths(3),
            ]);
        }
    }
}