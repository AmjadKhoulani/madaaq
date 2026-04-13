<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import axios from 'axios';
import { 
    Plus, 
    Server, 
    Activity, 
    Globe, 
    Settings, 
    Shield, 
    Trash2, 
    Wifi, 
    Zap,
    Cpu,
    HardDrive,
    Terminal,
    ChevronRight,
    MapPin
} from 'lucide-vue-next';

const props = defineProps({
    servers: Array,
});

const testingStatus = ref({});

const testConnection = async (serverId) => {
    testingStatus.value[serverId] = 'testing';
    
    try {
        const response = await axios.post(route('servers.test-connection', serverId));
        if (response.data.success) {
            testingStatus.value[serverId] = 'success';
        } else {
            testingStatus.value[serverId] = 'error';
        }
    } catch (error) {
        testingStatus.value[serverId] = 'error';
    } finally {
        setTimeout(() => {
            delete testingStatus.value[serverId];
        }, 3000);
    }
};

const deleteServer = (serverId) => {
    if (confirm('Initiate site decommissioning protocol? This will remove the core node from governance.')) {
        router.delete(route('servers.destroy', serverId));
    }
};
</script>

<template>
    <AppleLayout title="Core Node Fleet">
        <Head title="Core Node Fleet" />

        <!-- Header Shell -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
            <div>
                <h1 class="text-4xl font-bold tracking-tight mb-2">Core Node Fleet</h1>
                <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Primary Network Infrastructure Management
                </p>
            </div>
            <Link 
                :href="route('servers.create')" 
                class="px-8 py-4 bg-black text-white rounded-full font-bold text-sm shadow-2xl hover:bg-gray-800 transition-all active:scale-95 flex items-center gap-2"
            >
                <Plus class="w-5 h-5" />
                Deploy New Node
            </Link>
        </div>

        <!-- Node Matrix -->
        <div v-if="servers.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div 
                v-for="server in servers" 
                :key="server.id" 
                class="apple-card group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col min-h-[480px]"
            >
                <!-- Card Inner -->
                <div class="p-8 flex-grow">
                    <!-- Identity Header -->
                    <div class="flex items-start justify-between mb-10">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-black/5 rounded-[2rem] flex items-center justify-center border border-black/5 group-hover:scale-110 transition-transform duration-500">
                                <Server class="w-10 h-10 text-black/60" />
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold tracking-tight mb-2 truncate max-w-[150px]">{{ server.name }}</h3>
                                <div class="px-3 py-1 bg-black/5 rounded-full inline-flex items-center gap-2 border border-black/5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-black/40"></span>
                                    <span class="text-[10px] font-mono font-bold text-black/60">{{ server.ip }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Orb -->
                        <div class="relative">
                            <div 
                                class="w-12 h-12 rounded-2xl flex items-center justify-center border transition-all duration-500"
                                :class="{
                                    'bg-emerald-50 text-emerald-600 border-emerald-100': !testingStatus[server.id],
                                    'bg-blue-50 text-blue-600 border-blue-100 animate-spin': testingStatus[server.id] === 'testing',
                                    'bg-rose-50 text-rose-600 border-rose-100': testingStatus[server.id] === 'error',
                                    'bg-emerald-100 text-emerald-700 border-emerald-200 scale-110': testingStatus[server.id] === 'success'
                                }"
                            >
                                <Zap v-if="!testingStatus[server.id]" class="w-6 h-6 fill-current" />
                                <Activity v-else-if="testingStatus[server.id] === 'testing'" class="w-6 h-6" />
                                <Shield v-else-if="testingStatus[server.id] === 'error'" class="w-6 h-6" />
                                <CheckCircle2 v-else class="w-6 h-6" />
                            </div>
                        </div>
                    </div>

                    <!-- Telemetry -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="p-5 bg-black/[0.02] rounded-[2rem] border border-black/5">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Administrative Port</p>
                            <p class="text-xs font-bold">{{ server.api_port }} (Legacy)</p>
                        </div>
                        <div class="p-5 bg-black/[0.02] rounded-[2rem] border border-black/5">
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest mb-1">Deployment Site</p>
                            <p class="text-xs font-bold flex items-center gap-1.5">
                                <MapPin class="w-3 h-3 text-[var(--app-accent)]" />
                                {{ server.location || 'Root Site' }}
                            </p>
                        </div>
                    </div>

                    <!-- Actions Area -->
                    <button 
                        @click="testConnection(server.id)"
                        class="w-full py-4 bg-white border border-black/10 rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-black hover:text-white transition-all active:scale-95 flex items-center justify-center gap-2"
                        :disabled="testingStatus[server.id] === 'testing'"
                    >
                        <Terminal class="w-4 h-4" />
                        {{ testingStatus[server.id] === 'testing' ? 'Handshaking...' : 'Execute Pulse Test' }}
                    </button>
                </div>

                <!-- Footer Interface -->
                <div class="p-6 bg-black/[0.01] border-t border-black/5 grid grid-cols-2 gap-4">
                    <Link 
                        :href="route('servers.show', server.id)" 
                        class="py-4 bg-black text-white text-center font-bold text-[10px] uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-all active:scale-95"
                    >
                        Network Console
                    </Link>
                    <Link 
                        :href="route('servers.edit', server.id)" 
                        class="py-4 bg-white border border-black/10 text-center font-bold text-[10px] uppercase tracking-widest rounded-xl hover:bg-black hover:text-white transition-all active:scale-95"
                    >
                        Node Config
                    </Link>
                    <button 
                        @click="deleteServer(server.id)"
                        class="col-span-2 py-3 text-[9px] font-black uppercase tracking-widest text-rose-500 hover:text-rose-700 transition-colors"
                    >
                        Decommission Site Node
                    </button>
                </div>
            </div>

            <!-- Expansion Slot -->
            <Link 
                :href="route('servers.create')" 
                class="apple-card border-2 border-dashed border-black/10 flex flex-col items-center justify-center text-[#86868b] hover:border-black/20 hover:text-black transition-all duration-500 group min-h-[480px]"
            >
                <div class="w-16 h-16 rounded-3xl bg-black/5 flex items-center justify-center border border-black/5 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 mb-6">
                    <Plus class="w-8 h-8" />
                </div>
                <span class="font-bold text-xs uppercase tracking-widest">Deploy Strategic Node</span>
            </Link>
        </div>

        <!-- Empty Fleet -->
        <div v-else class="apple-card p-32 text-center">
            <div class="w-24 h-24 bg-black/5 rounded-[3rem] flex items-center justify-center mx-auto mb-10 border border-black/5">
                <HardDrive class="w-12 h-12 text-black/20" />
            </div>
            <h2 class="text-3xl font-bold tracking-tight mb-4 uppercase">Fleet Registry Empty</h2>
            <p class="text-[var(--app-secondary)] font-medium max-w-md mx-auto mb-10 text-sm">
                No active MikroTik nodes detected. Synchronize your first site node to initialize network governance.
            </p>
            <Link 
                :href="route('servers.create')" 
                class="inline-flex items-center gap-3 px-10 py-4 bg-black text-white rounded-full font-bold text-sm shadow-xl hover:bg-gray-800 transition-all active:scale-95"
            >
                <Zap class="w-4 h-4 fill-white" />
                Initialize Site Core
            </Link>
        </div>
    </AppleLayout>
</template>
