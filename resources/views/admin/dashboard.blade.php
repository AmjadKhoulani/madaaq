@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">نظرة عامة على النظام</h2>

    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Total Revenue -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 hover:translate-y-[-4px]">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-500/10 rounded-full blur-3xl group-hover:bg-green-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-green-500/10 flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg border border-green-100">+12%</span>
                </div>
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">إجمالي الإيرادات</div>
                <div class="text-3xl font-black text-gray-900 mb-1">${{ number_format($stats['total_revenue']) }}</div>
                <div class="text-[11px] text-gray-500 font-medium">
                    <span class="text-green-600 font-bold">+${{ number_format($stats['monthly_revenue']) }}</span> هذا الشهر
                </div>
            </div>
        </div>

        <!-- Active Tenants -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:translate-y-[-4px]">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">الشركات النشطة</div>
                <div class="text-3xl font-black text-gray-900 mb-1">{{ $stats['active_tenants'] }}</div>
                <div class="text-[11px] text-gray-500 font-medium">من أصل <span class="font-bold">{{ $stats['total_tenants'] }}</span> مسجل</div>
            </div>
        </div>

         <!-- Active Subscriptions -->
         <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-500 hover:translate-y-[-4px]">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">اشتراكات مدفوعة</div>
                <div class="text-3xl font-black text-gray-900 mb-1">{{ $stats['active_subscriptions'] }}</div>
                <div class="text-[11px] text-purple-600 font-bold uppercase tracking-tighter">باقات PRO / Basic</div>
            </div>
        </div>

        <!-- Total End Users -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 hover:translate-y-[-4px]">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">إجمالي المشتركين</div>
                <div class="text-3xl font-black text-gray-900 mb-1">{{ number_format($stats['total_clients']) }}</div>
                <div class="text-[11px] text-gray-500 font-medium">مستخدم نهائي (Clients)</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <!-- Revenue Chart -->
        <div class="glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-gray-800">نمو الإيرادات (6 أشهر)</h3>
                <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
            </div>
            <div class="relative h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Tenants Growth Chart -->
        <div class="glass-panel rounded-3xl p-8 transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-gray-800">انضمام الشركات الجديدة</h3>
                <div class="w-3 h-3 rounded-full bg-indigo-500 animate-pulse"></div>
            </div>
            <div class="relative h-72">
                <canvas id="tenantsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Invoices -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">أحدث العمليات المالية</h3>
                <a href="{{ route('admin.subscriptions.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">عرض الكل &larr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="bg-gray-100 text-xs uppercase font-medium text-gray-500">
                        <tr>
                            <th class="px-6 py-4">الفاتورة</th>
                            <th class="px-6 py-4">الشركة</th>
                            <th class="px-6 py-4">المبلغ</th>
                            <th class="px-6 py-4">الحالة</th>
                            <th class="px-6 py-4">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recent_invoices as $invoice)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-gray-900">{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4">{{ $invoice->tenant->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-green-600 font-bold">${{ number_format($invoice->amount) }}</td>
                                <td class="px-6 py-4">
                                     @if($invoice->status == 'paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 border border-green-200">مدفوع</span>
                                    @elseif($invoice->status == 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">معلق</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 border border-red-200">{{ $invoice->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $invoice->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">لا توجد عمليات حديثة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Tenants List (Compact) -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden h-fit shadow-sm">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">شركات انضمت حديثاً</h3>
                <a href="{{ route('admin.tenants.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">عرض الكل</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_tenants as $tenant)
                    <div class="p-4 hover:bg-gray-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-lg font-bold text-indigo-600">
                                {{ substr($tenant->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-gray-900 font-medium text-sm">{{ $tenant->name }}</div>
                                <a href="http://{{ $tenant->domain }}" target="_blank" class="text-gray-500 text-xs hover:text-indigo-600">{{ $tenant->domain }}</a>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-400">{{ $tenant->created_at->diffForHumans() }}</div>
                            <span class="inline-flex mt-1 items-center px-1.5 py-0.5 rounded text-[10px] font-medium 
                                {{ $tenant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $tenant->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 text-sm">لا توجد بيانات.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Free Trials -->
    <div class="mt-8 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">تجربة مجانية نشطة (Active Trials)</h3>
            <span class="bg-indigo-100 text-indigo-700 py-1 px-3 rounded-full text-xs font-bold">{{ $trial_users->count() }} نشط</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-100 text-xs uppercase font-medium text-gray-500">
                    <tr>
                        <th class="px-6 py-4">الشركة (Tenant)</th>
                        <th class="px-6 py-4">المالك (Owner)</th>
                        <th class="px-6 py-4">تاريخ الانتهاء</th>
                        <th class="px-6 py-4">متبقي</th>
                        <th class="px-6 py-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($trial_users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $user->tenant->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-400">{{ $user->tenant->domain ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-700">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                    {{ $user->trial_ends_at->format('Y-m-d H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-700">
                                {{ now()->diffInDays($user->trial_ends_at) }} يوم
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="http://{{ $user->tenant->domain ?? '#' }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold">زيارة</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">لا توجد تجارب مجانية نشطة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthLabels = @json($months);
        const revenueData = @json($chart_revenue);
        const tenantsData = @json($chart_tenants);

        // Common Chart Options
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#111827',
                    bodyColor: '#4B5563',
                    borderColor: 'rgba(255, 255, 255, 0.4)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    cornerRadius: 12,
                    bodyFont: { family: 'Rubik', size: 12 },
                    titleFont: { family: 'Rubik', weight: 'bold', size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.03)', drawBorder: false },
                    ticks: { 
                        color: '#9CA3AF',
                        font: { family: 'Rubik', size: 10 },
                        padding: 10
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        color: '#9CA3AF',
                        font: { family: 'Rubik', size: 10 },
                        padding: 10
                    }
                }
            }
        };

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'الإيرادات ($)',
                    data: revenueData,
                    borderColor: '#10B981', // Green
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: chartOptions
        });

        // Tenants Chart
        new Chart(document.getElementById('tenantsChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'شركات جديدة',
                    data: tenantsData,
                    backgroundColor: '#6366F1', // Indigo
                    borderRadius: 4
                }]
            },
            options: chartOptions
        });
    </script>
@endsection
