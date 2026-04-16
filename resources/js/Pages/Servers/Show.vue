<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
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
    { id: 'general', name: 'هوية النظام', icon: 'dns' },
    { id: 'users', name: 'سجل الجلسات', icon: 'group' },
    { id: 'tools', name: 'الأدوات الاستراتيجية', icon: 'settings_suggest' },
    { id: 'config', name: 'بروتوكولات الإعداد', icon: 'terminal' },
    { id: 'backups', name: 'سجل الاستعادة', icon: 'auto_backup' },
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
        setupScript.value = '# خطأ في استخراج قائمة الأوامر البرمجية.';
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
            message: 'فشل اختبار اتصال السيرفر البرمجي.'
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

        <div class="max-w-7xl mx-auto pb-24 text-right px-4">
            <!-- Command Center Header -->
            <div class="surface-card p-10 mb-10 overflow-hidden relative rounded-xl shadow-sm border-none">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-secondary/5 rounded-full blur-3xl"></div>

                <div class="relative flex flex-col lg:flex-row items-center gap-10">
                    <!-- Hardware Identity Visual -->
                    <div class="relative group shrink-0">
                        <div class="w-48 h-48 bg-surface-container-low rounded-2xl border border-outline-variant/10 flex items-center justify-center p-8 group-hover:scale-105 transition-all duration-700 shadow-inner">
                            <span class="material-symbols-outlined text-[96px] text-primary/30" style="font-variation-settings: 'wght' 200">dns</span>
                        </div>
                        <div class="absolute -bottom-2 -left-2">
                            <div 
                                class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border-4 border-white shadow-xl flex items-center gap-2"
                                :class="isConnected ? 'bg-secondary text-white' : 'bg-error text-white'"
                            >
                                <span class="w-2 h-2 rounded-full bg-white transition-all" :class="isConnected ? 'animate-pulse' : ''"></span>
                                {{ isConnected ? 'متزامن برمجياً' : 'منقطع' }}
                            </div>
                        </div>
                    </div>

                    <!-- Identity Matrix -->
                    <div class="flex-1 text-center lg:text-right">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-6 justify-center lg:justify-start lg:flex-row-reverse">
                            <h1 class="text-4xl font-black text-primary tracking-tight leading-none">{{ server.name }}</h1>
                            <span class="px-4 py-1.5 bg-surface-container-low border border-outline-variant/10 rounded-lg text-[10px] font-black uppercase tracking-widest text-slate-500">
                                {{ server.device_model?.model_name || 'عقدة MikroTik الأساسية' }}
                            </span>
                        </div>
                        
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4 flex-row-reverse">
                             <div class="px-6 py-3 bg-white border border-outline-variant/10 rounded-xl text-xs font-mono font-black text-primary flex items-center gap-3 shadow-sm hover:shadow-md transition-all">
                                <span>{{ server.ip }}</span>
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">globe</span>
                             </div>
                             <div class="px-6 py-3 bg-white border border-outline-variant/10 rounded-xl text-xs font-mono font-black text-slate-500 flex items-center gap-3 shadow-sm">
                                <span>API:{{ server.api_port }}</span>
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">api</span>
                             </div>
                             <div class="px-6 py-3 bg-white border border-outline-variant/10 rounded-xl text-xs font-black text-slate-500 flex items-center gap-3 shadow-sm">
                                <span>{{ server.location || 'العقدة الرئيسية' }}</span>
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">location_on</span>
                             </div>
                        </div>
                    </div>

                    <!-- Strategic Access Actions -->
                    <div class="shrink-0 flex gap-4">
                        <Link 
                            :href="route('servers.edit', server.id)" 
                            class="px-10 py-5 bg-primary text-white rounded-lg font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-3 active:scale-95"
                        >
                            <span class="material-symbols-outlined text-[20px]">settings_suggest</span>
                            تعديل التهيئة
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Real-time Telemetry Bento Grid -->
            <div v-if="isConnected && statusData" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- CPU Load Insight -->
                <div class="surface-card p-8 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">حمل المعالجة</p>
                            <h3 class="text-4xl font-black font-headline text-primary tracking-tight">{{ statusData.cpu_load }}%</h3>
                        </div>
                        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100/50">
                            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">memory</span>
                        </div>
                    </div>
                    <div class="w-full bg-surface-container-low h-2 rounded-full overflow-hidden shadow-inner">
                        <div class="bg-indigo-600 h-full transition-all duration-1000" :style="{ width: statusData.cpu_load + '%' }"></div>
                    </div>
                </div>

                <!-- Memory Architecture State -->
                <div class="surface-card p-8 rounded-xl border border-outline-variant/5 shadow-sm border-r-4 border-secondary">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-[10px] font-black text-secondary uppercase tracking-widest mb-2">الذاكرة النشطة</p>
                            <h3 class="text-2xl font-black font-headline text-secondary tracking-tight">
                                {{ Math.round(statusData.free_memory / 1024 / 1024) }} <span class="text-xs uppercase text-slate-400">MB متوفرة</span>
                            </h3>
                        </div>
                        <div class="w-14 h-14 bg-secondary-container/10 text-secondary rounded-2xl flex items-center justify-center border border-secondary-container/20">
                            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">hard_drive</span>
                        </div>
                    </div>
                    <div class="w-full bg-surface-container-low h-2 rounded-full overflow-hidden shadow-inner">
                        <div class="bg-secondary h-full transition-all duration-1000" :style="{ width: getMemPercent + '%' }"></div>
                    </div>
                </div>

                <!-- PPPoE Matrix Live -->
                <div class="surface-card p-8 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">جلسات PPPoE</p>
                            <h3 class="text-4xl font-black font-headline text-primary tracking-tight">{{ statusData.active_pppoe }}</h3>
                            <p class="text-[8px] font-black text-indigo-500 uppercase mt-2 tracking-widest">مزامنة الجلسات الحية</p>
                        </div>
                        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100/50">
                            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">wifi_tethering</span>
                        </div>
                    </div>
                </div>

                <!-- Hotspot Array Active -->
                <div class="surface-card p-8 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">جلسات Hotspot</p>
                            <h3 class="text-4xl font-black font-headline text-primary tracking-tight">{{ statusData.active_hotspot }}</h3>
                            <p class="text-[8px] font-black text-amber-600 uppercase mt-2 tracking-widest">القسائم المنشورة</p>
                        </div>
                        <div class="w-14 h-14 bg-amber-50 text-amber-700 rounded-2xl flex items-center justify-center border border-amber-100">
                            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">sensors_kronecker</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intelligence Hub Container -->
            <div class="surface-card rounded-xl overflow-hidden shadow-sm border border-outline-variant/5 min-h-[600px]">
                <!-- Strategic Hub Navigator -->
                <div class="border-b border-outline-variant/10 bg-surface-container-low/50 p-3">
                    <nav class="flex overflow-x-auto no-scrollbar gap-2 flex-row-reverse">
                        <button 
                            v-for="tab in tabs" 
                            :key="tab.id"
                            @click="activeTab = tab.id; if(tab.id === 'config') fetchSetupScript();"
                            class="px-8 py-4 rounded-lg font-black text-[11px] uppercase tracking-widest transition-all flex items-center gap-3 whitespace-nowrap active:scale-95"
                            :class="activeTab === tab.id ? 'bg-primary text-white shadow-lg' : 'text-slate-400 hover:bg-white hover:text-primary'"
                        >
                            <span class="material-symbols-outlined text-inherit text-[20px]">{{ tab.icon }}</span>
                            {{ tab.name }}
                        </button>
                    </nav>
                </div>

                <!-- Dynamic Tab Portal -->
                <div class="p-10 md:p-16">
                    <!-- General Asset Intelligence -->
                    <div v-if="activeTab === 'general'" class="animate-in fade-in duration-700">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                            <div class="space-y-10">
                                <div class="flex items-center gap-4 justify-end">
                                    <h4 class="text-sm font-black text-primary uppercase tracking-widest tracking-widest">بنية المكونات المادية (العتاد)</h4>
                                    <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="p-8 bg-surface-container-low/50 rounded-xl border border-outline-variant/10 shadow-inner">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">إصدار النظام</p>
                                        <p class="font-black text-[15px] text-primary" v-if="statusData">{{ statusData.version }}</p>
                                        <p class="font-bold text-sm text-slate-300 italic" v-else>جاري جلب البيانات...</p>
                                    </div>
                                    <div class="p-8 bg-surface-container-low/50 rounded-xl border border-outline-variant/10 shadow-inner">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">زمن التشغيل (Uptime)</p>
                                        <p class="font-headline font-black text-[15px] text-primary tracking-tight" v-if="statusData">{{ statusData.uptime }}</p>
                                        <p class="font-bold text-sm text-slate-300" v-else>--:--:--</p>
                                    </div>
                                    <div class="p-8 bg-surface-container-low/50 rounded-xl border border-outline-variant/10 shadow-inner">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">وصول الجذر</p>
                                        <p class="font-mono font-black text-[15px] text-primary">{{ server.username }}</p>
                                    </div>
                                    <div class="p-8 bg-surface-container-low/50 rounded-xl border border-outline-variant/10 shadow-inner border-r-4 border-indigo-500">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 leading-none">المواقع المرتبطة</p>
                                        <p class="font-black text-[15px] text-indigo-600">{{ server.towers_count || 0 }} مواقع بنية تحتية</p>
                                    </div>
                                </div>
                            </div>

                            <!-- DNS Performance Flux -->
                            <div v-if="statusData?.top_sites?.length > 0" class="space-y-10">
                                <div class="flex items-center gap-4 justify-end">
                                    <h4 class="text-sm font-black text-primary uppercase tracking-widest">تحليلات الشبكة (Network)</h4>
                                    <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                                </div>
                                <div class="p-10 bg-slate-900 rounded-xl shadow-2xl space-y-6">
                                    <div v-for="site in statusData.top_sites.slice(0, 5)" :key="site.domain" class="space-y-2">
                                        <div class="flex justify-between items-center text-[11px] font-black uppercase tracking-tight">
                                            <span class="text-secondary/80 font-mono tracking-tight">{{ site.domain }}</span>
                                            <span class="text-white/60">{{ site.hits }} عملية</span>
                                        </div>
                                        <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-secondary h-full transition-all duration-1000" :style="{ width: (site.hits / statusData.top_sites[0].hits * 100) + '%' }"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Session Registry Table -->
                    <div v-if="activeTab === 'users'" class="animate-in fade-in duration-700 space-y-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 flex-row-reverse">
                            <div class="flex items-center gap-4 justify-end">
                                <h4 class="text-sm font-black text-primary uppercase tracking-widest">سجل الجلسات التشغيلية الحية</h4>
                                <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                            </div>
                            <div class="flex gap-4">
                                 <span class="px-5 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg text-[10px] font-black uppercase tracking-widest">PPPoE نشطة: {{ statusData?.active_pppoe || 0 }}</span>
                                 <span class="px-5 py-2 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg text-[10px] font-black uppercase tracking-widest">قسائم هوت سبوت: {{ statusData?.active_hotspot || 0 }}</span>
                            </div>
                        </div>

                        <div class="surface-card rounded-lg overflow-hidden border border-outline-variant/10 shadow-inner">
                            <table class="w-full text-right border-collapse">
                                <thead class="bg-surface-container-low/50 border-b border-outline-variant/10">
                                    <tr>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">هوية المشترك</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">العنوان الافتراضي (IP)</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">البروتوكول المستخدم</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">زمن النبض</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/5">
                                    <template v-if="statusData?.pppoe_users?.length || statusData?.hotspot_users?.length">
                                        <tr v-for="user in statusData.pppoe_users" :key="user.user" class="hover:bg-indigo-50/30 transition-all">
                                            <td class="px-8 py-5">
                                                <div class="flex items-center gap-3 justify-end">
                                                    <span class="text-sm font-black text-primary">{{ user.user }}</span>
                                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 text-center font-mono text-[12px] font-black text-slate-500 tracking-tight">{{ user.address }}</td>
                                            <td class="px-8 py-5 text-center">
                                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded text-[9px] font-black uppercase tracking-widest">بروتوكول PPPoE</span>
                                            </td>
                                            <td class="px-8 py-5 text-center font-headline font-black text-[11px] text-slate-400 tracking-tighter">{{ user.uptime }}</td>
                                        </tr>
                                        <tr v-for="user in statusData.hotspot_users" :key="user.user" class="hover:bg-amber-50/30 transition-all">
                                            <td class="px-8 py-5">
                                                <div class="flex items-center gap-3 justify-end">
                                                    <span class="text-sm font-black text-primary">{{ user.user }}</span>
                                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 text-center font-mono text-[12px] font-black text-slate-500 tracking-tight">{{ user.address }}</td>
                                            <td class="px-8 py-5 text-center">
                                                <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded text-[9px] font-black uppercase tracking-widest">وصول هوت سبوت</span>
                                            </td>
                                            <td class="px-8 py-5 text-center font-headline font-black text-[11px] text-slate-400 tracking-tighter">{{ user.uptime }}</td>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="4" class="px-8 py-40 text-center">
                                            <div class="flex flex-col items-center gap-4 opacity-20">
                                                <span class="material-symbols-outlined text-[64px]">signal_disconnected</span>
                                                <p class="text-xs font-black uppercase tracking-[0.3em]">لا يوجد نبض تشغيلي للمشتركين حالياً</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Strategic Tool Matrix -->
                    <div v-if="activeTab === 'tools'" class="animate-in fade-in duration-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                             <!-- Pulse Test Tool -->
                             <div class="surface-card p-12 space-y-8 h-full border border-outline-variant/10 shadow-sm rounded-xl">
                                <div class="flex items-center gap-5 justify-end">
                                    <div class="text-right">
                                        <h4 class="text-lg font-black text-primary tracking-tight">اختبار اتصال السيرفر الحي</h4>
                                        <p class="text-[10px] font-black text-secondary uppercase tracking-widest mt-1">التحقق من استجابة API</p>
                                    </div>
                                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center border border-emerald-100/50">
                                        <span class="material-symbols-outlined text-[32px]">bolt</span>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-500 leading-relaxed text-right font-medium">التحقق من سلامة الاتصال المشفر بين المركز الرئيسي لعقل مداء وبين هذه العقدة الطرفية.</p>
                                <button 
                                    @click="testConnection"
                                    class="w-full py-5 bg-primary text-white rounded-lg font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95"
                                    :disabled="isTesting"
                                >
                                    {{ isTesting ? 'جاري اختبار الاتصال...' : 'بدء اختبار الاتصال' }}
                                </button>
                                <div v-if="testResult" class="p-5 rounded-lg text-center text-[10px] font-black uppercase tracking-widest" :class="testResult.success ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100'">
                                    {{ testResult.success ? 'تم تأكيد التزامن بنجاح' : testResult.message }}
                                </div>
                             </div>

                             <!-- Data Assimilation Mirror -->
                             <div class="surface-card p-12 bg-slate-900 text-white space-y-10 h-full relative overflow-hidden rounded-xl group/card shadow-2xl">
                                 <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/5 rounded-full blur-3xl group-hover/card:scale-150 transition-transform duration-1000"></div>
                                 <div class="flex items-center gap-5 justify-end relative z-10">
                                    <div class="text-right">
                                        <h4 class="text-lg font-black text-white tracking-tight">محاكاة واستيعاب الموارد</h4>
                                        <p class="text-[10px] font-black text-secondary uppercase tracking-widest mt-1">مزامنة سجلات RouterOS</p>
                                    </div>
                                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center border border-white/10 shadow-inner">
                                        <span class="material-symbols-outlined text-[32px]">sync_alt</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 relative z-10">
                                    <button @click="importData('pppoe')" class="px-8 py-5 bg-white/5 hover:bg-white text-white hover:text-primary rounded-lg text-[10px] font-black uppercase tracking-[0.2em] transition-all text-right flex items-center justify-between group/btn border border-white/5">
                                        <span class="material-symbols-outlined text-[18px] opacity-20 group-hover/btn:opacity-100 group-hover/btn:translate-x-[-4px] transition-all">chevron_left</span>
                                        مزامنة بيانات PPPoE
                                    </button>
                                    <button @click="importData('hotspot')" class="px-8 py-5 bg-white/5 hover:bg-white text-white hover:text-primary rounded-lg text-[10px] font-black uppercase tracking-[0.2em] transition-all text-right flex items-center justify-between group/btn border border-white/5">
                                        <span class="material-symbols-outlined text-[18px] opacity-20 group-hover/btn:opacity-100 group-hover/btn:translate-x-[-4px] transition-all">chevron_left</span>
                                        محاكاة سجلات Hotspot
                                    </button>
                                    <button @click="importData('pppoe-profiles')" class="px-8 py-5 bg-white/5 hover:bg-white text-white hover:text-primary rounded-lg text-[10px] font-black uppercase tracking-[0.2em] transition-all text-right flex items-center justify-between group/btn border border-white/5">
                                        <span class="material-symbols-outlined text-[18px] opacity-20 group-hover/btn:opacity-100 group-hover/btn:translate-x-[-4px] transition-all">chevron_left</span>
                                        مزامنة بروتوكولات السياسة
                                    </button>
                                </div>
                             </div>
                        </div>
                    </div>

                    <!-- Provisioning Protocol (Terminal System) -->
                    <div v-if="activeTab === 'config'" class="animate-in fade-in duration-700 space-y-12">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 flex-row-reverse">
                            <div class="flex items-center gap-5 justify-end">
                                <div class="text-right">
                                    <h4 class="text-xl font-black text-primary tracking-tight">بروتوكول تهيئة البنية التحتية</h4>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1.5">أوامر التأسيس والبرمجة</p>
                                </div>
                                <div class="w-16 h-16 bg-amber-50 text-amber-700 rounded-2xl flex items-center justify-center border border-amber-100">
                                    <span class="material-symbols-outlined text-[32px]">terminal</span>
                                </div>
                            </div>
                            <button 
                                @click="copyScript"
                                class="px-10 py-5 bg-primary text-white rounded-lg font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-4 active:scale-95"
                            >
                                <span class="material-symbols-outlined text-[20px]">{{ scriptCopied ? 'verified' : 'content_copy' }}</span>
                                {{ scriptCopied ? 'تم النسخ بنجاح' : 'نسخ قائمة الأوامر' }}
                            </button>
                        </div>

                        <div class="bg-[#0a0a0b] rounded-2xl p-6 shadow-2xl border border-white/10 relative overflow-hidden group" dir="ltr">
                             <div class="absolute top-4 right-4 flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500/50"></div>
                             </div>
                             <div class="p-10 max-h-[500px] overflow-y-auto font-mono text-[13px] leading-relaxed text-secondary/90 no-scrollbar rounded-xl border border-white/5 bg-black/40">
                                <pre class="whitespace-pre-wrap">{{ setupScript || 'جاري استخراج حزمة الأوامر البرمجية...' }}</pre>
                             </div>
                        </div>

                        <!-- Operational Roadmap Steps -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                             <div v-for="(step, i) in ['جلب الأوامر', 'فتح بيئة Winbox', 'تنفيذ الأوامر (Terminal)', 'تأكيد نجاح الربط']" :key="step" class="surface-card p-8 rounded-xl flex flex-col items-center justify-center text-center gap-3 border border-outline-variant/5 shadow-inner group/step">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest group-hover/step:text-primary transition-colors">المرحلة {{ i+1 }}</span>
                                <span class="font-black text-[13px] text-primary">{{ step }}</span>
                             </div>
                        </div>
                    </div>

                    <!-- Recovery Matrix (Backups) -->
                    <div v-if="activeTab === 'backups'" class="animate-in fade-in duration-700 space-y-10">
                        <div class="flex items-center gap-4 justify-end">
                            <h4 class="text-sm font-black text-primary uppercase tracking-widest">لقطات نزاهة النظام (Safe State Snapshots)</h4>
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        </div>

                        <div v-if="server.backups?.length > 0" class="surface-card rounded-lg overflow-hidden border border-outline-variant/10 shadow-inner">
                            <table class="w-full text-right border-collapse">
                                <thead class="bg-surface-container-low/50 border-b border-outline-variant/10">
                                    <tr>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">كيان النسخة الاحتياطية</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">نوع البروتوكول</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">تاريخ الأرشفة</th>
                                        <th class="px-8 py-5"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/5">
                                    <tr v-for="backup in server.backups" :key="backup.id" class="hover:bg-surface-container-low transition-all">
                                        <td class="px-8 py-6 font-black text-primary text-sm">{{ backup.filename }}</td>
                                        <td class="px-8 py-6 text-center">
                                            <span 
                                                class="px-5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border"
                                                :class="backup.type === 'backup' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 'bg-purple-50 text-purple-700 border-purple-100'"
                                            >
                                                {{ backup.type === 'backup' ? 'ملف ثنائي (Binary)' : 'سكربت تهيئة (Script)' }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-center text-slate-400 text-xs font-headline font-black">{{ backup.created_at.split('T')[0] }}</td>
                                        <td class="px-8 py-6 text-left">
                                            <a 
                                                :href="route('servers.backups.download', backup.id)" 
                                                class="inline-flex items-center gap-3 px-6 py-3 bg-primary text-white rounded-lg text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-primary/10 hover:bg-primary/90 transition-all active:scale-95"
                                            >
                                                <span class="material-symbols-outlined text-[18px]">download</span>
                                                استرجاع
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="py-40 flex flex-col items-center justify-center surface-card rounded-xl border-dashed border-2 border-outline-variant/20 text-slate-300 gap-6">
                            <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">database</span>
                            <div class="text-center">
                                <h5 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-1">لا توجد لقطات استعادة مرصودة</h5>
                                <p class="text-[9px] uppercase font-black tracking-widest">نظام الأرشفة الآلي بانتظار تفعيل المهام المجدولة.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.font-headline { font-family: 'Manrope', sans-serif; }
</style>
