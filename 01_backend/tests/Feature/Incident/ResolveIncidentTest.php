<?php

namespace Tests\Feature\Incident;

use Tests\TestCase;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResolveIncidentTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolve_incident()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $incident = Incident::factory()->create(['status' => 'investigating']);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/incidents/{$incident->id}/resolve", [
                             'resolution' => 'Root cause fixed',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('incidents', [
            'id' => $incident->id,
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function test_resolution_requires_reason()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $incident = Incident::factory()->create();
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/incidents/{$incident->id}/resolve", []);
        
        $response->assertStatus(422);
    }
}