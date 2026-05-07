<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Company;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::with('company')->get();
        return response()->json($licenses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'product_name' => 'required|string',
            'version' => 'required|string',
            'max_users' => 'required|integer|min:1',
            'expires_at' => 'required|date|after:now',
        ]);

        $licenseKey = 'SEC-' . strtoupper(bin2hex(random_bytes(8)));

        $license = License::create([
            'license_key' => $licenseKey,
            'company_id' => $validated['company_id'],
            'product_name' => $validated['product_name'],
            'version' => $validated['version'],
            'max_users' => $validated['max_users'],
            'expires_at' => $validated['expires_at'],
            'is_active' => true,
        ]);

        return response()->json($license, 201);
    }

    public function show(License $license)
    {
        return response()->json($license);
    }

    public function update(Request $request, License $license)
    {
        $validated = $request->validate([
            'max_users' => 'sometimes|integer|min:1',
            'expires_at' => 'sometimes|date',
            'is_active' => 'sometimes|boolean',
        ]);

        $license->update($validated);
        return response()->json($license);
    }

    public function validateLicense(Request $request)
    {
        $request->validate(['license_key' => 'required|string']);
        
        $license = License::where('license_key', $request->license_key)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$license) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired license'], 400);
        }

        return response()->json([
            'valid' => true,
            'product' => $license->product_name,
            'version' => $license->version,
            'max_users' => $license->max_users,
            'expires_at' => $license->expires_at,
        ]);
    }

    public function revoke($id)
    {
        $license = License::findOrFail($id);
        $license->update(['is_active' => false]);
        return response()->json(['message' => 'License revoked']);
    }
}