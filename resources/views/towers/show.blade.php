@extends('layouts.admin')

@section('title', 'تفاصيل البرج | Infrastructure Node Detail')

@section('content')
<div class="space-y-12" x-data="{ activeTab: 'overview' }">
    <!-- Radiant Hub Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-10 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Distribution Hub Profile</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">{{ $tower->name }}</h2>
            <div class="flex items-center gap-4 mt-3">
                <span class="px-4 py-1.5 rounded-full bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest italic flex items-center gap-2 shadow-glow-purple">
                    <span class="material-symbols-outlined text-xs text-neon-cyan">location_on</span>
                    {{ $tower->location ?? 'Global Registry' }}
                </span>
                <span class="px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-400 text-[9px] font-black uppercase tracking-widest italic flex items-center gap-2">
                    <span class="material-symbols-outlined text-xs">database</span>
                    Structural ID: {{ $tower->id }}
                </span>
            </div>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('admin.towers.edit', $tower->id) }}" class="px-8 py-3.5 bg-white border border-slate-200 text-slate-600 font-black rounded-2xl text-[10px] uppercase tracking-widest transition-all hover:border-primary hover:text-primary active:scale-95 italic">
                تعديل البيانات
            </a>
            <button class="px-8 py-3.5 bg-primary text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all italic flex items-center gap-3">
                <span class="material-symbols-outlined text-sm">print</span>
                Report Export
            </button>
        </div>
    </div>

    <!-- Radiant Navigation Tabs -->
    <div class="flex items-center gap-2 p-1.5 bg-slate-50 border border-slate-200 rounded-[1.8rem] w-fit shadow-inner">
        <button @click="activeTab = 'overview'" 
                :class="activeTab === 'overview' ? 'bg-slate-900 text-white shadow-glow-purple' : 'text-slate-500 hover:text-primary'"
                class="px-8 py-3 rounded-[1.4rem] text-[10px] font-black uppercase tracking-widest transition-all italic">
            Overview Matrix
        </button>
        <button @click="activeTab = 'assets'" 
                :class="activeTab === 'assets' ? 'bg-slate-900 text-white shadow-glow-purple' : 'text-slate-500 hover:text-primary'"
                class="px-8 py-3 rounded-[1.4rem] text-[10px] font-black uppercase tracking-widest transition-all italic">
            Connected Assets
        </button>
        <button @click="activeTab = 'financials'" 
                :class="activeTab === 'financials' ? 'bg-slate-900 text-white shadow-glow-purple' : 'text-slate-500 hover:text-primary'"
                class="px-8 py-3 rounded-[1.4rem] text-[10px] font-black uppercase tracking-widest transition-all italic">
            Resource Ledger
        </button>
    </div>

    <!-- Tab Content Matrix -->
    <div class="min-h-[400px] animate-in fade-in duration-500">
        
        <!-- Overview Intelligence -->
        <div x-show="activeTab === 'overview'" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card p-10 rounded-3xl relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-primary/5 rounded-full blur-3xl group-hover:scale-150 transition-transform"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 font-headline">Operational Score</p>
                    <div class="flex items-end gap-3 font-headline">
                        <span class="text-5xl font-black text-primary italic">98.4</span>
                        <span class="text-xl font-black text-slate-300 mb-1.5 italic">%</span>
                    </div>
                </div>
                <div class="glass-card p-10 rounded-3xl relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-secondary/5 rounded-full blur-3xl group-hover:scale-150 transition-transform"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 font-headline">Active Handshakes</p>
                    <div class="flex items-end gap-3 font-headline">
                        <span class="text-5xl font-black text-secondary italic">242</span>
                        <span class="text-[10px] font-black text-slate-300 mb-1.5 italic uppercase tracking-widest">Linked Assets</span>
                    </div>
                </div>
                <div class="glass-card p-10 rounded-3xl relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-primary/5 rounded-full blur-3xl group-hover:scale-150 transition-transform"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 font-headline">Revenue Velocity</p>
                    <div class="flex items-end gap-3 font-headline">
                        <span class="text-5xl font-black text-primary italic">$4.2k</span>
                        <span class="text-[10px] font-black text-slate-300 mb-1.5 italic uppercase tracking-widest">Monthly Yield</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="glass-panel p-10 rounded-[2.5rem] !bg-white/40 border-white/40">
                    <h4 class="text-sm font-black text-primary uppercase tracking-widest mb-8 italic flex items-center gap-3">
                        <span class="material-symbols-outlined text-neon-cyan">description</span>
                        Structural Identity
                    </h4>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center py-4 border-b border-slate-100 group">
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-headline">Management Status</span>
                            <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase italic">Node Verified</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-b border-slate-100">
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-headline">Geospatial Origin</span>
                            <span class="text-[11px] font-black text-slate-900 uppercase italic">{{ $tower->location ?? 'Unmapped' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-4">
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-headline">Registry Date</span>
                            <span class="text-[11px] font-black text-slate-900 italic">{{ $tower->created_at->format('Y . m . d') }}</span>
                        </div>
                    </div>
                </div>
                <div class="glass-panel p-10 rounded-[2.5rem] !bg-slate-900 text-white border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-accent-gradient opacity-10 blur-3xl -mr-32 -mt-32"></div>
                    <h4 class="text-sm font-black text-neon-cyan uppercase tracking-widest mb-8 italic flex items-center gap-3 relative z-10">
                        <span class="material-symbols-outlined">satellite</span>
                        Telemetry Summary
                    </h4>
                    <div class="space-y-8 relative z-10">
                        <div class="space-y-2">
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-slate-400">
                                <span>Spectral Efficiency</span>
                                <span class="text-white">82%</span>
                            </div>
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-accent-flow" style="width: 82%"></div>
                            </div>
                        </div>
                        <p class="text-[11px] font-bold text-slate-400 leading-relaxed uppercase tracking-tight italic">Current asset distribution shows high link reliability within the 5GHz spectral density zone. No structural anomalies detected in the last 24-hour cycle.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Linked Assets Matrix -->
        <div x-show="activeTab === 'assets'" class="glass-card rounded-3xl overflow-hidden !bg-white/60">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                        <th class="px-10 py-6">Hardware Profile</th>
                        <th class="px-10 py-6">MAC Identity</th>
                        <th class="px-10 py-6">Linked Assets</th>
                        <th class="px-10 py-6 text-center">Power State</th>
                        <th class="px-10 py-6 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20 text-sm">
                    @forelse($tower->devices as $device)
                        <tr class="group hover:bg-white/40 transition-all duration-300">
                            <td class="px-10 py-7">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-neon-cyan flex items-center justify-center font-black group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined">router</span>
                                    </div>
                                    <div>
                                        <p class="text-base font-black text-slate-900 italic uppercase leading-none">{{ $device->name }}</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2 italic">{{ $device->model }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 text-[11px] font-black font-manrope text-slate-600 tracking-tighter">{{ $device->mac }}</td>
                            <td class="px-10 py-7 text-[11px] font-black text-primary italic uppercase tracking-widest">
                                {{ $device->clients_count ?? 0 }} Registered
                            </td>
                            <td class="px-10 py-7">
                                <div class="flex justify-center">
                                    <span class="px-5 py-1.5 rounded-full text-[9px] font-black bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase italic flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Operational
                                    </span>
                                </div>
                            </td>
                            <td class="px-10 py-7 text-left">
                                <button class="p-3 bg-white border border-slate-100 rounded-xl text-primary hover:text-white hover:bg-primary transition-all">
                                    <span class="material-symbols-outlined text-lg">settings_suggest</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center opacity-30 italic font-black uppercase text-[11px] tracking-widest">No structural assets detected in current registry</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Resource Ledger -->
        <div x-show="activeTab === 'financials'" class="glass-card p-10 rounded-3xl !bg-white/60">
            <div class="flex flex-col items-center justify-center py-20 opacity-30 italic font-black uppercase text-[13px] tracking-[0.3em]">
                <span class="material-symbols-outlined text-6xl mb-6 font-light">account_balance</span>
                Resource ledger initialization required for this node
            </div>
        </div>
    </div>
</div>
@endsection
