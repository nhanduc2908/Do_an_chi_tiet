<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Requests\VerifyKeyRequest;
use App\Models\User;
use App\Models\Role;
use App\Services\Auth\JwtService;
use App\Services\Auth\OtpService;
use App\Services\Auth\KeyService;
use App\Services\Security\FHHEncryptionService;
use App\Services\Audit\LoginTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected JwtService $jwtService;
    protected OtpService $otpService;
    protected KeyService $keyService;
    protected FHHEncryptionService $encryptionService;

    public function __construct(
        JwtService $jwtService,
        OtpService $otpService,
        KeyService $keyService,
        FHHEncryptionService $encryptionService
    ) {
        $this->jwtService = $jwtService;
        $this->otpService = $otpService;
        $this->keyService = $keyService;
        $this->encryptionService = $encryptionService;
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            LoginTracker::record(0, 'failed', $request->ip());
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->status === 'locked') {
            return response()->json(['message' => 'Account locked'], 403);
        }

        $roleConfig = Role::getRolesList()[$user->role] ?? [];
        $requiresOtp = $roleConfig['requires_otp'] ?? false;
        $requiresKey = $roleConfig['requires_key'] ?? false;

        $device = $user->devices()->create([
            'device_id' => $request->device_id ?? bin2hex(random_bytes(16)),
            'device_name' => $request->device_name ?? $request->userAgent(),
            'ip_address' => $request->ip(),
            'session_token' => bin2hex(random_bytes(32)),
        ]);

        LoginTracker::record($user->id, 'pending', $request->ip(), $device->id);

        if ($requiresOtp) {
            $otp = $this->otpService->generate($user);
            $this->otpService->send($user->email, $otp);
            return response()->json([
                'requires_otp' => true,
                'device_id' => $device->device_id,
                'session_token' => $device->session_token,
            ]);
        }

        if ($requiresKey) {
            $challenge = $this->keyService->generateChallenge($user);
            return response()->json([
                'requires_key' => true,
                'challenge' => $challenge,
                'device_id' => $device->device_id,
                'session_token' => $device->session_token,
            ]);
        }

        return $this->completeLogin($user, $device, $request);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $device = $user->devices()->where('device_id', $request->device_id)->first();

        if (!$device || $device->session_token !== $request->session_token) {
            return response()->json(['message' => 'Invalid session'], 401);
        }

        if (!$this->otpService->verify($user, $request->otp)) {
            LoginTracker::record($user->id, 'failed_otp', $request->ip());
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        return $this->completeLogin($user, $device, $request);
    }

    public function verifyKey(VerifyKeyRequest $request)
    {
        $user = User::findOrFail($request->user_id);
        $device = $user->devices()->where('device_id', $request->device_id)->first();

        if (!$device || $device->session_token !== $request->session_token) {
            return response()->json(['message' => 'Invalid session'], 401);
        }

        if (!$this->keyService->verify($user, $request->signature, $request->challenge)) {
            LoginTracker::record($user->id, 'failed_key', $request->ip());
            return response()->json(['message' => 'Invalid key'], 400);
        }

        return $this->completeLogin($user, $device, $request);
    }

    protected function completeLogin(User $user, $device, Request $request)
    {
        $device->update(['last_login_at' => now(), 'is_verified' => true]);
        
        $token = $this->jwtService->generateToken($user);
        $encryptedSession = $this->encryptionService->encrypt(session()->getId(), 'session');
        
        LoginTracker::record($user->id, 'success', $request->ip(), $device->id);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'encrypted_session' => $encryptedSession,
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            LoginTracker::updateLogout($user->id);
        }
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'dev',
        ]);
        
        return response()->json($user, 201);
    }
}