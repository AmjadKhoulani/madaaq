<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Terminal, 
    Play, 
    Cpu, 
    ShieldAlert, 
    CheckCircle2, 
    Clock, 
    XCircle, 
    Activity, 
    Zap,
    Download,
    Trash2
} from 'lucide-vue-next';
import axios from 'axios';

const props = defineProps({
    servers: Array,
});

const selectedServer = ref('');
const command = ref('');
const isExecuting = ref(false);
const history = ref([]);

const executeCommand = async () => {
    if (!selectedServer.ref && !selectedServer.value) return;
    
    isExecuting.value = true;
    const currentCommand = command.value;
    
    try {
        const response = await axios.post(route('network.commands.execute'), {
            server_id: selectedServer.value,
            command: currentCommand
        });
        
        history.value.unshift({
            id: Date.now(),
            server: response.data.server,
            command: currentCommand,
            output: response.data.output,
            success: response.data.success,
            message: response.data.message,
            timestamp: new Date().toLocaleTimeString()
        });
        
        if (response.data.success) {
            command.value = '';
        }
    } catch (error) {
        history.value.unshift({
            id: Date.now(),
            server: 'ERR',
            command: currentCommand,
            output: 'Network Protocol Collision: ' + (error.response?.data?.message || error.message),
            success: false,
            timestamp: new Date().toLocaleTimeString()
        });
    } finally {
        isExecuting.value = false;
    }
};

const clearHistory = () => {
    history.value = [];
};

</script>

<template>
    <AppleLayout title="Pulse Terminal">
        <Head title="Direct API Extraction" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Pulse Terminal</h1>
                    <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <Terminal class="w-4 h-4" />
                        Direct API extraction and protocol injection on edge controllers
                    </p>
                </div>
                <button 
                    @click="clearHistory"
                    class="px-6 h-12 bg-white text-[#86868b] border border-black/5 rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:text-rose-500 transition-all flex items-center gap-3"
                >
                    <Trash2 class="w-4 h-4" /> Purge History
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- 1. Extraction Console -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="apple-card p-10">
                        <div class="flex items-center gap-3 mb-10">
                            <div class="w-1.5 h-6 bg-black rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Terminal Input</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Target Edge Node</label>
                                <div class="relative group">
                                    <Cpu class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-black/20 group-focus-within:text-black transition-colors" />
                                    <select v-model="selectedServer" class="apple-input h-14 pl-16 font-bold uppercase tracking-tight" required>
                                        <option value="">Choose Node...</option>
                                        <option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }} ({{ s.ip }})</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">API Protocol (Command)</label>
                                <div class="relative">
                                     <textarea 
                                        v-model="command"
                                        @keydown.enter.ctrl.prevent="executeCommand"
                                        class="apple-input py-6 min-h-[120px] font-mono font-bold text-sm tracking-tight leading-relaxed" 
                                        placeholder="/system/identity/print"
                                     ></textarea>
                                </div>
                                <p class="text-[8px] font-black text-[#86868b] uppercase tracking-[0.2em] ml-2">Hint: Use CTRL + ENTER to inject protocol</p>
                            </div>

                            <button 
                                @click="executeCommand"
                                class="w-full py-5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all disabled:opacity-50 flex items-center justify-center gap-3"
                                :disabled="isExecuting || !selectedServer || !command"
                            >
                                <Play v-if="!isExecuting" class="w-4 h-4" />
                                <Activity v-else class="w-4 h-4 animate-spin" />
                                {{ isExecuting ? 'Injecting Protocol...' : 'Execute Protocol' }}
                            </button>
                        </div>
                    </div>

                    <!-- Terminal Status Card -->
                    <div class="apple-card p-10 bg-indigo-600 text-white relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                        <div class="relative z-10 space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                                <Zap class="w-6 h-6 text-amber-300" />
                            </div>
                            <div>
                                <h4 class="text-lg font-bold uppercase tracking-tight">API Integrity</h4>
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1 leading-relaxed">Ensure valid RouterOS API syntax for successful handshake. Terminal maintains session extraction history.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Extraction Stream (Output) -->
                <div class="lg:col-span-2 space-y-8">
                    <div v-for="entry in history" :key="entry.id" class="apple-card overflow-hidden transition-all animate-in slide-in-from-top duration-500">
                        <div class="px-8 py-5 border-b border-black/[0.03] flex items-center justify-between" :class="entry.success ? 'bg-emerald-50/50' : 'bg-rose-50/50'">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white shadow-sm" :class="entry.success ? 'bg-emerald-500' : 'bg-rose-500'">
                                    <component :is="entry.success ? CheckCircle2 : XCircle" class="w-4 h-4" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-black uppercase tracking-widest flex items-center gap-2">
                                        {{ entry.server }} <span class="text-[#86868b]">•</span> <span class="font-mono">{{ entry.command }}</span>
                                    </p>
                                </div>
                            </div>
                            <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">{{ entry.timestamp }}</p>
                        </div>
                        <div class="p-8 bg-black text-[#00ff41] font-mono text-[11px] leading-relaxed overflow-x-auto whitespace-pre custom-scrollbar max-h-[400px]">
                            {{ entry.message || entry.output }}
                        </div>
                    </div>

                    <!-- Terminal Empty State -->
                    <div v-if="history.length === 0" class="apple-card py-24 flex flex-col items-center justify-center text-center opacity-50 border-dashed border-2">
                        <div class="w-20 h-20 rounded-[2.5rem] bg-black/5 flex items-center justify-center mb-6">
                            <Terminal class="w-10 h-10" />
                        </div>
                        <h4 class="text-lg font-bold uppercase tracking-tight">Terminal Standby</h4>
                        <p class="text-xs font-medium max-w-xs mx-auto mt-2">Initialize a protocol injection to view extraction output. Protocol history will materialize here during the session.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    height: 4px;
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #000;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #1a1a1a;
    border-radius: 10px;
}
</style>
