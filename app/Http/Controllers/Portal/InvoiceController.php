<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $client = Auth::guard('client')->user();
        $invoices = $client->invoices()->latest()->paginate(10);
        
        return view('portal.invoices.index', compact('invoices'));
    }

    public function show($tenant, $id)
    {
        $client = Auth::guard('client')->user();
        $invoice = $client->invoices()->findOrFail($id);
        
        return view('portal.invoices.show', compact('invoice'));
    }

    public function pay($tenant, $id)
    {
        $client = Auth::guard('client')->user();
        $invoice = $client->invoices()->where('status', 'unpaid')->findOrFail($id);
        
        // Get Active Gateways
        $gateways = [];
        if (\App\Models\Setting::getValue('stripe_active')) $gateways['stripe'] = 'Credit Card (Stripe)';
        if (\App\Models\Setting::getValue('paypal_active')) $gateways['paypal'] = 'PayPal';
        if (\App\Models\Setting::getValue('cham_cash_active')) $gateways['cham_cash'] = 'Cham Cash';
        if (\App\Models\Setting::getValue('cham_cash_personal_active')) $gateways['cham_cash_personal'] = 'Cham Cash (Manual)';
        if (\App\Models\Setting::getValue('syriatel_cash_active')) $gateways['syriatel_cash'] = 'Syriatel Cash';

        return view('portal.invoices.pay', compact('invoice', 'gateways'));
    }

    public function initiate(Request $request, $tenant, $id)
    {
        $client = Auth::guard('client')->user();
        $invoice = $client->invoices()->where('status', 'unpaid')->findOrFail($id);
        
        $gateway = $request->input('gateway');
        
        try {
            $paymentService = \App\Services\Payment\PaymentFactory::make($gateway);
            $result = $paymentService->initiatePayment($invoice, $invoice->amount);
            
            if ($result['type'] === 'redirect') {
                return redirect($result['url']);
            } elseif ($result['type'] === 'manual') {
                return view('portal.invoices.manual_pay', [
                    'invoice' => $invoice,
                    'result' => $result,
                    'gateway' => $gateway
                ]);
            }
            
            return back()->with('error', 'Payment initialization failed.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
