<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computedref } from 'vue';

import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    packages: Array,
    routers: Array,
    servers: Array,
    deviceModels: Array,
    lastIp: String,
});

const form = useForm({
    name: '',
    phone: '',
    email: '',
    password: '',
    service_password: '',
    type: 'pppoe',
    mikrotik_server_id: '',
    package_id: '',
    tower_id: '',
    price: '',
    pppoe_username: '',
    hotspot_username: '',
    ssid: '',
    connection_mode: 'wireless',
    cpe_model: '',
    cpe_username: 'admin',
    cpe_password: '',
    cpe_ip: '',
    receiver_model: '',
    receiver_ip: '',
    receiver_username: 'admin',
    receiver_password: '',
    switch_port: '',
    tower_device_id: '',
    ip_address: '',
    data_limit: '',
    duration_days: '30',
    lat: '',
    lng: '',
});

const availableTowers = computed(() => {
    if (!form.mikrotik_server_id) return [];
    const server = props.servers.find(s => s.id == form.mikrotik_server_id);
    return server ? server.towers : [];
});

const availableSSIDs = computed(() => {
    if (!form.tower_id) return [];
    const tower = availableTowers.value.find(t => t.id == form.tower_id);
    return tower ? tower.ssids : [];
});

const availableDevices = computed(() => {
    if (!form.tower_id) return [];
    const tower = availableTowers.value.find(t => t.id == form.tower_id);
    if (!tower) return [];
    
    return tower.devices.filter(d => {
        if (form.connection_mode === 'wireless') return d.type === 'wireless' || d.type === 'ap';
        return d.type === 'switch';
    });
});

const updatePackageDefaults = () => {
    if (!form.package_id) return;
    const pkg = props.packages.find(p => p.id == form.package_id);
    if (pkg) {
        form.price = pkg.price;
        form.duration_days = pkg.duration || 30;
        form.data_limit = pkg.data_limit_mb ? (pkg.data_limit_mb / 1024).toFixed(0) : '';
    }
};

const submit = () => {
    form.post(route('crm.clients.store'), {
        onSuccess: () => {},
        preserveScroll: true,
    });
};
</script>

