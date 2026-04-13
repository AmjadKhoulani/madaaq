<script setup>
import { onMounted, ref } from 'vue';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    TrendingUp, 
    Building2, 
    ShieldCheck, 
    Users, 
    ArrowUpRight,
    ExternalLink,
    Activity,
    CreditCard,
    ArrowDownLeft,
    Clock
} from 'lucide-vue-next';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    stats: Object,
    recent_invoices: Array,
    recent_tenants: Array,
    trial_users: Array,
    months: Array,
    chart_revenue: Array,
    chart_tenants: Array,
});

const revenueChartRef = ref(null);
const tenantsChartRef = ref(null);

onMounted(() => {
    // Revenue Chart - Professional Enterprise Style
    new Chart(revenueChartRef.value, {
        type: 'line',
        data: {
            labels: props.months,
            datasets: [{
                label: 'الإيرادات',
                data: props.chart_revenue,
                borderColor: '#2563eb', // Enterprise Blue
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0, // Solid straight lines for institutional feel
                pointRadius: 3,
                pointBackgroundColor: '#2563eb',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#94a3b8', font: { size: 10 } }
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#94a3b8', font: { size: 10 } } 
                }
            }
        }
    });

    // Tenants Chart - Stacked Bar Style
    new Chart(tenantsChartRef.value, {
        type: 'bar',
        data: {
            labels: props.months,
            datasets: [{
                label: 'المشتركين الجدد',
                data: props.chart_tenants,
                backgroundColor: '#0f172a', // Navy
                borderRadius: 4,
                barThickness: 15,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#94a3b8', font: { size: 10 } }
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#94a3b8', font: { size: 10 } } 
                }
            }
        }
    });
});

const formatNumber = (num) => {
    return new Intl.NumberFormat('en-US').format(num);
};

</script>

