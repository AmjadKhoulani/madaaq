@extends('layouts.admin')

@section('title', 'إعدادات النظام | Central Governance Hub')

@section('content')
<div class="space-y-12 pb-24" x-data="{ 
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
        this.statusColor = 'text-slate-400';
        
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
                this.statusColor = 'text-neon-cyan';
            } else {
                this.statusMessage = data.message;
                this.statusColor = 'text-rose-500';
            }
        } catch (error) {
            console.error(error);
            this.statusMessage = 'Protocol Check Error';
            this.statusColor = 'text-rose-500';
        } finally {
            this.isChecking = false;
        }
    }
}">
    
    <!-- Radiant Hub Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-12 h-1 bg-accent-flow rounded-full"></span>
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em] font-headline">System Architecture Governance</p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 tracking-tighter italic uppercase">إعدادات النظام المركزية</h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Customizing Global Parameters, Fiscal Protocols & Identity Gateways</p>
        </div>
    </div>

    <!-- Radiant Tab Navigation -->
    <div class="glass-panel p-2 rounded-[2.5rem] !bg-white/60 border-slate-100 shadow-sm flex flex-wrap lg:flex-nowrap gap-2 sticky top-24 z-30 backdrop-blur-3xl">
        <button @click="activeTab = 'general'" 
                :class="activeTab === 'general' ? 'bg-slate-900 text-neon-cyan shadow-glow-cyan' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 px-6 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all flex justify-center items-center gap-3 italic">
            <span class="material-symbols-outlined text-sm">settings_input_component</span>
            عامة والعملات
        </button>
        <button @click="activeTab = 'automation'" 
                :class="activeTab === 'automation' ? 'bg-vibrant-purple text-white shadow-glow-purple' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 px-6 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all flex justify-center items-center gap-3 italic">
            <span class="material-symbols-outlined text-sm">robot_2</span>
            الأتمتة والواتساب
        </button>
        <button @click="activeTab = 'payments'" 
                :class="activeTab === 'payments' ? 'bg-emerald-600 text-white shadow-glow-cyan' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 px-6 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all flex justify-center items-center gap-3 italic">
            <span class="material-symbols-outlined text-sm">payments</span>
            بوابات الدفع
        </button>
        <button @click="activeTab = 'domain'" 
                :class="activeTab === 'domain' ? 'bg-indigo-600 text-white shadow-glow-purple' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 px-6 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all flex justify-center items-center gap-3 italic">
            <span class="material-symbols-outlined text-sm">public</span>
            بوابة المشتركين
        </button>
        <button @click="activeTab = 'profile'" 
                :class="activeTab === 'profile' ? 'bg-primary text-white shadow-glow-purple' : 'text-slate-500 hover:bg-slate-50'"
                class="flex-1 px-6 py-4 rounded-3xl text-[10px] font-black uppercase tracking-widest transition-all flex justify-center items-center gap-3 italic">
            <span class="material-symbols-outlined text-sm">account_circle</span>
            الملف الشخصي
        </button>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
        @csrf

        <!-- TAB 1: General Settings -->
        <div x-show="activeTab === 'general'" x-transition class="space-y-10">
            <!-- Company Info Matrix -->
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                <div class="flex items-center gap-6 mb-10">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                        <span class="material-symbols-outlined">corporate_fare</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">معلومات المنظمة المركزية</h3>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">اسم المؤسسة المعترف به</label>
                        <input type="text" name="company_name" value="{{ $settings['company_name'] }}" class="input-radiant !py-4 text-[11px] font-black uppercase italic">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">الشعار المؤسسي (Identity Brand)</label>
                        <div class="flex items-center gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 shadow-inner">
                            @if($settings['company_logo'])
                                <img src="{{ asset('storage/' . $settings['company_logo']) }}" alt="Logo" class="h-16 w-16 object-contain rounded-xl bg-white shadow-sm border border-slate-100 p-2">
                            @else
                                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center text-slate-200 border border-slate-100">
                                    <span class="material-symbols-outlined text-4xl font-light">image</span>
                                </div>
                            @endif
                            <input type="file" name="company_logo" class="text-[9px] font-black text-slate-500 uppercase italic file:bg-primary file:text-white file:border-0 file:rounded-xl file:px-6 file:py-2 file:mr-4 file:text-[9px] cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Currency Protocols -->
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                <div class="flex items-center gap-6 mb-10">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-inner">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">العملات والبروتوكولات المالية</h3>
                </div>
                <div class="max-w-xl space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">عملة السيادة المركزية</label>
                    <select name="currency" class="input-radiant !py-4 text-[11px] font-black uppercase italic appearance-none">
                        <option value="ر.س" {{ $settings['currency'] === 'ر.س' ? 'selected' : '' }}>SAR - ريال سعودي</option>
                        <option value="$" {{ $settings['currency'] === '$' ? 'selected' : '' }}>USD - دولار أمريكي</option>
                        <option value="ل.س" {{ $settings['currency'] === 'ل.س' ? 'selected' : '' }}>SYP - ليرة سورية</option>
                        <option value="د.إ" {{ $settings['currency'] === 'د.إ' ? 'selected' : '' }}>AED - درهم إماراتي</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- TAB 2: Automation & WhatsApp -->
        <div x-show="activeTab === 'automation'" x-transition class="space-y-10" x-cloak>
            <!-- Automation Matrix -->
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                <div class="flex items-center gap-6 mb-10">
                    <div class="w-12 h-12 bg-vibrant-purple/10 rounded-2xl flex items-center justify-center text-vibrant-purple group-hover:bg-vibrant-purple group-hover:text-white transition-all shadow-inner">
                        <span class="material-symbols-outlined">auto_mode</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">الأتمتة والتحقق التلقائي</h3>
                </div>

                <div class="p-8 bg-slate-900 rounded-[2rem] border border-white/5 shadow-neon-purple/20 mb-10 flex items-center justify-between group">
                    <div>
                        <h4 class="text-xs font-black text-neon-cyan uppercase tracking-[0.2em] italic mb-1">التعليق التلقائي للخدمة</h4>
                        <p class="text-[10px] font-bold text-slate-400 italic">Automatic Protocol Termination on Subscription Lapse</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_suspend_enabled" value="1" class="sr-only peer" {{ $settings['auto_suspend_enabled'] ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:bg-neon-cyan after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full shadow-glow-cyan/20"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">فترة السماح (Temporal Grace Days)</label>
                        <input type="number" name="default_grace_period" value="{{ $settings['default_grace_period'] }}" class="input-radiant !py-4 text-[12px] font-bold font-manrope">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">إصدار الفاتورة قبل (Emissions Advance)</label>
                        <input type="number" name="invoice_generation_days" value="{{ $settings['invoice_generation_days'] }}" class="input-radiant !py-4 text-[12px] font-bold font-manrope">
                    </div>
                </div>
            </div>

            <!-- WhatsApp Intelligence -->
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100" x-data="{ whatsappType: '{{ $settings['whatsapp_type'] ?? 'api' }}' }">
                <div class="flex items-center gap-6 mb-10">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 shadow-inner">
                        <span class="material-symbols-outlined">message</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">إعدادات بروتوكول واتساب</h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                    <label class="flex items-center gap-6 p-6 rounded-[2rem] border-4 cursor-pointer transition-all group" 
                           :class="whatsappType === 'api' ? 'border-neon-cyan bg-slate-900 text-white shadow-glow-cyan' : 'border-slate-100 bg-white hover:border-slate-200'">
                        <input type="radio" name="whatsapp_type" value="api" x-model="whatsappType" class="sr-only">
                        <span class="material-symbols-outlined text-3xl opacity-50 group-hover:opacity-100" :class="whatsappType === 'api' ? 'text-neon-cyan opacity-100' : ''">cloud_queue</span>
                        <div>
                            <div class="text-xs font-black uppercase italic tracking-tighter">WhatsApp Cloud API</div>
                            <div class="text-[9px] font-bold uppercase tracking-widest opacity-60 mt-1">تزامن آلي كامل (Automated Matrix)</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-6 p-6 rounded-[2rem] border-4 cursor-pointer transition-all group"
                           :class="whatsappType === 'regular' ? 'border-primary bg-slate-900 text-white shadow-glow-purple' : 'border-slate-100 bg-white hover:border-slate-200'">
                        <input type="radio" name="whatsapp_type" value="regular" x-model="whatsappType" class="sr-only">
                        <span class="material-symbols-outlined text-3xl opacity-50 group-hover:opacity-100" :class="whatsappType === 'regular' ? 'text-primary opacity-100' : ''">smartphone</span>
                        <div>
                            <div class="text-xs font-black uppercase italic tracking-tighter">Direct Mobile Link</div>
                            <div class="text-[9px] font-bold uppercase tracking-widest opacity-60 mt-1">توجيه يدوي wa.me (Manual Handshake)</div>
                        </div>
                    </label>
                </div>

                <div x-show="whatsappType === 'api'" x-transition class="space-y-8">
                     <div class="p-6 bg-slate-900 rounded-2xl border border-neon-cyan/20 text-neon-cyan flex items-start gap-4 italic font-bold text-[10px] uppercase tracking-widest shadow-glow-cyan/5">
                        <span class="material-symbols-outlined text-xl">info</span>
                        ONLY WhatsApp Cloud API enables internal chat matrix and automated system emissions.
                     </div>
                     <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Access Token (Permanent)</label>
                        <input type="password" name="whatsapp_token" value="{{ $settings['whatsapp_token'] }}" class="input-radiant !py-4 text-[12px] font-bold font-manrope pr-12">
                     </div>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Phone Identity ID</label>
                            <input type="text" name="whatsapp_phone_id" value="{{ $settings['whatsapp_phone_id'] }}" class="input-radiant !py-4 text-[12px] font-bold font-manrope">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Business Account Identity</label>
                            <input type="text" name="whatsapp_business_id" value="{{ $settings['whatsapp_business_id'] }}" class="input-radiant !py-4 text-[12px] font-bold font-manrope">
                        </div>
                     </div>
                </div>

                <div x-show="whatsappType === 'regular'" x-transition class="max-w-xl space-y-4">
                     <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">رقم التواصل المباشر (Format: 963...)</label>
                        <input type="text" name="whatsapp_regular_number" value="{{ $settings['whatsapp_regular_number'] }}" class="input-radiant !py-4 text-[12px] font-black font-manrope tracking-[0.2em] uppercase pr-12" placeholder="963999123456" dir="ltr">
                     </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: Payment Gateways -->
        <div x-show="activeTab === 'payments'" x-transition class="space-y-10" x-cloak>
            <!-- High-Impact Gateway Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Stripe Gateway -->
                <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-blue-600/10 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                                <span class="material-symbols-outlined">credit_card</span>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">Stripe Protocol</h3>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="stripe_active" value="1" class="sr-only peer" {{ $settings['stripe_active'] ? 'checked' : '' }}>
                            <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Public Identity Key</label>
                            <input type="text" name="stripe_public_key" value="{{ $settings['stripe_public_key'] }}" class="input-radiant !py-3 text-[10px] font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Secret Hash Token</label>
                            <input type="password" name="stripe_secret_key" value="{{ $settings['stripe_secret_key'] }}" class="input-radiant !py-3 text-[10px] font-bold">
                        </div>
                    </div>
                </div>

                <!-- PayPal Gateway -->
                <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-blue-900/10 rounded-2xl flex items-center justify-center text-blue-900 shadow-inner">
                                <span class="material-symbols-outlined">account_balance_wallet</span>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter">PayPal Link</h3>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="paypal_active" value="1" class="sr-only peer" {{ $settings['paypal_active'] ? 'checked' : '' }}>
                            <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-900 after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Client Identity</label>
                            <input type="text" name="paypal_client_id" value="{{ $settings['paypal_client_id'] }}" class="input-radiant !py-3 text-[10px] font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">Secret Exchange Hash</label>
                            <input type="password" name="paypal_secret" value="{{ $settings['paypal_secret'] }}" class="input-radiant !py-3 text-[10px] font-bold">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: Domain Settings -->
        <div x-show="activeTab === 'domain'" x-transition class="space-y-10" x-cloak>
            <div class="glass-panel p-12 rounded-[3.5rem] !bg-white/80 border-slate-100 shadow-2xl relative overflow-hidden group">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-12">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600 border border-indigo-600/20 shadow-inner">
                                <span class="material-symbols-outlined text-3xl font-light">language</span>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter italic">بوابة المشتركين (Client Portal Node)</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Identity Gateway & Domain Allocation Management</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_subdomain_enabled" value="1" class="sr-only peer" {{ isset($tenant) && $tenant->is_subdomain_enabled ? 'checked' : '' }} x-model="subdomainEnabled">
                            <div class="w-16 h-8 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[6px] after:left-[6px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full shadow-glow-purple/20"></div>
                        </label>
                    </div>

                    <div x-show="subdomainEnabled" x-transition class="space-y-10">
                         @if(isset($tenant) && $tenant->domain && $tenant->is_subdomain_enabled)
                            <div class="flex items-center gap-6 p-8 bg-slate-900 rounded-[2rem] border border-white/5 shadow-neon-purple/20">
                                <span class="material-symbols-outlined text-neon-cyan">link</span>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Active Protocol Endpoint</p>
                                    <h4 class="text-xs font-black text-white font-manrope tracking-[0.2em] uppercase italic underline decoration-neon-cyan decoration-2 underline-offset-4">
                                        @php
                                            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
                                            $appHost = str_replace('www.', '', $appHost);
                                            $fullUrl = str_contains($tenant->domain, '.') ? $tenant->domain : $tenant->domain . '.' . $appHost;
                                            $fullUrl = 'http://' . $fullUrl;
                                        @endphp
                                        {{ $fullUrl }}
                                    </h4>
                                </div>
                                <a href="{{ $fullUrl }}" target="_blank" class="mr-auto w-12 h-12 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center text-white transition-all">
                                    <span class="material-symbols-outlined">open_in_new</span>
                                </a>
                            </div>
                         @endif

                        <div class="max-w-2xl space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">تخصيص النطاق الفرعي (Subdomain Allocation)</label>
                            <div class="flex items-center group shadow-sm rounded-3xl overflow-hidden border border-slate-200 bg-white focus-within:ring-4 focus-within:ring-primary/10 transition-all" dir="ltr">
                                <span class="px-6 py-4 bg-slate-50 text-slate-400 text-xs font-black border-r border-slate-200">https://</span>
                                <input type="text" name="subdomain" 
                                       x-model="subdomain"
                                       @input.debounce.500ms="checkSubdomain()"
                                       placeholder="identity-node" 
                                       class="flex-1 px-6 py-4 outline-none text-left text-xs font-black tracking-widest text-primary placeholder:text-slate-200"
                                       pattern="[A-Za-z0-9\-]+">
                                <span class="px-6 py-4 bg-slate-50 text-slate-400 text-xs font-black border-l border-slate-200">.{{ str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST)) }}</span>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between items-center gap-4 px-4">
                                <div class="flex items-center gap-2">
                                    <div x-show="isChecking" class="w-3 h-3 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                                    <p class="text-[9px] font-black uppercase italic tracking-widest text-slate-400" :class="statusColor" x-text="statusMessage"></p>
                                </div>
                                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Alpha-numeric characters and (-) only. Void spaces detected as errors.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 5: Profile Settings -->
        <div x-show="activeTab === 'profile'" x-transition class="space-y-10" x-cloak>
            <div class="glass-panel p-10 rounded-[3rem] !bg-white/80 border-slate-100 group">
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                        <span class="material-symbols-outlined text-3xl">account_circle</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter italic">الملف الشخصي للمعماري</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Identity Management & Secure Handshake Access</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">الاسم الكامل (Identity Name)</label>
                        <input type="text" name="profile_name" value="{{ auth()->user()->name }}" class="input-radiant !py-4 text-[11px] font-black uppercase italic pr-12">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">رقم الهاتف (Protocol Contact)</label>
                        <input type="text" name="profile_phone" value="{{ auth()->user()->phone }}" class="input-radiant !py-4 text-[11px] font-black font-manrope tracking-[0.2em] pr-12" dir="ltr">
                    </div>
                    <div class="md:col-span-2 space-y-3 opacity-60 grayscale cursor-not-allowed">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">البريد الإلكتروني للتعريف (Read-Only Identity)</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled class="input-radiant !py-4 text-[11px] bg-slate-50 font-black">
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-12">
                    <h4 class="text-xs font-black text-slate-900 uppercase italic tracking-[0.3em] mb-8">تحديث بروتوكول الأمان (Security Update)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                         <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">كلمة المرور الجديدة (New Access Hash)</label>
                            <input type="password" name="profile_password" class="input-radiant !py-4 text-[11px] font-black" placeholder="PROTOCOL REMAINS IF NULL">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">تأكيد الهاش الجديد (Confirm Hash)</label>
                            <input type="password" name="profile_password_confirmation" class="input-radiant !py-4 text-[11px] font-black">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Radiant Floating Footer Action -->
        <div class="fixed bottom-10 inset-x-0 mx-auto max-w-4xl px-8 z-50">
            <div class="glass-panel !bg-slate-900/90 text-white rounded-3xl p-4 shadow-2xl shadow-neon-purple/30 border border-white/10 flex items-center justify-between backdrop-blur-3xl animate-enter">
                <div class="hidden md:flex items-center gap-6 px-4">
                    <span class="w-3 h-3 bg-neon-cyan rounded-full animate-pulse shadow-glow-cyan"></span>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] italic opacity-60">System Ready for Governance Update</p>
                </div>
                <button type="submit" class="w-full md:w-auto px-12 py-4 bg-accent-flow text-white font-black rounded-2xl text-[11px] uppercase tracking-[0.4em] shadow-glow-purple hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center justify-center gap-4 italic group">
                    <span class="material-symbols-outlined text-lg group-hover:rotate-180 transition-transform duration-500">sync_saved_locally</span>
                    تثبيت كافة التغييرات المركزية
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
