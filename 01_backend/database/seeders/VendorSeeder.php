<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            ['name' => 'Cloud Provider A', 'contact_name' => 'Nguyễn Văn A', 'contact_email' => 'contact@clouda.com', 'risk_level' => 'medium', 'status' => 'active', 'website' => 'https://clouda.com'],
            ['name' => 'Security Vendor B', 'contact_name' => 'Trần Thị B', 'contact_email' => 'support@securityb.com', 'risk_level' => 'low', 'status' => 'active', 'website' => 'https://securityb.com'],
            ['name' => 'Software Vendor C', 'contact_name' => 'Lê Văn C', 'contact_email' => 'sales@softwarec.com', 'risk_level' => 'high', 'status' => 'active', 'website' => 'https://softwarec.com'],
            ['name' => 'Consulting Firm D', 'contact_name' => 'Phạm Thị D', 'contact_email' => 'info@consultd.com', 'risk_level' => 'low', 'status' => 'active', 'website' => 'https://consultd.com'],
            ['name' => 'Hardware Supplier E', 'contact_name' => 'Hoàng Văn E', 'contact_email' => 'sales@hardwaree.com', 'risk_level' => 'medium', 'status' => 'active', 'website' => 'https://hardwaree.com'],
            ['name' => 'SaaS Provider F', 'contact_name' => 'Ngô Thị F', 'contact_email' => 'support@saasf.com', 'risk_level' => 'high', 'status' => 'active', 'website' => 'https://saasf.com'],
            ['name' => 'Data Center G', 'contact_name' => 'Đỗ Văn G', 'contact_email' => 'ops@datacenterG.com', 'risk_level' => 'low', 'status' => 'active', 'website' => 'https://datacenterG.com'],
            ['name' => 'Penetration Testing H', 'contact_name' => 'Vũ Thị H', 'contact_email' => 'contact@pentestH.com', 'risk_level' => 'medium', 'status' => 'active', 'website' => 'https://pentestH.com'],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}