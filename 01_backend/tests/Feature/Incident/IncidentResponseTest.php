<?php

namespace Tests\Feature\Incident;

use Tests\TestCase;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncidentResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_response_to_incident()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $incident = Incident::factory()->create();
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/incidents/{$incident->id}/responses", [
                             'action' => 'containment',
                             'description' => 'Blocked malicious IP addresses',
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('incident_responses', [
            'incident_id' => $incident->id,
            'action' => 'containment',
        ]);
    }

    public function test_response_timeline_is_recorded()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $incident = Incident::factory()->create();
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/incidents/{$incident->id}/responses", [
                 'action' => 'detection',
                 'description' => 'Alert triggered',
             ]);
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/incidents/{$incident->id}/responses", [
                 'action' => 'analysis',
                 'description' => 'Investigation started',
             ]);
        
        $this->assertDatabaseCount('incident_responses', 2);
    }
}