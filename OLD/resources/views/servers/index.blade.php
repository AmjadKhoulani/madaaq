@extends('layouts.admin')

@section('title', 'أسطول التحكم المركزي | Infrastructure Core Fleet')

@section('content')
<div class="space-y-12">
    <!-- Radiant Infrastructure Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-10 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Core Node Governance</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">أسطول التحكم المركزي</h2>
            <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] font-headline opacity-80">Global Management Registry for MikroTik Core Infrastructure</p>
        </div>
        <a href="{{ route('servers.create') }}" class="group relative px-8 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] transition-all hover:scale-[1.05] hover:shadow-glow-purple active:scale-[0.98] flex items-center gap-4 overflow-hidden">
            <div class="absolute inset-0 bg-accent-flow opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <span class="material-symbols-outlined relative z-10 group-hover:rotate-90 transition-transform duration-500">dns</span>
            <span class="relative z-10 italic">إضافة سيرفر جديد</span>
        </a>
    </div>

    <!-- Node Command Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($servers as $server)
            <div class="glass-card p-10 rounded-3xl group relative overflow-hidden transition-all duration-500 hover:-translate-y-2">
                <!-- Status Atmosphere -->
                <div class="absolute top-0 left-0 w-full h-1 bg-accent-flow opacity-20"></div>
                
                <div class="flex items-start justify-between mb-10">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-slate-900 flex items-center justify-center text-white shadow-2xl shadow-primary/20 group-hover:scale-110 transition-transform duration-500">
                            <span class="material-symbols-outlined text-3xl text-neon-cyan">memory</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 italic tracking-tight uppercase leading-none">{{ $server->name }}</h3>
                            <p class="text-[9px] font-manrope font-black text-slate-400 uppercase tracking-[0.2em] mt-2 italic">{{ $server->ip }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-2">
                        <div class="px-4 py-1.5 rounded-full text-[9px] font-black bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase tracking-widest flex items-center gap-2 italic">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Handshake Stable
                        </div>
                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest italic">v.{{ $server->version ?? 'OPERATIONAL' }}</span>
                    </div>
                </div>

                <!-- Live Spectral Metrics (Simplified) -->
                <div class="grid grid-cols-2 gap-8 py-8 border-y border-white/40 mb-8">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center px-1">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">CPU LOAD</p>
                            <span class="text-[10px] font-manrope font-black text-primary italic">24%</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-accent-flow" style="width: 24%"></div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center px-1">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">RAM USE</p>
                            <span class="text-[10px] font-manrope font-black text-primary italic">512MB</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-accent-flow" style="width: 42%"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer Command Matrix -->
                <div class="flex items-center justify-between">
                    <div class="flex gap-4">
                        <a href="{{ route('servers.show', $server->id) }}" class="px-6 py-2.5 bg-primary text-white text-[9px] font-black rounded-xl uppercase tracking-[0.2em] shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all italic">
                            Open Intelligence
                        </a>
                        @if($server->coordinates)
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400">
                                <span class="material-symbols-outlined text-sm">location_searching</span>
                            </div>
                        @endif
                    </div>
                    
                    <form action="{{ route('servers.destroy', $server->id) }}" method="POST" onsubmit="return confirm('Confirms Local Core Decommissioning?')" class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-slate-300 hover:text-error transition-colors">
                            <span class="material-symbols-outlined text-lg">delete_forever</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 flex flex-col items-center justify-center gap-6 glass-card rounded-3xl opacity-40">
                <span class="material-symbols-outlined text-6xl font-light">dns_off</span>
                <p class="text-[11px] font-black uppercase tracking-[0.3em] italic">Void Fleet: No Operational Nodes Registered</p>
                <a href="{{ route('servers.create') }}" class="text-[10px] font-bold text-primary underline uppercase tracking-widest">Connect First Core Asset</a>
            </div>
        @endforelse
    </div>

    <!-- Radiant Provisioning Manual -->
    <div class="p-10 glass-panel rounded-[2rem] flex flex-col md:flex-row items-center gap-10">
        <div class="w-24 h-24 rounded-[2rem] bg-accent-gradient p-0.5 flex-shrink-0">
            <div class="w-full h-full bg-slate-900 rounded-[1.8rem] flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-3xl font-light">cloud_sync</span>
            </div>
        </div>
        <div class="space-y-3">
            <h4 class="text-lg font-black text-primary italic uppercase tracking-widest leading-none">Automated Node Provisioning Layer</h4>
            <p class="text-xs font-bold text-slate-500 leading-relaxed uppercase tracking-tight italic">MadaaQ utilizes a high-intensity synchronization layer to communicate with MikroTik RouterOS API endpoints. Ensure that the 'REST API' and 'API-SSL' services are operational before attempting asset registration.</p>
        </div>
        <div class="flex-shrink-0">
            <button class="px-8 py-3 bg-white border border-slate-200 rounded-xl font-black text-[10px] uppercase tracking-widest text-slate-600 hover:bg-primary hover:text-white transition-all italic">
                Registry Docs
            </button>
        </div>
    </div>
</div>
@endsection
