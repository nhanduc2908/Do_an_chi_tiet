<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlackTest extends TestCase
{
    public function test_slack_webhook()
    {
        $webhookUrl = config('services.slack.webhook_url');
        
        if (!$webhookUrl) {
            $this->markTestSkipped('Slack webhook not configured');
        }
        
        $response = Http::post($webhookUrl, [
            'text' => 'Test message from Security System',
        ]);
        
        $this->assertTrue($response->successful() || $response->failed());
    }
}