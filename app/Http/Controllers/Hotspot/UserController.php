<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Router;
use App\Models\Package;
use App\Services\MikroTikService;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function index()
    {
        $users = Client::where('type', 'hotspot')->with(['mikrotikServer', 'package'])->latest()->get();
        return \Inertia\Inertia::render('Hotspot/Users/Index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $servers = \App\Models\MikroTikServer::all();
        $packages = Package::where('type', 'hotspot')->get();
        $customers = \App\Models\Customer::select('id', 'name', 'phone')->get();
        
        return \Inertia\Inertia::render('Hotspot/Users/Create', [
            'servers' => $servers,
            'packages' => $packages,
            'customers' => $customers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mode' => 'required|in:new,existing',
            'customer_id' => 'required_if:mode,existing|nullable|exists:customers,id',
            'name' => 'required_if:mode,new|nullable|string|max:255',
            'phone' => 'required_if:mode,new|nullable|string|max:255',
            
            'server_id' => 'required|exists:mikrotik_servers,id',
            'package_id' => 'required|exists:packages,id',
            'username' => 'required|string|unique:clients,username',
            'password' => 'required|string|min:4',
            'expires_at' => 'nullable|date',
        ]);

        // Resolve Customer
        if ($validated['mode'] === 'existing') {
            $customer = \App\Models\Customer::findOrFail($validated['customer_id']);
        } else {
            $customer = \App\Models\Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'tenant_id' => auth()->user()->tenant_id ?? 1,
            ]);
        }

        $server = \App\Models\MikroTikServer::findOrFail($validated['server_id']);
        $package = Package::find($validated['package_id']); 
        
        $service = new MikroTikService($server);
        try {
            $profileName = $package->name ?? 'default';
            $service->createHotspotUser($validated['username'], $validated['password'], $profileName); 
            session()->flash('success', 'تم إنشاء مستخدم الهوتسبوت بنجاح على السيرفر وقاعدة البيانات.');
        } catch (\Exception $e) {
            \Log::warning("MikroTik Hotspot User Creation Failed: " . $e->getMessage());
            session()->flash('warning', 'تم حفظ المستخدم في قاعدة البيانات، ولكن تعذر التواصل مع السيرفر: ' . $e->getMessage());
        }

        \App\Models\Client::create([
            'tenant_id' => auth()->user()->tenant_id ?? 1,
            'customer_id' => $customer->id,
            'mikrotik_server_id' => $validated['server_id'],
            'type' => 'hotspot',
            'package_id' => $validated['package_id'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'status' => 'active',
            'expires_at' => $validated['expires_at'] ?? null,
            'name' => $customer->name,
            'phone' => $customer->phone,
        ]);

        return redirect()->route('hotspot.users.index')->with('success', 'User created successfully.');
    }

    public function destroy(Client $user)
    {
        // SYNC: Delete User from MikroTik
        if ($user->mikrotik_server_id) {
            try {
                $server = $user->mikrotikServer;
                if ($server) {
                    $service = new MikroTikService($server);
                    $service->deleteHotspotUser($user->username);
                    // Also kick if active
                    $service->kickHotspotUser($user->username);
                }
            } catch (\Exception $e) {
                \Log::error("Failed to delete hotspot user from MikroTik: " . $e->getMessage());
            }
        }

        $user->delete();
        return redirect()->route('hotspot.users.index')->with('success', 'User deleted from DB and MikroTik.');
    }

    public function print(Client $user)
    {
        $user->load(['package', 'mikrotikServer']);
        return \Inertia\Inertia::render('Hotspot/Users/Print', [
            'user' => $user
        ]);
    }
}
