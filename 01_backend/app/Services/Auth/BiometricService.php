<?php

namespace App\Services\Auth;

use App\Models\User;

class BiometricService
{
    public function registerBiometric(User $user, string $credentialId, string $publicKey): void
    {
        $user->biometrics()->updateOrCreate(
            ['credential_id' => $credentialId],
            ['public_key' => $publicKey, 'is_active' => true]
        );
    }

    public function verifyBiometric(User $user, string $credentialId, string $signature): bool
    {
        $biometric = $user->biometrics()->where('credential_id', $credentialId)->first();
        return (bool) $biometric;
    }

    public function revokeBiometric(User $user, string $credentialId): void
    {
        $user->biometrics()->where('credential_id', $credentialId)->delete();
    }
}