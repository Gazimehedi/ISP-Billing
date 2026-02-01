<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MikrotikRouter;
use Illuminate\Support\Facades\Crypt;
use App\Services\RouterOSService;

class MikrotikRouterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MikrotikRouter::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
        }

        if ($request->boolean('no_paginate')) {
            return response()->json($query->get());
        }

        $routers = $query->latest()->paginate(10);

        return response()->json($routers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:mikrotik_routers',
            'ip_address' => 'required|ipv4',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'port' => 'required|integer|between:1,65535',
            'sync_timer_unit' => 'required|in:seconds,minutes',
            'sync_timer_value' => 'required|integer|min:1',
        ]);

        // Encrypt password before saving
        $validated['password'] = Crypt::encryptString($validated['password']);

        $router = MikrotikRouter::create($validated);

        return response()->json($router, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $router = MikrotikRouter::findOrFail($id);
        // Do not expose password
        $router->makeHidden(['password']);
        return response()->json($router);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $router = MikrotikRouter::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:mikrotik_routers,name,' . $id,
            'ip_address' => 'required|ipv4',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string', // Nullable on update
            'port' => 'required|integer|between:1,65535',
            'sync_timer_unit' => 'required|in:seconds,minutes',
            'sync_timer_value' => 'required|integer|min:1',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Crypt::encryptString($validated['password']);
        } else {
            unset($validated['password']);
        }

        $router->update($validated);

        return response()->json($router);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $router = MikrotikRouter::findOrFail($id);
        $router->delete();
        return response()->json(null, 204);
    }

    /**
     * Test connection to the router.
     */
    public function testConnection(Request $request, RouterOSService $service)
    {
        $request->validate([
            'ip_address' => 'required|ipv4',
            'username' => 'required|string',
            'password' => 'required|string',
            'port' => 'required|integer',
        ]);

        $connected = $service->connect(
            $request->ip_address,
            $request->username,
            $request->password,
            $request->port
        );

        if ($connected) {
            $service->disconnect();
            return response()->json(['message' => 'Connection successful!'], 200);
        }

        return response()->json([
            'message' => 'Connection failed: ' . $service->getError()
        ], 400);
    }

    /**
     * Test connection to the router by ID (using stored credentials).
     */
    public function testConnectionById(string $id, RouterOSService $service)
    {
        $router = MikrotikRouter::findOrFail($id);

        try {
            $password = Crypt::decryptString($router->password);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json(['message' => 'Could not decrypt password.'], 500);
        }

        $connected = $service->connect(
            $router->ip_address,
            $router->username,
            $password,
            $router->port
        );

        if ($connected) {
            $service->disconnect();
            return response()->json(['message' => 'Connection successful!'], 200);
        }

        return response()->json([
            'message' => 'Connection failed: ' . $service->getError()
        ], 400);
    }


    /**
     * Sync data from all routers.
     */
    public function sync(Request $request)
    {
        // Mock sync process
        return response()->json(['message' => 'Sync started successfully']);
    }
}
