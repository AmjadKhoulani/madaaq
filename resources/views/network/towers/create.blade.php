@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Custom Map Styles */
    #map { height: 400px; width: 100%; border-radius: 1rem; position: relative; z-index: 10; }
    #device-map { height: 300px; width: 100%; border-radius: 0.75rem; }
    
    /* Wizard Step Transition */
    .step-content {
        transition: opacity 0.3s ease-in-out;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-8" x-data="towerWizard">
    
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-indigo-600">
                إضافة برج جديد
            </h2>
            <p class="text-gray-500 mt-2">قم بإضافة برج جديد إلى شبكتك وتكوين تفاصيله الكاملة</p>
        </div>
        <a href="{{ route('network.towers.index') }}" class="px-5 py-2.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-xl transition shadow-sm">
            إلغاء والعودة
        </a>
    </div>

    {{-- Error Handling Alert --}}
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-5 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="bg-red-100 p-2 rounded-full text-red-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="text-red-800 font-bold mb-1">يوجد أخطاء في البيانات المدخلة</h3>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('network.towers.store') }}" method="POST" id="tower-form" class="space-y-8" @submit.prevent="submitForm">
        @csrf

        {{-- Wizard Progress Bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/60 p-4">
            <div class="relative flex items-center justify-between px-6">
                {{-- Progress Bar Background --}}
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1.5 bg-gray-100 rounded-full -z-0 mx-6"></div>
                
                {{-- Active Progress --}}
                <div class="absolute right-0 top-1/2 transform -translate-y-1/2 h-1.5 bg-blue-600 rounded-full -z-0 transition-all duration-500 ease-out mx-6"
                     :style="'width: ' + ((step - 1) / (totalSteps - 1) * 100) + '%'"></div>

                <template x-for="(s, index) in steps" :key="index">
                    <div class="relative z-10 flex flex-col items-center cursor-pointer group" @click="goToStep(index + 1)">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 ring-4 ring-white"
                             :class="step >= index + 1 ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 scale-110' : 'bg-gray-100 text-gray-400 group-hover:bg-gray-200'">
                            <span x-text="index + 1"></span>
                        </div>
                        <span class="absolute top-12 text-xs font-bold whitespace-nowrap transition-colors duration-300"
                              :class="step >= index + 1 ? 'text-blue-700' : 'text-gray-400'"
                              x-text="s"></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Step 1: Basic Information --}}
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="step-content">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-8 pb-4 border-b border-gray-100 flex items-center gap-3">
                    <span class="bg-blue-50 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">1</span>
                    المعلومات الأساسية
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">اسم البرج / النقطة <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400"
                                placeholder="مثال: برج الجامعة">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">نوع الموقع <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="tower" class="peer sr-only" {{ old('type', 'tower') == 'tower' ? 'checked' : '' }}>
                                    <div class="text-center py-3 border-2 border-gray-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                        <div class="text-xl mb-1">🗼</div>
                                        <div class="text-sm font-bold text-gray-700">برج</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="building" class="peer sr-only" {{ old('type') == 'building' ? 'checked' : '' }}>
                                    <div class="text-center py-3 border-2 border-gray-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                        <div class="text-xl mb-1">🏢</div>
                                        <div class="text-sm font-bold text-gray-700">مبنى</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="pole" class="peer sr-only" {{ old('type') == 'pole' ? 'checked' : '' }}>
                                    <div class="text-center py-3 border-2 border-gray-200 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                        <div class="text-xl mb-1">📍</div>
                                        <div class="text-sm font-bold text-gray-700">عمود</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">المحافظة</label>
                                <select name="city" x-model="city" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500">
                                    <option value="">اختر المحافظة...</option>
                                    <option value="Damascus">دمشق</option>
                                    <option value="Aleppo">حلب</option>
                                    <option value="Homs">حمص</option>
                                    <option value="Latakia">اللاذقية</option>
                                    <option value="Hama">حماة</option>
                                    <option value="Tartus">طرطوس</option>
                                    <option value="Idlib">إدلب</option>
                                    <option value="Daraa">درعا</option>
                                    <option value="Suwayda">السويداء</option>
                                    <option value="Rif Dimashq">ريف دمشق</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">المنطقة</label>
                                <input type="text" name="district" value="{{ old('district') }}" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500" placeholder="الحي / الناحية">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">العنوان التفصيلي</label>
                            <textarea name="location" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500" 
                                placeholder="وصف دقيق للموقع للوصول إليه...">{{ old('location') }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">الارتفاع (م)</label>
                                <input type="number" name="height" value="{{ old('height') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500" placeholder="مثال: 15">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">عدد الطوابق</label>
                                <input type="number" name="number_of_floors" value="{{ old('number_of_floors') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500" placeholder="مثال: 4">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">حالة البرج</label>
                            <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🟢 فعال - Active</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>🟠 صيانة - Maintenance</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 متوقف - Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 2: Location Map --}}
        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="step-content">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="bg-blue-50 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">2</span>
                    تحديد الموقع الجغرافي
                </h3>
                
                <div class="relative rounded-2xl overflow-hidden border-2 border-gray-100 shadow-inner">
                    <div id="map"></div>
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm p-4 rounded-xl shadow-lg z-[1000] text-sm border border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">خط العرض (Lat)</label>
                                <input type="text" name="lat" x-model="lat" readonly class="w-28 px-2 py-1 bg-gray-50 rounded border border-gray-200 font-mono text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">خط الطول (Lng)</label>
                                <input type="text" name="lng" x-model="lng" readonly class="w-28 px-2 py-1 bg-gray-50 rounded border border-gray-200 font-mono text-xs">
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-center">انقر على الخريطة لتحديث الموقع</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 3: Power Configuration --}}
        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="step-content">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                    <span class="bg-blue-50 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">3</span>
                    إعدادات الطاقة
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Batteries -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 group hover:border-blue-200 transition">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-white rounded-lg shadow-sm text-lg">🔋</div>
                            <h4 class="font-bold text-gray-800">نظام البطاريات</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">العدد</label>
                                <input type="number" name="battery_count" value="{{ old('battery_count', 0) }}" class="mt-1 w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">النوع / السعة</label>
                                <input type="text" name="battery_type" value="{{ old('battery_type') }}" class="mt-1 w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="200Ah Gel">
                            </div>
                        </div>
                    </div>

                    <!-- Solar -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 group hover:border-yellow-200 transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-lg">☀️</div>
                                <h4 class="font-bold text-gray-800">الطاقة الشمسية</h4>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_solar" value="1" x-model="hasSolar" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-yellow-400"></div>
                            </label>
                        </div>
                        <div x-show="hasSolar" x-collapse>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">عدد الألواح</label>
                                    <input type="number" name="solar_panels_count" value="{{ old('solar_panels_count') }}" class="mt-1 w-full px-3 py-2 bg-white border border-gray-200 rounded-lg">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">القدرة (واط)</label>
                                    <input type="number" name="solar_panel_wattage" value="{{ old('solar_panel_wattage') }}" class="mt-1 w-full px-3 py-2 bg-white border border-gray-200 rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Generator -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 group hover:border-orange-200 transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-lg">⚡</div>
                                <h4 class="font-bold text-gray-800">المولد (Motor)</h4>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_generator" value="1" x-model="hasGenerator" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500"></div>
                            </label>
                        </div>
                        <div x-show="hasGenerator" x-collapse>
                            <label class="text-xs font-semibold text-gray-500 uppercase">قدرة المولد</label>
                            <input type="text" name="generator_capacity" value="{{ old('generator_capacity') }}" class="mt-1 w-full px-3 py-2 bg-white border border-gray-200 rounded-lg" placeholder="e.g 10 KVA">
                        </div>
                    </div>

                    <!-- Government Electricity -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 group hover:border-indigo-200 transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-lg">🏭</div>
                                <h4 class="font-bold text-gray-800">كهرباء الدولة</h4>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_government_electricity" value="1" x-model="hasGovElec" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500"></div>
                            </label>
                        </div>
                        <div x-show="hasGovElec" x-collapse>
                            <label class="text-xs font-semibold text-gray-500 uppercase">ملاحظات التوقيت</label>
                            <textarea name="government_electricity_notes" rows="2" class="mt-1 w-full px-3 py-2 bg-white border border-gray-200 rounded-lg" placeholder="ساعات الوصل والقطع...">{{ old('government_electricity_notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 4: Network & Costs --}}
        <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="step-content">
            
            {{-- Network Connection --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">🔗</div>
                     الربط الشبكي
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">السيرفر المغذي (Sending Server)</label>
                        <select name="mikrotik_server_id" x-model="serverId" @change="fetchInterfaces" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500">
                            <option value="">-- مباشر من المقسم --</option>
                            @foreach($servers as $server)
                                <option value="{{ $server->id }}" {{ old('mikrotik_server_id') == $server->id ? 'selected' : '' }}>
                                    {{ $server->name }} ({{ $server->ip }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">نوع وسيط النقل</label>
                        <select name="connection_type" x-model="connectionType" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500">
                            <option value="">-- اختر النوع --</option>
                            <option value="wireless">لاسلكي (PTP Wireless)</option>
                            <option value="fiber">ألياف ضوئية (Fiber)</option>
                            <option value="cable">كابل شبكة (Ethernet Cable)</option>
                        </select>
                    </div>

                    {{-- Wired Interface Selection --}}
                    <div x-show="connectionType === 'cable' || connectionType === 'fiber'">
                        <label class="block text-sm font-bold text-gray-700 mb-2">المنفذ (Interface)</label>
                        <input type="text" 
                               name="connection_port" 
                               value="{{ old('connection_port') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500" 
                               placeholder="مثال: ether1, sfp-sfpplus1, ether5"
                               dir="ltr">
                        <p class="text-xs text-gray-500 mt-1">أدخل اسم المنفذ يدوياً</p>
                    </div>
                </div>

                {{-- Wireless Connection Details --}}
                <div x-show="connectionType === 'wireless'" class="mt-8 pt-8 border-t border-gray-100">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-6">تفاصيل الربط اللاسلكي</h4>
                    <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- Transmitter Side --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-bold text-indigo-900">المرسل (Transmission)</label>
                                <div class="flex gap-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="transmitter_type" value="existing" x-model="transmitterType" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="mr-2 text-xs text-gray-700">موجود</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="transmitter_type" value="new" x-model="transmitterType" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="mr-2 text-xs text-gray-700">جديد</span>
                                    </label>
                                </div>
                            </div>
                            
                            {{-- Existing Transmitter --}}
                            <div x-show="transmitterType === 'existing'">
                                <select name="transmitter_router_id" class="w-full px-4 py-3 bg-white border border-indigo-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                                    <option value="">اختر مرسل موجود...</option>
                                    @foreach($activeRouters as $router)
                                        <option value="{{ $router->id }}">{{ $router->name }} | {{ $router->ip }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- New Transmitter --}}
                            <div x-show="transmitterType === 'new'" class="space-y-3">
                                <input type="text" name="transmitter_name" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-sm" placeholder="اسم الجهاز المرسل">
                                <input type="text" name="transmitter_ip" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-sm" placeholder="IP Address" dir="ltr">
                                <select name="transmitter_model_id" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-sm">
                                    <option value="">موديل الجهاز...</option>
                                    @foreach($deviceModels as $model)
                                        <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Receiver Side --}}
                        <div class="space-y-4 relative">
                             <div class="absolute left-0 top-1/2 -translate-x-1/2 -ml-4 hidden md:block text-indigo-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M13.293 6.293L7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg> 
                             </div>

                            <label class="text-sm font-bold text-indigo-900 block">المستقبل (Receiver)</label>
                            
                            <div class="space-y-3">
                                <input type="text" name="receiver_name" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-sm" placeholder="اسم الجهاز المستقبل (اختياري)">
                                <input type="text" name="receiver_ip" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-sm" placeholder="IP Address" dir="ltr">
                                <input type="text" name="receiver_mac" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-sm" placeholder="MAC Address" dir="ltr">
                                <select name="receiver_model_id" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-lg text-sm">
                                    <option value="">موديل الجهاز...</option>
                                    @foreach($deviceModels as $model)
                                        <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             {{-- Costs Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="bg-blue-50 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">💰</span>
                    بيانات التكاليف
                </h3>
                 <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">الإيجار الشهري</label>
                        <div class="relative">
                            <input type="number" name="monthly_rent" value="{{ old('monthly_rent', 0) }}" class="w-full pr-4 pl-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 text-left" dir="ltr">
                            <span class="absolute left-3 top-2 text-gray-500 text-xs font-bold">{{ $currency }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">صيانة شهرية</label>
                        <div class="relative">
                            <input type="number" name="monthly_maintenance" value="{{ old('monthly_maintenance', 0) }}" class="w-full pr-4 pl-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 text-left" dir="ltr">
                            <span class="absolute left-3 top-2 text-gray-500 text-xs font-bold">{{ $currency }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">كلفة الهيكل</label>
                        <div class="relative">
                            <input type="number" name="structure_cost" value="{{ old('structure_cost', 0) }}" class="w-full pr-4 pl-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 text-left" dir="ltr">
                            <span class="absolute left-3 top-2 text-gray-500 text-xs font-bold">{{ $currency }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">كلفة الطاقة</label>
                        <div class="relative">
                            <input type="number" name="solar_installation_cost" value="{{ old('solar_installation_cost', 0) }}" class="w-full pr-4 pl-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 text-left" dir="ltr">
                            <span class="absolute left-3 top-2 text-gray-500 text-xs font-bold">{{ $currency }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="bg-gray-50 px-8 py-6 rounded-2xl border border-gray-200 flex items-center justify-between">
            <button type="button" @click="prevStep" x-show="step > 1" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition shadow-sm">
                السابق
            </button>
            <div class="flex-1"></div> {{-- Spacer --}}
            
            <button type="button" @click="nextStep" x-show="step < totalSteps" class="px-8 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                المتابعة للتالي
            </button>
            
            <button type="submit" x-show="step === totalSteps" class="px-8 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-xl hover:from-emerald-600 hover:to-green-700 transition shadow-lg shadow-green-200">
                ✓ حفظ البرج
            </button>
        </div>

    </form>

    {{-- Device Modal --}}
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

            <div x-show="isModalOpen" x-transition.scale 
                 class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900" x-text="modalMode === 'transmitter' ? 'إضافة جهاز إرسال جديد (على برج آخر)' : 'بيانات جهاز الاستقبال'"></h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-500">✕</button>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div x-show="modalMode === 'transmitter'">
                                <label class="block text-sm font-medium text-gray-700">البرج المصدر</label>
                                <select x-model="modalData.tower_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">اختر البرج...</option>
                                    @foreach($towers as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">اسم الجهاز</label>
                                <input type="text" x-model="modalData.name" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">الموديل</label>
                                <select x-model="modalData.model_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">اختر الموديل...</option>
                                    @foreach($deviceModels as $model)
                                        <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                             <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">IP</label>
                                    <input type="text" x-model="modalData.ip" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" dir="ltr">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">MAC</label>
                                    <input type="text" x-model="modalData.mac" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" dir="ltr">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">SSID</label>
                                <input type="text" x-model="modalData.ssid" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        {{-- Modal Map --}}
                        <div class="bg-gray-50 rounded-xl p-1 border border-gray-200">
                             <div id="device-map"></div>
                             <p class="text-xs text-center text-gray-500 mt-1">حدد موقع الجهاز</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" @click="saveModalData" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                        حفظ البيانات
                    </button>
                    <button type="button" @click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pass Data to JS Cleanly --}}
<script>
    window.towerConfig = {
        old: @json(session()->getOldInput()), // Pass all old inputs
        routes: {
            storeRouter: "{{ route('routers.store') }}"
        },
        csrf: "{{ csrf_token() }}"
    };
</script>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('towerWizard', () => ({
            step: 1,
            totalSteps: 4,
            steps: ['المعلومات الأساسية', 'الموقع', 'الطاقة', 'التكاليف والربط'],
            
            // Form State
            city: window.towerConfig.old.city || '',
            lat: window.towerConfig.old.lat || '',
            lng: window.towerConfig.old.lng || '',
            
            // Toggles
            hasSolar: !!window.towerConfig.old.has_solar,
            hasGenerator: !!window.towerConfig.old.has_generator,
            hasGovElec: !!window.towerConfig.old.has_government_electricity,
            
            // Connection State
            serverId: window.towerConfig.old.mikrotik_server_id || '',
            connectionType: window.towerConfig.old.connection_type || '',
            interfaces: [],
            loadingInterfaces: false,
            
            // Wireless State
            transmitterType: 'existing', // existing or new
            transmitterIP: window.towerConfig.old.transmitter_ip || '',
            transmitterModelId: window.towerConfig.old.transmitter_model_id || '',
            receiverModelId: window.towerConfig.old.receiver_model_id || '',
            receiverIP: window.towerConfig.old.receiver_ip || '',
            receiverSSID: window.towerConfig.old.receiver_ssid || '',
            receiverName: window.towerConfig.old.receiver_name || '',
            receiverMAC: window.towerConfig.old.receiver_mac || '',

            // Map Objects
            map: null,
            marker: null,

            // Modal State
            isModalOpen: false,
            modalMode: 'receiver', // 'transmitter' or 'receiver'
            deviceMap: null,
            deviceMarker: null,
            modalData: {
                name: '', tower_id: '', model_id: '', ip: '', mac: '', ssid: '', lat: '', lng: ''
            },

            init() {
                console.log('Wizard Initialized', window.towerConfig);
                if(this.serverId && (this.connectionType === 'cable' || this.connectionType === 'fiber')) {
                    this.fetchInterfaces();
                }
                this.$watch('connectionType', value => {
                    if ((value === 'cable' || value === 'fiber') && this.serverId) {
                        this.fetchInterfaces();
                    }
                });
            },

            fetchInterfaces() {
                if (!this.serverId) return;
                
                this.loadingInterfaces = true;
                this.interfaces = [];
                
                // Construct URL: /servers/{id}/interfaces
                let url = `{{ route('servers.index') }}/${this.serverId}/interfaces`; // Ensure this route matches web.php
                
                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        this.loadingInterfaces = false;
                        if(data.success) {
                            this.interfaces = data.data;
                        }
                    })
                    .catch(e => {
                        console.error(e);
                        this.loadingInterfaces = false;
                    });
            },
            nextStep() {
                if (this.step < this.totalSteps) {
                    this.step++;
                    if (this.step === 2) this.$nextTick(() => this.initMap());
                }
            },

            prevStep() {
                if (this.step > 1) this.step--;
            },

            goToStep(n) {
                this.step = n;
                if (n === 2) this.$nextTick(() => this.initMap());
            },

            submitForm() {
                document.getElementById('tower-form').submit();
            },

            // --- Map Logic ---
            initMap() {
                if (this.map) return;
                
                const initialLat = this.lat || 33.5138;
                const initialLng = this.lng || 36.2765;

                this.map = L.map('map').setView([initialLat, initialLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);

                if (this.lat && this.lng) {
                    this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
                }

                this.map.on('click', (e) => {
                    this.lat = e.latlng.lat.toFixed(6);
                    this.lng = e.latlng.lng.toFixed(6);
                    
                    if (this.marker) this.marker.setLatLng(e.latlng);
                    else this.marker = L.marker(e.latlng).addTo(this.map);
                });
            },

            // --- Transmitter Logic ---
            updateTransmitterInfo(e) {
                const option = e.target.selectedOptions[0];
                if (option && option.dataset.ip) {
                    this.transmitterIP = option.dataset.ip;
                    this.transmitterModelId = option.dataset.model;
                } else {
                    this.transmitterIP = '';
                    this.transmitterModelId = '';
                }
            },

            // --- Modal Logic ---
            openModal(mode) {
                this.modalMode = mode;
                this.isModalOpen = true;
                
                // Reset Data
                this.modalData = {
                    name: '', tower_id: '', model_id: '', ip: '', mac: '', ssid: '', lat: '', lng: ''
                };

                // If Receiver, default lat/lng
                if (mode === 'receiver') {
                    this.modalData.lat = this.lat;
                    this.modalData.lng = this.lng;
                    this.modalData.model_id = this.receiverModelId;
                    this.modalData.ip = this.receiverIP;
                    this.modalData.ssid = this.receiverSSID;
                }

                this.$nextTick(() => this.initDeviceMap());
            },

            closeModal() {
                this.isModalOpen = false;
            },

            initDeviceMap() {
                if (this.deviceMap) {
                    this.deviceMap.invalidateSize();
                    return;
                }

                this.deviceMap = L.map('device-map').setView([33.5138, 36.2765], 10);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.deviceMap);

                this.deviceMap.on('click', (e) => {
                    this.modalData.lat = e.latlng.lat.toFixed(6);
                    this.modalData.lng = e.latlng.lng.toFixed(6);
                    
                    if (this.deviceMarker) this.deviceMarker.setLatLng(e.latlng);
                    else this.deviceMarker = L.marker(e.latlng).addTo(this.deviceMap);
                });
            },

            saveModalData() {
                if (this.modalMode === 'transmitter') {
                    // AJAX create transmitter
                    fetch(window.towerConfig.routes.storeRouter, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': window.towerConfig.csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ...this.modalData,
                            device_type: 'access_point',
                            status: 'active'
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.id) {
                            // Add to select
                            const select = this.$refs.transmitterSelect;
                            const option = new Option(data.name + ' | ' + data.ip, data.id, true, true);
                            option.dataset.ip = data.ip;
                            option.dataset.model = data.model_id;
                            select.add(option);
                            
                            this.transmitterIP = data.ip;
                            this.transmitterModelId = data.model_id;
                            this.closeModal();
                        } else {
                            alert('Error creating device');
                        }
                    });

                } else {
                    // Local save for receiver (fill hidden inputs)
                    this.receiverName = this.modalData.name;
                    this.receiverModelId = this.modalData.model_id;
                    this.receiverIP = this.modalData.ip;
                    this.receiverSSID = this.modalData.ssid;
                    this.receiverMAC = this.modalData.mac;
                    
                    this.closeModal();
                }
            }
        }));
    });
</script>
@endpush
@endsection
