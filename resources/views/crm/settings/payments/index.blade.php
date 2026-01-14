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
