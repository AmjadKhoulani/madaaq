@extends('layouts.admin')

@section('title', 'مركز قيادة العقدة | ' . $server->name)

@section('content')
<div class="space-y-12 pb-24" x-data="serverDashboard()">
    
    <!-- Radiant Hub Header -->
    <div class="glass-panel p-10 rounded-[2.5rem] relative overflow-hidden !bg-white/80">
        <div class="absolute -top-12 -right-12 w-64 h-64 bg-accent-gradient opacity-5 blur-3xl"></div>
        
        <div class="relative flex flex-col lg:flex-row items-center gap-12">
            <!-- Hardware Identity Block -->
            <div class="relative shrink-0 group">
                <div class="w-48 h-48 bg-white rounded-3xl border border-slate-100 p-8 flex items-center justify-center shadow-radiant group-hover:scale-105 transition-transform duration-700">
                    <img src="{{ $server->deviceModel->image_url ?? 'https://placehold.co/200x150?text=Hardware+Core' }}" 
                         alt="{{ $server->name }}" 
                         class="max-w-full max-h-full object-contain filter drop-shadow-2xl">
                </div>
                <div class="absolute -bottom-3 -right-3 z-10">
                    <template x-if="isConnected">
                        <div class="px-5 py-1.5 bg-emerald-500 text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-full shadow-glow-cyan border-2 border-white flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                            Handshake Stable
                        </div>
                    </template>
                    <template x-if="!isConnected">
                        <div class="px-5 py-1.5 bg-error text-white text-[9px] font-black uppercase tracking-[0.2em] rounded-full shadow-glow-purple border-2 border-white flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                            Protocol Loss
                        </div>
                    </template>
                </div>
            </div>

            <!-- Identity Core Matrix -->
            <div class="flex-1 text-center lg:text-right">
                <div class="flex flex-col lg:flex-row lg:items-center gap-5 mb-8 justify-center lg:justify-start">
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase leading-none">{{ $server->name }}</h1>
                    <span class="px-6 py-1.5 bg-slate-900 text-neon-cyan rounded-full text-[9px] font-black uppercase tracking-[0.3em] shadow-glow-cyan">
                        {{ $server->deviceModel->model_name ?? 'MikroTik Infrastructure' }}
                    </span>
                </div>
                
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3 group hover:border-primary transition-all">
                        <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-primary">public</span>
                        <span class="font-manrope font-black text-slate-700 text-xs tracking-tight italic">{{ $server->ip }}</span>
                    </div>
                    <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3 group hover:border-primary transition-all">
                        <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-primary">settings_input_component</span>
                        <span class="font-manrope font-black text-slate-700 text-xs tracking-tight">PORT:{{ $server->api_port }}</span>
                    </div>
                    <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3 group hover:border-primary transition-all">
                        <span class="material-symbols-outlined text-sm text-slate-300 group-hover:text-primary">location_on</span>
                        <span class="font-black text-slate-700 text-[10px] uppercase tracking-widest italic">{{ $server->location ?? 'Edge Node' }}</span>
                    </div>
                </div>
            </div>

            <!-- Hub Actions -->
            <div class="shrink-0">
                <a href="{{ route('servers.edit', $server) }}" class="px-10 py-4 bg-primary text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
                    <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform duration-500">settings_suggest</span>
                    Edit Node Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Live Telemetry Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" x-show="isConnected">
        <!-- CPU Load Shell -->
        <div class="glass-card p-8 rounded-3xl h-44 flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-primary/5 rounded-full blur-2xl transition-transform group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline italic">Compute Intensive Load</p>
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">processor</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter" id="cpu-val">0%</h3>
                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div id="cpu-bar" class="bg-accent-flow h-full transition-all duration-1000" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- RAM Registry -->
        <div class="glass-card p-8 rounded-3xl h-44 flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-vibrant-purple/5 rounded-full blur-2xl transition-transform group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline italic">Memory Flux Status</p>
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-vibrant-purple">
                    <span class="material-symbols-outlined">memory</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter uppercase" id="mem-val">-- / --</h3>
                <p class="text-[9px] font-black text-vibrant-purple uppercase tracking-[0.2em] mt-2 italic shadow-neon-purple shadow-sm">Megabytes Allocated</p>
            </div>
        </div>

        <!-- PPPoE Tunneling -->
        <div class="glass-card p-8 rounded-3xl h-44 flex flex-col justify-between relative overflow-hidden group border-b-4 border-primary/20">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-primary/5 rounded-full blur-2xl transition-transform group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline italic">Broadband Sessions</p>
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">lan</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-primary italic tracking-tighter" id="active-pppoe-val">0</h3>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2 italic">Active PPPoE Links</p>
            </div>
        </div>

        <!-- Hotspot Registry -->
        <div class="glass-card p-8 rounded-3xl h-44 flex flex-col justify-between relative overflow-hidden group border-b-4 border-neon-cyan/20">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-neon-cyan/5 rounded-full blur-2xl transition-transform group-hover:scale-150"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline italic">Identity Hub Gates</p>
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-neon-cyan">
                    <span class="material-symbols-outlined">wifi_tethering</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black text-neon-cyan italic tracking-tighter" id="active-hotspot-val">0</h3>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-2 italic">Active Portal Leases</p>
            </div>
        </div>
    </div>

    <!-- Intelligence Matrix Hub -->
    <div class="glass-panel rounded-[2.5rem] overflow-hidden !bg-white/60 flex flex-col min-h-[700px]">
        <!-- High-Intensity Tab Bar -->
        <div class="bg-white/40 border-b border-slate-100 px-6">
            <nav class="flex gap-2 overflow-x-auto no-scrollbar py-4">
                <template x-for="tab in tabs" :key="tab.id">
                    <button @click="activeTab = tab.id" 
                            class="px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-3 italic"
                            :class="activeTab === tab.id ? 'bg-slate-900 text-white shadow-glow-purple' : 'text-slate-500 hover:bg-white hover:text-primary'">
                        <span class="material-symbols-outlined text-sm" x-text="tab.md_icon"></span>
                        <span x-text="tab.name"></span>
                    </button>
                </template>
            </nav>
        </div>

        <!-- Operational Content Area -->
        <div class="p-10 flex-1">
            <!-- Tab: General Diagnostics & Topology -->
            <div x-show="activeTab === 'general'" x-transition:enter="duration-500 ease-out" class="space-y-12 animate-in fade-in slide-in-from-bottom-4">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <!-- Left: Asset Architecture -->
                    <div class="lg:col-span-12 space-y-10">
                        <div class="flex items-center gap-4">
                            <span class="w-10 h-px bg-primary/20"></span>
                            <h4 class="text-sm font-black text-primary uppercase tracking-[0.2em] italic">Infrastructure Logic Profile</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="p-8 bg-white/40 border border-slate-100 rounded-3xl group hover:border-neon-cyan transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Revision Meta</p>
                                <p class="font-manrope font-black text-primary text-sm italic" id="version-val">--</p>
                            </div>
                            <div class="p-8 bg-white/40 border border-slate-100 rounded-3xl group hover:border-vibrant-purple transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Uptime Heartbeat</p>
                                <p class="font-manrope font-black text-primary text-sm italic" id="uptime-val">--</p>
                            </div>
                            <div class="p-8 bg-white/40 border border-slate-100 rounded-3xl group hover:border-primary transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Admin Credentials</p>
                                <p class="font-black text-slate-900 text-[11px] uppercase truncate italic tracking-tighter">{{ $server->username }}</p>
                            </div>
                            <div class="p-8 bg-white/40 border border-slate-100 rounded-3xl group hover:border-emerald-500 transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Sync Termination</p>
                                <p class="font-black text-emerald-600 text-[10px] uppercase italic tracking-tighter">
                                    {{ $server->last_sync_at ? $server->last_sync_at->diffForHumans() : 'IDLE WAIT' }}
                                </p>
                            </div>
                        </div>

                        <!-- DNS Query Heatmap -->
                        <div id="top-sites-card-new" class="hidden animate-in fade-in slide-in-from-bottom-8 duration-700 pt-10 border-t border-slate-100">
                             <div class="flex items-center gap-4 mb-10">
                                <span class="w-10 h-px bg-vibrant-purple/20"></span>
                                <h4 class="text-sm font-black text-vibrant-purple uppercase tracking-[0.2em] italic">Spectral DNS Resolution Heatmap</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-10" id="top-sites-list-new"></div>
                        </div>
                    </div>

                    <!-- Geospatial Topology Registry -->
                    <div class="lg:col-span-12 space-y-8 pt-10 border-t border-slate-100">
                        <div class="flex items-center gap-4">
                            <span class="w-10 h-px bg-slate-300"></span>
                            <h4 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] italic">Geospatial Registry Mapping</h4>
                        </div>
                        <div class="relative group">
                            <div class="h-96 w-full rounded-[2rem] border border-slate-200 overflow-hidden relative shadow-radiant">
                                @if($server->lat && $server->lng)
                                    <div id="map" class="w-full h-full z-0 grayscale-[0.3] hover:grayscale-0 transition-all duration-1000"></div>
                                @else
                                    <div class="w-full h-full bg-slate-50 flex flex-col items-center justify-center text-slate-300 gap-6 uppercase italic">
                                        <span class="material-symbols-outlined text-6xl font-light">location_off</span>
                                        <p class="text-[12px] font-black tracking-[0.3em]">Geospatial Data Not Synchronized</p>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute bottom-8 left-8 z-[10] bg-slate-900/90 backdrop-blur-xl px-8 py-4 rounded-2xl border border-white/5 shadow-2xl transition-all">
                                <p class="text-[10px] font-black text-neon-cyan italic uppercase tracking-[0.2em]">Global Positioning System Vector | Real-Time Sync</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Live Telemetry Flow (Users) -->
            <div x-show="activeTab === 'users'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-10 animate-in fade-in slide-in-from-bottom-4">
                <div class="flex items-center justify-between gap-8 mb-6">
                    <div class="flex items-center gap-4">
                        <span class="w-10 h-px bg-primary/20"></span>
                        <h4 class="text-sm font-black text-primary uppercase tracking-[0.2em] italic">Spectral Session Identity Stream</h4>
                    </div>
                    <div class="flex gap-4">
                        <span class="px-5 py-2 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-glow-purple border border-white/10" id="pppoe-count-label-new">0 Sessions</span>
                        <span class="px-5 py-2 bg-neon-cyan text-slate-900 text-[9px] font-black uppercase tracking-widest rounded-xl shadow-glow-cyan border border-white/20" id="hotspot-count-label-new">0 Leases</span>
                    </div>
                </div>

                <div class="glass-card rounded-[2rem] overflow-hidden !bg-white/40">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                                <th class="px-10 py-6">Identity Registry</th>
                                <th class="px-10 py-6">Protocol Address</th>
                                <th class="px-10 py-6 text-center">Protocol Stack</th>
                                <th class="px-10 py-6">Temporal Pulse</th>
                            </tr>
                        </thead>
                        <tbody id="sessions-list-new" class="divide-y divide-white/20">
                            <tr>
                                <td colspan="4" class="px-10 py-32 text-center opacity-30 italic font-black uppercase text-[11px] tracking-[0.3em]">
                                    Scanning Global Infrastructure for Active Signals...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Provisioning Sequence (Config) -->
            <div x-show="activeTab === 'config'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-10 animate-in fade-in slide-in-from-bottom-4">
                <div class="flex items-center justify-between gap-8">
                    <div class="flex items-center gap-4">
                        <span class="w-10 h-px bg-secondary/20"></span>
                        <h4 class="text-sm font-black text-secondary uppercase tracking-[0.2em] italic">Provisioning Pulse Sequence</h4>
                    </div>
                    <button @click="copyScript()" class="px-8 py-3 bg-secondary text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] flex items-center gap-3 shadow-glow-cyan hover:scale-[1.05] transition-all">
                        <span class="material-symbols-outlined text-sm" x-text="scriptCopied ? 'verified' : 'terminal'"></span>
                        <span x-text="scriptCopied ? 'Sequence Synchronized' : 'Copy Registry Secret'"></span>
                    </button>
                </div>

                <div class="bg-slate-900 rounded-[2.5rem] p-2 shadow-2xl overflow-hidden border border-white/5" dir="ltr">
                    <div class="bg-slate-800/80 px-10 py-5 border-b border-white/5 flex items-center justify-between">
                        <div class="flex gap-3">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        </div>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest italic">Encrypted Secure Terminal Core v3.0</span>
                    </div>
                    <div class="p-10 max-h-[600px] overflow-y-auto custom-scrollbar-dark select-all bg-slate-900/10 backdrop-blur-3xl">
                        <pre class="text-xs font-manrope font-black text-emerald-400 leading-relaxed whitespace-pre-wrap"><code id="setup-code-new" class="block">Assembling Command Sequence Matrix for MikroTik Governance System...</code></pre>
                    </div>
                </div>
            </div>

            <!-- Tab: Recovery Snapshot Vault (Backups) -->
            <div x-show="activeTab === 'backups'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-8 animate-in fade-in slide-in-from-bottom-4">
                <div class="flex items-center gap-4 mb-6">
                    <span class="w-10 h-px bg-slate-300"></span>
                    <h4 class="text-sm font-black text-slate-500 uppercase tracking-[0.2em] italic">Infrastructure State Snapshots</h4>
                </div>
                
                @if($server->backups->count() > 0)
                    <div class="glass-card rounded-[2rem] overflow-hidden !bg-white/40">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 italic">
                                    <th class="px-10 py-6">Snapshot Identity</th>
                                    <th class="px-10 py-6">Storage Logic</th>
                                    <th class="px-10 py-6">Temporal Registry</th>
                                    <th class="px-10 py-6">Payload Density</th>
                                    <th class="px-10 py-6 text-left">Retrieval</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/20">
                                @foreach($server->backups()->latest()->take(15)->get() as $backup)
                                <tr class="group hover:bg-white/60 transition-all duration-300">
                                    <td class="px-10 py-7 font-black text-slate-900 text-[11px] uppercase italic tracking-tighter">{{ $backup->filename }}</td>
                                    <td class="px-10 py-7">
                                        <span class="px-4 py-1.5 bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest rounded-lg italic">
                                            {{ $backup->type == 'backup' ? 'Binary Binary' : 'Logical Script' }}
                                        </span>
                                    </td>
                                    <td class="px-10 py-7 font-manrope font-black text-slate-500 text-[11px] italic">{{ $backup->created_at->format('Y . m . d | H:i') }}</td>
                                    <td class="px-10 py-7 font-manrope font-black text-primary text-[11px] italic">{{ round($backup->size / 1024 / 1024, 2) }} MB</td>
                                    <td class="px-10 py-7 text-left">
                                        <a href="{{ route('servers.backups.download', $backup) }}" class="p-3 bg-white border border-slate-100 rounded-xl text-primary hover:text-white hover:bg-primary hover:shadow-glow-cyan transition-all italic">
                                            <span class="material-symbols-outlined text-lg">download</span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-32 flex flex-col items-center justify-center gap-6 glass-card rounded-[2.5rem] opacity-30 border-dashed italic">
                        <span class="material-symbols-outlined text-6xl font-light">cloud_off</span>
                        <p class="text-[11px] font-black uppercase tracking-[0.3em]">Snapshot Repository Initialization Required</p>
                    </div>
                @endif
            </div>

            <!-- Tab: Tactical Link Probes (Tools) -->
            <div x-show="activeTab === 'tools'" x-cloak x-transition:enter="duration-500 ease-out" class="grid grid-cols-1 md:grid-cols-2 gap-10 animate-in fade-in slide-in-from-bottom-4">
                <!-- Connect Verify Protocol -->
                <div class="glass-panel p-10 rounded-[2.5rem] border-white space-y-6 group hover:border-neon-cyan/50 transition-all">
                    <h5 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-3">
                        <span class="material-symbols-outlined text-neon-cyan">radar</span>
                        Tactical Handshake Probe
                    </h5>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-bold uppercase tracking-tight italic">Verify secure tunnel persistence between global center and identity node.</p>
                    <button onclick="testConnection()" class="w-full py-4 bg-slate-900 text-neon-cyan text-[10px] font-black uppercase tracking-[0.4em] rounded-2xl shadow-glow-cyan hover:scale-[1.02] transition-all italic">
                        Execute Link Persistence Test
                    </button>
                    <div id="connection-status-new" class="hidden animate-in zoom-in-95 duration-500 text-center p-4 rounded-xl bg-white border border-slate-100 font-black text-[10px] uppercase tracking-widest text-primary italic"></div>
                </div>

                <!-- Assimilation Logic -->
                <div class="p-10 bg-accent-gradient rounded-[2.5rem] space-y-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 blur-3xl rounded-full -mr-32 -mt-32"></div>
                    <h5 class="text-sm font-black text-white uppercase tracking-widest italic flex items-center gap-3 relative z-10">
                        <span class="material-symbols-outlined text-white">dynamic_feed</span>
                        Recursive Data Assimilation
                    </h5>
                    <div class="space-y-4 relative z-10">
                        <button onclick="importData('pppoe')" class="w-full py-4 bg-white/10 hover:bg-white/20 border border-white/20 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center justify-between px-8 transition-all group/btn italic">
                            <span class="tracking-[0.2em]">Import PPPoE User Matrix</span>
                            <span class="material-symbols-outlined text-sm group-hover/btn:translate-x-2 transition-transform">protocol</span>
                        </button>
                        <button onclick="importData('hotspot')" class="w-full py-4 bg-white/10 hover:bg-white/20 border border-white/20 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center justify-between px-8 transition-all group/btn italic">
                            <span class="tracking-[0.2em]">Sync Hotspot Portal Registry</span>
                            <span class="material-symbols-outlined text-sm group-hover/btn:translate-x-2 transition-transform">bolt</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@if($server->lat && $server->lng)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endif

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('serverDashboard', () => ({
        activeTab: 'general',
        scriptCopied: false,
        isConnected: {{ $server->is_connected ? 'true' : 'false' }},
        tabs: [
            { id: 'general', name: 'Identity & Topology', md_icon: 'intelligence_hub' },
            { id: 'users', name: 'Telemetry Flux', md_icon: 'monitoring' },
            { id: 'config', name: 'Provisioning Script', md_icon: 'script_box' },
            { id: 'backups', name: 'Snapshot Vault', md_icon: 'history' },
            { id: 'tools', name: 'Tactical Controls', md_icon: 'architecture' },
        ],

        init() {
            if (this.activeTab === 'general') {
                this.initMap();
                this.updateStatus();
            }
            setInterval(() => this.updateStatus(), 15000);
            this.$watch('activeTab', value => {
                if (value === 'general') setTimeout(() => this.initMap(), 100);
                if (value === 'config') this.fetchConfig();
            });
        },

        initMap() {
            @if($server->lat && $server->lng)
            if (document.getElementById('map') && !this.map) {
                this.map = L.map('map', {zoomControl: false}).setView([{{ $server->lat }}, {{ $server->lng }}], 15);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(this.map);
                L.marker([{{ $server->lat }}, {{ $server->lng }}]).addTo(this.map);
                L.control.zoom({position: 'bottomleft'}).addTo(this.map);
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
                        if(document.getElementById('version-val')) document.getElementById('version-val').textContent = info.version;
                        if(document.getElementById('uptime-val')) document.getElementById('uptime-val').textContent = info.uptime;
                        if(document.getElementById('cpu-val')) document.getElementById('cpu-val').textContent = info.cpu_load + '%';
                        if(document.getElementById('cpu-bar')) document.getElementById('cpu-bar').style.width = info.cpu_load + '%';
                        if(document.getElementById('mem-val')) document.getElementById('mem-val').textContent = Math.round(info.free_memory / 1024 / 1024) + ' / ' + Math.round(info.total_memory / 1024 / 1024);
                        if(document.getElementById('active-pppoe-val')) document.getElementById('active-pppoe-val').textContent = info.active_pppoe;
                        if(document.getElementById('active-hotspot-val')) document.getElementById('active-hotspot-val').textContent = info.active_hotspot;
                        if(document.getElementById('pppoe-count-label-new')) document.getElementById('pppoe-count-label-new').textContent = info.active_pppoe + ' Sessions';
                        if(document.getElementById('hotspot-count-label-new')) document.getElementById('hotspot-count-label-new').textContent = info.active_hotspot + ' Leases';

                        const list = document.getElementById('sessions-list-new');
                        if (list && (info.pppoe_users.length > 0 || info.hotspot_users.length > 0)) {
                            list.innerHTML = '';
                            info.pppoe_users.forEach(u => list.innerHTML += this.renderRow(u, 'PPPoE', 'primary'));
                            info.hotspot_users.forEach(u => list.innerHTML += this.renderRow(u, 'Hotspot', 'secondary'));
                        }

                        const sitesList = document.getElementById('top-sites-list-new');
                        if (sitesList && info.top_sites && info.top_sites.length > 0) {
                            document.getElementById('top-sites-card-new').classList.remove('hidden');
                            sitesList.innerHTML = '';
                            const max = info.top_sites[0].hits;
                            info.top_sites.forEach(s => {
                                sitesList.innerHTML += `
                                    <div class="space-y-4 group">
                                        <div class="flex justify-between text-[10px] font-black uppercase italic tracking-tighter">
                                            <span class="text-primary font-manrope font-black">${s.domain}</span>
                                            <span class="text-slate-400 opacity-60">${s.hits} QUERIES</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-accent-flow h-full rounded-full transition-all duration-1000" style="width: ${(s.hits/max)*100}%"></div>
                                        </div>
                                    </div>`;
                            });
                        }
                    } else {
                        this.isConnected = false;
                    }
                });
        },

        renderRow(u, type, color) {
            const variant = type === 'PPPoE' ? 'bg-slate-900 text-neon-cyan shadow-glow-cyan' : 'bg-primary text-white shadow-glow-purple';
            return `<tr class="group hover:bg-white/60 transition-all duration-300">
                <td class="px-10 py-7">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-primary text-xs uppercase italic">${u.name ? u.name.substring(0,2) : '?' }</div>
                        <span class="text-sm font-black text-slate-900 uppercase italic tracking-tighter">${u.name || u.user}</span>
                    </div>
                </td>
                <td class="px-10 py-7 font-manrope font-black text-slate-500 text-[11px] italic tracking-tighter">${u.address || '--'}</td>
                <td class="px-10 py-7 text-center">
                    <span class="px-4 py-1.5 ${variant} rounded-full text-[8px] font-black uppercase tracking-widest italic">${type} Session</span>
                </td>
                <td class="px-10 py-7 font-manrope font-black text-slate-400 text-[10px] italic">${u.uptime || '--'}</td>
            </tr>`;
        },

        fetchConfig() {
            const code = document.getElementById('setup-code-new');
            if (code && code.textContent.includes('Assembling')) {
                fetch('{{ route('servers.setup-script', $server) }}').then(r => r.json()).then(d => code.textContent = d.script);
            }
        },

        copyScript() {
            fetch('{{ route('servers.setup-script', $server) }}').then(r => r.json()).then(d => {
                navigator.clipboard.writeText(d.script);
                this.scriptCopied = true;
                setTimeout(() => this.scriptCopied = false, 3000);
            });
        }
    }));
});

function testConnection() {
    const status = document.getElementById('connection-status-new');
    status.innerHTML = 'Executing Sequence Probe Handshake...';
    status.classList.remove('hidden');
    fetch('{{ route('servers.test-connection', $server) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
    .then(r => r.json()).then(d => {
        status.innerHTML = d.success ? `✅ Handshake Pulse Synchronized Successfully` : `❌ Protocol Loss: Handshake Failed`;
        if(d.success) setTimeout(() => window.location.reload(), 1500);
    });
}

function importData(type) {
    const btn = event.currentTarget;
    const old = btn.innerHTML;
    btn.innerHTML = `Assimilation Pulse Initiated...`;
    btn.disabled = true;
    fetch(`/servers/${type}/import/{{ $server->id }}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
    .then(r => r.json()).then(d => {
        btn.innerHTML = old; btn.disabled = false;
        if(d.success) window.location.reload();
    });
}
</script>
@endsection
