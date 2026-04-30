# Source Catalog: cat2

## resources/views/crm\campaigns\create.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">حملة تسويقية عبر WhatsApp</h2>
        <p class="text-gray-500 mt-1">أرسل رسائل جماعية للعملاء</p>
    </div>

    @php
        $isConfigured = (new \App\Services\NotificationService())->isWhatsAppConfigured();
    @endphp

    @if(!$isConfigured)
    <div class="bg-orange-100 border-l-4 border-orange-500 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <p class="text-orange-800 font-bold">تنبيه: خدمة الواتساب غير مهيأة</p>
                <p class="text-orange-700 text-sm">يجب ضبط إعدادات Meta API أولاً لتتمكن من إرسال الحملات. <a href="{{ route('settings.index') }}" class="underline font-bold">انتقل للإعدادات</a></p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('crm.campaigns.store') }}" method="POST" class="space-y-6" x-data="{ targetType: 'all', selectedCount: 0 }">
        @csrf
        <input type="hidden" name="channel" value="whatsapp">

        <!-- Grid Layout: 2 columns -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Target Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <h3 class="text-lg font-bold text-gray-900">اختيار المستهدفين</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" :class="targetType === 'all' ? 'border-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="target" value="all" x-model="targetType" class="w-4 h-4 text-blue-600">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">جميع العملاء</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\Client::where('tenant_id', auth()->user()->tenant_id)->whereNotNull('phone')->count() }} عميل</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" :class="targetType === 'active' ? 'border-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="target" value="active" x-model="targetType" class="w-4 h-4 text-blue-600">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">العملاء النشطين</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\Client::where('tenant_id', auth()->user()->tenant_id)->where('status', 'active')->whereNotNull('phone')->count() }} عميل</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" :class="targetType === 'expiring' ? 'border-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="target" value="expiring" x-model="targetType" class="w-4 h-4 text-blue-600">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">قريبي الانتهاء</p>
                                <p class="text-xs text-gray-500">خلال 7 أيام</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" :class="targetType === 'inactive' ? 'border-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="target" value="inactive" x-model="targetType" class="w-4 h-4 text-blue-600">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">غير النشطين</p>
                                <p class="text-xs text-gray-500">لإعادة التفعيل</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Message Composer -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        <h3 class="text-lg font-bold text-gray-900">نص الرسالة</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">قالب جاهز</label>
                            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('message').value = this.value">
                                <option value="">-- مخصص --</option>
                                <option value="مرحباً @{{name}}، نقدم لك عرضاً خاصاً! احصل على خصم 20% عند تجديد اشتراكك اليوم.">عرض خصم 20%</option>
                                <option value="عزيزي @{{name}}، اشتراكك سينتهي قريباً. جدد الآن واحصل على شهر مجاناً!">تذكير تجديد</option>
                                <option value="@{{name}}، باقات جديدة بسرعات أعلى! اطلع عليها الآن.">باقات جديدة</option>
                                <option value="مرحباً @{{name}}، نفتقدك! عد واحصل على 30% خصم.">عودة عميل</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">الرسالة</label>
                            <textarea name="message" id="message" required rows="5" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 resize-none" placeholder="اكتب رسالتك... استخدم @{{name}} لإضافة الاسم"></textarea>
                            <p class="text-xs text-gray-500 mt-1">متغيرات: @{{name}}, @{{username}}, @{{package}}</p>
                        </div>

                        <!-- Preview -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                <span class="text-sm font-semibold text-green-900">معاينة</span>
                            </div>
                            <p class="text-sm text-gray-700 whitespace-pre-line" id="preview">اكتب رسالتك...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Width: Send Button -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-sm border border-green-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">جاهز للإرسال؟</h3>
                        <p class="text-sm text-gray-600">سيتم إرسال الرسالة فوراً إلى المستهدفين</p>
                    </div>
                </div>
                <button type="submit" {{ !$isConfigured ? 'disabled title=يجب_ضبط_الإعدادات_أولاً' : '' }} :class="!{{ $isConfigured ? 'true' : 'false' }} ? 'opacity-50 cursor-not-allowed' : ''" class="px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    إرسال الحملة
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('message').addEventListener('input', function() {
    const preview = document.getElementById('preview');
    const text = this.value || 'اكتب رسالتك...';
    preview.textContent = text.replace(/\{\{name\}\}/g, 'أحمد').replace(/\{\{username\}\}/g, 'ahmad123').replace(/\{\{package\}\}/g, 'باقة 100 ميجا');
});
</script>
@endsection

```

## resources/views/crm\clients\create.blade.php

```php
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

