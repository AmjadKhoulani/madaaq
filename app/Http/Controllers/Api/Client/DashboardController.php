<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MikroTikService;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->user();
        $client->load(['package', 'router']);

        $usage = [
            'uptime' => '--',
            'download_speed' => 0,
            'upload_speed' => 0,
            'status' => 'offline'
        ];

        // Fetch Real Stats
        if ($client->router) {
            try {
                $service = new MikroTikService($client->router);
                $activeUser = $service->getActiveUser($client->username);

                if ($activeUser) {
                    $usage['status'] = 'online';
                    $usage['uptime'] = $activeUser['uptime'];
                    
                    $traffic = $service->getInterfaceTraffic('<pppoe-' . $client->username . '>');
                    if (!$traffic) {
                         $traffic = $service->getInterfaceTraffic($client->username);
                    }

                    if ($traffic) {
                        $usage['download_speed'] = round($traffic['tx_bps'] / 1024 / 1024, 2);
                        $usage['upload_speed'] = round($traffic['rx_bps'] / 1024 / 1024, 2);
                    }
                }
            } catch (\Exception $e) {
                // Log::error("Mobile API Stats Error: " . $e->getMessage());
            }
        }

        return response()->json([
            'client' => [
                'name' => $client->name,
                'status' => $client->status,
                'expires_at' => $client->expires_at ? $client->expires_at->format('Y-m-d') : null,
                'days_left' => $client->expires_at ? $client->expires_at->diffInDays() : 0,
                'balance' => $client->balance ?? 0, // Assuming balance column exists or calculated?
                // Price
                'price' => $client->price ?? $client->package->price ?? 0,
                'currency' => 'ر.س',
            ],
            'package' => $client->package ? [
                'name' => $client->package->name,
                'speed_down' => $client->package->speed_down,
                'speed_up' => $client->package->speed_up,
                'data_limit' => $client->package->data_limit,
            ] : null,
            'usage' => $usage
        ]);
    }
}
