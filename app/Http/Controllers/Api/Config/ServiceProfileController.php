<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\ServiceProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceProfileController extends Controller
{
    /**
     * Display a listing of service profiles.
     */
    public function index(Request $request)
    {
        $query = ServiceProfile::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->boolean('no_paginate')) {
            return response()->json($query->get());
        }

        $profiles = $query->latest()->paginate($request->per_page ?? 15);
        return response()->json($profiles);
    }

    /**
     * Store a newly created service profile.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:service_profiles',
            'description' => 'nullable|string',
            'bandwidth_up' => 'required|integer|min:1',
            'bandwidth_down' => 'required|integer|min:1',
            'vlan_mode' => 'required|in:transparent,tag,translation',
            'vlan_id' => 'nullable|integer|min:1|max:4094',
            'cvlan_id' => 'nullable|integer|min:1|max:4094',
            'priority' => 'nullable|integer|min:0|max:7',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profile = ServiceProfile::create($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Service profile created successfully',
            'data' => $profile
        ], 201);
    }

    /**
     * Display the specified service profile.
     */
    public function show(ServiceProfile $serviceProfile)
    {
        return response()->json($serviceProfile->load('oltUsers'));
    }

    /**
     * Update the specified service profile.
     */
    public function update(Request $request, ServiceProfile $serviceProfile)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:service_profiles,name,' . $serviceProfile->id,
            'description' => 'nullable|string',
            'bandwidth_up' => 'required|integer|min:1',
            'bandwidth_down' => 'required|integer|min:1',
            'vlan_mode' => 'required|in:transparent,tag,translation',
            'vlan_id' => 'nullable|integer|min:1|max:4094',
            'cvlan_id' => 'nullable|integer|min:1|max:4094',
            'priority' => 'nullable|integer|min:0|max:7',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $serviceProfile->update($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Service profile updated successfully',
            'data' => $serviceProfile
        ]);
    }

    /**
     * Remove the specified service profile.
     */
    public function destroy(ServiceProfile $serviceProfile)
    {
        // Check if profile is in use
        if ($serviceProfile->oltUsers()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete profile that is assigned to ONUs'
            ], 400);
        }

        $serviceProfile->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Service profile deleted successfully'
        ]);
    }
}
