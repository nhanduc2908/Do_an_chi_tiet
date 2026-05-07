<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;

class NotificationTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $templates = NotificationTemplate::all();
        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:notification_templates',
            'subject' => 'required|string',
            'body' => 'required|string',
            'type' => 'required|string',
        ]);

        $template = NotificationTemplate::create($validated);
        return response()->json($template, 201);
    }

    public function update(Request $request, $id)
    {
        $template = NotificationTemplate::findOrFail($id);
        
        $validated = $request->validate([
            'subject' => 'sometimes|string',
            'body' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $template->update($validated);
        return response()->json($template);
    }

    public function destroy($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        $template->delete();
        return response()->json(['message' => 'Template deleted']);
    }

    public function preview($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        
        $preview = str_replace(
            ['{name}', '{email}', '{date}'],
            ['John Doe', 'john@example.com', now()->format('Y-m-d')],
            $template->body
        );
        
        return response()->json(['preview' => $preview]);
    }
}