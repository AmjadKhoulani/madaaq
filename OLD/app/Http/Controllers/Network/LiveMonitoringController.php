<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Services\MikroTikService;
use Illuminate\Http\Request;

class LiveMonitoringController extends Controller
{
    public function index()
    {
        $routers = Router::all();
        return view('network.live-monitoring', compact('routers'));
    }

    public function getRealtimeStats()
    {
        $routers = Router::all();
        $stats = [];

        foreach ($routers as $router) {
            try {
                $service = new MikroTikService($router);
                $resources = $service->getSystemResources();
                $sessions = $service->getActiveSessions('all');

                $stats[] = [
                    'router_name' => $router->name,
                    'cpu_load' => $resources['cpu_load'] ?? 0,
                    'memory_usage' => round((($resources['total_memory'] - $resources['free_memory']) / $resources['total_memory']) * 100),
                    'active_sessions' => count($sessions),
                    'uptime' => $resources['uptime'] ?? '0',
                ];
            } catch (\Exception $e) {
                $stats[] = [
                    'router_name' => $router->name,
                    'cpu_load' => 0,
                    'memory_usage' => 0,
                    'active_sessions' => 0,
                    'uptime' => 'Offline',
                    'status' => 'offline'
                ];
            }
        }

        return response()->json($stats);
    }
}
