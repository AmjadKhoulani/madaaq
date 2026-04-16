<script setup>
import { ref, watch } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import axios from 'axios';
import { debounce } from 'lodash';

const props = defineProps({
    settings: Object,
    tenant: Object,
    auth: Object
});

const activeTab = ref('general');

const form = useForm({
    // Profile
    profile_name: props.auth.user.name,
    profile_phone: props.auth.user.phone,
    profile_password: '',
    profile_password_confirmation: '',

    // General
    company_name: props.settings.company_name,
    company_logo: null,
    currency: props.settings.currency || 'ل.س',
    secondary_currency: props.settings.secondary_currency || '$',
    exchange_rate: props.settings.exchange_rate,
    
    // Automation
    auto_suspend_enabled: props.settings.auto_suspend_enabled == 1,
    default_grace_period: props.settings.default_grace_period,
    invoice_generation_days: props.settings.invoice_generation_days,

    // WhatsApp
    whatsapp_type: props.settings.whatsapp_type,
    whatsapp_token: props.settings.whatsapp_token,
    whatsapp_phone_id: props.settings.whatsapp_phone_id,
    whatsapp_business_id: props.settings.whatsapp_business_id,
    whatsapp_regular_number: props.settings.whatsapp_regular_number,

    // Payments
    stripe_active: props.settings.stripe_active == 1,
    stripe_public_key: props.settings.stripe_public_key,
    stripe_secret_key: props.settings.stripe_secret_key,
    
    paypal_active: props.settings.paypal_active == 1,
    paypal_client_id: props.settings.paypal_client_id,
    paypal_secret: props.settings.paypal_secret,

    cham_cash_active: props.settings.cham_cash_active == 1,
    cham_cash_merchant_id: props.settings.cham_cash_merchant_id,
    cham_cash_secret_key: props.settings.cham_cash_secret_key,

    syriatel_cash_active: props.settings.syriatel_cash_active == 1,
    syriatel_cash_merchant_id: props.settings.syriatel_cash_merchant_id,
    syriatel_cash_pin: props.settings.syriatel_cash_pin,

    // Domain
    subdomain: props.tenant?.domain || '',
    is_subdomain_enabled: props.tenant?.is_subdomain_enabled == 1,
});

const subdomainStatus = ref({ checking: false, available: null, message: '' });

const checkSubdomainAvailability = debounce(async (val) => {
    if (!val || val.length < 3) {
        subdomainStatus.value = { checking: false, available: null, message: '' };
        return;
    }
    
    subdomainStatus.value.checking = true;
    try {
        const response = await axios.post(route('settings.subdomain.check'), { subdomain: val });
        subdomainStatus.value = { checking: false, available: response.data.available, message: response.data.message };
    } catch (e) {
        subdomainStatus.value = { checking: false, available: false, message: 'Invalid format protocol' };
    }
}, 500);

watch(() => form.subdomain, (newVal) => {
    if (newVal !== props.tenant?.domain) {
        checkSubdomainAvailability(newVal);
    } else {
        subdomainStatus.value = { checking: false, available: null, message: '' };
    }
});

const submit = () => {
    form.post(route('settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Success logic
        }
    });
};

const tabs = [
    { id: 'general', icon: 'settings', label: 'البروتوكولات العامة' },
    { id: 'automation', icon: 'offline_bolt', label: 'حوكمة الأتمتة' },
    { id: 'reach', icon: 'chat_bubble', label: 'خوارزميات التواصل' },
    { id: 'payments', icon: 'payments', label: 'بوابات السيولة' },
    { id: 'domain', icon: 'language', label: 'بنية النطاقات' },
    { id: 'security', icon: 'manage_accounts', label: 'أمن المشغل' },
];

</script>

