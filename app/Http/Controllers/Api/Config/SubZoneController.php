<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubZone;

class SubZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SubZone::query()->with(['zone:id,name'])->withCount('boxes');

        if ($request->has('zone_id')) {
            $query->where('zone_id', $request->input('zone_id'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('zone', function ($zq) use ($search) {
                      $zq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('no_paginate')) {
            return response()->json($query->get());
        }

        $subZones = $query->latest()->paginate(10);

        return response()->json($subZones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => [
                'required',
                'string',
                'max:255',
                // Unique name within the zone
                function ($attribute, $value, $fail) use ($request) {
                    if (SubZone::where('zone_id', $request->zone_id)->where('name', $value)->exists()) {
                        $fail('The sub zone name has already been taken in this zone.');
                    }
                },
            ],
            'description' => 'nullable|string',
        ]);

        $subZone = SubZone::create($validated);

        return response()->json($subZone, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subZone = SubZone::with(['zone', 'boxes'])->withCount('boxes')->findOrFail($id);
        return response()->json($subZone);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subZone = SubZone::findOrFail($id);

        $validated = $request->validate([
            'zone_id' => 'sometimes|required|exists:zones,id',
            'name' => [
                'required',
                'string',
                'max:255',
                 // Unique name within the zone (excluding current)
                 function ($attribute, $value, $fail) use ($request, $subZone) {
                    $zoneId = $request->input('zone_id', $subZone->zone_id);
                    if (SubZone::where('zone_id', $zoneId)
                        ->where('name', $value)
                        ->where('id', '!=', $subZone->id)
                        ->exists()) {
                        $fail('The sub zone name has already been taken in this zone.');
                    }
                },
            ],
            'description' => 'nullable|string',
        ]);

        $subZone->update($validated);

        return response()->json($subZone);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subZone = SubZone::findOrFail($id);
        $subZone->delete();
        return response()->json(null, 204);
    }
}
