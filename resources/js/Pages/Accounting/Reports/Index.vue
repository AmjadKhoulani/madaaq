<script setup>
import { Head } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    TrendingUp, 
    TrendingDown, 
    DollarSign, 
    Calendar, 
    Zap, 
    Globe, 
    Wifi, 
    Activity, 
    HardDrive,
    TowerControl as Tower,
    PieChart,
    BarChart3,
    ArrowUpRight,
    ArrowDownRight,
    Info,
    CreditCard,
    Target
} from 'lucide-vue-next';

const props = defineProps({
    thisMonthRevenue: Number,
    thisYearRevenue: Number,
    thisMonthExpenses: Number,
    thisYearExpenses: Number,
    thisYearProfit: Number,
    totalDebts: Number,
    revenueData: Array,
    expensesData: Array,
    profitData: Array,
    months: Array,
    revenuePieData: Object,
    expenseBreakdown: Object,
    topTowers: Array,
    totalStructureCost: Number
});

// Calculate percentages
const totalRevenuePie = (props.revenuePieData.hotspot || 0) + (props.revenuePieData.broadband || 0);
const hotspotPercent = totalRevenuePie ? ((props.revenuePieData.hotspot || 0) / totalRevenuePie * 100).toFixed(0) : 0;
const broadbandPercent = totalRevenuePie ? ((props.revenuePieData.broadband || 0) / totalRevenuePie * 100).toFixed(0) : 0;

const totalExpenses = (props.expenseBreakdown.electricity || 0) + (props.expenseBreakdown.fuel || 0) + (props.expenseBreakdown.maintenance || 0) + (props.expenseBreakdown.rent || 0);

const getProgressColor = (type) => {
    switch (type) {
        case 'electricity': return 'bg-amber-500';
        case 'fuel': return 'bg-orange-600';
        case 'maintenance': return 'bg-indigo-500';
        case 'rent': return 'bg-emerald-600';
        default: return 'bg-gray-400';
    }
};

</script>

