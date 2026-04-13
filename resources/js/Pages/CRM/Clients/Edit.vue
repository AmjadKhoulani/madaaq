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
    ChevronLeft,
    Box,
    ExternalLink
} from 'lucide-vue-next';

const props = defineProps({
    client: Object,
    packages: Array,
    routers: Array,
    servers: Array,
    deviceModels: Array,
});

const form = useForm({
    name: props.client.name || props.client.username,
    phone: props.client.phone || props.client.username,
    email: props.client.email || '',
    password: '', // Kept empty for edit
    service_password: '', // Kept empty or original if needed
    type: props.client.type,
    mikrotik_server_id: props.client.mikrotik_server_id || '',
    package_id: props.client.package_id || '',
    tower_id: props.client.tower_id || '',
    price: props.client.custom_price || (props.client.package?.price || ''),
    status: props.client.status,
    pppoe_username: props.client.pppoe_username || props.client.username,
    hotspot_username: props.client.hotspot_username || props.client.username,
    ssid_id: props.client.ssid_id || '',
    connection_mode: props.client.connection_mode || 'wireless',
    cpe_model: props.client.cpe_model || '',
    cpe_username: props.client.cpe_username || 'admin',
    cpe_password: props.client.cpe_password || '',
    cpe_ip: props.client.cpe_ip || '',
    cpe_mac: props.client.cpe_mac || '',
    receiver_model: props.client.receiver_model || '',
    receiver_ip: props.client.receiver_ip || '',
    receiver_username: props.client.receiver_username || 'admin',
    receiver_password: props.client.receiver_password || '',
    switch_port: props.client.switch_port || '',
    tower_device_id: props.client.tower_device_id || '',
    ip_address: props.client.ip || '',
    data_limit: props.client.custom_data_limit_mb ? (props.client.custom_data_limit_mb / 1024).toFixed(0) : '',
    lat: props.client.lat || '',
    lng: props.client.lng || '',
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
        form.data_limit = pkg.data_limit_mb ? (pkg.data_limit_mb / 1024).toFixed(0) : '';
    }
};

const updateCpeModelName = () => {
    if (!form.device_model_id) return;
    const model = props.deviceModels.find(m => m.id == form.device_model_id);
    if (model) form.cpe_model = model.name;
};

const submit = () => {
    form.put(route('crm.clients.update', props.client.id), {
        onSuccess: () => {},
    });
};

</script>

