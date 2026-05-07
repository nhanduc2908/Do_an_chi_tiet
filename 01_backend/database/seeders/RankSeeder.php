<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rank;

class RankSeeder extends Seeder
{
    public function run(): void
    {
        $ranks = [
            ['code' => 'R001', 'name' => 'Nhân viên', 'level' => 1],
            ['code' => 'R002', 'name' => 'Chuyên viên', 'level' => 2],
            ['code' => 'R003', 'name' => 'Trưởng nhóm', 'level' => 3],
            ['code' => 'R004', 'name' => 'Quản lý', 'level' => 4],
            ['code' => 'R005', 'name' => 'Trưởng phòng', 'level' => 5],
            ['code' => 'R006', 'name' => 'Giám đốc', 'level' => 6],
        ];

        foreach ($ranks as $rank) {
            Rank::updateOrCreate(['code' => $rank['code']], $rank);
        }
    }
}