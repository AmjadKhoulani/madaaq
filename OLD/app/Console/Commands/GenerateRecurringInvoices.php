<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-recurring-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new invoices for subscriptions expiring soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting recurring invoice generation...');

        // Settings (should be dynamic later)
        $leadTimeDays = 3; 

        // Find active clients expiring in exactly X days
        // We use date comparison to avoid duplicate invoice generation if run multiple times
        $targetDate = Carbon::now()->addDays($leadTimeDays)->format('Y-m-d');
        
        $expiringClients = Client::where('status', 'active')
            ->where('auto_renewal', true)
            ->whereDate('expires_at', $targetDate)
            ->get();

        $this->info("Found {$expiringClients->count()} clients expiring on {$targetDate}");

        foreach ($expiringClients as $client) {
            // Check if invoice already exists for this period?
            // Simple check: pending invoice created recently for this client
            $existingInvoice = Invoice::where('client_id', $client->id)
                ->where('status', 'pending')
                ->where('created_at', '>=', Carbon::now()->subDays(5))
                ->first();

            if ($existingInvoice) {
                $this->info("Skipping {$client->username}, pending invoice already exists.");
                continue;
            }

            try {
                $package = $client->package;
                $amount = $client->custom_price ?? ($package ? $package->price : 0);
                
                if ($amount <= 0) {
                     $this->info("Skipping {$client->username}, amount is zero/invalid.");
                     continue;
                }

                $invoice = Invoice::create([
                    'tenant_id' => $client->tenant_id,
                    'client_id' => $client->id,
                    'amount' => $amount,
                    'status' => 'pending',
                    'due_date' => $client->expires_at, // Due on expiry day
                    'notes' => 'تجديد تلقائي للباقة: ' . ($package->name ?? 'N/A'),
                ]);

                $this->info("Generated Invoice #{$invoice->id} for {$client->username}");
                
                // Send Notification (Email/SMS/WhatsApp)
                // NotificationService::sendInvoiceGenerated($client, $invoice);

            } catch (\Exception $e) {
                Log::error("Failed to generate invoice for client {$client->id}: " . $e->getMessage());
                $this->error("Error generating invoice for {$client->username}");
            }
        }

        $this->info('Invoice generation process completed.');
    }
}
