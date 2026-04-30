<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Router;
use App\Models\Tower;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get statistics
            $stats = [
                'total_clients' => Client::count(),
                'active_clients' => Client::where('status', 'active')->count(),
                'inactive_clients' => Client::whereIn('status', ['inactive', 'disabled', 'expired'])->count(),
                'expiring_soon' => Client::where('status', 'active')
                    ->whereBetween('expires_at', [now(), now()->addDays(7)])
                    ->count(),
                
                'total_revenue' => Invoice::where('status', 'paid')->sum('amount'),
                'monthly_revenue' => Invoice::where('status', 'paid')
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount'),
                'pending_amount' => Invoice::where('status', 'pending')->sum('amount'),
                
                'total_routers' => Router::count(),
                'total_towers' => Tower::count(),
                'total_packages' => Package::count(),
            ];

            // Network Monitoring Stats
            $routers = Router::all();
            $towers = Tower::all();
            
            $onlineCount = 0;
            $totalDevices = $routers->count() + $towers->count();

            foreach($routers as $router) {
                if ($router->is_reachable) $onlineCount++;
            }

            foreach($towers as $tower) {
                $latestLog = \App\Models\DeviceStatusLog::where('device_type', Tower::class)
                    ->where('device_id', $tower->id)
                    ->latest('checked_at')
                    ->first();
                if ($latestLog?->status === 'online') $onlineCount++;
            }
            
            $networkStats = [
                'total' => $totalDevices,
                'online' => $onlineCount,
                'offline' => $totalDevices - $onlineCount,
            ];

            // Active Alerts
            $activeAlerts = \App\Models\NetworkAlert::where('is_resolved', false)
                ->latest()
                ->take(5)
                ->get();

            // Bandwidth last 24h
            $bandwidthToday = \App\Models\BandwidthLog::where('recorded_at', '>=', now()->subDay())
                ->selectRaw('SUM(rx_bytes) as total_rx, SUM(tx_bytes) as total_tx')
                ->first();

            // Top 5 Websites
            $topWebsites = \App\Models\WebsiteAnalytic::where('recorded_date', '>=', now()->subDays(7))
                ->selectRaw('domain, SUM(hits) as total_hits')
                ->groupBy('domain')
                ->orderByDesc('total_hits')
                ->limit(5)
                ->get();

            // Recent activities
            $recentClients = Client::with('package')->latest()->take(5)->get();
            $recentInvoices = Invoice::with('client')->latest()->take(5)->get();

            // Chart data
            $revenueChart = $this->getRevenueChartData();
            $clientsChart = $this->getClientsChartData();

            // Expiring soon
            $expiringClients = Client::where('status', 'active')
                ->whereDate('expires_at', '>', now())
                ->whereDate('expires_at', '<=', now()->addDays(7))
                ->orderBy('expires_at')
                ->take(10)
                ->get();

            // Setup Checklist
            $setupChecklist = [
                'has_tower' => Tower::exists(),
                'has_server' => \App\Models\MikroTikServer::exists(),
                'has_package' => Package::exists(),
                'has_client' => Client::exists(),
            ];
            $setupComplete = !in_array(false, array_values($setupChecklist));

            return Inertia::render('Vendor/Dashboard', [
                'stats' => $stats,
                'networkStats' => $networkStats,
                'activeAlerts' => $activeAlerts,
                'bandwidthToday' => $bandwidthToday,
                'topWebsites' => $topWebsites,
                'recentClients' => $recentClients,
                'recentInvoices' => $recentInvoices,
                'revenueChart' => $revenueChart,
                'clientsChart' => $clientsChart,
                'expiringClients' => $expiringClients,
                'setupChecklist' => $setupChecklist,
                'setupComplete' => $setupComplete
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard Error: ' . $e->getMessage());
            return back()->with('error', 'Intelligence handshake failed.');
        }
    }

    private function getRevenueChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            
            $revenue = Invoice::where('status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('amount');
            
            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getClientsChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            
            $count = Client::whereDate('created_at', '<=', $date)->count();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function getStats()
    {
        // API endpoint for real-time stats
        return response()->json([
            'clients' => [
                'total' => Client::count(),
                'active' => Client::where('status', 'active')->count(),
                'inactive' => Client::where('status', 'inactive')->count(),
            ],
            'revenue' => [
                'total' => Invoice::where('status', 'paid')->sum('amount'),
                'pending' => Invoice::where('status', 'pending')->sum('amount'),
            ],
            'network' => [
                'routers' => Router::count(),
                'towers' => Tower::count(),
            ]
        ]);
    }
}
