<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Activity, 
    Wifi, 
    Server, 
    TowerControl as Tower, 
    AlertTriangle, 
    CheckCircle2, 
    XCircle, 
    Clock, 
    Search, 
    Filter,
    ArrowUpRight,
    RefreshCw,
    ShieldAlert,
    HardDrive,
    Signal
} from 'lucide-vue-next';

const props = defineProps({
    devices: Array,
    activeAlerts: Array,
    recentAlerts: Array,
    stats: Object
});

const searchQuery = ref('');
const filterType = ref('all');

const filteredDevices = computed(() => {
    let result = props.devices;
    
    if (filterType.value !== 'all') {
        result = result.filter(d => d.type === filterType.value);
    }
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(d => 
            d.name.toLowerCase().includes(query) || 
            (d.ip && d.ip.includes(query))
        );
    }
    
    return result;
});

const resolveAlert = (id) => {
    router.patch(route('network.monitoring.alert.resolve', id), {}, {
        preserveScroll: true
    });
};

const getStatusColor = (status) => {
    switch (status) {
        case 'online': return 'text-emerald-500 bg-emerald-500/10';
        case 'offline': return 'text-rose-500 bg-rose-500/10';
        case 'warning': return 'text-amber-500 bg-amber-500/10';
        default: return 'text-gray-400 bg-gray-400/10';
    }
};

const getStatusBorder = (status) => {
    switch (status) {
        case 'online': return 'border-emerald-500/20';
        case 'offline': return 'border-rose-500/20';
        case 'warning': return 'border-amber-500/20';
        default: return 'border-transparent';
    }
};

const getDeviceIcon = (type) => {
    switch (type) {
        case 'server': return Server;
        case 'router': return HardDrive;
        case 'tower': return Tower;
        default: return Activity;
    }
};

</script>

