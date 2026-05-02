<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    AlertCircle,
    CheckCircle2,
    Clock,
    Edit3,
    Eye,
    Receipt,
    CreditCard,
    TrendingUp,
    Search,
    RefreshCcw,
    FileText,
    Download,
    Plus
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
        case 'paid': return { label: 'مدفوع', class: 'bg-emerald-100 text-emerald-600' };
        case 'unpaid': return { label: 'بانتظار الدفع', class: 'bg-amber-100 text-amber-600' };
        case 'overdue': return { label: 'متأخر', class: 'bg-rose-100 text-rose-600' };
        default: return { label: status, class: 'bg-slate-100 text-slate-500' };
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
    <InstitutionalLayout title="المحاسبة والفواتير">
        <div class="space-y-8 pb-20">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">المحاسبة والفواتير</h1>
                    <p class="text-slate-400 font-bold text-xs mt-1">تتبع وإدارة التدفقات المالية وفواتير المشتركين</p>
                </div>
                <div class="flex items-center gap-4">
                    <button class="btn-outline flex items-center gap-2 px-6 py-2.5">
                        <Download class="w-4 h-4" />
                        تصدير التقارير
                    </button>
                    <Link :href="route('accounting.invoices.create')" class="btn-primary flex items-center gap-2 px-8 py-2.5">
                        <Plus class="w-5 h-5" />
                        إصدار فاتورة يدوية
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="clean-card p-8 flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-bold mb-2">الأرباح الشهرية</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-black text-slate-900">{{ formatCurrency(stats.total_revenue || 0) }}</h3>
                            <span class="text-xs font-bold text-primary">ل.س</span>
                        </div>
                        <p class="text-emerald-500 text-[10px] font-bold mt-2 flex items-center gap-1">
                            <TrendingUp class="w-3 h-3" />
                            +12.5% مقارنة بالشهر الماضي
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-primary-soft rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                        <TrendingUp class="w-7 h-7" />
                    </div>
                </div>

                <div class="clean-card p-8 flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-bold mb-2">الديون المستحقة</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-black text-rose-500">{{ formatCurrency(stats.unpaid_amount || 0) }}</h3>
                            <span class="text-xs font-bold text-rose-300">ل.س</span>
                        </div>
                        <p class="text-slate-400 text-[10px] font-bold mt-2 italic">18 فاتورة بانتظار التحصيل</p>
                    </div>
                    <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 border border-rose-100">
                        <AlertCircle class="w-7 h-7" />
                    </div>
                </div>

                <div class="clean-card p-8 flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-bold mb-2">الفواتير المدفوعة</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-black text-slate-900">842</h3>
                            <span class="text-xs font-bold text-slate-300">فاتورة</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                             <div class="w-16 h-1.5 bg-slate-100 rounded-full">
                                <div class="w-[94%] h-full bg-primary rounded-full"></div>
                             </div>
                             <span class="text-[10px] font-bold text-primary">94% معدل السداد</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 border border-slate-200">
                        <CheckCircle2 class="w-7 h-7" />
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="clean-card p-6 flex flex-col lg:flex-row items-center gap-6">
                <div class="relative flex-1 w-full">
                    <Search class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 w-4 h-4" />
                    <input v-model="filters.search" type="text" placeholder="البحث عن رقم فاتورة، اسم مشترك..." class="w-full bg-slate-50 border-slate-200 rounded-xl pr-12 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all">
                </div>
                <div class="flex bg-slate-50 p-1.5 rounded-xl border border-slate-200 w-full lg:w-auto">
                    <button @click="filters.status = 'all'" class="flex-1 lg:flex-none px-8 py-2 text-[11px] font-black uppercase tracking-widest rounded-lg transition-all" :class="filters.status === 'all' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'">الكل</button>
                    <button @click="filters.status = 'paid'" class="flex-1 lg:flex-none px-8 py-2 text-[11px] font-black uppercase tracking-widest rounded-lg transition-all" :class="filters.status === 'paid' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'">مدفوع</button>
                    <button @click="filters.status = 'unpaid'" class="flex-1 lg:flex-none px-8 py-2 text-[11px] font-black uppercase tracking-widest rounded-lg transition-all" :class="filters.status === 'unpaid' ? 'bg-white text-primary shadow-sm' : 'text-slate-400 hover:text-slate-600'">متأخر</button>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="clean-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-clean">
                        <thead>
                            <tr>
                                <th class="text-right">رقم الفاتورة</th>
                                <th class="text-right">المشترك</th>
                                <th class="text-center">تاريخ الإصدار</th>
                                <th class="text-center">المبلغ الإجمالي</th>
                                <th class="text-center">حالة الدفع</th>
                                <th class="text-left">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="inv in invoices.data" :key="inv.id" class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-5 text-right">
                                    <span class="font-black text-primary">#INV-{{ inv.id + 9800 }}</span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary-soft text-primary flex items-center justify-center text-[10px] font-black border border-primary/10">
                                            {{ inv.client?.name?.substring(0,1) || '?' }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 leading-none">{{ inv.client?.name || 'مشترك مجهول' }}</p>
                                            <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-widest font-inter">User-{{ inv.client?.id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center text-xs font-bold text-slate-500">
                                    {{ new Date(inv.created_at).toLocaleDateString('ar-EG', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="flex items-baseline justify-center gap-1">
                                        <span class="text-sm font-black text-slate-800">{{ formatCurrency(inv.amount) }}</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase italic">SAR</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div :class="['inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-[10px] font-black', getStatusDetails(inv.status).class]">
                                        <div class="w-1 h-1 rounded-full bg-current"></div>
                                        {{ getStatusDetails(inv.status).label }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-left">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Link :href="route('accounting.invoices.show', inv.id)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:bg-primary-soft transition-all">
                                            <Eye class="w-4 h-4" />
                                        </Link>
                                        <Link :href="route('accounting.invoices.edit', inv.id)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all">
                                            <Edit3 class="w-4 h-4" />
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="invoices.links && invoices.links.length > 3" class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <p class="text-[10px] font-bold text-slate-400">عرض {{ invoices.data.length }} من أصل {{ invoices.total }} فاتورة</p>
                    <nav class="flex gap-1">
                        <Link 
                            v-for="link in invoices.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-8 min-w-[32px] flex items-center justify-center rounded-lg text-[11px] font-bold transition-all px-3"
                            :class="link.active ? 'bg-primary text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200 hover:bg-slate-50'"
                            v-html="link.label"
                        />
                    </nav>
                </div>
            </div>
            
            <!-- Bottom Suggestions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                 <div class="clean-card p-8 bg-blue-50/30 border-primary/10 flex items-center gap-8">
                    <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-primary shadow-sm border border-primary/5 shrink-0">
                         <span class="material-symbols-outlined text-[40px]">smart_toy</span>
                    </div>
                    <div>
                         <h4 class="text-lg font-black text-primary italic mb-2">نصيحة النظام الذكي</h4>
                         <p class="text-sm font-bold text-slate-600 leading-relaxed mb-4">هناك <span class="text-primary">5 مشتركين</span> تجاوزوا موعد الدفع بـ 3 أيام. هل تود إرسال رسائل تذكير تلقائية عبر WhatsApp أو SMS؟</p>
                         <button class="btn-primary px-8 py-2 text-xs">إرسال تنبيهات الآن</button>
                    </div>
                 </div>

                 <div class="clean-card p-8 border-dashed flex flex-col justify-center items-center text-center">
                    <div class="flex gap-6 mb-6">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-100">
                                <FileText class="w-6 h-6" />
                            </div>
                            <span class="text-[10px] font-black text-slate-400">Excel</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 border border-rose-100">
                                <FileText class="w-6 h-6" />
                            </div>
                            <span class="text-[10px] font-black text-slate-400">PDF</span>
                        </div>
                    </div>
                    <p class="text-sm font-black text-slate-800 mb-1">تصدير البيانات المالية</p>
                    <p class="text-[10px] font-bold text-slate-400">كافة التفاصيل وجداول الإيرادات بنقرة واحدة</p>
                 </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>
