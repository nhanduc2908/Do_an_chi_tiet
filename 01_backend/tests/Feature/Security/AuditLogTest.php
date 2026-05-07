<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_action_is_logged()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        
        $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'event_type' => 'login',
        ]);
    }

    public function test_evaluation_creation_is_logged()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/evaluations', [
                 'title' => 'Test Evaluation',
                 'domain_id' => 1,
             ]);
        
        $this->assertDatabaseHas('audit_logs', [
            'event_type' => 'create',
        ]);
    }

    public function test_only_admin_can_view_audit_logs()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/audit/logs');
        
        $response->assertStatus(403);
    }
}