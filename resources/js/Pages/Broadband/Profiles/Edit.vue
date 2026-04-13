<script setup>
import { ref, onMounted } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    HardDrive, 
    ArrowDownCircle, 
    ArrowUpCircle, 
    DollarSign, 
    Calendar, 
    Shield, 
    Globe, 
    Wifi, 
    Activity, 
    Server, 
    Router as RouterIcon,
    Save,
    CheckCircle2,
    Database,
    Cpu
} from 'lucide-vue-next';

const props = defineProps({
    profile: Object,
    servers: Array,
    routers: Array
});

// Initialize form with existing profile data
const form = useForm({
    name: props.profile.name,
    speed_down: props.profile.speed_down,
    speed_up: props.profile.speed_up,
    price: props.profile.price,
    duration_days: props.profile.duration_days || 30,
    data_limit_mb: props.profile.data_limit_mb || '',
    technology_type: props.profile.technology_type || 'fiber',
    targets: [], // We'll populate this on mounted
});

onMounted(() => {
    // Populate targets from relations
    const serverTargets = props.profile.mikrotik_servers?.map(s => `server_${s.id}`) || [];
    const routerTargets = props.profile.routers?.map(r => `router_${r.id}`) || [];
    form.targets = [...serverTargets, ...routerTargets];
});

const techTypes = [
    { value: 'fiber', label: 'Fiber Optic (GPON)', icon: Globe },
    { value: 'wireless', label: 'Wireless (RF)', icon: Wifi },
    { value: 'dsl', label: 'DSL / Copper', icon: Activity },
    { value: 'cable', label: 'Gigabit Cable', icon: Server },
];

const submit = () => {
    form.put(route('broadband.profiles.update', props.profile.id));
};

const toggleTarget = (type, id) => {
    const targetString = `${type}_${id}`;
    const index = form.targets.indexOf(targetString);
    if (index === -1) {
        form.targets.push(targetString);
    } else {
        form.targets.splice(index, 1);
    }
};

const isTargetSelected = (type, id) => {
    return form.targets.includes(`${type}_${id}`);
};

</script>

