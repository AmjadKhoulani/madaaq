<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateWebsiteAnalytics extends Command
{
    protected $signature = 'analytics:update-websites';
    protected $description = 'Collect top websites from MikroTik DNS cache';

    public function handle()
    {
        $this->info('🌐 Collecting website analytics from MikroTik servers...');
        
        $servers = \App\Models\MikroTikServer::where('connection_status', 'connected')->get();
        if ($servers->isEmpty()) {
            $this->warn('No connected servers found.');
            return;
        }

        foreach ($servers as $server) {
            try {
                $service = new \App\Services\MikroTikService($server);
                $dnsCache = $service->getDnsCache();
                
                $domains = [];
                foreach ($dnsCache as $entry) {
                    $name = $entry['name'] ?? '';
                    if ($name && !str_contains($name, '.arpa') && str_contains($name, '.') && !str_contains($name, 'localhost')) {
                        // Filter out common background services
                        $skipKeywords = ['ntp.org', 'nist.gov', '3gppnetwork.org', 'microsoft.com', 'apple.com', 'google.com/as', 'gstatic.com', 'fbcdn.net'];
                        $skip = false;
                        foreach ($skipKeywords as $kw) {
                            if (str_contains($name, $kw)) {
                                $skip = true;
                                break;
                            }
                        }
                        if ($skip) continue;

                        $parts = explode('.', $name);
                        $count = count($parts);
                        if ($count >= 2) {
                            $domain = $parts[$count-2] . '.' . $parts[$count-1];
                            $domains[$domain] = ($domains[$domain] ?? 0) + 1;
                        }
                    }
                }

                $tenantId = $server->tenant_id ?? 1;

                foreach ($domains as $domain => $hits) {
                    if (empty($domain)) continue;

                    $analytic = \App\Models\WebsiteAnalytic::firstOrNew([
                        'tenant_id' => $tenantId,
                        'domain' => $domain,
                        'recorded_date' => now()->toDateString(),
                    ]);

                    $analytic->hits = ($analytic->exists ? $analytic->hits : 0) + $hits;
                    $analytic->bytes = ($analytic->exists ? $analytic->bytes : 0) + ($hits * 1024 * 50);
                    $analytic->save();
                }

                $this->info("✓ Collected " . count($domains) . " domains from {$server->name}");

            } catch (\Exception $e) {
                $this->error("✗ Failed to collect from {$server->name}: " . $e->getMessage());
            }
        }

        $this->info('✅ Website analytics updated!');
    }
}
