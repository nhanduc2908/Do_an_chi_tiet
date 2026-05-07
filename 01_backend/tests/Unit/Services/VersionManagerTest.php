<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Version\VersionManager;

class VersionManagerTest extends TestCase
{
    protected VersionManager $versionManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->versionManager = new VersionManager();
    }

    public function test_get_all_versions()
    {
        $versions = $this->versionManager->getAll();
        
        $this->assertCount(5, $versions);
        $this->assertEquals(['v1', 'v2', 'v3', 'v4', 'v5'], $versions);
    }

    public function test_get_limit_by_version()
    {
        $this->assertEquals(10, $this->versionManager->getLimit('v1'));
        $this->assertEquals(50, $this->versionManager->getLimit('v2'));
        $this->assertEquals(200, $this->versionManager->getLimit('v3'));
        $this->assertEquals(500, $this->versionManager->getLimit('v4'));
        $this->assertEquals(9999, $this->versionManager->getLimit('v5'));
    }
}