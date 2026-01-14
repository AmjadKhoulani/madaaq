<?php

namespace App\Services\Payment;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StripeService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.stripe.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.stripe.secret');
    }

    /**
     * Create payment link for invoice
     */
    public function createPaymentLink(Invoice $invoice): ?string
    {
        if (!$this->apiKey) {
            Log::error('Stripe API key not configured');
            return null;
        }

        try {
            // Create product
            $product = $this->createProduct($invoice);
            
            // Create price
            $price = $this->createPrice($product['id'], $invoice->amount);
            
            // Create payment link
            $response = Http::withBasicAuth($this->apiKey, '')
                ->asForm()
                ->post("{$this->baseUrl}/payment_links", [
                    'line_items' => [
                        [
                            'price' => $price['id'],
                            'quantity' => 1,
                        ]
                    ],
                    'metadata' => [
                        'invoice_id' => $invoice->id,
                        'client_id' => $invoice->client_id,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $invoice->update(['payment_link' => $data['url']]);
                return $data['url'];
            }

            Log::error('Stripe payment link creation failed', ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('Stripe error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create Stripe product
     */
    protected function createProduct(Invoice $invoice): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->asForm()
            ->post("{$this->baseUrl}/products", [
                'name' => "Invoice #{$invoice->invoice_number}",
                'description' => "Payment for subscription",
            ]);

        return $response->json();
    }

    /**
     * Create Stripe price
     */
    protected function createPrice(string $productId, float $amount): array
    {
        // Convert to cents
        $amountCents = (int)($amount * 100);

        $response = Http::withBasicAuth($this->apiKey, '')
            ->asForm()
            ->post("{$this->baseUrl}/prices", [
                'product' => $productId,
                'unit_amount' => $amountCents,
                'currency' => config('services.stripe.currency', 'usd'),
            ]);

        return $response->json();
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhook(string $payload, string $signature): bool
    {
        $secret = config('services.stripe.webhook_secret');
        
        if (!$secret) {
            return false;
        }

        // Verify signature
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Process webhook event
     */
    public function handleWebhook(array $event): void
    {
        if ($event['type'] === 'checkout.session.completed') {
            $session = $event['data']['object'];
            $invoiceId = $session['metadata']['invoice_id'] ?? null;

            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice && $invoice->status !== 'paid') {
                    $invoice->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'payment_method' => 'stripe',
                        'transaction_id' => $session['id'],
                        'gateway_response' => json_encode($session),
                    ]);

                    // Extend client subscription
                    $this->extendSubscription($invoice);
                }
            }
        }
    }

    /**
     * Extend client subscription after payment
     */
    protected function extendSubscription(Invoice $invoice): void
    {
        $client = $invoice->client;
        if (!$client) return;

        $package = $client->package;
        $duration = $client->custom_duration_days ?? $package->duration_days ?? 30;

        // Extend from current expiry or today
        $currentExpiry = $client->expires_at ?? now();
        $newExpiry = $currentExpiry->addDays($duration);

        $client->update([
            'expires_at' => $newExpiry,
            'status' => 'active',
        ]);

        Log::info("Subscription extended for client {$client->id} until {$newExpiry}");
    }
}
