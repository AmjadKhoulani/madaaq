<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { Chart, registerables } from 'chart.js';
import { 
    LayoutDashboard, 
    Zap, 
    Users, 
    CreditCard, 
    BarChart3, 
    ShieldCheck, 
    Activity, 
    Globe, 
    ArrowUpRight, 
    PlusCircle,
    Calendar,
    TrendingUp,
    Building2
} from 'lucide-vue-next';

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
    // Strategic Revenue Intelligence Chart
    if (revenueChartRef.value) {
        const ctx = revenueChartRef.value.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(124, 58, 237, 0.15)');
        gradient.addColorStop(1, 'rgba(124, 58, 237, 0)');

        new Chart(revenueChartRef.value, {
            type: 'line',
            data: {
                labels: props.months,
                datasets: [{
                    label: 'إيرادات المصفوفة',
                    data: props.chart_revenue,
                    borderColor: '#7c3aed',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 3,
                    pointBorderColor: '#7c3aed'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        position: 'right',
                        grid: { color: 'rgba(0,0,0,0.03)' },
                        ticks: { color: '#94a3b8', font: { size: 11, weight: 'bold' } }
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { color: '#94a3b8', font: { size: 11, weight: 'bold' } } 
                    }
                }
            }
        });
    }

    // Subscriber Acquisition Growth Chart
    if (tenantsChartRef.value) {
        new Chart(tenantsChartRef.value, {
            type: 'bar',
            data: {
                labels: props.months,
                datasets: [{
                    label: 'النمو السيادي',
                    data: props.chart_tenants,
                    backgroundColor: '#7c3aed',
                    borderRadius: 8,
                    barThickness: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        display: false,
                        grid: { display: false }
                    },
                    x: { 
                        display: false,
                        grid: { display: false } 
                    }
                }
            }
        });
    }
});

const formatNumber = (num) => {
    return new Intl.NumberFormat('ar-SY').format(num);
};

</script>

