<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { Chart, registerables } from 'chart.js';

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
    new Chart(revenueChartRef.value, {
        type: 'line',
        data: {
            labels: props.months,
            datasets: [{
                label: 'الإيرادات الشهرية',
                data: props.chart_revenue,
                borderColor: '#0f172a',
                backgroundColor: 'rgba(15, 23, 42, 0.03)',
                borderWidth: 3,
                fill: true,
                tension: 0.1,
                pointRadius: 4,
                pointBackgroundColor: '#2563eb',
                pointBorderWidth: 2,
                pointBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    grid: { color: 'rgba(0,0,0,0.02)' },
                    ticks: { color: '#94a3b8', font: { size: 10, family: 'Manrope' } }
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#94a3b8', font: { size: 10, family: 'Manrope' } } 
                }
            }
        }
    });

    // Subscriber Acquisition Growth Chart
    new Chart(tenantsChartRef.value, {
        type: 'bar',
        data: {
            labels: props.months,
            datasets: [{
                label: 'المشتركين الجدد',
                data: props.chart_tenants,
                backgroundColor: '#2563eb',
                borderRadius: 6,
                barThickness: 12,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    grid: { color: 'rgba(0,0,0,0.02)' },
                    ticks: { color: '#94a3b8', font: { size: 10, family: 'Manrope' } }
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#94a3b8', font: { size: 10, family: 'Manrope' } } 
                }
            }
        }
    });
});

const formatNumber = (num) => {
    return new Intl.NumberFormat('ar-SY').format(num);
};

</script>

