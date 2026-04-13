<script setup>
import { ref, watch, onMounted } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    ChevronLeft, 
    TowerControl as Tower, 
    Building2, 
    Box, 
    Home, 
    Navigation,
    MapPin,
    Sun,
    Zap,
    Battery,
    DollarSign,
    Unlink,
    Globe,
    Wifi,
    Cpu,
    CheckCircle2,
    Shield
} from 'lucide-vue-next';

const props = defineProps({
    servers: Array,
    deviceModels: Array,
    activeRouters: Array,
    currency: String,
});

const form = useForm({
    name: '',
    type: 'tower',
    city: '',
    district: '',
    height: '',
    location: '',
    lat: '',
    lng: '',
    has_solar: false,
    solar_panels_count: '',
    solar_panel_wattage: '',
    has_generator: false,
    generator_capacity: '',
    has_government_electricity: false,
    battery_count: '',
    battery_type: '',
    structure_cost: '',
    monthly_rent: '',
    mikrotik_server_id: '',
    connection_type: '',
    transmitter_type: 'existing',
    transmitter_router_id: '',
    transmitter_name: '',
    transmitter_ip: '',
    transmitter_model_id: '',
    receiver_name: '',
    receiver_ip: '',
    receiver_model_id: '',
});

const siteTypes = [
    { value: 'tower', label: 'Tower', icon: Tower },
    { value: 'building', label: 'Building', icon: Building2 },
    { value: 'cabinet', label: 'Cabinet', icon: Box },
    { value: 'pole', label: 'Pole', icon: Navigation },
    { value: 'office', label: 'Office', icon: Home },
];

const submit = () => {
    form.post(route('network.towers.store'));
};

</script>

