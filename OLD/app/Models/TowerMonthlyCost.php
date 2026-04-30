<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TowerMonthlyCost extends Model
{
    protected $fillable = [
        'tower_id',
        'month',
        'ampere_bill',
        'ampere_cost', // Added
        'diesel_cost', // Added diesel cost
        'ampere_kwh_consumed',
        'maintenance_cost',
        'monthly_rent',
        'other_costs',
        'notes',
        'total_cost',
    ];

    protected $casts = [
        'ampere_bill' => 'decimal:2',
        'ampere_cost' => 'decimal:2', // Added
        'diesel_cost' => 'decimal:2',
        'ampere_kwh_consumed' => 'integer',
        'maintenance_cost' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'other_costs' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function tower()
    {
        return $this->belongsTo(Tower::class);
    }

    // Auto-calculate total cost
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($cost) {
            // Ensure values are not null before saving because DB columns are not nullable
            $cost->ampere_bill = $cost->ampere_bill ?? 0;
            $cost->ampere_cost = $cost->ampere_cost ?? 0;
            $cost->diesel_cost = $cost->diesel_cost ?? 0;
            $cost->maintenance_cost = $cost->maintenance_cost ?? 0;
            $cost->monthly_rent = $cost->monthly_rent ?? 0;
            $cost->other_costs = $cost->other_costs ?? 0;
            $cost->ampere_kwh_consumed = $cost->ampere_kwh_consumed ?? 0;

            $cost->total_cost = 
                ($cost->ampere_bill) + 
                ($cost->ampere_cost) + 
                ($cost->diesel_cost) + 
                ($cost->maintenance_cost) + 
                ($cost->monthly_rent) + 
                ($cost->other_costs);
        });
    }
}
