<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendExpiryAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp renewal reminders to clients expiring soon';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\NotificationService $notificationService)
    {
        $this->info('Checking for expiring subscriptions...');

        $this->ensureTemplateExists();

        // 1. Clients expiring in 3 days
        $clients3Days = \App\Models\Client::where('status', 'active')
            ->whereDate('expires_at', now()->addDays(3))
            ->get();

        foreach ($clients3Days as $client) {
            $this->sendAlert($notificationService, $client, 3);
        }

        // 2. Clients expiring Today
        $clientsToday = \App\Models\Client::where('status', 'active')
            ->whereDate('expires_at', now())
            ->get();

        foreach ($clientsToday as $client) {
            $this->sendAlert($notificationService, $client, 0);
        }

        $this->info("Processed: " . ($clients3Days->count() + $clientsToday->count()) . " clients.");
    }

    protected function sendAlert($service, $client, $daysLeft)
    {
        if (!$client->phone) {
            $this->warn("Client {$client->username} has no phone number. Skipped.");
            return;
        }

        $message = "مرحباً {$client->name}،\n";
        if ($daysLeft > 0) {
            $message .= "نود تذكيرك بأن اشتراك الإنترنت سينتهي خلال {$daysLeft} أيام في " . $client->expires_at->format('Y-m-d') . ".\n";
        } else {
            $message .= "اشتراكك ينتهي اليوم " . $client->expires_at->format('Y-m-d') . ".\n";
        }
        $message .= "يرجى التجديد لضمان استمرار الخدمة.\n\nرصيدك الحالي: " . number_format($client->balance, 2);

        // Here we ideally use using the template system, but for simplicity/robustness we can pass variables
        // if the template uses {{name}}, {{date}}, {{days}}.
        // Let's assume the ensuring method created a template with those placeholders.
        
        $sent = $service->send($client, 'expiry_warning', [
            'name' => $client->name ?? $client->username,
            'date' => $client->expires_at->format('m-d'),
            'days' => (string)$daysLeft,
            'amount' => (string)$client->price
        ], 'whatsapp');

        if ($sent) {
            $this->info("Sent alert to {$client->username}");
        } else {
            $this->error("Failed to send to {$client->username}");
        }
    }

    protected function ensureTemplateExists()
    {
        $exists = \App\Models\NotificationTemplate::where('name', 'expiry_warning')->exists();
        if (!$exists) {
            \App\Models\NotificationTemplate::create([
                'tenant_id' => 1, // Default tenant
                'name' => 'expiry_warning',
                'channel' => 'whatsapp',
                'subject' => 'تنبيه انتهاء الاشتراك',
                'content' => "مرحباً {{name}} 👋\n\nنود تذكيرك بأن اشتراكك سينتهي خلال {{days}} أيام بتاريخ {{date}}.\nقيمة التجديد: {{amount}} ريال.\n\nيرجى السداد لتجنب انقطاع الخدمة.",
                'active' => true,
            ]);
            $this->info("Created default 'expiry_warning' template.");
        }
    }
}
