<script setup>
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    tower: Object,
    servers: Array,
    deviceModels: Array,
    activeRouters: Array,
    currency: String,
});

const form = useForm({
    name: props.tower.name,
    type: props.tower.type,
    city: props.tower.city || '',
    district: props.tower.district || '',
    height: props.tower.height || '',
    location: props.tower.location || '',
    lat: props.tower.lat || '',
    lng: props.tower.lng || '',
    has_solar: !!props.tower.has_solar,
    solar_panels_count: props.tower.solar_panels_count || '',
    solar_panel_wattage: props.tower.solar_panel_wattage || '',
    has_generator: !!props.tower.has_generator,
    generator_capacity: props.tower.generator_capacity || '',
    has_government_electricity: !!props.tower.has_government_electricity,
    battery_count: props.tower.battery_count || '',
    battery_type: props.tower.battery_type || '',
    structure_cost: props.tower.structure_cost || '',
    monthly_rent: props.tower.monthly_rent || '',
    mikrotik_server_id: props.tower.mikrotik_server_id || '',
    connection_type: props.tower.connection_type || '',
    transmitter_type: 'existing',
    transmitter_router_id: props.tower.transmitter_router_id || '',
    transmitter_name: '',
    transmitter_ip: '',
    transmitter_model_id: '',
    receiver_name: '',
    receiver_ip: props.tower.receiver_ip || '',
    receiver_model_id: props.tower.receiver_model_id || '',
});

const siteTypes = [
    { value: 'tower', label: 'برج لاسلكي', icon: 'tower' },
    { value: 'building', label: 'مبنى سكني', icon: 'apartment' },
    { value: 'cabinet', label: 'خزانة توزيع', icon: 'box' },
    { value: 'pole', label: 'سارية حافة', icon: 'navigation' },
    { value: 'office', label: 'مكتب فني', icon: 'home' },
];

