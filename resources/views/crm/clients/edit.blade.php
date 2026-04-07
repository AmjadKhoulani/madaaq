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
     x-data="clientEditForm({ 
        client: {{ Js::from($client) }},
        servers: {{ Js::from($servers) }},
        deviceModels: {{ Js::from($deviceModels) }} 
     })"
     x-cloak>
     
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('crm.clients.show', $client) }}" class="w-12 h-12 rounded-2xl bg-white/40 backdrop-blur-md flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-white border border-white/30 transition-all shadow-sm group">
                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="relative">
                <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">تعديل المشترك</h2>
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1">
                    Editing: <span x-text="client.name || client.username"></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('crm.clients.show', $client) }}" class="px-6 py-3 bg-white/50 backdrop-blur-md text-gray-700 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-white/40 hover:bg-white transition-all">
                Cancel
            </a>
            <div class="h-8 w-px bg-gray-900/10 mx-2"></div>
            @if($client->status === 'active')
                <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إيقاف هذا المشترك؟')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-6 py-3 bg-rose-50/50 hover:bg-rose-50 text-rose-600 border border-rose-100 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all">
                        Suspend
                    </button>
                </form>
            @else
                <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-6 py-3 bg-emerald-50/50 hover:bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all">
                        Reactivate
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="glass-panel border-r-4 border-rose-500 p-6 mb-10 rounded-2xl relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl"></div>
            <div class="relative flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-rose-900 uppercase tracking-tight mb-2">Input Validation Error</h3>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs font-bold text-rose-700 flex items-center gap-2">
                                <span class="w-1 h-1 rounded-full bg-rose-400"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('crm.clients.update', $client) }}" method="POST" id="clientForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- LEFT COLUMN: Main Form -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. Subscriber Info -->
                <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all"></div>
                    <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                        بيانات المشترك
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">الاسم الكامل</label>
                            <input type="text" name="name" x-model="name" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400" required>
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2" x-data="{ 
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
                                    if(this.phone) this.iti.setNumber(this.phone);
                                    
                                    input.addEventListener('input', () => {
                                        document.getElementById('hiddenPhone').value = this.iti.getNumber();
                                    });
                                }
                            }
                        }" x-init="initPhone()">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">رقم الهاتف</label>
                            <div class="relative" dir="ltr">
                                <input type="tel" x-ref="phoneInput" x-model="phone" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400" required>
                                <input type="hidden" name="phone" id="hiddenPhone" :value="phone">
                            </div>
                        </div>

                        <!-- Portal Password -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">كلمة مرور البوابة (تحديث)</label>
                            <input type="text" name="password" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400" placeholder="اترك فارغاً لعدم التغيير">
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $client->email) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400">
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">العنوان بالتفصيل</label>
                            <input type="text" name="address" value="{{ old('address', $client->address) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400" placeholder="الشارع، البناء، الشقة">
                        </div>

                        <!-- City & District -->
                        <div class="grid grid-cols-2 gap-8 md:col-span-2">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">المدينة</label>
                                <input type="text" name="city" value="{{ old('city', $client->city) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">المنطقة</label>
                                <input type="text" name="district" value="{{ old('district', $client->district) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Service Details -->
                <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/5 rounded-full blur-3xl transition-all"></div>
                    <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-purple-600 rounded-full"></span>
                        تفاصيل الخدمة
                        <span class="mr-auto px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-gray-900/5 text-gray-500 border border-gray-900/5" x-text="connectionType.toUpperCase()"></span>
                    </h3>
                    
                    <input type="hidden" name="type" :value="connectionType">

                    <div class="space-y-8">
                        <!-- Infrastructure Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" x-show="connectionType === 'pppoe'">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">سيرفر التحكم</label>
                                <select name="mikrotik_server_id" x-model="selectedServerId" @change="updateTowers()" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none text-sm">
                                    <option value="">اختر السيرفر...</option>
                                    <template x-for="server in servers" :key="server.id">
                                        <option :value="server.id" x-text="server.name" :selected="server.id == selectedServerId"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">البرج</label>
                                <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none text-sm" :disabled="!selectedServerId">
                                    <option value="">اختر البرج...</option>
                                    <template x-for="tower in towers" :key="tower.id">
                                        <option :value="tower.id" x-text="tower.name" :selected="tower.id == selectedTowerId"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">الشبكة (SSID)</label>
                                <select name="ssid_id" x-model="selectedSSID" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none text-sm" :disabled="!selectedTowerId">
                                    <option value="">اختر الشبكة...</option>
                                    <template x-for="ssid in ssids" :key="ssid.id">
                                        <option :value="ssid.id" x-text="ssid.ssid_name" :selected="ssid.id == selectedSSID"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Credentials Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-900/5">
                             <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">اسم المستخدم (للاتصال)</label>
                                <input type="text" 
                                       :name="connectionType === 'pppoe' ? 'pppoe_username' : 'hotspot_username'" 
                                       class="w-full px-6 py-4 bg-gray-900/5 backdrop-blur-md border border-gray-900/5 rounded-2xl font-mono text-xs font-black text-indigo-600 cursor-not-allowed" 
                                       readonly
                                       :value="username">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1 ml-1">Phone number acts as identity</p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">كلمة مرور الخدمة (تحديث)</label>
                                <input type="text" name="service_password" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-mono text-sm font-black text-gray-900" 
                                       placeholder="Keep blank to retain existing key" 
                                       value="{{ old('service_password') }}"> 
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1 ml-1">Active Key: <span class="text-indigo-600 select-all">{{ $client->service_password }}</span></p>
                            </div>
                        </div>
                        
                        <!-- Hardware Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pt-8 border-t border-gray-900/5">
                             <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">نوع الربط (Core Technology)</label>
                                <select name="connection_mode" x-model="connectionMode" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 appearance-none text-sm">
                                    <option value="wireless">لاسلكي (Wireless)</option>
                                    <option value="tower_switch">سويتش مباشر (Direct Switch)</option>
                                    <option value="fiber">فايبر (Fiber / FTTH)</option>
                                    <option value="cable">كبل (Cable / Ethernet)</option>
                                    <option value="dsl">دي اس ال (DSL / VDSL)</option>
                                </select>
                            </div>
                             <div x-show="connectionMode === 'tower_switch'" class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">الطرفية (Switch)</label>
                                <select name="tower_device_id" x-model="selectedTowerDeviceId" class="w-full py-3.5 px-6 bg-emerald-50/50 backdrop-blur-md border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white focus:border-emerald-500/30 transition-all font-black text-emerald-600 appearance-none text-sm">
                                    <option value="">-- Choose Hardware --</option>
                                    <template x-for="device in towerDevices.filter(d => d.device_type === 'switch')" :key="device.id">
                                        <option :value="device.id" x-text="device.name + ' (' + (device.ports_count || '?') + ' P)'" :selected="device.id == selectedTowerDeviceId"></option>
                                    </template>
                                </select>
                            </div>
                            <div x-show="connectionMode !== 'tower_switch'" class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">موديل الجهاز (Selection)</label>
                                <select x-model="selectedDeviceModelId" @change="updateCpeModel()" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none text-sm">
                                    <option value="">أخرى / يدوي</option>
                                    <template x-for="model in deviceModels" :key="model.id">
                                        <option :value="model.id" x-text="model.name" :selected="model.id == selectedDeviceModelId"></option>
                                    </template>
                                </select>
                            </div>
                            <div x-show="connectionMode !== 'tower_switch'" class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1" x-text="connectionMode === 'fiber' ? 'ONT Model' : (connectionMode === 'dsl' ? 'Modem Model' : 'Unit Architecture')"></label>
                                <input type="text" name="cpe_model" x-model="cpeModel" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-black text-gray-900 text-sm">
                            </div>
                            <div x-show="connectionMode === 'wireless'" class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">عنوان IP (Radio)</label>
                                <input type="text" name="cpe_ip" value="{{ old('cpe_ip', $client->cpe_ip) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-mono text-sm font-black text-gray-900" placeholder="10.x.x.x">
                            </div>
                            <div x-show="connectionMode !== 'tower_switch'" class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">MAC Identity</label>
                                <input type="text" name="cpe_mac" 
                                       @input="$el.value = $el.value.toUpperCase().replace(/[^0-9A-F]/g, '').match(/.{1,2}/g)?.join(':').substr(0, 17) || $el.value" 
                                       value="{{ old('cpe_mac', $client->cpe_mac) }}" 
                                       class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-mono text-sm font-black text-gray-900" placeholder="AA:BB:CC:DD:EE:FF">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">رقم المنفذ (Access Port)</label>
                                <input type="text" name="switch_port" value="{{ old('switch_port', $client->switch_port) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-black text-gray-900 text-sm" placeholder="e.g. Port 5">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">عنوان IP (Subscriber)</label>
                                <input type="text" name="ip_address" value="{{ old('ip', $client->ip) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-mono text-sm font-black text-indigo-600" placeholder="172.x.x.x">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Billing & Package -->
                <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl transition-all"></div>
                    <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-2 h-8 bg-emerald-600 rounded-full"></span>
                        الباقة والمالية
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2 space-y-2">
                             <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">الباقة الحالية</label>
                             <select name="package_id" x-model="selectedPackageId" @change="loadPackageDefaults()" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white focus:border-emerald-500/30 transition-all font-bold text-gray-900 appearance-none text-sm">
                                <option value="">مخصص (Advanced Manual Control)</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            data-price="{{ $package->price }}" 
                                            data-limit="{{ $package->data_limit_mb }}">
                                        {{ $package->name }} - {{ $package->price }}$ ({{ $package->speed_down }}M/{{ $package->speed_up }}M)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Custom Settlement Price</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="price" x-model="customPrice" class="w-full pl-6 pr-12 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white focus:border-emerald-500/30 transition-all font-mono text-sm font-black text-gray-900" placeholder="0.00">
                                <span class="absolute right-6 top-4.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">$ USD</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Quota Allocation (GB)</label>
                             <input type="number" name="data_limit" x-model="customDataLimit" placeholder="Unlimited Access" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white focus:border-emerald-500/30 transition-all font-bold text-gray-900 text-sm">
                        </div>
                    </div>
                </div>

                <!-- 4. Location (Collapsible) -->
                <div class="glass-panel rounded-[2rem] overflow-hidden group">
                    <button type="button" @click="showMap = !showMap" class="w-full p-8 flex items-center justify-between hover:bg-white/40 transition-colors">
                         <h3 class="text-xl font-black text-gray-900 flex items-center gap-3">
                            <span class="w-2 h-8 bg-rose-500 rounded-full"></span>
                            الموقع الجغرافي
                        </h3>
                        <div class="w-10 h-10 rounded-xl bg-gray-900/5 flex items-center justify-center text-gray-400 transform transition-transform" :class="showMap ? 'rotate-180' : ''">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    
                    <div x-show="showMap" x-collapse>
                        <div class="p-8 pt-0">
                            <div id="map" class="mb-6 rounded-[1.5rem] overflow-hidden border border-gray-900/5 shadow-inner"></div>
                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Latitude</label>
                                    <input type="text" name="lat" x-model="lat" class="w-full px-6 py-4 bg-gray-900/5 border border-gray-900/5 rounded-2xl font-mono text-xs font-black text-gray-900 cursor-not-allowed" readonly>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Longitude</label>
                                    <input type="text" name="lng" x-model="lng" class="w-full px-6 py-4 bg-gray-900/5 border border-gray-900/5 rounded-2xl font-mono text-xs font-black text-gray-900 cursor-not-allowed" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">
                    <!-- Summary Card -->
                    <div class="bg-gradient-to-br from-gray-900 via-indigo-900 to-indigo-950 rounded-[2rem] shadow-2xl p-8 text-white relative overflow-hidden group">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl transition-all group-hover:scale-110"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-center mb-8 border-b border-white/10 pb-6">
                                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Account State</span>
                                @if($client->status === 'active')
                                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-emerald-500/20 text-emerald-300 border border-emerald-500/20">Active Hub</span>
                                @else
                                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-rose-500/20 text-rose-300 border border-rose-500/20">{{ strtoupper($client->status) }}</span>
                                @endif
                            </div>
                            
                            <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-2">Monthly Commitment</p>
                            <div class="text-6xl font-black tracking-tighter flex items-end gap-2 mb-8">
                                <span x-text="customPrice || '0.00'"></span>
                                <span class="text-xl text-indigo-400 font-bold mb-2">$</span>
                            </div>

                            <div class="space-y-4 mb-8 bg-black/20 backdrop-blur-md rounded-2xl p-6 border border-white/5">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Logic Type</span>
                                    <span class="font-black text-sm tracking-tight" x-text="connectionType.toUpperCase()"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Expiry Pulse</span>
                                    <span class="font-black text-sm text-yellow-400 tracking-tight">{{ $client->expires_at ? $client->expires_at->format('Y-M-d') : 'Non-Expiring' }}</span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full py-5 bg-white hover:bg-gray-50 text-indigo-950 font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                Sync Changes
                            </button>
                        </div>
                    </div>

                    <!-- Quick Warnings -->
                    @if($client->status == 'suspended')
                    <div class="glass-panel border-r-4 border-rose-500/50 p-6 rounded-2xl relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl"></div>
                        <h4 class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-rose-600 rounded-full animate-pulse"></span>
                             System Alert
                        </h4>
                        <p class="text-xs font-bold text-gray-900 leading-relaxed">Account is offline. Activate to restore service.</p>
                    </div>
                    @endif
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
        Alpine.data('clientEditForm', (data) => ({
            client: data.client,
            name: data.client.name || data.client.username,
            phone: data.client.phone || data.client.username,
            username: data.client.username,
            connectionType: data.client.type,
            connectionMode: data.client.connection_mode || 'wireless',
            
            servers: data.servers,
            selectedServerId: data.client.mikrotik_server_id,
            towers: [],
            selectedTowerId: data.client.tower_id,
            deviceModels: data.deviceModels || [],
            selectedDeviceModelId: data.client.device_model_id || '',
            cpeModel: data.client.cpe_model || '',
            towerDevices: [],
            selectedTowerDeviceId: data.client.tower_device_id || '',
            ssids: [],
            selectedSSID: data.client.ssid_id,
            
            selectedPackageId: data.client.package_id,
            customPrice: data.client.custom_price || (data.client.package ? data.client.package.price : ''),
            customDataLimit: data.client.custom_data_limit_mb ? (data.client.custom_data_limit_mb / 1024) : 
                             (data.client.package ? (data.client.package.data_limit_mb / 1024) : ''),
            
            // Map
            showMap: false,
            map: null,
            marker: null,
            lat: data.client.lat || '',
            lng: data.client.lng || '',

            init() {
                this.updateTowers();
                this.updateTowerData();
                
                this.$watch('showMap', (value) => {
                    if (value && !this.map) {
                        setTimeout(() => this.initMap(), 100);
                    }
                });
            },

            updateTowers() {
                // Keep selection if previously set and valid, else reset
                const oldTower = this.selectedTowerId;
                this.ssids = [];
                const server = this.servers.find(s => s.id == this.selectedServerId);
                this.towers = server ? (server.all_towers || server.towers) : [];
                
                // If the currently selected tower isn't in the new list, clear it
                if (!this.towers.find(t => t.id == oldTower)) {
                    this.selectedTowerId = '';
                }
            },
            updateTowerData() {
                const tower = this.towers.find(t => t.id == this.selectedTowerId);
                this.ssids = tower ? tower.ssids : [];
                this.towerDevices = tower ? tower.devices || [] : [];
                // Center Map if changed manually? Maybe not for Edit.
            },
            loadPackageDefaults() {
                const select = document.querySelector('select[name="package_id"]');
                const option = select.options[select.selectedIndex];
                if (option.dataset.price) {
                    this.customPrice = option.dataset.price;
                }
                if (option.dataset.limit) {
                    this.customDataLimit = (option.dataset.limit / 1024).toFixed(0);
                }
            },

            updateCpeModel() {
                const model = this.deviceModels.find(m => m.id == this.selectedDeviceModelId);
                if (model) {
                    this.cpeModel = model.name;
                }
            },

            initMap() {
                const initialLat = this.lat || 33.5138;
                const initialLng = this.lng || 36.2765;
                
                this.map = L.map('map').setView([initialLat, initialLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                
                if (this.lat && this.lng) {
                    this.marker = L.marker([this.lat, this.lng]).addTo(this.map);
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
