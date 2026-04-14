<script setup>
import { onMounted, ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    Users, 
    CreditCard, 
    HardDrive, 
    TowerControl as TowerIcon, 
    AlertTriangle,
    Activity,
    Globe,
    Clock,
    CheckCircle2,
    XCircle,
    ArrowUpRight,
    TrendingUp,
    Zap,
    Info,
    ShieldCheck
} from 'lucide-vue-next';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    stats: Object,
    networkStats: Object,
    activeAlerts: Array,
    bandwidthToday: Object,
    topWebsites: Array,
    recentClients: Array,
    recentInvoices: Array,
    revenueChart: Object,
    clientsChart: Object,
    expiringClients: Array,
    setupChecklist: Object,
    setupComplete: Boolean
});

const revenueChartRef = ref(null);
const clientsChartRef = ref(null);

onMounted(() => {
    // Premium Revenue Pulse
    new Chart(revenueChartRef.value, {
        type: 'line',
        data: {
            labels: props.revenueChart.labels,
            datasets: [{
                label: 'الإيرادات اليومية',
                data: props.revenueChart.data,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9', drawBorder: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' } }
            }
        }
    });

    // Premium Clients Velocity
    new Chart(clientsChartRef.value, {
        type: 'bar',
        data: {
            labels: props.clientsChart.labels,
            datasets: [{
                label: 'نمو المشتركين',
                data: props.clientsChart.data,
                backgroundColor: '#ffffff',
                borderRadius: 6,
                barThickness: 14,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false },
                x: { display: false }
            }
        }
    });
});

