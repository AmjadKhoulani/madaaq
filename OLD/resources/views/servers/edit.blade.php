@extends('layouts.admin')

@section('title', 'تعديل بروتوكول العقدة | Edit Node Protocol')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 400px; width: 100%; border-radius: 8px; z-index: 1; overflow: hidden; border: 1px solid rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">
    <!-- Command Center Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight italic uppercase">تعديل بروتوكول العقدة</h2>
            <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px] font-headline">Adjusting Governance Parameters for: {{ $server->name }}</p>
        </div>
        <a href="{{ route('servers.show', $server) }}" class="px-6 py-2 border border-outline-variant/20 rounded text-slate-500 font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm text-slate-400">visibility</span>
            معاينة العقدة
        </a>
    </div>

    <!-- Error Handling -->
    @if($errors->any())
    <div class="bg-error-container text-on-error-container p-6 rounded-lg border border-error/10">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-error rounded flex items-center justify-center text-white shadow-lg shrink-0">
                <span class="material-symbols-outlined">report</span>
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

    <form action="{{ route('servers.update', $server) }}" method="POST" class="space-y-10">
        @csrf
        @method('PUT')

        <!-- Infrastructure Blueprint -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-primary/5 rounded border border-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">identity_platform</span>
                </div>
                <h3 class="text-xl font-black text-primary tracking-tight uppercase italic">Identity Architecture</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Node Identification Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $server->name) }}" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-lg focus:ring-4 focus:ring-primary/5 focus:border-primary/40 font-black text-primary transition-all shadow-sm" required dir="ltr">
                </div>

                <!-- Hardware Reference -->
                <div class="bg-primary p-6 rounded flex items-center gap-6 shadow-lg shadow-primary/10">
                    <div class="w-16 h-16 shrink-0 bg-white/10 rounded flex items-center justify-center p-3 border border-white/10">
                        <img src="{{ $server->deviceModel->image_url ?? 'https://placehold.co/150x100?text=HW' }}" class="max-w-full max-h-full object-contain filter brightness-0 invert opacity-40">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[8px] font-black text-white/50 uppercase tracking-[0.2em] mb-0.5 italic">Assigned Hardware</p>
                        <p class="text-sm font-black text-white truncate uppercase italic tracking-widest font-manrope" dir="ltr">{{ $server->deviceModel->model_name ?? 'MikroTik Core' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Connection Governance -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-secondary/10 rounded border border-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">rss_feed</span>
                </div>
                <h3 class="text-xl font-black text-primary tracking-tight uppercase italic">Governance Stack</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Encrypted Gateway IP</label>
                    <input type="text" name="ip" value="{{ old('ip', $server->ip) }}" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary shadow-sm" dir="ltr">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol Port Index</label>
                    <input type="number" name="api_port" value="{{ old('api_port', $server->api_port) }}" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-manrope font-black text-primary shadow-sm" dir="ltr">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Access Identity (User)</label>
                    <input type="text" name="username" value="{{ old('username', $server->username) }}" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary shadow-sm" dir="ltr">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Secret Passcode (Unchanged if blank)</label>
                    <input type="password" name="password" autocomplete="new-password" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary shadow-sm" placeholder="••••••••••••" dir="ltr">
                </div>
            </div>
        </div>

        <!-- Geospatial Topology Registry -->
        <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 bg-primary/5 rounded border border-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <h3 class="text-xl font-black text-primary tracking-tight uppercase italic">Geospatial Registry</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Backbone Connectivity Source</label>
                    <select name="internet_source_id" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary italic">
                        <option value="">Autonomous Gateway (Standalone)</option>
                        @foreach($internetSources ?? [] as $source)
                        <option value="{{ $source->id }}" {{ old('internet_source_id', $server->internet_source_id) == $source->id ? 'selected' : '' }}>
                            🌐 {{ $source->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Structural Assembly (Tower)</label>
                    <select name="location_tower_id" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary italic">
                        <option value="">Virtual Infrastructure (Independent)</option>
                        @foreach($towers ?? [] as $tower)
                        <option value="{{ $tower->id }}" {{ old('location_tower_id', $server->location_tower_id) == $tower->id ? 'selected' : '' }}>
                            🗼 {{ $tower->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Physical Site Registry</label>
                    <input type="text" name="location" value="{{ old('location', $server->location) }}" class="w-full px-5 py-3.5 bg-white border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/10 font-black text-primary shadow-sm" placeholder="E.g. Distribution Room 1A">
                </div>

                <div class="md:col-span-2 space-y-6" x-data="serverMap()">
                    <div class="flex items-center justify-between">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Coordinate Synchronization Shield</label>
                        <div class="flex gap-2">
                            <input type="text" name="lat" x-model="lat" class="bg-surface-container-highest/10 border-none p-1.5 rounded text-[10px] font-manrope font-black text-primary w-24 text-center ring-1 ring-outline-variant/10" readonly>
                            <input type="text" name="lng" x-model="lng" class="bg-surface-container-highest/10 border-none p-1.5 rounded text-[10px] font-manrope font-black text-primary w-24 text-center ring-1 ring-outline-variant/10" readonly>
                        </div>
                    </div>
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <!-- Infrastructure Commitment -->
        <div class="p-8 bg-surface-container-low border border-outline-variant/10 rounded-lg flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-12 h-12 bg-secondary text-white rounded flex items-center justify-center shadow-lg shadow-secondary/10 italic font-black text-xl">S</div>
                <div>
                    <h5 class="text-sm font-black text-primary uppercase italic">Sync Infrastructure Commit</h5>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Applying governance adjustments to the core registry</p>
                </div>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('servers.show', $server) }}" class="px-8 py-3 bg-white border border-outline-variant/20 text-slate-500 font-bold text-xs uppercase tracking-widest rounded transition-all">
                    Discard
                </a>
                <button type="submit" class="px-10 py-3 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded shadow-xl shadow-primary/10 hover:scale-[1.02] active:scale-95 transition-all">
                    Apply Protocol Commit
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
        lat: '{{ old('lat', $server->lat) }}',
        lng: '{{ old('lng', $server->lng) }}',
        init() {
            setTimeout(() => {
                const startLat = this.lat || 33.5138;
                const startLng = this.lng || 36.2765;
                const map = L.map('map', {zoomControl: false}).setView([startLat, startLng], 13);
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
