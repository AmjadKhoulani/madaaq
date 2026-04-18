@extends('layouts.admin')

@section('title', 'تهيئة عقدة شبكة جديدة | Initialize Node')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 400px; width: 100%; border-radius: 8px; z-index: 1; overflow: hidden; border: 1px solid rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    <!-- Provisioning Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight italic">تهيئة عقدة MikroTik جديدة</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">Registering Hardware Core for Network Governance</p>
        </div>
        <a href="{{ route('servers.index') }}" class="px-6 py-2 border border-outline-variant/20 rounded text-slate-500 font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm text-slate-400">arrow_back</span>
            إلغاء العملية
        </a>
    </div>

    <!-- Error Handling Interface -->
    @if(session('error') || $errors->any())
    <div class="bg-error-container text-on-error-container p-6 rounded-lg border border-error/10 animate-in fade-in zoom-in duration-500">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-error rounded flex items-center justify-center text-white shadow-lg shrink-0">
                <span class="material-symbols-outlined">report</span>
            </div>
            <div>
                <p class="font-black text-sm uppercase tracking-tight mb-2">Validation Failure Protocol</p>
                @if(session('error'))
                    <p class="text-xs font-medium opacity-90 italic">{{ session('error') }}</p>
                @endif
                @if($errors->any())
                <ul class="space-y-1 mt-2">
                    @foreach($errors->all() as $error)
                    <li class="text-[10px] font-bold uppercase tracking-wide flex items-center gap-2 italic">
                        <span class="w-1 h-3 bg-error/50 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('servers.store') }}" method="POST" class="space-y-10">
        @csrf
        
        <!-- Hardware Selection Layer -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10 relative overflow-hidden" 
             x-data="modelSelector({{ $products->map(fn($p) => ['id' => $p->id, 'model_name' => $p->model_name, 'image_url' => $p->image_url, 'manufacturer' => $p->manufacturer, 'device_type' => $p->device_type])->toJson() }}, {{ old('model_id') ?? 'null' }})">
            
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-primary/5 rounded border border-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">developer_board</span>
                </div>
                <h3 class="text-xl font-black text-primary tracking-tight uppercase italic">Hardware Architecture</h3>
            </div>
            
            <!-- Global Discovery Matrix -->
            <div class="mb-10 relative">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Live Discovery Search</label>
                <div class="relative">
                    <input type="text" 
                           x-model="deviceQuery"
                           @input.debounce.300ms="searchDevices()"
                           @click.away="showDevices = false"
                           class="w-full pl-12 pr-6 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-4 focus:ring-primary/5 focus:border-primary/40 font-bold text-primary transition-all shadow-sm" 
                           placeholder="Search MikroTik matrix... (e.g. RB4011, CCR2004)"
                           autocomplete="off">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </div>
                </div>

                <!-- Discovery Result Overlay -->
                <div x-show="showDevices && devices.length > 0" 
                     x-cloak
                     x-transition:enter="duration-200 ease-out"
                     class="absolute z-50 w-full mt-2 bg-white border border-outline-variant/20 rounded shadow-2xl max-h-80 overflow-y-auto p-2 space-y-1">
                    <template x-for="device in devices" :key="device.id">
                        <div @click="selectDevice(device)" 
                             class="flex items-center gap-4 p-3 hover:bg-primary hover:text-white rounded cursor-pointer transition-all">
                            <div class="w-12 h-12 bg-surface-container-lowest rounded p-1 flex items-center justify-center shrink-0 border border-outline-variant/10">
                                <img :src="device.image_url" class="max-w-full max-h-full object-contain" onerror="this.src='https://placehold.co/100x100?text=Node'">
                            </div>
                            <div>
                                <p class="font-black text-xs tracking-tight italic" x-text="device.manufacturer + ' ' + device.model_name" dir="ltr"></p>
                                <p class="text-[9px] font-bold uppercase tracking-widest opacity-60" x-text="device.device_type"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Quick Selection Catalog -->
            <div x-show="!deviceQuery || devices.length === 0" class="mb-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <template x-for="product in products" :key="product.id">
                        <div @click="selectProduct(product)" 
                             class="cursor-pointer bg-white border rounded p-4 transition-all duration-300 hover:shadow-lg text-center flex flex-col items-center gap-3"
                             :class="selectedModelId === product.id ? 'border-primary ring-2 ring-primary/10' : 'border-outline-variant/10 hover:border-primary/40'">
                            
                            <div class="h-24 w-full flex items-center justify-center">
                                <img :src="product.image_url" :alt="product.model_name" class="max-h-full max-w-full object-contain grayscale hover:grayscale-0 transition-all opacity-80" onerror="this.src='https://placehold.co/150x100?text=Hardware'">
                            </div>

                            <div class="w-full">
                                <h4 class="text-[10px] font-black text-primary uppercase tracking-tight truncate font-manrope italic" x-text="product.model_name" dir="ltr"></h4>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1" x-text="product.manufacturer"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Identity Injection -->
            <input type="hidden" name="model_id" :value="selectedModelId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end border-t border-outline-variant/10 pt-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Node Identification Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="serverName" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-4 focus:ring-primary/5 focus:border-primary/40 font-black text-primary transition-all shadow-sm" placeholder="E.g. CORE-NODE-01" required dir="ltr">
                </div>

                <!-- Visual Handshake Preview -->
                <div class="bg-primary p-6 rounded flex items-center gap-6 shadow-lg shadow-primary/10 animate-in fade-in slide-in-from-right-4 h-[68px]" x-show="previewImage" x-transition>
                    <div class="w-12 h-12 shrink-0 bg-white/10 rounded flex items-center justify-center p-2 border border-white/10">
                        <img :src="previewImage" class="max-w-full max-h-full object-contain filter brightness-0 invert opacity-40">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[8px] font-black text-white/50 uppercase tracking-[0.2em] mb-0.5 italic">Registration Preview</p>
                        <p class="text-[11px] font-black text-white truncate uppercase italic tracking-widest" x-text="serverName" dir="ltr"></p>
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
                        if (product) this.previewImage = product.image_url;
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
                    } catch (e) {}
                },

                selectDevice(device) {
                    this.serverName = (device.model_name).toUpperCase();
                    this.previewImage = device.image_url;
                    this.deviceQuery = device.model_name;
                    this.selectedModelId = device.id;
                    this.showDevices = false;
                },

                selectProduct(product) {
                    this.selectedModelId = product.id;
                    this.serverName = product.model_name.toUpperCase();
                    this.previewImage = product.image_url;
                    this.deviceQuery = '';
                }
            }
        }
        </script>

        <!-- Connectivity Spec Registry -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-secondary/10 rounded border border-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">satellite_alt</span>
                </div>
                <h3 class="text-xl font-black text-primary tracking-tight uppercase italic">Connectivity Protocol</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">IP Infrastructure Address <span class="text-rose-500">*</span></label>
                    <input type="text" name="ip" value="{{ old('ip') }}" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary" placeholder="192.168.88.1" required dir="ltr">
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">API Governance Port <span class="text-rose-500">*</span></label>
                    <input type="number" name="api_port" value="{{ old('api_port', 8728) }}" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary" required dir="ltr">
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Governance Access ID (User) <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', 'admin') }}" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary" required dir="ltr">
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Access Protocol Secret (Password)</label>
                    <input type="password" name="password" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary" dir="ltr">
                </div>
            </div>
        </div>

        <!-- Geospatial Topology Registry -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-primary/5 rounded border border-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">map</span>
                </div>
                <h3 class="text-xl font-black text-primary tracking-tight uppercase italic">Geospatial Registry</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Structural Parent Alignment (Tower)</label>
                    <select name="location_tower_id" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary italic">
                        <option value="">Virtual Infrastructure (Independent)</option>
                        @foreach($towers ?? [] as $tower)
                        <option value="{{ $tower->id }}" {{ old('location_tower_id') == $tower->id ? 'selected' : '' }}>
                            🗼 {{ $tower->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Physical Site Registry</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary" placeholder="E.g. Terminal B, Room 402">
                </div>

                <div class="md:col-span-2 space-y-6" x-data="serverMap()">
                    <div class="flex items-center justify-between">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Coordinates Synchronization</label>
                        <div class="flex gap-2">
                            <input type="text" name="lat" x-model="lat" class="bg-surface-container-highest/10 border-none p-1.5 rounded text-[10px] font-manrope font-black text-primary w-24 text-center ring-1 ring-outline-variant/10" readonly>
                            <input type="text" name="lng" x-model="lng" class="bg-surface-container-highest/10 border-none p-1.5 rounded text-[10px] font-manrope font-black text-primary w-24 text-center ring-1 ring-outline-variant/10" readonly>
                        </div>
                    </div>
                    <div id="map" class="group relative">
                        <div class="absolute bottom-4 left-4 z-[10] bg-white/80 backdrop-blur-md px-4 py-2 rounded border border-outline-variant/10 shadow-sm opacity-0 group-hover:opacity-100 transition-all pointer-events-none">
                            <p class="text-[8px] font-black text-primary italic uppercase tracking-widest">Click to Plot Registry Point</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Infrastructure Commitment -->
        <div class="p-8 bg-surface-container-low border border-outline-variant/10 rounded-lg flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-12 h-12 bg-primary text-white rounded flex items-center justify-center shadow-lg shadow-primary/10 italic font-black text-xl">M</div>
                <div>
                    <h5 class="text-sm font-black text-primary uppercase italic">Infrastructure Provisioning committing</h5>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Finalizing node registration in the Sovereign registry</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="px-10 py-3 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded shadow-xl shadow-primary/10 hover:scale-[1.02] active:scale-95 transition-all">
                    Register Cluster Node
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
function serverMap() {
    return {
        lat: '{{ old('lat') }}',
        lng: '{{ old('lng') }}',
        init() {
            setTimeout(() => {
                const map = L.map('map', {zoomControl: false}).setView([33.5138, 36.2765], 13);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
                L.control.zoom({position: 'bottomleft'}).addTo(map);

                let marker = null;
                if (this.lat && this.lng) {
                    marker = L.marker([this.lat, this.lng]).addTo(map);
                    map.setView([this.lat, this.lng], 15);
                }

                map.on('click', (e) => {
                    this.lat = e.latlng.lat.toFixed(7);
                    this.lng = e.latlng.lng.toFixed(7);
                    if (marker) marker.setLatLng(e.latlng);
                    else marker = L.marker(e.latlng).addTo(map);
                });
            }, 200);
        }
    }
}
</script>
@endpush
