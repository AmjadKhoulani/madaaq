@extends('layouts.admin')

@section('title', 'تفاصيل البرج | Tower Registry: ' . $tower->name)

@section('content')
<div class="max-w-7xl mx-auto space-y-8" x-data="{ activeTab: 'info' }">
    
    <!-- Strategic Asset Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight italic uppercase">🗼 {{ $tower->name }}</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">Physical Infrastructure Node: {{ $tower->location }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('network.towers.edit', $tower) }}" class="px-8 py-2.5 bg-primary text-white font-bold rounded text-sm shadow-lg shadow-primary/10 hover:scale-[1.02] transition-all flex items-center gap-2 italic">
                <span class="material-symbols-outlined text-sm">edit_square</span>
                تعديل البيانات
            </a>
        </div>
    </div>

    <!-- Infrastructure Intelligence Hub -->
    <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg overflow-hidden shadow-sm flex flex-col min-h-[500px]">
        <!-- Terminal Navigator -->
        <div class="bg-white/50 border-b border-outline-variant/10 px-4">
            <nav class="flex gap-1 overflow-x-auto no-scrollbar py-2">
                <button @click="activeTab = 'info'" 
                        class="px-6 py-3 rounded text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap"
                        :class="activeTab === 'info' ? 'bg-primary text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100'">
                    <span class="material-symbols-outlined text-sm">info</span>
                    المعلومات المركزية
                </button>
                <button @click="activeTab = 'equipment'" 
                        class="px-6 py-3 rounded text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap"
                        :class="activeTab === 'equipment' ? 'bg-primary text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100'">
                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                    مصفوفة المعدات
                </button>
                <button @click="activeTab = 'power'" 
                        class="px-6 py-3 rounded text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap"
                        :class="activeTab === 'power' ? 'bg-primary text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100'">
                    <span class="material-symbols-outlined text-sm">bolt</span>
                    لوحة الطاقة
                </button>
                <button @click="activeTab = 'ssids'" 
                        class="px-6 py-3 rounded text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap"
                        :class="activeTab === 'ssids' ? 'bg-primary text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100'">
                    <span class="material-symbols-outlined text-sm">network_wifi</span>
                    نطاقات البث (SSIDs)
                </button>
                <button @click="activeTab = 'costs'" 
                        class="px-6 py-3 rounded text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap"
                        :class="activeTab === 'costs' ? 'bg-primary text-white shadow-lg' : 'text-slate-500 hover:bg-slate-100'">
                    <span class="material-symbols-outlined text-sm">payments</span>
                    التكاليف والاستدامة
                </button>
            </nav>
        </div>

        <!-- Registry Content Area -->
        <div class="p-8 md:p-10 flex-1">
            
            <!-- Tab: Info -->
            <div x-show="activeTab === 'info'" x-transition:enter="duration-300" class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <h4 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-2">
                            <span class="w-1 h-4 bg-primary rounded-full"></span>
                            Geospatial Registry Data
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-5 bg-surface-container-lowest border border-outline-variant/10 rounded">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Site Location</p>
                                <p class="text-xs font-bold text-primary">{{ $tower->location }}</p>
                            </div>
                            <div class="p-5 bg-surface-container-lowest border border-outline-variant/10 rounded">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Structural Height</p>
                                <p class="text-xs font-manrope font-black text-primary uppercase italic">{{ $tower->height }} METERS</p>
                            </div>
                            <div class="p-5 bg-surface-container-lowest border border-outline-variant/10 rounded col-span-2">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Coordinate Synchronization</p>
                                <p class="text-xs font-manrope font-black text-secondary tracking-widest">{{ $tower->lat }}, {{ $tower->lng }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-sm font-black text-primary uppercase tracking-widest italic flex items-center gap-2">
                            <span class="w-1 h-4 bg-secondary rounded-full"></span>
                            Active Infrastructure Metrics
                        </h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="p-6 bg-surface-container-lowest border border-outline-variant/10 rounded text-center">
                                <p class="text-2xl font-manrope font-black text-primary">{{ $tower->routers->count() }}</p>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Devices</p>
                            </div>
                            <div class="p-6 bg-surface-container-lowest border border-outline-variant/10 rounded text-center">
                                <p class="text-2xl font-manrope font-black text-secondary">{{ $tower->clients->count() }}</p>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Subscribers</p>
                            </div>
                            <div class="p-6 bg-surface-container-lowest border border-outline-variant/10 rounded text-center">
                                <p class="text-2xl font-manrope font-black text-primary italic">{{ $tower->ssids->count() }}</p>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">SSID Pools</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Equipment -->
            <div x-show="activeTab === 'equipment'" x-cloak x-transition:enter="duration-300" class="space-y-8">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-black text-primary uppercase tracking-widest italic">Hardware Asset Inventory</h4>
                    <div class="px-6 py-2 bg-primary/5 text-primary rounded border border-primary/10">
                        <p class="text-[10px] font-black uppercase tracking-widest">Aggregate Value: <span class="font-manrope ml-2 tracking-normal">${{ number_format($tower->routers->sum('price'), 2) }}</span></p>
                    </div>
                </div>

                <div class="bg-surface-container-lowest rounded border border-outline-variant/10 overflow-hidden">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="bg-primary/5 text-primary">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-right">Asset Identity</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-right">Protocol Class</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-right">Operational IP</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-right">Valuation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            @forelse($tower->routers as $router)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3.5 font-black text-primary text-xs italic">{{ $router->name }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="px-2 py-0.5 bg-surface-container-low text-slate-500 rounded text-[8px] font-black uppercase border border-outline-variant/5">
                                        @if($router->device_type === 'router') 📟 NODE
                                        @elseif($router->device_type === 'access_point') 📶 AP
                                        @else 🗼 PTMP
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 font-manrope font-black text-slate-500 text-[10px]">{{ $router->ip }}</td>
                                <td class="px-6 py-3.5 font-manrope font-black text-secondary text-[10px]">${{ number_format($router->price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center opacity-30 italic font-black uppercase text-[10px]">Registry Empty: No Active Assets Detected</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Power -->
            <div x-show="activeTab === 'power'" x-cloak x-transition:enter="duration-300" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Ampere Grid -->
                    <div class="bg-surface-container-lowest border border-outline-variant/10 rounded-lg p-8 space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-500/10 text-amber-500 rounded flex items-center justify-center border border-amber-500/20">
                                <span class="material-symbols-outlined text-2xl">electric_bolt</span>
                            </div>
                            <h5 class="text-sm font-black text-primary uppercase tracking-widest">Public Utility (Ampere)</h5>
                        </div>

                        @if($tower->has_ampere)
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-surface-container-low rounded border border-outline-variant/5">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Monthly Contract</p>
                                <p class="text-lg font-manrope font-black text-primary">${{ number_format($tower->ampere_subscription_monthly, 2) }}</p>
                            </div>
                            <div class="p-4 bg-surface-container-low rounded border border-outline-variant/5">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Unit Valuation</p>
                                <p class="text-sm font-manrope font-black text-secondary">${{ number_format($tower->kwh_price, 2) }}/kWh</p>
                            </div>
                        </div>
                        @else
                        <div class="p-8 text-center text-slate-300 italic uppercase text-[10px] font-black border border-dashed border-outline-variant/10 rounded">Offline Utility</div>
                        @endif
                    </div>

                    <!-- Solar Matrix -->
                    <div class="bg-surface-container-lowest border border-outline-variant/10 rounded-lg p-8 space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-500/10 text-orange-500 rounded flex items-center justify-center border border-orange-500/20">
                                <span class="material-symbols-outlined text-2xl">wb_sunny</span>
                            </div>
                            <h5 class="text-sm font-black text-primary uppercase tracking-widest">Photovoltaic Array (Solar)</h5>
                        </div>

                        @if($tower->has_solar)
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-surface-container-low rounded border border-outline-variant/5">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Panel Cluster</p>
                                <p class="text-lg font-manrope font-black text-primary">{{ $tower->solar_panels_count }} UNITS</p>
                            </div>
                            <div class="p-4 bg-surface-container-low rounded border border-outline-variant/5">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Peak Transmission</p>
                                <p class="text-lg font-manrope font-black text-secondary">{{ $tower->total_solar_capacity }}W</p>
                            </div>
                        </div>
                        @else
                        <div class="p-8 text-center text-slate-300 italic uppercase text-[10px] font-black border border-dashed border-outline-variant/10 rounded">No Solar Infrastructure</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab: SSIDs -->
            <div x-show="activeTab === 'ssids'" x-cloak x-transition:enter="duration-300" class="space-y-8">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-black text-primary uppercase tracking-widest italic">Wireless Spectrum Logic</h4>
                </div>

                <div class="bg-surface-container-lowest rounded border border-outline-variant/10 overflow-hidden">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="bg-secondary/5 text-secondary">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-right">Identifier (SSID)</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-right">Frequency band</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-right">Host Hardware</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-right">Protocol State</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            @forelse($tower->ssids as $ssid)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3.5 font-manrope font-black text-primary text-xs italic">{{ $ssid->ssid_name }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="px-3 py-1 bg-white border border-outline-variant/10 rounded text-[9px] font-manrope font-black text-slate-500 uppercase italic">
                                        {{ $ssid->frequency }} Ghz
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 font-black text-slate-400 text-[10px] uppercase italic">{{ $ssid->router?->name ?? 'VIRTUAL' }}</td>
                                <td class="px-6 py-3.5">
                                    @if($ssid->status === 'active')
                                        <span class="text-secondary font-black text-[9px] uppercase tracking-widest flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 bg-secondary rounded-full animate-pulse"></span>
                                            Operational
                                        </span>
                                    @else
                                        <span class="text-slate-300 font-black text-[9px] uppercase tracking-widest flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                            Offline
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center opacity-30 italic font-black uppercase text-[10px]">Spectral Isolation: No SSIDs detected</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Costs -->
            <div x-show="activeTab === 'costs'" x-cloak x-transition:enter="duration-300" class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-8 bg-primary rounded shadow-lg shadow-primary/10">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-2">Original CapEx</p>
                        <h5 class="text-3xl font-manrope font-black text-white tracking-widest">${{ number_format($tower->total_equipment_cost, 2) }}</h5>
                        <p class="text-[8px] font-bold text-white/40 uppercase mt-2 italic">Infrastructure Deployment Value</p>
                    </div>
                    <div class="p-8 bg-surface-container-lowest border border-outline-variant/10 rounded">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Fixed Monthly OpEx</p>
                        <h5 class="text-3xl font-manrope font-black text-primary tracking-widest">${{ number_format($tower->monthly_fixed_costs, 2) }}</h5>
                        <p class="text-[8px] font-bold text-slate-400 uppercase mt-2 italic text-left">Recurring Operational Drain</p>
                    </div>
                    <div class="p-8 bg-secondary rounded shadow-lg shadow-secondary/10">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-2">Current billing Cycle</p>
                        <h5 class="text-3xl font-manrope font-black text-white tracking-widest">${{ number_format($tower->getCurrentMonthCost(), 2) }}</h5>
                        <p class="text-[8px] font-bold text-white/40 uppercase mt-2 italic">{{ now()->format('Y-m') }} Protocol</p>
                    </div>
                </div>

                <div class="space-y-6 pt-8 border-t border-outline-variant/10">
                    <h4 class="text-sm font-black text-primary uppercase tracking-widest italic uppercase">Historical Sustainability Log</h4>
                    <div class="bg-surface-container-lowest rounded border border-outline-variant/10 overflow-hidden">
                        <table class="w-full text-right">
                            <thead>
                                <tr class="bg-slate-900 text-white">
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Protocol Date</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Utility Charge</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Fuel Usage</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Sustain/Repair</th>
                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest">Aggregate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/5">
                                @forelse($tower->monthlyCosts()->orderBy('month', 'desc')->take(6)->get() as $cost)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3.5 font-black text-primary text-[10px] uppercase font-manrope tracking-widest">{{ \Carbon\Carbon::parse($cost->month . '-01')->format('F Y') }}</td>
                                    <td class="px-6 py-3.5 font-manrope font-black text-slate-500 text-xs">${{ number_format($cost->ampere_bill, 2) }}</td>
                                    <td class="px-6 py-3.5 font-manrope font-black text-slate-500 text-xs">${{ number_format($cost->diesel_cost, 2) }}</td>
                                    <td class="px-6 py-3.5 font-manrope font-black text-slate-500 text-xs">${{ number_format($cost->maintenance_cost, 2) }}</td>
                                    <td class="px-6 py-3.5 font-manrope font-black text-secondary text-sm">${{ number_format($cost->total_cost, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center opacity-30 italic font-black uppercase text-[10px]">Archive Registry Empty: No costs recorded</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
