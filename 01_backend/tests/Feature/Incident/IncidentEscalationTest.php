<?php

namespace Tests\Feature\Incident;

use Tests\TestCase;
use App\Models\User;
use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncidentEscalationTest extends TestCase
{
    use RefreshDatabase;

    public function test_auto_escalate_critical_incident()
    {
        $incident = Incident::factory()->create([
            'severity' => 'critical',
            'status' => 'open',
            'created_at' => now()->subMinutes(10),
        ]);
        
        // Run escalation job
        \Artisan::call('incident:escalate');
        
        $incident->refresh();
        $this->assertNotNull($incident->escalated_at);
    }

    public function test_notify_manager_on_escalation()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $incident = Incident::factory()->create([
            'severity' => 'high',
            'status' => 'open',
            'created_at' => now()->subMinutes(30),
        ]);
        
        \Artisan::call('incident:escalate');
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $manager->id,
            'type' => 'incident_escalated',
        ]);
    }

    public function test_escalation_threshold_configurable()
    {
        config(['incident.escalation_minutes' => 60]);
        
        $incident = Incident::factory()->create([
            'severity' => 'high',
            'status' => 'open',
            'created_at' => now()->subMinutes(30),
        ]);
        
        \Artisan::call('incident:escalate');
        
        $incident->refresh();
        $this->assertNull($incident->escalated_at);
    }
}