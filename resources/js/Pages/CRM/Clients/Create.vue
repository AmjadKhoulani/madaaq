<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    User, 
    Smartphone, 
    Mail, 
    MapPin, 
    ShieldCheck, 
    Wifi, 
    Server, 
    Radio, 
    Monitor,
    CreditCard,
    Zap,
    Clock,
    History,
    Save,
    ChevronRight,
    Search
} from 'lucide-vue-next';

const props = defineProps({
    packages: Array,
    routers: Array,
    servers: Array,
    deviceModels: Array,
    lastIp: String,
});

const form = useForm({
    name: '',
    phone: '',
    email: '',
    password: '',
    service_password: '',
    type: 'pppoe',
    mikrotik_server_id: '',
    package_id: '',
    tower_id: '',
    price: '',
    pppoe_username: '',
    hotspot_username: '',
    ssid: '',
    connection_mode: 'wireless',
    cpe_model: '',
    cpe_username: 'admin',
    cpe_password: '',
    cpe_ip: '',
    receiver_model: '',
    receiver_ip: '',
    receiver_username: 'admin',
    receiver_password: '',
    switch_port: '',
    tower_device_id: '',
    ip_address: '',
    data_limit: '',
    duration_days: '30',
    lat: '',
    lng: '',
});

// Computed Data for Dynamic Filters
const availableTowers = computed(() => {
    if (!form.mikrotik_server_id) return [];
    const server = props.servers.find(s => s.id == form.mikrotik_server_id);
    return server ? server.towers : [];
});

const availableSSIDs = computed(() => {
    if (!form.tower_id) return [];
    const tower = availableTowers.value.find(t => t.id == form.tower_id);
    return tower ? tower.ssids : [];
});

const availableDevices = computed(() => {
    if (!form.tower_id) return [];
    const tower = availableTowers.value.find(t => t.id == form.tower_id);
    if (!tower) return [];
    
    return tower.devices.filter(d => {
        if (form.connection_mode === 'wireless') return d.type === 'wireless' || d.type === 'ap';
        return d.type === 'switch';
    });
});

const updatePackageDefaults = () => {
    if (!form.package_id) return;
    const pkg = props.packages.find(p => p.id == form.package_id);
    if (pkg) {
        form.price = pkg.price;
        form.duration_days = pkg.duration || 30;
        form.data_limit = pkg.data_limit_mb ? (pkg.data_limit_mb / 1024).toFixed(0) : '';
    }
};

const submit = () => {
    form.post(route('crm.clients.store'), {
        onSuccess: () => {},
    });
};

</script>

