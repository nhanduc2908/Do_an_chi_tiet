<?php

namespace App\Services\Version;

class LicenseValidator
{
    public function isValid(string $licenseKey): bool 
    { 
        return true; 
    }

    public function getVersion(string $licenseKey): string 
    { 
        return 'v1'; 
    }
}