<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataChangeLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_evaluation_update_is_logged()
    {
        $user = User::factory()->create();
        $evaluation = Evaluation::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->putJson("/api/evaluations/{$evaluation->id}", [
                 'title' => 'Updated Title',
             ]);
        
        $this->assertDatabaseHas('data_change_logs', [
            'table_name' => 'evaluations',
            'record_id' => $evaluation->id,
            'action' => 'update',
        ]);
    }

    public function test_data_change_log_shows_old_and_new_values()
    {
        $user = User::factory()->create();
        $evaluation = Evaluation::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->putJson("/api/evaluations/{$evaluation->id}", [
                 'title' => 'New Title',
             ]);
        
        $log = \App\Models\DataChangeLog::where('record_id', $evaluation->id)->first();
        
        $this->assertEquals('Original Title', $log->old_data['title']);
        $this->assertEquals('New Title', $log->new_data['title']);
    }
}