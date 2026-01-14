<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use App\Models\Invoice;
use App\Models\Setting;

class StripeService implements PaymentGatewayInterface
{
    public function initiatePayment(Invoice $invoice, $amount)
    {
        $stripeKey = Setting::getValue('stripe_secret_key');
        if (!$stripeKey) throw new \Exception("Stripe is not configured.");

        // Integration with Stripe PHP SDK (Mocked for now)
        // \Stripe\Stripe::setApiKey($stripeKey);
        // ... create Checkout Session ...
        
        return [
            'type' => 'redirect',
            'url' => "https://checkout.stripe.com/pay/mock_session_id"
        ];
    }

    public function verifyPayment($paymentId)
    {
        // Verify via Stripe API
        return true;
    }

    public function getSettingsKeys(): array
    {
        return ['stripe_public_key', 'stripe_secret_key'];
    }
}
