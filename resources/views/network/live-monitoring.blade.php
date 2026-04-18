@extends('layouts.admin')

@section('title', 'المراقبة الحية للشبكة | Live Telemetry')

@section('content')
<div class="space-y-8">
    <!-- Sovereign Monitoring Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight italic uppercase">مركز المراقبة الحية</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">Real-time Infrastructure Telemetry & Node Synchronization</p>
        </div>
        
        <div class="flex items-center gap-3 px-6 py-2.5 bg-secondary/10 text-secondary rounded border border-secondary/20 shadow-sm animate-pulse">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
            </span>
            <span class="text-[10px] font-black uppercase tracking-[0.2em] italic">Telemetry Stream Active</span>
        </div>
    </div>

    <!-- Operation Connectivity Status -->
    <div id="status-summary" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4 animate-in fade-in duration-700">
        <!-- Summary metrics will be injected here if needed, otherwise skip -->
    </div>

    <!-- Node Operational Matrix -->
    <div id="live-stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Initial Loading Protocol -->
        <div class="col-span-full py-32 flex flex-col items-center justify-center text-slate-400 gap-6 bg-surface-container-low border border-dashed border-outline-variant/20 rounded-lg opacity-60">
            <div class="w-16 h-16 border-2 border-outline-variant/10 border-t-primary rounded-full animate-spin"></div>
            <div class="text-center">
                <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em] italic mb-1">Initializing Handshake</h3>
                <p class="text-[9px] font-bold uppercase tracking-widest">Connecting to Global Node Matrix...</p>
            </div>
        </div>
    </div>
</div>

<!-- Extra Styles for Live Pulse -->
<style>
    .node-card-active { border-left-width: 4px !important; border-left-color: var(--md-sys-color-secondary) !important; }
    .node-card-stress { border-left-width: 4px !important; border-left-color: var(--md-sys-color-error) !important; }
</style>

<script>
function loadStats() {
    fetch('{{ route("api.network.realtime") }}')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('live-stats');
            
            if(data.length === 0) {
                 container.innerHTML = `
                    <div class="col-span-full py-20 text-center bg-surface-container-low rounded-lg border border-dashed border-outline-variant/20 italic">
                        <span class="material-symbols-outlined text-4xl text-slate-200 mb-4 font-light">router</span>
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">Registry Empty</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">No operational nodes detected in the sovereign registry</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            
            data.forEach(router => {
                const isStressed = router.cpu_load > 80;
                const statusClass = isStressed ? 'node-card-stress shadow-error/10' : 'node-card-active shadow-primary/5';
                const cpuBarColor = isStressed ? 'bg-error' : 'bg-primary';
                
                const card = `
                    <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-6 shadow-xl transition-all duration-500 hover:scale-[1.02] ${statusClass} group overflow-hidden relative">
                        <!-- Technical Identity -->
                        <div class="flex items-start justify-between mb-8 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded bg-white border border-outline-variant/10 flex items-center justify-center text-primary shadow-sm">
                                    <span class="material-symbols-outlined text-2xl font-light">dns</span>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-black text-primary tracking-tight truncate uppercase italic">${router.router_name}</h3>
                                    <p class="text-[10px] font-manrope font-black text-slate-400 mt-1 uppercase tracking-tighter">${router.ip_address || '0.0.0.0'}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Real-time Metrics -->
                        <div class="space-y-6 relative z-10">
                            <!-- CPU Telemetry -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-end">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">CPU LOAD</p>
                                    <span class="text-[10px] font-manrope font-black ${isStressed ? 'text-error' : 'text-primary'}">${router.cpu_load}%</span>
                                </div>
                                <div class="w-full bg-surface-container-highest/20 h-1.5 rounded-full overflow-hidden">
                                     <div class="${cpuBarColor} h-full transition-all duration-1000 ease-out" style="width: ${router.cpu_load}%"></div>
                                </div>
                            </div>

                            <!-- RAM Telemetry -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-end">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">RAM UTILIZATION</p>
                                    <span class="text-[10px] font-manrope font-black text-primary">${router.memory_usage}%</span>
                                </div>
                                <div class="w-full bg-surface-container-highest/20 h-1.5 rounded-full overflow-hidden">
                                     <div class="bg-secondary h-full transition-all duration-1000 ease-out" style="width: ${router.memory_usage}%"></div>
                                </div>
                            </div>

                            <!-- Sub-Grid Registry -->
                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <div class="bg-white/40 p-3 rounded border border-outline-variant/10 text-center">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Active Tunnel</p>
                                    <p class="text-sm font-manrope font-black text-primary">${router.active_sessions}</p>
                                </div>
                                <div class="bg-white/40 p-3 rounded border border-outline-variant/10 text-center">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Uptime Prot</p>
                                    <p class="text-[9px] font-manrope font-black text-slate-600 truncate">${router.uptime}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Background Aesthetic -->
                        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-primary/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                `;
                container.innerHTML += card;
            });
        })
        .catch(error => {
            console.error('Telemetry Retrieval Failed:', error);
        });
}

// Global Synchronization Protocol
loadStats();
setInterval(loadStats, 5000);
</script>
@endsection
