<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Models\Tower;
use App\Models\MikroTikServer;
use App\Models\DeviceStatusLog;
use App\Models\NetworkAlert;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        // Get latest status for each device type
        $routers = Router::all()->map(function ($router) {
            $status = $router->is_reachable ? 'online' : 'offline';
            
            // Check logs if reachability check is stale or not trusted
            $latestLog = DeviceStatusLog::where('device_type', Router::class)
                ->where('device_id', $router->id)
                ->latest('checked_at')
                ->first();

            // Prefer log status if recent
            if ($latestLog) {
                // If log is very recent (within 5 mins), trust it
                /* if ($latestLog->checked_at->diffInMinutes(now()) < 5) {
                    $status = $latestLog->status;
                } */
                // For now, simplify: if log says online, trust it.
                if ($latestLog->status === 'online') $status = 'online';
                // If log says offline but router says reachable? Trust router.
                if ($latestLog->status === 'offline' && $router->is_reachable) $status = 'online';
            }

            return [
                'id' => $router->id,
                'name' => $router->name,
                'ip' => $router->ip,
                'type' => 'router',
                'status' => $status,
                'response_time' => $latestLog->response_time ?? null,
                'last_check' => $latestLog->checked_at ?? null,
            ];
        });

        $towers = Tower::all()->map(function ($tower) {
            // Towers rely on router status if no direct IP
            $status = 'unknown';
            
            if ($tower->router && $tower->router->is_reachable) {
                $status = 'online';
            }

            $latestLog = DeviceStatusLog::where('device_type', Tower::class)
                ->where('device_id', $tower->id)
                ->latest('checked_at')
                ->first();

            if ($latestLog) {
                 if ($latestLog->status === 'online') $status = 'online';
            }
            
            return [
                'id' => $tower->id,
                'name' => $tower->name,
                'type' => 'tower',
                'status' => $status,
                'response_time' => $latestLog->response_time ?? null,
                'last_check' => $latestLog->checked_at ?? null,
            ];
        });

        $servers = MikroTikServer::all()->map(function ($server) {
             $latestLog = DeviceStatusLog::where('device_type', MikroTikServer::class)
                ->where('device_id', $server->id)
                ->latest('checked_at')
                ->first();
            
            return [
                'id' => $server->id,
                'name' => $server->name,
                'ip' => $server->ip,
                'type' => 'server',
                'status' => $latestLog->status ?? 'unknown',
                'response_time' => $latestLog->response_time ?? null,
                'last_check' => $latestLog->checked_at ?? null,
            ];
        });

        $devices = collect([])
            ->merge($routers)
            ->merge($towers)
            ->merge($servers);

        // Active alerts
        $activeAlerts = NetworkAlert::where('is_resolved', false)
            ->with('device')
            ->latest()
            ->get();

        // Recent alerts (last 24h)
        $recentAlerts = NetworkAlert::where('created_at', '>=', now()->subDay())
            ->with('device')
            ->latest()
            ->take(50)
            ->get();

        // Stats
        $stats = [
            'total_devices' => $devices->count(),
            'online' => $devices->where('status', 'online')->count(),
            'offline' => $devices->where('status', 'offline')->count(),
            'warning' => $devices->where('status', 'warning')->count(),
            'active_alerts' => $activeAlerts->count(),
        ];

        return view('network.monitoring.index', compact('devices', 'activeAlerts', 'recentAlerts', 'stats'));
    }

    public function resolveAlert($id)
    {
        $alert = NetworkAlert::findOrFail($id);
        $alert->resolve();

        return back()->with('success', 'تم تحديد التنبيه كـ "محلول" وإزالته من القائمة.');
    }

    public function bandwidth(Request $request)
    {
        $period = $request->get('period', '24h');
        $interface = $request->get('interface');
        
        $now = now();
        $from = match($period) {
            '7d' => $now->copy()->subDays(7),
            '30d' => $now->copy()->subDays(30),
            default => $now->copy()->subDay(),
        };
        
        // Filter bandwidth logs to only show data from routers belonging to current vendor
        $query = \App\Models\BandwidthLog::where('recorded_at', '>=', $from)
            ->whereHas('router', function($q) {
                $q->where('tenant_id', auth()->user()->tenant_id);
            });
        
        if ($interface) {
            $query->where('interface_name', $interface);
        }

        $logs = $query->orderBy('recorded_at')->get();
        
        // Get available interfaces only from current vendor's routers
        $availableInterfaces = \App\Models\BandwidthLog::whereHas('router', function($q) {
                $q->where('tenant_id', auth()->user()->tenant_id);
            })
            ->distinct()
            ->pluck('interface_name');

        $chartData = $logs->groupBy(function($log) use ($period) {
            if ($period === '24h') {
                return $log->recorded_at->format('H:00');
            } else {
                return $log->recorded_at->format('Y-m-d');
            }
        })->map(function($group) {
            return [
                'rx' => round($group->sum('rx_bytes') / 1024 / 1024, 2), // Total MB in this bucket
                'tx' => round($group->sum('tx_bytes') / 1024 / 1024, 2), // Total MB in this bucket
            ];
        });
        
        return view('network.monitoring.bandwidth', compact('chartData', 'period', 'availableInterfaces', 'interface'));
    }
}
