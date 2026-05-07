<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_qa_can_run_api_scan()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/api', [
                             'endpoint' => 'https://api.example.com/v1/users',
                             'method' => 'GET',
                         ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['scan_id', 'vulnerabilities']);
    }

    public function test_api_scan_checks_authentication()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/api', [
                             'endpoint' => 'https://api.example.com/v1/admin',
                             'method' => 'GET',
                         ]);
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('auth_check', $response->json());
    }

    public function test_api_rate_limit_check()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/api', [
                             'endpoint' => 'https://api.example.com/v1/users',
                             'check_rate_limit' => true,
                         ]);
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('rate_limit_info', $response->json());
    }
}