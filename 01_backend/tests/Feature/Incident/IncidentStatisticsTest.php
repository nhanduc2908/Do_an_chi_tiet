<?php

namespace Tests\Feature\Incident;

use Tests\TestCase;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncidentStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_view_incident_statistics()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        
        Incident::factory()->create(['severity' => 'critical', 'status' => 'resolved']);
        Incident::factory()->create(['severity' => 'high', 'status' => 'open']);
        Incident::factory()->create(['severity' => 'medium', 'status' => 'investigating']);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/incidents/statistics');
        
        $response->assertStatus(200);
        $this->assertEquals(3, $response->json('total'));
        $this->assertEquals(1, $response->json('by_severity')['critical']);
    }

    public function test_mttr_calculation()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        
        Incident::factory()->create([
            'detected_at' => now()->subHours(5),
            'resolved_at' => now(),
        ]);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/incidents/statistics');
        
        $response->assertStatus(200);
        $this->assertEquals(300, $response->json('avg_resolution_time_minutes'));
    }

    public function test_incident_trend_analysis()
    {
        $secops = User::factory()->create(['role' => 'secops']);
        
        Incident::factory()->create(['created_at' => now()->subDays(1)]);
        Incident::factory()->create(['created_at' => now()->subDays(2)]);
        Incident::factory()->create(['created_at' => now()->subDays(7)]);
        
        $token = $secops->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/incidents/trend?days=30');
        
        $response->assertStatus(200);
        $this->assertCount(30, $response->json());
    }
}