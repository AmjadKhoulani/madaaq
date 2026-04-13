<script setup>
import { useForm, Head, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Globe, 
    Zap, 
    Wifi, 
    Box, 
    Activity, 
    Plus, 
    Trash2, 
    Edit3, 
    CheckCircle2, 
    AlertTriangle, 
    MoreHorizontal,
    HardDrive,
    Server,
    ArrowRight
} from 'lucide-vue-next';

const props = defineProps({
    sources: Array,
});

const form = useForm({
    name: '',
    type: 'fiber',
    capacity: '',
    status: 'online',
    ip_gateway: '',
});

const submit = () => {
    form.post(route('network.internet-sources.store'), {
        onSuccess: () => form.reset()
    });
};

const deleteSource = (id) => {
    if (confirm('Decommission this upstream feed?')) {
        router.delete(route('network.internet-sources.destroy', id));
    }
};

const techTypes = [
    { value: 'fiber', label: 'Fiber Link', icon: Zap },
    { value: 'microwave', label: 'Microwave RF', icon: Wifi },
    { value: 'starlink', label: 'Satellite (Starlink)', icon: Globe },
    { value: '4g', label: 'Cellular 4G/5G', icon: Activity },
    { value: 'other', label: 'Alternative Feed', icon: Box },
];

const getStatusClass = (status) => {
    switch (status) {
        case 'online': return 'text-emerald-600 bg-emerald-50 border-emerald-100';
        case 'offline': return 'text-rose-600 bg-rose-50 border-rose-100';
        case 'maintenance': return 'text-amber-600 bg-amber-50 border-amber-100';
        default: return 'text-gray-600 bg-gray-50 border-gray-100';
    }
};

</script>

<template>
    <AppleLayout title="Upstream Governance">
        <Head title="Internet Source Intelligence" />

        <div class="max-w-[1400px] mx-auto pb-24">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- 1. Synthesis (Create) -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="apple-card p-10">
                        <div class="flex items-center gap-3 mb-10">
                            <div class="w-1.5 h-6 bg-black rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Initialize Upstream Feed</h3>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Feed Identifier (Name)</label>
                                <input v-model="form.name" type="text" class="apple-input h-14 font-bold" placeholder="E.g. Primary Fiber Core" required>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Transmission Protocol</label>
                                <select v-model="form.type" class="apple-input h-14 font-bold uppercase tracking-tight" required>
                                    <option v-for="tech in techTypes" :key="tech.value" :value="tech.value">{{ tech.label }}</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Provisioned (Mbps)</label>
                                    <input v-model="form.capacity" type="text" class="apple-input h-14 font-bold" placeholder="1000">
                                </div>
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Initial State</label>
                                    <select v-model="form.status" class="apple-input h-14 font-bold uppercase tracking-tight">
                                        <option value="online">Online</option>
                                        <option value="offline">Offline</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Gateway IP Artifact</label>
                                <input v-model="form.ip_gateway" type="text" class="apple-input h-14 font-mono font-bold" placeholder="10.0.0.1">
                            </div>

                            <button 
                                type="submit" 
                                class="w-full py-5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all disabled:opacity-50 flex items-center justify-center gap-3"
                                :disabled="form.processing"
                            >
                                <Plus class="w-4 h-4" /> Commit Upstream Protocol
                            </button>
                        </form>
                    </div>

                    <!-- Statistics Card -->
                    <div class="apple-card p-10 bg-black text-white relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all"></div>
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">Aggregate Capacity</p>
                                <h3 class="text-4xl font-bold tracking-tight">{{ sources.reduce((acc, s) => acc + (parseFloat(s.capacity) || 0), 0) }} Mbps</h3>
                            </div>
                            <Activity class="w-10 h-10 text-white/20" />
                        </div>
                    </div>
                </div>

                <!-- 2. Feed Registry (List) -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="flex items-center justify-between">
                         <h2 class="text-2xl font-bold tracking-tight">Upstream Feed Registry</h2>
                         <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">{{ sources.length }} Active Protocols</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div 
                            v-for="source in sources" 
                            :key="source.id"
                            class="apple-card p-8 flex flex-col relative group transition-all hover:scale-[1.02]"
                        >
                            <div class="flex items-center justify-between mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-black text-white flex items-center justify-center shadow-lg group-hover:bg-indigo-600 transition-colors">
                                    <component :is="techTypes.find(t => t.value === source.type)?.icon || Box" class="w-6 h-6" />
                                </div>
                                <div 
                                    class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border flex items-center gap-2"
                                    :class="getStatusClass(source.status)"
                                >
                                    <span v-if="source.status === 'online'" class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                                    {{ source.status }}
                                </div>
                            </div>

                            <div class="space-y-1 mb-8">
                                <h4 class="text-lg font-bold tracking-tight uppercase truncate">{{ source.name }}</h4>
                                <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">{{ source.type }} Extraction Force</p>
                            </div>

                            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-black/[0.03] mt-auto">
                                <div>
                                    <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mb-1">Capacity</p>
                                    <p class="text-sm font-bold">{{ source.capacity || 'N/A' }} Mbps</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mb-1">Gateway</p>
                                    <p class="text-sm font-mono font-bold truncate">{{ source.ip_gateway || 'DYNAMIC' }}</p>
                                </div>
                            </div>

                            <div class="absolute top-4 right-4 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                <button @click="router.get(route('network.internet-sources.edit', source.id))" class="w-8 h-8 rounded-lg bg-black/5 flex items-center justify-center hover:bg-black hover:text-white transition-all">
                                    <Edit3 class="w-3.5 h-3.5" />
                                </button>
                                <button @click="deleteSource(source.id)" class="w-8 h-8 rounded-lg bg-black/5 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all text-rose-500">
                                    <Trash2 class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="sources.length === 0" class="col-span-full py-24 apple-card flex flex-col items-center justify-center text-center opacity-50">
                             <div class="w-20 h-20 rounded-[2.5rem] bg-black/5 flex items-center justify-center mb-6">
                                <Globe class="w-10 h-10" />
                             </div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Registry Void</h4>
                             <p class="text-xs font-medium max-w-xs mx-auto mt-2">No upstream feeds detected in NOC segment. Initialize a new protocol to begin extraction.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
