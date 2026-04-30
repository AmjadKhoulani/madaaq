<script setup>
import { Head } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

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

// Calculate percentages for strategic visualization
const totalRevenuePie = (props.revenuePieData.hotspot || 0) + (props.revenuePieData.broadband || 0);
const hotspotPercent = totalRevenuePie ? ((props.revenuePieData.hotspot || 0) / totalRevenuePie * 100).toFixed(0) : 0;
const broadbandPercent = totalRevenuePie ? ((props.revenuePieData.broadband || 0) / totalRevenuePie * 100).toFixed(0) : 0;

const totalExpenses = (props.expenseBreakdown.electricity || 0) + (props.expenseBreakdown.fuel || 0) + (props.expenseBreakdown.maintenance || 0) + (props.expenseBreakdown.rent || 0);

const getProgressColor = (type) => {
    switch (type) {
        case 'electricity': return 'bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.4)]';
        case 'fuel': return 'bg-rose-600 shadow-[0_0_15px_rgba(225,29,72,0.4)]';
        case 'maintenance': return 'bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.4)]';
        case 'rent': return 'bg-slate-900 shadow-[0_0_15px_rgba(15,23,42,0.4)]';
        default: return 'bg-slate-400';
    }
};

const getExpenseLabel = (type) => {
    switch (type) {
        case 'electricity': return 'بروتوكول الطاقة والشبكة';
        case 'fuel': return 'تكاليف الوقود والاحتراق التشغيلي';
        case 'maintenance': return 'حوكمة الصيانة السنوية';
        case 'rent': return 'إيجارات المواقع الميدانية';
        default: return type;
    }
};

const formatNumber = (num) => {
    return new Intl.NumberFormat('ar-SY').format(num);
};

</script>

