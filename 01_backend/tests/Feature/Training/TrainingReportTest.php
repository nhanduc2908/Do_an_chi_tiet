<?php

namespace Tests\Feature\Training;

use Tests\TestCase;
use App\Models\User;
use App\Models\Training;
use App\Models\TrainingAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrainingReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_training_report()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/trainings/report');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total_trainings',
                     'completed_trainings',
                     'in_progress',
                     'completion_rate',
                     'average_score',
                 ]);
    }

    public function test_export_training_report()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/trainings/report/export?format=excel');
        
        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_filter_report_by_department()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/trainings/report?department_id=1');
        
        $response->assertStatus(200);
    }
}