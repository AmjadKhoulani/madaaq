@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="relative">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">إدارة مواقع الشبكة (Sites)</h1>
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse shadow-[0_0_8px_rgba(79,70,229,0.5)]"></span>
                Infrasturture Management Center
            </p>
        </div>
        <a href="{{ route('network.towers.create') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-200/50 transition-all transform hover:scale-105 active:scale-95 group">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>إضافة موقع جديد</span>
        </a>
    </div>

    <!-- Statistics Orbs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        <!-- Total Towers -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي المواقع</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['totalTowers']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-indigo-500 uppercase tracking-tighter">Sites Worldwide</p>
            </div>
        </div>

        <!-- Active -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-emerald-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">المواقع النشطة</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['activeTowers']) }}</p>
                <div class="mt-4 flex items-center text-[11px] font-bold">
                    <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-100 flex items-center gap-1">
                        {{ $stats['totalTowers'] > 0 ? round(($stats['activeTowers'] / $stats['totalTowers']) * 100) : 0 }}% Efficiency
                    </span>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-amber-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-600/10 flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">في الصيانة</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['maintenanceTowers']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-amber-600 uppercase tracking-tighter">Requires Action</p>
            </div>
        </div>

        <!-- Equipment -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-purple-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600/10 flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي الأجهزة</p>
                <p class="text-3xl font-black text-gray-900">{{ number_format($stats['totalRouters']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-purple-600 uppercase tracking-tighter">Active Nodes</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-panel rounded-3xl p-6 mb-10 relative overflow-hidden group">
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all group-hover:bg-indigo-500/10"></div>
        <form method="GET" action="{{ route('network.towers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mr-1">المحافظة</label>
                <select name="city" onchange="this.form.submit()" class="w-full py-3 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="all">كل المحافظات</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mr-1">الحالة</label>
                <select name="status" onchange="this.form.submit()" class="w-full py-3 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="all">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mr-1">النوع</label>
                <select name="type" onchange="this.form.submit()" class="w-full py-3 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="all">كل التصنيفات</option>
                    <option value="tower" {{ request('type') == 'tower' ? 'selected' : '' }}>برج (Tower)</option>
                    <option value="building" {{ request('type') == 'building' ? 'selected' : '' }}>بناء (Building)</option>
                    <option value="cabinet" {{ request('type') == 'cabinet' ? 'selected' : '' }}>كبينة (Cabinet)</option>
                    <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>مركز (Office)</option>
                    <option value="pole" {{ request('type') == 'pole' ? 'selected' : '' }}>عمود (Pole)</option>
                </select>
            </div>

            <div class="flex items-end">
                <a href="{{ route('network.towers.index') }}" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black text-[11px] uppercase tracking-widest rounded-2xl transition-all text-center border border-transparent active:scale-95">
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Towers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($towers as $tower)
        <div class="glass-panel rounded-[2rem] hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:translate-y-[-8px] group relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/5 rounded-full blur-[80px]"></div>
            
            <!-- Card Header -->
            <div class="p-8 pb-6 border-b border-white/10">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-xl group-hover:rotate-6 transition-transform duration-500">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-black text-gray-900 text-xl truncate tracking-tight">{{ $tower->name }}</h3>
                            @if($tower->city)
                                <p class="text-[10px] text-indigo-600 font-black uppercase tracking-widest mt-1">📍 {{ $tower->city }}</p>
                            @endif
                        </div>
                    </div>
                    @if($tower->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest bg-green-500/10 text-green-600 border border-green-500/10">
                            <span class="w-1.5 h-1.5 bg-green-600 rounded-full ml-1.5 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                            Online
                        </span>
                    @elseif($tower->status === 'maintenance')
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-600 border border-amber-500/10">
                             Maintenance
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest bg-gray-500/10 text-gray-500 border border-gray-500/10">
                             Offline
                        </span>
                    @endif
                </div>
                
                @if($tower->location)
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium mb-4">{{ Str::limit($tower->location, 80) }}</p>
                @endif

                <div class="flex flex-wrap gap-2">
                    @php
                        $typeMap = [
                            'tower' => ['label' => 'Tower Site', 'icon' => '🗼', 'bg' => 'blue'],
                            'building' => ['label' => 'Building', 'icon' => '🏢', 'bg' => 'purple'],
                            'cabinet' => ['label' => 'Pop / Cabinet', 'icon' => '🗄️', 'bg' => 'emerald'],
                            'office' => ['label' => 'HQ Office', 'icon' => '🏛️', 'bg' => 'indigo'],
                            'pole' => ['label' => 'Mast / Pole', 'icon' => '📍', 'bg' => 'gray']
                        ];
                        $type = $typeMap[$tower->type] ?? $typeMap['pole'];
                    @endphp
                    <span class="px-3 py-1 bg-{{ $type['bg'] }}-500/5 text-{{ $type['bg'] }}-600 rounded-xl text-[9px] font-black uppercase tracking-widest border border-{{ $type['bg'] }}-500/10">
                        {{ $type['icon'] }} {{ $type['label'] }}
                    </span>
                </div>
            </div>

            <!-- Stats & Details -->
            <div class="p-8 space-y-5">
                <div class="flex items-center justify-between p-4 bg-white/50 backdrop-blur-md rounded-2xl border border-white/50 shadow-sm transition-all group-hover:bg-white group-hover:shadow-md">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        Devices Linked
                    </span>
                    <span class="font-black text-gray-900 text-lg">{{ $tower->routers_count }}</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @if($tower->has_solar)
                        <div class="flex items-center gap-2 p-3 bg-yellow-500/5 border border-yellow-500/10 rounded-2xl transition-all group-hover:bg-yellow-50">
                            <span class="text-[9px] font-black text-yellow-700 uppercase tracking-tighter">☀️ Solar Power</span>
                        </div>
                    @endif
                    @if($tower->battery_count > 0)
                        <div class="flex items-center gap-2 p-3 bg-green-500/5 border border-green-500/10 rounded-2xl transition-all group-hover:bg-green-50">
                            <span class="text-[9px] font-black text-green-700 uppercase tracking-tighter">🔋 {{ $tower->battery_count }} Batteries</span>
                        </div>
                    @endif
                    @if($tower->has_generator)
                        <div class="flex items-center gap-2 p-3 bg-orange-500/5 border border-orange-500/10 rounded-2xl transition-all group-hover:bg-orange-50">
                            <span class="text-[9px] font-black text-orange-700 uppercase tracking-tighter">⚡ Generator</span>
                        </div>
                    @endif
                </div>

                @if($tower->monthly_rent || $tower->monthly_maintenance)
                    <div class="pt-4 border-t border-white/20">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Monthly Overheads</p>
                        <p class="font-black text-gray-900 text-lg">
                            @money(($tower->monthly_rent ?? 0) + ($tower->monthly_maintenance ?? 0))
                        </p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="px-8 py-5 bg-white/20 backdrop-blur-xl border-t border-white/10 flex items-center gap-4">
                <a href="{{ route('network.towers.show', $tower) }}" class="flex-1 text-center py-3 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-xl hover:shadow-gray-900/20 transition-all active:scale-95">
                    View Site
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('network.towers.edit', $tower) }}" class="w-11 h-11 flex items-center justify-center bg-white/50 backdrop-blur-md text-gray-400 hover:text-indigo-600 hover:bg-white rounded-2xl border border-white/50 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form action="{{ route('network.towers.destroy', $tower) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا البرج؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-11 h-11 flex items-center justify-center bg-white/50 backdrop-blur-md text-gray-300 hover:text-red-500 hover:bg-white rounded-2xl border border-white/50 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full font-inter">
            <div class="glass-panel rounded-[3rem] p-24 text-center relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-all duration-700"></div>
                <div class="w-24 h-24 bg-white/50 backdrop-blur-md rounded-[2rem] flex items-center justify-center mx-auto mb-10 border border-white/30 shadow-xl group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900">No Infrastructure Sites Found</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-3 mb-10">We couldn't find any network locations matching your current filters</p>
                <a href="{{ route('network.towers.index') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl transition-all shadow-xl hover:shadow-gray-900/30 active:scale-95">
                    Clear Active Filters
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
