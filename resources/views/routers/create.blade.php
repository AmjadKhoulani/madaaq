@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #locationMap { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 1; }
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { bg: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { bg: #c1c1c1; border-radius: 10px; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto" x-data="routerForm()">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إضافة جهاز جديد</h2>
            <p class="text-sm text-gray-500">أضف راوتر أو نقطة وصول أو محطة بث جديد</p>
        </div>
        <a href="{{ route('routers.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
            إلغاء
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
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

    <form action="{{ route('routers.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- RIGHT COLUMN: Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. Device Selection & Identity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        بيانات الجهاز
                    </h3>
                    
                    <!-- Search -->
                    <div class="mb-6 relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">موديل الجهاز</label>
                        <input type="text" x-model="deviceQuery" @input.debounce.300ms="searchDevices()" @click.away="showDevices = false"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none placeholder-gray-400" 
                               placeholder="ابحث... (مثال: NanoStation, hEX)">
                        <input type="hidden" name="model_id" :value="selectedDevice ? selectedDevice.id : ''">
                        
                        <!-- Dropdown -->
                        <div x-show="showDevices && devices.length > 0" x-cloak class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                            <template x-for="device in devices" :key="device.id">
                                <div @click="selectDevice(device)" class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0 gap-3">
                                    <img :src="device.image_url || 'https://via.placeholder.com/40'" class="w-10 h-10 object-contain p-1 border rounded bg-white">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900" x-text="device.model_name" dir="ltr"></p>
                                        <p class="text-xs text-gray-500" x-text="device.manufacturer"></p>
                                    </div>
                                    <span class="mr-auto text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600" x-text="device.device_type"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم الجهاز <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="routerName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none" required dir="ltr">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">نوع الجهاز <span class="text-red-500">*</span></label>
                            <select name="device_type" x-model="deviceType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none">
                                <option value="router">راوتر (Router)</option>
                                <option value="switch">سويتش (Switch)</option>
                                <option value="access_point">نقطة وصول (Access Point)</option>
                                <option value="base_station">محطة بث (Base Station)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Antenna Options -->
                    <div x-show="['access_point', 'base_station'].includes(deviceType)" x-transition class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <label class="block text-xs font-bold text-yellow-800 uppercase mb-2">نوع الأنتينا</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="sector" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Sector</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="omni" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Omni</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="antenna_type" value="dish" class="text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-700">Dish</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 2. Connection Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-1.015 1.09-2.132 1.335-3.354M10 9l5 5m0 0l-5 5m5-5H5"/></svg>
                        الاتصال والدخول
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">عنوان IP <span class="text-red-500">*</span></label>
                            <input type="text" name="ip" x-model="ip" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none font-mono" placeholder="192.168.88.1" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">منفذ API</label>
                            <input type="number" name="api_port" value="8728" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم المستخدم</label>
                            <input type="text" name="username" value="admin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">كلمة المرور</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none" required>
                        </div>
                    </div>
                </div>

                <!-- 3. Location (Collapsible Map) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button type="button" @click="showMap = !showMap" class="w-full p-4 flex items-center justify-between hover:bg-gray-50 transition">
                         <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            الموقع والتغطية
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="showMap ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div x-show="showMap" x-collapse>
                        <div class="p-5 pt-0">
                            <!-- Helper Options -->
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">نسخ إحداثيات برج</label>
                                <select @change="selectTower($event)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-red-500 outline-none text-sm">
                                    <option value="">-- اختر البرج --</option>
                                    @foreach($towers as $tower)
                                    <option value="{{ $tower->id }}" data-lat="{{ $tower->lat }}" data-lng="{{ $tower->lng }}">
                                        {{ $tower->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="locationMap" class="mb-4 bg-gray-100"></div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Latitude</label>
                                    <input type="text" name="lat" x-model="lat" class="w-full px-3 py-2 bg-gray-50 border rounded-lg text-xs" readonly>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Longitude</label>
                                    <input type="text" name="lng" x-model="lng" class="w-full px-3 py-2 bg-gray-50 border rounded-lg text-xs" readonly>
                                </div>
                            </div>
                            
                            <!-- Coverage Inputs -->
                            <div class="grid grid-cols-3 gap-4 border-t pt-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">المدى (متر)</label>
                                    <input type="number" name="coverage_radius" x-model="coverageRadius" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الاتجاه (0-360)</label>
                                    <input type="number" name="azimuth" x-model="azimuth" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">العرض (درجة)</label>
                                    <input type="number" name="beam_width" x-model="beamWidth" @input="updateCoverageCircle()" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- LEFT COLUMN: Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Preview Image -->
                        <div class="h-32 bg-gray-50 flex items-center justify-center p-4 border-b border-gray-100">
                             <img :src="selectedDevice ? selectedDevice.image_url : 'https://via.placeholder.com/150?text=Device'" 
                                  class="max-h-full max-w-full object-contain" :class="!selectedDevice ? 'opacity-30' : ''">
                        </div>
                        
                        <div class="p-5">
                            <div class="text-center mb-4">
                                <h3 class="font-bold text-gray-900 text-lg mb-1" x-text="routerName || 'جهاز جديد'"></h3>
                                <div class="flex items-center justify-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-xs font-mono bg-gray-100" x-text="ip || 'Pending IP'"></span>
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase text-white" 
                                          :class="{
                                              'bg-blue-500': deviceType === 'router',
                                              'bg-green-500': deviceType === 'access_point',
                                              'bg-teal-500': deviceType === 'switch',
                                              'bg-purple-500': deviceType === 'base_station'
                                          }" 
                                          x-text="deviceType"></span>
                                </div>
                            </div>
                            
                            <dl class="space-y-2 text-sm border-t border-gray-100 pt-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">الموديل</dt>
                                    <dd class="font-medium text-gray-900" x-text="selectedDevice ? selectedDevice.model_name : '---'"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">التردد</dt>
                                    <dd class="font-medium text-gray-900" x-text="selectedDevice ? selectedDevice.frequency : '---'"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">الإحداثيات</dt>
                                    <dd class="font-medium text-gray-900" x-text="lat ? 'تم التحديد' : '---'"></dd>
                                </div>
                            </dl>
                            
                            <button type="submit" class="w-full mt-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition transform active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                حفظ الجهاز
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('routerForm', () => ({
        routerName: '',
        deviceType: 'router',
        ip: '',
        
        lat: '',
        lng: '',
        coverageRadius: '',
        azimuth: '',
        beamWidth: '',
        
        showMap: true,
        map: null,
        marker: null,
        circle: null,
        
        deviceQuery: '',
        selectedDevice: null,
        devices: [],
        showDevices: false,

        async searchDevices() {
            if (this.deviceQuery.length < 2) { this.devices = []; return; }
            const res = await fetch(`{{ route('api.devices.search') }}?q=${this.deviceQuery}`);
            this.devices = await res.json();
            this.showDevices = true;
        },

        selectDevice(device) {
            this.selectedDevice = device;
            this.deviceQuery = device.model_name;
            this.deviceType = device.device_type;
            if (device.default_coverage_radius) this.coverageRadius = device.default_coverage_radius;
            this.showDevices = false;
            this.updateCoverageCircle();
        },

        selectTower(e) {
            const opt = e.target.options[e.target.selectedIndex];
            if (opt.dataset.lat) {
                this.lat = opt.dataset.lat;
                this.lng = opt.dataset.lng;
                this.updateMap();
            }
        },

        init() {
            this.initMap();
        },

        initMap() {
            this.map = L.map('locationMap').setView([24.7136, 46.6753], 6);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
            this.map.on('click', (e) => {
                this.lat = e.latlng.lat.toFixed(6);
                this.lng = e.latlng.lng.toFixed(6);
                this.updateMap();
            });
        },

        updateMap() {
            const latlng = [this.lat, this.lng];
            this.map.setView(latlng, 15);
            if(this.marker) this.marker.setLatLng(latlng);
            else this.marker = L.marker(latlng).addTo(this.map);
            this.updateCoverageCircle();
        },

        updateCoverageCircle() {
             if (this.circle) {
                this.map.removeLayer(this.circle);
                this.circle = null;
            }
            if (this.marker && this.coverageRadius > 0) {
                 const latlng = this.marker.getLatLng();
                 const color = this.deviceType === 'access_point' ? '#8b5cf6' : '#3b82f6';
                 
                 // Sector Logic
                 if (this.azimuth && this.beamWidth && this.beamWidth < 360) {
                    this.circle = this.drawSector(latlng, this.coverageRadius, this.azimuth, this.beamWidth, color);
                 } else {
                    this.circle = L.circle(latlng, { radius: this.coverageRadius, color: color }).addTo(this.map);
                 }
            }
        },

        drawSector(center, radius, azimuth, beamWidth, color) {
            // Sector drawing logic (omitted for brevity, same as before)
             const startAngle = parseFloat(azimuth) - (parseFloat(beamWidth) / 2);
            const endAngle = parseFloat(azimuth) + (parseFloat(beamWidth) / 2);
            const points = [center];
            for (let angle = startAngle; angle <= endAngle; angle += 2) {
                const rad = (angle * Math.PI) / 180;
                const latOffset = (radius / 111320) * Math.cos(rad);
                const lngOffset = (radius / (111320 * Math.cos(center.lat * Math.PI / 180))) * Math.sin(rad);
                points.push(L.latLng(center.lat + latOffset, center.lng + lngOffset));
            }
            points.push(center);
            return L.polygon(points, { color: color, fillColor: color, fillOpacity: 0.2 }).addTo(this.map);
        }
    }));
});
</script>
@endpush
@endsection
