<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    AlertCircle,
    BadgeDollarSign,
    Calendar,
    CheckCircle2,
    Clock,
    Edit3,
    Eye,
    Receipt,
    CreditCard,
    TrendingUp,
    UserCircle,
    Search,
    RefreshCcw
} from 'lucide-vue-next';
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
        case 'paid': return { label: 'تم التحصيل سيادياً', class: 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20 shadow-emerald-500/10', icon: CheckCircle2 };
        case 'unpaid': return { label: 'معلق - بانتظار السيولة', class: 'bg-amber-500/10 text-amber-600 border-amber-500/20 shadow-amber-500/10', icon: Clock };
        case 'overdue': return { label: 'ذمة مالية متأخرة', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20 shadow-rose-500/10', icon: AlertCircle };
        default: return { label: status, class: 'bg-slate-500/10 text-slate-500 border-slate-500/20', icon: Receipt };
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US').format(amount);
};

</script>

<template>
    <InstitutionalLayout title="سجل الاستخبارات المالية">
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-vendor rounded-full"></span>
                        <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Financial Intelligence Ledger</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">سجل <span class="text-vendor">الاستخبارات</span> المالية</h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">حوكمة التدفقات النقدية وإدارة السندات السيادية</p>
                </div>
                
                <Link :href="route('accounting.invoices.create')" class="btn-radiant btn-vendor px-8 py-4 text-xs font-black uppercase tracking-[0.2em] flex items-center gap-3">
                    <CreditCard class="w-5 h-5 stroke-[3]" />
                    إصدار سند مالي
                </Link>
            </div>

            <!-- Fiscal Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Total Revenue -->
                <div class="glass-card p-8 bg-slate-900 text-white group relative overflow-hidden border-none">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-vendor/20 rounded-full blur-2xl group-hover:scale-150 transition-transform opacity-30"></div>
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-vendor shadow-sm">
                            <TrendingUp class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="text-[9px] font-black text-white/40 uppercase tracking-[0.3em] font-inter">AGGR. REVENUE</div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-white italic tracking-tighter font-inter">{{ formatCurrency(stats.total_revenue || 0) }}</h3>
                        <span class="text-[10px] font-black text-vendor uppercase tracking-widest font-inter">SAR</span>
                    </div>
                    <p class="text-[9px] font-black text-white/20 uppercase tracking-[0.4em] mt-4 italic">إجمالي التحصيلات النشطة</p>
                </div>

                <!-- Pending Liquidity -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-amber-500/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm">
                            <Clock class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] font-inter">PENDING</div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-amber-600 italic tracking-tighter font-inter">{{ formatCurrency(stats.unpaid_amount || 0) }}</h3>
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest font-inter">SAR</span>
                    </div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4 italic">السيولة المعلقة حالياً</p>
                </div>

                <!-- Settled Array -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-emerald-500/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                            <CheckCircle2 class="w-6 h-6 stroke-[2.5]" />
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-emerald-600 italic tracking-tighter font-inter">{{ stats.paid_count }}</h3>
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest font-inter">INVOICES</span>
                    </div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4 italic">سندات تم تحصيلها</p>
                </div>

                <!-- Critical Alerts -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-rose-500/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-500 shadow-sm">
                            <AlertCircle class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="px-2 py-1 rounded-md bg-rose-500 text-white font-black text-[9px] uppercase tracking-widest">Alarm</div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-rose-600 italic tracking-tighter font-inter">{{ stats.unpaid_count }}</h3>
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest font-inter">OVERDUE</span>
                    </div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4 italic">مطالبات مالية متأخرة</p>
                </div>

            </div>

            <!-- Precision Analytical Filters -->
            <div class="glass-card p-8 bg-white/60 flex flex-col lg:flex-row items-center gap-8 border-white/60">
                <div class="flex-1 w-full flex flex-col md:flex-row items-center gap-6">
                    <div class="relative flex-1 w-full group">
                        <input v-model="filters.search" type="text" placeholder="رقم السند، هوية المشترك، أو النطاق الرقمي..." class="w-full bg-white/50 border-white/60 rounded-2xl pr-12 py-3.5 text-sm font-bold focus:ring-4 focus:ring-vendor/5 transition-all">
                        <Search class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 w-5 h-5 group-focus-within:text-vendor transition-colors stroke-[3]" />
                    </div>

                    <div class="flex bg-white/50 p-1.5 rounded-2xl border border-white/60 shadow-inner w-full md:w-auto">
                        <button @click="filters.status = 'all'" 
                            class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                            :class="filters.status === 'all' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-vendor'">
                            الكل
                        </button>
                        <button @click="filters.status = 'paid'" 
                            class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                            :class="filters.status === 'paid' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400 hover:text-emerald-600'">
                            المحصلة
                        </button>
                        <button @click="filters.status = 'unpaid'" 
                            class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all"
                            :class="filters.status === 'unpaid' ? 'bg-amber-500 text-white shadow-lg' : 'text-slate-400 hover:text-amber-600'">
                            المعلقة
                        </button>
                    </div>
                </div>

                <button @click="filters.search = ''; filters.status = 'all'" class="h-14 px-8 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all flex items-center gap-3 shrink-0">
                    <RefreshCcw class="w-4 h-4" />
                    تصفير
                </button>
            </div>

            <!-- Institutional Financial Register -->
            <div class="glass-card overflow-hidden bg-white/40 border-white/60">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                                <th class="px-10 py-8">هوية السند (Invoice ID)</th>
                                <th class="px-8 py-8">الكيان المستهدف</th>
                                <th class="px-8 py-8 text-center">القيمة التعاقدية</th>
                                <th class="px-8 py-8 text-center">أفق الاستحقاق</th>
                                <th class="px-8 py-8 text-center">وضعية التسوية</th>
                                <th class="px-10 py-8"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/20">
                            <tr v-for="inv in invoices.data" :key="inv.id" class="group hover:bg-white/60 transition-all duration-500">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-5 justify-end">
                                        <div class="text-right">
                                            <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase font-inter">{{ inv.invoice_number }}</h4>
                                            <div class="flex items-center gap-2 mt-1 opacity-40 justify-end">
                                                <span class="text-[9px] font-black uppercase tracking-widest font-inter">{{ formatDate(inv.created_at) }}</span>
                                                <Calendar class="w-3 h-3" />
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-vendor flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shrink-0 border border-white/10">
                                            <Receipt class="w-7 h-7 stroke-[2]" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-4 justify-end">
                                        <div class="text-right">
                                            <p class="text-[13px] font-black text-slate-700 leading-none mb-1">{{ inv.client?.name || 'Anonymous Subscriber' }}</p>
                                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic font-inter opacity-60">{{ inv.client?.username || 'NULL_IDENTITY' }}</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-400">
                                            <UserCircle class="w-6 h-6" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="flex items-baseline gap-1.5">
                                            <span class="text-2xl font-black text-vendor italic font-inter group-hover:scale-110 transition-transform">{{ formatCurrency(inv.amount || 0) }}</span>
                                            <span class="text-[9px] font-black text-slate-400 uppercase italic font-inter">SAR</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="px-5 py-2 bg-slate-900 text-white rounded-xl text-[11px] font-black font-inter shadow-lg group-hover:bg-vendor transition-colors italic whitespace-nowrap">
                                            {{ formatDate(inv.due_date) }}
                                        </div>
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-2 font-inter">Fiscal Deadline</span>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-3 px-6 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all shadow-sm group-hover:translate-y-[-2px]',
                                        getStatusDetails(inv.status).class
                                    ]">
                                        <component :is="getStatusDetails(inv.status).icon" class="w-3.5 h-3.5" />
                                        {{ getStatusDetails(inv.status).label }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link :href="route('accounting.invoices.show', inv.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-vendor hover:scale-110 active:scale-90 transition-all">
                                            <Eye class="w-5 h-5" />
                                         </Link>
                                         <Link :href="route('accounting.invoices.edit', inv.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-500 hover:scale-110 active:scale-90 transition-all">
                                            <Edit3 class="w-5 h-5" />
                                         </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="invoices.data.length === 0" class="py-40 flex flex-col items-center gap-8 text-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-slate-900 text-vendor flex items-center justify-center border-8 border-white/20 shadow-2xl relative group">
                        <div class="absolute inset-0 bg-vendor/20 opacity-20 blur-2xl animate-pulse"></div>
                        <BadgeDollarSign class="w-12 h-12 relative z-10" />
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 italic tracking-tighter uppercase">مجموع السجل صفر (Null Ledger)</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mt-3 opacity-70 italic font-inter">No financial records detected in the current sovereign domain.</p>
                    </div>
                </div>

                <!-- Strategic Pagination -->
                <div v-if="invoices.links && invoices.links.length > 3" class="px-10 py-8 bg-slate-900/5 border-t border-white/40 flex justify-between items-center">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-inter opacity-40">Financial Registry v2.1</div>
                    <nav class="flex gap-2">
                        <Link 
                            v-for="link in invoices.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-10 min-w-[40px] flex items-center justify-center rounded-xl text-[10px] font-black transition-all border px-4"
                            :class="link.active ? 'bg-vendor text-white border-vendor shadow-lg' : 'bg-white/50 text-slate-400 border-white/60 hover:text-vendor hover:bg-white'"
                            v-html="link.label"
                        />
                    </nav>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>


