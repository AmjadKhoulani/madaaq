@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold gradient-text">لوحة تحكم المزود (Vendor Dashboard)</h1>
            @if(auth()->user()->tenant)
                <p class="text-indigo-600 font-semibold text-lg mt-1 decoration-skip-ink-none">{{ auth()->user()->tenant->name }}</p>
            @endif
            <p class="text-gray-500 mt-1">نظرة شاملة على أداء الشبكة والعمل باحترافية</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Clients -->
        <div class="glass rounded-xl p-6 shadow-lg border border-white/30 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">إجمالي العملاء</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_clients']) }}</p>
                    <p class="text-xs text-green-600 mt-1">✓ {{ $stats['active_clients'] }} نشط</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="glass rounded-xl p-6 shadow-lg border border-white/30 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">الإيرادات (الشهر)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">@money($stats['monthly_revenue'])</p>
                    <p class="text-xs text-orange-600 mt-1">⏳ @money($stats['pending_amount']) معلقة</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Network Status -->
        <div class="glass rounded-xl p-6 shadow-lg border border-white/30 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">أجهزة الشبكة</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $networkStats['online'] }}/{{ $networkStats['total'] }}</p>
                    <p class="text-xs text-green-600 mt-1">⚡ متصل</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                </div>
            </div>
        </div>

        <!-- Bandwidth -->
        <div class="glass rounded-xl p-6 shadow-lg border border-white/30 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">الاستهلاك (24 ساعة)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format(($bandwidthToday->total_rx ?? 0) / 1024 / 1024 / 1024, 1) }} GB</p>
                    <p class="text-xs text-blue-600 mt-1">↓ Download</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Active Alerts -->
        <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">🚨 التنبيهات النشطة</h3>
                <a href="{{ route('network.monitoring.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-semibold">عرض الكل →</a>
            </div>
            <div class="space-y-3">
                @forelse($activeAlerts as $alert)
                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm font-semibold text-red-800">{{ $alert->message }}</p>
                    <p class="text-xs text-red-600 mt-1">{{ $alert->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm">لا توجد تنبيهات</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Websites -->
        <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold gradient-text">🌐 أكثر المواقع تصفحاً</h3>
                <a href="{{ route('network.website.analytics') }}" class="text-sm text-purple-600 hover:text-purple-700 font-semibold">عرض الكل →</a>
            </div>
            <div class="space-y-2">
                @forelse($topWebsites as $index => $website)
                <div class="flex items-center justify-between p-2 hover:bg-purple-50 rounded-lg transition">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 bg-purple-600 text-white rounded-full flex items-center justify-center text-xs font-bold">{{ $index + 1 }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $website->domain }}</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ number_format($website->total_hits) }}</span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">لا توجد بيانات</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">⏰ اشتراكات منتهية قريباً</h3>
                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded-full">{{ $stats['expiring_soon'] }}</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse($expiringClients->take(5) as $client)
                <div class="p-2 border border-orange-100 rounded-lg hover:bg-orange-50 transition">
                    <p class="text-sm font-semibold text-gray-900">{{ $client->name }}</p>
                    <p class="text-xs text-orange-600">ينتهي: {{ $client->expires_at->format('Y-m-d') }}</p>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-sm">لا توجد اشتراكات منتهية</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Revenue Chart & Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📈 الإيرادات (آخر 7 أيام)</h3>
            <div style="height: 250px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📋 آخر النشاطات</h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @foreach($recentClients->take(5) as $client)
                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($client->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $client->name }}</p>
                        <p class="text-xs text-gray-500">عميل جديد • {{ $client->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach

                @foreach($recentInvoices->take(3) as $invoice)
                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">فاتورة @money($invoice->amount)</p>
                        <p class="text-xs text-gray-500">{{ $invoice->client->name ?? 'N/A' }} • {{ $invoice->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold rounded-full {{ $invoice->status === 'paid' ? 'bg-green-200 text-green-800' : 'bg-orange-200 text-orange-800' }}">
                        {{ $invoice->status === 'paid' ? 'مدفوع' : 'معلق' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- System Health & Network Map -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- System Health -->
        <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
            <h3 class="text-lg font-bold text-gray-900 mb-4">💚 صحة النظام</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700">قاعدة البيانات</span>
                    </div>
                    <span class="text-xs font-bold text-green-600">✓ سليم</span>
                </div>

                <div class="flex items-center justify-between p-3 {{ $networkStats['online'] > 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 {{ $networkStats['online'] > 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700">الشبكة</span>
                    </div>
                    <span class="text-xs font-bold {{ $networkStats['online'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $networkStats['online'] > 0 ? '✓ متصلة' : '✗ معطلة' }}
                    </span>
                </div>

                <div class="flex items-center justify-between p-3 {{ $activeAlerts->count() === 0 ? 'bg-green-50' : 'bg-orange-50' }} rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 {{ $activeAlerts->count() === 0 ? 'bg-green-500' : 'bg-orange-500' }} rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700">التنبيهات</span>
                    </div>
                    <span class="text-xs font-bold {{ $activeAlerts->count() === 0 ? 'text-green-600' : 'text-orange-600' }}">
                        {{ $activeAlerts->count() }} نشط
                    </span>
                </div>

                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700">API</span>
                    </div>
                    <span class="text-xs font-bold text-blue-600">✓ جاهز</span>
                </div>
            </div>
        </div>

        <!-- Network Overview -->
        <div class="lg:col-span-2 glass rounded-2xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">🗺️ نظرة عامة على الشبكة</h3>
                <a href="{{ route('network.topology.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-semibold">عرض الخريطة →</a>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-purple-100 to-blue-100 rounded-xl">
                    <svg class="w-8 h-8 mx-auto mb-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z"/></svg>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_routers'] }}</p>
                    <p class="text-xs text-gray-600 font-medium">Routers</p>
                </div>

                <div class="text-center p-4 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl">
                    <svg class="w-8 h-8 mx-auto mb-2 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM10 7a3 3 0 100 6 3 3 0 000-6zM15.657 5.404a.75.75 0 10-1.06-1.06l-1.061 1.06a.75.75 0 001.06 1.06l1.06-1.06zM6.464 14.596a.75.75 0 10-1.06-1.06l-1.06 1.06a.75.75 0 001.06 1.06l1.06-1.06zM18 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0118 10zM5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 015 10zM14.596 15.657a.75.75 0 001.06-1.06l-1.06-1.061a.75.75 0 10-1.06 1.06l1.06 1.06zM5.404 6.464a.75.75 0 001.06-1.06l-1.06-1.06a.75.75 0 10-1.06 1.06l1.06 1.06z"/></svg>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_towers'] }}</p>
                    <p class="text-xs text-gray-600 font-medium">Towers</p>
                </div>

                <div class="text-center p-4 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl">
                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                    <p class="text-2xl font-bold text-gray-900">{{ $networkStats['online'] }}</p>
                    <p class="text-xs text-gray-600 font-medium">Online</p>
                </div>
            </div>

            <div class="mt-4 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                <p class="text-sm text-purple-800">
                    <strong>💡 نصيحة:</strong> افحص الخريطة التفاعلية لمراقبة حالة جميع الأجهزة في الوقت الفعلي
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
        <h3 class="text-lg font-bold text-gray-900 mb-4">⚡ إجراءات سريعة</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('crm.clients.create') }}" class="p-4 bg-gradient-to-br from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl shadow-lg transition text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <p class="text-sm font-bold">عميل جديد</p>
            </a>

            <a href="{{ route('network.monitoring.index') }}" class="p-4 bg-gradient-to-br from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg transition text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <p class="text-sm font-bold">مراقبة الشبكة</p>
            </a>

            <a href="{{ route('network.monitoring.bandwidth') }}" class="p-4 bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl shadow-lg transition text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <p class="text-sm font-bold">استهلاك Bandwidth</p>
            </a>

            <a href="{{ route('network.website.blocked') }}" class="p-4 bg-gradient-to-br from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700 text-white rounded-xl shadow-lg transition text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                <p class="text-sm font-bold">حظر المواقع</p>
            </a>
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
            label: 'الإيرادات ({{ $currency }})',
            data: @json($revenueChart['data']),
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#8b5cf6'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '{{ $currency }}' + value;
                    }
                }
            }
        }
    }
});
</script>
@endsection
