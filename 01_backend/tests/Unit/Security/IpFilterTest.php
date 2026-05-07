<?php

namespace Tests\Unit\Security;

use Tests\TestCase;
use App\Services\Security\IpFilter;
use App\Models\IpWhitelist;
use App\Models\IpBlacklist;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpFilterTest extends TestCase
{
    use RefreshDatabase;

    protected IpFilter $ipFilter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ipFilter = new IpFilter();
    }

    public function test_whitelisted_ip_allowed()
    {
        IpWhitelist::create(['ip_address' => '192.168.1.1', 'is_active' => true]);
        
        $result = $this->ipFilter->isAllowed('192.168.1.1');
        $this->assertTrue($result);
    }

    public function test_blacklisted_ip_blocked()
    {
        IpBlacklist::create(['ip_address' => '192.168.1.1', 'reason' => 'test', 'blocked_by' => 1]);
        
        $result = $this->ipFilter->isAllowed('192.168.1.1');
        $this->assertFalse($result);
    }
}