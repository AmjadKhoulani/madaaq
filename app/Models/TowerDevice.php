<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TowerDevice extends Model
{
    protected $fillable = [
        'tower_id',
        'device_model_id',
        'name',
        'ip',
        'mac_address',
        'ssid',
        'frequency',
        'mode',
        'status',
    ];

    public function tower(): BelongsTo
    {
        return $this->belongsTo(Tower::class);
    }

    public function deviceModel(): BelongsTo
    {
        return $this->belongsTo(DeviceModel::class);
    }

    public function ssids()
    {
        return $this->hasMany(TowerSSID::class, 'tower_device_id');
    }
}
