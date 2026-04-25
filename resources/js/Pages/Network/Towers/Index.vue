<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    TowerControl as TowerIcon, 
    MapPin, 
    Plus, 
    Search, 
    Settings2, 
    Wifi, 
    Zap, 
    Activity, 
    MoreVertical,
    Trash2,
    Eye,
    Edit3,
    CheckCircle2,
    AlertTriangle,
    XCircle,
    Building2,
    Inbox,
    Navigation,
    Router as RouterIcon,
    BatteryMedium,
    Sun,
    Power
} from 'lucide-vue-next';

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
        'tower': { label: 'برج اتصالات متخصص', icon: TowerIcon, color: 'text-vendor' },
        'building': { label: 'موقع حضري (Building)', icon: Building2, color: 'text-indigo-500' },
        'cabinet': { label: 'خزانة توزيع بروتوكولية', icon: Inbox, color: 'text-cyan-500' },
        'office': { label: 'مركز عمليات (NOC)', icon: Navigation, color: 'text-slate-500' },
        'pole': { label: 'سارية ربط طرفية', icon: Navigation, color: 'text-slate-400' },
    };
    return map[type] || map['pole'];
};

const getStatusDetails = (status) => {
    switch (status) {
        case 'active': return { label: 'النبض مستقر (Live)', class: 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20', icon: CheckCircle2 };
        case 'maintenance': return { label: 'تدقيق فني (Maint)', class: 'bg-amber-500/10 text-amber-600 border-amber-500/20', icon: AlertTriangle };
        case 'inactive': return { label: 'منقطع سيادياً', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20', icon: XCircle };
        default: return { label: 'غير معرف', class: 'bg-slate-500/10 text-slate-500 border-slate-500/20', icon: Activity };
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
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-vendor rounded-full"></span>
                        <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Geospatial Assets Matrix</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">إدارة <span class="text-vendor">المواقع</span> والأبراج</h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">حوكمة الأصول الميدانية وتتبع التغطية الطيفية</p>
                </div>
                
                <Link :href="route('network.towers.create')" class="btn-radiant btn-vendor px-8 py-4 text-xs font-black uppercase tracking-[0.2em] flex items-center gap-3">
                    <Plus class="w-5 h-5 stroke-[3]" />
                    تثبيت موقع جديد
                </Link>
            </div>

            <!-- Telemetry Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Total Sites -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-vendor/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-vendor/10 rounded-2xl flex items-center justify-center text-vendor shadow-sm">
                            <TowerIcon class="w-6 h-6 stroke-[2.5]" />
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">إجمالي المواقع</p>
                    <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter font-inter">{{ stats.totalTowers }}</h3>
                </div>

                <!-- Online Status -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                            <Wifi class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="px-2 py-1 rounded-md bg-emerald-500 text-white font-black text-[9px] uppercase tracking-widest animate-pulse">Live</div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">النبض التشغيلي</p>
                    <h3 class="text-4xl font-black text-emerald-600 italic tracking-tighter font-inter">{{ stats.activeTowers }} <span class="text-sm font-bold text-slate-300">/ {{ stats.totalTowers }}</span></h3>
                </div>

                <!-- Maintenance Queue -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-amber-500/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm">
                            <Settings2 class="w-6 h-6 stroke-[2.5]" />
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">تحت المعاينة</p>
                    <h3 class="text-4xl font-black text-amber-600 italic tracking-tighter font-inter">{{ stats.maintenanceTowers }}</h3>
                </div>

                <!-- Router Nodes -->
                <div class="glass-card p-8 bg-slate-900 text-white group relative overflow-hidden border-none">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-vendor/20 rounded-full blur-2xl group-hover:scale-150 transition-transform opacity-30"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vendor shadow-sm">
                            <RouterIcon class="w-6 h-6 stroke-[2.5]" />
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2 font-inter">عقد المعالجة (Nodes)</p>
                    <h3 class="text-4xl font-black text-white italic tracking-tighter font-inter">{{ stats.totalRouters }}</h3>
                </div>

            </div>

            <!-- Strategic Precision Filters -->
            <div class="glass-card p-8 bg-white/60 flex flex-col lg:flex-row items-center gap-8 border-white/60">
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                            <MapPin class="w-3 h-3" />
                            النطاق الجغرافي
                        </label>
                        <select v-model="filter.city" class="w-full bg-white/50 border-white/60 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-4 focus:ring-vendor/5 transition-all appearance-none cursor-pointer">
                            <option value="all">كافة المناطق السيادية</option>
                            <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                            <Activity class="w-3 h-3" />
                            بروتوكول الحالة
                        </label>
                        <select v-model="filter.status" class="w-full bg-white/50 border-white/60 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-4 focus:ring-vendor/5 transition-all appearance-none cursor-pointer">
                            <option value="all">كافة الحالات التشغيلية</option>
                            <option value="active">مستوى البث الحي (Live)</option>
                            <option value="maintenance">إجراءات الصيانة الوقائية</option>
                            <option value="inactive">خارج النطاق الطيفي</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                            <Building2 class="w-3 h-3" />
                            تصنيف المنشأة
                        </label>
                        <select v-model="filter.type" class="w-full bg-white/50 border-white/60 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-4 focus:ring-vendor/5 transition-all appearance-none cursor-pointer">
                            <option value="all">كافة أنواع المواقع</option>
                            <option value="tower">برج اتصالات سيادي</option>
                            <option value="building">موقع ضمن كتلة عمرانية</option>
                            <option value="cabinet">خزانة بروتوكولية طرفية</option>
                        </select>
                    </div>
                </div>

                <button @click="filter = { city: 'all', status: 'all', type: 'all' }" class="h-14 px-8 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all flex items-center gap-3 shrink-0">
                    <Zap class="w-4 h-4" />
                    تصفير الفلاتر
                </button>
            </div>

            <!-- Assets Ledger Matrix -->
            <div class="glass-card overflow-hidden bg-white/40 border-white/60">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                                <th class="px-10 py-8">هوية الموقع (Asset Identity)</th>
                                <th class="px-8 py-8">التوصيف الهيكلي</th>
                                <th class="px-8 py-8 text-center">عقد المعالجة</th>
                                <th class="px-8 py-8 text-center">مصفوفة الطاقة</th>
                                <th class="px-8 py-8 text-center">النبض الراهن</th>
                                <th class="px-10 py-8"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/20">
                            <tr v-for="tower in towers" :key="tower.id" class="group hover:bg-white/60 transition-all duration-500">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-5 justify-end">
                                        <div class="text-right">
                                            <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase">{{ tower.name }}</h4>
                                            <div class="flex items-center gap-2 mt-1 opacity-40 justify-end">
                                                <span class="text-[9px] font-black uppercase tracking-widest font-inter">{{ tower.city }} <span class="text-vendor">/</span> {{ tower.district || 'Unmarked Zone' }}</span>
                                                <MapPin class="w-3 h-3" />
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-vendor flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shrink-0 border border-white/10">
                                            <component :is="getSiteTypeDetails(tower.type).icon" class="w-7 h-7 stroke-[2]" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <span class="inline-flex items-center gap-3 px-5 py-2.5 bg-white/50 border border-white/60 rounded-xl text-[10px] font-black text-slate-500 uppercase tracking-widest group-hover:border-vendor transition-all">
                                        <component :is="getSiteTypeDetails(tower.type).icon" class="w-4 h-4 text-vendor" />
                                        {{ getSiteTypeDetails(tower.type).label }}
                                    </span>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-2xl font-black text-slate-900 font-inter group-hover:scale-125 transition-transform">{{ tower.routers_count || 0 }}</span>
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1 font-inter">Nodes</span>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="flex items-center justify-center gap-3">
                                        <div v-if="tower.has_solar" class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center border border-amber-100 shadow-inner" title="Solar Array">
                                            <Sun class="w-5 h-5" />
                                        </div>
                                        <div v-if="tower.battery_count > 0" class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-inner" title="Battery Storage">
                                            <BatteryMedium class="w-5 h-5" />
                                        </div>
                                        <div v-if="tower.has_generator" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shadow-inner" title="Power Gen">
                                            <Power class="w-5 h-5" />
                                        </div>
                                        <div v-if="!tower.has_solar && !tower.has_generator && tower.battery_count === 0" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-200 flex items-center justify-center border border-slate-100 opacity-20">
                                            <Zap class="w-5 h-5" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all shadow-sm group-hover:translate-y-[-2px]',
                                        getStatusDetails(tower.status || 'active').class
                                    ]">
                                        <component :is="getStatusDetails(tower.status || 'active').icon" class="w-3.5 h-3.5" />
                                        {{ getStatusDetails(tower.status || 'active').label }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link :href="route('network.towers.show', tower.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-vendor hover:scale-110 active:scale-90 transition-all">
                                            <Eye class="w-5 h-5" />
                                         </Link>
                                         <Link :href="route('network.towers.edit', tower.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-500 hover:scale-110 active:scale-90 transition-all">
                                            <Edit3 class="w-5 h-5" />
                                         </Link>
                                         <button @click="deleteTower(tower.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:scale-110 active:scale-90 transition-all">
                                            <Trash2 class="w-5 h-5" />
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="towers.length === 0" class="py-40 flex flex-col items-center gap-8 text-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-slate-900 text-vendor flex items-center justify-center border-8 border-white/20 shadow-2xl relative group">
                        <div class="absolute inset-0 bg-vendor/20 opacity-20 blur-2xl animate-pulse"></div>
                        <TowerIcon class="w-12 h-12 relative z-10" />
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 italic tracking-tighter uppercase">مجموع الخلية صفر (Null Domain)</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mt-3 opacity-70 italic font-inter">No field assets found in the current matrix dimension.</p>
                    </div>
                    <Link :href="route('network.towers.create')" class="btn-radiant btn-vendor px-12 py-5 text-xs font-black uppercase tracking-[0.3em]">
                        بدء بروتوكول التأسيس
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

