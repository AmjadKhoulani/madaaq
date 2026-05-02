<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('client')->latest();

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total_revenue' => Invoice::where('status', 'paid')->sum('amount'),
            'unpaid_amount' => Invoice::where('status', 'unpaid')->sum('amount'),
            'paid_count' => Invoice::where('status', 'paid')->count(),
            'unpaid_count' => Invoice::where('status', 'unpaid')->count(),
        ];

        return \Inertia\Inertia::render('Accounting/Invoices/Index', [
            'invoices' => $invoices,
            'stats' => $stats,
            'filters' => $request->only(['status'])
        ]);
    }

    public function create()
    {
        $clients = Client::select('id', 'username', 'name', 'type')->get();
        $nextInvoiceNumber = 'INV-' . strtoupper(Str::random(8));
        
        return \Inertia\Inertia::render('Accounting/Invoices/Create', [
            'clients' => $clients,
            'nextInvoiceNumber' => $nextInvoiceNumber
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:unpaid,paid',
        ]);

        $invoice = Invoice::create([
            'client_id' => $validated['client_id'],
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999), 
            'paid_at' => $validated['status'] === 'paid' ? now() : null,
            // Assuming description or items table later, for now just basic fields
        ]);

        return redirect()->route('accounting.invoices.index')->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client.router', 'client.package']);
        return \Inertia\Inertia::render('Accounting/Invoices/Show', [
            'invoice' => $invoice
        ]);
    }

    public function edit(Invoice $invoice)
    {
        $clients = Client::select('id', 'username', 'name')->get();
        $invoice->load('client');
        
        return \Inertia\Inertia::render('Accounting/Invoices/Edit', [
            'invoice' => $invoice,
            'clients' => $clients
        ]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,paid',
        ]);

        if ($validated['status'] === 'paid' && $invoice->status !== 'paid') {
            $validated['paid_at'] = now();
        } elseif ($validated['status'] === 'unpaid') {
            $validated['paid_at'] = null;
        }

        $invoice->update($validated);

        return redirect()->route('accounting.invoices.index')->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('accounting.invoices.index')->with('success', 'تم حذف الفاتورة بنجاح');
    }
}