<template>
    <InstitutionalLayout title="إضافة مشترك جديد">
        <Head title="إضافة مشترك جديد" />

        <!-- Subscriber Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10 text-right">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-tight mb-2">إضافة مشترك جديد</h1>
                <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest leading-none">إدخال بيانات المشترك وإعدادات الاتصال</p>
            </div>
            <Link 
                :href="route('crm.clients.index')" 
                class="px-8 py-3.5 bg-white border border-outline-variant/10 rounded-lg text-slate-500 font-black text-[11px] uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm group flex items-center gap-2"
            >
                <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                إلغاء العملية
            </Link>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start text-right">
            <!-- Central Intelligence Column -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Section 1: Identity Matrix -->
                <div class="surface-card p-10 rounded-lg relative overflow-hidden">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">person_add</span>
                        البيانات الشخصية
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">الاسم القانوني الكامل</label>
                            <input v-model="form.name" type="text" placeholder="مثلاً: أحمد محمد" class="form-input-monolith" required />
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">رقم الهاتف (اسم المستخدم)</label>
                            <input v-model="form.phone" type="tel" placeholder="+963..." class="form-input-monolith font-headline text-base" required />
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">البريد الإلكتروني (اختياري)</label>
                            <input v-model="form.email" type="email" placeholder="user@example.com" class="form-input-monolith font-headline" />
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">مفتاح الوصول للبوابة (Portal Password)</label>
                            <input v-model="form.password" type="text" placeholder="أدخل كلمة مرور قوية..." class="form-input-monolith" required />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Network Connectivity -->
                <div class="surface-card p-10 rounded-lg">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 mb-10">
                        <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3 w-full">
                            <span class="material-symbols-outlined text-primary text-[20px]">router</span>
                            إعدادات الربط الفني
                        </h3>
                        <div class="flex p-1 bg-surface-container-low rounded-lg border border-outline-variant/10 w-full sm:w-auto shrink-0">
                            <button 
                                type="button"
                                @click="form.type = 'pppoe'"
                                class="flex-1 px-6 py-2.5 rounded text-[10px] font-black uppercase tracking-widest transition-all"
                                :class="form.type === 'pppoe' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-400'"
                            >Broadband</button>
                            <button 
                                type="button"
                                @click="form.type = 'hotspot'"
                                class="flex-1 px-6 py-2.5 rounded text-[10px] font-black uppercase tracking-widest transition-all"
                                :class="form.type === 'hotspot' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-400'"
                            >Hotspot</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">سيرفر التحكم (Gateway)</label>
                            <select v-model="form.mikrotik_server_id" class="form-select-monolith">
                                <option value="">اختر السيرفر...</option>
                                <option v-for="server in servers" :key="server.id" :value="server.id">{{ server.name }}</option>
                            </select>
                        </div>
                        
                        <div class="space-y-3">
                             <label class="text-[11px] font-black text-primary uppercase tracking-widest">برج التغطية / الموقع</label>
                            <select v-model="form.tower_id" class="form-select-monolith" :disabled="!form.mikrotik_server_id">
                                <option value="">اختر البرج...</option>
                                <option v-for="tower in availableTowers" :key="tower.id" :value="tower.id">
                                    {{ (tower.type === 'cabinet' ? '📦 ' : '🗼 ') + tower.name }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-3">
                             <label class="text-[11px] font-black text-primary uppercase tracking-widest">نمط الارتباط المادي</label>
                             <select v-model="form.connection_mode" class="form-select-monolith font-black text-primary">
                                <option value="wireless">ارتباط لاسلكي (Radio)</option>
                                <option value="tower_switch">منفذ سويتش مباشر</option>
                                <option value="fiber">ألياف ضوئية (Fiber)</option>
                                <option value="cable">كبل إيثرنت محلي</option>
                             </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pt-10 border-t border-outline-variant/10">
                        <!-- Devices Strategy -->
                        <div class="space-y-6">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ form.connection_mode === 'wireless' ? 'وحدة البث الهدف (AP)' : 'وحدة التوزيع (Switch)' }}
                                </label>
                                <select v-model="form.tower_device_id" class="form-select-monolith h-11" :disabled="!form.tower_id">
                                    <option value="">تعيين جهاز ذكي...</option>
                                    <option v-for="device in availableDevices" :key="device.id" :value="device.id">
                                        {{ device.name }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="form.connection_mode === 'wireless'" class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">تردد البث المتاح (SSID)</label>
                                <select v-model="form.ssid" class="form-select-monolith h-11" :disabled="!form.tower_id">
                                    <option value="">اختر شبكة البث...</option>
                                    <option v-for="ssid in availableSSIDs" :key="ssid.id" :value="ssid.ssid_name">{{ ssid.ssid_name }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Network Identity -->
                        <div class="space-y-6">
                             <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">اسم مستخدم الشبكة (RADIUS)</label>
                                <input v-model="form.pppoe_username" type="text" class="form-input-monolith h-11 font-headline font-extrabold bg-surface-container-low" placeholder="customer_node_1">
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">كلمة مرور الشبكة (Secret)</label>
                                <input v-model="form.service_password" type="text" class="form-input-monolith h-11 font-headline font-extrabold bg-surface-container-low" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Subscription & Billing -->
                <div class="surface-card p-10 rounded-lg border-2 border-secondary/10 bg-surface-container-low">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-secondary text-[24px]">account_balance_wallet</span>
                        إعدادات الاشتراك والفوترة
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="lg:col-span-2 space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">فئة الخدمة (الباقة)</label>
                            <select v-model="form.package_id" @change="updatePackageDefaults" class="form-select-monolith font-black text-primary">
                                <option value="">تخصيص يدوي (بدون باقة)</option>
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                    {{ pkg.name }} — {{ pkg.price }} ر.س
                                </option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">القيمة المستحقة (ر.س)</label>
                            <input v-model="form.price" type="number" step="0.01" class="form-input-monolith font-headline font-black text-secondary" placeholder="0.00" />
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">فترة الصلاحية (أيام)</label>
                            <input v-model="form.duration_days" type="number" class="form-input-monolith font-headline font-extrabold" placeholder="30" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Strategy Sidebar -->
            <div class="lg:col-span-1 space-y-8 sticky top-24">
                <div class="surface-card bg-primary text-white p-10 rounded-lg shadow-2xl shadow-primary/30 relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                    
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/50 mb-4">قيمة الاشتراك</p>
                    <div class="text-6xl font-black font-headline tracking-tighter mb-10 flex items-baseline gap-3">
                        {{ form.price || '0.00' }} <span class="text-2xl font-bold opacity-40">ر.س</span>
                    </div>

                    <div class="space-y-6 pt-8 border-t border-white/10 mb-10">
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">بروتوكول الخدمة</span>
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 bg-white/10 rounded border border-white/20">{{ form.type }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">نمط الارتباط</span>
                            <span class="text-xs font-black font-headline">{{ form.connection_mode }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">مدة الصلاحية</span>
                            <span class="text-xs font-black font-headline">{{ form.duration_days }} يوم</span>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-5 bg-white text-primary rounded-lg font-black text-[12px] uppercase tracking-[0.2em] shadow-xl hover:bg-slate-50 transition-all active:scale-95 flex items-center justify-center gap-3 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        <span class="material-symbols-outlined text-[20px] fill-current" style="font-variation-settings: 'FILL' 1">bolt</span>
                        تأكيد إضافة المشترك
                    </button>
                </div>

                <div class="surface-card p-8 bg-amber-50 border-2 border-amber-100 rounded-lg">
                    <h4 class="text-[11px] font-black text-amber-900 flex items-center gap-3 mb-4 uppercase tracking-widest">
                         <span class="material-symbols-outlined text-[18px]">verified_user</span>
                         ملاحظات تقنية
                    </h4>
                    <p class="text-[11px] text-amber-800/70 font-bold leading-relaxed">
                        بمجرد بدء عملية التنفيذ، سيتم تلقائياً ترحيل بيانات الاشتراك إلى السيرفر المختار. يرجى التأكد من صحة البيانات قبل المتابعة.
                    </p>
                </div>
            </div>
        </form>
    </InstitutionalLayout>
</template>


