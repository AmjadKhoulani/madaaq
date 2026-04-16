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
        case 'electricity': return 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.3)]';
        case 'fuel': return 'bg-rose-600 shadow-[0_0_10px_rgba(225,29,72,0.3)]';
        case 'maintenance': return 'bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.3)]';
        case 'rent': return 'bg-slate-900 shadow-[0_0_10px_rgba(15,23,42,0.3)]';
        default: return 'bg-slate-400';
    }
};

const getExpenseLabel = (type) => {
    switch (type) {
        case 'electricity': return 'بروتوكول الطاقة والكهرباء';
        case 'fuel': return 'تكاليف الوقود والطاقة البديلة';
        case 'maintenance': return 'حوكمة الصيانة الدورية';
        case 'rent': return 'إيجارات المواقع والهياكل';
        default: return type;
    }
};

const formatNumber = (num) => {
    return new Intl.NumberFormat('ar-SY').format(num);
};

</script>

<template>
    <InstitutionalLayout title="استخبارات الأداء المالي">
        <Head title="التقارير المالية الإستراتيجية - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2 uppercase">استخبارات الأداء المالي (Fiscal Intel)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">تحليل سيولة المنظومة، التكاليف التشغيلية العاجلة، ومؤشرات ربحية الأصول الإستراتيجية</p>
                        <span class="material-symbols-outlined text-primary text-[24px]">analytics</span>
                    </div>
                </div>
            </div>

            <!-- Strategic Fiscal Matrix (Bento Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Profit Integrity Cluster -->
                <div class="surface-card p-10 bg-slate-950 text-white rounded-[2rem] relative overflow-hidden group shadow-2xl border border-white/5 border-b-8 border-primary">
                    <div class="absolute -top-16 -left-16 w-64 h-64 bg-primary/20 rounded-full blur-3xl group-hover:scale-150 transition-all duration-1000"></div>
                    <div class="relative z-10 text-right space-y-6">
                        <div class="flex items-center justify-between flex-row-reverse mb-4">
                             <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-primary shadow-inner">
                                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">payments</span>
                             </div>
                             <p class="text-[11px] font-black text-white/30 uppercase tracking-[0.2em] leading-none">صافي العوائد السنوية (Profit YTD)</p>
                        </div>
                        <div class="flex items-baseline justify-end gap-3 mb-6">
                             <h3 class="text-5xl font-black font-headline tracking-tighter">{{ formatNumber(thisYearProfit) }}</h3>
                             <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">ل.س</span>
                        </div>
                        <div class="inline-flex items-center gap-3 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 font-black text-[10px] uppercase tracking-widest shadow-inner">
                            <span class="material-symbols-outlined text-[16px]">trending_up</span>
                            كفاءة التحصيل: {{ ((thisYearProfit/thisYearRevenue)*100).toFixed(1) }}%
                        </div>
                    </div>
                </div>

                <!-- Monthly Liquidity Intake -->
                <div class="surface-card p-10 rounded-[2rem] border border-outline-variant/10 shadow-2xl bg-white border-b-8 border-slate-900 group">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">إيرادات الجلسة الشهرية</p>
                    <div class="flex items-baseline justify-end gap-3 mb-6">
                        <h3 class="text-4xl font-black font-headline text-primary tracking-tighter group-hover:translate-x-[-4px] transition-transform">{{ formatNumber(thisMonthRevenue) }}</h3>
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">ل.س</span>
                    </div>
                    <div class="flex items-center gap-3 justify-end text-primary font-black text-[10px] uppercase tracking-widest leading-none">
                         <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
                         سـيولة الدورة النشطة
                    </div>
                </div>

                <!-- Exposure (Outstanding Debts) -->
                <div class="surface-card p-10 bg-rose-50 border border-rose-100 rounded-[2rem] shadow-2xl border-b-8 border-rose-600">
                    <p class="text-[11px] font-black text-rose-600/60 uppercase tracking-[0.2em] mb-6">رأس المال المعلق (Debts)</p>
                    <div class="flex items-baseline justify-end gap-3 mb-6">
                        <h3 class="text-4xl font-black font-headline text-rose-600 tracking-tighter">{{ formatNumber(totalDebts) }}</h3>
                        <span class="text-[10px] font-black text-rose-600/20 uppercase tracking-widest">ل.س</span>
                    </div>
                    <div class="flex items-center gap-3 justify-end text-rose-600 font-black text-[10px] uppercase tracking-widest leading-none">
                         <span class="material-symbols-outlined text-[18px]">report</span>
                         مطالبات مالية قيد الملاحقة
                    </div>
                </div>

                <!-- CapEx Assets Integrity -->
                <div class="surface-card p-10 bg-indigo-50 border border-indigo-100 rounded-[2rem] shadow-2xl border-b-8 border-indigo-600">
                    <p class="text-[11px] font-black text-indigo-600/60 uppercase tracking-[0.2em] mb-6">قيمة البنية التحتية (CapEx)</p>
                    <div class="flex items-baseline justify-end gap-3 mb-6">
                        <h3 class="text-4xl font-black font-headline text-indigo-600 tracking-tighter">{{ formatNumber(totalStructureCost) }}</h3>
                        <span class="text-[10px] font-black text-indigo-600/20 uppercase tracking-widest">ل.س</span>
                    </div>
                    <div class="flex items-center gap-3 justify-end text-indigo-600 font-black text-[10px] uppercase tracking-widest leading-none">
                         <span class="material-symbols-outlined text-[18px]">foundation</span>
                         إجمالي الاستثمار البنيوي
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- 1. Segment Profitability Intelligence -->
                <div class="lg:col-span-1 surface-card p-12 rounded-[3rem] border border-outline-variant/10 shadow-2xl flex flex-col items-center bg-white overflow-hidden relative">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-[80px]"></div>
                    <div class="flex items-center gap-4 mb-16 w-full justify-end relative z-10">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">تحليل مصادر التدفق (Segment)</h3>
                        <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                    </div>

                    <div class="relative w-72 h-72 mb-16 group/ring relative z-10">
                         <!-- Strategic Distribution Ring -->
                         <div class="absolute inset-0 rounded-full border-[16px] border-slate-50 shadow-inner group-hover/ring:rotate-3 transition-transform duration-1000"></div>
                         <div class="absolute inset-0 rounded-full border-[16px] border-primary" :style="`clip-path: polygon(50% 50%, 50% 0, 100% 0, 100% 100%, 0 100%, 0 0, 50% 0); transform: rotate(${hotspotPercent * 3.6}deg)`"></div>
                         <div class="absolute inset-0 flex flex-col items-center justify-center">
                             <p class="text-7xl font-black font-headline tracking-tighter text-primary leading-none group-hover/ring:scale-110 transition-transform duration-500">{{ hotspotPercent }}<span class="text-2xl">%</span></p>
                             <div class="flex items-center gap-2 mt-4 opacity-40">
                                 <span class="material-symbols-outlined text-[18px]">wifi_tethering</span>
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">قطاع البث (Hotspot)</p>
                             </div>
                         </div>
                    </div>

                    <div class="grid grid-cols-2 gap-10 w-full mt-auto pt-10 border-t border-slate-50 flex-row-reverse relative z-10">
                        <div class="text-center group/item scale-95 hover:scale-100 transition-all">
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-center gap-3">
                                <span class="w-3 h-3 bg-primary rounded-full shadow-[0_0_8px_rgba(37,99,235,0.4)]"></span> توزيع لاسلكي
                             </p>
                             <p class="text-2xl font-black font-headline tracking-tight text-primary leading-none">{{ formatNumber(revenuePieData.hotspot || 0) }}</p>
                             <span class="text-[8px] font-black text-slate-300 uppercase mt-2 block">ل.س</span>
                        </div>
                        <div class="text-center group/item scale-95 hover:scale-100 transition-all">
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center justify-center gap-3">
                                <span class="w-3 h-3 bg-slate-900 rounded-full shadow-inner"></span> حزم منزلية
                             </p>
                             <p class="text-2xl font-black font-headline tracking-tight text-slate-900 leading-none">{{ formatNumber(revenuePieData.broadband || 0) }}</p>
                             <span class="text-[8px] font-black text-slate-300 uppercase mt-2 block">ل.س</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Fiscal Velocity Matrix (Trend Evolution) -->
                <div class="lg:col-span-2 surface-card p-12 rounded-[3rem] border border-outline-variant/10 shadow-2xl flex flex-col bg-white">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-20 gap-8 flex-row-reverse">
                        <div class="flex items-center gap-4 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em] leading-none">مصفوفة تسارع التحصيل (Velocity Matrix)</h3>
                            <div class="w-1.5 h-8 bg-emerald-500 rounded-full shadow-lg"></div>
                        </div>
                        <div class="flex bg-slate-100 p-2 rounded-2xl border border-outline-variant/5 shadow-inner">
                             <button class="px-8 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] bg-white text-primary shadow-xl">تحليل الإيرادات</button>
                             <button class="px-8 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-primary transition-all">هوامش النمو</button>
                        </div>
                    </div>

                    <!-- High-Contrast Analytical Performance Bars -->
                    <div class="flex-1 flex items-end justify-between gap-8 h-80 mb-12 group/chart px-4">
                         <div v-for="(val, idx) in revenueData" :key="idx" class="flex-1 flex flex-col items-center gap-6 group/bar h-full justify-end">
                             <div class="w-full bg-slate-50 rounded-2xl relative group-hover/bar:bg-primary/10 transition-all min-h-[8px] border border-slate-100/50 shadow-inner" :style="`height: ${Math.max(4, (val / Math.max(...revenueData)) * 100)}%` ">
                                 <!-- Intelligence Tooltip -->
                                 <div class="absolute -top-16 left-1/2 -translate-x-1/2 bg-slate-950 text-white px-5 py-3 rounded-2xl text-[11px] font-black font-headline opacity-0 group-hover/bar:opacity-100 transition-all shadow-[0_20px_40px_rgba(0,0,0,0.3)] pointer-events-none whitespace-nowrap z-30 border border-white/10">
                                     {{ formatNumber(val) }} ل.س
                                     <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-3 h-3 bg-slate-950 rotate-45 border-b border-r border-white/10"></div>
                                 </div>
                                 <div class="absolute inset-x-0 bottom-0 bg-primary/40 rounded-b-2xl h-[6px] opacity-0 group-hover/bar:opacity-100 animate-pulse"></div>
                             </div>
                             <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] opacity-40 group-hover/bar:opacity-100 group-hover/bar:text-primary transition-all font-headline">{{ months[idx].substring(0, 3) }}</span>
                         </div>
                    </div>

                    <!-- Strategic Metric Strip -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pt-12 border-t border-slate-50 flex-row-reverse">
                         <div class="flex items-center gap-6 justify-end group/stat">
                             <div class="text-right">
                                 <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] opacity-60">ذروة التحصيل (Apex Point)</p>
                                 <p class="text-2xl font-black font-headline text-primary tracking-tighter leading-none mt-2 group-hover:translate-x-[-2px] transition-transform">{{ formatNumber(Math.max(...revenueData)) }}</p>
                             </div>
                             <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center border border-emerald-500/20 shadow-xl group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <span class="material-symbols-outlined text-[32px]">trending_up</span>
                             </div>
                         </div>
                         <div class="flex items-center gap-6 justify-end group/stat">
                             <div class="text-right">
                                 <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] opacity-60">السـقف المالي (Cap)</p>
                                 <p class="text-2xl font-black font-headline text-rose-600 tracking-tighter leading-none mt-2 group-hover:translate-x-[-2px] transition-transform">{{ formatNumber(Math.min(...revenueData.filter(v => v > 0) || [0])) }}</p>
                             </div>
                             <div class="w-14 h-14 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center border border-rose-500/20 shadow-xl group-hover:bg-rose-600 group-hover:text-white transition-all">
                                <span class="material-symbols-outlined text-[32px]">trending_down</span>
                             </div>
                         </div>
                         <div class="flex items-center gap-6 justify-end group/stat">
                             <div class="text-right">
                                 <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] opacity-60">المتوسط المرجح (Median)</p>
                                 <p class="text-2xl font-black font-headline text-slate-900 tracking-tighter leading-none mt-2 group-hover:translate-x-[-2px] transition-transform">{{ formatNumber(Math.round(thisYearRevenue / 12)) }}</p>
                             </div>
                             <div class="w-14 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-xl group-hover:bg-primary transition-all">
                                <span class="material-symbols-outlined text-[32px]">balance</span>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- 3. Operational Burn Rate (Expenses Breakdown) -->
                <div class="surface-card p-12 rounded-[3.5rem] border border-outline-variant/10 shadow-2xl flex flex-col bg-white">
                    <div class="flex items-center gap-4 mb-14 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">تحليل النفقات التشغيلية (Burn Rate)</h3>
                        <div class="w-1.5 h-8 bg-rose-500 rounded-full shadow-lg"></div>
                    </div>

                    <div class="space-y-12 flex-1 mt-6">
                        <div v-for="(val, key) in expenseBreakdown" :key="key" class="space-y-5 animate-in slide-in-from-top-4 duration-500">
                             <div class="flex items-center justify-between flex-row-reverse text-right">
                                 <p class="text-[12px] font-bold text-slate-600 uppercase tracking-widest leading-none">{{ getExpenseLabel(key) }}</p>
                                 <div class="flex items-baseline gap-2 font-headline">
                                     <p class="text-lg font-black text-slate-950">{{ formatNumber(val) }}</p>
                                     <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">ل.س</span>
                                 </div>
                             </div>
                             <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200/50 p-0.5 shadow-inner">
                                 <div class="h-full rounded-full transition-all duration-[1.5s] ease-out-expo" :class="getProgressColor(key)" :style="`width: ${(val / totalExpenses * 100)}%`"></div>
                             </div>
                        </div>
                    </div>

                    <div class="mt-20 pt-12 border-t border-slate-50 text-center bg-slate-950 text-white rounded-[2.5rem] -mx-10 -mb-10 p-12 shadow-[0_-20px_50px_rgba(0,0,0,0.1)] relative overflow-hidden group">
                        <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.4em] mb-4 relative z-10">إجمالي المصاريف السيادية السنوية</p>
                        <div class="flex items-baseline justify-center gap-3 relative z-10">
                            <p class="text-5xl font-black font-headline tracking-tighter text-white">{{ formatNumber(thisYearExpenses) }}</p>
                            <span class="text-[11px] font-black text-white/20 uppercase tracking-widest">ل.س</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Site Profitability Intelligence (Node Integrity) -->
                <div class="lg:col-span-2 surface-card p-12 rounded-[3.5rem] border border-outline-variant/10 shadow-2xl bg-white bg-[radial-gradient(#f1f5f9_1px,transparent_1px)] [background-size:20px_20px]">
                    <div class="flex items-center gap-4 mb-16 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em] leading-none">مؤشر أداء العـقد والمواقع (Yield Intel)</h3>
                        <div class="w-1.5 h-8 bg-emerald-600 rounded-full shadow-lg"></div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-right border-separate border-spacing-y-6">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em]">
                                    <th class="pb-4 pr-10">موقع النشر (Node)</th>
                                    <th class="pb-4 px-6 text-center">كفاءة التحصيل (Utilization)</th>
                                    <th class="pb-4 pl-10 text-left">إجمالي الإيرادات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tower in topTowers" :key="tower.name" class="group hover:bg-slate-50 transition-all">
                                    <td class="py-8 pr-10 rounded-r-[2rem] border-y border-r border-slate-100 bg-white">
                                        <div class="flex items-center gap-8 flex-row-reverse text-right relative">
                                             <div class="w-16 h-16 rounded-3xl bg-slate-950 shadow-2xl flex items-center justify-center text-white shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 border border-white/5">
                                                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">hub</span>
                                             </div>
                                             <div>
                                                 <p class="text-lg font-black text-primary tracking-tight uppercase leading-none mb-3 group-hover:translate-x-[-4px] transition-transform">{{ tower.name }}</p>
                                                 <p class="text-[10px] font-black text-slate-400 tracking-[0.3em] uppercase opacity-60">عقدة إقليمية نشطة</p>
                                             </div>
                                        </div>
                                    </td>
                                    <td class="py-8 px-6 border-y border-slate-100 bg-white">
                                         <div class="w-full max-w-[250px] mx-auto h-4 bg-slate-100 rounded-full overflow-hidden shadow-inner p-1 border border-slate-200">
                                             <div class="h-full bg-gradient-to-l from-emerald-500 to-emerald-400 rounded-full transition-all duration-[2s] ease-out-expo shadow-lg" :style="`width: ${(tower.total / topTowers[0].total * 100)}%`"></div>
                                         </div>
                                    </td>
                                    <td class="py-8 pl-10 rounded-l-[2rem] border-y border-l border-slate-100 bg-white text-left">
                                         <div class="flex flex-col items-start font-headline group-hover:translate-x-4 transition-transform">
                                             <div class="flex items-baseline gap-2">
                                                 <p class="text-2xl font-black text-primary tracking-tighter leading-none">{{ formatNumber(tower.total) }}</p>
                                                 <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">ل.س</span>
                                             </div>
                                             <p class="text-[9px] font-black text-emerald-600 uppercase tracking-[0.3em] mt-3">إجمالي تسويات YTD</p>
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
.font-headline {
    font-family: 'Manrope', sans-serif;
}
.ease-out-expo {
    transition-timing-function: cubic-bezier(0.19, 1, 0.22, 1);
}
</style>
