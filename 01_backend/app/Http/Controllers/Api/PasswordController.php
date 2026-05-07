<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    protected PasswordResetService $passwordReset;

    public function __construct(PasswordResetService $passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $token = $this->passwordReset->createToken($request->email);
        
        // Send reset email
        // Mail::to($request->email)->send(new PasswordResetMail($token));
        
        return response()->json(['message' => 'Reset link sent to your email']);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = $this->passwordReset->reset(
            $request->email,
            $request->token,
            $request->password
        );

        if (!$reset) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        return response()->json(['message' => 'Password reset successfully']);
    }

    public function change(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password changed']);
    }

    public function validateToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        
        $valid = $this->passwordReset->validateToken($request->token);
        
        return response()->json(['valid' => $valid]);
    }
}