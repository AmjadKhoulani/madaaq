@extends('layouts.admin')

@section('title', 'إدارة المشتركين والعملاء')

@section('content')
<div class="space-y-8">
    <!-- Header Command -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight">قاعدة بيانات المشتركين</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[11px] font-headline">CRM System Architecture</p>
        </div>
        <a href="{{ route('crm.clients.create') }}" class="px-8 py-3.5 bg-primary text-white font-bold rounded-lg text-sm transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center gap-3 shadow-lg shadow-primary/10">
            <span class="material-symbols-outlined">person_add</span>
            إضافة مشترك جديد
        </a>
    </div>

    <!-- Quick Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 font-headline">إجمالي السجلات</p>
            <h4 class="text-2xl font-manrope font-black text-primary">{{ $clients->total() }}</h4>
        </div>
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 font-headline">الحالة: نشط</p>
            <h4 class="text-2xl font-manrope font-black text-secondary">{{ $clients->where('status', 'active')->count() }}</h4>
        </div>
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 font-headline">نوع: برودباند</p>
            <h4 class="text-2xl font-manrope font-black text-primary">{{ $clients->where('type', 'pppoe')->count() }}</h4>
        </div>
        <div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant/10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 font-headline">نوع: هوت سبوت</p>
            <h4 class="text-2xl font-manrope font-black text-primary">{{ $clients->where('type', 'hotspot')->count() }}</h4>
        </div>
    </div>

    <!-- Filter Monolith -->
    <div class="bg-surface-container-low/50 p-6 rounded-lg border border-outline-variant/10">
        <form method="GET" class="flex flex-col md:flex-row items-center gap-4">
            <div class="flex-1 w-full relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم، النطاق، أو الهاتف..." 
                       class="w-full pr-12 pl-4 py-3 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-bold text-sm">
                <span class="material-symbols-outlined absolute right-4 top-3 text-slate-400 text-xl">search</span>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                <select name="status" class="w-full md:w-40 py-3 px-4 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold text-slate-600 outline-none">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                
                <select name="type" class="w-full md:w-40 py-3 px-4 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold text-slate-600 outline-none">
                    <option value="">كل الأنواع</option>
                    <option value="pppoe" {{ request('type') == 'pppoe' ? 'selected' : '' }}>PPPoE</option>
                    <option value="hotspot" {{ request('type') == 'hotspot' ? 'selected' : '' }}>Hotspot</option>
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto px-8 py-3 bg-primary text-white font-black rounded-lg text-sm transition-all shadow-md">
                تصفية النتائج
            </button>
        </form>
    </div>

    <!-- Data Table Monolith -->
    <div class="bg-surface-container-lowest rounded-lg overflow-hidden shadow-sm border border-outline-variant/10">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-surface-container-low/50 text-slate-500 text-[11px] font-black uppercase tracking-widest border-b border-outline-variant/10">
                        <th class="px-8 py-5">المشترك / الهوية</th>
                        <th class="px-8 py-5">التكنولوجيا</th>
                        <th class="px-8 py-5">الباقة المخصصة</th>
                        <th class="px-8 py-5">الحالة</th>
                        <th class="px-8 py-5">نهاية الاشتراك</th>
                        <th class="px-8 py-5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($clients as $client)
                        <tr class="hover:bg-surface-container-low/20 transition-all group {{ $loop->even ? 'bg-surface-container-low/10' : '' }}">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-lg bg-surface-container flex items-center justify-center font-manrope font-black text-primary border border-outline-variant/10 group-hover:bg-white group-hover:scale-105 transition-all">
                                        {{ strtoupper(substr($client->username, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[15px] font-bold text-primary leading-tight">{{ $client->username }}</span>
                                            @if(auth()->user()->tenant->is_subdomain_enabled && empty($client->password))
                                                <span class="material-symbols-outlined text-sm text-error animate-pulse" title="يجب تعيين كلمة مرور">warning</span>
                                            @endif
                                        </div>
                                        @if($client->name)
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $client->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @if($client->type === 'pppoe')
                                    <span class="px-3 py-1 bg-primary/5 text-primary text-[10px] font-black rounded uppercase tracking-widest border border-primary/5">Broadband</span>
                                @else
                                    <span class="px-3 py-1 bg-surface-container-highest/50 text-slate-600 text-[10px] font-black rounded uppercase tracking-widest border border-outline-variant/10">Hotspot</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-sm font-bold text-slate-700">
                                {{ $client->package->name ?? 'خطة مخصصة' }}
                            </td>
                            <td class="px-8 py-5">
                                @if($client->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded text-[10px] font-black bg-secondary-container text-on-secondary-container uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                                        نشط
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded text-[10px] font-black bg-slate-100 text-slate-400 uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                        معطل
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-black font-manrope text-slate-600 tracking-tight">
                                    {{ $client->expires_at ? $client->expires_at->format('Y/m/d') : 'دائم' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('crm.clients.show', $client) }}" class="p-2.5 rounded-lg text-primary hover:bg-primary/10 transition-all" title="عرض الملف الكامل">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 bg-surface-container-low rounded-2xl flex items-center justify-center text-slate-300">
                                        <span class="material-symbols-outlined text-4xl">person_off</span>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">لا يوجد مشتركين مطابقين للبحث حالياً</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Monolith -->
        @if($clients->hasPages())
            <div class="px-8 py-6 bg-surface-container-low/30 border-t border-outline-variant/10">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
