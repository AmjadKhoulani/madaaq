<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Models\Client;
use App\Models\Tower;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $routers = Router::with('deviceModel')->whereNotNull('lat')->whereNotNull('lng')->get();
        
        $servers = \App\Models\MikroTikServer::whereNotNull('lat')->whereNotNull('lng')->get();
        $serverArrs = $servers->map(function($server) {
             $arr = $server->toArray();
             $arr['device_type'] = 'server';
             $arr['coverage_radius'] = 0;
             $arr['azimuth'] = 0;
             $arr['beam_width'] = 0;
             $arr['is_server_model'] = true;
             return $arr;
        });

        $routers = $routers->concat($serverArrs);

        $clients = Client::whereNotNull('lat')->whereNotNull('lng')->get();
        $towers = Tower::with('routers')->whereNotNull('lat')->whereNotNull('lng')->get();

        return view('maps.index', compact('routers', 'clients', 'towers'));
    }
}
