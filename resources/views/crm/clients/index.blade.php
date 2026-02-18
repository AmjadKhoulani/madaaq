@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إدارة العملاء</h2>
            <p class="text-gray-500 mt-1">نظام CRM شامل لإدارة علاقات العملاء</p>
        </div>
        <a href="{{ route('crm.clients.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>عميل جديد</span>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-200">
        <form method="GET" class="flex flex-col md:flex-row items-center gap-3">
            <div class="flex-1 w-full text-right">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم، البريد، الهاتف..." class="w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <span class="absolute right-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                <select name="status" class="w-full md:w-40 py-2.5 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white font-medium text-gray-700">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                
                <select name="type" class="w-full md:w-40 py-2.5 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white font-medium text-gray-700">
                    <option value="">كل الأنواع</option>
                    <option value="pppoe" {{ request('type') == 'pppoe' ? 'selected' : '' }}>برودباند</option>
                    <option value="hotspot" {{ request('type') == 'hotspot' ? 'selected' : '' }}>هوت سبوت</option>
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-lg transition shadow-sm">
                بحث
            </button>
        </form>
    </div>

    <!-- Clients Table -->
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">العميل</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">الباقة</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($clients as $client)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-700 font-bold border border-indigo-100">
                                    {{ strtoupper(substr($client->username, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-1 font-bold text-gray-900">
                                        {{ $client->username }}
                                        @if(auth()->user()->tenant->is_subdomain_enabled && empty($client->password))
                                            <div class="group relative cursor-help">
                                                <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10 pointer-events-none">
                                                    لا يوجد بيانات دخول للبوابة
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @if($client->name)
                                        <div class="text-xs text-gray-500">{{ $client->name }}</div>
                                    @endif
                                    @if($client->phone)
                                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            {{ $client->phone }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($client->type === 'pppoe')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">برودباند</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">هوت سبوت</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-gray-700">{{ $client->package->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($client->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full ml-1.5 animate-pulse"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full ml-1.5"></span>
                                    غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium dir-ltr text-right">
                            {{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('crm.clients.show', $client) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 transition border border-gray-200">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center border border-gray-200">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">لا يوجد عملاء</h3>
                                    <p class="text-sm text-gray-500 mt-1">لم يتم العثور على أي عملاء يطابقون بحثك.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($clients->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $clients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