```

## resources/views/crm\clients\edit.blade.php

```php
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
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">الموقع (برج / علبة توزيع)</label>
                                <select name="tower_id" x-model="selectedTowerId" @change="updateTowerData()" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none text-sm" :disabled="!selectedServerId">
                                    <option value="">اختر الموقع...</option>
                                    <template x-for="tower in towers" :key="tower.id">
                                        <option :value="tower.id" x-text="(tower.type === 'cabinet' || tower.type === 'pole' ? '📦 ' : '🗼 ') + tower.name" :selected="tower.id == selectedTowerId"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">الشبكة (SSID)</label>
                                <select name="ssid_id" x-model="selectedSSID" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none text-sm" :disabled="!selectedTowerId || connectionMode !== 'wireless'">
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
                                                  <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">نوع الربط (Core Technology)</label>
                                <select name="connection_mode" x-model="connectionMode" class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 appearance-none text-sm">
                                    <option value="wireless">لاسلكي (Wireless)</option>
                                    <option value="tower_switch">سويتش مباشر (Direct Switch)</option>
                                               <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div x-show="connectionMode === 'tower_switch' || connectionMode === 'wireless'" class="space-y-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1" x-text="connectionMode === 'wireless' ? 'جهاز البث (AP)' : 'السويتش (Switch)'"></label>
                                    <select name="tower_device_id" x-model="selectedTowerDeviceId" class="w-full py-3.5 px-6 bg-emerald-50/50 backdrop-blur-md border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white focus:border-emerald-500/30 transition-all font-black text-emerald-600 appearance-none text-sm" :disabled="!selectedTowerId">
                                        <option value="">-- Choose Hardware --</option>
                                        <template x-for="device in towerDevices" :key="device.id">
                                            <option :value="device.id" x-text="device.name + (device.ports_count ? ' (' + device.ports_count + ' Port)' : '')" :selected="device.id == selectedTowerDeviceId"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">رقم المنفذ (Access Port)</label>
                                    <input type="text" name="switch_port" value="{{ old('switch_port', $client->switch_port) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-black text-gray-900 text-sm" placeholder="e.g. Port 5">
                                </div>
                            </div>

                            <!-- 1. Interior Hub (Home Router) -->
                            <div class="p-6 bg-indigo-500/5 rounded-3xl border border-indigo-500/10 space-y-6 mt-4">
                                <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                                    إعدادات الراوتر المنزلي (Home Router)
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">موديل الجهاز</label>
                                        <select x-model="selectedDeviceModelId" @change="updateCpeModel()" class="w-full py-3.5 px-6 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-gray-700 appearance-none text-sm">
                                            <option value="">أخرى / يدوي</option>
                                            <template x-for="model in deviceModels" :key="model.id">
                                                <option :value="model.id" x-text="model.name" :selected="model.id == selectedDeviceModelId"></option>
                                            </template>
                                        </select>
                                        <input type="hidden" name="device_model_id" :value="selectedDeviceModelId">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">اسم الموديل (كتابة)</label>
                                        <input type="text" name="cpe_model" x-model="cpeModel" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all font-black text-gray-900 text-sm">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">عنوان IP الداخلي</label>
                                        <input type="text" name="cpe_ip" value="{{ old('cpe_ip', $client->cpe_ip) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all font-mono text-sm font-black text-gray-900" placeholder="10.x.x.x">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">منفذ الويب</label>
                                            <input type="number" name="cpe_port" value="{{ old('cpe_port', $client->cpe_port ?? 80) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-gray-900 text-sm">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">يوزر الدخول</label>
                                            <input type="text" name="cpe_username" value="{{ old('cpe_username', $client->cpe_username) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-gray-900 text-sm" placeholder="admin">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">باسورد الجهاز</label>
                                        <input type="password" name="cpe_password" value="{{ old('cpe_password', $client->cpe_password) }}" class="w-full px-6 py-4 bg-white border border-indigo-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-gray-900 text-sm" placeholder="••••••••">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">MAC Identity</label>
                                        <input type="text" name="cpe_mac" @input="$el.value = $el.value.toUpperCase().replace(/[^0-9A-F]/g, '').match(/.{1,2}/g)?.join(':').substr(0, 17) || $el.value" value="{{ old('cpe_mac', $client->cpe_mac) }}" class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all font-mono text-sm font-black text-gray-900" placeholder="AA:BB:CC:DD:EE:FF">
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Exterior Radio (Receiver) -->
                            <div x-show="connectionMode === 'wireless'" class="p-6 bg-emerald-500/5 rounded-3xl border border-emerald-500/10 space-y-6 mt-6 transition-all">
                                <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    إعدادات جهاز الاستقبال الخارجي (Radio/Receiver)
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">موديل اللاقط</label>
                                        <input type="text" name="receiver_model" value="{{ old('receiver_model', $client->receiver_model) }}" class="w-full px-6 py-4 bg-white border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 transition-all font-black text-gray-900 text-sm" placeholder="e.g. LiteBeam 5AC">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">عنوان IP اللاقط</label>
                                        <input type="text" name="receiver_ip" value="{{ old('receiver_ip', $client->receiver_ip) }}" class="w-full px-6 py-4 bg-white border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 transition-all font-mono text-sm font-black text-emerald-600" placeholder="10.x.x.x">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">منفذ الويب</label>
                                            <input type="number" name="receiver_port" value="{{ old('receiver_port', $client->receiver_port ?? 80) }}" class="w-full px-6 py-4 bg-white border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-gray-900 text-sm">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">يوزر اللاقط</label>
                                            <input type="text" name="receiver_username" value="{{ old('receiver_username', $client->receiver_username) }}" class="w-full px-6 py-4 bg-white border border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-gray-900 text-sm" placeholder="admin">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">باسورد اللاقط</label>
                                        <input type="password" name="receiver_password" value="{{ old('receiver_password', $client->receiver_password) }}" class="w-full px-6 py-4 bg-white border border-emerald-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-gray-900 text-sm" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 pt-6">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">عنوان IP المشترك (الخدمة)</label>
                                <input type="text" name="ip_address" value="{{ old('ip', $client->ip) }}" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-mono text-sm font-black text-indigo-600" placeholder="10.x.x.x">
                            </div>-1">عنوان IP (Subscriber)</label>
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
                this.towers = server ? server.towers : []; // Unified towers array from backend
                
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

