<?php

namespace Tests\Feature\Notification;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_notification_to_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/notifications/send', [
                             'user_id' => $user->id,
                             'title' => 'Test Notification',
                             'content' => 'This is a test',
                             'priority' => 'high',
                         ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Test Notification',
        ]);
    }

    public function test_user_receives_notification_on_evaluation_approval()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        
        $evaluation = \App\Models\Evaluation::factory()->create([
            'user_id' => $user->id,
            'status' => 'submitted',
        ]);
        
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson("/api/evaluations/{$evaluation->id}/approve");
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'evaluation_approved',
        ]);
    }
}