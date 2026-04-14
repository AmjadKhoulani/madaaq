<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { pickBy, throttle } from 'lodash';

const props = defineProps({
    invoices: Object,
    stats: Object,
    filters: Object
});

const filters = ref({
    status: props.filters.status || 'all'
});

watch(filters, throttle(() => {
    router.get(route('accounting.invoices.index'), pickBy(filters.value), {
        preserveState: true,
        replace: true
    });
}, 300), { deep: true });

const getStatusDetails = (status) => {
    switch (status) {
        case 'paid': return { label: 'تم التحصيل', color: 'bg-secondary-container/20 text-on-secondary-container border-secondary-container/30', icon: 'check_circle' };
        case 'unpaid': return { label: 'معلق', color: 'bg-amber-50 text-amber-700 border-amber-100', icon: 'schedule' };
        case 'overdue': return { label: 'متأخر', color: 'bg-error-container/10 text-error border-error-container/20', icon: 'warning' };
        default: return { label: status, color: 'bg-slate-50 text-slate-600 border-slate-100', icon: 'description' };
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR',
    }).format(value);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
    <InstitutionalLayout title="السجل المالي">
        <Head title="الفواتير والتحصيل" />

        <!-- Strategic Fiscal Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="surface-card p-6 rounded-lg bg-primary text-white shadow-xl shadow-primary/20">
                <p class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1">إجمالي الإيرادات المحصلة</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black font-headline tracking-tighter">{{ stats.total_revenue.toLocaleString() }}</h3>
                    <span class="text-xs font-bold text-white/50">ر.س</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-white/70 bg-white/10 w-fit px-2 py-1 rounded">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    نمو مستقر
                </div>
            </div>

            <div class="surface-card p-6 rounded-lg">
                <p class="metric-label mb-1">المبالغ المستحقة (معلقة)</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black font-headline text-amber-600 tracking-tighter">{{ stats.unpaid_amount.toLocaleString() }}</h3>
                    <span class="text-xs font-bold text-slate-400">ر.س</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400 mt-2">بانتظار التحصيل</p>
            </div>

            <div class="surface-card p-6 rounded-lg">
                <p class="metric-label mb-1">العمليات الناجحة</p>
                <h3 class="metric-value text-3xl tracking-tighter">{{ stats.paid_count }}</h3>
                <p class="text-[10px] font-bold text-secondary mt-2">تسوية مكتملة</p>
            </div>

            <div class="surface-card p-6 rounded-lg">
                <p class="metric-label mb-1">الفواتير غير المدفوعة</p>
                <h3 class="text-3xl font-black font-headline text-error tracking-tighter">{{ stats.unpaid_count }}</h3>
                <p class="text-[10px] font-bold text-error/60 mt-2">تنبيه مالي</p>
            </div>
        </div>

        <!-- Analytical Header & Filters -->
        <div class="flex flex-col lg:flex-row justify-between items-center gap-8 mb-8">
            <div class="flex flex-col md:flex-row items-center gap-4 w-full lg:w-auto">
                <div class="relative flex-1 md:w-80">
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                    <input type="text" placeholder="ابحث برقم الفاتورة أو اسم المشترك..." class="w-full h-12 bg-surface-container-low border-none rounded-lg pr-12 pl-4 text-sm focus:ring-2 focus:ring-primary transition-all">
                </div>
                <div class="flex bg-surface-container-low p-1 rounded-lg border border-outline-variant/10">
                     <button @click="filters.status = 'all'" class="px-6 py-2 rounded-md text-[11px] font-black uppercase tracking-wider transition-all" :class="filters.status === 'all' ? 'bg-white text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700'">كافة السجلات</button>
                     <button @click="filters.status = 'paid'" class="px-6 py-2 rounded-md text-[11px] font-black uppercase tracking-wider transition-all" :class="filters.status === 'paid' ? 'bg-white text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700'">المحصلة</button>
                     <button @click="filters.status = 'unpaid'" class="px-6 py-2 rounded-md text-[11px] font-black uppercase tracking-wider transition-all" :class="filters.status === 'unpaid' ? 'bg-white text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700'">المعلقة</button>
                </div>
            </div>
            <Link 
                :href="route('accounting.invoices.create')" 
                class="inline-flex items-center gap-3 px-8 py-3.5 bg-primary text-white rounded-lg font-bold shadow-xl shadow-primary/20 hover:bg-primary-container transition-all active:scale-95 whitespace-nowrap"
            >
                <span class="material-symbols-outlined text-[20px]">add_card</span>
                <span class="text-sm">إنشاء فاتورة جديدة</span>
            </Link>
        </div>

        <!-- Ledger Table Layer -->
        <div class="surface-card rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-surface-container/30 border-b border-outline-variant/10">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">بيانات الفاتورة</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">اسم المشترك</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none text-center">المبلغ المستحق</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none text-center">تاريخ الاستحقاق</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none text-center">حالة السداد</th>
                            <th class="px-8 py-5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/5">
                        <tr v-for="inv in invoices.data" :key="inv.id" class="group hover:bg-surface-container-low transition-all">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-11 h-11 rounded-lg bg-surface-container flex items-center justify-center text-primary shrink-0 group-hover:scale-110 transition-transform shadow-inner border border-outline-variant/10">
                                        <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1">description</span>
                                    </div>
                                    <div>
                                        <p class="font-headline font-black text-sm tracking-tight text-primary leading-tight uppercase">{{ inv.invoice_number }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">صدرت في: {{ formatDate(inv.created_at) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-[13px] font-black text-primary leading-tight">{{ inv.client?.name || 'مشترك عام' }}</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">{{ inv.client?.username || 'SYSTEM_INTERNAL' }}</p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <p class="text-base font-headline font-extrabold tracking-tight text-primary">{{ inv.amount?.toLocaleString() }} <span class="text-[10px] font-bold text-slate-400">ر.س</span></p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex items-center justify-center gap-2 text-slate-500">
                                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                    <span class="text-[11px] font-headline font-black tracking-tight">{{ formatDate(inv.due_date) }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="inline-flex items-center justify-center gap-2 px-4 py-1.5 rounded text-[10px] font-black uppercase tracking-widest border"
                                     :class="getStatusDetails(inv.status).color">
                                    <span class="material-symbols-outlined text-[16px]">{{ getStatusDetails(inv.status).icon }}</span>
                                    {{ getStatusDetails(inv.status).label }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-left">
                                <div class="flex items-center justify-end gap-3">
                                     <Link 
                                        :href="route('accounting.invoices.show', inv.id)" 
                                        class="w-10 h-10 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all group-hover:translate-x-[-4px]"
                                     >
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                     </Link>
                                     <Link 
                                        :href="route('accounting.invoices.edit', inv.id)" 
                                        class="w-10 h-10 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-amber-600 hover:border-amber-600 transition-all group-hover:translate-x-[-4px]"
                                     >
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                     </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Monolith Empty State -->
            <div v-if="invoices.data.length === 0" class="py-24 flex flex-col items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center text-slate-200">
                    <span class="material-symbols-outlined text-[40px]">history_edu</span>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-black text-primary">لا توجد سجلات مالية</h3>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-2">لم يتم إصدار أي فواتير ضمن معايير البحث الحالية</p>
                </div>
            </div>

            <!-- Precision Pagination -->
            <div v-if="invoices.links.length > 3" class="px-8 py-6 border-t border-outline-variant/10 flex items-center justify-center gap-2 bg-surface-container/10">
                <Link 
                    v-for="(link, k) in invoices.links" 
                    :key="k"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="h-10 px-4 flex items-center justify-center rounded-lg text-[11px] font-black transition-all font-headline"
                    :class="[
                        link.active ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'hover:bg-primary-fixed/20 text-slate-500',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                />
            </div>
        </div>
    </InstitutionalLayout>
</template>