<template>
    <InstitutionalLayout title="استخبارات الأداء المالي">
        <Head title="مصفوفة الاستخبارات المالية (Fiscal Intel) - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-12 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-5xl font-black text-primary tracking-tighter mb-3 uppercase">استخبارات الأداء المالي (Fiscal Intel)</h1>
                    <div class="flex items-center gap-6 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-[0.2em]">تحليل سيولة المنظومة، التكاليف التشغيلية العاجلة، ومؤشرات ربحية الأصول الإستراتيجية</p>
                        <span class="flex h-4 w-4 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-secondary"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Strategic Fiscal Matrix (Bento Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Profit Integrity Cluster -->
                <div class="surface-card p-10 bg-slate-950 text-white rounded-[2.5rem] relative overflow-hidden group shadow-2xl border border-white/5 border-b-8 border-primary transition-all duration-700 hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-grid-slate-50 opacity-5 pointer-events-none"></div>
                    <div class="absolute -top-20 -left-20 w-80 h-80 bg-primary/20 rounded-full blur-3xl group-hover:scale-150 transition-all duration-1000"></div>
                    <div class="relative z-10 text-right space-y-8">
                        <div class="flex items-center justify-between flex-row-reverse mb-4">
                             <div class="w-16 h-16 rounded-[1.2rem] bg-white/5 border border-white/10 flex items-center justify-center text-primary shadow-2xl group-hover:rotate-12 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">payments</span>
                             </div>
                             <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] font-headline">AGGR. PROFIT YTD</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline tracking-tighter leading-none">{{ formatNumber(thisYearProfit) }}</h3>
                                <span class="text-[11px] font-black text-primary uppercase tracking-[0.2em] italic">SYP</span>
                            </div>
                            <p class="text-[9px] font-black text-white/20 uppercase tracking-[0.4em] mt-4">صافي الأرباح السيادية المتحققة</p>
                        </div>
                        <div class="inline-flex items-center gap-4 px-6 py-2.5 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 font-black text-[10px] uppercase tracking-[0.2em]">
                            <span class="material-symbols-outlined text-[18px] animate-pulse">trending_up</span>
                            كفاءة التحصيل: {{ ((thisYearProfit/thisYearRevenue)*100).toFixed(1) }}%
                        </div>
                    </div>
                </div>

                <!-- Monthly Liquidity Intake -->
                <div class="surface-card p-10 rounded-[2.5rem] border border-outline-variant/10 shadow-2xl bg-white border-b-8 border-slate-900 group hover:scale-[1.02] transition-all duration-700 overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between flex-row-reverse mb-10">
                             <div class="w-16 h-16 rounded-[1.2rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:-rotate-12 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">history_edu</span>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] font-headline">MONTHLY_LIQUIDITY</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline text-primary tracking-tighter leading-none group-hover:translate-x-3 transition-transform">{{ formatNumber(thisMonthRevenue) }}</h3>
                                <span class="text-[11px] font-black text-slate-300 uppercase tracking-[0.2em] italic">SYP</span>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] mt-4 italic">إيرادات الجلسة التشغيلية الراهنة</p>
                        </div>
                    </div>
                </div>

                <!-- Exposure (Outstanding Debts) -->
                <div class="surface-card p-10 bg-rose-50 border border-rose-100 rounded-[2.5rem] shadow-2xl border-b-8 border-rose-600 group hover:scale-[1.02] transition-all duration-700 overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between flex-row-reverse mb-10">
                             <div class="w-16 h-16 rounded-[1.2rem] bg-rose-600 text-white flex items-center justify-center shadow-2xl group-hover:animate-pulse">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">report_problem</span>
                             </div>
                             <p class="text-[10px] font-black text-rose-600/40 uppercase tracking-[0.3em] font-headline">OUTSTANDING_EXPOSURE</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline text-rose-600 tracking-tighter leading-none">{{ formatNumber(totalDebts) }}</h3>
                                <span class="text-[11px] font-black text-rose-600/20 uppercase tracking-[0.2em] italic">SYP</span>
                            </div>
                            <p class="text-[9px] font-black text-rose-600 uppercase tracking-[0.4em] mt-4">رأس المال المعلق (Debts Profile)</p>
                        </div>
                    </div>
                </div>

                <!-- CapEx Assets Integrity -->
                <div class="surface-card p-10 bg-indigo-50 border border-indigo-100 rounded-[2.5rem] shadow-2xl border-b-8 border-indigo-600 group hover:scale-[1.02] transition-all duration-700 overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between flex-row-reverse mb-10">
                             <div class="w-16 h-16 rounded-[1.2rem] bg-indigo-600 text-white flex items-center justify-center shadow-2xl group-hover:rotate-6 transition-transform">
                                <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1">foundation</span>
                             </div>
                             <p class="text-[10px] font-black text-indigo-600/40 uppercase tracking-[0.3em] font-headline">CAPITAL_EXPENDITURE</p>
                        </div>
                        <div class="flex flex-col items-start gap-1">
                            <div class="flex items-baseline gap-4">
                                <h3 class="text-5xl font-black font-headline text-indigo-600 tracking-tighter leading-none">{{ formatNumber(totalStructureCost) }}</h3>
                                <span class="text-[11px] font-black text-indigo-100 uppercase tracking-[0.2em] italic">SYP</span>
                            </div>
                            <p class="text-[9px] font-black text-indigo-600 uppercase tracking-[0.4em] mt-4">إجمالي الاستثمار البنيوي (CapEx)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- 1. Segment Profitability Intelligence -->
                <div class="lg:col-span-1 surface-card p-12 rounded-[3.5rem] border border-outline-variant/10 shadow-2xl flex flex-col items-center bg-white overflow-hidden relative">
                    <div class="absolute -top-24 -right-24 w-80 h-80 bg-primary/5 rounded-full blur-[100px]"></div>
                    <div class="flex items-center gap-4 mb-20 w-full justify-end relative z-10">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.5em] italic">تحليل مصادر التدفق (Segment)</h3>
                        <div class="w-1.5 h-10 bg-primary rounded-full"></div>
                    </div>

                    <div class="relative w-80 h-80 mb-20 group/ring relative z-10">
                         <!-- Strategic Distribution Ring -->
                         <div class="absolute inset-x-0 inset-y-0 rounded-full border-[28px] border-slate-50 shadow-inner group-hover/ring:rotate-6 transition-transform duration-1000"></div>
                         <div class="absolute inset-x-0 inset-y-0 rounded-full border-[28px] border-primary" :style="`clip-path: polygon(50% 50%, 50% 0, 100% 0, 100% 100%, 0 100%, 0 0, 50% 0); transform: rotate(${hotspotPercent * 3.6}deg)`"></div>
                         <div class="absolute inset-x-0 inset-y-0 flex flex-col items-center justify-center">
                             <div class="animate-pulse absolute inset-x-0 inset-y-0 rounded-full bg-primary/5 blur-3xl"></div>
                             <p class="text-8xl font-black font-headline tracking-tighter text-primary leading-none group-hover/ring:scale-110 transition-transform duration-700 relative z-10">{{ hotspotPercent }}<span class="text-4xl ml-1">%</span></p>
                             <div class="flex items-center gap-3 mt-6 opacity-40 relative z-10">
                                 <span class="material-symbols-outlined text-[20px]">sensors</span>
                                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.4em]">بـث لاسلكي (Hotspot)</p>
                             </div>
                         </div>
                    </div>

                    <div class="grid grid-cols-2 gap-10 w-full mt-auto pt-12 border-t border-slate-50 flex-row-reverse relative z-10">
                        <div class="text-center group/item scale-95 hover:scale-105 transition-all duration-500">
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 flex items-center justify-center gap-4">
                                <span class="w-4 h-4 bg-primary rounded-full shadow-[0_0_15px_rgba(37,99,235,0.4)]"></span> قطاع الأفراد
                             </p>
                             <div class="flex items-baseline gap-2 justify-center">
                                <p class="text-3xl font-black font-headline tracking-tight text-primary leading-none">{{ formatNumber(revenuePieData.hotspot || 0) }}</p>
                                <span class="text-[9px] font-black text-slate-300 uppercase">SYP</span>
                             </div>
                        </div>
                        <div class="text-center group/item scale-95 hover:scale-105 transition-all duration-500">
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 flex items-center justify-center gap-4">
                                <span class="w-4 h-4 bg-slate-950 rounded-full shadow-2xl"></span> حزم مستدامة
                             </p>
                             <div class="flex items-baseline gap-2 justify-center">
                                <p class="text-3xl font-black font-headline tracking-tight text-slate-900 leading-none">{{ formatNumber(revenuePieData.broadband || 0) }}</p>
                                <span class="text-[9px] font-black text-slate-300 uppercase">SYP</span>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Fiscal Velocity Matrix (Trend Evolution) -->
                <div class="lg:col-span-2 surface-card p-12 rounded-[3.5rem] border border-outline-variant/10 shadow-2xl flex flex-col bg-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-50 [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.2))] pointer-events-none opacity-20"></div>
                    
                    <div class="flex flex-col md:flex-row items-center justify-between mb-24 gap-12 flex-row-reverse relative z-10">
                        <div class="flex items-center gap-6 justify-end text-right">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.5em] leading-none italic">مصفوفة تسارع التحصيل (Velocity Matrix)</h3>
                            <div class="w-1.5 h-10 bg-emerald-500 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.5)] animate-pulse"></div>
                        </div>
                        <div class="flex bg-slate-50 p-2.5 rounded-[1.5rem] border border-slate-200 shadow-inner">
                             <button class="px-10 py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.3em] bg-slate-950 text-white shadow-2xl scale-105">تحليل الإيرادات</button>
                             <button class="px-10 py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-primary transition-all">هوامش النمو</button>
                        </div>
                    </div>

                    <!-- High-Contrast Analytical Performance Bars -->
                    <div class="flex-1 flex items-end justify-between gap-10 h-80 mb-16 group/chart px-6 relative z-10">
                         <div v-for="(val, idx) in revenueData" :key="idx" class="flex-1 flex flex-col items-center gap-8 group/bar h-full justify-end">
                             <div class="w-full bg-slate-50 rounded-3xl relative group-hover/bar:bg-primary transition-all duration-700 min-h-[12px] border border-slate-200 shadow-inner group-hover/bar:shadow-2xl group-hover/bar:translate-y-[-10px]" :style="`height: ${Math.max(6, (val / Math.max(...revenueData)) * 100)}%` ">
                                 <!-- Intelligence Tooltip -->
                                 <div class="absolute -top-20 left-1/2 -translate-x-1/2 bg-slate-950 text-white px-8 py-4 rounded-2xl text-[12px] font-black font-headline opacity-0 group-hover/bar:opacity-100 transition-all shadow-[0_30px_60px_rgba(0,0,0,0.6)] pointer-events-none whitespace-nowrap z-30 border border-white/10 scale-50 group-hover/bar:scale-100">
                                     {{ formatNumber(val) }} ل.س
                                     <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-slate-950 rotate-45 border-b border-r border-white/10"></div>
                                 </div>
                                 <div class="absolute inset-x-0 bottom-0 bg-white/20 rounded-b-3xl h-[8px] opacity-0 group-hover/bar:opacity-100"></div>
                             </div>
                             <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] opacity-40 group-hover/bar:opacity-100 group-hover/bar:text-primary transition-all font-headline italic">{{ months[idx].substring(0, 3) }}</span>
                         </div>
                    </div>

                    <!-- Strategic Metric Strip -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pt-16 border-t border-slate-50 flex-row-reverse relative z-10">
                         <div class="flex items-center gap-8 justify-end group/stat">
                             <div class="text-right">
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] opacity-50 italic">ذروة التحصيل</p>
                                 <p class="text-3xl font-black font-headline text-primary tracking-tighter leading-none mt-3 group-hover:translate-x-[-4px] transition-transform">{{ formatNumber(Math.max(...revenueData)) }}</p>
                             </div>
                             <div class="w-18 h-18 rounded-3xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center border border-emerald-500/20 shadow-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                                <span class="material-symbols-outlined text-[40px]">trending_up</span>
                             </div>
                         </div>
                         <div class="flex items-center gap-8 justify-end group/stat">
                             <div class="text-right">
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] opacity-50 italic">الحد الأدنى</p>
                                 <p class="text-3xl font-black font-headline text-rose-600 tracking-tighter leading-none mt-3 group-hover:translate-x-[-4px] transition-transform">{{ formatNumber(Math.min(...revenueData.filter(v => v > 0) || [0])) }}</p>
                             </div>
                             <div class="w-18 h-18 rounded-3xl bg-rose-500/10 text-rose-600 flex items-center justify-center border border-rose-500/20 shadow-2xl group-hover:bg-rose-600 group-hover:text-white transition-all duration-500">
                                <span class="material-symbols-outlined text-[40px]">trending_down</span>
                             </div>
                         </div>
                         <div class="flex items-center gap-8 justify-end group/stat">
                             <div class="text-right">
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] opacity-50 italic">المتوسط المرجح</p>
                                 <p class="text-3xl font-black font-headline text-slate-900 tracking-tighter leading-none mt-3 group-hover:translate-x-[-4px] transition-transform">{{ formatNumber(Math.round(thisYearRevenue / 12)) }}</p>
                             </div>
                             <div class="w-18 h-18 rounded-3xl bg-slate-950 text-white flex items-center justify-center shadow-2xl group-hover:bg-primary transition-all duration-500">
                                <span class="material-symbols-outlined text-[40px]">balance</span>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- 3. Operational Burn Rate (Expenses Breakdown) -->
                <div class="surface-card p-12 rounded-[3.5rem] border border-outline-variant/10 shadow-2xl flex flex-col bg-white relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-l from-rose-500 to-rose-900 opacity-20"></div>
                    <div class="flex items-center gap-6 mb-16 justify-end relative z-10 text-right">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.5em] italic">تحليل النفقات التشغيلية (Burn Rate)</h3>
                        <div class="w-1.5 h-10 bg-rose-600 rounded-full shadow-[0_0_15px_rgba(225,29,72,0.5)]"></div>
                    </div>

                    <div class="space-y-16 flex-1 mt-6 relative z-10">
                        <div v-for="(val, key) in expenseBreakdown" :key="key" class="space-y-6 animate-in slide-in-from-top-10 duration-700">
                             <div class="flex items-center justify-between flex-row-reverse text-right">
                                 <div class="flex items-center gap-4 flex-row-reverse">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-2xl" :class="getProgressColor(key)">
                                         <span class="material-symbols-outlined text-[20px]">sell</span>
                                    </div>
                                    <p class="text-[13px] font-black text-slate-900 uppercase tracking-[0.1em] leading-none italic">{{ getExpenseLabel(key) }}</p>
                                 </div>
                                 <div class="flex items-baseline gap-3 font-headline">
                                     <p class="text-2xl font-black text-slate-950">{{ formatNumber(val) }}</p>
                                     <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">SYP</span>
                                 </div>
                             </div>
                             <div class="h-4 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-200/50 p-1 shadow-inner relative">
                                 <div class="h-full rounded-full transition-all duration-[2s] ease-out-expo relative z-10" :class="getProgressColor(key)" :style="`width: ${(val / totalExpenses * 100)}%` ">
                                     <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                 </div>
                             </div>
                        </div>
                    </div>

                    <div class="mt-20 pt-16 border-t border-slate-50 text-center bg-slate-950 text-white rounded-[3rem] -mx-12 -mb-12 p-16 shadow-[0_-30px_70px_rgba(0,0,0,0.15)] relative overflow-hidden group">
                        <div class="absolute inset-0 bg-rose-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.5em] mb-6 relative z-10 italic">إجمالي النزيف المالي السنوي (Total Burn)</p>
                        <div class="flex items-baseline justify-center gap-4 relative z-10">
                            <p class="text-6xl font-black font-headline tracking-tighter text-white group-hover:scale-110 transition-transform duration-700">{{ formatNumber(thisYearExpenses) }}</p>
                            <span class="text-[12px] font-black text-white/20 uppercase tracking-[0.3em]">SYP</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Site Profitability Intelligence (Yield Intel) -->
                <div class="lg:col-span-2 surface-card p-12 rounded-[4rem] border border-outline-variant/10 shadow-2xl bg-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-grid-slate-50 [mask-image:radial-gradient(ellipse_at_top_right,black,transparent)] opacity-10 pointer-events-none"></div>
                    <div class="flex items-center gap-6 mb-20 justify-end relative z-10 text-right">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.5em] leading-none italic">مردودية العـقد الاستراتيجية (Yield Intel)</h3>
                        <div class="w-1.5 h-10 bg-indigo-600 rounded-full shadow-[0_0_15px_rgba(79,70,229,0.5)]"></div>
                    </div>

                    <div class="overflow-x-auto relative z-10">
                        <table class="w-full text-right border-separate border-spacing-y-10">
                            <thead>
                                <tr class="text-[11px] font-black text-slate-400 uppercase tracking-[0.5em] text-right italic">
                                    <th class="pb-6 pr-12">العقدة الميدانية (Active Node)</th>
                                    <th class="pb-6 px-10 text-center">كثافة التحصيل (Yield Intensity)</th>
                                    <th class="pb-6 pl-12 text-left">العائد الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tower in topTowers" :key="tower.name" class="group hover:bg-slate-50 transition-all duration-500">
                                    <td class="py-12 pr-12 rounded-r-[3rem] border-y border-r border-slate-100 bg-white relative overflow-hidden">
                                        <div class="absolute inset-y-0 right-0 w-2 bg-primary transform translate-x-full group-hover:translate-x-0 transition-transform duration-700"></div>
                                        <div class="flex items-center gap-10 flex-row-reverse text-right relative">
                                             <div class="w-20 h-20 rounded-[1.8rem] bg-slate-950 shadow-[0_20px_40px_rgba(0,0,0,0.3)] flex items-center justify-center text-white shrink-0 group-hover:scale-110 group-hover:rotate-12 transition-all duration-700 border border-white/10 uppercase font-headline">
                                                <span class="material-symbols-outlined text-[40px]" style="font-variation-settings: 'FILL' 1; font-weight: 200;">GitBranch</span>
                                             </div>
                                             <div>
                                                 <p class="text-2xl font-black text-primary tracking-tighter uppercase leading-none mb-4 group-hover:translate-x-[-10px] transition-transform font-headline">{{ tower.name }}</p>
                                                 <div class="flex items-center gap-3 justify-end opacity-40 group-hover:opacity-100 transition-opacity">
                                                     <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">REGIONAL_STRATEGIC_NODE</p>
                                                     <span class="material-symbols-outlined text-[16px]">verified</span>
                                                 </div>
                                             </div>
                                        </div>
                                    </td>
                                    <td class="py-12 px-10 border-y border-slate-100 bg-white">
                                         <div class="w-full max-w-[300px] mx-auto h-5 bg-slate-50 rounded-full border border-slate-200 shadow-inner p-1.5 relative overflow-hidden">
                                             <div class="h-full bg-gradient-to-l from-primary via-secondary to-primary rounded-full transition-all duration-[3s] ease-out-expo shadow-2xl relative z-10" :style="`width: ${(tower.total / topTowers[0].total * 100)}%` ">
                                                 <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                             </div>
                                         </div>
                                    </td>
                                    <td class="py-12 pl-12 rounded-l-[3rem] border-y border-l border-slate-100 bg-white text-left">
                                         <div class="flex flex-col items-start font-headline group-hover:translate-x-6 transition-transform">
                                             <div class="flex items-baseline gap-4">
                                                 <p class="text-4xl font-black text-primary tracking-tighter leading-none">{{ formatNumber(tower.total) }}</p>
                                                 <span class="text-[11px] font-black text-slate-300 uppercase tracking-[0.2em] leading-none">SYP</span>
                                             </div>
                                             <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.4em] mt-5 italic flex items-center gap-3">
                                                <span class="material-symbols-outlined text-[18px] animate-bounce">keyboard_double_arrow_up</span>
                                                سندات تسوية YTD
                                             </p>
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