<template>
    <InstitutionalLayout title="مركز القيادة السيادي">
        <Head title="لوحة التحكم الإستراتيجية - MadaaQ" />
        
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700" dir="rtl">
            
            <!-- Strategic Header Intel -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-admin rounded-full"></span>
                        <p class="text-[10px] font-black text-admin uppercase tracking-[0.3em] font-inter">Sovereign Control Node</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">مركز <span class="text-admin">القيادة</span> السيادي</h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">Global Infrastructure Matrix & Fiscal Intelligence Oversight</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <button class="px-6 py-3.5 bg-white border border-white/60 rounded-2xl text-[10px] font-black text-slate-500 hover:text-admin transition-all flex items-center gap-3 shadow-sm active:scale-95 group">
                        <Calendar class="w-4 h-4 group-hover:rotate-12 transition-transform" />
                        تقارير الجلسة اللحظية
                    </button>
                    <Link 
                        :href="route('admin.tenants.index')" 
                        class="btn-radiant btn-admin px-8 py-3.5 text-[10px] font-black uppercase tracking-[0.2em] flex items-center gap-3"
                    >
                        <PlusCircle class="w-4 h-4" />
                        تفعيل مصفوفة شريك
                    </Link>
                </div>
            </div>

            <!-- Strategic KPIs Matrix -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Revenue Intelligence -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-admin/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-admin/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 bg-admin/10 rounded-2xl flex items-center justify-center text-admin shadow-sm">
                            <CreditCard class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                            <TrendingUp class="w-3 h-3" />
                            +14.2%
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">إجمالي الإيرادات العالمية</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ formatNumber(stats.total_revenue) }}</h3>
                        <span class="text-[10px] font-black text-slate-400 italic font-inter">SAR</span>
                    </div>
                </div>

                <!-- Network Grid Operators -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-slate-900/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-900/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg">
                            <Globe class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-inter">ACTIVE NODES</div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">مشغلي الشبكة النشطين</p>
                    <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ stats.active_tenants }} <span class="text-sm font-bold text-slate-300">/ {{ stats.total_tenants }}</span></h3>
                </div>

                <!-- Subscription Integrity -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-emerald-500/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                            <ShieldCheck class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-radiant"></div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">الاشتراكات الفعالة</p>
                    <h3 class="text-4xl font-black text-emerald-600 italic tracking-tighter">{{ stats.active_subscriptions }}</h3>
                </div>

                <!-- Aggregate Clients -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden border-b-4 border-indigo-500/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-8">
                        <div class="w-12 h-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                            <Users class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-inter">TOTAL IDENTITY</div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">قاعدة المشتركين المركزية</p>
                    <h3 class="text-4xl font-black text-indigo-600 italic tracking-tighter">{{ formatNumber(stats.total_clients) }}</h3>
                </div>

            </div>

            <!-- Analytical Insights -->
            <div class="grid grid-cols-12 gap-10">
                
                <!-- Revenue BarChart3 Shell -->
                <div class="col-span-12 lg:col-span-8 glass-card p-10 bg-white/60">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                        <div>
                            <h2 class="text-2xl font-black text-admin uppercase tracking-tighter italic">تحليل الأداء المالي العالمي</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-2 italic font-inter opacity-70">Strategic Yield & Fiscal Growth Matrix</p>
                        </div>
                        <div class="flex items-center gap-3 p-1.5 bg-slate-950/5 rounded-2xl border border-white/60 shadow-inner">
                            <button class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl">Real-Time</button>
                            <button class="px-6 py-2.5 text-slate-400 hover:text-admin rounded-xl text-[9px] font-black uppercase tracking-widest italic">Temporal</button>
                        </div>
                    </div>
                    <div class="relative h-[400px]">
                        <canvas ref="revenueChartRef"></canvas>
                    </div>
                </div>

                <!-- Strategic Network Status -->
                <div class="col-span-12 lg:col-span-4 glass-card p-10 bg-slate-900 text-white border-none relative overflow-hidden flex flex-col">
                    <div class="absolute -top-32 -left-32 w-80 h-80 bg-admin/10 rounded-full blur-[100px] opacity-40"></div>
                    
                    <div class="relative z-10 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-12">
                            <h3 class="text-xs font-black text-admin uppercase tracking-[0.3em] italic font-inter">Node Readiness (NOC)</h3>
                            <Activity class="w-5 h-5 text-admin animate-pulse" />
                        </div>

                        <div class="space-y-12 flex-1">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-[0.3em] italic">
                                    <span class="text-emerald-400">Global Service Quality</span>
                                    <span class="font-inter text-lg tracking-tighter text-emerald-400">98.4%</span>
                                </div>
                                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_20px_rgba(16,185,129,0.4)]" style="width: 98.4%"></div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-[0.3em] italic">
                                    <span class="text-admin">Aggregate Bandwidth</span>
                                    <span class="font-inter text-lg tracking-tighter text-admin">64%</span>
                                </div>
                                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-admin rounded-full shadow-[0_0_20px_rgba(124,58,237,0.4)]" style="width: 64%"></div>
                                </div>
                            </div>

                            <div class="pt-10 mt-10 border-t border-white/5 h-48">
                                <div class="flex items-center justify-between mb-6">
                                    <p class="text-[9px] font-black text-white/20 uppercase tracking-[0.4em] italic font-inter">Subscriber Acquisition Gradient</p>
                                    <div class="w-1.5 h-1.5 bg-admin rounded-full animate-ping"></div>
                                </div>
                                <canvas ref="tenantsChartRef"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Global Transaction Ledger -->
                <div class="col-span-12 glass-card overflow-hidden bg-white/40 border-white/60">
                    <div class="p-10 border-b border-white/40 flex flex-col md:flex-row justify-between items-center bg-slate-50/20">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 italic tracking-tighter uppercase">سجل العمليات السيادي العالمي</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-2 italic font-inter">Verified Monetary Pulse & Partner Matrix Registry</p>
                        </div>
                        <Link :href="route('admin.subscriptions.index')" class="px-10 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-admin transition-all shadow-xl italic flex items-center gap-3 group">
                            Full Registry Oversight
                            <ArrowUpRight class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" />
                        </Link>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                                    <th class="px-10 py-8">هوية الشريك المستفيد</th>
                                    <th class="px-8 py-8 text-center">أفق الإصدار</th>
                                    <th class="px-8 py-8 text-center">القيمة الاستحقاقية</th>
                                    <th class="px-8 py-8 text-center">وضعية التسوية</th>
                                    <th class="px-10 py-8"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/20">
                                <tr v-for="invoice in recent_invoices" :key="invoice.id" class="group hover:bg-white/60 transition-all duration-500">
                                    <td class="px-10 py-7">
                                        <div class="flex items-center gap-5 justify-end">
                                            <div class="text-right">
                                                <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase font-inter">{{ invoice.tenant?.name || 'Central Entity' }}</h4>
                                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic font-inter opacity-60">{{ invoice.invoice_number }}</p>
                                            </div>
                                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-admin flex items-center justify-center shadow-xl group-hover:rotate-6 transition-transform border border-white/10">
                                                <Building2 class="w-5 h-5" />
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-7 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="text-[13px] font-black text-slate-700 italic font-inter">{{ new Date().toLocaleDateString() }}</span>
                                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1 font-inter">Fiscal Horizon</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-7 text-center">
                                        <div class="flex items-baseline justify-center gap-1.5">
                                            <span class="text-xl font-black text-admin italic font-inter">{{ formatNumber(invoice.amount) }}</span>
                                            <span class="text-[9px] font-black text-slate-400 uppercase italic font-inter">SAR</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-7 text-center">
                                        <div class="flex justify-center">
                                            <span :class="['px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all shadow-sm', invoice.status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20' : 'bg-slate-100 text-slate-400 border border-slate-200']">
                                                {{ invoice.status === 'paid' ? 'Verified Settlement' : 'Awaiting Protocol' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-7"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.glass-card {
    @apply border border-white/40 shadow-glass rounded-[2.5rem] transition-all duration-500;
}
.glass-card:hover {
    @apply border-white/60 shadow-radiant -translate-y-1;
}
</style>
