<?php

namespace Tests\Feature\Incident;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateIncidentTest extends TestCase
{
    use RefreshDatabase;

    public function test_secops_can_create_incident()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/incidents', [
                             'title' => 'Phishing Attack Detected',
                             'description' => 'Multiple users reported suspicious emails',
                             'severity' => 'high',
                             'type' => 'attack',
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('incidents', [
            'title' => 'Phishing Attack Detected',
        ]);
    }

    public function test_admin_can_create_incident()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/incidents', [
                             'title' => 'Data Breach',
                             'description' => 'Customer data exposed',
                             'severity' => 'critical',
                             'type' => 'breach',
                         ]);
        
        $response->assertStatus(201);
    }

    public function test_dev_cannot_create_incident()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/incidents', [
                             'title' => 'Test Incident',
                             'description' => 'Test',
                             'severity' => 'medium',
                             'type' => 'other',
                         ]);
        
        $response->assertStatus(403);
    }
}