const formatBytes = (bytes) => {
    if (!bytes || bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('ar-SA', { style: 'currency', currency: 'USD' }).format(amount);
};

</script>

<template>
    <InstitutionalLayout title="مركز العمليات الذكي">
        <Head title="لوحة القيادة - مدى كيو" />

        <!-- Strategic Intelligence Pulse -->
        <div class="mb-12 flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="text-right order-2 lg:order-1">
                <h1 class="text-3xl font-black text-slate-900 mb-2 flex items-center gap-3 justify-end leading-none">
                    لوحة الإدارة والتحكم
                    <div class="w-2 h-8 bg-indigo-600 rounded-full shadow-lg shadow-indigo-600/30"></div>
                </h1>
                <p class="text-sm text-slate-500 font-bold">متابعة دقيقة وفورية لأداء السيرفرات وحركة البيانات والمشتركين</p>
            </div>
            
            <div class="flex items-center gap-6 order-1 lg:order-2">
                 <div class="glass-surface px-8 py-4 flex items-center gap-6 border-r-4 border-r-emerald-500 shadow-xl shadow-emerald-500/5 rounded-2xl">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">سلامة الشبكة</p>
                        <p class="text-base font-black text-slate-900 leading-none">
                            <span class="text-emerald-600">{{ networkStats.online }}</span> 
                            <span class="mx-1.5 text-slate-300">/</span> 
                            {{ networkStats.total }} نشط
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100 shadow-inner">
                        <Zap class="w-6 h-6" :class="networkStats.online > 0 ? 'animate-pulse' : ''" />
                    </div>
                 </div>

                 <div v-if="!setupComplete" class="glass-surface px-8 py-4 flex items-center gap-6 border-r-4 border-r-amber-500 shadow-xl shadow-amber-500/5 rounded-2xl">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">تحهيز النظام</p>
                        <p class="text-base font-black text-slate-900 leading-none">بانتظار الإكمال</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100 shadow-inner">
                        <AlertTriangle class="w-6 h-6 animate-bounce" />
                    </div>
                 </div>
            </div>
        </div>

        <!-- Tier 1: Global KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 text-right">
             <!-- Active Global Clients -->
             <div class="silk-card p-8 group transition-all">
                 <div class="flex items-center justify-between mb-6 flex-row-reverse">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <Users class="w-6 h-6" />
                    </div>
                    <div class="flex flex-col items-end">
                         <span class="text-rose-500 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-100 text-[10px] font-black group-hover:bg-rose-500 group-hover:text-white transition-all">
                            {{ stats.inactive_clients }} متوقف
                         </span>
                    </div>
                 </div>
                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">إجمالي المشتركين النشطين</p>
                 <div class="text-4xl font-extrabold text-slate-900 tracking-tighter leading-none">{{ stats.active_clients }}</div>
                 <div class="mt-4 pt-4 border-t border-slate-50 text-[11px] font-bold text-slate-400 flex items-center justify-end gap-2">
                    من أصل {{ stats.total_clients }} عملاء مسجلين
                 </div>
             </div>

             <!-- Revenue Performance -->
              <div class="silk-card p-8 group transition-all">
                 <div class="flex items-center justify-between mb-6 flex-row-reverse">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <CreditCard class="w-6 h-6" />
                    </div>
                 </div>
                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">إيرادات الدورة الحالية</p>
                 <div class="text-4xl font-extrabold text-emerald-600 tracking-tighter leading-none">{{ formatCurrency(stats.monthly_revenue) }}</div>
                 <div class="mt-4 pt-4 border-t border-slate-50 text-[12px] font-bold text-amber-600 flex items-center justify-end gap-2 animate-pulse">
                    {{ formatCurrency(stats.pending_amount) }} معلق <Clock class="w-4 h-4" />
                 </div>
             </div>

             <!-- Infrastructure Assets -->
              <div class="silk-card p-8 group transition-all">
                 <div class="flex items-center justify-between mb-6 flex-row-reverse">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center shadow-inner group-hover:scale-110 transition-all duration-300">
                        <HardDrive class="w-6 h-6" />
                    </div>
                 </div>
                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">الأصول والتجهيزات</p>
                 <div class="text-4xl font-extrabold text-slate-900 tracking-tighter leading-none">{{ stats.total_routers }} <span class="text-lg font-bold text-slate-300">سيرفر</span></div>
                 <div class="mt-4 pt-4 border-t border-slate-50 text-[11px] font-bold text-slate-500 flex items-center justify-end gap-2">
                    <TowerIcon class="w-4 h-4 text-indigo-500" /> {{ stats.total_towers }} كابينة بث
                 </div>
             </div>

             <!-- Telemetric Traffic -->
              <div class="silk-card p-8 group transition-all">
                 <div class="flex items-center justify-between mb-6 flex-row-reverse">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:bg-violet-600 group-hover:group-hover:text-white transition-all duration-300">
                        <Activity class="w-6 h-6" />
                    </div>
                 </div>
                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">استهلاك البيانات اليومي</p>
                 <div class="text-4xl font-extrabold text-violet-600 tracking-tighter leading-none">{{ formatBytes(bandwidthToday?.total_rx || 0) }}</div>
                 <div class="mt-4 pt-4 border-t border-slate-50 text-[11px] font-bold text-violet-400 flex items-center justify-end gap-2">
                    إجمالي الوارد والمنصرف <TrendingUp class="w-4 h-4" />
                 </div>
             </div>
        </div>

        <!-- Tier 2: Deep Analysis Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-12 text-right">
            <!-- Revenue Analytics -->
            <div class="lg:col-span-2 space-y-10">
                <div class="silk-card overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between flex-row-reverse bg-slate-50/30">
                        <div class="flex items-center gap-3 flex-row-reverse">
                             <div class="w-2 h-6 bg-indigo-500 rounded-full"></div>
                             <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">تحليل التدفق النقدي (آخر 7 أيام)</h3>
                        </div>
                        <CreditCard class="w-5 h-5 text-slate-300" />
                    </div>
                    <div class="p-10">
                        <div class="h-72">
                            <canvas ref="revenueChartRef"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Secondary Intelligence Tables -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Website Usage Pulse -->
                    <div class="silk-card overflow-hidden flex flex-col">
                        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between flex-row-reverse">
                            <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-widest">أهداف البيانات الأكثر طلباً</h3>
                            <Globe class="w-4 h-4 text-slate-300" />
                        </div>
                        <div class="p-4 flex-1 divide-y divide-slate-50">
                            <div v-for="site in topWebsites" :key="site.domain" class="py-4 flex items-center justify-between flex-row-reverse hover:bg-slate-50/50 px-2 rounded-xl transition-all">
                                <div class="flex items-center gap-4 flex-row-reverse text-right">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-600 border border-slate-200">
                                        {{ site.domain.substring(0,2).toUpperCase() }}
                                    </div>
                                    <span class="text-[13px] font-bold text-slate-700 truncate max-w-[140px]">{{ site.domain }}</span>
                                </div>
                                <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg text-[10px] font-black">{{ site.total_hits }} طلب</span>
                            </div>
                            <div v-if="topWebsites.length === 0" class="py-16 text-center text-slate-300 text-[11px] font-bold uppercase tracking-[0.2em]">
                                بانتظار تحليل حركة المرور...
                            </div>
                        </div>
                    </div>

                    <!-- Critical Alert Registry -->
                    <div class="silk-card overflow-hidden flex flex-col border-rose-100">
                        <div class="px-6 py-4 border-b border-rose-50 flex items-center justify-between flex-row-reverse bg-rose-50/30">
                            <h3 class="text-[11px] font-black text-rose-900 uppercase tracking-widest">تنبيهات النظام الحرجة</h3>
                            <AlertTriangle class="w-4 h-4 text-rose-500" />
                        </div>
                        <div class="p-4 flex-1 divide-y divide-rose-50">
                            <div v-for="alert in activeAlerts" :key="alert.id" class="py-4 px-2 group hover:bg-rose-50/50 rounded-xl transition-all">
                                <p class="text-[13px] font-extrabold text-slate-900 leading-tight">{{ alert.message }}</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2 flex items-center justify-end gap-2">
                                    {{ new Date(alert.created_at).toLocaleTimeString('ar-SA') }} <Clock class="w-3 h-3 text-rose-300" />
                                </p>
                            </div>
                            <div v-if="activeAlerts.length === 0" class="py-16 text-center flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mb-4">
                                     <ShieldCheck class="w-8 h-8 text-emerald-500" />
                                </div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">العمليات مستقرة بالكامل</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tier 3: Sidebar Focus -->
            <div class="space-y-10">
                 <!-- Strategic Readiness -->
                 <div v-if="!setupComplete" class="silk-card border-amber-200/50 shadow-2xl shadow-amber-500/10 scale-[1.02] relative z-10">
                    <div class="px-8 py-6 bg-amber-50/50 border-b border-amber-100 flex justify-between items-center flex-row-reverse rounded-t-2xl">
                         <h3 class="text-xs font-black text-amber-900 uppercase tracking-widest">جاهزية التشغيل المؤسساتي</h3>
                         <Info class="w-5 h-5 text-amber-500" />
                    </div>
                    <div class="p-8 space-y-5">
                        <div v-for="(v, k) in setupChecklist" :key="k" class="flex items-center justify-between flex-row-reverse group">
                            <div class="flex items-center gap-4 flex-row-reverse">
                                <div :class="['w-6 h-6 rounded-lg flex items-center justify-center border-2', v ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-200 text-slate-200 group-hover:border-amber-400']">
                                     <CheckCircle2 v-if="v" class="w-4 h-4" />
                                     <div v-else class="w-1.5 h-1.5 bg-slate-200 rounded-full"></div>
                                </div>
                                <span class="text-[13px] font-bold" :class="v ? 'text-slate-900' : 'text-slate-400'">
                                    {{ 
                                        k === 'has_tower' ? 'تسجيل أبراج البث' : 
                                        k === 'has_server' ? 'ربط البوابة البرمجية' : 
                                        k === 'has_package' ? 'تحديد باقات الاشتراك' : 
                                        'تسجيل أول مشترك'
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                 </div>

                 <!-- Upcoming Expirations -->
                 <div class="silk-card flex-1 overflow-hidden border-indigo-100/50">
                    <div class="px-6 py-4 flex justify-between items-center flex-row-reverse bg-slate-50/50 border-b border-slate-100">
                         <h3 class="text-[11px] font-black text-indigo-900 uppercase tracking-widest">مشتركين بانتظار التجديد</h3>
                         <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></div>
                    </div>
                    <div class="p-4 divide-y divide-indigo-50">
                        <div v-for="client in expiringClients" :key="client.id" class="py-4 flex items-center justify-between flex-row-reverse hover:bg-indigo-50/30 px-2 rounded-xl transition-all">
                            <div class="text-right">
                                <p class="text-[13px] font-extrabold text-slate-900">{{ client.name }}</p>
                                <p class="text-[10px] font-black text-indigo-500 uppercase mt-1">الموعد: {{ client.expires_at }}</p>
                            </div>
                            <Link :href="route('crm.clients.renew', client.id)" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 shadow-lg shadow-indigo-600/20 active:scale-95 transition-all">تفعيل</Link>
                        </div>
                        <div v-if="expiringClients.length === 0" class="py-20 text-center text-slate-300 text-[10px] font-black uppercase tracking-widest">
                            لا توجد تجديدات وشيكة
                        </div>
                    </div>
                 </div>

                 <!-- Velocity Pulse Card -->
                  <div class="silk-card p-8 bg-slate-950 text-white relative overflow-hidden group">
                    <div class="absolute -top-20 -right-20 w-48 h-48 bg-indigo-500/20 rounded-full blur-[80px] group-hover:bg-indigo-500/40 transition-all duration-700"></div>
                    <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-emerald-500/10 rounded-full blur-[80px]"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8 flex-row-reverse">
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">نبض النمو (7 أيام)</p>
                             <TrendingUp class="w-4 h-4 text-emerald-500" />
                        </div>
                        <div class="h-32 mb-4">
                             <canvas ref="clientsChartRef"></canvas>
                        </div>
                        <p class="text-center text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">تحليل كثافة المشتركين</p>
                    </div>
                  </div>
            </div>
        </div>

        <!-- Tier 4: Operations Registry -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 text-right">
             <!-- Latest Financial Ledger -->
             <div class="silk-card overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center flex-row-reverse bg-slate-50/30">
                     <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-widest">مركز القيود المالية الحديثة</h3>
                     <Link :href="route('accounting.invoices.index')" class="text-indigo-600 text-[10px] font-black uppercase tracking-widest hover:text-slate-900 flex items-center gap-2 flex-row-reverse transition-all">
                        سجل العمليات <ArrowUpRight class="w-4 h-4" />
                     </Link>
                </div>
                <div class="overflow-x-auto no-scrollbar">
                    <table class="w-full text-right text-[13px]">
                        <thead>
                            <tr class="bg-indigo-500/5 text-slate-400">
                                <th class="px-8 py-5 font-black uppercase tracking-widest">الرقم المرجعي</th>
                                <th class="px-8 py-5 font-black uppercase tracking-widest">المستفيد</th>
                                <th class="px-8 py-5 font-black uppercase tracking-widest text-center">المبلغ</th>
                                <th class="px-8 py-5 font-black uppercase tracking-widest text-center">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="inv in recentInvoices" :key="inv.id" class="hover:bg-indigo-50/30 transition-all group">
                                <td class="px-8 py-5 font-black font-mono text-slate-900">#{{ inv.invoice_number }}</td>
                                <td class="px-8 py-5 font-bold text-slate-700 leading-none">
                                    {{ inv.client?.name || 'مخصص للنظام' }}
                                    <p class="text-[9px] text-slate-400 mt-1 font-black">{{ inv.client?.username || 'ROOT' }}</p>
                                </td>
                                <td class="px-8 py-5 font-extrabold text-indigo-600 text-center">{{ formatCurrency(inv.amount) }}</td>
                                <td class="px-8 py-5 text-center">
                                    <span :class="['px-3 py-1.5 rounded-xl text-[10px] font-black uppercase shadow-sm', inv.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100']">
                                        {{ inv.status === 'paid' ? 'تم التحصيل' : 'انتظار' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
             </div>

             <!-- Latest Client Intelligence -->
             <div class="silk-card overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center flex-row-reverse bg-slate-50/30">
                     <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-widest">سجل الانضمام الأخير</h3>
                     <Link :href="route('crm.clients.index')" class="text-indigo-600 text-[10px] font-black uppercase tracking-widest hover:text-slate-900 flex items-center gap-2 flex-row-reverse transition-all">
                        إدارة المشتركين <ArrowUpRight class="w-4 h-4" />
                     </Link>
                </div>
                <div class="divide-y divide-slate-50">
                    <div v-for="client in recentClients" :key="client.id" class="p-6 flex items-center justify-between flex-row-reverse group hover:bg-slate-50 transition-all duration-300">
                        <div class="flex items-center gap-5 flex-row-reverse">
                            <div class="w-12 h-12 rounded-2xl bg-slate-950 flex items-center justify-center text-white border border-white/5 shadow-xl group-hover:scale-110 group-hover:bg-indigo-600 transition-all">
                                <span class="font-black text-xs uppercase">{{ client.username.substring(0,2) }}</span>
                            </div>
                            <div>
                                <p class="text-[14px] font-extrabold text-slate-900 leading-tight">{{ client.name }}</p>
                                <p class="text-[10px] text-slate-400 font-black tracking-[0.1em] mt-1">{{ client.username }}</p>
                            </div>
                        </div>
                        <div class="px-4 py-1.5 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-600 tracking-tight shadow-sm">
                            {{ client.package?.name || 'باقة افتراضية' }}
                        </div>
                    </div>
                </div>
             </div>
        </div>

    </InstitutionalLayout>
</template>

<style scoped>
/* High-performance UI registry */
canvas {
    filter: drop-shadow(0 0 15px rgba(79, 70, 229, 0.05));
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}
</style>
