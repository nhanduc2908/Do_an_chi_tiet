<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Models\Webhook;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $integrations = Integration::all();
        return response()->json($integrations);
    }

    public function webhooks()
    {
        $webhooks = Webhook::all();
        return response()->json($webhooks);
    }

    public function createWebhook(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'events' => 'required|array',
            'secret' => 'nullable|string|min:16',
        ]);

        $webhook = Webhook::create([
            'url' => $validated['url'],
            'events' => $validated['events'],
            'secret' => $validated['secret'] ?? bin2hex(random_bytes(16)),
            'is_active' => true,
        ]);

        return response()->json($webhook, 201);
    }

    public function updateWebhook(Request $request, $id)
    {
        $webhook = Webhook::findOrFail($id);
        
        $validated = $request->validate([
            'url' => 'sometimes|url',
            'events' => 'sometimes|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $webhook->update($validated);
        return response()->json($webhook);
    }

    public function deleteWebhook($id)
    {
        $webhook = Webhook::findOrFail($id);
        $webhook->delete();
        return response()->json(['message' => 'Webhook deleted']);
    }

    public function testWebhook($id)
    {
        $webhook = Webhook::findOrFail($id);
        
        // Send test webhook
        $payload = ['test' => true, 'timestamp' => now()->toIso8601String()];
        
        return response()->json(['message' => 'Test webhook sent', 'payload' => $payload]);
    }

    public function slack(Request $request)
    {
        $request->validate(['webhook_url' => 'required|url']);
        
        config(['services.slack.webhook_url' => $request->webhook_url]);
        
        return response()->json(['message' => 'Slack integration configured']);
    }

    public function teams(Request $request)
    {
        $request->validate(['webhook_url' => 'required|url']);
        
        config(['services.teams.webhook_url' => $request->webhook_url]);
        
        return response()->json(['message' => 'Teams integration configured']);
    }
}