<?php

namespace Tests\Feature\Notification;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlackNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_slack_notification()
    {
        Http::fake();
        
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/notifications/slack/send', [
                             'channel' => '#security-alerts',
                             'message' => 'Security alert: New vulnerability detected',
                             'color' => 'danger',
                         ]);
        
        $response->assertStatus(200);
    }

    public function test_send_slack_notification_with_attachments()
    {
        Http::fake();
        
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/notifications/slack/send', [
                             'channel' => '#reports',
                             'message' => 'Daily Report',
                             'attachments' => [
                                 [
                                     'title' => 'Evaluation Summary',
                                     'fields' => [
                                         ['title' => 'Total', 'value' => '15', 'short' => true],
                                         ['title' => 'Approved', 'value' => '12', 'short' => true],
                                     ],
                                 ],
                             ],
                         ]);
        
        $response->assertStatus(200);
    }

    public function test_send_slack_notification_to_multiple_channels()
    {
        Http::fake();
        
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/notifications/slack/broadcast', [
                             'channels' => ['#general', '#security', '#alerts'],
                             'message' => 'System maintenance scheduled',
                         ]);
        
        $response->assertStatus(200);
        $this->assertEquals(3, $response->json('sent_count'));
    }
}