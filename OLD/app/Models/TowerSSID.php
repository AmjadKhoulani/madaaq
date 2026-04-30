<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TowerSSID extends Model
{
    use HasFactory;

    protected $table = 'tower_ssids';

    protected $fillable = [
        'tower_id',
        'tower_device_id',
        'router_id',
        'ssid_name',
        'frequency',
        'security_type',
        'password',
        'is_active',
        'notes',
    ];

    public function tower()
    {
        return $this->belongsTo(Tower::class);
    }

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function device()
    {
        return $this->belongsTo(TowerDevice::class, 'tower_device_id');
    }
}
