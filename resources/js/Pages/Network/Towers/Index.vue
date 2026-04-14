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
        onSuccess: () => {
            // Success handshake
        }
    });
};

watch(filter, applyFilters, { deep: true });

const getSiteTypeDetails = (type) => {
    const map = {
        'tower': { label: 'برج اتصالات', icon: 'cell_tower', color: 'text-primary', bg: 'bg-primary-fixed/20' },
        'building': { label: 'مبنى سكني', icon: 'domain', color: 'text-indigo-600', bg: 'bg-indigo-50' },
        'cabinet': { label: 'كابينة توزيع', icon: 'inventory_2', color: 'text-secondary', bg: 'bg-secondary-container/20' },
        'office': { label: 'مكتب إداري', icon: 'corporate_fare', color: 'text-slate-600', bg: 'bg-slate-50' },
        'pole': { label: 'سارية / عمود', icon: 'navigation', color: 'text-slate-500', bg: 'bg-slate-50' },
    };
    return map[type] || map['pole'];
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'active': return { label: 'نشط / تشغيلي', color: 'bg-secondary-container/20 text-on-secondary-container border-secondary-container/30', icon: 'check_circle' };
        case 'maintenance': return { label: 'قيد الصيانة', color: 'bg-amber-50 text-amber-700 border-amber-100', icon: 'build' };
        case 'inactive': return { label: 'خارج الخدمة', color: 'bg-error-container/10 text-error border-error-container/20', icon: 'cancel' };
        default: return { label: status, color: 'bg-slate-50 text-slate-500 border-slate-100', icon: 'help' };
    }
};

const deleteTower = (id) => {
    if (confirm('تأكيد إزالة الموقع؟ هذا الإجراء سيؤدي إلى حذف كافة بيانات الأجهزة المرتبطة به.')) {
        router.delete(route('network.towers.destroy', id));
    }
};
</script>

