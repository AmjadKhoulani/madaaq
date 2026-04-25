<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    servers: Array,
    packages: Array,
    customers: Array
});

const form = useForm({
    mode: 'new', // new or existing
    customer_id: '',
    name: '',
    phone: '',
    server_id: '',
    package_id: '',
    username: '',
    password: Math.random().toString(36).slice(-6).toUpperCase(),
    expires_at: '',
});

const selectedPackage = computed(() => {
    return props.packages.find(p => p.id === form.package_id);
});

const selectedCustomer = computed(() => {
    return props.customers.find(c => c.id === form.customer_id);
});

const submit = () => {
    form.post(route('hotspot.users.store'));
};

const generatePassword = () => {
    form.password = Math.random().toString(36).slice(-6).toUpperCase();
};

</script>

<template>
    <InstitutionalLayout title="تأسيس عضو ضيف">
        <Head title="تأسيس عضو ضيف - MadaaQ" />

        <div class="max-w-4xl mx-auto pb-24 text-right px-4" dir="rtl">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-16 flex-row-reverse">
                <div class="text-right">
                    <h1 class="text-4xl font-black text-primary tracking-tight mb-2">تأسيس عضو ضيف (Guest)</h1>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-wider flex items-center gap-3 justify-end">
                        <span>تهيئة وصول الضيوف بمعايير حوكمة عالية</span>
                        <span class="material-symbols-outlined text-primary text-[20px]">person_add</span>
                    </p>
                </div>
                <Link 
                    :href="route('hotspot.users.index')" 
                    class="w-14 h-14 bg-white border border-outline-variant/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                >
                    <span class="material-symbols-outlined text-[28px] group-hover:translate-x-2 transition-transform">arrow_forward</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Customer Context -->
                <div class="surface-card p-12 rounded-2xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-4 mb-10 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-widest">سياق هوية الضيف (Identity Matrix)</h3>
                        <span class="material-symbols-outlined text-secondary">fingerprint</span>
                    </div>

                    <div class="space-y-10">
                        <div class="flex bg-surface-container-low p-2 rounded-2xl w-fit mr-auto flex-row-reverse shadow-inner">
                             <button type="button" @click="form.mode = 'new'" class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="form.mode === 'new' ? 'bg-white text-primary shadow-lg' : 'text-slate-400 hover:text-slate-600'">مشترك جديد</button>
                             <button type="button" @click="form.mode = 'existing'" class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="form.mode === 'existing' ? 'bg-white text-primary shadow-lg' : 'text-slate-400 hover:text-slate-600'">عميل مسجل</button>
                        </div>

                        <div v-if="form.mode === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-10 animate-in slide-in-from-top duration-500">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">الهوية الكاملة (Legal Name)</label>
                                <div class="relative group">
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[24px]">person</span>
                                    <input v-model="form.name" type="text" class="form-input-monolith h-16 pr-14 font-black text-lg" placeholder="مثلاً: أمجد الخولاني" required>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">رقم التواصل الفني</label>
                                <div class="relative group">
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[24px]">contact_phone</span>
                                    <input v-model="form.phone" type="text" class="form-input-monolith h-16 pr-14 font-headline font-black text-lg" placeholder="+963..." required>
                                </div>
                            </div>
                        </div>

                        <div v-else class="space-y-4 animate-in slide-in-from-top duration-500">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">قاعدة بيانات العملاء المركزي (CRM)</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[24px]">manage_accounts</span>
                                <select v-model="form.customer_id" class="form-input-monolith h-16 pr-14 font-black uppercase tracking-tight text-sm" required>
                                    <option value="" disabled>اختر العميل المعتمد من السجل...</option>
                                    <option v-for="c in customers" :key="c.id" :value="c.id">{{ c.name }} ({{ c.phone }})</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Infrastructure Commitment -->
                <div class="surface-card p-12 rounded-2xl border border-outline-variant/5 shadow-sm border-r-8 border-primary">
                    <div class="flex items-center gap-4 mb-10 justify-end">
                        <h3 class="text-xs font-black text-primary uppercase tracking-widest">بروتوكول الربط (Infrastructure Allocation)</h3>
                        <span class="material-symbols-outlined text-secondary">GitBranch</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                         <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">عنقود النشر (Target Gateway)</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-secondary text-[24px]">dns</span>
                                <select v-model="form.server_id" class="form-input-monolith h-16 pr-14 font-black uppercase tracking-tight text-sm" required>
                                    <option value="" disabled>اختر عقدة التزويد...</option>
                                    <option v-for="s in servers" :key="s.id" :value="s.id">{{ s.name }} ({{ s.ip }})</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">فئة السرعة (Service Profile)</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-amber-500 text-[24px]">bolt</span>
                                <select v-model="form.package_id" class="form-input-monolith h-16 pr-14 font-black uppercase tracking-tight text-sm" required>
                                    <option value="" disabled>اختر فئة الاشتراك المختارة...</option>
                                    <option v-for="p in packages" :key="p.id" :value="p.id">{{ p.name }} ({{ p.price }} ل.س)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Credential Protocol -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Identity Credentials -->
                    <div class="surface-card p-12 rounded-2xl space-y-10 border border-outline-variant/5 shadow-sm">
                        <div class="flex items-center gap-4 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-widest">بيانات الوصول (Access Matrix)</h3>
                            <span class="material-symbols-outlined text-emerald-500">lock_open</span>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">مُعرف الوصول (Username)</label>
                                <div class="relative">
                                    <input v-model="form.username" type="text" class="form-input-monolith h-16 pr-14 font-headline font-black text-xl tracking-widest" placeholder="HS-USER" required>
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 text-[24px]">badge</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2 flex items-center justify-between flex-row-reverse">
                                    <span>كلمة المرور (Secret Code)</span>
                                    <button type="button" @click="generatePassword" class="text-[10px] font-black text-secondary hover:text-primary transition-colors flex items-center gap-2">
                                        تجديد التوليد <span class="material-symbols-outlined text-[16px]">refresh</span>
                                    </button>
                                </label>
                                <div class="relative">
                                    <input v-model="form.password" type="text" class="form-input-monolith h-16 pr-14 font-headline font-black text-xl tracking-widest" required>
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 text-[24px]">password</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Temporal Horizon -->
                    <div class="surface-card p-12 rounded-2xl space-y-10 border border-outline-variant/5 shadow-sm">
                         <div class="flex items-center gap-4 justify-end">
                            <h3 class="text-xs font-black text-primary uppercase tracking-widest">أفق الصلاحية (Temporal Limit)</h3>
                            <span class="material-symbols-outlined text-rose-500">schedule</span>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">تاريخ انتهاء الصلاحية</label>
                                <div class="relative">
                                    <input v-model="form.expires_at" type="date" class="form-input-monolith h-16 pr-14 font-headline font-black text-lg">
                                    <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 text-[24px]">event_busy</span>
                                </div>
                                <p class="text-[9px] font-black text-rose-500/60 uppercase tracking-widest mr-2 italic">البقاء فارغاً يعني صلاحية دائمة (Permanent)</p>
                            </div>

                            <div v-if="selectedPackage" class="p-8 bg-slate-950 text-white rounded-2xl relative overflow-hidden group/pod shadow-2xl border border-white/5">
                                <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/20 rounded-full blur-3xl group-hover/pod:scale-125 transition-transform duration-1000"></div>
                                <div class="relative z-10 flex items-center gap-6 flex-row-reverse">
                                    <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center border border-white/10 shadow-inner">
                                        <span class="material-symbols-outlined text-amber-500 text-[32px]">speed</span>
                                    </div>
                                    <div class="text-right">
                                         <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1.5 leading-none">تصفية السرعة</p>
                                         <p class="text-xl font-black font-headline tracking-tighter">{{ selectedPackage.speed_down }} / {{ selectedPackage.speed_up }} <span class="text-xs opacity-50 uppercase mr-1">Mbps</span></p>
                                         <p class="text-[10px] opacity-40 mt-1 font-black leading-none italic">تعرفة الباقة: {{ selectedPackage.price }} ل.س</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Governance Commitment -->
                <div class="surface-card p-12 bg-slate-950 rounded-2xl shadow-2xl overflow-hidden relative group/final border border-white/5">
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl group-hover/final:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12 flex-row-reverse">
                        <div class="flex items-center gap-10 flex-1 justify-end text-right">
                            <div>
                                 <h4 class="text-3xl font-black text-white tracking-tight mb-3">تثبيت بروتوكول الضيف</h4>
                                 <p class="text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] leading-relaxed italic">
                                    سيتم إرسال إشارة التنشيط فوراً إلى عقدة التزويد عبر قناة API المشفرة.
                                 </p>
                            </div>
                            <div class="w-20 h-20 bg-white/10 rounded-[2rem] flex items-center justify-center border border-white/10 shadow-inner group-hover/final:scale-110 transition-transform duration-700">
                                <span class="material-symbols-outlined text-amber-500 text-[40px]">Shield_update_good</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 relative z-10 flex-row-reverse">
                             <button 
                                type="submit" 
                                class="px-14 py-5 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded-xl shadow-2xl shadow-primary/30 hover:bg-emerald-600 transition-all hover:scale-105 active:scale-95 flex items-center gap-4 border border-white/10"
                                :disabled="form.processing"
                            >
                                <span class="material-symbols-outlined text-[24px]">verified</span>
                                تفعيل وصول الضيف
                            </button>
                            <Link 
                                :href="route('hotspot.users.index')" 
                                class="px-10 py-5 bg-white/5 text-slate-500 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-white/10 hover:text-white transition-all active:scale-95 border border-white/5"
                            >
                                إلغاء العملية
                            </Link>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>

<style scoped>
.font-headline {
    font-family: 'Manrope', sans-serif;
}
</style>

