@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">نظرة عامة على النظام</h2>

    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 relative overflow-hidden group hover:border-green-500/50 transition-colors shadow-sm">
            <div class="absolute top-0 right-0 w-20 h-20 bg-green-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="text-gray-500 text-sm font-medium mb-2">إجمالي الإيرادات</div>
                <div class="text-3xl font-bold text-gray-900 mb-1">${{ number_format($stats['total_revenue']) }}</div>
                <div class="text-xs text-green-600 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    +${{ number_format($stats['monthly_revenue']) }} هذا الشهر
                </div>
            </div>

        </div>

        <!-- Active Tenants -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 relative overflow-hidden group hover:border-indigo-500/50 transition-colors shadow-sm">
             <div class="absolute top-0 right-0 w-20 h-20 bg-indigo-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="text-gray-500 text-sm font-medium mb-2">الشركات النشطة</div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['active_tenants'] }}</div>
                <div class="text-xs text-gray-500">من أصل {{ $stats['total_tenants'] }} مسجل</div>
            </div>

        </div>

         <!-- Active Subscriptions -->
         <div class="bg-white rounded-xl p-6 border border-gray-200 relative overflow-hidden group hover:border-purple-500/50 transition-colors shadow-sm">
             <div class="absolute top-0 right-0 w-20 h-20 bg-purple-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="text-gray-500 text-sm font-medium mb-2">اشتراكات مدفوعة</div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['active_subscriptions'] }}</div>
                <div class="text-xs text-purple-600 font-medium">باقات Pro / Basic</div>
            </div>

        </div>

        <!-- Total End Users -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 relative overflow-hidden group hover:border-cyan-500/50 transition-colors shadow-sm">
             <div class="absolute top-0 right-0 w-20 h-20 bg-cyan-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="text-gray-500 text-sm font-medium mb-2">إجمالي المشتركين</div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['total_clients']) }}</div>
                <div class="text-xs text-gray-500">مستخدم نهائي (Clients)</div>
            </div>

        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6">نمو الإيرادات (6 أشهر)</h3>
            <div class="relative h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Tenants Growth Chart -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-6">انضمام الشركات الجديدة</h3>
            <div class="relative h-64">
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
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#374151' },
                    ticks: { color: '#9CA3AF' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#9CA3AF' }
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
