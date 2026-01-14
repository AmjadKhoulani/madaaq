<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Tenant::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'domain' => 'nullable|string|unique:tenants,domain',
            'plan_id' => 'nullable|integer',
        ]);

        $tenant = \App\Models\Tenant::create($validated);
        return response()->json($tenant, 201);
    }

    public function show(\App\Models\Tenant $tenant)
    {
        return $tenant;
    }

    public function update(Request $request, \App\Models\Tenant $tenant)
    {
        $tenant->update($request->all());
        return $tenant;
    }

    public function destroy(\App\Models\Tenant $tenant)
    {
        $tenant->delete();
        return response()->noContent();
    }
}
