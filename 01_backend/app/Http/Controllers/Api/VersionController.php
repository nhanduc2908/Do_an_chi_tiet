<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Version;
use App\Services\Version\VersionManager;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    protected VersionManager $versionManager;

    public function __construct(VersionManager $versionManager)
    {
        $this->versionManager = $versionManager;
    }

    public function index()
    {
        $versions = Version::all();
        return response()->json($versions);
    }

    public function current(Request $request)
    {
        $version = $request->header('X-Version', 'v1');
        $versionData = Version::where('code', $version)->first();
        
        return response()->json([
            'version' => $version,
            'name' => $versionData->name ?? null,
            'features' => $versionData->features ?? [],
        ]);
    }

    public function switch(Request $request)
    {
        $request->validate(['version' => 'required|in:v1,v2,v3,v4,v5']);
        
        $this->versionManager->setCurrent($request->version);
        
        return response()->json([
            'message' => "Switched to version {$request->version}",
            'version' => $request->version,
        ]);
    }

    public function features($version)
    {
        $features = $this->versionManager->getFeatures($version);
        return response()->json($features);
    }

    public function limit($version)
    {
        $limit = $this->versionManager->getLimit($version);
        return response()->json(['max_users' => $limit]);
    }

    public function compare(Request $request)
    {
        $request->validate([
            'version1' => 'required|in:v1,v2,v3,v4,v5',
            'version2' => 'required|in:v1,v2,v3,v4,v5',
        ]);

        $v1 = Version::where('code', $request->version1)->first();
        $v2 = Version::where('code', $request->version2)->first();

        $comparison = [
            'version1' => ['code' => $v1->code, 'name' => $v1->name, 'max_users' => $v1->max_users],
            'version2' => ['code' => $v2->code, 'name' => $v2->name, 'max_users' => $v2->max_users],
            'differences' => [
                'users' => $v2->max_users - $v1->max_users,
                'features' => array_diff($v2->features ?? [], $v1->features ?? []),
            ],
        ];

        return response()->json($comparison);
    }
}