@extends('layouts.admin')

@section('title', 'مصفوفة أجهزة التوزيع | Device Distribution Matrix')

@section('content')
<div class="space-y-12">
    <!-- Radiant Hub Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-10 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Asset Distribution Matrix</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">مصفوفة أجهزة التوزيع</h2>
            <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] font-headline opacity-80">Connected Hardware Fleet & Peripheral Node Registry</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('routers.create') }}" class="group relative px-8 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] transition-all hover:scale-[1.05] hover:shadow-glow-purple active:scale-[0.98] flex items-center gap-4 overflow-hidden">
                <div class="absolute inset-0 bg-accent-flow opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <span class="material-symbols-outlined relative z-10 group-hover:rotate-90 transition-transform duration-500">add_moderator</span>
                <span class="relative z-10 italic">تسجيل جهاز جديد</span>
            </a>
        </div>
    </div>

    @if($master_router)
    <!-- Radiant Core Governance Section -->
    <div class="space-y-6">
        <h3 class="text-sm font-black text-primary uppercase tracking-[0.3em] italic flex items-center gap-4 px-2">
            <span class="w-8 h-px bg-primary/20"></span>
            Core Governance Node
        </h3>
        <div class="glass-card p-10 rounded-3xl border-r-8 border-primary group relative overflow-hidden transition-all duration-500 hover:-translate-y-2 !bg-white/80">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-primary/5 rounded-full blur-3xl transition-transform group-hover:scale-125"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 relative z-10">
                <div class="flex items-center gap-8">
                    <div class="w-20 h-20 rounded-2xl bg-slate-900 flex items-center justify-center shadow-2xl shadow-primary/30 group-hover:scale-110 transition-transform duration-500">
                        <span class="material-symbols-outlined text-4xl text-neon-cyan">hub</span>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-slate-900 italic tracking-tighter uppercase leading-none">{{ $master_router->name }}</h4>
                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-[10px] font-manrope font-black text-slate-400 uppercase tracking-widest">{{ $master_router->host }}</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span class="text-[10px] font-black text-primary uppercase italic">{{ $master_router->tower->name ?? 'Structural Root' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-10">
                    <div class="text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Protocol Logic</p>
                        <p class="text-sm font-black text-slate-700 italic uppercase">{{ $master_router->type }}</p>
                    </div>
                    <div class="h-12 w-px bg-slate-100"></div>
                    <div class="text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Handshake</p>
                        <div class="flex items-center gap-2 px-4 py-1.5 bg-emerald-500/10 text-emerald-600 rounded-full border border-emerald-500/20 text-[9px] font-black uppercase italic">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Verified
                        </div>
                    </div>
                    <div class="flex gap-3 ml-4">
                        <a href="{{ route('routers.edit', $master_router) }}" class="p-3.5 bg-white border border-slate-100 rounded-2xl text-slate-400 hover:text-primary transition-all shadow-sm">
                            <span class="material-symbols-outlined">settings_suggest</span>
                        </a>
                        <a href="{{ route('router-management.script', $master_router) }}" class="px-8 py-3.5 bg-primary text-white text-[10px] font-black rounded-2xl uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all italic">
                            Terminal Script
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Radiant Peripheral Fleet Registry -->
    <div class="space-y-8">
        <h3 class="text-sm font-black text-slate-400 uppercase tracking-[0.3em] italic flex items-center gap-4 px-2">
            <span class="w-8 h-px bg-slate-200"></span>
            Peripheral Device Matrix
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($routers->where('is_master', false) as $router)
                <div class="glass-card p-8 rounded-3xl group transition-all duration-300 hover:bg-white/80 hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all duration-500">
                            <span class="material-symbols-outlined text-xl">router</span>
                        </div>
                        <div class="flex flex-col items-end gap-1.5">
                            <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[8px] font-black rounded-full border border-slate-100 uppercase italic">
                                Node ID: {{ $router->id }}
                            </span>
                            @if($router->tower)
                                <span class="text-[8px] font-black text-primary uppercase tracking-widest italic">{{ $router->tower->name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-sm font-black text-slate-900 italic uppercase leading-none">{{ $router->name }}</h4>
                        <p class="text-[10px] font-manrope font-black text-slate-400 uppercase tracking-widest mt-2">{{ $router->host }}</p>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                            <span class="text-[8px] font-black text-secondary uppercase tracking-widest italic tracking-tighter">Connected</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('routers.edit', $router) }}" class="p-2 text-slate-300 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">edit_note</span>
                            </a>
                            <form action="{{ route('routers.destroy', $router) }}" method="POST" onsubmit="return confirm('Confirm Device Decommission?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-300 hover:text-error transition-colors">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 flex flex-col items-center justify-center gap-6 glass-card rounded-3xl border-dashed opacity-30">
                    <span class="material-symbols-outlined text-5xl font-light">account_tree</span>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] italic text-center">No Peripheral Devices Registered in Matrix</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
