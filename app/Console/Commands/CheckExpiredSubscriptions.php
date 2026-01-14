<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'app:check-expired-subscriptions';
    protected $description = 'Check and suspend expired subscriptions';

    public function handle()
    {
        $expiredSubs = \App\Models\Subscription::where('status', 'active')
            ->where('expires_at', '<', now())
            ->with(['client.router'])
            ->get();

        foreach ($expiredSubs as $sub) {
            $client = $sub->client;
            
            if (!$client || !$client->router) {
                continue;
            }

            try {
                $service = new \App\Services\MikroTikService($client->router);
                $service->disableUser($client->username);
                
                $client->update(['status' => 'suspended']);
                $sub->update(['status' => 'expired']);
                
                $this->info("Suspended client {$client->username} (Tenant: {$client->tenant_id})");
            } catch (\Exception $e) {
                $this->error("Failed to suspend {$client->username}: " . $e->getMessage());
            }
        }
        
        $this->info("Checked " . $expiredSubs->count() . " expired subscriptions.");
    }
}
