<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Company;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        $departments = ['IT', 'HR', 'Finance', 'Marketing', 'Sales', 'Legal', 'Security', 'Operation'];

        foreach ($companies as $company) {
            foreach ($departments as $dept) {
                Department::updateOrCreate(
                    ['company_id' => $company->id, 'name' => $dept],
                    ['code' => $company->code . '_' . strtoupper(substr($dept, 0, 3))]
                );
            }
        }
    }
}