<template>
    <InstitutionalLayout title="مركز حوكمة المنصة">
        <Head title="إعدادات النظام الإستراتيجية - MadaaQ" />

        <div class="max-w-7xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 flex-row-reverse text-right">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2 uppercase">مركز الحوكمة والإعدادات السيادية (HQ Settings)</h1>
                    <div class="flex items-center gap-4 justify-end">
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">ضبط معايير التشغيل المركزية، الهوية المؤسساتية، وبنيوية بوابات الربط البرمجي</p>
                        <span class="material-symbols-outlined text-[24px] text-primary">admin_panel_settings</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 flex-row-reverse">
                <!-- Navigation Tab Rail (Command Center Style) -->
                <div class="lg:col-span-1 space-y-4 order-2 lg:order-1">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        class="w-full surface-card p-6 flex items-center gap-5 transition-all hover:translate-x-[-8px] flex-row-reverse group rounded-2xl border border-outline-variant/10 shadow-sm"
                        :class="activeTab === tab.id ? 'bg-primary text-white shadow-2xl shadow-primary/20 border-primary' : 'bg-white text-slate-400 hover:text-primary hover:bg-slate-50'"
                    >
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300" :class="activeTab === tab.id ? 'bg-white/20 rotate-12' : 'bg-surface-container shadow-inner border border-outline-variant/5'">
                            <span class="material-symbols-outlined text-[28px]" :style="activeTab === tab.id ? 'font-variation-settings: \'FILL\' 1' : ''">{{ tab.icon }}</span>
                        </div>
                        <span class="font-black text-[12px] uppercase tracking-[0.2em]">{{ tab.label }}</span>
                    </button>

                    <!-- System Integrity Seal VIP -->
                    <div class="surface-card p-10 bg-slate-950 text-white relative overflow-hidden group mt-12 rounded-[2rem] shadow-2xl border border-white/5">
                         <div class="absolute -top-16 -right-16 w-64 h-64 bg-primary/20 rounded-full blur-3xl group-hover:scale-125 transition-all duration-1000"></div>
                         <div class="relative z-10 flex flex-col gap-8 text-right">
                             <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center shadow-inner border border-white/5">
                                <span class="material-symbols-outlined text-[40px] text-white/40">verified_user</span>
                             </div>
                             <div>
                                 <h4 class="text-base font-black uppercase tracking-tight">نزاهة الهيكلية الرقمية</h4>
                                 <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mt-3 leading-relaxed">تعديل هذه البروتوكولات يؤثر بشكل تكتيكي مباشر على تدفق البيانات واستدامة الخدمات.</p>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- Intelligence Configuration Workspace -->
                <div class="lg:col-span-3 order-1 lg:order-2">
                    <form @submit.prevent="submit" class="space-y-12">
                        
                        <!-- 1. GENERAL IDENTITIES -->
                        <div v-if="activeTab === 'general'" class="animate-in slide-in-from-top duration-500">
                             <div class="surface-card p-12 space-y-12 rounded-[2.5rem] shadow-2xl border border-outline-variant/5 bg-white">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">بروتوكول الهوية الأساسي (Core Identity)</h3>
                                    <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 flex-row-reverse">
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">مُعرف المنظومة (Network Label)</label>
                                        <input v-model="form.company_name" type="text" class="form-input-monolith h-16 font-black text-xl">
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">مخطط الشعار المؤسساتي (Logo Artifact)</label>
                                        <div class="flex items-center gap-6 flex-row-reverse">
                                            <div class="w-20 h-20 rounded-2xl bg-surface-container flex items-center justify-center text-slate-300 border border-outline-variant/10 shadow-2xl overflow-hidden relative group/logo">
                                                <div class="absolute inset-0 bg-primary opacity-0 group-hover/logo:opacity-5 transition-opacity"></div>
                                                <span v-if="!settings.company_logo" class="material-symbols-outlined text-[32px]">image</span>
                                                <img v-else :src="'/storage/' + settings.company_logo" class="w-full h-full object-contain p-2" />
                                            </div>
                                            <input type="file" @change="e => form.company_logo = e.target.files[0]" class="flex-1 text-[11px] font-black text-slate-400 file:ml-6 file:py-4 file:px-10 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-950 file:text-white hover:file:bg-primary transition-all cursor-pointer shadow-sm" />
                                        </div>
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">العملة السيادية للمنظومة</label>
                                        <input v-model="form.currency" type="text" class="form-input-monolith h-16 font-black text-xl" placeholder="ل.س">
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">عملة التقاص الثانوية (Global)</label>
                                        <input v-model="form.secondary_currency" type="text" class="form-input-monolith h-16 font-black text-xl" placeholder="$">
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">معامل التحويل اللحظي (Exchange Rate)</label>
                                        <div class="relative group">
                                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors">currency_exchange</span>
                                            <input v-model="form.exchange_rate" type="number" step="0.0001" class="form-input-monolith h-16 pr-14 font-headline font-black text-2xl tracking-tighter">
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- 2. AUTOMATION DEPLOYMENT -->
                        <div v-if="activeTab === 'automation'" class="animate-in slide-in-from-top duration-500">
                             <div class="surface-card p-12 space-y-12 bg-white rounded-[2.5rem] shadow-2xl border border-outline-variant/5">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">بروتوكول الأتمتة والتحصيل (Automation Grid)</h3>
                                    <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
                                </div>

                                <div class="space-y-12">
                                     <div class="flex items-center justify-between p-12 bg-slate-50 rounded-3xl border border-outline-variant/10 flex-row-reverse shadow-inner">
                                         <div class="flex items-center gap-8 flex-row-reverse">
                                             <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center border border-emerald-500/20 shadow-lg">
                                                <span class="material-symbols-outlined text-[32px]">bolt</span>
                                             </div>
                                             <div class="text-right">
                                                 <h4 class="text-lg font-black uppercase tracking-tight">بروتوكول الإيقاف التلقائي (Auto-Suspend)</h4>
                                                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-2 leading-none">فصل المشتركين برمجياً فور انتهاء الجلسة الزمنية</p>
                                             </div>
                                         </div>
                                         <div class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.auto_suspend_enabled" type="checkbox" class="sr-only peer">
                                             <div class="w-16 h-10 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-8 after:w-8 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                                         </div>
                                     </div>

                                     <div class="grid grid-cols-1 md:grid-cols-2 gap-10 flex-row-reverse">
                                         <div class="space-y-4 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">فترة السماح التكتيكية (Grace Period)</label>
                                            <div class="relative">
                                                <input v-model="form.default_grace_period" type="number" class="form-input-monolith h-16 font-headline font-black text-2xl pr-8">
                                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase tracking-widest">HRS/DAYS</span>
                                            </div>
                                        </div>
                                        <div class="space-y-4 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">دورة إصدار الفواتير المسبقة (يوم)</label>
                                            <div class="relative">
                                                <input v-model="form.invoice_generation_days" type="number" class="form-input-monolith h-16 font-headline font-black text-2xl pr-8">
                                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase tracking-widest">DAYS</span>
                                            </div>
                                        </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 3. REACH INTELLIGENCE -->
                        <div v-if="activeTab === 'reach'" class="animate-in slide-in-from-top duration-500">
                             <div class="surface-card p-12 space-y-12 bg-white rounded-[2.5rem] shadow-2xl border border-outline-variant/5">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">خوارزميات التواصل الموجه (Messaging Protocols)</h3>
                                    <div class="w-1.5 h-8 bg-[#25d366] rounded-full"></div>
                                </div>

                                <div class="space-y-12 text-right">
                                     <div class="space-y-5">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 text-right block">طريقة الربط البرمجي (Integration Protocol)</label>
                                        <div class="flex bg-slate-50 p-2 rounded-2xl border border-outline-variant/10 w-fit mr-auto flex-row-reverse shadow-inner">
                                            <button type="button" @click="form.whatsapp_type = 'api'" class="px-10 py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] transition-all" :class="form.whatsapp_type === 'api' ? 'bg-primary text-white shadow-xl' : 'text-slate-400 hover:text-primary'">Meta Cloud API (Premium)</button>
                                            <button type="button" @click="form.whatsapp_type = 'regular'" class="px-10 py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] transition-all" :class="form.whatsapp_type === 'regular' ? 'bg-primary text-white shadow-xl' : 'text-slate-400 hover:text-primary'">Direct Mobile Number</button>
                                        </div>
                                    </div>

                                    <div v-if="form.whatsapp_type === 'api'" class="grid grid-cols-1 md:grid-cols-2 gap-10 flex-row-reverse">
                                         <div class="space-y-4 md:col-span-2 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">مفتاح الوصول السيادي (Meta Cloud Token)</label>
                                            <input v-model="form.whatsapp_token" type="password" class="form-input-monolith h-16 font-headline font-black tracking-widest text-lg">
                                        </div>
                                        <div class="space-y-4 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">معرف بوابة الإرسال (Phone Node ID)</label>
                                            <input v-model="form.whatsapp_phone_id" type="text" class="form-input-monolith h-16 font-headline font-black text-xl">
                                        </div>
                                        <div class="space-y-4 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">معرف الأعمال المعتمد (Business ID)</label>
                                            <input v-model="form.whatsapp_business_id" type="text" class="form-input-monolith h-16 font-headline font-black text-xl">
                                        </div>
                                    </div>
                                    <div v-else class="space-y-4 text-right max-w-md mr-auto">
                                         <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">رقم التواصل المعتمد (Authorized Number)</label>
                                         <div class="relative group">
                                             <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-[#25d366] transition-colors">call</span>
                                             <input v-model="form.whatsapp_regular_number" type="text" class="form-input-monolith h-16 pr-14 font-headline font-black text-2xl tracking-widest" placeholder="+963 --- --- ---">
                                         </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- 4. GATEWAY PROTOCOLS (Fiscal) -->
                        <div v-if="activeTab === 'payments'" class="animate-in slide-in-from-top duration-500">
                             <div class="space-y-10">
                                <div class="surface-card p-12 space-y-12 bg-white rounded-[2.5rem] shadow-2xl border border-outline-variant/5">
                                    <div class="flex items-center justify-between flex-row-reverse">
                                        <div class="flex items-center gap-8 flex-row-reverse">
                                             <div class="w-16 h-16 rounded-2xl bg-[#635bff] text-white flex items-center justify-center italic font-black text-4xl shadow-2xl shadow-indigo-200">S</div>
                                             <div class="text-right">
                                                 <h3 class="text-lg font-black uppercase tracking-tight">بوابة Stripe العالمية للخصم</h3>
                                                 <p class="text-[11px] font-black text-slate-400 uppercase mt-2 tracking-widest opacity-60">بروتوكول تحصيل البطاقات الائتمانية والخصم المباشر العالمي</p>
                                             </div>
                                        </div>
                                        <div class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.stripe_active" type="checkbox" class="sr-only peer">
                                             <div class="w-16 h-10 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-8 after:w-8 after:transition-all peer-checked:bg-[#635bff] shadow-inner"></div>
                                         </div>
                                    </div>
                                    <div v-if="form.stripe_active" class="grid grid-cols-1 md:grid-cols-2 gap-10 flex-row-reverse animate-in fade-in zoom-in duration-300">
                                         <div class="space-y-4 text-right">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">المفتاح العلني للمنظومة (Publishable Key)</label>
                                            <input v-model="form.stripe_public_key" type="text" class="form-input-monolith h-14 font-headline text-[13px] tracking-tight">
                                        </div>
                                        <div class="space-y-4 text-right">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">المفتاح السري المشفر (Secret Key Artifact)</label>
                                            <input v-model="form.stripe_secret_key" type="password" class="form-input-monolith h-14 font-headline text-[13px] tracking-widest">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 flex-row-reverse">
                                     <div class="surface-card p-10 space-y-10 rounded-3xl bg-white shadow-xl border border-[#003087]/5">
                                         <div class="flex items-center justify-between flex-row-reverse">
                                             <div class="flex items-center gap-4 flex-row-reverse">
                                                 <div class="w-10 h-10 rounded-lg bg-[#0070ba] text-white flex items-center justify-center italic font-black text-xl">P</div>
                                                 <p class="text-[11px] font-black uppercase tracking-widest text-[#003087]">PayPal Hub Integration</p>
                                             </div>
                                             <input v-model="form.paypal_active" type="checkbox" class="w-6 h-6 rounded-lg border-outline-variant/30 text-[#0070ba] focus:ring-[#0070ba] shadow-sm">
                                         </div>
                                         <div v-if="form.paypal_active" class="space-y-6 animate-in slide-in-from-top duration-300">
                                             <input v-model="form.paypal_client_id" type="text" class="form-input-monolith h-14 text-[12px] font-headline" placeholder="Client Protocol ID">
                                             <input v-model="form.paypal_secret" type="password" class="form-input-monolith h-14 text-[12px] font-headline" placeholder="Secure Secret Token">
                                         </div>
                                     </div>
                                     <div class="surface-card p-10 space-y-10 rounded-3xl border border-rose-200 bg-rose-50/20">
                                         <div class="flex items-center justify-between flex-row-reverse">
                                             <div class="flex items-center gap-4 flex-row-reverse">
                                                 <div class="w-10 h-10 rounded-lg bg-rose-600 text-white flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">payments</span></div>
                                                 <p class="text-[11px] font-black uppercase tracking-widest text-rose-600">بوابة سيريتل كاش (SyriaTel)</p>
                                             </div>
                                             <input v-model="form.syriatel_cash_active" type="checkbox" class="w-6 h-6 rounded-lg border-rose-200 text-rose-600 focus:ring-rose-600 shadow-sm">
                                         </div>
                                         <div v-if="form.syriatel_cash_active" class="space-y-6 animate-in slide-in-from-top duration-300">
                                             <input v-model="form.syriatel_cash_merchant_id" type="text" class="form-input-monolith h-14 text-[12px] font-headline border-rose-100" placeholder="Merchant Identity Code">
                                             <input v-model="form.syriatel_cash_pin" type="password" class="form-input-monolith h-14 text-[12px] font-headline border-rose-100" placeholder="Wallet PIN Artifact">
                                         </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 5. DOMAIN ARCHITECTURE -->
                        <div v-if="activeTab === 'domain'" class="animate-in slide-in-from-top duration-500">
                             <div class="surface-card p-12 space-y-12 bg-white rounded-[2.5rem] shadow-2xl border border-outline-variant/5">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">هيكلية النطاقات المركزية (Domain Logic)</h3>
                                    <div class="w-1.5 h-8 bg-indigo-600 rounded-full"></div>
                                </div>

                                <div class="space-y-12">
                                     <div class="flex items-center justify-between p-12 bg-indigo-50/50 rounded-3xl border border-indigo-100 flex-row-reverse shadow-inner">
                                         <div class="flex items-center gap-8 flex-row-reverse">
                                             <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-2xl border border-white/10">
                                                <span class="material-symbols-outlined text-[32px]">public</span>
                                             </div>
                                             <div class="text-right">
                                                 <h4 class="text-lg font-black uppercase tracking-tight">اسم النطاق الفرعي السيادي (Subdomain)</h4>
                                                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-2 leading-none">تخصيص مسار فرعي موحد للوصول لبوابة الخدمات</p>
                                             </div>
                                         </div>
                                         <div class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.is_subdomain_enabled" type="checkbox" class="sr-only peer">
                                             <div class="w-16 h-10 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-8 after:w-8 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                                         </div>
                                     </div>

                                     <div v-if="form.is_subdomain_enabled" class="space-y-12 p-12 bg-white border border-outline-variant/10 rounded-[2rem] shadow-2xl animate-in fade-in duration-500">
                                         <div class="space-y-6 text-right">
                                            <div class="flex items-center justify-between px-3 flex-row-reverse">
                                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest leading-none">بادئة الرابط اللحظية (Prefix)</label>
                                                <div v-if="subdomainStatus.checking" class="flex items-center gap-3 text-[10px] font-black text-indigo-500 uppercase">
                                                    <span class="material-symbols-outlined text-[18px] animate-spin">rebase</span> جاري فحص الوفرة...
                                                </div>
                                                <div v-else-if="subdomainStatus.available === true" class="flex items-center gap-3 text-[10px] font-black text-emerald-500 uppercase">
                                                    <span class="material-symbols-outlined text-[18px]">verified</span> البروتوكول متاح للاعتماد
                                                </div>
                                                <div v-else-if="subdomainStatus.available === false" class="flex items-center gap-3 text-[10px] font-black text-rose-500 uppercase">
                                                    <span class="material-symbols-outlined text-[18px]">warning</span> البادئة محجوزة مسبقاً
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-8 flex-row-reverse">
                                                <div class="flex-1 relative group">
                                                    <input 
                                                        v-model="form.subdomain" 
                                                        type="text" 
                                                        class="form-input-monolith h-20 px-10 font-headline font-black text-3xl text-left lowercase focus:ring-indigo-600 shadow-inner border-2" 
                                                        placeholder="node_prefix"
                                                    >
                                                </div>
                                                <span class="text-4xl font-black text-slate-200 font-headline">.madaaq.com</span>
                                            </div>
                                         </div>

                                         <div class="p-10 bg-indigo-950 text-white rounded-3xl border border-white/5 flex items-start gap-8 flex-row-reverse shadow-2xl relative overflow-hidden">
                                              <div class="absolute inset-0 bg-primary/5 animate-pulse"></div>
                                              <span class="material-symbols-outlined text-primary text-[32px] mt-1 shrink-0">info</span>
                                              <p class="text-[14px] font-bold leading-relaxed text-right opacity-80">
                                                  بمجرد تأكيد البروتوكول، ستكون البوابة متاحة عالمياً عبر المسار <span class="font-black text-primary font-headline text-lg">{{ form.subdomain || 'prefix' }}.madaaq.com</span>. يرجى الانتظار لدقائق معدودة لاتمام مزامنة الـ <span class="text-primary font-headline">DNS</span>.
                                              </p>
                                         </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 6. OPERATOR AUTHORITY (Security Profile) -->
                        <div v-if="activeTab === 'security'" class="animate-in slide-in-from-top duration-500">
                             <div class="surface-card p-12 space-y-12 bg-white rounded-[2.5rem] shadow-2xl border border-outline-variant/5">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-primary uppercase tracking-[0.3em]">هوية المشغل السيادية (Admin Identity)</h3>
                                    <div class="w-1.5 h-8 bg-rose-500 rounded-full"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 flex-row-reverse">
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">هوية المشغل المعتمدة (Authorized Name)</label>
                                        <div class="relative group">
                                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors text-[28px]">shield_person</span>
                                            <input v-model="form.profile_name" type="text" class="form-input-monolith h-16 pr-16 font-black text-xl">
                                        </div>
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">رقم التواصل العاجل (Admin Phone)</label>
                                        <div class="relative group">
                                            <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary transition-colors text-[28px]">ad_units</span>
                                            <input v-model="form.profile_phone" type="text" class="form-input-monolith h-16 pr-16 font-headline font-black text-xl tracking-widest">
                                        </div>
                                    </div>
                                    <div class="md:col-span-2 pt-12 border-t border-outline-variant/10">
                                         <div class="flex items-center gap-4 justify-end mb-10">
                                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">تدوير شيفرة الدخول السيادية (Security Protocol Rotation)</h4>
                                      </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 flex-row-reverse">
                                             <div class="space-y-4 text-right">
                                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">الشيفرة الجديدة (New Secret)</label>
                                                <input v-model="form.profile_password" type="password" class="form-input-monolith h-16 font-headline tracking-[0.8em] text-xl">
                                            </div>
                                            <div class="space-y-4 text-right">
                                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">تأكيد الشيفرة الجديدة</label>
                                                <input v-model="form.profile_password_confirmation" type="password" class="form-input-monolith h-16 font-headline tracking-[0.8em] text-xl">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- GLOBAL SYNCHRONIZATION BAR (The Final Commit) -->
                        <div class="surface-card p-12 bg-slate-950 border-none relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-12 flex-row-reverse shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] rounded-[2.5rem] mt-24">
                            <div class="absolute inset-0 bg-primary/10 animate-pulse pointer-events-none"></div>
                            <div class="relative z-10 flex items-center gap-10 flex-row-reverse text-right">
                                <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center text-primary text-4xl border border-white/5 shadow-inner rotate-3 transition-transform group-hover:rotate-0">
                                    <span class="material-symbols-outlined text-[40px] font-black" style="font-variation-settings: 'FILL' 1">cloud_sync</span>
                                </div>
                                <div>
                                     <h4 class="text-2xl font-black text-white uppercase tracking-tight leading-none mb-3">حفظ الإعدادات الإستراتيجية (Commit)</h4>
                                     <p class="text-[11px] font-black text-white/30 uppercase tracking-[0.3em] leading-none">مزامنة كافة التغييرات السيادية مع قاعدة البيانات والبروتوكولات النشطة.</p>
                                </div>
                            </div>
                            <div class="relative z-10">
                                <button 
                                    type="submit"
                                    class="px-16 py-6 bg-white text-slate-950 font-black text-[12px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl hover:bg-primary hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center gap-5 group border border-white/10"
                                    :disabled="form.processing || (activeTab === 'domain' && subdomainStatus.available === false)"
                                >
                                    <span class="material-symbols-outlined text-[24px] group-hover:rotate-180 transition-transform duration-1000">rebase</span>
                                    <span v-if="form.processing">جاري المزامنة المركزية...</span>
                                    <span v-else>اعتماد وتفعيل البروتوكولات</span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>
