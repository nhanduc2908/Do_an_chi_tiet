<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CodeScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_dev_can_run_code_scan()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/code', [
                             'repository_url' => 'https://github.com/test/repo',
                             'branch' => 'main',
                         ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['scan_id', 'status']);
    }

    public function test_scan_creates_scan_result_record()
    {
        $dev = User::factory()->create(['role' => 'dev']);
        $token = $dev->createToken('auth_token')->plainTextToken;
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/scanner/code', [
                 'repository_url' => 'https://github.com/test/repo',
             ]);
        
        $this->assertDatabaseHas('scan_results', [
            'type' => 'code',
            'user_id' => $dev->id,
        ]);
    }

    public function test_hr_cannot_run_code_scan()
    {
        $hr = User::factory()->create(['role' => 'hr']);
        $token = $hr->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/code', [
                             'repository_url' => 'https://github.com/test/repo',
                         ]);
        
        $response->assertStatus(403);
    }
}