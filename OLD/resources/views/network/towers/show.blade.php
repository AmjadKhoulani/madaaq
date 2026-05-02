@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ request('tab', 'overview') }}' }">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('network.towers.index') }}" class="w-12 h-12 rounded-2xl bg-white/40 backdrop-blur-md flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-white border border-white/30 transition-all shadow-sm group">
                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="relative">
                <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
                <div class="flex items-center gap-4 mb-1">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">{{ $tower->name }}</h2>
                    @if($tower->status === 'active')
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                             Operational
                        </span>
                    @elseif($tower->status === 'maintenance')
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-600 border border-amber-500/20 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                             Maintenance
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-500/10 text-gray-600 border border-gray-500/20 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                             Offline
                        </span>
                    @endif
                </div>
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] opacity-70">
                    Infrastructure Unit: <span class="text-gray-900">{{ $tower->location ?? 'Global Node' }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('network.towers.edit', $tower) }}" class="px-6 py-3 bg-white/50 backdrop-blur-md text-gray-700 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-white/40 hover:bg-white transition-all shadow-sm flex items-center gap-3">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Modify Node
            </a>
            <div class="h-8 w-px bg-gray-900/10 mx-2"></div>
            <div class="flex -space-x-3 overflow-hidden">
                <div class="w-10 h-10 rounded-2xl bg-indigo-100 flex items-center justify-center border-2 border-white text-indigo-600 font-black text-xs">A</div>
                <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center border-2 border-white text-emerald-600 font-black text-xs">M</div>
                <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center border-2 border-white text-blue-600 font-black text-xs shadow-lg">+</div>
            </div>
        </div>
    </div>

    {{-- Quick Stats / Metric Orbs --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Equipment Orb --}}
        <div class="glass-panel rounded-[2rem] p-6 relative overflow-hidden group hover:scale-[1.02] transition-all cursor-default">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Hardware Units</p>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $tower->routers->count() }}</h3>
                    <p class="text-[9px] font-bold text-blue-600 uppercase mt-1">Routers & Access Points</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                </div>
            </div>
        </div>

        {{-- SSIDs Orb --}}
        <div class="glass-panel rounded-[2rem] p-6 relative overflow-hidden group hover:scale-[1.02] transition-all cursor-default">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Wireless SSIDs</p>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $tower->ssids->count() }}</h3>
                    <p class="text-[9px] font-bold text-purple-600 uppercase mt-1">Broadcasting Interfaces</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                </div>
            </div>
        </div>

        {{-- Clients Orb --}}
        <div class="glass-panel rounded-[2rem] p-6 relative overflow-hidden group hover:scale-[1.02] transition-all cursor-default">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Subscribers</p>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $tower->clients->count() }}</h3>
                    <p class="text-[9px] font-bold text-emerald-600 uppercase mt-1">Connected Customers</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Costs Orb --}}
        <div class="glass-panel rounded-[2rem] p-6 relative overflow-hidden group hover:scale-[1.02] transition-all cursor-default">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Operating Burn</p>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">{{ number_format($tower->monthly_fixed_costs ?? 0, 0) }}</h3>
                    <p class="text-[9px] font-bold text-amber-600 uppercase mt-1">Monthly Fixed Overhead</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-inner">
                    <span class="text-xl font-black">{{ $currency }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="glass-panel rounded-[2.5rem] overflow-hidden mb-10 border border-white/40 shadow-xl">
        <div class="border-b border-gray-900/5 bg-white/30 backdrop-blur-md">
            <nav class="flex overflow-x-auto no-scrollbar p-2 gap-2">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Overview
                </button>
                <button @click="activeTab = 'equipment'" :class="activeTab === 'equipment' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h2M4 8h16"/></svg>
                    Infrastructure
                </button>
                <button @click="activeTab = 'power'" :class="activeTab === 'power' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                   <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                   Energy
                </button>
                <button @click="activeTab = 'costs'" :class="activeTab === 'costs' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Ledger
                </button>
                <button @click="activeTab = 'connection'" :class="activeTab === 'connection' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900'" class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Uplink
                </button>
            </nav>
        </div>

        {{-- Tab Content --}}
        <div class="p-6">
            {{-- Overview Tab --}}
            <div x-show="activeTab === 'overview'" x-transition class="space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Basic Info --}}
                    <div class="bg-gray-900/5 rounded-3xl p-8 border border-white/50 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-gray-900/5 rounded-full blur-2xl"></div>
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-black text-gray-900 flex items-center gap-3 uppercase tracking-tight">
                                <span class="w-1.5 h-6 bg-gray-900 rounded-full"></span>
                                Primary Intelligence
                            </h3>
                            <a href="{{ route('network.towers.edit', [$tower, 'tab' => 'basic']) }}" class="w-8 h-8 rounded-xl bg-white/50 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Architecture</p>
                                <p class="text-sm font-black text-gray-900">
                                    @if($tower->type === 'tower') 🗼 Radio Tower
                                    @elseif($tower->type === 'building') 🏢 Structural Roof
                                    @else 📍 Support Pole
                                    @endif
                                </p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Altitude</p>
                                <p class="text-sm font-black text-gray-900">{{ $tower->height ?? '0' }}m AGL</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60 col-span-2">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Coordinates</p>
                                <p class="text-sm font-mono font-black text-indigo-600 select-all">{{ round($tower->lat, 6) }}, {{ round($tower->lng, 6) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Server Info --}}
                    <div class="bg-indigo-900 rounded-3xl p-8 relative overflow-hidden group shadow-2xl">
                        <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl group-hover:scale-125 transition-all"></div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-black text-white flex items-center gap-3 uppercase tracking-tight mb-8">
                                <span class="w-1.5 h-6 bg-indigo-400 rounded-full"></span>
                                Uplink Controller
                            </h3>
                            @if($tower->mikrotikServer)
                            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/10">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-1">Active Gateway</p>
                                        <p class="text-xl font-black text-white tracking-tight">{{ $tower->mikrotikServer->name }}</p>
                                    </div>
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 flex items-center justify-center text-indigo-300 border border-white/5">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-mono text-indigo-200 select-all">{{ $tower->mikrotikServer->ip }}</span>
                                    <a href="{{ route('servers.show', $tower->mikrotikServer) }}" class="text-[10px] font-black text-white uppercase tracking-widest bg-indigo-500 hover:bg-white hover:text-indigo-900 px-4 py-2 rounded-xl transition-all">
                                        View Logic
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="bg-rose-500/20 backdrop-blur-md rounded-2xl p-8 border border-white/10 text-center">
                                <p class="text-xs font-black text-rose-200 uppercase tracking-widest">No Controller Sync</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Power Summary --}}
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl"></div>
                    <h3 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-3 uppercase tracking-tight">
                        <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                        Energy Infrastructure
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60 text-center group/item hover:bg-white transition-all cursor-default shadow-sm">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 group-hover/item:scale-110 transition-transform">🔋</div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Batteries</p>
                            <p class="text-xl font-black text-gray-900 tracking-tight">{{ $tower->battery_count ?? '0' }}x Units</p>
                        </div>
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60 text-center group/item hover:bg-white transition-all cursor-default shadow-sm">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 group-hover/item:scale-110 transition-transform">☀️</div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Solar Intake</p>
                            <p class="text-xl font-black text-gray-900 tracking-tight">{{ $tower->has_solar ? ($tower->solar_panels_count ?? '✓') : 'Inactive' }}</p>
                        </div>
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60 text-center group/item hover:bg-white transition-all cursor-default shadow-sm">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 group-hover/item:scale-110 transition-transform">⚡</div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Generator</p>
                            <p class="text-xl font-black text-gray-900 tracking-tight">{{ $tower->has_generator ? $tower->generator_capacity : 'None' }}</p>
                        </div>
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60 text-center group/item hover:bg-white transition-all cursor-default shadow-sm">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 group-hover/item:scale-110 transition-transform">🔌</div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Inverter</p>
                            <p class="text-xl font-black text-gray-900 tracking-tight">{{ $tower->has_inverter ? $tower->inverter_capacity : 'None' }}</p>
                        </div>
                    </div>
                </div>

                @if($tower->notes)
                <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <h4 class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.2em] mb-4 flex items-center gap-3">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        Observer Logs
                    </h4>
                    <p class="text-lg font-bold leading-relaxed opacity-90 whitespace-pre-line">{{ $tower->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Equipment & SSIDs Tab --}}
            {{-- Equipment & SSIDs Tab --}}
            <div x-show="activeTab === 'equipment'" x-transition class="space-y-12">
                {{-- Wireless Devices (Sectors/Antennas) --}}
                <div>
                     <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Radio Access Units</h3>
                            <span class="bg-indigo-600 text-white px-3 py-1 rounded-xl text-[10px] font-black shadow-lg shadow-indigo-100">{{ $tower->wireless_devices->count() }}</span>
                        </div>
                        <button @click="$dispatch('open-device-modal', { type: 'wireless' })" type="button" class="px-6 py-3 bg-white hover:bg-indigo-600 hover:text-white text-indigo-600 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-indigo-100 transition-all shadow-sm flex items-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Add Radio Unit
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($tower->wireless_devices as $device)
                        <div class="glass-panel border border-white/40 rounded-[2.5rem] overflow-hidden group hover:scale-[1.02] transition-all shadow-xl">
                            <!-- Device Header -->
                            <div class="p-6 flex items-start justify-between bg-white/40 backdrop-blur-md border-b border-white/60">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100 relative group-hover:scale-110 transition-transform">
                                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-lg tracking-tight">{{ $device->name }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">{{ $device->mode }}</span>
                                            <span class="text-[9px] font-mono font-bold text-gray-400 select-all">{{ $device->ip }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('network.towers.devices.destroy', [$tower, $device]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الجهاز؟')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- Device Stats/Meta -->
                            <div class="p-6 grid grid-cols-2 gap-4">
                                <div class="bg-white/50 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/80">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Frequency</p>
                                    <p class="text-sm font-black text-gray-900 tracking-tight">{{ $device->frequency ?? '5GHz' }}</p>
                                </div>
                                <div class="bg-white/50 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/80">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Main SSID</p>
                                    <p class="text-sm font-black text-indigo-600 tracking-tight">{{ $device->ssid ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="md:col-span-2 py-16 text-center glass-panel border border-dashed border-gray-300 rounded-[2.5rem]">
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">No Wireless Infrastructure</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Network Switches Section --}}
                <div>
                     <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Network Switches</h3>
                            <span class="bg-emerald-600 text-white px-3 py-1 rounded-xl text-[10px] font-black shadow-lg shadow-emerald-100">{{ $tower->switch_devices->count() }}</span>
                        </div>
                        <button @click="$dispatch('open-device-modal', { type: 'switch' })" type="button" class="px-6 py-3 bg-white hover:bg-emerald-600 hover:text-white text-emerald-600 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-emerald-100 transition-all shadow-sm flex items-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Add Switch
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($tower->switch_devices as $switch)
                        <div class="glass-panel border border-white/40 rounded-[2.5rem] overflow-hidden group hover:scale-[1.02] transition-all shadow-xl">
                            <div class="p-6 flex items-start justify-between bg-white/40 backdrop-blur-md border-b border-white/60">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100 relative group-hover:scale-110 transition-transform">
                                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full animate-pulse"></div>
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-lg tracking-tight">{{ $switch->name }}</p>
                                        <p class="text-[9px] font-mono font-bold text-gray-400 select-all mt-1">{{ $switch->ip ?? 'OFFLINE_NODE' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">{{ $switch->ports_count ?? '?' }} Port</span>
                                    <form action="{{ route('network.towers.devices.destroy', [$tower, $switch]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السويتش؟')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center text-gray-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($switch->deviceModel)
                            <div class="px-6 py-4 bg-white/30 backdrop-blur-md border-t border-white/40 flex justify-between items-center">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Hardware Profile</p>
                                <p class="text-xs font-black text-gray-700 tracking-tight">{{ $switch->deviceModel->manufacturer }} {{ $switch->deviceModel->model_name }}</p>
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="md:col-span-2 py-16 text-center glass-panel border border-dashed border-gray-300 rounded-[2.5rem]">
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">No Switching Capacity Detected</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Unassigned SSIDs --}}
                @php
                    $unassignedSsids = $tower->ssids->whereNull('tower_device_id');
                @endphp
                @if($unassignedSsids->count() > 0)
                <div class="pt-12 border-t border-gray-900/5">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <h3 class="text-xl font-black text-gray-900 tracking-tight uppercase">Orphaned Interfaces</h3>
                            <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-xl text-[10px] font-black">{{ $unassignedSsids->count() }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($unassignedSsids as $ssid)
                        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-6 opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all group">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-gray-200 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-inner">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 tracking-tight">{{ $ssid->ssid_name }}</p>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $ssid->frequency }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-6">
                                <span class="text-[8px] font-black text-rose-600 uppercase tracking-[0.2em] bg-rose-50 px-3 py-1 rounded-full border border-rose-100">Unassigned Hook</span>
                                <button @click="$dispatch('open-edit-ssid-modal', { ssidId: '{{ $ssid->id }}', ssidName: '{{ $ssid->ssid_name }}', frequency: '{{ $ssid->frequency }}', isActive: {{ $ssid->is_active ? 'true' : 'false' }}, notes: '{{ $ssid->notes }}' })" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:underline">Link Now</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Power Tab --}}
            <div x-show="activeTab === 'power'" x-transition class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if($tower->battery_count > 0)
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4 uppercase tracking-tight">
                            <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-2xl">🔋</div>
                            Energy Storage
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Cell Count</p>
                                <p class="text-sm font-black text-gray-900">{{ $tower->battery_count }} Units</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Chemistry</p>
                                <p class="text-sm font-black text-gray-900 font-mono tracking-tighter">{{ $tower->battery_type }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_solar)
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4 uppercase tracking-tight">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-2xl">☀️</div>
                            Photovoltaic Array
                        </h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Panels</p>
                                <p class="text-sm font-black text-gray-900">{{ $tower->solar_panels_count ?? '0' }}</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Unit Wattage</p>
                                <p class="text-sm font-black text-gray-900">{{ $tower->solar_panel_wattage }}W</p>
                            </div>
                            <div class="bg-indigo-600 p-4 rounded-2xl shadow-lg shadow-indigo-100">
                                <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-1">Total</p>
                                <p class="text-sm font-black text-white">{{ $tower->total_solar_capacity }}W</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_generator)
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-orange-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4 uppercase tracking-tight">
                            <div class="w-12 h-12 bg-orange-500/10 rounded-2xl flex items-center justify-center text-2xl">⚡</div>
                            Internal Combustion
                        </h4>
                        <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Fuel Capacity / Rating</p>
                            <p class="text-sm font-black text-gray-900 font-mono">{{ $tower->generator_capacity }}</p>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_inverter)
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4 uppercase tracking-tight">
                            <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-2xl">🔌</div>
                                Conversion Hub
                        </h4>
                        <div class="bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/60">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Output Load Rating</p>
                            <p class="text-sm font-black text-gray-900 font-mono">{{ $tower->inverter_capacity }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                @if(!$tower->battery_count && !$tower->has_solar && !$tower->has_generator && !$tower->has_inverter)
                <div class="py-24 text-center glass-panel rounded-[3rem] border border-dashed border-gray-300">
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Energy Signature: Undetected</p>
                    <a href="{{ route('network.towers.edit', [$tower, 'tab' => 'power']) }}" class="inline-flex px-8 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all">Synchronize Specs</a>
                </div>
                @endif
            </div>

            {{-- Costs Tab --}}
            <div x-show="activeTab === 'costs'" x-transition class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Monthly Fixed Costs --}}
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 shadow-xl overflow-hidden group">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-3 uppercase tracking-tight relative">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                            Operational Burn
                        </h4>
                        <div class="space-y-4 relative">
                            @if($tower->monthly_rent)
                            <div class="bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Node Lease</p>
                                    <p class="text-sm font-black text-gray-900">{{ number_format($tower->monthly_rent, 0) }} {{ $currency }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-400">🏢</div>
                            </div>
                            @endif
                            @if($tower->monthly_maintenance)
                            <div class="bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Field Support</p>
                                    <p class="text-sm font-black text-gray-900">{{ number_format($tower->monthly_maintenance, 0) }} {{ $currency }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-400">🔧</div>
                            </div>
                            @endif
                            <div class="bg-gray-900 p-6 rounded-3xl shadow-2xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-x-1/2 translate-y-1/2 blur-2xl"></div>
                                <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-1">Total Monthly Liability</p>
                                <p class="text-3xl font-black text-white tracking-tighter">{{ number_format($tower->monthly_fixed_costs ?? 0, 0) }} <span class="text-lg opacity-50">{{ $currency }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Equipment Costs --}}
                    <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 shadow-xl overflow-hidden group">
                        <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
                        <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-3 uppercase tracking-tight relative">
                            <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                            Capital Investment
                        </h4>
                        <div class="space-y-4 relative">
                            @if($tower->structure_cost)
                            <div class="bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Structural Build</p>
                                    <p class="text-sm font-black text-gray-900">{{ number_format($tower->structure_cost, 0) }} {{ $currency }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-400">🗼</div>
                            </div>
                            @endif
                            @if($tower->solar_installation_cost)
                            <div class="bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/60 flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Energy Installation</p>
                                    <p class="text-sm font-black text-gray-900">{{ number_format($tower->solar_installation_cost, 0) }} {{ $currency }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-400">☀️</div>
                            </div>
                            @endif
                            <div class="bg-white p-6 rounded-3xl border border-emerald-500/20 shadow-lg relative overflow-hidden group/burn">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -translate-x-1/2 translate-y-1/2 blur-2xl group-hover/burn:scale-150 transition-all"></div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-1">Total CAPEX Evaluation</p>
                                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ number_format($tower->total_equipment_cost ?? 0, 0) }} <span class="text-lg text-gray-300 font-normal">{{ $currency }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tower->monthly_notes)
                <div class="bg-amber-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <h4 class="text-[10px] font-black text-amber-200 uppercase tracking-[0.2em] mb-4 flex items-center gap-3">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        Ledger Verification
                    </h4>
                    <p class="text-lg font-bold leading-relaxed opacity-90">{{ $tower->monthly_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Connection Tab --}}
            <div x-show="activeTab === 'connection'" x-transition class="space-y-8">
                <div class="glass-panel rounded-[2.5rem] p-8 border border-white/40 shadow-xl overflow-hidden group">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
                    <h4 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-3 uppercase tracking-tight relative">
                        <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                        Uplink Topology
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative">
                        <div class="col-span-1 md:col-span-2">
                             @if($tower->mikrotikServer)
                                <div class="bg-gray-900 p-8 rounded-[2rem] shadow-2xl relative overflow-hidden group/server">
                                    <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-500/10 rounded-full -translate-x-1/2 translate-y-1/2 blur-3xl group-hover/server:scale-110 transition-all"></div>
                                    <div class="relative flex items-center justify-between">
                                        <div>
                                            <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-2">Master Controller (Gateway)</p>
                                            <p class="text-2xl font-black text-white tracking-widest">{{ $tower->mikrotikServer->name }}</p>
                                            <p class="text-xs text-indigo-200 mt-1 font-mono opacity-60">{{ $tower->mikrotikServer->ip_address }}</p>
                                        </div>
                                        <a href="{{ route('servers.show', $tower->mikrotikServer) }}" class="w-14 h-14 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center justify-center text-white hover:bg-white hover:text-indigo-900 transition-all">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($tower->connection_type)
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/60">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Link Protocol</p>
                            <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $tower->connection_type }}</p>
                        </div>
                        @endif
                        @if($tower->connection_port)
                        <div class="bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/60">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Target Port</p>
                            <p class="text-sm font-black text-indigo-600 font-mono tracking-tighter">{{ $tower->connection_port }}</p>
                        </div>
                        @endif
                        
                        @if($tower->connection_type === 'wireless')
                            {{-- Transmitter Details --}}
                            <div class="md:col-span-2 mt-4 space-y-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Point-to-Point Analysis</p>
                                    <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    {{-- Source / Transmitter --}}
                                    <div class="bg-white/30 backdrop-blur-sm p-6 rounded-[2rem] border border-white/50">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">Radio Source (PTP-TX)</p>
                                        @if($tower->transmitterRouter)
                                            <div class="flex items-start gap-4">
                                                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                                </div>
                                                <div>
                                                    <a href="{{ route('routers.show', $tower->transmitterRouter) }}" class="text-lg font-black text-indigo-600 hover:text-indigo-900 transition-colors">
                                                        {{ $tower->transmitterRouter->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 font-mono mt-1 opacity-70">{{ $tower->transmitterRouter->ip }}</p>
                                                    @if($tower->transmitterRouter->tower)
                                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-4">Located at:</p>
                                                        <a href="{{ route('network.towers.show', $tower->transmitterRouter->tower) }}" class="text-xs font-black text-gray-900 hover:text-indigo-600 flex items-center gap-2 mt-1">
                                                            <span class="w-1 h-1 bg-indigo-600 rounded-full"></span>
                                                            {{ $tower->transmitterRouter->tower->name }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Destination / Receiver --}}
                                    <div class="bg-indigo-600 p-6 rounded-[2rem] text-white shadow-2xl relative overflow-hidden group/rx">
                                        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover/rx:scale-125 transition-all"></div>
                                        <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-4 relative z-10">Local Terminal (PTP-RX)</p>
                                        <div class="flex items-start gap-4 relative z-10">
                                            <div class="w-14 h-14 bg-white/20 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center justify-center text-white">
                                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-lg font-black tracking-tight">{{ $tower->name }} Station</p>
                                                <p class="text-xs text-indigo-200 font-mono mt-1 opacity-70">{{ $tower->receiver_ip }}</p>
                                                <div class="mt-4 inline-flex px-3 py-1 bg-white/10 rounded-lg border border-white/10">
                                                    <p class="text-[9px] font-black uppercase tracking-widest text-white">{{ $tower->receiverModel->model_name ?? 'Radio Unit' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Device Creation Modal --}}
    <div x-data="{ open: false, deviceType: 'wireless', deviceMode: 'existing' }" 
         @open-device-modal.window="open = true; deviceType = $event.detail.type || 'wireless'; deviceMode = 'existing'" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" 
         style="display: none;">
         
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900/40 backdrop-blur-md" aria-hidden="true"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative z-10 glass-panel border border-white/40 rounded-[2.5rem] shadow-2xl w-full max-w-2xl bg-white/80 overflow-hidden">
            <form action="{{ route('network.towers.devices.store', $tower) }}" method="POST">
                @csrf
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight" x-text="deviceType === 'wireless' ? 'Radio Unit Registration' : 'Switch Integration'"></h3>
                            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1" x-text="deviceType === 'wireless' ? 'Sector, Omni, or AP Deployment' : 'Ethernet / Backend Switching'"></p>
                        </div>
                        <button type="button" @click="open = false" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-all">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <input type="hidden" name="device_type" :value="deviceType">

                    {{-- Toggle --}}
                    <div class="bg-gray-100 p-1.5 rounded-2xl flex gap-2 mb-8">
                        <button type="button" @click="deviceMode = 'existing'" :class="deviceMode === 'existing' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500'" class="flex-1 py-3 rounded-xl font-black text-[11px] uppercase tracking-widest transition-all">
                            Stored Asset
                        </button>
                        <button type="button" @click="deviceMode = 'new'" :class="deviceMode === 'new' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500'" class="flex-1 py-3 rounded-xl font-black text-[11px] uppercase tracking-widest transition-all">
                            Provision New
                        </button>
                    </div>

                    {{-- Existing Device Selection --}}
                    <div x-show="deviceMode === 'existing'" x-transition class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Select Active Fragment</label>
                            <select name="existing_device_id" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                <option value="">-- Choose Hardware --</option>
                                @foreach(\App\Models\TowerDevice::whereNull('tower_id')->get() as $device)
                                    <option value="{{ $device->id }}" x-show="deviceType === '{{ $device->device_type }}'">
                                        {{ $device->name }} ({{ $device->ip ?? 'No IP' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- New Device Form --}}
                    <div x-show="deviceMode === 'new'" x-transition class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Target Name</label>
                                <input type="text" name="name" :required="deviceMode === 'new'" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900" placeholder="e.g. CORE-SW-01">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Hardware Model</label>
                                <select name="device_model_id" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                    <option value="">Choose Profile...</option>
                                    @foreach(\App\Models\DeviceModel::all() as $model)
                                        <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Control IP</label>
                                <input type="text" name="ip" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-mono font-bold text-gray-900" dir="ltr" placeholder="10.0.x.x">
                            </div>
                            <div x-show="deviceType === 'switch'">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Interface Count</label>
                                <input type="number" name="ports_count" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900" placeholder="24">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6" x-show="deviceType === 'wireless'">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Operational Mode</label>
                                <select name="mode" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                    <option value="ap">Access Point (AP)</option>
                                    <option value="station">Station</option>
                                    <option value="bridge">Bridge</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="device_mode" x-bind:value="deviceMode">
                <div class="bg-gray-900 p-8 flex items-center justify-between">
                    <button type="button" @click="open = false" class="text-[11px] font-black text-indigo-300 uppercase tracking-widest hover:text-white transition-colors">Abort Procedure</button>
                    <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all">
                        Commit Integration
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SSID Creation Modal --}}
    <div x-data="{ open: false, deviceId: null, deviceName: '', deviceType: '' }" 
         @open-ssid-modal.window="open = true; deviceId = $event.detail.deviceId; deviceName = $event.detail.deviceName; deviceType = $event.detail.deviceType" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="relative z-10 inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('network.towers.ssids.store', $tower) }}" method="POST">
                    @csrf
                    <input type="hidden" name="tower_device_id" x-bind:value="deviceId">
                    <input type="hidden" name="device_type" x-bind:value="deviceType">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">إضافة شبكة (SSID) جديدة</h3>
                            <p class="text-sm text-gray-500 mt-1">للجهاز: <span class="font-semibold" x-text="deviceName"></span></p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة (SSID) *</label>
                                <input type="text" name="ssid_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="My-WiFi">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد *</label>
                                    <select name="frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                                    <select name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="1">فعال</option>
                                        <option value="0">غير فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            حفظ SSID
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit SSID Modal --}}
    <div x-data="{ open: false, ssidId: null, ssidName: '', frequency: '', isActive: true, notes: '' }"
         @open-edit-ssid-modal.window="open = true; ssidId = $event.detail.ssidId; ssidName = $event.detail.ssidName; frequency = $event.detail.frequency; isActive = $event.detail.isActive; notes = $event.detail.notes"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="relative z-10 inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form :action="'{{ url('MadaaQ/public/network/towers/' . $tower->id . '/ssids') }}/' + ssidId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">تعديل شبكة (SSID)</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة (SSID) *</label>
                                <input type="text" name="ssid_name" required x-model="ssidName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد *</label>
                                    <select name="frequency" required x-model="frequency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                                    <select name="is_active" x-model="isActive" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="1">فعال</option>
                                        <option value="0">غير فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                <textarea name="notes" rows="2" x-model="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            حفظ التعديلات
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
