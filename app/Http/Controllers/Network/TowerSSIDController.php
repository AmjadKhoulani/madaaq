<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Tower;
use App\Models\TowerSSID;
use Illuminate\Http\Request;

class TowerSSIDController extends Controller
{
    public function store(Request $request, Tower $tower)
    {
        $validated = $request->validate([
            'ssid_name' => 'required|string|max:255',
            'frequency' => 'required|in:2.4GHz,5GHz,Both',
            'tower_device_id' => 'required|integer',
            'device_type' => 'nullable|string|in:router,tower_device',
            'router_id' => 'nullable|exists:routers,id',
            'password' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'nullable',
            'security_type' => 'nullable|string',
        ]);

        $deviceId = $validated['tower_device_id'];
        $deviceType = $validated['device_type'] ?? 'tower_device';

        if ($deviceType === 'router') {
            // It's a Router — create TowerSSID with router_id instead of tower_device_id
            $router = \App\Models\Router::find($deviceId);
            if ($router) {
                TowerSSID::create([
                    'tower_id' => $tower->id,
                    'router_id' => $router->id,
                    'ssid_name' => $validated['ssid_name'],
                    'frequency' => $validated['frequency'],
                    'is_active' => $request->boolean('is_active', true),
                    'notes' => $validated['notes'] ?? null,
                    'password' => $validated['password'] ?? null,
                    'security_type' => $validated['security_type'] ?? null,
                ]);
            }
        } else {
            // It's a TowerDevice
            $tower->ssids()->create($validated);
        }

        return redirect()->back()
            ->with('success', 'تم إضافة SSID بنجاح');
    }

    public function update(Request $request, Tower $tower, TowerSSID $ssid)
    {
        $validated = $request->validate([
            'ssid_name' => 'required|string|max:255',
            'frequency' => 'required|in:2.4GHz,5GHz,Both',
            'is_active' => 'nullable',
            'notes' => 'nullable|string',
            'password' => 'nullable|string',
            'security_type' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $ssid->update($validated);

        return redirect()->back()
            ->with('success', 'تم تحديث SSID بنجاح');
    }

    public function destroy(Tower $tower, TowerSSID $ssid)
    {
        $ssid->delete();
        return redirect()->route('network.towers.show', $tower)
            ->with('success', 'تم حذف SSID بنجاح');
    }
}
