@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 350px; width: 100%; border-radius: 0.75rem; z-index: 1; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تعديل السيرفر: {{ $server->name }}</h1>
            <p class="text-gray-500 mt-1">تحديث بيانات السيرفر وإعدادات الاتصال</p>
        </div>
        <a href="{{ route('servers.show', $server) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            عودة للتفاصيل
        </a>
    </div>

    <form action="{{ route('servers.update', $server) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="glass p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                المعلومات الأساسية
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم السيرفر <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $server->name) }}" required 
                           class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>

                <!-- Location Text -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الموقع (نصي)</label>
                    <input type="text" name="location" value="{{ old('location', $server->location) }}" 
                           class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="مثال: الغرفة الفنية - الطابق الثاني">
                </div>

                <!-- Location Tower -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البرج المتواجد عليه (اختياري)</label>
                    <select name="location_tower_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">-- خارج برج (موقع مستقل) --</option>
                        @foreach($towers ?? [] as $tower)
                            <option value="{{ $tower->id }}" {{ old('location_tower_id', $server->location_tower_id) == $tower->id ? 'selected' : '' }}>
                                🗼 {{ $tower->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Internet Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">مصدر الإنترنت الرئيسي</label>
                    <select name="internet_source_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">-- بدون مصدر --</option>
                        @foreach($internetSources ?? [] as $source)
                            <option value="{{ $source->id }}" {{ old('internet_source_id', $server->internet_source_id) == $source->id ? 'selected' : '' }}>
                                {{ $source->name }} ({{ $source->type }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Map Integration -->
                <div class="md:col-span-2 space-y-2" x-data="serverMap()" x-init="init()">
                    <label class="block text-sm font-medium text-gray-700">تحديد الموقع على الخريطة</label>
                    <div id="map" class="border border-gray-200 shadow-sm rounded-xl"></div>
                    <div class="grid grid-cols-2 gap-4 mt-2">
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

                <script>
                function serverMap() {
                    return {
                        lat: '{{ old('lat', $server->lat) }}',
                        lng: '{{ old('lng', $server->lng) }}',
                        map: null,
                        marker: null,
                        init() {
                            setTimeout(() => {
                                const startLat = this.lat || 33.5138;
                                const startLng = this.lng || 36.2765;
                                this.map = L.map('map').setView([startLat, startLng], 13);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                                
                                if(this.lat && this.lng) {
                                    this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
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
            </div>
        </div>

        <!-- Connection Details (Uplink) -->
        <div class="glass p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{ uplinkType: '{{ old('uplink_type', $server->uplink_type) }}' }">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                تفاصيل الربط (Uplink)
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Uplink Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الاتصال</label>
                    <select name="uplink_type" x-model="uplinkType" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">-- غير محدد --</option>
                        <option value="fiber">ألياف ضوئية (Fiber)</option>
                        <option value="ethernet">كبل شبكة (Ethernet)</option>
                        <option value="wireless">لاسلكي (Wireless)</option>
                        <option value="microwave">مايكروويف (Microwave)</option>
                    </select>
                </div>

                <!-- Interface -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">منفذ الاتصال (Interface)</label>
                    <input type="text" name="uplink_interface" value="{{ old('uplink_interface', $server->uplink_interface) }}" 
                           class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="مثال: ether1, sfp1">
                </div>

                <!-- Wireless Devices (Conditional) -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl" 
                     x-show="uplinkType === 'wireless' || uplinkType === 'microwave'" 
                     x-transition>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الجهاز المرسل (Source)</label>
                        <select name="uplink_sending_device_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- اختر الجهاز --</option>
                            @foreach($routers as $router)
                                <option value="{{ $router->id }}" {{ old('uplink_sending_device_id', $server->uplink_sending_device_id) == $router->id ? 'selected' : '' }}>
                                    {{ $router->name }} ({{ $router->host }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">الجهاز لدى الطرف الآخر (المزود/الرئيسي)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الجهاز المستقبل (Destination)</label>
                        <select name="uplink_receiving_device_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- اختر الجهاز --</option>
                            @foreach($routers as $router)
                                <option value="{{ $router->id }}" {{ old('uplink_receiving_device_id', $server->uplink_receiving_device_id) == $router->id ? 'selected' : '' }}>
                                    {{ $router->name }} ({{ $router->host }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">الجهاز عند هذا السيرفر</p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات الربط</label>
                    <textarea name="uplink_notes" rows="2" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ old('uplink_notes', $server->uplink_notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- MikroTik Credentials -->
        <div class="glass p-6 rounded-2xl shadow-sm border border-gray-100">
             <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                بيانات الاتصال (MikroTik API)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">IP Address / Host</label>
                    <input type="text" name="ip" value="{{ old('ip', $server->ip) }}" 
                           class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                 <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">API Port</label>
                    <input type="number" name="api_port" value="{{ old('api_port', $server->api_port) }}" 
                           class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                 <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $server->username) }}" 
                           class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                 <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">Password (اتركه فارغاً للإبقاء على القديم)</label>
                    <input type="password" name="password" autocomplete="new-password" 
                           class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('servers.show', $server) }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold rounded-xl shadow-lg hover:from-indigo-700 hover:to-blue-700 transition transform hover:scale-105">
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@endsection
