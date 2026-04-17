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
        case 'paid': return { label: 'تم التحصيل سيادياً', color: 'text-emerald-500 border-emerald-500/20 bg-emerald-500/5 shadow-emerald-500/10', icon: 'verified_user' };
        case 'unpaid': return { label: 'معلق - بانتظار السيولة', color: 'text-amber-600 border-amber-600/20 bg-amber-600/5 shadow-amber-500/10', icon: 'hourglass_empty' };
        case 'overdue': return { label: 'ذمة مالية متأخرة', color: 'text-rose-500 border-rose-500/20 bg-rose-500/5 shadow-rose-500/10', icon: 'emergency_home' };
        default: return { label: status, color: 'text-slate-500 border-slate-500/20 bg-slate-500/5', icon: 'description' };
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ar-SY', { year: 'numeric', month: 'long', day: 'numeric' });
};

</script>

<template>
    <InstitutionalLayout title="سجل الاستخبارات المالية">
        <Head title="سجل الاستخبارات المالية (Financial Ledger) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Fiscal Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">سجل الاستخبارات المالية (Financial Ledger)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">حوكمة التدفقات النقدية، تتبع ذمم المشتركين، وإدارة السندات المحاسبية</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <Link 
                    :href="route('accounting.invoices.create')" 
                    class="px-14 py-6 bg-slate-950 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.3em] shadow-2xl hover:bg-primary transition-all active:scale-95 flex items-center gap-5 border border-white/10 group"
                >
                    <span class="material-symbols-outlined text-[28px] group-hover:rotate-180 transition-transform">add_card</span>
                    إصدار سند مالي (Issue Invoice)
                </Link>
            </div>

            <!-- Fiscal Intelligence Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Total Revenue Metric -->
                <div class="surface-card p-10 rounded-[2.5rem] bg-slate-950 text-white shadow-2xl relative overflow-hidden group border border-white/5">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-primary opacity-50 group-hover:h-2 transition-all"></div>
                    <div class="absolute -top-20 -right-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 shadow-2xl group-hover:rotate-6 transition-transform">
                                <span class="material-symbols-outlined text-primary text-[36px]" style="font-variation-settings: 'FILL' 1">account_balance</span>
                             </div>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] font-headline">AGGR. REVENUE</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline tracking-tighter leading-none">{{ (stats.total_revenue || 0).toLocaleString() }}</h3>
                                <span class="text-[11px] font-black text-primary uppercase tracking-[0.2em] italic">SYP</span>
                            </div>
                            <p class="text-[9px] font-black text-white/20 uppercase tracking-[0.4em] mt-3">إجمالي التحصيلات النشطة</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Liquidity Pulse -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white group overflow-hidden relative">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-amber-500 opacity-20 group-hover:h-2 transition-all"></div>
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shadow-inner group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">hourglass_top</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline">PENDING_LIQUIDITY</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline text-amber-600 tracking-tighter leading-none">{{ (stats.unpaid_amount || 0).toLocaleString() }}</h3>
                                <span class="text-[11px] font-black text-slate-300 uppercase tracking-[0.2em] italic">SYP</span>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3 italic">السيولة المعلقة في المصفوفة</p>
                        </div>
                    </div>
                </div>

                <!-- Settlement Success Registry -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white group overflow-hidden relative">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-emerald-500 opacity-20 group-hover:h-2 transition-all"></div>
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-inner group-hover:-rotate-6 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">task_alt</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline">SETTLED_ARRAY</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline text-emerald-600 tracking-tighter leading-none">{{ stats.paid_count }}</h3>
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase">DOCS</span>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3">سندات تم تحصيلها بنجاح</p>
                        </div>
                    </div>
                </div>

                <!-- Overdue Debt Alert -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white border-b-8 border-rose-500 group overflow-hidden relative">
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shadow-inner group-hover:animate-pulse">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">warning</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline">ALARM_REGISTRY</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline text-rose-600 tracking-tighter leading-none">{{ stats.unpaid_count }}</h3>
                                <span class="px-3 py-1 bg-rose-100 text-rose-600 rounded-lg text-[9px] font-black uppercase tracking-widest leading-none">CRITICAL</span>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-3">مطالبات مالية متأخرة حالياً</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Precision Analytical Filters -->
            <div class="surface-card p-10 rounded-[3rem] mb-12 border border-outline-variant/10 shadow-2xl bg-white relative overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-l from-primary via-secondary to-primary opacity-20"></div>
                
                <div class="flex flex-col lg:flex-row justify-between items-center gap-10 flex-row-reverse">
                    <div class="flex flex-col md:flex-row items-center gap-10 w-full lg:w-auto flex-row-reverse">
                        <!-- Advanced Tactical Search -->
                        <div class="relative flex-1 md:w-[550px] group">
                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-primary opacity-40 group-focus-within:opacity-100 transition-opacity text-[28px]">pageview</span>
                            <input v-model="filters.search" type="text" placeholder="تدقيق: رقم السند، هوية المشترك، أو النطاق الهاتفي..." class="form-input-monolith h-18 pr-16 pl-8 text-base font-black tracking-tight shadow-inner rounded-2xl">
                        </div>

                        <!-- Status Matrix Selector -->
                        <div class="flex bg-slate-50 p-2.5 rounded-2xl border border-slate-200 flex-row-reverse shadow-inner">
                             <button @click="filters.status = 'all'" 
                                class="px-10 py-4 rounded-xl text-[10px] font-black uppercase tracking-[0.3em] transition-all flex items-center gap-3" 
                                :class="filters.status === 'all' ? 'bg-slate-950 text-white shadow-2xl scale-105' : 'text-slate-400 hover:text-primary'">
                                <span class="material-symbols-outlined text-[18px]">readness_score</span>
                                كافة السجلات
                             </button>
                             <button @click="filters.status = 'paid'" 
                                class="px-10 py-4 rounded-xl text-[10px] font-black uppercase tracking-[0.3em] transition-all flex items-center gap-3" 
                                :class="filters.status === 'paid' ? 'bg-emerald-500 text-white shadow-2xl scale-105' : 'text-slate-400 hover:text-emerald-600'">
                                <span class="material-symbols-outlined text-[18px]">verified</span>
                                تمت التسوية
                             </button>
                             <button @click="filters.status = 'unpaid'" 
                                class="px-10 py-4 rounded-xl text-[10px] font-black uppercase tracking-[0.3em] transition-all flex items-center gap-3" 
                                :class="filters.status === 'unpaid' ? 'bg-amber-500 text-white shadow-2xl scale-105' : 'text-slate-400 hover:text-amber-600'">
                                <span class="material-symbols-outlined text-[18px]">hourglass_empty</span>
                                قيد الانتظار
                             </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Institutional Financial Register -->
            <div class="surface-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-outline-variant/10 bg-white relative">
                 <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))] pointer-events-none opacity-20"></div>
                
                <div class="overflow-x-auto relative z-10">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-950 text-white border-b border-white/5 uppercase">
                                <th class="px-12 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">هوية السند (Invoice ID)</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-white/30">الكيان المستهدف</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">القيمة التعاقدية</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30 border-r border-white/5">أفق الاستحقاق</th>
                                <th class="px-10 py-8 text-[11px] font-black uppercase tracking-[0.3em] text-center text-white/30">وضعية التسوية</th>
                                <th class="px-10 py-8 w-48"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="inv in invoices.data" :key="inv.id" class="group hover:bg-slate-50/80 transition-all duration-500">
                                <td class="px-12 py-10">
                                    <div class="flex items-center gap-10 justify-end text-right">
                                        <div>
                                            <h4 class="text-2xl font-black text-primary leading-tight group-hover:translate-x-3 transition-transform tracking-tight uppercase font-headline">{{ inv.invoice_number }}</h4>
                                            <div class="flex items-center gap-3 mt-3 opacity-40 justify-end">
                                                <p class="text-[10px] font-black font-headline uppercase tracking-[0.2em] leading-none">{{ formatDate(inv.created_at) }}</p>
                                                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                                            </div>
                                        </div>
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6 transition-all duration-700 relative overflow-hidden shrink-0 border border-white/10">
                                             <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                                             <span class="material-symbols-outlined text-[36px] relative z-10" style="font-variation-settings: 'FILL' 1; font-weight: 200;">receipt_long</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="text-right">
                                        <p class="text-xl font-black text-primary leading-tight mb-3 uppercase group-hover:text-primary transition-colors">{{ inv.client?.name || 'مشترك غير معرف' }}</p>
                                        <div class="flex items-center gap-4 justify-end opacity-40">
                                            <span class="text-[11px] font-headline font-black tracking-[0.3em] uppercase">{{ inv.client?.username || 'ANONYMOUS_ENTITY' }}</span>
                                            <span class="material-symbols-outlined text-[18px]">person_pin_circle</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-3xl font-headline font-black tracking-tighter text-primary leading-none group-hover:scale-110 transition-transform">{{ (inv.amount || 0).toLocaleString() }}</p>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none">ل.س (SYP)</span>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center border-r border-outline-variant/5">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="text-[12px] font-headline font-black tracking-[0.1em] text-white bg-slate-950 px-6 py-2.5 rounded-xl shadow-xl group-hover:bg-primary transition-colors italic">{{ formatDate(inv.due_date) }}</span>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] leading-none mt-2">نهاية النافذة المالية</p>
                                    </div>
                                </td>
                                <td class="px-10 py-10 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-4 px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] border-2 transition-all shadow-2xl group-hover:translate-y-[-4px]',
                                        getStatusDetails(inv.status).color
                                    ]">
                                        <span class="relative flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-current"></span>
                                        </span>
                                        {{ getStatusDetails(inv.status).label }}
                                    </div>
                                </td>
                                <td class="px-10 py-10">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link 
                                            :href="route('accounting.invoices.show', inv.id)" 
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-primary hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">visibility</span>
                                         </Link>
                                         <Link 
                                            :href="route('accounting.invoices.edit', inv.id)" 
                                            class="w-14 h-14 bg-white shadow-2xl border border-outline-variant/10 rounded-2xl flex items-center justify-center text-slate-400 hover:text-amber-600 hover:scale-110 active:scale-90 transition-all group/icon"
                                         >
                                            <span class="material-symbols-outlined text-[28px] group-hover/icon:-rotate-12 transition-transform" style="font-variation-settings: 'wght' 700">edit_square</span>
                                         </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty Fiscal Registry State -->
                <div v-if="invoices.data.length === 0" class="py-64 flex flex-col items-center gap-12 group/empty relative z-10">
                    <div class="w-48 h-48 rounded-[3.5rem] bg-slate-950 text-white flex items-center justify-center border-8 border-white shadow-[0_0_80px_rgba(2,6,23,0.15)] group-hover/empty:scale-110 group-hover/empty:rotate-45 transition-all duration-1000 relative">
                        <div class="absolute inset-0 bg-primary opacity-20 blur-3xl animate-pulse"></div>
                        <span class="material-symbols-outlined text-[80px] relative z-10" style="font-variation-settings: 'wght' 100">money_off</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-4xl font-black text-primary mb-6 tracking-tighter uppercase leading-none">مجموع السجل صفر (Null Ledger)</h3>
                        <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em] max-w-sm leading-relaxed italic opacity-70">لم يتم رصد أي سندات مالية نشطة ضمن المصفوفة المحاسبية حالياً.</p>
                    </div>
                </div>

                <!-- Tactical Financial Pagination -->
                <div v-if="invoices.links && invoices.links.length > 3" class="px-12 py-10 bg-slate-950 border-t border-white/5 flex justify-between items-center flex-row-reverse relative z-10 overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                    <div class="text-[10px] font-black text-white/20 uppercase tracking-[0.5em] font-headline relative z-10">FISCAL_REGISTRY_NAVIGATION_PROTOCOL_v4.0</div>
                    <nav class="flex gap-5 relative z-10">
                        <Link 
                            v-for="link in invoices.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-14 flex items-center justify-center rounded-2xl text-[12px] font-headline font-black uppercase tracking-[0.2em] transition-all border px-8"
                            :class="link.active ? 'bg-primary text-white border-primary shadow-[0_15px_30px_rgba(37,99,235,0.3)] scale-110 z-10' : 'bg-white/5 text-white/40 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/30'"
                            v-html="link.label"
                        />
                    </nav>
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
.form-input-monolith {
    @apply w-full bg-slate-50 border-slate-200 text-slate-900 rounded-2xl pr-14 focus:ring-8 focus:ring-primary/5 focus:border-primary transition-all font-black text-base;
}
</style>
