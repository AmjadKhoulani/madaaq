<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity; // Assuming Spatie ActivityLog usage
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Fetch logs for this tenant's users or subjects related to tenant
        // This can be complex depending on how logs are scoped.
        // Simple approach: logs caused by users of this tenant
        
        $tenantId = auth()->user()->tenant_id;
        
        $logs = Activity::whereHas('causer', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->latest()
            ->paginate(20);
            
        return response()->json($logs);
    }
}
