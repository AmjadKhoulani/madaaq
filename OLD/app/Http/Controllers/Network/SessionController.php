<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Services\MikroTikService;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $routers = \App\Models\Router::all();
        $mikrotikServers = \App\Models\MikroTikServer::all();
        $allSessions = [];

        // Fetch from Routers
        foreach ($routers as $router) {
            try {
                $service = new MikroTikService($router);
                $sessions = $service->getActiveSessions('all');
                foreach ($sessions as $session) {
                    $allSessions[] = array_merge($session, [
                        'router_name' => $router->name,
                        'router_id' => $router->id,
                        'router_type' => 'Router'
                    ]);
                }
            } catch (\Exception $e) {
                // Skip offline
            }
        }

        // Fetch from MikroTik Servers
        foreach ($mikrotikServers as $server) {
            try {
                $service = new MikroTikService($server);
                $sessions = $service->getActiveSessions('all');
                foreach ($sessions as $session) {
                    $allSessions[] = array_merge($session, [
                        'router_name' => $server->name,
                        'router_id' => $server->id,
                        'router_type' => 'MikroTikServer'
                    ]);
                }
            } catch (\Exception $e) {
                // Skip offline
            }
        }

        return view('network.sessions.index', compact('allSessions', 'routers'));
    }

    public function disconnect(Request $request)
    {
        $request->validate([
            'router_id' => 'required|integer',
            'router_type' => 'required|string',
            'session_id' => 'required|string',
            'type' => 'required|in:pppoe,hotspot',
        ]);

        try {
            if ($request->router_type === 'MikroTikServer') {
                $router = \App\Models\MikroTikServer::findOrFail($request->router_id);
            } else {
                $router = \App\Models\Router::findOrFail($request->router_id);
            }
            
            $service = new MikroTikService($router);
            $service->disconnectSession($request->session_id, $request->type);

            return back()->with('success', 'تم قطع الاتصال بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل قطع الاتصال: ' . $e->getMessage());
        }
    }
}
