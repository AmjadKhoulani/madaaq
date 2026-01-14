@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 1; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">تعديل بيانات المشترك</h2>
        <p class="mt-2 text-lg text-gray-500">تحديث معلومات المشترك {{ $client->name ?? $client->username }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Decoration Header -->
        <div class="h-2 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>

        <form action="{{ route('crm.clients.update', $client) }}" method="POST" class="p-8 md:p-10 space-y-10"
              x-data="{ 
                  connectionType: '{{ $client->type }}',
                  connectionMode: '{{ $client->connection_mode ?? 'wireless' }}',
                  servers: {{ Js::from($servers) }},
                  deviceModels: {{ Js::from($deviceModels) }},
                  selectedServerId: {{ $client->mikrotik_server_id ?? 'null' }},
                  towers: [],
                  selectedTowerId: {{ $client->tower_id ?? 'null' }},
                  towerDevices: [],
                  ssids: [],
                  selectedSSID: {{ $client->ssid_id ?? 'null' }},
                  tower_device_id: {{ $client->tower_device_id ?? 'null' }},
                  cpeIp: '{{ $client->cpe_ip ?? $client->ip }}',
                  
                  // Map Data
                  lat: '{{ $client->lat }}',
                  lng: '{{ $client->lng }}',
                  map: null,
                  marker: null,

                  updateTowers() {
                      const server = this.servers.find(s => s.id == this.selectedServerId);
                      this.towers = server ? (server.all_towers || server.towers) : [];
                  },
                  updateTowerData() {
                      const tower = this.towers.find(t => t.id == this.selectedTowerId);
                      this.ssids = tower ? tower.ssids : [];
                      this.towerDevices = tower ? tower.devices : [];
                  },
                  autoMapDevice() {
                      if (!this.selectedSSID) return;
                      const ssidObj = this.ssids.find(s => s.id == this.selectedSSID);
                      if (ssidObj && ssidObj.tower_device_id) {
                          this.tower_device_id = ssidObj.tower_device_id;
                      }
                  },
                   syncIp(val) {
                       this.cpeIp = val;
                   },
                   syncCpeIp(val) {
                       this.cpeIp = val;
                   },
                  init() {
                      this.updateTowers();
                      this.updateTowerData();
                      this.initMap();
                  },
                  initMap() {
                      // Initial Lat/Lng or Default
                      const initialLat = this.lat || (this.towers.find(t => t.id == this.selectedTowerId)?.lat || 33.5138);
                      const initialLng = this.lng || (this.towers.find(t => t.id == this.selectedTowerId)?.lng || 36.2765);

                      this.map = L.map('map').setView([initialLat, initialLng], 13);
                      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                          attribution: 'Map data &copy; <a href=\'https://www.openstreetmap.org/\'>OpenStreetMap</a> contributors'
                      }).addTo(this.map);

                      if (this.lat && this.lng) {
                           this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
                      }

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
              }">
            @csrf
            @method('PUT')
            
            {{-- Validation Errors Display --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-5 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="mr-3">
                            <h3 class="text-base font-bold text-red-800 mb-2">يوجد مشاكل في البيانات المدخلة:</h3>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-5 mb-6">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Hidden Fields for Locked Data -->
            <input type="hidden" name="type" value="{{ $client->type }}">

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
                    
                    <!-- Status -->
                    <div class="col-span-1 md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">حالة الاشتراك</label>
                        <select name="status" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition">
                            <option value="active" {{ $client->status == 'active' ? 'selected' : '' }}>نشط (Active)</option>
                            <option value="inactive" {{ $client->status == 'inactive' ? 'selected' : '' }}>غير نشط (Inactive)</option>
                            <option value="suspended" {{ $client->status == 'suspended' ? 'selected' : '' }}>موقوف (Suspended)</option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ انتهاء الاشتراك (Renew Date)</label>
                        <input type="date" name="expires_at" value="{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : '' }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition">
                        <p class="text-xs text-gray-500 mt-1">تعديل هذا التاريخ سيغير موعد فصل الخدمة يدوياً.</p>
                    </div>

                    <!-- Connection Type (LOCKED) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">نوع الاشتراك (لا يمكن تغييره)</label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- PPPoE Option -->
                            <label class="relative flex cursor-not-allowed rounded-xl border p-4 shadow-sm focus:outline-none bg-gray-50 transition {{ $client->type == 'pppoe' ? 'border-blue-600 ring-1 ring-blue-600' : 'opacity-50' }}">
                                <input type="radio" value="pppoe" class="sr-only peer" :checked="connectionType === 'pppoe'" disabled>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900 peer-checked:text-blue-600">برودباند (PPPoE)</span>
                                        <span class="mt-1 flex items-center text-xs text-gray-500">اتصال ثابت وسريع</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-gray-400 peer-checked:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </label>

                            <!-- Hotspot Option -->
                            <label class="relative flex cursor-not-allowed rounded-xl border p-4 shadow-sm focus:outline-none bg-gray-50 transition {{ $client->type == 'hotspot' ? 'border-purple-600 ring-1 ring-purple-600' : 'opacity-50' }}">
                                <input type="radio" value="hotspot" class="sr-only peer" :checked="connectionType === 'hotspot'" disabled>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-bold text-gray-900 peer-checked:text-purple-600">هوت سبوت (Hotspot)</span>
                                        <span class="mt-1 flex items-center text-xs text-gray-500">كروت وشبكات واي فاي</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-gray-400 peer-checked:text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            </label>
                        </div>
                    </div>

                    <!-- Network Fields (Broadband Only) -->
                    <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8" x-show="connectionType === 'pppoe'" x-transition>
                        <!-- 1. Select Control Server -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">1. سيرفر التحكم (Core Router)</label>
                            <select name="mikrotik_server_id" x-model="selectedServerId" @change="selectedTowerId=''; updateTowers()" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition" :required="connectionType === 'pppoe'">
                                <option value="">اختر سيرفر التحكم...</option>
                                <template x-for="server in servers" :key="server.id">
                                    <option :value="server.id" x-text="server.name + ' (' + server.ip + ')'" :selected="server.id == selectedServerId"></option>
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
                                    <option :value="tower.id" x-text="tower.name" :selected="tower.id == selectedTowerId"></option>
                                </template>
                            </select>
                            @error('tower_id') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- 3. Select SSID (Filtered) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">3. الشبكة (SSID)</label>
                            <select name="ssid_id" x-model="selectedSSID" @change="autoMapDevice()" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition" :disabled="!selectedTowerId">
                                <option value="">اختر الشبكة...</option>
                                <template x-for="ssid in ssids" :key="ssid.id">
                                    <option :value="ssid.id" x-text="ssid.ssid_name" :selected="ssid.id == {{ $client->ssid_id ?? 'null' }}"></option>
                                </template>
                            </select>
                            <p class="text-xs text-gray-500 mt-1" x-show="!selectedTowerId">يرجى اختيار البرج أولاً</p>
                        </div>
                        
                        <!-- 4. Connection Mode + Details -->
                        <div class="col-span-1 md:col-span-2 border-t pt-6 mt-2">
                             <label class="block text-sm font-semibold text-gray-700 mb-4">طريقة الربط (Connection Mode)</label>
                             <div class="flex items-center gap-6 mb-4">
                                 <label class="flex items-center gap-2 cursor-pointer">
                                     <input type="radio" name="connection_mode" value="wireless" x-model="connectionMode" class="text-blue-600 focus:ring-blue-500" {{ $client->status == 'active' ? '' : '' /* Maybe lock if needed, but usually editable */ }}>
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
                                      <select name="tower_device_id" x-model="tower_device_id" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                         <option value="">اختر القطعة المرسلة...</option>
                                         <template x-for="device in towerDevices" :key="device.id">
                                             <option :value="device.id" x-text="device.name + (device.ip ? ' (' + device.ip + ')' : '')"></option>
                                         </template>
                                      </select>
                                      <p class="text-xs text-gray-500 mt-1" x-show="selectedTowerId && towerDevices && towerDevices.length > 0">
                                          <span x-text="'عدد الأجهزة: ' + towerDevices.length"></span>
                                      </p>
                                      <p class="text-xs text-gray-400 mt-1" x-show="!selectedTowerId">يرجى اختيار البرج أولاً</p>
                                      <p class="text-xs text-orange-500 mt-1" x-show="selectedTowerId && (!towerDevices || towerDevices.length === 0)">لا توجد أجهزة في هذا البرج</p>
                                  </div>
                                  <div>
                                      <label class="block text-sm font-semibold text-gray-700 mb-2">جهاز الاستقبال (CPE Model)</label>
                                      <input type="text" name="cpe_model" value="{{ $client->cpe_model }}" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="مثال: NanoStation M5">
                                  </div>
                                   <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">IP جهاز الاستقبال (CPE IP)</label>
                                        <input type="text" name="cpe_ip" x-model="cpeIp" @input="syncCpeIp($event.target.value)" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="192.168.x.x" dir="ltr">
                                   </div>
                                  <div>
                                       <label class="block text-sm font-semibold text-gray-700 mb-2">MAC جهاز الاستقبال</label>
                                       <input type="text" name="cpe_mac" value="{{ $client->cpe_mac }}" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="XX:XX:XX:XX:XX:XX" dir="ltr">
                                  </div>
                              </div>
                             
                             <!-- Cable Fields -->
                             <div class="p-4 bg-gray-50 rounded-xl border border-gray-200" x-show="connectionMode === 'cable'">
                                 <label class="block text-sm font-semibold text-gray-700 mb-2">رقم البورت (Switch Port)</label>
                                 <input type="number" name="switch_port" value="{{ old('switch_port', $client->switch_port) }}" class="block w-full py-2 px-3 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
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
                            <input type="text" name="pppoe_username" value="{{ old('pppoe_username', $client->type == 'pppoe' ? $client->username : '') }}" class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition shadow-sm bg-gray-50 focus:bg-white" dir="ltr" :required="connectionType === 'pppoe'" readonly>
                             <p class="text-xs text-gray-400 mt-1">اسم المستخدم للقراءة فقط</p>
                        </div>
                    </div>

                    <!-- Hotspot Username -->
                    <div x-show="connectionType === 'hotspot'" x-transition class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم (Hotspot Username)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="hotspot_username" value="{{ old('hotspot_username', $client->type == 'hotspot' ? $client->username : '') }}" class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-purple-500 focus:border-purple-500 transition shadow-sm bg-gray-50 focus:bg-white" dir="ltr" :required="connectionType === 'hotspot'" readonly>
                        </div>
                    </div>

                    <!-- Service Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة مرور الخدمة (PPPoE/Hotspot)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            </div>
                            <input type="text" name="service_password" value="{{ old('service_password', $client->service_password) }}" class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="Service Pass" dir="ltr">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">اتركها فارغة إذا لم ترد التغيير</p>
                    </div>

                    <!-- IP Address -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">عنوان IP (اختياري)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                            </div>
                             <input type="text" name="ip_address" value="{{ old('ip_address', $client->ip) }}" x-model="cpeIp" @input="syncIp($event.target.value)" class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="10.x.x.x" dir="ltr">
                        </div>
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
                        <p class="text-sm text-gray-500">حدث موقع العميل على الخريطة</p>
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
                                <option value="{{ $package->id }}" {{ $client->package_id == $package->id ? 'selected' : '' }}>{{ $package->name }} - {{ $package->price }}$ ({{ $package->speed_down }}M/{{ $package->speed_up }}M)</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom Price -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">السعر المخصص ($)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="custom_price" value="{{ old('custom_price', $client->custom_price) }}" class="block w-full pr-4 pl-12 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="0.00">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">$</span>
                            </div>
                        </div>
                    </div>

                    <!-- Data Limit -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">حد الاستخدام (GB)</label>
                        <input type="number" name="custom_data_limit_mb" value="{{ old('custom_data_limit_mb', $client->custom_data_limit_mb) }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-gray-50 focus:bg-white transition" placeholder="Unlimited">
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
                        <input type="text" name="name" value="{{ old('name', $client->name ?? $client->username) }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone', $client->phone ?? $client->username) }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" required>
                    </div>
                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة مرور البوابة</label>
                        <input type="text" name="password" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="اتركها فارغة لعدم التغيير">
                    </div>
                    <div>
                         <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', $client->email) }}" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white">
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
                         حفظ التعديلات
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush
@endsection
