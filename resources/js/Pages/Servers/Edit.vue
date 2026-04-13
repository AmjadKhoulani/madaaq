<script setup>
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    Server, 
    Globe, 
    MapPin, 
    Zap, 
    Shield, 
    Database, 
    Cpu, 
    Monitor,
    Save,
    X
} from 'lucide-vue-next';

const props = defineProps({
    server: Object,
    internetSources: Array,
    towers: Array,
});

const form = useForm({
    name: props.server.name,
    ip: props.server.ip,
    api_port: props.server.api_port,
    username: props.server.username,
    password: '', // Leave blank unless changing
    internet_source_id: props.server.internet_source_id || '',
    location_tower_id: props.server.location_tower_id || '',
    location: props.server.location || '',
    lat: props.server.lat || '',
    lng: props.server.lng || '',
});

const submit = () => {
    form.put(route('servers.update', props.server.id));
};

</script>

<template>
    <AppleLayout :title="'Edit ' + server.name">
        <Head :title="'Modify ' + server.name" />

        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('servers.show', server.id)" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Modify Node Protocol</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Adjusting governance parameters for {{ server.name }}</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Infrastructure Blueprint -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Infrastructure Blueprint</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Logical Node Identity</label>
                            <input v-model="form.name" type="text" class="apple-input h-14 font-bold uppercase tracking-tight" required>
                        </div>

                        <!-- Hardware Visual -->
                        <div class="bg-indigo-600 p-8 rounded-[2.5rem] flex items-center gap-8 shadow-2xl relative overflow-hidden group">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                            <div class="w-24 h-24 bg-white/10 rounded-2xl flex items-center justify-center p-4 border border-white/10 shrink-0">
                                <Monitor class="w-12 h-12 text-white/40" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Assigned Model</p>
                                <p class="text-xs font-bold text-white truncate uppercase">{{ server.device_model?.model_name || 'Generic Core' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Geospatial Distribution Point -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Geospatial Distribution Point</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Backbone Supply Source</label>
                            <select v-model="form.internet_source_id" class="apple-input h-14 font-bold text-xs uppercase">
                                <option value="">Independent Gateway</option>
                                <option v-for="source in internetSources" :key="source.id" :value="source.id">
                                    {{ source.name }} ({{ source.type }})
                                </option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Structural Assembly (Tower)</label>
                            <select v-model="form.location_tower_id" class="apple-input h-14 font-bold text-xs uppercase">
                                <option value="">Alternative Hub</option>
                                <option v-for="tower in towers" :key="tower.id" :value="tower.id">
                                    🗼 {{ tower.name }}
                                </option>
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-4">
                             <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Physical Site Intelligence</label>
                             <input v-model="form.location" type="text" class="apple-input h-14 text-sm font-medium" placeholder="E.g. MDF Rack 02">
                        </div>

                        <!-- Coordinates -->
                        <div class="space-y-2">
                             <label class="text-[10px] font-black text-rose-500 uppercase tracking-widest ml-2">Latitude</label>
                             <input v-model="form.lat" type="text" class="apple-input h-12 font-mono bg-rose-50/50 border-rose-100" placeholder="33.xxx">
                         </div>
                         <div class="space-y-2">
                             <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest ml-2">Longitude</label>
                             <input v-model="form.lng" type="text" class="apple-input h-12 font-mono bg-emerald-50/50 border-emerald-100" placeholder="36.xxx">
                         </div>
                    </div>
                </div>

                <!-- 3. Connection Governance Stack -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Connection Governance Stack</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Encrypted API Gateway (IP)</label>
                            <input v-model="form.ip" type="text" class="apple-input h-14 font-mono font-bold" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Protocol Port Index</label>
                            <input v-model="form.api_port" type="number" class="apple-input h-14 font-mono font-bold" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Governance User ID</label>
                            <input v-model="form.username" type="text" class="apple-input h-14 font-bold" required>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Secure Passcode Index (Blank to retain)</label>
                            <input v-model="form.password" type="password" class="apple-input h-14 font-mono" placeholder="••••••••••••">
                        </div>
                    </div>
                </div>

                <!-- Governance Commitment -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white overflow-hidden relative">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 flex-1">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">
                            <Save class="w-8 h-8 text-white" />
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Governance Commitment</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Protocol synchronization will re-initialize upon commitment.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('servers.show', server.id)" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Discard Archive
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl shadow-white/10 hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 flex items-center gap-2"
                            :disabled="form.processing"
                        >
                            Sync Infrastructure Commit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