<template>
    <InstitutionalLayout title="إدارة المواقع">
        <Head title="إدارة البنية التحتية" />

        <!-- Institutional Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-tight mb-2">إدارة أسطول المواقع</h1>
                <div class="flex items-center gap-3">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                    </span>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">مركز حوكمة البنية التحتية</p>
                </div>
            </div>
            <Link 
                :href="route('network.towers.create')" 
                class="inline-flex items-center gap-3 px-8 py-4 bg-primary text-white rounded-lg font-bold shadow-xl shadow-primary/20 hover:bg-primary-container transition-all active:scale-95"
            >
                <span class="material-symbols-outlined text-[20px]">add_location</span>
                <span class="text-sm">تثبيت موقع جديد</span>
            </Link>
        </div>

        <!-- Infrastructure Telemetry Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="surface-card p-6 rounded-lg">
                <div class="w-12 h-12 bg-primary-fixed/20 text-primary rounded-lg flex items-center justify-center mb-5 border border-primary-fixed/30 shadow-inner">
                    <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1">cell_tower</span>
                </div>
                <p class="metric-label mb-1">إجمالي المواقع</p>
                <h3 class="metric-value text-3xl">{{ stats.totalTowers }}</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">تغطية تشغيلية كاملة</p>
            </div>

            <div class="surface-card p-6 rounded-lg">
                <div class="w-12 h-12 bg-secondary-container/20 text-on-secondary-container rounded-lg flex items-center justify-center mb-5 border border-secondary-container/30 shadow-inner">
                    <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1">sensors</span>
                </div>
                <p class="metric-label mb-1">المواقع النشطة</p>
                <h3 class="metric-value text-3xl">{{ stats.activeTowers }}</h3>
                <div class="mt-3">
                    <span class="px-3 py-1 bg-secondary-container/10 text-on-secondary-container rounded text-[10px] font-black uppercase tracking-widest border border-secondary-container/20">
                        كفاءة {{ stats.totalTowers > 0 ? Math.round((stats.activeTowers / stats.totalTowers) * 100) : 0 }}%
                    </span>
                </div>
            </div>

            <div class="surface-card p-6 rounded-lg">
                <div class="w-12 h-12 bg-amber-50 text-amber-700 rounded-lg flex items-center justify-center mb-5 border border-amber-100 shadow-inner">
                    <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1">potted_plant</span>
                </div>
                <p class="metric-label mb-1">قيد الصيانة</p>
                <h3 class="metric-value text-3xl text-amber-700">{{ stats.maintenanceTowers }}</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">تحت التدقيق الفني</p>
            </div>

            <div class="surface-card p-6 rounded-lg">
                <div class="w-12 h-12 bg-surface-container-highest text-primary rounded-lg flex items-center justify-center mb-5 border border-outline-variant/20 shadow-inner">
                    <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1">router</span>
                </div>
                <p class="metric-label mb-1">الأجهزة الملحقة</p>
                <h3 class="metric-value text-3xl">{{ stats.totalRouters }}</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">عقد هاردوير مرتبطة</p>
            </div>
        </div>

        <!-- Matrix Filters Block -->
        <div class="surface-card p-6 mb-10 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">المنطقة الجغرافية</label>
                    <div class="relative">
                        <select v-model="filter.city" class="w-full h-12 px-4 bg-surface-container-low rounded-lg text-xs font-bold border-none appearance-none focus:ring-2 focus:ring-primary text-slate-700">
                            <option value="all">كافة المناطق</option>
                            <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                        </select>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[18px]">map</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">حالة التشغيل</label>
                    <div class="relative">
                        <select v-model="filter.status" class="w-full h-12 px-4 bg-surface-container-low rounded-lg text-xs font-bold border-none appearance-none focus:ring-2 focus:ring-primary text-slate-700">
                            <option value="all">كافة الحالات</option>
                            <option value="active">نشط / تشغيلي</option>
                            <option value="maintenance">تحت الصيانة</option>
                            <option value="inactive">متوقف</option>
                        </select>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[18px]">verified_user</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">تصنيف الموقع</label>
                    <div class="relative">
                        <select v-model="filter.type" class="w-full h-12 px-4 bg-surface-container-low rounded-lg text-xs font-bold border-none appearance-none focus:ring-2 focus:ring-primary text-slate-700">
                            <option value="all">كافة التصنيفات</option>
                            <option value="tower">برج رئيسي</option>
                            <option value="building">مبنى حضري</option>
                            <option value="cabinet">كابينة توزيع</option>
                            <option value="office">مقر إداري</option>
                        </select>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[18px]">layers</span>
                    </div>
                </div>

                <button 
                    @click="filter = { city: 'all', status: 'all', type: 'all' }"
                    class="h-12 bg-primary-fixed/20 hover:bg-primary-fixed/40 text-primary font-black text-[10px] uppercase tracking-widest rounded-lg transition-all active:scale-95 flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    تصفير المصفوفة
                </button>
            </div>
        </div>

        <!-- Site Registry Card Grid -->
        <div v-if="towers.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div 
                v-for="tower in towers" 
                :key="tower.id" 
                class="surface-card group hover:shadow-2xl hover:border-primary/20 transition-all duration-500 rounded-lg overflow-hidden flex flex-col"
            >
                <div class="p-8 pb-0">
                    <div class="flex items-start justify-between mb-8">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-lg bg-primary text-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg shadow-primary/20">
                                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">
                                    {{ getSiteTypeDetails(tower.type).icon }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg font-black text-primary leading-tight truncate max-w-[140px]">{{ tower.name }}</h3>
                                <p v-if="tower.city" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    {{ tower.city }}
                                </p>
                            </div>
                        </div>
                        <div class="p-1 px-3 rounded bg-surface-container-low border border-outline-variant/10 text-[9px] font-black uppercase tracking-widest text-slate-500">
                             ID: #{{ tower.id }}
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-lg border border-outline-variant/10 hover:bg-white transition-all">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">dns</span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 leading-none">الأجهزة النشطة</span>
                            </div>
                            <span class="font-headline font-black text-sm text-primary">{{ tower.routers_count || 0 }}</span>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            <div v-if="tower.has_solar" class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded border border-amber-100 text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">wb_sunny</span> طاقة شمسية
                            </div>
                            <div v-if="tower.battery_count > 0" class="px-3 py-1.5 bg-secondary-container/10 text-on-secondary-container rounded border border-secondary-container/20 text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">battery_std</span> {{ tower.battery_count }} بطارية
                            </div>
                            <div v-if="tower.has_generator" class="px-3 py-1.5 bg-error-container/10 text-error rounded border border-error-container/20 text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">bolt</span> مولد ديزل
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Foothold -->
                <div class="mt-auto px-8 py-6 bg-surface-container/20 border-t border-outline-variant/10 flex items-center gap-4">
                     <Link :href="route('network.towers.show', tower.id)" class="flex-1 text-center py-4 bg-primary text-white rounded-lg font-black text-[11px] uppercase tracking-widest hover:bg-primary-container transition-all active:scale-95 shadow-lg shadow-primary/10">
                        سجل الموقع بالكامل
                     </Link>
                     <div class="flex gap-2">
                        <Link :href="route('network.towers.edit', tower.id)" class="w-12 h-12 flex items-center justify-center bg-white border border-outline-variant/10 rounded-lg text-slate-400 hover:text-primary transition-all">
                            <span class="material-symbols-outlined text-[20px]">settings</span>
                        </Link>
                        <button @click="deleteTower(tower.id)" class="w-12 h-12 flex items-center justify-center bg-white border border-outline-variant/10 rounded-lg text-slate-400 hover:text-error transition-all">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                     </div>
                </div>
            </div>
        </div>

        <!-- Empty State Protocol -->
        <div v-else class="surface-card py-32 text-center rounded-lg">
            <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center text-slate-200 mx-auto mb-8">
                <span class="material-symbols-outlined text-[40px]">location_off</span>
            </div>
            <h2 class="text-2xl font-black text-primary mb-3">سجل أسطول المواقع فارغ</h2>
            <p class="text-slate-400 font-bold max-w-sm mx-auto mb-10 text-xs leading-relaxed uppercase tracking-widest">
                لم يتم رصد أي مواقع بنية تحتية نشطة في الحيز الجغرافي المحدد حالياً.
            </p>
            <Link :href="route('network.towers.index')" class="px-10 py-5 bg-primary text-white rounded-lg font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-primary-container transition-all">
                إعادة ضبط المصفوفة
            </Link>
        </div>
    </InstitutionalLayout>
</template>