<template>
    <AppleLayout title="Initialize Hub">
        <Head title="Site Deployment Protocol" />

        <div class="max-w-5xl mx-auto pb-20">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('network.towers.index')" 
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                    >
                        <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight mb-1">Deploy Hub</h1>
                        <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            Ready for Site Initialization
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Hub Identity -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Hub Identity Matrix</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-10">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Site Label / SSID Identity</label>
                            <input v-model="form.name" type="text" class="apple-input h-14 font-bold text-lg uppercase tracking-tight" placeholder="e.g. CORE-CENTER-01" required>
                        </div>

                        <div class="space-y-6">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Infrastructure Classification</label>
                            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                                <button 
                                    v-for="type in siteTypes" 
                                    :key="type.value"
                                    type="button"
                                    @click="form.type = type.value"
                                    class="p-6 rounded-3xl border-2 transition-all flex flex-col items-center gap-3 group"
                                    :class="form.type === type.value ? 'bg-black text-white border-black shadow-xl scale-105' : 'bg-white border-black/5 text-[#86868b] hover:bg-black/5'"
                                >
                                    <component :is="type.icon" class="w-8 h-8 group-hover:rotate-6 transition-transform" />
                                    <span class="text-[9px] font-black uppercase tracking-widest">{{ type.label }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">City / Region</label>
                                <input v-model="form.city" type="text" class="apple-input h-14 font-medium" placeholder="e.g. Damascus">
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">District</label>
                                <input v-model="form.district" type="text" class="apple-input h-14 font-medium" placeholder="e.g. Downtown">
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Structural Height (m)</label>
                                <input v-model="form.height" type="number" class="apple-input h-14 font-bold" placeholder="0.0">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Physical Access Protocol</label>
                            <textarea v-model="form.location" rows="3" class="apple-input p-6 font-medium resize-none" placeholder="Site entry requirements or precise directions..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Geospatial Grid -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1 h-6 bg-rose-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Geospatial Plotting</h3>
                    </div>

                    <div class="space-y-8">
                         <div class="aspect-video bg-black/[0.02] rounded-[2.5rem] border border-black/5 flex items-center justify-center relative overflow-hidden">
                             <!-- Simple Coordinate Manual Entry if map fails/not loaded yet -->
                             <div class="text-center p-12 space-y-6">
                                <MapPin class="w-12 h-12 text-rose-500 mx-auto animate-bounce" />
                                <div class="flex gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-rose-500 uppercase tracking-widest">N-Lat</label>
                                        <input v-model="form.lat" type="text" class="apple-input h-12 font-mono text-center w-32" placeholder="33.xxx">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">E-Lng</label>
                                        <input v-model="form.lng" type="text" class="apple-input h-12 font-mono text-center w-32" placeholder="36.xxx">
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- 3. Energy Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3 mb-4">
                            <div class="w-1 h-6 bg-amber-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Energy Redundancy Array</h3>
                        </div>

                        <div class="space-y-6">
                            <!-- Solar -->
                            <div class="p-6 rounded-3xl border transition-all" :class="form.has_solar ? 'bg-amber-50/50 border-amber-200' : 'bg-black/[0.01] border-black/5'">
                                <label class="flex items-center justify-between cursor-pointer mb-6">
                                    <span class="text-xs font-bold uppercase tracking-widest flex items-center gap-3">
                                        <Sun class="w-5 h-5 text-amber-500" /> Solar Array
                                    </span>
                                    <input v-model="form.has_solar" type="checkbox" class="w-10 h-10 rounded-full border-black/10 text-black">
                                </label>
                                <div v-if="form.has_solar" class="grid grid-cols-2 gap-4 animate-in fade-in duration-300">
                                    <input v-model="form.solar_panels_count" type="number" class="apple-input h-12" placeholder="Panel Units">
                                    <input v-model="form.solar_panel_wattage" type="number" class="apple-input h-12" placeholder="Wattage (W)">
                                </div>
                            </div>

                            <!-- Generator -->
                             <div class="p-6 rounded-3xl border transition-all" :class="form.has_generator ? 'bg-orange-50/50 border-orange-200' : 'bg-black/[0.01] border-black/5'">
                                <label class="flex items-center justify-between cursor-pointer mb-6">
                                    <span class="text-xs font-bold uppercase tracking-widest flex items-center gap-3">
                                        <Zap class="w-5 h-5 text-orange-500" /> Combustion Unit
                                    </span>
                                    <input v-model="form.has_generator" type="checkbox" class="w-10 h-10 rounded-full border-black/10 text-black">
                                </label>
                                <div v-if="form.has_generator" class="animate-in fade-in duration-300">
                                    <input v-model="form.generator_capacity" type="text" class="apple-input h-12" placeholder="e.g. 15 KVA / Diesel">
                                </div>
                            </div>

                            <!-- Grid Elec -->
                            <div class="p-6 rounded-3xl border transition-all" :class="form.has_government_electricity ? 'bg-emerald-50/50 border-emerald-200' : 'bg-black/[0.01] border-black/5'">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <span class="text-xs font-bold uppercase tracking-widest flex items-center gap-3">
                                        <Globe class="w-5 h-5 text-emerald-500" /> Utility Grid
                                    </span>
                                    <input v-model="form.has_government_electricity" type="checkbox" class="w-10 h-10 rounded-full border-black/10 text-black">
                                </label>
                            </div>

                            <!-- Batteries -->
                            <div class="p-8 bg-black text-white rounded-[2.5rem] space-y-6">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest flex items-center gap-3">
                                    <Battery class="w-5 h-5 text-indigo-400" /> Chemical Energy Storage
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <input v-model="form.battery_count" type="number" class="bg-white/10 border border-white/10 rounded-xl h-12 px-5 text-white outline-none" placeholder="Cells">
                                    <input v-model="form.battery_type" type="text" class="bg-white/10 border border-white/10 rounded-xl h-12 px-5 text-white outline-none" placeholder="e.g. 12V 200AH">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Capital -->
                    <div class="apple-card p-10 space-y-10">
                         <div class="flex items-center gap-3 mb-4">
                            <div class="w-1 h-6 bg-emerald-600 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Capital Expenditure</h3>
                        </div>

                        <div class="space-y-8">
                             <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Structural Build CAPEX</label>
                                <div class="relative">
                                    <input v-model="form.structure_cost" type="number" step="0.01" class="apple-input h-14 pl-16 font-bold text-lg">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-600 font-bold text-xs uppercase">{{ currency }}</div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Node Monthly Lease</label>
                                <div class="relative">
                                    <input v-model="form.monthly_rent" type="number" step="0.01" class="apple-input h-14 pl-16 font-bold text-lg">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-indigo-600 font-bold text-xs uppercase">{{ currency }}</div>
                                </div>
                            </div>

                            <div class="p-8 bg-emerald-600 text-white rounded-[2.5rem] relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                                <div class="relative z-10 flex items-center gap-6">
                                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">💰</div>
                                    <div>
                                         <p class="text-[9px] font-black text-emerald-200 uppercase tracking-widest mb-1">Audit Ledger Pulse</p>
                                         <p class="text-sm font-bold opacity-80 leading-relaxed">Ensure all structural overheads are committed to the fiscal registry.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Network Uplink -->
                <div class="apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Network Uplink Topology</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Gateway Controller</label>
                            <select v-model="form.mikrotik_server_id" class="apple-input h-14 font-bold text-xs uppercase">
                                <option value="">Standalone Site</option>
                                <option v-for="server in servers" :key="server.id" :value="server.id">{{ server.name }}</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Transport Standard</label>
                            <select v-model="form.connection_type" class="apple-input h-14 font-bold text-xs uppercase">
                                <option value="">No Transport Defined</option>
                                <option value="wireless">Wireless (PTP Array)</option>
                                <option value="fiber">Optical Fiber (GPON)</option>
                                <option value="cable">Gigabit Ethernet</option>
                            </select>
                        </div>

                        <!-- Wireless P2P Setup -->
                        <div v-if="form.connection_type === 'wireless'" class="md:col-span-2 bg-black text-white p-10 rounded-[3rem] animate-in slide-in-from-top duration-500 relative overflow-hidden">
                             <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-indigo-600/20 rounded-full blur-3xl"></div>
                             <h4 class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-10 pb-4 border-b border-white/10">Point-to-Point Backhaul Configuration</h4>

                             <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                                 <!-- Transmitter (TX) -->
                                 <div class="space-y-8">
                                    <div class="flex items-center justify-between">
                                         <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Source Array (TX)</label>
                                         <div class="flex bg-white/5 p-1 rounded-xl">
                                             <button type="button" @click="form.transmitter_type = 'existing'" class="px-4 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest transition-all" :class="form.transmitter_type === 'existing' ? 'bg-white text-black shadow-lg' : 'text-white/40'">Registry</button>
                                             <button type="button" @click="form.transmitter_type = 'new'" class="px-4 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-widest transition-all" :class="form.transmitter_type === 'new' ? 'bg-white text-black shadow-lg' : 'text-white/40'">Ad-Hoc</button>
                                         </div>
                                    </div>

                                    <div v-if="form.transmitter_type === 'existing'" class="space-y-4">
                                        <select v-model="form.transmitter_router_id" class="bg-white/10 border border-white/10 rounded-2xl h-14 px-6 w-full text-white font-bold outline-none">
                                            <option value="" class="text-black">Choose Hardware From Registry...</option>
                                            <option v-for="router in activeRouters" :key="router.id" :value="router.id" class="text-black">{{ router.name }} ({{ router.ip }})</option>
                                        </select>
                                    </div>

                                    <div v-else class="space-y-4 animate-in fade-in">
                                         <input v-model="form.transmitter_name" type="text" class="bg-white/10 border border-white/10 rounded-2xl h-14 px-6 w-full text-white font-bold outline-none" placeholder="Radio Handle">
                                         <input v-model="form.transmitter_ip" type="text" class="bg-white/10 border border-white/10 rounded-2xl h-14 px-6 w-full text-white font-mono outline-none" placeholder="Radio IP">
                                         <select v-model="form.transmitter_model_id" class="bg-white/10 border border-white/10 rounded-2xl h-14 px-6 w-full text-white font-bold outline-none">
                                            <option value="" class="text-black">Select Protocol Model...</option>
                                            <option v-for="model in deviceModels" :key="model.id" :value="model.id" class="text-black">{{ model.model_name }}</option>
                                        </select>
                                    </div>
                                 </div>

                                 <!-- Receiver (RX) -->
                                 <div class="space-y-8">
                                     <label class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Terminal Array (RX)</label>
                                     <div class="space-y-4">
                                         <input v-model="form.receiver_name" type="text" class="bg-white/10 border border-white/10 rounded-2xl h-14 px-6 w-full text-white font-bold outline-none" placeholder="Terminal Handle">
                                         <input v-model="form.receiver_ip" type="text" class="bg-white/10 border border-white/10 rounded-2xl h-14 px-6 w-full text-white font-mono outline-none" placeholder="Terminal IP">
                                         <select v-model="form.receiver_model_id" class="bg-white/10 border border-white/10 rounded-2xl h-14 px-6 w-full text-white font-bold outline-none">
                                            <option value="" class="text-black">Protocol Radio Profile...</option>
                                            <option v-for="model in deviceModels" :key="model.id" :value="model.id" class="text-black">{{ model.model_name }}</option>
                                        </select>
                                     </div>
                                 </div>
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
                             <h4 class="text-lg font-bold uppercase tracking-tight">Deployment Commitment</h4>
                             <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                Synchronizing site parameters to the global infrastructure registry.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('network.towers.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-bold text-xs uppercase tracking-widest rounded-2xl hover:bg-white/20 transition-all active:scale-95"
                        >
                            Abort Deployment
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-indigo-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Initialize Site Governance
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppleLayout>
</template>
