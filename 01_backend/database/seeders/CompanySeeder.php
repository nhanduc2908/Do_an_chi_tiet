<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['code' => 'COMP001', 'name' => 'Công ty Cổ phần ABC', 'industry' => 'technology', 'size' => 'large', 'status' => 'active'],
            ['code' => 'COMP002', 'name' => 'Ngân hàng XYZ', 'industry' => 'finance', 'size' => 'enterprise', 'status' => 'active'],
            ['code' => 'COMP003', 'name' => 'Bệnh viện Đa khoa', 'industry' => 'healthcare', 'size' => 'large', 'status' => 'active'],
            ['code' => 'COMP004', 'name' => 'Công ty Thương mại DEF', 'industry' => 'retail', 'size' => 'medium', 'status' => 'active'],
            ['code' => 'COMP005', 'name' => 'Startup Tech GHI', 'industry' => 'technology', 'size' => 'small', 'status' => 'active'],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(['code' => $company['code']], $company);
        }
    }
}