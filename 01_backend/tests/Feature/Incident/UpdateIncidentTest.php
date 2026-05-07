<?php

namespace Tests\Feature\Incident;

use Tests\TestCase;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateIncidentTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_incident_status()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $incident = Incident::factory()->create(['status' => 'open']);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/incidents/{$incident->id}", [
                             'status' => 'investigating',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('incidents', [
            'id' => $incident->id,
            'status' => 'investigating',
        ]);
    }

    public function test_assign_incident_to_user()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        $user = User::factory()->create();
        $incident = Incident::factory()->create();
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/incidents/{$incident->id}", [
                             'assigned_to' => $user->id,
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('incidents', [
            'id' => $incident->id,
            'assigned_to' => $user->id,
        ]);
    }
}