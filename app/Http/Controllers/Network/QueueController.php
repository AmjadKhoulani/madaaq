<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Router;
use App\Services\MikroTikService;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index()
    {
        $routers = Router::all();
        $servers = \App\Models\MikroTikServer::all();
        $allQueues = [];

        foreach ($routers as $router) {
            try {
                $service = new MikroTikService($router);
                $queues = $service->getQueues();
                foreach ($queues as $queue) {
                    $allQueues[] = array_merge($queue, [
                        'router_name' => $router->name,
                        'router_id' => $router->id,
                        'router_type' => 'tower'
                    ]);
                }
            } catch (\Exception $e) {
                // Skip offline routers
            }
        }

        foreach ($servers as $server) {
            try {
                $service = new MikroTikService($server);
                $queues = $service->getQueues();
                foreach ($queues as $queue) {
                    $allQueues[] = array_merge($queue, [
                        'router_name' => $server->name,
                        'router_id' => $server->id,
                        'router_type' => 'core'
                    ]);
                }
            } catch (\Exception $e) {
                // Skip offline servers
            }
        }

        return view('network.queues.index', compact('allQueues', 'routers', 'servers'));
    }

    public function setSpeed(Request $request)
    {
        $request->validate([
            'router_id' => 'required|integer',
            'router_type' => 'required|in:tower,core',
            'username' => 'required|string',
            'download_speed' => 'required|string',
            'upload_speed' => 'required|string',
        ]);

        if ($request->router_type === 'core') {
            $router = \App\Models\MikroTikServer::findOrFail($request->router_id);
        } else {
            $router = Router::findOrFail($request->router_id);
        }

        $service = new MikroTikService($router);
        
        $service->setUserSpeed(
            $request->username,
            $request->download_speed,
            $request->upload_speed
        );

        return back()->with('success', 'السرعة تم تحديثها بنجاح');
    }
}