<template>
    <InstitutionalLayout title="مركز العمليات الذكي">
        <Head title="لوحة التحكم الإستراتيجية - MadaaQ" />
        
        <div class="max-w-[1600px] mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Strategic Header Intel -->
            <div class="mb-16 flex flex-col lg:flex-row lg:items-center justify-between gap-10 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">لوحة التحكم التكتيكية (Command Center)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">مراقبة حية لأداء الشبكة، بيانات المشتركين، وتحليل المؤشرات المالية اللحظية</p>
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                     <button class="px-8 py-3.5 bg-white border border-outline-variant/10 rounded-xl text-[11px] font-black text-slate-500 hover:text-primary transition-all flex items-center gap-3 shadow-sm active:scale-95">
                        <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                        تقارير الجلسة الحالية
                     </button>
                      <Link 
                        :href="route('crm.clients.create')" 
                        class="px-10 py-3.5 bg-primary text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all active:scale-95 flex items-center gap-3 border border-white/10"
                    >
                        <span class="material-symbols-outlined text-[20px]">add_circle</span> تفعيل وحدة مشتركة
                    </Link>
                </div>
            </div>

            <!-- Strategic KPIs Matrix -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Revenue Intelligence -->
                <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-2xl relative overflow-hidden group bg-white border-b-8 border-primary">
                    <div class="absolute -top-16 -left-16 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center border border-primary/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]">payments</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none">إجمالي الإيرادات (Revenue)</p>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <h3 class="text-4xl font-headline font-black text-primary leading-none tracking-tighter">{{ formatNumber(stats.total_revenue) }}</h3>
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none">ل.س</span>
                        </div>
                        <div class="flex items-center gap-3 justify-end flex-row-reverse">
                            <span class="text-[10px] font-black text-emerald-600 bg-emerald-500/10 px-3 py-1 rounded-lg border border-emerald-500/10 flex items-center gap-2">
                                +14.2% <span class="material-symbols-outlined text-[14px]">trending_up</span>
                            </span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">مقارنة بالأداء السابق</span>
                        </div>
                    </div>
                </div>

                <!-- Network Grid Operators -->
                <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-sm bg-white relative overflow-hidden border-b-8 border-slate-950">
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center border border-white/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]">hub</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none">مشغلي الشبكة النشطين</p>
                        </div>
                        <h3 class="text-5xl font-headline font-black text-slate-900 leading-none tracking-tighter">{{ stats.active_tenants }}</h3>
                        <div class="flex items-center gap-2 justify-end opacity-40">
                            <p class="text-[10px] font-black uppercase tracking-widest">من أصل <span class="font-headline text-slate-900">{{ stats.total_tenants }}</span> عقدة توزيع مـتاحة</p>
                        </div>
                    </div>
                </div>

                <!-- Subscription Integrity -->
                <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-sm bg-white relative overflow-hidden border-b-8 border-emerald-500">
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-emerald-500/5 text-emerald-600 flex items-center justify-center border border-emerald-500/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]">verified_user</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none">اشتراكات الخدمة الفعالة</p>
                        </div>
                        <h3 class="text-5xl font-headline font-black text-emerald-600 leading-none tracking-tighter">{{ stats.active_subscriptions }}</h3>
                        <div class="flex items-center gap-3 justify-end flex-row-reverse opacity-60">
                            <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse shadow-lg"></div>
                            <span class="text-[10px] text-emerald-600 font-black uppercase tracking-widest">نظام حوكمة الحزم النشط</span>
                        </div>
                    </div>
                </div>

                <!-- Aggregate Clients -->
                <div class="surface-card p-10 rounded-3xl border border-outline-variant/5 shadow-sm bg-white relative overflow-hidden border-b-8 border-indigo-600">
                    <div class="relative z-10 flex flex-col gap-8">
                        <div class="flex items-center justify-between flex-row-reverse">
                             <div class="w-14 h-14 rounded-2xl bg-indigo-500/5 text-indigo-600 flex items-center justify-center border border-indigo-500/10 shadow-inner">
                                <span class="material-symbols-outlined text-[32px]">groups</span>
                             </div>
                             <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none">قاعدة المشتركين المركزية</p>
                        </div>
                        <h3 class="text-5xl font-headline font-black text-indigo-600 leading-none tracking-tighter">{{ formatNumber(stats.total_clients) }}</h3>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest opacity-60">إجمالي الهويات المسجلة كلياً</p>
                    </div>
                </div>
            </div>

            <!-- Analytical Visual Intel Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-16">
                <!-- Primary Revenue Chart -->
                <div class="lg:col-span-2 surface-card rounded-[2.5rem] border border-outline-variant/5 shadow-2xl bg-white overflow-hidden">
                    <div class="p-10 flex items-center justify-between flex-row-reverse border-b border-outline-variant/5">
                        <div class="flex items-center gap-4">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em] leading-none">تحليل الأداء المالي (Growth Intelligence)</h3>
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        </div>
                        <span class="material-symbols-outlined text-slate-300">finance_mode</span>
                    </div>
                    <div class="p-10">
                        <div class="h-[350px] w-full">
                            <canvas ref="revenueChartRef"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Strategic Network Health -->
                <div class="surface-card rounded-[2.5rem] border border-outline-variant/5 shadow-2xl bg-slate-950 text-white overflow-hidden flex flex-col relative">
                    <div class="absolute -top-32 -left-32 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="p-10 flex items-center justify-between flex-row-reverse border-b border-white/5 relative z-10">
                        <h3 class="text-xs font-black uppercase tracking-[0.3em] leading-none">مؤشرات الجاهزية (NOC Stats)</h3>
                        <span class="material-symbols-outlined text-white/20">query_stats</span>
                    </div>
                    <div class="p-10 flex-1 space-y-10 relative z-10">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-[11px] font-black uppercase tracking-[0.2em] flex-row-reverse">
                                <span class="text-emerald-500">جودة الخدمة (QoS)</span>
                                <span class="font-headline text-lg">98.4%</span>
                            </div>
                            <div class="h-3 bg-white/5 rounded-full overflow-hidden border border-white/10 p-0.5">
                                <div class="h-full bg-emerald-500 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.5)]" style="width: 98.4%"></div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-[11px] font-black uppercase tracking-[0.2em] flex-row-reverse">
                                <span class="text-blue-400">سعة النطاق المستهلكة</span>
                                <span class="font-headline text-lg">64%</span>
                            </div>
                            <div class="h-3 bg-white/5 rounded-full overflow-hidden border border-white/10 p-0.5">
                                <div class="h-full bg-primary rounded-full shadow-[0_0_15px_rgba(37,99,235,0.5)]" style="width: 64%"></div>
                            </div>
                        </div>
                        <div class="pt-10 mt-10 border-t border-white/5 h-48">
                            <div class="flex items-center justify-between mb-6 flex-row-reverse">
                                 <p class="text-[10px] font-black text-white/30 uppercase tracking-widest">اتجاه اكتساب المشتركين</p>
                                 <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            </div>
                             <canvas ref="tenantsChartRef"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operational Activity Ledger -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 text-right">
                <!-- Recent Fiscal Transactions -->
                 <div class="lg:col-span-2 surface-card rounded-[2.5rem] border border-outline-variant/5 shadow-2xl bg-white overflow-hidden border-t-8 border-primary">
                    <div class="p-10 flex justify-between items-center flex-row-reverse border-b border-outline-variant/5">
                        <div class="flex items-center gap-4">
                            <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em] leading-none">أحدث القيود والعمليات المالية (Ledger)</h3>
                            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        </div>
                        <Link :href="route('accounting.invoices.index')" class="text-primary text-[10px] font-black uppercase tracking-widest hover:translate-x-[-4px] transition-transform flex items-center gap-2 flex-row-reverse">
                             تـدقيق السجل المالي العام <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </Link>
                    </div>
                    <div class="overflow-x-auto px-6 pb-6">
                        <table class="w-full text-right border-separate border-spacing-0">
                            <thead>
                                <tr class="text-slate-400">
                                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">بروتوكول السند</th>
                                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em]">الطرف المستفيد</th>
                                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-center">القيمة الاستحقاقية</th>
                                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-center">حالة السداد</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/5">
                                <tr v-for="invoice in recent_invoices" :key="invoice.id" class="hover:bg-surface-container-low/50 transition-all group">
                                    <td class="px-8 py-5 font-headline font-black text-primary tracking-widest">#{{ invoice.invoice_number }}</td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3 justify-end">
                                            <span class="text-sm font-black text-slate-700">{{ invoice.tenant?.name || 'مشترك عام إداري' }}</span>
                                            <span class="material-symbols-outlined text-[16px] text-slate-300">account_balance</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <div class="inline-flex items-center gap-2 font-headline font-black text-primary bg-slate-100 px-4 py-1.5 rounded-xl border border-slate-200">
                                            {{ formatNumber(invoice.amount) }}
                                            <span class="text-[8px] font-black text-slate-400 uppercase">ل.س</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <div 
                                            :class="[
                                                'inline-flex items-center gap-2 px-4 py-1.5 rounded-xl text-[9px] font-black uppercase border-2 shadow-sm transition-all group-hover:scale-105',
                                                invoice.status === 'paid' ? 'bg-emerald-500/5 text-emerald-600 border-emerald-500/10' : 'bg-rose-500/5 text-rose-500 border-rose-500/10'
                                            ]"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full bg-current" :class="invoice.status === 'paid' ? 'animate-pulse' : ''"></span>
                                            {{ invoice.status === 'paid' ? 'تمت التسوية' : 'قيد المطالبة' }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                 </div>

                 <!-- Regional Network Nodes Intel -->
                 <div class="surface-card rounded-[2.5rem] border border-outline-variant/5 shadow-2xl bg-white overflow-hidden border-t-8 border-slate-950">
                    <div class="p-10 flex justify-between items-center flex-row-reverse border-b border-outline-variant/5">
                        <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em] leading-none">موزعي الشبكة الإقليميين</h3>
                        <span class="material-symbols-outlined text-slate-300">corporate_fare</span>
                    </div>
                    <div class="divide-y divide-outline-variant/5 px-6 pb-6 h-[450px] overflow-y-auto custom-scrollbar">
                        <div v-for="tenant in recent_tenants" :key="tenant.id" class="p-6 hover:bg-surface-container-low transition-all flex items-center justify-between flex-row-reverse rounded-2xl">
                            <div class="flex items-center gap-5 flex-row-reverse text-right">
                                <div class="w-12 h-12 rounded-xl bg-slate-950 text-white flex items-center justify-center text-lg font-black shadow-2xl border border-white/10 group-hover:rotate-6 transition-transform">
                                    {{ tenant.name.substring(0,1) }}
                                </div>
                                <div>
                                    <div class="text-[14px] font-black text-primary leading-none">{{ tenant.name }}</div>
                                    <div class="text-[10px] text-slate-400 font-headline font-black mt-2 tracking-widest uppercase opacity-60">{{ tenant.domain }}</div>
                                </div>
                            </div>
                            <a :href="'http://' + tenant.domain" target="_blank" class="w-10 h-10 flex items-center justify-center bg-white border border-outline-variant/10 rounded-xl transition-all text-slate-400 hover:text-primary hover:scale-110 active:scale-90 shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">exit_to_app</span>
                            </a>
                        </div>
                        
                        <div class="p-6">
                             <button class="w-full py-5 border-2 border-dashed border-slate-200 rounded-[1.5rem] text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] hover:bg-slate-50 hover:text-primary hover:border-primary transition-all active:scale-95 group">
                                <span class="flex items-center justify-center gap-3">
                                    <span class="material-symbols-outlined text-[18px]">manage_accounts</span> إدارة قاعدة الموزعين الشاملة
                                </span>
                             </button>
                        </div>
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
.custom-scrollbar::-webkit-scrollbar {
    width: 3px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
}
</style>
