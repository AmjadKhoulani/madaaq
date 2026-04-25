<script setup>
import { ref, watch } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import axios from 'axios';
import { debounce } from 'lodash';
import { 
    Settings, 
    Zap, 
    MessageSquare, 
    CreditCard, 
    Globe, 
    ShieldCheck, 
    CloudSync, 
    Image as ImageIcon,
    Currency,
    TrendingUp,
    ShieldAlert,
    Phone,
    Mail,
    Lock,
    UserCircle,
    CheckCircle2,
    AlertCircle,
    RefreshCcw,
    Database,
    Shield
} from 'lucide-vue-next';

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
    { id: 'general', icon: Settings, label: 'البروتوكولات العامة' },
    { id: 'automation', icon: Zap, label: 'حوكمة الأتمتة' },
    { id: 'reach', icon: MessageSquare, label: 'خوارزميات التواصل' },
    { id: 'payments', icon: CreditCard, label: 'بوابات السيولة' },
    { id: 'domain', icon: Globe, label: 'بنية النطاقات' },
    { id: 'Shield', icon: ShieldCheck, label: 'أمن المشغل' },
];

</script>

<template>
    <InstitutionalLayout title="مركز حوكمة المنصة">
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-vendor rounded-full"></span>
                        <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">HQ Governance Center</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">مركز <span class="text-vendor">الحوكمة</span> والإعدادات السيادية</h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">ضبط معايير التشغيل المركزية، الهوية المؤسساتية، وبنيوية بوابات الربط</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Navigation Tab Rail -->
                <div class="lg:col-span-1 space-y-4">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        class="w-full glass-card p-6 flex items-center gap-5 transition-all hover:translate-x-2 group"
                        :class="activeTab === tab.id ? 'bg-slate-900 text-white shadow-radiant border-slate-900' : 'bg-white/40 text-slate-400 hover:text-vendor hover:bg-white/60'"
                    >
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300" :class="activeTab === tab.id ? 'bg-white/10 rotate-12 text-vendor' : 'bg-white/50 shadow-inner border border-white/60'">
                            <component :is="tab.icon" class="w-6 h-6 stroke-[2.5]" />
                        </div>
                        <span class="font-black text-[12px] uppercase tracking-[0.2em] font-inter">{{ tab.label }}</span>
                    </button>

                    <!-- System Integrity Seal -->
                    <div class="glass-card p-10 bg-slate-900 text-white relative overflow-hidden group mt-12 border-none">
                         <div class="absolute -top-16 -right-16 w-64 h-64 bg-vendor/20 rounded-full blur-3xl group-hover:scale-125 transition-all duration-1000"></div>
                         <div class="relative z-10 flex flex-col gap-8 text-right">
                             <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center shadow-inner border border-white/5 text-vendor/40">
                                <Database class="w-10 h-10" />
                             </div>
                             <div>
                                 <h4 class="text-base font-black uppercase tracking-tight font-inter">نزاهة الهيكلية الرقمية</h4>
                                 <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mt-3 leading-relaxed italic">تعديل هذه البروتوكولات يؤثر بشكل تكتيكي مباشر على تدفق البيانات واستدامة الخدمات.</p>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- Intelligence Configuration Workspace -->
                <div class="lg:col-span-3">
                    <form @submit.prevent="submit" class="space-y-12">
                        
                        <!-- 1. GENERAL IDENTITIES -->
                        <div v-if="activeTab === 'general'" class="animate-in fade-in slide-in-from-top-4 duration-500">
                             <div class="glass-card p-12 space-y-12 bg-white/40 border-white/60">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] font-inter italic">بروتوكول الهوية الأساسي (Core Identity)</h3>
                                    <div class="w-1.5 h-8 bg-vendor rounded-full"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">مُعرف المنظومة (Network Label)</label>
                                        <input v-model="form.company_name" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black text-xl focus:ring-4 focus:ring-vendor/5 transition-all">
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">مخطط الشعار المؤسساتي (Logo Artifact)</label>
                                        <div class="flex items-center gap-6 flex-row-reverse">
                                            <div class="w-20 h-20 rounded-2xl bg-white/50 flex items-center justify-center text-slate-300 border border-white/60 shadow-inner overflow-hidden relative group/logo">
                                                <div class="absolute inset-0 bg-vendor/5 opacity-0 group-hover/logo:opacity-100 transition-opacity"></div>
                                                <ImageIcon v-if="!settings.company_logo" class="w-8 h-8" />
                                                <img v-else :src="'/storage/' + settings.company_logo" class="w-full h-full object-contain p-2" />
                                            </div>
                                            <input type="file" @change="e => form.company_logo = e.target.files[0]" class="flex-1 text-[11px] font-black text-slate-400 file:ml-6 file:py-4 file:px-10 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-vendor transition-all cursor-pointer shadow-sm" />
                                        </div>
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">العملة السيادية للمنظومة</label>
                                        <input v-model="form.currency" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black text-xl focus:ring-4 focus:ring-vendor/5 transition-all" placeholder="ل.س">
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">عملة التقاص الثانوية (Global)</label>
                                        <input v-model="form.secondary_currency" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black text-xl focus:ring-4 focus:ring-vendor/5 transition-all" placeholder="$">
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">معامل التحويل اللحظي (Exchange Rate)</label>
                                        <div class="relative group">
                                            <TrendingUp class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors w-6 h-6 stroke-[2.5]" />
                                            <input v-model="form.exchange_rate" type="number" step="0.0001" class="w-full bg-white/50 border-white/60 rounded-2xl pr-14 h-16 font-black text-2xl tracking-tighter focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- 2. AUTOMATION DEPLOYMENT -->
                        <div v-if="activeTab === 'automation'" class="animate-in fade-in slide-in-from-top-4 duration-500">
                             <div class="glass-card p-12 space-y-12 bg-white/40 border-white/60">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] font-inter italic">بروتوكول الأتمتة والتحصيل (Automation Grid)</h3>
                                    <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
                                </div>

                                <div class="space-y-12">
                                     <div class="flex items-center justify-between p-12 bg-white/60 rounded-[2rem] border border-white/60 flex-row-reverse shadow-inner group">
                                         <div class="flex items-center gap-8 flex-row-reverse">
                                             <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center border border-emerald-500/20 shadow-lg group-hover:scale-110 transition-transform">
                                                <Zap class="w-8 h-8 stroke-[2.5]" />
                                             </div>
                                             <div class="text-right">
                                                 <h4 class="text-lg font-black uppercase tracking-tight italic font-inter">بروتوكول الإيقاف التلقائي (Auto-Suspend)</h4>
                                                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-2 leading-none italic opacity-60">فصل المشتركين برمجياً فور انتهاء الجلسة الزمنية</p>
                                             </div>
                                         </div>
                                         <label class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.auto_suspend_enabled" type="checkbox" class="sr-only peer">
                                             <div class="w-16 h-10 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-8 after:w-8 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                                         </label>
                                     </div>

                                     <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                         <div class="space-y-4 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">فترة السماح التكتيكية (Grace Period)</label>
                                            <div class="relative">
                                                <input v-model="form.default_grace_period" type="number" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black text-2xl pr-8 focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase tracking-widest font-inter">HRS/DAYS</span>
                                            </div>
                                        </div>
                                        <div class="space-y-4 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">دورة إصدار الفواتير المسبقة (يوم)</label>
                                            <div class="relative">
                                                <input v-model="form.invoice_generation_days" type="number" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black text-2xl pr-8 focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-300 uppercase tracking-widest font-inter">DAYS</span>
                                            </div>
                                        </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 3. REACH INTELLIGENCE -->
                        <div v-if="activeTab === 'reach'" class="animate-in fade-in slide-in-from-top-4 duration-500">
                             <div class="glass-card p-12 space-y-12 bg-white/40 border-white/60">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] font-inter italic">خوارزميات التواصل الموجه (Messaging Protocols)</h3>
                                    <div class="w-1.5 h-8 bg-[#25d366] rounded-full"></div>
                                </div>

                                <div class="space-y-12 text-right">
                                     <div class="space-y-5">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 text-right block italic">طريقة الربط البرمجي (Integration Protocol)</label>
                                        <div class="flex bg-white/60 p-2 rounded-2xl border border-white/60 w-fit mr-auto shadow-inner">
                                            <button type="button" @click="form.whatsapp_type = 'api'" class="px-10 py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] transition-all font-inter" :class="form.whatsapp_type === 'api' ? 'bg-slate-900 text-white shadow-xl' : 'text-slate-400 hover:text-vendor'">Meta Cloud API (Premium)</button>
                                            <button type="button" @click="form.whatsapp_type = 'regular'" class="px-10 py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] transition-all font-inter" :class="form.whatsapp_type === 'regular' ? 'bg-slate-900 text-white shadow-xl' : 'text-slate-400 hover:text-vendor'">Direct Mobile Number</button>
                                        </div>
                                    </div>

                                    <div v-if="form.whatsapp_type === 'api'" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                         <div class="space-y-4 md:col-span-2 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">مفتاح الوصول السيادي (Meta Cloud Token)</label>
                                            <input v-model="form.whatsapp_token" type="password" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black tracking-widest text-lg focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                        </div>
                                        <div class="space-y-4 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">معرف بوابة الإرسال (Phone Node ID)</label>
                                            <input v-model="form.whatsapp_phone_id" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black text-xl focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                        </div>
                                        <div class="space-y-4 text-right">
                                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">معرف الأعمال المعتمد (Business ID)</label>
                                            <input v-model="form.whatsapp_business_id" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black text-xl focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                        </div>
                                    </div>
                                    <div v-else class="space-y-4 text-right max-w-md mr-auto">
                                         <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">رقم التواصل المعتمد (Authorized Number)</label>
                                         <div class="relative group">
                                             <Phone class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-[#25d366] transition-colors w-6 h-6 stroke-[2.5]" />
                                             <input v-model="form.whatsapp_regular_number" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl pr-14 h-16 font-black text-2xl tracking-widest focus:ring-4 focus:ring-vendor/5 transition-all font-inter" placeholder="+963 --- --- ---">
                                         </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- 4. GATEWAY PROTOCOLS -->
                        <div v-if="activeTab === 'payments'" class="animate-in fade-in slide-in-from-top-4 duration-500">
                             <div class="space-y-10">
                                <div class="glass-card p-12 space-y-12 bg-white/40 border-white/60">
                                    <div class="flex items-center justify-between flex-row-reverse group">
                                        <div class="flex items-center gap-8 flex-row-reverse text-right">
                                             <div class="w-16 h-16 rounded-2xl bg-[#635bff] text-white flex items-center justify-center italic font-black text-4xl shadow-2xl group-hover:rotate-6 transition-transform">S</div>
                                             <div>
                                                 <h3 class="text-lg font-black uppercase tracking-tight font-inter italic">بوابة Stripe العالمية للخصم</h3>
                                                 <p class="text-[11px] font-black text-slate-400 uppercase mt-2 tracking-widest opacity-60 italic leading-none">بروتوكول تحصيل البطاقات الائتمانية والخصم المباشر العالمي</p>
                                             </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.stripe_active" type="checkbox" class="sr-only peer">
                                             <div class="w-16 h-10 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-8 after:w-8 after:transition-all peer-checked:bg-[#635bff] shadow-inner"></div>
                                         </label>
                                    </div>
                                    <div v-if="form.stripe_active" class="grid grid-cols-1 md:grid-cols-2 gap-10 animate-in fade-in zoom-in-95 duration-300">
                                         <div class="space-y-4 text-right">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2 font-inter">المفتاح العلني للمنظومة (Publishable Key)</label>
                                            <input v-model="form.stripe_public_key" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-14 font-black text-[13px] tracking-tight focus:ring-4 focus:ring-[#635bff]/5 transition-all font-inter">
                                        </div>
                                        <div class="space-y-4 text-right">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2 font-inter">المفتاح السري المشفر (Secret Key Artifact)</label>
                                            <input v-model="form.stripe_secret_key" type="password" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-14 font-black text-[13px] tracking-widest focus:ring-4 focus:ring-[#635bff]/5 transition-all font-inter">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                     <div class="glass-card p-10 space-y-10 bg-white/40 border-[#003087]/20">
                                         <div class="flex items-center justify-between flex-row-reverse">
                                             <div class="flex items-center gap-4 flex-row-reverse text-right">
                                                 <div class="w-10 h-10 rounded-lg bg-[#0070ba] text-white flex items-center justify-center italic font-black text-xl">P</div>
                                                 <p class="text-[11px] font-black uppercase tracking-widest text-[#003087] font-inter italic leading-none">PayPal GitBranch Integration</p>
                                             </div>
                                             <input v-model="form.paypal_active" type="checkbox" class="w-6 h-6 rounded-lg border-white/60 bg-white text-[#0070ba] focus:ring-[#0070ba] shadow-sm cursor-pointer">
                                         </div>
                                         <div v-if="form.paypal_active" class="space-y-6 animate-in slide-in-from-top-2 duration-300">
                                             <input v-model="form.paypal_client_id" type="text" class="w-full bg-white/50 border-white/60 rounded-xl px-4 h-14 text-[12px] font-black font-inter" placeholder="Client Protocol ID">
                                             <input v-model="form.paypal_secret" type="password" class="w-full bg-white/50 border-white/60 rounded-xl px-4 h-14 text-[12px] font-black font-inter" placeholder="Secure Secret Token">
                                         </div>
                                     </div>
                                     <div class="glass-card p-10 space-y-10 bg-white/40 border-rose-500/20">
                                         <div class="flex items-center justify-between flex-row-reverse">
                                             <div class="flex items-center gap-4 flex-row-reverse text-right">
                                                 <div class="w-10 h-10 rounded-lg bg-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-500/20"><CreditCard class="w-5 h-5 stroke-[2.5]" /></div>
                                                 <p class="text-[11px] font-black uppercase tracking-widest text-rose-600 italic leading-none">بوابة سيريتل كاش (SyriaTel)</p>
                                             </div>
                                             <input v-model="form.syriatel_cash_active" type="checkbox" class="w-6 h-6 rounded-lg border-white/60 bg-white text-rose-600 focus:ring-rose-600 shadow-sm cursor-pointer">
                                         </div>
                                         <div v-if="form.syriatel_cash_active" class="space-y-6 animate-in slide-in-from-top-2 duration-300">
                                             <input v-model="form.syriatel_cash_merchant_id" type="text" class="w-full bg-white/50 border-white/60 rounded-xl px-4 h-14 text-[12px] font-black font-inter" placeholder="Merchant Identity Code">
                                             <input v-model="form.syriatel_cash_pin" type="password" class="w-full bg-white/50 border-white/60 rounded-xl px-4 h-14 text-[12px] font-black font-inter" placeholder="Wallet PIN Artifact">
                                         </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 5. DOMAIN ARCHITECTURE -->
                        <div v-if="activeTab === 'domain'" class="animate-in fade-in slide-in-from-top-4 duration-500">
                             <div class="glass-card p-12 space-y-12 bg-white/40 border-white/60">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] font-inter italic">هيكلية النطاقات المركزية (Domain Logic)</h3>
                                    <div class="w-1.5 h-8 bg-indigo-600 rounded-full"></div>
                                </div>

                                <div class="space-y-12">
                                     <div class="flex items-center justify-between p-12 bg-white/60 rounded-[2rem] border border-white/60 flex-row-reverse shadow-inner group">
                                         <div class="flex items-center gap-8 flex-row-reverse text-right">
                                             <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-2xl border border-white/10 group-hover:scale-110 transition-transform">
                                                <Globe class="w-8 h-8 stroke-[2.5]" />
                                             </div>
                                             <div>
                                                 <h4 class="text-lg font-black uppercase tracking-tight italic font-inter">اسم النطاق الفرعي السيادي (Subdomain)</h4>
                                                 <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-2 leading-none italic opacity-60">تخصيص مسار فرعي موحد للوصول لبوابة الخدمات</p>
                                             </div>
                                         </div>
                                         <label class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.is_subdomain_enabled" type="checkbox" class="sr-only peer">
                                             <div class="w-16 h-10 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-8 after:w-8 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                                         </label>
                                     </div>

                                     <div v-if="form.is_subdomain_enabled" class="space-y-12 p-12 bg-white/60 border border-white/60 rounded-[2rem] shadow-radiant animate-in fade-in zoom-in-95 duration-500">
                                         <div class="space-y-6 text-right">
                                            <div class="flex items-center justify-between px-3 flex-row-reverse">
                                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest leading-none italic">بادئة الرابط اللحظية (Prefix)</label>
                                                <div v-if="subdomainStatus.checking" class="flex items-center gap-3 text-[10px] font-black text-indigo-500 uppercase">
                                                    <RefreshCcw class="w-4 h-4 animate-spin" /> جاري فحص الوفرة...
                                                </div>
                                                <div v-else-if="subdomainStatus.available === true" class="flex items-center gap-3 text-[10px] font-black text-emerald-500 uppercase">
                                                    <CheckCircle2 class="w-4 h-4" /> البروتوكول متاح للاعتماد
                                                </div>
                                                <div v-else-if="subdomainStatus.available === false" class="flex items-center gap-3 text-[10px] font-black text-rose-500 uppercase">
                                                    <AlertCircle class="w-4 h-4" /> البادئة محجوزة مسبقاً
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-8 flex-row-reverse">
                                                <div class="flex-1 relative group">
                                                    <input 
                                                        v-model="form.subdomain" 
                                                        type="text" 
                                                        class="w-full bg-white/50 border-white/60 rounded-2xl px-10 h-20 font-black text-3xl text-left lowercase focus:ring-4 focus:ring-indigo-600/5 transition-all font-inter" 
                                                        placeholder="node_prefix"
                                                    >
                                                </div>
                                                <span class="text-4xl font-black text-slate-200 font-inter italic tracking-tighter">.madaaq.com</span>
                                            </div>
                                         </div>

                                         <div class="p-10 bg-slate-900 text-white rounded-[2rem] border border-white/10 flex items-start gap-8 flex-row-reverse shadow-2xl relative overflow-hidden group">
                                              <div class="absolute inset-0 bg-vendor/5 animate-pulse opacity-20"></div>
                                              <AlertCircle class="text-vendor w-10 h-10 mt-1 shrink-0 group-hover:rotate-12 transition-transform" />
                                              <p class="text-[14px] font-bold leading-relaxed text-right opacity-80 italic">
                                                  بمجرد تأكيد البروتوكول، ستكون البوابة متاحة عالمياً عبر المسار <span class="font-black text-vendor font-inter text-lg">{{ form.subdomain || 'prefix' }}.madaaq.com</span>. يرجى الانتظار لدقائق معدودة لاتمام مزامنة الـ <span class="text-vendor font-inter">DNS</span>.
                                              </p>
                                         </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 6. OPERATOR AUTHORITY -->
                        <div v-if="activeTab === 'Shield'" class="animate-in fade-in slide-in-from-top-4 duration-500">
                             <div class="glass-card p-12 space-y-12 bg-white/40 border-white/60">
                                <div class="flex items-center gap-4 justify-end">
                                    <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] font-inter italic">هوية المشغل السيادية (Admin Identity)</h3>
                                    <div class="w-1.5 h-8 bg-rose-500 rounded-full"></div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">هوية المشغل المعتمدة (Authorized Name)</label>
                                        <div class="relative group">
                                            <UserCircle class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors w-8 h-8 stroke-[2.5]" />
                                            <input v-model="form.profile_name" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl pr-16 h-16 font-black text-xl focus:ring-4 focus:ring-vendor/5 transition-all">
                                        </div>
                                    </div>
                                    <div class="space-y-4 text-right">
                                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">رقم التواصل العاجل (Admin Phone)</label>
                                        <div class="relative group">
                                            <Phone class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors w-8 h-8 stroke-[2.5]" />
                                            <input v-model="form.profile_phone" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl pr-16 h-16 font-black text-xl tracking-widest focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                        </div>
                                    </div>
                                    <div class="md:col-span-2 pt-12 border-t border-white/20">
                                         <div class="flex items-center gap-4 justify-end mb-10">
                                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">تدوير شيفرة الدخول السيادية (Shield Protocol Rotation)</h4>
                                            <Shield class="w-4 h-4 text-rose-500" />
                                       </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                             <div class="space-y-4 text-right">
                                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">الشيفرة الجديدة (New Secret)</label>
                                                <input v-model="form.profile_password" type="password" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black tracking-[0.8em] text-xl focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                            </div>
                                            <div class="space-y-4 text-right">
                                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">تأكيد الشيفرة الجديدة</label>
                                                <input v-model="form.profile_password_confirmation" type="password" class="w-full bg-white/50 border-white/60 rounded-2xl px-6 h-16 font-black tracking-[0.8em] text-xl focus:ring-4 focus:ring-vendor/5 transition-all font-inter">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- GLOBAL SYNCHRONIZATION BAR -->
                        <div class="glass-card p-12 bg-slate-900 border-none relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-12 shadow-radiant rounded-[3rem] mt-24 group/bar">
                            <div class="absolute inset-0 bg-vendor/10 animate-pulse pointer-events-none opacity-20"></div>
                            <div class="relative z-10 flex items-center gap-10 text-right w-full lg:w-auto">
                                <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center text-vendor text-4xl border border-white/5 shadow-inner rotate-3 transition-transform group-hover/bar:rotate-0">
                                    <CloudSync class="w-12 h-12 stroke-[2.5]" />
                                </div>
                                <div>
                                     <h4 class="text-2xl font-black text-white uppercase tracking-tight leading-none mb-3 italic font-inter">حفظ الإعدادات الإستراتيجية (Commit)</h4>
                                     <p class="text-[11px] font-black text-white/30 uppercase tracking-[0.3em] leading-none italic">مزامنة كافة التغييرات السيادية مع قاعدة البيانات والبروتوكولات النشطة.</p>
                                </div>
                            </div>
                            <div class="relative z-10 w-full lg:w-auto">
                                <button 
                                    type="submit"
                                    class="w-full lg:w-auto px-16 py-6 bg-white text-slate-900 font-black text-[12px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl hover:bg-vendor hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-5 group border border-white/10 font-inter"
                                    :disabled="form.processing || (activeTab === 'domain' && subdomainStatus.available === false)"
                                >
                                    <RefreshCcw class="w-6 h-6 group-hover:rotate-180 transition-transform duration-1000" />
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
.glass-card {
    @apply border border-white/40 shadow-glass rounded-[2.5rem] transition-all duration-500;
}
.glass-card:hover {
    @apply border-white/60 shadow-radiant -translate-y-1;
}
</style>

