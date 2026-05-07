<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_dev_cannot_access_admin_routes()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_dev_can_access_scan_routes()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/code', [
                             'repository_url' => 'https://github.com/test/repo',
                         ]);

        $response->assertStatus(200);
    }

    public function test_auditor_can_access_audit_logs()
    {
        $auditor = User::factory()->create(['role' => 'auditor']);
        $token = $auditor->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/audit/logs');

        $response->assertStatus(200);
    }
}