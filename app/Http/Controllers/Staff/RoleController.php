<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        // BelongsToTenant scope is applied automatically
        $roles = Role::withCount('users')->latest()->paginate(20);
        return view('staff.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('guard_name');
        return view('staff.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_role' => 'required|string',
            'additional_roles' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $validated['name'], // internal name
            'display_name' => $validated['display_name'], // arabic friendly name
            'description' => $validated['description'],
            'tenant_id' => auth()->user()->tenant_id ?? 1,
            'guard_name' => 'web',
        ]);

        $permissionsToSync = collect();

        if ($validated['base_role'] === 'custom') {
            if (!empty($validated['additional_roles'])) {
                 $sourceRoles = Role::where('tenant_id', auth()->user()->tenant_id)
                                    ->whereIn('name', $validated['additional_roles'])
                                    ->with('permissions')
                                    ->get();
                 foreach($sourceRoles as $sourceRole) {
                     $permissionsToSync = $permissionsToSync->merge($sourceRole->permissions);
                 }
            }
        } else {
             $sourceRole = Role::where('tenant_id', auth()->user()->tenant_id)
                                ->where('name', $validated['base_role'])
                                ->with('permissions')
                                ->first();
             if ($sourceRole) {
                 $permissionsToSync = $sourceRole->permissions;
             }
        }
        
        $role->permissions()->sync($permissionsToSync->pluck('id')->unique());

        return redirect()->route('roles.index')->with('success', 'تم إنشاء الدور الوظيفي بنجاح');
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::all();
        return view('staff.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Note: Update logic might need similar template handling if we allow changing templates, 
        // but usually update is for changing name/description or fine-tuning permissions.
        // For now, let's keep basic permission syncing update or assume advanced UI is needed later.
        // Given the redesign, maybe we should stick to basic permission management for update,
        // or re-introduce template selection. For now, preserving original update logic is safest unless asked.
        
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'],
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'تم تحديث الدور الوظيفي بنجاح');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف دور وظيفي مرتبط بموظفين. يرجى نقلهم لدور آخر أولاً.');
        }
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'تم حذف الدور الوظيفي بنجاح');
    }
}
