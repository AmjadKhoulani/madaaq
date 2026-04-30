@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8" x-data="{
    activeTab: 'all',
    showSuccess: true,
    init() {
        setTimeout(() => this.showSuccess = false, 3000);
    }
}">
    <div class="flex justify-between items-center mb-8">
        <div>
             <h3 class="text-gray-800 text-3xl font-bold">إعدادات الدفع (Super Admin)</h3>
             <p class="text-gray-500 mt-1">تكوين بوابات الدفع المتاحة للمشتركين (Vendors)</p>
        </div>
        <button type="submit" form="settings-form" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all transform hover:scale-105 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            حفظ التغييرات
        </button>
    </div>

    @if(session('success'))
        <div x-show="showSuccess" x-transition class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <!-- Stripe -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow relative group">
                <div class="absolute top-0 right-0 w-2 h-full bg-indigo-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Stripe</h4>
                                <span class="text-xs text-gray-500">Credit Card Gateway</span>
                            </div>
                        </div>
                        <!-- Toggle -->
                        <div x-data="{ on: {{ ($settings['stripe_active'] ?? '0') == '1' ? 'true' : 'false' }} }">
                            <input type="hidden" name="stripe_active" :value="on ? '1' : '0'">
                            <button type="button" @click="on = !on" :class="{ 'bg-indigo-600': on, 'bg-gray-200': !on }" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">Enable Stripe</span>
                                <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Public Key</label>
                            <input type="text" name="stripe_key" value="{{ $settings['stripe_key'] ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors bg-gray-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Secret Key</label>
                            <input type="password" name="stripe_secret" value="{{ $settings['stripe_secret'] ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors bg-gray-50 focus:bg-white text-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret</label>
                            <input type="password" name="stripe_webhook_secret" value="{{ $settings['stripe_webhook_secret'] ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors bg-gray-50 focus:bg-white text-sm font-mono">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PayPal -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow relative group">
                <div class="absolute top-0 right-0 w-2 h-full bg-blue-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                                <span class="font-bold italic text-xl">P</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">PayPal</h4>
                                <span class="text-xs text-gray-500">Global Payments</span>
                            </div>
                        </div>
                        <div x-data="{ on: {{ ($settings['paypal_active'] ?? '0') == '1' ? 'true' : 'false' }} }">
                            <input type="hidden" name="paypal_active" :value="on ? '1' : '0'">
                            <button type="button" @click="on = !on" :class="{ 'bg-blue-600': on, 'bg-gray-200': !on }" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="sr-only">Enable PayPal</span>
                                <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Client ID</label>
                            <input type="text" name="paypal_client_id" value="{{ $settings['paypal_client_id'] ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-gray-50 focus:bg-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Secret</label>
                            <input type="password" name="paypal_secret" value="{{ $settings['paypal_secret'] ?? '' }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors bg-gray-50 focus:bg-white text-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mode</label>
                            <select name="paypal_mode" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 focus:bg-white text-sm">
                                <option value="sandbox" {{ ($settings['paypal_mode'] ?? '') == 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                                <option value="live" {{ ($settings['paypal_mode'] ?? '') == 'live' ? 'selected' : '' }}>Live (Production)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sham Cash -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow relative group">
                <div class="absolute top-0 right-0 w-2 h-full bg-yellow-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Sham Cash</h4>
                                <span class="text-xs text-gray-500">Local Payment (Man.)</span>
                            </div>
                        </div>
                        <div x-data="{ on: {{ ($settings['sham_cash_active'] ?? '0') == '1' ? 'true' : 'false' }} }">
                            <input type="hidden" name="sham_cash_active" :value="on ? '1' : '0'">
                            <button type="button" @click="on = !on" :class="{ 'bg-yellow-500': on, 'bg-gray-200': !on }" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                <span class="sr-only">Enable Sham Cash</span>
                                <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructions / Wallet Info</label>
                            <textarea name="sham_cash_instructions" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 transition-colors bg-gray-50 focus:bg-white text-sm">{{ $settings['sham_cash_instructions'] ?? '' }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Displayed to vendors to complete the transfer manually.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">QR Code Image</label>
                            <div class="space-y-3">
                                <input type="file" name="sham_cash_qr" accept="image/*" class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-yellow-50 file:text-yellow-700
                                    hover:file:bg-yellow-100
                                "/>
                                @if(isset($settings['sham_cash_qr']))
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 inline-block">
                                        <p class="text-xs text-gray-500 mb-2 font-semibold">Current QR:</p>
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['sham_cash_qr']) }}" class="h-40 w-auto rounded border border-gray-300 shadow-sm object-contain bg-white">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Syriatel Cash -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow relative group">
                <div class="absolute top-0 right-0 w-2 h-full bg-red-600"></div>
                <div class="p-6">
                     <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-red-50 rounded-xl text-red-600">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Syriatel Cash</h4>
                                <span class="text-xs text-gray-500">Local Payment</span>
                            </div>
                        </div>
                         <div x-data="{ on: {{ ($settings['syriatel_active'] ?? '0') == '1' ? 'true' : 'false' }} }">
                            <input type="hidden" name="syriatel_active" :value="on ? '1' : '0'">
                            <button type="button" @click="on = !on" :class="{ 'bg-red-600': on, 'bg-gray-200': !on }" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <span class="sr-only">Enable Syriatel</span>
                                <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                            <textarea name="syriatel_cash_instructions" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors bg-gray-50 focus:bg-white text-sm">{{ $settings['syriatel_cash_instructions'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Turkish IBAN -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow relative group">
                <div class="absolute top-0 right-0 w-2 h-full bg-red-400"></div>
                <div class="p-6">
                     <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-red-50 rounded-xl text-red-500">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Turkish IBAN</h4>
                                <span class="text-xs text-gray-500">Bank Transfer</span>
                            </div>
                        </div>
                         <div x-data="{ on: {{ ($settings['turkish_active'] ?? '0') == '1' ? 'true' : 'false' }} }">
                            <input type="hidden" name="turkish_active" :value="on ? '1' : '0'">
                            <button type="button" @click="on = !on" :class="{ 'bg-red-500': on, 'bg-gray-200': !on }" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <span class="sr-only">Enable Turkish IBAN</span>
                                <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank Details (IBAN)</label>
                            <textarea name="turkish_bank_details" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors bg-gray-50 focus:bg-white text-sm" placeholder="Bank Name:&#10;Account Name:&#10;IBAN: TR...">{{ $settings['turkish_bank_details'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
