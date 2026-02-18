<?php

namespace App\Http\Controllers\Broadband;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Package::where('type', 'pppoe')->get();
        return view('broadband.profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('broadband.profiles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $validated['type'] = 'pppoe';
        $package = Package::create($validated);

        // SYNC: Create Profile on all MikroTik devices
        $this->syncProfileToMikroTik($package, 'create');

        return redirect()->route('broadband.profiles.index')->with('success', 'Profile created and synced to routers.');
    }

    public function edit(Package $profile)
    {
        return view('broadband.profiles.edit', compact('profile'));
    }

    public function update(Request $request, Package $profile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
            'duration_days' => 'nullable|integer',
            'data_limit_mb' => 'nullable|integer',
        ]);

        $oldName = $profile->name;
        $profile->update($validated);

        // SYNC: Update Profile on all MikroTik devices
        $this->syncProfileToMikroTik($profile, 'update', $oldName);

        return redirect()->route('broadband.profiles.index')->with('success', 'Profile updated and synced to routers.');
    }

    public function destroy(Package $profile)
    {
        $name = $profile->name;
        $profile->delete();

        // SYNC: Delete Profile from all MikroTik devices
        $this->syncProfileToMikroTik(null, 'delete', $name);

        return redirect()->route('broadband.profiles.index')->with('success', 'Profile deleted from DB and routers.');
    }

    /**
     * Sync Profile to all Tenant Routers & Servers
     */
    protected function syncProfileToMikroTik($package, $action, $oldName = null)
    {
        $tenantId = auth()->user()->tenant_id ?? 1;
        
        // Fetch all devices
        $routers = \App\Models\Router::where('tenant_id', $tenantId)->get();
        $servers = \App\Models\MikroTikServer::where('tenant_id', $tenantId)->get();
        $allDevices = $routers->concat($servers);

        foreach ($allDevices as $device) {
            try {
                $service = new \App\Services\MikroTikService($device);
                
                if ($action === 'create') {
                     // rate-limit: "rx/tx" -> "upload/download" (from client perspective)
                     // speed_up/speed_down
                     $rateLimit = "{$package->speed_up}M/{{$package->speed_down}}M";
                     $service->createPPPoEProfile($package->name, $rateLimit);
                } 
                elseif ($action === 'update') {
                     $rateLimit = "{$package->speed_up}M/{{$package->speed_down}}M";
                     $service->updatePPPoEProfile($oldName, $package->name, $rateLimit);
                }
                elseif ($action === 'delete') {
                     $service->deletePPPoEProfile($oldName); // oldName holds the name to delete
                }

            } catch (\Exception $e) {
                \Log::error("Failed to sync profile to {$device->name}: " . $e->getMessage());
                // Continue to next device
            }
        }
    }
}
