<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    Save, 
    Globe, 
    Zap, 
    Wifi, 
    Box, 
    Activity, 
    ShieldCheck
} from 'lucide-vue-next';

const props = defineProps({
    internetSource: Object,
});

const form = useForm({
    name: props.internetSource.name,
    type: props.internetSource.type,
    capacity: props.internetSource.capacity,
    status: props.internetSource.status,
    ip_gateway: props.internetSource.ip_gateway,
    connection_type: props.internetSource.connection_type || '',
});

const techTypes = [
    { value: 'fiber', label: 'Fiber Link', icon: Zap },
    { value: 'microwave', label: 'Microwave RF', icon: Wifi },
    { value: 'starlink', label: 'Satellite (Starlink)', icon: Globe },
    { value: '4g', label: 'Cellular 4G/5G', icon: Activity },
    { value: 'other', label: 'Alternative Feed', icon: Box },
];

const submit = () => {
    form.put(route('network.internet-sources.update', props.internetSource.id));
};

</script>

<template>
    <AppleLayout :title="'Modify ' + internetSource.name">
        <Head :title="'Upstream Governance: ' + internetSource.name" />

        <div class="max-w-4xl mx-auto pb-24">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('network.internet-sources.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Modify Upstream Protocol</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Adjusting parameters for {{ internetSource.name }}</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Feed Identity -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-black rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Protocol Identity</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Feed Identifier (Name)</label>
                            <input v-model="form.name" type="text" class="apple-input h-14 font-bold text-lg uppercase tracking-tight" required>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Transmission Standard</label>
                            <select v-model="form.type" class="apple-input h-14 font-bold uppercase tracking-tight" required>
                                <option v-for="tech in techTypes" :key="tech.value" :value="tech.value">{{ tech.label }}</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Current State Protocol</label>
                            <select v-model="form.status" class="apple-input h-14 font-bold uppercase tracking-tight" required>
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. Performance Specs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="apple-card p-10 space-y-8">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Performance Specs</h3>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Extraction Capacity (Mbps)</label>
                            <input v-model="form.capacity" type="text" class="apple-input h-14 font-bold" placeholder="1000">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Gateway IP Artifact</label>
                            <input v-model="form.ip_gateway" type="text" class="apple-input h-14 font-mono font-bold" placeholder="10.0.0.1">
                        </div>
                    </div>

                    <div class="apple-card p-10 bg-black text-white relative overflow-hidden group">
                         <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all"></div>
                         <div class="relative z-10 space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-amber-500">
                                <ShieldCheck class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-lg font-bold uppercase tracking-tight">Sync Integrity</h4>
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1 leading-relaxed">Changes to upstream protocols must be verified against physical link status before deployment.</p>
                            </div>
                         </div>
                    </div>
                </div>

                <!-- 3. Commitment Confirmation -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl">
                            🌍
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Sync Maintenance</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Updating feed parameters and notifying NOC subscribers.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('network.internet-sources.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Changes
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center gap-2"
                            :disabled="form.processing"
                        >
                            <Save class="w-4 h-4" /> Sync Artifact
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
