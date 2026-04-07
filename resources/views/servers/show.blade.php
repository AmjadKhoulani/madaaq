@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-12" x-data="serverDashboard()">
    <!-- Command Center Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden mb-10">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl"></div>

        <div class="relative flex flex-col lg:flex-row items-center gap-12">
            <!-- Hardware Hologram -->
            <div class="relative group">
                <div class="w-56 h-56 bg-white/40 backdrop-blur-md rounded-[2.5rem] border border-white/60 p-6 flex items-center justify-center shadow-xl group-hover:scale-105 transition-all duration-700">
                    <img src="{{ $server->deviceModel->image_url ?? 'https://placehold.co/200x150?text=MikroTik' }}" 
                         alt="{{ $server->name }}" 
                         class="max-w-full max-h-full object-contain filter drop-shadow-2xl">
                </div>
                <div class="absolute -bottom-3 -right-3 transform transition-transform group-hover:scale-110">
                    @if($server->is_connected)
                        <div class="px-5 py-2 rounded-2xl bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-200 flex items-center gap-2 border-2 border-white">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            Synchronized
                        </div>
                    @else
                        <div class="px-5 py-2 rounded-2xl bg-rose-500 text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-rose-200 flex items-center gap-2 border-2 border-white">
                            <span class="w-2 h-2 bg-white rounded-full"></span>
                            Disconnected
                        </div>
                    @endif
                </div>
            </div>

            <!-- Identity Core -->
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-6 justify-center lg:justify-start">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">{{ $server->name }}</h1>
                    <span class="px-4 py-1.5 bg-indigo-600/10 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border border-indigo-600/20">
                        {{ $server->deviceModel->model_name ?? 'MadaaQ Infrastructure' }}
                    </span>
                </div>
                
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <div class="px-6 py-3 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-sm flex items-center gap-3 group/chip transition-all hover:bg-white">
                        <span class="text-lg grayscale group-hover/chip:grayscale-0 transition-all">🌐</span>
                        <span class="font-mono font-black text-gray-900 text-sm tracking-tight">{{ $server->ip }}</span>
                    </div>
                    <div class="px-6 py-3 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-sm flex items-center gap-3 group/chip transition-all hover:bg-white">
                        <span class="text-lg grayscale group-hover/chip:grayscale-0 transition-all">🔌</span>
                        <span class="font-mono font-black text-gray-900 text-sm tracking-tight">API:{{ $server->api_port }}</span>
                    </div>
                    <div class="px-6 py-3 bg-white/60 backdrop-blur-md rounded-2xl border border-white/80 shadow-sm flex items-center gap-3 group/chip transition-all hover:bg-white">
                        <span class="text-lg grayscale group-hover/chip:grayscale-0 transition-all">📍</span>
                        <span class="font-black text-gray-900 text-[11px] uppercase tracking-widest">{{ $server->location ?? 'Edge Node' }}</span>
                    </div>
                </div>
            </div>

            <!-- Global Actions -->
            <div class="shrink-0">
                <a href="{{ route('servers.edit', $server) }}" class="px-8 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    System Protocol
                </a>
            </div>
        </div>
    </div>

    <!-- Live Telemetry Orbs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10" x-show="isConnected">
        <!-- CPU Orb -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
            <div class="relative flex items-center justify-between mb-8">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Compute Load</h4>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter" id="cpu-val">0%</h3>
                </div>
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
            </div>
            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden border border-gray-50">
                <div id="cpu-bar" class="bg-indigo-600 h-full transition-all duration-1000 shadow-sm" style="width: 0%"></div>
            </div>
        </div>

        <!-- RAM Orb -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Memory</h4>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tighter" id="mem-val">-- / --</h3>
                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mt-1">Megabytes Available</p>
                </div>
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- PPPoE Orb -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-blue-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">PPPoE Registry</h4>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter" id="active-pppoe-val">0</h3>
                    <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mt-1">Synchronized Sessions</p>
                </div>
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                </div>
            </div>
        </div>

        <!-- Hotspot Orb -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-amber-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Hotspot Guard</h4>
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter" id="active-hotspot-val">0</h3>
                    <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mt-1">Voucher Connections</p>
                </div>
                <div class="w-14 h-14 bg-amber-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- System Intelligence Registry -->
    <div class="glass-panel border border-white/40 rounded-[3rem] shadow-2xl overflow-hidden min-h-[600px] mb-12">
        <!-- Modern Navigator -->
        <div class="border-b border-gray-900/5 bg-white/30 backdrop-blur-md p-3">
            <nav class="flex overflow-x-auto no-scrollbar gap-2">
                <template x-for="tab in tabs" :key="tab.id">
                    <button @click="activeTab = tab.id" 
                            class="px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all flex items-center gap-3 whitespace-nowrap"
                            :class="activeTab === tab.id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-white hover:text-gray-900'">
                        <span x-text="tab.icon"></span>
                        <span x-text="tab.name"></span>
                    </button>
                </template>
            </nav>
        </div>

        <!-- Intelligence Hub -->
        <div class="p-8 md:p-12">
            <!-- Global Overview -->
            <div x-show="activeTab === 'general'" x-transition:enter="duration-500 ease-out" class="space-y-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Unit Specifications -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Hardware Intelligence</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Operating System</p>
                                <p class="font-bold text-gray-900 font-mono text-sm" id="version-val">--</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Up-time Period</p>
                                <p class="font-bold text-gray-900 font-mono text-sm tracking-tighter" id="uptime-val">--</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Governance Access</p>
                                <p class="font-black text-gray-900 text-sm tracking-tight">{{ $server->username }}</p>
                            </div>
                            <div class="bg-white/40 backdrop-blur-md p-6 rounded-3xl border border-white/60">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Sync Telemetry</p>
                                <p class="font-black text-indigo-600 text-[10px] uppercase">
                                    {{ $server->last_sync_at ? $server->last_sync_at->diffForHumans() : 'Standby Mode' }}
                                </p>
                            </div>
                        </div>
                    </div>
                        
                        <!-- Connectivity Spectrum (DNS) -->
                        <div id="top-sites-card-new" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </div>
                                <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">DNS Demand Matrix</h4>
                            </div>
                            <div class="glass-panel border border-white/60 p-8 rounded-[2.5rem] space-y-6" id="top-sites-list-new">
                                <!-- Synchronized via JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Geospatial Node Plot -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="w-12 h-12 bg-rose-600/10 rounded-2xl flex items-center justify-center text-rose-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Network Topology Point</h4>
                        </div>
                        <div class="aspect-video w-full rounded-[2.5rem] overflow-hidden border border-white/80 shadow-2xl relative group">
                            @if($server->lat && $server->lng)
                                <div id="map" class="w-full h-full z-0 grayscale-[0.5] contrast-[1.1]"></div>
                                <div class="absolute bottom-6 right-6 z-[10] bg-white/40 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/60 shadow-xl">
                                    <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest mb-1">Coordinates Verified</p>
                                    <p class="font-mono text-xs font-bold text-gray-900">{{ $server->lat }}, {{ $server->lng }}</p>
                                </div>
                            @else
                                <div class="w-full h-full bg-slate-50 flex flex-col items-center justify-center text-gray-400 gap-4">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-3xl">🗺️</div>
                                    <p class="font-black text-xs uppercase tracking-widest">Geospatial Data Missing</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Registry (Users) -->
            <div x-show="activeTab === 'users'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-8">
                <div id="sessions-card-new" class="space-y-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Live Session Registry</h4>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="px-6 py-2 bg-emerald-600/5 text-emerald-700 rounded-xl border border-emerald-600/10 text-[10px] font-black uppercase tracking-widest">
                                PPPoE Array: <span id="pppoe-count-label-new" class="ml-1">0</span>
                            </div>
                            <div class="px-6 py-2 bg-amber-600/5 text-amber-700 rounded-xl border border-amber-600/10 text-[10px] font-black uppercase tracking-widest">
                                Proxy Hub: <span id="hotspot-count-label-new" class="ml-1">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel border border-white/60 rounded-[2.5rem] overflow-hidden shadow-xl">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-gray-900/5 border-b border-gray-900/5">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">End-User Identity</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Network Interface (IP)</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Protocol Type</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Signal Uptime</th>
                                </tr>
                            </thead>
                            <tbody id="sessions-list-new" class="divide-y divide-gray-900/5">
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center gap-4 text-gray-400">
                                            <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            <p class="font-black text-xs uppercase tracking-widest">Awaiting Active Packets...</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Utility Array (Tools) -->
            <div x-show="activeTab === 'tools'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Protocol Handshake -->
                    <div class="glass-panel border border-white/60 p-10 rounded-[3rem] shadow-xl space-y-8 group hover:scale-[1.01] transition-all">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Connection Pulse</h4>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mt-1">WireGuard API Verification</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed">Execute a diagnostic handshake between MadaaQ Central and the RouterOS API layer to verify encrypted tunneling stability.</p>
                        <button onclick="testConnection()" class="w-full py-5 bg-gray-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl hover:bg-emerald-600 transition-all active:scale-95">
                            Verify Infrastructure
                        </button>
                        <div id="connection-status-new" class="hidden animate-in zoom-in-95 duration-300 text-center p-5 rounded-2xl bg-slate-50 border border-gray-100 font-black text-xs uppercase tracking-widest"></div>
                    </div>

                    <!-- Database Mirroring -->
                    <div class="bg-gray-900 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden space-y-8 group">
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
                        <div class="flex items-center gap-5 relative z-10">
                            <div class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center text-3xl border border-white/10">📥</div>
                            <div>
                                <h4 class="text-xl font-black text-white tracking-tight uppercase">Registry Assimilation</h4>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mt-1">Local Resource Import</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-400 leading-relaxed relative z-10">Synchronize existing RouterOS configuration profiles into the MadaaQ distributed database for administrative oversight.</p>
                        <div class="grid grid-cols-1 gap-4 relative z-10">
                            <button onclick="importData('pppoe')" class="flex items-center justify-between px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-white font-black text-[10px] uppercase tracking-widest transition-all">
                                <span>Migrate PPPoE Matrix</span>
                                <span class="bg-white/10 p-2 rounded-lg">📡</span>
                            </button>
                            <button onclick="importData('hotspot')" class="flex items-center justify-between px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-white font-black text-[10px] uppercase tracking-widest transition-all">
                                <span>Mirror Hotspot Stack</span>
                                <span class="bg-white/10 p-2 rounded-lg">📶</span>
                            </button>
                            <button onclick="importData('pppoe-profiles')" class="flex items-center justify-between px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-white font-black text-[10px] uppercase tracking-widest transition-all">
                                <span>Sync Policy Profiles</span>
                                <span class="bg-white/10 p-2 rounded-lg">📋</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terminal Protocol (Config) -->
            <div x-show="activeTab === 'config'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-600/10 rounded-2xl flex items-center justify-center text-amber-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Initialization Terminal</h4>
                    </div>
                    <button @click="copyScript()" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:scale-105 transition-all flex items-center gap-3">
                        <span x-show="!scriptCopied" class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            Copy Command Stack
                        </span>
                        <span x-show="scriptCopied" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            Stack Copied
                        </span>
                    </button>
                </div>

                <div class="rounded-[2.5rem] border border-gray-900/10 bg-gray-950 p-1 shadow-2xl relative group overflow-hidden" dir="ltr">
                    <div class="bg-gray-900/50 backdrop-blur-md px-8 py-4 flex items-center justify-between border-b border-white/5">
                        <div class="flex gap-2.5">
                            <div class="w-3 h-3 rounded-full bg-rose-500/80 shadow-sm"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-500/80 shadow-sm"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500/80 shadow-sm"></div>
                        </div>
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Infrastructure Provisioning</span>
                    </div>
                    <div class="p-10 max-h-[500px] overflow-y-auto custom-scrollbar-dark scroll-smooth">
                        <pre class="text-sm font-mono text-indigo-400 leading-relaxed whitespace-pre-wrap"><code id="setup-code-new" class="block">Generating optimized protocol script...</code></pre>
                    </div>
                </div>

                <div class="bg-indigo-600 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden group">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="flex items-start gap-6 relative z-10">
                        <div class="w-14 h-14 bg-white/20 text-white rounded-2xl flex items-center justify-center text-3xl border border-white/10 shrink-0">💡</div>
                        <div class="space-y-4">
                            <h5 class="text-xl font-black text-white uppercase tracking-tight">Provisioning Protocol</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-indigo-100 text-sm">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center text-[10px] font-black">01</span>
                                        <p>Capture the command stack using the <b>Copy</b> button.</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center text-[10px] font-black">02</span>
                                        <p>Initialize a secure session via <b>Winbox</b>.</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center text-[10px] font-black">03</span>
                                        <p>Inject commands into the <b>New Terminal</b> interface.</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center text-[10px] font-black">04</span>
                                        <p>Allow <b>60 Seconds</b> for secure tunnel registration.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Version Stability (Backups) -->
            <div x-show="activeTab === 'backups'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-600/10 rounded-2xl flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 tracking-tight uppercase">Infrastructure Recovery Log</h4>
                </div>
                
                @if($server->backups->count() > 0)
                    <div class="glass-panel border border-white/60 rounded-[2.5rem] overflow-hidden shadow-xl">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-gray-900/5 border-b border-gray-900/5">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Snapshot Identity</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Protocol Type</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Timestamp</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Payload Size</th>
                                    <th class="px-8 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Deployment</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-900/5">
                                @foreach($server->backups()->latest()->take(10)->get() as $backup)
                                <tr class="hover:bg-gray-900/5 transition-all">
                                    <td class="px-8 py-6 font-black text-gray-900">{{ $backup->filename }}</td>
                                    <td class="px-8 py-6">
                                        @if($backup->type == 'backup')
                                            <span class="px-4 py-1.5 bg-blue-600/5 text-blue-700 rounded-xl border border-blue-600/10 text-[9px] font-black tracking-widest uppercase">Binary Stack</span>
                                        @else
                                            <span class="px-4 py-1.5 bg-purple-600/5 text-purple-700 rounded-xl border border-purple-600/10 text-[9px] font-black tracking-widest uppercase">Script Export</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-gray-500 font-mono text-[10px] tracking-widest" dir="ltr">{{ $backup->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-8 py-6 text-indigo-600 font-black text-xs" dir="ltr">{{ round($backup->size / 1024 / 1024, 2) }} MB</td>
                                    <td class="px-8 py-6 text-center">
                                        <a href="{{ route('servers.backups.download', $backup) }}" class="inline-flex items-center gap-3 px-6 py-2.5 bg-white text-gray-900 border border-gray-100 rounded-xl font-black text-[10px] uppercase shadow-sm hover:bg-gray-900 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Retrieve
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="glass-panel border-2 border-dashed border-gray-200 py-32 rounded-[3.5rem] flex flex-col items-center justify-center text-gray-400 gap-6">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-5xl grayscale opacity-40">💾</div>
                        <div class="text-center">
                            <h5 class="text-xl font-black text-gray-900 tracking-tight uppercase mb-2">No Snapshots Detected</h5>
                            <p class="text-xs font-bold uppercase tracking-widest">Automated nightly backups are initialized.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<!-- Extra Styles for progress and scrollbars -->
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .custom-scrollbar-dark::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar-dark::-webkit-scrollbar-track { background: #0f172a; }
    .custom-scrollbar-dark::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; border: 2px solid #0f172a; }

    [x-cloak] { display: none !important; }

    /* Map Styling */
    #map {
        filter: saturate(1.2) contrast(1.1);
    }
