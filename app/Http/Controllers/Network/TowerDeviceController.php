<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Tower;
use App\Models\TowerDevice;
use Illuminate\Http\Request;

class TowerDeviceController extends Controller
{
    public function store(Request $request, Tower $tower)
    {
        // Check if selecting existing device or creating new
        if ($request->device_mode === 'existing' && $request->existing_device_id) {
            // Link existing device to this tower
            $device = \App\Models\TowerDevice::findOrFail($request->existing_device_id);
            $device->update(['tower_id' => $tower->id]);
            
            return redirect()->back()->with('success', 'تم ربط الجهاز بالبرج بنجاح');
        }
        
        // Create new device
        $validated = $request->validate([
            'device_type' => 'required|in:wireless,switch',
            'name' => 'required|string|max:255',
            'device_model_id' => 'nullable|exists:device_models,id',
            'ip' => 'nullable|ip',
            'mac_address' => 'nullable|string',
            'ports_count' => 'nullable|integer|min:1',
            'ssid' => 'nullable|string',
            'frequency' => 'nullable|string',
            'mode' => 'nullable|string',
        ]);

        $device = $tower->devices()->create($validated);

        // If it's wireless and SSID was provided, create a TowerSSID record for it
        if ($validated['device_type'] === 'wireless' && !empty($validated['ssid'])) {
            \App\Models\TowerSSID::create([
                'tower_id' => $tower->id,
                'tower_device_id' => $device->id,
                'ssid_name' => $validated['ssid'],
                'frequency' => $validated['frequency'] ?? '5',
                'is_active' => true,
            ]);
        }

        return redirect()->back()->with('success', 'تم إضافة الجهاز بنجاح');
    }

    public function destroy(Tower $tower, TowerDevice $device)
    {
        $device->delete();
        return redirect()->back()->with('success', 'تم حذف الجهاز بنجاح');
    }
}
