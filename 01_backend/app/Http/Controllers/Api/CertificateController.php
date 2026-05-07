<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        return response()->json(Certificate::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'domain' => 'required|string|unique:certificates',
            'certificate_file' => 'required|file|mimes:pem,crt',
            'private_key_file' => 'required|file|mimes:key,pem',
        ]);

        $certPath = $request->file('certificate_file')->store('certificates');
        $keyPath = $request->file('private_key_file')->store('certificates');

        $certificate = Certificate::create([
            'name' => $request->name,
            'domain' => $request->domain,
            'certificate_path' => $certPath,
            'private_key_path' => $keyPath,
            'expires_at' => $this->getExpiryDate($certPath),
            'created_by' => auth()->id(),
        ]);

        return response()->json($certificate, 201);
    }

    public function show(Certificate $certificate)
    {
        return response()->json($certificate);
    }

    public function destroy(Certificate $certificate)
    {
        Storage::delete([$certificate->certificate_path, $certificate->private_key_path]);
        $certificate->delete();
        return response()->json(['message' => 'Certificate deleted']);
    }

    public function checkExpiry()
    {
        $expiringSoon = Certificate::where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>', now())->get();
        $expired = Certificate::where('expires_at', '<=', now())->get();
        
        return response()->json(['expiring_soon' => $expiringSoon, 'expired' => $expired]);
    }

    protected function getExpiryDate($certPath): ?string
    {
        $certContent = Storage::get($certPath);
        $cert = openssl_x509_read($certContent);
        $certData = openssl_x509_parse($cert);
        return $certData ? date('Y-m-d H:i:s', $certData['validTo_time_t']) : null;
    }
}