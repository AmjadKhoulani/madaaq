<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import axios from 'axios';
import { 
    Activity, 
    Cpu, 
    HardDrive, 
    Zap, 
    Users, 
    Settings, 
    FileText, 
    Download, 
    RefreshCw, 
    Terminal, 
    Globe, 
    Shield, 
    CheckCircle2,
    XCircle,
    Copy,
    ChevronLeft,
    Clock,
    Database,
    Radio,
    Wifi,
    ExternalLink
} from 'lucide-vue-next';

const props = defineProps({
    server: Object,
});

const activeTab = ref('general');
const isConnected = ref(props.server.is_connected);
const statusData = ref(null);
const setupScript = ref('');
const scriptCopied = ref(false);
const pollingInterval = ref(null);
const isTesting = ref(false);
const testResult = ref(null);

const tabs = [
    { id: 'general', name: 'Identity Matrix', icon: Globe },
    { id: 'users', name: 'Session Registry', icon: Users },
    { id: 'tools', name: 'Strategic Tools', icon: Settings },
    { id: 'config', name: 'Core Protocol', icon: FileText },
    { id: 'backups', name: 'Recovery Log', icon: HardDrive },
];

const fetchStatus = async () => {
    try {
        const response = await axios.get(route('servers.status', props.server.id));
        if (response.data.success) {
            statusData.value = response.data.data;
            isConnected.value = true;
        } else {
            isConnected.value = false;
        }
    } catch (error) {
        isConnected.value = false;
    }
};

const fetchSetupScript = async () => {
    if (setupScript.value) return;
    try {
        const response = await axios.get(route('servers.setup-script', props.server.id));
        setupScript.value = response.data.script;
    } catch (error) {
        setupScript.value = '# Error generating protocol stack.';
    }
};

const copyScript = () => {
    navigator.clipboard.writeText(setupScript.value);
    scriptCopied.value = true;
    setTimeout(() => scriptCopied.value = false, 3000);
};

const startPolling = () => {
    fetchStatus();
    pollingInterval.value = setInterval(fetchStatus, 30000);
};

const stopPolling = () => {
    if (pollingInterval.value) {
        clearInterval(pollingInterval.value);
    }
};

const testConnection = async () => {
    isTesting.value = true;
    testResult.value = null;
    try {
        const response = await axios.post(route('servers.test-connection', props.server.id));
        testResult.value = {
            success: response.data.success,
            message: response.data.message
        };
        if (response.data.success) {
            setTimeout(() => router.reload(), 2000);
        }
    } catch (error) {
        testResult.value = {
            success: false,
            message: 'Handshake protocol failed.'
        };
    } finally {
        isTesting.value = false;
    }
};

const importData = async (type) => {
    let endpoint = '';
    switch(type) {
        case 'pppoe': endpoint = route('servers.import-pppoe', props.server.id); break;
        case 'hotspot': endpoint = route('servers.import-hotspot', props.server.id); break;
        case 'pppoe-profiles': endpoint = route('servers.import-pppoe-profiles', props.server.id); break;
        case 'hotspot-profiles': endpoint = route('servers.import-hotspot-profiles', props.server.id); break;
    }

    try {
        const response = await axios.post(endpoint);
        alert(response.data.success ? '✅ ' + response.data.message : '❌ ' + response.data.message);
        if (response.data.success) router.reload();
    } catch (error) {
        alert('❌ Assimilation command failed.');
    }
};

onMounted(() => {
    startPolling();
});

onUnmounted(() => {
    stopPolling();
});

const getMemPercent = computed(() => {
    if (!statusData.value) return 0;
    return (((statusData.value.total_memory - statusData.value.free_memory) / statusData.value.total_memory) * 100).toFixed(0);
});

</script>

