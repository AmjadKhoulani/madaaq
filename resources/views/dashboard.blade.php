@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black leading-tight text-gray-900 tracking-tight">لوحة التحكم</h1>
            @if(auth()->user()->tenant)
                <p class="text-[11px] uppercase tracking-widest text-indigo-600 font-bold mt-1.5 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    {{ auth()->user()->tenant->name }}
                </p>
            @endif
        </div>
        <div class="flex items-center gap-3">
             <span class="inline-flex items-center rounded-2xl bg-white/50 backdrop-blur-md px-4 py-2 text-sm font-bold text-gray-900 shadow-sm border border-white/20">
                <div class="w-2.5 h-2.5 rounded-full {{ $networkStats['online'] > 0 ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]' : 'bg-red-500' }} animate-pulse ml-2.5"></div>
                الشبكة العامه: {{ $networkStats['online'] > 0 ? 'متصلة' : 'مفصولة' }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Clients -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي العملاء</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['total_clients']) }}</p>
                
                <div class="mt-4 flex items-center text-[11px] font-bold">
                    <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-100 flex items-center gap-1">
                        {{ $stats['active_clients'] }} نشط
                    </span>
                    <span class="text-gray-400 mx-2">•</span>
                    <span class="text-gray-500 uppercase tracking-tighter">المشتركين الحاليين</span>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-green-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-500/10 rounded-full blur-3xl group-hover:bg-green-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-green-600/10 flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">الإيرادات (الشهر)</p>
                <p class="text-3xl font-black text-gray-900">@money($stats['monthly_revenue'])</p>
                
                <div class="mt-4 flex items-center text-[11px] font-bold">
                    <span class="text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full border border-orange-100 flex items-center gap-1">
                         @money($stats['pending_amount']) معلقة
                    </span>
                </div>
            </div>
        </div>

        <!-- Network Status -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-purple-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600/10 flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">أجهزة الشبكة</p>
                <p class="text-3xl font-black text-gray-900">{{ $networkStats['online'] }} <span class="text-lg text-gray-400 font-normal">/ {{ $networkStats['total'] }}</span></p>
                
                <div class="mt-4 flex items-center text-[11px]">
                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-600 rounded-full" style="width: {{ ($networkStats['online'] / max($networkStats['total'], 1)) * 100 }}%"></div>
                    </div>
                    <span class="mr-3 font-bold text-gray-900">{{ number_format(($networkStats['online'] / max($networkStats['total'], 1)) * 100, 0) }}%</span>
                </div>
            </div>
        </div>

        <!-- Bandwidth -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600/10 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">الاستهلاك (24 ساعة)</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format(($bandwidthToday->total_rx ?? 0) / 1024 / 1024 / 1024, 1) }} <span class="text-lg text-gray-400 font-normal font-inter">GB</span></p>
                
                <div class="mt-4 flex items-center text-[11px] font-bold text-indigo-600 uppercase tracking-tighter">
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    Data Traffic Flow
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Revenue Chart -->
        <div class="lg:col-span-1 glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl">
            <h3 class="text-lg font-black text-gray-900 mb-8 flex items-center justify-between">
                تحليل الإيرادات
                <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
            </h3>
            <div style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Clients Growth Chart -->
        <div class="lg:col-span-1 glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl">
            <h3 class="text-lg font-black text-gray-900 mb-8 flex items-center justify-between">
                نمو المشتركين
                <span class="w-3 h-3 rounded-full bg-indigo-500 animate-pulse"></span>
            </h3>
            <div style="height: 300px;">
                <canvas id="clientsChart"></canvas>
            </div>
        </div>

        <!-- Alerts & Activities -->
        <div class="space-y-8">
            <!-- Alerts -->
            <div class="glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-red-500/5 rounded-full blur-2xl"></div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-gray-900">التنبيهات النشطة</h3>
                    <a href="{{ route('network.monitoring.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-500 uppercase tracking-widest">عرض الكل</a>
                </div>
                <div class="space-y-5">
                    @forelse($activeAlerts->take(3) as $alert)
                    <div class="flex gap-4 p-3 rounded-2xl hover:bg-white/50 transition-colors border border-transparent hover:border-white/20">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $alert->message }}</p>
                            <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase">{{ $alert->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">لا توجد تنبيهات نشطة</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Top Websites -->
             <div class="glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl"></div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-gray-900">أكثر المواقع زيارة</h3>
                    <a href="{{ route('network.website.analytics') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-500 uppercase tracking-widest">التفاصيل</a>
                </div>
                <div class="space-y-4">
                    @forelse($topWebsites->take(5) as $website)
                    <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-white/50 transition-colors border border-transparent hover:border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 text-[10px] font-black uppercase">
                                {{ substr($website->domain, 0, 2) }}
                            </div>
                            <span class="text-sm font-bold text-gray-700 truncate max-w-[150px]">{{ $website->domain }}</span>
                        </div>
                        <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">{{ number_format($website->total_hits) }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest text-center py-6">لا توجد بيانات تحليلية</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Recent Clients -->
        <div class="glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-gray-900">آخر المشتركين</h3>
                <a href="{{ route('crm.clients.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-500 uppercase tracking-widest">عرض الكل</a>
            </div>
            <div class="space-y-4">
                @forelse($recentClients as $client)
                <div class="flex justify-between items-center p-3 rounded-2xl hover:bg-white/50 transition-colors border border-transparent hover:border-white/20">
                    <div class="min-w-0 flex flex-col">
                        <p class="text-sm font-black text-gray-900 truncate">{{ $client->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $client->username }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-xl bg-blue-50 px-3 py-1 text-[10px] font-black text-blue-600">{{ $client->package->name ?? 'مخصص' }}</span>
                </div>
                @empty
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest text-center py-10">لا يوجد مشتركين جدد</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-green-500/5 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-gray-900">آخر الفواتير</h3>
                <a href="{{ route('accounting.invoices.index') }}" class="text-xs font-bold text-green-600 hover:text-green-500 uppercase tracking-widest">عرض الكل</a>
            </div>
            <div class="space-y-4">
                @forelse($recentInvoices as $invoice)
                <div class="flex justify-between items-center p-3 rounded-2xl hover:bg-white/50 transition-colors border border-transparent hover:border-white/20">
                    <div>
                        <p class="text-sm font-black text-gray-900">#{{ $invoice->invoice_number }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase truncate max-w-[100px]">{{ $invoice->client->name ?? 'عميل محذوف' }}</p>
                    </div>
                    <div class="text-left">
                         <p class="text-sm font-black text-green-600">@money($invoice->amount)</p>
                         <span class="text-[9px] font-bold uppercase tracking-widest {{ $invoice->status == 'paid' ? 'text-green-600' : 'text-orange-500 animate-pulse' }}">
                            {{ $invoice->status == 'paid' ? 'Paid' : 'Pending' }}
                         </span>
                    </div>
                </div>
                @empty
                 <p class="text-xs text-gray-400 font-bold uppercase tracking-widest text-center py-10">لا توجد فواتير حديثة</p>
                @endforelse
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-red-500/5 rounded-full blur-2xl"></div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-gray-900">تنتهي قريباً</h3>
            </div>
            <div class="space-y-4">
                @forelse($expiringClients as $client)
                <div class="flex justify-between items-center p-3 rounded-2xl hover:bg-white/50 transition-colors border border-transparent hover:border-white/20">
                    <div class="min-w-0">
                        <p class="text-sm font-black text-gray-900 truncate">{{ $client->name }}</p>
                         <p class="text-[10px] text-red-500 font-black uppercase tracking-tighter">Expires: {{ \Carbon\Carbon::parse($client->expires_at)->format('Y-m-d') }}</p>
                    </div>
                    <a href="{{ route('crm.clients.renew', $client) }}" class="rounded-xl bg-red-50 px-3 py-1.5 text-[10px] font-black text-red-600 hover:bg-red-100 transition-colors">Renew</a>
                </div>
                @empty
                 <p class="text-xs text-gray-400 font-bold uppercase tracking-widest text-center py-10">لا توجد اشتراكات تنتهي قريباً</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Common Chart Options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            titleColor: '#111827',
            bodyColor: '#4B5563',
            borderColor: 'rgba(0, 0, 0, 0.05)',
            borderWidth: 1,
            padding: 14,
            displayColors: false,
            cornerRadius: 16,
            bodyFont: { family: 'Rubik', size: 12, weight: '500' },
            titleFont: { family: 'Rubik', weight: 'bold', size: 14 }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: 'rgba(0, 0, 0, 0.02)', drawBorder: false },
            ticks: { 
                color: '#9CA3AF',
                font: { family: 'Rubik', size: 10, weight: 'bold' },
                padding: 10
            }
        },
        x: {
            grid: { display: false },
            ticks: { 
                color: '#9CA3AF',
                font: { family: 'Rubik', size: 10, weight: 'bold' },
                padding: 10
            }
        }
    },
    interaction: { intersect: false, mode: 'index' },
};

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
            backgroundColor: 'rgba(79, 70, 229, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointRadius: 0,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: '#4f46e5',
            pointHoverBorderColor: '#ffffff',
            pointHoverBorderWidth: 3
        }]
    },
    options: chartOptions
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
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            hoverBackgroundColor: '#10b981',
            borderRadius: 10,
            barThickness: 15
        }]
    },
    options: chartOptions
});
</script>
@endsection