```

## resources/views/crm\clients\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="relative">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">إدارة العملاء</h2>
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                CRM Relations Management
            </p>
        </div>
        <a href="{{ route('crm.clients.create') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-200/50 transition-all transform hover:scale-105 active:scale-95 group">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>عميل جديد</span>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="glass-panel rounded-3xl p-6 mb-8 relative overflow-hidden group">
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all group-hover:bg-indigo-500/10"></div>
        <form method="GET" class="flex flex-col md:flex-row items-center gap-4 relative z-10">
            <div class="flex-1 w-full text-right">
                <div class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم، البريد، الهاتف..." class="w-full pl-6 pr-12 py-3.5 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400">
                    <span class="absolute right-4 top-3.5 text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <select name="status" class="w-full md:w-44 py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                
                <select name="type" class="w-full md:w-44 py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="">كل الأنواع</option>
                    <option value="pppoe" {{ request('type') == 'pppoe' ? 'selected' : '' }}>برودباند</option>
                    <option value="hotspot" {{ request('type') == 'hotspot' ? 'selected' : '' }}>هوت سبوت</option>
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto px-10 py-3.5 bg-gray-900 hover:bg-black text-white font-black rounded-2xl transition-all shadow-xl hover:shadow-gray-900/20 active:scale-95">
                تصفية
            </button>
        </form>
    </div>

    <!-- Clients Table -->
    <div class="glass-panel rounded-[2rem] overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/5">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/30 backdrop-blur-lg border-b border-white/20">
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">العميل</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">النوع</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الباقة</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الحالة</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">تاريخ الانتهاء</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($clients as $client)
                    <tr class="hover:bg-white/40 transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-50 to-white flex items-center justify-center text-indigo-700 font-black border border-white/50 shadow-sm transition-transform group-hover:scale-110 group-hover:rotate-3">
                                    {{ strtoupper(substr($client->username, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 font-black text-gray-900 leading-none">
                                        <span class="truncate max-w-[200px]">{{ $client->username }}</span>
                                        @if(auth()->user()->tenant->is_subdomain_enabled && empty($client->password))
                                            <svg class="w-4 h-4 text-orange-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        @endif
                                    </div>
                                    @if($client->name)
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-1">{{ $client->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($client->type === 'pppoe')
                                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-600 border border-blue-500/10">PPPoE</span>
                            @else
                                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest bg-purple-500/10 text-purple-600 border border-purple-500/10">Hotspot</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-black text-gray-700 tracking-tight">{{ $client->package->name ?? 'Custom Plan' }}</span>
                        </td>
                        <td class="px-8 py-6">
                            @if($client->status === 'active')
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-green-500/5 text-green-600 border border-green-500/10">
                                    <span class="w-2 h-2 bg-green-600 rounded-full ml-2 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-gray-500/5 text-gray-500 border border-gray-500/10">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full ml-2"></span>
                                    Disabled
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-xs font-black text-gray-600 tracking-tighter">
                            {{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'Permanent' }}
                        </td>
                        <td class="px-8 py-6 text-left">
                            <a href="{{ route('crm.clients.show', $client) }}" class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-white/50 backdrop-blur-sm text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-300 border border-white/40 shadow-sm hover:shadow-indigo-500/20 group-hover:translate-x-[-4px]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-6">
                                <div class="w-20 h-20 bg-white/50 backdrop-blur-md rounded-[2rem] flex items-center justify-center border border-white/30 shadow-lg shadow-gray-200/50">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div class="relative">
                                    <h3 class="text-xl font-black text-gray-900">لا يوجد عملاء متاحين</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2 text-center">No active client records found matching your criteria</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($clients->hasPages())
        <div class="px-8 py-6 border-t border-white/10 bg-white/20 backdrop-blur-lg">
            {{ $clients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

```

