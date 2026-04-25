<script setup>
import { Link, router, Head } from '@inertiajs/vue3';
import { computedref } from 'vue';
import {  } from 'lucide-vue-next';;
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    devices: Array,
    activeAlerts: Array,
    recentAlerts: Array,
    stats: Object
});

const searchQuery = ref('');
const filterType = ref('all');

const filteredDevices = computed(() => {
    let result = props.devices;
    
    if (filterType.value !== 'all') {
        result = result.filter(d => d.type === filterType.value);
    }
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(d => 
            d.name.toLowerCase().includes(query) || 
            (d.ip && d.ip.includes(query))
        );
    }
    
    return result;
});

const resolveAlert = (id) => {
    router.patch(route('network.monitoring.alert.resolve', id), {}, {
        preserveScroll: true
    });
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'online': return { label: 'حالة نشطة', class: 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20', icon: 'check_circle' };
        case 'offline': return { label: 'انقطاع الاتصال', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20', icon: 'voice_over_off' };
        case 'warning': return { label: 'تنبيه فني', class: 'bg-amber-500/10 text-amber-600 border-amber-500/20', icon: 'warning' };
        default: return { label: 'غير محدد', class: 'bg-slate-500/10 text-slate-500 border-slate-500/20', icon: 'help_center' };
    }
};

const getDeviceIcon = (type) => {
    switch (type) {
        case 'server': return 'dns';
        case 'router': return 'router';
        case 'tower': return 'cell_tower';
        default: return 'settings_input_antenna';
    }
};

const formatTime = (timeString) => {
    return new Date(timeString).toLocaleTimeString('ar-SY', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
};

</script>

<template>
    <InstitutionalLayout title="مركز العمليات البرمجية">
        <Head title="مراقبة البنية التحتية (NOC) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic NOC Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10 mb-16 flex-row-reverse">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">مركز مراقبة الشبكة (Network Ops Center)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">بث حي وشامل لمؤشرات سلامة البنية التحتية</p>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                     <div class="relative group hidden md:block">
                        <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">search</span>
                        <input v-model="searchQuery" type="text" placeholder="الأجهزة أو عناوين IP..." class="form-input-monolith pr-14 h-14 w-96 text-sm font-bold">
                     </div>
                     <Link 
                        :href="route('network.monitoring.bandwidth')" 
                        class="px-10 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95 whitespace-nowrap border border-white/10 flex items-center gap-4"
                     >
                        <span class="material-symbols-outlined text-[24px]">troubleshoot</span> مـحلل النطاق العريض
                     </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 text-right">
                <!-- Status Matrix & Devices Layer -->
                <div class="lg:col-span-3 space-y-12">
                    <!-- Global Health Stats -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="surface-card p-8 rounded-2xl border border-outline-variant/5 shadow-sm">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">إجمالي العقد الطرفية</p>
                            <h3 class="text-4xl font-headline font-black text-primary leading-none">{{ stats.total_devices }}</h3>
                        </div>
                        <div class="surface-card p-8 rounded-2xl border border-outline-variant/5 shadow-sm border-b-4 border-emerald-500">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-3">العقد النشطة (Live)</p>
                            <h3 class="text-4xl font-headline font-black text-emerald-600 leading-none">{{ stats.online }}</h3>
                        </div>
                        <div class="surface-card p-8 rounded-2xl border border-outline-variant/5 shadow-sm border-b-4 border-rose-500 text-rose-500">
                            <p class="text-[10px] font-black uppercase tracking-widest mb-3">العقد المنقطعة (Fault)</p>
                            <h3 class="text-4xl font-headline font-black leading-none">{{ stats.offline }}</h3>
                        </div>
                        <div class="surface-card p-8 rounded-2xl bg-amber-500/5 border border-amber-500/20 text-amber-600">
                            <p class="text-[10px] font-black uppercase tracking-widest mb-3">تنبيهات حرجة (Alerts)</p>
                            <h3 class="text-4xl font-headline font-black leading-none">{{ stats.active_alerts }}</h3>
                        </div>
                    </div>

                    <!-- Edge Nodes Matrix -->
                    <div class="space-y-8">
                        <div class="flex items-center justify-between flex-row-reverse border-b border-outline-variant/5 pb-6">
                             <div class="flex items-center gap-3">
                                <h3 class="text-xs font-black text-primary uppercase tracking-widest">مصفوفة الأصول الفنية</h3>
                                <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                             </div>
                            <div class="flex bg-surface-container-low p-2 rounded-2xl border border-outline-variant/10 shadow-inner">
                                <button v-for="type in ['all', 'server', 'router', 'tower']" :key="type" @click="filterType = type" 
                                    class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" 
                                    :class="filterType === type ? 'bg-white text-primary shadow-xl' : 'text-slate-400 hover:text-slate-600'">
                                    {{ type === 'all' ? 'الكل' : (type === 'server' ? 'خوادم' : (type === 'router' ? 'موجهات' : 'أبراج')) }}
                                </button>
                            </div>
                        </div>

                        <div class="surface-card rounded-2xl overflow-hidden shadow-2xl border border-outline-variant/5">
                            <div class="overflow-x-auto">
                                <table class="w-full text-right border-separate border-spacing-0">
                                    <thead>
                                        <tr class="bg-slate-950 text-white border-b border-white/5">
                                            <th class="px-8 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-white/40">تجهيزات الشبكة (Identity)</th>
                                            <th class="px-8 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">عنوان IP البروتوكولي</th>
                                            <th class="px-8 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">زمن الاستجابة</th>
                                            <th class="px-8 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">الحالة التشغيلية</th>
                                            <th class="px-8 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">آخر مزامنة</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-outline-variant/5">
                                        <tr v-for="device in filteredDevices" :key="device.id + device.type" class="group hover:bg-surface-container-low transition-all">
                                            <td class="px-8 py-6">
                                                <div class="flex items-center gap-5 justify-end">
                                                    <div>
                                                        <p class="text-base font-black text-primary leading-tight group-hover:translate-x-1 transition-transform">{{ device.name }}</p>
                                                        <p class="text-[10px] font-headline font-black text-slate-400 uppercase tracking-widest mt-2">{{ device.type }} — ARCH_ID: <span class="text-primary/50">#{{ device.id }}</span></p>
                                                    </div>
                                                    <div class="w-14 h-14 rounded-2xl bg-surface-container-low flex items-center justify-center text-primary border border-outline-variant/5 group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                                                        <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">
                                                            {{ getDeviceIcon(device.type) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6 text-center">
                                                <span class="text-[12px] font-headline font-black text-primary tracking-widest bg-surface-container-low px-4 py-2 rounded-lg border border-outline-variant/5">{{ device.ip || 'PROTO_INTERNAL' }}</span>
                                            </td>
                                            <td class="px-8 py-6 text-center">
                                                <div v-if="device.response_time" class="inline-flex items-center gap-3 px-4 py-2 bg-emerald-500/5 rounded-xl border border-emerald-500/10 transition-all group-hover:scale-110 shadow-sm">
                                                    <span class="material-symbols-outlined text-[16px] text-emerald-500">speed</span>
                                                    <span class="text-[11px] font-headline font-black text-emerald-600">{{ device.response_time }}ms</span>
                                                </div>
                                                <span v-else class="text-slate-200 tracking-widest opacity-20">— — —</span>
                                            </td>
                                            <td class="px-8 py-6 text-center">
                                                <div :class="[
                                                    'inline-flex items-center gap-3 px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all shadow-sm',
                                                    getStatusDetails(device.status).class
                                                ]">
                                                    <span class="w-2 h-2 rounded-full bg-current" :class="device.status === 'online' ? 'animate-pulse' : ''"></span>
                                                    {{ getStatusDetails(device.status).label }}
                                                    <span class="material-symbols-outlined text-[16px]">{{ getStatusDetails(device.status).icon }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6 text-center text-slate-400">
                                                <div class="flex items-center gap-2 justify-center">
                                                     <span class="text-[11px] font-headline font-black text-slate-900">{{ device.last_check ? formatTime(device.last_check) : 'N/A' }}</span>
                                                     <span class="material-symbols-outlined text-[16px]">history</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert Intelligence Sidebar -->
                <div class="lg:col-span-1 space-y-10">
                    <!-- Critical Fault Stream -->
                    <div class="surface-card bg-slate-900 text-white p-10 rounded-2xl flex flex-col min-h-[500px] shadow-2xl relative overflow-hidden border border-white/5">
                        <div class="absolute -top-16 -left-16 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                        <div class="flex items-center justify-between mb-12 flex-row-reverse relative z-10">
                            <h3 class="text-xs font-black uppercase tracking-[0.2em] flex items-center gap-4">
                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span> البث الحي للأعطال
                            </h3>
                            <span class="px-4 py-1.5 bg-rose-500 font-headline font-black text-[12px] rounded-xl shadow-lg border border-white/10">{{ activeAlerts.length }}</span>
                        </div>

                        <div class="space-y-6 flex-1 overflow-y-auto pr-2 custom-scrollbar relative z-10">
                            <div v-for="alert in activeAlerts" :key="alert.id" class="p-6 bg-white/5 border border-white/10 rounded-2xl group hover:bg-white/10 transition-all shadow-lg text-right">
                                <div class="flex items-start justify-between mb-6 flex-row-reverse">
                                     <div class="w-10 h-10 bg-rose-500/20 text-rose-500 rounded-xl flex items-center justify-center border border-rose-500/10 shadow-inner group-hover:rotate-12 transition-transform">
                                        <span class="material-symbols-outlined text-[20px]">report_problem</span>
                                     </div>
                                     <button @click="resolveAlert(alert.id)" class="px-4 py-2 rounded-lg bg-white/10 text-[9px] font-black text-white/50 uppercase tracking-widest hover:bg-white/20 hover:text-white transition-all">تأكيد المعالجة</button>
                                </div>
                                <h5 class="text-sm font-black tracking-tight mb-2 leading-tight text-white">{{ alert.device?.name || 'Protocol Artifact' }}</h5>
                                <p class="text-[11px] text-white/50 leading-relaxed font-bold">{{ alert.message }}</p>
                                <div class="flex items-center gap-3 mt-6 text-[9px] font-headline font-black text-white/20 uppercase tracking-[0.3em] justify-end border-t border-white/5 pt-4">
                                     {{ formatTime(alert.created_at) }}
                                     <span class="material-symbols-outlined text-[14px]">alarm</span>
                                </div>
                            </div>

                            <div v-if="activeAlerts.length === 0" class="flex flex-col items-center justify-center h-full text-center py-20 opacity-30">
                                <div class="w-20 h-20 bg-emerald-500/10 rounded-[2rem] flex items-center justify-center mb-6 text-emerald-500 border border-emerald-500/10 shadow-2xl">
                                    <span class="material-symbols-outlined text-[40px]">Shield</span>
                                </div>
                                <h4 class="text-sm font-black uppercase tracking-widest text-emerald-500 mb-2 leading-none">نزاهة الشبكة 100%</h4>
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] italic">لا توجد ثغرات أو أعطال في النطاق</p>
                            </div>
                        </div>
                    </div>

                    <!-- Historical Stream Activity -->
                    <div class="surface-card p-10 rounded-2xl border border-outline-variant/5 shadow-sm">
                        <h3 class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-400 mb-10 border-b border-outline-variant/10 pb-6 flex items-center gap-3 justify-end leading-none">
                            الأرشيف الزمني للأعطال
                            <span class="material-symbols-outlined text-[18px]">history</span>
                        </h3>
                         <div class="space-y-8">
                            <div v-for="alert in recentAlerts.slice(0, 6)" :key="alert.id" class="flex items-start gap-5 flex-row-reverse text-right group">
                                <div class="w-2 h-2 rounded-full mt-2 shrink-0 shadow-lg" :class="alert.is_resolved ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-black text-primary leading-tight mb-2 group-hover:text-primary/70 transition-colors">{{ alert.message }}</p>
                                    <p class="text-[9px] font-black text-slate-400 flex items-center gap-3 justify-end opacity-60">
                                        <span class="font-headline tracking-widest">{{ formatTime(alert.created_at) }}</span>
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <span class="truncate">{{ alert.device?.name }}</span>
                                    </p>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>




