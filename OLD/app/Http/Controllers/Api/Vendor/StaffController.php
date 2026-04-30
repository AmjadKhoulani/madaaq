<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('tenant_id', auth()->user()->tenant_id)
            ->with('roles')
            ->get();
            
        return response()->json(['data' => $staff]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        // Assign default role if provided or 'vendor_staff'
        // $user->assignRole('staff');

        return response()->json($user, 201);
    }
}
