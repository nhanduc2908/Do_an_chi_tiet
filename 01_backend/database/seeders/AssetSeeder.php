<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\User;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        $assets = [
            ['name' => 'Database Server', 'type' => 'hardware', 'classification' => 'restricted', 'value' => 50000, 'location' => 'Data Center A'],
            ['name' => 'Source Code Repository', 'type' => 'software', 'classification' => 'confidential', 'value' => 100000, 'location' => 'GitLab Cloud'],
            ['name' => 'Customer Data', 'type' => 'data', 'classification' => 'restricted', 'value' => 500000, 'location' => 'Database'],
            ['name' => 'Firewall System', 'type' => 'service', 'classification' => 'internal', 'value' => 25000, 'location' => 'Network Perimeter'],
            ['name' => 'Backup Storage', 'type' => 'hardware', 'classification' => 'internal', 'value' => 15000, 'location' => 'Offsite Backup'],
            ['name' => 'Email Server', 'type' => 'service', 'classification' => 'internal', 'value' => 10000, 'location' => 'Cloud'],
            ['name' => 'Employee Records', 'type' => 'data', 'classification' => 'confidential', 'value' => 75000, 'location' => 'HR Database'],
            ['name' => 'Network Switch', 'type' => 'hardware', 'classification' => 'internal', 'value' => 5000, 'location' => 'Server Room'],
            ['name' => 'SSL Certificate', 'type' => 'software', 'classification' => 'public', 'value' => 500, 'location' => 'Web Server'],
            ['name' => 'API Gateway', 'type' => 'service', 'classification' => 'internal', 'value' => 30000, 'location' => 'Cloud'],
        ];

        foreach ($assets as $asset) {
            Asset::create([
                'name' => $asset['name'],
                'type' => $asset['type'],
                'classification' => $asset['classification'],
                'value' => $asset['value'],
                'location' => $asset['location'],
                'owner_id' => $users->random()->id,
                'status' => 'active',
            ]);
        }
    }
}