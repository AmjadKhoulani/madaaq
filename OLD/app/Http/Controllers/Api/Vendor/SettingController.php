<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SettingController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string'],
        ]);
        
        $user->update($validated);
        
        return response()->json(['message' => 'Profile updated', 'user' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Password updated']);
    }

    public function updateTenantDetails(Request $request)
    {
        $user = auth()->user();
        if (!$user->tenant_id) {
             return response()->json(['message' => 'User is not associated with a tenant'], 400);
        }

        $validated = $request->validate([
            'billing_address' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
        ]);
        
        $tenant = $user->tenant;
        $tenant->update($validated);

        return response()->json(['message' => 'Tenant details updated', 'tenant' => $tenant]);
    }
}
