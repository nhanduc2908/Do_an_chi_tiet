<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Version;

class VersionSeeder extends Seeder
{
    public function run(): void
    {
        $versions = [
            ['code' => 'v1_sme', 'name' => 'SME', 'description' => 'Dành cho doanh nghiệp nhỏ', 'max_users' => 10, 'max_evaluations' => 50, 'price' => 0, 'features' => ['basic_evaluation', 'basic_report'], 'is_active' => true],
            ['code' => 'v2_midmarket', 'name' => 'Mid-market', 'description' => 'Dành cho doanh nghiệp vừa', 'max_users' => 50, 'max_evaluations' => 200, 'price' => 500, 'features' => ['advanced_scoring', 'team_evaluation'], 'is_active' => true],
            ['code' => 'v3_enterprise', 'name' => 'Enterprise', 'description' => 'Dành cho tập đoàn lớn', 'max_users' => 200, 'max_evaluations' => 1000, 'price' => 2000, 'features' => ['real_time_sync', 'ai_suggestions'], 'is_active' => true],
            ['code' => 'v4_finance', 'name' => 'Finance', 'description' => 'Dành cho ngân hàng, tài chính', 'max_users' => 500, 'max_evaluations' => 5000, 'price' => 5000, 'features' => ['compliance_check', 'risk_matrix'], 'is_active' => true],
            ['code' => 'v5_government', 'name' => 'Government', 'description' => 'Dành cho chính phủ', 'max_users' => 9999, 'max_evaluations' => 99999, 'price' => 10000, 'features' => ['dark_web_monitor', 'threat_intel'], 'is_active' => true],
        ];

        foreach ($versions as $version) {
            Version::updateOrCreate(['code' => $version['code']], $version);
        }
    }
}