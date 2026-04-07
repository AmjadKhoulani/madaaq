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
    <!-- Command Center Header -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row items-center justify-between gap-8 z-10">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="w-20 h-20 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-indigo-100 shrink-0">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Modify Node Protocol</h1>
                    <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mt-1">Adjusting governance parameters for {{ $server->name }}</p>
                </div>
            </div>
            <a href="{{ route('servers.show', $server) }}" class="px-8 py-4 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-white hover:text-gray-900 transition-all border border-white/60 shadow-sm flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Identity Overview
            </a>
        </div>
    </div>

    <form action="{{ route('servers.update', $server) }}" method="POST" class="space-y-10">
        @csrf
        @method('PUT')

        <!-- Hardware Blueprint (Read Onlyish) -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Infrastructure Blueprint</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Node Identity Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $server->name) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="E.g. Core-BGP-01" required dir="ltr">
                    </div>
                </div>

                <!-- Hardware Visual -->
                <div class="bg-indigo-600 p-8 rounded-[2.5rem] flex items-center gap-8 shadow-xl shadow-indigo-100 relative group">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="w-24 h-24 shrink-0 bg-white/20 backdrop-blur-md rounded-2xl border border-white/20 p-3 flex items-center justify-center shadow-lg relative z-10">
                        <img src="{{ $server->deviceModel->image_url ?? 'https://placehold.co/150x100?text=Hardware' }}" class="max-w-full max-h-full object-contain filter drop-shadow-md">
                    </div>
                    <div class="flex-1 min-w-0 relative z-10">
                        <p class="text-[9px] font-black text-indigo-200 uppercase tracking-[0.2em] mb-1">Assigned Hardware</p>
                        <p class="text-sm font-black text-white truncate uppercase tracking-tight" dir="ltr">{{ $server->deviceModel->model_name ?? 'MikroTik Infrastructure' }}</p>
                    </div>
                </div>
            </div>
        </div>

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
                            <option value="{{ $source->id }}" {{ old('internet_source_id', $server->internet_source_id) == $source->id ? 'selected' : '' }}>
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
                            <option value="{{ $tower->id }}" {{ old('location_tower_id', $server->location_tower_id) == $tower->id ? 'selected' : '' }}>
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
                    <input type="text" name="location" value="{{ old('location', $server->location) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="E.g. Main Distribution Frame - Terminal 4C">
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
                    </div>
                </div>
            </div>
        </div>

        <script>
        function serverMap() {
            return {
                lat: '{{ old('lat', $server->lat) }}',
                lng: '{{ old('lng', $server->lng) }}',
                map: null,
                marker: null,
                init() {
                    setTimeout(() => {
                        const startLat = this.lat || 33.5138;
                        const startLng = this.lng || 36.2765;
                        this.map = L.map('map', {zoomControl: false}).setView([startLat, startLng], 13);
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

        <!-- Connectivity Credentials Stack -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-12 h-12 bg-emerald-600/10 rounded-2xl flex items-center justify-center text-emerald-600 font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Connection Governance Stack</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Encrypted API Gateway (IP)</label>
                    <input type="text" name="ip" value="{{ old('ip', $server->ip) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-mono font-black text-gray-900 transition-all shadow-inner" dir="ltr">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Protocol Port Index</label>
                    <input type="number" name="api_port" value="{{ old('api_port', $server->api_port) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-mono font-black text-gray-900 transition-all shadow-inner" dir="ltr">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Governance User ID</label>
                    <input type="text" name="username" value="{{ old('username', $server->username) }}" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-black text-gray-900 transition-all shadow-inner" dir="ltr">
                </div>
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1 ml-2">Secure Passcode Index (Unchanged if blank)</label>
                    <input type="password" name="password" autocomplete="new-password" class="w-full px-6 py-5 bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/40 font-black text-gray-900 transition-all shadow-inner" placeholder="••••••••••••" dir="ltr">
                </div>
            </div>
        </div>

        <!-- Infrastructure Commitment -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 glass-panel border border-white/40 p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl -mb-32 -mr-32"></div>
            <div class="flex items-center gap-8 relative z-10">
                <div class="w-16 h-16 bg-indigo-600/10 text-indigo-600 rounded-[1.5rem] flex items-center justify-center text-3xl shrink-0">💡</div>
                <div class="space-y-1">
                    <h5 class="text-lg font-black text-gray-900 uppercase tracking-tight">Governance Commitment</h5>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Protocol synchronization will re-initialize upon commitment.</p>
                </div>
            </div>
            <div class="flex items-center gap-6 relative z-10">
                <a href="{{ route('servers.show', $server) }}" class="px-10 py-5 bg-white/60 text-gray-500 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:text-gray-900 transition-all">
                    Discard Changes
                </a>
                <button type="submit" class="px-12 py-5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95 leading-none">
                    Sync Infrastructure Commit
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush
