<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('tenant');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->latest()->paginate(20);
        $tenants = Tenant::pluck('name', 'id');

        return view('admin.clients.index', compact('clients', 'tenants'));
    }

    public function show(Client $client)
    {
        // For now, redirect to index or show simple view. 
        // We will just show the index with filtered view for simplicity or a simple show page later.
        // Let's stick to the plan: List view first.
        return redirect()->route('admin.clients.index', ['search' => $client->username]);
    }
}
