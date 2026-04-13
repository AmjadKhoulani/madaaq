<script setup>
import { Head } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    GitGraph, 
    Globe, 
    Cpu, 
    TowerControl as Tower, 
    HardDrive, 
    Zap, 
    Activity, 
    CheckCircle2, 
    ChevronRight, 
    ArrowRight,
    Search,
    Filter,
    Table,
    Layers,
    SignalLow,
    SignalMedium,
    SignalHigh as Signal
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
    routers: Array,
    towers: Array,
    servers: Array,
    internetSources: Array
});

const getStatusColor = (status) => {
    switch (status) {
        case 'online': return 'text-emerald-500 bg-emerald-500/10';
        case 'offline': return 'text-rose-500 bg-rose-500/10';
        default: return 'text-amber-500 bg-amber-500/10';
    }
};

</script>

<template>
    <AppleLayout title="Topology Intelligence">
        <Head title="Logical Infrastructure Hierarchy" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
                <div>
                     <h1 class="text-3xl font-bold tracking-tight mb-2">Topology Intelligence</h1>
                     <p class="text-[var(--app-secondary)] font-medium flex items-center gap-3">
                        <Signal class="w-4 h-4 text-indigo-500" />
                        Synchronizing multi-tier logical extraction paths
                     </p>
                </div>
            </div>

            <!-- Multi-Tier Topology Matrix -->
            <div class="space-y-12 pb-24 relative">
                
                <!-- Connection Guiding Lines (Background Artifacts) -->
                <div class="absolute left-1/2 top-40 bottom-0 w-px bg-black/[0.03] -translate-x-1/2 hidden lg:block"></div>

                <!-- Tier 1: Upstream Extraction Sources -->
                <section class="relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="px-4 py-1.5 bg-black text-white rounded-full text-[9px] font-black uppercase tracking-widest">Tier 01: Core Ingress</div>
                        <div class="flex-1 h-px bg-black/[0.05]"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div v-for="source in internetSources" :key="source.id" class="apple-card p-6 bg-white border border-black/5 hover:border-black/10 transition-all group">
                             <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-inner">
                                    <Globe class="w-6 h-6" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold truncate uppercase">{{ source.name }}</h4>
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">{{ source.type }} Extraction</p>
                                </div>
                             </div>
                             <div class="flex items-center justify-between pt-4 border-t border-black/[0.03]">
                                <span class="text-xs font-mono font-bold text-black/40">{{ source.capacity }} Mbps</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full shadow-[0_0_5px_rgba(16,185,129,0.5)] animate-pulse" :class="source.status === 'online' ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                                    <span class="text-[8px] font-black uppercase tracking-widest text-[#86868b]">{{ source.status }}</span>
                                </div>
                             </div>
                        </div>
                    </div>
                </section>

                <div class="flex justify-center py-4 text-black/10"><ArrowRight class="w-10 h-10 rotate-90" /></div>

                <!-- Tier 2: Edge Controllers (Hubs) -->
                <section class="relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="px-4 py-1.5 bg-black text-white rounded-full text-[9px] font-black uppercase tracking-widest">Tier 02: Edge Extraction Hubs</div>
                        <div class="flex-1 h-px bg-black/[0.05]"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="server in servers" :key="server.id" class="apple-card p-10 bg-white border border-black/5 hover:border-black/10 transition-all group relative overflow-hidden">
                             <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/[0.03] rounded-full blur-3xl"></div>
                             <div class="flex items-center gap-5 mb-8">
                                <div class="w-16 h-16 rounded-[2.5rem] bg-black text-white flex items-center justify-center transition-all group-hover:rotate-3">
                                    <Cpu class="w-8 h-8" />
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold tracking-tight uppercase">{{ server.name }}</h4>
                                    <p class="text-[10px] font-mono font-bold text-[#86868b]">{{ server.ip }}</p>
                                </div>
                             </div>
                             <div class="p-4 bg-black/[0.02] rounded-2xl border border-black/5 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <Activity class="w-4 h-4 text-emerald-500" />
                                    <span class="text-[10px] font-black uppercase tracking-widest">Operational Protocol</span>
                                </div>
                                <div class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest" :class="getStatusColor(server.status)">
                                    {{ server.status }}
                                </div>
                             </div>
                        </div>
                    </div>
                </section>

                <div class="flex justify-center py-4 text-black/10"><ArrowRight class="w-10 h-10 rotate-90" /></div>

                <!-- Tier 3: Physical Dissemination (Towers/Routers) -->
                <section class="relative z-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="px-4 py-1.5 bg-black text-white rounded-full text-[9px] font-black uppercase tracking-widest">Tier 03: Physical Dissemination Nodes</div>
                        <div class="flex-1 h-px bg-black/[0.05]"></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                         <!-- Towers (Branch Origins) -->
                         <div class="space-y-6">
                            <h5 class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-4">Deployment Clusters (Sites)</h5>
                            <div class="space-y-4">
                                <div v-for="tower in towers" :key="tower.id" class="apple-card p-6 border border-black/5 flex items-center justify-between hover:bg-black/[0.01] transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-black text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                            <Tower class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <h6 class="text-sm font-bold uppercase tracking-tight">{{ tower.name }}</h6>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="w-1 h-1 bg-emerald-500 rounded-full animate-ping"></span>
                                                <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">Transmitting Active Signal</span>
                                            </div>
                                        </div>
                                    </div>
                                    <ArrowRight class="w-5 h-5 text-black/10 group-hover:translate-x-1 group-hover:text-black transition-all" />
                                </div>
                            </div>
                         </div>

                         <!-- Routers (Endpoint Access) -->
                         <div class="space-y-6">
                            <h5 class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-4">Access Handshake Nodes (Radios)</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="router in routers" :key="router.id" class="apple-card p-5 border border-black/5 bg-white/50 backdrop-blur-3xl flex flex-col gap-4 group">
                                     <div class="flex items-center justify-between">
                                          <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform">
                                               <Zap class="w-5 h-5" />
                                          </div>
                                          <div class="text-right">
                                               <p class="text-[8px] font-black text-black uppercase tracking-widest">{{ router.status }}</p>
                                               <p class="text-[7px] font-bold text-[#86868b] uppercase tracking-widest mt-0.5">Protocol State</p>
                                          </div>
                                     </div>
                                     <div>
                                         <h6 class="text-xs font-bold uppercase tracking-tight truncate">{{ router.name }}</h6>
                                         <p class="text-[9px] font-mono text-black/30 mt-1">{{ router.ip }}</p>
                                     </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>
        </div>
    </AppleLayout>
</template>

<style scoped>
/* Scoped styles for topology paths if needed */
</style>
