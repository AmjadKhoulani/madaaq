<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    TowerControl, 
    ArrowRight, 
    MapPin, 
    Zap, 
    Sun, 
    BatteryMedium, 
    Settings, 
    Wallet, 
    Wifi, 
    Info,
    CheckCircle2
} from 'lucide-vue-next';

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
    { value: 'tower', label: 'برج لاسلكي', icon: TowerControl },
    { value: 'building', label: 'مبنى سكني', icon: Settings },
    { value: 'cabinet', label: 'خزانة توزيع', icon: Settings },
    { value: 'pole', label: 'سارية حافة', icon: Settings },
    { value: 'office', label: 'مكتب فني', icon: Settings },
];

const submit = () => {
    form.post(route('network.towers.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <InstitutionalLayout title="إضافة موقع جديد">
        <div class="space-y-8 pb-20">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">تهيئة موقع/برج جديد</h1>
                    <p class="text-slate-400 font-bold text-xs mt-1">أدخل بيانات الموقع الجغرافي وتفاصيل الطاقة والربط الشبكي</p>
                </div>
                <Link :href="route('network.towers.index')" class="btn-outline flex items-center gap-2 px-6 py-2.5">
                    <ArrowRight class="w-4 h-4" />
                    العودة للمصفوفة
                </Link>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Main Form Area -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Basic Info -->
                    <div class="clean-card p-10">
                        <div class="flex items-center gap-4 mb-10 border-b border-slate-50 pb-6">
                            <div class="w-10 h-10 bg-primary-soft text-primary rounded-xl flex items-center justify-center">
                                <TowerControl class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">هوية الموقع</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Basic Site Identity & Type</p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">اسم الموقع / البرج</label>
                                <input v-model="form.name" type="text" placeholder="مثلاً: برج العليا الرئيسي" class="w-full bg-slate-50 border-slate-200 rounded-xl px-5 py-4 text-xl font-black text-slate-900 focus:border-primary focus:ring-0 transition-all" required />
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <button 
                                    v-for="type in siteTypes" 
                                    :key="type.value"
                                    type="button"
                                    @click="form.type = type.value"
                                    class="p-6 rounded-2xl border-2 transition-all flex flex-col items-center gap-3"
                                    :class="form.type === type.value ? 'bg-primary text-white border-primary shadow-xl scale-105' : 'bg-slate-50 border-slate-100 text-slate-400 hover:bg-slate-100'"
                                >
                                    <component :is="type.icon" class="w-8 h-8" />
                                    <span class="text-[10px] font-black uppercase">{{ type.label }}</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">المدينة</label>
                                    <input v-model="form.city" type="text" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">الحي/المنطقة</label>
                                    <input v-model="form.district" type="text" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 block">الارتفاع (متر)</label>
                                    <input v-model="form.height" type="number" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Energy Systems -->
                    <div class="clean-card p-10">
                        <div class="flex items-center gap-4 mb-10 border-b border-slate-50 pb-6">
                            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center border border-amber-100">
                                <Zap class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">أنظمة الطاقة</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Power Redundancy & Storage</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Solar -->
                            <div class="p-6 rounded-2xl border transition-all" :class="form.has_solar ? 'bg-amber-50/30 border-amber-200' : 'bg-slate-50 border-slate-100 opacity-60'">
                                <label class="flex items-center justify-between cursor-pointer mb-6">
                                    <div class="flex items-center gap-3">
                                        <Sun class="w-6 h-6 text-amber-500" />
                                        <span class="text-xs font-black uppercase tracking-widest">طاقة شمسية</span>
                                    </div>
                                    <input v-model="form.has_solar" type="checkbox" class="w-6 h-6 rounded-lg text-primary focus:ring-0 border-slate-300" />
                                </label>
                                <div v-if="form.has_solar" class="grid grid-cols-2 gap-4 animate-in fade-in">
                                    <input v-model="form.solar_panels_count" type="number" placeholder="عدد الألواح" class="w-full bg-white border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold" />
                                    <input v-model="form.solar_panel_wattage" type="number" placeholder="الواط" class="w-full bg-white border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold" />
                                </div>
                            </div>

                            <!-- Battery -->
                            <div class="p-6 rounded-2xl bg-slate-900 text-white border-none shadow-xl shadow-slate-900/10">
                                <div class="flex items-center gap-3 mb-6">
                                    <BatteryMedium class="w-6 h-6 text-primary" />
                                    <span class="text-xs font-black uppercase tracking-widest">تخزين البطاريات</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <input v-model="form.battery_count" type="number" placeholder="العدد" class="w-full bg-white/10 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold outline-none" />
                                    <input v-model="form.battery_type" type="text" placeholder="النوع" class="w-full bg-white/10 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold outline-none" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Sidebar -->
                <div class="space-y-8 sticky top-24">
                    
                    <!-- Map & Location -->
                    <div class="clean-card p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <MapPin class="w-5 h-5 text-rose-500" />
                            <h4 class="text-sm font-black text-slate-800">الإحداثيات الجغرافية</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Latitude</label>
                                <input v-model="form.lat" type="text" placeholder="33.xxx" class="w-full bg-slate-50 border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-center" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Longitude</label>
                                <input v-model="form.lng" type="text" placeholder="36.xxx" class="w-full bg-slate-50 border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-center" />
                            </div>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-center">
                            <p class="text-[10px] font-bold text-slate-400">سيتم تحديد الموقع على الخريطة تلقائياً</p>
                        </div>
                    </div>

                    <!-- Financial Card -->
                    <div class="clean-card p-10 bg-primary text-white border-none shadow-2xl shadow-primary/20">
                        <div class="flex items-center gap-3 mb-8">
                            <Wallet class="w-5 h-5 opacity-60" />
                            <h4 class="text-xs font-black uppercase tracking-widest">التكاليف التشغيلية</h4>
                        </div>
                        <div class="space-y-6 mb-10">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest opacity-40">تكلفة التأسيس</label>
                                <div class="flex items-baseline gap-2">
                                    <input v-model="form.structure_cost" type="number" class="w-full bg-transparent border-none p-0 text-3xl font-black italic tracking-tighter outline-none" placeholder="0.00" />
                                    <span class="text-[10px] font-bold opacity-40">SAR</span>
                                </div>
                            </div>
                        </div>
                        <button 
                            type="submit" 
                            class="w-full py-4 bg-white text-primary rounded-xl font-black text-sm uppercase tracking-widest shadow-xl hover:bg-blue-50 transition-all active:scale-95 flex items-center justify-center gap-3 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            <CheckCircle2 class="w-5 h-5" />
                            تثبيت الموقع الآن
                        </button>
                    </div>

                    <!-- Info Tip -->
                    <div class="p-6 bg-blue-50 border border-blue-100 rounded-2xl">
                        <div class="flex gap-4">
                            <Info class="w-5 h-5 text-primary shrink-0" />
                            <p class="text-[11px] font-bold text-slate-600 leading-relaxed">
                                تأكد من صحة إحداثيات الموقع لضمان دقة خرائط التغطية وحساب المسافات للمشتركين.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>
