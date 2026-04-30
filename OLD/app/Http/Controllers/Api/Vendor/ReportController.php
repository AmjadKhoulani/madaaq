<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function financial(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        
        // Simplified financial stats
        $totalRevenue = Invoice::where('tenant_id', $tenantId)
            ->where('status', 'paid')
            ->sum('total');

        $pendingRevenue = Invoice::where('tenant_id', $tenantId)
            ->where('status', 'unpaid')
            ->sum('total');

        // Recent Invoices
        $recentInvoices = Invoice::where('tenant_id', $tenantId)
            ->latest()
            ->take(5)
            ->get();
            
        // Advanced: Monthly Revenue Chart (Last 6 months)
        $revenueChart = Invoice::where('tenant_id', $tenantId)
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), 
                DB::raw('sum(total) as amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // Advanced: Client Growth (Last 6 months)
        $clientGrowth = Client::where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), 
                DB::raw('count(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

         return response()->json([
            'total_revenue' => $totalRevenue,
            'pending_revenue' => $pendingRevenue,
            'recent_invoices' => $recentInvoices,
            'charts' => [
                'revenue' => $revenueChart,
                'clients' => $clientGrowth
            ],
            'currency' => \App\Models\Setting::getValue('currency', 'SAR')
        ]);
    }
}
