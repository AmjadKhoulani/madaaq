<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Tower extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'mikrotik_server_id',
        'name',
        'city',
        'district',
        'location',
        'lat',
        'lng',
        'height',
        'type',
        'battery_count',
        'battery_type',
        'has_inverter',
        'inverter_capacity',
        'has_generator',
        'generator_capacity',
        'has_solar',
        'solar_capacity',
        'notes',
        'status',
        // Cost fields
        'equipment_total_cost', // Legacy/Manual override
        'structure_cost',       // New: Cost of the tower structure
        'equipment_notes',
        'has_ampere',
        // 'ampere_subscription_monthly', // REMOVED
        'kwh_price',
        'has_government_electricity', // Added
        'government_electricity_notes', // Added
        'solar_panels_count',
        'solar_panel_wattage',
        'solar_installation_cost',
        'monthly_maintenance',
        'monthly_rent',
        'monthly_other_costs',
        'monthly_notes',
        'connection_type',
        'connection_port',
        'transmitter_ip',
        'receiver_ip',
        'transmitter_model_id',
        'receiver_model_id',
        'transmitter_status',
        'receiver_status',
        'sending_server_id',
    ];

    protected $casts = [
        'has_inverter' => 'boolean',
        'has_generator' => 'boolean',
        'has_solar' => 'boolean',
        'has_ampere' => 'boolean',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'height' => 'decimal:2',
        'equipment_total_cost' => 'decimal:2',
        'structure_cost' => 'decimal:2',
        // 'ampere_subscription_monthly' => 'decimal:2', // REMOVED
        'kwh_price' => 'decimal:2',
        'has_government_electricity' => 'boolean', // Added
        'solar_installation_cost' => 'decimal:2',
        'monthly_maintenance' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'monthly_other_costs' => 'decimal:2',
    ];

    public function routers()
    {
        return $this->hasMany(Router::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function monthlyCosts()
    {
        return $this->hasMany(TowerMonthlyCost::class);
    }

    public function ssids()
    {
        return $this->hasMany(TowerSSID::class);
    }

    public function devices()
    {
        return $this->hasMany(TowerDevice::class);
    }

    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function transmitterModel()
    {
        return $this->belongsTo(DeviceModel::class, 'transmitter_model_id');
    }

    public function receiverModel()
    {
        return $this->belongsTo(DeviceModel::class, 'receiver_model_id');
    }

    public function sendingServer()
    {
        return $this->belongsTo(MikroTikServer::class, 'sending_server_id');
    }

    public function mikrotikServer()
    {
        return $this->belongsTo(MikroTikServer::class, 'mikrotik_server_id');
    }

    // Cost calculations
    public function getTotalSolarCapacityAttribute()
    {
        return ($this->solar_panels_count ?? 0) * ($this->solar_panel_wattage ?? 0);
    }

    public function getMonthlyFixedCostsAttribute()
    {
        // Removed ampere_subscription_monthly
        return ($this->monthly_rent ?? 0) + 
               ($this->monthly_maintenance ?? 0) + 
               ($this->monthly_other_costs ?? 0);
    }

    public function getCurrentMonthCost()
    {
        $currentMonth = now()->format('Y-m');
        $monthlyCost = $this->monthlyCosts()->where('month', $currentMonth)->first();
        
        if ($monthlyCost) {
            return $monthlyCost->total_cost + $this->monthly_fixed_costs;
        }
        
        return $this->monthly_fixed_costs;
    }
    
    public function transmitterRouter()
    {
        return $this->belongsTo(Router::class, 'transmitter_router_id');
    }

    public function receiverRouter()
    {
        return $this->belongsTo(Router::class, 'receiver_router_id');
    }

    public function getBroadcastDevicesAttribute() 
    {
        // Get existing TowerDevices
        $towerDevices = $this->devices;

        // Get Routers that are APs or Base Stations linked to this tower
        $routers = $this->routers()
            ->whereIn('device_type', ['access_point', 'base_station'])
            ->get()
            ->map(function ($router) {
                // Map Router attributes to match TowerDevice structure (duck typing for view)
                $router->mode = $router->device_type === 'access_point' ? 'ap' : 'station'; // Approximate mapping
                $router->frequency = $router->frequency ?? '-'; // Router might not have frequency column yet
                
                // If antenna_type is set, use it to refine display if needed, or just append to name
                if ($router->antenna_type) {
                    $router->mode = ucfirst($router->antenna_type);
                }

                return $router;
            });

        return $towerDevices->concat($routers);
    }

    // New Attribute for Total Equipment Cost
    public function getTotalEquipmentCostAttribute() 
    {
        $routersCost = $this->routers->sum('price');
        return ($this->structure_cost ?? 0) + $routersCost;
    }
}
