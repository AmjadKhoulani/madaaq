<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\VendorInvoice;
use App\Models\User;
use App\Models\Client; // Assuming Client model exists
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function financial(Request $request)
    {
        $query = VendorInvoice::with('tenant'); // Eager load tenant

        // Filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->start_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(20);
        $total_revenue = $query->where('status', 'paid')->sum('amount');

        return view('admin.reports.financial', compact('invoices', 'total_revenue'));
    }

    public function tenants(Request $request)
    {
         $query = Tenant::withCount('users');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $tenants = $query->latest()->paginate(20);

        return view('admin.reports.tenants', compact('tenants'));
    }

    public function clients(Request $request)
    {
        $query = Client::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->latest()->paginate(20);
        $total_clients = Client::count();
        $active_clients = Client::where('status', 'active')->count();

        return view('admin.reports.clients', compact('clients', 'total_clients', 'active_clients'));
    }
}
