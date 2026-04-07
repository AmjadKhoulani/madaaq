<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Tower;
use App\Models\Router;
use Illuminate\Http\Request;

class TowerController extends Controller
{
    public function index(Request $request)
    {
        $query = Tower::withCount('routers')->latest();
        
        // Apply Filters
        if ($request->has('city') && $request->city != 'all') {
            $query->where('city', $request->city);
        }
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        $towers = $query->get();
        
        // Get unique cities for filter dropdown
        $cities = Tower::select('city')->whereNotNull('city')->distinct()->pluck('city');
        
        // Calculate Statistics
        $stats = [
            'totalTowers' => Tower::count(),
            'activeTowers' => Tower::where('status', 'active')->count(),
            'maintenanceTowers' => Tower::where('status', 'maintenance')->count(),
            'inactiveTowers' => Tower::where('status', 'inactive')->count(),
            'totalRouters' => \App\Models\Router::count(),
            'totalMonthlyRent' => Tower::sum('monthly_rent'),
            'totalMonthlyMaintenance' => Tower::sum('monthly_maintenance'),
        ];
        
        return view('network.towers.index', compact('towers', 'cities', 'stats'));
    }

    public function create()
    {
        $servers = \App\Models\MikroTikServer::all();
        $deviceModels = \App\Models\DeviceModel::all();
        $towers = Tower::all(); // For selecting source tower for transmitter
        // Fetch active routers that can serve as transmitters (APs, etc.)
        $activeRouters = Router::whereIn('device_type', ['access_point', 'transmitter', 'router'])->get();
        $currency = \App\Models\Setting::getValue('currency_symbol', '$');
        
        return view('network.towers.create', compact('servers', 'deviceModels', 'currency', 'towers', 'activeRouters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'type' => 'required|in:tower,building,pole,cabinet,office',
            'battery_count' => 'nullable|integer|min:0',
            'battery_type' => 'nullable|string',
            'has_inverter' => 'nullable|boolean',
            'inverter_capacity' => 'nullable|string',
            'has_generator' => 'nullable|boolean',
            'generator_capacity' => 'nullable|string',
            'has_solar' => 'nullable|boolean',
            'solar_capacity' => 'nullable|string',
            'notes' => 'nullable|string',
            // New cost fields
            'has_ampere' => 'nullable|boolean',
            'kwh_price' => 'nullable|numeric',
            'has_government_electricity' => 'nullable|boolean',
            'government_electricity_notes' => 'nullable|string',
            'electricity_hours' => 'nullable|numeric|min:0|max:24',
            'solar_panels_count' => 'nullable|integer',
            'solar_panel_wattage' => 'nullable|integer',
            'solar_installation_cost' => 'nullable|numeric',
            'monthly_maintenance' => 'nullable|numeric',
            'monthly_rent' => 'nullable|numeric',
            'monthly_other_costs' => 'nullable|numeric',
            'monthly_notes' => 'nullable|string',
            'equipment_notes' => 'nullable|string',
            'structure_cost' => 'nullable|numeric',
            
            // Connection Details
            'mikrotik_server_id' => 'nullable|exists:mikrotik_servers,id', // Parent Server
            'connection_type' => 'nullable|in:cable,fiber,wireless',
            
            // Wired Only
            'connection_port' => 'nullable|string|max:255', 
            
            // Wireless Only
            // Transmitter
            'transmitter_type' => 'nullable|in:new,existing',
            'transmitter_router_id' => 'nullable|exists:routers,id',
            'transmitter_name' => 'nullable|string',
            'transmitter_ip' => 'nullable|ip',
            'transmitter_model_id' => 'nullable|exists:device_models,id',
            
            // Receiver
            'receiver_model_id' => 'nullable|exists:device_models,id',
            'receiver_name' => 'nullable|string',
            'receiver_ip' => 'nullable|ip',
            'receiver_mac' => 'nullable|string',
        ]);

        // Default non-nullable numeric fields
        $data = array_merge($validated, [
            'battery_count' => $request->integer('battery_count', 0),
            'has_inverter' => $request->boolean('has_inverter'),
            'has_generator' => $request->boolean('has_generator'),
            'has_solar' => $request->boolean('has_solar'),
            'has_ampere' => $request->boolean('has_ampere'),
            'sending_server_id' => $validated['mikrotik_server_id'] ?? null,
            'tenant_id' => auth()->user()->tenant_id ?? 1,
            'status' => 'active',
        ]);

        // Handle Wireless Device Creation Logic BEFORE creating tower
        $transmitterId = $request->transmitter_router_id;
        $receiverId = null;

        if ($request->connection_type === 'wireless') {
            // 1. Handle Transmitter
            if ($request->transmitter_type === 'new' && $request->filled('transmitter_name')) {
                $checkTx = Router::where('ip', $request->transmitter_ip)->first();
                if (!$checkTx) {
                    $transmitter = Router::create([
                        'tenant_id' => auth()->user()->tenant_id ?? 1,
                        'name' => $request->transmitter_name,
                        'ip' => $request->transmitter_ip,
                        'model_id' => $request->transmitter_model_id,
                        'device_type' => 'transmitter', // Transmitter/AP
                        'lat' => $validated['lat'] ?? 0, // Should technically be parent location, but approx ok
                        'lng' => $validated['lng'] ?? 0,
                    ]);
                    $transmitterId = $transmitter->id;
                } else {
                    $transmitterId = $checkTx->id;
                }
            }

            // 2. Handle Receiver (Always new/specific to this tower usually)
            if ($request->filled('receiver_name') || $request->filled('receiver_ip')) {
                 $checkRx = Router::where('ip', $request->receiver_ip)->first();
                 if (!$checkRx) {
                     $receiver = Router::create([
                        'tenant_id' => auth()->user()->tenant_id ?? 1,
                        'name' => $request->receiver_name ?? ($validated['name'] . ' - Receiver'),
                        'ip' => $request->receiver_ip,
                        'mac_address' => $request->receiver_mac,
                        'model_id' => $request->receiver_model_id,
                        'device_type' => 'station', // Receiver/Station
                        'lat' => $validated['lat'], 
                        'lng' => $validated['lng'],
                     ]);
                     $receiverId = $receiver->id;
                 } else {
                     $receiverId = $checkRx->id;
                 }
            }
        }

        $data['transmitter_router_id'] = $transmitterId;
        $data['receiver_router_id'] = $receiverId;

        $tower = Tower::create($data);

        // Link auto-created receiver to tower explicitly if needed (one-to-many)
        if ($request->connection_type === 'wireless' && isset($receiver)) {
            $receiver->update(['tower_id' => $tower->id]);
        }

        return redirect()->route('network.towers.index')->with('success', 'تم إضافة البرج بنجاح');
    }

