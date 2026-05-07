<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Department;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        $teams = ['Development', 'Security', 'QA', 'Infrastructure', 'Data'];

        foreach ($departments as $department) {
            foreach ($teams as $teamName) {
                Team::updateOrCreate(
                    ['department_id' => $department->id, 'name' => $teamName],
                    ['code' => $department->code . '_' . strtoupper(substr($teamName, 0, 3))]
                );
            }
        }
    }
}