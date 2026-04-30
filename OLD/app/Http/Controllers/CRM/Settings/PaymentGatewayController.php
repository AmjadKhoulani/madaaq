<?php

namespace App\Http\Controllers\CRM\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = [
            'stripe' => ['stripe_public_key', 'stripe_secret_key', 'stripe_active'],
            'paypal' => ['paypal_client_id', 'paypal_secret', 'paypal_active'],
            'cham_cash' => ['cham_cash_merchant_id', 'cham_cash_secret_key', 'cham_cash_active'],
            'cham_cash_personal' => ['cham_cash_personal_wallet', 'cham_cash_personal_qr', 'cham_cash_personal_active'],
            'syriatel_cash' => ['syriatel_cash_merchant_id', 'syriatel_cash_pin', 'syriatel_cash_active'],
        ];

        $settings = [];
        foreach ($gateways as $gateway => $keys) {
            foreach ($keys as $key) {
                $settings[$key] = Setting::getValue($key);
            }
        }

        return view('crm.settings.payments.index', compact('settings', 'gateways'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        // Handle File Upload for QR Code
        if ($request->hasFile('cham_cash_personal_qr')) {
            $path = $request->file('cham_cash_personal_qr')->store('payments/qr', 'public');
            Setting::setValue('cham_cash_personal_qr', $path);
            unset($data['cham_cash_personal_qr']);
        }

        foreach ($data as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->back()->with('success', 'تم تحديث إعدادات الدفع بنجاح');
    }
}
