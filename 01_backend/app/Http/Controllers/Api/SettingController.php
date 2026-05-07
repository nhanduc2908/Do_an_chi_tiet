<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }

        return response()->json(['message' => 'Settings updated']);
    }

    public function get($key)
    {
        $setting = Setting::where('key', $key)->first();
        return response()->json(['value' => $setting->value ?? null]);
    }

    public function set(Request $request, $key)
    {
        $request->validate(['value' => 'required']);
        
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $request->value, 'group' => 'general']
        );

        return response()->json(['message' => 'Setting saved']);
    }

    public function delete($key)
    {
        Setting::where('key', $key)->delete();
        return response()->json(['message' => 'Setting deleted']);
    }
}