@extends('layouts.admin')

@section('title', 'مركز المراقبة الحية | Live Telemetry NOC')

@section('content')
<div class="space-y-12" x-data="liveMonitoring()">
    <!-- Radiant NOC Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-10 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Telemetry NOC Layer</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">مركز المراقبة الحية</h2>
            <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] font-headline opacity-80">Real-Time Spectral Analysis of Infrastructure Assets</p>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="glass-card px-6 py-3 rounded-2xl flex items-center gap-4">
                <div class="w-3 h-3 bg-neon-cyan rounded-full animate-pulse shadow-glow-cyan"></div>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Active Polling: 15s</span>
            </div>
            <button @click="refreshTelemetry()" class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-glow-purple group">
                <span class="material-symbols-outlined group-hover:rotate-180 transition-transform duration-500">sync</span>
            </button>
        </div>
    </div>

    <!-- Node Activity Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="node in nodes" :key="node.id">
            <div class="glass-card p-8 rounded-3xl border-l-4 group relative overflow-hidden transition-all duration-300 hover:bg-white/60"
                 :class="node.status === 'online' ? 'border-neon-cyan' : 'border-slate-300 opacity-60'">
                
                <!-- Atmosphere Grid -->
                <div class="absolute top-0 right-0 w-16 h-16 bg-accent-gradient opacity-0 group-hover:opacity-10 blur-2xl transition-opacity"></div>

                <div class="flex justify-between items-start mb-8 relative z-10">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 italic tracking-tight uppercase leading-none" x-text="node.name"></h3>
                        <p class="text-[9px] font-manrope font-black text-slate-400 uppercase tracking-[0.2em] mt-2 italic" x-text="node.ip"></p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-primary" x-text="node.type === 'master' ? 'hub' : 'router'"></span>
                    </div>
                </div>

                <!-- Resource Performance Gauges -->
                <div class="space-y-6 relative z-10">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-[9px] font-black uppercase tracking-widest px-1">
                            <span class="text-slate-400">System Load</span>
                            <span class="text-primary italic" x-text="node.cpu + '%'"></span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-accent-flow transition-all duration-1000" :style="{ width: node.cpu + '%' }"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-[9px] font-black uppercase tracking-widest px-1">
                            <span class="text-slate-400">Memory Cluster</span>
                            <span class="text-primary italic" x-text="node.ram + 'MB'"></span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-accent-flow transition-all duration-1000" :style="{ width: node.ram_percentage + '%' }"></div>
                        </div>
                    </div>
                </div>

                <!-- Telemetry Action Shield -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full" :class="node.status === 'online' ? 'bg-secondary animate-pulse' : 'bg-slate-300'"></span>
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic" x-text="node.status === 'online' ? 'Active Handshake' : 'Offline State'"></span>
                    </div>
                    <a :href="'/servers/' + node.id" class="text-[9px] font-black text-primary hover:text-secondary uppercase tracking-[0.2em] italic underline underline-offset-4">
                        Telemetry Details
                    </a>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State / Initialization -->
    <template x-if="nodes.length === 0">
        <div class="py-32 flex flex-col items-center justify-center gap-6 glass-card rounded-3xl opacity-40">
            <span class="material-symbols-outlined text-6xl font-light">satellite_alt</span>
            <p class="text-[11px] font-black uppercase tracking-[0.3em] italic">Initializing Spectral Scan: No Assets Detected</p>
        </div>
    </template>
</div>

<script>
function liveMonitoring() {
    return {
        nodes: @json($monitoring_data ?? []),
        
        async refreshTelemetry() {
            // High-intensity polling simulated for now
            // In a real app, this would fetch from a dedicated API endpoint
            console.log('Initiating High-Intensity Telemetry Scan...');
            location.reload(); 
        },

        init() {
            console.log('Telemetry NOC Operational');
        }
    }
}
</script>
@endsection
