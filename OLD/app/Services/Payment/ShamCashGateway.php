<?php

namespace App\Services\Payment;

class ShamCashGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, string $currency, array $options = []): array
    {
        // Sham Cash is Manual/Semi-Manual
        // Expects 'transaction_ref' from user input
        
        $ref = $options['transaction_ref'] ?? 'MANUAL-' . uniqid();
        
        return [
            'success' => true, // In real scenario, might be 'pending' until admin approval
            'transaction_id' => $ref,
            'raw' => ['status' => 'pending_verification', 'gateway' => 'sham_cash', 'ref' => $ref]
        ];
    }
}
