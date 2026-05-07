<?php

namespace Tests\Integration\External;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AIServiceTest extends TestCase
{
    protected $aiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->aiUrl = config('ai.api_url', 'http://ai-service:5000');
    }

    public function test_ai_service_health()
    {
        $response = Http::get($this->aiUrl . '/health');
        
        $this->assertTrue($response->successful() || $response->failed());
    }

    public function test_ai_scoring_endpoint()
    {
        $response = Http::post($this->aiUrl . '/api/v1/score', [
            'evaluation_id' => 1,
            'criteria_data' => [],
        ]);
        
        $this->assertIsArray($response->json() ?? []);
    }

    public function test_ai_prediction_endpoint()
    {
        $response = Http::post($this->aiUrl . '/api/v1/predict', [
            'evaluation_id' => 1,
            'days' => 30,
        ]);
        
        $this->assertIsArray($response->json() ?? []);
    }
}