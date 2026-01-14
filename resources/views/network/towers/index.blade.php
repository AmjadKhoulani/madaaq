@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">إدارة الأبراج</h2>
            <p class="text-gray-500 mt-1">نظرة شاملة على البنية التحتية لشبكتك</p>
        </div>
        <a href="{{ route('network.towers.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
            <span>إضافة برج جديد</span>
        </a>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Towers --}}
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-blue-100 font-medium text-sm">إجمالي الأبراج</p>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-bold">{{ $stats['totalTowers'] }}</h3>
                <p class="text-blue-100 text-xs mt-2">عبر {{ $cities->count() }} محافظة</p>
            </div>
        </div>

        {{-- Active Towers --}}
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-emerald-100 font-medium text-sm">الأبراج النشطة</p>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-bold">{{ $stats['activeTowers'] }}</h3>
                <p class="text-emerald-100 text-xs mt-2">
                    {{ $stats['totalTowers'] > 0 ? round(($stats['activeTowers'] / $stats['totalTowers']) * 100) : 0 }}% من الإجمالي
                </p>
            </div>
        </div>

        {{-- Under Maintenance --}}
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-amber-100 font-medium text-sm">تحت الصيانة</p>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-bold">{{ $stats['maintenanceTowers'] }}</h3>
                <p class="text-amber-100 text-xs mt-2">تحتاج للمتابعة</p>
            </div>
        </div>

        {{-- Total Equipment --}}
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-purple-100 font-medium text-sm">إجمالي الأجهزة</p>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-bold">{{ $stats['totalRouters'] }}</h3>
                <p class="text-purple-100 text-xs mt-2">موزعة على الشبكة</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('network.towers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- City Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المحافظة</label>
                <select name="city" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">كل المحافظات</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>صيانة</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>

            {{-- Type Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                <select name="type" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">كل الأنواع</option>
                    <option value="tower" {{ request('type') == 'tower' ? 'selected' : '' }}>برج</option>
                    <option value="building" {{ request('type') == 'building' ? 'selected' : '' }}>مبنى</option>
                    <option value="pole" {{ request('type') == 'pole' ? 'selected' : '' }}>عمود</option>
                </select>
            </div>

            {{-- Clear Filters --}}
            <div class="flex items-end">
                <a href="{{ route('network.towers.index') }}" class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition text-center">
                    مسح الفلاتر
                </a>
            </div>
        </form>
    </div>

    {{-- Towers Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($towers as $tower)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            {{-- Header --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $tower->name }}</h3>
                            @if($tower->city)
                                <p class="text-xs text-gray-500 mt-0.5">📍 {{ $tower->city }}</p>
                            @endif
                        </div>
                    </div>
                    @if($tower->status === 'active')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">نشط</span>
                    @elseif($tower->status === 'maintenance')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">صيانة</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">غير نشط</span>
                    @endif
                </div>
                
                @if($tower->location)
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($tower->location, 60) }}</p>
                @endif

                <div class="flex items-center gap-2 text-xs">
                    @if($tower->type === 'tower')
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md font-medium">🗼 برج</span>
                    @elseif($tower->type === 'building')
                        <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-md font-medium">🏢 مبنى</span>
                    @else
                        <span class="px-2 py-1 bg-gray-50 text-gray-700 rounded-md font-medium">📍 عمود</span>
                    @endif
                </div>
            </div>

            {{-- Stats --}}
            <div class="p-6 space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <span class="text-sm text-gray-600 font-medium flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        الأجهزة
                    </span>
                    <span class="font-bold text-gray-900">{{ $tower->routers_count }}</span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    @if($tower->has_solar)
                        <div class="flex items-center gap-2 text-xs text-gray-600 bg-yellow-50 p-2 rounded-lg">
                            <span>☀️ طاقة شمسية</span>
                        </div>
                    @endif
                    @if($tower->battery_count > 0)
                        <div class="flex items-center gap-2 text-xs text-gray-600 bg-green-50 p-2 rounded-lg">
                            <span>🔋 {{ $tower->battery_count }} بطارية</span>
                        </div>
                    @endif
                    @if($tower->has_generator)
                        <div class="flex items-center gap-2 text-xs text-gray-600 bg-orange-50 p-2 rounded-lg">
                            <span>⚡ مولد كهرباء</span>
                        </div>
                    @endif
                </div>

                @if($tower->monthly_rent || $tower->monthly_maintenance)
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-1">التكلفة الشهرية</p>
                        <p class="font-bold text-gray-900">
                            {{ number_format(($tower->monthly_rent ?? 0) + ($tower->monthly_maintenance ?? 0), 0) }} ل.س
                        </p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center gap-3">
                <a href="{{ route('network.towers.show', $tower) }}" class="flex-1 text-center py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition text-sm">
                    عرض التفاصيل
                </a>
                <a href="{{ route('network.towers.edit', $tower) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('network.towers.destroy', $tower) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا البرج؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-gray-300">
                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد أبراج</h3>
                <p class="text-gray-500 mb-6">لم يتم العثور على أبراج تطابق معايير البحث</p>
                <a href="{{ route('network.towers.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                    مسح الفلاتر
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
