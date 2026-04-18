# Source Catalog: cat6

## resources/views/settings\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ 
    activeTab: 'general',
    subdomainEnabled: {{ isset($tenant) && $tenant->is_subdomain_enabled ? 'true' : 'false' }},
    subdomain: '{{ isset($tenant) ? $tenant->domain : '' }}',
    statusMessage: '',
    statusColor: '',
    isChecking: false,
    async checkSubdomain() {
        if (this.subdomain.length < 3) {
            this.statusMessage = '';
            return;
        }
        this.isChecking = true;
        this.statusMessage = 'جاري التحقق...';
        this.statusColor = 'text-gray-500';
        
        try {
            const response = await fetch('{{ route('settings.check-subdomain') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ subdomain: this.subdomain })
            });
            const data = await response.json();
            
            if (data.available) {
                this.statusMessage = data.message;
                this.statusColor = 'text-green-600';
            } else {
                this.statusMessage = data.message;
                this.statusColor = 'text-red-600';
            }
        } catch (error) {
            console.error(error);
            this.statusMessage = 'حدث خطأ أثناء التحقق';
            this.statusColor = 'text-red-500';
        } finally {
            this.isChecking = false;
        }
    }
}">
    
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إعدادات النظام</h2>
        <p class="text-gray-500 mt-1">تخصيص الإعدادات العامة، المالية، وبوابات الدفع</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-1 flex space-x-1 rtl:space-x-reverse overflow-x-auto">
        <button @click="activeTab = 'general'" 
                :class="activeTab === 'general' ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            عامة والعملات
        </button>
        <button @click="activeTab = 'automation'" 
                :class="activeTab === 'automation' ? 'bg-purple-50 text-purple-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            الأتمتة والواتساب
        </button>
        <button @click="activeTab = 'payments'" 
                :class="activeTab === 'payments' ? 'bg-green-50 text-green-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            بوابات الدفع
        </button>
        <button @click="activeTab = 'domain'" 
                :class="activeTab === 'domain' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
            رابط بوابة العملاء
        </button>
        <button @click="activeTab = 'profile'" 
                :class="activeTab === 'profile' ? 'bg-orange-50 text-orange-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            الملف الشخصي
        </button>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- TAB 1: General Settings -->
        <div x-show="activeTab === 'general'" class="space-y-6">
            <!-- Company Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">معلومات الشركة</h3>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم الشركة</label>
                    <input type="text" name="company_name" value="{{ $settings['company_name'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">شعار الشركة (للفواتير وبوابة المشتركين)</label>
                    <div class="flex items-center gap-4">
                        @if($settings['company_logo'])
                            <img src="{{ asset('storage/' . $settings['company_logo']) }}" alt="Company Logo" class="h-16 w-auto object-contain border rounded-lg p-1 bg-gray-50">
                        @else
                            <div class="h-16 w-16 bg-gray-100 rounded-lg border border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <input type="file" name="company_logo" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100
                          "/>
                    </div>
                </div>
            </div>

            <!-- Currency -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">العملات المالية</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">العملة الرئيسية</label>
                        <select name="currency" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                            <option value="ر.س" {{ $settings['currency'] === 'ر.س' ? 'selected' : '' }}>ر.س (ريال سعودي)</option>
                            <option value="$" {{ $settings['currency'] === '$' ? 'selected' : '' }}>$ (دولار أمريكي)</option>
                            <option value="€" {{ $settings['currency'] === '€' ? 'selected' : '' }}>€ (يورو)</option>
                            <option value="د.إ" {{ $settings['currency'] === 'د.إ' ? 'selected' : '' }}>د.إ (درهم إماراتي)</option>
                            <option value="د.ك" {{ $settings['currency'] === 'د.ك' ? 'selected' : '' }}>د.ك (دينار كويتي)</option>
                            <option value="ج.م" {{ $settings['currency'] === 'ج.م' ? 'selected' : '' }}>ج.م (جنيه مصري)</option>
                            <option value="ل.س" {{ $settings['currency'] === 'ل.س' ? 'selected' : '' }}>ل.س (ليرة سورية قديمة)</option>
                            <option value="ل.س.ج" {{ $settings['currency'] === 'ل.س.ج' ? 'selected' : '' }}>ل.س.ج (ليرة سورية جديدة)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: Automation & WhatsApp -->
        <div x-show="activeTab === 'automation'" class="space-y-6" x-cloak>
            <!-- Automation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">الأتمتة والتحكم</h3>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100 mb-4">
                    <div>
                        <h4 class="font-semibold text-gray-900">الإيقاف التلقائي للخدمة</h4>
                        <p class="text-xs text-gray-500">فصل الخدمة تلقائياً عند انتهاء الاشتراك</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_suspend_enabled" value="1" class="sr-only peer" {{ $settings['auto_suspend_enabled'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 rounded-full peer peer-checked:bg-purple-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">فترة السماح (أيام)</label>
                        <input type="number" name="default_grace_period" value="{{ $settings['default_grace_period'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">إصدار الفاتورة قبل (أيام)</label>
                        <input type="number" name="invoice_generation_days" value="{{ $settings['invoice_generation_days'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <!-- WhatsApp -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" x-data="{ whatsappType: '{{ $settings['whatsapp_type'] ?? 'api' }}' }">
                 <h3 class="text-lg font-bold text-gray-900 mb-4">إعدادات واتساب</h3>
                 
                 <!-- WhatsApp Type Selection -->
                 <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                     <label class="block text-sm font-bold text-gray-800 mb-3">اختر طريقة التواصل عبر واتساب</label>
                     <div class="flex gap-4">
                         <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border-2 transition-all" 
                                :class="whatsappType === 'api' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:border-green-300'">
                             <input type="radio" name="whatsapp_type" value="api" x-model="whatsappType" class="w-4 h-4 text-green-600">
                             <div>
                                 <div class="font-bold text-gray-900">WhatsApp Cloud API</div>
                                 <div class="text-xs text-gray-600">إرسال رسائل تلقائية عبر API</div>
                             </div>
                         </label>
                         
                         <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border-2 transition-all"
                                :class="whatsappType === 'regular' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:border-green-300'">
                             <input type="radio" name="whatsapp_type" value="regular" x-model="whatsappType" class="w-4 h-4 text-green-600">
                             <div>
                                 <div class="font-bold text-gray-900">رقم واتساب عادي</div>
                                 <div class="text-xs text-gray-600">التواصل المباشر عبر wa.me</div>
                             </div>
                         </label>
                     </div>
                 </div>

                 <!-- API Fields (shown when type = api) -->
                 <div x-show="whatsappType === 'api'" x-transition class="space-y-4">
                     <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                         <div class="flex items-start gap-2">
                             <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                             <div class="text-sm text-blue-800">
                                 <strong>ملاحظة هامة:</strong> فقط عند استخدام WhatsApp Cloud API ستتمكن من استخدام ميزة المحادثة ضمن النظام وإرسال الرسائل التلقائية. الرقم العادي يوجه لمحادثة مباشرة عبر wa.me فقط.
                             </div>
                         </div>
                     </div>
                     
                     <div>
                         <label class="block text-sm font-semibold text-gray-700 mb-2">WhatsApp Access Token</label>
                         <input type="password" name="whatsapp_token" value="{{ $settings['whatsapp_token'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="EAAxxxxxxxxxxxxx">
                         <p class="text-xs text-gray-500 mt-1">الحصول عليه من Facebook Developer Console</p>
                     </div>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div>
                             <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number ID</label>
                             <input type="text" name="whatsapp_phone_id" value="{{ $settings['whatsapp_phone_id'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="123456789">
                         </div>
                         <div>
                             <label class="block text-sm font-semibold text-gray-700 mb-2">Business Account ID</label>
                             <input type="text" name="whatsapp_business_id" value="{{ $settings['whatsapp_business_id'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="987654321">
                         </div>
                     </div>
                 </div>

                 <!-- Regular Number Field (shown when type = regular) -->
                 <div x-show="whatsappType === 'regular'" x-transition class="space-y-4">
                     <div>
                         <label class="block text-sm font-semibold text-gray-700 mb-2">رقم واتساب</label>
                         <div class="relative">
                             <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 font-mono">+</span>
                             <input type="text" 
                                    name="whatsapp_regular_number" 
                                    value="{{ $settings['whatsapp_regular_number'] }}" 
                                    class="w-full pr-8 pl-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-mono" 
                                    placeholder="963999123456"
                                    dir="ltr">
                         </div>
                         <p class="text-xs text-gray-500 mt-1">أدخل الرقم بصيغة دولية بدون + (مثال: 963999123456)</p>
                         <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                             <div class="flex items-start gap-2">
                                 <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                 <div class="text-sm text-blue-800">
                                     <strong>ملاحظة:</strong> عند استخدام رقم عادي، سيتم توجيه المشتركين إلى محادثة واتساب مباشرة معك عبر الرابط wa.me
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
            </div>
        </div>

        <!-- TAB 3: Payment Gateways -->
        <div x-show="activeTab === 'payments'" class="space-y-6" x-cloak>
            
            <!-- Stripe -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-100 rounded text-blue-600 text-xs font-bold">Stripe</span>
                        بطاقات الائتمان
                    </h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="stripe_active" value="1" class="sr-only peer" {{ $settings['stripe_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Public Key</label>
                        <input type="text" name="stripe_public_key" value="{{ $settings['stripe_public_key'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Secret Key</label>
                        <input type="password" name="stripe_secret_key" value="{{ $settings['stripe_secret_key'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <!-- PayPal -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-900 rounded text-white text-xs font-bold">PayPal</span>
                        بايبال
                    </h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="paypal_active" value="1" class="sr-only peer" {{ $settings['paypal_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Client ID</label>
                        <input type="text" name="paypal_client_id" value="{{ $settings['paypal_client_id'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Secret</label>
                        <input type="password" name="paypal_secret" value="{{ $settings['paypal_secret'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Cham Cash Personal -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="p-1.5 bg-purple-100 rounded text-purple-600 text-xs font-bold">Cham Cash</span>
                        حساب شخصي (يدوي)
                    </h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="cham_cash_personal_active" value="1" class="sr-only peer" {{ $settings['cham_cash_personal_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">رقم المحفظة</label>
                        <input type="text" name="cham_cash_personal_wallet" value="{{ $settings['cham_cash_personal_wallet'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">صورة QR Code</label>
                        <input type="file" name="cham_cash_personal_qr" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @if($settings['cham_cash_personal_qr'])
                            <img src="{{ asset('storage/' . $settings['cham_cash_personal_qr']) }}" class="h-20 mt-2 rounded border">
                        @endif
                    </div>
                </div>
            </div>

             <!-- Syriatel Cash -->
             <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="p-1.5 bg-red-100 rounded text-red-600 text-xs font-bold">Syriatel Cash</span>
                        سيريتل كاش (Merchant)
                    </h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="syriatel_cash_active" value="1" class="sr-only peer" {{ $settings['syriatel_cash_active'] ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Merchant ID</label>
                        <input type="text" name="syriatel_cash_merchant_id" value="{{ $settings['syriatel_cash_merchant_id'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">PIN / Secret</label>
                        <input type="password" name="syriatel_cash_pin" value="{{ $settings['syriatel_cash_pin'] }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

        </div>

        <!-- TAB 4: Domain Settings -->
        <div x-show="activeTab === 'domain'" class="space-y-6" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">إعدادات رابط المشتركين</h3>
                
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-indigo-100 rounded-full text-indigo-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-indigo-900">بوابة المشتركين (Client Portal)</h4>
                            <p class="text-sm text-indigo-700 mt-1">يمكنك تفعيل رابط خاص لمشتركيك للدخول إلى حساباتهم.</p>
                        </div>
                         <label class="relative inline-flex items-center cursor-pointer mt-1">
                            <input type="checkbox" name="is_subdomain_enabled" value="1" class="sr-only peer" {{ isset($tenant) && $tenant->is_subdomain_enabled ? 'checked' : '' }} x-model="subdomainEnabled">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                        </label>
                    </div>

                    <div x-show="subdomainEnabled" x-transition class="mt-4 border-t border-indigo-200 pt-4">
                        @if(isset($tenant) && $tenant->domain && $tenant->is_subdomain_enabled)
                             @php
                                $appHost = parse_url(config('app.url'), PHP_URL_HOST);
                                $appHost = str_replace('www.', '', $appHost);
                                $fullUrl = str_contains($tenant->domain, '.') ? $tenant->domain : $tenant->domain . '.' . $appHost;
                                $fullUrl = 'http://' . $fullUrl;
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-indigo-800">الرابط الحالي:</span>
                                <code class="px-3 py-1.5 bg-white border border-indigo-200 rounded text-indigo-800 font-mono text-sm direct-ltr">{{ $fullUrl }}</code>
                                <a href="{{ $fullUrl }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 underline">فتح الرابط</a>
                            </div>
                        @else
                            <p class="text-sm text-indigo-800">قم بإدخال اسم النطاق الفرعي في الأسفل وحفظ الإعدادات لتفعيل الرابط.</p>
                        @endif
                    </div>
                </div>

                <div class="max-w-xl" x-show="subdomainEnabled" x-transition>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">النطاق الفرعي (Subdomain)</label>
                    <div class="flex items-center" dir="ltr">
                        <span class="px-3 py-2.5 bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg text-gray-500 text-sm">https://</span>
                        <input type="text" name="subdomain" 
                               x-model="subdomain"
                               @input.debounce.500ms="checkSubdomain()"
                               placeholder="your-name" 
                               class="flex-1 px-4 py-2.5 border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none text-left @error('subdomain') border-red-500 @enderror"
                               pattern="[A-Za-z0-9\-]+"
                               title="أحرف إنجليزية وأرقام وعلامة (-) فقط">
                        <span class="px-3 py-2.5 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg text-gray-500 text-sm">.{{ str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST)) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                         <p class="text-xs font-semibold" :class="statusColor" x-text="statusMessage"></p>
                         <p class="text-xs text-gray-500">أحرف إنجليزية وأرقام وعلامة (-) فقط، بدون مسافات.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 5: Profile Settings -->
        <div x-show="activeTab === 'profile'" class="space-y-6" x-cloak>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">الملف الشخصي</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل</label>
                        <input type="text" name="profile_name" value="{{ auth()->user()->name }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="text" name="profile_phone" value="{{ auth()->user()->phone }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" dir="ltr">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني (للعرض فقط)</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-lg text-gray-500">
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                <h4 class="text-md font-bold text-gray-900 mb-4">تغيير كلمة المرور</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور الجديدة</label>
                        <input type="password" name="profile_password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="اتركها فارغة إذا لم ترد التغيير">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">تأكيد كلمة المرور</label>
                        <input type="password" name="profile_password_confirmation" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer Save Button -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 p-4 -mx-4 -mb-6 flex justify-end z-10">
            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-transform transform active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                حفظ كافة التغييرات
            </button>
        </div>
    </form>
</div>
@endsection

```

## resources/views/staff\create.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إضافة موظف جديد</h2>
        <p class="text-gray-500 mt-1">إنشاء حساب موظف وتعيين الصلاحيات</p>
    </div>

    <form action="{{ route('staff.store') }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل *</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('name') }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني *</label>
                <input type="email" name="email" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('email') }}">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف</label>
                <input type="text" name="phone" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('phone') }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">المنصب</label>
                <input type="text" name="position" placeholder="مثال: موظف دعم فني" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('position') }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور *</label>
            <input type="password" name="password" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" minlength="6">
            <p class="text-xs text-gray-500 mt-1">6 أحرف على الأقل</p>
        </div>

        <div class="border-t border-purple-100 pt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">الأدوار الوظيفية *</label>
            <div class="space-y-2">
                @foreach($roles as $role)
                    <label class="flex items-center gap-3 p-3 border-2 border-purple-100 rounded-xl hover:bg-purple-50 cursor-pointer transition">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                        <div>
                            <div class="font-semibold text-gray-900">{{ $role->display_name }}</div>
                            @if($role->description)
                                <div class="text-xs text-gray-500">{{ $role->description }}</div>
                            @endif
                        </div>
                    </label>
                @endforeach
                @if($roles->isEmpty())
                    <p class="text-gray-500 text-sm">لا توجد أدوار وظيفية. <a href="{{ route('roles.create') }}" class="text-purple-600 font-bold">أنشئ دوراً الآن</a></p>
                @endif
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                إضافة الموظف
            </button>
            <a href="{{ route('staff.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

```

## resources/views/staff\edit.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">تعديل موظف</h2>
        <p class="text-gray-500 mt-1">تحديث بيانات الموظف وصلاحياته</p>
    </div>

    <form action="{{ route('staff.update', $staff) }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل *</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('name', $staff->name) }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني *</label>
                <input type="email" name="email" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('email', $staff->email) }}">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف</label>
                <input type="text" name="phone" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('phone', $staff->phone) }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">المنصب</label>
                <input type="text" name="position" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('position', $staff->position) }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور الجديدة (اتركها فارغة للإبقاء على القديمة)</label>
            <input type="password" name="password" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" minlength="6">
        </div>

        <div class="flex items-center gap-3 p-4 bg-purple-50 rounded-xl border border-purple-200">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ $staff->is_active ? 'checked' : '' }} class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
            <label for="is_active" class="font-semibold text-gray-900 cursor-pointer">الحساب نشط</label>
        </div>

        <div class="border-t border-purple-100 pt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">الأدوار الوظيفية *</label>
            <div class="space-y-2">
                @foreach($roles as $role)
                    <label class="flex items-center gap-3 p-3 border-2 border-purple-100 rounded-xl hover:bg-purple-50 cursor-pointer transition">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ $staff->roles->contains($role->id) ? 'checked' : '' }} class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                        <div>
                            <div class="font-semibold text-gray-900">{{ $role->display_name }}</div>
                            @if($role->description)
                                <div class="text-xs text-gray-500">{{ $role->description }}</div>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                حفظ التغييرات
            </button>
            <a href="{{ route('staff.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

```

## resources/views/staff\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إدارة الموظفين</h2>
            <p class="text-gray-500 mt-1">إضافة وتعديل صلاحيات الموظفين</p>
        </div>
        <a href="{{ route('staff.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            موظف جديد
        </a>
    </div>

    <!-- Staff Table -->
    <div class="glass shadow-lg rounded-2xl overflow-hidden border border-white/30">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-purple-100">
                <thead class="bg-gradient-to-r from-purple-50 to-blue-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">الموظف</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">البريد</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">الدور الوظيفي</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-purple-700 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white/80 divide-y divide-purple-50">
                    @forelse($staff as $user)
                    <tr class="hover:bg-purple-50/50 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                    @if($user->phone)
                                        <div class="text-xs text-purple-600">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-indigo-400 to-purple-500 text-white shadow-md mr-1">
                                    {{ $role->display_name ?? $role->name }}
                                </span>
                            @endforeach
                            @if($user->roles->isEmpty())
                                <span class="text-gray-400 text-sm">بدون دور</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-400 to-emerald-500 text-white shadow-md">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full ml-1.5 animate-pulse"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-gray-400 to-gray-600 text-white shadow-md">
                                    معطل
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('staff.edit', $user) }}" class="text-purple-600 hover:text-purple-800 font-bold text-sm">
                                    تعديل
                                </a>
                                <form action="{{ route('staff.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-sm">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span class="text-lg font-medium">لا يوجد موظفين</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-purple-100 bg-white/50">
            {{ $staff->links() }}
        </div>
    </div>
</div>
@endsection

```

## resources/views/staff\roles\create.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إنشاء دور وظيفي جديد</h2>
        <p class="text-gray-500 mt-1">حدد الاسم والصلاحيات للدور الجديد</p>
    </div>

    <form action="{{ route('roles.store') }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6" x-data="{ selectedTemplate: '' }">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">اسم الدور (بالإنجليزية) *</label>
                <input type="text" name="name" required placeholder="custom_role" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('name') }}">
                <p class="text-xs text-gray-500 mt-1">استخدم أحرف إنجليزية صغيرة بدون مسافات</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم المعروض (بالعربية) *</label>
                <input type="text" name="display_name" required placeholder="دور مخصص" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('display_name') }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الوصف</label>
            <textarea name="description" rows="2" placeholder="مثال: مسؤول عن الحسابات والفواتير" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">{{ old('description') }}</textarea>
        </div>

        <div class="border-t border-purple-100 pt-6">
            <h3 class="block text-lg font-bold text-gray-900 mb-2">الأدوار الوظيفية *</h3>
            <p class="text-sm text-gray-600 mb-4">حدد الدور المناسب أو مجموعة أدوار</p>
            
            <!-- Management Roles (Exclusive) -->
            <div class="mb-6">
                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    أدوار إدارية (اختيار واحد فقط)
                </p>
                <div class="space-y-3">
                    @php
                        $adminRoles = \App\Models\Role::where('tenant_id', auth()->user()->tenant_id)
                            ->whereIn('name', ['admin', 'manager'])
                            ->whereNotNull('display_name') // Exclude roles without display_name
                            ->get();
                    @endphp
                    
                    @foreach($adminRoles as $role)
                        <label class="flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer transition"
                               :class="selectedTemplate === '{{ $role->name }}' ? 'border-indigo-500 bg-indigo-50' : 'border-purple-100 hover:bg-purple-50'">
                            <input type="radio" name="base_role" value="{{ $role->name }}" 
                                   x-model="selectedTemplate"
                                   class="mt-1 w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <div class="flex-1">
                                <div class="text-sm font-bold text-gray-900">{{ $role->display_name }}</div>
                                <div class="text-xs text-gray-600 mt-1">{{ $role->description }}</div>
                            </div>
                        </label>
                    @endforeach
                    
                    <label class="flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer transition"
                           :class="selectedTemplate === 'custom' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:bg-gray-50'">
                        <input type="radio" name="base_role" value="custom" 
                               x-model="selectedTemplate"
                               class="mt-1 w-5 h-5 text-purple-600 focus:ring-purple-500">
                        <div class="flex-1">
                            <div class="text-sm font-bold text-gray-900">دور مخصص</div>
                            <div class="text-xs text-gray-600 mt-1">حدد صلاحيات خاصة من القائمة أدناه</div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Operational Roles (Multiple Selection) -->
            <div class="transition-all duration-300"
                 :class="selectedTemplate !== 'custom' && selectedTemplate !== '' ? 'opacity-75 pointer-events-none' : ''">
                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    أدوار تشغيلية (يتم تحديدها تلقائياً مع الأدوار الإدارية)
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @php
                        $operationalRoles = \App\Models\Role::where('tenant_id', auth()->user()->tenant_id)
                            ->whereIn('name', ['technician', 'accountant', 'marketer', 'subscriptions_followup'])
                            ->whereNotNull('display_name')
                            ->get();
                    @endphp
                    
                    @foreach($operationalRoles as $role)
                        <label class="flex items-start gap-3 p-3 border-2 border-blue-100 rounded-xl transition group"
                               :class="{'hover:bg-blue-50 cursor-pointer': selectedTemplate === 'custom', 'bg-gray-50': selectedTemplate !== 'custom'}">
                            <input type="checkbox" name="additional_roles[]" value="{{ $role->name }}"
                                   class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                   :checked="selectedTemplate === 'admin' || selectedTemplate === 'manager'"
                                   :disabled="selectedTemplate !== 'custom'">
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-gray-900">{{ $role->display_name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $role->description }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                إنشاء الدور
            </button>
            <a href="{{ route('roles.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

```

## resources/views/staff\roles\edit.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">تعديل دور وظيفي</h2>
        <p class="text-gray-500 mt-1">تحديث الصلاحيات والتفاصيل</p>
    </div>

    <form action="{{ route('roles.update', $role) }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">اسم الدور (بالإنجليزية) *</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('name', $role->name) }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم المعروض (بالعربية) *</label>
                <input type="text" name="display_name" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80" value="{{ old('display_name', $role->display_name) }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الوصف</label>
            <textarea name="description" rows="2" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">{{ old('description', $role->description) }}</textarea>
        </div>

        <div class="border-t border-purple-100 pt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-4">الصلاحيات</label>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($permissions as $permission)
                    <label class="flex items-center gap-3 p-3 border-2 border-purple-100 rounded-xl hover:bg-purple-50 cursor-pointer transition">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                        <div class="text-sm font-semibold text-gray-900">{{ $permission->display_name }}</div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 flex gap-3">
            <span class="text-2xl">ℹ️</span>
            <div>
                <h4 class="font-bold text-blue-900 text-sm">ملاحظة</h4>
                <p class="text-blue-700 text-xs mt-1">التغييرات ستؤثر على جميع الموظفين المرتبطين بهذا الدور ({{ $role->users_count ?? 0 }} موظف)</p>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                حفظ التغييرات
            </button>
            <a href="{{ route('roles.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

```

## resources/views/staff\roles\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إدارة الأدوار الوظيفية</h2>
            <p class="text-gray-500 mt-1">إنشاء وتعديل الأدوار وصلاحياتها</p>
        </div>
        <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            دور وظيفي جديد
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roles as $role)
        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6 hover:shadow-xl transition">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">{{ $role->display_name }}</h3>
                    @if($role->description)
                        <p class="text-sm text-gray-500 mt-1">{{ $role->description }}</p>
                    @endif
                </div>
                <span class="px-3 py-1 bg-gradient-to-r from-purple-100 to-blue-100 text-purple-700 rounded-full text-xs font-bold">
                    {{ $role->users_count ?? 0 }} موظف
                </span>
            </div>

            @if($role->permissions->count() > 0)
                <div class="mb-4">
                    <div class="text-xs font-semibold text-gray-500 mb-2">الصلاحيات ({{ $role->permissions->count() }}):</div>
                    <div class="flex flex-wrap gap-1">
                        @foreach($role->permissions->take(3) as $permission)
                            <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded text-xs">{{ $permission->display_name }}</span>
                        @endforeach
                        @if($role->permissions->count() > 3)
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">+{{ $role->permissions->count() - 3 }}</span>
                        @endif
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-2 pt-4 border-t border-purple-100">
                <a href="{{ route('roles.edit', $role) }}" class="flex-1 text-center py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold rounded-lg transition text-sm">
                    تعديل
                </a>
                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟ هذا سيزيل الدور من جميع الموظفين المرتبطين به.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="py-2 px-4 bg-red-50 hover:bg-red-100 text-red-700 font-semibold rounded-lg transition text-sm">
                        حذف
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full glass rounded-2xl p-12 text-center">
            <svg class="w-16 h-16 text-purple-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <h3 class="text-lg font-bold text-gray-700 mb-2">لا توجد أدوار وظيفية</h3>
            <p class="text-gray-500 mb-4">ابدأ بإنشاء دور وظيفي جديد وتعيين الصلاحيات</p>
            <a href="{{ route('roles.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                إنشاء الدور الأول
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection

