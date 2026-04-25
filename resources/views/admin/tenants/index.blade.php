@extends('layouts.admin')

@section('title', 'إدارة الشركاء')

@section('content')
<div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700" dir="rtl">
    
    <!-- Strategic Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="w-12 h-1 bg-admin rounded-full"></span>
                <p class="text-[10px] font-black text-admin uppercase tracking-[0.3em] font-inter">Global Tenant Governance</p>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">سجل <span class="text-admin">الشركاء</span> والقطاعات</h1>
            <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">حوكمة مقدمي الخدمة والمستأجرين النشطين في المنظومة</p>
        </div>
        
        <a href="{{ route('admin.tenants.create') }}" class="btn-radiant btn-admin px-8 py-4 text-xs font-black uppercase tracking-[0.2em] flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-circle"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/></svg>
            إضافة شريك سيادي
        </a>
    </div>

    <!-- Governance Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Total Registration -->
        <div class="glass-card p-8 bg-white/40 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-admin/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-admin/10 rounded-2xl flex items-center justify-center text-admin shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building-2"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">إجمالي المسجلين</p>
            <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter font-inter">{{ $tenants->total() }}</h3>
        </div>

        <!-- Live Pulse Nodes -->
        <div class="glass-card p-8 bg-white/40 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <div class="px-2 py-1 rounded-md bg-emerald-500 text-white font-black text-[9px] uppercase tracking-widest animate-pulse">Live</div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">النشطين حالياً</p>
            <h3 class="text-4xl font-black text-emerald-600 italic tracking-tighter font-inter">{{ $tenants->where('status', 'active')->count() }}</h3>
        </div>

        <!-- Suspended Zones -->
        <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-rose-500/20">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-600 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-alert"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">قيد التعليق</p>
            <h3 class="text-4xl font-black text-rose-600 italic tracking-tighter font-inter">{{ $tenants->where('status', '!=', 'active')->count() }}</h3>
        </div>
    </div>

    <!-- Data Ledger Matrix -->
    <div class="glass-card overflow-hidden bg-white/40 border-white/60">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                        <th class="px-10 py-8">المؤسسة / النطاق السيادي</th>
                        <th class="px-8 py-8">إدارة الحساب الإستراتيجي</th>
                        <th class="px-8 py-8 text-center">وضعية النبض</th>
                        <th class="px-8 py-8 text-center">أفق الانضمام</th>
                        <th class="px-10 py-8"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @foreach($tenants as $tenant)
                        <tr class="group hover:bg-white/60 transition-all duration-500">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5 justify-end">
                                    <div class="text-right">
                                        <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase font-inter">{{ $tenant->name }}</h4>
                                        <div class="flex items-center gap-2 mt-1 opacity-40 justify-end">
                                            <a href="http://{{ $tenant->domain }}" target="_blank" class="text-[9px] font-black uppercase tracking-widest hover:text-admin transition-colors">{{ $tenant->domain }}</a>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                        </div>
                                    </div>
                                    <div class="w-14 h-14 rounded-2xl bg-slate-900 text-admin flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shrink-0 border border-white/10">
                                        <span class="text-xl font-black uppercase font-inter">{{ substr($tenant->name, 0, 1) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                @php
                                    $owner = $tenant->users()->whereHas('roles', function($q) {
                                        $q->where('name', 'owner');
                                    })->first() ?? $tenant->users()->first();
                                @endphp
                                @if($owner)
                                    <div class="flex items-center gap-4 justify-end">
                                        <div class="text-right">
                                            <p class="text-[13px] font-black text-slate-700 leading-none mb-1">{{ $owner->name }}</p>
                                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic font-inter opacity-60">{{ $owner->email }}</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-cog"><circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m21.7 16.4-.9-.3"/><path d="m15.2 13.9-.9-.3"/><path d="m16.6 18.7.3-.9"/><path d="m19.4 11.3.3-.9"/><path d="m14.9 17.6.3.9"/><path d="m21.1 12.4.3.9"/><path d="m19.7 19.1-.3-.9"/><path d="m16.3 10.9-.3-.9"/></svg>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[10px] font-black text-slate-300 uppercase italic">Null Identity</span>
                                @endif
                            </td>
                            <td class="px-8 py-8 text-center">
                                @if($tenant->status == 'active')
                                    <div class="inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/10 text-emerald-600 shadow-sm group-hover:translate-y-[-2px] transition-all">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                        </span>
                                        نشط (Live)
                                    </div>
                                @else
                                    <div class="inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-rose-500/20 bg-rose-500/10 text-rose-500 opacity-60">
                                        <span class="w-2.5 h-2.5 bg-rose-500 rounded-full"></span>
                                        معلق (Paused)
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="px-5 py-2 bg-slate-900 text-white rounded-xl text-[11px] font-black font-inter shadow-lg group-hover:bg-admin transition-colors italic whitespace-nowrap">
                                        {{ $tenant->created_at->format('Y-m-d') }}
                                    </div>
                                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-2 font-inter">{{ $tenant->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                    <form action="{{ route('admin.tenants.impersonate', $tenant->id) }}" method="POST" onsubmit="return confirm('الدخول بصلاحية المالك لهذا الشريك؟')">
                                        @csrf
                                        <button type="submit" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-admin hover:scale-110 active:scale-90 transition-all" title="محاكاة المالك">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.tenants.toggle', $tenant->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-500 hover:scale-110 active:scale-90 transition-all">
                                            @if($tenant->status == 'active')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pause-circle"><circle cx="12" cy="12" r="10"/><line x1="10" x2="10" y1="15" y2="9"/><line x1="14" x2="14" y1="15" y2="9"/></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play-circle"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-admin hover:scale-110 active:scale-90 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Strategic Pagination -->
        <div class="px-10 py-8 bg-slate-900/5 border-t border-white/40 flex justify-between items-center">
            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-inter opacity-40">Governance Matrix v8.4</div>
            <div class="flex gap-2">
                {{ $tenants->links('vendor.pagination.simple-monolith') }}
            </div>
        </div>
    </div>
</div>

<style>
.glass-card {
    @apply border border-white/40 shadow-glass rounded-[2.5rem] transition-all duration-500;
}
.glass-card:hover {
    @apply border-white/60 shadow-radiant -translate-y-1;
}
</style>
@endsection
