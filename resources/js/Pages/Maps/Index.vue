<script setup>
import { Head } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Globe, 
    TowerControl as Tower, 
    Users, 
    Cpu, 
    Zap, 
    Activity, 
    Layers, 
    Maximize, 
    Minimize,
    Navigation,
    Search,
    Filter,
    Table,
    Layout,
    SignalHigh,
    Wifi
} from 'lucide-vue-next';
import { ref, onMounted } from 'vue';

const props = defineProps({
    routers: Array,
    clients: Array,
    towers: Array
});

const isFullscreen = ref(false);
const activeFilter = ref('all');

</script>

<template>
    <AppleLayout title="Geospatial Intelligence">
        <Head title="Infrastructure Topology Map" />

        <div class="max-w-[1600px] mx-auto pb-20">
            <!-- Strategic Header Overlay (Hidden on Fullscreen if needed) -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
                <div>
                     <h1 class="text-3xl font-bold tracking-tight mb-2">Geospatial Intelligence</h1>
                     <p class="text-[var(--app-secondary)] font-medium flex items-center gap-3">
                        <Navigation class="w-4 h-4 text-indigo-500" />
                        Mapping physical infrastructure deployment and radio segments
                     </p>
                </div>
                <div class="flex items-center gap-4">
                     <div class="flex bg-black/[0.03] p-1.5 rounded-2xl">
                          <button @click="activeFilter = 'all'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeFilter === 'all' ? 'bg-white text-black shadow-lg text-[11px]' : 'text-[#86868b]'">All Layers</button>
                          <button @click="activeFilter = 'towers'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeFilter === 'towers' ? 'bg-white text-black shadow-lg text-[11px]' : 'text-[#86868b]'">Sites</button>
                          <button @click="activeFilter = 'clients'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeFilter === 'clients' ? 'bg-white text-black shadow-lg text-[11px]' : 'text-[#86868b]'">Stations</button>
                     </div>
                </div>
            </div>

            <!-- Strategic Map Artifact -->
            <div class="relative apple-card overflow-hidden bg-black/[0.05] border-transparent transition-all group/map" :class="isFullscreen ? 'fixed inset-4 z-[99] rounded-[3rem]' : 'h-[700px]'">
                 
                 <!-- Simulated Map Placeholder with Strategic Graphics -->
                 <div class="absolute inset-0 bg-[#f5f5f7] flex items-center justify-center">
                      <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(circle at 1px 1px, black 1px, transparent 0); background-size: 40px 40px;"></div>
                      
                      <!-- Visual Matrix (If Leaflet is not loaded, show beautiful representation) -->
                      <div class="relative flex flex-col items-center gap-8 opacity-20 group-hover/map:opacity-30 transition-all duration-1000">
                           <Globe class="w-24 h-24 stroke-[1px] animate-pulse" />
                           <h3 class="text-sm font-black uppercase tracking-[0.5em]">Establishing Geospatial Handshake...</h3>
                      </div>

                      <!-- UI Overlay - Map Controls -->
                      <div class="absolute top-8 left-8 flex flex-col gap-4">
                           <button class="w-14 h-14 apple-card bg-white/90 backdrop-blur-xl flex items-center justify-center text-black shadow-xl hover:scale-110 active:scale-95 transition-all">
                               <Layers class="w-6 h-6" />
                           </button>
                           <button @click="isFullscreen = !isFullscreen" class="w-14 h-14 apple-card bg-white/90 backdrop-blur-xl flex items-center justify-center text-black shadow-xl hover:scale-110 active:scale-95 transition-all">
                               <component :is="isFullscreen ? Minimize : Maximize" class="w-6 h-6" />
                           </button>
                      </div>

                      <!-- UI Overlay - Stats (NOC Style) -->
                      <div class="absolute bottom-8 left-8 right-8 flex items-center justify-between pointer-events-none">
                           <div class="flex gap-4 pointer-events-auto">
                                <div class="apple-card bg-black/80 backdrop-blur-3xl text-white p-6 flex flex-col gap-1 pr-12 border-white/5">
                                    <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">Global Sites</p>
                                    <h4 class="text-2xl font-bold tracking-tight">{{ towers.length }}</h4>
                                </div>
                                <div class="apple-card bg-black/80 backdrop-blur-3xl text-white p-6 flex flex-col gap-1 pr-12 border-white/5">
                                    <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">Active Stations</p>
                                    <h4 class="text-2xl font-bold tracking-tight">{{ clients.length }}</h4>
                                </div>
                                <div class="apple-card bg-black/80 backdrop-blur-3xl text-white p-6 flex flex-col gap-1 pr-12 border-white/5">
                                    <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">Radio Nodes</p>
                                    <h4 class="text-2xl font-bold tracking-tight">{{ routers.length }}</h4>
                                </div>
                           </div>
                           
                           <div class="apple-card bg-white/90 backdrop-blur-3xl p-6 flex items-center gap-6 border-black/5 pointer-events-auto shadow-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Operational</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Towers</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Fault State</span>
                                </div>
                           </div>
                      </div>

                      <!-- Legend / Floating Identity (Placeholder for clicked marker) -->
                      <div class="absolute top-8 right-8 w-80 apple-card bg-white/90 backdrop-blur-3xl p-8 border-black/5 shadow-2xl animate-in slide-in-from-right duration-500">
                           <h3 class="text-xs font-black uppercase tracking-widest mb-6 flex items-center gap-2">
                                <Activity class="w-4 h-4 text-emerald-500" /> Node Telemetry
                           </h3>
                           <div class="flex flex-col items-center text-center py-8 opacity-20 grayscale">
                                <Wifi class="w-16 h-16 mb-4" />
                                <p class="text-[10px] font-black uppercase tracking-widest">No Node Projected</p>
                           </div>
                           <div class="mt-4 pt-8 border-t border-black/[0.03]">
                                <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">NOC Instructions</p>
                                <p class="text-[11px] font-medium leading-relaxed mt-2">Select a physical deployment node to initialize telemetry extraction.</p>
                           </div>
                      </div>
                 </div>

                 <!-- Overlay Instructions -->
                 <div class="absolute inset-0 pointer-events-none group-hover/map:bg-black/5 transition-all duration-700"></div>
            </div>

            <!-- Intelligence Analytics Below Map -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
                 <div class="apple-card p-8 bg-white border border-black/5 flex items-center gap-6 group hover:border-black/10 transition-all">
                      <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center transition-all group-hover:scale-110">
                           <SignalHigh class="w-8 h-8" />
                      </div>
                      <div>
                           <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Density Intelligence</p>
                           <h4 class="text-xl font-bold tracking-tight">High Aggregation</h4>
                      </div>
                 </div>
                 <div class="apple-card p-8 bg-white border border-black/5 flex items-center gap-6 group hover:border-black/10 transition-all">
                      <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center transition-all group-hover:scale-110">
                           <Zap class="w-8 h-8" />
                      </div>
                      <div>
                           <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Grid Operationality</p>
                           <h4 class="text-xl font-bold tracking-tight">99.98% Protocol Sync</h4>
                      </div>
                 </div>
                 <div class="apple-card p-8 bg-black text-white flex items-center justify-between border-transparent group hover:scale-[1.02] transition-all overflow-hidden relative">
                      <div class="absolute -right-8 -top-8 w-24 h-24 bg-white/5 rounded-full blur-3xl"></div>
                      <div class="flex items-center gap-6 relative z-10">
                           <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center">
                                <Users class="w-8 h-8" />
                           </div>
                           <div>
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Subscribers Coverage</p>
                                <h4 class="text-xl font-bold tracking-tight">{{ clients.length }} Stations Registered</h4>
                           </div>
                      </div>
                      <ArrowRight class="w-6 h-6 text-white/20 group-hover:text-white transition-all transform group-hover:translate-x-2" />
                 </div>
            </div>
        </div>
    </AppleLayout>
</template>

<style scoped>
/* Scoped styles for map container if needed */
</style>
