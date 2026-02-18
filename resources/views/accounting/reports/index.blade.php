@extends('layouts.app')

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('financialCharts', () => ({
            init() {
                this.renderRevenueChart();
                this.renderSourceChart();
                this.renderExpensesChart();
            },
            
            renderRevenueChart() {
                const ctx = document.getElementById('revenueChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($months),
                        datasets: [
                            {
                                label: 'الإيرادات',
                                data: @json($revenueData),
                                borderColor: '#10B981', // Emerald 500
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'المصاريف',
                                data: @json($expensesData),
                                borderColor: '#EF4444', // Red 500
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            },

            renderSourceChart() {
                const ctx = document.getElementById('sourceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['هوت سبوت', 'برودباند'],
                        datasets: [{
                            data: [@json($revenuePieData['hotspot']), @json($revenuePieData['broadband'])],
                            backgroundColor: ['#8B5CF6', '#3B82F6'], // Purple, Blue
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            },

            renderExpensesChart() {
                const ctx = document.getElementById('expensesChart').getContext('2d');
                const breakdown = @json($expenseBreakdown);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['الكهرباء', 'الوقود', 'الصيانة', 'الآجار'],
                        datasets: [{
                            label: 'المصاريف',
                            data: [breakdown.electricity, breakdown.fuel, breakdown.maintenance, breakdown.rent],
                            backgroundColor: ['#F59E0B', '#EF4444', '#6366F1', '#10B981'],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="space-y-6" x-data="financialCharts">
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .dark .gradient-text {
            background: linear-gradient(135deg, #818cf8 0%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold gradient-text">التقارير المالية 📊</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">نظرة شاملة على أداء الشبكة المالي باحترافية</p>
        </div>
        <div class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full dark:bg-slate-700 dark:text-slate-300">
            السنة المالية: {{ date('Y') }}
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Revenue -->
        <div class="glass p-5 rounded-2xl border-l-4 border-green-500">
            <div class="text-slate-500 dark:text-slate-400 text-sm mb-1">إيرادات السنة الحالية</div>
            <div class="text-2xl font-bold text-slate-800 dark:text-white">@money($thisYearRevenue)</div>
            <div class="text-xs text-green-600 mt-2 font-medium">
                +@money($thisMonthRevenue) هذا الشهر
            </div>
        </div>

        <!-- Profit -->
        <div class="glass p-5 rounded-2xl border-l-4 border-blue-500">
            <div class="text-slate-500 dark:text-slate-400 text-sm mb-1">صافي الربح (التقريبي)</div>
            <div class="text-2xl font-bold text-blue-600">@money($thisYearProfit)</div>
            <div class="text-xs text-slate-400 mt-2">الإيرادات - مصاريف الأبراج</div>
        </div>

        <!-- Expenses -->
        <div class="glass p-5 rounded-2xl border-l-4 border-red-500">
            <div class="text-slate-500 dark:text-slate-400 text-sm mb-1">إجمالي المصاريف</div>
            <div class="text-2xl font-bold text-red-600">@money($thisYearExpenses)</div>
            <div class="text-xs text-red-400 mt-2 font-medium">
                مصاريف تشغيلية للأبراج
            </div>
        </div>

        <!-- Debts -->
        <div class="glass p-5 rounded-2xl border-l-4 border-orange-500">
            <div class="text-slate-500 dark:text-slate-400 text-sm mb-1">الديون المستحقة</div>
            <div class="text-2xl font-bold text-orange-600">@money($totalDebts)</div>
            <div class="text-xs text-orange-400 mt-2">فواتير لم يتم سدادها</div>
        </div>
    </div>

    <!-- Capital Assets Section -->
    <h2 class="text-lg font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        الأصول الرأسمالية (Capital Assets)
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Structure Costs -->
        <div class="glass p-5 rounded-2xl border-l-4 border-indigo-500 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative">
                <div class="text-slate-500 dark:text-slate-400 text-sm mb-1 font-medium">قيمة البنية التحتية (الهيكل)</div>
                <div class="text-3xl font-bold text-indigo-700 mt-1">@money($totalStructureCost)</div>
                <div class="text-xs text-indigo-500 mt-2 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    استثمار ثابت في الأبراج
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Revenue Chart -->
        <div class="lg:col-span-2 glass rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-bold gradient-text mb-4">📈 نمو الإيرادات والمصاريف (شهرياً)</h3>
            <canvas id="revenueChart" height="250" class="w-full"></canvas>
        </div>

        <!-- Source Breakdown -->
        <div class="glass rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-bold gradient-text mb-4">📊 توزيع الإيرادات (المصدر)</h3>
            <div class="h-64 flex items-center justify-center">
                <canvas id="sourceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Expenses Breakdown -->
        <div class="glass rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-bold gradient-text mb-4">💸 تحليل المصاريف</h3>
            <canvas id="expensesChart" height="200"></canvas>
        </div>

        <!-- Top Towers Table -->
        <div class="glass rounded-2xl p-6 shadow-sm overflow-hidden">
            <h3 class="text-xl font-bold gradient-text mb-4">🏆 أعلى الأبراج دخلاً</h3>
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500">
                    <tr>
                        <th class="px-4 py-3 rounded-r-lg">البرج</th>
                        <th class="px-4 py-3 rounded-l-lg">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($topTowers as $tower)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-4 py-3 font-medium text-slate-700 dark:text-slate-200">{{ $tower->name }}</td>
                        <td class="px-4 py-3 font-bold text-green-600">@money($tower->total)</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-4 py-4 text-center text-slate-400">لا توجد بيانات كافية</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
