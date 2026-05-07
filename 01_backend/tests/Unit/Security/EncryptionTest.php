<?php

namespace Tests\Unit\Security;

use Tests\TestCase;
use App\Services\Security\FHHEncryptionService;

class EncryptionTest extends TestCase
{
    protected FHHEncryptionService $encryption;

    protected function setUp(): void
    {
        parent::setUp();
        $this->encryption = new FHHEncryptionService();
    }

    public function test_encrypt_and_decrypt_returns_original()
    {
        $original = 'This is secret data';
        
        $encrypted = $this->encryption->encrypt($original);
        $decrypted = $this->encryption->decrypt($encrypted);
        
        $this->assertEquals($original, $decrypted);
    }

    public function test_encrypted_data_is_different()
    {
        $original = 'secret';
        $encrypted = $this->encryption->encrypt($original);
        
        $this->assertNotEquals($original, $encrypted);
    }
}