<?php

namespace Tests\Integration\Workflow;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_generation_workflow()
    {
        $user = User::factory()->create();
        $evaluation = Evaluation::factory()->create([
            'user_id' => $user->id,
            'status' => 'approved',
            'percentage' => 85,
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/reports/generate', [
                             'evaluation_id' => $evaluation->id,
                             'format' => 'pdf',
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('reports', [
            'evaluation_id' => $evaluation->id,
            'format' => 'pdf',
        ]);
    }

    public function test_scheduled_report_workflow()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/reports/schedule', [
                             'type' => 'evaluation_summary',
                             'cron' => '0 8 * * *',
                             'recipients' => ['admin@example.com'],
                             'format' => 'excel',
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('scheduled_reports', [
            'type' => 'evaluation_summary',
            'cron' => '0 8 * * *',
        ]);
    }
}