<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    UserPlus, 
    ChevronLeft, 
    User, 
    Phone, 
    Mail, 
    Lock, 
    Router, 
    TowerControl, 
    Wifi, 
    Wallet, 
    CheckCircle2,
    Info,
    Zap,
    ArrowRight
} from 'lucide-vue-next';

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
        preserveScroll: true,
    });
};
</script>

<template>
    <InstitutionalLayout title="إضافة مشترك جديد">
        <div class="space-y-8 pb-20">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">تسجيل مشترك جديد</h1>
                    <p class="text-slate-400 font-bold text-xs mt-1">قم بتعبئة البيانات الأساسية وإعدادات الشبكة لتفعيل الحساب</p>
                </div>
                <Link :href="route('crm.clients.index')" class="btn-outline flex items-center gap-2 px-6 py-2.5">
                    <ArrowRight class="w-4 h-4" />
                    العودة للسجل
                </Link>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Main Form Area -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Identity Section -->
                    <div class="clean-card p-10">
                        <div class="flex items-center gap-4 mb-10 border-b border-slate-50 pb-6">
                            <div class="w-10 h-10 bg-primary-soft text-primary rounded-xl flex items-center justify-center">
                                <User class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">البيانات الشخصية</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Subscriber Identity Details</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">الاسم الكامل</label>
                                <input v-model="form.name" type="text" placeholder="مثلاً: محمد أحمد" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">رقم الهاتف</label>
                                <input v-model="form.phone" type="tel" placeholder="09xxxxxxxx" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">البريد الإلكتروني</label>
                                <input v-model="form.email" type="email" placeholder="name@example.com" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">كلمة مرور البوابة</label>
                                <input v-model="form.password" type="text" placeholder="تعيين كلمة مرور للدخول..." class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all" required />
                            </div>
                        </div>
                    </div>

                    <!-- Network Section -->
                    <div class="clean-card p-10">
                        <div class="flex items-center justify-between mb-10 border-b border-slate-50 pb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100">
                                    <Router class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-900">إعدادات الاتصال والشبكة</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Network Logic & Gateway Config</p>
                                </div>
                            </div>
                            <div class="flex bg-slate-100 p-1 rounded-xl">
                                <button type="button" @click="form.type = 'pppoe'" class="px-6 py-2 rounded-lg text-[10px] font-black uppercase transition-all" :class="form.type === 'pppoe' ? 'bg-white text-primary shadow-sm' : 'text-slate-400'">برودباند</button>
                                <button type="button" @click="form.type = 'hotspot'" class="px-6 py-2 rounded-lg text-[10px] font-black uppercase transition-all" :class="form.type === 'hotspot' ? 'bg-white text-primary shadow-sm' : 'text-slate-400'">هوتسبوت</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">سيرفر الميكروتيك</label>
                                <select v-model="form.mikrotik_server_id" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all appearance-none cursor-pointer">
                                    <option value="">اختر السيرفر...</option>
                                    <option v-for="server in servers" :key="server.id" :value="server.id">{{ server.name }}</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">برج التغطية</label>
                                <select v-model="form.tower_id" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all appearance-none cursor-pointer" :disabled="!form.mikrotik_server_id">
                                    <option value="">اختر البرج...</option>
                                    <option v-for="tower in availableTowers" :key="tower.id" :value="tower.id">{{ tower.name }}</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">اسم مستخدم الشبكة</label>
                                <input v-model="form.pppoe_username" type="text" placeholder="الاسم في الميكروتيك" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">كلمة مرور الشبكة</label>
                                <input v-model="form.service_password" type="text" placeholder="السر (Secret)" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Sidebar -->
                <div class="space-y-8 sticky top-24">
                    
                    <!-- Summary Card -->
                    <div class="clean-card p-10 bg-primary text-white border-none shadow-2xl shadow-primary/20 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        
                        <div class="relative z-10">
                            <p class="text-[11px] font-black uppercase tracking-widest opacity-60 mb-2">القيمة الإجمالية</p>
                            <div class="flex items-baseline gap-2 mb-10">
                                <h3 class="text-5xl font-black italic tracking-tighter">{{ form.price || '0' }}</h3>
                                <span class="text-xs font-bold opacity-60">SAR</span>
                            </div>

                            <div class="space-y-4 mb-10 pt-10 border-t border-white/10">
                                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                                    <span class="opacity-60">نوع الاتصال</span>
                                    <span>{{ form.type }}</span>
                                </div>
                                <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                                    <span class="opacity-60">مدة الاشتراك</span>
                                    <span>{{ form.duration_days }} يوم</span>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full py-4 bg-white text-primary rounded-xl font-black text-sm uppercase tracking-widest shadow-xl hover:bg-blue-50 transition-all active:scale-95 flex items-center justify-center gap-3 disabled:opacity-50"
                                :disabled="form.processing"
                            >
                                <Zap class="w-5 h-5 fill-current" />
                                تفعيل الحساب الآن
                            </button>
                        </div>
                    </div>

                    <!-- Billing Selection -->
                    <div class="clean-card p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <Wallet class="w-5 h-5 text-slate-400" />
                            <h4 class="text-sm font-black text-slate-800 italic">الباقة والفوترة</h4>
                        </div>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">اختر الباقة</label>
                                <select v-model="form.package_id" @change="updatePackageDefaults" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:border-primary focus:ring-0 appearance-none">
                                    <option value="">تخصيص يدوي</option>
                                    <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">{{ pkg.name }}</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">السعر المخصص</label>
                                <input v-model="form.price" type="number" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold focus:border-primary focus:ring-0" />
                            </div>
                        </div>
                    </div>

                    <!-- Tech Tips -->
                    <div class="p-6 bg-blue-50 border border-blue-100 rounded-2xl">
                        <div class="flex gap-4">
                            <Info class="w-5 h-5 text-primary shrink-0" />
                            <p class="text-[11px] font-bold text-slate-600 leading-relaxed">
                                سيتم تلقائياً ترحيل إعدادات المشترك إلى سيرفر الميكروتيك المختار بمجرد الضغط على زر التفعيل.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>
