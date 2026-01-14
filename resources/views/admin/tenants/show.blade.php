@extends('layouts.admin')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.tenants.index') }}" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h2 class="text-2xl font-bold text-white">تفاصيل الشركة: {{ $tenant->name }}</h2>
            </div>
            <p class="text-gray-400 ml-7">عرض كافة المعلومات الخاصة بمزود الخدمة</p>
        </div>
        
        <div class="flex items-center gap-3">
             <!-- Impersonate Button -->
             <form action="{{ route('admin.tenants.impersonate', $tenant->id) }}" method="POST" onsubmit="return confirm('تنبيه: سيتم تسجيل خروجك من لوحة الإدارة وتسجيل الدخول بحساب هذا العميل. للمتابعة اضغط موافق.')">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    دخول كمالك للشركة
                </button>
            </form>

            <form action="{{ route('admin.tenants.toggle', $tenant->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                @csrf
                @method('PATCH')
                @if($tenant->status == 'active')
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-600/10 hover:bg-red-600/20 text-red-400 border border-red-600/30 rounded-lg transition-colors font-medium">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        إيقاف الحساب
                    </button>
                @else
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-green-600/10 hover:bg-green-600/20 text-green-400 border border-green-600/30 rounded-lg transition-colors font-medium">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        تفعيل الحساب
                    </button>
                @endif
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Company Details -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    معلومات الشركة الأساسية
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm text-gray-400 mb-1">اسم الشركة</div>
                        <div class="text-white font-medium text-lg">{{ $tenant->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 mb-1">النطاق (Domain)</div>
                        <a href="http://{{ $tenant->domain }}" target="_blank" class="text-indigo-400 font-mono text-lg hover:underline flex items-center gap-1">
                            {{ $tenant->domain }} <span class="text-xs opacity-50">↗</span>
                        </a>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 mb-1">الحالة</div>
                        @if($tenant->status == 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">نشط</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">معلق</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 mb-1">تاريخ الإنشاء</div>
                        <div class="text-white font-medium">{{ $tenant->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 mb-1">الرقم الضريبي</div>
                        <div class="text-white font-medium font-mono">{{ $tenant->tax_number ?? '--' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-400 mb-1">عنوان الفوترة</div>
                        <div class="text-white font-medium text-sm">{{ $tenant->billing_address ?? '--' }}</div>
                    </div>
                     <div>
                        <div class="text-sm text-gray-400 mb-1">جهة اتصال الدعم</div>
                        <div class="text-white font-medium text-sm">{{ $tenant->support_contact ?? '--' }}</div>
                    </div>
                </div>
            </div>

            <!-- Owners / Administrators -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        مدراء النظام للشركة
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="text-gray-400 text-xs uppercase border-b border-gray-700">
                                <th class="px-4 py-2">الاسم</th>
                                <th class="px-4 py-2">البريد الإلكتروني</th>
                                <th class="px-4 py-2">الهاتف</th>
                                <th class="px-4 py-2">الدور (Role)</th>
                                <th class="px-4 py-2">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($tenant->users as $user)
                            <tr class="hover:bg-gray-700/50">
                                <td class="px-4 py-3 text-white">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-gray-300 font-mono text-sm">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-gray-300 font-mono text-sm">{{ $user->phone ?? '--' }}</td>
                                <td class="px-4 py-3">
                                    <span class="bg-indigo-500/10 text-indigo-400 px-2 py-0.5 rounded text-xs border border-indigo-500/20">
                                        {{ $user->roles->pluck('name')->join(', ') ?: 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-green-400 text-xs">● نشط</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Clients Summary -->
             <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    نظرة عامة على المشتركين (Clients)
                </h3>
                <p class="text-gray-400 text-sm mb-4">هؤلاء هم العملاء النهائيين المشتركين لدى هذا المزود.</p>
                
                @php
                    // We assume Client model has tenant_id as verified.
                    // We can access via relationship if defined in Tenant model, or manual query.
                    // Checking Tenant model earlier showed: public function users() ... but NOT public function clients().
                    // Checking Client model showed: public function tenant().
                    // So we can query Client::where('tenant_id', $tenant->id)->count();
                    $clientCount = \App\Models\Client::where('tenant_id', $tenant->id)->count();
                    $activeClients = \App\Models\Client::where('tenant_id', $tenant->id)->where('status', 'active')->count();
                @endphp
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                        <div class="text-2xl font-bold text-white">{{ $clientCount }}</div>
                        <div class="text-sm text-gray-400">إجمالي المشتركين</div>
                    </div>
                    <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600">
                         <div class="text-2xl font-bold text-green-400">{{ $activeClients }}</div>
                        <div class="text-sm text-gray-400">مشترك نشط</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar / Stats -->
        <div class="space-y-6">
            <!-- Subscription Info (If applicable) -->
             <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4">بيانات الاشتراك</h3>
                <div class="space-y-4">
                     <!-- Assuming first user holds subscription data -->
                    @php
                         $owner = $tenant->users()->whereHas('roles', function($q) {
                             $q->where('name', 'owner');
                         })->first() ?? $tenant->users()->first();
                    @endphp
                    @if($owner)
                        <div>
                            <div class="text-sm text-gray-400 mb-1">الباقة الحالية</div>
                            <div class="text-white font-medium">{{ $owner->plan_name ?? 'Basic' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400 mb-1">حالة الاشتراك</div>
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $owner->subscription_status ?? 'N/A' }}
                             </span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400 mb-1">تاريخ الانتهاء</div>
                            <div class="text-white font-medium">{{ $owner->subscription_ends_at ? $owner->subscription_ends_at->format('Y-m-d') : 'غير محدد' }}</div>
                        </div>
                    @else
                        <div class="text-gray-500 text-sm">-- غير متوفر --</div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
             <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h3 class="text-lg font-bold text-white mb-4">إجراءات سريعة</h3>
                <div class="space-y-2">
                    <button disabled class="w-full py-2 px-4 bg-gray-700 text-gray-400 rounded-lg cursor-not-allowed text-sm">تعديل بيانات الشركة (قريباً)</button>
                    <button disabled class="w-full py-2 px-4 bg-gray-700 text-gray-400 rounded-lg cursor-not-allowed text-sm">إرسال تنبيه (قريباً)</button>
                </div>
            </div>

        </div>
    </div>
@endsection
