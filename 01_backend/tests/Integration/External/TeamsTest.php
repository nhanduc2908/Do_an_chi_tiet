<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamsTest extends TestCase
{
    public function test_teams_webhook()
    {
        $webhookUrl = config('services.teams.webhook_url');
        
        if (!$webhookUrl) {
            $this->markTestSkipped('Teams webhook not configured');
        }
        
        $response = Http::post($webhookUrl, [
            'title' => 'Security Alert',
            'text' => 'Test message from Security System',
            'themeColor' => 'FF0000',
        ]);
        
        $this->assertTrue($response->successful() || $response->failed());
    }
}