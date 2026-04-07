@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 450px; width: 100%; z-index: 1; filter: grayscale(0.2) contrast(1.1); }
    .glass-map-frame { position: relative; border-radius: 2.5rem; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
    .coordinates-badge { position: absolute; bottom: 2rem; right: 2rem; z-index: 1000; background: rgba(255, 255, 255, 0.8); backdrop-blur: 12px; border: 1px solid rgba(255, 255, 255, 0.5); border-radius: 1.5rem; padding: 1rem 1.5rem; display: flex; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
    .form-toggle:checked + .toggle-track { background-color: #4f46e5; }
    .form-toggle:checked + .toggle-track::after { transform: translateX(100%); background-color: white; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto pb-24" x-data="towerForm">
    
    <!-- Navigation Header -->
    <div class="glass-panel border border-white/40 rounded-[2.5rem] p-6 mb-12 flex items-center justify-between shadow-xl relative overflow-hidden group">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-all duration-700"></div>
        <div class="flex items-center gap-6 relative">
            <a href="{{ route('network.towers.show', $tower) }}" class="w-14 h-14 bg-white/50 backdrop-blur-md border border-white/80 rounded-2xl flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm group-hover:rotate-[-5deg]">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Edit Hub <span class="text-indigo-600">Specs</span></h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] select-all">{{ $tower->name }}</p>
                </div>
            </div>
        </div>
        <div class="flex gap-4 relative">
             <a href="{{ route('network.towers.show', $tower) }}" class="px-8 py-3.5 bg-white border border-indigo-100 text-indigo-600 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-indigo-50 transition-all shadow-sm">
                View Instance
            </a>
            <a href="{{ route('network.towers.index') }}" class="px-8 py-3.5 bg-gray-900 text-white font-black text-[11px] uppercase tracking-widest rounded-2xl hover:scale-105 transition-all shadow-lg shadow-indigo-100">
                Cancel Update
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    <!-- Alert System -->
    @if($errors->any())
    <div class="glass-panel border border-rose-500/20 bg-rose-500/5 backdrop-blur-xl p-8 rounded-[2.5rem] mb-12 shadow-xl animate-shake">
        <div class="flex items-start gap-6">
            <div class="w-14 h-14 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-200 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h4 class="text-lg font-black text-rose-900 uppercase tracking-tight mb-2">Protocol Disruption</h4>
                <ul class="space-y-1.5">
                    @foreach($errors->all() as $error)
                    <li class="text-sm font-bold text-rose-700/80 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('network.towers.update', $tower) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Informatics -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center gap-4 mb-12">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Hub Identity</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Core site registration and classification</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Site Label / SSID Host</label>
                    <input type="text" name="name" required value="{{ old('name', $tower->name) }}"
                        class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm"
                        placeholder="e.g. TOWER_NORTH_CORE">
                </div>

                <!-- Type Selection -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 ml-1">Infrastructure Classification</label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @php
                            $types = [
                                'tower' => ['Tower', '🗼'],
                                'building' => ['Building', '🏢'],
                                'cabinet' => ['Cabinet', '🗄️'],
                                'pole' => ['Pole', '📍'],
                                'office' => ['Office', '🏛️']
                            ];
                        @endphp
                        @foreach($types as $value => $meta)
                        <label class="cursor-pointer group">
                            <input type="radio" name="type" value="{{ $value }}" x-model="siteType" class="peer sr-only">
                            <div class="text-center py-6 bg-white border border-gray-100 rounded-3xl peer-checked:border-indigo-600 peer-checked:bg-indigo-600 peer-checked:text-white transition-all shadow-sm group-hover:scale-105">
                                <div class="text-3xl mb-3">{{ $meta[1] }}</div>
                                <div class="text-[9px] font-black uppercase tracking-widest">{{ $meta[0] }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Location Details -->
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">City / Region</label>
                        <input type="text" name="city" value="{{ old('city', $tower->city) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900" placeholder="Dubai">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Sub-District</label>
                        <input type="text" name="district" value="{{ old('district', $tower->district) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                    </div>
                </div>
                
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Structural Height (m)</label>
                        <input type="number" name="height" value="{{ old('height', $tower->height) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Operational State</label>
                        <select name="status" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900 appearance-none">
                            <option value="active" {{ old('status', $tower->status) == 'active' ? 'selected' : '' }}>Operational (Live)</option>
                            <option value="maintenance" {{ old('status', $tower->status) == 'maintenance' ? 'selected' : '' }}>Maintenance Mode</option>
                            <option value="inactive" {{ old('status', $tower->status) == 'inactive' ? 'selected' : '' }}>Offline / Decommissioned</option>
                        </select>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Physical Access Protocol / Address</label>
                    <textarea name="location" rows="3" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900 resize-none" placeholder="Precise directions or site entry requirements...">{{ old('location', $tower->location) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Geospatial Grid -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Geospatial Plot</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Precise GIS coordinates for network mapping</p>
                </div>
            </div>
            
            <div class="glass-map-frame">
                <div id="map"></div>
                <!-- Coordinates Badge -->
                <div class="coordinates-badge">
                    <div>
                        <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Latitude</span>
                        <input type="text" name="lat" x-model="lat" readonly class="bg-transparent font-mono font-black text-gray-900 border-none p-0 focus:ring-0 text-sm select-all">
                    </div>
                    <div class="w-px bg-gray-200 my-1"></div>
                    <div>
                        <span class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Longitude</span>
                        <input type="text" name="lng" x-model="lng" readonly class="bg-transparent font-mono font-black text-gray-900 border-none p-0 focus:ring-0 text-sm select-all">
                    </div>
                </div>
            </div>
            <div class="mt-8 flex items-center gap-3 bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Interactive Plot: Tap map to update telemetry</p>
            </div>
        </div>

        <!-- Energy & Financial Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Power Configuration -->
            <div class="glass-panel border border-white/40 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Energy Grid</h3>
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mt-1">Autonomous power systems verification</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Solar -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasSolar ? 'ring-2 ring-indigo-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer mb-6">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">☀️</span> Solar Array
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_solar" value="1" x-model="hasSolar" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                        <div x-show="hasSolar" x-collapse>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="number" name="solar_panels_count" value="{{ old('solar_panels_count', $tower->solar_panels_count) }}" placeholder="Panel Count" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                                <input type="number" name="solar_panel_wattage" value="{{ old('solar_panel_wattage', $tower->solar_panel_wattage) }}" placeholder="Wattage (W)" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                            </div>
                        </div>
                    </div>

                    <!-- Generator -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasGenerator ? 'ring-2 ring-indigo-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer mb-4">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">🔌</span> Combustion Unit
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_generator" value="1" x-model="hasGenerator" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                        <div x-show="hasGenerator" x-collapse>
                            <input type="text" name="generator_capacity" value="{{ old('generator_capacity', $tower->generator_capacity) }}" placeholder="e.g. 15 KVA / Diesel" class="w-full px-5 py-3 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-bold text-gray-900">
                        </div>
                    </div>

                    <!-- Gov Electricity -->
                    <div class="bg-white/40 backdrop-blur-md rounded-3xl p-6 border border-white/60 group transition-all" :class="hasGovElec ? 'ring-2 ring-emerald-600 border-transparent shadow-lg bg-white/80' : ''">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                                <span class="text-xl">🏛️</span> Grid Electricity
                            </span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_government_electricity" value="1" x-model="hasGovElec" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-600 transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                        </label>
                    </div>

                    <!-- Batteries -->
                    <div class="bg-gray-900 p-8 rounded-[2rem] shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-x-1/2 translate-y-1/2 blur-2xl"></div>
                        <label class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-6 block">Chemical Energy Storage</label>
                        <div class="grid grid-cols-2 gap-4 relative z-10">
                            <input type="number" name="battery_count" value="{{ old('battery_count', $tower->battery_count) }}" placeholder="Units" class="bg-white/10 border border-white/10 rounded-xl px-5 py-3 text-white font-bold outline-none focus:bg-white/20 transition-all">
                            <input type="text" name="battery_type" value="{{ old('battery_type', $tower->battery_type) }}" placeholder="e.g. 12V 200AH" class="bg-white/10 border border-white/10 rounded-xl px-5 py-3 text-white font-bold outline-none focus:bg-white/20 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Ledger -->
            <div class="glass-panel border border-white/40 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Capital Ledger</h3>
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mt-1">Operational and construction burn rates</p>
                    </div>
                </div>
                
                <div class="space-y-10">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Structural Build CAPEX</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="structure_cost" value="{{ old('structure_cost', $tower->structure_cost) }}" class="w-full pl-16 pr-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-emerald-50 focus:border-emerald-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-xs font-black text-emerald-600 uppercase">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Node Monthly Lease</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="monthly_rent" value="{{ old('monthly_rent', $tower->monthly_rent) }}" class="w-full pl-16 pr-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 text-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-xs font-black text-indigo-600 uppercase">{{ $currency }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-emerald-600 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group/burn">
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover/burn:scale-125 transition-all"></div>
                        <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-2 relative z-10">Financial Health Disclosure</p>
                        <p class="text-sm font-bold text-white/80 leading-relaxed mb-6 relative z-10">Ensure all costs are audited for tax and operational compliance.</p>
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-white">💰</div>
                            <div>
                                <p class="text-[9px] font-black text-emerald-200 uppercase tracking-widest">Global Asset Cost</p>
                                <p class="text-xl font-black text-white tracking-widest">{{ number_format(($tower->structure_cost ?? 0) + ($tower->solar_installation_cost ?? 0), 0) }} {{ $currency }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Network Configuration -->
        <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden mt-12">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
            
            <div class="flex items-center gap-4 mb-12">
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Network Uplink</h3>
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">Routing and upstream topology definitions</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                 <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Gateway Controller</label>
                    <select name="mikrotik_server_id" x-model="serverId" class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 appearance-none shadow-sm">
                        <option value="">-- Standalone Node --</option>
                        @foreach($servers as $server)
                            <option value="{{ $server->id }}" {{ old('mikrotik_server_id', $tower->mikrotik_server_id) == $server->id ? 'selected' : '' }}>{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Transport Standard</label>
                    <select name="connection_type" x-model="connectionType" class="w-full px-8 py-5 bg-white border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 outline-none transition-all font-black text-gray-900 appearance-none shadow-sm">
                        <option value="">-- Choose Protocol --</option>
                        <option value="wireless">Wireless (PTP Array)</option>
                        <option value="fiber">Optical Fiber (FTTH)</option>
                        <option value="cable">Copper (Gigabit Ethernet)</option>
                    </select>
                </div>

                <!-- Wireless Grid (PTP) -->
                <div class="md:col-span-2 bg-indigo-600 p-10 rounded-[3rem] shadow-2xl relative overflow-hidden" x-show="connectionType === 'wireless'" x-transition>
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <h4 class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.3em] mb-10 pb-4 border-b border-white/10">Point-to-Point Configuration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Transmitter (TX) -->
                        <div class="space-y-8">
                            <label class="block text-[10px] font-black text-white uppercase tracking-widest opacity-60">Source Link (TX Array)</label>
                            
                             <div class="bg-white/10 p-2 rounded-2xl flex gap-2">
                                <button type="button" @click="transmitterType = 'existing'" :class="transmitterType === 'existing' ? 'bg-white text-indigo-600 shadow-sm' : 'text-indigo-200'" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">Stored</button>
                                <button type="button" @click="transmitterType = 'new'" :class="transmitterType === 'new' ? 'bg-white text-indigo-600 shadow-sm' : 'text-indigo-200'" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">New</button>
                            </div>

                            <div x-show="transmitterType === 'existing'">
                                <select name="transmitter_router_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none focus:bg-white/20">
                                    <option value="" class="text-gray-900">Choose Hardware...</option>
                                    @foreach($activeRouters as $router)
                                    <option value="{{ $router->id }}" class="text-gray-900" {{ old('transmitter_router_id', $tower->transmitter_router_id) == $router->id ? 'selected' : '' }}>{{ $router->name }} ({{ $router->ip }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="transmitterType === 'new'" class="space-y-4">
                                <input type="text" name="transmitter_name" placeholder="Radio Name" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black placeholder-white/30 outline-none">
                                <input type="text" name="transmitter_ip" placeholder="Target IP" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-mono placeholder-white/30 outline-none" dir="ltr">
                                <select name="transmitter_model_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none">
                                    <option value="" class="text-gray-900">Model Selection...</option>
                                    @foreach($deviceModels as $m)
                                    <option value="{{ $m->id }}" class="text-gray-900">{{ $m->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Receiver (RX) -->
                         <div class="space-y-8">
                            <label class="block text-[10px] font-black text-white uppercase tracking-widest opacity-60">Terminal Link (RX Array)</label>
                            <input type="text" name="receiver_name" value="{{ old('receiver_name', $tower->receiver_name) }}" placeholder="Terminal Handle" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-black placeholder-white/30 outline-none">
                            <input type="text" name="receiver_ip" value="{{ old('receiver_ip', $tower->receiver_ip) }}" placeholder="Control IP" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-mono placeholder-white/30 outline-none" dir="ltr">
                            <select name="receiver_model_id" class="w-full px-6 py-4 bg-white/10 border border-white/10 rounded-2xl text-white font-bold outline-none">
                                <option value="" class="text-gray-900">Radio Profile...</option>
                                @foreach($deviceModels as $m)
                                <option value="{{ $m->id }}" class="text-gray-900" {{ old('receiver_model_id', $tower->receiver_model_id) == $m->id ? 'selected' : '' }}>{{ $m->model_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Governance -->
        <div class="glass-panel border border-white/40 rounded-[2.5rem] p-8 flex items-center justify-between shadow-xl mt-12 bg-white/80 backdrop-blur-md">
             <a href="{{ route('network.towers.index') }}" class="text-[11px] font-black text-gray-500 uppercase tracking-widest hover:text-gray-900 transition-colors">Discard Sequence</a>
             <div class="flex gap-4">
                 <button type="reset" class="px-8 py-3.5 bg-gray-100 text-gray-600 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-gray-200 transition-all">Reset Form</button>
                 <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:scale-105 transition-all">
                    Commit Changes
                 </button>
             </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('towerForm', () => ({
            siteType: '{{ old('type', $tower->type) }}',
            lat: '{{ old('lat', $tower->lat) }}',
            lng: '{{ old('lng', $tower->lng) }}',
            hasSolar: {{ old('has_solar', $tower->has_solar) ? 'true' : 'false' }},
            hasGenerator: {{ old('has_generator', $tower->has_generator) ? 'true' : 'false' }},
            hasGovElec: {{ old('has_government_electricity', $tower->has_government_electricity) ? 'true' : 'false' }},
            hasSubscription: {{ old('has_ampere_subscription', $tower->has_ampere_subscription) ? 'true' : 'false' }},
            serverId: '{{ old('mikrotik_server_id', $tower->mikrotik_server_id) }}',
            connectionType: '{{ old('connection_type', $tower->connection_type) }}',
            transmitterType: 'existing',
            map: null,
            marker: null,

            init() {
                this.$nextTick(() => {
                    this.initMap();
                });
            },

            initMap() {
                // Default to Damascus if no coordinates
                const initialLat = this.lat ? parseFloat(this.lat) : 33.5138;
                const initialLng = this.lng ? parseFloat(this.lng) : 36.2765;

                this.map = L.map('map').setView([initialLat, initialLng], this.lat ? 15 : 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);

                if (this.lat && this.lng) {
                    this.marker = L.marker([initialLat, initialLng]).addTo(this.map);
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
