<script setup>
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    Users, 
    ArrowUpRight, 
    Activity, 
    CreditCard, 
    TowerControl, 
    Server,
    Zap,
    AlertCircle,
    Monitor,
    TrendingUp
} from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
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
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.15)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

        new Chart(revenueChartRef.value, {
            type: 'line',
            data: {
                labels: props.revenueChart?.labels || [],
                datasets: [{
                    label: 'PROTOCOL YIELD',
                    data: props.revenueChart?.data || [],
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        position: 'right',
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { font: { size: 10, weight: 'bold' } }
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
                }
            }
        });
    }
});

</script>

<template>
    <InstitutionalLayout title="لوحة القيادة المركزية">
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-vendor rounded-full"></span>
                        <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Infrastructure Management Node</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">مركز القيادة <span class="text-vendor">الذكي</span></h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80">Real-Time Network Telemetry & Performance Matrix</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="glass-card px-6 py-3 bg-white/40 flex items-center gap-4">
                        <Monitor class="w-5 h-5 text-slate-400" />
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">System Time</p>
                            <p class="text-xs font-black text-slate-700 font-inter">{{ new Date().toLocaleTimeString('ar-EG') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Radiant Metric Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Subscribers -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-vendor/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-vendor/10 rounded-2xl flex items-center justify-center text-vendor shadow-sm">
                            <Users class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest">
                            <TrendingUp class="w-3 h-3" />
                            +12%
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">المشتركين النشطين</p>
                    <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ stats.active_clients || 0 }}</h3>
                </div>

                <!-- Financial Flux -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-500 shadow-sm">
                            <CreditCard class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-inter">MONTHLY YIELD</div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">الإيرادات الحالية</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ stats.monthly_revenue?.toLocaleString() || 0 }}</h3>
                        <span class="text-[10px] font-black text-slate-400 italic font-inter">SAR</span>
                    </div>
                </div>

                <!-- Network Nodes -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-500 shadow-sm">
                            <TowerControl class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <span :class="['px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm', networkStats.offline > 0 ? 'bg-red-500 text-white' : 'bg-slate-900 text-vendor']">
                            {{ networkStats.offline > 0 ? 'ATTENTION' : 'STABLE' }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">البنية التحتية</p>
                    <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ stats.total_towers || 0 }} <span class="text-sm font-bold text-slate-300">/ {{ stats.total_routers || 0 }}</span></h3>
                </div>

                <!-- Data Flow -->
                <div class="glass-card p-8 bg-white/40 group relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-500 shadow-sm">
                            <Zap class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 font-inter">استهلاك البيانات</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter">4.2</h3>
                        <span class="text-[10px] font-black text-slate-400 italic font-inter uppercase tracking-widest">TB / DAY</span>
                    </div>
                </div>

            </div>

            <!-- Bento Operational Workspace -->
            <div class="grid grid-cols-12 gap-10">
                
                <!-- Performance Analytics Shell -->
                <div class="col-span-12 lg:col-span-8 glass-card p-10 bg-white/60">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                        <div>
                            <h2 class="text-xl font-black text-vendor uppercase tracking-widest italic decoration-vendor decoration-4 underline underline-offset-8">تحليل التدفق الإنتاجي</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-4 italic font-inter">Temporal Yield & Network Traffic Matrix</p>
                        </div>
                        <div class="flex items-center gap-2 p-1.5 bg-slate-50 border border-slate-200 rounded-2xl shadow-inner">
                            <button class="px-6 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-vendor/20 italic">REALTIME</button>
                            <button class="px-6 py-2 text-slate-400 hover:text-vendor rounded-xl text-[9px] font-black uppercase tracking-widest italic">HISTORICAL</button>
                        </div>
                    </div>
                    <div class="relative h-[400px]">
                        <canvas ref="revenueChartRef"></canvas>
                    </div>
                </div>

                <!-- Intelligence Alerts Panel -->
                <div class="col-span-12 lg:col-span-4 space-y-8">
                    <div class="glass-card p-10 bg-slate-900 text-white border-white/5 relative overflow-hidden min-h-[480px]">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-vendor/10 blur-3xl -mr-32 -mt-32 opacity-20"></div>
                        <div class="relative z-10">
                            <h2 class="text-sm font-black text-vendor uppercase tracking-[0.3em] mb-10 italic flex items-center gap-4">
                                <AlertCircle class="w-5 h-5" />
                                Protocol Alerts
                            </h2>
                            
                            <div class="space-y-6">
                                <div v-for="alert in activeAlerts" :key="alert.id" class="p-6 bg-white/5 border-r-4 border-red-500 rounded-2xl hover:bg-white/10 transition-all group">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-black text-red-400 text-[11px] uppercase italic tracking-tighter group-hover:scale-105 transition-transform origin-right">{{ alert.message }}</h4>
                                        <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest italic font-inter">{{ new Date(alert.created_at).toLocaleTimeString() }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 leading-relaxed font-bold italic">Critical Handshake Failure detected in operational node.</p>
                                </div>

                                <div v-if="activeAlerts.length === 0" class="text-center py-10 opacity-40">
                                    <Activity class="w-12 h-12 mx-auto mb-4 text-slate-500" />
                                    <p class="text-[10px] font-black uppercase tracking-widest">لا توجد تنبيهات نشطة</p>
                                </div>
                            </div>

                            <button v-if="activeAlerts.length > 0" class="w-full mt-12 py-4 bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-[0.3em] rounded-2xl flex items-center justify-center gap-3 hover:bg-white/10 transition-all italic">
                                Analyze All Telemetry Logs
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Transaction Registry -->
                <div class="col-span-12 glass-card overflow-hidden bg-white/40">
                    <div class="p-10 border-b border-white/40 flex flex-col md:flex-row justify-between items-center bg-slate-50/20">
                        <div>
                            <h2 class="text-xl font-black text-vendor uppercase tracking-widest italic">سجل العمليات السيادي</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3 italic font-inter">Verified Monetary Pulse & Identity Acquisition</p>
                        </div>
                        <Link :href="route('accounting.invoices.index')" class="px-10 py-4 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-vendor uppercase tracking-[0.3em] hover:bg-slate-900 hover:text-white transition-all shadow-sm italic">
                            Full Registry Explorer
                        </Link>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-vendor/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic font-inter">
                                    <th class="px-10 py-6">Beneficiary Identity</th>
                                    <th class="px-10 py-6 text-center">Protocol Yield</th>
                                    <th class="px-10 py-6 text-center">Execution Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/20">
                                <tr v-for="inv in recentInvoices" :key="inv.id" class="group hover:bg-white/60 transition-all duration-300">
                                    <td class="px-10 py-7">
                                        <div class="flex items-center gap-4">
                                            <div class="w-11 h-11 rounded-xl bg-slate-900 flex items-center justify-center text-xs font-black text-vendor uppercase shadow-lg">
                                                {{ inv.client?.name?.substring(0,1) || '?' }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">{{ inv.client?.name }}</p>
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1 font-inter italic opacity-60">Subscriber Instance</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-7 text-center">
                                        <div class="flex items-baseline justify-center gap-1.5">
                                            <span class="text-sm font-black text-vendor italic font-inter">{{ inv.amount }}</span>
                                            <span class="text-[9px] font-black text-slate-400 uppercase italic font-inter">SAR</span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-7 text-center">
                                        <div class="flex justify-center">
                                            <span :class="['px-5 py-2 rounded-full text-[9px] font-black uppercase italic shadow-sm', inv.status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-slate-100 text-slate-400 border border-slate-200']">
                                                {{ inv.status === 'paid' ? 'Verified Settlement' : 'Awaiting Protocol' }}
                                            </span>
                                        </div>
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

<style scoped>
.glass-card {
    @apply border border-white/40 shadow-glass rounded-[2.5rem] transition-all duration-500;
}
.glass-card:hover {
    @apply border-white/60 shadow-radiant -translate-y-1;
}
</style>
