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
