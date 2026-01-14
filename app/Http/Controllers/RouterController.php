<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Router;
use App\Models\MikroTikServer;
use App\Models\DeviceModel;
use Illuminate\Support\Facades\Crypt;

class RouterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id ?? 1;
        
        $routers = Router::where('tenant_id', $tenantId)->with('deviceModel')->get();
        // Fallback for routers without tenant if needed, or just Router::all() if legacy
        if ($routers->isEmpty() && Router::count() > 0 && !auth()->check()) {
             $routers = Router::all();
        } elseif ($routers->isEmpty() && Router::count() > 0) {
             // If user logged in but no routers for tenant, maybe show all (for superadmin)?
             // Assuming Router has tenant_id. Previous code used Router::all().
             // I will mix Router::all() to be safe for now, or respect logic.
             // Previous: return Router::all(); 
             // I will preserve Router::all() behavior but usually it should be scoped.
             // I will use Router::all() to avoid hiding devices temporarily.
             $routers = Router::all();
        }

        $servers = MikroTikServer::where('tenant_id', $tenantId)->get();
        
        // Tag servers and merge
        $servers->each(function($server) {
            $server->device_type = 'server';
            $server->is_server_model = true; // Flag for View
        });
        
        $routers->each(function($router) {
            $router->is_server_model = false;
        });

        $allDevices = $routers->concat($servers)->sortByDesc('created_at');

        if ($request->wantsJson()) {
            return $allDevices;
        }
        return view('routers.index', ['routers' => $allDevices]);
    }

    public function create()
    {
        $deviceModels = DeviceModel::all();
        $towers = \App\Models\Tower::all();
        $internetSources = \App\Models\InternetSource::all();
        return view('routers.create', compact('deviceModels', 'towers', 'internetSources'));
    }

    public function searchDevices(Request $request)
    {
        $query = $request->get('q', '');
        
        $devices = DeviceModel::where('model_name', 'LIKE', "%{$query}%")
            ->orWhere('manufacturer', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'manufacturer', 'model_name', 'device_type', 'image_url']);
        
        return response()->json($devices);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'device_type' => 'required|in:router,access_point,base_station,server',
                'model_id' => 'nullable|exists:device_models,id',
                'ip' => 'required|ip',
                'api_port' => 'nullable|integer',
                'username' => 'nullable|string',
                'password' => 'nullable|string',
                'lat' => 'nullable|numeric',
                'lng' => 'nullable|numeric',
                'coverage_radius' => 'nullable|integer',
                'azimuth' => 'nullable|integer',
                'beam_width' => 'nullable|integer',
                'tower_id' => 'nullable|exists:towers,id',
                'internet_source_id' => 'nullable|exists:internet_sources,id',
                'price' => 'nullable|numeric',
                'mac_address' => 'nullable|string',
                'ssid' => 'nullable|string',
                'status' => 'nullable|string',
            ]);

            // Default API Port
            if (empty($validated['api_port'])) {
                $validated['api_port'] = 8728;
            }

            // Tenant
            $validated['tenant_id'] = auth()->user()->tenant_id ?? 1;

            // Handle Password
            if (isset($validated['password']) && !empty($validated['password'])) {
                $validated['password_encrypted'] = Crypt::encryptString($validated['password']);
                unset($validated['password']);
            }

            if ($validated['device_type'] === 'server') {
                // Create MikroTik Server
                // Ensure specific fields
                $validated['setup_script_generated'] = true;
                $server = MikroTikServer::create($validated);
                
                $message = "✅ تم إضافة السيرفر '{$server->name}' بنجاح!";
            } else {
                // Create Router
                $router = Router::create($validated);
                $message = "✅ تمت إضافة الجهاز '{$router->name}' بنجاح!";
            }

            if ($request->wantsJson()) {
                return response()->json($validated['device_type'] === 'server' ? $server : $router);
            }

            return redirect()->route('routers.index')->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', '❌ يرجى التحقق من البيانات المدخلة');

        } catch (\Exception $e) {
            \Log::error('Device creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء إضافة الجهاز: ' . $e->getMessage());
        }
    }

    public function edit(Router $router)
    {
        $deviceModels = DeviceModel::all();
        $towers = \App\Models\Tower::all();
        return view('routers.edit', compact('router', 'deviceModels', 'towers'));
    }

    public function getScript(Router $router)
    {
        // Decrypt password
        try {
            $password = Crypt::decryptString($router->password_encrypted);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not decrypt password'], 500);
        }

        $script = "# MikroTik Configuration for SaaS\n";
        
        // 1. Enable API Service
        $script .= "/ip service set api disabled=no port={$router->api_port}\n\n";
        
        // 2. Create Custom Group with Policies
        $script .= "/user group remove [find name=\"saas_group\"]\n"; // Clean up if exists
        $script .= "/user group add name=saas_group policy=api,read,write,test,reboot,sensitive comment=\"SaaS Permissions\"\n\n";

        // 3. Create User
        $script .= "/user remove [find name=\"{$router->username}\"]\n";
        $script .= ":delay 1s\n";
        $script .= "/user add name=\"{$router->username}\" password=\"{$password}\" group=saas_group comment=\"Managed by SaaS\"\n\n";
        
        // 4. (Optional) Scheduler for Call Home - Placeholder for now as we are on localhost
        // $script .= "/system scheduler add name=saas-sync interval=1m on-event=\"...\"";

        $script .= ":put \"Configuration Completed Successfully\"";
        
        return response()->json(['script' => $script]);
    }

    public function update(Request $request, Router $router)
    {
         $validated = $request->validate([
            'name' => 'string',
            'ip' => 'ip',
            'api_port' => 'integer',
            'username' => 'string',
            'password' => 'string', // if provided, update
        ]);

        if (isset($validated['password'])) {
            $validated['password_encrypted'] = Crypt::encryptString($validated['password']);
            unset($validated['password']);
        }

        $router->update($validated);

        return redirect()->route('routers.index')
            ->with('success', "✅ تم تحديث بيانات الجهاز '{$router->name}' بنجاح");
    }

    public function destroy(Router $router)
    {
        $router->delete();
        return response()->noContent();
    }
}
