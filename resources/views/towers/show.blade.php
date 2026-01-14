@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ activeTab: 'info' }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">🗼 {{ $tower->name }}</h2>
            <p class="text-gray-500 mt-1">{{ $tower->location }}</p>
        </div>
        <a href="{{ route('network.towers.edit', $tower) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            ✏️ تعديل
        </a>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    ℹ️ معلومات
                </button>
                <button @click="activeTab = 'equipment'" :class="activeTab === 'equipment' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    📦 المعدات
                </button>
                <button @click="activeTab = 'power'" :class="activeTab === 'power' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    ⚡ الطاقة
                </button>
                <button @click="activeTab = 'ssids'" :class="activeTab === 'ssids' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    📶 SSIDs
                </button>
                <button @click="activeTab = 'costs'" :class="activeTab === 'costs' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    💰 التكاليف
                </button>
            </nav>
        </div>

        <!-- Tab: Info -->
        <div x-show="activeTab === 'info'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">موقع البرج</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500">الموقع</dt>
                            <dd class="text-gray-900">{{ $tower->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">الإحداثيات</dt>
                            <dd class="text-gray-900">{{ $tower->lat }}, {{ $tower->lng }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">الارتفاع</dt>
                            <dd class="text-gray-900">{{ $tower->height }} متر</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">المعدات المثبتة</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">📡 {{ $tower->routers->count() }} معدات شبكة</p>
                        <p class="text-sm text-gray-600">👥 {{ $tower->clients->count() }} عميل</p>
                        <p class="text-sm text-gray-600">📶 {{ $tower->ssids->count() }} SSID</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Equipment -->
        <div x-show="activeTab === 'equipment'" class="p-6">
            <div class="space-y-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                    <h3 class="font-bold text-xl text-gray-900 mb-2">💰 تكلفة الأجهزة</h3>
                    <div class="flex items-center justify-center mt-4">
                        <div>
                            <p class="text-sm text-gray-600 text-center">إجمالي تكلفة الأجهزة</p>
                            <p class="text-3xl font-bold text-blue-600">${{ number_format($tower->routers->sum('price'), 2) }}</p>
                        </div>
                    </div>
                    @if($tower->equipment_notes)
                    <p class="text-sm text-gray-600 mt-4 pt-4 border-t border-blue-200">{{ $tower->equipment_notes }}</p>
                    @endif
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">المعدات المثبتة</h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الجهاز</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">النوع</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">IP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($tower->routers as $router)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $router->name }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($router->device_type === 'router') 📡 راوتر
                                        @elseif($router->device_type === 'access_point') 📶 نقطة وصول
                                        @else 🗼 محطة بث
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-mono">{{ $router->ip }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                        لا توجد معدات مثبتة بعد
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Power -->
        <div x-show="activeTab === 'power'" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ampere -->
                <div class="bg-white border rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">⚡</span>
                        </div>
                        <h3 class="font-bold text-lg">كهرباء أمبير</h3>
                    </div>

                    @if($tower->has_ampere)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">الاشتراك الشهري</p>
                            <p class="text-xl font-bold text-gray-900">${{ number_format($tower->ampere_subscription_monthly, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">سعر الكيلو واط</p>
                            <p class="text-lg font-semibold text-gray-900">${{ number_format($tower->kwh_price, 2) }}/kWh</p>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500">⚪ غير متوفر</p>
                    @endif
                </div>

                <!-- Solar -->
                <div class="bg-white border rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">☀️</span>
                        </div>
                        <h3 class="font-bold text-lg">الطاقة الشمسية</h3>
                    </div>

                    @if($tower->has_solar)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">عدد الألواح</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $tower->solar_panels_count }} لوح</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">استطاعة اللوح الواحد</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $tower->solar_panel_wattage }}W</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">الاستطاعة الكلية</p>
                            <p class="text-xl font-bold text-green-600">{{ $tower->total_solar_capacity }}W</p>
                        </div>
                        <div class="pt-3 border-t">
                            <p class="text-sm text-gray-500">تكلفة التركيب</p>
                            <p class="text-lg font-semibold text-gray-900">${{ number_format($tower->solar_installation_cost, 2) }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500">⚪ غير متوفر</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab: SSIDs -->
        <div x-show="activeTab === 'ssids'" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900">📶 شبكات WiFi على البرج</h3>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    + إضافة SSID
                </button>
            </div>

            <div class="bg-white border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">SSID</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">التردد</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الجهاز</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tower->ssids as $ssid)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $ssid->ssid_name }}</td>
                            <td class="px-4 py-3 text-sm">{!! $ssid->frequency_badge !!}</td>
                            <td class="px-4 py-3 text-sm">{{ $ssid->router?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{!! $ssid->status_badge !!}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                لم يتم إضافة SSIDs بعد
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Costs -->
        <div x-show="activeTab === 'costs'" class="p-6">
            <div class="space-y-6">
                <!-- Setup Costs -->
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">🛠️ تكاليف التجهيز الأولية</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">تكلفة الهيكل</p>
                            <p class="text-2xl font-bold text-gray-800">${{ number_format($tower->structure_cost, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">تكلفة الأجهزة</p>
                            <p class="text-2xl font-bold text-gray-800">${{ number_format($tower->routers->sum('price'), 2) }}</p>
                        </div>
                        <div class="border-r border-purple-200 pr-4">
                            <p class="text-sm text-purple-800 font-semibold">الإجمالي الكلي</p>
                            <p class="text-3xl font-bold text-purple-600">${{ number_format($tower->total_equipment_cost, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fixed Costs -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">💵 التكاليف الثابتة الشهرية</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">صيانة</p>
                            <p class="text-lg font-bold">${{ number_format($tower->monthly_maintenance, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">إيجار</p>
                            <p class="text-lg font-bold">${{ number_format($tower->monthly_rent, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">أخرى</p>
                            <p class="text-lg font-bold">${{ number_format($tower->monthly_other_costs, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-300">
                        <p class="text-sm text-gray-600">الإجمالي الثابت</p>
                        <p class="text-2xl font-bold text-blue-600">${{ number_format($tower->monthly_fixed_costs, 2) }}/شهر</p>
                    </div>
                </div>

                <!-- Current Month -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-2">📅 تكلفة الشهر الحالي</h3>
                    <p class="text-3xl font-bold text-green-600">${{ number_format($tower->getCurrentMonthCost(), 2) }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ now()->format('F Y') }}</p>
                </div>

                <!-- Monthly Costs History -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-900">📊 سجل التكاليف الشهرية</h3>
                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            + إضافة فاتورة
                        </button>
                    </div>

                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الشهر</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">فاتورة أمبير</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">مازوت (ديزل)</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">صيانة</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">أخرى</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($tower->monthlyCosts()->orderBy('month', 'desc')->take(6)->get() as $cost)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium">{{ \Carbon\Carbon::parse($cost->month . '-01')->format('F Y') }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($cost->ampere_bill, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($cost->diesel_cost, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($cost->maintenance_cost, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($cost->other_costs, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600">${{ number_format($cost->total_cost, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        لم يتم إضافة فواتير بعد
                                    </td>
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