<template>
    <AppleLayout :title="'Edit ' + client.username">
        <Head :title="'Edit ' + client.username" />

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="flex items-center gap-6">
                <Link 
                    :href="route('crm.clients.show', client.id)" 
                    class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all group"
                >
                    <ChevronLeft class="w-6 h-6 group-hover:-translate-x-1 transition-transform" />
                </Link>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Synchronizing {{ client.username }}</h1>
                    <p class="text-[var(--app-secondary)] font-medium">Update subscriber parameters and network topology.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                 <Link 
                    :href="route('crm.clients.show', client.id)" 
                    class="px-6 py-3 apple-card font-bold text-sm hover:bg-black/5 transition-all active:scale-95"
                >
                    Discard Changes
                </Link>
                <div class="h-8 w-px bg-black/5 mx-2"></div>
                <div 
                    class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border"
                    :class="client.status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'"
                >
                    {{ client.status }}
                </div>
            </div>
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
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Assigned Identity</label>
                            <input v-model="form.name" type="text" placeholder="John Doe" class="apple-input h-12" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Network Username</label>
                            <input v-model="form.phone" type="tel" class="apple-input h-12 font-mono" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Update Portal PassKey</label>
                            <input v-model="form.password" type="text" placeholder="Leave empty to retain..." class="apple-input h-12">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Intelligence E-Mail</label>
                            <input v-model="form.email" type="email" class="apple-input h-12">
                        </div>
                    </div>
                </div>

                <!-- 2. Infrastructure Deployment -->
                <div class="apple-card p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-[var(--app-accent)] rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight">Infrastructure Alignment</h3>
                        </div>
                        <Link 
                            :href="route('crm.clients.show', client.id)" 
                            class="px-4 py-1.5 bg-black/5 rounded-full text-[9px] font-black uppercase tracking-widest text-[#86868b] flex items-center gap-2"
                        >
                            View Active Nodes <ExternalLink class="w-3 h-3" />
                        </Link>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Management Server</label>
                            <select v-model="form.mikrotik_server_id" class="apple-input h-12 text-sm bg-black/[0.01]">
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

                    <!-- Hardware Intelligence (Hybrid View) -->
                    <div class="space-y-8 pt-8 border-t border-black/5">
                        
                        <!-- Row 1: Primary Hardware -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">CPE Model</label>
                                <select v-model="form.device_model_id" @change="updateCpeModelName" class="apple-input h-10 text-xs">
                                    <option value="">Custom Hardware...</option>
                                    <option v-for="model in deviceModels" :key="model.id" :value="model.id">{{ model.name }}</option>
                                </select>
                                <input v-model="form.cpe_model" type="text" class="apple-input h-10 mt-2 text-xs font-bold" placeholder="Manual Override Name...">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Interior IP Address</label>
                                <input v-model="form.cpe_ip" type="text" class="apple-input h-10 font-mono text-xs" placeholder="10.x.x.x">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">CPE MAC Identity</label>
                                <input v-model="form.cpe_mac" type="text" class="apple-input h-10 font-mono text-xs" placeholder="AA:BB:CC:DD:EE:FF">
                            </div>
                        </div>

                        <!-- Row 2: Secondary Hardware (Outdoor) -->
                        <div v-show="form.connection_mode === 'wireless'" class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-emerald-50/30 rounded-2xl border border-emerald-100/50">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-emerald-600 uppercase tracking-widest ml-1">Radio Model (External)</label>
                                <input v-model="form.receiver_model" type="text" class="apple-input h-10 text-xs border-emerald-200" placeholder="e.g. SXT sq Lite5">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-emerald-600 uppercase tracking-widest ml-1">Management IP</label>
                                <input v-model="form.receiver_ip" type="text" class="apple-input h-10 font-mono text-xs border-emerald-200" placeholder="10.x.x.x">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-emerald-600 uppercase tracking-widest ml-1">Radio Auth Pass</label>
                                <input v-model="form.receiver_password" type="password" class="apple-input h-10 text-xs border-emerald-200" placeholder="••••••••">
                            </div>
                        </div>

                         <!-- Row 3: Final Provisioning -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 ">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Broadcast Target (SSID)</label>
                                <select v-model="form.ssid_id" class="apple-input h-12 text-sm" :disabled="form.connection_mode !== 'wireless'">
                                    <option value="">Select Broadcast Hub...</option>
                                    <option v-for="ssid in availableSSIDs" :key="ssid.id" :value="ssid.id">{{ ssid.ssid_name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Network Service Password</label>
                                <input v-model="form.service_password" type="text" class="apple-input h-12 font-mono text-xs bg-indigo-50/50 border-indigo-100" placeholder="Retain encrypted key...">
                                <p class="text-[8px] font-black text-indigo-400 mt-1 ml-1 uppercase">Leave blank to keep existing</p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Assigned ISP IP</label>
                                <input v-model="form.ip_address" type="text" class="apple-input h-12 font-mono text-xs text-indigo-600 border-indigo-100" placeholder="172.x.x.x">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Billing & Evaluation -->
                <div class="apple-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight">Billing Alignment</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="lg:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Service Tier (Package)</label>
                            <select v-model="form.package_id" @change="updatePackageDefaults" class="apple-input h-12 text-sm font-bold">
                                <option value="">Custom Plan (No Tier)</option>
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
                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-1">Quota (GB)</label>
                            <input v-model="form.data_limit" type="number" class="apple-input h-12 text-sm" placeholder="Unlimited">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Confirmation -->
            <div class="lg:col-span-1 space-y-6 sticky top-24">
                <div class="apple-card bg-black text-white p-8 overflow-hidden relative">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                    
                    <div class="text-white/60 text-[10px] font-black uppercase tracking-widest mb-2">Subscriber Commitment</div>
                    <div class="text-5xl font-bold tracking-tight mb-8">
                        <span class="text-white/40 text-3xl font-medium">$</span>{{ form.price || '0.00' }}
                    </div>

                    <div class="space-y-4 pt-6 border-t border-white/10 mb-8">
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">Subscriber ID</span>
                            <span class="text-xs font-bold">{{ client.username }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">Network Mode</span>
                            <span class="font-black text-[10px] uppercase text-[var(--app-accent)]">{{ form.connection_mode }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">Termination</span>
                            <span class="text-xs font-bold text-amber-500">{{ client.expires_at ? client.expires_at.split('T')[0] : 'Permanent' }}</span>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-4 bg-white text-black rounded-full font-bold shadow-xl shadow-white/10 hover:bg-gray-100 transition-all active:scale-95 flex items-center justify-center gap-2"
                        :disabled="form.processing"
                    >
                        <Save class="w-4 h-4" />
                        Save Sync Pulse
                    </button>
                    
                    <p class="mt-4 text-[8px] text-white/30 text-center uppercase tracking-widest">Updates will sync with MikroTik API instantly</p>
                </div>

                <div class="apple-card p-6 border-2 border-black/5">
                    <h4 class="text-xs font-bold flex items-center gap-2 mb-2">
                         <History class="w-4 h-4 text-[#86868b]" />
                         Provisioning History
                    </h4>
                    <ul class="space-y-3 mt-4">
                        <li v-for="activity in client.activities?.slice(0, 3)" :key="activity.id" class="text-[10px] font-medium text-[#86868b] flex gap-2">
                            <span class="shrink-0">•</span>
                            <span>{{ activity.description }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </AppleLayout>
</template>

<style scoped>
.apple-input:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
