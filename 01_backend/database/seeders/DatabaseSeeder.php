<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            AdminUserSeeder::class,
            CompanySeeder::class,
            DepartmentSeeder::class,
            TeamSeeder::class,
            RankSeeder::class,
            DomainSeeder::class,
            CriteriaSeeder::class,
            ConditionSeeder::class,
            VersionSeeder::class,
            EvaluationSeeder::class,
            ReportSeeder::class,
            NotificationSeeder::class,
            TrainingSeeder::class,
            PolicySeeder::class,
            IncidentSeeder::class,
            RiskSeeder::class,
            AssetSeeder::class,
            VendorSeeder::class,
            ContractSeeder::class,
            ComplianceSeeder::class,
            TestDataSeeder::class,
        ]);
    }
}