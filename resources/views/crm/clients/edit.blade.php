@extends('layouts.admin')

@section('title', 'تعديل بروتوكول الشريك | ' . $client->username)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/css/intlTelInput.css">
<style>
    #map { height: 350px; width: 100%; border-radius: 8px; z-index: 1; }
    [x-cloak] { display: none !important; }
    .iti { width: 100%; border: none; }
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
     
    <!-- Command Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('crm.clients.show', $client) }}" class="w-11 h-11 rounded-lg bg-surface-container-low flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all border border-outline-variant/10 shadow-sm">
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
            <div>
                <h2 class="text-3xl font-black text-primary tracking-tight">تعديل بارامترات المشترك</h2>
                <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">Node Configuration Editor</p>
            </div>
        </div>
        <div class="flex gap-3">
            @if($client->status === 'active')
                <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST" onsubmit="return confirm('تأكيد تعليق الخدمة لهذا المشترك؟')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-6 py-2.5 bg-error-container text-on-error-container font-black text-[10px] uppercase tracking-widest rounded-lg transition-all border border-outline-variant/10">
                        Suspend Connection
                    </button>
                </form>
            @else
                <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-6 py-2.5 bg-secondary-container text-on-secondary-container font-black text-[10px] uppercase tracking-widest rounded-lg transition-all border border-outline-variant/10">
                        Restore Access
                    </button>
                </form>
            @endif
            <button type="submit" form="clientForm" class="px-8 py-2.5 bg-primary text-white font-bold rounded-lg text-sm shadow-lg shadow-primary/10 hover:scale-[1.02] transition-all">
                مزامنة التعديلات
            </button>
        </div>
    </div>

    <!-- Integrity Dashboard -->
    @if ($errors->any())
        <div class="bg-error-container/20 border-r-4 border-error p-6 mb-8 rounded-lg">
            <div class="flex items-start gap-4 text-error">
                <span class="material-symbols-outlined">report</span>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-tight mb-2">تنبيه المزامنة: فشل في معالجة المدخلات</h3>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs font-bold leading-relaxed flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-error"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('crm.clients.update', $client) }}" method="POST" id="clientForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-12 gap-8 items-start">
            
            <!-- Main Content Area -->
            <div class="col-span-12 lg:col-span-8 space-y-8">

                <!-- Section 1: Identity -->
                <div class="bg-surface-container-low p-8 rounded-lg border border-outline-variant/10">
                    <h3 class="text-xl font-black text-primary mb-8 flex items-center gap-3 italic">
                        <span class="w-1 h-6 bg-primary rounded-full"></span>
                        الهوية والاتصال
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">الاسم الكامل</label>
                            <input type="text" name="name" x-model="name" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 focus:border-primary outline-none text-sm font-bold" required>
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
                                    if(this.phone) this.iti.setNumber(this.phone);
                                    input.addEventListener('input', () => {
                                        document.getElementById('hiddenPhone').value = this.iti.getNumber();
                                    });
                                }
                            }
                        }" x-init="initPhone()">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">رقم الهاتف</label>
                            <div dir="ltr">
                                <input type="tel" x-ref="phoneInput" x-model="phone" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 focus:border-primary outline-none text-sm font-manrope font-bold">
                                <input type="hidden" name="phone" id="hiddenPhone" :value="phone">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">كلمة مرور البوابة (تحديث)</label>
                            <input type="text" name="password" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none" placeholder="اترك فارغاً لعدم التغيير">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $client->email) }}" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none">
                        </div>

                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 border-r-2 border-primary/20 pr-2 uppercase tracking-widest font-headline">العنوان</label>
                            <input type="text" name="address" value="{{ old('address', $client->address) }}" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Infrastructure Configuration -->
                <div class="bg-surface-container-low p-8 rounded-lg border border-outline-variant/10">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-primary italic flex items-center gap-3">
                            <span class="w-1 h-6 bg-secondary rounded-full"></span>
                            إعدادات بروتوكول الشبكة
                        </h3>
                        <span class="px-3 py-1 bg-surface-container rounded text-[9px] font-black text-slate-400 uppercase tracking-widest" x-text="connectionType"></span>
                    </div>

                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-show="connectionType === 'pppoe'">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">سيرفر التحكم</label>
                                <select name="mikrotik_server_id" x-model="selectedServerId" @change="updateTowers()" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none border-r-4 border-r-primary">
                                    <option value="">-- اختر السيرفر --</option>
                                    <template x-for="server in servers" :key="server.id">
                                        <option :value="server.id" x-text="server.name" :selected="server.id == selectedServerId"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">الموقع الجغرافي</label>
                                <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none" :disabled="!selectedServerId">
                                    <template x-for="tower in towers" :key="tower.id">
                                        <option :value="tower.id" x-text="tower.name" :selected="tower.id == selectedTowerId"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">تكنولوجيا الربط</label>
                                <select name="connection_mode" x-model="connectionMode" class="w-full px-4 py-3 bg-surface-container-highest/20 border border-outline-variant/20 rounded-lg text-sm font-black text-primary outline-none">
                                    <option value="wireless">Wireless Station</option>
                                    <option value="tower_switch">Direct Ethernet</option>
                                    <option value="fiber">Fiber GPON</option>
                                </select>
                            </div>
                        </div>

                        <!-- Hardware Units -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="connectionType === 'pppoe'">
                            <!-- CPE -->
                            <div class="bg-surface-container-lowest p-6 rounded border border-outline-variant/10 space-y-4">
                                <h4 class="text-[10px] font-black text-primary uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">router</span>
                                    Home Module (CPE)
                                </h4>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="cpe_model" x-model="cpeModel" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold font-manrope">
                                        <input type="text" name="cpe_ip" value="{{ old('cpe_ip', $client->cpe_ip) }}" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-manrope font-bold text-primary">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="cpe_username" value="{{ old('cpe_username', $client->cpe_username) }}" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold">
                                        <input type="password" name="cpe_password" value="{{ old('cpe_password', $client->cpe_password) }}" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold">
                                    </div>
                                    <input type="text" name="cpe_mac" value="{{ old('cpe_mac', $client->cpe_mac) }}" class="w-full px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-manrope font-bold text-slate-500" placeholder="MAC Identity">
                                </div>
                            </div>

                            <!-- Radio -->
                            <div class="bg-surface-container-lowest p-6 rounded border border-outline-variant/10 space-y-4" x-show="connectionMode === 'wireless'">
                                <h4 class="text-[10px] font-black text-secondary uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">settings_input_antenna</span>
                                    Radio Unit (STA)
                                </h4>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="receiver_model" value="{{ old('receiver_model', $client->receiver_model) }}" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold">
                                        <input type="text" name="receiver_ip" value="{{ old('receiver_ip', $client->receiver_ip) }}" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-manrope font-bold text-secondary">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" name="receiver_username" value="{{ old('receiver_username', $client->receiver_username) }}" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold">
                                        <input type="password" name="receiver_password" value="{{ old('receiver_password', $client->receiver_password) }}" class="px-4 py-2 bg-white border border-outline-variant/10 rounded text-xs font-bold">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Bar -->
                        <div class="p-6 bg-primary/5 rounded-lg border-r-4 border-primary grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary uppercase tracking-widest font-headline">اسم مستخدم الخدمة</label>
                                <input type="text" :value="username" class="w-full px-4 py-3 bg-white/50 border border-outline-variant/10 rounded-lg text-sm font-manrope font-black text-slate-400 cursor-not-allowed" readonly>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-primary uppercase tracking-widest font-headline">كلمة مرور الخدمة (تحديث)</label>
                                <input type="text" name="service_password" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-black text-primary outline-none" placeholder="Keep blank to retain active key">
                                <p class="text-[9px] font-black text-slate-400 uppercase mt-1 italic">Active: <span class="text-primary">{{ $client->service_password }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Billing -->
                <div class="bg-surface-container-low p-8 rounded-lg border border-outline-variant/10">
                    <h3 class="text-xl font-black text-primary mb-8 flex items-center gap-3 italic">
                        <span class="w-1 h-6 bg-error rounded-full"></span>
                        المستوى المالي والباقة
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">تعديل باقة الخدمة</label>
                            <select name="package_id" x-model="selectedPackageId" @change="loadPackageDefaults()" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-bold outline-none">
                                <option value="">-- حساب مخصص --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            data-price="{{ $package->price }}" 
                                            data-limit="{{ $package->data_limit_mb }}">
                                        {{ $package->name }} - {{ number_format($package->price) }} SAR
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">السعر المخصص (SAR)</label>
                            <input type="number" step="0.01" name="price" x-model="customPrice" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-bold outline-none text-primary">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">الحصة الشهرية (GB)</label>
                            <input type="number" name="data_limit" x-model="customDataLimit" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-bold outline-none">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sticky Summary Card -->
            <div class="col-span-12 lg:col-span-4 lg:sticky lg:top-8 space-y-8">
                <div class="bg-primary p-8 rounded-lg text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 pointer-events-none">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 C 30 0 70 0 100 100 Z" fill="white" />
                        </svg>
                    </div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-black italic tracking-tighter">الحالة التشغيلية</h3>
                            <span class="px-3 py-1 bg-white/20 rounded text-[9px] font-black uppercase" x-text="client.status"></span>
                        </div>
                        
                        <div class="space-y-4 border-y border-white/10 py-6">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-fixed opacity-70">إجمالي التعاقد</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-manrope font-black tracking-tight" x-text="customPrice || '0.00'"></span>
                                    <span class="text-xs font-bold text-primary-fixed">SAR</span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-fixed opacity-70">نهاية الصلاحية</span>
                                <span class="text-xs font-manrope font-black">{{ $client->expires_at ? $client->expires_at->format('Y/m/d') : 'دائم' }}</span>
                            </div>
                        </div>

                        <button type="submit" form="clientForm" class="w-full py-4 bg-white text-primary font-black rounded-lg text-[11px] uppercase tracking-[0.2em] shadow-lg hover:bg-slate-50 transition-all active:scale-95">
                            SYNC CLIENT STATE
                        </button>
                    </div>
                </div>

                <!-- Warnings -->
                @if($client->status === 'suspended')
                    <div class="bg-error-container p-6 rounded-lg border border-error/20 flex gap-4 text-on-error-container">
                        <span class="material-symbols-outlined">warning</span>
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-widest font-headline">تنبيه الحساب</p>
                            <p class="text-[10px] font-medium mt-1 leading-relaxed">الخدمة معطلة حالياً لهذا المشترك. أي تعديل سيبقى قيد الاحتفاظ حتى يتم تفعيل الحساب.</p>
                        </div>
                    </div>
                @endif
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
            connectionMode: data.client.connection_mode || 'wireless',
            
            servers: data.servers,
            selectedServerId: data.client.mikrotik_server_id,
            towers: [],
            selectedTowerId: data.client.tower_id,
            deviceModels: data.deviceModels || [],
            selectedDeviceModelId: data.client.device_model_id || '',
            cpeModel: data.client.cpe_model || '',
            towerDevices: [],
            selectedTowerDeviceId: data.client.tower_device_id || '',
            ssids: [],
            selectedSSID: data.client.ssid_id,
            
            selectedPackageId: data.client.package_id,
            customPrice: data.client.custom_price || (data.client.package ? data.client.package.price : ''),
            customDataLimit: data.client.custom_data_limit_mb ? (data.client.custom_data_limit_mb / 1024) : 
                             (data.client.package ? (data.client.package.data_limit_mb / 1024) : ''),
            
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
                const oldTower = this.selectedTowerId;
                const server = this.servers.find(s => s.id == this.selectedServerId);
                this.towers = server ? server.towers : []; 
                if (!this.towers.find(t => t.id == oldTower)) {
                    this.selectedTowerId = '';
                }
            },
            updateTowerData() {
                const tower = this.towers.find(t => t.id == this.selectedTowerId);
                this.ssids = tower ? tower.ssids : [];
                this.towerDevices = tower ? tower.devices || [] : [];
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
            updateCpeModel() {
                const model = this.deviceModels.find(m => m.id == this.selectedDeviceModelId);
                if (model) {
                    this.cpeModel = model.name;
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
