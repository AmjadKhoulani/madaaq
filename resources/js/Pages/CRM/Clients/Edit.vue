<script setup>
import { ref, Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useForm } from 'lucide-vue-next';;
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    client: Object,
    packages: Array,
    routers: Array,
    servers: Array,
    deviceModels: Array,
});

const form = useForm({
    name: props.client.name || props.client.username,
    phone: props.client.phone || props.client.username,
    email: props.client.email || '',
    password: '', 
    service_password: '', 
    type: props.client.type,
    mikrotik_server_id: props.client.mikrotik_server_id || '',
    package_id: props.client.package_id || '',
    tower_id: props.client.tower_id || '',
    price: props.client.custom_price || (props.client.package?.price || ''),
    status: props.client.status,
    pppoe_username: props.client.pppoe_username || props.client.username,
    hotspot_username: props.client.hotspot_username || props.client.username,
    ssid_id: props.client.ssid_id || '',
    connection_mode: props.client.connection_mode || 'wireless',
    cpe_model: props.client.cpe_model || '',
    cpe_username: props.client.cpe_username || 'admin',
    cpe_password: props.client.cpe_password || '',
    cpe_ip: props.client.cpe_ip || '',
    cpe_mac: props.client.cpe_mac || '',
    receiver_model: props.client.receiver_model || '',
    receiver_ip: props.client.receiver_ip || '',
    receiver_username: props.client.receiver_username || 'admin',
    receiver_password: props.client.receiver_password || '',
    tower_device_id: props.client.tower_device_id || '',
    ip_address: props.client.ip || '',
    data_limit: props.client.custom_data_limit_mb ? (props.client.custom_data_limit_mb / 1024).toFixed(0) : '',
    lat: props.client.lat || '',
    lng: props.client.lng || '',
    device_model_id: props.client.device_model_id || '',
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

const updatePackageDefaults = () => {
    if (!form.package_id) return;
    const pkg = props.packages.find(p => p.id == form.package_id);
    if (pkg) {
        form.price = pkg.price;
        form.data_limit = pkg.data_limit_mb ? (pkg.data_limit_mb / 1024).toFixed(0) : '';
    }
};

const updateCpeModelName = () => {
    if (!form.device_model_id) return;
    const model = props.deviceModels.find(m => m.id == form.device_model_id);
    if (model) form.cpe_model = model.name;
};

const submit = () => {
    form.put(route('crm.clients.update', props.client.id), {
        onSuccess: () => {},
        preserveScroll: true,
    });
};
</script>

<template>
    <InstitutionalLayout :title="'تعديل: ' + client.username">
        <Head :title="'تعديل المشترك: ' + client.username" />

        <!-- Form Top Command Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10 text-right">
            <div class="flex items-center gap-6">
                <Link 
                    :href="route('crm.clients.show', client.id)" 
                    class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </Link>
                <div>
                    <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تعديل بيانات المشترك: {{ client.username }}</h1>
                    <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest leading-none">تعديل إعدادات الاتصال والبيانات الفنية</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                 <div class="px-5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest border shadow-sm"
                    :class="client.status === 'active' ? 'bg-secondary-container/20 text-on-secondary-container border-secondary-container/30' : 'bg-error-container/10 text-error border-error-container/20'">
                    الحالة الحالية: {{ client.status === 'active' ? 'نشط' : 'مجمد' }}
                </div>
            </div>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start text-right">
            <!-- Central Form Column -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Section 1: Identity & Profile -->
                <div class="surface-card p-10 rounded-lg relative overflow-hidden">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">id_card</span>
                        البيانات الشخصية والتعريف
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest flex items-center gap-2">الاسم الكامل</label>
                            <input v-model="form.name" type="text" class="form-input-monolith" required />
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest flex items-center gap-2">رقم الهاتف (اسم المستخدم)</label>
                            <input v-model="form.phone" type="tel" class="form-input-monolith font-headline text-base" required />
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest flex items-center gap-2">كلمة مرور البوابة (Portal)</label>
                            <input v-model="form.password" type="text" placeholder="اتركه فارغاً للحفاظ على الحالة..." class="form-input-monolith" />
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest flex items-center gap-2">البريد الإلكتروني الرسمي</label>
                            <input v-model="form.email" type="email" class="form-input-monolith font-headline" />
                        </div>
                    </div>
                </div>

                <!-- Section 2: ISP Logic & Equipment -->
                <div class="surface-card p-10 rounded-lg">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">settings_input_antenna</span>
                            إعدادات الربط الفني
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">سيرفر الإدارة</label>
                            <select v-model="form.mikrotik_server_id" class="form-select-monolith">
                                <option value="">اختر عقدة الحافة...</option>
                                <option v-for="server in servers" :key="server.id" :value="server.id">{{ server.name }}</option>
                            </select>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">برج التغطية المرتبط</label>
                            <select v-model="form.tower_id" class="form-select-monolith" :disabled="!form.mikrotik_server_id">
                                <option value="">اختر البرج الهدف...</option>
                                <option v-for="tower in availableTowers" :key="tower.id" :value="tower.id">
                                    {{ (tower.type === 'cabinet' ? '📦 ' : '🗼 ') + tower.name }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-3">
                             <label class="text-[11px] font-black text-primary uppercase tracking-widest">بروتوكول الربط (Mode)</label>
                             <select v-model="form.connection_mode" class="form-select-monolith font-black text-primary border-primary-fixed/30 bg-primary-fixed/5">
                                <option value="wireless">ارتباط راديوي (Wireless)</option>
                                <option value="tower_switch">ربط مباشر (Switch Port)</option>
                                <option value="fiber">ألياف ضوئية (Fiber/GPON)</option>
                                <option value="cable">شبكة محلية (LAN)</option>
                             </select>
                        </div>
                    </div>

                    <!-- Hardware Segment: Peripheral Nodes -->
                    <div class="space-y-10 pt-10 border-t border-outline-variant/10">
                        <!-- Primary Hardware Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">طراز الراوتر الداخلي (CPE)</label>
                                <select v-model="form.device_model_id" @change="updateCpeModelName" class="form-select-monolith h-11 text-[13px]">
                                    <option value="">أجهزة مخصصة...</option>
                                    <option v-for="model in deviceModels" :key="model.id" :value="model.id">{{ model.name }}</option>
                                </select>
                                <input v-model="form.cpe_model" type="text" class="form-input-monolith h-10 mt-2 text-[12px] font-bold" placeholder="اسم الطراز يدوياً...">
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">عنوان IP الداخلي</label>
                                <input v-model="form.cpe_ip" type="text" class="form-input-monolith h-11 font-headline font-extrabold text-sm" placeholder="10.x.x.x" />
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">معرف الـ MAC الفيزيائي</label>
                                <input v-model="form.cpe_mac" type="text" class="form-input-monolith h-11 font-headline font-extrabold text-xs" placeholder="AA:BB:CC:DD:EE:FF" />
                            </div>
                        </div>

                        <!-- Secondary Radio Segment -->
                        <div v-show="form.connection_mode === 'wireless'" class="p-8 bg-secondary-container/5 rounded-lg border border-secondary/15 space-y-6">
                            <p class="text-[10px] font-black text-secondary uppercase tracking-[0.2em] mb-4">وحدة الاستقبال الخارجية (Radio Unit)</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">طراز الراديو (SXT/LHG)</label>
                                    <input v-model="form.receiver_model" type="text" class="form-input-monolith h-11" placeholder="مثلاً: SXT sq Lite5" />
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">IP إدارة الراديو</label>
                                    <input v-model="form.receiver_ip" type="text" class="form-input-monolith h-11 font-headline font-extrabold text-sm" placeholder="10.x.x.x" />
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">كلمة مرور الراديو</label>
                                    <input v-model="form.receiver_password" type="password" class="form-input-monolith h-11" placeholder="••••••••" />
                                </div>
                            </div>
                        </div>

                        <!-- Service Logic Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">واجهة البث (SSID)</label>
                                <select v-model="form.ssid_id" class="form-select-monolith" :disabled="form.connection_mode !== 'wireless'">
                                    <option value="">اختر هدف البث...</option>
                                    <option v-for="ssid in availableSSIDs" :key="ssid.id" :value="ssid.id">{{ ssid.ssid_name }}</option>
                                </select>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">كلمة مرور الخدمة (Secret)</label>
                                <input v-model="form.service_password" type="text" class="form-input-monolith font-headline font-extrabold bg-surface-container-low" placeholder="احتفظ بالمفتاح المشفر..." />
                                <p class="text-[9px] font-black text-slate-400 opacity-70">اتركه فارغاً للحفاظ على الكلمة الحالية</p>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">الـ IP المخصص (Static)</label>
                                <input v-model="form.ip_address" type="text" class="form-input-monolith font-headline font-black text-primary border-primary-fixed/50" placeholder="172.x.x.x" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Subscription & Billing -->
                <div class="surface-card p-10 rounded-lg border-2 border-secondary/10 bg-surface-container-low">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-secondary text-[24px]">payments</span>
                        بيانات الاشتراك والفوترة
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="lg:col-span-2 space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">باقة الاشتراك المخصصة</label>
                            <select v-model="form.package_id" @change="updatePackageDefaults" class="form-select-monolith font-black text-primary">
                                <option value="">باقة مخصصة (خارج الأطر)</option>
                                <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                    {{ pkg.name }} — {{ pkg.price }} ر.س
                                </option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">السعر المستقطع (ر.س)</label>
                            <input v-model="form.price" type="number" step="0.01" class="form-input-monolith font-headline font-black text-secondary" placeholder="0.00" />
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">الحصة المحددة (GB)</label>
                            <input v-model="form.data_limit" type="number" class="form-input-monolith font-headline font-extrabold" placeholder="مفتوح" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Strategy Column -->
            <div class="lg:col-span-1 space-y-8 sticky top-24">
                <div class="surface-card bg-primary text-white p-10 rounded-lg shadow-2xl shadow-primary/30 relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                    
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/50 mb-4">التزام مالي متوقع</p>
                    <div class="text-6xl font-black font-headline tracking-tighter mb-10 flex items-baseline gap-3">
                        {{ form.price || '0.00' }} <span class="text-2xl font-bold opacity-40">ر.س</span>
                    </div>

                    <div class="space-y-6 pt-8 border-t border-white/10 mb-10">
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">هوية المشترك</span>
                            <span class="text-xs font-black font-headline">{{ client.username }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest">نمط الارتباط</span>
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 bg-white/10 rounded border border-white/20">{{ form.connection_mode }}</span>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-5 bg-white text-primary rounded-lg font-black text-[12px] uppercase tracking-[0.2em] shadow-xl hover:bg-slate-50 transition-all active:scale-95 flex items-center justify-center gap-3 disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        حفظ التغييرات
                    </button>
                    
                    <p class="mt-6 text-[9px] text-white/30 text-center uppercase tracking-widest font-bold">التعديلات تنعكس فوراً على خوادم MikroTik المركزية</p>
                </div>

                <div class="surface-card p-6 border-2 border-outline-variant/10 rounded-lg">
                    <h4 class="text-[11px] font-black text-primary uppercase tracking-widest flex items-center gap-3 mb-6">
                         <span class="material-symbols-outlined text-[18px]">history</span>
                         آخر نبضات النشاط
                    </h4>
                    <ul class="space-y-4 pr-3 border-r-2 border-outline-variant/5">
                        <li v-for="activity in client.activities?.slice(0, 3)" :key="activity.id" class="text-[12px] font-bold text-slate-500 leading-snug">
                            {{ activity.description }}
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </InstitutionalLayout>
</template>


