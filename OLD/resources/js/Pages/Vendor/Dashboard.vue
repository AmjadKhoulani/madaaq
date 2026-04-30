<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    Activity,
    AlertCircle,
    ArrowUpRight,
    CreditCard,
    Users,
    Monitor,
    Server,
    TowerControl,
    TrendingUp,
    Zap,
    ChevronLeft
} from 'lucide-vue-next';
import Chart from 'chart.js/auto';

const props = defineProps({
    stats: Object,
    networkStats: Object,
    activeAlerts: Array,
    recentInvoices: Array,
    recentClients: Array,
    revenueChart: Object,
    clientsChart: Object
});

const revenueChartRef = ref(null);

onMounted(() => {
    if (revenueChartRef.value) {
        const ctx = revenueChartRef.value.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 97, 242, 0.1)');
        gradient.addColorStop(1, 'rgba(0, 97, 242, 0)');

        new Chart(revenueChartRef.value, {
            type: 'line',
            data: {
                labels: props.revenueChart?.labels || [],
                datasets: [{
                    label: 'الإيرادات',
                    data: props.revenueChart?.data || [],
                    borderColor: '#0061f2',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHitRadius: 30,
                    pointBackgroundColor: '#0061f2',
                    pointBorderWidth: 4,
                    pointBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1f2d41',
                        padding: 12,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        position: 'right',
                        grid: { color: '#f1f5f9' },
                        border: { display: false },
                        ticks: { 
                            font: { size: 10, weight: 'bold' },
                            color: '#94a3b8',
                            callback: (value) => value.toLocaleString()
                        }
                    },
                    x: { 
                        grid: { display: false }, 
                        border: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' } 
                    }
                }
            }
        });
    }
});

</script>

