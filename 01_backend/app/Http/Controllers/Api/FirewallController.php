<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FirewallRule;
use App\Models\IpWhitelist;
use App\Models\IpBlacklist;
use Illuminate\Http\Request;

class FirewallController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,secops');
    }

    public function rules(Request $request)
    {
        $rules = FirewallRule::orderBy('priority')->get();
        return response()->json($rules);
    }

    public function createRule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'source_ip' => 'nullable|ip',
            'destination_ip' => 'nullable|ip',
            'port' => 'nullable|integer|min:1|max:65535',
            'protocol' => 'nullable|in:tcp,udp,icmp',
            'action' => 'required|in:allow,deny',
            'priority' => 'integer',
        ]);

        $rule = FirewallRule::create($validated);
        return response()->json($rule, 201);
    }

    public function updateRule(Request $request, $id)
    {
        $rule = FirewallRule::findOrFail($id);
        $rule->update($request->only(['name', 'action', 'priority', 'is_active']));
        return response()->json($rule);
    }

    public function deleteRule($id)
    {
        $rule = FirewallRule::findOrFail($id);
        $rule->delete();
        return response()->json(['message' => 'Rule deleted']);
    }

    public function whitelist(Request $request)
    {
        $whitelist = IpWhitelist::where('is_active', true)->get();
        return response()->json($whitelist);
    }

    public function addToWhitelist(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:ip_whitelist',
            'label' => 'nullable|string',
        ]);

        $ip = IpWhitelist::create([
            'ip_address' => $request->ip_address,
            'label' => $request->label,
            'is_active' => true,
        ]);

        return response()->json($ip, 201);
    }

    public function removeFromWhitelist($id)
    {
        $ip = IpWhitelist::findOrFail($id);
        $ip->delete();
        return response()->json(['message' => 'Removed from whitelist']);
    }

    public function blacklist(Request $request)
    {
        $blacklist = IpBlacklist::orderBy('blocked_at', 'desc')->get();
        return response()->json($blacklist);
    }

    public function addToBlacklist(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:ip_blacklist',
            'reason' => 'required|string',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $ip = IpBlacklist::create([
            'ip_address' => $request->ip_address,
            'reason' => $request->reason,
            'blocked_by' => auth()->id(),
            'expires_at' => $request->expires_at,
        ]);

        return response()->json($ip, 201);
    }

    public function removeFromBlacklist($id)
    {
        $ip = IpBlacklist::findOrFail($id);
        $ip->delete();
        return response()->json(['message' => 'Removed from blacklist']);
    }
}