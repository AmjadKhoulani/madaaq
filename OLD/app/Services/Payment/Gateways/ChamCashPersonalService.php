<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use App\Models\Invoice;
use App\Models\Setting;

class ChamCashPersonalService implements PaymentGatewayInterface
{
    public function initiatePayment(Invoice $invoice, $amount)
    {
        $walletNumber = Setting::getValue('cham_cash_personal_wallet');
        $qrImage = Setting::getValue('cham_cash_personal_qr');
        
        if (!$walletNumber) throw new \Exception("Cham Cash Personal is not configured.");

        // For manual payment, we don't redirect to external site.
        // We return data to show the QR code and instructions.
        return [
            'type' => 'manual',
            'wallet_number' => $walletNumber,
            'qr_image' => $qrImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($qrImage) : null,
            'instructions' => "يرجى تحويل المبلغ $amount ل.س إلى المحفظة أعلاه، ثم إرفاق إشعار التحويل."
        ];
    }

    public function verifyPayment($paymentId)
    {
        // Manual verification by Admin
        return false; // Pending approval
    }

    public function getSettingsKeys(): array
    {
        return ['cham_cash_personal_wallet', 'cham_cash_personal_qr'];
    }
}
