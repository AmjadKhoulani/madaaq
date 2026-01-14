<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Services\MikroTikService;
use App\Models\Router;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessSubscriptionExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-subscription-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired subscriptions and suspend users if grace period is over';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Check Global Setting
        $isEnabled = \App\Models\Setting::getValue('auto_suspend_enabled', 0);
        if (!$isEnabled) {
            $this->info('Auto-suspend is disabled in settings. Skipping.');
            return;
        }

        $this->info('Starting subscription expiry process...');
        
        $defaultGracePeriod = (int) \App\Models\Setting::getValue('default_grace_period', 0);

        // 2. Get all active clients who have expired
        // Status 'active' means they are currently supposed to be running
        $expiredClients = Client::where('status', 'active')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        $this->info("Found {$expiredClients->count()} potentially expired clients.");

        foreach ($expiredClients as $client) {
            
            // Calculate effective expiry including grace period
            // Use client specific if set, otherwise default
            $gracePeriod = $client->grace_period_days ?? $defaultGracePeriod;
            
            $effectiveExpiry = Carbon::parse($client->expires_at)->addDays($gracePeriod);

            if (Carbon::now()->gt($effectiveExpiry)) {
                $this->processSuspension($client);
            } else {
                $this->info("Client {$client->username} is in grace period until {$effectiveExpiry}");
            }
        }

        $this->info('Expiry process completed.');
    }

    protected function processSuspension(Client $client)
    {
        $this->info("Suspending client: {$client->username} (ID: {$client->id})");

        try {
            // 1. Disable on MikroTik
            // Determine Router or Server (Hotspot clients might have mikrotik_server_id)
            $mikrotikDevice = $client->router ?? $client->mikrotikServer;

            if ($mikrotikDevice) {
                $service = new MikroTikService($mikrotikDevice);
                // Try disabling based on type
                if ($client->type === 'broadband' || $client->type === 'pppoe') {
                    $service->disableUser($client->username);
                    // Optionally kick
                    $session = $service->getSessionDetails($client->username);
                    if ($session) {
                        $service->disconnectSession($session['.id'], 'pppoe');
                    }
                } elseif ($client->type === 'hotspot') {
                    $service->disableHotspotUser($client->username);
                    // Optionally kick
                    $session = $service->getSessionDetails($client->username);
                    if ($session && ($session['type'] ?? '') === 'hotspot') {
                        $service->disconnectSession($session['.id'], 'hotspot');
                    }
                }
            } else {
                Log::warning("Client {$client->id} has no linked router/server. Skipping MikroTik sync.");
            }

            // 2. Update Local Status
            $client->status = 'suspended';
            $client->save();
            
            // 3. Log Activity / Send Notification
            // ClientActivity::create([...]); 
            // NotificationService::send(...)

            Log::info("Client {$client->id} suspended due to expiry.");

        } catch (\Exception $e) {
            Log::error("Failed to suspend client {$client->id}: " . $e->getMessage());
            $this->error("Error suspending {$client->username}: " . $e->getMessage());
        }
    }
}
