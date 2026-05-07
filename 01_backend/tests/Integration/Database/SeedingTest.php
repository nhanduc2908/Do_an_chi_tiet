<?php

namespace Tests\Integration\Database;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Domain;
use App\Models\Criteria;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeedingTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_seeder_creates_10_roles()
    {
        $this->artisan('db:seed --class=RoleSeeder');
        
        $this->assertEquals(10, Role::count());
    }

    public function test_domain_seeder_creates_20_domains()
    {
        $this->artisan('db:seed --class=DomainSeeder');
        
        $this->assertEquals(20, Domain::count());
    }

    public function test_criteria_seeder_creates_400_criteria()
    {
        Domain::factory()->count(20)->create();
        $this->artisan('db:seed --class=CriteriaSeeder');
        
        $this->assertEquals(400, Criteria::count());
    }

    public function test_version_seeder_creates_5_versions()
    {
        $this->artisan('db:seed --class=VersionSeeder');
        
        $this->assertEquals(5, \App\Models\Version::count());
    }

    public function test_full_database_seed()
    {
        $this->artisan('db:seed');
        
        $this->assertGreaterThan(0, User::count());
        $this->assertEquals(10, Role::count());
        $this->assertEquals(20, Domain::count());
    }
}