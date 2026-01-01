<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\Olt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Services\Olt\OltService;
use Illuminate\Validation\Rule;

class OltController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Olt::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('host', 'like', "%{$search}%");
            });
        }

        $olts = $query->latest()->paginate(10);

        return response()->json($olts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:olts,name',
            'type' => 'required|string|max:255',
            'pon_ports' => 'required|integer|min:1',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string',
            'host' => 'required|string|max:255',
            'port' => 'required|integer',
            'status' => 'boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Crypt::encryptString($validated['password']);
        }

        $olt = Olt::create($validated);

        return response()->json([
            'message' => 'OLT created successfully',
            'data' => $olt
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Olt $olt)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('olts')->ignore($olt->id),
            ],
            'type' => 'required|string|max:255',
            'pon_ports' => 'required|integer|min:1',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string',
            'host' => 'required|string|max:255',
            'port' => 'required|integer',
            'status' => 'boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Crypt::encryptString($validated['password']);
        } else {
            unset($validated['password']); // Don't overwrite if not provided
        }

        $olt->update($validated);

        return response()->json([
            'message' => 'OLT updated successfully',
            'data' => $olt
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Olt $olt)
    {
        $olt->delete();

        return response()->json([
            'message' => 'OLT deleted successfully'
        ]);
    }

    /**
     * Test connection to the OLT.
     */
    public function testConnection(Olt $olt, OltService $service)
    {
        try {
            $service->testConnection($olt);
            return response()->json([
                'message' => 'Connected successfully',
                'status' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Connection failed: ' . $e->getMessage(),
                'status' => false
            ], 400);
        }
    }

    /**
     * Reboot the OLT.
     */
    public function reboot(Olt $olt, OltService $service)
    {
        // Check for onu_id in request
        $onuId = request('onu_id');
        
        try {
             $success = $service->reboot($olt, $onuId);
             if ($success) {
                  return response()->json(['message' => 'Reboot command sent successfully']);
             }
        } catch (\Exception $e) {
             return response()->json(['message' => 'Failed to send reboot command: ' . $e->getMessage()], 500);
        }
        
        return response()->json(['message' => 'Failed to send reboot command'], 500);
    }

    public function syncUsers(Olt $olt, OltService $service)
    {
        try {
            $data = $service->syncUsers($olt);
            return response()->json([
                'message' => 'User sync completed',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sync failed: ' . $e->getMessage()], 500);
        }
    }

    public function checkOnuInfo(Olt $olt, $onuId, OltService $service)
    {
        try {
            $data = $service->checkOnuInfo($olt, $onuId);
            return response()->json([
                'message' => 'ONU Info retrieved',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Check failed: ' . $e->getMessage()], 500);
        }
    }
}
