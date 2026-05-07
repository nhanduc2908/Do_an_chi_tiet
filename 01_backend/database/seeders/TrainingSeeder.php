<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training;
use App\Models\User;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $trainings = [
            [
                'title' => 'An toàn thông tin cơ bản',
                'description' => 'Khóa học cung cấp kiến thức cơ bản về an toàn thông tin',
                'type' => 'course',
                'duration' => 120,
                'status' => 'active',
            ],
            [
                'title' => 'Phòng chống lừa đảo trực tuyến',
                'description' => 'Nhận biết và phòng tránh các hình thức lừa đảo',
                'type' => 'webinar',
                'duration' => 60,
                'status' => 'active',
            ],
            [
                'title' => 'Bảo mật dữ liệu doanh nghiệp',
                'description' => 'Các biện pháp bảo vệ dữ liệu quan trọng',
                'type' => 'course',
                'duration' => 180,
                'status' => 'active',
            ],
            [
                'title' => 'Ứng phó sự cố bảo mật',
                'description' => 'Quy trình xử lý khi xảy ra sự cố',
                'type' => 'workshop',
                'duration' => 240,
                'status' => 'active',
            ],
            [
                'title' => 'Mã hóa và bảo mật dữ liệu',
                'description' => 'Kỹ thuật mã hóa nâng cao',
                'type' => 'course',
                'duration' => 150,
                'status' => 'active',
            ],
        ];

        foreach ($trainings as $training) {
            Training::create([
                'title' => $training['title'],
                'description' => $training['description'],
                'type' => $training['type'],
                'duration' => $training['duration'],
                'status' => $training['status'],
                'created_by' => $admin->id,
            ]);
        }
    }
}