<template>
    <AppleLayout :title="'Modify ' + profile.name">
        <Head :title="'Broadband Governance: ' + profile.name" />

        <div class="max-w-5xl mx-auto pb-24">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('broadband.profiles.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Adjust Service Tier</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Updating governance parameters for {{ profile.name }}</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Identity & Technology -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Identity & Extraction Standard</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Tier Identifier (Profile Name)</label>
                            <input v-model="form.name" type="text" class="apple-input h-14 font-bold text-lg uppercase tracking-tight" required>
                        </div>

                        <div class="space-y-6 md:col-span-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Transmission Standard</label>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                <button 
                                    v-for="tech in techTypes" 
                                    :key="tech.value"
                                    type="button"
                                    @click="form.technology_type = tech.value"
                                    class="p-6 rounded-3xl border-2 transition-all flex flex-col items-center gap-3 group"
                                    :class="form.technology_type === tech.value ? 'bg-black text-white border-black shadow-xl scale-105' : 'bg-white border-black/5 text-[#86868b] hover:bg-black/5'"
                                >
                                    <component :is="tech.icon" class="w-7 h-7 group-hover:rotate-6 transition-transform" />
                                    <span class="text-[8px] font-black uppercase tracking-widest text-center">{{ tech.label }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Performance & Fiscal Pulse -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Performance Matrix -->
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Throughput Performance</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Downlink Velocity (Mbps)</label>
                                <div class="relative">
                                    <input v-model="form.speed_down" type="number" class="apple-input h-14 pl-16 font-bold text-lg">
                                    <ArrowDownCircle class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-emerald-500" />
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Uplink Velocity (Mbps)</label>
                                <div class="relative">
                                    <input v-model="form.speed_up" type="number" class="apple-input h-14 pl-16 font-bold text-lg">
                                    <ArrowUpCircle class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-indigo-500" />
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Data Integrity Threshold (MB)</label>
                                <div class="relative">
                                    <input v-model="form.data_limit_mb" type="number" class="apple-input h-14 pl-16 font-bold text-lg">
                                    <Database class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-amber-500" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fiscal Strategy -->
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Fiscal Strategy</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Commercial Price Tier</label>
                                <div class="relative">
                                    <input v-model="form.price" type="number" step="0.01" class="apple-input h-14 pl-16 font-bold text-lg">
                                    <DollarSign class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-emerald-600" />
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Lifecycle Validity (Days)</label>
                                <div class="relative">
                                    <input v-model="form.duration_days" type="number" class="apple-input h-14 pl-16 font-bold text-lg">
                                    <Calendar class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-[#86868b]" />
                                </div>
                            </div>

                            <div class="p-8 bg-emerald-600 text-white rounded-[2.5rem] relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-all"></div>
                                <div class="relative z-10">
                                     <p class="text-[9px] font-black text-emerald-100 uppercase tracking-widest mb-2">Sync Status</p>
                                     <p class="text-sm font-medium opacity-90 leading-relaxed">Updating a profile will trigger a background handshake with all selected edge nodes to refresh rate limits.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Deployment Topology -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-black rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Edge Deployment Topology</h3>
                    </div>

                    <div class="space-y-12">
                        <!-- Server Targets -->
                        <div class="space-y-6">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Master Controllers (MikroTik Servers)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <button 
                                    v-for="server in servers" 
                                    :key="server.id"
                                    type="button"
                                    @click="toggleTarget('server', server.id)"
                                    class="p-6 rounded-[2rem] border-2 transition-all flex items-center gap-5 group text-left"
                                    :class="isTargetSelected('server', server.id) ? 'bg-black text-white border-black shadow-lg scale-[1.02]' : 'bg-white border-black/5 text-[#86868b] hover:bg-black/[0.02]'"
                                >
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all bg-black/5 group-hover:bg-white/10" :class="isTargetSelected('server', server.id) ? 'text-indigo-400' : 'text-indigo-600'">
                                        <Cpu class="w-6 h-6" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold tracking-tight truncate">{{ server.name }}</p>
                                        <p class="text-[9px] font-mono opacity-60">{{ server.ip }}</p>
                                    </div>
                                    <div v-if="isTargetSelected('server', server.id)" class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-black">
                                        <CheckCircle2 class="w-4 h-4" />
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Router Targets -->
                        <div class="space-y-6">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Active Service Routers</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <button 
                                    v-for="router in routers" 
                                    :key="router.id"
                                    type="button"
                                    @click="toggleTarget('router', router.id)"
                                    class="p-6 rounded-[2rem] border-2 transition-all flex items-center gap-5 group text-left"
                                    :class="isTargetSelected('router', router.id) ? 'bg-black text-white border-black shadow-lg scale-[1.02]' : 'bg-white border-black/5 text-[#86868b] hover:bg-black/[0.02]'"
                                >
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all bg-black/5 group-hover:bg-white/10" :class="isTargetSelected('router', router.id) ? 'text-emerald-400' : 'text-emerald-600'">
                                        <RouterIcon class="w-6 h-6" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold tracking-tight truncate">{{ router.name }}</p>
                                        <p class="text-[9px] font-mono opacity-60">{{ router.ip }}</p>
                                    </div>
                                    <div v-if="isTargetSelected('router', router.id)" class="w-6 h-6 bg-white rounded-full flex items-center justify-center text-black">
                                        <CheckCircle2 class="w-4 h-4" />
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Governance Commitment -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">
                            <Shield class="w-8 h-8 text-indigo-400" />
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Sync Maintenance</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Updating tier specs on edge controllers via background API protocol.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('broadband.profiles.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Changes
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center gap-2"
                            :disabled="form.processing"
                        >
                            <Save class="w-4 h-4" /> Sync Tier Protocol
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
