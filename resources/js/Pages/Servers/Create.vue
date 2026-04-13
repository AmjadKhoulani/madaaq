<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Plus, 
    Server, 
    Zap, 
    MapPin, 
    Globe, 
    Monitor, 
    ShieldCheck, 
    Cpu, 
    HardDrive,
    Search,
    ChevronLeft,
    CheckCircle2,
    X,
    Wifi
} from 'lucide-vue-next';

const props = defineProps({
    internetSources: Array,
    towers: Array,
    products: Array,
});

const form = useForm({
    name: '',
    model_id: '',
    internet_source_id: '',
    location_tower_id: '',
    location: '',
    lat: '',
    lng: '',
    ip: '',
    api_port: '8728',
    username: 'admin',
    password: '',
});

const deviceQuery = ref('');
const isSearching = ref(false);
const showResults = ref(false);

const filteredProducts = computed(() => {
    if (!deviceQuery.value) return props.products;
    return props.products.filter(p => 
        p.model_name.toLowerCase().includes(deviceQuery.value.toLowerCase()) ||
        p.manufacturer.toLowerCase().includes(deviceQuery.value.toLowerCase())
    );
});

const selectedProduct = computed(() => {
    return props.products.find(p => p.id === form.model_id);
});

const selectProduct = (product) => {
    form.model_id = product.id;
    if (!form.name) {
        form.name = `${product.manufacturer} ${product.model_name}`;
    }
    deviceQuery.value = '';
    showResults.value = false;
};

const submit = () => {
    form.post(route('servers.store'));
};

</script>

