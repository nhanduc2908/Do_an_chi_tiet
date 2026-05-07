<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\IpWhitelist;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpWhitelistTest extends TestCase
{
    use RefreshDatabase;

    public function test_whitelisted_ip_can_access()
    {
        IpWhitelist::create([
            'ip_address' => '192.168.1.100',
            'is_active' => true,
        ]);
        
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
                         ->get('/api/health');
        
        $response->assertStatus(200);
    }

    public function test_non_whitelisted_ip_is_blocked()
    {
        IpWhitelist::create([
            'ip_address' => '192.168.1.100',
            'is_active' => true,
        ]);
        
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.200'])
                         ->get('/api/health');
        
        $response->assertStatus(403);
    }

    public function test_expired_whitelist_ip_is_blocked()
    {
        IpWhitelist::create([
            'ip_address' => '192.168.1.100',
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);
        
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
                         ->get('/api/health');
        
        $response->assertStatus(403);
    }
}