## resources/views/crm\clients\renew.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('crm.clients.show', $client) }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" class="transform rotate-180"/></svg>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">تجديد اشتراك</h2>
            <p class="mt-1 text-lg text-gray-500">{{ $client->name }} ({{ $client->username }})</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="h-2 bg-gradient-to-r from-green-500 to-teal-500"></div>

        <form action="{{ route('crm.clients.renew.post', $client) }}" method="POST" class="p-8 space-y-8" x-data="{ 
            mode: 'extend', 
            usePackage: true,
            packages: @js($packages),
            selectedPackageId: '',
            duration: 30,
            price: 0,
            
            updateFromPackage() {
                const pkg = this.packages.find(p => p.id == this.selectedPackageId);
                if (pkg) {
                    this.duration = pkg.duration_days || 30;
                    this.price = pkg.price || 0;
                }
            }
        }">
            @csrf
            
            <!-- Current Status -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">الوضع الحالي</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-gray-500 block">تاريخ الانتهاء</span>
                        <span class="font-mono font-bold text-gray-900">{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'غير محدد' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 block">حد البيانات</span>
                        <span class="font-mono font-bold text-gray-900">{{ $client->data_limit ? round($client->data_limit / 1024 / 1024 / 1024, 2) . ' GB' : 'غير محدود' }}</span>
                    </div>
                </div>
            </div>

            <!-- Renewal Mode -->
            <div>
                <label class="block text-sm font-bold text-gray-900 mb-3">طريقة التجديد</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-gray-50 transition peer-checked:border-green-500 peer-checked:ring-1 peer-checked:ring-green-500">
                        <input type="radio" name="renew_mode" value="extend" class="sr-only peer" x-model="mode">
                        <span class="flex flex-col">
                            <span class="block text-sm font-bold text-gray-900 peer-checked:text-green-600">تمديد (Extend)</span>
                            <span class="mt-1 text-xs text-gray-500">إضافة فوق الرصيد الحالي</span>
                        </span>
                        <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-green-500 pointer-events-none"></div>
                    </label>

                    <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-gray-50 transition peer-checked:border-red-500 peer-checked:ring-1 peer-checked:ring-red-500">
                        <input type="radio" name="renew_mode" value="reset" class="sr-only peer" x-model="mode">
                        <span class="flex flex-col">
                            <span class="block text-sm font-bold text-gray-900 peer-checked:text-red-600">تصفير وتجديد (Reset)</span>
                            <span class="mt-1 text-xs text-gray-500">تجاهل القديم وبدء دورة جديدة</span>
                        </span>
                        <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-red-500 pointer-events-none"></div>
                    </label>
                </div>
            </div>

            <!-- Package Selection -->
            <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
                <label class="block text-sm font-bold text-blue-900 mb-3">اختر باقة للتجديد (اختياري)</label>
                <select x-model="selectedPackageId" @change="updateFromPackage()" class="block w-full py-3 px-4 border-blue-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white transition">
                    <option value="">تخصيص يدوي...</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->price }} {{ $currency }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-blue-600 mt-2">اختيار باقة سيقوم بتعبئة المدة والسعر تلقائياً.</p>
            </div>

            <!-- Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Duration -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">مدة التجديد (يوم)</label>
                    <input type="number" name="duration_days" x-model="duration" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="30">
                </div>

                <!-- Data -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">البيانات الإضافية (GB)</label>
                    <input type="number" name="data_limit" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="0">
                </div>

                <!-- Price -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">القيمة المدفوعة ({{ $currency }})</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="price" x-model="price" class="block w-full pr-4 pl-12 py-3 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="0.00">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold">{{ $currency }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">سيتم إنشاء فاتورة مدفوعة بهذه القيمة</p>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                 <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold rounded-xl hover:shadow-lg hover:from-green-700 hover:to-teal-700 transition transform hover:-translate-y-0.5 w-full md:w-auto">
                    تأكيد التجديد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

```

## resources/views/crm\clients\show.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('crm.clients.index') }}" class="w-12 h-12 rounded-2xl bg-white/40 backdrop-blur-md flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-white border border-white/30 transition-all shadow-sm group">
                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="relative">
                <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
                <h2 class="text-4xl font-black text-gray-900 tracking-tight">{{ $client->username }}</h2>
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    {{ $client->name ?? 'Client Profile' }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('crm.clients.renew', $client) }}" class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-200/50 transition-all transform hover:scale-105 active:scale-95 group">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>تجديد الاشتراك</span>
            </a>

            <div class="h-10 w-px bg-gray-900/5 mx-2 hidden md:block"></div>

            <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                @if($client->status === 'active')
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-rose-50/50 hover:bg-rose-50 text-rose-600 border border-rose-100 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all hover:shadow-lg hover:shadow-rose-100 active:scale-95" onclick="return confirm('هل أنت متأكد من تعطيل هذا الحساب؟ سيتم فصل الإنترنت عنه فوراً.')">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                        Deactivate
                    </button>
                @else
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-50/50 hover:bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all hover:shadow-lg hover:shadow-emerald-100 active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Activate
                    </button>
                @endif
            </form>

            <a href="{{ route('crm.clients.edit', $client) }}" class="inline-flex items-center justify-center w-12 h-12 bg-white/50 backdrop-blur-md text-gray-400 hover:text-indigo-600 hover:bg-white rounded-2xl border border-white/40 transition-all shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </a>
        </div>
    </div>

    <!-- Stats Orbs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
        <!-- Invoices -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 text-blue-600">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">إجمالي الفواتير</p>
                <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['total_invoices']) }}</p>
                <p class="mt-4 text-[11px] font-bold text-blue-500 uppercase tracking-tighter">Billing Records</p>
            </div>
        </div>

        <!-- Paid -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-emerald-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 text-emerald-600">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">المدفوعات</p>
                <p class="text-3xl font-black text-emerald-600 tracking-tighter">@money($stats['total_paid'])</p>
                <p class="mt-4 text-[11px] font-bold text-emerald-500 uppercase tracking-tighter">Total Collected</p>
            </div>
        </div>

        <!-- Pending -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-orange-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl group-hover:bg-orange-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 text-orange-600">
                    <div class="w-12 h-12 rounded-2xl bg-orange-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">المبلغ المعلق</p>
                <p class="text-3xl font-black text-orange-600 tracking-tighter">@money($stats['pending_amount'])</p>
                <p class="mt-4 text-[11px] font-bold text-orange-600 uppercase tracking-tighter">Awaiting Settlement</p>
            </div>
        </div>

        <!-- Expiry -->
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:translate-y-[-4px] transition-all duration-500 hover:shadow-2xl hover:shadow-purple-500/10">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 text-purple-600">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">تاريخ الانتهاء</p>
                <p class="text-2xl font-black text-gray-900 tracking-tight">{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'Permanent' }}</p>
                <p class="mt-4 text-[11px] font-bold text-purple-500 uppercase tracking-tighter">Contract Duration</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Info Cards -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all"></div>
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    Identity & Connection
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Username Account</span>
                        <span class="font-black text-gray-900 tracking-tight">{{ $client->username }}</span>
                    </div>
                    @if($client->name)
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Full Legal Name</span>
                        <span class="font-black text-gray-900 tracking-tight">{{ $client->name }}</span>
                    </div>
                    @endif
                    @if($client->email)
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">E-mail Address</span>
                        <span class="font-black text-gray-900 tracking-tight">{{ $client->email }}</span>
                    </div>
                    @endif
                    @if($client->phone)
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Mobile Phone</span>
                        <span class="font-black text-indigo-600 tracking-tight dir-ltr text-right">{{ $client->phone }}</span>
                    </div>
                    @endif
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Service Type</span>
                        <span class="font-black text-gray-900 tracking-tight uppercase">{{ $client->type === 'pppoe' ? 'Broadband (PPPoE)' : 'Hotspot (Portal)' }}</span>
                    </div>
                    <div class="flex flex-col p-5 bg-white/40 backdrop-blur-sm rounded-2xl border border-white/40 transition-all hover:bg-white/60">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Assigned Package</span>
                        <span class="px-4 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-black inline-block w-fit tracking-tight shadow-md">{{ $client->package->name ?? 'Custom Plan' }}</span>
                    </div>
                </div>
            </div>

            <!-- Network & CPE Access (Premium) -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group border border-indigo-500/10 shadow-2xl shadow-indigo-500/5">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl transition-all group-hover:bg-indigo-500/20"></div>
                
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-gray-900 flex items-center gap-3">
                        <span class="w-1.5 h-8 bg-indigo-600 rounded-full"></span>
                        Equipment Intelligence & Remote Access
                    </h3>
                    @if($client->mikrotik_server_id)
                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-500/10">Edge Node Connected</span>
                    @else
                        <span class="px-3 py-1 bg-gray-500/10 text-gray-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gray-500/10">No Management Node</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- 1. Interior Router (CPE) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="p-2 bg-indigo-600/10 text-indigo-600 rounded-lg uppercase text-[10px] font-black tracking-widest">Device 1: Interior Router</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-900/5 rounded-2xl border border-gray-900/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Model / Vendor</span>
                                <span class="text-sm font-black text-gray-900">{{ $client->cpe_model ?? 'Generic CPE' }}</span>
                            </div>
                            <div class="p-4 bg-gray-900/5 rounded-2xl border border-gray-900/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Network IP</span>
                                <span class="text-sm font-black text-indigo-600 font-mono">{{ $client->cpe_ip ?? 'Not Set' }}</span>
                            </div>
                        </div>

                        @if($client->cpe_ip && $client->mikrotik_server_id)
                        <div class="p-4 bg-indigo-600/5 rounded-2xl border border-indigo-600/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $client->cpe_username ?? 'admin' }}</p>
                                    <p class="text-[10px] font-bold text-gray-900">Encrypted Auth</p>
                                </div>
                            </div>
                            <a href="{{ route('crm.clients.cpe-proxy', ['client' => $client, 'type' => 'cpe']) }}" target="_blank" class="px-5 py-2 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-indigo-700 transition-all">
                                Open Webfig
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- 2. Exterior Receiver (Radio) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="p-2 bg-emerald-600/10 text-emerald-600 rounded-lg uppercase text-[10px] font-black tracking-widest">Device 2: Outdoor Radio</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-900/5 rounded-2xl border border-gray-900/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Radio Model</span>
                                <span class="text-sm font-black text-gray-900">{{ $client->receiver_model ?? 'Generic Radio' }}</span>
                            </div>
                            <div class="p-4 bg-gray-900/5 rounded-2xl border border-gray-900/5">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Radio IP</span>
                                <span class="text-sm font-black text-emerald-600 font-mono">{{ $client->receiver_ip ?? 'Not Set' }}</span>
                            </div>
                        </div>

                        @if($client->receiver_ip && $client->mikrotik_server_id)
                        <div class="p-4 bg-emerald-600/5 rounded-2xl border border-emerald-600/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $client->receiver_username ?? 'admin' }}</p>
                                    <p class="text-[10px] font-bold text-gray-900">Encrypted Auth</p>
                                </div>
                            </div>
                            <a href="{{ route('crm.clients.cpe-proxy', ['client' => $client, 'type' => 'receiver']) }}" target="_blank" class="px-5 py-2 bg-emerald-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-emerald-700 transition-all">
                                Open Dashboard
                            </a>
                        </div>
                        @else
                        <div class="p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200 text-center">
                            <p class="text-[10px] font-bold text-gray-400 italic">No Outdoor Equipment Registered</p>
                        </div>
                        @endif
                    </div>
                </div>

                <p class="mt-8 text-[9px] text-gray-400 font-bold px-2 italic text-left">
                    Network nodes managed via: <span class="text-indigo-600">{{ $client->mikrotikServer->name ?? 'Primary Infrastructure' }}</span>
                </p>
            </div>

            <!-- Client Portal Access -->
            @if(auth()->user()->tenant->is_subdomain_enabled)
            <!-- Client Portal Access -->
            @if(auth()->user()->tenant->is_subdomain_enabled)
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-gray-900 flex items-center gap-3">
                        <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                        Subscriber Portal Access
                    </h3>
                    @if(empty($client->password))
                        <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-rose-500/10 text-rose-600 border border-rose-500/10 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-rose-600 rounded-full"></span>
                             Inactive
                        </span>
                    @else
                        <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-600 border border-emerald-500/10 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-emerald-600 rounded-full animate-pulse"></span>
                             Portal Ready
                        </span>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-gray-900/5 backdrop-blur-md rounded-2xl p-6 border border-gray-900/5 space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 border-b border-gray-900/5 pb-4">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Authentication URL</span>
                            <span class="font-mono text-xs font-black text-indigo-600 dir-ltr text-right select-all truncate max-w-[300px]">
                                {{ 'http://' . (str_contains(auth()->user()->tenant->domain, '.') ? auth()->user()->tenant->domain : auth()->user()->tenant->domain . '.' . str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST))) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Login Identity</span>
                            <span class="font-black text-sm text-gray-900 select-all">{{ $client->username }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button onclick="copyCredentials()" class="flex items-center justify-center gap-3 px-6 py-3.5 bg-white/50 hover:bg-white text-gray-700 rounded-2xl border border-white/40 shadow-sm transition-all font-black text-[11px] uppercase tracking-widest group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copy Payload
                        </button>
                        
                        <form action="{{ route('crm.clients.send-credentials', $client) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="flex items-center justify-center gap-3 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl shadow-xl shadow-emerald-200/50 transition-all font-black text-[11px] uppercase tracking-widest active:scale-95 w-full">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Send via WhatsApp
                            </button>
                        </form>
                    </div>

                    <!-- Update Password -->
                    <form action="{{ route('crm.clients.update-password', $client) }}" method="POST" class="pt-8 border-t border-gray-900/5">
                        @csrf
                        @method('PUT')
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Renew Portal Access Key</label>
                        <div class="flex gap-3">
                            <input type="text" name="password" placeholder="New high-security password..." class="flex-1 px-6 py-3.5 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400 text-sm" minlength="6" required>
                            <button type="submit" class="px-8 py-3.5 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl transition-all shadow-xl hover:shadow-gray-900/20 active:scale-95">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @endif

            <!-- Activity Timeline -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group h-fit">
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
                    Activity Pulse
                </h3>
                <div class="space-y-8 relative before:absolute before:right-1 before:top-2 before:bottom-0 before:w-0.5 before:bg-gray-900/5">
                    @forelse($client->activities as $activity)
                    <div class="flex gap-6 pr-6 relative">
                        <div class="absolute right-0 top-1.5 w-2 h-2 rounded-full bg-blue-500 ring-4 ring-blue-500/10"></div>
                        <div class="flex-1">
                            <p class="text-sm font-black text-gray-900 leading-tight">{{ $activity->description }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-12 h-12 bg-gray-900/5 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Silence is golden. No activity.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Notes -->
        <div class="space-y-8">
            <!-- Add Note -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-gray-900 rounded-full"></span>
                    Observer Log
                </h3>
                <form action="{{ route('crm.clients.notes.store', $client) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <select name="type" required class="w-full py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none text-sm">
                            <option value="general">General Narrative</option>
                            <option value="technical">Technical Report</option>
                            <option value="billing">Financial Log</option>
                        </select>
                    </div>
                    <div>
                        <textarea name="content" required rows="4" class="w-full px-6 py-4 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400 text-sm resize-none" placeholder="Record your observations..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-gray-900 hover:bg-black text-white font-black text-[11px] uppercase tracking-widest rounded-2xl shadow-xl hover:shadow-gray-900/20 transition-all active:scale-95">
                        Commit Observation
                    </button>
                </form>
            </div>

            <!-- Notes List -->
            <div class="glass-panel rounded-[2rem] p-8 relative overflow-hidden group">
                <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-gray-400/20 rounded-full"></span>
                    Log Records
                </h3>
                <div class="space-y-4">
                    @forelse($client->clientNotes as $note)
                    <div class="p-6 bg-white/40 backdrop-blur-sm rounded-2xl border-r-4 {{ $note->type === 'technical' ? 'border-purple-500' : ($note->type === 'billing' ? 'border-emerald-500' : 'border-blue-500') }} transition-all hover:bg-white/60">
                        <p class="text-xs font-bold text-gray-900 leading-relaxed">{{ $note->content }}</p>
                        <div class="flex items-center justify-between mt-4 text-[9px] font-black uppercase tracking-widest text-gray-400">
                            <span>{{ $note->type }}</span>
                            <span>{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No manual logs present</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>

<script>
function copyCredentials() {
    const link = "{{ 'http://' . (str_contains(auth()->user()->tenant->domain, '.') ? auth()->user()->tenant->domain : auth()->user()->tenant->domain . '.' . str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST))) }}";
    const username = "{{ $client->username }}";
    
    const text = `رابط البوابة: ${link}\nاسم المستخدم: ${username}`;
    
    navigator.clipboard.writeText(text).then(() => {
        alert('تم نسخ بيانات الدخول!');
    }).catch(err => {
        console.error('فشل النسخ', err);
    });
}
</script>
@endsection

```

## resources/views/crm\settings\payments\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">إعدادات بوابات الدفع</h1>
            <p class="text-gray-500 mt-2">قم تفعيل وتعطيل بوابات الدفع وإدارة مفاتيح الربط (API Keys)</p>
        </div>
        <button type="submit" form="payment-settings-form" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            حفظ التغييرات
        </button>
    </div>

    <form id="payment-settings-form" action="{{ route('crm.settings.payments.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Stripe -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.895-1.305 2.623-1.305 2.378 0 4.255.88 5.279 1.64l.896-1.92c-.939-.636-2.78-1.534-4.856-1.534-3.57 0-5.645 2.155-5.645 5.344 0 2.503 1.91 3.588 5.405 4.673 2.17.7 2.978 1.455 2.978 2.518 0 1.059-1.288 1.547-2.924 1.547-2.316 0-5.467-1.127-6.529-1.995l-.953 2.05c1.233.916 3.65 1.765 6.015 1.765 4.09 0 5.922-2.189 5.922-5.424 0-2.822-1.893-3.8-4.854-4.94z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Stripe</h3>
                            <p class="text-xs text-gray-500">دفع عبر البطاقات الائتمانية</p>
                        </div>
                    </div>
                    <!-- Toggle -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="stripe_active" value="0">
                        <input type="checkbox" name="stripe_active" value="1" class="sr-only peer" {{ $settings['stripe_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Public Key</label>
                        <input type="text" name="stripe_public_key" value="{{ $settings['stripe_public_key'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors" placeholder="pk_test_...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                        <input type="password" name="stripe_secret_key" value="{{ $settings['stripe_secret_key'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors" placeholder="sk_test_...">
                    </div>
                </div>
            </div>

            <!-- PayPal -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7.076 21.337l.846-5.368h2.035c3.279 0 5.962-1.353 6.64-4.827.275-1.407-.152-2.583-1.222-3.415-1.235-.961-3.141-1.397-5.834-1.397h-4.3l-.226 1.442-.879 5.619-.661 4.305h3.601zm3.848-12.872h1.69c1.936 0 3.16.275 3.514 1.762.247 1.037-.089 1.933-1.492 2.373-.666.212-1.611.233-2.68.233h-1.921l.889-4.368z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">PayPal</h3>
                            <p class="text-xs text-gray-500">دفع عالمي آمن</p>
                        </div>
                    </div>
                     <!-- Toggle -->
                     <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="paypal_active" value="0">
                        <input type="checkbox" name="paypal_active" value="1" class="sr-only peer" {{ $settings['paypal_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client ID</label>
                        <input type="text" name="paypal_client_id" value="{{ $settings['paypal_client_id'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Secret</label>
                        <input type="password" name="paypal_secret" value="{{ $settings['paypal_secret'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Cham Cash (Merchant) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center text-white font-bold">
                            CC
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Cham Cash <span class="text-xs font-normal text-green-600 bg-green-50 px-2 py-0.5 rounded-full ml-1">Merchant</span></h3>
                            <p class="text-xs text-gray-500">حساب تجار (API Integration)</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="cham_cash_active" value="0">
                        <input type="checkbox" name="cham_cash_active" value="1" class="sr-only peer" {{ $settings['cham_cash_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Merchant ID</label>
                        <input type="text" name="cham_cash_merchant_id" value="{{ $settings['cham_cash_merchant_id'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                        <input type="password" name="cham_cash_secret_key" value="{{ $settings['cham_cash_secret_key'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Cham Cash (Personal/Manual) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-600 flex items-center justify-center text-white font-bold">
                            QR
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Cham Cash <span class="text-xs font-normal text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full ml-1">Manual/QR</span></h3>
                            <p class="text-xs text-gray-500">تحويل يدوي (QR Code)</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="cham_cash_personal_active" value="0">
                        <input type="checkbox" name="cham_cash_personal_active" value="1" class="sr-only peer" {{ $settings['cham_cash_personal_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-600"></div>
                    </label>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">رقم المحفظة</label>
                        <input type="text" name="cham_cash_personal_wallet" value="{{ $settings['cham_cash_personal_wallet'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-gray-500 focus:ring-gray-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">صورة QR Code</label>
                        <div class="space-y-3">
                            <input type="file" name="cham_cash_personal_qr" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 file:text-violet-700
                                hover:file:bg-violet-100
                            "/>
                            @if($settings['cham_cash_personal_qr'])
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 inline-block">
                                    <p class="text-xs text-gray-500 mb-2 font-semibold">الصورة الحالية:</p>
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['cham_cash_personal_qr']) }}" class="h-40 w-auto rounded border border-gray-300 shadow-sm object-contain bg-white">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Syriatel Cash -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-red-600 flex items-center justify-center text-white font-bold">
                            SC
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Syriatel Cash</h3>
                            <p class="text-xs text-gray-500">سيريتل كاش (API)</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="syriatel_cash_active" value="0">
                        <input type="checkbox" name="syriatel_cash_active" value="1" class="sr-only peer" {{ $settings['syriatel_cash_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>
                 <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Merchant ID</label>
                        <input type="text" name="syriatel_cash_merchant_id" value="{{ $settings['syriatel_cash_merchant_id'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PIN / Secret</label>
                        <input type="password" name="syriatel_cash_pin" value="{{ $settings['syriatel_cash_pin'] }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</div>
@endsection

```

## resources/views/whatsapp\index.blade.php

```php
@extends('layouts.app')

@section('content')
<!-- Super WhatsApp Interface -->
<div class="flex-1 flex w-full bg-[#f0f2f5] overflow-hidden relative" dir="rtl">
    
    <!-- Right Sidebar: Contacts List (w-1/4 or fixed width) -->
    <div class="w-[320px] bg-white border-l border-gray-200 flex flex-col h-full z-10 shrink-0">
        <!-- Header -->
        <div class="h-16 bg-[#00a884] px-4 flex items-center justify-between shrink-0">
            <h2 class="text-white font-bold text-lg flex items-center gap-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                محادثات العملاء
            </h2>
            <div class="flex gap-2">
                <button class="p-2 text-white/80 hover:text-white transition rounded-full hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="p-3 border-b border-gray-100 bg-white">
            <div class="relative">
                <input type="text" placeholder="بحث أو بدء محادثة جديدة" class="w-full pl-4 pr-10 py-2 bg-[#f0f2f5] rounded-lg text-sm border-none focus:ring-0 text-gray-700">
                <div class="absolute inset-y-0 right-0 pl-3 flex items-center pointer-events-none pr-3">
                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Contact List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @foreach($clients as $c)
                <a href="{{ route('whatsapp.show', $c->id) }}" class="flex items-center gap-3 p-3 border-b border-gray-50 hover:bg-[#f5f6f6] transition group cursor-pointer {{ isset($client) && $client->id == $c->id ? 'bg-[#f0f2f5]' : '' }}">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold overflow-hidden">
                            @if(isset($c->profile_photo_url)) 
                                <img src="{{ $c->profile_photo_url }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($c->name ?? $c->username, 0, 1) }}
                            @endif
                        </div>
                        @if($c->status === 'active')
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                        @else
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></div>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-semibold text-gray-900 truncate group-hover:text-black">{{ $c->name ?? $c->username }}</h3>
                            <span class="text-[11px] text-gray-400">12:30 م</span>
                        </div>
                        <p class="text-sm text-gray-500 truncate flex items-center gap-1">
                            <span class="text-gray-400">✓✓</span> 
                            انقر لعرض المحادثة
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Center: Main Chat Area -->
    <div class="flex-1 flex flex-col h-full relative bg-[#efeae2] before:content-[''] before:absolute before:inset-0 before:opacity-[0.06] before:bg-[url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png')]">
        
        @if(!$isConfigured)
        <div class="bg-orange-100 border-b border-orange-200 text-orange-800 px-4 py-3 flex items-center justify-between shrink-0 z-30 relative">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="font-bold text-sm">ميزة الواتساب غير مفعلة</p>
                    <p class="text-xs text-orange-700">لاستخدام هذه الميزة وإرسال الرسائل، يجب إعداد WhatsApp Cloud API أولاً.</p>
                </div>
            </div>
            <a href="{{ route('settings.index') }}" class="text-xs bg-orange-600 text-white px-3 py-1.5 rounded-md hover:bg-orange-700 transition font-bold shadow-sm whitespace-nowrap">
                ⚙️ إعداد الخدمة
            </a>
        </div>
        @endif

        @if(isset($client))
            <!-- Sticky Header -->
            <header class="h-16 bg-[#00a884] px-4 flex items-center justify-between shrink-0 z-20 shadow-sm border-l border-green-700/20">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold backdrop-blur-sm">
                        {{ substr($client->name ?? $client->username, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-base">{{ $client->name ?? $client->username }}</h3>
                        <p class="text-xs text-green-100 flex items-center gap-1">
                            {{ $client->status == 'active' ? 'مشترك نشط' : 'مشترك غير نشط' }}
                            @if($client->status == 'active') • ينتهي في {{ \Carbon\Carbon::parse($client->expires_at)->format('Y-m-d') }} @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-white">
                    <button class="p-2 hover:bg-white/10 rounded-full" title="جعل غير مقروء"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></button>
                    <button class="p-2 hover:bg-white/10 rounded-full"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></button>
                </div>
            </header>

            <!-- Messages Stream -->
            <div class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6 z-10 custom-scrollbar" id="chat-messages">
                
                <!-- Encryption Notice -->
                <div class="flex justify-center mb-6">
                    <div class="bg-[#FFEECD] text-[#54656f] text-xs px-3 py-1.5 rounded-[8px] shadow-sm flex items-center gap-1.5 max-w-md text-center">
                        <svg class="w-3 h-3 text-[10px]" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>
                        الرسائل في هذه المحادثة محمية بواسطة واتساب كلاود API.
                    </div>
                </div>

                @forelse($messages as $msg)
                    <div class="flex w-full {{ $msg->direction === 'outbound' ? 'justify-end' : 'justify-start' }}">
                        <div class="relative max-w-[65%] md:max-w-[60%] {{ $msg->direction === 'outbound' ? 'bg-[#d9fdd3] rounded-l-lg rounded-tr-none rounded-br-lg' : 'bg-white rounded-r-lg rounded-tl-none rounded-bl-lg' }} p-2 pl-3 shadow-[0_1px_0.5px_rgba(0,0,0,0.13)]">
                            
                            <!-- Tail SVG for aesthetics -->
                            @if($msg->direction === 'outbound')
                            <span class="absolute -right-[8px] top-0 text-[#d9fdd3]">
                                <svg viewBox="0 0 8 13" height="13" width="8" preserveAspectRatio="none" version="1.1"><path d="M5.188,1H0v11.193l6.467-8.625 C7.526,2.156,6.958,1,5.188,1z" fill="currentColor"></path></svg>
                            </span>
                            @else
                            <span class="absolute -left-[8px] top-0 text-white">
                                <svg viewBox="0 0 8 13" height="13" width="8" preserveAspectRatio="none" version="1.1" transform="scale(-1,1)"><path d="M5.188,1H0v11.193l6.467-8.625 C7.526,2.156,6.958,1,5.188,1z" fill="currentColor"></path></svg>
                            </span>
                            @endif

                            <div class="text-[#111b21] text-[14.2px] leading-[19px] whitespace-pre-wrap">{{ $msg->body }}</div>
                            <div class="flex justify-end items-end gap-1 mt-1 -mb-1 ml-2 select-none">
                                <span class="text-[11px] text-[#667781]">{{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}</span>
                                @if($msg->direction === 'outbound')
                                    <span class="text-[#53bdeb]"><svg class="w-4 h-4" viewBox="0 0 16 11" fill="currentColor"><path d="M11.057 9.993L15.305 4.47 13.91 3.4 10.358 8.016 8.356 6.014 6.96 7.41 9.662 10.111 9.662 10.112 11.057 9.993 11.057 9.993ZM4.673 8.398L7.228 5.842 5.832 4.447 2.578 7.701 0.305 5.429 1.488 4.246 1.488 4.246 1.488 4.246 3.761 6.519 4.673 8.397 4.673 8.398ZM14.161 3.074L12.766 2 8.423 7.647 8.356 7.734 7.027 6.405 7.228 5.842 8.558 7.172 12.016 2.677 12.016 2.677 14.161 3.074 14.161 3.074Z"></path></svg></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center p-8">
                        <div class="bg-[#f0f2f5] p-6 rounded-full mb-4">
                            <svg class="w-16 h-16 text-[#d1d7db]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
                        </div>
                        <h3 class="text-xl text-[#41525d] font-normal mb-1">لا توجد رسائل بعد</h3>
                        <p class="text-sm text-[#8696a0]">أرسل رسالة لبدء المحادثة وتشغيل الحملة التسويقية.</p>
                    </div>
                @endforelse
            </div>

            <!-- Sticky Footer: Input -->
            <div class="bg-[#f0f2f5] px-4 py-3 flex items-end gap-2 shrink-0 z-20">
                <button class="p-2 text-[#54656f] hover:text-[#41525d] transition">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
                <button class="p-2 text-[#54656f] hover:text-[#41525d] transition">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </button>
                
                <form action="{{ route('whatsapp.store', $client->id) }}" method="POST" class="flex-1 flex gap-2">
                    @csrf
                    <div class="flex-1 bg-white rounded-lg px-4 py-2 shadow-sm border border-transparent focus-within:border-green-500 focus-within:ring-1 focus-within:ring-green-500 transition flex items-center">
                        <input type="text" name="message" required placeholder="اكتب رسالة" class="w-full bg-transparent border-none text-[#111b21] placeholder-[#8696a0] focus:ring-0 p-0 text-[15px] h-[24px]" autocomplete="off">
                    </div>
                    <button type="submit" class="p-2 bg-[#00a884] text-white rounded-full hover:bg-[#008f6f] shadow-sm transition flex items-center justify-center cursor-pointer">
                        <svg class="w-6 h-6 transform rotate-90 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
                    </button>
                </form>
            </div>

        @else
            <!-- Empty State for Main Area -->
            <div class="flex-1 flex flex-col items-center justify-center p-10 text-center bg-[#f0f2f5] border-b-[6px] border-[#25d366]">
                <div class="w-full max-w-md mx-auto">
                     <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/512px-WhatsApp.svg.png" class="w-24 h-24 mx-auto mb-8 opacity-40 grayscale">
                    <h1 class="text-3xl font-light text-[#41525d] mb-4">واتساب الشبكة الذكية</h1>
                    <p class="text-[#667781] text-sm leading-6">
                        أرسل واستقبل الرسائل من المشتركين مباشرة.<br>
                        قم بربط هاتفك للحفاظ على التواصل مع العملاء.<br>
                        استخدم واتساب على ما يصل إلى 4 أجهزة مرتبطة و 1 هاتف في نفس الوقت.
                    </p>
                    <div class="mt-8 flex items-center justify-center gap-2 text-xs text-[#8696a0]">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        محمي بواسطة التشفير التام بين الطرفين
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Left (Right in RTL): Client Info Panel (w-1/4) -->
    @if(isset($client))
        <div class="w-[320px] bg-white border-r border-gray-200 overflow-y-auto hidden xl:block z-10 shrink-0">
            <!-- Section: Profile -->
            <div class="p-8 flex flex-col items-center border-b border-gray-100 bg-[#f0f2f5]/30">
                <div class="w-32 h-32 bg-gray-200 rounded-full mb-4 flex items-center justify-center text-gray-500 text-3xl font-bold border-4 border-white shadow-sm overflow-hidden">
                    @if(isset($client->profile_photo_url)) 
                        <img src="{{ $c->profile_photo_url }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($client->name ?? $client->username, 0, 1) }}
                    @endif
                </div>
                <h2 class="text-xl font-bold text-gray-800 text-center">{{ $client->name ?? $client->username }}</h2>
                <p class="text-gray-500 text-sm mt-1" dir="ltr">{{ $client->phone ?? 'لا يوجد هاتف' }}</p>
            </div>

            <!-- Section: About -->
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-[#008069] text-sm font-semibold mb-3 uppercase tracking-wider">تفاصيل الاشتراك</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                         <div class="mt-0.5 text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                         <div>
                             <p class="text-gray-900 font-medium">{{ $client->package->name ?? 'غير محدد' }}</p>
                             <p class="text-xs text-gray-500">الباقة الحالية</p>
                         </div>
                    </div>
                    <div class="flex items-start gap-3">
                         <div class="mt-0.5 text-gray-400"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                         <div>
                             <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($client->expires_at)->format('Y-m-d') }}</p>
                             <p class="text-xs text-red-500">انتهاء الاشتراك ({{ \Carbon\Carbon::now()->diffInDays($client->expires_at) }} يوم متبقي)</p>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Section: Technical -->
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-[#008069] text-sm font-semibold mb-3 uppercase tracking-wider">معلومات فنية</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-600">IP Address</span>
                         <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $client->ip ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-600">البرج</span>
                         <span class="text-sm text-gray-900">{{ $client->tower->name ?? 'غير مرتبط' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                         <span class="text-sm text-gray-600">الرصيد</span>
                         <span class="text-sm font-bold text-green-600">{{ number_format($client->balance ?? 0, 2) }} {{ $currency }}</span>
                    </div>
                </div>
            </div>

            <!-- Section: Actions -->
            <div class="p-5 space-y-3">
                <button class="w-full py-2.5 bg-[#00a884] text-white rounded-lg hover:bg-[#008f6f] transition font-semibold shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    تجديد الاشتراك
                </button>
                <button class="w-full py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    حظر المشترك
                </button>
            </div>
        </div>
    @endif

</div>

<!-- CSS for Scrollbar -->
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 20px;
    }
</style>

<script>
    // Auto scroll
    const chatContainer = document.getElementById('chat-messages');
    if(chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>
@endsection

```

