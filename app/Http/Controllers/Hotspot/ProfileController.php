<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Package::where('type', 'hotspot')->get();
        return view('hotspot.profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('hotspot.profiles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $validated['type'] = 'hotspot';
        $package = Package::create($validated);

        // SYNC: Create Profile on all MikroTik devices
        $this->syncProfileToMikroTik($package, 'create');

        return redirect()->route('hotspot.profiles.index')->with('success', 'Hotspot Profile created and synced to routers.');
    }

    public function destroy(Package $profile)
    {
        $name = $profile->name;
        $profile->delete();

        // SYNC: Delete Profile from all MikroTik devices
        $this->syncProfileToMikroTik(null, 'delete', $name);

        return redirect()->route('hotspot.profiles.index')->with('success', 'Profile deleted successfully.');
    }

    /**
     * Sync Profile to all Tenant Routers & Servers
     */
    protected function syncProfileToMikroTik($package, $action, $oldName = null)
    {
        $tenantId = auth()->user()->tenant_id ?? 1;
        
        // Fetch all devices (Routers and Servers)
        $routers = \App\Models\Router::where('tenant_id', $tenantId)->get();
        $servers = \App\Models\MikroTikServer::where('tenant_id', $tenantId)->get();
        $allDevices = $routers->concat($servers);

        foreach ($allDevices as $device) {
            try {
                $service = new \App\Services\MikroTikService($device);
                
                if ($action === 'create') {
                     // rate-limit: "rx/tx" -> "upload/download" (from client perspective)
                     $rateLimit = "{$package->speed_up}M/{{$package->speed_down}}M";
                     $service->createHotspotProfile($package->name, $rateLimit);
                } 
                elseif ($action === 'delete') {
                     $service->deleteHotspotProfile($oldName);
                }

            } catch (\Exception $e) {
                \Log::error("Failed to sync hotspot profile to {$device->name}: " . $e->getMessage());
            }
        }
    }
}