<template>
    <InstitutionalLayout title="مركز العمليات الذكي">
        
        <!-- Header Info -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="text-right">
                <h1 class="text-2xl font-black text-slate-900 mb-1">لوحة التحكم الإستراتيجية</h1>
                <p class="text-sm text-slate-500 font-medium flex items-center justify-end gap-2">
                    مراقبة أداء الشبكة، المشتركين، وتحليل البيانات المالية اللحظية
                    <Activity class="w-4 h-4 text-blue-600" />
                </p>
            </div>
            <div class="flex items-center gap-3">
                 <button class="px-5 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition-none flex items-center gap-2">
                    <Clock class="w-4 h-4" />
                    تقارير اليوم
                 </button>
                 <button class="ent-btn-primary">
                    إضافة وحدة جديدة
                 </button>
            </div>
        </div>

        <!-- Top KPIs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 text-right">
            <!-- Revenue -->
            <div class="ent-card p-6 border-r-4 border-r-blue-600">
                <div class="flex items-center justify-between mb-4 flex-row-reverse">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <TrendingUp class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">إجمالي الإيرادات</p>
                <div class="text-2xl font-black text-slate-900 mb-1 leading-none tracking-tight">${{ formatNumber(stats.total_revenue) }}</div>
                <div class="flex items-center gap-2 mt-2 flex-row-reverse">
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 flex items-center gap-1">
                        +14.2% <ArrowUpRight class="w-3 h-3" />
                    </span>
                    <span class="text-[10px] text-slate-400 font-medium">مقارنة بالشهر السابق</span>
                </div>
            </div>

            <!-- Tenants -->
            <div class="ent-card p-6 border-r-4 border-r-slate-900">
                <div class="flex items-center justify-between mb-4 flex-row-reverse">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-900">
                        <Building2 class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">الموزعين النشطين</p>
                <div class="text-2xl font-black text-slate-900 mb-1 leading-none tracking-tight">{{ stats.active_tenants }}</div>
                <p class="text-[10px] text-slate-500 font-medium">من أصل <span class="font-bold text-slate-700">{{ stats.total_tenants }}</span> موزعين متاحين</p>
            </div>

            <!-- Subscriptions -->
            <div class="ent-card p-6 border-r-4 border-r-emerald-500">
                <div class="flex items-center justify-between mb-4 flex-row-reverse">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <ShieldCheck class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">الاشتراكات الفعالة</p>
                <div class="text-2xl font-black text-slate-900 mb-1 leading-none tracking-tight">{{ stats.active_subscriptions }}</div>
                <div class="flex items-center gap-1 mt-2 flex-row-reverse">
                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] text-emerald-600 font-black uppercase">باقات المؤسسات والبرو</span>
                </div>
            </div>

            <!-- Clients -->
            <div class="ent-card p-6 border-r-4 border-r-indigo-600">
                <div class="flex items-center justify-between mb-4 flex-row-reverse">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <Users class="w-5 h-5" />
                    </div>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">إجمالي المشتركين</p>
                <div class="text-2xl font-black text-slate-900 mb-1 leading-none tracking-tight">{{ formatNumber(stats.total_clients) }}</div>
                <p class="text-[10px] text-slate-500 font-medium">مستخدم مسجل في كافة الشبكات</p>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 ent-card overflow-hidden">
                <div class="ent-card-header flex items-center justify-between flex-row-reverse">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">تحليل النمو المالي (آخر 6 أشهر)</h3>
                    <TrendingUp class="w-4 h-4 text-slate-400" />
                </div>
                <div class="p-8">
                    <div class="h-[300px] w-full">
                        <canvas ref="revenueChartRef"></canvas>
                    </div>
                </div>
            </div>

            <!-- Stats Breakdown Card -->
            <div class="ent-card overflow-hidden flex flex-col">
                <div class="ent-card-header flex items-center justify-between flex-row-reverse">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">توزيع الشبكات</h3>
                    <Activity class="w-4 h-4 text-slate-400" />
                </div>
                <div class="p-6 flex-1 space-y-6">
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase flex-row-reverse">
                            <span>جودة الخدمة (QoS)</span>
                            <span>98.4%</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 w-[98.4%]"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase flex-row-reverse">
                            <span>سعة التحميل المستهلكة</span>
                            <span>64%</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 w-[64%]"></div>
                        </div>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100 h-40">
                         <canvas ref="tenantsChartRef"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-right">
            <!-- Recent Invoices Table -->
             <div class="lg:col-span-2 ent-card overflow-hidden">
                <div class="ent-card-header flex justify-between items-center flex-row-reverse">
                    <div class="flex items-center gap-3">
                        <CreditCard class="w-4 h-4 text-slate-400" />
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">أحدث العمليات المالية</h3>
                    </div>
                    <button class="text-blue-600 text-[10px] font-black uppercase tracking-widest hover:underline flex items-center gap-1 flex-row-reverse transition-none">
                         عرض السجل المالي <ArrowUpRight class="w-3 h-3" />
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 border-b border-slate-200">
                                <th class="px-8 py-4 font-black uppercase tracking-widest">رقم الفاتورة</th>
                                <th class="px-8 py-4 font-black uppercase tracking-widest">جهة الدفع</th>
                                <th class="px-8 py-4 font-black uppercase tracking-widest">القيمة الإجمالية</th>
                                <th class="px-8 py-4 font-black uppercase tracking-widest text-center">حالة الدفع</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="invoice in recent_invoices" :key="invoice.id" class="hover:bg-slate-50 transition-none group">
                                <td class="px-8 py-4 font-bold font-mono text-slate-900">#{{ invoice.invoice_number }}</td>
                                <td class="px-8 py-4 font-bold text-slate-700">{{ invoice.tenant?.name || 'غير محدد' }}</td>
                                <td class="px-8 py-4 font-black text-blue-600">${{ formatNumber(invoice.amount) }}</td>
                                <td class="px-8 py-4 text-center">
                                    <span 
                                        :class="[
                                            'px-3 py-1 rounded text-[9px] font-black uppercase border border-opacity-50',
                                            invoice.status === 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200'
                                        ]"
                                    >
                                        {{ invoice.status === 'paid' ? 'تم تحصيلها' : 'قيد الانتظار' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
             </div>

             <!-- Recent Tenants Module -->
             <div class="ent-card overflow-hidden">
                <div class="ent-card-header flex justify-between items-center flex-row-reverse">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">الموزعين المضافين حديثاً</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <div v-for="tenant in recent_tenants" :key="tenant.id" class="p-4 hover:bg-slate-50 transition-none flex items-center justify-between flex-row-reverse">
                        <div class="flex items-center gap-4 flex-row-reverse text-right">
                            <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                                {{ tenant.name.substring(0,1) }}
                            </div>
                            <div>
                                <div class="text-[12px] font-bold text-slate-900">{{ tenant.name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ tenant.domain }}</div>
                            </div>
                        </div>
                        <a :href="'http://' + tenant.domain" target="_blank" class="w-8 h-8 flex items-center justify-center hover:bg-white border hover:border-slate-200 rounded-lg transition-none text-slate-400 hover:text-blue-600">
                            <ExternalLink class="w-4 h-4" />
                        </a>
                    </div>
                    
                    <div class="p-6 bg-slate-50/50">
                         <button class="w-full py-3 border border-dashed border-slate-300 rounded-lg text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-white hover:text-slate-900 transition-none">
                            إدارة كافة الموزعين
                         </button>
                    </div>
                </div>
             </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
/* Force instant transitions for RAM performance */
* {
    transition: none !important;
}
</style>
