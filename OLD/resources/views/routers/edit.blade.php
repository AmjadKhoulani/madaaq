@extends('layouts.admin')

@section('title', 'تعديل بيانات الجهاز | Modify Network Asset')

@section('content')
<div class="max-w-7xl mx-auto space-y-12 pb-24" x-data="routerForm()">
    
    <!-- Radiant Form Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-12 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">Asset Modification Hub</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">تعديل سجل الجهاز</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Refining Spectral Architecture for Asset: <span class="text-primary italic">{{ $router->name }}</span></p>
        </div>
        <a href="{{ route('routers.index') }}" class="px-8 py-3.5 bg-white border border-slate-200 text-slate-500 font-black rounded-2xl text-[10px] uppercase tracking-widest transition-all hover:bg-slate-50 flex items-center gap-3 italic">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            إلغاء و العودة
        </a>
    </div>

    <!-- Error Matrix -->
    @if($errors->any())
    <div class="glass-panel p-8 bg-error/5 border-error/20 rounded-3xl animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex items-start gap-6">
            <div class="w-12 h-12 bg-error rounded-2xl flex items-center justify-center text-white shadow-glow-purple shrink-0">
                <span class="material-symbols-outlined">warning</span>
            </div>
            <div>
                <p class="font-black text-error text-xs uppercase tracking-widest italic mb-2">Protocol Conflict Detected</p>
                <ul class="space-y-2">
                    @foreach($errors->all() as $error)
                    <li class="text-[10px] font-bold text-slate-600 uppercase tracking-tight flex items-center gap-3 italic">
                        <span class="w-1 h-3 bg-error rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('routers.update', $router) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
        @csrf
        @method('PUT')
        
        <!-- Logic & Identity Blueprint -->
        <div class="lg:col-span-2 space-y-10">
            
            <!-- 1. Technical Identity -->
            <div class="glass-panel p-10 rounded-[2.5rem]">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm border border-primary/10">
                        <span class="material-symbols-outlined">developer_board</span>
                    </div>
                    <h3 class="text-lg font-black text-primary uppercase italic tracking-widest italic">Asset Identity Matrix</h3>
                </div>
                
                <!-- Search Registry -->
                <div class="mb-10 relative group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-2 italic">Global Hardware Discovery Scan</label>
                    <div class="relative">
                        <input type="text" x-model="deviceQuery" @input.debounce.300ms="searchDevices()" @click.away="showDevices = false"
                               class="input-radiant !bg-white/80 pr-14 pl-8" 
                               placeholder="Search Hardware Specs... (e.g. LiteBeam, hAP, EdgeRouter)"
                               autocomplete="off">
                        <span class="material-symbols-outlined absolute right-5 top-4 text-slate-300 group-focus-within:text-primary transition-colors">search</span>
                    </div>
                    <input type="hidden" name="model_id" :value="selectedDevice ? selectedDevice.id : ''">
                    
                    <!-- Overlay Results -->
                    <div x-show="showDevices && devices.length > 0" x-cloak x-transition
                         class="absolute z-50 w-full mt-4 bg-white/95 backdrop-blur-xl border border-slate-200/50 rounded-3xl shadow-2xl p-4 space-y-2 max-h-96 overflow-y-auto custom-scrollbar">
                        <template x-for="device in devices" :key="device.id">
                            <div @click="selectDevice(device)" class="flex items-center gap-6 p-4 hover:bg-slate-900 hover:text-white rounded-2xl cursor-pointer transition-all group/item">
                                <div class="w-16 h-16 bg-white rounded-2xl border border-slate-100 p-2 flex items-center justify-center shrink-0 shadow-sm group-hover/item:scale-110 transition-transform">
                                    <img :src="device.image_url" class="max-w-full max-h-full object-contain" onerror="this.src='https://placehold.co/100x100?text=HW'">
                                </div>
                                <div>
                                    <p class="font-black text-sm tracking-tight italic uppercase" x-text="device.manufacturer + ' ' + device.model_name" dir="ltr"></p>
                                    <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-50 mt-1" x-text="device.device_type"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Asset Operational Label <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="routerName" class="input-radiant !bg-white/80" required dir="ltr">
                    </div>
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Functional Logic Category <span class="text-rose-500">*</span></label>
                        <select name="device_type" x-model="deviceType" class="input-radiant !bg-white/80 italic">
                            <option value="router">Gateway Node (Router)</option>
                            <option value="switch">Matrix Core (Switch)</option>
                            <option value="access_point">Spectral Hub (Access Point)</option>
                            <option value="base_station">Transmission Array (Base Station)</option>
                        </select>
                    </div>
                </div>

                <!-- Spectral Profile -->
                <div x-show="['access_point', 'base_station'].includes(deviceType)" x-transition class="mt-10 p-8 bg-primary/5 rounded-3xl border border-primary/10 space-y-6">
                    <h4 class="text-[10px] font-black text-primary uppercase tracking-[0.3em] italic">Spectral Radiation Profile</h4>
                    <div class="flex flex-wrap gap-8">
                        <label class="flex items-center gap-4 cursor-pointer group">
                            <input type="radio" name="antenna_type" value="sector" {{ $router->antenna_type == 'sector' ? 'checked' : '' }} class="w-5 h-5 text-primary border-slate-300 focus:ring-primary">
                            <span class="text-[11px] font-black uppercase text-slate-600 group-hover:text-primary transition-all italic">Sector Axis</span>
                        </label>
                        <label class="flex items-center gap-4 cursor-pointer group">
                            <input type="radio" name="antenna_type" value="omni" {{ $router->antenna_type == 'omni' ? 'checked' : '' }} class="w-5 h-5 text-primary border-slate-300 focus:ring-primary">
                            <span class="text-[11px] font-black uppercase text-slate-600 group-hover:text-primary transition-all italic">Omni Sphere</span>
                        </label>
                        <label class="flex items-center gap-4 cursor-pointer group">
                            <input type="radio" name="antenna_type" value="dish" {{ $router->antenna_type == 'dish' ? 'checked' : '' }} class="w-5 h-5 text-primary border-slate-300 focus:ring-primary">
                            <span class="text-[11px] font-black uppercase text-slate-600 group-hover:text-primary transition-all italic">Directional Dish</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- 2. Connectivity Protocol -->
            <div class="glass-panel p-10 rounded-[2.5rem]">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-12 h-12 bg-neon-cyan/10 rounded-2xl flex items-center justify-center text-neon-cyan shadow-sm border border-neon-cyan/10">
                        <span class="material-symbols-outlined">rss_feed</span>
                    </div>
                    <h3 class="text-lg font-black text-primary uppercase italic tracking-widest italic">Connectivity Protocol</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Network Operational IP <span class="text-rose-500">*</span></label>
                        <input type="text" name="ip" x-model="ip" class="input-radiant !bg-white/80 font-manrope" placeholder="192.168.x.x" required dir="ltr">
                    </div>
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Handshake Port (API)</label>
                        <input type="number" name="api_port" value="{{ $router->api_port }}" class="input-radiant !bg-white/80 font-manrope" dir="ltr">
                    </div>
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Access ID (Username)</label>
                        <input type="text" name="username" value="{{ $router->username }}" class="input-radiant !bg-white/80" dir="ltr">
                    </div>
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Protocol Secret (Password)</label>
                        <input type="password" name="password" class="input-radiant !bg-white/80" placeholder="••••••••" dir="ltr">
                        <p class="text-[8px] font-bold text-slate-400 italic">Leave void to maintain current encrypted secret</p>
                    </div>
                </div>
            </div>

            <!-- 3. Geospatial Mapping -->
            <div class="glass-panel rounded-[2.5rem] overflow-hidden">
                <button type="button" @click="showMap = !showMap" class="w-full p-10 flex items-center justify-between hover:bg-slate-50 transition-all group">
                    <div class="flex items-center gap-5 text-primary">
                        <div class="w-12 h-12 bg-vibrant-purple/10 rounded-2xl flex items-center justify-center text-vibrant-purple shadow-sm border border-vibrant-purple/10 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">location_on</span>
                        </div>
                        <h3 class="text-lg font-black uppercase italic tracking-widest italic">Geospatial Topology Registry</h3>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 transform transition-transform" :class="showMap ? 'rotate-180' : ''">expand_more</span>
                </button>
                
                <div x-show="showMap" x-collapse>
                    <div class="px-10 pb-10 space-y-10">
                        <!-- Calibration -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 border-t border-slate-100 pt-10">
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Structural Parent (Tower Sync)</label>
                                <select name="tower_id" @change="selectTower($event)" class="input-radiant !bg-white/80 italic">
                                    <option value="">-- Manual Site Selection --</option>
                                    @foreach($towers as $tower)
                                    <option value="{{ $tower->id }}" {{ $router->tower_id == $tower->id ? 'selected' : '' }} data-lat="{{ $tower->lat }}" data-lng="{{ $tower->lng }}">
                                        🗼 {{ $tower->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-6 items-end">
                                <div class="flex-1 space-y-3">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest italic">N-LAT COORD</label>
                                    <input type="text" name="lat" x-model="lat" class="w-full px-5 py-4 bg-slate-900 text-neon-cyan border-none rounded-xl text-[11px] font-manrope font-black text-center shadow-2xl" readonly>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest italic">E-LNG COORD</label>
                                    <input type="text" name="lng" x-model="lng" class="w-full px-5 py-4 bg-slate-900 text-neon-cyan border-none rounded-xl text-[11px] font-manrope font-black text-center shadow-2xl" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Map Shell -->
                        <div class="relative group">
                            <div id="locationMap" class="rounded-3xl border border-slate-200 overflow-hidden shadow-radiant transition-all group-hover:shadow-2xl h-[450px]"></div>
                            <div class="absolute bottom-6 left-6 z-[10] bg-slate-900/90 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/10 shadow-2xl transition-all pointer-events-none">
                                <p class="text-[9px] font-black text-neon-cyan italic uppercase tracking-[0.2em]">Matrix Mapping Engine | Verified Click Registration</p>
                            </div>
                        </div>
                        
                        <!-- Spectral Matrix -->
                        <div class="grid grid-cols-3 gap-8 pt-6 border-t border-slate-100">
                            <div class="space-y-4">
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest italic leading-tight">Max Effective Radius (m)</label>
                                <input type="number" name="coverage_radius" x-model="coverageRadius" @input="updateCoverageCircle()" class="input-radiant !bg-white/80 !py-3 !text-xs font-manrope">
                            </div>
                            <div class="space-y-4">
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest italic leading-tight">Azimuth Axis (0°-360°)</label>
                                <input type="number" name="azimuth" x-model="azimuth" @input="updateCoverageCircle()" class="input-radiant !bg-white/80 !py-3 !text-xs font-manrope">
                            </div>
                            <div class="space-y-4">
                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest italic leading-tight">Beamwidth Aperture</label>
                                <input type="number" name="beam_width" x-model="beamWidth" @input="updateCoverageCircle()" class="input-radiant !bg-white/80 !py-3 !text-xs font-manrope">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Radiant Reality Check -->
        <div class="lg:col-span-1">
            <div class="sticky top-12 space-y-8">
                <div class="glass-card rounded-[2.5rem] overflow-hidden !bg-white/90">
                    <!-- High-Intensity Profile -->
                    <div class="h-48 bg-slate-50 flex items-center justify-center p-8 border-b border-slate-100 relative group">
                        <div class="absolute inset-0 bg-accent-flow opacity-5 group-hover:opacity-10 transition-opacity"></div>
                        <img :src="selectedDevice ? selectedDevice.image_url : ( '{{ $router->model ? $router->model->image_url : '' }}' || 'https://placehold.co/200x200?text=HW+Registry' )" 
                             class="max-h-full max-w-full object-contain filter drop-shadow-2xl group-hover:scale-110 transition-transform duration-700">
                    </div>
                    
                    <div class="p-10 space-y-10">
                        <div class="text-center">
                            <h3 class="text-xl font-black text-primary uppercase italic tracking-tighter mb-2" x-text="routerName || 'Awaiting Device Identity'"></h3>
                            <div class="flex items-center justify-center gap-3">
                                <span class="px-4 py-1.5 rounded-full bg-slate-900 text-neon-cyan font-manrope font-black text-[9px] italic tracking-widest shadow-glow-cyan" x-text="ip || 'Node IP Required'"></span>
                                <span class="px-4 py-1.5 rounded-full font-black text-[9px] uppercase tracking-widest text-white shadow-xl" 
                                      :class="{
                                          'bg-primary shadow-primary/20': deviceType === 'router',
                                          'bg-secondary shadow-secondary/20': deviceType === 'access_point',
                                          'bg-vibrant-purple shadow-vibrant-purple/20': deviceType === 'base_station',
                                          'bg-slate-700': deviceType === 'switch'
                                      }" 
                                      x-text="deviceType"></span>
                            </div>
                        </div>
                        
                        <div class="space-y-4 border-t border-slate-100 pt-10">
                            <div class="flex justify-between items-center group">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">HW Profile</p>
                                <p class="text-[10px] font-black text-primary uppercase italic" x-text="selectedDevice ? selectedDevice.model_name : '{{ $router->model ? $router->model->model_name : 'N/A' }}'"></p>
                            </div>
                            <div class="flex justify-between items-center group">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Spectral Band</p>
                                <p class="text-[10px] font-manrope font-black text-secondary italic uppercase" x-text="selectedDevice ? selectedDevice.frequency + ' GHZ' : '{{ $router->model ? $router->model->frequency : '' }} GHZ'"></p>
                            </div>
                            <div class="flex justify-between items-center group">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Topology Status</p>
                                <p class="text-[10px] font-black italic" :class="lat ? 'text-secondary' : 'text-slate-300'" x-text="lat ? 'Sync Established' : 'Awaiting Plot'"></p>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full py-5 bg-primary text-white font-black text-xs uppercase tracking-[0.4em] rounded-2xl shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center justify-center gap-4 italic group">
                            <span class="material-symbols-outlined text-sm">save_as</span>
                            Sync Modifications
                        </button>
                    </div>
                </div>

                <!-- Handshake Pulse -->
                <div class="p-8 glass-card border-neon-cyan/20 !bg-neon-cyan/5 rounded-[2rem] flex gap-6 animate-pulse-soft">
                    <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-neon-cyan shadow-sm shrink-0">
                        <span class="material-symbols-outlined">sync_saved_locally</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-1 italic">Protocol Persistence</p>
                        <p class="text-[10px] font-bold text-slate-600 leading-relaxed italic">Changes will be pushed to the core management registry instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('routerForm', () => ({
        routerName: '{{ $router->name }}',
        deviceType: '{{ $router->device_type }}',
        ip: '{{ $router->ip }}',
        lat: '{{ $router->lat }}',
        lng: '{{ $router->lng }}',
        coverageRadius: '{{ $router->coverage_radius }}',
        azimuth: '{{ $router->azimuth }}',
        beamWidth: '{{ $router->beam_width }}',
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
            this.map = L.map('locationMap', {zoomControl: false}).setView([{{ $router->lat ?? 33.5138 }}, {{ $router->lng ?? 36.2765 }}], 15);
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
                 const color = '#00355f';
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
