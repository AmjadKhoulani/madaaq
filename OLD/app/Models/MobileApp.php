<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileApp extends Model
{
    protected $fillable = [
        'tenant_id',
        'app_name',
        'app_name_en',
        'package_name',
        'description',
        'icon_path',
        'primary_color',
        'secondary_color',
        'contact_email',
        'contact_phone',
        'website',
        'status',
        'is_paid',
        'price',
        'aab_file_path',
        'submitted_at',
        'completed_at',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'price' => 'decimal:2',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">قيد الانتظار</span>',
            'processing' => '<span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">قيد المعالجة</span>',
            'completed' => '<span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">مكتمل</span>',
            'rejected' => '<span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">مرفوض</span>',
            default => '<span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">غير معروف</span>',
        };
    }
}
