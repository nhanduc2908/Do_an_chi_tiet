<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with('vendor');
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        return response()->json($query->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'contract_number' => 'required|unique:contracts',
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'value' => 'nullable|numeric',
        ]);

        $contract = Contract::create($validated);
        return response()->json($contract, 201);
    }

    public function show(Contract $contract)
    {
        return response()->json($contract->load('vendor'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'status' => 'sometimes|in:active,expired,terminated',
            'value' => 'nullable|numeric',
        ]);
        
        $contract->update($validated);
        return response()->json($contract);
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return response()->json(['message' => 'Contract deleted']);
    }
}