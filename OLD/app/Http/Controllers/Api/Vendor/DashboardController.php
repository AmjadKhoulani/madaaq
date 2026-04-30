<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Invoice;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Client Stats
        $activeClients = Client::where('status', 'active')->count();
        $totalClients = Client::count();
        $expiredClients = Client::where('status', 'expired')
            ->orWhere('expires_at', '<', now())
            ->count();
        
        // 2. Expiring Today (Vital for sync)
        $expiringTodayCount = Client::where('status', 'active')
            ->whereDate('expires_at', now())
            ->count();

        // 3. Revenue (This Month) - Using Invoice model as per web dashboard
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
            
        // 4. Network Stats (Basic count for now)
        $onlineRouters = \App\Models\Router::where('is_reachable', true)->count();
        $totalRouters = \App\Models\Router::count();

        return response()->json([
            'stats' => [
                'active_users' => $activeClients,
                'total_users' => $totalClients,
                'expired_users' => $expiredClients,
                'expiring_today' => $expiringTodayCount, // New field for mobile widget
                'monthly_revenue' => $monthlyRevenue,
                'network_online' => $onlineRouters,
                'network_total' => $totalRouters,
            ],
            // Sending the actual expiring clients list could be useful too
            'expiring_today_list' => Client::where('status', 'active')
                ->whereDate('expires_at', now())
                ->select('id', 'name', 'expires_at', 'phone')
                ->get(),
        ]);
    }
}
