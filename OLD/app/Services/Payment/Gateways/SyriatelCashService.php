<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use App\Models\Invoice;
use App\Models\Setting;

class SyriatelCashService implements PaymentGatewayInterface
{
    public function initiatePayment(Invoice $invoice, $amount)
    {
        $merchantId = Setting::getValue('syriatel_cash_merchant_id');
        
        if (!$merchantId) throw new \Exception("Syriatel Cash is not configured.");

        return [
            'type' => 'redirect',
            'url' => "https://syriatel.sy/cash/pay/mock_id"
        ];
    }

    public function verifyPayment($paymentId)
    {
        return true;
    }

    public function getSettingsKeys(): array
    {
        return ['syriatel_cash_merchant_id', 'syriatel_cash_pin'];
    }
}
