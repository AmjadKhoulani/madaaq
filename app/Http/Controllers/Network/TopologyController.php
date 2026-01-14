<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Models\Tower;
use App\Models\MikroTikServer;
use App\Models\DeviceStatusLog;

use App\Models\InternetSource;

class TopologyController extends Controller
{
    public function index()
    {
        $internetSources = InternetSource::all();

        // Get all devices with their latest status
        $routers = Router::with('deviceModel')->get()->map(function ($router) {
            $latestLog = DeviceStatusLog::where('device_type', Router::class)
                ->where('device_id', $router->id)
                ->latest('checked_at')
                ->first();

            return [
                'id' => $router->id,
                'name' => $router->name,
                'type' => 'router',
                'ip' => $router->ip,
                'status' => $latestLog->status ?? 'unknown',
                'image_url' => $router->deviceModel ? $router->deviceModel->image_url : null,
            ];
        });

        $towers = Tower::all()->map(function ($tower) {
            $latestLog = DeviceStatusLog::where('device_type', Tower::class)
                ->where('device_id', $tower->id)
                ->latest('checked_at')
                ->first();

            return [
                'id' => $tower->id,
                'name' => $tower->name,
                'type' => 'tower',
                'router_id' => null, // Towers don't belong to a single router
                'status' => $latestLog->status ?? 'unknown',
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
                'type' => 'server',
                'ip' => $server->ip,
                'status' => $latestLog->status ?? 'unknown',
            ];
        });

        return view('network.topology.index', compact('routers', 'towers', 'servers', 'internetSources'));
    }
}
