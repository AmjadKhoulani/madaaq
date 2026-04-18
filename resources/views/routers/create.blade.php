@extends('layouts.admin')

@section('title', 'تهيئة جهاز شبكة جديد | Initialize Network Asset')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #locationMap { height: 400px; width: 100%; border-radius: 8px; z-index: 1; border: 1px solid rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-20" x-data="routerForm()">
    <!-- Strategic Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight italic uppercase">تهيئة سجل جهاز جديد</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">Provisioning Peripheral Routing & Spectral Distribution Hardware</p>
        </div>
        <a href="{{ route('routers.index') }}" class="px-6 py-2 border border-outline-variant/20 rounded text-slate-500 font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm text-slate-400">arrow_back</span>
            إلغاء العملية
        </a>
    </div>

    <!-- Error Matrix -->
    @if($errors->any())
    <div class="bg-error-container text-on-error-container p-6 rounded-lg border border-error/10">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-error rounded flex items-center justify-center text-white shadow-lg shrink-0">
                <span class="material-symbols-outlined text-lg font-light">warning</span>
            </div>
            <div>
                <p class="font-black text-sm uppercase tracking-tight mb-2">Protocol Conflict Detected</p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-[10px] font-bold uppercase tracking-wide flex items-center gap-2 italic">
                        <span class="w-1 h-3 bg-error/50 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('routers.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Technical Blueprint Controls -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- 1. Asset Identity -->
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-10 h-10 bg-primary/5 rounded border border-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-2xl font-light">developer_board</span>
                        </div>
                        <h3 class="text-xl font-black text-primary tracking-tight uppercase italic">Asset Identity</h3>
                    </div>
                    
                    <!-- Search Registry -->
                    <div class="mb-8 relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Hardware Global Discovery Search</label>
                        <div class="relative">
                            <input type="text" x-model="deviceQuery" @input.debounce.300ms="searchDevices()" @click.away="showDevices = false"
                                   class="w-full pl-12 pr-6 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-4 focus:ring-primary/5 focus:border-primary/40 font-bold text-primary transition-all shadow-sm" 
                                   placeholder="Search Registry... (e.g. LiteBeam, hAP lite, EdgeRouter)"
                                   autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-lg">search</span>
                            </div>
                        </div>
                        <input type="hidden" name="model_id" :value="selectedDevice ? selectedDevice.id : ''">
                        
                        <!-- Overlay Selector -->
                        <div x-show="showDevices && devices.length > 0" x-cloak x-transition:enter="duration-200"
                             class="absolute z-50 w-full mt-2 bg-white border border-outline-variant/20 rounded shadow-2xl max-h-80 overflow-y-auto p-2 space-y-1">
                            <template x-for="device in devices" :key="device.id">
                                <div @click="selectDevice(device)" class="flex items-center gap-4 p-3 hover:bg-primary hover:text-white rounded cursor-pointer transition-all">
                                    <div class="w-12 h-12 bg-surface-container-lowest rounded p-1 flex items-center justify-center shrink-0 border border-outline-variant/10">
                                        <img :src="device.image_url" class="max-w-full max-h-full object-contain" onerror="this.src='https://placehold.co/100x100?text=HW'">
                                    </div>
                                    <div>
                                        <p class="font-black text-xs tracking-tight italic" x-text="device.manufacturer + ' ' + device.model_name" dir="ltr"></p>
                                        <p class="text-[9px] font-bold uppercase tracking-widest opacity-60" x-text="device.device_type"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Operational Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="routerName" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-4 focus:ring-primary/5 focus:border-primary/40 font-black text-primary transition-all shadow-sm" required dir="ltr">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Functional Category <span class="text-rose-500">*</span></label>
                            <select name="device_type" x-model="deviceType" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-4 focus:ring-primary/5 focus:border-primary/40 font-black text-primary transition-all shadow-sm italic">
                                <option value="router">Gateway Node (Router)</option>
                                <option value="switch">Matrix Core (Switch)</option>
                                <option value="access_point">Spectral Hub (Access Point)</option>
                                <option value="base_station">Transmission Array (Base Station)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Antenna Specs -->
                    <div x-show="['access_point', 'base_station'].includes(deviceType)" x-transition class="mt-8 p-6 bg-primary/5 rounded border border-primary/10 space-y-4">
                        <label class="block text-[10px] font-black text-primary uppercase tracking-widest italic">Spectral Radiation Profile (Antenna)</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="antenna_type" value="sector" class="text-primary focus:ring-primary">
                                <span class="text-[10px] font-black uppercase text-slate-600 group-hover:text-primary transition-all italic">Sector Axis</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="antenna_type" value="omni" class="text-primary focus:ring-primary">
                                <span class="text-[10px] font-black uppercase text-slate-600 group-hover:text-primary transition-all italic">Omni Sphere</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="antenna_type" value="dish" class="text-primary focus:ring-primary">
                                <span class="text-[10px] font-black uppercase text-slate-600 group-hover:text-primary transition-all italic">Directional Dish</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 2. Connectivity Stack -->
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-10 h-10 bg-secondary/10 rounded border border-secondary/10 flex items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-2xl font-light">rss_feed</span>
                        </div>
                        <h3 class="text-xl font-black text-primary tracking-tight uppercase italic">Connectivity Protocol</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Operational IP <span class="text-rose-500">*</span></label>
                            <input type="text" name="ip" x-model="ip" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary shadow-sm" placeholder="192.168.x.x" required dir="ltr">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">API Governance Port</label>
                            <input type="number" name="api_port" value="8728" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary shadow-sm" dir="ltr">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Identity Access ID (User)</label>
                            <input type="text" name="username" value="admin" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary shadow-sm" dir="ltr">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Access Protocol Secret <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary shadow-sm" required dir="ltr">
                        </div>
                    </div>
                </div>

                <!-- 3. Geospatial Topology -->
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg overflow-hidden">
                    <button type="button" @click="showMap = !showMap" class="w-full p-10 flex items-center justify-between hover:bg-slate-50/50 transition-all">
                        <div class="flex items-center gap-4 text-primary">
                            <span class="material-symbols-outlined text-2xl font-light">location_on</span>
                            <h3 class="text-xl font-black tracking-tight uppercase italic">Geospatial Topology Registry</h3>
                        </div>
                        <span class="material-symbols-outlined text-slate-400 transform transition-transform" :class="showMap ? 'rotate-180' : ''">keyboard_arrow_down</span>
                    </button>
                    
                    <div x-show="showMap" x-collapse>
                        <div class="px-10 pb-10 space-y-8">
                            <!-- Site Calibration -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-outline-variant/5 pt-8">
                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Structural Parent Synchronization (Tower)</label>
                                    <select @change="selectTower($event)" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary italic">
                                        <option value="">-- Manual Plotting --</option>
                                        @foreach($towers as $tower)
                                        <option value="{{ $tower->id }}" data-lat="{{ $tower->lat }}" data-lng="{{ $tower->lng }}">
                                            🗼 {{ $tower->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex gap-4 items-end">
                                    <div class="flex-1 space-y-3">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">N-LAT</label>
                                        <input type="text" name="lat" x-model="lat" class="w-full px-4 py-3 bg-surface-container-highest/10 border-none rounded text-[11px] font-manrope font-black text-primary text-center ring-1 ring-outline-variant/10" readonly>
                                    </div>
                                    <div class="flex-1 space-y-3">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">E-LNG</label>
                                        <input type="text" name="lng" x-model="lng" class="w-full px-4 py-3 bg-surface-container-highest/10 border-none rounded text-[11px] font-manrope font-black text-primary text-center ring-1 ring-outline-variant/10" readonly>
                                    </div>
                                </div>
                            </div>

                            <div id="locationMap" class="group relative">
                                <div class="absolute bottom-4 left-4 z-[10] bg-white/80 backdrop-blur-md px-4 py-2 rounded border border-outline-variant/10 shadow-sm opacity-0 group-hover:opacity-100 transition-all pointer-events-none">
                                    <p class="text-[8px] font-black text-primary italic uppercase tracking-widest">Global Mapping Engine v3.1 | Click to Registry</p>
                                </div>
                            </div>
                            
                            <!-- Coverage Matrix -->
                            <div class="grid grid-cols-3 gap-6 pt-4 border-t border-outline-variant/5">
                                <div class="space-y-2">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Effective Range (m)</label>
                                    <input type="number" name="coverage_radius" x-model="coverageRadius" @input="updateCoverageCircle()" class="w-full px-4 py-2 bg-white border border-outline-variant/10 rounded focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary text-xs">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Azimuth Index (0-360)</label>
                                    <input type="number" name="azimuth" x-model="azimuth" @input="updateCoverageCircle()" class="w-full px-4 py-2 bg-white border border-outline-variant/10 rounded focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary text-xs">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Beamwidth Aperture</label>
                                    <input type="number" name="beam_width" x-model="beamWidth" @input="updateCoverageCircle()" class="w-full px-4 py-2 bg-white border border-outline-variant/10 rounded focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Static Handshake Preview -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg overflow-hidden shadow-2xl">
                        <!-- Technical Profile Image -->
                        <div class="h-40 bg-white flex items-center justify-center p-6 border-b border-outline-variant/10">
                             <img :src="selectedDevice ? selectedDevice.image_url : 'https://placehold.co/150x150?text=Registry'" 
                                  class="max-h-full max-w-full object-contain filter drop-shadow-lg" :class="!selectedDevice ? 'opacity-20 grayscale' : ''">
                        </div>
                        
                        <div class="p-8 space-y-8">
                            <div class="text-center">
                                <h3 class="text-lg font-black text-primary uppercase italic tracking-tight mb-2" x-text="routerName || 'Initialize Asset Identification'"></h3>
                                <div class="flex items-center justify-center gap-2">
                                    <span class="px-3 py-1 rounded bg-surface-container-highest/10 border border-outline-variant/10 font-manrope font-black text-[10px] text-slate-500 italic tracking-widest" x-text="ip || 'Await Deployment IP'"></span>
                                    <span class="px-3 py-1 rounded font-black text-[9px] uppercase tracking-widest text-white shadow-sm" 
                                          :class="{
                                              'bg-primary': deviceType === 'router',
                                              'bg-secondary': deviceType === 'access_point',
                                              'bg-slate-700': deviceType === 'switch',
                                              'bg-slate-900': deviceType === 'base_station'
                                          }" 
                                          x-text="deviceType"></span>
                                </div>
                            </div>
                            
                            <div class="space-y-4 border-t border-outline-variant/5 pt-8">
                                <div class="flex justify-between items-center">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Hardware Profile</p>
                                    <p class="text-[10px] font-bold text-primary uppercase italic" x-text="selectedDevice ? selectedDevice.model_name : 'N/A'"></p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Spectral Band</p>
                                    <p class="text-[10px] font-manrope font-black text-secondary italic uppercase" x-text="selectedDevice ? selectedDevice.frequency + ' GHZ' : 'N/A'"></p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Topology Plot</p>
                                    <p class="text-[10px] font-bold" :class="lat ? 'text-secondary italic' : 'text-slate-300'" x-text="lat ? 'Registry Synchronized' : 'Registry Pending'"></p>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-4 bg-primary text-white font-black text-xs uppercase tracking-[0.3em] rounded shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                                <span class="material-symbols-outlined text-sm">cloud_upload</span>
                                Register Asset commit
                            </button>
                        </div>
                    </div>

                    <!-- Informational Micro-card -->
                    <div class="p-6 bg-secondary/5 border border-secondary/10 rounded-lg flex gap-4">
                        <span class="material-symbols-outlined text-secondary text-2xl">info</span>
                        <div>
                            <p class="text-[9px] font-black text-secondary uppercase tracking-[0.2em] mb-1">Provisioning Protocol</p>
                            <p class="text-[10px] font-bold text-slate-500 leading-relaxed italic">The system will generate a localized MikroTik script upon registration to facilitate handshake connectivity.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('routerForm', () => ({
        routerName: '',
        deviceType: 'router',
        ip: '',
        lat: '{{ old('lat') }}',
        lng: '{{ old('lng') }}',
        coverageRadius: '{{ old('coverage_radius') }}',
        azimuth: '{{ old('azimuth') }}',
        beamWidth: '{{ old('beam_width') }}',
        showMap: true,
        map: null,
        marker: null,
        circle: null,
        deviceQuery: '',
        selectedDevice: null,
        devices: [],
        showDevices: false,

        async searchDevices() {
            if (this.deviceQuery.length < 2) { this.devices = []; return; }
            const res = await fetch(`{{ route('api.devices.search') }}?q=${this.deviceQuery}`);
            this.devices = await res.json();
            this.showDevices = true;
        },

        selectDevice(device) {
            this.selectedDevice = device;
            this.deviceQuery = device.model_name;
            this.deviceType = device.device_type;
            this.routerName = (device.manufacturer + ' ' + device.model_name).toUpperCase();
            if (device.default_coverage_radius) this.coverageRadius = device.default_coverage_radius;
            this.showDevices = false;
            this.updateCoverageCircle();
        },

        selectTower(e) {
            const opt = e.target.options[e.target.selectedIndex];
            if (opt.dataset.lat) {
                this.lat = opt.dataset.lat;
                this.lng = opt.dataset.lng;
                this.updateMap();
            }
        },

        init() {
            setTimeout(() => this.initMap(), 200);
        },

        initMap() {
            this.map = L.map('locationMap', {zoomControl: false}).setView([33.5138, 36.2765], 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(this.map);
            L.control.zoom({position: 'bottomleft'}).addTo(this.map);
            
            this.map.on('click', (e) => {
                this.lat = e.latlng.lat.toFixed(7);
                this.lng = e.latlng.lng.toFixed(7);
                this.updateMap();
            });

            if (this.lat && this.lng) this.updateMap();
        },

        updateMap() {
            const latlng = [this.lat, this.lng];
            this.map.setView(latlng, 15);
            if(this.marker) this.marker.setLatLng(latlng);
            else this.marker = L.marker(latlng).addTo(this.map);
            this.updateCoverageCircle();
        },

        updateCoverageCircle() {
             if (this.circle) { this.map.removeLayer(this.circle); this.circle = null; }
            if (this.marker && this.coverageRadius > 0) {
                 const latlng = this.marker.getLatLng();
                 const color = this.deviceType === 'access_point' ? '#00355f' : '#00355f';
                 if (this.azimuth && this.beamWidth && this.beamWidth < 360) {
                    this.circle = this.drawSector(latlng, this.coverageRadius, this.azimuth, this.beamWidth, color);
                 } else {
                    this.circle = L.circle(latlng, { radius: this.coverageRadius, color: color, weight: 1, fillOpacity: 0.1 }).addTo(this.map);
                 }
            }
        },

        drawSector(center, radius, azimuth, beamWidth, color) {
            const startAngle = parseFloat(azimuth) - (parseFloat(beamWidth) / 2);
            const endAngle = parseFloat(azimuth) + (parseFloat(beamWidth) / 2);
            const points = [center];
            for (let angle = startAngle; angle <= endAngle; angle += 2) {
                const rad = ((90 - angle) * Math.PI) / 180;
                const latOffset = (radius / 111320) * Math.sin(rad);
                const lngOffset = (radius / (111320 * Math.cos(center.lat * Math.PI / 180))) * Math.cos(rad);
                points.push(L.latLng(center.lat + latOffset, center.lng + lngOffset));
            }
            points.push(center);
            return L.polygon(points, { color: color, fillColor: color, weight: 1, fillOpacity: 0.15 }).addTo(this.map);
        }
    }));
});
</script>
@endpush
@endsection