const submit = () => {
    form.put(route('network.towers.update', props.tower.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <InstitutionalLayout :title="'تعديل: ' + tower.name">
        <Head :title="'حوكمة الموقع: ' + tower.name" />

        <div class="max-w-6xl mx-auto pb-24 text-right">
            <!-- Strategic Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('network.towers.show', tower.id)" 
                        class="w-12 h-12 bg-white shadow-sm border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all group"
                    >
                        <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تعديل بروتوكول الموقع</h1>
                        <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest leading-none">تعديل معايير الحوكمة لـ {{ tower.name }}</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- 1. Identity & Classification Matrix -->
                <div class="surface-card p-10 rounded-lg">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">fingerprint</span>
                        بيانات الموقع وتوصيف البرج
                    </h3>

                    <div class="space-y-10">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">اسم الموقع / عنوان البث (SSID Host)</label>
                            <input v-model="form.name" type="text" class="form-input-monolith text-xl font-black tracking-tight" required />
                        </div>

                        <div class="space-y-6">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">تصنيف البنية التحتية</label>
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
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">المدينة / المنطقة</label>
                                <input v-model="form.city" type="text" class="form-input-monolith" />
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">الحي / القطاع</label>
                                <input v-model="form.district" type="text" class="form-input-monolith" />
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest">ارتفاع الهيكل (متر)</label>
                                <input v-model="form.height" type="number" class="form-input-monolith font-headline font-black text-primary" />
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">بروتوكول الوصول الفيزيائي (الوصف)</label>
                            <textarea v-model="form.location" rows="3" class="form-input-monolith font-bold resize-none" placeholder="اكتب تفاصيل الوصول للموقع..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Geospatial Registry -->
                <div class="surface-card p-10 rounded-lg">
                    <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-[20px]">location_on</span>
                        سجل الإحداثيات الجغرافية (Geospatial)
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                         <div class="space-y-3">
                             <label class="text-[10px] font-black text-error uppercase tracking-widest block mr-2">خط العرض (Latitude)</label>
                             <input v-model="form.lat" type="text" class="form-input-monolith font-headline font-black text-primary border-error/20 bg-error/5" placeholder="33.xxx" />
                         </div>
                         <div class="space-y-3">
                             <label class="text-[10px] font-black text-secondary uppercase tracking-widest block mr-2">خط الطول (Longitude)</label>
                             <input v-model="form.lng" type="text" class="form-input-monolith font-headline font-black text-primary border-secondary/20 bg-secondary/5" placeholder="36.xxx" />
                         </div>
                    </div>
                </div>

                <!-- 3. Pulse Systems (Energy Redundancy) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Energy Connectivity -->
                    <div class="surface-card p-10 space-y-10">
                         <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">bolt</span>
                            نظام تكرار الطاقة
                        </h3>

                        <div class="space-y-6">
                            <!-- Solar -->
                            <div class="p-8 rounded-lg border transition-all" :class="form.has_solar ? 'bg-amber-50/30 border-amber-200' : 'bg-surface-container-low border-outline-variant/10'">
                                <label class="flex items-center justify-between cursor-pointer mb-8">
                                    <span class="text-xs font-black uppercase tracking-widest flex items-center gap-4">
                                        <span class="material-symbols-outlined text-amber-600 text-[28px]">wb_sunny</span>
                                        الامتصاص الكهروضوئي (Solar)
                                    </span>
                                    <input v-model="form.has_solar" type="checkbox" class="w-8 h-8 rounded border-outline-variant/30 text-primary focus:ring-primary shadow-sm" />
                                </label>
                                <div v-if="form.has_solar" class="grid grid-cols-2 gap-6 animate-in fade-in">
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase">وحدات الألواح</label>
                                        <input v-model="form.solar_panels_count" type="number" class="form-input-monolith h-12" placeholder="عدد الخلايا" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase">القدرة الاسمية (W)</label>
                                        <input v-model="form.solar_panel_wattage" type="number" class="form-input-monolith h-12" placeholder="واط لكل لوح" />
                                    </div>
                                </div>
                            </div>

                            <!-- Generator -->
                             <div class="p-8 rounded-lg border transition-all" :class="form.has_generator ? 'bg-error-container/5 border-error-container/20' : 'bg-surface-container-low border-outline-variant/10'">
                                <label class="flex items-center justify-between cursor-pointer mb-8">
                                    <span class="text-xs font-black uppercase tracking-widest flex items-center gap-4">
                                        <span class="material-symbols-outlined text-error text-[28px]">bolt</span>
                                        مولد الاحتراق الاحتياطي
                                    </span>
                                    <input v-model="form.has_generator" type="checkbox" class="w-8 h-8 rounded border-outline-variant/30 text-primary focus:ring-primary shadow-sm" />
                                </label>
                                <div v-if="form.has_generator" class="animate-in fade-in space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase">سعة التوليد / البروتوكول</label>
                                    <input v-model="form.generator_capacity" type="text" class="form-input-monolith h-12" placeholder="مثلاً: 15 KVA / Diesel" />
                                </div>
                            </div>

                            <!-- Grid Elec -->
                            <div class="p-8 rounded-lg border transition-all" :class="form.has_government_electricity ? 'bg-secondary-container/10 border-secondary-container/30' : 'bg-surface-container-low border-outline-variant/10'">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <span class="text-xs font-black uppercase tracking-widest flex items-center gap-4">
                                        <span class="material-symbols-outlined text-secondary text-[28px]">electric_bolt</span>
                                        الشبكة الوطنية العامة
                                    </span>
                                    <input v-model="form.has_government_electricity" type="checkbox" class="w-8 h-8 rounded border-outline-variant/30 text-primary focus:ring-primary shadow-sm" />
                                </label>
                            </div>

                            <!-- Batteries -->
                            <div class="p-10 bg-primary text-white rounded-lg space-y-8 shadow-xl shadow-primary/20 relative overflow-hidden">
                                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                                <label class="text-[11px] font-black text-white/50 uppercase tracking-widest flex items-center gap-4">
                                    <span class="material-symbols-outlined text-white/80 text-[24px]">battery_std</span>
                                    تخزين الطاقة الكيميائية (Batteries)
                                </label>
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-white/40 uppercase">عدد الخلايا</label>
                                        <input v-model="form.battery_count" type="number" class="w-full h-12 bg-white/10 border border-white/20 rounded-lg px-5 text-white font-headline font-black outline-none focus:ring-2 focus:ring-white/30" placeholder="Cells" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-white/40 uppercase">بروفايل الكيمياء</label>
                                        <input v-model="form.battery_type" type="text" class="w-full h-12 bg-white/10 border border-white/20 rounded-lg px-5 text-white font-black outline-none focus:ring-2 focus:ring-white/30" placeholder="مثلاً: Deep Cycle" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Ledger -->
                    <div class="surface-card p-10 space-y-10">
                         <h3 class="text-sm font-black text-primary uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">payments</span>
                            تطور سجل الإنفاق الرأسمالي (Ledger)
                        </h3>

                        <div class="space-y-8">
                             <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">تكلفة التأسيس والهيكل (CAPEX)</label>
                                <div class="relative">
                                    <input v-model="form.structure_cost" type="number" step="0.01" class="form-input-monolith h-16 pr-16 font-headline font-black text-xl text-primary" />
                                    <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400 font-bold text-xs uppercase">{{ currency }}</div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-primary uppercase tracking-widest block mr-2">بروتوكول الإيجار الشهري (OPEX)</label>
                                <div class="relative">
                                    <input v-model="form.monthly_rent" type="number" step="0.01" class="form-input-monolith h-16 pr-16 font-headline font-black text-xl text-secondary" />
                                    <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400 font-bold text-xs uppercase">{{ currency }}</div>
                                </div>
                            </div>

                            <div class="p-10 bg-secondary-container/10 border border-secondary/20 text-on-secondary-container rounded-lg shadow-inner relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-secondary/10 rounded-full blur-3xl transition-all group-hover:scale-125"></div>
                                <div class="relative z-10 flex items-center gap-6">
                                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center text-3xl shadow-sm">💰</div>
                                    <div class="text-right">
                                         <p class="text-[10px] font-black text-secondary uppercase tracking-[0.2em] mb-2">إزاحة السجل المحاسبي</p>
                                         <p class="text-[12px] font-bold text-slate-500 leading-relaxed italic opacity-80">أي تعديل في التكاليف التشغيلية سينعكس تلقائياً في دورة التحصيل القادمة للموقع.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Strategic Commitment & Sync -->
                <div class="surface-card p-10 bg-primary text-white rounded-lg shadow-2xl shadow-primary/30 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="relative z-10 flex items-center gap-8 text-right w-full">
                        <div class="w-20 h-20 bg-white/10 rounded-lg flex items-center justify-center border border-white/20 shrink-0">
                            <span class="material-symbols-outlined text-white/80 text-[40px]">security</span>
                        </div>
                        <div>
                             <h4 class="text-xl font-black uppercase tracking-tight">التزام الصيانة السحابية</h4>
                             <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mt-2">
                                إعادة مزامنة معايير الموقع مع السجل المركزي للبنية التحتية.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6 w-full md:w-auto shrink-0">
                        <Link 
                            :href="route('network.towers.show', tower.id)" 
                            class="px-8 py-4 bg-white/10 text-white font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/20 transition-all active:scale-95"
                        >
                            إلغاء البروتوكول
                        </Link>
                        <button 
                            type="submit" 
                            class="px-12 py-5 bg-white text-primary font-black text-[12px] uppercase tracking-[0.2em] rounded-lg shadow-2xl hover:bg-slate-50 transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center gap-3"
                            :disabled="form.processing"
                        >
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            مزامنة البروتوكول
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>
