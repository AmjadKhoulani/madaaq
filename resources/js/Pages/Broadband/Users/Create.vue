<script setup>
import { ref, watch, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    User, 
    Wifi, 
    Package as PackageIcon, 
    Key, 
    Monitor, 
    Calendar, 
    Database, 
    DollarSign,
    Shield,
    TowerControl as TowerIcon,
    ArrowDownCircle,
    ArrowUpCircle,
    CheckCircle2,
    Users,
    Search,
    Globe
} from 'lucide-vue-next';

const props = defineProps({
    routers: Array,
    towers: Array,
    packages: Array,
    customers: Array
});

const form = useForm({
    mode: 'new', // new, existing
    customer_id: '',
    name: '',
    phone: '',
    tower_id: '',
    ssid_id: '',
    router_id: '',
    package_id: '',
    username: '',
    password: '',
    ip: '',
    custom_duration_days: '',
    custom_data_limit_mb: '',
    custom_price: '',
    expires_at: '',
});

const selectedTower = computed(() => {
    return props.towers.find(t => t.id === form.tower_id);
});

const availableSsids = computed(() => {
    return selectedTower.value?.ssids || [];
});

const selectedPackage = computed(() => {
    return props.packages.find(p => p.id === form.package_id);
});

watch(() => form.tower_id, () => {
    form.ssid_id = '';
    form.router_id = '';
});

watch(() => form.ssid_id, (newSsidId) => {
    const ssid = availableSsids.value.find(s => s.id === newSsidId);
    if (ssid?.router) {
        form.router_id = ssid.router.id;
    }
});

const submit = () => {
    form.post(route('broadband.users.store'));
};

const generateSecret = () => {
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let pass = '';
    for (let i = 0; i < 8; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    form.password = pass;
    if (!form.username && form.name) {
         form.username = form.name.toLowerCase().replace(/\s+/g, '.') + '.' + Math.floor(Math.random() * 100);
    }
};

</script>

<template>
    <AppleLayout title="Provision Account">
        <Head title="Initialize Broadband Lease" />

        <div class="max-w-5xl mx-auto pb-24">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('broadband.users.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Provision PPPoE Lease</h1>
                        <p class="text-[var(--app-secondary)] font-medium">Initializing a high-integrity subscriber account</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Subscriber Identity -->
                <div class="apple-card p-10">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Subscriber Intelligence</h3>
                        </div>
                        <div class="flex bg-black/[0.03] p-1.5 rounded-2xl">
                             <button type="button" @click="form.mode = 'new'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="form.mode === 'new' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">New Lead</button>
                             <button type="button" @click="form.mode = 'existing'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="form.mode === 'existing' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">Existing Peer</button>
                        </div>
                    </div>

                    <div v-if="form.mode === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-in fade-in duration-500">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Full Identity (Name)</label>
                            <input v-model="form.name" type="text" class="apple-input h-14 font-bold" placeholder="e.g. Alexander Hamilton">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Extraction Link (Phone)</label>
                            <input v-model="form.phone" type="text" class="apple-input h-14 font-bold" placeholder="09xx xxx xxx">
                        </div>
                    </div>

                    <div v-else class="space-y-4 animate-in fade-in duration-500">
                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Select Registered Subscriber</label>
                        <select v-model="form.customer_id" class="apple-input h-14 font-bold text-lg uppercase tracking-tight">
                            <option value="">Choose From Local Registry...</option>
                            <option v-for="c in customers" :key="c.id" :value="c.id">{{ c.name }} ({{ c.phone }})</option>
                        </select>
                    </div>
                </div>

                <!-- 2. Topology & Performance -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Network Topology -->
                    <div class="apple-card p-10 space-y-8">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Edge Topology</h3>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Site Infrastructure (Tower)</label>
                                <select v-model="form.tower_id" class="apple-input h-14 font-bold uppercase tracking-tight">
                                    <option value="">Select Target Site...</option>
                                    <option v-for="t in towers" :key="t.id" :value="t.id">🗼 {{ t.name }} ({{ t.city }})</option>
                                </select>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Transmission Interface (SSID)</label>
                                <select v-model="form.ssid_id" class="apple-input h-14 font-bold uppercase tracking-tight" :disabled="!form.tower_id">
                                    <option value="">Choose Interface...</option>
                                    <option v-for="s in availableSsids" :key="s.id" :value="s.id">📶 {{ s.ssid_name }} ({{ s.frequency }})</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Tier -->
                    <div class="apple-card p-10 space-y-8">
                         <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Service Velocity</h3>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Select Performance Package</label>
                                <select v-model="form.package_id" class="apple-input h-14 font-bold uppercase tracking-tight">
                                    <option value="">Global Service Tier...</option>
                                    <option v-for="p in packages" :key="p.id" :value="p.id">⚡ {{ p.name }} (${{ p.price }})</option>
                                </select>
                            </div>

                            <div v-if="selectedPackage" class="p-6 bg-black text-white rounded-[2rem] animate-in slide-in-from-top duration-500 relative overflow-hidden">
                                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-indigo-600/30 rounded-full blur-3xl"></div>
                                <div class="relative z-10 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                         <div class="flex flex-col items-center">
                                            <ArrowDownCircle class="w-4 h-4 text-emerald-400 mb-1" />
                                            <span class="text-xl font-bold tracking-tighter">{{ selectedPackage.speed_down }}M</span>
                                         </div>
                                         <div class="w-px h-8 bg-white/10"></div>
                                         <div class="flex flex-col items-center">
                                            <ArrowUpCircle class="w-4 h-4 text-indigo-400 mb-1" />
                                            <span class="text-xl font-bold tracking-tighter">{{ selectedPackage.speed_up }}M</span>
                                         </div>
                                    </div>
                                    <div class="text-right">
                                         <p class="text-[8px] font-black text-white/40 uppercase tracking-widest mb-1">Fiscal Pulse</p>
                                         <p class="text-xl font-bold tracking-tight">${{ selectedPackage.price }} <span class="text-[10px] font-medium opacity-50">/{{ selectedPackage.duration_days }} Days</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Account Synthesis -->
                <div class="apple-card p-10">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Account Synthesis</h3>
                        </div>
                        <button type="button" @click="generateSecret" class="px-5 py-2 bg-black/[0.03] text-black font-black text-[9px] uppercase tracking-widest rounded-xl hover:bg-black hover:text-white transition-all">Generate Payload</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">PPPoE Handle (Username)</label>
                            <input v-model="form.username" type="text" class="apple-input h-14 font-mono font-bold tracking-wider" placeholder="e.g. alex.broadband">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Authentication Secret (Password)</label>
                            <input v-model="form.password" type="text" class="apple-input h-14 font-mono font-bold tracking-wider" placeholder="********">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Static Lease Protocol (IP Address)</label>
                            <input v-model="form.ip" type="text" class="apple-input h-14 font-mono font-bold tracking-wider" placeholder="e.g. 10.0.x.x (Leave for Dynamic)">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Activation Horizon (Start Date)</label>
                            <input v-model="form.expires_at" type="date" class="apple-input h-14 font-bold">
                        </div>
                    </div>
                </div>

                <!-- 4. Governance Commitment -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8">
                        <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">
                            <Shield class="w-8 h-8 text-indigo-400" />
                        </div>
                        <div>
                             <h4 class="text-lg font-bold uppercase tracking-tight">Provisioning Commitment</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Committing subscriber credentials to edge controllers via API handshake.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('broadband.users.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Procedure
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Finalize Provisioning
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
