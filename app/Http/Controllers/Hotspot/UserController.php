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
        $users = Client::where('type', 'hotspot')->with(['router', 'package'])->get();
        return view('hotspot.users.index', compact('users'));
    }

    public function create()
    {
        $routers = Router::all();
        $servers = \App\Models\MikroTikServer::all();
        $packages = Package::where('type', 'hotspot')->get();
        // Fetch existing customers
        $customers = \App\Models\Customer::select('id', 'name', 'phone')->get();
        
        return view('hotspot.users.create', compact('routers', 'servers', 'packages', 'customers'));
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
        } catch (\Exception $e) {
            \Log::error("MikroTik Hotspot User Creation Failed: " . $e->getMessage());
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
        $user->delete();
        return redirect()->route('hotspot.users.index')->with('success', 'User deleted.');
    }

    public function print(Client $user)
    {
        return view('hotspot.users.print', compact('user'));
    }
}
