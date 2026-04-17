<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    towers: Array,
    cities: Array,
    stats: Object,
    filters: Object,
});

const filter = ref({
    city: props.filters.city || 'all',
    status: props.filters.status || 'all',
    type: props.filters.type || 'all',
});

const applyFilters = () => {
    router.get(route('network.towers.index'), filter.value, {
        preserveState: true,
        replace: true,
    });
};

watch(filter, applyFilters, { deep: true });

const getSiteTypeDetails = (type) => {
    const map = {
        'tower': { label: 'برج اتصالات متخصص', icon: 'tower', color: 'text-primary' },
        'building': { label: 'موقع حضري (Building)', icon: 'apartment', color: 'text-indigo-600' },
        'cabinet': { label: 'خزانة توزيع بروتوكولية', icon: 'box', color: 'text-secondary' },
        'office': { label: 'مركز عمليات (NOC)', icon: 'home', color: 'text-slate-600' },
        'pole': { label: 'سارية ربط طرفية', icon: 'navigation', color: 'text-slate-500' },
    };
    return map[type] || map['pole'];
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'active': return { label: 'النبض مستقر (Live)', class: 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20', icon: 'check_circle' };
        case 'maintenance': return { label: 'تدقيق فني (Maint)', class: 'bg-amber-500/10 text-amber-600 border-amber-500/20', icon: 'manufacturing' };
        case 'inactive': return { label: 'منقطع سيادياً', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20', icon: 'cancel' };
        default: return { label: 'غير معرف', class: 'bg-slate-500/10 text-slate-500 border-slate-500/20', icon: 'help_center' };
    }
};

const deleteTower = (id) => {
    if (confirm('تأكيد إخراج الموقع من المصفوفة الجيومكانية؟ سيتم إتلاف كافة سجلات الأجهزة المرتبطة برمجياً.')) {
        router.delete(route('network.towers.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <InstitutionalLayout title="مصفوفة المواقع الجيومكانية">
        <Head title="مصفوفة المواقع الجيومكانية (Site Matrix) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">مصفوفة المواقع الجيومكانية (Geospatial Matrix)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">حوكمة الأصول الميدانية، تتبع التغطية الطيفية، وإدارة مراكز الربط</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <Link 
                    :href="route('network.towers.create')" 
                    class="px-14 py-6 bg-slate-950 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 flex items-center gap-5 border border-white/10 group"
                >
                    <span class="material-symbols-outlined text-[28px] group-hover:rotate-180 transition-transform">add_location_alt</span>
                    تثبيت موقع جديد (Inject Site)
                </Link>
            </div>

            <!-- Fleet Telemetry Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Site Inventory Metric -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white relative overflow-hidden group">
                    <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 bg-slate-950 text-white rounded-2xl flex items-center justify-center border border-white/10 shadow-2xl group-hover:rotate-12 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">cell_tower</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">تعداد المواقع (Sites)</p>
                        </div>
                        <h3 class="text-6xl font-headline font-black text-primary leading-none tracking-tighter">{{ stats.totalTowers }}</h3>
                    </div>
                </div>

                <!-- Operational Efficiency Flux -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white border-b-8 border-emerald-500 group">
                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center border border-emerald-100 shadow-inner group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">wifi_tethering</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">النبض التشغيلي (Live)</p>
                        </div>
                        <div class="flex items-baseline gap-4 justify-start flex-row-reverse">
                            <h3 class="text-6xl font-headline font-black text-emerald-600 leading-none tracking-tighter">{{ stats.activeTowers }}</h3>
                            <span class="px-4 py-1.5 bg-emerald-500/10 text-emerald-600 rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-500/10">
                                {{ stats.totalTowers > 0 ? Math.round((stats.activeTowers / stats.totalTowers) * 100) : 0 }}% CORE_UP
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Technical Maintenance Queue -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white border-b-8 border-amber-500 group">
                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center border border-amber-100 shadow-inner group-hover:-rotate-12 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">manufacturing</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">تحت المعاينة الفنية</p>
                        </div>
                        <h3 class="text-6xl font-headline font-black text-amber-600 leading-none tracking-tighter">{{ stats.maintenanceTowers }}</h3>
                    </div>
                </div>

                <!-- Cluster Node Density -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-slate-950 text-white border-none relative overflow-hidden group">
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-primary/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 bg-white/10 text-primary rounded-2xl flex items-center justify-center border border-white/10 shadow-2xl">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">router</span>
                             </div>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">إجمالي عقد المعالجة</p>
                        </div>
                        <h3 class="text-6xl font-headline font-black text-white leading-none tracking-tighter">{{ stats.totalRouters }}</h3>
                    </div>
                </div>
            </div>

            <!-- Strategic Precision Filters -->
            <div class="surface-card p-12 mb-12 rounded-[3rem] flex flex-col lg:flex-row items-center gap-12 bg-white border border-outline-variant/10 shadow-2xl relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-l from-primary via-secondary to-primary opacity-20"></div>
                
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="space-y-4 text-right">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 leading-none italic">المجال الجغرافي (Region)</label>
                        <div class="relative group">
                            <select v-model="filter.city" class="form-input-monolith h-18 pr-16 text-sm font-black appearance-none rounded-2xl border-slate-200 focus:border-primary transition-all shadow-inner">
                                <option value="all">كافة المناطق السيادية</option>
                                <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary text-[28px] opacity-40 group-hover:opacity-100 transition-opacity">map</span>
                            <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_content</span>
                        </div>
                    </div>

                    <div class="space-y-4 text-right">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 leading-none italic">بروتوكول الحالة (Pulse State)</label>
                        <div class="relative group">
                            <select v-model="filter.status" class="form-input-monolith h-18 pr-16 text-sm font-black appearance-none rounded-2xl border-slate-200 focus:border-primary transition-all shadow-inner">
                                <option value="all">كافة الحالات التشغيلية</option>
                                <option value="active">مستوى البث الحي (Live)</option>
                                <option value="maintenance">إجراءات الصيانة الوقائية</option>
                                <option value="inactive">خارج النطاق الطيفي</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary text-[28px] opacity-40 group-hover:opacity-100 transition-opacity">verified</span>
                            <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_content</span>
                        </div>
                    </div>

                    <div class="space-y-4 text-right">
                        <label class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block mr-3 leading-none italic">تصنيف المنشأة (Structure)</label>
                        <div class="relative group">
                            <select v-model="filter.type" class="form-input-monolith h-18 pr-16 text-sm font-black appearance-none rounded-2xl border-slate-200 focus:border-primary transition-all shadow-inner">
                                <option value="all">كافة أنواع المواقع</option>
                                <option value="tower">برج اتصالات سيادي</option>
                                <option value="building">موقع ضمن كتلة عمرانية</option>
                                <option value="cabinet">خزانة بروتوكولية طرفية</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary text-[28px] opacity-40 group-hover:opacity-100 transition-opacity">layers</span>
                            <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_content</span>
                        </div>
                    </div>
                </div>

                <button 
                    @click="filter = { city: 'all', status: 'all', type: 'all' }"
                    class="h-18 px-12 bg-slate-50 border border-slate-200 hover:bg-white text-slate-400 hover:text-primary transition-all active:scale-95 rounded-2xl flex items-center justify-center gap-5 shrink-0 shadow-lg group"
                >
                    <span class="material-symbols-outlined text-[28px] group-hover:rotate-180 transition-transform">restart_alt</span>
                    <span class="text-[11px] font-black uppercase tracking-[0.3em]">تصفير المصفوفة</span>
                </button>
            </div>

            <!-- Institutional Register Ledger -->
            <div class="surface-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                 <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                <th class="px-12 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">هوية الموقع (Asset Identity)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">التوصيف الهيكلي</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">عقد المعالجة</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30">مصفوفة الطاقة</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30">النبض الراهن</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="tower in towers" :key="tower.id" class="group hover:bg-slate-50/80 transition-all duration-500">
                                <td class="px-12 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-2xl font-black text-primary leading-tight group-hover:translate-x-3 transition-transform tracking-tight uppercase">{{ tower.name }}</h4>
                                            <div class="flex items-center gap-3 mt-3 opacity-40 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-[0.2em] leading-none">{{ tower.city }} <span class="text-primary/50 text-base">/</span> {{ tower.district || 'قطاع غير معنون' }}</p>
                                                <span class="material-symbols-outlined text-[18px]">location_on_special</span>
                                            </div>
                                        </div>
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                             <span class="material-symbols-outlined text-[36px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">
                                                {{ getSiteTypeDetails(tower.type).icon }}
                                             </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <span class="inline-flex items-center gap-4 px-6 py-3 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] shadow-xl group-hover:border-primary transition-all">
                                        <span class="material-symbols-outlined text-[20px] text-primary" style="font-variation-settings: 'FILL' 1">{{ getSiteTypeDetails(tower.type).icon }}</span>
                                        {{ getSiteTypeDetails(tower.type).label }}
                                    </span>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="text-3xl font-headline font-black text-primary leading-none group-hover:scale-125 transition-transform">{{ tower.routers_count || 0 }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none">NODE_ARRAY</span>
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-center gap-5">
                                        <div v-if="tower.has_solar" class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center border border-amber-100 shadow-inner group-hover:animate-spin-slow" title="نظام طاقة شمسية مستدام">
                                            <span class="material-symbols-outlined text-[24px]">wb_sunny</span>
                                        </div>
                                        <div v-if="tower.battery_count > 0" class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-inner" title="مصفوفة بطاريات تخزين">
                                            <span class="material-symbols-outlined text-[24px]">battery_vert_050</span>
                                        </div>
                                        <div v-if="tower.has_generator" class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shadow-inner" title="وحدة توليد طاقة احتياطية">
                                            <span class="material-symbols-outlined text-[24px]">power</span>
                                        </div>
                                        <div v-if="!tower.has_solar && !tower.has_generator && tower.battery_count === 0" class="w-14 h-14 rounded-2xl bg-slate-50 text-slate-200 flex items-center justify-center border border-slate-100" title="غياب بيانات الطاقة">
                                            <span class="material-symbols-outlined text-[24px]">flash_off</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-4 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border-2 transition-all shadow-2xl group-hover:translate-y-[-4px]',
                                        getStatusDetails(tower.status || 'active').class
                                    ]">
                                        <span class="relative flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-current"></span>
                                        </span>
                                        {{ getStatusDetails(tower.status || 'active').label }}
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link 
                                            :href="route('network.towers.show', tower.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">visibility</span>
                                         </Link>
                                         <Link 
                                            :href="route('network.towers.edit', tower.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:-rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">troubleshoot</span>
                                         </Link>
                                         <button 
                                            @click="deleteTower(tower.id)"
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:scale-75 transition-transform" style="font-variation-settings: 'wght' 700">delete_forever</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty Topology Protocol -->
                <div v-if="towers.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-48 h-48 rounded-[3.5rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.15)] group-hover/empty:scale-110 group-hover/empty:rotate-45 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">location_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-4xl font-black text-primary mb-6 tracking-tighter uppercase leading-none">مجموع الخلية صفر (Null Domain)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-70">لم يتم تثبيت أي أصول ميدانية ضمن المصفوفة الجيومكانية. ابدأ بروتوكول Inject Site الأول.</p>
                    </div>
                    <Link :href="route('network.towers.create')" class="px-16 py-8 bg-primary text-white rounded-3xl font-black text-xs uppercase tracking-[0.4em] shadow-[0_30px_60px_rgba(37,99,235,0.25)] hover:bg-emerald-600 hover:-translate-y-2 transition-all active:scale-95 border border-white/10 flex items-center gap-6">
                        <span class="material-symbols-outlined text-[32px]">add_location_alt</span> تأسيس البنية التحتية
                    </Link>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline { font-family: 'Manrope', sans-serif; }
.bg-grid-slate-50 {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(15 23 42 / 0.05)'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
}
.animate-spin-slow {
    animation: spin 8s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
