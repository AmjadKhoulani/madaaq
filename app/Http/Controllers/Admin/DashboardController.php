<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Key Metrics
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            // Revenue Stats
            'total_revenue' => \App\Models\VendorInvoice::where('status', 'paid')->sum('amount'),
            'monthly_revenue' => \App\Models\VendorInvoice::where('status', 'paid')
                                    ->where('paid_at', '>=', now()->startOfMonth())
                                    ->sum('amount'),
            // Subscription Stats (Active Owners)
            'active_subscriptions' => User::whereHas('roles', function($q) {
                $q->where('name', 'owner');
            })->where('subscription_status', 'active')->count(),
            // End Users (Clients)
            'total_clients' => \App\Models\Client::count(),
        ];

        // 2. Charts Data (Last 6 Months)
        $months = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('Y-m'));
        }

        // Revenue Chart
        $revenueData = \App\Models\VendorInvoice::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(6)->startOfMonth())
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $chart_revenue = $months->map(fn($month) => $revenueData[$month] ?? 0);

        // Tenants Growth Chart
        $tenantsData = Tenant::where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        $chart_tenants = $months->map(fn($month) => $tenantsData[$month] ?? 0);

        // 3. Lists
        $recent_tenants = Tenant::withCount('users')
                            ->latest()
                            ->take(5)
                            ->get();

        $recent_invoices = \App\Models\VendorInvoice::with('tenant')
                            ->latest()
                            ->take(5)
                            ->get();

        // 4. Active Trials Report
        $trial_users = User::where(function($q) {
                $q->where('subscription_status', 'trial')
                  ->orWhere('trial_ends_at', '>', now());
            })
            ->where('trial_ends_at', '>', now()) // Ensure it's still active
            ->whereHas('roles', function($q) {
                $q->where('name', 'owner'); // Only show owners
            })
            ->with('tenant')
            ->orderBy('trial_ends_at')
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recent_tenants', 
            'recent_invoices', 
            'months', 
            'chart_revenue', 
            'chart_tenants',
            'trial_users'
        ));
    }
}
