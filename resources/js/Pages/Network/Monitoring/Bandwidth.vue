<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    Signal, 
    ArrowUpCircle, 
    ArrowDownCircle, 
    Calendar, 
    Activity, 
    Globe, 
    Zap,
    Download,
    Upload,
    MousePointer2
} from 'lucide-vue-next';
import { pickBy } from 'lodash';

const props = defineProps({
    chartData: Object,
    period: String,
    availableInterfaces: Array,
    interface: String,
});

const filters = ref({
    period: props.period || '24h',
    interface: props.interface || ''
});

watch(filters, (newVal) => {
    router.get(route('network.monitoring.bandwidth'), pickBy(newVal), {
        preserveState: true,
        replace: true
    });
}, { deep: true });

// Convert Object data to arrays for custom Chart component or SVG implementation
const categories = Object.keys(props.chartData);
const rxData = Object.values(props.chartData).map(v => v.rx);
const txData = Object.values(props.chartData).map(v => v.tx);

const maxVal = Math.max(...rxData, ...txData, 1);

const stats = {
    total_rx: rxData.reduce((a, b) => a + b, 0),
    total_tx: txData.reduce((a, b) => a + b, 0),
    peak_rx: Math.max(...rxData),
    peak_tx: Math.max(...txData),
};

</script>

