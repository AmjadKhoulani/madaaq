<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import axios from 'axios';
import { 
    Activity,
    CheckCircle2,
    Cpu,
    GitBranch,
    MapPin,
    Plus,
    Power,
    RefreshCcw,
    Server,
    Settings2,
    Terminal,
    Wifi,
    XCircle,
    Zap
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
        }, 5000);
    }
};

const deleteServer = (serverId) => {
    if (confirm('تأكيد إخراج وحدة المعالجة المركزية (Core) من الخدمة؟ سيؤدي هذا الإجراء إلى انقطاع التنسيق البرمجي الفوري مع كافة عقد الشبكة المرتبطة.')) {
        router.delete(route('servers.destroy', serverId), {
            preserveScroll: true,
        });
    }
};

const formatNumber = (num) => {
    return new Intl.NumberFormat('en-US').format(num);
};
</script>

<template>
    <InstitutionalLayout title="حوكمة العقد المركزية">
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-vendor rounded-full"></span>
                        <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Core Node Matrix</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">حوكمة <span class="text-vendor">العقد</span> المركزية</h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">إدارة وحدات المعالجة المركزية (Core Nodes) وتأمين المسارات</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="glass-card px-6 py-3 bg-white/40 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-vendor/10 flex items-center justify-center text-vendor shadow-sm">
                            <GitBranch class="w-5 h-5 stroke-[2.5]" />
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">إجمالي العقد</p>
                            <p class="text-xl font-black text-slate-700 font-inter">{{ formatNumber(servers.length) }}</p>
                        </div>
                    </div>
                    <Link :href="route('servers.create')" class="btn-radiant btn-vendor px-8 py-4 text-xs font-black uppercase tracking-[0.2em] flex items-center gap-3">
                        <Plus class="w-5 h-5 stroke-[3]" />
                        حقن وحدة معالجة
                    </Link>
                </div>
            </div>

            <!-- The Node Matrix Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <div 
                    v-for="server in servers" 
                    :key="server.id" 
                    class="glass-card group relative p-8 bg-white/40 overflow-hidden"
                >
                    <!-- Tactical Status Bar -->
                    <div class="flex items-center justify-between mb-8 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-vendor flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 border border-white/10">
                             <Cpu class="w-7 h-7 stroke-[2]" />
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-black text-slate-400 font-inter tracking-widest uppercase">ACTIVE_CORE</span>
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/40 animate-pulse"></div>
                        </div>
                    </div>

                    <!-- Core Identification -->
                    <div class="text-right mb-8 relative z-10">
                        <h3 class="text-2xl font-black text-slate-900 italic tracking-tighter truncate group-hover:translate-x-2 transition-transform uppercase">{{ server.name }}</h3>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1 italic font-inter opacity-60">
                            {{ server.device_model?.model_name || 'MikroTik Core Infrastructure' }}
                        </p>
                    </div>

                    <!-- Technical Metrics -->
                    <div class="grid grid-cols-2 gap-4 mb-8 p-6 bg-white/60 rounded-2xl border border-white/40 shadow-inner group-hover:bg-vendor/5 transition-all duration-500 relative z-10 font-inter">
                        <div class="text-right">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">IP_ADDRESS</p>
                            <p class="text-sm font-black text-vendor tracking-tight">{{ server.ip }}</p>
                        </div>
                        <div class="text-right border-r border-slate-200 pr-4">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">API_PORT</p>
                            <p class="text-sm font-black text-slate-600 tracking-tight">{{ server.api_port }}</p>
                        </div>
                    </div>

                    <!-- Geographic Anchoring -->
                    <div class="flex items-center gap-3 justify-end mb-10 text-right px-1 relative z-10">
                        <div>
                             <p class="text-[13px] font-black text-slate-700 leading-none mb-1">{{ server.location || 'Sovereign Node Site' }}</p>
                             <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest italic font-inter">NODE_TOPOLOGY_ORIGIN</p>
                        </div>
                        <div class="w-9 h-9 bg-white shadow-sm rounded-xl flex items-center justify-center border border-slate-100">
                            <MapPin class="w-4 h-4 text-vendor stroke-[3]" />
                        </div>
                    </div>

                    <!-- Interactive Diagnostic -->
                    <div class="flex flex-col gap-3 relative z-10">
                        <button 
                            @click="testConnection(server.id)"
                            :disabled="testingStatus[server.id] === 'testing'"
                            class="w-full h-14 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all border flex items-center justify-center gap-3 active:scale-95 group/btn"
                            :class="{
                                'bg-white/50 text-slate-400 border-white/60 hover:border-vendor hover:text-vendor hover:bg-white': !testingStatus[server.id],
                                'bg-vendor/10 text-vendor border-vendor/20 animate-pulse': testingStatus[server.id] === 'testing',
                                'bg-rose-500/10 text-rose-500 border-rose-500/20': testingStatus[server.id] === 'error',
                                'bg-emerald-500/10 text-emerald-600 border-emerald-500/20': testingStatus[server.id] === 'success'
                            }"
                        >
                            <RefreshCcw v-if="testingStatus[server.id] === 'testing'" class="w-4 h-4 animate-spin" />
                            <XCircle v-else-if="testingStatus[server.id] === 'error'" class="w-4 h-4" />
                            <CheckCircle2 v-else-if="testingStatus[server.id] === 'success'" class="w-4 h-4" />
                            <Zap v-else class="w-4 h-4 group-hover/btn:scale-125 transition-transform" />
                            
                            {{ testingStatus[server.id] === 'testing' ? 'Testing Protocol...' : (testingStatus[server.id] === 'error' ? 'Path Failure' : (testingStatus[server.id] === 'success' ? 'Node Stable' : 'Check Connectivity')) }}
                        </button>

                        <div class="flex items-center gap-3">
                             <Link :href="route('servers.show', server.id)" class="flex-1 h-14 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-vendor transition-all active:scale-95 group/icon">
                                <Terminal class="w-5 h-5 group-hover/icon:scale-110 transition-transform" />
                                <span class="mr-2 text-[9px] font-black uppercase tracking-widest font-inter">Console</span>
                             </Link>
                             <Link :href="route('servers.edit', server.id)" class="w-14 h-14 bg-white shadow-sm border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-500 transition-all active:scale-95">
                                <Settings2 class="w-5 h-5" />
                             </Link>
                             <button @click="deleteServer(server.id)" class="w-14 h-14 bg-white shadow-sm border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 transition-all active:scale-95">
                                <Power class="w-5 h-5" />
                             </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="servers.length === 0" class="col-span-full py-40 flex flex-col items-center gap-8 text-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-slate-900 text-vendor flex items-center justify-center border-8 border-white/20 shadow-2xl relative group">
                        <div class="absolute inset-0 bg-vendor/20 opacity-20 blur-2xl animate-pulse"></div>
                        <Server class="w-12 h-12 relative z-10" />
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 italic tracking-tighter uppercase">مصفوفة العقد غير مكتملة (Null Fleet)</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mt-3 opacity-70 italic font-inter">No processing nodes found in the current tactical matrix.</p>
                    </div>
                    <Link :href="route('servers.create')" class="btn-radiant btn-vendor px-12 py-5 text-xs font-black uppercase tracking-[0.3em]">
                        تأسيس النواة الأولى
                    </Link>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.glass-card {
    @apply border border-white/40 shadow-glass rounded-[2.5rem] transition-all duration-500;
}
.glass-card:hover {
    @apply border-white/60 shadow-radiant -translate-y-1;
}
</style>
