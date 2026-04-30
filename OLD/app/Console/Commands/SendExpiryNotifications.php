<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendExpiryNotifications extends Command
{
    protected $signature = 'notifications:expiry';
    protected $description = 'Send expiry warning notifications to clients';

    public function handle()
    {
        $notificationService = app(NotificationService::class);
        
        // Find clients expiring in 3 days
        $expiring3Days = Client::whereNotNull('expires_at')
            ->whereDate('expires_at', '=', Carbon::today()->addDays(3))
            ->where('status', 'active')
            ->get();

        // Find clients expiring in 1 day
        $expiring1Day = Client::whereNotNull('expires_at')
            ->whereDate('expires_at', '=', Carbon::today()->addDays(1))
            ->where('status', 'active')
            ->get();

        // Find clients already expired
        $expired = Client::whereNotNull('expires_at')
            ->whereDate('expires_at', '<', Carbon::today())
            ->where('status', 'active')
            ->get();

        // Send 3-day warnings
        foreach ($expiring3Days as $client) {
            $package = $client->package;
            $variables = [
                'username' => $client->username,
                'package_name' => $package->name ?? 'N/A',
                'expiry_date' => $client->expires_at->format('Y-m-d'),
                'days_remaining' => 3,
            ];

            $notificationService->send($client, 'expiry_warning_3days', $variables);
            $this->info("Sent 3-day warning to {$client->username}");
        }

        // Send 1-day warnings
        foreach ($expiring1Day as $client) {
            $package = $client->package;
            $variables = [
                'username' => $client->username,
                'package_name' => $package->name ?? 'N/A',
                'expiry_date' => $client->expires_at->format('Y-m-d'),
                'days_remaining' => 1,
            ];

            $notificationService->send($client, 'expiry_warning_1day', $variables);
            $this->info("Sent 1-day warning to {$client->username}");
        }

        // Send expired notifications
        foreach ($expired as $client) {
            $package = $client->package;
            $variables = [
                'username' => $client->username,
                'package_name' => $package->name ?? 'N/A',
                'expiry_date' => $client->expires_at->format('Y-m-d'),
            ];

            $notificationService->send($client, 'subscription_expired', $variables);
            $this->info("Sent expiry notice to {$client->username}");
        }

        $this->info("Notification job completed!");
        $this->info("3-day warnings: {$expiring3Days->count()}");
        $this->info("1-day warnings: {$expiring1Day->count()}");
        $this->info("Expired notices: {$expired->count()}");

        return Command::SUCCESS;
    }
}
