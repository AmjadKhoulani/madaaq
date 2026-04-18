@extends('layouts.admin')

@section('title', 'إضافة شريك جديد')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/css/intlTelInput.css">
<style>
    #map { height: 350px; width: 100%; border-radius: 8px; z-index: 1; }
    [x-cloak] { display: none !important; }
    .iti { width: 100%; border: none; }
    .iti__country-list { z-index: 50; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto" 
     x-data="clientForm({ 
        servers: {{ Js::from($servers) }}, 
        deviceModels: {{ Js::from($deviceModels) }},
        lastIp: '{{ $lastIp }}'
     })"
     x-cloak>
     
    <!-- Command Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight">بروتوكول تسجيل مشترك</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">New Node Provisioning</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('crm.clients.index') }}" class="px-6 py-2.5 bg-surface-container-low text-slate-500 font-bold rounded-lg text-sm border border-outline-variant/10 hover:bg-slate-200 transition-all">
                إلغاء العملية
            </a>
            <button type="submit" form="clientForm" class="px-8 py-2.5 bg-primary text-white font-bold rounded-lg text-sm shadow-lg shadow-primary/10 hover:scale-[1.02] transition-all">
                إتمام التسجيل
            </button>
        </div>
    </div>

    <form action="{{ route('crm.clients.store') }}" method="POST" id="clientForm">
        @csrf
        
        <div class="grid grid-cols-12 gap-8 items-start">
            
            <!-- Main Content Area -->
            <div class="col-span-12 lg:col-span-8 space-y-8">

                <!-- Section 1: Identity & Contact -->
                <div class="bg-surface-container-low p-8 rounded-lg border border-outline-variant/10">
                    <h3 class="text-xl font-black text-primary mb-8 flex items-center gap-3 italic">
                        <span class="w-1 h-6 bg-primary rounded-full"></span>
                        بيانات الهوية والاتصال
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">الاسم الكامل للمشترك</label>
                            <input type="text" name="name" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 focus:border-primary outline-none text-sm font-bold" required>
                        </div>

                        <div class="space-y-1.5" x-data="{ 
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
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">قناة الواتساب / الهاتف</label>
                            <div dir="ltr">
                                <input type="tel" x-ref="phoneInput" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 focus:border-primary outline-none text-sm font-manrope font-bold">
                                <input type="hidden" name="phone" id="hiddenPhone">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">البريد الإلكتروني (إشعارات)</label>
                            <input type="email" name="email" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 focus:border-primary outline-none text-sm font-bold">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">كلمة مرور بوابة العميل</label>
                            <input type="text" name="password" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 focus:border-primary outline-none text-sm font-bold" required value="{{ Str::random(8) }}">
                        </div>

                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">تفاصيل الموقع الجغرافي</label>
                            <input type="text" name="address" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg outline-none text-sm font-bold" placeholder="المدينة، المنطقة، اسم البناء">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Infrastructure Configuration -->
                <div class="bg-surface-container-low p-8 rounded-lg border border-outline-variant/10">
                    <h3 class="text-xl font-black text-primary mb-8 flex items-center gap-3 italic">
                        <span class="w-1 h-6 bg-secondary rounded-full"></span>
                        إعدادات بروتوكول الشبكة
                    </h3>
                    
                    <div class="space-y-8">
                        <!-- Mode Switcher -->
                        <div class="flex gap-2 p-1.5 bg-surface-container rounded-lg w-fit">
                            <button type="button" @click="connectionType = 'pppoe'" 
                                    class="px-8 py-2 rounded font-black text-[10px] uppercase tracking-widest transition-all"
                                    :class="connectionType === 'pppoe' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-primary'">
                                PPPoE Link
                            </button>
                            <button type="button" @click="connectionType = 'hotspot'" 
                                    class="px-8 py-2 rounded font-black text-[10px] uppercase tracking-widest transition-all"
                                    :class="connectionType === 'hotspot' ? 'bg-primary text-white shadow-md' : 'text-slate-500 hover:text-primary'">
                                Hotspot Portal
                            </button>
                            <input type="hidden" name="type" :value="connectionType">
                        </div>

                        <!-- PPPoE Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="connectionType === 'pppoe'">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">المركز الرئيسي (Edge Node)</label>
                                <select name="mikrotik_server_id" x-model="selectedServerId" @change="updateTowers()" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none border-r-4 border-r-primary">
                                    <option value="">-- اختر السيرفر --</option>
                                    <template x-for="server in servers" :key="server.id">
                                        <option :value="server.id" x-text="server.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">محطة البث (Tower / Field)</label>
                                <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none" :disabled="!selectedServerId">
                                    <option value="">-- اختر المحطة --</option>
                                    <template x-for="tower in towers" :key="tower.id">
                                        <option :value="tower.id" x-text="tower.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">تكنولوجيا الربط</label>
                                <select name="connection_mode" x-model="connectionMode" class="w-full px-4 py-3 bg-surface-container-highest/20 border border-outline-variant/20 rounded-lg text-sm font-black text-primary">
                                    <option value="wireless">Wireless (AirMax/LTU)</option>
                                    <option value="tower_switch">Direct Ethernet / LAN</option>
                                    <option value="fiber">Fiber Optic (GPON)</option>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">جهاز التوزيع (Distribution Unit)</label>
                                <select name="tower_device_id" x-model="selectedTowerDeviceId" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none" :disabled="!selectedTowerId">
                                    <option value="">-- اختر الجهاز --</option>
                                    <template x-for="device in towerDevices.filter(d => (connectionMode === 'wireless' ? d.type === 'wireless' : d.type === 'switch'))" :key="device.id">
                                        <option :value="device.id" x-text="device.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Hardware Deployment Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="connectionType === 'pppoe'">
                            <!-- CPE Module -->
                            <div class="bg-surface-container-lowest p-6 rounded border border-outline-variant/10 space-y-4">
                                <h4 class="text-[10px] font-black text-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">router</span>
                                    Interior Hub (CPE)
                                </h4>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="cpe_model" x-model="cpeModel" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold" placeholder="Model (e.g. hAP ax2)">
                                        <input type="text" name="cpe_ip" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-manrope font-bold" placeholder="IP Address">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="cpe_username" value="admin" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold" placeholder="Admin User">
                                        <input type="password" name="cpe_password" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold" placeholder="Terminal Pass">
                                    </div>
                                </div>
                            </div>

                            <!-- Radio Module -->
                            <div class="bg-surface-container-lowest p-6 rounded border border-outline-variant/10 space-y-4" x-show="connectionMode === 'wireless'">
                                <h4 class="text-[10px] font-black text-secondary uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">settings_input_antenna</span>
                                    Outdoor Radio (STA)
                                </h4>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="receiver_model" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold" placeholder="Model (e.g. LiteBeam)">
                                        <input type="text" name="receiver_ip" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-manrope font-bold" placeholder="Radio IP">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="receiver_username" value="admin" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold" placeholder="Radio User">
                                        <input type="password" name="receiver_password" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold" placeholder="Access Pass">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credentials Bar -->
                        <div class="p-6 bg-primary/5 rounded-lg grid grid-cols-1 md:grid-cols-2 gap-6 border-r-4 border-primary">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary uppercase tracking-widest font-headline italic">Service Login Username</label>
                                <input type="text" :name="connectionType === 'pppoe' ? 'pppoe_username' : 'hotspot_username'" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-black text-primary outline-none" required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary uppercase tracking-widest font-headline italic">Service Access Password</label>
                                <input type="text" name="service_password" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-black text-primary outline-none" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Billing & Tiers -->
                <div class="bg-surface-container-low p-8 rounded-lg border border-outline-variant/10">
                    <h3 class="text-xl font-black text-primary mb-8 flex items-center gap-3 italic">
                        <span class="w-1 h-6 bg-error rounded-full"></span>
                        المستوى المالي والتعاقد
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-3 space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">باقة الاشتراك</label>
                            <select name="package_id" x-model="selectedPackageId" @change="loadPackageDefaults()" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none">
                                <option value="">-- حساب مخصص (يدوي) --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            data-price="{{ $package->price }}" 
                                            data-limit="{{ $package->data_limit_mb }}"
                                            data-duration="{{ $package->duration }}">
                                        {{ $package->name }} ({{ number_format($package->price) }} SAR)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">السعة الشهرية (GB)</label>
                            <input type="number" name="data_limit" x-model="customDataLimit" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-bold outline-none" placeholder="Unlimited">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">سعر التجديد (SAR)</label>
                            <input type="number" step="0.01" name="price" x-model="customPrice" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-bold outline-none" placeholder="0.00">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">صلاحية الحساب (يوم)</label>
                            <input type="number" name="duration_days" x-model="durationDays" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-black text-primary outline-none">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Map Location -->
                <div class="bg-surface-container-low rounded-lg border border-outline-variant/10 overflow-hidden">
                    <button type="button" @click="showMap = !showMap" class="w-full p-6 flex justify-between items-center bg-surface-container-low hover:bg-slate-200 transition-all">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">location_on</span>
                            <span class="text-sm font-black text-primary italic">تثبيت الموقع الجغرافي للمحطة</span>
                        </div>
                        <span class="material-symbols-outlined transition-transform" :class="showMap ? 'rotate-180' : ''">keyboard_arrow_down</span>
                    </button>
                    <div x-show="showMap" x-collapse>
                        <div class="p-8 border-t border-outline-variant/10 space-y-4">
                            <div id="map"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="lat" x-model="lat" readonly class="px-4 py-2.5 bg-surface rounded text-xs font-manrope font-bold text-slate-400" placeholder="Latitude">
                                <input type="text" name="lng" x-model="lng" readonly class="px-4 py-2.5 bg-surface rounded text-xs font-manrope font-bold text-slate-400" placeholder="Longitude">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sticky Control Panel -->
            <div class="col-span-12 lg:col-span-4 lg:sticky lg:top-8 space-y-8">
                <div class="bg-primary p-8 rounded-lg text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 pointer-events-none">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 C 30 0 70 0 100 100 Z" fill="white" />
                        </svg>
                    </div>
                    
                    <div class="relative z-10 space-y-6">
                        <h3 class="text-xl font-black italic tracking-tighter">ملخص الإعداد الفوري</h3>
                        
                        <div class="space-y-4 border-y border-white/10 py-6">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-fixed opacity-70">قيمة الاشتراك</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-manrope font-black tracking-tight" x-text="customPrice || '0'"></span>
                                    <span class="text-xs font-bold text-primary-fixed">SAR</span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-fixed opacity-70">باي لود الخدمة</span>
                                <span class="text-xs font-black uppercase" x-text="packageName || 'حساب مخصص'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-fixed opacity-70">نوع الربط</span>
                                <span class="text-xs font-black uppercase" x-text="connectionType"></span>
                            </div>
                        </div>

                        <button type="submit" form="clientForm" class="w-full py-4 bg-white text-primary font-black rounded-lg text-[11px] uppercase tracking-[0.2em] shadow-lg hover:bg-slate-50 transition-all active:scale-95">
                            EXECUTE PROVISIONING
                        </button>
                    </div>
                </div>

                <!-- Alert Box -->
                <div class="bg-surface-container-low p-6 rounded-lg border border-primary/20 flex gap-4">
                    <span class="material-symbols-outlined text-primary">info</span>
                    <div>
                        <p class="text-[11px] font-black text-primary uppercase tracking-widest font-headline">تنبيه المزامنة</p>
                        <p class="text-[10px] text-slate-500 font-medium mt-1 leading-relaxed">سيتم إرسال بيانات الدخول آلياً عبر واتساب للمشترك فور إتمام العملية في حال تفعيل المديول.</p>
                    </div>
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
        Alpine.data('clientForm', (data) => ({
            connectionType: 'pppoe',
            connectionMode: 'wireless',
            servers: data.servers,
            selectedServerId: '',
            towers: [],
            selectedTowerId: '',
            towerDevices: [],
            selectedTowerDeviceId: '',
            ssids: [],
            selectedSSID: '',
            
            selectedPackageId: '',
            customPrice: '',
            packageName: '',
            customDataLimit: '',
            durationDays: '',
            
            deviceModels: data.deviceModels || [],
            selectedDeviceModelId: '',
            cpeModel: '',
            
            // Map
            showMap: false,
            map: null,
            marker: null,
            lat: '',
            lng: '',
            lastIp: data.lastIp || '',

            init() {
                this.$watch('showMap', (value) => {
                    if (value && !this.map) {
                        setTimeout(() => this.initMap(), 100);
                    }
                });
            },

            updateTowers() {
                this.selectedTowerId = '';
                this.ssids = [];
                const server = this.servers.find(s => s.id == this.selectedServerId);
                this.towers = server ? server.towers : []; 
            },
            updateTowerData() {
                const tower = this.towers.find(t => t.id == this.selectedTowerId);
                this.ssids = tower ? tower.ssids : [];
                this.towerDevices = tower ? tower.devices : [];
                this.selectedTowerDeviceId = '';
                if (tower && tower.lat && this.map) {
                     this.map.setView([tower.lat, tower.lng], 14);
                }
            },
            updateCpeModel() {
                const model = this.deviceModels.find(m => m.id == this.selectedDeviceModelId);
                if (model) {
                    this.cpeModel = model.name;
                }
            },
            loadPackageDefaults() {
                const select = document.querySelector('select[name="package_id"]');
                const option = select.options[select.selectedIndex];
                if (option.dataset.price) {
                    this.customPrice = option.dataset.price;
                    this.packageName = option.text.split('(')[0].trim();
                } else {
                    this.packageName = '';
                    this.customPrice = '';
                }

                if (option.dataset.limit) {
                    this.customDataLimit = (option.dataset.limit / 1024).toFixed(0);
                } else {
                    this.customDataLimit = '';
                }

                if (option.dataset.duration) {
                    this.durationDays = option.dataset.duration;
                } else {
                    this.durationDays = '';
                }
            },

            initMap() {
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
</script>
@endpush
@endsection
