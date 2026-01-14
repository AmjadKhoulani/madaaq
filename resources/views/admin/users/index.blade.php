@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">إدارة المستخدمين (Staff)</h2>
            <p class="text-gray-400 mt-1">مشاهدة والبحث عن كافة موظفي ومدراء النظام لدى الشركات</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            
            <div class="flex-1 min-w-[250px]">
                <label class="block text-xs text-gray-400 mb-1">بحث (الاسم، البريد، الهاتف)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث هنا..." class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-gray-400 mb-1">الشركة (Tenant)</label>
                <select name="tenant_id" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="">كل الشركات</option>
                    @foreach($tenants as $id => $name)
                        <option value="{{ $id }}" {{ request('tenant_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

             <div class="flex-1 min-w-[150px]">
                <label class="block text-xs text-gray-400 mb-1">الدور</label>
                <select name="role" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="">الكل</option>
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                بحث
            </button>
            
            @if(request()->hasAny(['search', 'tenant_id', 'role']))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg font-medium transition-colors">
                    إلغاء
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-750 border-b border-gray-700 text-gray-400 text-sm uppercase">
                        <th class="px-6 py-4 font-medium">الاسم</th>
                        <th class="px-6 py-4 font-medium">البريد الإلكتروني</th>
                        <th class="px-6 py-4 font-medium">الهاتف</th>
                        <th class="px-6 py-4 font-medium">الشركة</th>
                        <th class="px-6 py-4 font-medium">الأدوار</th>
                        <th class="px-6 py-4 font-medium">تاريخ التسجيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-300 font-mono text-sm">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-300 font-mono text-sm">{{ $user->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-300">
                            @if($user->tenant)
                                <a href="{{ route('admin.tenants.show', $user->tenant_id) }}" class="text-indigo-400 hover:underline">
                                    {{ $user->tenant->name }}
                                </a>
                            @else
                                <span class="text-orange-400">Super Admin / No Tenant</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                <span class="bg-gray-700 text-gray-300 px-2 py-0.5 rounded text-xs border border-gray-600">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm font-mono">
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            لا يوجد مستخدمين.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-700 bg-gray-800">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
@endsection
