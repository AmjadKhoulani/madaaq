<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computedref } from 'vue';

import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    servers: Array,
    deviceModels: Array,
    activeRouters: Array,
    currency: String,
});

const form = useForm({
    name: '',
    type: 'tower',
    city: '',
    district: '',
    height: '',
    location: '',
    lat: '',
    lng: '',
    has_solar: false,
    solar_panels_count: '',
    solar_panel_wattage: '',
    has_generator: false,
    generator_capacity: '',
    has_government_electricity: false,
    battery_count: '',
    battery_type: '',
    structure_cost: '',
    monthly_rent: '',
    mikrotik_server_id: '',
    connection_type: '',
    transmitter_type: 'existing',
    transmitter_router_id: '',
    transmitter_name: '',
    transmitter_ip: '',
    transmitter_model_id: '',
    receiver_name: '',
    receiver_ip: '',
    receiver_model_id: '',
});

const siteTypes = [
    { value: 'tower', label: 'برج لاسلكي', icon: 'tower' },
    { value: 'building', label: 'مبنى سكني', icon: 'apartment' },
    { value: 'cabinet', label: 'خزانة توزيع', icon: 'box' },
    { value: 'pole', label: 'سارية حافة', icon: 'navigation' },
    { value: 'office', label: 'مكتب فني', icon: 'home' },
];

