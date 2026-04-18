<script setup>
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
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

onMounted(() => {
    const ctx = document.getElementById('trafficChart');
    if (ctx) {
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(0, 229, 255, 0.15)');
        gradient.addColorStop(1, 'rgba(124, 77, 255, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: props.revenueChart?.labels || ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '23:59'],
                datasets: [{
                    label: 'PROTOCOL YIELD',
                    data: props.revenueChart?.data || [28, 35, 42, 38, 45, 40, 32],
                    borderColor: '#00e5ff',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.5,
                    borderWidth: 4,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#00e5ff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8,
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
                        backgroundColor: '#0f172a',
                        titleColor: '#00e5ff',
                        titleFont: { family: 'IBM Plex Sans Arabic', weight: 'bold' },
                        bodyFont: { family: 'Manrope', weight: 'bold' },
                        padding: 15,
                        cornerRadius: 12
                    }
                },
                scales: {
                    y: {
                        position: 'right',
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false },
                        ticks: { font: { family: 'Manrope', size: 10, weight: '800' }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Manrope', size: 10, weight: '800' }, color: '#94a3b8' }
                    }
                }
            }
        });
    }
});
</script>

<template>
    <InstitutionalLayout title="لوحة القيادة | Command Hub">
        <div class="space-y-12 pb-24">
            
            <!-- Radiant Hub Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-12 h-1 bg-accent-flow rounded-full"></span>
                        <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Operational Architect Node</p>
                    </div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">نظرة عامة على المنظومة</h2>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] font-headline opacity-80 italic">Real-Time Infrastructure Telemetry & Commercial Growth Analytics</p>
                </div>
                
                <div class="flex gap-4">
                    <button class="px-8 py-3.5 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
                        <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform duration-500">sync</span>
                        تزامن الأنظمة الشامل
                    </button>
                </div>
            </div>

            <!-- Radiant Metric Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Clients Metric -->
                <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-primary/20">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm border border-primary/10">
                            <span class="material-symbols-outlined">group</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-full text-[9px] font-black uppercase italic border border-emerald-500/10">
                            <span class="material-symbols-outlined text-[12px]">trending_up</span>
                            12%
                        </div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">العملاء النشطين</p>
                    <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter">{{ stats.active_clients || 0 }}</h3>
                </div>

                <!-- Revenue Metric -->
                <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-neon-cyan/20">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-neon-cyan/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-neon-cyan/10 rounded-2xl flex items-center justify-center text-neon-cyan shadow-sm border border-neon-cyan/10">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">YIELD FLUX</div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">إيرادات الشهر الحالي</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter">{{ stats.monthly_revenue || 0 }}</h3>
                        <span class="text-[10px] font-black text-slate-400 italic">SAR</span>
                    </div>
                </div>

                <!-- Network Metric -->
                <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group border-b-4 border-vibrant-purple/20">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-vibrant-purple/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-vibrant-purple/10 rounded-2xl flex items-center justify-center text-vibrant-purple shadow-sm border border-vibrant-purple/10">
                            <span class="material-symbols-outlined">cell_tower</span>
                        </div>
                        <span :class="['px-3 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest italic shadow-sm', networkStats.offline > 0 ? 'bg-error text-white shadow-glow-purple' : 'bg-slate-900 text-neon-cyan shadow-glow-cyan']">
                            {{ networkStats.offline > 0 ? 'Protocol Loss' : 'STABLE' }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">إجمالي الأبراج</p>
                    <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter">{{ stats.total_towers || 0 }}</h3>
                </div>

                <!-- Tickets Metric -->
                <div class="glass-card p-8 rounded-[2rem] relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm border border-primary/10">
                            <span class="material-symbols-outlined">confirmation_number</span>
                        </div>
                        <div class="w-3 h-3 bg-error rounded-full animate-pulse shadow-glow-purple"></div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">تذاكر الدعم المفتوحة</p>
                    <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter">24</h3>
                </div>
            </div>

            <!-- Radiant Bento Matrix -->
            <div class="grid grid-cols-12 gap-10">
                <!-- Large Analytics Shell -->
                <div class="col-span-12 lg:col-span-8 glass-panel p-10 rounded-[2.5rem] !bg-white/80">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                        <div>
                            <h2 class="text-xl font-black text-primary uppercase tracking-widest italic flex items-center gap-4 decoration-accent-flow decoration-4 underline underline-offset-4">تحليل التدفق الإنتاجي</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3 italic">Temporal Yield & Network Traffic Assimilation Matrix</p>
                        </div>
                        <div class="flex items-center gap-2 p-1.5 bg-slate-50 border border-slate-200 rounded-2xl shadow-inner">
                            <button class="px-6 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-glow-purple italic">REALTIME</button>
                            <button class="px-6 py-2 text-slate-400 hover:text-primary rounded-xl text-[9px] font-black uppercase tracking-widest italic">HISTORICAL</button>
                        </div>
                    </div>
                    <div class="relative h-[380px]">
                        <canvas id="trafficChart"></canvas>
                    </div>
                </div>

                <!-- Intelligent Status Column -->
                <div class="col-span-12 lg:col-span-4 space-y-8">
                    <div class="glass-panel p-10 rounded-[2.5rem] !bg-slate-900 text-white border-white/5 relative overflow-hidden min-h-[480px]">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-accent-gradient opacity-10 blur-3xl -mr-32 -mt-32"></div>
                        <div class="relative z-10">
                            <h2 class="text-sm font-black text-neon-cyan uppercase tracking-[0.3em] mb-10 italic flex items-center gap-4">
                                <span class="material-symbols-outlined text-neon-cyan">report</span>
                                Protocol Breach Alerts
                            </h2>
                            
                            <div class="space-y-6">
                                <div class="p-6 bg-white/5 border-r-4 border-error rounded-2xl hover:bg-white/10 transition-all cursor-pointer group">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-black text-error text-[11px] uppercase italic tracking-tighter group-hover:scale-105 transition-transform origin-right">برج القطاع الشمالي</h4>
                                        <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest italic">12 MIN AGO</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 leading-relaxed font-bold italic">Critical Handshake Failure. Absolute Protocol Loss in Sector Alpha Matrix.</p>
                                </div>
                                <div class="p-6 bg-white/5 border-r-4 border-amber-500 rounded-2xl hover:bg-white/10 transition-all cursor-pointer group">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-black text-amber-500 text-[11px] uppercase italic tracking-tighter group-hover:scale-105 transition-transform origin-right">تجاوز الحمل: برج #12</h4>
                                        <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest italic">45 MIN AGO</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 leading-relaxed font-bold italic">Thermal Threshold Near Breach. Bandwidth saturation at 98% of peak capacity.</p>
                                </div>
                            </div>

                            <button class="w-full mt-12 py-4 bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-[0.3em] font-headline rounded-2xl flex items-center justify-center gap-3 hover:bg-white/10 transition-all italic">
                                Analyze All Telemetry Logs
                                <span class="material-symbols-outlined text-[14px]">history</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Transaction Registry Matrix -->
                <div class="col-span-12 glass-panel rounded-[3rem] overflow-hidden !bg-white/60">
                    <div class="p-10 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-slate-50/20">
                        <div>
                            <h2 class="text-xl font-black text-primary uppercase tracking-widest italic flex items-center gap-4">
                                <span class="w-10 h-1 bg-accent-flow rounded-full"></span>
                                سجل العمليات السيادي
                            </h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3 italic">Verified Monetary Pulse & Identity Acquisition History</p>
                        </div>
                        <button class="px-10 py-4 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-primary uppercase tracking-[0.3em] hover:bg-slate-900 hover:text-white transition-all flex items-center gap-4 shadow-sm italic">
                            Full Registry Explorer
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                                    <th class="px-10 py-6">Beneficiary Identity</th>
                                    <th class="px-10 py-6 text-center">Protocol Yield</th>
                                    <th class="px-10 py-6 text-center">Temporal Index</th>
                                    <th class="px-10 py-6 text-center">Execution Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/20">
                                <tr v-for="inv in recentInvoices" :key="inv.id" class="group hover:bg-white/40 transition-all duration-300 cursor-pointer">
                                    <td class="px-10 py-7">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-slate-900 p-0.5">
                                                <div class="w-full h-full bg-slate-800 rounded-lg flex items-center justify-center text-[10px] font-black text-neon-cyan uppercase">
                                                    {{ inv.client?.name ? inv.client.name.substring(0,1) : '?' }}
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">{{ inv.client?.name }}</p>
                                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 font-manrope italic opacity-60">{{ inv.client?.username }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-7 text-center">
                                        <div class="flex items-baseline justify-center gap-1.5">
                                            <span class="text-sm font-manrope font-black text-primary italic">{{ inv.amount }}</span>
                                            <span class="text-[9px] font-black text-slate-400 uppercase italic">SAR</span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-7 text-center text-slate-400 text-[10px] font-black font-manrope italic opacity-60">{{ inv.created_at_human }}</td>
                                    <td class="px-10 py-7 text-center">
                                        <div class="flex justify-center">
                                            <span :class="['px-5 py-1.5 rounded-full text-[9px] font-black uppercase italic shadow-sm', inv.status === 'paid' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 shadow-glow-cyan' : 'bg-slate-100 text-slate-400 border border-slate-200']">
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
canvas {
    filter: drop-shadow(0 12px 24px rgba(0, 229, 255, 0.1));
}
</style>
