<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $client = Auth::guard('client')->user();
        $client->load(['package', 'router']);

        $usage = [
            'uptime' => '--',
            'download_speed' => 0,
            'upload_speed' => 0,
            'status' => 'offline'
        ];

        // Fetch Real Stats from Router
        if ($client->router) {
            try {
                $service = new \App\Services\MikroTikService($client->router);
                $activeUser = $service->getActiveUser($client->username);

                if ($activeUser) {
                    $usage['status'] = 'online';
                    $usage['uptime'] = $activeUser['uptime'];
                    
                    // If it's PPPoE, the interface name usually matches username or <pppoe-username>
                    // Let's try to get traffic for the interface named with username
                    $traffic = $service->getInterfaceTraffic('<pppoe-' . $client->username . '>');
                    if (!$traffic) {
                         $traffic = $service->getInterfaceTraffic($client->username);
                    }

                    if ($traffic) {
                        $usage['download_speed'] = round($traffic['tx_bps'] / 1024 / 1024, 2); // Router TX = User RX (Download)
                        $usage['upload_speed'] = round($traffic['rx_bps'] / 1024 / 1024, 2);   // Router RX = User TX (Upload)
                    }
                }
            } catch (\Exception $e) {
                // Log::error("Failed to fetch stats: " . $e->getMessage());
                // Fail silently for dashboard to load
            }
        }

        return view('portal.dashboard', compact('client', 'usage'));
    }
}

