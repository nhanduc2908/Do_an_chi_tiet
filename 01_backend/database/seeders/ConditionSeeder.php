<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Condition;
use App\Models\Criteria;

class ConditionSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = Criteria::all();
        
        foreach ($criteria as $criterion) {
            for ($i = 1; $i <= rand(3, 5); $i++) {
                Condition::updateOrCreate(
                    ['criteria_id' => $criterion->id, 'name' => "Điều kiện {$i}"],
                    [
                        'description' => "Mô tả điều kiện {$i} cho tiêu chí {$criterion->name}",
                        'weight' => rand(1, 10),
                        'order' => $i
                    ]
                );
            }
        }
    }
}