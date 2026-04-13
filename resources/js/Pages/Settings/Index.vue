<script setup>
import { ref, watch } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Settings, 
    Globe, 
    CreditCard, 
    MessageSquare, 
    ShieldCheck, 
    Zap, 
    Image as ImageIcon, 
    Save, 
    Activity, 
    Smartphone, 
    Lock, 
    User,
    CheckCircle2,
    XCircle,
    Info,
    Layout,
    ExternalLink
} from 'lucide-vue-next';
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
    currency: props.settings.currency,
    secondary_currency: props.settings.secondary_currency,
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
            // Logic for success handled by redirect back
        }
    });
};

const tabs = [
    { id: 'general', icon: Layout, label: 'General' },
    { id: 'fiscal', icon: CreditCard, label: 'Fiscal' },
    { id: 'reach', icon: MessageSquare, label: 'WhatsApp' },
    { id: 'payments', icon: Zap, label: 'Gateways' },
    { id: 'domain', icon: Globe, label: 'Domain' },
    { id: 'security', icon: Lock, label: 'Profile' },
];

</script>

<template>
    <AppleLayout title="System Intelligence Hub">
        <Head title="Command Configuration" />

        <div class="max-w-[1400px] mx-auto pb-24">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">System Intelligence Hub</h1>
                    <p class="text-[var(--app-secondary)] font-medium flex items-center gap-2">
                        <Settings class="w-4 h-4" />
                        Configuring core administrative artifacts and protocol boundaries
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Navigation Tab Matrix -->
                <div class="lg:col-span-1 space-y-4">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.id"
                        @click="activeTab = tab.id"
                        class="w-full apple-card p-6 flex items-center gap-4 transition-all hover:scale-105"
                        :class="activeTab === tab.id ? 'bg-black text-white shadow-xl' : 'bg-white/50 text-[#86868b]'"
                    >
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors" :class="activeTab === tab.id ? 'bg-white/10' : 'bg-black/[0.03]'">
                            <component :is="tab.icon" class="w-5 h-5" />
                        </div>
                        <span class="font-bold text-xs uppercase tracking-[0.2em]">{{ tab.label }}</span>
                    </button>

                    <!-- Strategic Insights Card -->
                    <div class="apple-card p-8 bg-indigo-600 text-white relative overflow-hidden group mt-8">
                         <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all"></div>
                         <div class="relative z-10 flex flex-col gap-4">
                             <ShieldCheck class="w-10 h-10 text-white/40" />
                             <div>
                                 <h4 class="text-sm font-bold uppercase tracking-tight">Sync Integrity</h4>
                                 <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">Updates to these protocols impact the global infrastructure handshake.</p>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- Intelligence Workspace (Form Sections) -->
                <div class="lg:col-span-3">
                    <form @submit.prevent="submit" class="space-y-12">
                        
                        <!-- 1. GENERAL PROTOCOLS -->
                        <div v-if="activeTab === 'general'" class="animate-in slide-in-from-right duration-500">
                             <div class="apple-card p-12 space-y-12">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-black rounded-full"></div>
                                    <h3 class="text-sm font-bold tracking-tight uppercase">Base Identity Protocols</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Legacy Entity Name</label>
                                        <input v-model="form.company_name" type="text" class="apple-input h-14 font-bold text-lg">
                                    </div>
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Handshake Symbol (Logo)</label>
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-2xl bg-black/[0.03] flex items-center justify-center text-[#86868b] border border-dashed border-black/10">
                                                <ImageIcon v-if="!settings.company_logo" class="w-6 h-6" />
                                                <img v-else :src="'/storage/' + settings.company_logo" class="w-10 h-10 object-contain" />
                                            </div>
                                            <input type="file" @change="e => form.company_logo = e.target.files[0]" class="flex-1 text-[11px] font-medium text-[#86868b] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-black file:text-white hover:file:bg-black/80 transition-all" />
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Operational Currency</label>
                                        <input v-model="form.currency" type="text" class="apple-input h-14 font-bold text-lg" placeholder="ر.س">
                                    </div>
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Secondary Exchange Artifact</label>
                                        <input v-model="form.secondary_currency" type="text" class="apple-input h-14 font-bold text-lg" placeholder="$">
                                    </div>
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Extraction Velocity (Rate)</label>
                                        <input v-model="form.exchange_rate" type="number" step="0.0001" class="apple-input h-14 font-mono font-bold text-lg">
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- 2. FISCAL AUTOMATION -->
                        <div v-if="activeTab === 'fiscal'" class="animate-in slide-in-from-right duration-500">
                             <div class="apple-card p-12 space-y-12">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                                    <h3 class="text-sm font-bold tracking-tight uppercase">Fiscal Automation Protocols</h3>
                                </div>

                                <div class="space-y-10">
                                     <div class="flex items-center justify-between p-8 bg-black/[0.02] rounded-[2.5rem] border border-black/5">
                                         <div class="flex items-center gap-6">
                                             <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                                <Smartphone class="w-6 h-6" />
                                             </div>
                                             <div>
                                                 <h4 class="text-sm font-bold uppercase tracking-tight">Auto-Suspension Handshake</h4>
                                                 <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mt-1">Disconnect subscribers automatically upon credit depletion</p>
                                             </div>
                                         </div>
                                         <div class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.auto_suspend_enabled" type="checkbox" class="sr-only peer">
                                             <div class="w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500"></div>
                                         </div>
                                     </div>

                                     <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                         <div class="space-y-4">
                                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Default Grace Horizon (Days)</label>
                                            <input v-model="form.default_grace_period" type="number" class="apple-input h-14 font-bold text-lg">
                                        </div>
                                        <div class="space-y-4">
                                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Invoice Pre-Generation Lead (Days)</label>
                                            <input v-model="form.invoice_generation_days" type="number" class="apple-input h-14 font-bold text-lg">
                                        </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 3. WHATSAPP INTELLIGENCE -->
                        <div v-if="activeTab === 'reach'" class="animate-in slide-in-from-right duration-500">
                             <div class="apple-card p-12 space-y-12">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-emerald-400 rounded-full"></div>
                                    <h3 class="text-sm font-bold tracking-tight uppercase">Reach Intelligence Protocols (WhatsApp)</h3>
                                </div>

                                <div class="space-y-10">
                                     <div class="space-y-4">
                                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Extraction Method</label>
                                        <div class="flex bg-black/[0.03] p-1.5 rounded-2xl w-fit">
                                            <button type="button" @click="form.whatsapp_type = 'api'" class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="form.whatsapp_type === 'api' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">Official Meta Cloud API</button>
                                            <button type="button" @click="form.whatsapp_type = 'regular'" class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="form.whatsapp_type === 'regular' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">Legacy Number</button>
                                        </div>
                                    </div>

                                    <div v-if="form.whatsapp_type === 'api'" class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                         <div class="space-y-4 md:col-span-2">
                                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Meta Access Artifact (Token)</label>
                                            <input v-model="form.whatsapp_token" type="password" class="apple-input h-14 font-mono font-bold">
                                        </div>
                                        <div class="space-y-4">
                                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Phone Identity ID</label>
                                            <input v-model="form.whatsapp_phone_id" type="text" class="apple-input h-14 font-mono font-bold">
                                        </div>
                                        <div class="space-y-4">
                                            <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Business Handshake ID</label>
                                            <input v-model="form.whatsapp_business_id" type="text" class="apple-input h-14 font-mono font-bold">
                                        </div>
                                    </div>
                                    <div v-else class="space-y-4">
                                         <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Extraction Number</label>
                                         <input v-model="form.whatsapp_regular_number" type="text" class="apple-input h-14 font-bold text-lg" placeholder="+963...">
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- 4. PAYMENT GATEWAYS -->
                        <div v-if="activeTab === 'payments'" class="animate-in slide-in-from-right duration-500">
                             <div class="space-y-8">
                                <!-- Stripe Artifact -->
                                <div class="apple-card p-10 space-y-10">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                             <div class="w-12 h-12 rounded-2xl bg-[#635bff] text-white flex items-center justify-center italic font-black text-2xl">S</div>
                                             <h3 class="text-xs font-black uppercase tracking-widest">Global Credit Extraction (Stripe)</h3>
                                        </div>
                                        <div class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.stripe_active" type="checkbox" class="sr-only peer">
                                             <div class="w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#635bff]"></div>
                                         </div>
                                    </div>
                                    <div v-if="form.stripe_active" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                         <div class="space-y-4">
                                            <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Publishable Artifact</label>
                                            <input v-model="form.stripe_public_key" type="text" class="apple-input h-12 font-mono text-[11px]">
                                        </div>
                                        <div class="space-y-4">
                                            <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest">Secret Handshake</label>
                                            <input v-model="form.stripe_secret_key" type="password" class="apple-input h-12 font-mono text-[11px]">
                                        </div>
                                    </div>
                                </div>

                                <!-- Regional Gateways Matrix -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                     <!-- PayPal -->
                                     <div class="apple-card p-8 space-y-6">
                                         <div class="flex items-center justify-between">
                                             <p class="text-[10px] font-black uppercase tracking-widest text-[#003087]">PayPal Hub</p>
                                             <input v-model="form.paypal_active" type="checkbox" class="w-5 h-5 rounded border-[#d2d2d7] text-[#0070ba] focus:ring-[#0070ba]">
                                         </div>
                                         <div v-if="form.paypal_active" class="space-y-4">
                                             <input v-model="form.paypal_client_id" type="text" class="apple-input h-10 text-[10px] font-mono" placeholder="Client ID">
                                             <input v-model="form.paypal_secret" type="password" class="apple-input h-10 text-[10px] font-mono" placeholder="Secret Artifact">
                                         </div>
                                     </div>
                                     <!-- SyriaTel Cash -->
                                     <div class="apple-card p-8 space-y-6 border-rose-100 bg-rose-50/10">
                                         <div class="flex items-center justify-between">
                                             <p class="text-[10px] font-black uppercase tracking-widest text-rose-600">SyriaTel Cash</p>
                                             <input v-model="form.syriatel_cash_active" type="checkbox" class="w-5 h-5 rounded border-rose-200 text-rose-600 focus:ring-rose-600">
                                         </div>
                                         <div v-if="form.syriatel_cash_active" class="space-y-4">
                                             <input v-model="form.syriatel_cash_merchant_id" type="text" class="apple-input h-10 text-[10px] font-mono border-rose-100" placeholder="Merchant ID">
                                             <input v-model="form.syriatel_cash_pin" type="password" class="apple-input h-10 text-[10px] font-mono border-rose-100" placeholder="PIN Artifact">
                                         </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 5. DOMAIN GOVERNANCE -->
                        <div v-if="activeTab === 'domain'" class="animate-in slide-in-from-right duration-500">
                             <div class="apple-card p-12 space-y-12">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                                    <h3 class="text-sm font-bold tracking-tight uppercase">Domain Governance Intelligence</h3>
                                </div>

                                <div class="space-y-10">
                                     <div class="flex items-center justify-between p-8 bg-black/5 rounded-[2.5rem] border border-black/5">
                                         <div class="flex items-center gap-6">
                                             <div class="w-12 h-12 rounded-2xl bg-black text-white flex items-center justify-center">
                                                <Globe class="w-6 h-6" />
                                             </div>
                                             <div>
                                                 <h4 class="text-sm font-bold uppercase tracking-tight">Personalized Extraction Link</h4>
                                                 <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mt-1">Activate custom subdomain for subscriber portal entry</p>
                                             </div>
                                         </div>
                                         <div class="relative inline-flex items-center cursor-pointer">
                                             <input v-model="form.is_subdomain_enabled" type="checkbox" class="sr-only peer">
                                             <div class="w-14 h-8 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                                         </div>
                                     </div>

                                     <div v-if="form.is_subdomain_enabled" class="space-y-8 p-10 bg-white border border-black/5 rounded-[2.5rem] shadow-sm">
                                         <div class="space-y-4">
                                            <div class="flex items-center justify-between px-2">
                                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest">Identity Prefix</label>
                                                <div v-if="subdomainStatus.checking" class="flex items-center gap-2 text-[9px] font-bold text-indigo-500">
                                                    <Activity class="w-3 h-3 animate-spin" /> PROBING...
                                                </div>
                                                <div v-else-if="subdomainStatus.available === true" class="flex items-center gap-2 text-[9px] font-bold text-emerald-500">
                                                    <CheckCircle2 class="w-3 h-3" /> PROTOCOL AVAILABLE
                                                </div>
                                                <div v-else-if="subdomainStatus.available === false" class="flex items-center gap-2 text-[9px] font-bold text-rose-500">
                                                    <XCircle class="w-3 h-3" /> CONFLICT DETECTED
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <div class="flex-1 relative group">
                                                    <input 
                                                        v-model="form.subdomain" 
                                                        type="text" 
                                                        class="apple-input h-14 pl-6 font-bold text-lg text-right lowercase focus:ring-indigo-500" 
                                                        placeholder="vendor1"
                                                    >
                                                </div>
                                                <span class="text-xl font-bold text-[#86868b]">.madaaq.com</span>
                                            </div>
                                         </div>

                                         <div class="p-6 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-start gap-4">
                                              <Info class="w-5 h-5 text-indigo-600 mt-0.5" />
                                              <p class="text-[11px] font-medium text-indigo-900 leading-relaxed">
                                                  Once committed, your subscriber portal will be accessible via <span class="font-black text-indigo-600">{{ form.subdomain || 'prefix' }}.madaaq.com</span>. Please allow 5-10 minutes for global DNS propagation.
                                              </p>
                                         </div>
                                     </div>
                                </div>
                             </div>
                        </div>

                        <!-- 6. SECURITY / PROFILE -->
                        <div v-if="activeTab === 'security'" class="animate-in slide-in-from-right duration-500">
                             <div class="apple-card p-12 space-y-12">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
                                    <h3 class="text-sm font-bold tracking-tight uppercase">Command Authority Identity (Profile)</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Operator Identity</label>
                                        <div class="relative group">
                                            <User class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-black/20 group-focus-within:text-black transition-colors" />
                                            <input v-model="form.profile_name" type="text" class="apple-input h-14 pl-16 font-bold">
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Direct Signal (Phone)</label>
                                        <div class="relative group">
                                            <Smartphone class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-black/20 group-focus-within:text-black transition-colors" />
                                            <input v-model="form.profile_phone" type="text" class="apple-input h-14 pl-16 font-bold">
                                        </div>
                                    </div>
                                    <div class="md:col-span-2 pt-6">
                                        <div class="h-px bg-black/[0.03] w-full mb-10"></div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                             <div class="space-y-4">
                                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Secret Rotation (New Password)</label>
                                                <input v-model="form.profile_password" type="password" class="apple-input h-14 font-mono">
                                            </div>
                                            <div class="space-y-4">
                                                <label class="text-[10px] font-black text-[#86868b] uppercase tracking-widest ml-2">Secret Confirmation</label>
                                                <input v-model="form.profile_password_confirmation" type="password" class="apple-input h-14 font-mono">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- GLOBAL COMMITMENT BAR -->
                        <div class="flex flex-col md:flex-row items-center justify-between gap-10 apple-card p-12 bg-black text-white relative overflow-hidden">
                            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                            <div class="relative z-10 flex items-center gap-8 text-left">
                                <div class="w-16 h-16 bg-white/10 rounded-[1.5rem] flex items-center justify-center text-3xl">
                                    💾
                                </div>
                                <div>
                                     <h4 class="text-lg font-bold uppercase tracking-tight">Synchronization Commitment</h4>
                                     <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mt-1">
                                        Pushing updated intelligence protocols to global infrastructure nodes.
                                     </p>
                                </div>
                            </div>
                            <div class="relative z-10">
                                <button 
                                    type="submit"
                                    class="px-12 py-5 bg-white text-black font-bold text-xs uppercase tracking-[0.2em] rounded-2xl shadow-2xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center gap-3"
                                    :disabled="form.processing || (activeTab === 'domain' && subdomainStatus.available === false)"
                                >
                                    <Save class="w-5 h-5" /> 
                                    <span v-if="form.processing">Synchronizing...</span>
                                    <span v-else>Apply Protocols</span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
