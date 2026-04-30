<script setup>
import { Link, router, Head } from '@inertiajs/vue3';
import { watchref } from 'vue';
import {  } from 'lucide-vue-next';;
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { pickBy } from 'lodash';

const props = defineProps({
    chartData: Object,
    period: String,
    availableInterfaces: Array,
    interface: String,
});

const filters = ref({
    period: props.period || '24h',
    interface: props.interface || ''
});

watch(filters, (newVal) => {
    router.get(route('network.monitoring.bandwidth'), pickBy(newVal), {
        preserveState: true,
        replace: true
    });
}, { deep: true });

// Convert Object data to arrays for custom Chart component or SVG implementation
const categories = Object.keys(props.chartData);
const rxData = Object.values(props.chartData).map(v => v.rx);
const txData = Object.values(props.chartData).map(v => v.tx);

const maxVal = Math.max(...rxData, ...txData, 1);

const stats = {
    total_rx: rxData.reduce((a, b) => a + b, 0),
    total_tx: txData.reduce((a, b) => a + b, 0),
    peak_rx: Math.max(...rxData),
    peak_tx: Math.max(...txData),
};

</script>

<template>
    <InstitutionalLayout title="تحليل عرض النطاق">
        <Head title="ذكاء إنتاجية الشبكة - MadaaQ" />

        <div class="max-w-[1400px] mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">ذكاء سعة التمرير (Throughput)</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">تحليل سرعة الاستخراج والتدفق عبر واجهات الحافة البرمجية</p>
                    </div>
                </div>
                <Link 
                    :href="route('network.monitoring.index')" 
                    class="w-12 h-12 bg-white border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                </Link>
            </div>

            <!-- Strategic Filtering -->
            <div class="surface-card p-10 mb-12 bg-slate-900 text-white rounded-xl shadow-2xl relative overflow-hidden">
                <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-10 flex-row-reverse">
                    <div class="flex-1 space-y-4 text-right">
                        <label class="text-[9px] font-black text-white/40 uppercase tracking-widest mr-2">الأفق الزمني (Temporal Horizon)</label>
                        <div class="flex bg-white/5 p-1.5 rounded-xl w-fit flex-row-reverse">
                             <button @click="filters.period = '24h'" class="px-6 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.period === '24h' ? 'bg-primary text-white shadow-lg' : 'text-white/40 hover:text-white'">آخر 24 ساعة</button>
                             <button @click="filters.period = '7d'" class="px-6 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.period === '7d' ? 'bg-primary text-white shadow-lg' : 'text-white/40 hover:text-white'">آخر 7 أيام</button>
                             <button @click="filters.period = '30d'" class="px-6 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.period === '30d' ? 'bg-primary text-white shadow-lg' : 'text-white/40 hover:text-white'">آخر 30 يوم</button>
                        </div>
                    </div>
                    <div class="flex-1 space-y-4 w-full md:w-auto text-right">
                        <label class="text-[9px] font-black text-white/40 uppercase tracking-widest mr-2">عقدة استخراج الواجهة</label>
                        <select v-model="filters.interface" class="w-full bg-white/5 border-white/10 text-white rounded-xl h-14 px-6 font-black text-sm focus:ring-2 focus:ring-primary/50 transition-all outline-none appearance-none cursor-pointer">
                            <option value="" class="bg-slate-900">كافة البيانات المجمعة (Aggregate)</option>
                            <option v-for="int in availableInterfaces" :key="int" :value="int" class="bg-slate-900 text-white">{{ int }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Intelligence Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Data Visualizer -->
                <div class="lg:col-span-3 surface-card p-10 flex flex-col min-h-[550px] rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center justify-between mb-12 flex-row-reverse">
                         <div class="flex items-center gap-4 justify-end">
                            <h3 class="text-sm font-black text-primary uppercase tracking-widest">نبض السرعة (MB/s)</h3>
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        </div>
                        <div class="flex items-center gap-8 flex-row-reverse">
                            <div class="flex items-center gap-2.5">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">استقبال (RX)</span>
                                <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">إرسال (TX)</span>
                                <div class="w-3 h-3 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Custom SVG Velocity Chart -->
                    <div class="flex-1 relative mt-10 group/chart">
                         <!-- Grid Lines -->
                         <div class="absolute inset-0 flex flex-col justify-between opacity-[0.05] pointer-events-none">
                             <div v-for="i in 6" :key="i" class="border-b border-slate-900 w-full h-px"></div>
                         </div>
                         
                         <!-- Chart Bars/Lines -->
                         <div class="absolute inset-0 flex items-end justify-between gap-1.5 overflow-visible flex-row-reverse">
                             <div v-for="(cat, idx) in categories" :key="idx" class="flex-1 h-full flex items-end gap-[2px] group/item relative">
                                 <!-- RX Bar -->
                                 <div 
                                    class="flex-1 bg-emerald-500/10 group-hover/item:bg-emerald-500/30 transition-all relative rounded-t-sm"
                                    :style="`height: ${(rxData[idx] / maxVal) * 100}%`"
                                 >
                                    <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.4)]"></div>
                                 </div>
                                 <!-- TX Bar -->
                                 <div 
                                    class="flex-1 bg-indigo-500/10 group-hover/item:bg-indigo-500/30 transition-all relative rounded-t-sm"
                                    :style="`height: ${(txData[idx] / maxVal) * 100}%`"
                                 >
                                    <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.4)]"></div>
                                 </div>

                                 <!-- Tooltip on hover -->
                                 <div class="absolute -top-20 left-1/2 -translate-x-1/2 bg-slate-900 text-white p-4 rounded-xl text-[10px] font-black opacity-0 group-hover/item:opacity-100 transition-all shadow-2xl z-20 pointer-events-none whitespace-nowrap border border-white/10 scale-90 group-hover/item:scale-100 origin-bottom">
                                     <p class="text-white/40 uppercase text-[8px] mb-2 text-center">{{ cat }}</p>
                                     <div class="flex items-center gap-6">
                                         <div class="flex flex-col items-center">
                                             <span class="text-emerald-400 mb-1">RX</span>
                                             <span class="font-headline">{{ rxData[idx] }}M</span>
                                         </div>
                                         <div class="w-px h-6 bg-white/10"></div>
                                         <div class="flex flex-col items-center">
                                             <span class="text-indigo-400 mb-1">TX</span>
                                             <span class="font-headline">{{ txData[idx] }}M</span>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                    
                    <div class="flex justify-between mt-10 pt-10 border-t border-outline-variant/5 flex-row-reverse">
                        <span v-for="cat in categories.filter((_, i) => i % (categories.length > 10 ? Math.floor(categories.length/10) : 1) === 0)" :key="cat" class="text-[8px] font-black text-slate-400 uppercase tracking-widest font-headline">{{ cat }}</span>
                    </div>
                </div>

                <!-- Analysis Panel -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Global Accumulation -->
                    <div class="surface-card p-8 rounded-xl border border-outline-variant/5 shadow-sm text-right">
                        <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-10">الاستخراج التراكمي</h3>
                        <div class="space-y-10">
                            <div class="flex items-center gap-5 flex-row-reverse">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shadow-inner">
                                    <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">download</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">إجمالي الاستقبال</p>
                                    <p class="text-2xl font-black tracking-tight text-primary font-headline">{{ (stats.total_rx / 1024).toFixed(2) }}<span class="text-xs mr-1 text-slate-400">GB</span></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-5 flex-row-reverse">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-inner">
                                    <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1">upload</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">إجمالي الإرسال</p>
                                    <p class="text-2xl font-black tracking-tight text-primary font-headline">{{ (stats.total_tx / 1024).toFixed(2) }}<span class="text-xs mr-1 text-slate-400">GB</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Peak Analysis -->
                    <div class="surface-card p-10 bg-slate-900 text-white rounded-xl shadow-2xl relative overflow-hidden group">
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                        <h3 class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-10 text-right">بروتوكول السرعة القصوى</h3>
                        <div class="space-y-10">
                             <div class="space-y-3">
                                 <div class="flex items-center justify-between text-[11px] font-black flex-row-reverse">
                                     <span class="text-white/60">الاستقبال الأقصى</span>
                                     <span class="text-emerald-400 font-headline">{{ stats.peak_rx }} Mbps</span>
                                 </div>
                                 <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                     <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]" :style="`width: ${(stats.peak_rx / maxVal * 100)}%`"></div>
                                 </div>
                             </div>
                             <div class="space-y-3">
                                 <div class="flex items-center justify-between text-[11px] font-black flex-row-reverse">
                                     <span class="text-white/60">الإرسال الأقصى</span>
                                     <span class="text-indigo-400 font-headline">{{ stats.peak_tx }} Mbps</span>
                                 </div>
                                 <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                     <div class="h-full bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]" :style="`width: ${(stats.peak_tx / maxVal * 100)}%`"></div>
                                 </div>
                             </div>
                        </div>
                        <div class="mt-12 p-6 bg-white/5 rounded-2xl border border-white/10 flex items-center gap-5 flex-row-reverse">
                             <span class="material-symbols-outlined text-amber-400 text-[24px]">bolt</span>
                             <p class="text-[9px] font-bold leading-relaxed opacity-60 text-right">نم رصد أداء ذروة (Peak) خلال فترات الحمل العالي عبر كافة عقد القطاع.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>





