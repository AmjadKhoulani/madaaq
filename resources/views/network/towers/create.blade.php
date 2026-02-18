@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 350px; width: 100%; border-radius: 0.75rem; z-index: 1; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="towerForm">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-indigo-600">
                إضافة برج جديد
            </h2>
            <p class="text-gray-500 mt-1">إضافة نقطة توزيع جديدة إلى الشبكة (برج، مبنى، أو عمود)</p>
        </div>
        <a href="{{ route('network.towers.index') }}" class="px-5 py-2.5 text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-xl transition shadow-sm">
            إلغاء والعودة
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-red-800 font-bold mb-2">يرجى تصحيح الأخطاء التالية:</p>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('network.towers.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Card 1: Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">📝</span>
                المعلومات الأساسية
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">اسم البرج / النقطة <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400"
                        placeholder="مثال: برج الساحة، بناء المهندسين...">
                </div>

                <!-- Type Selection -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-3">نوع الموقع <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="tower" class="peer sr-only" {{ old('type', 'tower') == 'tower' ? 'checked' : '' }}>
                            <div class="text-center py-4 border-2 border-gray-100 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50/50 group-hover:border-blue-200 transition bg-white">
                                <div class="text-2xl mb-2">🗼</div>
                                <div class="text-sm font-bold text-gray-700">برج حديدي</div>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="building" class="peer sr-only" {{ old('type') == 'building' ? 'checked' : '' }}>
                            <div class="text-center py-4 border-2 border-gray-100 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50/50 group-hover:border-blue-200 transition bg-white">
                                <div class="text-2xl mb-2">🏢</div>
                                <div class="text-sm font-bold text-gray-700">بناء سكني</div>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="pole" class="peer sr-only" {{ old('type') == 'pole' ? 'checked' : '' }}>
                            <div class="text-center py-4 border-2 border-gray-100 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50/50 group-hover:border-blue-200 transition bg-white">
                                <div class="text-2xl mb-2">📍</div>
                                <div class="text-sm font-bold text-gray-700">عمود / سارية</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Location Details -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">المحافظة</label>
                    <select name="city" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="">اختر المحافظة...</option>
                        @foreach(['Damascus' => 'دمشق', 'Aleppo' => 'حلب', 'Homs' => 'حمص', 'Latakia' => 'اللاذقية', 'Hama' => 'حماة', 'Tartus' => 'طرطوس', 'Idlib' => 'إدلب', 'Daraa' => 'درعا', 'Suwayda' => 'السويداء', 'Rif Dimashq' => 'ريف دمشق'] as $key => $val)
                            <option value="{{ $key }}" {{ old('city') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">المنطقة / الحي</label>
                    <input type="text" name="district" value="{{ old('district') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">العنوان التفصيلي</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500" placeholder="الشارع، العلامات المميزة...">
                </div>

                <!-- Specs -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الارتفاع (متر)</label>
                    <input type="number" name="height" value="{{ old('height') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500">
                </div>

            </div>
        </div>

        <!-- Card 2: Map Location -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">🗺️</span>
                الموقع الجغرافي
            </h3>
            
            <div class="relative rounded-xl overflow-hidden border border-gray-200">
                <div id="map"></div>
                <!-- Coordinates Badge -->
                <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur shadow-lg rounded-lg p-3 text-xs z-[1000] border border-gray-100 flex gap-4">
                    <div>
                        <span class="block text-gray-400 font-bold mb-0.5">LAT</span>
                        <input type="text" name="lat" x-model="lat" readonly class="w-24 bg-transparent font-mono font-bold text-gray-800 border-none p-0 focus:ring-0">
                    </div>
                    <div class="w-px bg-gray-200 my-1"></div>
                    <div>
                        <span class="block text-gray-400 font-bold mb-0.5">LNG</span>
                        <input type="text" name="lng" x-model="lng" readonly class="w-24 bg-transparent font-mono font-bold text-gray-800 border-none p-0 focus:ring-0">
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                اضغط على الخريطة لتحديث الإحداثيات تلقائياً
            </p>
        </div>

        <!-- Card 3: Power Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">⚡</span>
                أمن الطاقة
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Solar -->
                <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 transition" :class="{'bg-blue-50 border-blue-300': hasSolar, 'bg-gray-50': !hasSolar}">
                    <label class="flex items-center justify-between cursor-pointer mb-3">
                        <span class="font-bold text-gray-800 flex items-center gap-2">☀️ الطاقة الشمسية</span>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="has_solar" value="1" x-model="hasSolar" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                    </label>
                    <div x-show="hasSolar" x-collapse>
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            <input type="number" name="solar_panels_count" placeholder="العدد" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                            <input type="number" name="solar_panel_wattage" placeholder="القدرة (واط)" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                        </div>
                    </div>
                </div>

                <!-- Generator -->
                <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 transition" :class="{'bg-blue-50 border-blue-300': hasGenerator, 'bg-gray-50': !hasGenerator}">
                    <label class="flex items-center justify-between cursor-pointer mb-3">
                        <span class="font-bold text-gray-800 flex items-center gap-2">🔌 المولد (Motor)</span>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="has_generator" value="1" x-model="hasGenerator" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                    </label>
                    <div x-show="hasGenerator" x-collapse>
                        <input type="text" name="generator_capacity" placeholder="القدرة (مثال: 10 KVA)" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                    </div>
                </div>

                <!-- Gov Electricity -->
                <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 transition" :class="{'bg-blue-50 border-blue-300': hasGovElec, 'bg-gray-50': !hasGovElec}">
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="font-bold text-gray-800 flex items-center gap-2">🏭 كهرباء الدولة</span>
                         <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="has_government_electricity" value="1" x-model="hasGovElec" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                    </label>
                </div>

                <!-- Ampere Subscription -->
                <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 transition" :class="{'bg-blue-50 border-blue-300': hasSubscription, 'bg-gray-50': !hasSubscription}">
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="font-bold text-gray-800 flex items-center gap-2">🔌 اشتراك (أمبير)</span>
                         <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="has_ampere_subscription" value="1" x-model="hasSubscription" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                    </label>
                </div>

                <!-- Batteries (Always Visible) -->
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                    <label class="font-bold text-gray-800 flex items-center gap-2 mb-3">🔋 البطاريات</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" name="battery_count" placeholder="العدد" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                        <input type="text" name="battery_type" placeholder="النوع/السعة" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3.5: Costs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">💰</span>
                التكاليف
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Structure Cost -->
                <div>
                     <label class="block text-sm font-bold text-gray-700 mb-2">تكلفة الهيكل (البنية التحتية)</label>
                     <div class="relative">
                        <input type="number" step="0.01" name="structure_cost" value="{{ old('structure_cost') }}" class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold">{{ $currency }}</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Rent -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الإيجار الشهري (للموقع)</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="monthly_rent" value="{{ old('monthly_rent') }}" class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold">{{ $currency }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Network & Connection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="p-2 bg-green-50 text-green-600 rounded-lg">🔗</span>
                الربط الشبكي
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">السيرفر المغذي</label>
                    <select name="mikrotik_server_id" x-model="serverId" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                        <option value="">-- مباشر --</option>
                        @foreach($servers as $server)
                            <option value="{{ $server->id }}" {{ old('mikrotik_server_id') == $server->id ? 'selected' : '' }}>{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">نوع وسيط النقل</label>
                    <select name="connection_type" x-model="connectionType" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500">
                        <option value="">-- اختر --</option>
                        <option value="wireless">لاسلكي (PTP Wireless)</option>
                        <option value="fiber">ألياف ضوئية (Fiber)</option>
                        <option value="cable">كابل شبكة (Ethernet)</option>
                    </select>
                </div>

                <!-- Wired Details -->
                <div class="md:col-span-2" x-show="connectionType === 'cable' || connectionType === 'fiber'" x-transition>
                    <label class="block text-sm font-bold text-gray-700 mb-2">اسم المنفذ (Interface)</label>
                    <input type="text" name="connection_port" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-left" dir="ltr" placeholder="ether1, sfp1...">
                </div>

                <!-- Wireless Details -->
                <div class="md:col-span-2 bg-green-50 rounded-xl p-6 border border-green-100" x-show="connectionType === 'wireless'" x-transition>
                    <h4 class="font-bold text-green-900 mb-4 border-b border-green-200 pb-2">تفاصيل الرابط اللاسلكي</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Transmitter -->
                        <div class="space-y-4">
                            <label class="text-sm font-bold text-gray-700">جهاز الإرسال (Transmitter)</label>
                            
                             <select name="transmitter_type" x-model="transmitterType" class="w-full px-3 py-2 bg-white border border-green-200 rounded-lg text-sm mb-2">
                                <option value="existing">جهاز موجود مسبقاً</option>
                                <option value="new">إضافة جهاز جديد</option>
                            </select>

                            <div x-show="transmitterType === 'existing'">
                                <select name="transmitter_router_id" class="w-full px-3 py-2 bg-white border border-green-200 rounded-lg">
                                    <option value="">اختر الجهاز...</option>
                                    @foreach($activeRouters as $router)
                                    <option value="{{ $router->id }}">{{ $router->name }} ({{ $router->ip }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="transmitterType === 'new'" class="space-y-3">
                                <input type="text" name="transmitter_name" placeholder="اسم الجهاز" class="w-full px-3 py-2 bg-white border border-green-200 rounded-lg">
                                <input type="text" name="transmitter_ip" placeholder="IP Address" class="w-full px-3 py-2 bg-white border border-green-200 rounded-lg text-left" dir="ltr">
                                <select name="transmitter_model_id" class="w-full px-3 py-2 bg-white border border-green-200 rounded-lg">
                                    <option value="">الموديل...</option>
                                    @foreach($deviceModels as $m)
                                    <option value="{{ $m->id }}">{{ $m->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Receiver -->
                         <div class="space-y-4">
                            <label class="text-sm font-bold text-gray-700">جهاز الاستقبال (Receiver)</label>
                            <input type="text" name="receiver_name" placeholder="اسم الجهاز" class="w-full px-3 py-2 bg-white border border-green-200 rounded-lg">
                            <input type="text" name="receiver_ip" placeholder="IP Address" class="w-full px-3 py-2 bg-white border border-green-200 rounded-lg text-left" dir="ltr">
                            <select name="receiver_model_id" class="w-full px-3 py-2 bg-white border border-green-200 rounded-lg">
                                <option value="">الموديل...</option>
                                @foreach($deviceModels as $m)
                                <option value="{{ $m->id }}">{{ $m->model_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="flex items-center justify-end gap-4 pt-4">
             <a href="{{ route('network.towers.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">
                إلغاء التغييرات
            </a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-200">
                💾 حفظ وإضافة البرج
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('towerForm', () => ({
            lat: '{{ old('lat') }}',
            lng: '{{ old('lng') }}',
            hasSolar: {{ old('has_solar') ? 'true' : 'false' }},
            hasGenerator: {{ old('has_generator') ? 'true' : 'false' }},
            hasGovElec: {{ old('has_government_electricity') ? 'true' : 'false' }},
            hasSubscription: {{ old('has_ampere_subscription') ? 'true' : 'false' }},
            serverId: '{{ old('mikrotik_server_id') }}',
            connectionType: '{{ old('connection_type') }}',
            transmitterType: 'existing',
            map: null,
            marker: null,

            init() {
                this.$nextTick(() => {
                    this.initMap();
                });
            },

            initMap() {
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
            }
        }));
    });
</script>
@endpush
@endsection