<template>
    <AppleLayout title="Fiscal Analysis">
        <Head title="Performance Intelligence" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Overview Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Profit Pulse -->
                <div class="apple-card p-10 bg-black text-white relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-3">Operating Profit (YTD)</p>
                        <h3 class="text-5xl font-bold tracking-tight mb-2">${{ thisYearProfit.toLocaleString() }}</h3>
                        <div class="flex items-center gap-2 text-emerald-400 font-bold text-xs">
                            <ArrowUpRight class="w-4 h-4" />
                            <span>{{ ((thisYearProfit/thisYearRevenue)*100).toFixed(1) }}% Margin</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="apple-card p-10 bg-white border border-black/5 group">
                    <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-3">Revenue (Cycle Pulse)</p>
                    <h3 class="text-4xl font-bold tracking-tight text-black mb-2">${{ thisMonthRevenue.toLocaleString() }}</h3>
                    <div class="flex items-center gap-2 text-indigo-600 font-bold text-xs uppercase tracking-widest">
                         <Activity class="w-4 h-4" />
                         Current Billing Month
                    </div>
                </div>

                <!-- Debts / Receivables -->
                <div class="apple-card p-10 bg-rose-50 border border-rose-100 group">
                    <p class="text-[10px] font-black text-rose-600/60 uppercase tracking-widest mb-3">Receivables (Delta)</p>
                    <h3 class="text-4xl font-bold tracking-tight text-rose-600 mb-2">${{ totalDebts.toLocaleString() }}</h3>
                    <div class="flex items-center gap-2 text-rose-500 font-bold text-xs uppercase tracking-widest">
                         <Info class="w-4 h-4" />
                         Critical Exposure
                    </div>
                </div>

                <!-- Assets / Structure -->
                <div class="apple-card p-10 bg-emerald-50 border border-emerald-100 group">
                    <p class="text-[10px] font-black text-emerald-600/60 uppercase tracking-widest mb-3">Structural Asset Value</p>
                    <h3 class="text-4xl font-bold tracking-tight text-emerald-700 mb-2">${{ totalStructureCost.toLocaleString() }}</h3>
                    <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs uppercase tracking-widest">
                         <Tower class="w-4 h-4" />
                         Fixed Topology CAPEX
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- 1. Revenue Distribution (Source) -->
                <div class="lg:col-span-1 apple-card p-10 flex flex-col items-center justify-center text-center">
                    <div class="flex items-center gap-3 mb-10 w-full text-left">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Segment Intelligence</h3>
                    </div>

                    <div class="relative w-64 h-64 mb-10">
                         <!-- Distribution Ring (Simplified CSS Ring) -->
                         <div class="absolute inset-0 rounded-full border-[10px] border-black opacity-[0.03]"></div>
                         <div class="absolute inset-0 rounded-full border-[10px] border-indigo-600" :style="`clip-path: polygon(50% 50%, 50% 0, 100% 0, 100% 100%, 0 100%, 0 0, 50% 0); transform: rotate(${hotspotPercent * 3.6}deg)`"></div>
                         <div class="absolute inset-0 flex flex-col items-center justify-center">
                             <p class="text-5xl font-black tracking-tight text-indigo-600">{{ hotspotPercent }}<span class="text-2xl">%</span></p>
                             <p class="text-[9px] font-black text-[#86868b] uppercase tracking-[0.2em] mt-2">Hotspot Dominance</p>
                         </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 w-full mt-auto pt-10 border-t border-black/[0.03]">
                        <div>
                             <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mb-2 flex items-center justify-center gap-2">
                                <span class="w-2 h-2 bg-indigo-600 rounded-full"></span> Hotspot
                             </p>
                             <p class="text-lg font-bold">${{ (revenuePieData.hotspot || 0).toLocaleString() }}</p>
                        </div>
                        <div>
                             <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mb-2 flex items-center justify-center gap-2">
                                <span class="w-2 h-2 bg-black/10 rounded-full"></span> PPPoE / Fiber
                             </p>
                             <p class="text-lg font-bold">${{ (revenuePieData.broadband || 0).toLocaleString() }}</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Performance Matrix (Monthly Trend) -->
                <div class="lg:col-span-2 apple-card p-10 flex flex-col">
                    <div class="flex items-center justify-between mb-12">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-sm font-bold tracking-tight uppercase">Fiscal Velocity Matrix</h3>
                        </div>
                        <div class="flex bg-black/[0.03] p-1.5 rounded-2xl">
                             <button class="px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-white text-black shadow-lg">Revenue</button>
                             <button class="px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest text-[#86868b]">Margin</button>
                        </div>
                    </div>

                    <!-- CSS-Based Bar Chart -->
                    <div class="flex-1 flex items-end justify-between gap-4 h-64 mb-8 group/chart">
                         <div v-for="(val, idx) in revenueData" :key="idx" class="flex-1 flex flex-col items-center gap-3 group/bar">
                             <div class="w-full bg-black/[0.03] rounded-t-xl relative group-hover/bar:bg-black/[0.08] transition-all min-h-[4px]" :style="`height: ${Math.max(1, (val / Math.max(...revenueData)) * 100)}%` ">
                                 <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-black text-white px-3 py-1.5 rounded-lg text-[9px] font-black opacity-0 group-hover/bar:opacity-100 transition-all shadow-xl pointer-events-none">
                                     ${{ val.toLocaleString() }}
                                 </div>
                             </div>
                             <span class="text-[8px] font-black text-[#86868b] uppercase tracking-widest opacity-40 group-hover/bar:opacity-100 group-hover/bar:text-black transition-all">{{ months[idx].substring(0, 3) }}</span>
                         </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-10 border-t border-black/[0.03]">
                         <div class="flex items-center gap-4">
                             <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <TrendingUp class="w-5 h-5" />
                             </div>
                             <div>
                                 <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Apex Month</p>
                                 <p class="text-sm font-bold">${{ Math.max(...revenueData).toLocaleString() }}</p>
                             </div>
                         </div>
                         <div class="flex items-center gap-4">
                             <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                                <TrendingDown class="w-5 h-5" />
                             </div>
                             <div>
                                 <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Nadir Month</p>
                                 <p class="text-sm font-bold">${{ Math.min(...revenueData.filter(v => v > 0) || [0]).toLocaleString() }}</p>
                             </div>
                         </div>
                         <div class="flex items-center gap-4">
                             <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <Globe class="w-5 h-5" />
                             </div>
                             <div>
                                 <p class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Cycle Median</p>
                                 <p class="text-sm font-bold">${{ (thisYearRevenue / 12).toLocaleString() }}</p>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- 3. Expense Evolution (Breakdown) -->
                <div class="apple-card p-10 flex flex-col">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Expense Evolution</h3>
                    </div>

                    <div class="space-y-10 flex-1">
                        <div v-for="(val, key) in expenseBreakdown" :key="key" class="space-y-3">
                             <div class="flex items-center justify-between">
                                 <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">{{ key }} Protocol</p>
                                 <p class="text-[10px] font-black text-black uppercase tracking-tight">${{ val.toLocaleString() }}</p>
                             </div>
                             <div class="h-2 w-full bg-black/[0.03] rounded-full overflow-hidden">
                                 <div class="h-full rounded-full transition-all duration-1000" :class="getProgressColor(key)" :style="`width: ${(val / totalExpenses * 100)}%`"></div>
                             </div>
                        </div>
                    </div>

                    <div class="mt-auto pt-10 border-t border-black/[0.03] text-center">
                        <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-1">Combined Overhead Extraction</p>
                        <p class="text-3xl font-black tracking-tight text-rose-500">${{ thisYearExpenses.toLocaleString() }}</p>
                        <p class="text-[9px] font-bold text-[#86868b] mt-2 uppercase">Annual Cumulative Expenditure</p>
                    </div>
                </div>

                <!-- 4. Top Hub Intelligence (Revenue per Tower) -->
                <div class="lg:col-span-2 apple-card p-10">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
                        <h3 class="text-sm font-bold tracking-tight uppercase">Core Site Intelligence (Top Performers)</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[9px] font-black text-[#86868b] uppercase tracking-widest border-b border-black/[0.03]">
                                    <th class="pb-6 px-4">Node Identity</th>
                                    <th class="pb-6 px-4">Extraction Force</th>
                                    <th class="pb-6 px-4 text-right">Commitment Metric</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/[0.02]">
                                <tr v-for="tower in topTowers" :key="tower.name" class="group hover:bg-black/[0.01] transition-all">
                                    <td class="py-6 px-4">
                                        <div class="flex items-center gap-4">
                                             <div class="w-10 h-10 rounded-xl bg-black shadow-lg flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                                                <Tower class="w-5 h-5" />
                                             </div>
                                             <p class="text-sm font-bold tracking-tight uppercase">{{ tower.name }}</p>
                                        </div>
                                    </td>
                                    <td class="py-6 px-4">
                                         <div class="w-48 h-1.5 bg-black/[0.03] rounded-full overflow-hidden">
                                             <div class="h-full bg-emerald-500 rounded-full" :style="`width: ${(tower.total / topTowers[0].total * 100)}%`"></div>
                                         </div>
                                    </td>
                                    <td class="py-6 px-4 text-right">
                                         <p class="text-lg font-black tracking-tight">${{ tower.total.toLocaleString() }}</p>
                                         <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest mt-1">Settled YTD</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
