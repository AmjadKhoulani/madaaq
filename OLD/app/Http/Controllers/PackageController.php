<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Package::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        return \App\Models\Package::create($validated);
    }

    public function show(\App\Models\Package $package)
    {
        return $package;
    }

    public function update(Request $request, \App\Models\Package $package)
    {
        $package->update($request->all());
        return $package;
    }

    public function destroy(\App\Models\Package $package)
    {
        $package->delete();
        return response()->noContent();
    }
}
