@extends('layouts.admin')

@section('title', 'إضافة شريك جديد | Identity Synthesis Protocol')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/css/intlTelInput.css">
<style>
    #map { height: 350px; width: 100%; border-radius: 2.5rem; z-index: 1; filter: grayscale(1) invert(1) contrast(1.2) brightness(0.9); }
    [x-cloak] { display: none !important; }
    .iti { width: 100%; border: none; }
    .iti__country-list { z-index: 100; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px); border-radius: 1.5rem; border: 1px solid rgba(0,0,0,0.05); }
    .iti__flag-container { border-radius: 1rem 0 0 1rem; }
</style>
@endpush

@section('content')
<div class="space-y-12 pb-24" 
     x-data="clientForm({ 
        servers: {{ Js::from($servers) }}, 
        deviceModels: {{ Js::from($deviceModels) }},
        lastIp: '{{ $lastIp }}'
     })"
     x-cloak>
     
    <!-- Radiant Hub Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-10">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-12 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">New Node Provisioning</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">بروتوكول تركيب مشترك جديد</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Synthesizing Network Identity & Physical Distribution Parameters</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('crm.clients.index') }}" class="px-8 py-4 bg-white border border-slate-200 text-slate-500 font-black rounded-2xl text-[10px] uppercase tracking-[0.2em] transition-all hover:bg-slate-50 italic">
                إلغاء العملية
            </a>
            <button type="submit" form="clientForm" class="px-10 py-4 bg-slate-900 text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.2em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center gap-4 italic group">
                <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform duration-500">terminal</span>
                إتمام التسجيل والتركيب
            </button>
        </div>
    </div>

    <form action="{{ route('crm.clients.store') }}" method="POST" id="clientForm">
        @csrf
        
        <div class="grid grid-cols-12 gap-10 items-start">
            
            <!-- Matrix Content Area -->
            <div class="col-span-12 lg:col-span-8 space-y-10">

                <!-- Section 1: Identity Matrix -->
                <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                    <div class="flex items-center gap-6 mb-10">
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-inner border border-primary/20">
                            <span class="material-symbols-outlined">fingerprint</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">بيانات الهوية والاتصال المعتمدة</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">الاسم الكامل (Subscriber Name)</label>
                            <input type="text" name="name" class="input-radiant !py-4 text-[11px] font-black uppercase italic" required>
                        </div>

                        <div class="space-y-3" x-data="{ 
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
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">قناة التواصل (WhatsApp / Tel)</label>
                            <div dir="ltr" class="relative">
                                <input type="tel" x-ref="phoneInput" class="input-radiant !py-4 pr-16 text-[12px] font-manrope font-black tracking-widest">
                                <input type="hidden" name="phone" id="hiddenPhone">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">البريد الإلكتروني (Email Index)</label>
                            <input type="email" name="email" class="input-radiant !py-4 text-[11px] font-black italic">
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">كلمة مرور البوابة (Portal Access Key)</label>
                            <div class="relative">
                                <input type="text" name="password" class="input-radiant !py-4 pr-12 text-[11px] font-black uppercase italic" required value="{{ Str::random(10) }}">
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300">vpn_key</span>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">تفاصيل الموقع الفيزيائي (Physical Address)</label>
                            <input type="text" name="address" class="input-radiant !py-4 text-[11px] font-black italic" placeholder="City Node, Street Cluster, Terminal Point">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Infrastructure Topology -->
                <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                    <div class="flex items-center gap-6 mb-10">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-inner border border-emerald-500/20">
                            <span class="material-symbols-outlined">router</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">إعدادات بروتوكول واتجاهات الشبكة</h3>
                    </div>
                    
                    <div class="space-y-10">
                        <!-- Mode Switcher Pills -->
                        <div class="flex gap-2 p-2 bg-slate-900 rounded-[2rem] w-fit shadow-glow-purple/20">
                            <button type="button" @click="connectionType = 'pppoe'" 
                                    class="px-10 py-3 rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] transition-all italic"
                                    :class="connectionType === 'pppoe' ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-500 hover:text-white'">
                                Broadband (PPPoE)
                            </button>
                            <button type="button" @click="connectionType = 'hotspot'" 
                                    class="px-10 py-3 rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] transition-all italic"
                                    :class="connectionType === 'hotspot' ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-500 hover:text-white'">
                                Identity (Hotspot)
                            </button>
                            <input type="hidden" name="type" :value="connectionType">
                        </div>

                        <!-- Hub Connectivity Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">المركز الرئيسي (Edge Node)</label>
                                <select name="mikrotik_server_id" x-model="selectedServerId" @change="updateTowers()" class="input-radiant !py-4 text-[11px] font-black uppercase italic appearance-none !bg-white">
                                    <option value="">-- SELECT ACCESS SERVER --</option>
                                    <template x-for="server in servers" :key="server.id">
                                        <option :value="server.id" x-text="server.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">محطة التوزيع (Tower Field)</label>
                                <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="input-radiant !py-4 text-[11px] font-black uppercase italic appearance-none !bg-white" :disabled="!selectedServerId">
                                    <option value="">-- SELECT DISTRIBUTION TOWER --</option>
                                    <template x-for="tower in towers" :key="tower.id">
                                        <option :value="tower.id" x-text="tower.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="space-y-3" x-show="connectionType === 'pppoe'">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">تكنولوجيا الاتصال (Link Protocol)</label>
                                <select name="connection_mode" x-model="connectionMode" class="input-radiant !py-4 text-[11px] font-black uppercase italic appearance-none !bg-slate-900 !text-neon-cyan shadow-glow-cyan/20">
                                    <option value="wireless">RF Wireless Link (AirMax)</option>
                                    <option value="tower_switch">Direct Ethernet Switch</option>
                                    <option value="fiber">Active Fiber Optic (PON)</option>
                                </select>
                            </div>

                            <div class="space-y-3" x-show="connectionType === 'pppoe'">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">وحدة التوزيع (Access Device)</label>
                                <select name="tower_device_id" x-model="selectedTowerDeviceId" class="input-radiant !py-4 text-[11px] font-black uppercase italic appearance-none !bg-white" :disabled="!selectedTowerId">
                                    <option value="">-- SELECT TOWER DEVICE --</option>
                                    <template x-for="device in towerDevices.filter(d => (connectionMode === 'wireless' ? d.type === 'wireless' : d.type === 'switch'))" :key="device.id">
                                        <option :value="device.id" x-text="device.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Hardware Deployment Shards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10" x-show="connectionType === 'pppoe'">
                            <!-- CPE Shard -->
                            <div class="glass-card p-10 rounded-[2.5rem] space-y-8 bg-white border-slate-100">
                                <div class="flex items-center gap-4">
                                    <span class="material-symbols-outlined text-primary text-2xl">modem</span>
                                    <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.2em] italic">Interior Node (CPE)</h4>
                                </div>
                                <div class="grid grid-cols-1 gap-6">
                                    <input type="text" name="cpe_model" x-model="cpeModel" class="input-radiant !py-3 text-[11px] font-black italic" placeholder="CPE MODEL SHARD">
                                    <input type="text" name="cpe_ip" class="input-radiant !py-3 text-[11px] font-manrope font-black tracking-widest" placeholder="CPE IP ENDPOINT">
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="text" name="cpe_username" value="admin" class="input-radiant !py-3 text-[10px] font-black italic" placeholder="ADMIN USER">
                                        <input type="password" name="cpe_password" class="input-radiant !py-3 text-[10px] font-black italic" placeholder="SEC PASS">
                                    </div>
                                </div>
                            </div>

                            <!-- Radio Shard -->
                            <div class="glass-card p-10 rounded-[2.5rem] space-y-8 bg-white border-slate-100" x-show="connectionMode === 'wireless'">
                                <div class="flex items-center gap-4 text-emerald-600">
                                    <span class="material-symbols-outlined text-2xl">wifi_tethering</span>
                                    <h4 class="text-[11px] font-black uppercase tracking-[0.2em] italic">RF Access (STA)</h4>
                                </div>
                                <div class="grid grid-cols-1 gap-6">
                                    <input type="text" name="receiver_model" class="input-radiant !py-3 text-[11px] font-black italic" placeholder="RF UNIT ARCHITECTURE">
                                    <input type="text" name="receiver_ip" class="input-radiant !py-3 text-[11px] font-manrope font-black tracking-widest" placeholder="BACKHAUL IP">
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="text" name="receiver_username" value="admin" class="input-radiant !py-3 text-[10px] font-black italic" placeholder="UNIT ADMIN">
                                        <input type="password" name="receiver_password" class="input-radiant !py-3 text-[10px] font-black italic" placeholder="ACCESS KEY">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- High-Intensity Credentials Matrix -->
                        <div class="p-10 bg-slate-900 rounded-[3rem] grid grid-cols-1 md:grid-cols-2 gap-10 border border-white/5 shadow-neon-purple/20">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-neon-cyan uppercase tracking-[0.3em] pr-4 italic">Protocol Access Identity</label>
                                <input type="text" :name="connectionType === 'pppoe' ? 'pppoe_username' : 'hotspot_username'" class="input-radiant !py-4 pr-12 text-[13px] font-manrope font-black text-white bg-white/5 border-white/10 italic tracking-widest uppercase focus:border-neon-cyan shadow-glow-cyan/5" required>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-vibrant-purple uppercase tracking-[0.3em] pr-4 italic">Auth Token Protocol</label>
                                <input type="text" name="service_password" class="input-radiant !py-4 pr-12 text-[13px] font-manrope font-black text-white bg-white/5 border-white/10 italic tracking-widest uppercase focus:border-vibrant-purple shadow-glow-purple/5" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Fiscal Architecture -->
                <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                    <div class="flex items-center gap-6 mb-10">
                        <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all shadow-inner border border-rose-500/20">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">النموذج المالي والتعاقدي للمشترك</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        <div class="lg:col-span-3 space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">فئة الاشتراك المركزية (Service Tier)</label>
                            <select name="package_id" x-model="selectedPackageId" @change="loadPackageDefaults()" class="input-radiant !py-4 text-[11px] font-black uppercase italic appearance-none !bg-white">
                                <option value="">-- CUSTOM PROTOCOL (MANUAL) --</option>
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

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">سعة التدفق (Temporal Data GB)</label>
                            <input type="number" name="data_limit" x-model="customDataLimit" class="input-radiant !py-4 text-[12px] font-black font-manrope" placeholder="∞">
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">تكلفة الوحدة (Unit Cost SAR)</label>
                            <input type="number" step="0.01" name="price" x-model="customPrice" class="input-radiant !py-4 text-[12px] font-black font-manrope" placeholder="0.00">
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">صلاحية النبضة (Pulse Duration Days)</label>
                            <input type="number" name="duration_days" x-model="durationDays" class="input-radiant !py-4 text-[12px] font-black font-manrope text-primary">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Physical Geospatial Node -->
                <div class="glass-panel p-0 rounded-[3.5rem] !bg-white/80 border-slate-100 overflow-hidden group shadow-2xl">
                    <button type="button" @click="showMap = !showMap" class="w-full p-10 flex justify-between items-center bg-white/60 hover:bg-slate-900 hover:text-white transition-all duration-500">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                                <span class="material-symbols-outlined text-3xl">location_on</span>
                            </div>
                            <div class="text-right">
                                <h3 class="text-xl font-black uppercase italic tracking-tighter">الموقع الجغرافي للمحطة</h3>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40 group-hover:opacity-100 transition-opacity">Geospatial Station Point Mapping</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-3xl transition-transform duration-700" :class="showMap ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="showMap" x-collapse>
                        <div class="p-10 space-y-8">
                            <div id="map" class="shadow-glow-purple/20"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic pr-4">Latitude Shard</label>
                                    <input type="text" name="lat" x-model="lat" readonly class="input-radiant border-dashed !bg-slate-50 text-slate-500 text-[11px] font-manrope font-black">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic pr-4">Longitude Shard</label>
                                    <input type="text" name="lng" x-model="lng" readonly class="input-radiant border-dashed !bg-slate-50 text-slate-500 text-[11px] font-manrope font-black">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Radiant Sticky Summary Control -->
            <div class="col-span-12 lg:col-span-4 lg:sticky lg:top-24 space-y-10">
                <div class="glass-panel !bg-slate-900 p-10 rounded-[3rem] text-white shadow-2xl relative overflow-hidden group animate-enter">
                    <div class="absolute inset-0 opacity-10 pointer-events-none">
                         <div class="absolute -top-24 -right-24 w-64 h-64 bg-accent-flow rounded-full blur-3xl animate-pulse"></div>
                    </div>
                    
                    <div class="relative z-10 space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-10 h-1 bg-neon-cyan rounded-full"></span>
                            <h3 class="text-xl font-black italic tracking-tighter uppercase">Provisioning Summary</h3>
                        </div>
                        
                        <div class="space-y-6 border-y border-white/10 py-8">
                            <div class="flex justify-between items-center">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Protocol Cost</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-manrope font-black tracking-tighter text-neon-cyan" x-text="customPrice || '0'"></span>
                                    <span class="text-[10px] font-black text-slate-500 uppercase">SAR</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Service Tier</p>
                                <span class="text-[11px] font-black text-white italic uppercase tracking-tighter" x-text="packageName || 'Custom Pulse'"></span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Access Method</p>
                                <span class="text-[11px] font-black text-white italic uppercase tracking-tighter underline decoration-primary decoration-2 underline-offset-4" x-text="connectionType"></span>
                            </div>
                        </div>

                        <button type="submit" form="clientForm" class="w-full py-5 bg-accent-flow text-white font-black rounded-2xl text-[12px] uppercase tracking-[0.3em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center justify-center gap-4 italic group">
                            <span class="material-symbols-outlined text-xl group-hover:translate-x-[-10px] transition-transform">rocket_launch</span>
                            EXECUTE SYNTHESIS
                        </button>
                    </div>
                </div>

                <!-- Atmospheric Alert Shard -->
                <div class="glass-panel p-8 rounded-[2.5rem] !bg-emerald-500/10 border-emerald-500/20 flex gap-6 italic group">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-emerald-600 shadow-md group-hover:rotate-12 transition-transform">
                        <span class="material-symbols-outlined text-2xl">sensors</span>
                    </div>
                    <div class="flex-1 space-y-1">
                        <p class="text-[11px] font-black text-emerald-600 uppercase tracking-[0.2em]">Automated Handshake</p>
                        <p class="text-[10px] text-emerald-700 font-bold leading-relaxed opacity-70">Client access credentials will be automatically transmitted via WhatsApp protocol upon matrix commitment.</p>
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
                L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(this.map);
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