<template>
    <InstitutionalLayout title="لوحة التحكم">
        <div class="space-y-8 pb-20">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">نظرة عامة على النظام</h1>
                    <p class="text-slate-400 font-bold text-xs mt-1">مراقبة الأداء المالي والتقني للشبكة في الوقت الفعلي</p>
                </div>
                <div class="flex items-center gap-3">
                     <div class="px-5 py-2 bg-white border border-slate-200 rounded-xl flex items-center gap-3">
                         <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                         <span class="text-xs font-bold text-slate-600">النظام مستقر</span>
                     </div>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Financial Card -->
                <div class="clean-card p-8 group overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-primary border border-blue-200/50 shadow-sm">
                                <CreditCard class="w-6 h-6" />
                            </div>
                            <div class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[10px] font-black flex items-center gap-1">
                                <TrendingUp class="w-3 h-3" />
                                +15%
                            </div>
                        </div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">الأرباح الشهرية</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-black text-slate-900 tracking-tighter">{{ stats.monthly_revenue?.toLocaleString() || 0 }}</h3>
                            <span class="text-[10px] font-black text-slate-400">SAR</span>
                        </div>
                    </div>
                </div>

                <!-- Subscribers Card -->
                <div class="clean-card p-8 group overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-600 border border-slate-200/50 shadow-sm">
                                <Users class="w-6 h-6" />
                            </div>
                            <div class="w-12 h-1 bg-slate-100 rounded-full mt-5 relative overflow-hidden">
                                <div class="absolute inset-0 bg-primary w-[70%] rounded-full"></div>
                            </div>
                        </div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">إجمالي المشتركين</p>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tighter">{{ stats.active_clients || 0 }}</h3>
                    </div>
                </div>

                <!-- Network Nodes Card -->
                <div class="clean-card p-8 group overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 border border-indigo-200/50 shadow-sm">
                                <TowerControl class="w-6 h-6" />
                            </div>
                            <div class="px-3 py-1 rounded-full text-[10px] font-black uppercase" :class="networkStats.offline > 0 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600'">
                                {{ networkStats.offline > 0 ? 'يوجد أعطال' : 'مستقر' }}
                            </div>
                        </div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">الأبراج والقطاعات</p>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tighter">{{ stats.total_towers || 0 }} <span class="text-sm font-bold text-slate-300">/ {{ stats.total_routers || 0 }}</span></h3>
                    </div>
                </div>

                <!-- Data Usage Card -->
                <div class="clean-card p-8 group overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 border border-amber-200/50 shadow-sm">
                                <Zap class="w-6 h-6" />
                            </div>
                            <div class="flex gap-1">
                                <div class="w-1 h-4 bg-primary/20 rounded-full"></div>
                                <div class="w-1 h-6 bg-primary rounded-full"></div>
                                <div class="w-1 h-3 bg-primary/40 rounded-full"></div>
                            </div>
                        </div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">حجم استهلاك البيانات</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-black text-slate-900 tracking-tighter">4.2</h3>
                            <span class="text-[10px] font-black text-slate-400">TB / Day</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Activity Section -->
            <div class="grid grid-cols-12 gap-8">
                
                <!-- Financial Line Chart -->
                <div class="col-span-12 lg:col-span-8 clean-card p-10">
                    <div class="flex items-center justify-between mb-12">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 italic">الأرباح والنمو المالي</h3>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest italic">Financial Yield Performance Analytics</p>
                        </div>
                        <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-200">
                             <button class="px-6 py-2 bg-white text-primary text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm">الشهري</button>
                             <button class="px-6 py-2 text-slate-400 hover:text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-lg">السنوي</button>
                        </div>
                    </div>
                    <div class="h-[350px]">
                        <canvas ref="revenueChartRef"></canvas>
                    </div>
                </div>

                <!-- Active Alerts -->
                <div class="col-span-12 lg:col-span-4 clean-card bg-slate-900 border-none p-10 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-primary/10 animate-pulse pointer-events-none opacity-20"></div>
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-10 flex items-center gap-3 relative z-10 italic">
                        <AlertCircle class="w-5 h-5" />
                        تنبيهات البروتوكول
                    </h3>
                    
                    <div class="space-y-6 relative z-10">
                        <div v-for="alert in activeAlerts.slice(0, 4)" :key="alert.id" class="p-6 bg-white/5 border-r-4 border-rose-500 rounded-2xl hover:bg-white/10 transition-all cursor-pointer">
                            <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1 italic">{{ alert.message }}</p>
                            <p class="text-xs font-bold text-slate-400 italic">تم رصد فشل في المصافحة التقنية في العقدة الفرعية</p>
                            <div class="mt-3 flex justify-between items-center opacity-40">
                                <span class="text-[9px] font-bold italic">{{ new Date(alert.created_at).toLocaleTimeString() }}</span>
                                <ChevronLeft class="w-4 h-4" />
                            </div>
                        </div>

                        <div v-if="activeAlerts.length === 0" class="py-20 text-center opacity-30">
                            <Activity class="w-12 h-12 mx-auto mb-4" />
                            <p class="text-[11px] font-black uppercase tracking-widest">جميع الأنظمة تعمل بكفاءة</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity/Invoices Table -->
                <div class="col-span-12 clean-card">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                         <h3 class="text-lg font-black text-slate-900 italic">آخر العمليات المالية</h3>
                         <Link :href="route('accounting.invoices.index')" class="text-[11px] font-black text-primary uppercase tracking-widest hover:underline italic">عرض كافة الفواتير</Link>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table-clean">
                            <thead>
                                <tr>
                                    <th class="text-right">المشترك</th>
                                    <th class="text-center">المبلغ</th>
                                    <th class="text-center">الحالة</th>
                                    <th class="text-left">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="inv in recentInvoices" :key="inv.id" class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-slate-900 text-primary flex items-center justify-center font-black text-xs border border-white/10 shadow-sm">
                                                {{ inv.client?.name?.substring(0,1) || '?' }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-800">{{ inv.client?.name }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-tighter">INV-{{ inv.id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="text-sm font-black text-slate-700">{{ inv.amount }} <span class="text-[10px] font-black text-slate-400">SAR</span></span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span :class="['px-4 py-1 rounded-full text-[10px] font-black uppercase', inv.status === 'paid' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-400 border border-slate-200']">
                                            {{ inv.status === 'paid' ? 'مدفوعة' : 'بانتظار الدفع' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-left text-[11px] font-bold text-slate-400 italic">
                                        {{ new Date(inv.created_at).toLocaleDateString() }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>
