<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criteria;
use App\Models\Domain;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $domains = Domain::all();
        
        foreach ($domains as $domain) {
            for ($i = 1; $i <= 20; $i++) {
                Criteria::updateOrCreate(
                    [
                        'domain_id' => $domain->id,
                        'code' => $domain->code . '_C' . str_pad($i, 3, '0', STR_PAD_LEFT)
                    ],
                    [
                        'name' => "Tiêu chí {$i} - {$domain->name}",
                        'name_en' => "Criteria {$i} - {$domain->name_en}",
                        'criteria_group' => $this->getCriteriaGroup($i),
                        'weight' => rand(1, 10),
                        'priority' => $this->getPriority($i),
                        'order' => $i,
                        'status' => 'active'
                    ]
                );
            }
        }
    }

    private function getCriteriaGroup(int $index): string
    {
        if ($index <= 5) return 'policy';
        if ($index <= 10) return 'technical';
        if ($index <= 15) return 'operational';
        return 'compliance';
    }

    private function getPriority(int $index): string
    {
        if ($index <= 5) return 'high';
        if ($index <= 12) return 'medium';
        return 'low';
    }
}