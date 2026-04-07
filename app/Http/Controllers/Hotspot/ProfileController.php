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
        $servers = \App\Models\MikroTikServer::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        return view('hotspot.profiles.create', compact('servers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
            'technology_type' => 'required|in:fiber,wireless,dsl,cable',
            'targets' => 'nullable|array',
        ]);

        $validated['type'] = 'hotspot';
        $package = Package::create($validated);

        // Attach Targets
        if (!empty($request->targets)) {
            $routers = [];
            $servers = [];
            foreach ($request->targets as $target) {
                if (str_starts_with($target, 'router_')) {
                    $routers[] = substr($target, 7);
                } elseif (str_starts_with($target, 'server_')) {
                    $servers[] = substr($target, 7);
                }
            }
            if (!empty($routers)) $package->routers()->attach($routers);
            if (!empty($servers)) $package->mikrotikServers()->attach($servers);
        }

        // SYNC: Create Profile on selected MikroTik devices
        $this->syncProfileToMikroTik($package, 'create');

        return redirect()->route('hotspot.profiles.index')->with('success', 'Hotspot Profile created and synced to selected devices.');
    }

    public function edit(Package $profile)
    {
        $servers = \App\Models\MikroTikServer::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        
        $profile->load(['routers', 'mikrotikServers']);
        
        return view('hotspot.profiles.edit', compact('profile', 'servers'));
    }

    public function update(Request $request, Package $profile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
            'technology_type' => 'required|in:fiber,wireless,dsl,cable',
            'targets' => 'nullable|array',
        ]);

        $oldName = $profile->name;
        $profile->update($validated);

        // Sync Relations
        $profile->routers()->detach();
        $profile->mikrotikServers()->detach();

        if (!empty($request->targets)) {
            $routers = [];
            $servers = [];
            foreach ($request->targets as $target) {
                if (str_starts_with($target, 'router_')) {
                    $routers[] = substr($target, 7);
                } elseif (str_starts_with($target, 'server_')) {
                    $servers[] = substr($target, 7);
                }
            }
            if (!empty($routers)) $profile->routers()->attach($routers);
            if (!empty($servers)) $profile->mikrotikServers()->attach($servers);
        }

        $this->syncProfileToMikroTik($profile, 'update', $oldName);

        return redirect()->route('hotspot.profiles.index')->with('success', 'Profile updated and synced.');
    }

    public function destroy(Package $profile)
    {
        $name = $profile->name;
        
        $profile->load(['routers', 'mikrotikServers']);
        $targets = $profile->routers->concat($profile->mikrotikServers);

        $profile->delete();

        // SYNC: Delete Profile from targeted devices
        foreach ($targets as $device) {
             try {
                $service = new \App\Services\MikroTikService($device);
                $service->deleteHotspotProfile($name);
             } catch (\Exception $e) {
                \Log::error("Failed to delete hotspot profile from {$device->name}: " . $e->getMessage());
             }
        }

        return redirect()->route('hotspot.profiles.index')->with('success', 'Profile deleted successfully.');
    }

    /**
     * Sync Profile to Selected Routers & Servers
     */
    protected function syncProfileToMikroTik($package, $action, $oldName = null)
    {
        $package->load(['routers', 'mikrotikServers']);
        $targets = $package->routers->concat($package->mikrotikServers);

        if ($targets->isEmpty()) return;

        foreach ($targets as $device) {
            try {
                $service = new \App\Services\MikroTikService($device);
                
                if ($action === 'create') {
                     $rateLimit = "{$package->speed_up}M/{{$package->speed_down}}M";
                     $service->createHotspotProfile($package->name, $rateLimit);
                } 
                elseif ($action === 'update') {
                     $rateLimit = "{$package->speed_up}M/{{$package->speed_down}}M";
                     $service->updateHotspotProfile($oldName, $package->name, $rateLimit);
                }

            } catch (\Exception $e) {
                \Log::error("Failed to sync hotspot profile to {$device->name}: " . $e->getMessage());
            }
        }
    }
}