```

## resources/views/tools\migration\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">النقل من MikroTik</h2>
        <p class="text-gray-500 mt-1">استيراد البيانات من جهاز MikroTik الخاص بك</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Import via API -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">الاتصال المباشر</h3>
                    <p class="text-xs text-gray-500">استيراد مباشر من جهاز MikroTik</p>
                </div>
            </div>

            <form action="{{ route('tools.migration.import-mikrotik') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">عنوان IP الجهاز</label>
                    <input type="text" name="mikrotik_host" required placeholder="192.168.88.1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم</label>
                        <input type="text" name="mikrotik_username" required placeholder="admin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور</label>
                        <input type="password" name="mikrotik_password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">المنفذ (Port)</label>
                    <input type="number" name="mikrotik_port" value="8728" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع الاستيراد</label>
                    <select name="import_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="pppoe">PPPoE فقط</option>
                        <option value="hotspot">Hotspot فقط</option>
                        <option value="both">الكل</option>
                    </select>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition">
                    بدء الاستيراد
                </button>
            </form>
        </div>

        <!-- Import from File -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">من ملف CSV</h3>
                    <p class="text-xs text-gray-500">رفع ملف يحتوي على البيانات</p>
                </div>
            </div>

            <form action="{{ route('tools.migration.import-file') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع البيانات</label>
                    <select name="file_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="pppoe">PPPoE</option>
                        <option value="hotspot">Hotspot</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ملف CSV</label>
                    <input type="file" name="import_file" accept=".csv,.txt" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-2">صيغة الملف: username,password,package,profile</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-900 mb-2">📋 مثال CSV:</p>
                    <pre class="text-xs text-blue-800 font-mono">username,password,package,profile
user1,pass123,Gold,profile1
user2,pass456,Silver,profile2</pre>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-sm transition">
                    رفع واستيراد
                </button>
            </form>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📖 تعليمات الاستخدام</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">الاتصال المباشر:</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600">•</span>
                        <span>تأكد من تفعيل API على جهاز MikroTik</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600">•</span>
                        <span>المنفذ الافتراضي للـ API هو 8728</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600">•</span>
                        <span>تحتاج صلاحيات admin للوصول</span>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 mb-2">استيراد من ملف:</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="text-green-600">•</span>
                        <span>استخدم ملف CSV بصيغة UTF-8</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-600">•</span>
                        <span>السطر الأول يجب أن يكون رؤوس الأعمدة</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-600">•</span>
                        <span>يمكنك تصدير البيانات من MikroTik ثم رفعها</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

```

