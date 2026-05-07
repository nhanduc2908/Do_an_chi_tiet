<?php

namespace Tests\Feature\Notification;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_email_notification()
    {
        Mail::fake();
        
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/notifications/email/send', [
                             'to' => $user->email,
                             'subject' => 'Test Email',
                             'content' => 'This is a test email',
                         ]);
        
        $response->assertStatus(200);
        Mail::assertSent(\App\Mail\NotificationMail::class);
    }

    public function test_send_bulk_emails()
    {
        Mail::fake();
        
        $admin = User::factory()->create(['role' => 'admin']);
        $users = User::factory()->count(3)->create();
        
        $token = $admin->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/notifications/email/bulk', [
                             'user_ids' => $users->pluck('id')->toArray(),
                             'subject' => 'Bulk Email',
                             'content' => 'This is a bulk email',
                         ]);
        
        $response->assertStatus(200);
        Mail::assertSent(\App\Mail\NotificationMail::class, 3);
    }

    public function test_email_template_rendering()
    {
        Mail::fake();
        
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/notifications/email/template', [
                             'template' => 'evaluation_approved',
                             'data' => [
                                 'user_name' => $user->name,
                                 'evaluation_title' => 'Security Check',
                                 'score' => 85,
                             ],
                         ]);
        
        $response->assertStatus(200);
    }
}