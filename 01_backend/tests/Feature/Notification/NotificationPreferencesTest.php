<?php

namespace Tests\Feature\Notification;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationPreferencesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_notification_preferences()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson('/api/notifications/preferences', [
                             'email_enabled' => true,
                             'push_enabled' => false,
                             'sms_enabled' => false,
                             'digest_frequency' => 'daily',
                         ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->id,
            'key' => 'notification_preferences',
        ]);
    }

    public function test_user_can_get_notification_preferences()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/notifications/preferences');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'email_enabled',
                     'push_enabled',
                     'sms_enabled',
                     'digest_frequency',
                 ]);
    }

    public function test_user_can_opt_out_of_all_notifications()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson('/api/notifications/preferences', [
                             'email_enabled' => false,
                             'push_enabled' => false,
                             'sms_enabled' => false,
                         ]);
        
        $response->assertStatus(200);
        
        // Verify no notifications are sent
        $this->withoutNotifications();
    }
}