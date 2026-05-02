<?php

namespace App\Services\Payment;

class PaypalGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, string $currency, array $options = []): array
    {
        // Real implementation would use PayPal SDK/REST API
        
        return [
            'success' => true,
            'transaction_id' => 'PAYID-FAKE-' . uniqid(),
            'raw' => ['status' => 'COMPLETED', 'gateway' => 'paypal']
        ];
    }
}
