@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 350px; width: 100%; border-radius: 0.75rem; z-index: 1; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-800">إضافة سيرفر MikroTik</h2>
        <p class="text-gray-500 mt-1">أضف سيرفر MikroTik رئيسي لإدارة المستخدمين</p>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-red-800 font-semibold">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-red-800 font-semibold mb-2">يرجى تصحيح الأخطاء التالية:</p>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('servers.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Model Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6" x-data="modelSelector({{ $products->map(fn($p) => ['id' => $p->id, 'name' => $p->model_name, 'image' => $p->image_url, 'price' => $p->max_throughput, 'description' => $p->description])->toJson() }}, {{ old('model_id') ?? 'null' }})">
            <h3 class="text-lg font-bold text-gray-800 mb-2">معلومات الجهاز</h3>
            <p class="text-gray-500 text-sm mb-6">اختر من القائمة أدناه أو ابحث عن جهازك إذا لم تجده ضمن القائمة</p>
            
            <!-- Device Search -->
            <div class="mb-8 relative">
                <label class="block text-gray-700 font-semibold mb-2">بحث عن جهاز</label>
                <div class="relative">
                    <input type="text" 
                           x-model="deviceQuery"
                           @input.debounce.300ms="searchDevices()"
                           @click.away="showDevices = false"
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="ادخل اسم الجهاز للبحث... (مثال: CCR1009)"
                           autocomplete="off">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Autocomplete Dropdown -->
                <div x-show="showDevices && devices.length > 0" 
                     x-cloak
                     class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-80 overflow-y-auto">
                    <template x-for="device in devices" :key="device.id">
                        <div @click="selectDevice(device)" 
                             class="flex items-center gap-3 p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0 transition">
                            <img :src="device.image_url" 
                                 class="w-12 h-12 object-contain bg-white rounded border border-gray-200 p-1"
                                 onerror="this.src='https://placehold.co/150x100?text=Device'">
                            <div>
                                <p class="font-bold text-gray-800 text-sm" x-text="device.manufacturer + ' ' + device.model_name" dir="ltr" style="text-align: left;"></p>
                                <p class="text-xs text-blue-600" x-text="device.device_type"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Quick Selection Grid -->
            <div x-show="!deviceQuery || devices.length === 0" class="mb-6">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 text-center">كتالوج منتجات MikroTik (اختر جهازاً)</p>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <template x-for="product in products" :key="product.id">
                        <div @click="selectProduct(product)" 
                             class="cursor-pointer border rounded-2xl p-6 transition-all duration-300 hover:shadow-xl text-center group bg-white relative overflow-hidden flex flex-col items-center justify-center"
                             :class="selectedModelId === product.id ? 'border-blue-500 ring-4 ring-blue-50 bg-blue-50' : 'border-gray-100 hover:border-blue-400'">
                            
                            <!-- Checkmark for selected -->
                            <div x-show="selectedModelId === product.id" class="absolute top-3 right-3 text-blue-600 z-10">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>

                            <div class="h-32 w-32 flex items-center justify-center mb-4 p-2 bg-white rounded-xl shadow-inner-sm">
                                <img :src="product.image_url" :alt="product.model_name" class="max-h-full max-w-full object-contain transition-transform duration-500 group-hover:scale-110" onerror="this.src='https://placehold.co/150x100?text=No+Image'">
                            </div>

                            <div class="space-y-1 w-full text-center">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2" x-text="product.model_name" dir="ltr"></h4>
                            </div>

                            <!-- Hover Tooltip/Overlay -->
                            <div class="absolute inset-0 bg-blue-600/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Selected Device Preview / Name Input -->
            <input type="hidden" name="model_id" :value="selectedModelId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">اسم السيرفر <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="serverName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-medium" placeholder="مثال: MikroTik CCR1009" required dir="ltr" style="text-align: left;">
                        <p class="text-[10px] text-gray-500 mt-2">يمكنك تعديل الاسم المقترح إذا رغبت.</p>
                    </div>
                </div>

                <!-- Preview Image -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50/30 rounded-2xl p-4 border border-blue-100 flex items-center gap-6 shadow-sm transition-all duration-300" x-show="previewImage" x-transition>
                    <div class="w-24 h-24 flex-shrink-0 bg-white rounded-xl border border-blue-200 p-2 shadow-inner">
                        <img :src="previewImage" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">الجهاز المختار</p>
                        <p class="text-sm font-bold text-gray-900 truncate" x-text="serverName.replace('MikroTik ', '')" dir="ltr"></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function modelSelector(initialProducts, initialModelId = null) {
            return {
                selectedModelId: initialModelId,
                serverName: '{{ old('name') }}',
                deviceQuery: '',
                previewImage: '',
                devices: [],
                showDevices: false,
                products: initialProducts || [],

                init() {
                    if (this.selectedModelId) {
                        const product = this.products.find(p => p.id == this.selectedModelId);
                        if (product) {
                            this.previewImage = product.image_url;
                        }
                    }
                },

                async searchDevices() {
                    if (this.deviceQuery.length < 2) {
                        this.devices = [];
                        this.showDevices = false;
                        return;
                    }
                    try {
                        const response = await fetch(`{{ route('api.devices.search') }}?q=${this.deviceQuery}`);
                        this.devices = await response.json();
                        this.showDevices = true;
                    } catch (e) {
                        console.error("Search failed", e);
                    }
                },

                selectDevice(device) {
                    this.serverName = 'MikroTik ' + device.model_name;
                    this.previewImage = device.image_url;
                    this.deviceQuery = device.model_name;
                    this.selectedModelId = device.id;
                    this.showDevices = false;
                },

                selectProduct(product) {
                    this.selectedModelId = product.id;
                    this.serverName = product.manufacturer + ' ' + product.model_name;
                    this.previewImage = product.image_url;
                    this.deviceQuery = '';
                }
            }
        }
        </script>

        <!-- Location -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">موقع السيرفر</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">مصدر الإنترنت الرئيسي</label>
                    <select name="internet_source_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- بدون مصدر --</option>
                        @foreach($internetSources ?? [] as $source)
                        <option value="{{ $source->id }}" {{ old('internet_source_id') == $source->id ? 'selected' : '' }}>
                            🌐 {{ $source->name }} ({{ $source->type }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">الموقع التفصيلي</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Data Center - Room 101">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">البرج المتواجد عليه (اختياري)</label>
                    <select name="location_tower_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- خارج برج (موقع مستقل) --</option>
                        @foreach($towers ?? [] as $tower)
                        <option value="{{ $tower->id }}" {{ old('location_tower_id') == $tower->id ? 'selected' : '' }}>
                            🗼 {{ $tower->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Map Integration -->
                <div class="md:col-span-2 space-y-4" x-data="serverMap()" x-init="init()">
                    <label class="block text-gray-700 font-semibold">تحديد الموقع على الخريطة</label>
                    <div id="map" class="border border-gray-200 shadow-sm"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">خط العرض (Lat)</label>
                            <input type="text" name="lat" x-model="lat" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-50" readonly>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">خط الطول (Lng)</label>
                            <input type="text" name="lng" x-model="lng" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-50" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function serverMap() {
            return {
                lat: '{{ old('lat') }}',
                lng: '{{ old('lng') }}',
                map: null,
                marker: null,
                init() {
                    setTimeout(() => {
                        this.map = L.map('map').setView([33.5138, 36.2765], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                        
                        // If old lat/lng exist, set initial marker
                        if (this.lat && this.lng) {
                            const initialLatLng = [parseFloat(this.lat), parseFloat(this.lng)];
                            this.marker = L.marker(initialLatLng).addTo(this.map);
                            this.map.setView(initialLatLng, 13);
                        }

                        this.map.on('click', (e) => {
                            this.lat = e.latlng.lat.toFixed(7);
                            this.lng = e.latlng.lng.toFixed(7);
                            if (this.marker) this.marker.setLatLng(e.latlng);
                            else this.marker = L.marker(e.latlng).addTo(this.map);
                        });
                    }, 100);
                }
            }
        }
        </script>
        <!-- Submit -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('servers.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
                💾 حفظ السيرفر
            </button>
        </div>
    </form>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-semibold text-blue-900 mb-1">ملاحظة</p>
                <p class="text-xs text-blue-800">بعد حفظ السيرفر، ستحصل على كود إعداد جاهز لنسخه ولصقه في Terminal السيرفر.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush
