<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Box;
use App\Models\SubZone;
use Illuminate\Validation\Rule;

class BoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Box::query()->with(['zone:id,name', 'subZone:id,name']);

        if ($request->has('sub_zone_id')) {
            $query->where('sub_zone_id', $request->input('sub_zone_id'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('subZone', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $boxes = $query->latest()->paginate(10);

        return response()->json($boxes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'sub_zone_id' => [
                'required',
                'exists:sub_zones,id',
                // Verify sub_zone belongs to zone
                function ($attribute, $value, $fail) use ($request) {
                    if (!SubZone::where('id', $value)->where('zone_id', $request->zone_id)->exists()) {
                        $fail('The selected sub zone does not belong to the selected zone.');
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                // Unique name within sub zone
                Rule::unique('boxes')->where(function ($query) use ($request) {
                    return $query->where('sub_zone_id', $request->sub_zone_id);
                }),
            ],
            'description' => 'nullable|string',
        ]);

        $box = Box::create($validated);

        return response()->json($box, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $box = Box::with(['zone', 'subZone'])->findOrFail($id);
        return response()->json($box);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $box = Box::findOrFail($id);

        $validated = $request->validate([
            'zone_id' => 'sometimes|required|exists:zones,id',
            'sub_zone_id' => [
                'sometimes',
                'required',
                'exists:sub_zones,id',
                 function ($attribute, $value, $fail) use ($request, $box) {
                    $zoneId = $request->input('zone_id', $box->zone_id);
                    if (!SubZone::where('id', $value)->where('zone_id', $zoneId)->exists()) {
                        $fail('The selected sub zone does not belong to the selected zone.');
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                 Rule::unique('boxes')->where(function ($query) use ($request, $box) {
                    $subZoneId = $request->input('sub_zone_id', $box->sub_zone_id);
                    return $query->where('sub_zone_id', $subZoneId);
                })->ignore($id),
            ],
            'description' => 'nullable|string',
        ]);

        $box->update($validated);

        return response()->json($box);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $box = Box::findOrFail($id);
        $box->delete();
        return response()->json(null, 204);
    }
}