<template>
    <AppleLayout title="Network Operations Center">
        <Head title="NOC Intelligence" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic NOC Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
                <div>
                     <h1 class="text-3xl font-bold tracking-tight mb-2">Network Operations Center</h1>
                     <p class="text-[var(--app-secondary)] font-medium flex items-center gap-3">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        Real-time Infrastructure Telemetry Active
                     </p>
                </div>
                <div class="flex items-center gap-4">
                     <div class="relative group hidden md:block">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868b] group-focus-within:text-black transition-colors" />
                        <input v-model="searchQuery" type="text" placeholder="Probe device or node..." class="apple-input pl-11 h-12 w-64 bg-black/[0.02] border-transparent focus:bg-white focus:border-black/5">
                     </div>
                     <button class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all">
                        <RefreshCw class="w-5 h-5" />
                     </button>
                     <Link 
                        :href="route('network.monitoring.bandwidth')" 
                        class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <Signal class="w-4 h-4" /> Analyzers
                     </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Status Matrix & Devices -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Global Health Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="apple-card p-6 bg-white border-black/5">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-2">Total Edge Nodes</p>
                            <h3 class="text-2xl font-bold tracking-tight">{{ stats.total_devices }}</h3>
                        </div>
                        <div class="apple-card p-6 bg-emerald-50 border-emerald-100">
                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2">Operational</p>
                            <h3 class="text-2xl font-bold tracking-tight text-emerald-700">{{ stats.online }}</h3>
                        </div>
                        <div class="apple-card p-6 bg-rose-50 border-rose-100">
                            <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest mb-2">Decommissioned</p>
                            <h3 class="text-2xl font-bold tracking-tight text-rose-700">{{ stats.offline }}</h3>
                        </div>
                        <div class="apple-card p-6 bg-amber-50 border-amber-100 relative overflow-hidden group">
                           <div class="absolute -right-4 -top-4 w-12 h-12 bg-amber-500/10 rounded-full blur-xl group-hover:bg-amber-500/20 transition-all"></div>
                            <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-2">Active Faults</p>
                            <h3 class="text-2xl font-bold tracking-tight text-amber-700">{{ stats.active_alerts }}</h3>
                        </div>
                    </div>

                    <!-- Device Grid -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex bg-black/[0.03] p-1 rounded-xl">
                                <button v-for="type in ['all', 'server', 'router', 'tower']" :key="type" @click="filterType = type" class="px-5 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all" :class="filterType === type ? 'bg-white text-black shadow-sm' : 'text-[#86868b]'">{{ type }}</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            <div 
                                v-for="device in filteredDevices" 
                                :key="device.id + device.type"
                                class="apple-card p-6 flex items-center gap-5 transition-all hover:scale-[1.03]"
                                :class="getStatusBorder(device.status)"
                            >
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all shadow-inner relative" :class="getStatusColor(device.status)">
                                    <component :is="getDeviceIcon(device.type)" class="w-7 h-7" />
                                    <div v-if="device.status === 'online'" class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white animate-pulse"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold tracking-tight uppercase truncate">{{ device.name }}</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                         <p class="text-[9px] font-mono text-[#86868b]">{{ device.ip || 'SITE_LINK' }}</p>
                                         <span v-if="device.response_time" class="text-[8px] font-black px-1.5 py-0.5 bg-black/5 rounded text-[#86868b]">{{ device.response_time }}ms</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                     <p class="text-[8px] font-black uppercase tracking-[0.2em]" :class="device.status === 'online' ? 'text-emerald-500' : 'text-rose-500'">{{ device.status }}</p>
                                     <p class="text-[8px] font-medium text-[#86868b] mt-0.5 truncate max-w-[60px]">{{ device.last_check ? new Date(device.last_check).toLocaleTimeString() : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert Stream -->
                <div class="lg:col-span-1 flex flex-col gap-8">
                    <!-- Critical Faults -->
                    <div class="apple-card bg-black text-white p-8 flex flex-col min-h-[400px]">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                                <ShieldAlert class="w-4 h-4 text-rose-500" /> Critical Faults
                            </h3>
                            <span class="px-2 py-1 bg-white/10 rounded text-[9px] font-black">{{ activeAlerts.length }}</span>
                        </div>

                        <div class="space-y-4 flex-1 overflow-y-auto pr-2 custom-scrollbar">
                            <div v-for="alert in activeAlerts" :key="alert.id" class="p-5 bg-white/5 border border-white/10 rounded-2xl group hover:border-white/20 transition-all">
                                <div class="flex items-start justify-between mb-3">
                                     <div class="p-2 bg-rose-500/20 text-rose-500 rounded-lg">
                                        <AlertTriangle class="w-4 h-4" />
                                     </div>
                                     <button @click="resolveAlert(alert.id)" class="text-[8px] font-black text-white/40 uppercase hover:text-emerald-400 transition-colors">Resolve Artifact</button>
                                </div>
                                <h5 class="text-xs font-bold uppercase tracking-tight mb-1">{{ alert.device?.name || 'Protocol Device' }}</h5>
                                <p class="text-[10px] text-white/60 leading-relaxed">{{ alert.message }}</p>
                                <div class="flex items-center gap-2 mt-4 text-[8px] font-black text-white/30 uppercase tracking-widest">
                                    <Clock class="w-3 h-3" /> {{ new Date(alert.created_at).toLocaleTimeString() }}
                                </div>
                            </div>

                            <div v-if="activeAlerts.length === 0" class="flex flex-col items-center justify-center h-full text-center py-10">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4 text-emerald-500">
                                    <CheckCircle2 class="w-8 h-8" />
                                </div>
                                <h4 class="text-sm font-bold uppercase tracking-widest text-emerald-500 mb-1">Grid Operational</h4>
                                <p class="text-[9px] font-medium text-white/40 uppercase">No active faults detected in NOC segment.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="apple-card p-8 bg-white border border-black/5 flex-1">
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-8">Historical Stream</h3>
                         <div class="space-y-6">
                            <div v-for="alert in recentAlerts.slice(0, 5)" :key="alert.id" class="flex items-start gap-4">
                                <div class="w-1.5 h-1.5 bg-black/10 rounded-full mt-1.5 shrink-0" :class="alert.is_resolved ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                                <div>
                                    <p class="text-[10px] font-bold text-black uppercase leading-tight">{{ alert.message }}</p>
                                    <p class="text-[8px] font-medium text-[#86868b] mt-1">{{ alert.device?.name }} • {{ new Date(alert.created_at).toLocaleTimeString() }}</p>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}
</style>
