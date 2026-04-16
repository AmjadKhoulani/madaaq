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
    status: props.filters.status || 'all',
    search: props.filters.search || ''
});

watch(filters, throttle(() => {
    router.get(route('accounting.invoices.index'), pickBy(filters.value), {
        preserveState: true,
        replace: true
    });
}, 300), { deep: true });

const getStatusDetails = (status) => {
    switch (status) {
        case 'paid': return { label: 'تم التحصيل بنجاح', color: 'text-emerald-500 border-emerald-500/20 bg-emerald-500/5', icon: 'verified_user' };
        case 'unpaid': return { label: 'معلق - بانتظار السداد', color: 'text-amber-600 border-amber-600/20 bg-amber-600/5', icon: 'hourglass_top' };
        case 'overdue': return { label: 'متأخرات ذمة مالية', color: 'text-rose-500 border-rose-500/20 bg-rose-500/5', icon: 'emergency_home' };
        default: return { label: status, color: 'text-slate-500 border-slate-500/20 bg-slate-500/5', icon: 'description' };
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ar-SY', { year: 'numeric', month: 'long', day: 'numeric' });
};

</script>

<template>
    <InstitutionalLayout title="السجل المالي العام">
        <Head title="الفواتير والتحصيل المركزي - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">السجل المحاسبي العام (Invoices Ledger)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">حوكمة الفواتير الصادرة، إدارة عمليات التحصيل، وتدقيق المستحقات المالية</p>
                        <span class="material-symbols-outlined text-[24px] text-primary">account_balance_wallet</span>
                    </div>
                </div>
                <Link 
                    :href="route('accounting.invoices.create')" 
                    class="px-12 py-5 bg-primary text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-4 border border-white/10"
                >
                    <span class="material-symbols-outlined text-[24px]">add_card</span> إصدار مطالعة مالية
                </Link>
            </div>

            <!-- Strategic Fiscal Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Total Revenue -->
                <div class="surface-card p-10 rounded-2xl bg-slate-950 text-white shadow-2xl relative overflow-hidden group border border-white/5">
                    <div class="absolute -top-16 -left-16 w-64 h-64 bg-primary/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center border border-white/10">
                                <span class="material-symbols-outlined text-emerald-500 text-[24px]">payments</span>
                             </div>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">إجمالي التحصيلات (Revenue)</p>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <h3 class="text-4xl font-black font-headline tracking-tighter">{{ stats.total_revenue.toLocaleString() }}</h3>
                            <span class="text-[10px] font-black text-white/20 uppercase tracking-widest leading-none">ل.س</span>
                        </div>
                    </div>
                </div>

                <!-- Unpaid Amount -->
                <div class="surface-card p-10 rounded-2xl border border-outline-variant/5 shadow-sm bg-white overflow-hidden relative">
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-12 h-12 rounded-xl bg-amber-500/5 flex items-center justify-center border border-amber-500/10">
                                <span class="material-symbols-outlined text-amber-600 text-[24px]">history_edu</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">ذمم مالية معلقة (Pending)</p>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <h3 class="text-4xl font-black font-headline text-amber-600 tracking-tighter leading-none">{{ stats.unpaid_amount.toLocaleString() }}</h3>
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none">ل.س</span>
                        </div>
                    </div>
                </div>

                <!-- Success Count -->
                <div class="surface-card p-10 rounded-2xl border border-outline-variant/5 shadow-sm bg-white">
                    <div class="flex flex-col gap-6">
                         <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center border border-primary/10">
                                <span class="material-symbols-outlined text-primary text-[24px]">done_all</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">عمليات تمت تسويتها</p>
                        </div>
                        <div class="flex items-baseline gap-4">
                            <h3 class="text-4xl font-black font-headline text-primary tracking-tighter leading-none">{{ stats.paid_count }}</h3>
                            <p class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.2em] leading-none mb-1">تسوية مكتملة</p>
                        </div>
                    </div>
                </div>

                <!-- Warning Count -->
                <div class="surface-card p-10 rounded-2xl border border-outline-variant/5 shadow-sm bg-white border-b-8 border-rose-500">
                    <div class="flex flex-col gap-6">
                         <div class="flex items-center justify-between flex-row-reverse text-right">
                             <div class="w-12 h-12 rounded-xl bg-rose-500/5 flex items-center justify-center border border-rose-500/10">
                                <span class="material-symbols-outlined text-rose-500 text-[24px]">notification_important</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">تنبيهات الذمة المالية</p>
                        </div>
                        <div class="flex items-baseline gap-4">
                            <h3 class="text-4xl font-black font-headline text-rose-500 tracking-tighter leading-none">{{ stats.unpaid_count }}</h3>
                            <p class="text-[10px] font-black text-rose-500/60 uppercase tracking-[0.2em] leading-none mb-1">مطالبات حالية</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytical Filter Suite -->
            <div class="surface-card p-10 rounded-3xl mb-12 border border-outline-variant/5 shadow-sm bg-white">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-10 flex-row-reverse">
                    <div class="flex flex-col md:flex-row items-center gap-8 w-full lg:w-auto flex-row-reverse">
                        <div class="relative flex-1 md:w-[450px] group">
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[24px]">search</span>
                            <input v-model="filters.search" type="text" placeholder="ابحث برقم السند المالي، اسم المشترك، أو الهاتف..." class="form-input-monolith h-16 pr-14 pl-6 text-sm font-bold shadow-inner">
                        </div>
                        <div class="flex bg-surface-container-low p-2 rounded-2xl border border-outline-variant/10 flex-row-reverse shadow-inner">
                             <button @click="filters.status = 'all'" 
                                class="px-10 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" 
                                :class="filters.status === 'all' ? 'bg-white text-primary shadow-xl' : 'text-slate-400 hover:text-primary'">كافة القيود</button>
                             <button @click="filters.status = 'paid'" 
                                class="px-10 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" 
                                :class="filters.status === 'paid' ? 'bg-white text-primary shadow-xl' : 'text-slate-400 hover:text-primary'">تم التحصيل</button>
                             <button @click="filters.status = 'unpaid'" 
                                class="px-10 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" 
                                :class="filters.status === 'unpaid' ? 'bg-white text-primary shadow-xl' : 'text-slate-400 hover:text-primary'">قيد الانتظار</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Institutional Ledger Table -->
            <div class="surface-card rounded-3xl overflow-hidden shadow-2xl border border-outline-variant/5 mb-10 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5">
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-white/40">بيانات السند المالي (Identity)</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-white/40">الطرف المستفيد</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">القيمة الاستحقاقية</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40 border-r border-white/5">أفق المزامنة</th>
                                <th class="px-10 py-6 text-[11px] font-black uppercase tracking-[0.2em] leading-none text-center text-white/40">حالة التسوية</th>
                                <th class="px-10 py-6 w-32"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="inv in invoices.data" :key="inv.id" class="group hover:bg-surface-container-low transition-all duration-300">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-6 justify-end">
                                        <div class="text-right">
                                            <p class="font-headline font-black text-lg tracking-tight text-primary leading-tight group-hover:translate-x-1 transition-transform uppercase">{{ inv.invoice_number }}</p>
                                            <div class="flex items-center gap-2 justify-end mt-2 opacity-50">
                                                <span class="text-[10px] font-black uppercase tracking-widest leading-none">{{ formatDate(inv.created_at) }}</span>
                                                <span class="material-symbols-outlined text-[14px]">edit_note</span>
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-outline-variant/10 flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white shadow-inner transition-all">
                                            <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">description</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="text-right">
                                        <p class="text-[15px] font-black text-primary leading-tight mb-2">{{ inv.client?.name || 'مشترك عام' }}</p>
                                        <div class="flex items-center gap-3 justify-end opacity-40">
                                            <span class="text-[11px] font-headline font-black tracking-widest uppercase">{{ inv.client?.username || 'GUEST_ARCH' }}</span>
                                            <span class="material-symbols-outlined text-[16px]">id_card</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center text-right">
                                    <div class="inline-flex flex-col items-center gap-2">
                                        <p class="text-xl font-headline font-black tracking-tighter text-primary leading-none">{{ inv.amount?.toLocaleString() }}</p>
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none">ليرة سورية</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-[11px] font-headline font-black tracking-[0.1em] text-primary bg-surface-container-low px-4 py-1.5 rounded-lg">{{ formatDate(inv.due_date) }}</span>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">موعد انتهاء الصلاحية</p>
                                    </div>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    <div class="inline-flex items-center justify-center gap-3 px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border-2 transition-all shadow-sm group-hover:scale-105"
                                         :class="getStatusDetails(inv.status).color">
                                        <span class="material-symbols-outlined text-[18px]">{{ getStatusDetails(inv.status).icon }}</span>
                                        {{ getStatusDetails(inv.status).label }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                         <Link 
                                            :href="route('accounting.invoices.show', inv.id)" 
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">visibility</span>
                                         </Link>
                                         <Link 
                                            :href="route('accounting.invoices.edit', inv.id)" 
                                            class="w-12 h-12 bg-white shadow-xl border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-90 transition-all"
                                         >
                                            <span class="material-symbols-outlined text-[24px]">border_color</span>
                                         </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Monolith Empty State -->
                <div v-if="invoices.data.length === 0" class="py-48 flex flex-col items-center gap-10 group/empty">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-surface-container-low flex items-center justify-center text-slate-200 border border-outline-variant/5 shadow-2xl group-hover/empty:scale-110 transition-all duration-1000">
                        <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100">receipt_long</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-black text-primary mb-3">لا توجد سجلات مالية مفعلة</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm leading-relaxed">لم يتم العثور على أي بيانات مطابقة في قاعدة البيانات المالية المركزية.</p>
                    </div>
                </div>

                <!-- Institutional Pagination (Command Center Look) -->
                <div v-if="invoices.links && invoices.links.length > 3" class="px-10 py-8 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse">
                    <div class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">تدقيق السجلات المالية (Registry Navigation)</div>
                    <nav class="flex gap-3">
                        <Link 
                            v-for="link in invoices.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-12 flex items-center justify-center rounded-xl text-[12px] font-headline font-black uppercase tracking-widest transition-all border px-6"
                            :class="link.active ? 'bg-primary text-white border-primary shadow-2xl shadow-primary/20 scale-110' : 'bg-white/5 text-white/40 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/30'"
                            v-html="link.label"
                        />
                    </nav>
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
