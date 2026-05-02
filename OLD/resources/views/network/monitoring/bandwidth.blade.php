@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">استهلاك Bandwidth</h2>
            <p class="text-gray-500 mt-1">رسوم بيانية لاستهلاك الشبكة عبر الوقت</p>
        </div>
        
        <!-- Selector Area -->
        <div class="flex items-center gap-4">
            <!-- Interface Selector -->
            <form action="" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="period" value="{{ $period }}">
                <select name="interface" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block p-2.5">
                    <option value="">جميع الواجهات</option>
                    @foreach($availableInterfaces as $iface)
                        <option value="{{ $iface }}" {{ $interface === $iface ? 'selected' : '' }}>{{ $iface }}</option>
                    @endforeach
                </select>
            </form>

            <!-- Period Selector -->
            <div class="flex gap-2">
                <a href="?period=24h&interface={{ $interface }}" class="px-4 py-2 {{ $period === '24h' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition hover:opacity-80">
                    24 ساعة
                </a>
                <a href="?period=7d&interface={{ $interface }}" class="px-4 py-2 {{ $period === '7d' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition hover:opacity-80">
                    7 أيام
                </a>
                <a href="?period=30d&interface={{ $interface }}" class="px-4 py-2 {{ $period === '30d' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700' }} rounded-lg font-semibold transition hover:opacity-80">
                    30 يوم
                </a>
            </div>
        </div>
    </div>

    <!--Chart -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 p-6 relative">
        @if(empty($chartData) || count($chartData) == 0)
            <div class="absolute inset-0 flex items-center justify-center bg-white/50 z-10 rounded-2xl">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <p class="text-gray-500 font-medium">لا توجد بيانات كافية لعرض الرسم البياني حالياً.</p>
                    <p class="text-xs text-gray-400 mt-1">يتم جمع البيانات كل 5 دقائق تلقائياً.</p>
                </div>
            </div>
        @endif
        <div style="height: 400px; width: 100%;">
            <canvas id="bandwidthChart"></canvas>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass rounded-xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إجمالي Download</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(collect($chartData)->sum('rx'), 2) }} MB</p>
                </div>
            </div>
        </div>

        <div class="glass rounded-xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إجمالي Upload</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(collect($chartData)->sum('tx'), 2) }} MB</p>
                </div>
            </div>
        </div>

        <div class="glass rounded-xl p-6 shadow-lg border border-white/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">إجمالي الاستهلاك</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format(collect($chartData)->sum('rx') + collect($chartData)->sum('tx'), 2) }} MB</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('bandwidthChart');
    if (!ctx) return;

    const dataFromPHP = @json($chartData);
    const labels = Object.keys(dataFromPHP);
    const rxData = Object.values(dataFromPHP).map(d => d.rx);
    const txData = Object.values(dataFromPHP).map(d => d.tx);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'تحميل (Download - MB)',
                    data: rxData,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#0ea5e9',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'رفع (Upload - MB)',
                    data: txData,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#8b5cf6',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    position: 'top',
                    rtl: true,
                    labels: {
                        font: {
                            family: 'Rubik'
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    titleFont: { family: 'Rubik' },
                    bodyFont: { family: 'Rubik' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: { family: 'Rubik' },
                        callback: function(value) {
                            return value + ' MB';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { family: 'Rubik' }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
