<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class NotificationTemplate extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'channel',
        'subject',
        'content',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Replace variables in template
     */
    public function render(array $variables = []): string
    {
        $content = $this->content;
        
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }
}
