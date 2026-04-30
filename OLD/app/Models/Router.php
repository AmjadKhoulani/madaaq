<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Router extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'tower_id',
        'name',
        'device_type',
        'model_id',
        'ip',
        'api_port',
        'username',
        'password',
        'password_encrypted',
        'lat',
        'lng',
        'coverage_radius',
        'azimuth',
        'beam_width',
        'price',
        'mac_address',
        'ssid',
        'latency',
        'is_reachable',
        'last_ping_at',
        'status', 
        'antenna_type',
        'wireguard_enabled',
        'wireguard_public_key',
        'wireguard_private_key',
        'wireguard_ip',
    ];

    protected $casts = [
        'wireguard_enabled' => 'boolean',
        'is_reachable' => 'boolean',
        'last_ping_at' => 'datetime',
    ];

    protected $hidden = [
        'password_encrypted',
        'wireguard_private_key',
    ];

    protected $appends = ['ssids'];

    public function getSsidsAttribute()
    {
        // Get TowerSSID records linked to this router
        $towerSsids = \App\Models\TowerSSID::where('router_id', $this->id)->get();
        
        $ssids = collect();

        // Add TowerSSID records
        foreach ($towerSsids as $ts) {
            $ssids->push((object)[
                'ssid_name' => $ts->ssid_name,
                'frequency' => $ts->frequency,
                'is_active' => $ts->is_active,
            ]);
        }

        // Also add the legacy single ssid field if set and no TowerSSID records exist
        if ($ssids->isEmpty() && $this->attributes['ssid'] ?? null) {
            $ssids->push((object)[
                'ssid_name' => $this->attributes['ssid'],
                'frequency' => '5GHz',
                'is_active' => true,
            ]);
        }

        return $ssids;
    }

    public function tower()
    {
        return $this->belongsTo(\App\Models\Tower::class);
    }

    public function deviceModel()
    {
        return $this->belongsTo(\App\Models\DeviceModel::class, 'model_id');
    }
}
