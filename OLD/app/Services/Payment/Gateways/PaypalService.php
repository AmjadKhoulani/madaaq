<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use App\Models\Invoice;
use App\Models\Setting;

class PaypalService implements PaymentGatewayInterface
{
    public function initiatePayment(Invoice $invoice, $amount)
    {
        $clientId = Setting::getValue('paypal_client_id');
        
        if (!$clientId) throw new \Exception("PayPal is not configured.");

        return [
            'type' => 'redirect',
            'url' => "https://paypal.com/checkout/mock_id"
        ];
    }

    public function verifyPayment($paymentId)
    {
        return true;
    }

    public function getSettingsKeys(): array
    {
        return ['paypal_client_id', 'paypal_secret'];
    }
}
