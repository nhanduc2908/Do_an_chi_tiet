<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function role_has_users(): void
    {
        $role = Role::factory()->create(['name' => 'test_role']);
        $user = User::factory()->create(['role' => 'test_role']);

        $this->assertEquals(1, $role->users->count());
    }

    /** @test */
    public function role_has_permissions(): void
    {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();
        $role->permissions()->attach($permission);

        $this->assertEquals(1, $role->permissions->count());
    }
}