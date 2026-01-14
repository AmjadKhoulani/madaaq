<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Payment\StripeService;
use App\Services\Payment\PayPalService;
use App\Services\NotificationService;
use App\Services\MikroTikService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Generate payment link for invoice
     */
    public function generatePaymentLink(Invoice $invoice, Request $request)
    {
        $gateway = $request->input('gateway', 'stripe'); // stripe or paypal

        if ($gateway === 'stripe') {
            $stripeService = new StripeService();
            $link = $stripeService->createPaymentLink($invoice);
        } else {
            $paypalService = new PayPalService();
            $link = $paypalService->createPaymentLink($invoice);
        }

        if ($link) {
            return response()->json([
                'success' => true,
                'payment_link' => $link,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate payment link',
        ], 500);
    }

    /**
     * Stripe webhook handler
     */
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        $stripeService = new StripeService();
        
        if (!$stripeService->verifyWebhook($payload, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = json_decode($payload, true);
        $stripeService->handleWebhook($event);

        return response()->json(['status' => 'success']);
    }

    /**
     * PayPal success callback
     */
    public function paypalSuccess(Invoice $invoice, Request $request)
    {
        $orderId = $request->query('token');
        
        $paypalService = new PayPalService();
        
        if ($paypalService->capturePayment($orderId)) {
            // 1. Update Invoice Status
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => 'paypal',
                'transaction_id' => $orderId,
            ]);

            // 2. Extend Subscription & Re-enable User
            $client = $invoice->client;
            if ($client) {
                // Determine duration
                $duration = $client->custom_duration_days ?? ($client->package->duration_days ?? 30);
                
                // If expired, start from today? Or from last expiry?
                // Usually for prepaid, start from payment date.
                // If expired long ago, start from today.
                // If active, add to current expiry.
                if ($client->expires_at && $client->expires_at->gt(now())) {
                    $client->expires_at = $client->expires_at->addDays($duration);
                } else {
                    $client->expires_at = now()->addDays($duration);
                }

                $client->status = 'active';
                $client->save();

                // Re-enable on MikroTik
                if ($client->router) {
                    try {
                        $mikroTik = new \App\Services\MikroTikService($client->router);
                        $mikroTik->enableUser($client->username);
                    } catch (\Exception $e) {
                         // Log error but don't fail the payment flow
                        \Log::error("Failed to re-enable user {$client->username} on MikroTik: " . $e->getMessage());
                    }
                }
            }

            // 3. Send payment confirmation notification
            $notificationService = app(NotificationService::class);
            
            $notificationService->send($client, 'payment_received', [
                'username' => $client->username,
                'amount' => currency($invoice->amount, false),
                'new_expiry' => $client->expires_at?->format('Y-m-d') ?? 'N/A',
            ]);

            return redirect()->route('accounting.invoices.show', $invoice)
                ->with('success', 'تم الدفع وتجديد الاشتراك بنجاح! 🎉');
        }

        return redirect()->route('accounting.invoices.show', $invoice)
            ->with('error', 'فشل تأكيد الدفع');
    }

    /**
     * PayPal cancel callback
     */
    public function paypalCancel(Invoice $invoice)
    {
        return redirect()->route('accounting.invoices.show', $invoice)
            ->with('warning', 'تم إلغاء عملية الدفع');
    }
}
