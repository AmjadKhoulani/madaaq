<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Router;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    public function index()
    {
        $routers = Router::where('tenant_id', auth()->user()->tenant_id)
            ->withCount(['clients'])
            ->get()
            ->map(function($router) {
                return [
                    'id' => $router->id,
                    'name' => $router->name,
                    'ip_address' => $router->ip_address,
                    'is_online' => $router->is_online, 
                    'clients_count' => $router->clients_count,
                    'model' => $router->model,
                ];
            });
            
        return response()->json(['data' => $routers]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'username' => 'required|string',
            'password' => 'required|string',
            'port' => 'nullable|integer',
            'tower_id' => 'nullable|exists:towers,id',
        ]);
        
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $router = Router::create($validated);
        
        return response()->json($router, 201);
    }

    public function show($id)
    {
        $router = Router::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        return response()->json($router);
    }

    public function update(Request $request, $id)
    {
        $router = Router::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'port' => 'nullable|integer',
            'tower_id' => 'nullable|exists:towers,id',
        ]);
        
        $router->update($validated);
        return response()->json($router);
    }

    public function destroy($id)
    {
        $router = Router::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        $router->delete();
        return response()->json(['message' => 'Router deleted']);
    }
}
