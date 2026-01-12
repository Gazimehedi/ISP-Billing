<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::query()->where('is_active', true);

        if ($request->boolean('no_paginate')) {
            return response()->json($query->get());
        }

        return response()->json($query->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'speed' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $package = Package::create($validated);
        return response()->json($package, 201);
    }

    public function show($id)
    {
        $package = Package::findOrFail($id);
        return response()->json($package);
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'speed' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $package->update($validated);
        return response()->json($package);
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();
        return response()->json(null, 204);
    }
}
