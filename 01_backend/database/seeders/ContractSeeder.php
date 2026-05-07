<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\Vendor;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = Vendor::all();
        
        $contracts = [
            ['title' => 'Dịch vụ đám mây', 'value' => 50000, 'status' => 'active'],
            ['title' => 'Phần mềm bảo mật', 'value' => 25000, 'status' => 'active'],
            ['title' => 'Tư vấn bảo mật', 'value' => 15000, 'status' => 'active'],
            ['title' => 'Cung cấp phần cứng', 'value' => 30000, 'status' => 'expired'],
            ['title' => 'Dịch vụ SaaS', 'value' => 12000, 'status' => 'active'],
        ];

        foreach ($vendors as $index => $vendor) {
            $contract = $contracts[$index % count($contracts)];
            Contract::create([
                'vendor_id' => $vendor->id,
                'contract_number' => 'CT' . str_pad($vendor->id, 5, '0', STR_PAD_LEFT),
                'title' => $contract['title'] . ' - ' . $vendor->name,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'value' => $contract['value'],
                'status' => $contract['status'],
            ]);
        }
    }
}