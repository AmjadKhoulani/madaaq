@extends('layouts.admin')

@section('title', 'نظرة عامة على المنظومة | System Governance Hub')

@section('content')
<div class="space-y-12">
    <!-- Radiant Hub Header -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-12 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Authenticated Architect Portal</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase underline decoration-accent-flow decoration-8 underline-offset-8">المركز القيادي الرئيسي</h2>
            <p class="text-slate-400 font-bold mt-6 uppercase tracking-widest text-[11px] font-headline opacity-80 italic">Global Infrastructure Performance & Commercial Yield Intelligence</p>
        </div>
        
        <div class="flex gap-4">
            <button class="px-8 py-3.5 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
                <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform duration-500">analytics</span>
                توليد التقرير الاستراتيجي
            </button>
        </div>
    </div>

    <!-- Radiant Metric Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Metric: Total Clients -->
        <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm border border-primary/10">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-full text-[9px] font-black uppercase italic border border-emerald-500/10">
                    <span class="material-symbols-outlined text-[12px]">trending_up</span>
                    12%
                </div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">المشتركين النشطين</p>
            <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter transition-transform group-hover:scale-110 origin-right">{{ number_format($stats['total_clients']) }}</h3>
        </div>

        <!-- Metric: Revenue Velocity -->
        <div class="glass-card p-8 rounded-3xl relative overflow-hidden group border-b-4 border-neon-cyan/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-neon-cyan/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-neon-cyan/10 rounded-2xl flex items-center justify-center text-neon-cyan shadow-sm border border-neon-cyan/10">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">YIELD DATA</div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">الإيرادات المحققة</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter transition-transform group-hover:scale-110 origin-right">{{ number_format($stats['total_revenue']) }}</h3>
                <span class="text-[10px] font-black text-slate-400 italic">SAR</span>
            </div>
        </div>

        <!-- Metric: Global Tenants -->
        <div class="glass-card p-8 rounded-3xl relative overflow-hidden group border-b-4 border-vibrant-purple/20">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-vibrant-purple/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-vibrant-purple/10 rounded-2xl flex items-center justify-center text-vibrant-purple shadow-sm border border-vibrant-purple/10">
                    <span class="material-symbols-outlined">corporate_fare</span>
                </div>
                <span class="px-3 py-1 bg-slate-900 text-neon-cyan text-[8px] font-black rounded-lg uppercase tracking-widest italic shadow-glow-cyan">STABLE</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">شركاء المنظومة</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter transition-transform group-hover:scale-110 origin-right">{{ $stats['active_tenants'] }}</h3>
                <span class="text-[10px] font-black text-slate-400 italic">/ {{ $stats['total_tenants'] }}</span>
            </div>
        </div>

        <!-- Metric: Pulse Subscriptions -->
        <div class="glass-card p-8 rounded-3xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm border border-primary/10">
                    <span class="material-symbols-outlined">verified</span>
                </div>
                <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse shadow-glow-cyan"></div>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 font-headline italic">الاشتراكات النشطة</p>
            <h3 class="text-4xl font-headline font-black text-slate-900 italic tracking-tighter transition-transform group-hover:scale-110 origin-right">{{ $stats['active_subscriptions'] }}</h3>
        </div>
    </div>

    <!-- Radiant Bento Pulse -->
    <div class="grid grid-cols-12 gap-10">
        
        <!-- Large Analytics Shell -->
        <div class="col-span-12 lg:col-span-8 glass-panel p-10 rounded-[2.5rem] !bg-white/80">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                <div>
                    <h2 class="text-xl font-black text-primary uppercase tracking-widest italic flex items-center gap-4 decoration-accent-flow decoration-4 underline underline-offset-4">نظرة النمو الاستراتيجية</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3 italic">Temporal Yield Flux & Partnership Acquisition Velocity (6M)</p>
                </div>
                <div class="flex items-center gap-2 p-1.5 bg-slate-50 border border-slate-200 rounded-2xl shadow-inner">
                    <button class="px-6 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-glow-purple italic">REVENUE</button>
                    <button class="px-6 py-2 text-slate-400 hover:text-primary rounded-xl text-[9px] font-black uppercase tracking-widest italic">PARTNERS</button>
                </div>
            </div>
            <div class="relative h-[480px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Side Activity Stream -->
        <div class="col-span-12 lg:col-span-4 space-y-8">
            <div class="glass-panel p-10 rounded-[2.5rem] !bg-slate-900 text-white border-white/5 relative overflow-hidden min-h-[580px]">
                <div class="absolute top-0 right-0 w-64 h-64 bg-accent-gradient opacity-10 blur-3xl -mr-32 -mt-32"></div>
                <div class="relative z-10">
                    <h2 class="text-sm font-black text-neon-cyan uppercase tracking-[0.3em] mb-10 italic flex items-center gap-4">
                        <span class="material-symbols-outlined text-neon-cyan">bolt</span>
                        Registry Flux
                    </h2>
                    
                    <div class="space-y-6">
                        @forelse($recent_tenants as $tenant)
                            <div class="flex items-center justify-between p-5 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/10 transition-all hover:-translate-x-2 group cursor-pointer">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-xl bg-accent-gradient p-0.5 shadow-sm group-hover:shadow-glow-cyan transition-all">
                                        <div class="w-full h-full bg-slate-900 rounded-[10px] flex items-center justify-center font-black text-white text-sm">
                                            {{ substr($tenant->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-xs text-white uppercase italic tracking-tighter">{{ $tenant->name }}</h4>
                                        <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1 italic">{{ $tenant->domain }}</p>
                                    </div>
                                </div>
                                <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest italic">{{ $tenant->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="text-center py-20 opacity-30 italic font-black uppercase text-[10px] tracking-widest">No Recent Identity Influx Detected</div>
                        @endforelse
                    </div>

                    <a href="{{ route('admin.tenants.index') }}" class="w-full mt-12 py-4 bg-white/5 border border-white/10 text-[9px] font-black text-neon-cyan uppercase tracking-[0.3em] font-headline rounded-2xl flex items-center justify-center gap-3 hover:bg-white/10 transition-all italic">
                        Explore Full Registry
                        <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Financial Command Ledger -->
        <div class="col-span-12 glass-panel rounded-[3rem] overflow-hidden !bg-white/60">
            <div class="p-10 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h2 class="text-xl font-black text-primary uppercase tracking-widest italic flex items-center gap-4">
                        <span class="w-10 h-1 bg-accent-flow rounded-full"></span>
                        المحرك المالي الاستراتيجي
                    </h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3 italic">Verified Transaction Matrix & Protocol Settlement History</p>
                </div>
                <a href="{{ route('admin.subscriptions.index') }}" class="px-10 py-4 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-primary uppercase tracking-[0.3em] hover:bg-slate-900 hover:text-white transition-all flex items-center gap-4 shadow-sm italic">
                    Historical Ledger
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-primary/5 text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/40 italic">
                            <th class="px-10 py-6">Protocol ID</th>
                            <th class="px-10 py-6">Beneficiary Identity</th>
                            <th class="px-10 py-6">Allocation Density</th>
                            <th class="px-10 py-6 text-center">Settlement Status</th>
                            <th class="px-10 py-6">Temporal Pulse</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/20">
                        @foreach($recent_invoices as $invoice)
                            <tr class="group hover:bg-white/40 transition-all duration-300">
                                <td class="px-10 py-7 font-manrope font-black text-primary text-[11px] italic tracking-tighter uppercase group-hover:scale-110 transition-transform origin-right">#{{ $invoice->invoice_number }}</td>
                                <td class="px-10 py-7">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 p-0.5">
                                            <div class="w-full h-full bg-slate-800 rounded-lg flex items-center justify-center text-[10px] font-black text-neon-cyan">
                                                {{ substr($invoice->tenant->name ?? '?', 0, 1) }}
                                            </div>
                                        </div>
                                        <span class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">{{ $invoice->tenant->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-7">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-sm font-manrope font-black text-primary italic">{{ number_format($invoice->amount) }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase italic">SAR</span>
                                    </div>
                                </td>
                                <td class="px-10 py-7">
                                    <div class="flex justify-center">
                                        @if($invoice->status == 'paid')
                                            <span class="px-5 py-1.5 rounded-full text-[9px] font-black bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 uppercase italic shadow-glow-cyan shadow-sm">Verified Settlement</span>
                                        @else
                                            <span class="px-5 py-1.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-400 border border-slate-200 uppercase italic">Awaiting Protocol</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-10 py-7 text-slate-400 text-[10px] font-black font-manrope italic opacity-60">{{ $invoice->created_at->format('Y/m/d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Radiant Analytics Chart Engine -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthLabels = @json($months);
        const revenueData = @json($chart_revenue);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 480);
        gradient.addColorStop(0, 'rgba(0, 229, 255, 0.15)');
        gradient.addColorStop(1, 'rgba(124, 77, 255, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'PROTOCOL YIELD',
                    data: revenueData,
                    borderColor: '#00e5ff',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.5,
                    pointRadius: 6,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#7c4dff',
                    pointBorderWidth: 3,
                    pointHoverRadius: 10,
                    pointHoverBackgroundColor: '#7c4dff',
                    pointHoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        rtl: true,
                        backgroundColor: '#0f172a',
                        titleColor: '#00e5ff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 20,
                        cornerRadius: 16,
                        titleFont: { family: 'IBM Plex Sans Arabic', size: 14, weight: 'bold' },
                        bodyFont: { family: 'Manrope', size: 12, weight: 'bold' },
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        position: 'right',
                        grid: { color: 'rgba(0, 53, 95, 0.05)', drawBorder: false },
                        ticks: { font: { family: 'Manrope', size: 10, weight: 'bold' }, color: '#94a3b8', padding: 20 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Manrope', size: 10, weight: 'bold' }, color: '#94a3b8', padding: 20 }
                    }
                }
            }
        });
    });
</script>
@endsection
