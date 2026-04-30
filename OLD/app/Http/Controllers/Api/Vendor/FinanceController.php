<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Invoice;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::where('tenant_id', auth()->user()->tenant_id)
            ->with(['client:id,name', 'items'])
            ->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(20);

        
        $data = $invoices->toArray();
        $data['currency'] = \App\Models\Setting::getValue('currency', 'SAR');

        return response()->json($data);
    }

    public function show($id)
    {
        $invoice = Invoice::where('tenant_id', auth()->user()->tenant_id)
            ->with(['client', 'items'])
            ->findOrFail($id);

        return response()->json([
            'invoice' => $invoice, // Wrap existing user to avoid breaking if mobile expects root? 
            // Wait, previous code returned json($invoice). If mobile expects fields at root, I should merge.
            ...$invoice->toArray(),
            'currency' => \App\Models\Setting::getValue('currency', 'SAR')
        ]);
    }
}
