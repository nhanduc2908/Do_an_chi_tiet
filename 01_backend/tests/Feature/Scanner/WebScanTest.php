<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_qa_can_run_web_scan()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/web', [
                             'url' => 'https://example.com',
                             'depth' => 2,
                         ]);
        
        $response->assertStatus(200);
    }

    public function test_web_scan_detects_vulnerabilities()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/web', [
                             'url' => 'https://example.com',
                         ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['vulnerabilities']);
    }

    public function test_invalid_url_rejected()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/web', [
                             'url' => 'invalid-url',
                         ]);
        
        $response->assertStatus(422);
    }
}