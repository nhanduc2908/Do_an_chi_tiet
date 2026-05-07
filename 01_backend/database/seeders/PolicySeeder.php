<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Policy;
use App\Models\User;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $policies = [
            [
                'name' => 'Chính sách bảo mật thông tin',
                'content' => 'Nội dung chi tiết về chính sách bảo mật thông tin của tổ chức...',
                'category' => 'security',
                'version' => '1.0',
                'effective_date' => '2025-01-01',
            ],
            [
                'name' => 'Chính sách sử dụng tài nguyên CNTT',
                'content' => 'Quy định về sử dụng máy tính, email, internet...',
                'category' => 'it',
                'version' => '2.0',
                'effective_date' => '2025-01-01',
            ],
            [
                'name' => 'Chính sách ứng phó sự cố',
                'content' => 'Quy trình xử lý khi xảy ra sự cố bảo mật...',
                'category' => 'security',
                'version' => '1.5',
                'effective_date' => '2025-01-01',
            ],
            [
                'name' => 'Chính sách bảo vệ dữ liệu cá nhân',
                'content' => 'Tuân thủ GDPR và quy định về bảo vệ dữ liệu cá nhân...',
                'category' => 'compliance',
                'version' => '1.0',
                'effective_date' => '2025-01-01',
            ],
            [
                'name' => 'Chính sách kiểm soát truy cập',
                'content' => 'Quy định về phân quyền và kiểm soát truy cập...',
                'category' => 'security',
                'version' => '1.2',
                'effective_date' => '2025-01-01',
            ],
        ];

        foreach ($policies as $policy) {
            Policy::create([
                'name' => $policy['name'],
                'content' => $policy['content'],
                'category' => $policy['category'],
                'version' => $policy['version'],
                'effective_date' => $policy['effective_date'],
                'created_by' => $admin->id,
            ]);
        }
    }
}