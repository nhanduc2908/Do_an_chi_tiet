<?php

namespace App\Services\Security;

use ParagonIE\Halite\Symmetric\Crypto;
use ParagonIE\Halite\KeyFactory;

class FHHEncryptionService
{
    protected string $masterKey;

    public function __construct()
    {
        $this->masterKey = config('security.fhhe.master_key');
    }

    public function encrypt(string $data, string $context = 'default'): string
    {
        $key = $this->getContextKey($context);
        return base64_encode(Crypto::encrypt($data, $key));
    }

    public function decrypt(string $encryptedData, string $context = 'default'): string
    {
        $key = $this->getContextKey($context);
        return Crypto::decrypt(base64_decode($encryptedData), $key);
    }

    protected function getContextKey(string $context)
    {
        $raw = hash_hmac('sha256', $context, $this->masterKey, true);
        return KeyFactory::importEncryptionKey($raw);
    }
}