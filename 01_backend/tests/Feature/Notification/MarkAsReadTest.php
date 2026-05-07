<?php

namespace Tests\Feature\Notification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MarkAsReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'title' => 'Test',
            'content' => 'Content',
            'is_read' => false,
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/notifications/{$notification->id}/read");
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    public function test_user_can_mark_all_as_read()
    {
        $user = User::factory()->create();
        Notification::create([
            'user_id' => $user->id,
            'type' => 'test1',
            'title' => 'Test1',
            'content' => 'Content1',
            'is_read' => false,
        ]);
        Notification::create([
            'user_id' => $user->id,
            'type' => 'test2',
            'title' => 'Test2',
            'content' => 'Content2',
            'is_read' => false,
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/notifications/mark-all-read');
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $user->id,
            'is_read' => false,
        ]);
    }

    public function test_get_unread_count()
    {
        $user = User::factory()->create();
        Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'title' => 'Test',
            'content' => 'Content',
            'is_read' => false,
        ]);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/notifications/unread-count');
        
        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('unread_count'));
    }
}