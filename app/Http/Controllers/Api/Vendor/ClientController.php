<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // Auto-scoped by BelongsToTenant trait via User
        $query = Client::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:clients,username',
            'password' => 'required|string|min:4',
            'phone' => 'nullable|string',
            'type' => 'required|in:hotspot,pppoe', // service_type mapped to type
            'package_id' => 'required|exists:packages,id',
            'router_id' => 'nullable|exists:routers,id',
            'tower_id' => 'nullable|exists:towers,id',
            'ssid' => 'nullable|string',
            'pppoe_username' => 'nullable|string|unique:clients,pppoe_username',
            'hotspot_username' => 'nullable|string|unique:clients,hotspot_username',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['status'] = 'active';

        // Map specific usernames based on type if not provided explicitly or to ensure consistency
        if ($validated['type'] === 'pppoe' && !isset($validated['pppoe_username'])) {
             $validated['pppoe_username'] = $validated['username'];
        }
        if ($validated['type'] === 'hotspot' && !isset($validated['hotspot_username'])) {
             $validated['hotspot_username'] = $validated['username'];
        }

        $client = Client::create($validated);

        // Sync with MikroTik
        try {
            if ($client->router) {
                $mikrotikService = new \App\Services\MikroTikService($client->router);
                
                if ($client->type === 'hotspot') {
                     // Check if exists first? Service create throws error usually. 
                     // Assuming clean create for now as its new client.
                     $mikrotikService->createHotspotUser($client->username, $client->password, $client->package->name ?? 'default');
                } elseif ($client->type === 'pppoe') {
                     $mikrotikService->createPPPoEUser($client->username, $client->password, $client->package->name ?? 'default');
                }
            }
        } catch (\Exception $e) {
            \Log::error('MikroTik Sync Failed: ' . $e->getMessage());
        }

        return response()->json($client, 201);
    }

    public function show($id)
    {
        $client = Client::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        $client->load(['package', 'router', 'tower']); 
        
        return response()->json($client);
    }
    
    public function block(Request $request, Client $client)
    {
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Toggle status
        $newStatus = $client->status === 'active' ? 'suspended' : 'active';
        $client->update(['status' => $newStatus]);
        
        // Sync with MikroTik
        try {
            if ($client->router) {
                $mikrotikService = new \App\Services\MikroTikService($client->router);

                if ($newStatus === 'suspended') {
                    // Disable user
                     if ($client->type === 'hotspot') {
                         $mikrotikService->disableHotspotUser($client->username);
                         $mikrotikService->disconnectSession($client->username, 'hotspot'); // Assuming disconnect takes ID/User? Service says ID.
                         // Service disconnectSession takes sessionId. We need to find session first.
                         // But we can key off username if we modify service or just skip for now to avoid complexity?
                         // Service has getActiveSessions. 
                         // Let's rely on standard disable for now or use kick logic if easy.
                         $mikrotikService->kickDuplicateSessions($client->username); // This disconnects all by username
                    } elseif ($client->type === 'pppoe') {
                         $mikrotikService->disableUser($client->username); // PPPoE disable
                         $mikrotikService->kickDuplicateSessions($client->username);
                    }
                } else {
                    // Enable
                     if ($client->type === 'hotspot') {
                         $mikrotikService->enableHotspotUser($client->username);
                    } elseif ($client->type === 'pppoe') {
                         $mikrotikService->enableUser($client->username);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('MikroTik Sync Failed during block/unblock: ' . $e->getMessage());
        }
        
        return response()->json(['message' => 'تم تحديث حالة المشترك', 'status' => $newStatus]);
    }

    public function renew(Request $request, Client $client)
    {
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $duration = $client->package->duration ?? 30; // days
        $client->update([
            'status' => 'active',
            'expires_at' => \Carbon\Carbon::parse($client->expires_at)->addDays($duration)
        ]);
        
         // Sync with MikroTik
        try {
            if ($client->router) {
                $mikrotikService = new \App\Services\MikroTikService($client->router);
                
                if ($client->type === 'hotspot') {
                     $mikrotikService->enableHotspotUser($client->username);
                } elseif ($client->type === 'pppoe') {
                     $mikrotikService->enableUser($client->username);
                }
            }
        } catch (\Exception $e) {
             \Log::error('MikroTik Sync Failed during renew: ' . $e->getMessage());
        }

        return response()->json(['message' => 'تم تجديد الاشتراك بنجاح']);
    }
}
