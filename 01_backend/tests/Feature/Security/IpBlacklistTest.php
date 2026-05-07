<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\IpBlacklist;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpBlacklistTest extends TestCase
{
    use RefreshDatabase;

    public function test_blacklisted_ip_is_blocked()
    {
        IpBlacklist::create([
            'ip_address' => '192.168.1.100',
            'reason' => 'Suspicious activity',
            'blocked_by' => 1,
        ]);
        
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
                         ->get('/api/health');
        
        $response->assertStatus(403);
    }

    public function test_non_blacklisted_ip_can_access()
    {
        IpBlacklist::create([
            'ip_address' => '192.168.1.100',
            'reason' => 'Suspicious activity',
            'blocked_by' => 1,
        ]);
        
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.200'])
                         ->get('/api/health');
        
        $response->assertStatus(200);
    }

    public function test_expired_blacklist_allows_access()
    {
        IpBlacklist::create([
            'ip_address' => '192.168.1.100',
            'reason' => 'Suspicious activity',
            'blocked_by' => 1,
            'expires_at' => now()->subHour(),
        ]);
        
        $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
                         ->get('/api/health');
        
        $response->assertStatus(200);
    }
}