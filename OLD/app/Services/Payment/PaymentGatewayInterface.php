<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    /**
     * Charge the user.
     * 
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return array Result of the transaction ['success' => bool, 'transaction_id' => string, 'raw' => array]
     */
    public function charge(float $amount, string $currency, array $options = []): array;
}
