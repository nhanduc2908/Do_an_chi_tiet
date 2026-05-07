<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserKey;
use Illuminate\Support\Facades\Cache;

class KeyService
{
    public function createUserKeys(User $user): void
    {
        $keyPair = sodium_crypto_box_keypair();
        UserKey::create([
            'user_id' => $user->id,
            'public_key' => base64_encode(sodium_crypto_box_publickey($keyPair)),
            'private_key' => base64_encode(sodium_crypto_box_secretkey($keyPair)),
            'key_version' => 1,
            'is_active' => true,
        ]);
    }

    public function generateChallenge(User $user): string
    {
        $challenge = bin2hex(random_bytes(32));
        Cache::put("key_challenge_{$user->id}", $challenge, now()->addMinutes(5));
        return $challenge;
    }

    public function verify(User $user, string $signature, string $challenge): bool
    {
        $cached = Cache::get("key_challenge_{$user->id}");
        if (!$cached || $cached !== $challenge) return false;
        
        $userKey = $user->keys()->where('is_active', true)->first();
        if (!$userKey) return false;
        
        Cache::forget("key_challenge_{$user->id}");
        return true;
    }
}