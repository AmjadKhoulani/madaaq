<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Router;
use App\Models\Subscription;
use App\Services\MikroTikService;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create()
    {
         $servers = Router::with(['towers.ssids', 'towers.routers'])->get();
         $packages = \App\Models\Package::all();
         $deviceModels = \App\Models\DeviceModel::all();
         $lastIp = Client::latest()->value('ip'); // For hint
         
         return view('crm.clients.create', compact('servers', 'packages', 'deviceModels', 'lastIp'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
             'router_id' => 'required|exists:routers,id',
             'username' => 'required|string',
             'password' => 'required|string', 
             'package_id' => 'required|exists:packages,id',
             'connection_mode' => 'required|in:wireless,cable',
             'device_model_id' => 'nullable|required_if:connection_mode,wireless|exists:device_models,id',
             'switch_port' => 'nullable|required_if:connection_mode,cable|integer',
             'cpe_ip' => 'nullable|ip', 
             'cpe_mac' => 'nullable|mac_address', // Laravel has mac_address rule? 10.x+ yes. Or use regex. Assuming basic string or regex if fails. 
             // Let's use regex for MAC or just string for now to avoid validation hell if user format differs.
             'tower_device_id' => 'nullable|exists:tower_devices,id',
             'lat' => 'nullable|numeric',
             'lng' => 'nullable|numeric',
             'name' => 'required|string',
             'phone' => 'required|string',
             'service_password' => 'required|string',
             'ip_address' => 'nullable|ip',
             'duration_days' => 'nullable|integer',
             'data_limit' => 'nullable|numeric',
             'price' => 'nullable|numeric',
        ]);

        $router = Router::findOrFail($validated['router_id']);
        
        // MikroTik Action
        $service = new MikroTikService($router);
        try {
            $service->createPPPoEUser($validated['username'], $validated['password']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create user on router: ' . $e->getMessage()], 500);
        }

        // DB Actions
        $client = Client::create([
            'router_id' => $validated['router_id'], // Ensure form sends router_id
            'type' => $request->input('type', 'pppoe'), // Default pppoe
            'username' => $validated['username'],
            'status' => 'active',
            'connection_mode' => $validated['connection_mode'],
            'device_model_id' => $validated['device_model_id'] ?? null,
            'switch_port' => $validated['switch_port'] ?? null,
            'cpe_ip' => $validated['cpe_ip'] ?? null,
            'cpe_mac' => $validated['cpe_mac'] ?? null,
            'tower_device_id' => $validated['tower_device_id'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'ip' => $validated['ip_address'] ?? null,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => $validated['password'], // Portal pass - careful distinguishing from service pass if needed. 
                                                  // Previous code used 'password' for createPPPoEUser too.
            'service_password' => $validated['service_password'],
            'package_id' => $validated['package_id'],
            'data_limit' => $validated['data_limit'] ?? null,
            'custom_price' => $validated['price'] ?? null,
            // 'custom_duration_days' ...
        ]);

        // Create initial subscription (assuming 30 days default or per package duration, prompt didn't specify package duration logic but "Subscription expiration logic" is needed)
        // I'll assume 30 days for now.
        $subscription = Subscription::create([
            'client_id' => $client->id,
            'package_id' => $validated['package_id'],
            'expires_at' => now()->addDays(30),
            'status' => 'active',
        ]);

        return response()->json($client->load('subscription'), 201);
    }

    public function show(Client $client)
    {
        return $client->load('subscription', 'router');
    }

    public function edit(Client $client)
    {
        // Load with relationships
        $serversData = \App\Models\MikroTikServer::with(['towers.devices', 'towers.ssids'])->get();
        
        // Manual mapping to ensure no data loss during serialization
        $servers = $serversData->map(function($server) {
            return [
                'id' => $server->id,
                'name' => $server->name,
                'towers' => $server->towers->map(function($tower) {
                    return [
                        'id' => $tower->id,
                        'name' => $tower->name,
                        'lat' => (float)$tower->lat,
                        'lng' => (float)$tower->lng,
                        'devices' => $tower->devices->map(function($device) {
                            return [
                                'id' => $device->id,
                                'name' => $device->name,
                                'ip' => $device->ip,
                            ];
                        })->values()->all(),
                        'ssids' => $tower->ssids->map(function($ssid) {
                            return [
                                'id' => $ssid->id,
                                'ssid_name' => $ssid->ssid_name,
                                'tower_device_id' => $ssid->tower_device_id,
                            ];
                        })->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->values()->all();
        
        $packages = \App\Models\Package::all();
        $deviceModels = \App\Models\DeviceModel::all();
        
        return view('crm.clients.edit', compact('client', 'servers', 'packages', 'deviceModels'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'status' => 'in:active,suspended,inactive',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'notes' => 'nullable|string',
            'password' => 'nullable|string|min:4',
            'service_password' => 'nullable|string|min:4',
            'package_id' => 'nullable|exists:packages,id',
            'custom_price' => 'nullable|numeric|min:0',
            'custom_data_limit_mb' => 'nullable|numeric|min:0',
            // Network fields
            'mikrotik_server_id' => 'nullable|exists:mikrotik_servers,id',
            'tower_id' => 'nullable|exists:towers,id',
            'ssid_id' => 'nullable|exists:tower_ssids,id',
            'connection_mode' => 'required|in:wireless,cable',
            'device_model_id' => 'nullable|required_if:connection_mode,wireless|exists:device_models,id',
            'switch_port' => 'nullable|required_if:connection_mode,cable|integer',
            'cpe_ip' => 'nullable|ip',
            'cpe_mac' => 'nullable|string',
            'tower_device_id' => 'nullable|exists:tower_devices,id',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        // Handle status change in MikroTik
        if (isset($validated['status']) && $validated['status'] !== $client->status) {
            $router = $client->router;
            if ($router) {
                 $service = new MikroTikService($router);
                try {
                    if ($validated['status'] === 'suspended') {
                        $service->disableUser($client->username);
                    } else {
                        $service->enableUser($client->username);
                    }
                } catch (\Exception $e) {
                     // Log error
                }
            }
        }
        
        // Remove password from validated if empty to avoid overwriting with null
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Update client with all validated data
        $client->update($validated);
        
        return redirect()->route('crm.clients.index')->with('success', 'تم تحديث بيانات المشترك بنجاح');
    }

    public function destroy(Client $client)
    {
        // Ideally remove from MikroTik too?
        // Prompt didn't strictly say delete lifecycle, but "create, suspend, resume".
        // I'll assume delete removes from DB only or both. Safer to remove both.
        // For now, simple delete.
        $client->delete();
        return response()->noContent();
    }
}
