<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorInvoice;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions/invoices.
     */
    public function index(Request $request)
    {
        $query = VendorInvoice::with('tenant');

        // Search (Invoice # or Tenant Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('tenant', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Payment Method
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        $invoices = $query->latest()->paginate(20)->withQueryString();

        // Gateway options for filter dropdown
        $gateways = [
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            'sham_cash' => 'شام كاش',
            'syriatel_cash' => 'سيريتيل',
            'turkish_iban' => 'حساب تركي',
        ];

        return view('admin.subscriptions.index', compact('invoices', 'gateways'));
    }

    /**
     * Approve a payment (e.g., Sham Cash).
     */
    public function approve(VendorInvoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'هذه الفاتورة مدفوعة بالفعل.');
        }

        DB::transaction(function () use ($invoice) {
            // Update Invoice
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Activate/Extend Tenant's Subscription
            // Note: We need to access the 'owner' user of the tenant to update subscription fields
            // Assuming the tenant has one primary owner for subscription purposes, 
            // OR we update the Tenant model if fields are there. 
            // Based on previous steps, subscription fields are on the USER model.
            
            $tenant = $invoice->tenant;
            $owner = $tenant->users()->whereHas('roles', function($q) {
                $q->where('name', 'owner');
            })->first() ?? $tenant->users()->first();

            if ($owner) {
                $owner->update([
                    'subscription_status' => 'active',
                    'subscription_ends_at' => now()->addYear(), // Or parse plan duration
                    'plan_name' => $invoice->plan_name,
                ]);
            }
        });

        return back()->with('success', 'تم قبول الدفع وتفعيل الاشتراك بنجاح.');
    }

    /**
     * Reject a payment.
     */
    public function reject(VendorInvoice $invoice)
    {
        $invoice->update(['status' => 'rejected']);
        return back()->with('success', 'تم رفض الفاتورة.');
    }

    /**
     * Manually extend a tenant's subscription.
     */
    public function extend(Request $request, Tenant $tenant)
    {
        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $owner = $tenant->users()->whereHas('roles', function($q) {
            $q->where('name', 'owner');
        })->first() ?? $tenant->users()->first();

        if ($owner) {
            $currentExpiry = $owner->subscription_ends_at ? \Carbon\Carbon::parse($owner->subscription_ends_at) : now();
            if ($currentExpiry->isPast()) {
                $currentExpiry = now();
            }
            
            $newExpiry = $currentExpiry->addDays($request->days);
            
            $owner->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $newExpiry,
            ]);
            
            return back()->with('success', 'تم تمديد الاشتراك بنجاح حتى ' . $newExpiry->format('Y-m-d'));
        }

        return back()->with('error', 'لم يتم العثور على مالك لهذا المشترك.');
    }
}
