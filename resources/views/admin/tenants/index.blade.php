@extends('layouts.admin')

@section('title', 'إدارة الشركاء')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight">سجل الشركاء والقطاعات</h2>
            <p class="text-slate-500 font-medium mt-1">الرقابة الكاملة على مقدمي الخدمة والمستأجرين النشطين</p>
        </div>
        <a href="{{ route('admin.tenants.create') }}" class="px-6 py-3 bg-primary text-white font-bold rounded-lg text-sm transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center gap-2 shadow-lg shadow-primary/10">
            <span class="material-symbols-outlined text-sm">add_business</span>
            إضافة شريك جديد
        </a>
    </div>

    <!-- Stats Bar (Architect Style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center text-primary shadow-sm">
                <span class="material-symbols-outlined">corporate_fare</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">إجمالي المسجلين</p>
                <p class="text-xl font-manrope font-black text-primary">{{ $tenants->total() }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center text-secondary shadow-sm">
                <span class="material-symbols-outlined">verified</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">النشطين حالياً</p>
                <p class="text-xl font-manrope font-black text-secondary">{{ $tenants->where('status', 'active')->count() }}</p>
            </div>
        </div>
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center text-error shadow-sm">
                <span class="material-symbols-outlined">do_not_disturb_on</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">قيد التعليق</p>
                <p class="text-xl font-manrope font-black text-error">{{ $tenants->where('status', '!=', 'active')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Data Table Monolith -->
    <div class="bg-surface-container-lowest rounded-lg overflow-hidden shadow-sm border border-outline-variant/10">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-surface-container-low/50 text-slate-500 text-[11px] font-black uppercase tracking-widest border-b border-outline-variant/10">
                        <th class="px-8 py-5">المؤسسة / النطاق</th>
                        <th class="px-8 py-5">إدارة الحساب</th>
                        <th class="px-8 py-5">الحالة التشغيلية</th>
                        <th class="px-8 py-5">تاريخ الانضمام</th>
                        <th class="px-8 py-5">مركز التحكم</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @foreach($tenants as $tenant)
                        <tr class="hover:bg-surface-container-low/20 transition-all group {{ $loop->even ? 'bg-surface-container-low/10' : '' }}">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-lg bg-primary-container/10 flex items-center justify-center font-bold text-primary border border-primary/10">
                                        {{ substr($tenant->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-[15px] font-bold text-primary leading-tight">{{ $tenant->name }}</div>
                                        <a href="http://{{ $tenant->domain }}" target="_blank" class="text-xs text-slate-400 font-manrope hover:text-primary transition-colors flex items-center gap-1">
                                            {{ $tenant->domain }}
                                            <span class="material-symbols-outlined text-[12px]">open_in_new</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @php
                                    $owner = $tenant->users()->whereHas('roles', function($q) {
                                        $q->where('name', 'owner');
                                    })->first() ?? $tenant->users()->first();
                                @endphp
                                @if($owner)
                                    <div class="text-sm font-bold text-slate-700">{{ $owner->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $owner->email }}</div>
                                @else
                                    <span class="text-xs text-slate-300 italic">-- غير معرف --</span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                @if($tenant->status == 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded text-[10px] font-black bg-secondary-container text-on-secondary-container uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                                        نشط
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded text-[10px] font-black bg-tertiary-container/10 text-tertiary-container uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                        معلق
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-xs font-bold text-slate-500 font-manrope">{{ $tenant->created_at->format('Y-m-d') }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $tenant->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-1">
                                    <form action="{{ route('admin.tenants.impersonate', $tenant->id) }}" method="POST" onsubmit="return confirm('الدخول بصلاحية المالك لهذا الشريك؟')">
                                        @csrf
                                        <button type="submit" class="p-2.5 rounded-lg text-primary hover:bg-primary/10 transition-all" title="محاكاة المالك">
                                            <span class="material-symbols-outlined text-lg !fill-0">login</span>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.tenants.toggle', $tenant->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2.5 rounded-lg text-slate-400 hover:text-primary hover:bg-slate-100 transition-all">
                                            <span class="material-symbols-outlined text-lg">{{ $tenant->status == 'active' ? 'pause_circle' : 'play_circle' }}</span>
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="p-2.5 rounded-lg text-slate-400 hover:text-primary hover:bg-slate-100 transition-all">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Monolith -->
        <div class="px-8 py-6 bg-surface-container-low/30 border-t border-outline-variant/10">
            {{ $tenants->links() }}
        </div>
    </div>
</div>
@endsection
