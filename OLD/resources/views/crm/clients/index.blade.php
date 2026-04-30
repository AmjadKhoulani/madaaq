@extends('layouts.admin')

@section('title', 'قاعدة بيانات المشتركين | Subscriber Registry')

@section('content')
<div class="space-y-12">
    <!-- Radiant Header Command -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-10 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Administrative Shield</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">قاعدة بيانات المشتركين</h2>
            <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] font-headline opacity-80">Global Client Identity Registry & Access Control System</p>
        </div>
        <a href="{{ route('crm.clients.create') }}" class="group relative px-8 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] transition-all hover:scale-[1.05] hover:shadow-glow-purple active:scale-[0.98] flex items-center gap-4 overflow-hidden">
            <div class="absolute inset-0 bg-accent-flow opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <span class="material-symbols-outlined relative z-10 group-hover:rotate-90 transition-transform duration-500">person_add</span>
            <span class="relative z-10 italic">إضافة مشترك جديد</span>
        </a>
    </div>

    <!-- Radiant High-Intensity Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-8 rounded-2xl group overflow-hidden relative">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline">إجمالي السجلات الرقمية</p>
            <h4 class="text-4xl metric-value-radiant group-hover:scale-110 transition-transform origin-right">{{ $clients->total() }}</h4>
        </div>
        <div class="glass-card p-8 rounded-2xl border-r-4 border-emerald-500/20 group overflow-hidden relative">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[9px] font-black text-emerald-600/60 uppercase tracking-widest mb-3 font-headline">الحالات التشغيلية النشطة</p>
            <h4 class="text-4xl metric-value-radiant text-emerald-500 group-hover:scale-110 transition-transform origin-right">{{ $clients->where('status', 'active')->count() }}</h4>
        </div>
        <div class="glass-card p-8 rounded-2xl group overflow-hidden relative">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline">بروتوكول PPPoE (Broadband)</p>
            <h4 class="text-4xl metric-value-radiant group-hover:scale-110 transition-transform origin-right">{{ $clients->where('type', 'pppoe')->count() }}</h4>
        </div>
        <div class="glass-card p-8 rounded-2xl group overflow-hidden relative">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline">بروتوكول Hotspot (Vouchers)</p>
            <h4 class="text-4xl metric-value-radiant group-hover:scale-110 transition-transform origin-right">{{ $clients->where('type', 'hotspot')->count() }}</h4>
        </div>
    </div>

    <!-- Radiant Control Matrix -->
    <div class="glass-panel p-8 rounded-3xl !bg-white/40">
        <form method="GET" class="flex flex-col lg:flex-row items-center gap-6">
            <div class="flex-1 w-full relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث في سجلات المشتركين..." 
                       class="w-full pr-14 pl-6 py-4 bg-white/80 border border-slate-200/50 rounded-2xl focus:ring-8 focus:ring-primary/5 focus:border-primary outline-none transition-all font-black text-xs uppercase tracking-widest text-slate-700 shadow-inner">
                <span class="material-symbols-outlined absolute right-5 top-3.5 text-slate-300 group-focus-within:text-primary transition-colors duration-300">search</span>
            </div>
            
            <div class="flex gap-4 w-full lg:w-auto">
                <select name="status" class="w-full lg:w-48 py-4 px-6 bg-white/80 border border-slate-200/50 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-600 outline-none focus:border-primary transition-all italic">
                    <option value="">كل حالات الوصول</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط (Active)</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطل (Inactive)</option>
                </select>
                
                <select name="type" class="w-full lg:w-48 py-4 px-6 bg-white/80 border border-slate-200/50 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-600 outline-none focus:border-primary transition-all italic">
                    <option value="">كل البروتوكولات</option>
                    <option value="pppoe" {{ request('type') == 'pppoe' ? 'selected' : '' }}>PPPoE Broadband</option>
                    <option value="hotspot" {{ request('type') == 'hotspot' ? 'selected' : '' }}>Hotspot Auth</option>
                </select>
            </div>

            <button type="submit" class="w-full lg:w-auto px-10 py-4 bg-primary text-white font-black rounded-2xl text-[10px] uppercase tracking-[0.3em] transition-all hover:bg-slate-900 shadow-xl shadow-primary/10 italic">
                تنفيذ الفلترة
            </button>
        </form>
    </div>

    <!-- Radiant Data Registry -->
    <div class="glass-card rounded-3xl overflow-hidden !bg-white/60">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                        <th class="px-10 py-6">ID & Registry Profile</th>
                        <th class="px-10 py-6">Protocol Layer</th>
                        <th class="px-10 py-6">Subscription Plan</th>
                        <th class="px-10 py-6 text-center">Status Index</th>
                        <th class="px-10 py-6">Expiration Terminal</th>
                        <th class="px-10 py-6 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($clients as $client)
                        <tr class="group hover:bg-white/40 transition-all duration-300">
                            <td class="px-10 py-7">
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center font-headline font-black text-primary shadow-sm group-hover:scale-110 group-hover:shadow-glow-cyan transition-all duration-500">
                                        {{ strtoupper(substr($client->username, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-base font-black text-slate-900 italic tracking-tight uppercase leading-none">{{ $client->username }}</span>
                                            @if(auth()->user()->tenant->is_subdomain_enabled && empty($client->password))
                                                <span class="material-symbols-outlined text-sm text-error animate-pulse-soft" title="Requires Secret Key Allocation">error</span>
                                            @endif
                                        </div>
                                        @if($client->name)
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-2 italic">{{ $client->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7">
                                @if($client->type === 'pppoe')
                                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/5 text-primary text-[9px] font-black rounded-full border border-primary/10 uppercase italic">
                                        <span class="w-1 h-1 rounded-full bg-primary"></span>
                                        Broadband (PPPoE)
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-100 text-slate-500 text-[9px] font-black rounded-full border border-slate-200 uppercase italic">
                                        <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                                        Hotspot Cluster
                                    </div>
                                @endif
                            </td>
                            <td class="px-10 py-7">
                                <div class="text-[11px] font-black text-slate-700 uppercase italic tracking-widest">
                                    {{ $client->package->name ?? 'Custom Allocation' }}
                                </div>
                            </td>
                            <td class="px-10 py-7">
                                <div class="flex justify-center">
                                    @if($client->status === 'active')
                                        <div class="px-5 py-1.5 rounded-full text-[9px] font-black bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase tracking-widest flex items-center gap-2 italic">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Active Link
                                        </div>
                                    @else
                                        <div class="px-5 py-1.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-widest flex items-center gap-2 italic">
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                            Suspended
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-10 py-7">
                                <span class="text-[11px] font-black font-manrope text-slate-500 tracking-tighter italic">
                                    {{ $client->expires_at ? $client->expires_at->format('Y . m . d') : 'PERMANENT ARCHIVE' }}
                                </span>
                            </td>
                            <td class="px-10 py-7 text-left">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('crm.clients.show', $client) }}" class="p-3 bg-white border border-slate-100 rounded-xl text-primary hover:text-white hover:bg-primary hover:shadow-glow-cyan transition-all group/btn" title="Inspect Terminal">
                                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center gap-6 opacity-30">
                                    <span class="material-symbols-outlined text-6xl font-light">person_search</span>
                                    <p class="text-[11px] font-black uppercase tracking-[0.3em] italic">Void Registry: No Matching Profiles Detected</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Radiant Pagination Flow -->
        @if($clients->hasPages())
            <div class="px-10 py-8 bg-white/20 border-t border-white/40">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
