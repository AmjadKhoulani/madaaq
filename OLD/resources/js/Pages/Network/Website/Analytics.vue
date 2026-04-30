<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    topWebsites: Array,
    period: String
});

const form = useForm({
    domain: '',
    type: 'domain',
    reason: 'Restricted from Top BarChart3'
});

const blockDomain = (domain) => {
    form.domain = domain;
    if(confirm(`هل أنت متأكد من رغبتك في تفعيل بروتوكول الحظر السيادي للنطاق ${domain} عبر كافة عقد الشبكة المركزية؟`)) {
        form.post(route('network.website.block'), {
            preserveScroll: true,
            onSuccess: () => {
                // Success logic handled by controller flash
            }
        });
    }
};

const formatNumber = (num) => {
    return new Intl.NumberFormat('en-US').format(num);
};

</script>

<template>
    <InstitutionalLayout title="استخبارات الويب العالمية">
        <Head title="تحليلات العنونة العالمية - MadaaQ" />

        <div class="max-w-[1400px] mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Intelligence Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                     <h1 class="text-4xl font-black text-primary tracking-tight mb-2 uppercase">تحليلات العنونة والارتباط (Resolution Intel)</h1>
                     <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">ذكاء طلبيات <span class="text-primary font-headline">DNS</span> التراكمي لأعلى هويات النطاقات المستخرجة عبر العقد</p>
                        <span class="material-symbols-outlined text-primary text-[24px]">troubleshoot</span>
                     </div>
                </div>
                
                <div class="flex items-center gap-3 p-2 bg-white rounded-2xl border border-outline-variant/10 shadow-2xl">
                    <Link :href="route('network.website.BarChart3', { period: '7d' })" 
                          class="px-8 py-3 rounded-xl text-[10px] font-black transition-all text-center uppercase tracking-[0.2em]"
                          :class="period === '7d' ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-slate-400 hover:text-primary hover:bg-slate-50'">
                        دورة 07 أيام
                    </Link>
                    <Link :href="route('network.website.BarChart3', { period: '30d' })" 
                          class="px-8 py-3 rounded-xl text-[10px] font-black transition-all text-center uppercase tracking-[0.2em]"
                          :class="period === '30d' ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-slate-400 hover:text-primary hover:bg-slate-50'">
                        دورة 30 يوماً
                    </Link>
                </div>
            </div>

            <!-- Resolution BarChart3 Ledger -->
            <div class="surface-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-outline-variant/5 bg-white">
                <div class="p-10 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between flex-row-reverse">
                    <div class="flex items-center gap-4 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">تصنيف الاستخراج السيادي (DNS Ranking)</h3>
                        <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                    </div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-4">
                        <span>أعلى 100 هوية مستهدفة</span>
                        <span class="material-symbols-outlined text-slate-300 text-[20px]">leaderboard</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-50/20 text-slate-400">
                                <th class="p-8 text-[11px] font-black uppercase tracking-[0.2em] text-center w-32">الرتبة</th>
                                <th class="p-8 text-[11px] font-black uppercase tracking-[0.2em]">هوية النطاق (Resolution Host)</th>
                                <th class="p-8 text-[11px] font-black uppercase tracking-[0.2em]">كثافة الطلبات التراكمية</th>
                                <th class="p-8 text-[11px] font-black uppercase tracking-[0.2em] text-left">بروتوكول الردع (Action)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="(website, index) in topWebsites" :key="website.domain" class="hover:bg-slate-50/80 transition-all group">
                                <td class="p-8 text-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto text-[14px] font-black font-headline shadow-inner border transition-all group-hover:scale-110"
                                         :class="{
                                             'bg-amber-100 text-amber-700 border-amber-200 shadow-amber-200/50': index === 0,
                                             'bg-slate-200 text-slate-700 border-slate-300 shadow-slate-300/50': index === 1,
                                             'bg-orange-100 text-orange-700 border-orange-200 shadow-orange-200/50': index === 2,
                                             'bg-slate-50 text-slate-400 border-slate-100 group-hover:text-primary transition-colors': index > 2
                                         }">
                                         {{ index + 1 }}
                                    </div>
                                </td>
                                <td class="p-8 text-right">
                                    <div class="flex items-center gap-6 flex-row-reverse">
                                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-2xl shadow-inner border transition-all group-hover:rotate-6"
                                             :class="index < 3 ? 'bg-primary/5 text-primary border-primary/10' : 'bg-slate-50 text-slate-300 border-slate-100'">
                                            {{ website.domain.substring(0, 1).toUpperCase() }}
                                        </div>
                                        <div class="text-right">
                                            <p class="font-black text-base text-primary group-hover:text-slate-900 transition-colors tracking-tight">{{ website.domain }}</p>
                                            <div class="flex items-center gap-2 justify-end mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                 <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">VERIFIED_RESOLVER_NODE</span>
                                                 <span class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-8">
                                    <div class="flex items-center gap-4 justify-end">
                                        <div class="text-right">
                                            <span class="font-headline text-lg font-black text-slate-900 tracking-tighter">{{ formatNumber(website.total_hits) }}</span>
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mt-1">طلبات عنونة (Hits)</span>
                                        </div>
                                        <span class="material-symbols-outlined text-slate-300 text-[24px]">cloud_sync</span>
                                    </div>
                                </td>
                                <td class="p-8 text-left">
                                    <button @click="blockDomain(website.domain)" class="px-8 py-3.5 bg-white border-2 border-rose-500/10 text-rose-500 hover:bg-rose-600 hover:text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl transition-all active:scale-90 inline-flex items-center gap-4 shadow-sm group/btn">
                                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover/btn:rotate-180">block</span>
                                        تفعيل بروتوكول الحظر
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="topWebsites.length === 0">
                                <td colspan="4" class="p-48 text-center">
                                    <div class="flex flex-col items-center gap-8 opacity-40 grayscale group/empty">
                                        <div class="w-24 h-24 rounded-[2rem] bg-slate-100 flex items-center justify-center shadow-inner group-hover/empty:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-[64px] font-light">data_alert</span>
                                        </div>
                                        <h4 class="text-2xl font-black text-primary uppercase tracking-tight">قاعدة البيانات شاغرة حالياً</h4>
                                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] italic">لم يتم رصد أي مسارات عنونة نشطة ضمن الجدول الزمني المحدد</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Intelligence Advisory (Sovereign Alert) -->
            <div class="mt-16 p-10 rounded-[2.5rem] bg-slate-950 text-white flex items-start gap-8 flex-row-reverse text-right shadow-2xl relative overflow-hidden group">
                <div class="absolute -top-16 -left-16 w-64 h-64 bg-primary/20 rounded-full blur-3xl group-hover:scale-150 transition-all duration-1000"></div>
                <div class="w-16 h-16 bg-white/5 text-primary rounded-2xl flex items-center justify-center shrink-0 border border-white/10 shadow-inner relative z-10 transition-transform group-hover:rotate-12">
                    <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1">info</span>
                </div>
                <div class="text-right relative z-10">
                    <h4 class="text-sm font-black text-white/40 mb-4 uppercase tracking-[0.3em]">تنبيه مصفوفة التحليل السيادي (Advisory)</h4>
                    <p class="text-sm font-bold text-white/80 leading-relaxed max-w-4xl">
                        يتم اشتقاق هذه البيانات عبر استخراج لقطات الذاكرة المؤقتة للعنونة (<span class="text-primary font-headline">DNS Cache Snapshots</span>) من كافة العقد البنيوية النشطة. تعبر الإحصائيات عن كثافة طلبات المسارات اللحظية ولا تمثل بالضرورة حجم الاستهلاك الفعلي (<span class="text-primary font-headline">Traffic Volume</span>) المرتبط بكل نطاق.
                    </p>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>