const submit = () => {
    form.post(route('network.towers.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <InstitutionalLayout title="نشر موقع جديد">
        <Head title="بروتوكول نشر الموقع" />

        <div class="max-w-6xl mx-auto pb-24 text-right">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('network.towers.index')" 
                        class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                    >
                        <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تهيئة ونشر موقع جديد</h1>
                        <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest leading-none flex items-center gap-3 justify-end">
                            جاهز لبدء بروتوكول التهيئة
                            <span class="w-2 h-2 bg-secondary rounded-full animate-pulse shadow-[0_0_8px_rgba(var(--secondary-rgb),0.5)]"></span>
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. GitBranch Identity Matrix -->
                <div class="surface-card p-10 rounded-lg">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">id_card</span>
                        بيانات الموقع وتوصيف البرج
                    </h3>

                    <div class="space-y-10">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">تسمية الموقع / هوية الـ SSID</label>
                            <input v-model="form.name" type="text" class="form-input-monolith text-xl font-black tracking-tight" placeholder="مثال: CORE-SITE-01" required />
                        </div>

                        <div class="space-y-6">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">تصنيف البنية التحتية للموقع</label>
                            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                                <button 
                                    v-for="type in siteTypes" 
                                    :key="type.value"
                                    type="button"
                                    @click="form.type = type.value"
                                    class="p-6 rounded-lg border-2 transition-all flex flex-col items-center gap-4 group shadow-sm"
                                    :class="form.type === type.value ? 'bg-primary text-white border-primary shadow-xl scale-105' : 'bg-white border-outline-variant/10 text-slate-400 hover:bg-slate-50'"
                                >
                                    <span class="material-symbols-outlined text-[32px] group-hover:rotate-6 transition-transform" :style="form.type === type.value ? { 'font-variation-settings': '\'FILL\' 1' } : {}">{{ type.icon }}</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ type.label }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">المدينة / الإقليم</label>
                                <input v-model="form.city" type="text" class="form-input-monolith" placeholder="اسم المدينة الرئيسي" />
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">المنطقة / الحي</label>
                                <input v-model="form.district" type="text" class="form-input-monolith" placeholder="الحي أو القطاع" />
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">ارتفاع الهيكل (متر)</label>
                                <input v-model="form.height" type="number" class="form-input-monolith font-headline font-black text-primary" placeholder="0.0" />
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">بروتوكول الوصول الفيزيائي للموقع</label>
                            <textarea v-model="form.location" rows="3" class="form-input-monolith font-bold resize-none" placeholder="أي تعليمات خاصة للوصول للموقع أو الصيانة..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Geospatial Grid Entry -->
                <div class="surface-card p-10 rounded-lg">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">explore</span>
                        تخطيط الإحداثيات الجغرافية (Plotting)
                    </h3>

                    <div class="bg-surface-container-low border border-outline-variant/10 rounded-lg p-10 flex flex-col items-center justify-center gap-8 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
                        <span class="material-symbols-outlined text-primary text-[48px] animate-bounce">location_on</span>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-md">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-error uppercase tracking-widest text-center block">عرض (N-Lat)</label>
                                <input v-model="form.lat" type="text" class="form-input-monolith text-center font-headline font-black bg-white" placeholder="33.xxx" />
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-secondary uppercase tracking-widest text-center block">طول (E-Lng)</label>
                                <input v-model="form.lng" type="text" class="form-input-monolith text-center font-headline font-black bg-white" placeholder="36.xxx" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Pulse & Capital Configuration -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Energy Redundancy -->
                    <div class="surface-card p-10 space-y-8">
                         <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">bolt</span>
                             نظام تكرار الطاقة
                        </h3>

                        <div class="space-y-6">
                            <!-- Solar -->
                            <div class="p-8 rounded-lg border transition-all" :class="form.has_solar ? 'bg-amber-50/30 border-amber-200' : 'bg-surface-container-low border-outline-variant/10 shadow-inner'">
                                <label class="flex items-center justify-between cursor-pointer mb-8">
                                    <span class="text-xs font-black uppercase tracking-widest flex items-center gap-4">
                                        <span class="material-symbols-outlined text-amber-600 text-[28px]">wb_sunny</span>
                                        منظومة طاقة شمسية
                                    </span>
                                    <input v-model="form.has_solar" type="checkbox" class="w-8 h-8 rounded border-outline-variant/30 text-primary focus:ring-primary shadow-sm" />
                                </label>
                                <div v-if="form.has_solar" class="grid grid-cols-2 gap-6 animate-in fade-in">
                                    <input v-model="form.solar_panels_count" type="number" class="form-input-monolith h-12 bg-white" placeholder="عدد الألواح" />
                                    <input v-model="form.solar_panel_wattage" type="number" class="form-input-monolith h-12 bg-white" placeholder="الواط (W)" />
                                </div>
                            </div>

                            <!-- Generator -->
                             <div class="p-8 rounded-lg border transition-all" :class="form.has_generator ? 'bg-error-container/5 border-error-container/20' : 'bg-surface-container-low border-outline-variant/10 shadow-inner'">
                                <label class="flex items-center justify-between cursor-pointer mb-8">
                                    <span class="text-xs font-black uppercase tracking-widest flex items-center gap-4">
                                        <span class="material-symbols-outlined text-error text-[28px]">settings_power</span>
                                        محرك احتراق (Generator)
                                    </span>
                                    <input v-model="form.has_generator" type="checkbox" class="w-8 h-8 rounded border-outline-variant/30 text-primary focus:ring-primary shadow-sm" />
                                </label>
                                <div v-if="form.has_generator" class="animate-in fade-in space-y-2">
                                    <input v-model="form.generator_capacity" type="text" class="form-input-monolith h-12 bg-white" placeholder="مثلاً: 15 KVA / Diesel" />
                                </div>
                            </div>

                            <!-- Batteries Logic -->
                            <div class="p-10 bg-primary text-white rounded-lg space-y-8 shadow-xl shadow-primary/20 relative overflow-hidden">
                                <label class="text-[11px] font-black text-white/50 uppercase tracking-widest flex items-center gap-4">
                                    <span class="material-symbols-outlined text-white/80 text-[24px]">battery_std</span>
                                    تخزين الطاقة المادي (Batteries)
                                </label>
                                <div class="grid grid-cols-2 gap-6">
                                    <input v-model="form.battery_count" type="number" class="w-full h-12 bg-white/10 border border-white/20 rounded-lg px-5 text-white font-headline font-black outline-none" placeholder="العدد" />
                                    <input v-model="form.battery_type" type="text" class="w-full h-12 bg-white/10 border border-white/20 rounded-lg px-5 text-white font-black outline-none" placeholder="النوع" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial CAPEX -->
                    <div class="surface-card p-10 space-y-10">
                         <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">account_balance</span>
                            تقدير الإنفاق الرأسمالي (CAPEX)
                        </h3>

                        <div class="space-y-8">
                             <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">إجمالي تكلفة الهيكل والتأسيس</label>
                                <div class="relative">
                                    <input v-model="form.structure_cost" type="number" step="0.01" class="form-input-monolith h-16 pr-16 font-headline font-black text-xl text-primary" />
                                    <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400 font-bold text-xs uppercase">{{ currency }}</div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">التزام الإيجار الشهري للموقع</label>
                                <div class="relative">
                                    <input v-model="form.monthly_rent" type="number" step="0.01" class="form-input-monolith h-16 pr-16 font-headline font-black text-xl text-secondary" />
                                    <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400 font-bold text-xs uppercase">{{ currency }}</div>
                                </div>
                            </div>

                            <div class="p-10 bg-primary text-white rounded-lg shadow-xl shadow-primary/10 relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                                <div class="relative z-10 flex items-center gap-6">
                                    <div class="w-16 h-16 bg-white/20 rounded-lg flex items-center justify-center text-3xl">📊</div>
                                    <div class="text-right">
                                         <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">نبض السجل المالي</p>
                                         <p class="text-[12px] font-bold text-white leading-relaxed italic opacity-90">يجب تأكيد كافة الرسوم التشغيلية لضمان دقة التقارير المالية المركزية للمنصة.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Network Uplink Topology -->
                <div class="surface-card p-10 rounded-lg">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">GitBranch</span>
                        طوبوغرافيا الارتباط الشبكي (Uplink)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">سيرفر التزويد الرئيسي</label>
                            <select v-model="form.mikrotik_server_id" class="form-select-monolith h-16 font-black text-primary uppercase">
                                <option value="">موقع مستقل (Standalone)</option>
                                <option v-for="server in servers" :key="server.id" :value="server.id">{{ server.name }}</option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest">نمط تزويد البيانات للموقع</label>
                            <select v-model="form.connection_type" class="form-select-monolith h-16 font-black text-primary">
                                <option value="">لم يتم تحديد النمط</option>
                                <option value="wireless">ارتباط لاسلكي (PTP Array)</option>
                                <option value="fiber">ألياف ضوئية (Fiber)</option>
                                <option value="cable">ربط سلكي (Gigabit LAN)</option>
                            </select>
                        </div>

                        <!-- Wireless Backhaul Details -->
                        <div v-if="form.connection_type === 'wireless'" class="md:col-span-2 bg-slate-900 text-white p-10 rounded-lg animate-in slide-in-from-top duration-500 relative overflow-hidden text-right shadow-2xl">
                             <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
                             <h4 class="text-[10px] font-black text-primary-fixed/50 uppercase tracking-[0.3em] mb-10 pb-4 border-b border-white/5">إعدادات الارتباط اللاسلكي النقطي (Point-to-Point)</h4>

                             <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                                 <!-- Transmitter Row -->
                                 <div class="space-y-8">
                                    <div class="flex items-center justify-between flex-row-reverse">
                                         <label class="text-[11px] font-black text-primary-fixed uppercase tracking-widest">عنصر البث المصدر (TX)</label>
                                         <div class="flex bg-white/5 p-1 rounded-lg">
                                             <button type="button" @click="form.transmitter_type = 'existing'" class="px-5 py-2 rounded text-[9px] font-black uppercase tracking-widest transition-all" :class="form.transmitter_type === 'existing' ? 'bg-white text-slate-900 shadow-xl' : 'text-white/40'">مكتشف</button>
                                             <button type="button" @click="form.transmitter_type = 'new'" class="px-5 py-2 rounded text-[9px] font-black uppercase tracking-widest transition-all" :class="form.transmitter_type === 'new' ? 'bg-white text-slate-900 shadow-xl' : 'text-white/40'">يدوي</button>
                                         </div>
                                    </div>

                                    <div v-if="form.transmitter_type === 'existing'" class="space-y-4">
                                        <select v-model="form.transmitter_router_id" class="w-full h-14 bg-white/10 border border-white/10 rounded-lg px-6 font-black text-white outline-none focus:ring-2 focus:ring-primary/50 transition-all">
                                            <option value="" class="text-slate-900">اختر الراديو من السجل...</option>
                                            <option v-for="router in activeRouters" :key="router.id" :value="router.id" class="text-slate-900">{{ router.name }} — {{ router.ip }}</option>
                                        </select>
                                    </div>

                                    <div v-else class="space-y-4 animate-in fade-in">
                                         <input v-model="form.transmitter_name" type="text" class="w-full h-14 bg-white/10 border border-white/10 rounded-lg px-6 text-white font-black outline-none" placeholder="تسمية برمجية للراديو" />
                                         <input v-model="form.transmitter_ip" type="text" class="w-full h-14 bg-white/10 border border-white/10 rounded-lg px-6 text-white font-headline outline-none" placeholder="عنوان IP المصدر" />
                                    </div>
                                 </div>

                                 <!-- Receiver Row -->
                                 <div class="space-y-8 border-r border-white/5 pr-8">
                                     <label class="text-[11px] font-black text-secondary uppercase tracking-widest block">عنصر الاستقبال الختامي (RX)</label>
                                     <div class="space-y-4">
                                         <input v-model="form.receiver_name" type="text" class="w-full h-14 bg-white/10 border border-white/10 rounded-lg px-6 text-white font-black outline-none" placeholder="تسمية الطرف المستلم" />
                                         <input v-model="form.receiver_ip" type="text" class="w-full h-14 bg-white/10 border border-white/10 rounded-lg px-6 text-white font-headline outline-none" placeholder="IP وحدة الاستقبال" />
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Strategic Deployment Commitment -->
                <div class="surface-card p-10 bg-primary text-white rounded-lg shadow-2xl shadow-primary/30 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-right w-full">
                        <div class="w-20 h-20 bg-white/10 rounded-lg flex items-center justify-center border border-white/20 shrink-0 shadow-lg">
                            <span class="material-symbols-outlined text-white/80 text-[40px]">shield_with_heart</span>
                        </div>
                        <div>
                             <h4 class="text-xl font-black uppercase tracking-tight">التزام النشر الاستراتيجي</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mt-2 leading-none">
                                ترحيل معايير الموقع إلى السجل المركزي للبنية التحتية.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6 w-full md:w-auto shrink-0">
                        <Link 
                            :href="route('network.towers.index')" 
                            class="px-8 py-4 bg-white/10 text-white font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/20 transition-all active:scale-95 text-center"
                        >
                            إجهاض العملية
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-primary font-black text-[12px] uppercase tracking-[0.2em] rounded-lg shadow-2xl hover:bg-slate-50 transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3 w-full"
                            :disabled="form.processing"
                        >
                            <span class="material-symbols-outlined text-[20px] fill-current" style="font-variation-settings: 'FILL' 1">bolt</span>
                            تهيئة حوكمة الموقع
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>


