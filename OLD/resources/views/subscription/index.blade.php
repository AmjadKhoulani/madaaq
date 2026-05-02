@extends('layouts.app')

@push('styles')
<style>
    .payment-option.selected {
        border-color: #6366f1;
        background-color: #f5f3ff;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8" x-data="{ 
    selectedPlan: null, 
    showModal: false,
    selectedMethod: '{{ array_key_first($gateways) ?? "" }}',
    managePlans: {{ auth()->user()->hasAccess() ? 'false' : 'true' }},
    
    openModal(plan) {
        this.selectedPlan = plan;
        this.showModal = true;
    },
    
    get amount() {
        return this.selectedPlan === 'basic_annual' ? 50 : 100;
    }
}">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            إدارة اشتراكك وباقاتك 🚀
        </h2>
        <p class="mt-4 text-lg text-gray-600">
            خطط سنوية مصممة لتلبية احتياجات جميع مزودي الخدمة
        </p>
    </div>

    <!-- Current Subscription Status -->
    <div class="max-w-3xl mx-auto mb-16">
        <div class="bg-white rounded-2xl shadow-lg border border-indigo-100 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-2 h-full bg-indigo-500"></div>
            <div class="p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">حالة الاشتراك الحالية</h3>
                    <div class="mt-2 flex items-center gap-2">
                        @if(auth()->user()->subscription_status === 'active')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                نشط (Active)
                            </span>
                        @elseif(auth()->user()->onTrial())
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                فترة تجريبية (Trial)
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                غير نشط / منتهي
                            </span>
                        @endif
                        <span class="text-gray-500 text-sm">|</span>
                        <span class="font-semibold text-gray-700">الباقة: {{ auth()->user()->plan_name == 'pro_annual' ? 'الاحترافية (Pro)' : (auth()->user()->plan_name == 'basic_annual' ? 'الأساسية (Basic)' : 'مجانية / غير محددة') }}</span>
                    </div>
                </div>
                
                <div class="text-center md:text-left flex flex-col items-end gap-2">
                    @if(auth()->user()->subscription_ends_at)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">تاريخ الانتهاء</p>
                            <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse(auth()->user()->subscription_ends_at)->format('Y-m-d') }}</p>
                            <p class="text-xs text-indigo-600 mt-1">({{ \Carbon\Carbon::parse(auth()->user()->subscription_ends_at)->diffForHumans() }})</p>
                        </div>
                    @elseif(auth()->user()->trial_ends_at)
                         <div>
                            <p class="text-sm text-gray-500 mb-1">تاريخ انتهاء التجربة</p>
                            <p class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse(auth()->user()->trial_ends_at)->format('Y-m-d') }}</p>
                            <p class="text-xs text-indigo-600 mt-1">({{ \Carbon\Carbon::parse(auth()->user()->trial_ends_at)->diffForHumans() }})</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">لا يوجد تاريخ انتهاء</p>
                    @endif

                    @if(auth()->user()->hasAccess())
                        <button @click="managePlans = !managePlans" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold underline mt-2">
                            <span x-text="managePlans ? 'إخفاء الباقات' : 'تغيير الباقة / ترقية (Change Plan)'"></span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Cards -->
    <div x-show="managePlans" x-collapse class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Basic Plan -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow relative">
            <div class="p-8">
                <h3 class="text-xl font-semibold text-gray-900">الباقة الأساسية</h3>
                <p class="mt-4 text-gray-500">للبدء في إدارة شبكتك بكفاءة</p>
                <div class="mt-6 flex items-baseline justify-center">
                    <span class="text-5xl font-extrabold text-gray-900">$50</span>
                    <span class="text-xl font-medium text-gray-500">/ سنوياً</span>
                </div>
                
                <ul class="mt-8 space-y-4 text-right">
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-500 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-600">لوحة تحكم أساسية (Dashboard)</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-500 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-600">إدارة المشتركين (Clients)</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-500 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-600">مراقبة أساسية (Basic Monitoring)</span></li>
                </ul>
            </div>
            <div class="p-8 bg-gray-50 border-t border-gray-100 space-y-3">
                @if(auth()->user()->plan_name == 'basic_annual' && auth()->user()->hasAccess())
                    <div class="w-full bg-green-100 text-green-700 font-bold py-3 px-4 rounded-xl text-center cursor-default flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        باقتك الحالية
                    </div>
                @else
                    <button @click="openModal('basic_annual')" class="w-full bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold py-3 px-4 rounded-xl transition-colors">
                        {{ auth()->user()->plan_name == 'pro_annual' ? 'تخفيض (Downgrade)' : 'اشتراك / تحويل' }}
                    </button>
                    
                    @if(!auth()->user()->onTrial() && !auth()->user()->hasActiveSubscription())
                        <form action="{{ route('subscription.trial') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="plan" value="basic_annual">
                            <button type="submit" class="w-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-3 px-4 rounded-xl transition-colors text-sm">
                                تجربة مجانية (5 أيام)
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Pro Plan -->
        <div class="bg-gray-900 rounded-2xl shadow-2xl border border-indigo-500 overflow-hidden transform scale-105 relative">
            <div class="absolute top-0 right-0 bg-yellow-400 text-xs font-bold px-3 py-1 rounded-bl-lg">الأكثر مبيعاً</div>
            <div class="p-8">
                <h3 class="text-xl font-semibold text-white">الباقة الاحترافية (Pro)</h3>
                <p class="mt-4 text-indigo-200">للشركات التي تبحث عن التحكم الكامل</p>
                <div class="mt-6 flex items-baseline justify-center">
                    <span class="text-5xl font-extrabold text-white">$100</span>
                    <span class="text-xl font-medium text-indigo-200">/ سنوياً</span>
                </div>
                
                <ul class="mt-8 space-y-4 text-right">
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-400 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-300">جميع ميزات الباقة الأساسية</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-400 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-300 font-bold">تقارير مالية ومحاسبة</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-400 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-300 font-bold">خريطة الشبكة (NOC Topology)</span></li>
                    <li class="flex items-center"><svg class="flex-shrink-0 h-5 w-5 text-green-400 ml-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="text-gray-300 font-bold">نظام الفواتير (Invoices)</span></li>
                </ul>
            </div>
            <div class="p-8 bg-gray-800 border-t border-gray-700 space-y-3">
                @if(auth()->user()->plan_name == 'pro_annual' && auth()->user()->hasAccess())
                    <div class="w-full bg-green-600/20 text-green-400 font-bold py-3 px-4 rounded-xl text-center cursor-default flex items-center justify-center gap-2 border border-green-500/30">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        باقتك الحالية
                    </div>
                @else
                    <button @click="openModal('pro_annual')" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:from-purple-700 hover:to-indigo-700 font-bold py-3 px-4 rounded-xl shadow-lg transition-all transform hover:scale-105">
                        {{ auth()->user()->plan_name == 'basic_annual' ? 'ترقية الآن (Upgrade)' : 'اشتراك / تحويل' }}
                    </button>

                    @if(!auth()->user()->onTrial() && !auth()->user()->hasActiveSubscription())
                        <form action="{{ route('subscription.trial') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="plan" value="pro_annual">
                            <button type="submit" class="w-full bg-gray-700 border border-gray-600 text-gray-300 hover:bg-gray-600 font-semibold py-3 px-4 rounded-xl transition-colors text-sm">
                                تجربة مجانية (5 أيام)
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div x-show="showModal" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center">
                    إتمام الاشتراك (<span x-text="selectedPlan == 'basic_annual' ? 'Basic' : 'Pro'"></span>)
                </h3>

                <form action="{{ route('subscription.subscribe') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="plan" :value="selectedPlan">

                    <!-- Amount -->
                    <div class="mb-6 text-center">
                        <span class="text-3xl font-bold text-gray-900">$<span x-text="amount"></span></span>
                        <span class="text-gray-500">/ سنوياً</span>
                    </div>

                    <!-- Payment Methods -->
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        @if(isset($gateways['stripe']))
                        <!-- Stripe -->
                        <div @click="selectedMethod = 'stripe'" 
                             :class="{'selected ring-2 ring-indigo-500': selectedMethod === 'stripe'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">Stripe (بطاقة ائتمان)</span>
                            </div>
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        </div>
                        @endif
                        
                        @if(isset($gateways['paypal']))
                        <!-- PayPal -->
                        <div @click="selectedMethod = 'paypal'" 
                             :class="{'selected ring-2 ring-indigo-500': selectedMethod === 'paypal'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">PayPal</span>
                            </div>
                            <!-- PayPal Icon -->
                             <span class="font-bold text-blue-700 italic">Pay<span class="text-blue-500">Pal</span></span>
                        </div>
                        @endif

                        @if(isset($gateways['sham_cash']))
                        <!-- Sham Cash -->
                        <div @click="selectedMethod = 'sham_cash'"
                             :class="{'selected ring-2 ring-indigo-500': selectedMethod === 'sham_cash'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">شام كاش (Sham Cash)</span>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">محلي</span>
                            </div>
                            <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>
                        @endif

                        @if(isset($gateways['syriatel_cash']))
                        <!-- Syriatel Cash -->
                        <div @click="selectedMethod = 'syriatel_cash'"
                             :class="{'selected ring-2 ring-red-500': selectedMethod === 'syriatel_cash'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">Syriatel Cash (سيريتيل)</span>
                                <span class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">محلي</span>
                            </div>
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        @endif
 
                        @if(isset($gateways['turkish_iban']))
                        <!-- Turkish IBAN -->
                        <div @click="selectedMethod = 'turkish_iban'"
                             :class="{'selected ring-2 ring-red-500': selectedMethod === 'turkish_iban'}"
                             class="payment-option cursor-pointer border rounded-lg p-4 flex items-center justify-between hover:border-indigo-300 transition-all">
                            <div class="flex items-center">
                                <span class="ml-3 font-medium text-gray-900">تحويل بنكي (تركيا)</span>
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">IBAN</span>
                            </div>
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        @endif
                        
                        @if(empty($gateways))
                            <div class="p-4 bg-red-50 text-red-600 rounded-lg text-center">
                                لا توجد وسائل دفع متاحة حالياً.
                            </div>
                        @endif
                    </div>

                    <input type="hidden" name="payment_method" :value="selectedMethod">

                    <!-- Sham Cash Details -->
                    <div x-show="selectedMethod === 'sham_cash'" class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <p class="text-sm text-yellow-800 mb-2 font-bold">تعليمات الدفع:</p>
                        
                        @php
                            $shamQr = $settings['sham_cash_qr'] ?? null;
                        @endphp
                         @if($shamQr)
                            <div class="mb-4 text-center bg-white p-4 rounded-xl border border-yellow-100 shadow-sm inline-block w-full">
                                <p class="text-xs text-gray-500 mb-2 font-bold">امسح الكود (QR Code)</p>
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($shamQr) }}" alt="QR Code" class="h-48 w-auto mx-auto rounded-lg object-contain">
                            </div>
                        @endif

                        <div class="text-sm text-gray-700 mb-4 whitespace-pre-line">{{ $settings['sham_cash_instructions'] ?? 'يرجى التواصل مع الإدارة' }}</div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رقم العملية / الإشعار</label>
                                <input type="text" name="sham_cash_ref" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="أدخل رقم عملية التحويل">
                            </div>
                        </div>
                    </div>

                    <!-- Syriatel Cash Details -->
                    <div x-show="selectedMethod === 'syriatel_cash'" class="mb-6 bg-red-50 p-4 rounded-lg border border-red-200">
                        <p class="text-sm text-red-800 mb-2 font-bold">تعليمات الدفع (سيريتيل كاش):</p>
                        <div class="text-sm text-gray-700 mb-4 whitespace-pre-line">{{ $settings['syriatel_cash_instructions'] ?? 'يرجى التواصل مع الإدارة' }}</div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رقم العملية / الإشعار</label>
                                <input type="text" name="syriatel_ref" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="أدخل رقم عملية التحويل">
                            </div>
                        </div>
                    </div>

                    <!-- Turkish IBAN Details -->
                    <div x-show="selectedMethod === 'turkish_iban'" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-800 mb-2 font-bold">بيانات الحساب البنكي:</p>
                        <div class="text-sm text-gray-700 mb-4 whitespace-pre-line font-mono bg-white p-3 rounded border">{{ $settings['turkish_bank_details'] ?? 'يرجى التواصل مع الإدارة' }}</div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رقم العملية / اسم المرسل</label>
                                <input type="text" name="turkish_ref" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="أدخل مرجع التحويل">
                            </div>
                        </div>
                    </div>

                    <!-- Common Receipt Upload (Visible for all manual methods) -->
                    <div x-show="['sham_cash', 'syriatel_cash', 'turkish_iban'].includes(selectedMethod)" class="mb-6">
                         <label class="block text-sm font-medium text-gray-700 mb-1">صورة الإشعار (مطلوب)</label>
                         <input type="file" name="receipt_image" accept="image/*" class="block w-full text-sm text-gray-500
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-full file:border-0
                           file:text-sm file:font-semibold
                           file:bg-indigo-50 file:text-indigo-700
                           hover:file:bg-indigo-100
                         "/>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        إتمام وإرسال
                    </button>
                    
                    <button type="button" @click="showModal = false" class="mt-3 w-full bg-white border border-gray-300 rounded-md shadow-sm py-3 px-4 text-base font-medium text-gray-700 hover:bg-gray-50">
                        إلغاء
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
