<?php

namespace App\Http\Controllers\Broadband;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Package::where('type', 'pppoe')->with(['routers', 'mikrotikServers'])->get();
        return \Inertia\Inertia::render('Broadband/Profiles/Index', [
            'profiles' => $profiles
        ]);
    }

    public function create()
    {
        $servers = \App\Models\MikroTikServer::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        $routers = \App\Models\Router::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        
        return \Inertia\Inertia::render('Broadband/Profiles/Create', [
            'servers' => $servers,
            'routers' => $routers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
            'duration_days' => 'nullable|integer',
            'data_limit_mb' => 'nullable|integer',
            'technology_type' => 'required|in:fiber,wireless,dsl,cable',
            'targets' => 'nullable|array', // e.g., ['router_1', 'server_2']
        ]);

        $validated['type'] = 'pppoe';
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

        return redirect()->route('broadband.profiles.index')->with('success', 'Profile created and synced to selected devices.');
    }

    public function edit(Package $profile)
    {
        $servers = \App\Models\MikroTikServer::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        $routers = \App\Models\Router::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        
        // Load existing targets
        $profile->load(['routers', 'mikrotikServers']);
        
        return \Inertia\Inertia::render('Broadband/Profiles/Edit', [
            'profile' => $profile,
            'servers' => $servers,
            'routers' => $routers
        ]);
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

        // SYNC: Update Profile on selected MikroTik devices
        // Note: For update, we ideally allow moving profiles (delete from old, create on new).
        // For simplicity now, we assume strict update on currently attached.
        // A better approach would be to calculate diff, but mostly users just edit params.
        $this->syncProfileToMikroTik($profile, 'update', $oldName);

        return redirect()->route('broadband.profiles.index')->with('success', 'Profile updated and synced.');
    }

    public function destroy(Package $profile)
    {
        $name = $profile->name;
        
        // Load targets before delete to know where to remove from
        $profile->load(['routers', 'mikrotikServers']);
        $targets = $profile->routers->concat($profile->mikrotikServers);

        $profile->delete();

        // SYNC: Delete Profile from specific devices
        foreach ($targets as $device) {
             try {
                $service = new \App\Services\MikroTikService($device);
                $service->deletePPPoEProfile($name);
             } catch (\Exception $e) {
                \Log::error("Failed to delete profile from {$device->name}: " . $e->getMessage());
             }
        }

        return redirect()->route('broadband.profiles.index')->with('success', 'Profile deleted from DB and routers.');
    }

    /**
     * Sync Profile to Selected Routers & Servers
     */
    protected function syncProfileToMikroTik($package, $action, $oldName = null)
    {
        // Refresh relations to get latest attached
        $package->load(['routers', 'mikrotikServers']);
        $targets = $package->routers->concat($package->mikrotikServers);

        // If no targets selected, do nothing (or maybe delete if updating? skipping for now)
        if ($targets->isEmpty()) return;

        foreach ($targets as $device) {
            try {
                $service = new \App\Services\MikroTikService($device);
                
                if ($action === 'create') {
                     $rateLimit = "{$package->speed_up}M/{{$package->speed_down}}M";
                     $service->createPPPoEProfile($package->name, $rateLimit);
                } 
                elseif ($action === 'update') {
                     $rateLimit = "{$package->speed_up}M/{{$package->speed_down}}M";
                     $service->updatePPPoEProfile($oldName, $package->name, $rateLimit);
                }
                // Check if we need 'delete' here? destroy() handles it separately manually.

            } catch (\Exception $e) {
                \Log::error("Failed to sync profile to {$device->name}: " . $e->getMessage());
            }
        }
    }
}
