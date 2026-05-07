<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use App\Models\WebhookDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function index()
    {
        $webhooks = Webhook::all();
        return response()->json($webhooks);
    }

    public function store(Request $request)
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

    public function show(Webhook $webhook)
    {
        return response()->json($webhook);
    }

    public function update(Request $request, Webhook $webhook)
    {
        $validated = $request->validate([
            'url' => 'sometimes|url',
            'events' => 'sometimes|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $webhook->update($validated);
        return response()->json($webhook);
    }

    public function destroy(Webhook $webhook)
    {
        $webhook->delete();
        return response()->json(['message' => 'Webhook deleted']);
    }

    public function deliveries(Webhook $webhook)
    {
        $deliveries = $webhook->deliveries()->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($deliveries);
    }

    public function retry(Webhook $webhook, $deliveryId)
    {
        $delivery = WebhookDelivery::findOrFail($deliveryId);
        
        $response = Http::timeout(10)
            ->withHeaders(['X-Retry' => $delivery->attempt + 1])
            ->post($webhook->url, $delivery->payload);

        $delivery->update([
            'response_code' => $response->status(),
            'status' => $response->successful() ? 'success' : 'failed',
            'attempt' => $delivery->attempt + 1,
        ]);

        return response()->json(['message' => 'Webhook retried']);
    }

    public function test(Webhook $webhook)
    {
        $payload = ['test' => true, 'timestamp' => now()->toIso8601String()];
        
        $signature = hash_hmac('sha256', json_encode($payload), $webhook->secret);
        
        $response = Http::timeout(10)
            ->withHeaders(['X-Webhook-Signature' => $signature])
            ->post($webhook->url, $payload);

        WebhookDelivery::create([
            'webhook_id' => $webhook->id,
            'event' => 'test',
            'payload' => $payload,
            'response_code' => $response->status(),
            'status' => $response->successful() ? 'success' : 'failed',
            'attempt' => 1,
        ]);

        return response()->json([
            'success' => $response->successful(),
            'status_code' => $response->status(),
        ]);
    }
}