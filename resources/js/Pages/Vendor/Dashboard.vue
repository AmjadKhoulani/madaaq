<script setup>
import { onMounted, ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    Users, 
    CreditCard, 
    HardDrive, 
    TowerControl as TowerIcon, 
    Package as PackageIcon,
    AlertTriangle,
    Activity,
    Globe,
    ExternalLink,
    Clock,
    CheckCircle2,
    XCircle,
    ArrowUpRight,
    TrendingUp,
    Zap,
    Info
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
    // Revenue Pulse (7 Days)
    new Chart(revenueChartRef.value, {
        type: 'line',
        data: {
            labels: props.revenueChart.labels,
            datasets: [{
                label: 'الإيرادات اليومية',
                data: props.revenueChart.data,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#2563eb',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // Clients Velocity (7 Days)
    new Chart(clientsChartRef.value, {
        type: 'bar',
        data: {
            labels: props.clientsChart.labels,
            datasets: [{
                label: 'نمو المشتركين',
                data: props.clientsChart.data,
                backgroundColor: '#0f172a',
                borderRadius: 4,
                barThickness: 12,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
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
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
};

</script>

<template>
    <InstitutionalLayout title="مركز إدارة الشبكة">
        <Head title="Vendor Dashboard" />

        <!-- Network Intelligence Pulse -->
        <div class="mb-10 flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="text-right order-2 lg:order-1">
                <h1 class="text-2xl font-black text-slate-900 mb-1">لوحة إدارة العمليات</h1>
                <p class="text-sm text-slate-500 font-medium">مراقبة حية لأداء السيرفرات، المشتركين، وحركة البيانات</p>
            </div>
            
            <div class="flex items-center gap-4 order-1 lg:order-2">
                 <div class="ent-card px-6 py-3 flex items-center gap-4 border-r-4 border-r-emerald-500 shadow-sm">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">حالة الشبكة</p>
                        <p class="text-sm font-bold text-slate-900">{{ networkStats.online }} متصل / {{ networkStats.total }} إجمالي</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <Zap class="w-5 h-5" :class="networkStats.online > 0 ? 'animate-pulse' : ''" />
                    </div>
                 </div>

                 <div v-if="!setupComplete" class="ent-card px-6 py-3 flex items-center gap-4 border-r-4 border-r-amber-500">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">إكمال الإعداد</p>
                        <p class="text-sm font-bold text-slate-900">تنبيه: خطوات مطلوبة</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                        <AlertTriangle class="w-5 h-5 animate-bounce" />
                    </div>
                 </div>
            </div>
        </div>

        <!-- Strategic KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 text-right">
             <!-- Clients -->
             <div class="ent-card p-6">
                 <div class="flex items-center justify-between mb-4 flex-row-reverse">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <Users class="w-5 h-5" />
                    </div>
                 </div>
                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">المشتركين النشطين</p>
                 <div class="text-2xl font-black text-slate-900">{{ stats.active_clients }}</div>
                 <div class="flex items-center gap-2 mt-2 flex-row-reverse text-[10px] font-bold">
                    <span class="text-slate-400">{{ stats.total_clients }} إجمالي</span>
                    <span class="text-rose-500 bg-rose-50 px-2 py-0.5 rounded border border-rose-100 flex items-center gap-1">
                        {{ stats.inactive_clients }} متوقف
                    </span>
                 </div>
             </div>

             <!-- Revenue -->
              <div class="ent-card p-6">
                 <div class="flex items-center justify-between mb-4 flex-row-reverse">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <CreditCard class="w-5 h-5" />
                    </div>
                 </div>
                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">إيرادات الشهر</p>
                 <div class="text-2xl font-black text-slate-900">{{ formatCurrency(stats.monthly_revenue) }}</div>
                 <p class="text-[10px] text-amber-600 font-bold mt-2 flex items-center justify-end gap-1">
                    {{ formatCurrency(stats.pending_amount) }} مستحقات معلقة <Clock class="w-3 h-3" />
                 </p>
             </div>

             <!-- Network Capacity -->
              <div class="ent-card p-6">
                 <div class="flex items-center justify-between mb-4 flex-row-reverse">
                    <div class="w-10 h-10 rounded-lg bg-slate-900 text-white flex items-center justify-center">
                        <HardDrive class="w-5 h-5" />
                    </div>
                 </div>
                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">تجهيزات الشبكة</p>
                 <div class="text-2xl font-black text-slate-900">{{ stats.total_routers }} <span class="text-sm font-medium text-slate-400">سيرفر</span></div>
                 <div class="flex items-center gap-2 mt-2 flex-row-reverse text-[10px] font-bold text-slate-500">
                    <TowerIcon class="w-3.5 h-3.5" /> {{ stats.total_towers }} برج إرسال
                 </div>
             </div>

             <!-- Bandwidth -->
              <div class="ent-card p-6">
                 <div class="flex items-center justify-between mb-4 flex-row-reverse">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <Activity class="w-5 h-5" />
                    </div>
                 </div>
                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">ترافيك اليوم</p>
                 <div class="text-2xl font-black text-slate-900">{{ formatBytes(bandwidthToday?.total_rx || 0) }}</div>
                 <p class="text-[10px] text-indigo-600 font-bold mt-2 flex items-center justify-end gap-1">
                    إجمالي التحميل والرفع <TrendingUp class="w-3 h-3" />
                 </p>
             </div>
        </div>

        <!-- Intelligence Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <!-- Main Analysis Charts -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Revenue Flow -->
                <div class="ent-card overflow-hidden">
                    <div class="ent-card-header flex items-center justify-between flex-row-reverse">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">مخطط التحصيل المالي (7 أيام)</h3>
                        <CreditCard class="w-4 h-4 text-slate-400" />
                    </div>
                    <div class="p-8">
                        <div class="h-64">
                            <canvas ref="revenueChartRef"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Strategic Tables -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-right">
                    <!-- Top Websites -->
                    <div class="ent-card overflow-hidden flex flex-col">
                        <div class="ent-card-header flex items-center justify-between flex-row-reverse">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">أكثر المواقع زيارة</h3>
                            <Globe class="w-4 h-4 text-slate-400" />
                        </div>
                        <div class="p-4 flex-1 divide-y divide-slate-100">
                            <div v-for="site in topWebsites" :key="site.domain" class="py-3 flex items-center justify-between flex-row-reverse">
                                <div class="flex items-center gap-3 flex-row-reverse">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-900">
                                        {{ site.domain.substring(0,2).toUpperCase() }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 truncate max-w-[120px]">{{ site.domain }}</span>
                                </div>
                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-[10px] font-black leading-none">{{ site.total_hits }} طلب</span>
                            </div>
                            <div v-if="topWebsites.length === 0" class="py-12 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                                لا توجد بيانات للتحليل
                            </div>
                        </div>
                    </div>

                    <!-- Alerts -->
                    <div class="ent-card overflow-hidden flex flex-col border-rose-100">
                        <div class="ent-card-header flex items-center justify-between flex-row-reverse bg-rose-50/30">
                            <h3 class="text-xs font-black text-rose-900 uppercase tracking-widest">التنبيهات البرمجية</h3>
                            <AlertTriangle class="w-4 h-4 text-rose-500" />
                        </div>
                        <div class="p-4 flex-1 divide-y divide-rose-50">
                            <div v-for="alert in activeAlerts" :key="alert.id" class="py-3 group">
                                <p class="text-xs font-bold text-slate-900 leading-tight">{{ alert.message }}</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ new Date(alert.created_at).toLocaleTimeString() }}</p>
                            </div>
                            <div v-if="activeAlerts.length === 0" class="py-12 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                                <CheckCircle2 class="w-8 h-8 mx-auto mb-2 text-emerald-500 opacity-50" />
                                الشبكة مستقرة بالكامل
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Insights (Checklist & Expiring) -->
            <div class="space-y-8">
                 <!-- Onboarding Checklist -->
                 <div v-if="!setupComplete" class="ent-card border-amber-200 shadow-xl shadow-amber-500/5">
                    <div class="ent-card-header bg-amber-50/50 flex justify-between items-center flex-row-reverse">
                         <h3 class="text-xs font-black text-amber-900 uppercase tracking-widest">إكمال تهيئة المنصة</h3>
                         <Info class="w-4 h-4 text-amber-500" />
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-for="(v, k) in setupChecklist" :key="k" class="flex items-center justify-between flex-row-reverse py-1">
                            <div class="flex items-center gap-3 flex-row-reverse">
                                <CheckCircle2 v-if="v" class="w-4 h-4 text-emerald-500" />
                                <XCircle v-else class="w-4 h-4 text-slate-300" />
                                <span class="text-[11px] font-bold" :class="v ? 'text-slate-900' : 'text-slate-400'">
                                    {{ 
                                        k === 'has_tower' ? 'إضافة أول برج إرسال' : 
                                        k === 'has_server' ? 'ربط سيرفر مايكروتك' : 
                                        k === 'has_package' ? 'إنشاء باقات اشتراك' : 
                                        'إضافة أول مشترك'
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                 </div>

                 <!-- Expiring Soon -->
                 <div class="ent-card flex-1 overflow-hidden border-indigo-100">
                    <div class="ent-card-header flex justify-between items-center flex-row-reverse bg-indigo-50/30">
                         <h3 class="text-xs font-black text-indigo-900 uppercase tracking-widest">اشتراكات تنتهي قريباً</h3>
                         <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                    </div>
                    <div class="p-4 divide-y divide-indigo-50">
                        <div v-for="client in expiringClients" :key="client.id" class="py-3 flex items-center justify-between flex-row-reverse">
                            <div class="text-right">
                                <p class="text-xs font-bold text-slate-900">{{ client.name }}</p>
                                <p class="text-[9px] font-black text-indigo-500 uppercase">ينتهي في: {{ client.expires_at }}</p>
                            </div>
                            <Link :href="route('crm.clients.renew', client.id)" class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700">تجديد</Link>
                        </div>
                        <div v-if="expiringClients.length === 0" class="py-12 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">
                            لا توجد اشتراكات تنتهي قريباً
                        </div>
                    </div>
                 </div>

                 <!-- Growth Pulse -->
                  <div class="ent-card p-6 bg-slate-900 text-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">سرعة نمو المشتركين</p>
                        <div class="h-32">
                             <canvas ref="clientsChartRef"></canvas>
                        </div>
                    </div>
                  </div>
            </div>
        </div>

        <!-- Operations Registry (Tables) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-right">
             <!-- Recent Invoices -->
             <div class="ent-card overflow-hidden">
                <div class="ent-card-header flex justify-between items-center flex-row-reverse">
                     <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">أحدث العمليات المالية</h3>
                     <Link :href="route('accounting.invoices.index')" class="text-blue-600 text-[10px] font-black uppercase tracking-widest hover:underline flex items-center gap-1 flex-row-reverse">
                        سجل الفواتير <ArrowUpRight class="w-3 h-3" />
                     </Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 border-b border-slate-200">
                                <th class="px-6 py-4 font-black uppercase tracking-widest">الفاتورة</th>
                                <th class="px-6 py-4 font-black uppercase tracking-widest">المشترك</th>
                                <th class="px-6 py-4 font-black uppercase tracking-widest">المبلغ</th>
                                <th class="px-6 py-4 font-black uppercase tracking-widest text-center">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="inv in recentInvoices" :key="inv.id" class="hover:bg-slate-50 transition-none group">
                                <td class="px-6 py-4 font-bold font-mono text-slate-900">#{{ inv.invoice_number }}</td>
                                <td class="px-6 py-4 font-bold text-slate-700">{{ inv.client?.name || 'مستخدم عام' }}</td>
                                <td class="px-6 py-4 font-black text-blue-600">{{ formatCurrency(inv.amount) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span :class="['px-2 py-0.5 rounded text-[9px] font-black uppercase border', inv.status === 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-amber-50 text-amber-600 border-amber-200']">
                                        {{ inv.status === 'paid' ? 'مدفوعة' : 'معلقة' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
             </div>

             <!-- Recent Clients -->
             <div class="ent-card overflow-hidden">
                <div class="ent-card-header flex justify-between items-center flex-row-reverse">
                     <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">المشتركين المضافين حديثاً</h3>
                     <Link :href="route('crm.clients.index')" class="text-blue-600 text-[10px] font-black uppercase tracking-widest hover:underline flex items-center gap-1 flex-row-reverse">
                        إدارة المشتركين <ArrowUpRight class="w-3 h-3" />
                     </Link>
                </div>
                <div class="divide-y divide-slate-100">
                    <div v-for="client in recentClients" :key="client.id" class="p-4 flex items-center justify-between flex-row-reverse">
                        <div class="flex items-center gap-3 flex-row-reverse">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs uppercase">
                                {{ client.username.substring(0,2) }}
                            </div>
                            <div>
                                <p class="text-[12px] font-bold text-slate-900">{{ client.name }}</p>
                                <p class="text-[10px] text-slate-400 font-mono">{{ client.username }}</p>
                            </div>
                        </div>
                        <div class="px-3 py-1 bg-slate-50 border border-slate-100 rounded text-[9px] font-black text-slate-600 uppercase">
                            {{ client.package?.name || 'باقة افتراضية' }}
                        </div>
                    </div>
                </div>
             </div>
        </div>

    </InstitutionalLayout>
</template>

<style scoped>
/* Disable animations for max efficiency */
* {
    transition: none !important;
}

/* Custom Registry styling */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
</style>
