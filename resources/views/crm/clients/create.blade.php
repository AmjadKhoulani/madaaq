@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/css/intlTelInput.css">
<style>
    #map { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 1; }
    [x-cloak] { display: none !important; }
    
    /* Wizard Steps Animation */
    .step-content {
        transition: all 0.3s ease-in-out;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto" 
     x-data="clientWizard({ 
        servers: {{ Js::from($servers) }}, 
        deviceModels: {{ Js::from($deviceModels) }} 
     })"
     x-cloak>
     
    <!-- Header & Progress -->
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">إضافة مشترك جديد</h2>
        <p class="mt-2 text-lg text-gray-500">معالج إنشاء الاشتراك (خطوة <span x-text="step"></span> من 4)</p>
        
        <!-- Progress Bar -->
        <div class="mt-6 relative">
            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                <div :style="'width: ' + ((step / 4) * 100) + '%'" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600 transition-all duration-500"></div>
            </div>
            <div class="flex justify-between text-xs font-bold text-gray-400">
                <span :class="{'text-blue-600': step >= 1}">1. تفاصيل الخدمة</span>
                <span :class="{'text-blue-600': step >= 2}">2. الباقة والمالية</span>
                <span :class="{'text-blue-600': step >= 3}">3. بيانات المشترك</span>
                <span :class="{'text-blue-600': step >= 4}">4. الموقع والتأكيد</span>
            </div>
        </div>
    </div>

    <!-- Wizard Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 relative">
        <!-- Validation Errors -->
        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 m-6 mb-0 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">يرجى التحقق من الأخطاء أدناه.</p>
                    <ul class="list-disc list-inside text-xs text-red-600 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('crm.clients.store') }}" method="POST" id="clientForm" class="p-8">
            @csrf

            <!-- STEP 1: Service Details -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm">1</span>
                    تفاصيل الخدمة والاتصال
                </h3>
                
                <div class="space-y-6">
                    <!-- Connection Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">نوع الاشتراك</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-gray-50 transition" :class="{'ring-2 ring-blue-500 border-blue-500 bg-blue-50': connectionType === 'pppoe'}">
                                <input type="radio" name="type" value="pppoe" class="sr-only" x-model="connectionType">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900">برودباند (PPPoE)</span>
                                        <span class="mt-1 text-xs text-gray-500">منزلي / تجاري ثابت</span>
                                    </span>
                                </span>
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-gray-50 transition" :class="{'ring-2 ring-purple-500 border-purple-500 bg-purple-50': connectionType === 'hotspot'}">
                                <input type="radio" name="type" value="hotspot" class="sr-only" x-model="connectionType">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900">هوت سبوت (Hotspot)</span>
                                        <span class="mt-1 text-xs text-gray-500">كروت / شبكات عامة</span>
                                    </span>
                                </span>
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            </label>
                        </div>
                    </div>

                    <!-- Server Selection -->
                    <div x-show="connectionType === 'pppoe'" x-transition>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">سيرفر التحكم (Router)</label>
                        <select name="mikrotik_server_id" x-model="selectedServerId" @change="updateTowers()" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">اختر السيرفر...</option>
                            <template x-for="server in servers" :key="server.id">
                                <option :value="server.id" x-text="server.name + ' (' + server.ip + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Tower & SSID Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="connectionType === 'pppoe'" x-transition>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">البرج (Tower)</label>
                            <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" :disabled="!selectedServerId">
                                <option value="">اختر البرج...</option>
                                <template x-for="tower in towers" :key="tower.id">
                                    <option :value="tower.id" x-text="tower.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">الشبكة (SSID)</label>
                            <select name="ssid" x-model="selectedSSID" @change="autoMapDevice()" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" :disabled="!selectedTowerId">
                                <option value="">اختر الشبكة...</option>
                                <template x-for="ssid in ssids" :key="ssid.id">
                                    <option :value="ssid.ssid_name" x-text="ssid.ssid_name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Package & Billing -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm">2</span>
                    الباقة والتسعير
                </h3>

                <div class="space-y-6">
                    <!-- Package Select -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">اختر الباقة</label>
                        <select name="package_id" x-model="selectedPackageId" @change="loadPackageDefaults()" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">بدون باقة (تخصيص يدوي)</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" 
                                        data-price="{{ $package->price }}" 
                                        data-speed="{{ $package->speed_down }}/{{ $package->speed_up }}"
                                        data-limit="{{ $package->data_limit_mb }}">
                                    {{ $package->name }} - {{ $package->price }}$ ({{ $package->speed_down }}M)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Pricing Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 p-6 rounded-xl border border-gray-200">
                         <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">السعر (شهري)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="price" x-model="customPrice" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none" placeholder="0.00">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">مدة الاشتراك (يوم)</label>
                             <input type="number" name="duration_days" value="30" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">حد الاستخدام (GB)</label>
                             <input type="number" name="data_limit" placeholder="Unlimited" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Subscriber Info -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm">3</span>
                    بيانات المشترك
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل</label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required placeholder="مثال: محمد أحمد">
                    </div>

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
                                input.addEventListener('input', () => {
                                    document.getElementById('hiddenPhone').value = this.iti.getNumber();
                                });
                            }
                        }
                    }" x-init="initPhone()">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف</label>
                        <div class="relative" dir="ltr">
                            <input type="tel" x-ref="phoneInput" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <input type="hidden" name="phone" id="hiddenPhone">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة مرور البوابة</label>
                        <input type="text" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required placeholder="********">
                    </div>

                    <div class="md:col-span-2 pt-4 border-t border-gray-100">
                        <h4 class="font-bold text-gray-900 mb-4">بيانات الخدمة (PPPoE/Hotspot)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم (Username)</label>
                                <input type="text" 
                                       :name="connectionType === 'pppoe' ? 'pppoe_username' : 'hotspot_username'" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-indigo-50 font-mono" 
                                       required placeholder="user_login">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة مرور الخدمة</label>
                                <input type="text" name="service_password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-indigo-50 font-mono" required placeholder="service_pass">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: Location & Review -->
            <div x-show="step === 4" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm">4</span>
                    الموقع الجغرافي والتأكيد
                </h3>

                <div class="space-y-6">
                    <div>
                        <div id="map" class="w-full h-64 bg-gray-100 rounded-xl border border-gray-300"></div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <input type="text" name="lat" x-model="lat" placeholder="Latitude" class="px-4 py-2 border rounded-lg bg-gray-50 text-xs" readonly>
                            <input type="text" name="lng" x-model="lng" placeholder="Longitude" class="px-4 py-2 border rounded-lg bg-gray-50 text-xs" readonly>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <h4 class="font-bold text-yellow-800 mb-2">ملخص سريع:</h4>
                        <ul class="text-sm text-yellow-700 list-disc list-inside space-y-1">
                            <li>النوع: <span x-text="connectionType.toUpperCase()" class="font-bold"></span></li>
                            <li>الباقة: <span x-text="selectedPackageId ? 'باقة محددة' : 'مخصص'" class="font-bold"></span></li>
                            <li>السعر: <span x-text="customPrice + '$'" class="font-bold"></span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                <button type="button" @click="prevStep()" x-show="step > 1" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                    السابق
                </button>
                <div class="flex-1"></div> <!-- Spacer -->
                
                <button type="button" @click="nextStep()" x-show="step < 4" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg hover:shadow-xl transition transform active:scale-95">
                    التالي
                </button>

                <button type="submit" x-show="step === 4" class="px-8 py-3 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 shadow-lg hover:shadow-xl transition transform active:scale-95">
                    ✅ إنشاء الاشتراك
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('clientWizard', (data) => ({
            step: 1,
            connectionType: 'pppoe',
            servers: data.servers,
            selectedServerId: '',
            towers: [],
            selectedTowerId: '',
            ssids: [],
            selectedSSID: '',
            selectedPackageId: '',
            customPrice: '',
            
            // Map
            map: null,
            marker: null,
            lat: '',
            lng: '',

            nextStep() {
                if (this.step < 4) {
                    this.step++;
                    if (this.step === 4) {
                        setTimeout(() => this.initMap(), 300); // Init map when visible
                    }
                }
            },
            prevStep() {
                if (this.step > 1) this.step--;
            },

            updateTowers() {
                this.selectedTowerId = '';
                this.ssids = [];
                const server = this.servers.find(s => s.id == this.selectedServerId);
                this.towers = server ? (server.all_towers || server.towers) : [];
            },
            updateTowerData() {
                const tower = this.towers.find(t => t.id == this.selectedTowerId);
                this.ssids = tower ? tower.ssids : [];
                // Update map center if available
                if (tower && tower.lat && this.map) {
                     this.map.setView([tower.lat, tower.lng], 14);
                }
            },
            loadPackageDefaults() {
                const select = document.querySelector('select[name="package_id"]');
                const option = select.options[select.selectedIndex];
                if (option.dataset.price) {
                    this.customPrice = option.dataset.price;
                }
            },

            initMap() {
                if (this.map) {
                    this.map.invalidateSize();
                    return;
                }
                this.map = L.map('map').setView([33.5138, 36.2765], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                this.map.on('click', (e) => {
                    this.lat = e.latlng.lat.toFixed(6);
                    this.lng = e.latlng.lng.toFixed(6);
                    if (this.marker) this.marker.setLatLng(e.latlng);
                    else this.marker = L.marker(e.latlng).addTo(this.map);
                });
            }
        }));
    });
    
    // Global style for Intl-Tel-Input
     document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.innerHTML = `
            .iti { width: 100%; direction: ltr !important; }
            .iti__country-list { text-align: left !important; }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
@endsection
