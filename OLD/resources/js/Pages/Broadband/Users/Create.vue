<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { watch, computed, ref } from 'vue';

import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    routers: Array,
    towers: Array,
    packages: Array,
    customers: Array
});

const form = useForm({
    mode: 'new',
    customer_id: '',
    name: '',
    phone: '',
    tower_id: '',
    ssid_id: '',
    router_id: '',
    package_id: '',
    username: '',
    password: '',
    ip: '',
    custom_duration_days: '',
    custom_data_limit_mb: '',
    custom_price: '',
    expires_at: '',
});

const selectedTower = computed(() => {
    return props.towers.find(t => t.id === form.tower_id);
});

const availableSsids = computed(() => {
    return selectedTower.value?.ssids || [];
});

const selectedPackage = computed(() => {
    return props.packages.find(p => p.id === form.package_id);
});

watch(() => form.tower_id, () => {
    form.ssid_id = '';
    form.router_id = '';
});

watch(() => form.ssid_id, (newSsidId) => {
    const ssid = availableSsids.value.find(s => s.id === newSsidId);
    if (ssid?.router) {
        form.router_id = ssid.router.id;
    }
});

const submit = () => {
    form.post(route('broadband.users.store'));
};

const generateSecret = () => {
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let pass = '';
    for (let i = 0; i < 8; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    form.password = pass;
    if (!form.username && form.name) {
         form.username = form.name.toLowerCase().replace(/\s+/g, '.') + '.' + Math.floor(Math.random() * 100);
    }
};

</script>

<template>
    <InstitutionalLayout title="تفعيل اشتراك جديد">
        <Head title="إعداد اشتراك برودباند" />

        <div class="max-w-5xl mx-auto pb-24 text-right" dir="rtl">
            <!-- Navigation Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-1">تفعيل حساب PPPoE</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">إنشاء حساب مشترك جديد في منظومة البرودباند</p>
                    </div>
                    <Link 
                        :href="route('broadband.users.index')" 
                        class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                    >
                        <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </Link>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Subscriber Identity -->
                <div class="surface-card p-10 rounded-xl relative overflow-hidden">
                    <div class="flex items-center justify-between mb-10 flex-row-reverse">
                        <div class="flex items-center gap-3">
                            <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em]">بيانات المشترك</h3>
                            <span class="material-symbols-outlined text-secondary">person_search</span>
                        </div>
                        <div class="flex bg-surface-container-low p-1.5 rounded-xl border border-outline-variant/5">
                             <button type="button" @click="form.mode = 'new'" class="px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all" :class="form.mode === 'new' ? 'bg-primary text-white shadow-lg' : 'text-slate-400 hover:text-primary'">مشترك جديد</button>
                             <button type="button" @click="form.mode = 'existing'" class="px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all" :class="form.mode === 'existing' ? 'bg-primary text-white shadow-lg' : 'text-slate-400 hover:text-primary'">مشترك مسجل</button>
                        </div>
                    </div>

                    <div v-if="form.mode === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">الاسم الكامل للمشترك</label>
                            <input v-model="form.name" type="text" class="form-input-monolith font-bold" placeholder="مثال: أحمد المحمد">
                        </div>
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">رقم التواصل</label>
                            <input v-model="form.phone" type="text" class="form-input-monolith font-bold" placeholder="09xxxxxxx">
                        </div>
                    </div>

                    <div v-else class="space-y-4 text-right animate-in fade-in duration-500">
                        <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">اختيار مشترك من السجل</label>
                        <select v-model="form.customer_id" class="form-input-monolith h-14 font-bold text-lg">
                            <option value="">اختر من سجل المشتركين المحلي...</option>
                            <option v-for="c in customers" :key="c.id" :value="c.id">{{ c.name }} ({{ c.phone }})</option>
                        </select>
                    </div>
                </div>

                <!-- 2. Topology & Performance -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Network Topology -->
                    <div class="surface-card p-10 space-y-8 rounded-xl">
                         <div class="flex items-center gap-3 justify-end">
                            <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em]">بيانات الربط الفني</h3>
                            <span class="material-symbols-outlined text-emerald-500">cell_tower</span>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-4 text-right">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">موقع البرج / المركز الداعم</label>
                                <select v-model="form.tower_id" class="form-input-monolith h-14 font-bold">
                                    <option value="">اختر موقع الربط...</option>
                                    <option v-for="t in towers" :key="t.id" :value="t.id">{{ t.name }} ({{ t.city }})</option>
                                </select>
                            </div>
                            <div class="space-y-4 text-right">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">واجهة البث (SSID)</label>
                                <select v-model="form.ssid_id" class="form-input-monolith h-14 font-bold" :disabled="!form.tower_id">
                                    <option value="">اختيار واجهة الاتصال (اختياري)...</option>
                                    <option v-for="s in availableSsids" :key="s.id" :value="s.id">{{ s.ssid_name }} ({{ s.frequency }})</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Tier -->
                    <div class="surface-card p-10 space-y-8 rounded-xl">
                         <div class="flex items-center gap-3 justify-end">
                            <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em]">باقة وبراعة الخدمة</h3>
                            <span class="material-symbols-outlined text-amber-500">speed</span>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-4 text-right">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">اختيار باقة الاشتراك</label>
                                <select v-model="form.package_id" class="form-input-monolith h-14 font-bold">
                                    <option value="">اختيار فئة السرعة المتاحة...</option>
                                    <option v-for="p in packages" :key="p.id" :value="p.id">{{ p.name }} ({{ p.price }} ل.س)</option>
                                </select>
                            </div>

                            <div v-if="selectedPackage" class="p-8 bg-slate-950 text-white rounded-xl relative overflow-hidden shadow-2xl">
                                <div class="absolute -bottom-16 -right-16 w-40 h-40 bg-primary/20 rounded-full blur-3xl"></div>
                                <div class="relative z-10 flex items-center justify-between flex-row-reverse">
                                    <div class="flex items-center gap-6">
                                         <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-emerald-400 mb-1">download</span>
                                            <span class="text-2xl font-black tracking-tighter">{{ selectedPackage.speed_down }}M</span>
                                         </div>
                                         <div class="w-px h-10 bg-white/10"></div>
                                         <div class="flex flex-col items-center">
                                            <span class="material-symbols-outlined text-secondary mb-1">upload</span>
                                            <span class="text-2xl font-black tracking-tighter">{{ selectedPackage.speed_up }}M</span>
                                         </div>
                                    </div>
                                    <div class="text-right">
                                         <p class="text-[10px] font-black text-white/30 uppercase tracking-widest mb-1">التكلفة والمدة</p>
                                         <p class="text-2xl font-black tracking-tight">{{ selectedPackage.price }} <span class="text-[12px] font-bold opacity-40">ل.س / {{ selectedPackage.duration_days }} يوم</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Account Synthesis -->
                <div class="surface-card p-10 rounded-xl relative overflow-hidden">
                    <div class="flex items-center justify-between mb-10 flex-row-reverse">
                        <div class="flex items-center gap-3">
                            <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em]">إعدادات الحساب</h3>
                            <span class="material-symbols-outlined text-secondary">vpn_key</span>
                        </div>
                        <button type="button" @click="generateSecret" class="px-6 py-2 bg-secondary-container/10 text-secondary font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-secondary hover:text-white transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">terminal</span>
                            توليد بيانات تلقائية
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">اسم المستخدم (Username)</label>
                            <input v-model="form.username" type="text" class="form-input-monolith font-mono font-bold" placeholder="مثال: alex.pppoe">
                        </div>
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">كلمة المرور (Password)</label>
                            <input v-model="form.password" type="text" class="form-input-monolith font-mono font-bold" placeholder="كلمة المرور المشفرة">
                        </div>
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">عنوان IP ثابت (اختياري)</label>
                            <input v-model="form.ip" type="text" class="form-input-monolith font-mono font-bold" placeholder="اتركه فارغاً للحصول على IP تلقائي">
                        </div>
                        <div class="space-y-4 text-right">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest mr-2">تاريخ انتهاء الصلاحية</label>
                            <input v-model="form.expires_at" type="date" class="form-input-monolith font-bold">
                        </div>
                    </div>
                </div>

                <!-- 4. Governance Commitment -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 surface-card p-12 bg-slate-950 text-white rounded-xl relative overflow-hidden shadow-2xl">
                    <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-right flex-row-reverse">
                        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center text-3xl shrink-0">
                            <span class="material-symbols-outlined text-[32px] text-primary">verified_user</span>
                        </div>
                        <div>
                             <h4 class="text-xl font-black uppercase tracking-tight">إتمام تفعيل الاشتراك</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mt-1">
                                سيتم إرسال بيانات المشترك وإعدادات الربط إلى الميكروتيك فور الحفظ.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6">
                        <Link 
                            :href="route('broadband.users.index')" 
                            class="px-8 py-5 bg-white/5 text-white/50 font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/10 hover:text-white transition-all active:scale-95 border border-white/10"
                        >
                            إلغاء العملية
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-slate-950 font-black text-[11px] uppercase tracking-[0.2em] rounded-lg shadow-xl hover:bg-emerald-500 hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-30 flex items-center gap-3"
                            :disabled="form.processing"
                        >
                            <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                            تأكيد التفعيل
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>


