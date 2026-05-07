<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_qa_can_run_mobile_app_scan()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/mobile', [
                             'app_url' => 'https://example.com/app.apk',
                             'platform' => 'android',
                         ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['scan_id', 'security_score']);
    }

    public function test_mobile_scan_checks_permissions()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/mobile', [
                             'app_url' => 'https://example.com/app.ipa',
                             'platform' => 'ios',
                         ]);
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('permission_analysis', $response->json());
    }

    public function test_mobile_scan_detects_insecure_storage()
    {
        $qa = User::factory()->create(['role' => 'qa']);
        $token = $qa->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/mobile', [
                             'app_url' => 'https://example.com/app.apk',
                         ]);
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('storage_findings', $response->json());
    }
}