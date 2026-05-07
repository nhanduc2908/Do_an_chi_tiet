<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\SecurityViolation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityViolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sql_injection_detected()
    {
        $response = $this->get('/api/login?email=\' OR \'1\'=\'1');
        
        $this->assertDatabaseHas('security_violations', [
            'type' => 'sql_injection',
        ]);
    }

    public function test_xss_attempt_detected()
    {
        $response = $this->get('/api/search?q=<script>alert("xss")</script>');
        
        $this->assertDatabaseHas('security_violations', [
            'type' => 'xss',
        ]);
    }

    public function test_admin_can_resolve_violation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $violation = SecurityViolation::create([
            'type' => 'test',
            'severity' => 'medium',
            'description' => 'Test violation',
            'ip_address' => '127.0.0.1',
        ]);
        
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/security/violations/{$violation->id}/resolve", [
                             'note' => 'Resolved',
                         ]);
        
        $response->assertStatus(200);
        $this->assertNotNull($violation->refresh()->resolved_at);
    }
}