<template>
    <AppleLayout title="Initialize Node">
        <Head title="Deploy Core Node" />

        <div class="max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('servers.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Initialize MikroTik Node</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Provision a new primary infrastructure core.</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Hardware Architecture Intelligence -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Hardware Intelligence Core</h3>
                    </div>

                    <!-- Search / Discovery -->
                    <div class="relative mb-12 group">
                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2 mb-4 block">Smart Node Discovery</label>
                        <div class="relative">
                            <Search class="w-5 h-5 absolute left-5 top-1/2 -translate-y-1/2 text-[#86868b] group-focus-within:text-black transition-colors" />
                            <input 
                                v-model="deviceQuery"
                                type="text" 
                                placeholder="Search MikroTik Catalog (e.g. CCR, hAP, RB...)"
                                class="apple-input h-14 pl-14 text-sm font-bold"
                                @focus="showResults = true"
                            >
                        </div>

                        <!-- Search Dropdown -->
                        <div v-if="showResults && deviceQuery" class="absolute z-50 w-full mt-4 bg-white/95 backdrop-blur-xl border border-black/5 rounded-[2.5rem] shadow-2xl overflow-hidden p-2">
                            <div 
                                v-for="product in filteredProducts.slice(0, 5)" 
                                :key="product.id"
                                @click="selectProduct(product)"
                                class="flex items-center gap-4 p-4 hover:bg-black/5 rounded-[1.5rem] cursor-pointer transition-all"
                            >
                                <div class="w-14 h-14 bg-white rounded-xl border border-black/5 p-2 flex items-center justify-center shrink-0">
                                    <Monitor class="w-6 h-6 text-black/40" />
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-sm tracking-tight">{{ product.manufacturer }} {{ product.model_name }}</p>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[#86868b]">{{ product.device_type || 'Core Router' }}</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-black/5 flex items-center justify-center text-black/20">
                                    <Plus class="w-4 h-4" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Preview / Quick Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                         <div 
                            v-for="product in products.slice(0, 4)" 
                            :key="product.id"
                            @click="selectProduct(product)"
                            class="apple-card p-6 flex flex-col items-center text-center cursor-pointer transition-all group border-2"
                            :class="form.model_id === product.id ? 'border-emerald-500 bg-emerald-50/20' : 'border-transparent hover:border-black/5'"
                        >
                            <div class="w-24 h-24 mb-4 flex items-center justify-center grayscale group-hover:grayscale-0 transition-all">
                                <Monitor class="w-12 h-12 text-black/20 group-hover:text-black/60" />
                            </div>
                            <h4 class="text-[10px] font-black uppercase tracking-tight line-clamp-1">{{ product.model_name }}</h4>
                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mt-1">{{ product.manufacturer }}</p>
                        </div>
                    </div>

                    <!-- Config Layer -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Logical Node Identifier</label>
                            <input v-model="form.name" type="text" placeholder="E.g. DC01-Primary-Core" class="apple-input h-14 font-bold uppercase tracking-tight" required>
                        </div>

                        <div v-if="selectedProduct" class="bg-black p-8 rounded-[2.5rem] flex items-center gap-6 shadow-2xl relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center shrink-0">
                                <Cpu class="w-8 h-8 text-white/40" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Topology Placement</p>
                                <p class="text-xs font-bold text-white truncate uppercase">{{ selectedProduct.model_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Connection Governance -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Connection Governance</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="lg:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Global Interface IP (Public/Private)</label>
                            <input v-model="form.ip" type="text" placeholder="10.x.x.x or 185.x.x.x" class="apple-input h-14 font-mono font-bold text-sm" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">API Protocol Port</label>
                            <input v-model="form.api_port" type="number" class="apple-input h-14 font-mono font-bold" placeholder="8728">
                        </div>

                        <div class="space-y-2">
                             <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Access Lifecycle</label>
                             <div class="h-14 apple-input flex items-center justify-center bg-black/5 text-[10px] font-black uppercase tracking-widest text-black/40">
                                Persistent Connection
                             </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Auth Username (Admin)</label>
                            <input v-model="form.username" type="text" class="apple-input h-12 font-bold" placeholder="admin">
                        </div>

                        <div class="lg:col-span-3 space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Infrastructure Access Key (Password)</label>
                            <input v-model="form.password" type="password" class="apple-input h-12 font-mono" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- 3. Geospatial Registry -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Geospatial Registry</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Backbone Supply Source</label>
                            <select v-model="form.internet_source_id" class="apple-input h-14 font-bold text-xs uppercase">
                                <option value="">Independent Core Gateway</option>
                                <option v-for="source in internetSources" :key="source.id" :value="source.id">
                                    {{ source.name }} ({{ source.type }})
                                </option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Parent Infrastructure (Tower)</label>
                            <select v-model="form.location_tower_id" class="apple-input h-14 font-bold text-xs uppercase">
                                <option value="">Autonomous Placement</option>
                                <option v-for="tower in towers" :key="tower.id" :value="tower.id">
                                    🗼 {{ tower.name }}
                                </option>
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-4">
                             <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Physical Site intelligence</label>
                             <input v-model="form.location" type="text" class="apple-input h-14 text-sm font-medium" placeholder="E.g. North Rack-Unit 4">
                        </div>
                    </div>

                    <!-- Simple Coordinates (Map placeholder logic) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                         <div class="space-y-2">
                             <label class="text-[10px] font-black text-rose-500 uppercase tracking-widest ml-2">North Latitude</label>
                             <input v-model="form.lat" type="text" class="apple-input h-12 font-mono bg-rose-50/50 border-rose-100" placeholder="33.xxx">
                         </div>
                         <div class="space-y-2">
                             <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest ml-2">East Longitude</label>
                             <input v-model="form.lng" type="text" class="apple-input h-12 font-mono bg-emerald-50/50 border-emerald-100" placeholder="36.xxx">
                         </div>
                    </div>
                </div>

                <!-- Submission Logic -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white overflow-hidden relative">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 flex-1">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl">
                            <Zap class="w-8 h-8 fill-white" />
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Provisioning Pulse</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                High-priority network governance initialization. Synchronize carefully.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('servers.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Entry
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl shadow-white/10 hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95"
                            :disabled="form.processing"
                        >
                            Execute Initialize commit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