    public function show(Tower $tower)
    {
        $tower->load(['routers', 'clients', 'monthlyCosts.tower', 'ssids', 'devices.deviceModel', 'devices.ssids', 'mikrotikServer', 'transmitterModel', 'receiverModel']);
        $currency = \App\Models\Setting::getValue('currency_symbol', '$');
        return view('network.towers.show', compact('tower', 'currency'));
    }

    public function edit(Tower $tower)
    {
        $deviceModels = \App\Models\DeviceModel::all();
        $servers = \App\Models\MikroTikServer::all();
        $towers = Tower::where('id', '!=', $tower->id)->get(); // Exclude current tower
        $activeRouters = Router::whereIn('device_type', ['access_point', 'transmitter', 'router'])->get();
        $currency = \App\Models\Setting::getValue('currency_symbol', '$');
        
        $tower->load(['devices.deviceModel', 'devices.ssids']);
        return view('network.towers.edit', compact('tower', 'deviceModels', 'currency', 'servers', 'towers', 'activeRouters'));
    }

    public function update(Request $request, Tower $tower)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'type' => 'required|in:tower,building,pole,cabinet,office',
            'battery_count' => 'nullable|integer|min:0',
            'battery_type' => 'nullable|string',
            'has_inverter' => 'nullable|boolean',
            'inverter_capacity' => 'nullable|string',
            'has_generator' => 'nullable|boolean',
            'generator_capacity' => 'nullable|string',
            'has_solar' => 'nullable|boolean',
            'solar_capacity' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
            'generator_capacity' => 'nullable|string',
            'has_solar' => 'nullable|boolean',
            'solar_capacity' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,maintenance,inactive',
            // New cost fields
            'has_ampere' => 'nullable|boolean',
            'kwh_price' => 'nullable|numeric',
            'electricity_hours' => 'nullable|numeric|min:0|max:24', // Added
            'solar_panels_count' => 'nullable|integer',
            'solar_panel_wattage' => 'nullable|integer',
            'solar_installation_cost' => 'nullable|numeric',
            'monthly_maintenance' => 'nullable|numeric',
            'monthly_rent' => 'nullable|numeric',
            'monthly_other_costs' => 'nullable|numeric',
            'monthly_notes' => 'nullable|string',
            'equipment_notes' => 'nullable|string',
            'structure_cost' => 'nullable|numeric',
            'connection_type' => 'nullable|in:cable,fiber,wireless',
            'connection_port' => 'nullable|string|max:255',
            'transmitter_ip' => 'nullable|string|max:255',
            'receiver_ip' => 'nullable|string|max:255',
            'transmitter_model_id' => 'nullable|exists:device_models,id',
            'receiver_model_id' => 'nullable|exists:device_models,id',
            'mikrotik_server_id' => 'nullable|exists:mikrotik_servers,id',
        ]);

        $data = array_merge($validated, [
            'battery_count' => $request->integer('battery_count', 0),
            'has_inverter' => $request->boolean('has_inverter'),
            'has_generator' => $request->boolean('has_generator'),
            'has_solar' => $request->boolean('has_solar'),
            'has_ampere' => $request->boolean('has_ampere'),
            'sending_server_id' => $validated['mikrotik_server_id'] ?? null,
        ]);

        $tower->update($data);

        return redirect()->route('network.towers.index')->with('success', 'تم تحديث البرج بنجاح');
    }

    public function destroy(Tower $tower)
    {
        $tower->delete();
        return redirect()->route('network.towers.index')->with('success', 'تم حذف البرج بنجاح');
    }

    public function storeSsid(Request $request, Tower $tower)
    {
        \Log::info('storeSsid request:', $request->all());

        $validated = $request->validate([
            'tower_device_id' => 'required', // Can be numeric ID for TowerDevice or Router ID
            'device_type' => 'nullable|string|in:router,tower_device', // New field
            'ssid_name' => 'required|string|max:255',
            'frequency' => 'required|in:2.4,5,60',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $deviceId = $validated['tower_device_id'];
        $deviceType = $validated['device_type'] ?? 'tower_device'; // Default to legacy if not present
        
        if ($deviceType === 'tower_device') {
             // It's a TowerDevice
            $device = \App\Models\TowerDevice::find($deviceId);
            if ($device) {
                \App\Models\TowerSSID::create([
                    'tower_id' => $tower->id,
                    'tower_device_id' => $device->id,
                    'ssid_name' => $validated['ssid_name'],
                    'frequency' => $validated['frequency'],
                    'is_active' => $request->boolean('is_active', true),
                    'notes' => $validated['notes'],
                ]);
            }
        } else {
            // It's a Router (Sector/Omni/AP)
            $router = \App\Models\Router::find($deviceId);
            if ($router) {
                // Update Router SSID and potentially Frequency if column exists, 
                // otherwise just SSID for now as per current schema.
                // Note: Router model only has 'ssid' column currently.
                $router->update([
                    'ssid' => $validated['ssid_name'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'تم إضافة الشبكة بنجاح');
    }
}
