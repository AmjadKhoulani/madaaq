<?php

namespace App\Services\Payment;

class StripeGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, string $currency, array $options = []): array
    {
        // Real implementation would use Stripe SDK:
        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
        // For now, we simulate a successful charge
        return [
            'success' => true,
            'transaction_id' => 'ch_fake_stripe_' . uniqid(),
            'raw' => ['status' => 'succeeded', 'gateway' => 'stripe']
        ];
    }
}
