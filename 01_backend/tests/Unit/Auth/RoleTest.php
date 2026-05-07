<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_10_default_roles()
    {
        $this->artisan('db:seed --class=RoleSeeder');
        
        $roles = Role::all();
        $this->assertEquals(10, $roles->count());
    }

    public function test_admin_has_all_permissions()
    {
        $permissions = Role::getPermissions('admin');
        $this->assertContains('*', $permissions);
    }

    public function test_dev_has_code_permissions()
    {
        $permissions = Role::getPermissions('dev');
        $this->assertContains('scan.code.view', $permissions);
        $this->assertContains('scan.code.run', $permissions);
    }

    public function test_manager_can_approve()
    {
        $permissions = Role::getPermissions('manager');
        $this->assertContains('evaluation.approve', $permissions);
    }
}