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
        'tower': { label: 'برج لاسلكي (Tower)', icon: 'tower', color: 'text-primary', bg: 'bg-primary/5' },
        'building': { label: 'موقع مدني (Building)', icon: 'apartment', color: 'text-indigo-600', bg: 'bg-indigo-500/5' },
        'cabinet': { label: 'خزانة توزيع (Cabinet)', icon: 'box', color: 'text-secondary', bg: 'bg-secondary/5' },
        'office': { label: 'مكتب فني (Office)', icon: 'home', color: 'text-slate-600', bg: 'bg-slate-500/5' },
        'pole': { label: 'سارية طرفية (Pole)', icon: 'navigation', color: 'text-slate-500', bg: 'bg-slate-500/5' },
    };
    return map[type] || map['pole'];
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'active': return { label: 'تشغيلي (Live)', class: 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20', icon: 'check_circle' };
        case 'maintenance': return { label: 'قيد الصيانة', class: 'bg-amber-500/10 text-amber-600 border-amber-500/20', icon: 'manufacturing' };
        case 'inactive': return { label: 'منقطع / خامل', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20', icon: 'cancel' };
        default: return { label: 'غير محدد', class: 'bg-slate-500/10 text-slate-500 border-slate-500/20', icon: 'help_center' };
    }
};

