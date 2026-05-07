<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_permissions_are_created()
    {
        $this->artisan('db:seed --class=PermissionSeeder');
        
        $permissions = Permission::all();
        $this->assertGreaterThan(0, $permissions->count());
    }

    public function test_permissions_have_modules()
    {
        $permission = Permission::create([
            'name' => 'test.view',
            'display_name' => 'Test View',
            'module' => 'test',
        ]);
        
        $this->assertEquals('test', $permission->module);
    }

    public function test_role_can_have_multiple_permissions()
    {
        $role = Role::factory()->create();
        $perm1 = Permission::factory()->create();
        $perm2 = Permission::factory()->create();
        
        $role->permissions()->attach([$perm1->id, $perm2->id]);
        
        $this->assertEquals(2, $role->permissions->count());
    }
}