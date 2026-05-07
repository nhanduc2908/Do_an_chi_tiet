<?php

namespace App\Services\Auth;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    protected string $secret;
    protected int $ttl;

    public function __construct()
    {
        $this->secret = config('auth.jwt_secret', env('JWT_SECRET'));
        $this->ttl = config('auth.jwt_ttl', 3600);
    }

    public function generateToken(User $user): string
    {
        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + $this->ttl,
            'role' => $user->role,
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function verifyToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function refreshToken(string $token): ?string
    {
        $decoded = $this->verifyToken($token);
        if (!$decoded) return null;
        $user = User::find($decoded->sub);
        return $user ? $this->generateToken($user) : null;
    }

    public function generatePasswordResetToken(User $user): string
    {
        $payload = ['sub' => $user->id, 'type' => 'reset', 'exp' => time() + 1800];
        return JWT::encode($payload, $this->secret, 'HS256');
    }
}