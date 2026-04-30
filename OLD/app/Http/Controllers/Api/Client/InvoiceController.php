<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = $request->user()->invoices()->latest()->paginate(10);
        return response()->json($invoices);
    }

    public function show(Request $request, $id)
    {
        $invoice = $request->user()->invoices()->findOrFail($id);
        
        // Generate Payment Link if unpaid
        $payment_link = null;
        if ($invoice->status !== 'paid') {
           // $payment_link = route('payment.page', ['invoice' => $invoice->id]); // Example, or Stripe link
        }

        return response()->json([
            'invoice' => $invoice,
            'payment_link' => $payment_link
        ]);
    }
}
