<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use App\Models\Invoice;
use App\Models\Setting;

class ChamCashService implements PaymentGatewayInterface
{
    public function initiatePayment(Invoice $invoice, $amount)
    {
        $merchantId = Setting::getValue('cham_cash_merchant_id');
        $secretKey = Setting::getValue('cham_cash_secret_key');
        
        if (!$merchantId) throw new \Exception("Cham Cash Merchant is not configured.");

        // Example API Call to Cham Cash
        // $response = Http::post('https://api.chamcash.sy/pay', [...]);
        
        return [
            'type' => 'redirect',
            'url' => "https://chamcash.sy/pay/mock_id"
        ];
    }

    public function verifyPayment($paymentId)
    {
        return true;
    }

    public function getSettingsKeys(): array
    {
        return ['cham_cash_merchant_id', 'cham_cash_secret_key'];
    }
}
