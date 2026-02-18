@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ request('tab', 'overview') }}' }">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('network.towers.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $tower->name }}</h2>
                <p class="text-gray-500 mt-0.5">{{ $tower->location ?? 'موقع غير محدد' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($tower->status === 'active')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full ml-2 animate-pulse"></span>
                    نشط
                </span>
            @elseif($tower->status === 'maintenance')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-amber-100 text-amber-700 border border-amber-200">
                    <span class="w-2 h-2 bg-amber-500 rounded-full ml-2"></span>
                    صيانة
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-600 border border-gray-200">
                    <span class="w-2 h-2 bg-gray-400 rounded-full ml-2"></span>
                    غير نشط
                </span>
            @endif
            <a href="{{ route('network.towers.edit', $tower) }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-lg shadow-blue-200">
                ✏️ تعديل البرج
            </a>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Equipment Count --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <p class="text-blue-100 font-medium text-sm">الأجهزة</p>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold">{{ $tower->routers->count() }}</h3>
            <p class="text-blue-100 text-xs mt-1">Router & AP</p>
        </div>

        {{-- SSIDs Count --}}
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <p class="text-purple-100 font-medium text-sm">الشبكات</p>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold">{{ $tower->ssids->count() }}</h3>
            <p class="text-purple-100 text-xs mt-1">SSID</p>
        </div>

        {{-- Clients Count --}}
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-xl p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <p class="text-emerald-100 font-medium text-sm">العملاء</p>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold">{{ $tower->clients->count() }}</h3>
            <p class="text-emerald-100 text-xs mt-1">مشترك نشط</p>
        </div>

        {{-- Monthly Cost --}}
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-xl p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <p class="text-amber-100 font-medium text-sm">التكلفة الشهرية</p>
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold">{{ number_format($tower->monthly_fixed_costs ?? 0, 0) }}</h3>
            <p class="text-amber-100 text-xs mt-1">{{ $currency }}</p>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="px-6 py-4 border-b-2 font-semibold text-sm transition whitespace-nowrap">
                    📊 نظرة عامة
                </button>
                <button @click="activeTab = 'equipment'" :class="activeTab === 'equipment' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="px-6 py-4 border-b-2 font-semibold text-sm transition whitespace-nowrap">
                    📡 الأجهزة والشبكات
                </button>
                <button @click="activeTab = 'power'" :class="activeTab === 'power' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="px-6 py-4 border-b-2 font-semibold text-sm transition whitespace-nowrap">
                    ⚡ الطاقة
                </button>
                <button @click="activeTab = 'costs'" :class="activeTab === 'costs' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="px-6 py-4 border-b-2 font-semibold text-sm transition whitespace-nowrap">
                    💰 التكاليف
                </button>
                <button @click="activeTab = 'connection'" :class="activeTab === 'connection' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="px-6 py-4 border-b-2 font-semibold text-sm transition whitespace-nowrap">
                    🔗 الاتصال
                </button>
            </nav>
        </div>

        {{-- Tab Content --}}
        <div class="p-6">
            {{-- Overview Tab --}}
            <div x-show="activeTab === 'overview'" x-transition class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Basic Info --}}
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                معلومات أساسية
                            </h3>
                            <a href="{{ route('network.towers.edit', [$tower, 'tab' => 'basic']) }}" class="text-xs text-blue-600 hover:underline font-medium">تعديل</a>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between p-3 bg-white rounded-lg">
                                <span class="text-sm text-gray-600">النوع</span>
                                <span class="font-semibold text-gray-900">
                                    @if($tower->type === 'tower') 🗼 برج
                                    @elseif($tower->type === 'building') 🏢 مبنى
                                    @else 📍 عمود
                                    @endif
                                </span>
                            </div>
                            @if($tower->city)
                            <div class="flex justify-between p-3 bg-white rounded-lg">
                                <span class="text-sm text-gray-600">المحافظة</span>
                                <span class="font-semibold text-gray-900">{{ $tower->city }}</span>
                            </div>
                            @endif
                            @if($tower->district)
                            <div class="flex justify-between p-3 bg-white rounded-lg">
                                <span class="text-sm text-gray-600">المنطقة</span>
                                <span class="font-semibold text-gray-900">{{ $tower->district }}</span>
                            </div>
                            @endif
                            @if($tower->height)
                            <div class="flex justify-between p-3 bg-white rounded-lg">
                                <span class="text-sm text-gray-600">الارتفاع</span>
                                <span class="font-semibold text-gray-900">{{ $tower->height }} متر</span>
                            </div>
                            @endif
                            @if($tower->lat && $tower->lng)
                            <div class="flex justify-between p-3 bg-white rounded-lg">
                                <span class="text-sm text-gray-600">الإحداثيات</span>
                                <span class="font-mono text-xs text-gray-900">{{ round($tower->lat, 4) }}, {{ round($tower->lng, 4) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Server Info --}}
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                السيرفر المرتبط
                            </h3>
                        </div>
                        @if($tower->mikrotikServer)
                        <div class="bg-white rounded-lg p-4">
                            <p class="font-bold text-gray-900 text-lg mb-1">{{ $tower->mikrotikServer->name }}</p>
                            <p class="text-sm text-gray-600 mb-3">{{ $tower->mikrotikServer->ip }}</p>
                            <a href="{{ route('servers.show', $tower->mikrotikServer) }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                عرض تفاصيل السيرفر
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                        @else
                        <p class="text-center text-gray-500 py-4">لا يوجد سيرفر مرتبط</p>
                        @endif
                    </div>
                </div>

                {{-- Power Summary --}}
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        ملخص الطاقة
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @if($tower->battery_count > 0)
                        <div class="bg-white rounded-lg p-3 text-center">
                            <div class="text-2xl mb-1">🔋</div>
                            <p class="font-bold text-gray-900">{{ $tower->battery_count }}</p>
                            <p class="text-xs text-gray-600">بطارية</p>
                        </div>
                        @endif
                        @if($tower->has_solar)
                        <div class="bg-white rounded-lg p-3 text-center">
                            <div class="text-2xl mb-1">☀️</div>
                            <p class="font-bold text-gray-900">{{ $tower->solar_panels_count ?? '✓' }}</p>
                            <p class="text-xs text-gray-600">ألواح شمسية</p>
                        </div>
                        @endif
                        @if($tower->has_generator)
                        <div class="bg-white rounded-lg p-3 text-center">
                            <div class="text-2xl mb-1">⚡</div>
                            <p class="font-bold text-gray-900">{{ $tower->generator_capacity }}</p>
                            <p class="text-xs text-gray-600">مولد</p>
                        </div>
                        @endif
                        @if($tower->has_inverter)
                        <div class="bg-white rounded-lg p-3 text-center">
                            <div class="text-2xl mb-1">🔌</div>
                            <p class="font-bold text-gray-900">{{ $tower->inverter_capacity }}</p>
                            <p class="text-xs text-gray-600">انفرتر</p>
                        </div>
                        @endif
                    </div>
                </div>

                @if($tower->notes)
                <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-5">
                    <h4 class="font-bold text-amber-900 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        ملاحظات
                    </h4>
                    <p class="text-amber-800 whitespace-pre-line text-sm">{{ $tower->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Equipment & SSIDs Tab --}}
            {{-- Equipment & SSIDs Tab --}}
            <div x-show="activeTab === 'equipment'" x-transition class="space-y-6">
                {{-- Devices (Sectors/Antennas) --}}
                <div>
                     <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">أجهزة البث (Sectors/Omnis) ({{ $tower->broadcast_devices->count() }})</h3>
                        <button @click="$dispatch('open-device-modal')" type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            إضافة جهاز بث
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        @forelse($tower->broadcast_devices as $device)
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition">
                            <!-- Device Header -->
                            <div class="p-5 flex items-start justify-between bg-gray-50 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-lg">{{ $device->name }}</p>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <span class="font-mono bg-gray-200 px-1.5 py-0.5 rounded">{{ $device->ip }}</span>
                                            @if($device->mac_address)
                                            <span class="text-gray-300">|</span>
                                            <span class="font-mono">{{ $device->mac_address }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                    <!-- Actions -->
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wider">{{ $device->mode }}</span>
                                        
                                        @if($device instanceof \App\Models\Router)
                                            <!-- Router Actions -->
                                            <a href="{{ route('routers.edit', $device->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="تعديل">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('routers.destroy', $device->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الجهاز؟')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @else
                                            <!-- TowerDevice Actions -->
                                            <button type="button" onclick="alert('تحت التطوير')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="تعديل">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <form action="{{ route('network.towers.devices.destroy', [$tower, $device]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الجهاز؟')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                            </div>
                            
                            <!-- Device Details -->
                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm border-b border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">التردد:</span>
                                    <span class="font-semibold text-gray-900">{{ $device->frequency ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">SSID الأساسي:</span>
                                    <span class="font-semibold text-gray-900">{{ $device->ssid ?? '-' }}</span>
                                </div>
                                @if($device->deviceModel)
                                <div class="flex justify-between items-center sm:col-span-2">
                                    <span class="text-gray-500">الموديل:</span>
                                    <span class="font-semibold text-gray-900">{{ $device->deviceModel->manufacturer }} {{ $device->deviceModel->model_name }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Nested SSIDs -->
                            <div class="bg-gray-50 p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">الشبكات (SSIDs) التابعة لهذا الجهاز</h4>
                                    <button @click="$dispatch('open-ssid-modal', { deviceId: {{ $device->id }}, deviceName: '{{ $device->name }}', deviceType: '{{ $device instanceof \App\Models\Router ? 'router' : 'tower_device' }}' })" type="button" class="text-xs bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg font-medium transition  flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        إضافة SSID
                                    </button>
                                </div>
                                @if($device->ssids->count() > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($device->ssids as $ssid)
                                        <div class="flex items-start justify-between gap-3 p-3 bg-white border border-gray-200 rounded-lg group">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 text-sm">{{ $ssid->ssid_name }}</p>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="text-xs text-gray-500">{{ $ssid->frequency }}</span>
                                                        <span class="text-[10px] px-1.5 py-0.5 rounded {{ $ssid->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                            {{ $ssid->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Edit/Delete buttons — only for real TowerSSID records --}}
                                            @if(isset($ssid->id))
                                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition shrink-0">
                                                <button type="button"
                                                    @click="$dispatch('open-edit-ssid-modal', {
                                                        ssidId: {{ $ssid->id }},
                                                        ssidName: '{{ $ssid->ssid_name }}',
                                                        frequency: '{{ $ssid->frequency }}',
                                                        isActive: {{ $ssid->is_active ? 'true' : 'false' }},
                                                        notes: '{{ $ssid->notes ?? '' }}'
                                                    })"
                                                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="تعديل">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <form action="{{ route('network.towers.ssids.destroy', [$tower, $ssid->id]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشبكة؟')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="حذف">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 italic">لا توجد شبكات إضافية محددة</p>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12 text-gray-500 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                            <p class="font-medium">لا توجد أجهزة بث مضافة</p>
                            <p class="text-xs mt-1">يمكنك إضافة أجهزة البث من صفحة التعديل</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Unassigned SSIDs --}}
                @php
                    $unassignedSsids = $tower->ssids->whereNull('tower_device_id');
                @endphp
                @if($unassignedSsids->count() > 0)
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-700">شبكات أخرى (غير مرتبطة بجهاز)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($unassignedSsids as $ssid)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 opacity-75">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $ssid->ssid_name }}</p>
                                    <p class="text-xs text-gray-600">{{ $ssid->frequency }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-red-500 font-medium">⚠️ غير مرتبط بجهاز</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Power Tab --}}
            <div x-show="activeTab === 'power'" x-transition>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($tower->battery_count > 0)
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-xl">🔋</span>
                            </div>
                            البطاريات
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">العدد</span>
                                <span class="font-bold text-gray-900">{{ $tower->battery_count }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">النوع</span>
                                <span class="font-bold text-gray-900">{{ $tower->battery_type }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_solar)
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <span class="text-xl">☀️</span>
                            </div>
                            الطاقة الشمسية
                        </h4>
                        <div class="space-y-2">
                            @if($tower->solar_panels_count)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">عدد الألواح</span>
                                <span class="font-bold text-gray-900">{{ $tower->solar_panels_count }}</span>
                            </div>
                            @endif
                            @if($tower->solar_panel_wattage)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">القدرة/لوح</span>
                                <span class="font-bold text-gray-900">{{ $tower->solar_panel_wattage }}W</span>
                            </div>
                            @endif
                            @if($tower->total_solar_capacity)
                            <div class="flex justify-between pt-2 border-t border-yellow-200">
                                <span class="text-sm text-gray-600">الإجمالي</span>
                                <span class="font-bold text-yellow-700">{{ $tower->total_solar_capacity }}W</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($tower->has_generator)
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <span class="text-xl">⚡</span>
                            </div>
                            المولد الكهربائي
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">القدرة</span>
                                <span class="font-bold text-gray-900">{{ $tower->generator_capacity }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_inverter)
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="text-xl">🔌</span>
                            </div>
                            الانفرتر
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">القدرة</span>
                                <span class="font-bold text-gray-900">{{ $tower->inverter_capacity }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($tower->has_government_electricity)
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-xl">💡</span>
                            </div>
                            كهرباء الدولة
                        </h4>
                        @if($tower->government_electricity_notes)
                        <p class="text-sm text-gray-700">{{ $tower->government_electricity_notes }}</p>
                        @else
                        <p class="text-sm text-gray-500">متوفرة</p>
                        @endif
                    </div>
                    @endif
                </div>

                @if(!$tower->battery_count && !$tower->has_solar && !$tower->has_generator && !$tower->has_inverter)
                <div class="text-center py-16 text-gray-500">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <p class="font-medium">لم يتم تحديد معلومات الطاقة بعد</p>
                    <a href="{{ route('network.towers.edit', [$tower, 'tab' => 'power']) }}" class="inline-block mt-3 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        إضافة معلومات الطاقة
                    </a>
                </div>
                @endif
            </div>

            {{-- Costs Tab --}}
            <div x-show="activeTab === 'costs'" x-transition>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Monthly Fixed Costs --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h4 class="font-bold text-gray-900 mb-4">التكاليف الشهرية الثابتة</h4>
                        <div class="space-y-3">
                            @if($tower->monthly_rent)
                            <div class="flex justify-between p-3 bg-blue-50 rounded-lg">
                                <span class="text-sm text-gray-600">إيجار</span>
                                <span class="font-bold text-gray-900">{{ number_format($tower->monthly_rent, 0) }} {{ $currency }}</span>
                            </div>
                            @endif
                            @if($tower->monthly_maintenance)
                            <div class="flex justify-between p-3 bg-purple-50 rounded-lg">
                                <span class="text-sm text-gray-600">صيانة</span>
                                <span class="font-bold text-gray-900">{{ number_format($tower->monthly_maintenance, 0) }} {{ $currency }}</span>
                            </div>
                            @endif
                            @if($tower->monthly_other_costs)
                            <div class="flex justify-between p-3 bg-amber-50 rounded-lg">
                                <span class="text-sm text-gray-600">تكاليف أخرى</span>
                                <span class="font-bold text-gray-900">{{ number_format($tower->monthly_other_costs, 0) }} {{ $currency }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between p-4 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-lg mt-4">
                                <span class="font-bold">الإجمالي الشهري</span>
                                <span class="font-bold text-xl">{{ number_format($tower->monthly_fixed_costs ?? 0, 0) }} {{ $currency }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Equipment Costs --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h4 class="font-bold text-gray-900 mb-4">تكاليف المعدات</h4>
                        <div class="space-y-3">
                            @if($tower->structure_cost)
                            <div class="flex justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">تكلفة الهيكل</span>
                                <span class="font-bold text-gray-900">{{ number_format($tower->structure_cost, 0) }} {{ $currency }}</span>
                            </div>
                            @endif
                            @if($tower->solar_installation_cost)
                            <div class="flex justify-between p-3 bg-yellow-50 rounded-lg">
                                <span class="text-sm text-gray-600">تركيب الطاقة الشمسية</span>
                                <span class="font-bold text-gray-900">{{ number_format($tower->solar_installation_cost, 0) }} {{ $currency }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between p-4 bg-gradient-to-br from-purple-600 to-purple-700 text-white rounded-lg mt-4">
                                <span class="font-bold">إجمالي الاستثمار</span>
                                <span class="font-bold text-xl">{{ number_format($tower->total_equipment_cost ?? 0, 0) }} {{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tower->monthly_notes)
                <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-5">
                    <h4 class="font-bold text-amber-900 mb-2">ملاحظات التكاليف</h4>
                    <p class="text-amber-800 text-sm">{{ $tower->monthly_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Connection Tab --}}
            <div x-show="activeTab === 'connection'" x-transition>
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h4 class="font-bold text-gray-900 mb-6 text-lg">تفاصيل الاتصال</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2">
                             @if($tower->mikrotikServer)
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">السيرفر الرئيسي (Uplink)</p>
                                        <p class="font-bold text-gray-900 text-lg">{{ $tower->mikrotikServer->name }}</p>
                                        <p class="text-xs text-gray-500 font-mono">{{ $tower->mikrotikServer->ip_address }}</p>
                                    </div>
                                    <a href="{{ route('servers.show', $tower->mikrotikServer) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if($tower->connection_type)
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">نوع الاتصال</p>
                            <p class="font-bold text-gray-900 capitalize">{{ $tower->connection_type }}</p>
                        </div>
                        @endif
                        @if($tower->connection_port)
                        <div class="p-4 bg-purple-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">المنفذ</p>
                            <p class="font-bold text-gray-900 font-mono">{{ $tower->connection_port }}</p>
                        </div>
                        @endif
                        
                        @if($tower->connection_type === 'wireless')
                            {{-- Transmitter Details --}}
                            <div class="md:col-span-2 bg-white border border-gray-200 rounded-xl overflow-hidden mt-4">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                    <h5 class="font-bold text-gray-800 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-15.857 21.213 0"/></svg>
                                        تفاصيل الربط اللاسلكي (PTP)
                                    </h5>
                                </div>
                                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Source / Transmitter --}}
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">المرسل (Source)</p>
                                        @if($tower->transmitterRouter)
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                                                    <span class="text-lg">📡</span>
                                                </div>
                                                <div>
                                                    <a href="{{ route('routers.show', $tower->transmitterRouter) }}" class="font-bold text-blue-600 hover:underline">
                                                        {{ $tower->transmitterRouter->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $tower->transmitterRouter->ip }}</p>
                                                    <div class="mt-2 flex items-center gap-2">
                                                        <span class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-600">
                                                            {{ $tower->transmitterRouter->deviceModel->model_name ?? 'Unknown Model' }}
                                                        </span>
                                                    </div>
                                                    @if($tower->transmitterRouter->tower)
                                                        <p class="text-xs text-gray-500 mt-2">
                                                            على برج: <a href="{{ route('network.towers.show', $tower->transmitterRouter->tower) }}" class="text-gray-700 font-semibold hover:underline">{{ $tower->transmitterRouter->tower->name }}</a>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-3 bg-gray-50 rounded border border-gray-100 text-center">
                                                <p class="text-sm text-gray-500">جهاز غير محدد</p>
                                                <p class="text-xs text-gray-400">{{ $tower->transmitterModel->model_name ?? '-' }}</p>
                                                <p class="text-xs font-mono text-gray-400">{{ $tower->transmitter_ip }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Destination / Receiver --}}
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">المستقبل (Receiver)</p>
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                                                <span class="text-lg">🏠</span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">
                                                    {{ $tower->name }} (Station)
                                                </p>
                                                <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $tower->receiver_ip }}</p>
                                                <div class="mt-2 text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-600 inline-block">
                                                     {{ $tower->receiverModel->model_name ?? 'Unknown Model' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Device Creation Modal --}}
    <div x-data="{ open: false, deviceMode: 'existing' }" 
         @open-device-modal.window="open = true; deviceMode = 'existing'" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="relative z-10 inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <form action="{{ route('network.towers.devices.store', $tower) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-6">
                            <h3 class="text-xl leading-6 font-bold text-gray-900">إضافة جهاز بث للبرج</h3>
                            <p class="text-sm text-gray-500 mt-1">Sector, Omni, أو Access Point</p>
                        </div>

                        {{-- Toggle Between Existing and New --}}
                        <div class="mb-6">
                            <div class="flex gap-3 p-2 bg-gray-100 rounded-lg">
                                <button type="button" @click="deviceMode = 'existing'" :class="deviceMode === 'existing' ? 'bg-white shadow-sm' : 'bg-transparent'" class="flex-1 px-4 py-3 rounded-md font-semibold text-sm transition">
                                    📡 جهاز موجود
                                </button>
                                <button type="button" @click="deviceMode = 'new'" :class="deviceMode === 'new' ? 'bg-white shadow-sm' : 'bg-transparent'" class="flex-1 px-4 py-3 rounded-md font-semibold text-sm transition">
                                    ➕ جهاز جديد
                                </button>
                            </div>
                        </div>

                        {{-- Existing Device Selection --}}
                        <div x-show="deviceMode === 'existing'" x-transition class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">اختر جهاز بث من معدات الشبكة</label>
                                <select name="existing_device_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- اختر الجهاز --</option>
                                    @foreach(\App\Models\TowerDevice::whereNull('tower_id')->orWhere('tower_id', '!=', $tower->id)->get() as $device)
                                        <option value="{{ $device->id }}">
                                            {{ $device->name }} - {{ $device->mode }} ({{ $device->ip ?? 'No IP' }}) 
                                            @if($device->tower) - {{ $device->tower->name }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-2">سيتم نقل الجهاز وربطه بهذا البرج</p>
                            </div>
                        </div>

                        {{-- New Device Form --}}
                        <div x-show="deviceMode === 'new'" x-transition class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">اسم الجهاز *</label>
                                    <input type="text" name="name" :required="deviceMode === 'new'" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" placeholder="مثلاً: Sector 1">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">موديل الجهاز</label>
                                    <select name="device_model_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                        <option value="">اختر الموديل...</option>
                                        @foreach(\App\Models\DeviceModel::all() as $model)
                                            <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                                    <input type="text" name="ip" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" dir="ltr" placeholder="192.168.1.10">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">MAC Address</label>
                                    <input type="text" name="mac_address" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" dir="ltr" placeholder="00:00:00:00:00:00">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الوضع (Mode) *</label>
                                    <select name="mode" :required="deviceMode === 'new'" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                        <option value="ap">Access Point (AP)</option>
                                        <option value="station">Station</option>
                                        <option value="bridge">Bridge</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد</label>
                                    <select name="frequency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="60GHz">60 GHz</option>
                                        <option value="other">أخرى</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة الأساسي (SSID)</label>
                                <input type="text" name="ssid" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" placeholder="إذا كان AP">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="device_mode" x-bind:value="deviceMode">
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-green-600 text-base font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            حفظ وإضافة
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm transition">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SSID Creation Modal --}}
    <div x-data="{ open: false, deviceId: null, deviceName: '', deviceType: '' }" 
         @open-ssid-modal.window="open = true; deviceId = $event.detail.deviceId; deviceName = $event.detail.deviceName; deviceType = $event.detail.deviceType" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="relative z-10 inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('network.towers.ssids.store', $tower) }}" method="POST">
                    @csrf
                    <input type="hidden" name="tower_device_id" x-bind:value="deviceId">
                    <input type="hidden" name="device_type" x-bind:value="deviceType">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">إضافة شبكة (SSID) جديدة</h3>
                            <p class="text-sm text-gray-500 mt-1">للجهاز: <span class="font-semibold" x-text="deviceName"></span></p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة (SSID) *</label>
                                <input type="text" name="ssid_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="My-WiFi">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد *</label>
                                    <select name="frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                                    <select name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="1">فعال</option>
                                        <option value="0">غير فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            حفظ SSID
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit SSID Modal --}}
    <div x-data="{ open: false, ssidId: null, ssidName: '', frequency: '', isActive: true, notes: '' }"
         @open-edit-ssid-modal.window="open = true; ssidId = $event.detail.ssidId; ssidName = $event.detail.ssidName; frequency = $event.detail.frequency; isActive = $event.detail.isActive; notes = $event.detail.notes"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="relative z-10 inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form :action="'{{ url('MadaaQ/public/network/towers/' . $tower->id . '/ssids') }}/' + ssidId" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">تعديل شبكة (SSID)</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة (SSID) *</label>
                                <input type="text" name="ssid_name" required x-model="ssidName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد *</label>
                                    <select name="frequency" required x-model="frequency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                                    <select name="is_active" x-model="isActive" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="1">فعال</option>
                                        <option value="0">غير فعال</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                <textarea name="notes" rows="2" x-model="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            حفظ التعديلات
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
