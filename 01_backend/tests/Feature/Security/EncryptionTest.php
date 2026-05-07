<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Services\Security\FHHEncryptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EncryptionTest extends TestCase
{
    protected FHHEncryptionService $encryption;

    protected function setUp(): void
    {
        parent::setUp();
        $this->encryption = new FHHEncryptionService();
    }

    public function test_data_encryption_and_decryption()
    {
        $original = 'Sensitive data here';
        
        $encrypted = $this->encryption->encrypt($original);
        $decrypted = $this->encryption->decrypt($encrypted);
        
        $this->assertEquals($original, $decrypted);
        $this->assertNotEquals($original, $encrypted);
    }

    public function test_different_contexts_produce_different_encryption()
    {
        $data = 'test data';
        
        $encrypted1 = $this->encryption->encrypt($data, 'context1');
        $encrypted2 = $this->encryption->encrypt($data, 'context2');
        
        $this->assertNotEquals($encrypted1, $encrypted2);
    }
}