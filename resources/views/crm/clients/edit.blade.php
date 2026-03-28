@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/css/intlTelInput.css">
<style>
    #map { height: 300px; width: 100%; border-radius: 0.5rem; z-index: 1; }
    [x-cloak] { display: none !important; }
    .iti { width: 100%; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto" 
     x-data="clientEditForm({ 
        client: {{ Js::from($client) }},
        servers: {{ Js::from($servers) }},
        deviceModels: {{ Js::from($deviceModels) }} 
     })"
     x-cloak>
     
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل بيانات المشترك</h2>
            <p class="text-sm text-gray-500">تحديث المعلومات والاشتراك: <span class="font-mono font-bold" x-text="client.name || client.username"></span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('crm.clients.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
                إلغاء
            </a>
            @if($client->status === 'active')
                <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إيقاف هذا المشترك؟')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 font-medium rounded-lg hover:bg-red-100 transition text-sm">
                        إيقاف الحساب
                    </button>
                </form>
            @else
                <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-green-50 text-green-700 border border-green-200 font-medium rounded-lg hover:bg-green-100 transition text-sm">
                        تفعيل الحساب
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">يوجد أخطاء في البيانات:</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('crm.clients.update', $client) }}" method="POST" id="clientForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- LEFT COLUMN: Main Form -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. Subscriber Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        بيانات المشترك
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الاسم الكامل</label>
                            <input type="text" name="name" x-model="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" required>
                        </div>

                        <!-- Phone -->
                        <div x-data="{ 
                            iti: null, 
                            initPhone() {
                                if (window.intlTelInput) {
                                    const input = this.$refs.phoneInput;
                                    this.iti = window.intlTelInput(input, {
                                        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/js/utils.js',
                                        initialCountry: 'auto',
                                        geoIpLookup: function(callback) {
                                            fetch('https://ipapi.co/json')
                                            .then(res => res.json())
                                            .then(data => callback(data.country_code))
                                            .catch(() => callback('SY'));
                                        },
                                        separateDialCode: true,
                                        preferredCountries: ['sy', 'sa', 'tr']
                                    });
                                    if(this.phone) this.iti.setNumber(this.phone);
                                    
                                    input.addEventListener('input', () => {
                                        document.getElementById('hiddenPhone').value = this.iti.getNumber();
                                    });
                                }
                            }
                        }" x-init="initPhone()">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">رقم الهاتف</label>
                            <div class="relative" dir="ltr">
                                <input type="tel" x-ref="phoneInput" x-model="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" required>
                                <input type="hidden" name="phone" id="hiddenPhone" :value="phone">
                            </div>
                        </div>

                        <!-- Portal Password -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">كلمة مرور البوابة (تحديث)</label>
                            <input type="text" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="اترك فارغاً لعدم التغيير">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $client->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- 2. Service Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        تفاصيل الخدمة
                        <span class="mr-auto text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded" x-text="connectionType.toUpperCase()"></span>
                    </h3>
                    
                    <input type="hidden" name="type" :value="connectionType">

                    <div class="space-y-4">
                        <!-- Infrastructure Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-show="connectionType === 'pppoe'">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">سيرفر التحكم</label>
                                <select name="mikrotik_server_id" x-model="selectedServerId" @change="updateTowers()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm">
                                    <option value="">اختر السيرفر...</option>
                                    <template x-for="server in servers" :key="server.id">
                                        <option :value="server.id" x-text="server.name" :selected="server.id == selectedServerId"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">البرج</label>
                                <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm" :disabled="!selectedServerId">
                                    <option value="">اختر البرج...</option>
                                    <template x-for="tower in towers" :key="tower.id">
                                        <option :value="tower.id" x-text="tower.name" :selected="tower.id == selectedTowerId"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الشبكة (SSID)</label>
                                <select name="ssid_id" x-model="selectedSSID" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm" :disabled="!selectedTowerId">
                                    <option value="">اختر الشبكة...</option>
                                    <template x-for="ssid in ssids" :key="ssid.id">
                                        <option :value="ssid.id" x-text="ssid.ssid_name" :selected="ssid.id == selectedSSID"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Credentials Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                             <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم المستخدم (للاتصال)</label>
                                <input type="text" 
                                       :name="connectionType === 'pppoe' ? 'pppoe_username' : 'hotspot_username'" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 font-mono text-sm cursor-not-allowed" 
                                       readonly
                                       :value="username">
                                <p class="text-[10px] text-gray-400 mt-1">يُستخدم رقم الهاتف كاسم مستخدم</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">كلمة مرور الخدمة (تحديث)</label>
                                <input type="text" name="service_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none bg-blue-50 font-mono text-sm" 
                                       placeholder="اترك فارغاً لعدم التغيير" 
                                       value="{{ old('service_password') }}"> 
                                <p class="text-[10px] text-gray-400 mt-1">كلمة المرور المسجلة: <span class="font-mono text-gray-600">{{ $client->service_password }}</span></p>
                            </div>
                        </div>
                        
                        <!-- Hardware Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2 border-t border-gray-100" x-show="connectionType === 'pppoe'">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">جهاز العميل (CPE)</label>
                                <input type="text" name="cpe_model" value="{{ old('cpe_model', $client->cpe_model) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm placeholder-gray-400" placeholder="NanoStation 5">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">عنوان IP (Static)</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address', $client->ip) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm placeholder-gray-400 font-mono" placeholder="10.x.x.x">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">MAC Address</label>
                                <input type="text" name="cpe_mac" value="{{ old('cpe_mac', $client->cpe_mac) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm placeholder-gray-400 font-mono" placeholder="AA:BB:CC:DD:EE:FF">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Billing & Package -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        الباقة والمالية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الباقة الحالية</label>
                             <select name="package_id" x-model="selectedPackageId" @change="loadPackageDefaults()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                                <option value="">مخصص (بدون باقة)</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            data-price="{{ $package->price }}" 
                                            data-limit="{{ $package->data_limit_mb }}">
                                        {{ $package->name }} - {{ $package->price }}$ ({{ $package->speed_down }}M/{{ $package->speed_up }}M)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">السعر (شهري)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="price" x-model="customPrice" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none font-mono" placeholder="0.00">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                            </div>
                        </div>

                        <div>
                             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">حد البيانات (GB)</label>
                             <input type="number" name="data_limit" x-model="customDataLimit" placeholder="Unlimited" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- 4. Location (Collapsible) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button type="button" @click="showMap = !showMap" class="w-full p-4 flex items-center justify-between hover:bg-gray-50 transition">
                         <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            الموقع الجغرافي
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="showMap ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div x-show="showMap" x-collapse>
                        <div class="p-5 pt-0">
                            <div id="map" class="mb-4"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="lat" x-model="lat" placeholder="Latitude" class="px-3 py-2 border rounded-lg bg-gray-50 text-xs" readonly>
                                <input type="text" name="lng" x-model="lng" placeholder="Longitude" class="px-3 py-2 border rounded-lg bg-gray-50 text-xs" readonly>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-4">
                    <!-- Summary Card -->
                    <div class="bg-gradient-to-br from-indigo-900 to-blue-900 rounded-xl shadow-lg p-6 text-white text-center">
                        <div class="flex justify-between items-center mb-4 border-b border-white/10 pb-4">
                            <span class="text-indigo-200 text-sm">الحالة</span>
                            @if($client->status === 'active')
                                <span class="bg-green-500/20 text-green-300 px-2 py-1 rounded text-xs border border-green-500/30">نشط (Active)</span>
                            @else
                                <span class="bg-red-500/20 text-red-300 px-2 py-1 rounded text-xs border border-red-500/30 font-bold">{{ strtoupper($client->status) }}</span>
                            @endif
                        </div>
                        
                        <div class="text-indigo-200 text-sm font-medium mb-1">الاشتراك الشهري</div>
                        <div class="text-4xl font-bold font-mono tracking-tight flex items-center justify-center gap-1">
                            <span x-text="customPrice || '0'"></span>
                            <span class="text-2xl">$</span>
                        </div>
                        <div class="mt-4 pt-4 text-sm space-y-2 text-left">
                            <div class="flex justify-between">
                                <span class="text-indigo-200">النوع:</span>
                                <span class="font-bold" x-text="connectionType.toUpperCase()"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-indigo-200">ينتهي في:</span>
                                <span class="font-bold text-yellow-300">{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'غير محدد' }}</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full mt-6 py-3 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition transform active:scale-95">
                            💾 حفظ التعديلات
                        </button>
                    </div>

                    <!-- Quick Warnings -->
                    @if($client->status == 'suspended')
                    <div class="bg-red-50 rounded-xl border border-red-200 p-4">
                        <h4 class="font-bold text-red-800 text-sm mb-2">تنبيه هام</h4>
                        <p class="text-xs text-red-700">هذا الحساب موقوف حالياً. للتفعيل، اضغط على زر "تفعيل الحساب" في الأعلى.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('clientEditForm', (data) => ({
            client: data.client,
            name: data.client.name || data.client.username,
            phone: data.client.phone || data.client.username,
            username: data.client.username,
            connectionType: data.client.type,
            
            servers: data.servers,
            selectedServerId: data.client.mikrotik_server_id,
            towers: [],
            selectedTowerId: data.client.tower_id,
            ssids: [],
            selectedSSID: data.client.ssid_id,
            
            selectedPackageId: data.client.package_id,
            customPrice: data.client.custom_price || (data.client.package ? data.client.package.price : ''),
            customDataLimit: data.client.custom_data_limit_mb ? (data.client.custom_data_limit_mb / 1024) : 
                             (data.client.package ? (data.client.package.data_limit_mb / 1024) : ''),
            
            // Map
            showMap: false,
            map: null,
            marker: null,
            lat: data.client.lat || '',
            lng: data.client.lng || '',

            init() {
                this.updateTowers();
                this.updateTowerData();
                
                this.$watch('showMap', (value) => {
                    if (value && !this.map) {
                        setTimeout(() => this.initMap(), 100);
                    }
                });
            },

            updateTowers() {
                // Keep selection if previously set and valid, else reset
                const oldTower = this.selectedTowerId;
                this.ssids = [];
                const server = this.servers.find(s => s.id == this.selectedServerId);
                this.towers = server ? (server.all_towers || server.towers) : [];
                
                // If the currently selected tower isn't in the new list, clear it
                if (!this.towers.find(t => t.id == oldTower)) {
                    this.selectedTowerId = '';
                }
            },
            updateTowerData() {
                const tower = this.towers.find(t => t.id == this.selectedTowerId);
                this.ssids = tower ? tower.ssids : [];
                // Center Map if changed manually? Maybe not for Edit.
            },
            loadPackageDefaults() {
                const select = document.querySelector('select[name="package_id"]');
                const option = select.options[select.selectedIndex];
                if (option.dataset.price) {
                    this.customPrice = option.dataset.price;
                }
                if (option.dataset.limit) {
                    this.customDataLimit = (option.dataset.limit / 1024).toFixed(0);
                }
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
