@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 450px; width: 100%; border-radius: 2.5rem; z-index: 1; overflow: hidden; border: 4px solid white; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); }
    .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-10 pb-20">
    <!-- Provisioning Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl"></div>
        <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
            <div class="w-20 h-20 bg-emerald-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-emerald-100 shrink-0">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M5 12l4-4m-4 4l4 4"/></svg>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Initialize MikroTik Node</h2>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-1">Registering main infrastructure core for user governance</p>
            </div>
        </div>
    </div>

    <!-- Notification Layer -->
    @if(session('error'))
    <div class="bg-rose-500/10 border border-rose-500/20 p-6 rounded-[2rem] animate-in fade-in zoom-in duration-500">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-rose-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-rose-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <p class="text-rose-900 font-black text-xs uppercase tracking-widest">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-500/10 border border-rose-500/20 p-8 rounded-[2rem] animate-in fade-in zoom-in duration-500">
        <div class="flex items-start gap-5">
            <div class="w-12 h-12 bg-rose-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-rose-100 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-rose-900 font-black text-sm uppercase tracking-tight mb-3">Validation Protocol Failed</p>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-rose-800/80 font-bold text-[11px] uppercase tracking-wide flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('servers.store') }}" method="POST" class="space-y-10">
        @csrf
        
        <!-- Hardware Architecture & Catalog -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden" x-data="modelSelector({{ $products->map(fn($p) => ['id' => $p->id, 'model_name' => $p->model_name, 'image_url' => $p->image_url, 'price' => $p->max_throughput, 'description' => $p->description, 'manufacturer' => $p->manufacturer, 'device_type' => $p->device_type])->toJson() }}, {{ old('model_id') ?? 'null' }})">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Hardware Intelligence Core</h3>
            </div>
            
            <!-- Global Device Discovery Matrix -->
            <div class="mb-12 relative group">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-2">Smart Discovery Search</label>
                <div class="relative">
                    <input type="text" 
                           x-model="deviceQuery"
                           @input.debounce.300ms="searchDevices()"
                           @click.away="showDevices = false"
                           class="w-full pl-14 pr-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-bold text-gray-900 transition-all shadow-inner" 
                           placeholder="Type to search global MikroTik matrix... (e.g. CCR2004)"
                           autocomplete="off">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <!-- Live Results Hologram -->
                <div x-show="showDevices && devices.length > 0" 
                     x-cloak
                     x-transition:enter="duration-300 ease-out"
                     class="absolute z-50 w-full mt-4 bg-white/95 backdrop-blur-xl border border-white/60 rounded-[2.5rem] shadow-2xl max-h-96 overflow-y-auto p-4 space-y-2">
                    <template x-for="device in devices" :key="device.id">
                        <div @click="selectDevice(device)" 
                             class="flex items-center gap-4 p-4 hover:bg-indigo-600 hover:text-white rounded-2xl cursor-pointer transition-all">
                            <div class="w-16 h-16 bg-white rounded-xl border border-gray-100 p-2 flex items-center justify-center shrink-0">
                                <img :src="device.image_url" class="max-w-full max-h-full object-contain" onerror="this.src='https://placehold.co/150x100?text=Node'">
                            </div>
                            <div>
                                <p class="font-black text-sm tracking-tight" x-text="device.manufacturer + ' ' + device.model_name" dir="ltr"></p>
                                <p class="text-[9px] font-black uppercase tracking-widest opacity-60" x-text="device.device_type"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Curated Inventory Registry -->
            <div x-show="!deviceQuery || devices.length === 0" class="mb-12 animate-in fade-in duration-700">
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-10 text-center bg-indigo-50/50 py-2.5 rounded-full border border-indigo-100/50 shadow-sm">Distributed Infrastructure Catalog</p>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <template x-for="product in products" :key="product.id">
                        <div @click="selectProduct(product)" 
                             class="cursor-pointer glass-panel border bg-white/40 backdrop-blur-md rounded-[2rem] p-6 transition-all duration-500 hover:scale-[1.05] hover:shadow-2xl text-center group relative overflow-hidden flex flex-col items-center border-white/60"
                             :class="selectedModelId === product.id ? 'ring-4 ring-emerald-500/20 border-emerald-500/40 bg-emerald-500/5' : 'hover:border-indigo-600/40'">
                            
                            <!-- Selection Check -->
                            <div x-show="selectedModelId === product.id" class="absolute top-4 right-4 text-emerald-500 animate-in zoom-in duration-300">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>

                            <div class="h-32 w-full flex items-center justify-center mb-6">
                                <img :src="product.image_url" :alt="product.model_name" class="max-h-full max-w-full object-contain filter drop-shadow-lg group-hover:scale-110 transition-transform duration-700" onerror="this.src='https://placehold.co/150x100?text=Hardware'">
                            </div>

                            <div class="w-full">
                                <h4 class="text-[11px] font-black text-gray-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight line-clamp-2" x-text="product.model_name" dir="ltr"></h4>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-2" x-text="product.manufacturer"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Identity Core -->
            <input type="hidden" name="model_id" :value="selectedModelId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-end">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Node Specification Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="serverName" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="E.g. Main-Gateway-X1" required dir="ltr">
                </div>

                <!-- Live Preview Metadata -->
                <div class="bg-indigo-600 p-8 rounded-[2.5rem] flex items-center gap-8 shadow-xl shadow-indigo-100 transition-all duration-700 animate-in fade-in slide-in-from-right-8" x-show="previewImage" x-transition>
                    <div class="w-24 h-24 shrink-0 bg-white/20 backdrop-blur-md rounded-2xl border border-white/20 p-3 flex items-center justify-center shadow-lg">
                        <img :src="previewImage" class="max-w-full max-h-full object-contain filter drop-shadow-md">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] font-black text-indigo-200 uppercase tracking-[0.2em] mb-1">Provisioning Preview</p>
                        <p class="text-sm font-black text-white truncate uppercase tracking-tight" x-text="serverName.replace('MikroTik ', '')" dir="ltr"></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function modelSelector(initialProducts, initialModelId = null) {
            return {
                selectedModelId: initialModelId,
                serverName: '{{ old('name') }}',
                deviceQuery: '',
                previewImage: '',
                devices: [],
                showDevices: false,
                products: initialProducts || [],

                init() {
                    if (this.selectedModelId) {
                        const product = this.products.find(p => p.id == this.selectedModelId);
                        if (product) {
                            this.previewImage = product.image_url;
                        }
                    }
                },

                async searchDevices() {
                    if (this.deviceQuery.length < 2) {
                        this.devices = [];
                        this.showDevices = false;
                        return;
                    }
                    try {
                        const response = await fetch(`{{ route('api.devices.search') }}?q=${this.deviceQuery}`);
                        this.devices = await response.json();
                        this.showDevices = true;
                    } catch (e) {
                        console.error("Search failed", e);
                    }
                },

                selectDevice(device) {
                    this.serverName = 'MikroTik ' + device.model_name;
                    this.previewImage = device.image_url;
                    this.deviceQuery = device.model_name;
                    this.selectedModelId = device.id;
                    this.showDevices = false;
                },

                selectProduct(product) {
                    this.selectedModelId = product.id;
                    this.serverName = product.manufacturer + ' ' + product.model_name;
                    this.previewImage = product.image_url;
                    this.deviceQuery = '';
                }
            }
        }
        </script>

        <!-- Geospatial Registry Shield -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-12 h-12 bg-rose-600/10 rounded-2xl flex items-center justify-center text-rose-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Geospatial Distribution Point</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Backbone Connectivity Source</label>
                    <div class="relative">
                        <select name="internet_source_id" class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 appearance-none transition-all shadow-inner">
                            <option value="">Autonomous Gateway (Standalone)</option>
                            @foreach($internetSources ?? [] as $source)
                            <option value="{{ $source->id }}" {{ old('internet_source_id') == $source->id ? 'selected' : '' }}>
                                🌐 {{ $source->name }} ({{ $source->type }})
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Structural Assembly (Tower)</label>
                    <div class="relative">
                        <select name="location_tower_id" class="w-full pl-6 pr-12 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 appearance-none transition-all shadow-inner">
                            <option value="">Virtual Infrastructure (Independent)</option>
                            @foreach($towers ?? [] as $tower)
                            <option value="{{ $tower->id }}" {{ old('location_tower_id') == $tower->id ? 'selected' : '' }}>
                                🗼 {{ $tower->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Physical Location Intelligence</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="E.g. Main Distribution Frame - Terminal 4C">
                </div>

                <!-- Geospatial Plotting Control -->
                <div class="md:col-span-2 space-y-8" x-data="serverMap()" x-init="init()">
                    <div class="flex items-center justify-between">
                        <label class="block text-[10px] font-black text-rose-600 uppercase tracking-[0.3em] ml-2">Coordinate Synchronization Shield</label>
                        <div class="flex gap-4">
                            <div class="bg-rose-50 px-6 py-2 rounded-2xl border border-rose-100 flex items-center gap-3">
                                <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest">N-Lat</span>
                                <input type="text" name="lat" x-model="lat" class="bg-transparent border-none p-0 text-xs font-mono font-black text-rose-900 w-24 focus:ring-0" readonly>
                            </div>
                            <div class="bg-emerald-50 px-6 py-2 rounded-2xl border border-emerald-100 flex items-center gap-3">
                                <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">E-Lng</span>
                                <input type="text" name="lng" x-model="lng" class="bg-transparent border-none p-0 text-xs font-mono font-black text-emerald-900 w-24 focus:ring-0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="aspect-video w-full rounded-[3.5rem] overflow-hidden border border-white/80 shadow-2xl relative group">
                        <div id="map" class="w-full h-full z-0 grayscale-[0.4] contrast-[1.1] brightness-[0.95]"></div>
                        <div class="absolute inset-0 bg-rose-500/5 pointer-events-none group-focus-within:bg-transparent transition-all"></div>
                        <div class="absolute top-8 left-8 z-[10] bg-white/60 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/60 shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-500">
                            <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest">Global Mapping Engine v2.4</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function serverMap() {
            return {
                lat: '{{ old('lat') }}',
                lng: '{{ old('lng') }}',
                map: null,
                marker: null,
                init() {
                    setTimeout(() => {
                        this.map = L.map('map', {zoomControl: false}).setView([33.5138, 36.2765], 13);
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                            maxZoom: 19
                        }).addTo(this.map);
                        
                        L.control.zoom({position: 'bottomleft'}).addTo(this.map);

                        const customIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: "<div style='background-color:#e11d48; width:16px; height:16px; border-radius:50%; border:3px solid white; shadow:0 5px 15px rgba(225,29,72,0.4)'></div>",
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });

                        if (this.lat && this.lng) {
                            const initialLatLng = [parseFloat(this.lat), parseFloat(this.lng)];
                            this.marker = L.marker(initialLatLng, {icon: customIcon}).addTo(this.map);
                            this.map.setView(initialLatLng, 15);
                        }

                        this.map.on('click', (e) => {
                            this.lat = e.latlng.lat.toFixed(7);
                            this.lng = e.latlng.lng.toFixed(7);
                            if (this.marker) {
                                this.marker.setLatLng(e.latlng);
                            } else {
                                this.marker = L.marker(e.latlng, {icon: customIcon}).addTo(this.map);
                            }
                        });
                    }, 200);
                }
            }
        }
        </script>

        <!-- Infrastructure Commitment -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 glass-panel border border-white/40 p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl -mb-32 -mr-32"></div>
            <div class="flex items-center gap-8 relative z-10">
                <div class="w-16 h-16 bg-indigo-600/10 text-indigo-600 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">💡</div>
                <div class="space-y-1">
                    <h5 class="text-lg font-black text-gray-900 uppercase tracking-tight">Provisioning Intelligence</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Post-registration terminal logic will be generated for the RouterOS stack.</p>
                </div>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('servers.index') }}" class="px-10 py-5 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:text-gray-900 transition-all">
                    Discard Archive
                </a>
                <button type="submit" class="px-12 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95 leading-none">
                    Initialize Infrastructure commit
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush
