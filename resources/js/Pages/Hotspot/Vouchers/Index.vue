<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { pickBy, throttle } from 'lodash';

const props = defineProps({
    vouchers: Object,
    packages: Array,
    stats: Object,
    filters: Object
});

const selectedIds = ref([]);
const showFilters = ref(false);

const form = ref({
    search: props.filters.search || '',
    status: props.filters.status || '',
    package_id: props.filters.package_id || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || ''
});

watch(form, throttle(() => {
    router.get(route('hotspot.vouchers.index'), pickBy(form.value), {
        preserveState: true,
        replace: true
    });
}, 300), { deep: true });

const toggleSelectAll = (e) => {
    if (e.target.checked) {
        selectedIds.value = props.vouchers.data.map(v => v.id);
    } else {
        selectedIds.value = [];
    }
};

const handleBulkAction = (action) => {
    if (!selectedIds.value.length) return;
    
    let actionLabel = action === 'delete' ? 'حذف نهائي' : 'تعطيل';
    if (confirm(`هل أنت متأكد من تنفيذ إجراء (${actionLabel}) على ${selectedIds.value.length} قسيمة مختارة؟`)) {
        router.post(route('hotspot.vouchers.bulk_action'), {
            ids: selectedIds.value,
            action: action
        }, {
            onSuccess: () => selectedIds.value = []
        });
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'active': return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        case 'inactive': return 'bg-rose-50 text-rose-600 border-rose-100';
        default: return 'bg-gray-50 text-gray-600 border-gray-100';
    }
};

</script>

<template>
    <InstitutionalLayout title="سجل القسائم">
        <Head title="إدارة قسائم الهوت سبوت - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="surface-card p-10 bg-slate-950 text-white relative overflow-hidden group rounded-xl shadow-2xl">
                    <div class="absolute -top-16 -right-16 w-48 h-48 bg-primary/10 rounded-full blur-3xl group-hover:bg-primary/20 transition-all"></div>
                    <div class="relative z-10 flex flex-col items-start text-right">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-3">إجمالي القسائم المُصدرة</p>
                        <h3 class="text-5xl font-black tracking-tighter font-headline">{{ stats.total }}</h3>
                        <p class="text-[10px] font-bold text-emerald-400 mt-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">analytics</span>
                            إجمالي القسائم المنشأة منذ تأسيس النظام
                        </p>
                    </div>
                </div>
                <div class="surface-card p-10 bg-white group rounded-xl shadow-sm border border-outline-variant/10">
                    <div class="flex flex-col items-start text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">القسائم النشطة حالياً</p>
                        <h3 class="text-5xl font-black tracking-tighter text-secondary font-headline">{{ stats.active }}</h3>
                        <p class="text-[10px] font-bold text-slate-500 mt-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-secondary rounded-full animate-pulse"></span>
                            اشتراكات فعالة قيد الاستخدام الفعلي
                        </p>
                    </div>
                </div>
                <div class="surface-card p-10 bg-white group rounded-xl shadow-sm border border-outline-variant/10">
                    <div class="flex flex-col items-start text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">القسائم المنتهية / المعطلة</p>
                        <h3 class="text-5xl font-black tracking-tighter text-error/70 font-headline">{{ stats.inactive }}</h3>
                        <p class="text-[10px] font-bold text-slate-500 mt-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-error/30 text-[18px]">cancel</span>
                            سجلات خارج الخدمة أو منتهية الصلاحية
                        </p>
                    </div>
                </div>
            </div>

            <!-- Management Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-10">
                <div class="flex items-center gap-4 flex-1">
                    <div class="relative group flex-1 max-w-md">
                        <input v-model="form.search" type="text" placeholder="بحث سريع عن رمز القسيمة..." class="form-input-monolith pr-12 h-14 w-full">
                        <span class="material-symbols-outlined absolute right-4 top-4 text-slate-400">search</span>
                    </div>
                    <button 
                        @click="showFilters = !showFilters"
                        class="w-14 h-14 surface-card flex items-center justify-center text-slate-400 hover:text-primary transition-all rounded-xl border border-outline-variant/10"
                        :class="showFilters ? 'bg-primary text-white border-primary' : ''"
                    >
                        <span class="material-symbols-outlined text-[24px]">filter_list</span>
                    </button>
                    <div v-if="selectedIds.length > 0" class="flex items-center gap-3 animate-in fade-in slide-in-from-right-4">
                        <div class="h-10 w-px bg-outline-variant/10 mx-2"></div>
                        <button @click="handleBulkAction('disable')" class="px-6 py-3 bg-secondary-container/10 text-secondary rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-secondary hover:text-white transition-all">تعطيل ({{ selectedIds.length }})</button>
                        <button @click="handleBulkAction('delete')" class="px-6 py-3 bg-error/5 text-error rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-error hover:text-white transition-all">حذف نهائي</button>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                     <Link 
                        :href="route('hotspot.vouchers.reprint_last')" 
                        class="px-8 h-14 surface-card flex items-center justify-center gap-3 text-[11px] font-black uppercase tracking-widest text-slate-600 hover:text-primary transition-all rounded-xl border border-outline-variant/10"
                     >
                        <span class="material-symbols-outlined text-[20px]">print</span>
                        طباعة آخر دفعة
                     </Link>
                     <Link 
                        :href="route('hotspot.vouchers.create')" 
                        class="px-10 h-14 bg-primary text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-4"
                     >
                        <span class="material-symbols-outlined text-[24px]">add_box</span>
                        توليد قسائم جديدة
                     </Link>
                </div>
            </div>

            <!-- Filter Panel -->
            <div v-if="showFilters" class="surface-card p-10 mb-10 bg-surface-container-low/50 border border-outline-variant/10 rounded-xl grid grid-cols-1 md:grid-cols-4 gap-8 animate-in slide-in-from-top duration-300">
                <div class="space-y-4 text-right">
                    <label class="text-[10px] font-black text-primary uppercase tracking-widest mr-2">حالة القسيمة</label>
                    <select v-model="form.status" class="form-input-monolith h-12 text-sm bg-white">
                        <option value="">جميع الحالات التشغيلية</option>
                        <option value="active">القسائم النشطة</option>
                        <option value="inactive">القسائم المنتهية</option>
                    </select>
                </div>
                <div class="space-y-4 text-right">
                    <label class="text-[10px] font-black text-primary uppercase tracking-widest mr-2">باقة الاشتراك</label>
                    <select v-model="form.package_id" class="form-input-monolith h-12 text-sm bg-white">
                        <option value="">جميع الباقات المعرفة</option>
                        <option v-for="p in packages" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                </div>
                <div class="space-y-4 text-right">
                    <label class="text-[10px] font-black text-primary uppercase tracking-widest mr-2">من تاريخ الإصدار</label>
                    <input v-model="form.date_from" type="date" class="form-input-monolith h-12 text-sm bg-white font-headline">
                </div>
                <div class="space-y-4 text-right">
                    <label class="text-[10px] font-black text-primary uppercase tracking-widest mr-2">إلى تاريخ الإصدار</label>
                    <input v-model="form.date_to" type="date" class="form-input-monolith h-12 text-sm bg-white font-headline">
                </div>
            </div>

            <!-- Vouchers Registry -->
            <div class="surface-card rounded-xl overflow-hidden shadow-sm border border-outline-variant/10">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low/50 border-b border-outline-variant/10">
                                <th class="px-8 py-6 w-12 text-center">
                                    <input type="checkbox" @change="toggleSelectAll" class="w-5 h-5 rounded-lg border-outline-variant/20 text-primary focus:ring-primary/20 transition-all">
                                </th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">بيانات القسيمة الائتمانية</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">عقدة التزويد (Gateway)</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">فئة السرعة والتعرفة</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">الحالة البرمجية</th>
                                <th class="px-8 py-6"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="v in vouchers.data" :key="v.id" class="group hover:bg-surface-container-low/50 transition-all" :class="selectedIds.includes(v.id) ? 'bg-primary/5' : ''">
                                <td class="px-8 py-6 text-center">
                                    <input type="checkbox" v-model="selectedIds" :value="v.id" class="w-5 h-5 rounded-lg border-outline-variant/20 text-primary focus:ring-primary/20 transition-all">
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-6">
                                        <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20 group-hover:rotate-6 transition-transform border border-white/10 shrink-0">
                                            <span class="material-symbols-outlined text-[28px]">confirmation_number</span>
                                        </div>
                                        <div class="text-right min-w-0">
                                            <p class="font-black text-lg tracking-[0.1em] font-headline uppercase text-primary leading-none">{{ v.username }}</p>
                                            <div class="flex items-center gap-3 mt-2 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">
                                                <span>إصدار: {{ new Date(v.created_at).toLocaleDateString('ar-SY') }}</span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span class="font-headline">{{ new Date(v.created_at).toLocaleTimeString('ar-SY', {hour: '2-digit', minute:'2-digit'}) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2 justify-end mb-1">
                                        <span class="text-[11px] font-black text-primary uppercase whitespace-nowrap">{{ v.mikrotik_server?.name || 'خادم محلي' }}</span>
                                        <span class="material-symbols-outlined text-[16px] text-secondary">dns</span>
                                    </div>
                                    <p class="text-[10px] font-headline font-black text-slate-400 opacity-60 flex justify-end tracking-tight">{{ v.mikrotik_server?.ip || '0.0.0.0' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="px-5 py-3 bg-surface-container-low border border-outline-variant/10 rounded-2xl flex flex-col items-center shadow-inner">
                                         <p class="text-[11px] font-black tracking-tight text-primary uppercase leading-none mb-1.5">{{ v.package?.name || 'باقة افتراضية' }}</p>
                                         <p class="text-[10px] font-black text-secondary font-headline">{{ v.package?.price || '0' }} <span class="text-[8px] opacity-60 mr-0.5">S.P</span></p>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div 
                                        class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest border shadow-sm"
                                        :class="getStatusClass(v.status)"
                                    >
                                        <span v-if="v.status === 'active'" class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                                        {{ v.status === 'active' ? 'قيد الاستخدام' : 'منتهي الصلاحية' }}
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <button class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary/30 transition-all">
                                            <span class="material-symbols-outlined text-[22px]">visibility</span>
                                         </button>
                                         <button class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-secondary hover:border-secondary/30 transition-all">
                                            <span class="material-symbols-outlined text-[22px]">print</span>
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="vouchers.data.length === 0" class="py-40 flex flex-col items-center gap-10 text-center animate-in fade-in duration-700">
                    <div class="w-28 h-28 rounded-full bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-inner">
                        <span class="material-symbols-outlined text-[56px]" style="font-variation-settings: 'wght' 100">confirmation_number</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-primary mb-3">لا توجد قسائم مسجلة في هذا النطاق</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mx-auto leading-relaxed">لم يتم رصد أي سجلات للقسائم حالياً. يرجى تعديل معايير البحث أو توليد دفعة جديدة.</p>
                    </div>
                </div>

                <!-- Professional Pagination -->
                <div class="px-8 py-8 border-t border-outline-variant/10 flex items-center justify-center gap-3 bg-surface-container/5">
                    <Link 
                        v-for="(link, k) in vouchers.links" 
                        :key="k"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="h-11 px-6 flex items-center justify-center rounded-xl text-[11px] font-black transition-all font-headline"
                        :class="[
                            link.active ? 'bg-primary text-white shadow-xl shadow-primary/20 scale-110 mx-2' : 'hover:bg-primary/5 text-slate-500 border border-transparent hover:border-outline-variant/10',
                            !link.url ? 'opacity-30 cursor-not-allowed' : ''
                        ]"
                    />
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

