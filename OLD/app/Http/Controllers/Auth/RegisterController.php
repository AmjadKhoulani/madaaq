<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request)
    {
        // Merge country code with phone number ensuring no duplication of code if user types it
        if ($request->has('country_code') && $request->has('phone')) {
             $phone = $request->phone;
             // Remove leading zero if present
             if (str_starts_with($phone, '0')) {
                 $phone = substr($phone, 1);
             }
             $request->merge(['phone' => $request->country_code . $phone]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'unique:tenants,domain', 'alpha_dash'],
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->company_name,
                'domain' => $request->domain, // Subdomain
                'status' => 'active',
                'plan_id' => null, // No plan yet
            ]);

            // 2. Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'subscription_status' => 'inactive',
            ]);

            // 3. Assign Role (Assuming 'admin' or 'owner' is the role for the main vendor user)
            // Need to check if roles are global or per tenant, usually typical Spatie roles
            // We'll try to find 'admin' role.
            $adminRole = \App\Models\Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->roles()->attach($adminRole);
            }

            DB::commit();

            event(new \Illuminate\Auth\Events\Registered($user));

            Auth::login($user);

            // Redirect to Subscription page to pick a plan immediately
            return redirect()->route('subscription.index')->with('success', 'تم إنشاء الحساب بنجاح! الرجاء فحص بريدك الإلكتروني لتأكيد الحساب واختيار باقة للاستمرار.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration failed: ' . $e->getMessage());
            return back()->with('error', 'فشل إنشاء الحساب. ' . $e->getMessage())->withInput();
        }
    }
}