</style>

<!-- Leaflet for Map (only if coords exist) -->
@if($server->lat && $server->lng)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endif

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('serverDashboard', () => ({
        activeTab: 'general',
        showScript: false,
        scriptCopied: false,
        isConnected: {{ $server->is_connected ? 'true' : 'false' }},
        tabs: [
            { id: 'general', name: 'العامة', icon: '🏠' },
            { id: 'users', name: 'المستخدمين', icon: '👥' },
            { id: 'tools', name: 'الأدوات', icon: '🛠️' },
            { id: 'config', name: 'الإعداد', icon: '📜' },
            { id: 'backups', name: 'النسخ الاحتياطي', icon: '💾' },
        ],

        init() {
            if (this.activeTab === 'general') {
                this.initMap();
                this.updateStatus();
            }
            
            // Auto update every 30s
            setInterval(() => this.updateStatus(), 30000);
            
            this.$watch('activeTab', value => {
                if (value === 'general') setTimeout(() => this.initMap(), 100);
                if (value === 'config') this.fetchConfig();
            });
        },

        initMap() {
            @if($server->lat && $server->lng)
            if (document.getElementById('map') && !this.mapLoaded) {
                const map = L.map('map').setView([{{ $server->lat }}, {{ $server->lng }}], 14);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19
                }).addTo(map);
                
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div style='background-color:#3b82f6; width:16px; height:16px; border-radius:50%; border:3px solid white; shadow:0 5px 15px rgba(0,0,0,0.2)'></div>",
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                L.marker([{{ $server->lat }}, {{ $server->lng }}], {icon: customIcon}).addTo(map);
                this.mapLoaded = true;
            }
            @endif
        },

        updateStatus() {
            fetch('{{ route('servers.status', $server) }}')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const info = data.data;
                        this.isConnected = true;
                        
                        // Header info
                        if(document.getElementById('version-val')) document.getElementById('version-val').textContent = info.version;
                        if(document.getElementById('uptime-val')) document.getElementById('uptime-val').textContent = info.uptime;
                        
                        // Cards
                        if(document.getElementById('cpu-val')) document.getElementById('cpu-val').textContent = info.cpu_load + '%';
                        if(document.getElementById('cpu-bar')) document.getElementById('cpu-bar').style.width = info.cpu_load + '%';
                        
                        if(document.getElementById('mem-val')) {
                            document.getElementById('mem-val').textContent = 
                                Math.round(info.free_memory / 1024 / 1024) + ' / ' + Math.round(info.total_memory / 1024 / 1024);
                        }

                        if(document.getElementById('active-pppoe-val')) document.getElementById('active-pppoe-val').textContent = info.active_pppoe;
                        if(document.getElementById('active-hotspot-val')) document.getElementById('active-hotspot-val').textContent = info.active_hotspot;
                        if(document.getElementById('pppoe-count-label-new')) document.getElementById('pppoe-count-label-new').textContent = info.active_pppoe;
                        if(document.getElementById('hotspot-count-label-new')) document.getElementById('hotspot-count-label-new').textContent = info.active_hotspot;

                        // Sessions List
                        const list = document.getElementById('sessions-list-new');
                        if (list && (info.pppoe_users.length > 0 || info.hotspot_users.length > 0)) {
                            list.innerHTML = '';
                            info.pppoe_users.forEach(user => {
                                list.innerHTML += this.renderSessionRow(user, 'PPPoE', 'green');
                            });
                            info.hotspot_users.forEach(user => {
                                list.innerHTML += this.renderSessionRow(user, 'Hotspot', 'orange');
                            });
                        }

                        // Top Sites
                        const sitesList = document.getElementById('top-sites-list-new');
                        if (sitesList && info.top_sites && info.top_sites.length > 0) {
                            document.getElementById('top-sites-card-new').classList.remove('hidden');
                            sitesList.innerHTML = '';
                            const maxHits = info.top_sites[0].hits;
                            info.top_sites.forEach(site => {
                                const percent = (site.hits / maxHits) * 100;
                                sitesList.innerHTML += `
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between text-xs font-bold">
                                            <span class="text-gray-700 font-mono">${site.domain}</span>
                                            <span class="text-indigo-600">${site.hits} طلب</span>
                                        </div>
                                        <div class="w-full bg-white h-2 rounded-full border border-gray-100 overflow-hidden">
                                            <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000" style="width: ${percent}%"></div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                    } else {
                        this.isConnected = false;
                    }
                })
                .catch(() => this.isConnected = false);
        },

        renderSessionRow(user, type, color) {
            return `
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-black text-gray-900">${user.name || user.user || 'N/A'}</td>
                    <td class="px-6 py-4 font-mono text-gray-500">${user.address || '--'}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-${color}-50 text-${color}-700 rounded-lg text-[10px] font-black uppercase tracking-widest">${type}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-mono text-xs">${user.uptime || '--'}</td>
                </tr>
            `;
        },

        fetchConfig() {
            const codeBlock = document.getElementById('setup-code-new');
            if (codeBlock && codeBlock.textContent.includes('Loading')) {
                fetch('{{ route('servers.setup-script', $server) }}')
                    .then(res => res.json())
                    .then(data => {
                        codeBlock.textContent = data.script;
                    })
                    .catch(() => {
                        codeBlock.textContent = '# خطأ في تحميل السكريبت';
                    });
            }
        },

        copyScript() {
            fetch('{{ route('servers.setup-script', $server) }}')
                .then(res => res.json())
                .then(data => {
                    navigator.clipboard.writeText(data.script);
                    this.scriptCopied = true;
                    setTimeout(() => this.scriptCopied = false, 3000);
                });
        }
    }));
});

function testConnection() {
    const statusDiv = document.getElementById('connection-status-new');
    statusDiv.innerHTML = '<span class="flex items-center justify-center gap-2"><span class="animate-spin text-blue-500">🔄</span> جاري الاختبار...</span>';
    statusDiv.classList.remove('hidden');

    fetch('{{ route('servers.test-connection', $server) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        statusDiv.innerHTML = data.success ? 
            `<span class="text-green-600">✅ ${data.message}</span>` : 
            `<span class="text-red-600">❌ ${data.message}</span>`;
            
        if (data.success) {
            setTimeout(() => window.location.reload(), 2000);
        }
    });
}

function importData(type) {
    const btn = event.currentTarget;
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<span>جاري الاستيراد...</span><span class="animate-spin">⌛</span>';
    btn.disabled = true;

    let route = '';
    if (type === 'pppoe') route = '{{ route('servers.import-pppoe', $server) }}';
    if (type === 'hotspot') route = '{{ route('servers.import-hotspot', $server) }}';
    if (type === 'pppoe-profiles') route = '{{ route('servers.import-pppoe-profiles', $server) }}';
    if (type === 'hotspot-profiles') route = '{{ route('servers.import-hotspot-profiles', $server) }}';

    fetch(route, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        if (data.success) {
            alert('✅ ' + data.message);
            window.location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        alert('❌ حدث خطأ غير متوقع');
    });
}
</script>
@endsection
