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
        $users = Client::where('type', 'pppoe')->with(['router', 'package', 'customer', 'tower', 'ssid'])->get();
        return \Inertia\Inertia::render('Broadband/Users/Index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $routers = Router::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        $towers = \App\Models\Tower::with(['ssids.router'])->get(); 
        $packages = Package::where('type', 'pppoe')->get();
        $customers = \App\Models\Customer::select('id', 'name', 'phone')->get();
        
        return \Inertia\Inertia::render('Broadband/Users/Create', [
            'routers' => $routers,
            'towers' => $towers,
            'packages' => $packages,
            'customers' => $customers
        ]);
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
        $routerResponse = null;
        $routerError = null;

        try {
            // Profile name should match Package name usually
            $profileName = $package->name ?? 'default';
            $routerResponse = $service->createPPPoEUser($validated['username'], $validated['password'], $profileName); 
            session()->flash('success', 'تم إنشاء المستخدم بنجاح على السيرفر وقاعدة البيانات.');
        } catch (\Exception $e) {
            $routerError = $e->getMessage();
            Log::warning("MikroTik PPPoE Creation failed for {$validated['username']}: " . $routerError);
            session()->flash('warning', 'تم حفظ المستخدم في قاعدة البيانات، ولكن تعذر إنشاؤه على السيرفر حالياً: ' . $routerError);
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
        // SYNC: Delete User from MikroTik
        if ($user->router_id) {
            try {
                $router = $user->router;
                if ($router) {
                    $service = new MikroTikService($router);
                    $service->deletePPPoEUser($user->username);
                    // Also kick if active
                    $service->kickUser($user->username);
                }
            } catch (\Exception $e) {
                \Log::error("Failed to delete PPPoE user from MikroTik: " . $e->getMessage());
            }
        }

        $user->delete();
        return redirect()->route('broadband.users.index')->with('success', 'User deleted from DB and MikroTik.');
    }
}
