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
        'status', // Added price field
    ];

    protected $hidden = [
        'password_encrypted',
    ];

    public function tower()
    {
        return $this->belongsTo(\App\Models\Tower::class);
    }

    public function deviceModel()
    {
        return $this->belongsTo(\App\Models\DeviceModel::class, 'model_id');
    }
}
