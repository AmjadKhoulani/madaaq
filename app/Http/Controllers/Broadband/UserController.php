<?php

namespace App\Http\Controllers\Broadband;

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
        $users = Client::where('type', 'pppoe')->with(['router', 'package'])->get();
        return view('broadband.users.index', compact('users'));
    }

    public function create()
    {
        $routers = Router::all();
        $towers = \App\Models\Tower::with(['ssids.router'])->get(); 
        $packages = Package::where('type', 'pppoe')->get();
        // Fetch existing customers for selection
        $customers = \App\Models\Customer::select('id', 'name', 'phone')->get();
        
        return view('broadband.users.create', compact('routers', 'towers', 'packages', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mode' => 'required|in:new,existing',
            // Existing Customer
            'customer_id' => 'required_if:mode,existing|nullable|exists:customers,id',
            // New Customer
            'name' => 'required_if:mode,new|nullable|string|max:255',
            'phone' => 'required_if:mode,new|nullable|string|max:255',
            
            // Broadband Account details
            'tower_id' => 'required|exists:towers,id',
            'ssid_id' => 'required|exists:tower_ssids,id',
            'router_id' => 'nullable|exists:routers,id',
            'package_id' => 'required|exists:packages,id',
            'username' => 'required|string|unique:clients,username',
            'password' => 'required|string|min:6',
            'ip' => 'nullable|ip',
            'custom_duration_days' => 'nullable|integer|min:1',
            'custom_data_limit_mb' => 'nullable|integer|min:1',
            'custom_price' => 'nullable|numeric|min:0',
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

        // Get router from SSID if not provided directly
        $ssid = \App\Models\TowerSSID::with('router')->findOrFail($validated['ssid_id']);
        $router = $ssid->router;
        
        if (!$router) {
             // Fallback if SSID has no router assigned (should not happen if configured correctly)
             // Maybe router_id is passed?
             if (isset($validated['router_id'])) {
                 $router = Router::findOrFail($validated['router_id']);
             } else {
                 return back()->with('error', 'SSID has no associated router.')->withInput();
             }
        }

        $package = Package::findOrFail($validated['package_id']);

        // Create on MikroTik
        $service = new MikroTikService($router);
        try {
            // Check connection first? Service connect() does it.
            // Create PPPoE Secret
            // Profile name should match Package name usually, or we pass it if we sync profiles. 
            // For now assuming existing profile 'default' or we need to create profile on router too.
            // Simplified: User enters profile or we use package name. Defaults to 'default' if not set.
            // Wait, MikroTikService::createPPPoEUser takes profile as 3rd arg.
            
            // Note: In a real scenario, we should ensure the Profile exists on the Router.
            // For MVP, we will use 'default' or assume the user configured it manually on router.
            // Or better, use the Package Name and hope it exists or fallback.
            $service->createPPPoEUser($validated['username'], $validated['password'], 'default'); 
            
        } catch (\Exception $e) {
            return back()->withErrors(['router_error' => 'Failed to add user to MikroTik: ' . $e->getMessage()])->withInput();
        }

        // Save to DB
        // Save to DB
         \App\Models\Client::create([
            'tenant_id' => auth()->user()->tenant_id ?? 1,
            'customer_id' => $customer->id,
            'type' => 'pppoe',
            'tower_id' => $validated['tower_id'],
            'ssid_id' => $validated['ssid_id'],
            'router_id' => $router->id, 
            'package_id' => $validated['package_id'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'ip' => $validated['ip'] ?? null,
            'status' => 'active',
            'expires_at' => $validated['expires_at'] ?? null,
            'custom_duration_days' => $validated['custom_duration_days'] ?? null,
            'custom_data_limit_mb' => $validated['custom_data_limit_mb'] ?? null,
            'custom_price' => $validated['custom_price'] ?? null,
            
            // Legacy/Cache fields (Optional, if we want to keep using Client model as Person holder too for now)
            'name' => $customer->name,
            'phone' => $customer->phone,
        ]);

        return redirect()->route('broadband.users.index')->with('success', 'User created successfully on Router & DB.');
    }
    
    public function destroy(Client $user)
    {
        // Remove from Router?
        // Implementation pending for full sync. Just delete from DB for now.
        $user->delete();
        return redirect()->route('broadband.users.index')->with('success', 'User deleted from DB.');
    }
}
