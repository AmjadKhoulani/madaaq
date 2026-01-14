@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="towerEditForm()">

    <div>
        <h2 class="text-2xl font-bold text-gray-900">تعديل البرج</h2>
        <p class="text-gray-500 mt-1">تحديث معلومات وتجهيزات البرج</p>
    </div>

    <!-- Error Messages -->
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

    <form action="{{ route('network.towers.update', $tower) }}" method="POST" class="space-y-6" 
          x-data="{ 
              activeTab: new URLSearchParams(window.location.search).get('tab') || 'basic',
              hasInverter: {{ $tower->has_inverter ? 'true' : 'false' }}, 
              hasGenerator: {{ $tower->has_generator ? 'true' : 'false' }}, 
              hasSolar: {{ $tower->has_solar ? 'true' : 'false' }}, 
              hasAmpere: {{ $tower->has_ampere ? 'true' : 'false' }},
              hasGovElectricity: {{ $tower->has_government_electricity ? 'true' : 'false' }},
              lat: {{ $tower->lat ?? 'null' }}, 
              lng: {{ $tower->lng ?? 'null' }},
              connectionType: '{{ $tower->connection_type ?? 'cable' }}',
              selectedCity: '{{ old('city', $tower->city) }}',

              // Interface Fetching Logic
              interfaces: [],
              loadingInterfaces: false,
              fetchInterfaces() {
                  if (!this.activeTab) return; // Optimization
                  const serverId = document.querySelector('select[name=\'mikrotik_server_id\']').value;
                  if (!serverId || (this.connectionType !== 'cable' && this.connectionType !== 'fiber')) return;
                  
                  this.loadingInterfaces = true;
                  fetch(`/servers/${serverId}/interfaces`)
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
              init() {
                   if(this.connectionType === 'cable' || this.connectionType === 'fiber') {
                       this.fetchInterfaces();
                   }
                   this.$watch('connectionType', val => {
                       if(val === 'cable' || val === 'fiber') this.fetchInterfaces();
                   });
              },
              
              // Device Modal Logic (Ported from Create Page)
              isDeviceModalOpen: false,
              deviceMode: 'receiver', // 'transmitter' or 'receiver'
              deviceMap: null,
              deviceMarker: null,
              newDevice: { 
                  name: '',
                  manufacturer: '', 
                  model_name: '', 
                  model_id: '',
                  type: 'station', 
                  ip: '',
                  mac: '',
                  ssid: '',
                  tower_id: '', 
                  lat: '',
                  lng: ''
              },
              openDeviceModal(mode) { 
                  this.deviceMode = mode;
                  this.isDeviceModalOpen = true; 
                  this.newDevice.type = mode === 'transmitter' ? 'access_point' : 'station';
                  
                  // Initialize or Reset Map
                  this.$nextTick(() => {
                      if(!this.deviceMap) {
                          this.initDeviceMap();
                      }
                      // If receiver, use current tower location as default
                      if(mode === 'receiver') {
                          // Try to get from hidden inputs first if set
                          const currentLat = document.querySelector('input[name=\'lat\']').value;
                          const currentLng = document.querySelector('input[name=\'lng\']').value;
                          
                          if(currentLat && currentLng) {
                              this.setDeviceLocation(currentLat, currentLng);
                          }
                      }
                  });
              },
              closeDeviceModal() { 
                  this.isDeviceModalOpen = false; 
                  this.newDevice = { 
                      name: '', manufacturer: '', model_name: '', model_id: '', 
                      type: 'station', ip: '', mac: '', ssid: '', tower_id: '', lat: '', lng: '' 
                  }; 
              },
              initDeviceMap() {
                  if(document.getElementById('device-map')) {
                      this.deviceMap = L.map('device-map').setView([33.5138, 36.2765], 10);
                      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(this.deviceMap);
                      this.deviceMap.on('click', (e) => {
                          this.setDeviceLocation(e.latlng.lat, e.latlng.lng);
                      });
                      setTimeout(() => this.deviceMap.invalidateSize(), 500);
                  }
              },
              setDeviceLocation(lat, lng) {
                  this.newDevice.lat = lat;
                  this.newDevice.lng = lng;
                  if(this.deviceMarker) this.deviceMap.removeLayer(this.deviceMarker);
                  this.deviceMarker = L.marker([lat, lng]).addTo(this.deviceMap);
                  this.deviceMap.setView([lat, lng], 13);
              },
              saveDevice() {
                  if(this.deviceMode === 'transmitter') {
                       // AJAX store to create NEW Router on Source Tower
                       fetch('{{ route('routers.store') }}', { 
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                          body: JSON.stringify({
                              name: this.newDevice.name,
                              tower_id: this.newDevice.tower_id,
                              model_id: this.newDevice.model_id,
                              ip: this.newDevice.ip,
                              mac_address: this.newDevice.mac,
                              device_type: 'access_point',
                              lat: this.newDevice.lat,
                              lng: this.newDevice.lng,
                              ssid: this.newDevice.ssid,
                              status: 'active'
                          })
                      })
                       .then(r => r.json())
                       .then(data => {
                           if(data.id) {
                               // Add to Transmitter Select
                               const select = document.querySelector('select[name=\'transmitter_router_id\']');
                               const option = new Option(data.name + ' (' + data.ip + ')', data.id, true, true);
                               select.add(option);
                               // Set hidden fields
                               document.querySelector('input[name=\'transmitter_ip\']').value = data.ip;
                               document.querySelector('input[name=\'transmitter_model_id\']').value = data.model_id;
                               this.closeDeviceModal();
                           } else {
                               alert('خطأ أثناء حفظ الجهاز: ' + (data.message || 'Unknown error'));
                           }
                       })
                       .catch(error => {
                           console.error('Error:', error);
                           alert('حدث خطأ في الاتصال');
                       });
                  } else {
                      // Receiver: Just fill hidden inputs for main form submission
                      document.querySelector('input[name=\'receiver_name\']').value = this.newDevice.name; // Note: Ensure this input exists or logic adapts
                      document.querySelector('input[name=\'receiver_ip\']').value = this.newDevice.ip;
                      
                      // Check if we have receiver_mac hidden field, if not, we might need to rely on just IP/Model or add it
                      // The controller uses update, assumes receiver comes from main form data.
                      // TowerController update validation rule 201-204 only lists receiver_ip, receiver_model_id.
                      // We might need to ensure these values are populated.
                      
                      const receiverSelect = document.querySelector('select[name=\'receiver_model_id\']');
                      receiverSelect.value = this.newDevice.model_id;
                      
                      this.closeDeviceModal();
                  }
              }
          }"
          x-init="init()"
          @keydown.escape.window="activeTab = 'basic'">
        @csrf
        @method('PUT')

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px overflow-x-auto">
                    <button type="button" @click="activeTab = 'basic'" :class="activeTab === 'basic' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition whitespace-nowrap">
                        ℹ️ المعلومات الأساسية
                    </button>
                    <button type="button" @click="activeTab = 'power'" :class="activeTab === 'power' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition whitespace-nowrap">
                        ⚡ الطاقة
                    </button>
                    <button type="button" @click="activeTab = 'costs'" :class="activeTab === 'costs' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition whitespace-nowrap">
                        💰 التكاليف
                    </button>
                    <button type="button" @click="activeTab = 'connection'" :class="activeTab === 'connection' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 border-b-2 font-medium text-sm transition whitespace-nowrap">
                        🔗 تفاصيل الربط
                    </button>
                </nav>
            </div>

            <!-- Tab: Basic Info -->
            <div x-show="activeTab === 'basic'" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">اسم البرج *</label>
                            <input type="text" name="name" required value="{{ old('name', $tower->name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="مثال: برج الميدان">
                        </div>


                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">المحافظة</label>
                            <select name="city" x-model="selectedCity" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">اختر المحافظة...</option>
                                @foreach(['Damascus' => 'دمشق', 'Aleppo' => 'حلب', 'Homs' => 'حمص', 'Latakia' => 'اللاذقية', 'Hama' => 'حماة', 'Tartus' => 'طرطوس', 'Raqqa' => 'الرقة', 'Deir ez-Zor' => 'دير الزور', 'Idlib' => 'إدلب', 'Daraa' => 'درعا', 'Suwayda' => 'السويداء', 'Hasakah' => 'الحسكة', 'Quneitra' => 'القنيطرة', 'Rif Dimashq' => 'ريف دمشق'] as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="selectedCity" x-transition>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">المنطقة / الناحية</label>
                            <input type="text" name="district" value="{{ old('district', $tower->district) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="مثال: جرمانا، المزة...">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">تفاصيل الموقع</label>
                            <textarea name="location" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="وصف دقيق للموقع...">{{ old('location', $tower->location) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">النوع *</label>
                            <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="tower" {{ old('type', $tower->type) == 'tower' ? 'selected' : '' }}>برج (Tower)</option>
                                <option value="building" {{ old('type', $tower->type) == 'building' ? 'selected' : '' }}>مبنى (Building)</option>
                                <option value="pole" {{ old('type', $tower->type) == 'pole' ? 'selected' : '' }}>عمود (Pole)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">الحالة *</label>
                            <select name="status" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ old('status', $tower->status) == 'active' ? 'selected' : '' }}>🟢 فعال (Active)</option>
                                <option value="maintenance" {{ old('status', $tower->status) == 'maintenance' ? 'selected' : '' }}>🟠 صيانة (Maintenance)</option>
                                <option value="inactive" {{ old('status', $tower->status) == 'inactive' ? 'selected' : '' }}>🔴 متوقف (Inactive)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="h-full min-h-[400px] bg-gray-100 rounded-xl relative overflow-hidden border border-gray-300">
                        <div id="map" class="absolute inset-0 z-0"></div>
                        <input type="hidden" name="lat" value="{{ old('lat', $tower->lat) }}">
                        <input type="hidden" name="lng" value="{{ old('lng', $tower->lng) }}">
                        <div class="absolute top-4 right-4 z-10 bg-white/90 backdrop-blur px-4 py-2 rounded-lg shadow-sm text-xs font-mono">
                            انقر على الخريطة لتحديد الموقع
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Power -->
            <div x-show="activeTab === 'power'" class="p-6">
                <div class="space-y-6">
                    <!-- Government Electricity -->
                     <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100">
                        <label class="flex items-center gap-3 mb-4 cursor-pointer">
                            <input type="checkbox" name="has_government_electricity" value="1" x-model="hasGovElectricity" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                            <span class="font-bold text-gray-800 text-lg">كهرباء الدولة (Commercial Power)</span>
                        </label>
                        
                        <div x-show="hasGovElectricity" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">سعر الكيلو واط ({{ $currency }})</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="kwh_price" value="{{ old('kwh_price', $tower->kwh_price) }}" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">{{ $currency }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ساعات الوصل اليومي (تقديري)</label>
                                <input type="number" step="0.5" name="electricity_hours" value="{{ old('electricity_hours', $tower->electricity_hours) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                             <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات خط الكهرباء</label>
                                <textarea name="government_electricity_notes" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('government_electricity_notes', $tower->government_electricity_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Solar Power -->
                    <div class="bg-yellow-50/50 p-6 rounded-xl border border-yellow-100">
                        <label class="flex items-center gap-3 mb-4 cursor-pointer">
                            <input type="checkbox" name="has_solar" value="1" x-model="hasSolar" class="w-5 h-5 text-yellow-500 rounded focus:ring-yellow-400">
                            <span class="font-bold text-gray-800 text-lg">الطاقة الشمسية</span>
                        </label>

                        <div x-show="hasSolar" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-6 pl-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">سعة المنظومة (أمبير/واط...)</label>
                                <input type="text" name="solar_capacity" value="{{ old('solar_capacity', $tower->solar_capacity) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">عدد الألواح</label>
                                <input type="number" name="solar_panels_count" value="{{ old('solar_panels_count', $tower->solar_panels_count) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">استطاعة اللوح (واط)</label>
                                <div class="relative">
                                    <input type="number" name="solar_panel_wattage" value="{{ old('solar_panel_wattage', $tower->solar_panel_wattage) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Watt</span>
                                </div>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تكلفة التركيب ({{ $currency }})</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="solar_installation_cost" value="{{ old('solar_installation_cost', $tower->solar_installation_cost) }}" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">{{ $currency }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ampere Subscription -->
                    <div class="bg-purple-50/50 p-6 rounded-xl border border-purple-100">
                         <label class="flex items-center gap-3 mb-4 cursor-pointer">
                            <input type="checkbox" name="has_ampere" value="1" x-model="hasAmpere" class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                            <span class="font-bold text-gray-800 text-lg">اشتراك أمبيرات</span>
                        </label>
                    </div>

                    <!-- Batteries & Inverter -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <h3 class="font-semibold text-gray-800 mb-4">البطاريات</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">العدد</label>
                                    <input type="number" name="battery_count" value="{{ old('battery_count', $tower->battery_count) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">النوع</label>
                                    <input type="text" name="battery_type" value="{{ old('battery_type', $tower->battery_type) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Gel, Lead Acid, Lithium...">
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <div class="mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="has_inverter" value="1" x-model="hasInverter" class="w-4 h-4 text-gray-600 rounded focus:ring-gray-500">
                                    <span class="font-semibold text-gray-800">يوجد انفيتر (Inverter)</span>
                                </label>
                            </div>
                             <div x-show="hasInverter" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الاستطاعة / النوع</label>
                                <input type="text" name="inverter_capacity" value="{{ old('inverter_capacity', $tower->inverter_capacity) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                             <div class="mt-4 mb-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="has_generator" value="1" x-model="hasGenerator" class="w-4 h-4 text-gray-600 rounded focus:ring-gray-500">
                                    <span class="font-semibold text-gray-800">يوجد مولدة خاصة (Generator)</span>
                                </label>
                            </div>
                             <div x-show="hasGenerator" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الاستطاعة / التفاصيل</label>
                                <input type="text" name="generator_capacity" value="{{ old('generator_capacity', $tower->generator_capacity) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Costs -->
            <div x-show="activeTab === 'costs'" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الإيجار الشهري (للمكان) ({{ $currency }})</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="monthly_rent" value="{{ old('monthly_rent', $tower->monthly_rent) }}" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">تكلفة الصيانة الشهرية المتوقعة ({{ $currency }})</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="monthly_maintenance" value="{{ old('monthly_maintenance', $tower->monthly_maintenance) }}" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">تكاليف شهرية أخرى ({{ $currency }})</label>
                         <div class="relative">
                            <input type="number" step="0.01" name="monthly_other_costs" value="{{ old('monthly_other_costs', $tower->monthly_other_costs) }}" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">تكلفة الهيكل الحديدي والتركيب (مرة واحدة) ({{ $currency }})</label>
                         <div class="relative">
                             <input type="number" step="0.01" name="structure_cost" value="{{ old('structure_cost', $tower->structure_cost) }}" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                         <label class="block text-sm font-semibold text-gray-700 mb-2">ملاحظات مالية</label>
                        <textarea name="monthly_notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('monthly_notes', $tower->monthly_notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tab: Connection -->
             <div x-show="activeTab === 'connection'" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">السيرفر الرئيسي (Sending Server)</label>
                        <select name="mikrotik_server_id" @change="fetchInterfaces()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">-- اختر السيرفر --</option>
                            @foreach($servers as $server)
                                <option value="{{ $server->id }}" {{ old('mikrotik_server_id', $tower->sending_server_id) == $server->id ? 'selected' : '' }}>
                                    {{ $server->name }} ({{ $server->ip_address }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">السيرفر الذي يغذي هذا البرج بالإنترنت.</p>
                    </div>

                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">نوع الاتصال</label>
                        <select name="connection_type" x-model="connectionType" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="cable">Cable (Ethernet)</option>
                            <option value="fiber">Fiber Optic</option>
                            <option value="wireless">Wireless (PTP)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">المنفذ / البورت (Connected Port)</label>
                        <div class="relative">
                            <template x-if="loadingInterfaces">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </template>
                            
                            <!-- Select Interface (if loaded) -->
                            <template x-if="!loadingInterfaces && interfaces.length > 0">
                                <select name="connection_port" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">اختر المنفذ...</option>
                                    <template x-for="iface in interfaces" :key="iface.name">
                                        <option :value="iface.name" x-text="iface.name" :selected="iface.name == '{{ $tower->connection_port }}'"></option>
                                    </template>
                                </select>
                            </template>
                            
                            <!-- Fallback Input (if no interfaces loaded or wireless) -->
                            <template x-if="!loadingInterfaces && interfaces.length === 0">
                                <input type="text" name="connection_port" value="{{ old('connection_port', $tower->connection_port) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="ether1, sfp-plus1...">
                            </template>
                        </div>
                    </div>

                    <!-- Wireless Details -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl border border-gray-200" x-show="connectionType === 'wireless'" x-transition>
                        <div class="md:col-span-2 mb-2">
                             <h4 class="font-bold text-gray-800">تفاصيل الربط اللاسلكي (PTP)</h4>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الإرسال (Transmitter)</label>
                            <div class="flex gap-2">
                                <select name="transmitter_router_id" @change="
                                    const selectedParams = $event.target.selectedOptions[0].dataset;
                                    document.querySelector('input[name=\'transmitter_model_id\']').value = selectedParams.modelId || '';
                                    document.querySelector('input[name=\'transmitter_ip\']').value = selectedParams.ip || '';
                                " class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                                    <option value="">اختر جهاز مرسل (Access Point)...</option>
                                    @foreach($activeRouters as $router)
                                        <option value="{{ $router->id }}" data-model-id="{{ $router->model_id }}" data-ip="{{ $router->ip }}" {{ (old('transmitter_ip', $tower->transmitter_ip) == $router->ip) ? 'selected' : '' }}>
                                            {{ $router->name }} ({{ $router->ip }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="transmitter_model_id" value="{{ old('transmitter_model_id', $tower->transmitter_model_id) }}">
                                <button type="button" @click="openDeviceModal('transmitter')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 text-gray-700" title="إضافة مرسل جديد على برج آخر">
                                    +
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">IP جهاز الإرسال</label>
                             <input type="text" name="transmitter_ip" value="{{ old('transmitter_ip', $tower->transmitter_ip) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50" readonly dir="ltr">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قطعة الاستقبال (Receiver)</label>
                            <div class="flex gap-2">
                                <select name="receiver_model_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                                    <option value="">اختر الموديل...</option>
                                    @foreach($deviceModels as $model)
                                        <option value="{{ $model->id }}" {{ old('receiver_model_id', $tower->receiver_model_id) == $model->id ? 'selected' : '' }}>
                                            {{ $model->manufacturer }} {{ $model->model_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" @click="openDeviceModal('receiver')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-300 text-gray-700" title="إضافة جهاز استقبال جديد (كامل)">
                                    +
                                </button>
                            </div>
                            {{-- Hidden Fields for Receiver --}}
                            <input type="hidden" name="receiver_name">
                            <input type="hidden" name="receiver_mac">
                            <input type="hidden" name="receiver_ssid">
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">IP جهاز الاستقبال</label>
                             <input type="text" name="receiver_ip" value="{{ old('receiver_ip', $tower->receiver_ip) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" dir="ltr">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between rounded-b-xl">
                <a href="{{ route('network.towers.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-white transition">
                    إلغاء
                </a>
                <div class="flex gap-3">
                    <button type="button" @click="activeTab = 'basic'" x-show="activeTab !== 'basic'" class="px-6 py-2.5 text-gray-600 hover:text-gray-900 font-medium">
                        السابق
                    </button>
                    <!-- Update logic for Next/Submit buttons -->
                     <button type="button" @click="activeTab = (activeTab === 'basic' ? 'power' : (activeTab === 'power' ? 'costs' : 'connection'))" x-show="activeTab !== 'connection'" class="px-6 py-2.5 bg-blue-100 text-blue-700 font-semibold rounded-lg hover:bg-blue-200 transition">
                        التالي
                    </button>
                    
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
                        💾 حفظ التعديلات
                    </button>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Full Device Modal -->
    <div x-show="isDeviceModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
            <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all sm:max-w-3xl w-full z-10 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900" x-text="deviceMode === 'transmitter' ? 'إضافة جهاز مرسل (على برج آخر)' : 'إضافة جهاز استقبال (على هذا البرج)'"></h3>
                    <button @click="closeDeviceModal()" class="text-gray-400 hover:text-gray-600">
                         <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <!-- Source Tower (Only for Transmitter) -->
                        <div x-show="deviceMode === 'transmitter'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">البرج المصدر (Source Tower)</label>
                            <select x-model="newDevice.tower_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">اختر البرج...</option>
                                @foreach($towers as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم الجهاز</label>
                            <input type="text" x-model="newDevice.name" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="مثال: Sector 1 / Receiver A">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">موديل الجهاز</label>
                            <select x-model="newDevice.model_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">اختر الموديل...</option>
                                @foreach($deviceModels as $model)
                                    <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                                <input type="text" x-model="newDevice.ip" class="w-full px-3 py-2 border border-gray-300 rounded-lg" dir="ltr" placeholder="192.168.x.x">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">MAC Address</label>
                                <input type="text" x-model="newDevice.mac" class="w-full px-3 py-2 border border-gray-300 rounded-lg" dir="ltr">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SSID</label>
                            <input type="text" x-model="newDevice.ssid" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <!-- Map Side -->
                    <div>
                         <label class="block text-sm font-medium text-gray-700 mb-2">الموقع الجغرافي للجهاز</label>
                         <div id="device-map" class="w-full h-64 bg-gray-100 rounded-xl border border-gray-300"></div>
                         <div class="grid grid-cols-2 gap-2 mt-2">
                            <input type="text" x-model="newDevice.lat" readonly class="text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1">
                            <input type="text" x-model="newDevice.lng" readonly class="text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1">
                         </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="closeDeviceModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">إلغاء</button>
                    <button type="button" @click="saveDevice()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-sm">حفظ وإضافة</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Device Modal -->
    <div x-data="{ open: false }" 
         @open-device-modal.window="open = true" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('network.towers.devices.store', $tower) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">إضافة جهاز جديد</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الجهاز *</label>
                                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="مثلاً: Sector 1">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                                    <input type="text" name="ip" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" dir="ltr">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">MAC Address</label>
                                    <input type="text" name="mac_address" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" dir="ltr">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">موديل الجهاز</label>
                                <select name="device_model_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">اختر الموديل...</option>
                                    @foreach($deviceModels as $model)
                                        <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">الوضع (Mode)</label>
                                    <select name="mode" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="ap">Access Point (AP)</option>
                                        <option value="station">Station</option>
                                        <option value="bridge">Bridge</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد</label>
                                    <select name="frequency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="60GHz">60 GHz</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة (SSID)</label>
                                <input type="text" name="ssid" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="إذا كان AP">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            حفظ
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultLat = 33.5138;
    const defaultLng = 36.2765;
    const lat = {{ $tower->lat ?? 'defaultLat' }};
    const lng = {{ $tower->lng ?? 'defaultLng' }};
    
    // Only initialize map if container exists
    if(document.getElementById('map')) {
        const map = L.map('map').setView([lat, lng], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
    
        let marker = null;
    
        @if($tower->lat && $tower->lng)
            marker = L.marker([{{ $tower->lat }}, {{ $tower->lng }}]).addTo(map);
            map.setView([{{ $tower->lat }}, {{ $tower->lng }}], 15);
        @endif
    
        map.on('click', function(e) {
            const { lat, lng } = e.latlng;
            
            document.querySelector('[name="lat"]').value = lat;
            document.querySelector('[name="lng"]').value = lng;
            
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker([lat, lng]).addTo(map);
        });
        
        // Fix map rendering issue in tabs
        setTimeout(function(){ map.invalidateSize(); }, 2000);
    }
});
</script>

    <!-- Add SSID Modal -->
    <div x-data="{ open: false, deviceId: null, deviceName: '' }" 
         @open-ssid-modal.window="open = true; deviceId = $event.detail.deviceId; deviceName = $event.detail.deviceName" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition.scale class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('network.towers.ssids.store', $tower) }}" method="POST">
                    @csrf
                    <input type="hidden" name="tower_device_id" :value="deviceId">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                إضافة SSID للجهاز: <span x-text="deviceName"></span>
                            </h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الشبكة (SSID) *</label>
                                <input type="text" name="ssid_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">التردد *</label>
                                    <select name="frequency" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="5GHz">5 GHz</option>
                                        <option value="2.4GHz">2.4 GHz</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                                    <input type="text" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" dir="ltr">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            حفظ
                        </button>
                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
