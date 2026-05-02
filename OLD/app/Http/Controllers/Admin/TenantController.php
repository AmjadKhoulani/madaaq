<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display a listing of the tenants.
     */
    public function index()
    {
        $tenants = Tenant::withCount('users')->latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created tenant in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'domain'    => 'required|string|max:255|unique:tenants,domain',
            'support_contact' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'owner_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        // 1. Create Tenant
        $tenant = Tenant::create([
            'name'   => $request->name,
            'domain' => $request->domain,
            'support_contact' => $request->support_contact,
            'billing_address' => $request->billing_address,
            'tax_number' => $request->tax_number,
            'status' => 'active',
        ]);

        // 2. Create Owner User
        $user = User::create([
            'name'      => $request->owner_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'tenant_id' => $tenant->id,
        ]);

        // Assign Role (using Spatie/Roles)
        // Ensure "owner" role exists
        $user->assignRole('owner');
        
        return redirect()->route('admin.tenants.index')->with('success', 'تم إنشاء الشركة بنجاح!');
    }

    /**
     * Display the specified tenant.
     */
    public function show(Tenant $tenant)
    {
        // View details
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Update the specified tenant status.
     */
    public function toggleStatus(Tenant $tenant)
    {
        $tenant->status = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->save();

        return back()->with('success', 'تم تحديث حالة الشركة.');
    }

    /**
     * Impersonate the tenant owner.
     */
    public function impersonate(Tenant $tenant)
    {
        // Find the owner user (role='owner') or the first user linked to this tenant
        $user = $tenant->users()->whereHas('roles', function($q) {
            $q->where('name', 'owner');
        })->first();

        if (!$user) {
            // Fallback to first user if no explicit owner found (e.g. legacy data)
            $user = $tenant->users()->first();
        }

        if (!$user) {
            return back()->with('error', 'لا يوجد مستخدمين لهذه الشركة لتسجيل الدخول بهم.');
        }

        // Store the original admin user ID in session
        session()->put('impersonated_by', auth()->id());

        // Log in as the user
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'تم تسجيل الدخول بصفتك ' . $user->name);
    }

    public function leaveImpersonation()
    {
        if (session()->has('impersonated_by')) {
            $adminId = session()->pull('impersonated_by');
            $admin = \App\Models\User::find($adminId);

            if ($admin) {
                \Illuminate\Support\Facades\Auth::login($admin);
                return redirect()->route('admin.tenants.index')->with('success', 'تمت العودة لحساب المسؤول.');
            }
        }

        return redirect()->route('login');
    }
}
