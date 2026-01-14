<?php

namespace App\Services\Payment;

use App\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->baseUrl = config('services.paypal.mode') === 'live' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Get access token
     */
    protected function getAccessToken(): ?string
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post("{$this->baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('PayPal auth error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create payment order
     */
    public function createPaymentLink(Invoice $invoice): ?string
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'reference_id' => $invoice->invoice_number,
                            'description' => "Invoice #{$invoice->invoice_number}",
                            'amount' => [
                                'currency_code' => config('services.paypal.currency', 'USD'),
                                'value' => number_format($invoice->amount, 2, '.', ''),
                            ],
                        ]
                    ],
                    'application_context' => [
                        'return_url' => route('payment.paypal.success', ['invoice' => $invoice->id]),
                        'cancel_url' => route('payment.paypal.cancel', ['invoice' => $invoice->id]),
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $approveLink = collect($data['links'])->firstWhere('rel', 'approve');
                
                if ($approveLink) {
                    $invoice->update(['payment_link' => $approveLink['href']]);
                    return $approveLink['href'];
                }
            }

            Log::error('PayPal order creation failed', ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('PayPal error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Capture payment
     */
    public function capturePayment(string $orderId): bool
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('PayPal capture error: ' . $e->getMessage());
            return false;
        }
    }
}
