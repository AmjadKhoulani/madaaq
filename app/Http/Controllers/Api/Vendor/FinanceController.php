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

        return response()->json($invoices);
    }

    public function show($id)
    {
        $invoice = Invoice::where('tenant_id', auth()->user()->tenant_id)
            ->with(['client', 'items'])
            ->findOrFail($id);

        return response()->json($invoice);
    }
}
