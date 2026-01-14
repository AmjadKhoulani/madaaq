<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity('created', $model);
        });

        static::updated(function ($model) {
            static::logActivity('updated', $model, $model->getOriginal());
        });

        static::deleted(function ($model) {
            static::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($event, $model, $old = [])
    {
        // Skip if no authenticated user
        if (!Auth::check()) {
            return;
        }

        $modelName = class_basename($model);
        $descriptions = [
            'created' => "تم إنشاء {$modelName} جديد",
            'updated' => "تم تعديل {$modelName}",
            'deleted' => "تم حذف {$modelName}",
        ];

        // Prepare properties
        $properties = [
            'attributes' => $model->getAttributes(),
        ];

        if ($event === 'updated' && !empty($old)) {
            $properties['old'] = $old;
            $changes = array_diff_assoc($model->getAttributes(), $old);
            $properties['changes'] = $changes;
        }

        // Check for Impersonation
        if (session()->has('impersonated_by')) {
            $adminId = session('impersonated_by');
            $adminUser = \App\Models\User::find($adminId);
            $adminName = $adminUser ? $adminUser->name : 'Unknown Admin';
            
            $properties['impersonator_id'] = $adminId;
            $properties['impersonator_name'] = $adminName;
            
            // Append to description
            $descriptions[$event] = ($descriptions[$event] ?? "عملية على {$modelName}") . " (بواسطة المسؤول: {$adminName})";
        }

        ActivityLog::create([
            'tenant_id' => Auth::user()->tenant_id ?? 1,
            'log_name' => strtolower($modelName),
            'description' => $descriptions[$event] ?? "عملية على {$modelName}",
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'causer_type' => get_class(Auth::user()),
            'causer_id' => Auth::id(),
            'properties' => $properties,
        ]);
    }
}
