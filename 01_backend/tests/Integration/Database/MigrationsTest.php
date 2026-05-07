<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_migrations_ran_successfully()
    {
        $this->artisan('migrate')->assertExitCode(0);
    }

    public function test_required_tables_exist()
    {
        $tables = ['users', 'roles', 'evaluations', 'reports', 'audit_logs'];
        
        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table {$table} does not exist");
        }
    }

    public function test_foreign_key_constraints()
    {
        $this->artisan('migrate:fresh')->assertExitCode(0);
        
        // Verify foreign keys are enforced
        $this->expectException(\Exception::class);
        DB::table('evaluations')->insert([
            'title' => 'Test',
            'user_id' => 99999, // Non-existent user
        ]);
    }
}