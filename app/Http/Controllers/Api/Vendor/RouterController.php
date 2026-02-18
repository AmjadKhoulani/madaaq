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

        // Generate WireGuard Keys using Sodium
        try {
            $keyPair = sodium_crypto_box_keypair();
            $privateKey = sodium_crypto_box_secretkey($keyPair);
            $publicKey = sodium_crypto_box_publickey($keyPair);
            
            $validated['wireguard_private_key'] = base64_encode($privateKey);
            $validated['wireguard_public_key'] = base64_encode($publicKey);
            $validated['wireguard_enabled'] = true;
        } catch (\Throwable $e) {
            \Log::warning('Router WireGuard Key Gen Failed: ' . $e->getMessage());
        }

        $router = Router::create($validated);

        // Assign WireGuard IP based on ID (Offset to avoid collisions with Servers)
        // Servers start at 201.10.0.1. We can give Routers 201.10.100.x range or similar.
        // Actually, to avoid any collision, let's use a unique offset.
        try {
            $routerId = $router->id + 1000; // Offset for Routers
            $ipOctet3 = floor($routerId / 255) % 255; 
            $ipOctet4 = $routerId % 255;
            if ($ipOctet4 == 0) $ipOctet4 = 1; 
            if ($ipOctet4 == 255) $ipOctet4 = 254;
            
            $router->wireguard_ip = "201.10.{$ipOctet3}.{$ipOctet4}";
            $router->save();

            \Log::info("Router created. Pending WireGuard Sync for: {$router->name}");
        } catch (\Exception $e) {
            \Log::error('Router WireGuard IP Assignment Failed: ' . $e->getMessage());
        }
        
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