const deleteTower = (id) => {
    if (confirm('تأكيد إزالة الموقع؟ هذا الإجراء سيؤدي إلى حذف كافة بيانات الأجهزة المرتبطة به بشكل نهائي.')) {
        router.delete(route('network.towers.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <InstitutionalLayout title="سجل المواقع">
        <Head title="أصول البنية التحتية - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">إدارة أصول البنية التحتية (Network Infrastructure)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">حوكمة المواقع الفيزيائية، تتبع التغطية الجغرافية، وإدارة عقد الربط</p>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <Link 
                    :href="route('network.towers.create')" 
                    class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-4 border border-white/10"
                >
                    <span class="material-symbols-outlined text-[24px]">add_location_alt</span> تثبيت موقع جديد
                </Link>
            </div>

            <!-- Telemetry Matrix -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Total Sites -->
                <div class="surface-card p-10 rounded-2xl border border-outline-variant/5 shadow-2xl bg-white relative overflow-hidden group">
                    <div class="absolute -top-16 -left-16 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col gap-6 text-right">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center border border-primary/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">cell_tower</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">إجمالي المواقع (Sites)</p>
                        </div>
                        <h3 class="text-5xl font-headline font-black text-primary leading-none tracking-tighter">{{ stats.totalTowers }}</h3>
                    </div>
                </div>

                <!-- Online Sites -->
                <div class="surface-card p-10 rounded-2xl border border-outline-variant/5 shadow-sm bg-white border-b-8 border-emerald-500">
                    <div class="relative z-10 flex flex-col gap-6 text-right">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-emerald-500/5 text-emerald-600 flex items-center justify-center border border-emerald-500/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">sensors</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">المواقع التشغيلية (Live)</p>
                        </div>
                        <div class="flex items-baseline gap-4 justify-end flex-row-reverse">
                            <h3 class="text-5xl font-headline font-black text-emerald-600 leading-none tracking-tighter">{{ stats.activeTowers }}</h3>
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-500/10 leading-none">
                                {{ stats.totalTowers > 0 ? Math.round((stats.activeTowers / stats.totalTowers) * 100) : 0 }}% كفاءة
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Sites -->
                <div class="surface-card p-10 rounded-2xl border border-outline-variant/5 shadow-sm bg-white border-b-8 border-amber-500">
                    <div class="relative z-10 flex flex-col gap-6 text-right">
                        <div class="flex items-center justify-between flex-row-reverse text-right">
                             <div class="w-14 h-14 rounded-2xl bg-amber-500/5 text-amber-600 flex items-center justify-center border border-amber-500/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">build_circle</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">قيد الصيانة الفنية</p>
                        </div>
                        <h3 class="text-5xl font-headline font-black text-amber-600 leading-none tracking-tighter">{{ stats.maintenanceTowers }}</h3>
                    </div>
                </div>

                <!-- Attached Routers -->
                <div class="surface-card p-10 rounded-2xl border border-outline-variant/5 shadow-sm bg-slate-950 text-white border-none relative overflow-hidden">
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex flex-col gap-6 text-right">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-white/5 text-primary flex items-center justify-center border border-white/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">router</span>
                             </div>
                             <p class="text-[11px] font-black text-white/40 uppercase tracking-[0.15em]">إجمالي عقد الأجهزة</p>
                        </div>
                        <h3 class="text-5xl font-headline font-black text-white leading-none tracking-tighter">{{ stats.totalRouters }}</h3>
                    </div>
                </div>
            </div>

            <!-- Precision Filters -->
            <div class="surface-card p-10 mb-12 rounded-3xl flex flex-col lg:flex-row items-center gap-10 bg-white border border-outline-variant/5 shadow-sm">
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-4 text-right">
                        <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2 leading-none">النطاق الجغرافي (Region)</label>
                        <div class="relative group">
                            <select v-model="filter.city" class="form-input-monolith h-16 pr-14 text-sm font-black appearance-none">
                                <option value="all">كافة المناطق المشمولة</option>
                                <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-primary text-[24px]">map</span>
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-4 text-right">
                        <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2 leading-none">بروتوكول الحالة (System State)</label>
                        <div class="relative group">
                            <select v-model="filter.status" class="form-input-monolith h-16 pr-14 text-sm font-black appearance-none">
                                <option value="all">كافة الأوضاع التشغيلية</option>
                                <option value="active">وضع البث الحي (Live)</option>
                                <option value="maintenance">تحت التدقيق الفني</option>
                                <option value="inactive">خارج نطاق الخدمة</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-primary text-[24px]">verified</span>
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-4 text-right">
                        <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2 leading-none">تصنيف المنشأة (Structure)</label>
                        <div class="relative group">
                            <select v-model="filter.type" class="form-input-monolith h-16 pr-14 text-sm font-black appearance-none">
                                <option value="all">كافة أنواع المواقع</option>
                                <option value="tower">برج لاسلكي متخصص</option>
                                <option value="building">موقع ضمن منشأة حضرية</option>
                                <option value="cabinet">خزانة توزيع بروتوكولية</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-primary text-[24px]">layers</span>
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                </div>

                <button 
                    @click="filter = { city: 'all', status: 'all', type: 'all' }"
                    class="h-16 px-10 bg-surface-container-low border border-outline-variant/10 hover:bg-white text-slate-400 hover:text-primary transition-all active:scale-95 rounded-2xl flex items-center justify-center gap-4 shrink-0 shadow-inner"
                >
                    <span class="material-symbols-outlined text-[24px]">restart_alt</span>
                    <span class="text-[11px] font-black uppercase tracking-widest">تصفير الفلاتر</span>
                </button>
            </div>

            <!-- Institutional Register (The Table) -->
            <div class="surface-card rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/5 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">هوية الموقع (Site Identity)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-white/40">التصنيف الوظيفي</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40 border-r border-white/5">عقد الربط</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">منظومة الطاقة</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] text-center text-white/40">الوضع الحالي</th>
                                <th class="px-10 py-6 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="tower in towers" :key="tower.id" class="group hover:bg-surface-container-low/50 transition-all duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-8 justify-end text-right">
                                        <div>
                                            <h4 class="text-lg font-black text-primary leading-tight group-hover:translate-x-1 transition-transform">{{ tower.name }}</h4>
                                            <div class="flex items-center gap-3 mt-2 opacity-50 justify-end">
                                                <p class="text-[10px] font-black uppercase tracking-widest leading-none">{{ tower.city }} <span class="text-primary/50 text-xs">/</span> {{ tower.district || 'عقدة غير معنونة' }}</p>
                                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-outline-variant/10 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white shadow-inner transition-all overflow-hidden border-none shadow-2xl">
                                             <div class="absolute inset-0 bg-primary opacity-5 group-hover:opacity-0 transition-opacity"></div>
                                            <span class="material-symbols-outlined text-[32px] relative z-10" style="font-variation-settings: 'FILL' 1">
                                                {{ getSiteTypeDetails(tower.type).icon }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-[10px] font-black text-slate-500 uppercase tracking-widest shadow-sm">
                                        <span class="material-symbols-outlined text-[16px]">{{ getSiteTypeDetails(tower.type).icon }}</span>
                                        {{ getSiteTypeDetails(tower.type).label }}
                                    </span>
                                </td>
                                <td class="px-10 py-8 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-xl font-headline font-black text-primary leading-none">{{ tower.routers_count || 0 }}</span>
                                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">عقدة نشطة</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-center gap-4">
                                        <div v-if="tower.has_solar" class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center border border-amber-500/10 shadow-sm" title="طاقة شمسية">
                                            <span class="material-symbols-outlined text-[20px]">wb_sunny</span>
                                        </div>
                                        <div v-if="tower.battery_count > 0" class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center border border-secondary/10 shadow-sm" title="منظومة بطاريات">
                                            <span class="material-symbols-outlined text-[20px]">battery_charging_full</span>
                                        </div>
                                        <div v-if="tower.has_generator" class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center border border-rose-500/10 shadow-sm" title="مولد طوارئ">
                                            <span class="material-symbols-outlined text-[20px]">offline_bolt</span>
                                        </div>
                                        <div v-if="!tower.has_solar && !tower.has_generator && tower.battery_count === 0" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-300 flex items-center justify-center border border-slate-200" title="لا توجد بيانات طاقة">
                                            <span class="material-symbols-outlined text-[20px]">bolt_slash</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-right">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-3 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border-2 transition-all shadow-sm group-hover:scale-105',
                                        getStatusDetails(tower.status || 'active').class
                                    ]">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-current"></span>
                                        </span>
                                        {{ getStatusDetails(tower.status || 'active').label }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <Link 
                                            :href="route('network.towers.show', tower.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">visibility</span>
                                         </Link>
                                         <Link 
                                            :href="route('network.towers.edit', tower.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">troubleshoot</span>
                                         </Link>
                                         <button 
                                            @click="deleteTower(tower.id)"
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">delete_sweep</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="towers.length === 0" class="py-48 flex flex-col items-center gap-10 group/empty">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-2xl group-hover/empty:scale-110 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">location_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-black text-primary mb-3">سجل أسطول المواقع فارغ</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm leading-relaxed">لم يتم رصد أي أصول بنية تحتية نشطة حالياً ضمن نطاق التتبع.</p>
                    </div>
                    <Link :href="route('network.towers.create')" class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:bg-emerald-600 transition-all active:scale-95 border border-white/10 flex items-center gap-4">
                        <span class="material-symbols-outlined text-[24px]">add_location_alt</span> تثبيت البنيـة الأولیة
                    </Link>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>
