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
            'tower_device_id' => 'required|exists:tower_devices,id',
            'router_id' => 'nullable|exists:routers,id',
            'password' => 'nullable|string',
            'notes' => 'nullable|string',
            'security_type' => 'nullable|string',
        ]);

        $tower->ssids()->create($validated);

        return redirect()->back()
            ->with('success', 'تم إضافة SSID بنجاح');
    }

    public function destroy(Tower $tower, TowerSSID $ssid)
    {
        $ssid->delete();
        return redirect()->route('network.towers.show', $tower)
            ->with('success', 'تم حذف SSID بنجاح');
    }
}
