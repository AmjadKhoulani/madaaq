<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::with('roles')->latest()->paginate(20);
        return \Inertia\Inertia::render('Staff/Index', [
            'staff' => $staff
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        return \Inertia\Inertia::render('Staff/Create', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'position' => 'nullable|string',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'] ?? null,
            'tenant_id' => auth()->user()->tenant_id ?? 1,
            'is_active' => true,
        ]);

        $user->roles()->sync($validated['roles']);

        return redirect()->route('staff.index')->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function edit(User $staff)
    {
        $roles = Role::all();
        $staff->load('roles');
        return \Inertia\Inertia::render('Staff/Edit', [
            'staff' => $staff,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|min:6',
            'phone' => 'nullable|string',
            'position' => 'nullable|string',
            'is_active' => 'boolean',
            'roles' => 'required|array',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'] ?? null,
            'is_active' => $request->has('is_active'),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $staff->update($data);
        $staff->roles()->sync($validated['roles']);

        return redirect()->route('staff.index')->with('success', 'تم تحديث الموظف بنجاح');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'تم حذف الموظف بنجاح');
    }
}
