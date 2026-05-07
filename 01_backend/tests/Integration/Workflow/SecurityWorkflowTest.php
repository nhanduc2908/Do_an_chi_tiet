<?php

namespace Tests\Integration\Workflow;

use Tests\TestCase;
use App\Models\User;
use App\Models\SecurityViolation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_violation_detection_workflow()
    {
        // Simulate brute force attack
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/login', [
                'email' => 'test@example.com',
                'password' => 'wrong' . $i,
            ]);
        }
        
        $violations = SecurityViolation::where('type', 'brute_force')->count();
        $this->assertGreaterThan(0, $violations);
    }

    public function test_security_violation_resolution_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $violation = SecurityViolation::create([
            'type' => 'test',
            'severity' => 'high',
            'description' => 'Test violation',
            'ip_address' => '192.168.1.1',
        ]);
        
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/security/violations/{$violation->id}/resolve", [
                 'note' => 'Resolved',
             ]);
        
        $this->assertNotNull($violation->refresh()->resolved_at);
    }
}