<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceStatusLog extends Model
{
    protected $fillable = [
        'device_type',
        'device_id',
        'status',
        'response_time',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    // Get the device (polymorphic)
    public function device()
    {
        return $this->morphTo('device', 'device_type', 'device_id');
    }
}
