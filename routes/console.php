<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Monitor Network every 5 minutes
Schedule::command('monitor:devices')->everyFiveMinutes();
Schedule::command('bandwidth:collect')->everyFiveMinutes();

// Send Expiry Alerts daily at 10 AM
Schedule::command('alerts:expiry')->dailyAt('10:00');

// Process Subscription Expiry (Auto Suspend) - Check every hour
// Process Subscription Expiry (Auto Suspend) - Check every hour
Schedule::command('app:process-subscription-expiry')->hourly();

// Sync WireGuard Peers (Zero Touch Automation)
// Sync WireGuard Peers (Zero Touch Automation)
Schedule::command('sync:wireguard')->everyMinute();

// Auto Backup Servers (Daily at 2 AM)
Schedule::command('backup:servers')->dailyAt('02:00');
