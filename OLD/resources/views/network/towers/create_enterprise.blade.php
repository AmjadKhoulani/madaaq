@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Compact Map for Enterprise View */
    #map { height: 250px; width: 100%; border-radius: 0.5rem; }
    
    /* Dense Form Styling */
    .form-dense label { font-size: 0.75rem; font-weight: 600; color: #4b5563; margin-bottom: 0.25rem; display: block; }
    .form-dense input, .form-dense select, .form-dense textarea {
        font-size: 0.875rem; padding: 0.4rem 0.6rem; border-radius: 0.375rem;
    }
    
    /* Section Card */
    .enterprise-card {
        background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem;
        height: 100%; display: flex; flex-direction: column;
    }
    .enterprise-card-header {
        padding: 0.75rem 1rem; border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center; gap: 0.5rem;
        background: #f9fafb; border-radius: 0.5rem 0.5rem 0 0;
    }
    .enterprise-card-body { padding: 1rem; flex: 1; }
</style>
@endpush

@section('content')
<div class="max-w-[1600px] mx-auto space-y-4" x-data="towerEnterprise">
    
    {{-- Top Bar --}}
    <div class="flex items-center justify-between bg-white px-6 py-3 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-bold text-gray-800">إضافة برج جديد (Enterprise)</h2>
            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded border border-indigo-200">وضع المحترفين</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="h-4 w-px bg-gray-300"></div>
            <button @click="submitForm" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                حفظ البرج
            </button>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded shadow-sm text-sm">
        <p class="font-bold text-red-700">يرجى التحقق من البيانات:</p>
        <ul class="list-disc list-inside text-red-600 mt-1">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('network.towers.store') }}" method="POST" id="tower-form" class="form-dense">
        @csrf

        {{-- Main Grid --}}
        <div class="grid grid-cols-12 gap-4">
            
            {{-- Column 1: Basic Info & Location (Width 4) --}}
            <div class="col-span-12 lg:col-span-4 space-y-4">
                {{-- Basic Info --}}
                <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-blue-600">📝</span> <h3 class="font-bold text-gray-700 text-sm">البيانات الأساسية</h3>
                    </div>
                    <div class="enterprise-card-body space-y-3">
                        <div>
                            <label>اسم البرج <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name') }}" class="w-full border-gray-300 focus:ring-blue-500" placeholder="اسم النقطة التعريفية">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>نوع الموقع</label>
                                <select name="type" class="w-full border-gray-300 focus:ring-blue-500">
                                    <option value="tower" {{ old('type') == 'tower' ? 'selected' : '' }}>🗼 برج</option>
                                    <option value="building" {{ old('type') == 'building' ? 'selected' : '' }}>🏢 مبنى</option>
                                    <option value="pole" {{ old('type') == 'pole' ? 'selected' : '' }}>📍 عمود</option>
                                </select>
                            </div>
                            <div>
                                <label>الحالة</label>
                                <select name="status" class="w-full border-gray-300 focus:ring-blue-500">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>المحافظة</label>
                                <select name="city" x-model="city" class="w-full border-gray-300 focus:ring-blue-500">
                                    <option value="">اختر...</option>
                                    <option value="Damascus">دمشق</option>
                                    <option value="Aleppo">حلب</option>
                                    {{-- Add others --}}
                                </select>
                            </div>
                            <div>
                                <label>المنطقة / الحي</label>
                                <input type="text" name="district" value="{{ old('district') }}" class="w-full border-gray-300 focus:ring-blue-500">
                            </div>
                        </div>

                         <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>الارتفاع (م)</label>
                                <input type="number" name="height" value="{{ old('height') }}" class="w-full border-gray-300 focus:ring-blue-500">
                            </div>
                            <div>
                                <label>الطوابق</label>
                                <input type="number" name="number_of_floors" value="{{ old('number_of_floors') }}" class="w-full border-gray-300 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Location Map --}}
                 <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-green-600">📍</span> <h3 class="font-bold text-gray-700 text-sm">الإحداثيات</h3>
                    </div>
                    <div class="enterprise-card-body p-0 relative">
                        <div id="map"></div>
                         <div class="grid grid-cols-2 gap-0 border-t border-gray-200">
                            <input type="text" name="lat" x-model="lat" readonly class="border-0 bg-gray-50 text-xs text-center border-r border-gray-200 py-1" placeholder="Lat">
                            <input type="text" name="lng" x-model="lng" readonly class="border-0 bg-gray-50 text-xs text-center py-1" placeholder="Lng">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 2: Network & Connectivity (Width 4) --}}
            <div class="col-span-12 lg:col-span-4 space-y-4">
                <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-indigo-600">🔗</span> <h3 class="font-bold text-gray-700 text-sm">الربط الشبكي</h3>
                    </div>
                    <div class="enterprise-card-body space-y-4">
                        <div>
                            <label>السيرفر المغذي</label>
                            <select name="mikrotik_server_id" class="w-full border-gray-300 focus:ring-blue-500">
                                <option value="">-- مباشر من المقسم --</option>
                                @foreach($servers as $server)
                                    <option value="{{ $server->id }}" {{ old('mikrotik_server_id') == $server->id ? 'selected' : '' }}>
                                        {{ $server->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>نوع الربط</label>
                                <select name="connection_type" x-model="connectionType" class="w-full border-gray-300 focus:ring-blue-500">
                                    <option value="">نوع الوسيط...</option>
                                    <option value="wireless">لاسلكي (Wireless)</option>
                                    <option value="fiber">ألياف (Fiber)</option>
                                    <option value="cable">كبل (Cable)</option>
                                </select>
                            </div>
                            <div>
                                <label>المنفذ (Port)</label>
                                <input type="text" name="connection_port" value="{{ old('connection_port') }}" class="w-full border-gray-300 focus:ring-blue-500" placeholder="ether1">
                            </div>
                        </div>

                        {{-- Wireless Fields --}}
                        <div x-show="connectionType === 'wireless'" class="bg-gray-50 p-3 rounded border border-gray-200 space-y-3">
                             <div>
                                <label class="text-indigo-800">جهاز الإرسال (AP)</label>
                                <select name="transmitter_router_id" @change="updateTransmitter" class="w-full border-gray-300 focus:ring-indigo-500 text-xs">
                                    <option value="">اختر المرسل...</option>
                                    @foreach($activeRouters as $router)
                                    <option value="{{ $router->id }}" data-ip="{{ $router->ip }}" {{ old('transmitter_router_id') == $router->id ? 'selected' : '' }}>
                                        {{ $router->name }} ({{ $router->ip }})
                                    </option>
                                    @endforeach
                                </select>
                                <div class="flex justify-between items-center mt-1">
                                    <span x-text="transmitterIP" class="text-xs font-mono text-gray-500"></span>
                                    <a href="#" @click.prevent="alert('Available in Wizard Mode')" class="text-xs text-blue-600 hover:underline">+ جديد</a>
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <div>
                                <label class="text-indigo-800">جهاز الاستقبال (CPE)</label>
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                     <select name="receiver_model_id" class="w-full border-gray-300 focus:ring-indigo-500 text-xs col-span-2">
                                        <option value="">موديل الجهاز...</option>
                                        @foreach($deviceModels as $model)
                                            <option value="{{ $model->id }}">{{ $model->manufacturer }} {{ $model->model_name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="receiver_ip" value="{{ old('receiver_ip') }}" placeholder="IP" class="form-input text-xs">
                                    <input type="text" name="receiver_ssid" value="{{ old('receiver_ssid') }}" placeholder="SSID" class="form-input text-xs">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 3: Power & Costs (Width 4) --}}
            <div class="col-span-12 lg:col-span-4 space-y-4">
                 <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-yellow-600">⚡</span> <h3 class="font-bold text-gray-700 text-sm">الطاقة</h3>
                    </div>
                    <div class="enterprise-card-body space-y-3">
                        <div class="flex items-center justify-between border-b pb-2 border-gray-100">
                             <label class="mb-0">🔌 كهرباء الدولة</label>
                             <input type="checkbox" name="has_government_electricity" value="1" {{ old('has_government_electricity') ? 'checked' : '' }}>
                        </div>

                        <div class="flex items-center justify-between">
                             <label class="mb-0">☀️ طاقة شمسية</label>
                             <input type="checkbox" name="has_solar" value="1" x-model="hasSolar">
                        </div>
                        <div x-show="hasSolar" class="grid grid-cols-2 gap-2 bg-yellow-50 p-2 rounded">
                            <input type="number" name="solar_panels_count" placeholder="عدد الألواح" class="text-xs">
                            <input type="number" name="solar_panel_wattage" placeholder="القدرة (واط)" class="text-xs">
                        </div>

                        <div class="flex items-center justify-between">
                             <label class="mb-0">🔋 بطاريات</label>
                             <div class="flex items-center gap-2">
                                <input type="number" name="battery_count" placeholder="العدد" class="w-16 text-center h-8">
                             </div>
                        </div>
                    </div>
                </div>

                 <div class="enterprise-card">
                    <div class="enterprise-card-header">
                        <span class="text-gray-600">💰</span> <h3 class="font-bold text-gray-700 text-sm">التكاليف</h3>
                    </div>
                    <div class="enterprise-card-body grid grid-cols-2 gap-3">
                         <div>
                            <label>آجار شهري</label>
                            <input type="number" name="monthly_rent" class="w-full">
                        </div>
                        <div>
                            <label>صيانة شهرية</label>
                            <input type="number" name="monthly_maintenance" class="w-full">
                        </div>
                        <div>
                            <label>كلفة الهيكل</label>
                            <input type="number" name="structure_cost" class="w-full">
                        </div>
                        <div>
                            <label>كلفة الطاقة</label>
                            <input type="number" name="solar_installation_cost" class="w-full">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('towerEnterprise', () => ({
            city: '{{ old("city") }}',
            lat: '{{ old("lat") }}',
            lng: '{{ old("lng") }}',
            connectionType: '{{ old("connection_type") }}',
            hasSolar: {{ old("has_solar") ? 'true' : 'false' }},
            transmitterIP: '',
            map: null,
            marker: null,

            init() {
                this.initMap();
            },

            submitForm() {
                document.getElementById('tower-form').submit();
            },

            initMap() {
                setTimeout(() => {
                    const startLat = this.lat || 33.5138;
                    const startLng = this.lng || 36.2765;
                    this.map = L.map('map').setView([startLat, startLng], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                    
                    this.map.on('click', (e) => {
                        this.lat = e.latlng.lat.toFixed(6);
                        this.lng = e.latlng.lng.toFixed(6);
                        if(this.marker) this.marker.setLatLng(e.latlng);
                        else this.marker = L.marker(e.latlng).addTo(this.map);
                    });
                }, 200);
            },

            updateTransmitter(e) {
                const opt = e.target.selectedOptions[0];
                this.transmitterIP = opt.dataset.ip || '';
            }
        }));
    });
</script>
@endsection
