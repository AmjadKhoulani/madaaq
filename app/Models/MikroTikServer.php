<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class MikroTikServer extends Model
{
    use BelongsToTenant;

    protected $table = 'mikrotik_servers';

    protected $fillable = [
        'tenant_id',
        'name',
        'ip',
        'api_port',
        'username',
        'password_encrypted',
        'tower_id', // Currently used?
        'location_tower_id', // New relation: Where this server is located
        'uplink_type',
        'uplink_interface',
        'uplink_sending_device_id',
        'uplink_receiving_device_id',
        'uplink_notes',
        'location',
        'lat',
        'lng',
        'wireguard_enabled',
        'wireguard_public_key',
        'wireguard_private_key',
        'wireguard_ip',
        'is_connected',
        'last_sync_at',
        'connection_status',
        'setup_script_generated',
        'setup_completed',
        'internet_source_id',
    ];

    protected $casts = [
        'wireguard_enabled' => 'boolean',
        'is_connected' => 'boolean',
        'setup_script_generated' => 'boolean',
        'setup_completed' => 'boolean',
        'last_sync_at' => 'datetime',
    ];

    protected $hidden = [
        'password_encrypted',
        'wireguard_private_key',
    ];

    public function internetSource()
    {
        return $this->belongsTo(InternetSource::class);
    }

    // The tower where this server is LOCATED (e.g. Server X is installed at Tower A)
    public function locationTower()
    {
        return $this->belongsTo(Tower::class, 'location_tower_id');
    }

    public function uplinkSendingDevice()
    {
        return $this->belongsTo(Router::class, 'uplink_sending_device_id');
    }

    public function uplinkReceivingDevice()
    {
        return $this->belongsTo(Router::class, 'uplink_receiving_device_id');
    }

    // Administrative link: Towers managed by this server (for PPPoE/Hotspot accounts)
    public function towers()
    {
        return $this->hasMany(Tower::class, 'mikrotik_server_id');
    }

    // Connection link: Towers that get their internet flow from this server
    public function uplinkTowers()
    {
        return $this->hasMany(Tower::class, 'sending_server_id');
    }

    public function backups()
    {
        return $this->hasMany(ServerBackup::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->connection_status) {
            'connected' => '<span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">✅ متصل</span>',
            'connecting' => '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">🔄 جاري الاتصال</span>',
            'error' => '<span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">❌ خطأ</span>',
            default => '<span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">⚪ غير متصل</span>',
        };
    }
}
