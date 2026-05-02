<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * Show subscription plans.
     */
    public function index()
    {
        $settings = [];
        $gateways = [];
        try {
            // Check enabled gateways using AdminSetting helper or query
            if (\App\Models\AdminSetting::where('key', 'stripe_active')->value('value')) {
                $gateways['stripe'] = 'Stripe (بطاقة ائتمان)';
            }
            if (\App\Models\AdminSetting::where('key', 'paypal_active')->value('value')) {
                $gateways['paypal'] = 'PayPal';
            }
            if (\App\Models\AdminSetting::where('key', 'sham_cash_active')->value('value')) {
                $gateways['sham_cash'] = 'شام كاش (Sham Cash)';
            }
            if (\App\Models\AdminSetting::where('key', 'syriatel_active')->value('value')) {
                $gateways['syriatel_cash'] = 'Syriatel Cash';
            }
            if (\App\Models\AdminSetting::where('key', 'turkish_active')->value('value')) {
                $gateways['turkish_iban'] = 'Turkish IBAN (Bank Transfer)';
            }

            // Fetch generic settings safely
            $settings['sham_cash_instructions'] = \App\Models\AdminSetting::where('key', 'sham_cash_instructions')->value('value');
            $settings['sham_cash_qr'] = \App\Models\AdminSetting::where('key', 'sham_cash_qr')->value('value');
            $settings['syriatel_cash_instructions'] = \App\Models\AdminSetting::where('key', 'syriatel_cash_instructions')->value('value');
            $settings['turkish_bank_details'] = \App\Models\AdminSetting::where('key', 'turkish_bank_details')->value('value');

        } catch (\Exception $e) {
            // Log error but allow page to load with empty gateways
            \Illuminate\Support\Facades\Log::error('Failed to load subscription gateways/settings: ' . $e->getMessage());
        }
        
        // Return view with gateways
        return view('subscription.index', compact('gateways', 'settings'));
    }

    /**
     * Process subscription (Mock Payment).
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic_annual,pro_annual',
            'payment_method' => 'required|in:stripe,paypal,sham_cash,syriatel_cash,turkish_iban',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'sham_cash_ref' => ['nullable', function ($attribute, $value, $fail) use ($request) {
                if ($request->payment_method === 'sham_cash' && !$request->hasFile('receipt_image') && empty($value)) {
                    $fail('يرجى إدخال رقم العملية أو إرفاق صورة الإشعار.');
                }
            }],
            'syriatel_ref' => ['nullable', function ($attribute, $value, $fail) use ($request) {
                if ($request->payment_method === 'syriatel_cash' && !$request->hasFile('receipt_image') && empty($value)) {
                     $fail('يرجى إدخال رقم العملية أو إرفاق صورة الإشعار.');
                }
            }],
            'turkish_ref' => ['nullable', function ($attribute, $value, $fail) use ($request) {
                if ($request->payment_method === 'turkish_iban' && !$request->hasFile('receipt_image') && empty($value)) {
                     $fail('يرجى إدخال رقم العملية أو إرفاق صورة الإشعار.');
                }
            }],
        ]);

        $user = auth()->user();
        $plan = $request->plan;
        $method = $request->payment_method;
        
        // Pricing Logic - Yearly Plans ($50 and $100)
        $plans = [
            'basic_annual' => 50.00,
            'pro_annual' => 100.00,
        ];
        
        $baseAmount = $plans[$plan] ?? 100.00;
        $amount = $baseAmount;
        $prorationDiscount = 0;

        // Proration Logic
        if ($user->subscription_status === 'active' && $user->subscription_ends_at && $user->subscription_ends_at->isFuture()) {
            $remainingDays =  now()->diffInDays($user->subscription_ends_at, false);
            if ($remainingDays > 0) {
                // Determine current plan value
                $currentPlanValue = $plans[$user->plan_name] ?? 50.00; // Default to basic if unknown
                $dailyRate = $currentPlanValue / 365;
                $prorationDiscount = round($remainingDays * $dailyRate, 2);
                
                // Calculate new amount
                $amount = max(0, $baseAmount - $prorationDiscount);
            }
        }
        
        // Manual Payment Check
        $isManual = in_array($method, ['sham_cash', 'syriatel_cash', 'turkish_iban']);

        try {
            if ($isManual) {
                 // Handle Manual Payments directly
                 $transactionId = $request->input($method === 'sham_cash' ? 'sham_cash_ref' : ($method === 'syriatel_cash' ? 'syriatel_ref' : 'turkish_ref'));
                 
                 $result = [
                     'success' => true,
                     'transaction_id' => $transactionId,
                     'raw' => ['method' => $method, 'ref' => $transactionId, 'proration_discount' => $prorationDiscount]
                 ];
            } else {
                // Gateway Strategy
                $gateway = match($method) {
                    'stripe' => new \App\Services\Payment\StripeGateway(),
                    'paypal' => new \App\Services\Payment\PaypalGateway(),
                    default => throw new \Exception("Invalid Payment Method"),
                };
                
                $result = $gateway->charge($amount, 'USD', []);
            }

            if (!$result['success']) {
                return back()->with('error', 'فشلت عملية الدفع. يرجى المحاولة مرة أخرى.');
            }

            // Create Invoice
            \App\Models\VendorInvoice::create([
                'tenant_id' => $user->tenant_id ?? 1,
                'amount' => $amount,
                'plan_name' => $plan,
                'invoice_number' => 'VINV-' . date('Ymd') . '-' . rand(1000, 9999),
                'status' => ($isManual && $method !== 'turkish_iban') ? 'pending' : 'paid', // Assume Turkish IBAN verified manually too actually, kept logic same as before but safe
                // Logic fix: Sham/Syriatel/Turkish usually manual pending. 
                // Previous code: $method === 'sham_cash' ? 'pending' : 'paid'. 
                // I will set all manual to pending if needed, but user might want 'paid' if auto-verified.
                // Reverting to previous logic slightly modified for better manual handling:
                'status' => $isManual ? 'pending' : 'paid', // All manual methods pending admin approval
                'paid_at' => $isManual ? null : now(),
                'expires_at' => now()->addYear(),
                'payment_method' => $method,
                'transaction_id' => $result['transaction_id'],
                'payment_details' => json_encode($result['raw']),
                'receipt_image' => $request->hasFile('receipt_image') 
                    ? $request->file('receipt_image')->store('receipts', 'public') 
                    : null,
            ]);

            // Update User Subscription (If instant payment or trusted)
            // If manual, we wait for approval. 
            // Previous code updated instantly for non-sham_cash. 
            // I should stick to user requirements. Assuming manual needs check.
            if (!$isManual) {
                $user->update([
                    'subscription_status' => 'active',
                    'subscription_ends_at' => now()->addYear(),
                    'plan_name' => $plan
                ]);
                $msg = "تم الاشتراك بنجاح! شكراً لك.";
            } else {
                $msg = "تم استلام طلبك. سيتم تفعيل الاشتراك بعد التحقق من الدفعة.";
            }

            return redirect()->route('dashboard')->with('success', $msg);

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Start a 5-day free trial.
     */
    public function startTrial(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic_annual,pro_annual',
        ]);

        $user = auth()->user();

        // Check if user already used trial (Optional: Add 'trial_used' column if needed later, 
        // for now we just check if they have ever had a subscription or trial before could be complex, 
        // but let's stick to the request: valid for 5 days).
        
        // Prevent abuse: If they already have an active subscription, don't override it with a trial
        if ($user->subscription_status === 'active' && $user->subscription_ends_at && $user->subscription_ends_at->isFuture()) {
            return back()->with('error', 'لديك اشتراك نشط بالفعل.');
        }

        $user->update([
            'plan_name' => $request->plan,
            'trial_ends_at' => now()->addDays(5),
            'subscription_status' => 'trial', // Explicit status for UI handling
        ]);

        return redirect()->route('dashboard')->with('success', 'تم تفعيل الفترة التجريبية (باقة ' . ($request->plan == 'pro_annual' ? 'برو' : 'أساسي') . ') لمدة 5 أيام بنجاح! استمتع بالتجربة.');
    }
}
