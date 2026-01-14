<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\BelongsToTenant;
use App\Traits\LogsActivity;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use BelongsToTenant, HasApiTokens, LogsActivity;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'batch_id',
        'type',
        'router_id',
        'mikrotik_server_id',
        'tower_id',
        'ssid_id',
        'package_id',
        'username',
        'pppoe_username',
        'hotspot_username',
        'ssid',
        'password',
        'service_password',
        'ip',
        'status',
        'expires_at',
        'custom_duration_days',
        'custom_data_limit_mb',
        'custom_price',
        'lat',
        'lng',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'notes',
        'connection_mode',
        'device_model_id',
        'cpe_model',
        'switch_port',
        'cpe_ip',
        'cpe_mac',
        'tower_device_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }
    
    public function tower()
    {
        return $this->belongsTo(Tower::class);
    }

    public function server()
    {
        return $this->belongsTo(MikroTikServer::class, 'mikrotik_server_id');
    }

    public function mikrotikServer()
    {
        return $this->belongsTo(MikroTikServer::class, 'mikrotik_server_id');
    }

    public function ssid()
    {
        return $this->belongsTo(TowerSSID::class, 'ssid_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function clientNotes()
    {
        return $this->hasMany(ClientNote::class)->latest();
    }

    public function activities()
    {
        return $this->hasMany(ClientActivity::class)->latest();
    }

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModel::class);
    }

    public function towerDevice()
    {
        return $this->belongsTo(TowerDevice::class);
    }
}
