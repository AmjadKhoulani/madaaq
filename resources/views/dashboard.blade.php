@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">لوحة التحكم</h1>
            @if(auth()->user()->tenant)
                <p class="text-sm text-indigo-600 font-semibold mt-1">{{ auth()->user()->tenant->name }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
             <span class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300">
                <div class="w-2 h-2 rounded-full {{ $networkStats['online'] > 0 ? 'bg-green-500' : 'bg-red-500' }} animate-pulse mr-2"></div>
                الشبكة {{ $networkStats['online'] > 0 ? 'متصلة' : 'مفصولة' }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Clients -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">إجمالي العملاء</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_clients']) }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $stats['active_clients'] }} نشط
                </span>
                <span class="text-gray-400 mx-2">•</span>
                <span class="text-gray-500">المشتركين الحاليين</span>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">الإيرادات (الشهر)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">@money($stats['monthly_revenue'])</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-orange-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                     @money($stats['pending_amount']) معلقة
                </span>
                <span class="text-gray-400 mx-2">•</span>
                <span class="text-gray-500">فواتير غير مدفوعة</span>
            </div>
        </div>

        <!-- Network Status -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">أجهزة الشبكة</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $networkStats['online'] }} <span class="text-lg text-gray-400 font-normal">/ {{ $networkStats['total'] }}</span></p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="{{ $networkStats['online'] == $networkStats['total'] ? 'text-green-600' : 'text-orange-600' }} font-medium flex items-center gap-1">
                    {{ number_format(($networkStats['online'] / max($networkStats['total'], 1)) * 100, 0) }}%
                </span>
                <span class="text-gray-500 mr-1">نسبة التوافر</span>
            </div>
        </div>

        <!-- Bandwidth -->
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">الاستهلاك (24 ساعة)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format(($bandwidthToday->total_rx ?? 0) / 1024 / 1024 / 1024, 1) }} <span class="text-lg text-gray-400 font-normal">GB</span></p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-indigo-600 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    Download
                </span>
                <span class="text-gray-400 mx-2">•</span>
                <span class="text-gray-500">حركة البيانات</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">تحليل الإيرادات (آخر 7 أيام)</h3>
            <div style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Clients Growth Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">نمو المشتركين (آخر 7 أيام)</h3>
            <div style="height: 300px;">
                <canvas id="clientsChart"></canvas>
            </div>
        </div>

        <!-- Alerts & Activities -->
        <div class="space-y-6">
            <!-- Alerts -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">التنبيهات النشطة</h3>
                    <a href="{{ route('network.monitoring.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">عرض الكل</a>
                </div>
                <div class="space-y-4">
                    @forelse($activeAlerts->take(3) as $alert)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $alert->message }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $alert->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">لا توجد تنبيهات نشطة حالياً</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Websites -->
             <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">أكثر المواقع زيارة</h3>
                    <a href="{{ route('network.website.analytics') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">التفاصيل</a>
                </div>
                <ul role="list" class="divide-y divide-gray-100">
                    @forelse($topWebsites->take(5) as $website)
                    <li class="flex items-center justify-between gap-x-6 py-2">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $website->domain }}</p>
                        </div>
                        <div class="flex flex-none items-center gap-x-4">
                            <span class="rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">{{ number_format($website->total_hits) }} زيارة</span>
                        </div>
                    </li>
                    @empty
                     <p class="text-sm text-gray-500 text-center py-4">لا توجد بيانات متاحة</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Clients -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900">آخر المشتركين</h3>
                <a href="{{ route('crm.clients.index') }}" class="text-sm text-blue-600 hover:text-blue-500">عرض الكل</a>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($recentClients as $client)
                <li class="p-4 hover:bg-gray-50 transition">
                    <div class="flex justify-between gap-x-3">
                        <div class="min-w-0 flex flex-col gap-1">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $client->name }}</p>
                            <p class="text-xs text-gray-500">{{ $client->username }}</p>
                        </div>
                        <div class="flex items-center gap-x-2">
                             <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ $client->package->name ?? 'مخصص' }}</span>
                        </div>
                    </div>
                </li>
                @empty
                <li class="p-4 text-center text-gray-500 text-sm">لا يوجد مشتركين جدد</li>
                @endforelse
            </ul>
        </div>

        <!-- Recent Invoices -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900">آخر الفواتير</h3>
                <a href="{{ route('accounting.invoices.index') }}" class="text-sm text-blue-600 hover:text-blue-500">عرض الكل</a>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($recentInvoices as $invoice)
                <li class="p-4 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-900">#{{ $invoice->invoice_number }}</p>
                            <p class="text-xs text-gray-500">{{ $invoice->client->name ?? 'عميل محذوف' }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-sm font-bold text-gray-900">@money($invoice->amount)</p>
                             <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $invoice->status == 'paid' ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-yellow-50 text-yellow-800 ring-yellow-600/20' }}">
                                {{ $invoice->status == 'paid' ? 'مدفوعة' : 'انتظار' }}
                             </span>
                        </div>
                    </div>
                </li>
                @empty
                 <li class="p-4 text-center text-gray-500 text-sm">لا توجد فواتير حديثة</li>
                @endforelse
            </ul>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
             <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-gray-900">تنتهي قريباً (7 أيام)</h3>
            </div>
            <ul role="list" class="divide-y divide-gray-100">
                @forelse($expiringClients as $client)
                <li class="p-4 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-center">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $client->name }}</p>
                             <p class="text-xs text-red-500 font-medium">ينتهي: {{ \Carbon\Carbon::parse($client->expires_at)->format('Y-m-d') }}</p>
                        </div>
                        <a href="{{ route('crm.clients.renew', $client) }}" class="rounded bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-600 shadow-sm hover:bg-indigo-100">تجديد</a>
                    </div>
                </li>
                @empty
                 <li class="p-4 text-center text-gray-500 text-sm">لا توجد اشتراكات تنتهي قريباً</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($revenueChart['labels']),
        datasets: [{
            label: 'الإيرادات',
            data: @json($revenueChart['data']),
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79, 70, 229, 0.05)',
            borderWidth: 2,
            tension: 0.3,
            fill: true,
            pointRadius: 3,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#4f46e5',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1f2937',
                padding: 12,
                titleFont: { family: "'Tajawal', sans-serif" },
                bodyFont: { family: "'Tajawal', sans-serif" },
                callbacks: {
                     label: function(context) {
                        return ' ' + context.parsed.y + ' {{ \App\Models\Setting::getValue('currency', 'SAR') }}';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' },
                ticks: {
                    font: { family: "'Tajawal', sans-serif" },
                    color: '#6b7280'
                },
                border: { display: false }
            },
            x: {
                grid: { display: false },
                ticks: {
                    font: { family: "'Tajawal', sans-serif" },
                    color: '#6b7280'
                },
                border: { display: false }
            }
        },
        interaction: { intersect: false, mode: 'index' },
    }
});

// Clients Growth Chart
const clientsCtx = document.getElementById('clientsChart').getContext('2d');
new Chart(clientsCtx, {
    type: 'bar',
    data: {
        labels: @json($clientsChart['labels']),
        datasets: [{
            label: 'مشتركين جدد',
            data: @json($clientsChart['data']),
            backgroundColor: '#10b981',
            borderRadius: 4,
            barThickness: 20
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1f2937',
                padding: 12,
                titleFont: { family: "'Tajawal', sans-serif" },
                bodyFont: { family: "'Tajawal', sans-serif" }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' },
                ticks: {
                    stepSize: 1,
                    font: { family: "'Tajawal', sans-serif" },
                    color: '#6b7280'
                },
                border: { display: false }
            },
            x: {
                grid: { display: false },
                ticks: {
                    font: { family: "'Tajawal', sans-serif" },
                    color: '#6b7280'
                },
                border: { display: false }
            }
        }
    }
});
</script>
@endsection
