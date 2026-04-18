@extends('layouts.admin')

@section('title', 'مركز قيادة العقدة | ' . $server->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-12" x-data="serverDashboard()">
    
    <!-- Command Center Header -->
    <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-8 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/5 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col lg:flex-row items-center gap-10">
            <!-- Hardware Hologram -->
            <div class="relative shrink-0">
                <div class="w-44 h-44 bg-white rounded-lg border border-outline-variant/10 p-6 flex items-center justify-center shadow-sm">
                    <img src="{{ $server->deviceModel->image_url ?? 'https://placehold.co/200x150?text=MikroTik' }}" 
                         alt="{{ $server->name }}" 
                         class="max-w-full max-h-full object-contain grayscale opacity-80 group-hover:opacity-100 transition-all">
                </div>
                <div class="absolute -bottom-2 -left-2">
                    @if($server->is_connected)
                        <div class="px-3 py-1 bg-secondary text-white text-[9px] font-black uppercase tracking-widest rounded shadow-lg flex items-center gap-1.5 border border-white/20">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                            Synchronized
                        </div>
                    @else
                        <div class="px-3 py-1 bg-error text-white text-[9px] font-black uppercase tracking-widest rounded shadow-lg flex items-center gap-1.5 border border-white/20">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                            Disconnected
                        </div>
                    @endif
                </div>
            </div>

            <!-- Identity Core -->
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6 justify-center lg:justify-start">
                    <h1 class="text-3xl font-black text-primary tracking-tighter italic uppercase">{{ $server->name }}</h1>
                    <span class="px-3 py-1 bg-primary/5 text-primary rounded text-[9px] font-black uppercase tracking-widest border border-primary/10">
                        {{ $server->deviceModel->model_name ?? 'MikroTik Infrastructure' }}
                    </span>
                </div>
                
                <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                    <div class="px-4 py-2 bg-white border border-outline-variant/20 rounded shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-slate-400">public</span>
                        <span class="font-manrope font-black text-primary text-xs">{{ $server->ip }}</span>
                    </div>
                    <div class="px-4 py-2 bg-white border border-outline-variant/20 rounded shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-slate-400">settings_input_component</span>
                        <span class="font-manrope font-black text-primary text-xs">PORT:{{ $server->api_port }}</span>
                    </div>
                    <div class="px-4 py-2 bg-white border border-outline-variant/20 rounded shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-slate-400">location_on</span>
                        <span class="font-black text-primary text-[9px] uppercase tracking-widest">{{ $server->location ?? 'Edge Node' }}</span>
                    </div>
                </div>
            </div>

            <!-- Global Actions -->
            <div class="shrink-0">
                <a href="{{ route('servers.edit', $server) }}" class="px-8 py-2.5 bg-primary text-white rounded font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary/10 hover:scale-[1.02] transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">settings_suggest</span>
                    Edit Node Config
                </a>
            </div>
        </div>
    </div>

    <!-- Live Telemetry Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" x-show="isConnected">
        <!-- CPU Load -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-6 flex flex-col justify-between h-36 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-primary/5 rounded-full blur-2xl"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-headline">Compute Load</p>
                <span class="material-symbols-outlined text-primary text-lg">processor</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-manrope font-black text-primary" id="cpu-val">0%</h3>
                <div class="w-full bg-surface-container-highest/20 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div id="cpu-bar" class="bg-primary h-full transition-all duration-1000" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- RAM State -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-6 flex flex-col justify-between h-36 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-secondary/5 rounded-full blur-2xl"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-headline">Active Memory</p>
                <span class="material-symbols-outlined text-secondary text-lg">memory</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-xl font-manrope font-black text-primary" id="mem-val">-- / --</h3>
                <p class="text-[8px] font-black text-secondary uppercase tracking-widest mt-1">Megabytes Available</p>
            </div>
        </div>

        <!-- PPPoE Matrix -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-6 flex flex-col justify-between h-36 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-primary/5 rounded-full blur-2xl"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-headline">PPPoE Pulse</p>
                <span class="material-symbols-outlined text-primary text-lg">lan</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-manrope font-black text-primary" id="active-pppoe-val">0</h3>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Tunneling Sessions</p>
            </div>
        </div>

        <!-- Hotspot Guard -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-6 flex flex-col justify-between h-36 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-secondary/5 rounded-full blur-2xl"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-headline">Hotspot Hub</p>
                <span class="material-symbols-outlined text-secondary text-lg">wifi_tethering</span>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-manrope font-black text-primary" id="active-hotspot-val">0</h3>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Portal Connections</p>
            </div>
        </div>
    </div>

    <!-- Intelligence Registry -->
    <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg overflow-hidden shadow-sm flex flex-col min-h-[600px]">
        <!-- Terminal Navigator -->
        <div class="bg-white/50 border-b border-outline-variant/10 px-4">
            <nav class="flex gap-1 overflow-x-auto no-scrollbar py-2">
                <template x-for="tab in tabs" :key="tab.id">
                    <button @click="activeTab = tab.id" 
                            class="px-6 py-3 rounded text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap"
                            :class="activeTab === tab.id ? 'bg-primary text-white' : 'text-slate-500 hover:bg-slate-100'">
                        <span class="material-symbols-outlined text-sm" x-text="tab.md_icon"></span>
                        <span x-text="tab.name"></span>
                    </button>
                </template>
            </nav>
        </div>

        <!-- Matrix Hub Content -->
        <div class="p-8 flex-1">
            <!-- Tab: General Diagnostics -->
            <div x-show="activeTab === 'general'" x-transition:enter="duration-500 ease-out" class="space-y-12">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <!-- Left: Hardware Spec -->
                    <div class="lg:col-span-12 space-y-8">
                        <h4 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-2">
                            <span class="w-1 h-4 bg-primary rounded-full"></span>
                            Hardware Intelligence Node
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="p-5 bg-surface-container-lowest border border-outline-variant/5 rounded">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Software Revision</p>
                                <p class="font-manrope font-black text-primary text-xs" id="version-val">--</p>
                            </div>
                            <div class="p-5 bg-surface-container-lowest border border-outline-variant/5 rounded">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Uptime</p>
                                <p class="font-manrope font-black text-primary text-xs" id="uptime-val">--</p>
                            </div>
                            <div class="p-5 bg-surface-container-lowest border border-outline-variant/5 rounded">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Governance Registry</p>
                                <p class="font-black text-primary text-[10px] uppercase truncate">{{ $server->username }}</p>
                            </div>
                            <div class="p-5 bg-surface-container-lowest border border-outline-variant/5 rounded">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Last Sync Heartbeat</p>
                                <p class="font-black text-secondary text-[10px] uppercase">
                                    {{ $server->last_sync_at ? $server->last_sync_at->diffForHumans() : 'Standby Mode' }}
                                </p>
                            </div>
                        </div>

                        <!-- DNS Heatmap (Hidden unless populated) -->
                        <div id="top-sites-card-new" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-700 pt-8 border-t border-outline-variant/10">
                            <h4 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-2 mb-6">
                                <span class="w-1 h-4 bg-secondary rounded-full"></span>
                                DNS Resolution Spectrum
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6" id="top-sites-list-new">
                                <!-- Populated via JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Map Overlay -->
                    <div class="lg:col-span-12 space-y-6 pt-8 border-t border-outline-variant/10">
                        <h4 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">map</span>
                            Geospatial Topology Point
                        </h4>
                        <div class="aspect-video w-full rounded border border-outline-variant/10 overflow-hidden relative grayscale-[0.2]">
                            @if($server->lat && $server->lng)
                                <div id="map" class="w-full h-full z-0"></div>
                            @else
                                <div class="w-full h-full bg-surface-container-lowest flex flex-col items-center justify-center text-slate-300 gap-4 uppercase italic">
                                    <span class="material-symbols-outlined text-4xl">location_off</span>
                                    <p class="text-[10px] font-black tracking-widest">Geolocation Matrix Offline</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Session Stream -->
            <div x-show="activeTab === 'users'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-8">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-black text-primary uppercase tracking-widest italic">Live Intelligence Flow</h4>
                    <div class="flex gap-3">
                        <span class="px-3 py-1 bg-primary text-white text-[9px] font-black uppercase tracking-widest rounded" id="pppoe-count-label-new">0 PPPoE</span>
                        <span class="px-3 py-1 bg-secondary text-white text-[9px] font-black uppercase tracking-widest rounded" id="hotspot-count-label-new">0 Hotspot</span>
                    </div>
                </div>

                <div class="bg-surface-container-lowest rounded border border-outline-variant/10 overflow-hidden">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="bg-primary/5 text-primary">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Identity</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Interface IP</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Protocol</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Active Pulse</th>
                            </tr>
                        </thead>
                        <tbody id="sessions-list-new" class="divide-y divide-outline-variant/5">
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center opacity-30 italic font-black uppercase text-[10px]">
                                    Scanning Network for Active Signals...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Command Protocol (Config) -->
            <div x-show="activeTab === 'config'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-8">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-black text-primary uppercase tracking-widest italic">Node Boot Protocol</h4>
                    <button @click="copyScript()" class="px-6 py-2 bg-primary text-white rounded text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm" x-text="scriptCopied ? 'done_all' : 'content_copy'"></span>
                        <span x-text="scriptCopied ? 'Stack Copied' : 'Copy Sequence'"></span>
                    </button>
                </div>

                <div class="bg-slate-900 rounded p-1 shadow-2xl overflow-hidden" dir="ltr">
                    <div class="bg-slate-800/50 px-6 py-3 border-b border-white/5 flex items-center justify-between">
                        <div class="flex gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500/50"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500/50"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/50"></span>
                        </div>
                        <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest italic">Provisioning Terminal</span>
                    </div>
                    <div class="p-8 max-h-[500px] overflow-y-auto custom-scrollbar-dark select-all">
                        <pre class="text-xs font-manrope font-black text-indigo-400 leading-relaxed whitespace-pre-wrap"><code id="setup-code-new" class="block">Assembling Command Sequence Matrix...</code></pre>
                    </div>
                </div>
            </div>

            <!-- Tab: Recovery Vault (Backups) -->
            <div x-show="activeTab === 'backups'" x-cloak x-transition:enter="duration-500 ease-out" class="space-y-8">
                <h4 class="text-sm font-black text-primary uppercase tracking-widest italic">Infrastructure Restoration Logs</h4>
                
                @if($server->backups->count() > 0)
                    <div class="bg-surface-container-lowest rounded border border-outline-variant/10 overflow-hidden">
                        <table class="w-full text-right">
                            <thead>
                                <tr class="bg-secondary/5 text-secondary">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Snapshot Hash</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Method</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Timestamp</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Payload</th>
                                    <th class="px-6 py-4 text-center text-[9px] font-black uppercase tracking-widest">Retrieval</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/5">
                                @foreach($server->backups()->latest()->take(10)->get() as $backup)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-black text-primary text-xs">{{ $backup->filename }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 bg-surface-container-low text-primary text-[8px] font-black uppercase border border-outline-variant/10 rounded">
                                            {{ $backup->type == 'backup' ? 'Binary' : 'Script' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-manrope font-black text-primary text-[10px]">{{ $backup->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 font-manrope font-black text-secondary text-[10px]">{{ round($backup->size / 1024 / 1024, 2) }} MB</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('servers.backups.download', $backup) }}" class="p-2 text-primary hover:bg-primary/5 rounded inline-block transition-all shadow-sm">
                                            <span class="material-symbols-outlined text-lg">download</span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-20 bg-surface-container-lowest border border-dashed border-outline-variant/20 rounded-lg text-center opacity-40">
                        <span class="material-symbols-outlined text-4xl mb-4">cloud_off</span>
                        <p class="text-[10px] font-black uppercase tracking-widest italic">Registry Snapshots Unavailable</p>
                    </div>
                @endif
            </div>

            <!-- Tab: Tactical Tools -->
            <div x-show="activeTab === 'tools'" x-cloak x-transition:enter="duration-500 ease-out" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Connect Verify -->
                <div class="p-8 bg-surface-container-lowest border border-outline-variant/10 rounded-lg space-y-4">
                    <h5 class="text-xs font-black text-primary uppercase tracking-widest italic">Handshake Probe</h5>
                    <p class="text-[10px] text-slate-500 leading-relaxed font-medium">Verify tunnel status between center and node.</p>
                    <button onclick="testConnection()" class="w-full py-3 bg-secondary text-white text-[10px] font-black uppercase tracking-[0.1em] rounded shadow-lg shadow-secondary/10">
                        Execute Link Probe
                    </button>
                    <div id="connection-status-new" class="hidden animate-in zoom-in-95 duration-300 text-center p-3 rounded bg-slate-50 border border-outline-variant/5 font-black text-[9px] uppercase tracking-widest text-primary"></div>
                </div>

                <!-- Data Mirroring -->
                <div class="p-8 bg-primary text-white rounded-lg space-y-6 relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                    <h5 class="text-xs font-black uppercase tracking-widest italic relative z-10">Assimilation Phase</h5>
                    <div class="space-y-3 relative z-10">
                        <button onclick="importData('pppoe')" class="w-full py-2.5 bg-white/10 hover:bg-white/20 border border-white/10 rounded text-[9px] font-black uppercase tracking-widest flex items-center justify-between px-4 transition-all">
                            <span>Import PPPoE Node Matrix</span>
                            <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </button>
                        <button onclick="importData('hotspot')" class="w-full py-2.5 bg-white/10 hover:bg-white/20 border border-white/10 rounded text-[9px] font-black uppercase tracking-widest flex items-center justify-between px-4 transition-all">
                            <span>Mirror Hotspot Stack</span>
                            <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .custom-scrollbar-dark::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar-dark::-webkit-scrollbar-track { background: #0f172a; }
    .custom-scrollbar-dark::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }

    [x-cloak] { display: none !important; }
</style>

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
            { id: 'general', name: 'الهيكل والتشخيص', md_icon: 'analytics' },
            { id: 'users', name: 'الجلسات والتدفق', md_icon: 'monitoring' },
            { id: 'tools', name: 'تكتيكات الربط', md_icon: 'architecture' },
            { id: 'config', name: 'بروتوكول البدء', md_icon: 'script_box' },
            { id: 'backups', name: 'خزنة الاستعادة', md_icon: 'history' },
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
                this.map = L.map('map').setView([{{ $server->lat }}, {{ $server->lng }}], 14);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(this.map);
                L.marker([{{ $server->lat }}, {{ $server->lng }}]).addTo(this.map);
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
                        if(document.getElementById('pppoe-count-label-new')) document.getElementById('pppoe-count-label-new').textContent = info.active_pppoe + ' PPPoE';
                        if(document.getElementById('hotspot-count-label-new')) document.getElementById('hotspot-count-label-new').textContent = info.active_hotspot + ' Hotspot';

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
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between text-[10px] font-black">
                                            <span class="text-primary font-manrope">${s.domain}</span>
                                            <span class="text-secondary">${s.hits} QUERY</span>
                                        </div>
                                        <div class="w-full bg-surface-container-highest/10 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: ${(s.hits/max)*100}%"></div>
                                        </div>
                                    </div>`;
                            });
                        }
                    }
                });
        },

        renderRow(u, type, color) {
            return `<tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-3 font-black text-primary text-[10px] uppercase truncate">${u.name || u.user}</td>
                <td class="px-6 py-3 font-manrope font-black text-slate-500 text-[10px]">${u.address || '--'}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-0.5 bg-${color}/10 text-${color} rounded text-[8px] font-black uppercase border border-${color}/10">${type}</span>
                </td>
                <td class="px-6 py-3 font-manrope font-black text-slate-500 text-[9px]">${u.uptime || '--'}</td>
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
    status.innerHTML = 'Executing Sequence Probe...';
    status.classList.remove('hidden');
    fetch('{{ route('servers.test-connection', $server) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
    .then(r => r.json()).then(d => {
        status.innerHTML = d.success ? `✅ Handshake Synchronized` : `❌ Protocol Loss`;
        if(d.success) setTimeout(() => window.location.reload(), 1500);
    });
}

function importData(type) {
    const btn = event.currentTarget;
    const old = btn.innerHTML;
    btn.innerHTML = 'Assimilation in Progress...';
    btn.disabled = true;
    fetch(`/servers/${type}/import/{{ $server->id }}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
    .then(r => r.json()).then(d => {
        btn.innerHTML = old; btn.disabled = false;
        alert(d.message); if(d.success) window.location.reload();
    });
}
</script>
@endsection