<template>
    <AppleLayout :title="server.name">
        <Head :title="server.name" />

        <!-- Command Center Header -->
        <div class="apple-card p-10 mb-10 overflow-hidden relative">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row items-center gap-10">
                <!-- Hardware Visual -->
                <div class="relative group">
                    <div class="w-48 h-48 bg-black/5 rounded-[2.5rem] border border-black/5 flex items-center justify-center p-8 group-hover:scale-105 transition-all duration-700">
                        <Server class="w-24 h-24 text-black/40" />
                    </div>
                    <div class="absolute -bottom-2 -right-2">
                        <div 
                            class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border-2 border-white shadow-xl"
                            :class="isConnected ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'"
                        >
                            {{ isConnected ? 'Synchronized' : 'Offline' }}
                        </div>
                    </div>
                </div>

                <!-- Identity Matrix -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-4 justify-center lg:justify-start">
                        <h1 class="text-4xl font-bold tracking-tight">{{ server.name }}</h1>
                        <span class="px-4 py-1.5 bg-black/5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-black/5">
                            {{ server.device_model?.model_name || 'MikroTik Core' }}
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                         <div class="px-5 py-2.5 apple-card text-xs font-mono font-bold flex items-center gap-2">
                            <Globe class="w-3.5 h-3.5 text-[#86868b]" />
                            {{ server.ip }}
                         </div>
                         <div class="px-5 py-2.5 apple-card text-xs font-mono font-bold flex items-center gap-2">
                            <Activity class="w-3.5 h-3.5 text-[#86868b]" />
                            API:{{ server.api_port }}
                         </div>
                         <div class="px-5 py-2.5 apple-card text-xs font-bold flex items-center gap-2 uppercase tracking-tight">
                            <MapPin class="w-3.5 h-3.5 text-[#86868b]" />
                            {{ server.location || 'Edge Node' }}
                         </div>
                    </div>
                </div>

                <!-- Strategic Access -->
                <div class="shrink-0">
                    <Link 
                        :href="route('servers.edit', server.id)" 
                        class="px-8 py-4 bg-black text-white rounded-full font-bold text-xs uppercase tracking-widest shadow-xl hover:bg-gray-800 transition-all flex items-center gap-3"
                    >
                        <Settings class="w-4 h-4" />
                        System Protocol
                    </Link>
                </div>
            </div>
        </div>

        <!-- Live Telemetry -->
        <div v-if="isConnected && statusData" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- CPU Load -->
            <div class="apple-card p-6 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Compute Load</p>
                        <h3 class="text-3xl font-bold tracking-tight">{{ statusData.cpu_load }}%</h3>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                        <Cpu class="w-6 h-6" />
                    </div>
                </div>
                <div class="w-full bg-black/5 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-600 h-full transition-all duration-700" :style="{ width: statusData.cpu_load + '%' }"></div>
                </div>
            </div>

            <!-- RAM State -->
            <div class="apple-card p-6 group">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Active Memory</p>
                        <h3 class="text-xl font-bold tracking-tight">{{ Math.round(statusData.free_memory / 1024 / 1024) }} / {{ Math.round(statusData.total_memory / 1024 / 1024) }} MB</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                        <HardDrive class="w-6 h-6" />
                    </div>
                </div>
                <div class="w-full bg-black/5 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full transition-all duration-700" :style="{ width: getMemPercent + '%' }"></div>
                </div>
            </div>

            <!-- PPPoE Matrix -->
            <div class="apple-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">PPPoE Registry</p>
                        <h3 class="text-3xl font-bold tracking-tight">{{ statusData.active_pppoe }}</h3>
                        <p class="text-[8px] font-black text-blue-600 uppercase mt-1">Live Sessions</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                        <Wifi class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- Hotspot Base -->
            <div class="apple-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Hotspot Guard</p>
                        <h3 class="text-3xl font-bold tracking-tight">{{ statusData.active_hotspot }}</h3>
                        <p class="text-[8px] font-black text-amber-600 uppercase mt-1">Vouchers Active</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                        <Radio class="w-6 h-6" />
                    </div>
                </div>
            </div>
        </div>

        <!-- System Intelligence Registry -->
        <div class="apple-card overflow-hidden min-h-[600px] mb-12">
            <!-- Strategic Navigator -->
            <div class="border-b border-black/5 bg-black/[0.01] p-2">
                <nav class="flex overflow-x-auto no-scrollbar gap-1">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id; if(tab.id === 'config') fetchSetupScript();"
                        class="px-6 py-3.5 rounded-xl font-bold text-[10px] uppercase tracking-widest transition-all flex items-center gap-3 whitespace-nowrap"
                        :class="activeTab === tab.id ? 'bg-black text-white shadow-lg' : 'text-[#86868b] hover:bg-black/5 hover:text-black'"
                    >
                        <component :is="tab.icon" class="w-4 h-4" />
                        {{ tab.name }}
                    </button>
                </nav>
            </div>

            <!-- Center Content -->
            <div class="p-8 md:p-12">
                <!-- General Identity -->
                <div v-if="activeTab === 'general'" class="space-y-12 animate-in fade-in duration-500">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div class="space-y-8">
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-5 bg-black rounded-full"></div>
                                <h4 class="text-sm font-bold tracking-tight uppercase">Hardware Architecture</h4>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-6 bg-black/[0.02] rounded-3xl border border-black/5">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Architecture</p>
                                    <p class="font-bold text-sm" v-if="statusData">{{ statusData.version }}</p>
                                    <p class="font-bold text-sm text-[#86868b]" v-else>Loading Registry...</p>
                                </div>
                                <div class="p-6 bg-black/[0.02] rounded-3xl border border-black/5">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Uptime Pulse</p>
                                    <p class="font-mono font-bold text-sm" v-if="statusData">{{ statusData.uptime }}</p>
                                    <p class="font-bold text-sm text-[#86868b]" v-else>--:--:--</p>
                                </div>
                                <div class="p-6 bg-black/[0.02] rounded-3xl border border-black/5">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Access Root</p>
                                    <p class="font-bold text-sm">{{ server.username }}</p>
                                </div>
                                <div class="p-6 bg-black/[0.02] rounded-3xl border border-black/5">
                                    <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Governed Sites</p>
                                    <p class="font-bold text-sm text-indigo-600">{{ server.towers_count || 0 }} Distribution Hubs</p>
                                </div>
                            </div>
                        </div>

                        <!-- DNS Matrix (Top Sites) -->
                        <div v-if="statusData?.top_sites?.length > 0" class="space-y-8">
                             <div class="flex items-center gap-3">
                                <div class="w-1 h-5 bg-black rounded-full"></div>
                                <h4 class="text-sm font-bold tracking-tight uppercase">Intelligence Flux (DNS)</h4>
                            </div>
                            <div class="space-y-4 apple-card p-8 border-black/5 bg-black/[0.01]">
                                <div v-for="site in statusData.top_sites.slice(0, 5)" :key="site.domain" class="space-y-1.5">
                                    <div class="flex justify-between text-[10px] font-bold">
                                        <span class="text-[#86868b] font-mono">{{ site.domain }}</span>
                                        <span class="text-black">{{ site.hits }} Events</span>
                                    </div>
                                    <div class="w-full bg-black/5 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-black/40 h-full transition-all duration-1000" :style="{ width: (site.hits / statusData.top_sites[0].hits * 100) + '%' }"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Session Registry -->
                <div v-if="activeTab === 'users'" class="space-y-8 animate-in fade-in duration-500">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-1 h-5 bg-black rounded-full"></div>
                            <h4 class="text-sm font-bold tracking-tight uppercase">Live Active Sessions</h4>
                        </div>
                        <div class="flex gap-2">
                             <div class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-100">
                                PPPoE Pool: {{ statusData?.active_pppoe || 0 }}
                             </div>
                             <div class="px-4 py-1.5 bg-amber-50 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-100">
                                Hotspot Array: {{ statusData?.active_hotspot || 0 }}
                             </div>
                        </div>
                    </div>

                    <div class="apple-card overflow-hidden border-black/5">
                        <table class="w-full text-left">
                            <thead class="bg-black/[0.02] border-b border-black/5">
                                <tr>
                                    <th class="px-8 py-5 text-[9px] font-black text-[#86868b] uppercase tracking-widest">Subscriber Identity</th>
                                    <th class="px-8 py-5 text-[9px] font-black text-[#86868b] uppercase tracking-widest">Virtual IP</th>
                                    <th class="px-8 py-5 text-[9px] font-black text-[#86868b] uppercase tracking-widest">Protocol</th>
                                    <th class="px-8 py-5 text-[9px] font-black text-[#86868b] uppercase tracking-widest">Signal Pulse</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5">
                                <template v-if="statusData?.pppoe_users?.length || statusData?.hotspot_users?.length">
                                    <tr v-for="user in statusData.pppoe_users" :key="user.user" class="hover:bg-black/[0.01] transition-colors">
                                        <td class="px-8 py-5 font-bold text-sm">{{ user.user }}</td>
                                        <td class="px-8 py-5 font-mono text-[11px] text-[#86868b]">{{ user.address }}</td>
                                        <td class="px-8 py-5">
                                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest">PPPoE</span>
                                        </td>
                                        <td class="px-8 py-5 font-mono text-[10px] text-[#86868b]">{{ user.uptime }}</td>
                                    </tr>
                                    <tr v-for="user in statusData.hotspot_users" :key="user.user" class="hover:bg-black/[0.01] transition-colors">
                                        <td class="px-8 py-5 font-bold text-sm">{{ user.user }}</td>
                                        <td class="px-8 py-5 font-mono text-[11px] text-[#86868b]">{{ user.address }}</td>
                                        <td class="px-8 py-5">
                                            <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[9px] font-black uppercase tracking-widest">Hotspot</span>
                                        </td>
                                        <td class="px-8 py-5 font-mono text-[10px] text-[#86868b]">{{ user.uptime }}</td>
                                    </tr>
                                </template>
                                <tr v-else>
                                    <td colspan="4" class="px-8 py-32 text-center text-[#86868b] font-bold text-xs uppercase tracking-widest">
                                        No Packets Detected in Signal Array
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Strategic Tools -->
                <div v-if="activeTab === 'tools'" class="space-y-12 animate-in fade-in duration-500">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Connection Pulse -->
                        <div class="apple-card p-10 space-y-6 flex flex-col h-full border-black/5">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                                    <Zap class="w-7 h-7" />
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold tracking-tight">Handshake Protocol</h4>
                                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">API Diagnostics</p>
                                </div>
                            </div>
                            <p class="text-sm text-[#86868b] leading-relaxed flex-grow">Verify the encrypted signaling between MadaaQ Central and this edge node's API gateway.</p>
                            <button 
                                @click="testConnection"
                                class="w-full py-4 bg-black text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-gray-800 transition-all"
                                :disabled="isTesting"
                            >
                                {{ isTesting ? 'Handshaking...' : 'Execute Signal Test' }}
                            </button>
                            <div v-if="testResult" class="p-4 rounded-xl text-center text-[10px] font-black uppercase tracking-widest" :class="testResult.success ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100'">
                                {{ testResult.message }}
                            </div>
                        </div>

                        <!-- Data Mirroring -->
                        <div class="apple-card p-10 bg-black text-white space-y-8 h-full relative overflow-hidden">
                             <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                             <div class="flex items-center gap-4 relative z-10">
                                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center border border-white/10">
                                    <Download class="w-7 h-7" />
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold tracking-tight">Assimilate Resources</h4>
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest">Infrastructure Sync</p>
                                </div>
                            </div>
                            <p class="text-sm text-white/40 leading-relaxed relative z-10">Mirror existing RouterOS profiles and user identities into the local MadaaQ database.</p>
                            <div class="grid grid-cols-1 gap-3 relative z-10">
                                <button @click="importData('pppoe')" class="px-6 py-4 bg-white/10 hover:bg-white text-white hover:text-black rounded-xl text-[9px] font-black uppercase tracking-widest transition-all text-left flex items-center justify-between group">
                                    Migrate PPPoE Matrix <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                                </button>
                                <button @click="importData('hotspot')" class="px-6 py-4 bg-white/10 hover:bg-white text-white hover:text-black rounded-xl text-[9px] font-black uppercase tracking-widest transition-all text-left flex items-center justify-between group">
                                    Mirror Hotspot Stack <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                                </button>
                                <button @click="importData('pppoe-profiles')" class="px-6 py-4 bg-white/10 hover:bg-white text-white hover:text-black rounded-xl text-[9px] font-black uppercase tracking-widest transition-all text-left flex items-center justify-between group">
                                    Sync Policy Profiles <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Core Protocol (Terminal) -->
                <div v-if="activeTab === 'config'" class="space-y-10 animate-in fade-in duration-500">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                                <Terminal class="w-6 h-6" />
                            </div>
                            <h4 class="text-xl font-bold tracking-tight uppercase">Infrastructure Provisioning Stack</h4>
                        </div>
                        <button 
                            @click="copyScript"
                            class="px-8 py-4 bg-black text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest shadow-xl hover:scale-105 transition-all flex items-center gap-3"
                        >
                            <component :is="scriptCopied ? CheckCircle2 : Copy" class="w-4 h-4" />
                            {{ scriptCopied ? 'Stack Copied to Buffer' : 'Copy Instruction Matrix' }}
                        </button>
                    </div>

                    <div class="rounded-[2.5rem] bg-[#0c0c0d] border border-white/5 p-4 shadow-2xl relative group overflow-hidden" dir="ltr">
                        <div class="p-8 max-h-[500px] overflow-y-auto no-scrollbar font-mono text-sm leading-relaxed text-[#00ff41] bg-black/40 rounded-[2rem] border border-white/5">
                            <pre class="whitespace-pre-wrap">{{ setupScript || 'Generating high-integrity protocol commands...' }}</pre>
                        </div>
                    </div>

                    <!-- Steps -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                         <div v-for="(step, i) in ['Capture Stack', 'Open Winbox', 'Inject Terminal', 'Await Pulse']" :key="step" class="apple-card p-6 flex flex-col items-center justify-center text-center gap-2">
                            <span class="text-[10px] font-black text-black/20 uppercase tracking-widest">Step 0{{ i+1 }}</span>
                            <span class="font-bold text-xs">{{ step }}</span>
                         </div>
                    </div>
                </div>

                <!-- Recovery Log (Backups) -->
                <div v-if="activeTab === 'backups'" class="space-y-8 animate-in fade-in duration-500">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-5 bg-black rounded-full"></div>
                        <h4 class="text-sm font-bold tracking-tight uppercase">System Integrity Snapshots</h4>
                    </div>

                    <div v-if="server.backups?.length > 0" class="apple-card overflow-hidden border-black/5">
                         <table class="w-full text-left">
                            <thead class="bg-black/[0.02] border-b border-black/5">
                                <tr>
                                    <th class="px-8 py-5 text-[9px] font-black text-[#86868b] uppercase tracking-widest">Snapshot Entity</th>
                                    <th class="px-8 py-5 text-[9px] font-black text-[#86868b] uppercase tracking-widest">Protocol</th>
                                    <th class="px-8 py-5 text-[9px] font-black text-[#86868b] uppercase tracking-widest">Evaluation Date</th>
                                    <th class="px-8 py-5 text-right text-[9px] font-black text-[#86868b] uppercase tracking-widest">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5">
                                <tr v-for="backup in server.backups" :key="backup.id" class="hover:bg-black/[0.01] transition-colors">
                                    <td class="px-8 py-5 font-bold text-sm">{{ backup.filename }}</td>
                                    <td class="px-8 py-5">
                                        <span 
                                            class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest"
                                            :class="backup.type === 'backup' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600'"
                                        >
                                            {{ backup.type === 'backup' ? 'Binary' : 'Script' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-[#86868b] text-xs font-mono">{{ backup.created_at.split('T')[0] }}</td>
                                    <td class="px-8 py-5 text-right">
                                        <a 
                                            :href="route('servers.backups.download', backup.id)" 
                                            class="inline-flex items-center gap-2 px-5 py-2 bg-black text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-all"
                                        >
                                            <Download class="w-3.5 h-3.5" />
                                            Retrieve
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="apple-card py-32 flex flex-col items-center justify-center border-dashed border-2 border-black/10 text-[#86868b]">
                        <Database class="w-12 h-12 mb-4 text-black/10" />
                        <h5 class="text-sm font-bold uppercase tracking-widest">No Backups Detected</h5>
                        <p class="text-[10px] uppercase font-bold text-black/30 mt-1">Automated vault snapshots are pending initialization.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
