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

const trafficChart = ref(null);

onMounted(() => {
    const ctx = document.getElementById('trafficChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: props.revenueChart?.labels || ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '23:59'],
                datasets: [{
                    label: 'الإيرادات اليومية',
                    data: props.revenueChart?.data || [28, 35, 42, 38, 45, 40, 32],
                    borderColor: '#00355f',
                    backgroundColor: 'rgba(15, 76, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.03)' },
                        ticks: { font: { family: 'Manrope', size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Manrope', size: 10 } }
                    }
                }
            }
        });
    }
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR',
    }).format(value);
};
</script>

<template>
    <InstitutionalLayout title="لوحة القيادة">
        <!-- Metrics Row: Tonal Authority -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Clients -->
            <div class="surface-card p-6 rounded-lg">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-primary-fixed/30 rounded-lg">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1">group</span>
                    </div>
                    <span class="text-secondary text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span>
                        12%+
                    </span>
                </div>
                <p class="metric-label">العملاء النشطين</p>
                <h3 class="metric-value text-3xl mt-1">{{ stats.active_clients || 0 }}</h3>
            </div>

            <!-- Revenue -->
            <div class="surface-card p-6 rounded-lg">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-secondary-container/30 rounded-lg">
                        <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1">payments</span>
                    </div>
                    <span class="text-secondary text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span>
                        5%+
                    </span>
                </div>
                <p class="metric-label">إيرادات الشهر الحالي</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <h3 class="metric-value text-3xl">{{ stats.monthly_revenue || 0 }}</h3>
                    <span class="text-xs font-bold text-slate-400 font-headline">SAR</span>
                </div>
            </div>

            <!-- Towers -->
            <div class="surface-card p-6 rounded-lg">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-surface-container-highest rounded-lg">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1">cell_tower</span>
                    </div>
                    <span :class="['text-xs font-bold', networkStats.offline > 0 ? 'text-error' : 'text-secondary']">
                        {{ networkStats.offline > 0 ? 'تنبيه' : 'مستقر' }}
                    </span>
                </div>
                <p class="metric-label">إجمالي الأبراج</p>
                <h3 class="metric-value text-3xl mt-1">{{ stats.total_towers || 0 }}</h3>
            </div>

            <!-- Pending Tickets -->
            <div class="surface-card p-6 rounded-lg">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-error-container/30 rounded-lg">
                        <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1">confirmation_number</span>
                    </div>
                    <span class="text-error text-xs font-bold">عالي</span>
                </div>
                <p class="metric-label">تذاكر الدعم المفتوحة</p>
                <h3 class="metric-value text-3xl mt-1">24</h3>
            </div>
        </div>

        <!-- Bento Grid: Analytical Core -->
        <div class="grid grid-cols-12 gap-8">
            <!-- Traffic Graph (Col 1-8) -->
            <div class="col-span-12 lg:col-span-8 surface-card rounded-lg p-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-lg font-bold text-primary">تحليل حركة الشبكة</h2>
                        <p class="text-xs text-slate-400">تحديث مباشر لتغطية النطاق الترددي</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 text-xs font-bold bg-primary text-white rounded">24 ساعة</button>
                        <button class="px-3 py-1 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded">أسبوع</button>
                    </div>
                </div>
                <div class="h-64 w-full">
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>

            <!-- Recent Intelligent Alerts (Col 9-12) -->
            <div class="col-span-12 lg:col-span-4 surface-card rounded-lg p-8">
                <h2 class="text-lg font-bold text-primary mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-error">report</span>
                    تنبيهات الأنظمة
                </h2>
                <div class="space-y-4">
                    <div class="p-4 bg-error-container/10 border-r-4 border-error rounded-lg">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-error text-sm">برج القطاع الشمالي</h4>
                            <span class="text-[10px] text-slate-500 font-headline">منذ 12 دقيقة</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">فقدان الاتصال بالكامل بالمنطقة الشمالية. محولات الطاقة لا تستجيب.</p>
                    </div>
                    <div class="p-4 bg-amber-50 border-r-4 border-amber-500 rounded-lg">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-amber-700 text-sm">تجاوز الحمل: برج #12</h4>
                            <span class="text-[10px] text-slate-500 font-headline">منذ 45 دقيقة</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">وصول حركة المرور إلى 95% من السعة القصوى المتاحة.</p>
                    </div>
                </div>
                <button class="w-full mt-6 py-3 text-xs font-bold text-primary bg-primary-fixed/20 hover:bg-primary-fixed/40 transition-colors rounded">عرض كافة السجلات</button>
            </div>

            <!-- Recent Transactions Table (Full Width Bottom) -->
            <div class="col-span-12 surface-card rounded-lg overflow-hidden">
                <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-lg font-bold text-primary">سجل العمليات الأخير</h2>
                        <p class="text-xs text-slate-400">آخر التحصيلات والاشتراكات في المنصة</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="bg-surface-container-low/50">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">المشترك</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">المبلغ</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">التاريخ</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            <tr v-for="inv in recentInvoices" :key="inv.id" class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5">
                                    <p class="text-sm font-bold text-primary">{{ inv.client?.name }}</p>
                                    <p class="text-[10px] text-slate-400 font-headline mt-0.5">{{ inv.client?.username }}</p>
                                </td>
                                <td class="px-8 py-5 font-headline font-extrabold text-primary text-center">{{ inv.amount }} ر.س</td>
                                <td class="px-8 py-5 text-[11px] font-headline text-slate-500 text-center">{{ inv.created_at_human }}</td>
                                <td class="px-8 py-5 text-center">
                                    <span :class="['px-3 py-1 rounded text-[10px] font-bold', inv.status === 'paid' ? 'bg-secondary-container text-on-secondary-container' : 'bg-tertiary-fixed text-on-tertiary-fixed']">
                                        {{ inv.status === 'paid' ? 'تم التحصيل' : 'معلق' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
canvas {
    filter: drop-shadow(0 4px 12px rgba(0, 53, 95, 0.05));
}
</style>