## resources/views/activity-logs\index.blade.php

```php
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">سجل النشاطات</h2>
            <p class="text-gray-500 mt-1">تتبع جميع العمليات التي تمت على النظام</p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="glass rounded-2xl shadow-lg border border-white/30 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث..." class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الموظف</label>
                <select name="user_id" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                    <option value="">الكل</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>
        </div>

        <div class="flex gap-3 mt-4">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                تطبيق الفلاتر
            </button>
            <a href="{{ route('activity-logs.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                إعادة تعيين
            </a>
        </div>
    </form>

    <!-- Activity Timeline -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
        <div class="space-y-4">
            @forelse($logs as $log)
                <div class="flex gap-4 p-4 bg-white/50 rounded-xl border border-purple-100 hover:border-purple-300 transition">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white shadow-lg">
                            @if(str_contains($log->description, 'إنشاء'))
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            @elseif(str_contains($log->description, 'تعديل'))
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            @elseif(str_contains($log->description, 'حذف'))
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $log->description }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    بواسطة: <span class="font-medium text-purple-600">{{ $log->causer->name ?? 'نظام' }}</span>
                                </p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                        </div>

                        @if($log->properties && isset($log->properties['changes']))
                            <details class="mt-2">
                                <summary class="text-xs text-blue-600 cursor-pointer hover:text-blue-800">عرض التفاصيل</summary>
                                <div class="mt-2 p-3 bg-blue-50 rounded-lg text-xs space-y-1">
                                    @foreach($log->properties['changes'] as $key => $value)
                                        <div class="flex gap-2">
                                            <span class="font-semibold text-gray-700">{{ $key }}:</span>
                                            <span class="text-gray-500">{{ $log->properties['old'][$key] ?? '--' }}</span>
                                            <span class="text-gray-400">→</span>
                                            <span class="text-blue-600 font-medium">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-purple-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-gray-500">لا توجد نشاطات محفوظة</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 pt-6 border-t border-purple-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection

```

