@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/css/intlTelInput.css">
<style>
    #map { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 1; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">إضافة مشترك جديد</h2>
        <p class="mt-2 text-lg text-gray-500">سجل بيانات المشترك الجديد للبدء في تقديم خدمات الإنترنت.</p>
    </div>

<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Decoration Header -->
        <div class="h-2 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>

        <form action="{{ route('crm.clients.store') }}" method="POST" class="p-8 md:p-10 space-y-10" 
              x-data="{ 
                  connectionType: 'pppoe',
                  connectionMode: 'wireless',
                  servers: {{ Js::from($servers) }},
                  deviceModels: {{ Js::from($deviceModels) }},
                  selectedServerId: '',
                  towers: [],
                  selectedTowerId: '',
                  towerRouters: [],
                  ssids: [],
                  selectedSSID: '',
                  tower_device_id: '',
                  cpeIp: '',
                  
                  // Map Data
                  lat: '',
                  lng: '',
                  map: null,
                  marker: null,

                  updateTowers() {
                      this.selectedTowerId = '';
                      this.ssids = [];
                      this.towerRouters = [];
                      const server = this.servers.find(s => s.id == this.selectedServerId);
                      this.towers = server ? (server.all_towers || server.towers) : [];
                  },
                  updateTowerData() {
                      const tower = this.towers.find(t => t.id == this.selectedTowerId);
                      this.ssids = tower ? tower.ssids : [];
                      this.towerRouters = tower ? tower.routers : [];
                      
                      // Update Map Center if tower has location
                      if (tower && tower.lat && tower.lng && this.map) {
                          this.map.setView([tower.lat, tower.lng], 15);
                      }
                  },
                  autoMapDevice() {
                      if (!this.selectedSSID) return;
                      
                      console.log('🔍 Auto-mapping router for SSID:', this.selectedSSID);
                      console.log('📡 Available tower routers:', this.towerRouters);
                      
                      // Routers don't have SSID field, so we need to find the SSID object
                      // and check if it has a router_id that matches
                      const ssidObj = this.ssids.find(s => s.ssid_name === this.selectedSSID);
                      
                      if (ssidObj && ssidObj.router_id) {
                          const router = this.towerRouters.find(r => r.id == ssidObj.router_id);
                          if (router) {
                              console.log('✅ Found matching router:', router);
                              this.tower_device_id = router.id;
                          } else {
                              console.warn('⚠️ Router ID from SSID not found in tower routers');
                          }
                      } else {
                          console.warn('⚠️ No router_id found for SSID:', this.selectedSSID);
                      }
                  },
                  syncIp(val) {
                      this.cpeIp = val;
                  },
                  syncCpeIp(val) {
                      this.cpeIp = val;
                  },
                  initMap() {
                      this.map = L.map('map').setView([33.5138, 36.2765], 13);
                      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                          attribution: 'Map data &copy; <a href=\'https://www.openstreetmap.org/\'>OpenStreetMap</a> contributors'
                      }).addTo(this.map);

                      this.map.on('click', (e) => {
                          this.lat = e.latlng.lat.toFixed(7);
                          this.lng = e.latlng.lng.toFixed(7);
                          
                          if (this.marker) {
                              this.marker.setLatLng(e.latlng);
                          } else {
                              this.marker = L.marker(e.latlng).addTo(this.map);
                          }
                      });
                  }
              }"
              x-init="initMap()">
            @csrf
            
            {{-- Show Validation Errors --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4 class="font-bold text-red-800 text-sm">يرجى تصحيح الأخطاء التالية:</h4>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <!-- Section 1: Connection & Account Details -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">بيانات الاتصال والحساب (الشبكة)</h3>
                        <p class="text-sm text-gray-500">معلومات الدخول ونوع الاشتراك</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">


                    <!-- Connection Type -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">نوع الاشتراك</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-gray-50 transition peer-checked:border-blue-600 peer-checked:ring-1 peer-checked:ring-blue-600">
                                <input type="radio" name="type" value="pppoe" class="sr-only peer" x-model="connectionType" checked>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900 peer-checked:text-blue-600">برودباند (PPPoE)</span>
                                        <span class="mt-1 flex items-center text-xs text-gray-500">اتصال ثابت وسريع</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-gray-400 peer-checked:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <!-- Highlight Border Fake -->
                                <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-blue-600 pointer-events-none" aria-hidden="true"></div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-gray-50 transition">
                                <input type="radio" name="type" value="hotspot" class="sr-only peer" x-model="connectionType">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900 peer-checked:text-purple-600">هوت سبوت (Hotspot)</span>
                                        <span class="mt-1 flex items-center text-xs text-gray-500">كروت وشبكات واي فاي</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-gray-400 peer-checked:text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-purple-600 pointer-events-none" aria-hidden="true"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Network Fields (Broadband Only) -->
                    <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8" x-show="connectionType === 'pppoe'" x-transition>
                        <!-- 1. Select Control Server -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">1. سيرفر التحكم (Core Router)</label>
                            <select name="mikrotik_server_id" x-model="selectedServerId" @change="updateTowers()" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition" :required="connectionType === 'pppoe'">
                                <option value="">اختر سيرفر التحكم...</option>
                                <template x-for="server in servers" :key="server.id">
                                    <option :value="server.id" x-text="server.name + ' (' + server.ip + ')'"></option>
                                </template>
                            </select>
                            @error('router_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- 2. Select Tower (Filtered) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">2. البرج (Tower)</label>
                            <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition" :disabled="!selectedServerId">
                                <option value="">اختر البرج...</option>
                                <template x-for="tower in towers" :key="tower.id">
                                    <option :value="tower.id" x-text="tower.name"></option>
                                </template>
                            </select>
                            <p class="text-xs text-gray-500 mt-1" x-show="!selectedServerId">يرجى اختيار السيرفر أولاً</p>
                            @error('tower_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- 3. Select SSID (Filtered) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">3. الشبكة (SSID)</label>
                            <select name="ssid" x-model="selectedSSID" @change="autoMapDevice()" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition" :disabled="!selectedTowerId">
                                <option value="">اختر الشبكة...</option>
                                 <template x-for="ssid in ssids" :key="ssid.id">
                                    <option :value="ssid.ssid_name" x-text="ssid.ssid_name"></option>
                                </template>
                            </select>
                             <p class="text-xs text-gray-500 mt-1" x-show="!selectedTowerId">يرجى اختيار البرج أولاً</p>
                        </div>
                        
                        <!-- 4. Connection Mode + Details -->
                        <div class="col-span-1 md:col-span-2 border-t pt-6 mt-2">
                             <label class="block text-sm font-semibold text-gray-700 mb-4">طريقة الربط (Connection Mode)</label>
                             <div class="flex items-center gap-6 mb-4">
                                 <label class="flex items-center gap-2 cursor-pointer">
                                     <input type="radio" name="connection_mode" value="wireless" x-model="connectionMode" class="text-blue-600 focus:ring-blue-500">
                                     <span class="text-gray-900 font-medium">هوائي (Wireless)</span>
                                 </label>
                                 <label class="flex items-center gap-2 cursor-pointer">
                                     <input type="radio" name="connection_mode" value="cable" x-model="connectionMode" class="text-blue-600 focus:ring-blue-500">
                                     <span class="text-gray-900 font-medium">كابل (Cable)</span>
                                 </label>
                             </div>
                             
                             <!-- Wireless Fields -->
                              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl border border-gray-200" x-show="connectionMode === 'wireless'">
                                  <div>
                                      <label class="block text-sm font-semibold text-gray-700 mb-2">الجهاز المرسل (البرج)</label>
                                      <div class="flex gap-2">
                                          <select name="tower_device_id" x-model="tower_device_id" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" :disabled="!selectedTowerId">
                                             <option value="">اختر القطعة المرسلة...</option>
                                             <template x-for="router in towerRouters" :key="router.id">
                                                 <option :value="router.id" x-text="router.name + (router.ip ? ' ('+router.ip+')' : '')" :selected="router.id == tower_device_id"></option>
                                             </template>
                                          </select>
                                      </div>
                                  </div>
                                  <div>
                                      <label class="block text-sm font-semibold text-gray-700 mb-2">جهاز الاستقبال (العميل) - اختياري</label>
                                      <input type="text" name="cpe_model" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="مثال: NanoStation M5">
                                  </div>
                                  <div>
                                       <label class="block text-sm font-semibold text-gray-700 mb-2">IP جهاز الاستقبال (CPE IP)</label>
                                        <input type="text" name="cpe_ip" x-model="cpeIp" @input="syncCpeIp($event.target.value)" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="192.168.x.x" dir="ltr">
                                  </div>
                                  <div>
                                       <label class="block text-sm font-semibold text-gray-700 mb-2">MAC جهاز الاستقبال</label>
                                       <input type="text" name="cpe_mac" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="XX:XX:XX:XX:XX:XX" dir="ltr">
                                  </div>
                              </div>
                             
                             <!-- Cable Fields -->
                             <div class="p-4 bg-gray-50 rounded-xl border border-gray-200" x-show="connectionMode === 'cable'">
                                 <label class="block text-sm font-semibold text-gray-700 mb-2">رقم البورت (Switch Port)</label>
                                 <input type="number" name="switch_port" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="e.g. 5">
                             </div>
                        </div>
                    </div>


                    <!-- PPPoE Username -->
                    <div x-show="connectionType === 'pppoe'" x-transition class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم (PPPoE Username)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="pppoe_username" value="{{ old('pppoe_username') }}" class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="client123" dir="ltr" :required="connectionType === 'pppoe'">
                        </div>
                    </div>

                    <!-- Hotspot Username -->
                    <div x-show="connectionType === 'hotspot'" x-transition class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم (Hotspot Username)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="hotspot_username" value="{{ old('hotspot_username') }}" class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-purple-500 focus:border-purple-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="user123" dir="ltr" :required="connectionType === 'hotspot'">
                        </div>
                    </div>

                    <!-- Service Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة مرور الخدمة (PPPoE/Hotspot)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            </div>
                            <input type="text" name="service_password" value="{{ old('service_password') }}" class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="Service Pass" dir="ltr" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">كلمة المرور في الراوتر (MikroTik)</p>
                    </div>

                    <!-- IP Address -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">عنوان IP (اختياري)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                            <input type="text" name="ip_address" value="{{ old('ip_address') }}" x-model="cpeIp" @input="syncIp($event.target.value)" class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="10.x.x.x" dir="ltr">
                       </div>
                        @if($lastIp)
                           <p class="text-[10px] text-gray-400 mt-1 font-light">آخر IP تم تسجيله لعميل: {{ $lastIp }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <hr class="border-gray-100">

            <!-- Section: Location Map -->
            <div>
                 <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">الموقع الجغرافي</h3>
                        <p class="text-sm text-gray-500">حدد موقع العميل على الخريطة</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <div id="map" class="shadow-sm border border-gray-200"></div>
                        <p class="text-xs text-gray-500 mt-2">انقر على الخريطة لتحديد الموقع</p>
                    </div>
                    <div>
                         <label class="block text-sm font-semibold text-gray-700 mb-2">خط العرض (Latitude)</label>
                         <input type="text" name="lat" x-model="lat" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" readonly>
                    </div>
                    <div>
                         <label class="block text-sm font-semibold text-gray-700 mb-2">خط الطول (Longitude)</label>
                         <input type="text" name="lng" x-model="lng" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" readonly>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Section: Limits & Billing -->
            <div>
                 <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">الباقة، المدة، والحدود</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                     <!-- Package -->
                     <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة المختارة</label>
                        <select name="package_id" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-gray-50 focus:bg-white transition">
                            <option value="">بدون باقة (اشتراك مخصص)</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }} - {{ $package->price }}$ ({{ $package->speed_down }}M/{{ $package->speed_up }}M)</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Price -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">السعر المخصص ($)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="block w-full pr-4 pl-12 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="0.00">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">$</span>
                            </div>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">مدة الاشتراك (يوم)</label>
                        <input type="number" name="duration_days" value="{{ old('duration_days', 30) }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-gray-50 focus:bg-white transition" placeholder="مثال: 30">
                        <p class="text-xs text-gray-500 mt-1">يفصل الاشتراك تلقائياً بعد هذه المدة</p>
                    </div>

                    <!-- Data Limit -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">حد الاستخدام (GB)</label>
                        <input type="number" name="data_limit" value="{{ old('data_limit') }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-gray-50 focus:bg-white transition" placeholder="Unlimited">
                        <p class="text-xs text-gray-500 mt-1">اتركه فارغاً لغير المحدود</p>
                    </div>
                </div>
            </div>
             
             <hr class="border-gray-100">

            <!-- Section: User Personal Info -->
             <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">البيانات الشخصية</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" required>
                    </div>
                    <div x-data="{ 
                        iti: null,
                        phonenumber: '{{ old('phone') }}',
                        init() {
                            if (typeof window.intlTelInput === 'undefined') {
                                setTimeout(() => this.init(), 100);
                                return;
                            }
                            
                            const input = document.querySelector('#phone');
                            this.iti = window.intlTelInput(input, {
                                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/js/utils.js',
                                initialCountry: 'auto',
                                geoIpLookup: function(callback) {
                                    fetch('https://ipapi.co/json')
                                    .then(function(res) { return res.json(); })
                                    .then(function(data) { callback(data.country_code); })
                                    .catch(function() { callback('SY'); });
                                },
                                separateDialCode: true,
                                preferredCountries: ['sy', 'sa', 'ae', 'jo', 'lb', 'tr'],
                            });
                            
                            // Set initial value if old input exists
                            if(this.phonenumber) {
                                this.iti.setNumber(this.phonenumber);
                            }

                            // Update hidden input on change
                            input.addEventListener('countrychange', () => {
                                this.phonenumber = this.iti.getNumber();
                            });
                            input.addEventListener('input', () => {
                                this.phonenumber = this.iti.getNumber();
                            });
                        }
                    }" x-init="init()">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف (الاسم المستخدم للبوابة)</label>
                        <div class="relative" dir="ltr" style="direction: ltr !important; text-align: left !important;">
                            <input type="tel" id="phone" dir="ltr" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" required style="direction: ltr !important; text-align: left !important;">
                            <input type="hidden" name="phone" :value="phonenumber">
                        </div>
                        <p id="phone-error" class="text-red-500 text-xs mt-1 hidden">يرجى إدخال رقم هاتف صحيح</p>
                    </div>
                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة مرور البوابة</label>
                        <input type="text" name="password" value="{{ old('password') }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" required>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-6 flex flex-col md:flex-row items-center justify-end gap-4">
                <a href="{{ route('crm.clients.index') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition w-full md:w-auto text-center">
                    إلغاء الأمر
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-lg hover:from-blue-700 hover:to-indigo-700 transition transform hover:-translate-y-0.5 w-full md:w-auto">
                    <span class="flex items-center justify-center gap-2">
                         حفظ وإنشاء الاشتراك
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/js/intlTelInput.min.js"></script>
<script>
    // Global style fix for intl-tel-input inside text-right container
    document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.innerHTML = `
            .iti { width: 100%; direction: ltr !important; text-align: left !important; }
            .iti__country-list { text-align: left !important; direction: ltr !important; left: 0 !important; right: auto !important; }
            .iti__flag-container { left: 0 !important; right: auto !important; }
            .iti__selected-flag { direction: ltr !important; padding-left: 12px !important; }
            .iti__flag { background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/img/flags.png"); }
            @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
              .iti__flag { background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/img/flags@2x.png"); }
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
@endsection
