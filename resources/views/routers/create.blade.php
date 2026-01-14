@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="routerForm()">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-800">إضافة جهاز جديد</h2>
        <p class="text-gray-500 mt-1">أضف راوتر أو نقطة وصول أو محطة بث إلى شبكتك</p>
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

    <form action="{{ route('routers.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Device Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">اختيار الجهاز</h3>
            
            <!-- Device Search Autocomplete -->
            <div class="mb-4 relative">
                <label class="block text-gray-700 font-semibold mb-2">الجهاز *</label>
                <input type="text" 
                       x-model="deviceQuery"
                       @input.debounce.300ms="searchDevices()"
                       @click.away="showDevices = false"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                       placeholder="ابحث عن الجهاز... (مثال: NanoStation, hEX, PowerBeam, Mimosa)"
                       autocomplete="off">
                
                <!-- Hidden input for device_model_id -->
                <input type="hidden" name="model_id" :value="selectedDevice ? selectedDevice.id : ''">
                
                <!-- Autocomplete Dropdown -->
                <div x-show="showDevices && devices.length > 0" 
                     x-cloak
                     class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-96 overflow-y-auto">
                    <template x-for="device in devices" :key="device.id">
                        <div @click="selectDevice(device)" 
                             class="flex items-center gap-4 p-4 hover:bg-indigo-50 cursor-pointer transition border-b border-gray-100 last:border-0">
                            <img :src="device.image_url" 
                                 :alt="device.model_name"
                                 class="w-16 h-16 object-contain rounded border border-gray-200 bg-white p-1"
                                 onerror="this.src='https://via.placeholder.com/64?text=Device'">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900" x-text="device.manufacturer + ' ' + device.model_name"></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs px-2 py-1 rounded-full" 
                                          :class="device.device_type === 'router' ? 'bg-blue-100 text-blue-700' : device.device_type === 'access_point' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700'"
                                          x-text="device.device_type === 'router' ? '📡 راوتر' : device.device_type === 'access_point' ? '📶 نقطة وصول' : '🗼 محطة بث'"></span>
                                    <span class="text-xs text-gray-500" x-text="device.frequency"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Selected Device Preview -->
                <div x-show="selectedDevice" 
                     x-cloak
                     class="mt-3 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-xl flex items-center gap-4">
                    <img :src="selectedDevice?.image_url"
                         :alt="selectedDevice?.model_name"
                         class="w-20 h-20 object-contain rounded-lg border-2 border-indigo-300 bg-white p-2 shadow-sm"
                         onerror="this.src='https://via.placeholder.com/80?text=Device'">
                    <div class="flex-1">
                        <p class="font-bold text-indigo-900 text-lg" x-text="selectedDevice?.manufacturer"></p>
                        <p class="font-semibold text-gray-700" x-text="selectedDevice?.model_name"></p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-700" 
                                  x-text="selectedDevice?.device_type === 'router' ? '📡 راوتر' : selectedDevice?.device_type === 'access_point' ? '📶 نقطة وصول' : '🗼 محطة بث'"></span>
                            <span class="text-xs text-gray-600" x-text="selectedDevice?.frequency"></span>
                        </div>
                    </div>
                    <button type="button" @click="selectedDevice = null; deviceQuery = ''" class="text-red-500 hover:text-red-700 p-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">اسم الجهاز <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="routerName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">نوع الجهاز <span class="text-red-500">*</span></label>
                    <select name="device_type" x-model="deviceType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                        <option value="router">راوتر</option>
                        <option value="access_point">نقطة وصول - Access Point</option>
                        <option value="base_station">محطة بث - Base Station</option>
                        <option value="server">سيرفر - Server</option>
                    </select>
                </div>



                <div>
                    <label class="block text-gray-700 font-semibold mb-2">البرج (اختياري)</label>
                    <select name="tower_id" 
                            @change="selectTower($event)" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- بدون برج --</option>
                        @if(isset($towers))
                            @foreach($towers as $tower)
                            <option value="{{ $tower->id }}" 
                                    data-lat="{{ $tower->lat }}" 
                                    data-lng="{{ $tower->lng }}">
                                🗼 {{ $tower->name }} - {{ $tower->location }}
                            </option>
                            @endforeach
                        @endif
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        💡 اختر البرج لملء الإحداثيات تلقائياً
                    </p>
                </div>
            </div>
        </div>

        <!-- Location Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">الموقع ومدى التغطية</h3>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">حدد الموقع على الخريطة</label>
                <div id="locationMap" class="h-96 rounded-lg border border-gray-300 mb-2"></div>
                <p class="text-xs text-gray-500">انقر على الخريطة لتحديد موقع الجهاز</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">خط العرض (Latitude)</label>
                    <input type="number" step="0.00000001" name="lat" x-model="lat" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg" readonly>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">خط الطول (Longitude)</label>
                    <input type="number" step="0.00000001" name="lng" x-model="lng" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg" readonly>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">مدى التغطية (متر)</label>
                    <input type="number" name="coverage_radius" x-model="coverageRadius" @input="updateCoverageCircle()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">اتجاه الشعاع (درجة)</label>
                    <input type="number" min="0" max="360" name="azimuth" x-model="azimuth" @input="updateCoverageCircle()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="0-360">
                    <p class="text-xs text-gray-500 mt-1">0° = شمال، 90° = شرق، 180° = جنوب، 270° = غرب</p>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">عرض الشعاع (درجة)</label>
                    <input type="number" min="1" max="360" name="beam_width" x-model="beamWidth" @input="updateCoverageCircle()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="مثال: 60">
                    <p class="text-xs text-gray-500 mt-1">اتركه فارغاً للتغطية الدائرية الكاملة (360°)</p>
                </div>
            </div>
        </div>

        <!-- Settings & Price -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">إعدادات الاتصال والسعر</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">سعر الجهاز (تكلفة)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                        <input type="number" step="0.01" name="price" class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">عنوان IP <span class="text-red-500">*</span></label>
                    <input type="text" name="ip" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                </div>



                <div>
                    <label class="block text-gray-700 font-semibold mb-2">اسم المستخدم</label>
                    <input type="text" name="username" value="admin" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                </div>

                <div x-show="deviceType === 'server'">
                    <label class="block text-gray-700 font-semibold mb-2">مصدر الإنترنت (Uplink)</label>
                    <select name="internet_source_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- اختر مصدر الإنترنت --</option>
                        @if(isset($internetSources))
                            @foreach($internetSources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }} ({{ $source->capacity }} Mbps)</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">كلمة المرور</label>
                    <input type="text" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required value="{{ Str::random(12) }}">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('routers.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition">
                حفظ الجهاز
            </button>
        </div>
    </form>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
function routerForm() {
    return {
        routerName: '',
        deviceType: 'router',
        lat: null,
        lng: null,
        coverageRadius: '',
        azimuth: '',
        beamWidth: '',
        map: null,
        marker: null,
        coverageCircle: null,
        deviceQuery: '',
        selectedDevice: null,
        devices: [],
        showDevices: false,
        
        async searchDevices() {
            if (this.deviceQuery.length < 2) {
                this.devices = [];
                this.showDevices = false;
                return;
            }
            
            const response = await fetch(`{{ route('api.devices.search') }}?q=${this.deviceQuery}`);
            this.devices = await response.json();
            this.showDevices = true;
        },
        
        selectDevice(device) {
            this.selectedDevice = device;
            this.deviceQuery = device.manufacturer + ' ' + device.model_name;
            this.deviceType = device.device_type;
            this.coverageRadius = device.default_coverage_radius;
            this.updateCoverageCircle();
            this.showDevices = false;
        },
        
        selectTower(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const towerLat = selectedOption.getAttribute('data-lat');
            const towerLng = selectedOption.getAttribute('data-lng');
            
            if (towerLat && towerLng) {
                this.lat = parseFloat(towerLat);
                this.lng = parseFloat(towerLng);
                
                // Update map center and marker
                if (this.map) {
                    this.map.setView([this.lat, this.lng], 15);
                    
                    if (this.marker) {
                        this.marker.setLatLng([this.lat, this.lng]);
                    } else {
                        this.marker = L.marker([this.lat, this.lng], {
                            draggable: true,
                            icon: L.icon({
                                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41]
                            })
                        }).addTo(this.map);
                        
                        this.marker.on('dragend', (e) => {
                            const position = e.target.getLatLng();
                            this.lat = position.lat;
                            this.lng = position.lng;
                        });
                    }
                    
                    this.updateCoverageCircle();
                }
            }
        },
        
        init() {
            // Initialize map
            this.map = L.map('locationMap').setView([24.7136, 46.6753], 6);
            
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(this.map);

            // Click to set location
            this.map.on('click', (e) => {
                this.lat = e.latlng.lat.toFixed(8);
                this.lng = e.latlng.lng.toFixed(8);
                
                // Update marker
                if (this.marker) {
                    this.marker.setLatLng(e.latlng);
                } else {
                    this.marker = L.marker(e.latlng).addTo(this.map);
                }

                this.updateCoverageCircle();
            });
        },

        updateFromModel() {
            const select = document.querySelector('select[name="model_id"]');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                this.deviceType = option.dataset.deviceType || 'router';
                this.coverageRadius = option.dataset.coverage || '';
                this.updateCoverageCircle();
            }
        },

        updateCoverageCircle() {
            if (this.circle) {
                this.map.removeLayer(this.circle);
                this.circle = null;
            }

            if (this.marker && this.coverageRadius > 0) {
                const latlng = this.marker.getLatLng();
                let color = '#3b82f6';
                if (this.deviceType === 'access_point') color = '#8b5cf6';
                if (this.deviceType === 'base_station') color = '#ec4899';
                if (this.deviceType === 'server') color = '#6b7280';
                
                // Draw sector if azimuth and beam width are specified
                if (this.azimuth !== '' && this.beamWidth !== '' && this.beamWidth > 0 && this.beamWidth < 360) {
                    this.circle = this.drawSector(latlng, parseFloat(this.coverageRadius), parseFloat(this.azimuth), parseFloat(this.beamWidth), color);
                } else {
                    // Draw full circle
                    this.circle = L.circle(latlng, {
                        radius: parseFloat(this.coverageRadius),
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.15,
                        weight: 2
                    }).addTo(this.map);
                }
            }
        },

        drawSector(center, radius, azimuth, beamWidth, color) {
            const startAngle = azimuth - (beamWidth / 2);
            const endAngle = azimuth + (beamWidth / 2);
            
            // Create sector polygon
            const points = [center];
            for (let angle = startAngle; angle <= endAngle; angle += 1) {
                const rad = (angle * Math.PI) / 180;
                const latOffset = (radius / 111320) * Math.cos(rad);
                const lngOffset = (radius / (111320 * Math.cos(center.lat * Math.PI / 180))) * Math.sin(rad);
                points.push(L.latLng(center.lat + latOffset, center.lng + lngOffset));
            }
            points.push(center);
            
            return L.polygon(points, {
                color: color,
                fillColor: color,
                fillOpacity: 0.2,
                weight: 2,
                opacity: 0.6
            }).addTo(this.map);
        }
    }
}
</script>
@endsection
