<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    tower: Object,
    currency: String,
});

const activeTab = ref('overview');

const tabs = [
    { id: 'overview', name: 'نظرة عامة', icon: 'dashboard' },
    { id: 'equipment', name: 'المعدات', icon: 'router' },
    { id: 'power', name: 'الطاقة', icon: 'bolt' },
    { id: 'costs', name: 'السجل المالي', icon: 'payments' },
    { id: 'connection', name: 'الربط', icon: 'settings_input_antenna' },
];

const stats = computed(() => [
    { name: 'الوحدات الصلبة', value: props.tower.routers?.length || 0, label: 'أجهزة التوجيه والبث', icon: 'dns', color: 'text-primary', bg: 'bg-primary-fixed/20' },
    { name: 'شبكات البث', value: props.tower.ssids?.length || 0, label: 'واجهات هوائية', icon: 'wifi', color: 'text-indigo-600', bg: 'bg-indigo-50' },
    { name: 'المشتركين', value: props.tower.clients?.length || 0, label: 'اتصالات نشطة', icon: 'group', color: 'text-secondary', bg: 'bg-secondary-container/20' },
    { name: 'تكلفة الموقع', value: props.tower.monthly_rent || 0, label: 'إيجار شهري', icon: 'account_balance_wallet', color: 'text-amber-700', bg: 'bg-amber-50' },
]);

const deleteDevice = (id) => {
    if (confirm('تأكيد إزالة هذا الجهاز؟ سيتم حذف كافة البيانات والارتباطات المحفوظة.')) {
        router.delete(route('network.towers.devices.destroy', [props.tower.id, id]));
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
    <InstitutionalLayout :title="tower.name">
        <Head :title="'تفاصيل الموقع: ' + tower.name" />

        <!-- Strategic Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
            <div class="flex items-center gap-6 text-right">
                <Link 
                    :href="route('network.towers.index')" 
                    class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </Link>
                <div class="relative">
                    <div class="flex items-center gap-4 mb-2">
                        <h1 class="text-3xl font-black text-primary tracking-tight leading-none">{{ tower.name }}</h1>
                        <span 
                            class="px-3 py-1.5 rounded text-[10px] font-black uppercase tracking-widest border flex items-center gap-2"
                            :class="{
                                'bg-secondary-container/20 text-on-secondary-container border-secondary-container/30': tower.status === 'active',
                                'bg-amber-50 text-amber-700 border-amber-100': tower.status === 'maintenance',
                                'bg-error-container/10 text-error border-error-container/20': tower.status === 'inactive'
                            }"
                        >
                             <span v-if="tower.status === 'active'" class="w-2 h-2 bg-secondary rounded-full animate-pulse shadow-sm shadow-secondary/50"></span>
                             {{ tower.status === 'active' ? 'قيد التشغيل' : (tower.status === 'maintenance' ? 'صيانة دورية' : 'خارج الخدمة') }}
                        </span>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                         <span class="material-symbols-outlined text-[16px]">location_on</span>
                         إحداثيات الموقع: {{ tower.location || tower.city || 'نقطة حافة' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                 <Link 
                    :href="route('network.towers.edit', tower.id)" 
                    class="px-8 py-3.5 surface-card text-slate-500 hover:text-primary font-black text-[11px] uppercase tracking-widest flex items-center gap-3 transition-all rounded-lg border border-outline-variant/10 shadow-sm"
                 >
                    <span class="material-symbols-outlined text-[20px]">settings</span>
                    تعديل معايير الموقع
                 </Link>
                 <div class="h-10 w-px bg-outline-variant/20 mx-2 hidden lg:block"></div>
                 <div class="flex -space-x-3 rtl:space-x-reverse">
                     <div v-for="i in 3" :key="i" class="w-11 h-11 rounded-lg bg-surface-container-low border-2 border-white flex items-center justify-center text-[10px] font-black text-slate-400 shadow-sm">
                         {{ i === 3 ? '+' : 'A' }}
                     </div>
                 </div>
            </div>
        </div>

        <!-- Telemetry Data Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div 
                v-for="stat in stats" 
                :key="stat.name" 
                class="surface-card p-6 relative overflow-hidden group hover:shadow-xl transition-all rounded-lg"
            >
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ stat.name }}</p>
                        <h3 class="text-3xl font-black font-headline tracking-tighter text-primary">{{ stat.value }}</h3>
                        <p class="text-[9px] font-black border-t border-outline-variant/10 pt-2 mt-3 uppercase tracking-widest" :class="stat.color">{{ stat.label }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-lg flex items-center justify-center transition-all shadow-inner border border-outline-variant/5" :class="stat.bg + ' ' + stat.color">
                        <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">{{ stat.icon }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Institutional Multi-Tab Navigator -->
        <div class="surface-card rounded-lg overflow-hidden min-h-[600px] mb-20">
            <div class="bg-surface-container/30 border-b border-outline-variant/10 p-2">
                <nav class="flex overflow-x-auto no-scrollbar gap-1">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        class="px-8 py-4 rounded font-black text-[11px] uppercase tracking-widest transition-all flex items-center gap-3 whitespace-nowrap"
                        :class="activeTab === tab.id ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-500 hover:bg-white hover:text-primary'"
                    >
                        <span class="material-symbols-outlined text-[20px]">{{ tab.icon }}</span>
                        {{ tab.name }}
                    </button>
                </nav>
            </div>

            <div class="p-8 md:p-12">
                 <!-- 1. Overview Tab -->
                 <div v-if="activeTab === 'overview'" class="space-y-12">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 text-right">
                        <!-- Site Physics -->
                        <div class="space-y-8">
                            <h4 class="text-sm font-black text-primary uppercase tracking-widest flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-[20px]">architecture</span>
                                الخصائص الهيكلية (Physics)
                            </h4>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">نوع الهيكل</p>
                                    <p class="font-black text-sm text-primary">
                                        {{ tower.type === 'tower' ? 'برج اتصالات لاسلكي' : (tower.type === 'building' ? 'مبنى إنشائي سكني' : 'سارية / نقطة تثبيت') }}
                                    </p>
                                </div>
                                <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">الارتفاع الفعلي</p>
                                    <p class="font-black font-headline text-lg text-primary">{{ tower.height || '0' }} <span class="text-xs font-bold text-slate-400">متر</span></p>
                                </div>
                                <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5 col-span-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">إحداثيات GIS الجغرافية</p>
                                    <p class="font-headline font-black text-base text-primary tracking-widest select-all">{{ tower.lat }}, {{ tower.lng }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Uplink Gateway -->
                        <div class="space-y-8">
                            <h4 class="text-sm font-black text-primary uppercase tracking-widest flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-[20px]">hub</span>
                                بوابة الارتباط (Uplink)
                            </h4>
                            <div v-if="tower.mikrotik_server" class="p-8 bg-primary text-white rounded-lg shadow-2xl relative overflow-hidden group">
                                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-all"></div>
                                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                    <div class="text-right">
                                        <p class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5">سيرفر التحكم النشط</p>
                                        <h5 class="text-2xl font-black font-headline tracking-tighter">{{ tower.mikrotik_server.name }}</h5>
                                        <p class="text-xs font-headline font-extrabold opacity-60 mt-1 tracking-widest">{{ tower.mikrotik_server.ip }}</p>
                                    </div>
                                    <Link :href="route('servers.show', tower.mikrotik_server.id)" class="px-6 py-3 bg-white text-primary rounded font-black text-[11px] uppercase tracking-widest hover:bg-white/90 transition-all flex items-center gap-2 shadow-lg">
                                        قاعدة المنطق <span class="material-symbols-outlined text-[18px]">terminal</span>
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="p-12 bg-surface-container-low border border-dashed border-outline-variant/20 rounded-lg text-center">
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest italic">لم يتم ربط بوابة منطقية (Gateway)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Observer Log -->
                    <div v-if="tower.notes" class="p-10 bg-surface-container/30 border border-outline-variant/10 rounded-lg relative overflow-hidden">
                        <h4 class="text-[10px] font-black text-primary uppercase tracking-widest mb-4 flex items-center gap-3 italic">
                            <span class="material-symbols-outlined text-secondary text-[20px]">description</span>
                            سجل ملاحظات النزاهة الهيكلية
                        </h4>
                        <p class="text-lg font-bold text-primary leading-relaxed opacity-90 whitespace-pre-wrap">{{ tower.notes }}</p>
                    </div>
                 </div>

                 <!-- 2. Infrastructure Tab -->
                 <div v-if="activeTab === 'equipment'" class="space-y-12">
                    <!-- RF Sector -->
                    <div class="space-y-8">
                         <div class="flex items-center justify-between">
                             <h4 class="text-sm font-black text-primary uppercase tracking-widest flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-[20px]">sensors</span>
                                وحدات البث الراديوي (RF Array)
                                <span class="px-3 py-1 bg-primary text-white rounded text-[10px] font-black mr-4">{{ tower.wireless_devices?.length || 0 }} وحدة</span>
                             </h4>
                             <button class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-black text-[11px] uppercase tracking-widest hover:bg-primary-container transition-all shadow-lg shadow-primary/10">
                                <span class="material-symbols-outlined text-[18px]">add</span> تهيئة راديو جديد
                             </button>
                         </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="device in tower.wireless_devices" :key="device.id" class="p-6 surface-card rounded-lg border border-outline-variant/10 hover:shadow-xl transition-all group">
                                <div class="flex items-start justify-between mb-8">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 bg-primary text-white rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">wifi_tethering</span>
                                        </div>
                                        <div class="text-right">
                                            <h5 class="text-lg font-black text-primary tracking-tight leading-none">{{ device.name }}</h5>
                                            <div class="flex items-center gap-3 mt-2">
                                                <span class="px-2 py-0.5 bg-primary-fixed/30 text-primary rounded text-[9px] font-black uppercase tracking-widest border border-primary-fixed/50">{{ device.mode }}</span>
                                                <span class="text-[11px] font-headline font-extrabold text-slate-400 tracking-widest">{{ device.ip }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click="deleteDevice(device.id)" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-error hover:bg-error/5 rounded transition-all">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                     <div class="p-4 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">ملف التردد</p>
                                        <p class="font-black font-headline text-sm text-primary">{{ device.frequency || '5 GHz' }}</p>
                                     </div>
                                     <div class="p-4 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">عدد المشتركين</p>
                                        <p class="font-black font-headline text-sm text-secondary">{{ device.clients_count || 0 }} متصل</p>
                                     </div>
                                </div>
                            </div>
                         </div>
                    </div>

                    <!-- Commutation Layer -->
                     <div class="space-y-8">
                         <h4 class="text-sm font-black text-primary uppercase tracking-widest flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">settings_input_component</span>
                            طبقة التبديل والارتباط (Switches)
                            <span class="px-3 py-1 bg-surface-container-highest text-primary rounded text-[10px] font-black mr-4">{{ tower.switch_devices?.length || 0 }} وحدة</span>
                         </h4>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="sw in tower.switch_devices" :key="sw.id" class="p-6 surface-card rounded-lg border border-outline-variant/10 shadow-sm hover:shadow-lg transition-all group">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 bg-surface-container flex items-center justify-center text-primary rounded-lg shadow-inner border border-outline-variant/10 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-[28px]">data_array</span>
                                    </div>
                                    <div class="flex-1 min-w-0 text-right">
                                        <div class="flex items-center justify-between">
                                            <h5 class="text-lg font-black text-primary leading-none">{{ sw.name }}</h5>
                                            <button @click="deleteDevice(sw.id)" class="text-slate-300 hover:text-error transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-4 mt-2">
                                             <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 font-headline">{{ sw.ports_count || 24 }} Port Ledger</span>
                                             <div class="h-1 w-1 bg-outline-variant rounded-full"></div>
                                             <span class="text-[11px] font-headline font-extrabold text-primary tracking-widest">{{ sw.ip || 'INTERNAL_SITE_NODE' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                 </div>

                 <!-- 3. Energy Tab -->
                 <div v-if="activeTab === 'power'" class="space-y-12">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-right">
                        <!-- Batteries -->
                        <div v-if="tower.battery_count > 0" class="p-8 surface-card rounded-lg relative overflow-hidden shadow-sm border border-outline-variant/10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-primary-fixed/20 text-primary rounded-lg flex items-center justify-center shadow-inner">
                                    <span class="material-symbols-outlined text-[24px]">battery_std</span>
                                </div>
                                <h4 class="text-lg font-black text-primary uppercase tracking-widest">مجمع الطاقة الكيميائية (Batteries)</h4>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="p-5 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">إجمالي الخلايا</p>
                                    <p class="text-2xl font-black font-headline text-primary tracking-tighter">{{ tower.battery_count }} <span class="text-xs">خلايا</span></p>
                                </div>
                                <div class="p-5 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">نوع تكنولوجيا التخزين</p>
                                    <p class="text-sm font-black uppercase text-primary leading-tight">{{ tower.battery_type }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Solar -->
                        <div v-if="tower.has_solar" class="p-8 surface-card rounded-lg relative overflow-hidden shadow-sm border border-outline-variant/10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-lg flex items-center justify-center shadow-inner">
                                    <span class="material-symbols-outlined text-[24px]">wb_sunny</span>
                                </div>
                                <h4 class="text-lg font-black text-primary uppercase tracking-widest">نظام الامتصاص الكهروضوئي (Solar)</h4>
                            </div>
                            <div class="grid grid-cols-3 gap-6">
                                <div class="p-5 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">عدد الألواح</p>
                                    <p class="font-black font-headline text-lg text-primary">{{ tower.solar_panels_count }}</p>
                                </div>
                                <div class="p-5 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">قدرة اللوح</p>
                                    <p class="font-black font-headline text-lg text-primary">{{ tower.solar_panel_wattage }}W</p>
                                </div>
                                <div class="p-5 bg-primary text-white rounded-lg shadow-xl shadow-primary/20">
                                    <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1.5">القدرة الإجمالية</p>
                                    <p class="font-black font-headline text-lg">{{ tower.total_solar_capacity || (tower.solar_panels_count * tower.solar_panel_wattage) }}W</p>
                                </div>
                            </div>
                        </div>

                        <!-- Generator -->
                        <div v-if="tower.has_generator" class="p-8 surface-card rounded-lg shadow-sm border border-outline-variant/10 flex flex-col justify-center">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-error-container/10 text-error rounded-lg flex items-center justify-center shadow-inner">
                                    <span class="material-symbols-outlined text-[24px]">bolt</span>
                                </div>
                                <h4 class="text-lg font-black text-primary uppercase tracking-widest">مولد الاحتراق الاحتياطي (Generator)</h4>
                            </div>
                            <div class="p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">سعة التوليد القصوى / البروتوكول</p>
                                <p class="text-2xl font-black font-headline text-primary tracking-tighter">{{ tower.generator_capacity }}</p>
                            </div>
                        </div>
                    </div>
                 </div>

                 <!-- 4. Ledger Tab -->
                 <div v-if="activeTab === 'costs'" class="space-y-12">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 text-right">
                        <!-- Operational Burn -->
                         <div class="p-10 surface-card rounded-lg shadow-sm border border-outline-variant/10 relative overflow-hidden">
                             <h4 class="text-xl font-black text-primary uppercase tracking-widest mb-10 flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary-fixed/20 text-primary rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[24px]">account_balance_wallet</span>
                                </div>
                                تكاليف التشغيل الشهرية (OPEX)
                             </h4>
                             <div class="space-y-4">
                                <div class="flex items-center justify-between p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest font-headline">إيجار الموقع الفعلي</span>
                                    <span class="font-black font-headline text-base text-primary">{{ tower.monthly_rent?.toLocaleString() }} <span class="text-[10px] font-bold text-slate-400 mr-1">ر.س</span></span>
                                </div>
                                <div class="flex items-center justify-between p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest font-headline">تكاليف الصيانة والحراسة</span>
                                    <span class="font-black font-headline text-base text-primary">{{ tower.monthly_maintenance?.toLocaleString() || 0 }} <span class="text-[10px] font-bold text-slate-400 mr-1">ر.س</span></span>
                                </div>
                                <div class="p-8 bg-primary text-white rounded-lg shadow-2xl shadow-primary/20 mt-8">
                                    <p class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-2">إجمالي الالتزام الشهري</p>
                                    <h5 class="text-4xl font-black font-headline tracking-tighter">{{ (parseFloat(tower.monthly_rent) || 0) + (parseFloat(tower.monthly_maintenance) || 0) }} <span class="text-base font-bold text-white/60 mr-2">ر.س</span></h5>
                                </div>
                             </div>
                         </div>

                         <!-- Investment Artifact -->
                         <div class="p-10 surface-card rounded-lg shadow-sm border border-outline-variant/10">
                             <h4 class="text-xl font-black text-primary uppercase tracking-widest mb-10 flex items-center gap-4">
                                <div class="w-12 h-12 bg-secondary-container/20 text-on-secondary-container rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[24px]">analytics</span>
                                </div>
                                الاستثمار الرأسمالي (CAPEX)
                             </h4>
                             <div class="space-y-4">
                                <div class="flex items-center justify-between p-6 bg-surface-container-low rounded-lg border border-outline-variant/5">
                                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest font-headline">تكلفة التأسيس والإنشاء</span>
                                    <span class="font-black font-headline text-base text-primary">{{ tower.structure_cost?.toLocaleString() || 0 }} <span class="text-[10px] font-bold text-slate-400 mr-1">ر.س</span></span>
                                </div>
                                <div class="p-8 bg-secondary-container/20 border border-secondary/20 text-on-secondary-container rounded-lg shadow-lg mt-8 relative overflow-hidden">
                                     <p class="text-[10px] font-black text-on-secondary-container/60 uppercase tracking-widest mb-2">إجمالي قيمة التجهيزات</p>
                                     <h5 class="text-4xl font-black font-headline tracking-tighter">{{ tower.total_equipment_cost?.toLocaleString() || tower.structure_cost?.toLocaleString() || 0 }} <span class="text-base font-bold text-on-secondary-container/60 mr-2">ر.س</span></h5>
                                </div>
                             </div>
                         </div>
                    </div>
                 </div>

                 <!-- 5. Link Topology Tab -->
                 <div v-if="activeTab === 'connection'" class="space-y-12 animate-in fade-in duration-500">
                    <div class="max-w-4xl mx-auto space-y-12 text-right">
                        <!-- Primary Gateway -->
                        <div v-if="tower.mikrotik_server" class="p-10 bg-primary text-white rounded-lg shadow-2xl relative overflow-hidden">
                            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                                <div class="flex items-center gap-6">
                                    <div class="w-16 h-16 bg-white/10 rounded-lg flex items-center justify-center border border-white/10">
                                        <span class="material-symbols-outlined text-[32px] text-white/80">hub</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5">بوابة التحكم والارتباط (Master Gateway)</p>
                                        <h5 class="text-2xl font-black font-headline tracking-tighter">{{ tower.mikrotik_server.name }}</h5>
                                        <p class="text-xs font-headline font-extrabold opacity-60 mt-1 tracking-widest">{{ tower.mikrotik_server.ip }}</p>
                                    </div>
                                </div>
                                <Link :href="route('servers.show', tower.mikrotik_server.id)" class="px-8 py-4 bg-white text-primary rounded font-black text-[11px] uppercase tracking-widest hover:bg-white/90 transition-all shadow-xl active:scale-95">
                                    بدء التحكم المركزي
                                </Link>
                            </div>
                        </div>

                        <!-- Backhaul P2P Topology -->
                        <div v-if="tower.connection_type === 'wireless'" class="space-y-10">
                             <div class="flex items-center gap-6">
                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] whitespace-nowrap">تحليل الارتباط الصاعد (P2P Backhaul)</span>
                                <div class="h-px w-full bg-outline-variant/10"></div>
                             </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative">
                                <!-- Connection Pulse Visual -->
                                <div class="hidden md:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                                    <div class="w-14 h-14 bg-white border border-outline-variant/10 rounded-full flex items-center justify-center shadow-xl">
                                        <span class="material-symbols-outlined text-primary text-[28px] animate-pulse">settings_input_antenna</span>
                                    </div>
                                </div>

                                <!-- Transmitter Unit -->
                                <div class="p-8 surface-card border border-outline-variant/10 rounded-lg group hover:bg-surface-container-low transition-all">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8">مصدر الإشارة (PTP-Transmitter)</p>
                                    <div v-if="tower.transmitter_router" class="flex gap-6">
                                         <div class="w-14 h-14 bg-primary text-white rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">sensors</span>
                                         </div>
                                         <div class="min-w-0">
                                            <h6 class="font-black text-lg text-primary leading-tight mb-2 truncate">{{ tower.transmitter_router.name }}</h6>
                                            <p class="text-[11px] font-headline font-extrabold text-slate-400 tracking-widest uppercase">{{ tower.transmitter_router.ip }}</p>
                                            <div v-if="tower.transmitter_router.tower" class="mt-6 pt-6 border-t border-outline-variant/10">
                                                <p class="text-[9px] font-black text-slate-400 uppercase mb-2">موقع البث في المصفوفة:</p>
                                                <Link :href="route('network.towers.show', tower.transmitter_router.tower_id)" class="text-xs font-black text-primary flex items-center gap-2 hover:translate-x-[-4px] transition-transform">
                                                    <span class="w-1.5 h-3.5 bg-primary rounded-full"></span>
                                                    {{ tower.transmitter_router.tower.name }}
                                                </Link>
                                            </div>
                                         </div>
                                    </div>
                                </div>

                                <!-- Local Receiver Unit -->
                                <div class="p-8 bg-surface-container-highest rounded-lg border border-outline-variant/20 shadow-inner group">
                                     <p class="text-[10px] font-black text-primary/60 uppercase tracking-widest mb-8">وحدة الاستقبال المحلية (Link-Terminal)</p>
                                     <div class="flex gap-6 relative z-10">
                                         <div class="w-14 h-14 bg-white text-primary rounded-lg flex items-center justify-center border border-outline-variant/10 group-hover:scale-110 transition-all shadow-sm">
                                            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">wifi</span>
                                         </div>
                                         <div>
                                            <h6 class="font-black text-lg text-primary leading-tight mb-2">واجهة استقبال {{ tower.name }}</h6>
                                            <p class="text-[11px] font-headline font-extrabold text-primary/80 font-bold tracking-widest">{{ tower.receiver_ip || 'بانتظار الإشارة...' }}</p>
                                            <span class="mt-5 inline-block px-3 py-1.5 bg-primary/10 rounded-md text-[9px] font-black text-primary uppercase tracking-widest">{{ tower.receiver_model?.model_name || 'وحدة نبض لاسلكي' }}</span>
                                         </div>
                                     </div>
                                </div>
                             </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
/* High-density grid refinements */
.font-headline {
    letter-spacing: -0.015em;
}
</style>
