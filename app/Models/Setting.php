<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Setting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
    ];

    /**
     * Get a setting by key, or return default.
     */
    public static function getValue($key, $default = null)
    {
        // For development/demo, we assume tenant_id = 1 temporarily if not set in session
        // In production BelongsToTenant handles the filtering automatically
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
    
    /**
     * Set a setting value.
     */
    public static function setValue($key, $value)
    {
        // tenant_id is automatically handled by the trait observer creating, 
        // but for updateOrCreate we might need to be explicit if the scope is applied.
        // However, standard create/update calls work fine with the trait.
        
        // Since BelongsToTenant adds a global scope, updateOrCreate matches against logged in tenant.
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
