<?php

namespace Tests\Feature\Incident;

use Tests\TestCase;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncidentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_incident_workflow()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        // 1. Create incident
        $createResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                               ->postJson('/api/incidents', [
                                   'title' => 'Security Breach',
                                   'description' => 'Unauthorized access detected',
                                   'severity' => 'high',
                                   'type' => 'breach',
                               ]);
        
        $createResponse->assertStatus(201);
        $incidentId = $createResponse->json('id');
        
        // 2. Update status to investigating
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->putJson("/api/incidents/{$incidentId}", [
                 'status' => 'investigating',
             ]);
        
        // 3. Add response
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/incidents/{$incidentId}/responses", [
                 'action' => 'investigation',
                 'description' => 'Analyzing logs',
             ]);
        
        // 4. Resolve incident
        $resolveResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                                ->postJson("/api/incidents/{$incidentId}/resolve", [
                                    'resolution' => 'Blocked malicious IP',
                                ]);
        
        $resolveResponse->assertStatus(200);
        
        // Verify final state
        $this->assertDatabaseHas('incidents', [
            'id' => $incidentId,
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function test_incident_cannot_be_reopened_after_closure()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $incident = Incident::factory()->create(['status' => 'closed']);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/incidents/{$incident->id}", [
                             'status' => 'open',
                         ]);
        
        $response->assertStatus(422);
    }

    public function test_incident_requires_approval_for_closure()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $incident = Incident::factory()->create(['status' => 'resolved']);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/incidents/{$incident->id}/close", [
                             'approval_note' => 'All good',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('incidents', [
            'id' => $incident->id,
            'status' => 'closed',
            'closed_by' => $secops->id,
        ]);
    }
}