<template>
    <AppleLayout title="Bandwidth Analysis">
        <Head title="Grid Throughput Intelligence" />

        <div class="max-w-[1400px] mx-auto pb-24">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('network.monitoring.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Throughput Intelligence</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Analyzing extraction velocity across edge interfaces</p>
                    </div>
                </div>
            </div>

            <!-- Strategic Filtering -->
            <div class="apple-card p-8 mb-12 bg-black text-white relative overflow-hidden">
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-1 space-y-4">
                        <label class="text-[9px] font-black text-white/40 uppercase tracking-widest ml-2">Temporal Horizon</label>
                        <div class="flex bg-white/10 p-1.5 rounded-2xl w-fit">
                             <button @click="filters.period = '24h'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.period === '24h' ? 'bg-white text-black shadow-lg' : 'text-white/60'">Last 24h</button>
                             <button @click="filters.period = '7d'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.period === '7d' ? 'bg-white text-black shadow-lg' : 'text-white/60'">Last 7 Days</button>
                             <button @click="filters.period = '30d'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.period === '30d' ? 'bg-white text-black shadow-lg' : 'text-white/60'">Last 30 Days</button>
                        </div>
                    </div>
                    <div class="flex-1 space-y-4 w-full md:w-auto">
                        <label class="text-[9px] font-black text-white/40 uppercase tracking-widest ml-2">Interface Extraction Node</label>
                        <select v-model="filters.interface" class="w-full bg-white/10 border-transparent text-white rounded-2xl h-14 px-6 font-bold text-sm focus:ring-2 focus:ring-white/20 transition-all outline-none">
                            <option value="">All Aggregate Traffic</option>
                            <option v-for="int in availableInterfaces" :key="int" :value="int" class="text-black">{{ int }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Intelligence Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Data Visualizer -->
                <div class="lg:col-span-3 apple-card p-10 flex flex-col min-h-[500px]">
                    <div class="flex items-center justify-between mb-12">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Velocity Pulse (Throughput MB/s)</h3>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Downlink (RX)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-indigo-400"></div>
                                <span class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Uplink (TX)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Custom SVG Velocity Chart -->
                    <div class="flex-1 relative mt-10 group/chart">
                         <!-- Grid Lines -->
                         <div class="absolute inset-0 flex flex-col justify-between opacity-[0.03] pointer-events-none">
                             <div v-for="i in 5" :key="i" class="border-b border-black w-full h-px"></div>
                         </div>
                         
                         <!-- Chart Bars/Lines (SVG approach) -->
                         <div class="absolute inset-0 flex items-end justify-between gap-1 overflow-visible">
                             <div v-for="(cat, idx) in categories" :key="idx" class="flex-1 h-full flex items-end gap-[1px] group/item relative">
                                 <!-- RX Bar -->
                                 <div 
                                    class="flex-1 bg-emerald-500/20 group-hover/item:bg-emerald-500/40 transition-colors relative"
                                    :style="`height: ${(rxData[idx] / maxVal) * 100}%`"
                                 >
                                    <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.4)]"></div>
                                 </div>
                                 <!-- TX Bar -->
                                 <div 
                                    class="flex-1 bg-indigo-500/20 group-hover/item:bg-indigo-500/40 transition-colors relative"
                                    :style="`height: ${(txData[idx] / maxVal) * 100}%`"
                                 >
                                    <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.4)]"></div>
                                 </div>

                                 <!-- Tooltip on hover -->
                                 <div class="absolute -top-16 left-1/2 -translate-x-1/2 bg-black text-white p-3 rounded-xl text-[10px] font-bold opacity-0 group-hover/item:opacity-100 transition-all shadow-2xl z-20 pointer-events-none whitespace-nowrap">
                                     <p class="text-white/40 uppercase text-[8px] mb-1">{{ cat }}</p>
                                     <div class="flex items-center gap-4">
                                         <div><span class="text-emerald-400">RX:</span> {{ rxData[idx] }}M</div>
                                         <div><span class="text-indigo-400">TX:</span> {{ txData[idx] }}M</div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                    
                    <div class="flex justify-between mt-8 pt-8 border-t border-black/[0.03]">
                        <span v-for="cat in categories.filter((_, i) => i % (categories.length > 10 ? Math.floor(categories.length/10) : 1) === 0)" :key="cat" class="text-[8px] font-black text-[#86868b] uppercase tracking-widest">{{ cat }}</span>
                    </div>
                </div>

                <!-- Analysis Panel -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Global Accumulation -->
                    <div class="apple-card p-8 bg-white border border-black/5">
                        <h3 class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-8">Cumulative Extraction</h3>
                        <div class="space-y-8">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                                    <Download class="w-6 h-6" />
                                </div>
                                <div>
                                    <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest">Total Downlink</p>
                                    <p class="text-xl font-bold tracking-tight">{{ (stats.total_rx / 1024).toFixed(2) }}<span class="text-xs ml-1">GB</span></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                                    <Upload class="w-6 h-6" />
                                </div>
                                <div>
                                    <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest">Total Uplink</p>
                                    <p class="text-xl font-bold tracking-tight">{{ (stats.total_tx / 1024).toFixed(2) }}<span class="text-xs ml-1">GB</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Peak Analysis -->
                    <div class="apple-card p-8 bg-black text-white">
                        <h3 class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-8">Peak Velocity Protocol</h3>
                        <div class="space-y-8">
                             <div class="space-y-2">
                                 <div class="flex items-center justify-between text-[10px] font-bold">
                                     <span class="text-white/60">Peak Downlink</span>
                                     <span class="text-emerald-400">{{ stats.peak_rx }} Mbps</span>
                                 </div>
                                 <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                     <div class="h-full bg-emerald-500 rounded-full" :style="`width: ${(stats.peak_rx / maxVal * 100)}%`"></div>
                                 </div>
                             </div>
                             <div class="space-y-2">
                                 <div class="flex items-center justify-between text-[10px] font-bold">
                                     <span class="text-white/60">Peak Uplink</span>
                                     <span class="text-indigo-400">{{ stats.peak_tx }} Mbps</span>
                                 </div>
                                 <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                     <div class="h-full bg-indigo-500 rounded-full" :style="`width: ${(stats.peak_tx / maxVal * 100)}%`"></div>
                                 </div>
                             </div>
                        </div>
                        <div class="mt-10 p-6 bg-white/5 rounded-2xl border border-white/10 flex items-center gap-4">
                             <Zap class="w-6 h-6 text-amber-500" />
                             <p class="text-[9px] font-medium leading-relaxed opacity-60">Peak performance detected during high-load intervals across segment cluster.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
