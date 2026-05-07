<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domain;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            ['code' => 'SEC001', 'name' => 'Quản trị an ninh', 'name_en' => 'Security Governance', 'weight' => 5],
            ['code' => 'SEC002', 'name' => 'Kiểm soát truy cập', 'name_en' => 'Access Control', 'weight' => 5],
            ['code' => 'SEC003', 'name' => 'Mã hóa dữ liệu', 'name_en' => 'Cryptography', 'weight' => 5],
            ['code' => 'SEC004', 'name' => 'An ninh mạng', 'name_en' => 'Network Security', 'weight' => 5],
            ['code' => 'SEC005', 'name' => 'An ninh ứng dụng', 'name_en' => 'Application Security', 'weight' => 5],
            ['code' => 'SEC006', 'name' => 'Bảo vệ dữ liệu', 'name_en' => 'Data Protection', 'weight' => 5],
            ['code' => 'SEC007', 'name' => 'Phản ứng sự cố', 'name_en' => 'Incident Response', 'weight' => 5],
            ['code' => 'SEC008', 'name' => 'Liên tục kinh doanh', 'name_en' => 'Business Continuity', 'weight' => 5],
            ['code' => 'SEC009', 'name' => 'Tuân thủ pháp lý', 'name_en' => 'Compliance', 'weight' => 5],
            ['code' => 'SEC010', 'name' => 'An ninh vật lý', 'name_en' => 'Physical Security', 'weight' => 5],
            ['code' => 'SEC011', 'name' => 'An ninh nhân sự', 'name_en' => 'Human Resources Security', 'weight' => 5],
            ['code' => 'SEC012', 'name' => 'An ninh chuỗi cung ứng', 'name_en' => 'Supply Chain Security', 'weight' => 5],
            ['code' => 'SEC013', 'name' => 'An ninh đám mây', 'name_en' => 'Cloud Security', 'weight' => 5],
            ['code' => 'SEC014', 'name' => 'DevSecOps', 'name_en' => 'DevSecOps', 'weight' => 5],
            ['code' => 'SEC015', 'name' => 'An ninh IoT', 'name_en' => 'IoT Security', 'weight' => 5],
            ['code' => 'SEC016', 'name' => 'An ninh di động', 'name_en' => 'Mobile Security', 'weight' => 5],
            ['code' => 'SEC017', 'name' => 'An ninh API', 'name_en' => 'API Security', 'weight' => 5],
            ['code' => 'SEC018', 'name' => 'An ninh AI', 'name_en' => 'AI Security', 'weight' => 5],
            ['code' => 'SEC019', 'name' => 'Zero Trust', 'name_en' => 'Zero Trust', 'weight' => 5],
            ['code' => 'SEC020', 'name' => 'Tình báo đe dọa', 'name_en' => 'Threat Intelligence', 'weight' => 5],
        ];

        foreach ($domains as $domain) {
            Domain::updateOrCreate(['code' => $domain['code']], $domain);
        }
    }
}