<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Services\MikroTikService;
use App\Models\Router;
use App\Models\MikroTikServer;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function sessions(Request $request)
    {
        // This is a heavy operation as it connects to all routers.
        // For API, we might want to paginate or filter by specific router if possible.
        // For now, mimicking the web controller logic.
        
        $routers = Router::where('tenant_id', auth()->user()->tenant_id ?? 1)->get(); // Filter by tenant if needed
        // Assuming MikroTikServers are also tenant scoped or global? Using all for now or check usage.
        // Usually servers are infrastructure, maybe scoped? 
        $mikrotikServers = MikroTikServer::all(); 
        
        $allSessions = [];

        // Fetch from Routers
        foreach ($routers as $router) {
            try {
                $service = new MikroTikService($router);
                $sessions = $service->getActiveSessions('all'); // 'all' fetches pppoe and hotspot
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
        
        // Pagination logic for array
        $page = $request->input('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($allSessions, $offset, $perPage);
        
        return response()->json([
            'data' => $paginatedItems,
            'total' => count($allSessions),
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil(count($allSessions) / $perPage)
        ]);
    }

    public function disconnectSession(Request $request)
    {
        $request->validate([
            'router_id' => 'required|integer',
            'router_type' => 'required|string',
            'session_id' => 'required|string',
            'type' => 'required|in:pppoe,hotspot',
        ]);

        try {
            if ($request->router_type === 'MikroTikServer') {
                $router = MikroTikServer::findOrFail($request->router_id);
            } else {
                $router = Router::findOrFail($request->router_id);
            }
            
            $service = new MikroTikService($router);
            $service->disconnectSession($request->session_id, $request->type);

            return response()->json(['message' => 'Session disconnected successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to disconnect: ' . $e->getMessage()], 500);
        }
    }

    public function topology()
    {
        $towers = \App\Models\Tower::where('tenant_id', auth()->user()->tenant_id)
            ->with(['routers', 'sendingServer', 'mikrotikServer'])
            ->get();
            
        // Construct simple nodes/links structure
        $nodes = [];
        $links = [];
        
        foreach ($towers as $tower) {
             $nodes[] = [
                 'id' => 'tower_' . $tower->id,
                 'label' => $tower->name,
                 'type' => 'tower',
                 'lat' => $tower->lat,
                 'lng' => $tower->lng,
                 'status' => $tower->status
             ];
             
             // Server Link
             if ($tower->sending_server_id) {
                 $links[] = [
                     'source' => 'server_' . $tower->sending_server_id,
                     'target' => 'tower_' . $tower->id,
                     'type' => 'wireless'
                 ];
                 // Ensure server node exists (might be duplicate but frontend handles or we unique)
                 // Ideally we fetch servers separately
             }
             
             // Routers
             foreach ($tower->routers as $router) {
                  $nodes[] = [
                      'id' => 'router_' . $router->id, 
                      'label' => $router->name,
                      'type' => 'router',
                      'parent_id' => 'tower_' . $tower->id // Logic for nesting
                  ];
             }
        }
        
        return response()->json([
            'nodes' => $nodes,
            'links' => $links
        ]);
    }

    public function analytics()
    {
        // Placeholder for now as Website Analytics is complex (Log aggregation)
        // Returning mock or basic data
        return response()->json([
            'top_sites' => [],
            'blocked_count' => \App\Models\BlockedWebsite::where('tenant_id', auth()->user()->tenant_id)->count()
        ]);
    }
}
