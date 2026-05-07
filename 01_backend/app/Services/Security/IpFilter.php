<?php

namespace App\Services\Security;

use App\Models\IpWhitelist;
use App\Models\IpBlacklist;

class IpFilter
{
    public function isAllowed(string $ip): bool
    {
        if (IpBlacklist::where('ip_address', $ip)->where(function($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->exists()) {
            return false;
        }
        
        $whitelistExists = IpWhitelist::where('is_active', true)->exists();
        if (!$whitelistExists) return true;
        
        return IpWhitelist::where('ip_address', $ip)->where('is_active', true)->exists();
    }
}