@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.16/build/css/intlTelInput.css">
<style>
    #map { height: 300px; width: 100%; border-radius: 0.5rem; z-index: 1; }
    [x-cloak] { display: none !important; }
    .iti { width: 100%; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto" 
     x-data="clientForm({ 
        servers: {{ Js::from($servers) }}, 
        deviceModels: {{ Js::from($deviceModels) }},
        lastIp: '{{ $lastIp }}'
     })"
     x-cloak>
     
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إضافة مشترك جديد</h2>
            <p class="text-sm text-gray-500">تسجيل سريع للاشتراك (Quick Entry)</p>
        </div>
        <a href="{{ route('crm.clients.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm">
            إلغاء
        </a>
    </div>

    <form action="{{ route('crm.clients.store') }}" method="POST" id="clientForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- LEFT COLUMN: Main Form -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. Subscriber Info (Horizontal Row) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        بيانات المشترك
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الاسم الكامل</label>
                            <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" required placeholder="محمد أحمد">
                        </div>

                        <!-- Phone -->
                        <div x-data="{ 
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
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">رقم الهاتف</label>
                            <div class="relative" dir="ltr">
                                <input type="tel" x-ref="phoneInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" required>
                                <input type="hidden" name="phone" id="hiddenPhone">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="example@mail.com">
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">العنوان بالتفصيل</label>
                            <input type="text" name="address" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="الشارع، البناء، الشقة">
                        </div>

                        <!-- City & District -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">المدينة</label>
                                <input type="text" name="city" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="دمشق">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">المنطقة</label>
                                <input type="text" name="district" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="المزة">
                            </div>
                        </div>

                        <!-- Portal Password -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">كلمة مرور البوابة</label>
                            <input type="text" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" required placeholder="********">
                        </div>
                    </div>
                </div>

                <!-- 2. Service Details (Tight Grid) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        تفاصيل الخدمة
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Type Selector -->
                        <div class="flex gap-4 p-1 bg-gray-50 rounded-lg border border-gray-200 w-fit">
                            <label class="cursor-pointer px-4 py-1.5 rounded-md text-sm font-medium transition" :class="connectionType === 'pppoe' ? 'bg-white shadow-sm text-blue-700' : 'text-gray-500 hover:text-gray-700'">
                                <input type="radio" name="type" value="pppoe" class="sr-only" x-model="connectionType">
                                PPPoE
                            </label>
                            <label class="cursor-pointer px-4 py-1.5 rounded-md text-sm font-medium transition" :class="connectionType === 'hotspot' ? 'bg-white shadow-sm text-purple-700' : 'text-gray-500 hover:text-gray-700'">
                                <input type="radio" name="type" value="hotspot" class="sr-only" x-model="connectionType">
                                Hotspot
                            </label>
                        </div>

                        <!-- Infrastructure Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" x-show="connectionType === 'pppoe'">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">سيرفر التحكم</label>
                                <select name="mikrotik_server_id" x-model="selectedServerId" @change="updateTowers()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm">
                                    <option value="">اختر السيرفر...</option>
                                    <template x-for="server in servers" :key="server.id">
                                        <option :value="server.id" x-text="server.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الموقع (برج / علبة توزيع)</label>
                                <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm font-bold" :disabled="!selectedServerId">
                                    <option value="">اختر الموقع...</option>
                                    <template x-for="tower in towers" :key="tower.id">
                                        <option :value="tower.id" x-text="(tower.type === 'cabinet' || tower.type === 'pole' ? '📦 ' : '🗼 ') + tower.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الشبكة (SSID)</label>
                                <select name="ssid" x-model="selectedSSID" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm" :disabled="!selectedTowerId || connectionMode !== 'wireless'">
                                    <option value="">اختر الشبكة...</option>
                                    <template x-for="ssid in ssids" :key="ssid.id">
                                        <option :value="ssid.ssid_name" x-text="ssid.ssid_name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1" x-text="connectionMode === 'wireless' ? 'جهاز البث (AP)' : 'السويتش (Switch)'"></label>
                                <select name="tower_device_id" x-model="selectedTowerDeviceId" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm font-black" :disabled="!selectedTowerId">
                                    <option value="">اختر الجهاز...</option>
                                    <template x-for="device in towerDevices.filter(d => (connectionMode === 'wireless' ? d.type === 'wireless' : d.type === 'switch'))" :key="device.id">
                                        <option :value="device.id" x-text="device.name + (device.ports_count ? ' (' + device.ports_count + ' Ports)' : '')"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Technical Details Sub-Section -->
                        <div class="pt-4 border-t border-gray-100" x-show="connectionType === 'pppoe'">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">تفاصيل فنية (Hardware)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">نوع الربط (Core Technology)</label>
                                    <select name="connection_mode" x-model="connectionMode" class="w-full px-3 py-2 border border-blue-100 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm bg-blue-50 font-bold">
                                        <option value="wireless">لاسلكي (Wireless)</option>
                                        <option value="tower_switch">سويتش مباشر (Direct Switch)</option>
                                        <option value="fiber">فايبر (Fiber / FTTH)</option>
                                        <option value="cable">كبل (Cable / Ethernet)</option>
                                        <option value="dsl">دي اس ال (DSL / VDSL)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Hardware Deployment Group -->
                            <div class="space-y-6 mt-6">
                                <div class="p-4 bg-indigo-50/30 rounded-2xl border border-indigo-100/50">
                                    <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                                        Interior Hub (Home Router / CPE)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Model Selection</label>
                                            <select x-model="selectedDeviceModelId" @change="updateCpeModel()" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none text-xs font-bold">
                                                <option value="">أخرى / يدوي</option>
                                                <template x-for="model in deviceModels" :key="model.id">
                                                    <option :value="model.id" x-text="model.name"></option>
                                                </template>
                                            </select>
                                            <input type="hidden" name="device_model_id" :value="selectedDeviceModelId">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Architecture Name</label>
                                            <input type="text" name="cpe_model" x-model="cpeModel" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none text-xs font-black" placeholder="e.g. hAP ax2">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Interior IP</label>
                                            <input type="text" name="cpe_ip" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none text-xs font-mono font-bold" placeholder="10.x.x.x">
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Port (Web)</label>
                                                <input type="number" name="cpe_port" value="80" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none text-xs font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Auth User</label>
                                                <input type="text" name="cpe_username" value="admin" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none text-xs font-bold">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Equipment Password</label>
                                            <input type="password" name="cpe_password" class="w-full px-3 py-2 bg-white border border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none text-xs font-bold" placeholder="••••••••">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Equipment MAC</label>
                                            <input type="text" name="cpe_mac" @input="$el.value = $el.value.toUpperCase().replace(/[^0-9A-F]/g, '').match(/.{1,2}/g)?.join(':').substr(0, 17) || $el.value" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none text-xs font-mono font-bold" placeholder="AA:BB:CC:DD:EE:FF">
                                        </div>
                                    </div>
                                </div>

                                <div x-show="connectionMode === 'wireless'" class="p-4 bg-emerald-50/30 rounded-2xl border border-emerald-100/50">
                                    <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Exterior Radio (Outdoor Receiver)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Radio Model</label>
                                            <input type="text" name="receiver_model" class="w-full px-3 py-2 bg-white border border-emerald-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none text-xs font-black" placeholder="e.g. LiteBeam 5AC">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Management IP</label>
                                            <input type="text" name="receiver_ip" class="w-full px-3 py-2 bg-white border border-emerald-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none text-xs font-mono font-bold" placeholder="10.x.x.x">
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Port</label>
                                                <input type="number" name="receiver_port" value="80" class="w-full px-3 py-2 bg-white border border-emerald-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none text-xs font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Radio User</label>
                                                <input type="text" name="receiver_username" value="admin" class="w-full px-3 py-2 bg-white border border-emerald-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none text-xs font-bold">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Radio Password</label>
                                            <input type="password" name="receiver_password" class="w-full px-3 py-2 bg-white border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none text-xs font-bold" placeholder="••••••••">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div x-show="connectionMode === 'tower_switch'">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Distribution Switch</label>
                                        <select name="tower_device_id" x-model="selectedTowerDeviceId" class="w-full px-3 py-2 border border-emerald-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none text-xs bg-emerald-50/50 font-black text-emerald-700">
                                            <option value="">-- Select Hub --</option>
                                            <template x-for="device in towerDevices" :key="device.id">
                                                <option :value="device.id" x-text="device.name + (device.ports_count ? ' (' + device.ports_count + ' Port)' : '')"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Assign Service IP</label>
                                        <input type="text" name="ip_address" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 outline-none text-xs font-black text-indigo-700 font-mono" placeholder="10.x.x.x">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credentials Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                             <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">اسم المستخدم (للاتصال)</label>
                                <input type="text" 
                                       :name="connectionType === 'pppoe' ? 'pppoe_username' : 'hotspot_username'" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none bg-blue-50 font-mono text-sm" 
                                       required placeholder="user_login">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">كلمة مرور الخدمة</label>
                                <input type="text" name="service_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none bg-blue-50 font-mono text-sm" required placeholder="service_pass">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Billing & Package -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        الباقة والمالية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">الباقة</label>
                             <select name="package_id" x-model="selectedPackageId" @change="loadPackageDefaults()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                                <option value="">مخصص (بدون باقة)</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            data-price="{{ $package->price }}" 
                                            data-speed="{{ $package->speed_down }}/{{ $package->speed_up }}"
                                            data-limit="{{ $package->data_limit_mb }}"
                                            data-duration="{{ $package->duration }}">
                                        {{ $package->name }} - {{ $package->price }}$ ({{ $package->speed_down }}M/{{ $package->speed_up }}M)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">السعر (شهري)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="price" x-model="customPrice" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none font-mono" placeholder="0.00">
                                <span class="absolute left-3 top-2.5 text-gray-400 text-sm">$</span>
                            </div>
                        </div>

                        <div>
                             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">حد البيانات (GB)</label>
                             <input type="number" name="data_limit" x-model="customDataLimit" placeholder="Unlimited" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                        </div>

                        <div>
                             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">المدة (أيام)</label>
                             <input type="number" name="duration_days" x-model="durationDays" placeholder="30" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-green-500 outline-none">
                        </div>

                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-500 uppercase mb-1">ملاحظات إضافية</label>
                             <textarea name="notes" rows="2" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none text-sm" placeholder="أية ملاحظات حول الموقع أو التمديد..."></textarea>
                        </div>
                    </div>
                </div>
                    </div>
                </div>

                <!-- 4. Location (Collapsible) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button type="button" @click="showMap = !showMap" class="w-full p-4 flex items-center justify-between hover:bg-gray-50 transition">
                         <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            الموقع الجغرافي (اختياري)
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="showMap ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div x-show="showMap" x-collapse>
                        <div class="p-5 pt-0">
                            <div id="map" class="mb-4"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="lat" x-model="lat" placeholder="Latitude" class="px-3 py-2 border rounded-lg bg-gray-50 text-xs" readonly>
                                <input type="text" name="lng" x-model="lng" placeholder="Longitude" class="px-3 py-2 border rounded-lg bg-gray-50 text-xs" readonly>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-4">
                    <!-- Summary Card -->
                    <div class="bg-gradient-to-br from-indigo-900 to-blue-900 rounded-xl shadow-lg p-6 text-white text-center">
                        <div class="text-indigo-200 text-sm font-medium mb-1">إجمالي الاشتراك</div>
                        <div class="text-4xl font-bold font-mono tracking-tight flex items-center justify-center gap-1">
                            <span x-text="customPrice || '0'"></span>
                            <span class="text-2xl">$</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/10 text-sm space-y-2 text-left">
                            <div class="flex justify-between">
                                <span class="text-indigo-200">النوع:</span>
                                <span class="font-bold" x-text="connectionType.toUpperCase()"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-indigo-200">الباقة:</span>
                                <span class="font-bold" x-text="packageName || 'مخصص'"></span>
                            </div>
                             <div class="flex justify-between">
                                <span class="text-indigo-200">الدفع:</span>
                                <span class="font-bold">شهري</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full mt-6 py-3 bg-white text-blue-900 font-bold rounded-lg shadow hover:bg-blue-50 transition transform active:scale-95">
                            ✅ حفظ وإنشاء
                        </button>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-4">
                        <h4 class="font-bold text-yellow-800 text-sm mb-2">تنبيهات سريعة</h4>
                        <ul class="text-xs text-yellow-700 space-y-1 list-disc list-inside">
                            <li>تأكد من اختيار البرج الصحيح لضمان التغطية.</li>
                            <li>كلمة مرور البوابة ترسل للعميل عبر SMS.</li>
                        </ul>
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
                this.towers = server ? server.towers : []; // Unified towers array from backend
            },
            updateTowerData() {
                const tower = this.towers.find(t => t.id == this.selectedTowerId);
                this.ssids = tower ? tower.ssids : [];
                
                // Filter devices based on connection mode if needed, or just provide full list for UI filtering
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
                    this.packageName = option.text.split('-')[0].trim();
                } else {
                    this.packageName = '';
                    this.customPrice = '';
                }

                if (option.dataset.limit) {
                    this.customDataLimit = (option.dataset.limit / 1024 / 1024 / 1024).toFixed(0); // Assuming MB to GB if needed, wait, let's see controller
                    // Controller line 121: custom_data_limit_mb = data_limit * 1024
                    // So data_limit input is in GB.
                    // Dataset limit is usually MB.
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
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
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
