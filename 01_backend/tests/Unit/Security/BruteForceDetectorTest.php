<?php

namespace Tests\Unit\Security;

use Tests\TestCase;
use App\Services\Security\BruteForceDetector;

class BruteForceDetectorTest extends TestCase
{
    protected BruteForceDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new BruteForceDetector();
    }

    public function test_record_attempt()
    {
        $key = 'test_ip';
        $attempts = $this->detector->recordAttempt($key);
        
        $this->assertEquals(1, $attempts);
    }

    public function test_is_locked_after_max_attempts()
    {
        $key = 'test_ip';
        
        for ($i = 0; $i < 5; $i++) {
            $this->detector->recordAttempt($key);
        }
        
        $this->assertTrue($this->detector->isLocked($key));
    }
}