<script setup>
import { ref, onMounted, Link, router, Head } from '@inertiajs/vue3';
import { onUnmounted, computed } from 'vue';
import {  } from 'lucide-vue-next';;
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import axios from 'axios';

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
    { id: 'general', name: 'هوية النواة', icon: 'dns' },
    { id: 'users', name: 'سجل النبض الحي', icon: 'dynamic_feed' },
    { id: 'tools', name: 'المصفوفات الإجرائية', icon: 'rule_settings' },
    { id: 'config', name: 'بروتوكولات التأسيس', icon: 'terminal' },
    { id: 'backups', name: 'أرشيف الاسترداد', icon: 'history_edu' },
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
        setupScript.value = '# خطأ في استخراج حزمة الأوامر البرمجية.';
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
            message: 'فشل اختبار التزامن مع العقدة الطرفية.'
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
        if (response.data.success) {
            router.reload();
        }
    } catch (error) {
        // Error handling
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
    <InstitutionalLayout :title="server.name">
        <Head :title="server.name" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Command GitBranch Header -->
            <div class="surface-card p-12 mb-10 overflow-hidden relative rounded-[2.5rem] shadow-2xl border border-outline-variant/10 bg-white">
                <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12 flex-row-reverse">
                    <!-- Hardware Topology Visual -->
                    <div class="relative group shrink-0">
                        <div class="w-56 h-56 bg-slate-950 rounded-[3rem] flex items-center justify-center p-12 group-hover:scale-105 group-hover:rotate-3 transition-all duration-1000 shadow-[0_0_50px_rgba(2,6,23,0.1)] border border-white/10 relative overflow-hidden">
                            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/40 to-transparent"></div>
                            <span class="material-symbols-outlined text-[100px] text-white/40 relative z-10" style="font-variation-settings: 'wght' 100">router</span>
                        </div>
                        <div class="absolute -bottom-4 -left-4">
                            <div 
                                class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] border-4 border-white shadow-2xl flex items-center gap-3 transition-all"
                                :class="isConnected ? 'bg-emerald-500 text-white shadow-emerald-500/20' : 'bg-rose-500 text-white shadow-rose-500/20'"
                            >
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                                </span>
                                {{ isConnected ? 'النبض متزامن (Live)' : 'خارج المنظومة' }}
                            </div>
                        </div>
                    </div>

                    <!-- Node Identity Matrix -->
                    <div class="flex-1 text-right">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-6 mb-8 justify-start flex-row-reverse">
                            <h1 class="text-5xl font-black text-primary tracking-tighter leading-none uppercase">{{ server.name }}</h1>
                            <div class="flex items-center gap-3 px-5 py-2 bg-slate-100 border border-slate-200 rounded-xl">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">identity_platform</span>
                                <span class="text-[11px] font-black uppercase tracking-widest text-slate-500">
                                    {{ server.device_model?.model_name || 'MikroTik Sovereign Core' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap justify-start gap-4 flex-row-reverse">
                             <div class="px-8 py-4 bg-white border border-outline-variant/10 rounded-2xl text-base font-headline font-black text-primary flex items-center gap-4 shadow-xl hover:-translate-y-1 transition-all group">
                                <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors text-[24px]">globe</span>
                                <span class="tracking-widest">{{ server.ip }}</span>
                             </div>
                             <div class="px-8 py-4 bg-white border border-outline-variant/10 rounded-2xl text-sm font-headline font-black text-slate-500 flex items-center gap-4 shadow-xl">
                                <span class="material-symbols-outlined text-slate-300 text-[24px]">api</span>
                                <span class="uppercase tracking-widest">Protocol Port: <span class="text-primary">{{ server.api_port }}</span></span>
                             </div>
                             <div class="px-8 py-4 bg-white border border-outline-variant/10 rounded-2xl text-sm font-black text-slate-500 flex items-center gap-4 shadow-xl">
                                <span class="material-symbols-outlined text-slate-300 text-[24px]">location_on_special</span>
                                <span class="tracking-tight">{{ server.location || 'العقدة الرئيسية المستقلة' }}</span>
                             </div>
                        </div>
                    </div>

                    <!-- Tactical Overhaul Menu -->
                    <div class="shrink-0 flex gap-4">
                        <Link 
                            :href="route('servers.edit', server.id)" 
                            class="px-12 py-6 bg-slate-950 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all flex items-center gap-4 active:scale-95 group"
                        >
                            <span class="material-symbols-outlined text-[24px] group-hover:rotate-90 transition-transform">architecture</span>
                            إعادة تهيئة العقدة
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Real-time Telemetry Bento Grid -->
            <div v-if="isConnected && statusData" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Processor Saturation Flux -->
                <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-2xl relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="flex items-center justify-between mb-10 flex-row-reverse">
                        <div class="w-16 h-16 bg-slate-950 text-white rounded-2xl flex items-center justify-center shadow-2xl border border-white/10 group-hover:rotate-12 transition-transform">
                            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">memory</span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 leading-none">إجهاد النواة</p>
                            <h3 class="text-4xl font-black font-headline text-primary tracking-tighter">{{ statusData.cpu_load }}%</h3>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden shadow-inner border border-slate-200">
                        <div class="bg-primary h-full transition-all duration-1000 shadow-[0_0_15px_rgba(37,99,235,0.4)]" :style="{ width: statusData.cpu_load + '%' }"></div>
                    </div>
                </div>

                <!-- Memory Architecture State -->
                <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-2xl relative overflow-hidden group border-r-4 border-secondary">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-secondary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="flex items-center justify-between mb-10 flex-row-reverse">
                        <div class="w-16 h-16 bg-secondary text-white rounded-2xl flex items-center justify-center shadow-2xl border border-white/10 group-hover:-rotate-12 transition-transform">
                            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">database</span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-secondary uppercase tracking-[0.2em] mb-2 leading-none">استيعاب الذاكرة</p>
                            <h3 class="text-2xl font-black font-headline text-slate-900 tracking-tighter uppercase leading-none">
                                {{ Math.round(statusData.free_memory / 1024 / 1024) }} <span class="text-[10px] text-slate-400">MB_FREE</span>
                            </h3>
                        </div>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden shadow-inner border border-slate-200">
                        <div class="bg-secondary h-full transition-all duration-1000 shadow-[0_0_15px_rgba(50,201,133,0.4)]" :style="{ width: getMemPercent + '%' }"></div>
                    </div>
                </div>

                <!-- Subscriber Population Density (PPPoE) -->
                <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-2xl group">
                    <div class="flex items-center justify-between flex-row-reverse">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100/50 shadow-inner group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">vpn_lock</span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 leading-none">حقن PPPoE النشط</p>
                            <h3 class="text-5xl font-black font-headline text-primary tracking-tighter">{{ statusData.active_pppoe }}</h3>
                            <p class="text-[9px] font-black text-indigo-500 uppercase mt-4 tracking-[0.3em] flex items-center gap-2 justify-end italic">
                                ACTIVE_POOL_LIVE <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Hotspot Array Fragmentation -->
                <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-2xl group border-l-4 border-amber-400">
                    <div class="flex items-center justify-between flex-row-reverse">
                        <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center border border-amber-100 shadow-inner group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">wifi_tethering</span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 leading-none">مشتركي الهوت سبوت</p>
                            <h3 class="text-5xl font-black font-headline text-amber-600 tracking-tighter">{{ statusData.active_hotspot }}</h3>
                            <p class="text-[9px] font-black text-amber-400 uppercase mt-4 tracking-[0.3em] flex items-center gap-2 justify-end italic">
                                VIRTUAL_GitBranch_SYNC <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Command GitBranch Terminal Container -->
            <div class="surface-card rounded-[3rem] overflow-hidden shadow-2xl border border-outline-variant/10 min-h-[700px] bg-white relative">
                <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-10"></div>
                
                <!-- Tactical GitBranch Navigator -->
                <div class="border-b border-outline-variant/10 bg-slate-950 p-6 relative z-10">
                    <nav class="flex overflow-x-auto no-scrollbar gap-4 flex-row-reverse">
                        <button 
                            v-for="tab in tabs" 
                            :key="tab.id"
                            @click="activeTab = tab.id; if(tab.id === 'config') fetchSetupScript();"
                            class="px-10 py-5 rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] transition-all flex items-center gap-4 whitespace-nowrap active:scale-95 group/tab"
                            :class="activeTab === tab.id ? 'bg-primary text-white shadow-[0_10px_30px_rgba(37,99,235,0.3)] scale-105' : 'text-slate-500 hover:text-white hover:bg-white/10'"
                        >
                            <span class="material-symbols-outlined text-inherit text-[24px] group-hover/tab:scale-110 transition-transform">{{ tab.icon }}</span>
                            {{ tab.name }}
                        </button>
                    </nav>
                </div>

                <!-- High-Density Intelligence Portal -->
                <div class="p-10 md:p-20 relative z-10">
                    <!-- Global Asset Telemetry -->
                    <div v-if="activeTab === 'general'" class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                            <div class="space-y-12">
                                <div class="flex items-center gap-6 justify-end">
                                    <h4 class="text-sm font-black text-primary uppercase tracking-[0.3em]">توصيف العتاد والبروتوكول (Hardware)</h4>
                                    <div class="w-2 h-8 bg-primary rounded-full"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-8">
                                    <div class="p-10 bg-slate-50 rounded-3xl border border-outline-variant/10 shadow-inner group flex flex-col items-center text-center">
                                        <div class="w-12 h-12 bg-white rounded-xl shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-primary text-[24px]">system_update_alt</span>
                                        </div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 leading-none">إصدار الـ CORE</p>
                                        <p class="font-headline font-black text-xl text-primary tracking-widest" v-if="statusData">{{ statusData.version }}</p>
                                        <p class="font-bold text-sm text-slate-300 italic animate-pulse" v-else>جاري المزامنة...</p>
                                    </div>
                                    <div class="p-10 bg-slate-50 rounded-3xl border border-outline-variant/10 shadow-inner group flex flex-col items-center text-center">
                                        <div class="w-12 h-12 bg-white rounded-xl shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-emerald-500 text-[24px]">hourglass_top</span>
                                        </div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 leading-none">النبض التشغيلي (Uptime)</p>
                                        <p class="font-headline font-black text-xl text-primary tracking-widest" v-if="statusData">{{ statusData.uptime }}</p>
                                        <p class="font-bold text-sm text-slate-300" v-else>--:--:--</p>
                                    </div>
                                    <div class="p-10 bg-slate-950 text-white rounded-3xl group flex flex-col items-center text-center shadow-2xl col-span-2 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-primary opacity-5 group-hover:opacity-10 transition-opacity"></div>
                                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center mb-6 border border-white/10">
                                            <span class="material-symbols-outlined text-white text-[24px]">key_visualizer</span>
                                        </div>
                                        <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.4em] mb-3 leading-none">هوية الدخول السيادية</p>
                                        <p class="font-mono font-black text-2xl tracking-[0.3em] uppercase">{{ server.username }} <span class="text-primary italic opacity-50">@MadaaQ</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- DNS Oversight BarChart3 -->
                            <div v-if="statusData?.top_sites?.length > 0" class="space-y-12">
                                <div class="flex items-center gap-6 justify-end">
                                    <h4 class="text-sm font-black text-primary uppercase tracking-[0.3em]">تحليلات الاختراق الطيفي (DNS Oversight)</h4>
                                    <div class="w-2 h-8 bg-primary rounded-full"></div>
                                </div>
                                <div class="p-12 bg-slate-900 rounded-[2.5rem] shadow-2xl space-y-8 relative overflow-hidden group/BarChart3">
                                     <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-secondary/5 rounded-full blur-3xl"></div>
                                     <div v-for="site in statusData.top_sites.slice(0, 5)" :key="site.domain" class="space-y-4">
                                        <div class="flex justify-between items-center text-[11px] font-black uppercase tracking-[0.2em] flex-row-reverse">
                                            <span class="text-emerald-400 font-mono tracking-tight">{{ site.domain }}</span>
                                            <span class="text-white/40">{{ site.hits.toLocaleString() }} REQ_PROCESSED</span>
                                        </div>
                                        <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden border border-white/5">
                                            <div class="bg-gradient-to-l from-emerald-500 to-primary h-full transition-all duration-1000 shadow-[0_0_15px_rgba(50,201,133,0.3)]" :style="{ width: (site.hits / statusData.top_sites[0].hits * 100) + '%' }"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flow Matrix (Subscribers Ledger) -->
                    <div v-if="activeTab === 'users'" class="animate-in fade-in slide-in-from-bottom-4 duration-700 space-y-12">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 flex-row-reverse">
                            <div class="flex items-center gap-6 justify-end">
                                <h4 class="text-sm font-black text-primary uppercase tracking-[0.3em]">سجل التدفق الحي للجلسات (Session Ledger)</h4>
                                <div class="w-2 h-8 bg-primary rounded-full"></div>
                            </div>
                            <div class="flex gap-4">
                                 <span class="px-8 py-3 bg-indigo-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-500/10 border border-white/5 flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                                    PPPoE_NODES: {{ statusData?.active_pppoe || 0 }}
                                 </span>
                                 <span class="px-8 py-3 bg-amber-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-amber-500/10 border border-white/5 flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                    HOTSPOT_FRAG: {{ statusData?.active_hotspot || 0 }}
                                 </span>
                            </div>
                        </div>

                        <div class="surface-card rounded-[2rem] overflow-hidden border border-outline-variant/10 shadow-2xl bg-white relative">
                             <div class="absolute inset-0 bg-slate-50 opacity-20 pointer-events-none"></div>
                             <div class="overflow-x-auto relative z-10">
                                <table class="w-full text-right border-separate border-spacing-0">
                                    <thead>
                                        <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                            <th class="px-10 py-7 text-[10px] font-black text-white/40 uppercase tracking-[0.3em]">هوية المشترك (Node ID)</th>
                                            <th class="px-10 py-7 text-[10px] font-black text-white/40 uppercase tracking-[0.3em] text-center">العنوان المخصص</th>
                                            <th class="px-10 py-7 text-[10px] font-black text-white/40 uppercase tracking-[0.3em] text-center">بروتوكول الوصل</th>
                                            <th class="px-10 py-7 text-[10px] font-black text-white/40 uppercase tracking-[0.3em] text-center">عمر الجلسة</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-outline-variant/5">
                                        <template v-if="statusData?.pppoe_users?.length || statusData?.hotspot_users?.length">
                                            <tr v-for="user in statusData.pppoe_users" :key="user.user" class="hover:bg-indigo-50/50 transition-all duration-300 group">
                                                <td class="px-10 py-8">
                                                    <div class="flex items-center gap-5 justify-end">
                                                        <div class="text-right">
                                                            <h5 class="text-base font-black text-primary tracking-tight">{{ user.user }}</h5>
                                                            <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1">PPPoE_AUTH_KEY</p>
                                                        </div>
                                                        <div class="w-12 h-12 bg-white rounded-xl shadow-xl flex items-center justify-center border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                            <span class="material-symbols-outlined text-[24px]">vpn_key</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-10 py-8 text-center font-headline font-black text-base text-primary tracking-[0.2em]">{{ user.address }}</td>
                                                <td class="px-10 py-8 text-center">
                                                    <span class="px-6 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] shadow-sm">PPPoE Protocol</span>
                                                </td>
                                                <td class="px-10 py-8 text-center font-headline font-black text-[12px] text-slate-400 tracking-widest">{{ user.uptime }}</td>
                                            </tr>
                                            <tr v-for="user in statusData.hotspot_users" :key="user.user" class="hover:bg-amber-50/50 transition-all duration-300 group">
                                                <td class="px-10 py-8">
                                                    <div class="flex items-center gap-5 justify-end">
                                                        <div class="text-right">
                                                            <h5 class="text-base font-black text-primary tracking-tight">{{ user.user }}</h5>
                                                            <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1">HOTSPOT_SECRET</p>
                                                        </div>
                                                        <div class="w-12 h-12 bg-white rounded-xl shadow-xl flex items-center justify-center border border-amber-100 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                                            <span class="material-symbols-outlined text-[24px]">sensors</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-10 py-8 text-center font-headline font-black text-base text-primary tracking-[0.2em]">{{ user.address }}</td>
                                                <td class="px-10 py-8 text-center">
                                                    <span class="px-6 py-2 bg-amber-50 text-amber-700 border border-amber-100 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] shadow-sm">Hotspot Portal</span>
                                                </td>
                                                <td class="px-10 py-8 text-center font-headline font-black text-[12px] text-slate-400 tracking-widest">{{ user.uptime }}</td>
                                            </tr>
                                        </template>
                                        <tr v-else>
                                            <td colspan="4" class="px-10 py-64 text-center">
                                                <div class="flex flex-col items-center gap-8 opacity-20">
                                                    <div class="w-24 h-24 bg-slate-100 rounded-[2rem] flex items-center justify-center mb-4">
                                                        <span class="material-symbols-outlined text-[48px]" style="font-variation-settings: 'wght' 100">cloud_off</span>
                                                    </div>
                                                    <div>
                                                        <h5 class="text-xl font-black text-primary mb-2 uppercase tracking-widest">لا توجد تدفقات حية</h5>
                                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">نظام العقدة حالياً في وضع السكون البرمجي للمشتركين.</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                             </div>
                        </div>
                    </div>

                    <!-- Tactical Matrix (Tools Panel) -->
                    <div v-if="activeTab === 'tools'" class="animate-in fade-in slide-in-from-bottom-4 duration-700 space-y-16">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                             <!-- Pulse Synthesis Tool -->
                             <div class="surface-card p-16 space-y-10 h-full border border-outline-variant/10 shadow-2xl rounded-[3rem] relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                                <div class="flex items-center gap-8 justify-end relative z-10">
                                    <div class="text-right">
                                        <h4 class="text-2xl font-black text-primary tracking-tighter uppercase">اختبار نبض المزامنة</h4>
                                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] mt-2">API_PROTOCOL_VERIFICATION</p>
                                    </div>
                                    <div class="w-20 h-20 bg-emerald-950 text-white rounded-[1.5rem] flex items-center justify-center shadow-2xl border border-white/10 group-hover:rotate-12 transition-transform">
                                        <span class="material-symbols-outlined text-[40px]" style="font-variation-settings: 'FILL' 1">bolt</span>
                                    </div>
                                </div>
                                <p class="text-[13px] text-slate-500 leading-relaxed text-right font-medium relative z-10 px-4">تعتمد حوكمة مداء على استقرار نبض البيانات المشفرة بين المركز الرئيسي وهذه العقدة. اختبار الاستجابة الفوري (Ping Analysis) ضروري لضمان جودة الخدمة.</p>
                                <button 
                                    @click="testConnection"
                                    class="w-full py-6 bg-primary text-white rounded-2xl font-black text-[12px] uppercase tracking-[0.3em] shadow-2xl shadow-primary/30 hover:bg-emerald-600 hover:-translate-y-1 transition-all active:scale-95 relative z-10 border border-white/10"
                                    :disabled="isTesting"
                                >
                                    {{ isTesting ? 'جاري بناء النبض...' : 'إرسال حزمة الاختبار' }}
                                </button>
                                <div v-if="testResult" class="p-6 rounded-2xl text-center text-[11px] font-black uppercase tracking-[0.2em] relative z-10 border-2" :class="testResult.success ? 'bg-emerald-50 text-emerald-600 border-emerald-100 shadow-xl shadow-emerald-500/10' : 'bg-rose-50 text-rose-600 border-rose-100'">
                                    {{ testResult.success ? 'تأكيد التزامن: العقدة مستقرة سيادياً' : testResult.message }}
                                </div>
                             </div>

                             <!-- Data Ingestion Portal -->
                             <div class="surface-card p-16 bg-slate-900 text-white space-y-12 h-full relative overflow-hidden rounded-[3rem] group/card shadow-2xl">
                                 <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                                 <div class="flex items-center gap-8 justify-end relative z-10">
                                    <div class="text-right">
                                        <h4 class="text-2xl font-black text-white tracking-tighter uppercase">حقن واستيعاب الموارد</h4>
                                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.3em] mt-2">RESOURCE_INGESTION_MODULE</p>
                                    </div>
                                    <div class="w-20 h-20 bg-white/10 rounded-[1.5rem] flex items-center justify-center border border-white/10 shadow-2xl group-hover/card:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-[40px]">settings_input_component</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-6 relative z-10">
                                    <button @click="importData('pppoe')" class="px-10 py-6 bg-white/5 hover:bg-white text-white hover:text-primary rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] transition-all text-right flex items-center justify-between group/btn border border-white/5">
                                        <span class="material-symbols-outlined text-[20px] opacity-20 group-hover/btn:opacity-100 group-hover/btn:translate-x-[-8px] transition-all">database_sync</span>
                                        مزامنة سجلات PPPoE
                                    </button>
                                    <button @click="importData('hotspot')" class="px-10 py-6 bg-white/5 hover:bg-white text-white hover:text-primary rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] transition-all text-right flex items-center justify-between group/btn border border-white/5">
                                        <span class="material-symbols-outlined text-[20px] opacity-20 group-hover/btn:opacity-100 group-hover/btn:translate-x-[-8px] transition-all">diversity_1</span>
                                        حقن أصول Hotspot
                                    </button>
                                    <button @click="importData('pppoe-profiles')" class="px-10 py-6 bg-white/5 hover:bg-white text-white hover:text-primary rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] transition-all text-right flex items-center justify-between group/btn border border-white/5">
                                        <span class="material-symbols-outlined text-[20px] opacity-20 group-hover/btn:opacity-100 group-hover/btn:translate-x-[-8px] transition-all">shield_with_house</span>
                                        تحديث سياسات الربط
                                    </button>
                                </div>
                             </div>
                        </div>
                    </div>

                    <!-- Cyber Infrastructure Terminal (Provisioning) -->
                    <div v-if="activeTab === 'config'" class="animate-in fade-in slide-in-from-bottom-4 duration-700 space-y-16">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 flex-row-reverse">
                            <div class="flex items-center gap-8 justify-end">
                                <div class="text-right">
                                    <h4 class="text-3xl font-black text-primary tracking-tighter uppercase">نظام التأسيس السيادي (Provisioning Core)</h4>
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3">أتمتة أوامر الـ RouterOS</p>
                                </div>
                                <div class="w-20 h-20 bg-slate-950 text-white rounded-[2rem] flex items-center justify-center border border-white/10 shadow-2xl">
                                    <span class="material-symbols-outlined text-[40px]" style="font-variation-settings: 'wght' 700">terminal</span>
                                </div>
                            </div>
                            <button 
                                @click="copyScript"
                                class="px-14 py-6 bg-primary text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] shadow-[0_20px_40px_rgba(37,99,235,0.2)] hover:bg-slate-900 transition-all flex items-center gap-5 active:scale-95 border border-white/10 group"
                            >
                                <span class="material-symbols-outlined text-[24px] group-hover:rotate-12 transition-transform">{{ scriptCopied ? 'done_all' : 'file_copy' }}</span>
                                {{ scriptCopied ? 'تم نسخ الحزمة' : 'نسخ بروتوكول الأوامر' }}
                            </button>
                        </div>

                        <div class="bg-slate-950 rounded-[2.5rem] p-12 shadow-[0_0_100px_rgba(2,6,23,0.1)] border border-white/10 relative overflow-hidden group" dir="ltr">
                             <div class="absolute top-6 right-10 flex gap-3">
                                <div class="w-4 h-4 rounded-full bg-rose-500/30"></div>
                                <div class="w-4 h-4 rounded-full bg-amber-500/30"></div>
                                <div class="w-4 h-4 rounded-full bg-emerald-500/30"></div>
                             </div>
                             <div class="p-12 max-h-[600px] overflow-y-auto font-mono text-[14px] leading-relaxed text-indigo-400/90 no-scrollbar rounded-3xl border border-white/5 bg-black/60 shadow-inner">
                                <pre class="whitespace-pre-wrap">{{ setupScript || '#_INITIALIZING_INFRASTRUCTURE_PACKET...' }}</pre>
                             </div>
                        </div>

                        <!-- Tactical Roadmap Progression -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                             <div v-for="(step, i) in ['استخراج الحزمة', 'ولوج الـ Winbox', 'حقن الأوامر (Terminal)', 'مصادقة التزامن']" :key="step" class="surface-card p-10 rounded-[2rem] flex flex-col items-center justify-center text-center gap-5 border border-outline-variant/5 shadow-2xl group/step hover:scale-105 transition-all">
                                <div class="w-10 h-10 bg-slate-950 text-white rounded-xl flex items-center justify-center text-xs font-black shadow-2xl group-hover/step:bg-primary transition-colors">0{{ i+1 }}</div>
                                <span class="font-black text-[15px] text-primary tracking-tight">{{ step }}</span>
                                <span class="text-[8px] font-black text-slate-300 uppercase tracking-[0.4em]">PHASE_DEPLOYMENT</span>
                             </div>
                        </div>
                    </div>

                    <!-- Recovery Ledger (Sovereign Backups) -->
                    <div v-if="activeTab === 'backups'" class="animate-in fade-in slide-in-from-bottom-4 duration-700 space-y-12">
                        <div class="flex items-center gap-6 justify-end">
                            <h4 class="text-sm font-black text-primary uppercase tracking-[0.3em]">أرشيف لقطات النزاهة (Integrity Ledger)</h4>
                            <div class="w-2 h-8 bg-primary rounded-full"></div>
                        </div>

                        <div v-if="server.backups?.length > 0" class="surface-card rounded-[2.5rem] overflow-hidden border border-outline-variant/10 shadow-2xl bg-white">
                            <table class="w-full text-right border-separate border-spacing-0">
                                <thead class="bg-slate-950 text-white border-b border-white/5">
                                    <tr>
                                        <th class="px-10 py-7 text-[10px] font-black text-white/40 uppercase tracking-[0.3em]">مُعرف النسخة (Asset ID)</th>
                                        <th class="px-10 py-7 text-[10px] font-black text-white/40 uppercase tracking-[0.3em] text-center">نوع المشفر</th>
                                        <th class="px-10 py-7 text-[10px] font-black text-white/40 uppercase tracking-[0.3em] text-center">توقيت الأرشفة</th>
                                        <th class="px-10 py-7"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/5">
                                    <tr v-for="backup in server.backups" :key="backup.id" class="hover:bg-slate-50 transition-all duration-300 group">
                                        <td class="px-10 py-8">
                                            <div class="flex items-center gap-5 justify-end">
                                                <span class="text-base font-black text-primary group-hover:translate-x-3 transition-transform">{{ backup.filename }}</span>
                                                <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">history_edu</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-8 text-center">
                                            <span 
                                                class="px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border-2"
                                                :class="backup.type === 'backup' ? 'bg-indigo-50 text-indigo-700 border-indigo-100 shadow-sm' : 'bg-purple-50 text-purple-700 border-purple-100 shadow-sm'"
                                            >
                                                {{ backup.type === 'backup' ? 'الملف الثنائي (Full)' : 'سكربت التكوين (Script)' }}
                                            </span>
                                        </td>
                                        <td class="px-10 py-8 text-center text-slate-400 text-sm font-headline font-black tracking-widest">{{ backup.created_at.split('T')[0] }}</td>
                                        <td class="px-10 py-8 text-left">
                                            <a 
                                                :href="route('servers.backups.download', backup.id)" 
                                                class="inline-flex items-center gap-4 px-10 py-4 bg-slate-950 text-white rounded-xl text-[10px] font-black uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 border border-white/10 group/dl"
                                            >
                                                <span class="material-symbols-outlined text-[20px] group-hover/dl:translate-y-1 transition-transform">cloud_download</span>
                                                سحب الملف
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="py-64 flex flex-col items-center justify-center surface-card rounded-[3rem] border-4 border-dashed border-slate-100 bg-slate-50 text-slate-300 gap-8 shadow-inner">
                            <div class="w-32 h-32 bg-white rounded-[2.5rem] flex items-center justify-center shadow-2xl border border-outline-variant/5">
                                <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">inventory_2</span>
                            </div>
                            <div class="text-center">
                                <h5 class="text-2xl font-black uppercase tracking-tight text-primary mb-3">لا يوجد أرشيف استرداد مرصود</h5>
                                <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 opacity-60">نظام الأرشفة بانتظار تفعيل المهام المجدولة أو التدخل اليدوي.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>




