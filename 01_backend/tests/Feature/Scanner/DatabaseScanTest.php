<?php

namespace Tests\Feature\Scanner;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_da_can_run_database_scan()
    {
        $da = User::factory()->create(['role' => 'da']);
        $token = $da->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/database', [
                             'database_name' => 'production_db',
                             'scan_type' => 'full',
                         ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['scan_id', 'status']);
    }

    public function test_database_scan_detects_sensitive_data()
    {
        $da = User::factory()->create(['role' => 'da']);
        $token = $da->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/database', [
                             'database_name' => 'production_db',
                         ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['findings', 'sensitive_tables']);
    }

    public function test_unauthorized_user_cannot_run_database_scan()
    {
        $hr = User::factory()->create(['role' => 'hr']);
        $token = $hr->createToken('auth_token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/scanner/database', [
                             'database_name' => 'production_db',
                         ]);
        
        $response->assertStatus(403);
    }
}