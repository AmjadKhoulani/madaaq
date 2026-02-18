@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إدارة الشركات (Tenants)</h2>
            <p class="text-gray-500 mt-1">عرض وإدارة جميع مقدمي الخدمة المشتركين في المنصة</p>
        </div>
        <a href="{{ route('admin.tenants.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors font-medium shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة شركة جديدة
        </a>
    </div>

    <!-- Tenants Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-sm uppercase">
                        <th class="px-6 py-4 font-bold">الشركة</th>
                        <th class="px-6 py-4 font-bold">المالك (Owner)</th>
                        <th class="px-6 py-4 font-bold">النطاق (Domain)</th>
                        <th class="px-6 py-4 font-bold">الحالة</th>
                        <th class="px-6 py-4 font-bold">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($tenants as $tenant)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-lg font-bold text-indigo-600 border border-indigo-100">
                                    {{ substr($tenant->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-gray-900 font-bold">{{ $tenant->name }}</div>
                                    <div class="text-gray-500 text-xs">ID: {{ $tenant->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                // User model uses Spatie Roles, so we query via relationship, not column.
                                $owner = $tenant->users()->whereHas('roles', function($q) {
                                    $q->where('name', 'owner');
                                })->first() ?? $tenant->users()->first();
                            @endphp
                            @if($owner)
                                <div class="text-gray-900 text-sm font-medium">{{ $owner->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $owner->email }}</div>
                            @else
                                <span class="text-gray-400 text-xs">-- لا يوجد مستخدمين --</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-indigo-600 font-mono text-sm">
                            <a href="http://{{ $tenant->domain }}" target="_blank" class="hover:underline flex items-center gap-1">
                                {{ $tenant->domain }}
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @if($tenant->status == 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">نشط</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-200">معلق</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Impersonate Button -->
                                <form action="{{ route('admin.tenants.impersonate', $tenant->id) }}" method="POST" onsubmit="return confirm('تنبيه: سيتم تسجيل خروجك من لوحة الإدارة وتسجيل الدخول بحساب هذا العميل. للمتابعة اضغط موافق.')">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-lg hover:bg-gray-100 text-indigo-600 hover:text-indigo-800 transition-colors" title="تسجيل الدخول كمالك للشركة">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                    </button>
                                </form>

                                <!-- Toggle Status -->
                                <form action="{{ route('admin.tenants.toggle', $tenant->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من تغيير حالة الشركة؟')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors" title="{{ $tenant->status == 'active' ? 'إيقاف الشركة' : 'تفعيل الشركة' }}">
                                        @if($tenant->status == 'active')
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </button>
                                </form>
                                
                                <!-- Edit Button (Link to Show Page) -->
                                <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors" title="عرض التفاصيل">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $tenants->links() }}
        </div>
    </div>
@endsection