<template>
    <AppleLayout title="New Subscriber Acquisition">
        <Head title="Add Subscriber" />

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-bold tracking-tight mb-2">New Subscriber Acquisition</h1>
                <p class="text-[var(--app-secondary)] font-medium">Provision a new ISP client on the edge network.</p>
            </div>
            <Link 
                :href="route('crm.clients.index')" 
                class="px-6 py-3 apple-card font-bold text-sm hover:bg-black hover:text-white transition-all active:scale-95"
            >
                Cancel Entry
            </Link>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Left Column: Primary Config -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 1. Identity Matrix -->
                <div class="apple-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-1.5 h-6 bg-black rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight">Identity Matrix</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Legal Full Name</label>
                            <div class="relative">
                                <User class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]" />
                                <input v-model="form.name" type="text" placeholder="John Doe" class="apple-input pl-10 h-12" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Subscriber Phone (Username)</label>
                            <div class="relative">
                                <Smartphone class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]" />
                                <input v-model="form.phone" type="tel" placeholder="+963..." class="apple-input pl-10 h-12" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Intelligence E-Mail</label>
                            <div class="relative">
                                <Mail class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]" />
                                <input v-model="form.email" type="email" placeholder="john@example.com" class="apple-input pl-10 h-12">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Portal Access Key</label>
                            <div class="relative">
                                <ShieldCheck class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]" />
                                <input v-model="form.password" type="text" placeholder="High-security key..." class="apple-input pl-10 h-12" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Infrastructure Deployment -->
                <div class="apple-card p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-[var(--app-accent)] rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight">Infrastructure Deployment</h3>
                        </div>
                        <div class="flex p-1 bg-black/5 rounded-full border border-black/5">
                            <button 
                                type="button"
                                @click="form.type = 'pppoe'"
                                class="px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all"
                                :class="form.type === 'pppoe' ? 'bg-black text-white shadow-md' : 'text-[#86868b]'"
                            >Broadband</button>
                            <button 
                                type="button"
                                @click="form.type = 'hotspot'"
                                class="px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all"
                                :class="form.type === 'hotspot' ? 'bg-black text-white shadow-md' : 'text-[#86868b]'"
                            >Hotspot</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Management Server</label>
                            <select v-model="form.mikrotik_server_id" class="apple-input h-12 text-sm">
                                <option value="">Select Edge Node...</option>
                                <option v-for="server in servers" :key="server.id" :value="server.id">{{ server.name }}</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Access Tower (Hub)</label>
                            <select v-model="form.tower_id" class="apple-input h-12 text-sm" :disabled="!form.mikrotik_server_id">
                                <option value="">Select Target Hub...</option>
                                <option v-for="tower in availableTowers" :key="tower.id" :value="tower.id">
                                    {{ (tower.type === 'cabinet' ? '📦 ' : '🗼 ') + tower.name }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                             <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Provisioning Mode</label>
                             <select v-model="form.connection_mode" class="apple-input h-12 text-sm bg-blue-50/50 border-blue-100 font-bold">
                                <option value="wireless">Wireless Radio</option>
                                <option value="tower_switch">Direct Switch Port</option>
                                <option value="fiber">Optical Fiber (GPON)</option>
                                <option value="cable">Ethernet LAN</option>
                             </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-black/5">
                        <!-- Left Sub-column: Devices -->
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">
                                    {{ form.connection_mode === 'wireless' ? 'Access Point (Radio)' : 'Distribution Switch' }}
                                </label>
                                <select v-model="form.tower_device_id" class="apple-input h-12 text-sm" :disabled="!form.tower_id">
                                    <option value="">Auto-Assign Device...</option>
                                    <option v-for="device in availableDevices" :key="device.id" :value="device.id">
                                        {{ device.name }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="form.connection_mode === 'wireless'" class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Radio Frequency (SSID)</label>
                                <select v-model="form.ssid" class="apple-input h-12 text-sm" :disabled="!form.tower_id">
                                    <option value="">Select Broadcast Node...</option>
                                    <option v-for="ssid in availableSSIDs" :key="ssid.id" :value="ssid.ssid_name">{{ ssid.ssid_name }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Sub-column: Auth -->
                        <div class="space-y-6">
                             <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Network Identity (PPPoE)</label>
                                <input v-model="form.pppoe_username" type="text" class="apple-input h-12 font-mono text-xs bg-black/[0.02]" placeholder="subscriber_node_1">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Network Access Password</label>
                                <input v-model="form.service_password" type="text" class="apple-input h-12 font-mono text-xs bg-black/[0.02]" placeholder="********">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Logistics & Billing Matrix -->
                <div class="apple-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight">Logistics & Billing Matrix</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="lg:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Service Tier (Package)</label>
                            <select v-model="form.package_id" @change="updatePackageDefaults" class="apple-input h-12 text-sm font-bold">
                                <option value="">Custom Intelligence (No Tier)</option>
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                    {{ pkg.name }} — ${{ pkg.price }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Unit Valuation ($)</label>
                            <input v-model="form.price" type="number" step="0.01" class="apple-input h-12 font-mono text-sm" placeholder="0.00">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Provisioned Days</label>
                            <input v-model="form.duration_days" type="number" class="apple-input h-12 text-sm" placeholder="30">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Confirmation -->
            <div class="lg:col-span-1 space-y-6 sticky top-24">
                <div class="apple-card bg-black text-white p-8 overflow-hidden relative">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                    
                    <div class="text-white/60 text-[10px] font-black uppercase tracking-widest mb-2">Total Monthly Commitment</div>
                    <div class="text-5xl font-bold tracking-tight mb-8">
                        <span class="text-white/40 text-3xl font-medium">$</span>{{ form.price || '0.00' }}
                    </div>

                    <div class="space-y-4 pt-6 border-t border-white/10 mb-8">
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">Protocol</span>
                            <span class="text-xs font-bold uppercase">{{ form.type }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">Network Mode</span>
                            <span class="text-xs font-bold">{{ form.connection_mode }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">Duration</span>
                            <span class="text-xs font-bold">{{ form.duration_days }} Days</span>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-4 bg-white text-black rounded-full font-bold shadow-xl shadow-white/10 hover:bg-gray-100 transition-all active:scale-95 flex items-center justify-center gap-2"
                        :disabled="form.processing"
                    >
                        <Zap class="w-4 h-4 fill-black" />
                        Execute Provisioning
                    </button>
                </div>

                <div class="apple-card p-6 bg-amber-50 border-amber-100">
                    <h4 class="text-xs font-bold text-amber-900 flex items-center gap-2 mb-2">
                         <ShieldCheck class="w-4 h-4" />
                         Network Verification
                    </h4>
                    <p class="text-[10px] text-amber-800/70 font-medium leading-relaxed">
                        By executing provisioning, credentials will be synchronized with the selected edge node. Ensure the tower capacity is verified before deployment.
                    </p>
                </div>
            </div>
        </form>
    </AppleLayout>
</template>
