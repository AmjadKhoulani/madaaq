<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandwidthLog extends Model
{
    protected $fillable = [
        'device_type',
        'device_id',
        'interface_name',
        'rx_bytes',
        'tx_bytes',
        'raw_rx',
        'raw_tx',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'rx_bytes' => 'integer',
        'tx_bytes' => 'integer',
    ];

    // Get the device (polymorphic)
    public function device()
    {
        return $this->morphTo('device', 'device_type', 'device_id');
    }

    // Relationship to Router (for filtering by tenant)
    public function router()
    {
        return $this->belongsTo(\App\Models\Router::class, 'device_id')->where('device_type', 'App\\Models\\Router');
    }

    // Calculate bandwidth in Mbps
    public function getRxMbpsAttribute()
    {
        return round($this->rx_bytes / 1024 / 1024, 2);
    }

    public function getTxMbpsAttribute()
    {
        return round($this->tx_bytes / 1024 / 1024, 2);
    }

}
