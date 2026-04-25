<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';

const props = defineProps({
    internetSources: Array,
    towers: Array,
    products: Array,
});

const form = useForm({
    name: '',
    model_id: '',
    internet_source_id: '',
    location_tower_id: '',
    location: '',
    lat: '',
    lng: '',
    ip: '',
    api_port: '8728',
    username: 'admin',
    password: '',
});

const deviceQuery = ref('');
const isSearching = ref(false);
const showResults = ref(false);

const filteredProducts = computed(() => {
    if (!deviceQuery.value) return props.products;
    return props.products.filter(p => 
        p.model_name.toLowerCase().includes(deviceQuery.value.toLowerCase()) ||
        p.manufacturer.toLowerCase().includes(deviceQuery.value.toLowerCase())
    );
});

const selectedProduct = computed(() => {
    return props.products.find(p => p.id === form.model_id);
});

const selectProduct = (product) => {
    form.model_id = product.id;
    if (!form.name) {
        form.name = `${product.manufacturer} ${product.model_name}`;
    }
    deviceQuery.value = '';
    showResults.value = false;
};

const submit = () => {
    form.post(route('servers.store'));
};

</script>

<template>
    <InstitutionalLayout title="تأسيس عقدة شبكة">
        <Head title="ربط سيرفر MikroTik جديد" />

        <div class="max-w-5xl mx-auto pb-24 text-right px-4">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 flex-row-reverse">
                <div class="flex items-center gap-6 justify-end">
                    <div class="text-right">
                        <h1 class="text-3xl font-black text-primary tracking-tight mb-2">تأسيس عقدة MikroTik</h1>
                        <p class="text-slate-500 font-bold text-sm uppercase tracking-wider">تهيئة وتثبيت نواة برمجية جديدة في البنية التحتية</p>
                    </div>
                </div>
                <Link 
                    :href="route('servers.index')" 
                    class="w-12 h-12 bg-white border border-outline-variant/10 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm group"
                >
                    <span class="material-symbols-outlined text-[24px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Hardware Architecture Intelligence -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-4 mb-12 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">بيانات العتاد والطراز</h3>
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    </div>

                    <!-- Smart Node Discovery -->
                    <div class="relative mb-12 group">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2 mb-4 block">اكتشاف طراز العقدة (Smart Discovery)</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">search</span>
                            <input 
                                v-model="deviceQuery"
                                type="text" 
                                placeholder="ابحث في كتالوج MikroTik (مثلاً: CCR, hAP, RB...)"
                                class="form-input-monolith h-16 pr-14 text-sm font-black"
                                @focus="showResults = true"
                            >
                        </div>

                        <!-- Search Dropdown Logic -->
                        <div v-if="showResults && deviceQuery" class="absolute z-50 w-full mt-4 bg-white border border-outline-variant/10 rounded-xl shadow-2xl overflow-hidden p-3 animate-in fade-in slide-in-from-top-4 duration-300">
                            <div 
                                v-for="product in filteredProducts.slice(0, 5)" 
                                :key="product.id"
                                @click="selectProduct(product)"
                                class="flex items-center gap-4 p-4 hover:bg-surface-container-low rounded-lg cursor-pointer transition-all flex-row-reverse"
                            >
                                <div class="w-12 h-12 bg-primary/5 rounded-lg flex items-center justify-center shrink-0 border border-primary/10">
                                    <span class="material-symbols-outlined text-primary text-[24px]">router</span>
                                </div>
                                <div class="flex-1 text-right">
                                    <p class="font-black text-sm text-primary tracking-tight">{{ product.manufacturer }} {{ product.model_name }}</p>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-1">{{ product.device_type || 'عقدة راوتر أساسية' }}</p>
                                </div>
                                <span class="material-symbols-outlined text-slate-300">add_circle</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hardware Configuration Matrix -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-end flex-row-reverse">
                         <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">المُعرف المنطقي للعقدة (Node Identifier)</label>
                            <input v-model="form.name" type="text" placeholder="مثلاً: DC01-Primary-Core" class="form-input-monolith h-16 font-black uppercase tracking-tight" required>
                        </div>

                        <div v-if="selectedProduct" class="bg-primary p-8 rounded-xl flex items-center gap-6 shadow-xl shadow-primary/20 relative overflow-hidden group/pod">
                            <div class="absolute -top-10 -left-10 w-32 h-32 bg-white/5 rounded-full blur-3xl group-hover/pod:scale-150 transition-transform duration-1000"></div>
                            <div class="w-16 h-16 bg-white/10 rounded-lg flex items-center justify-center shrink-0 border border-white/10">
                                <span class="material-symbols-outlined text-white/50 text-[32px]">memory</span>
                            </div>
                            <div class="min-w-0 text-right">
                                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1.5 leading-none">توضع الطوبولوجيا</p>
                                <p class="text-sm font-black text-white truncate uppercase">{{ selectedProduct.model_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Connection Governance Protocols -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm">
                    <div class="flex items-center gap-4 mb-12 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">إعدادات الاتصال والوصول</h3>
                        <div class="w-1.5 h-6 bg-secondary rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="lg:col-span-2 space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">عنوان الواجهة العالمية (IP Address)</label>
                            <input v-model="form.ip" type="text" placeholder="10.x.x.x or 185.x.x.x" class="form-input-monolith h-16 font-mono font-black text-sm" required>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">منفذ بروتوكول API</label>
                            <input v-model="form.api_port" type="number" class="form-input-monolith h-16 font-mono font-black" placeholder="8728">
                        </div>

                        <div class="space-y-4">
                             <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">دورة الوصول</label>
                             <div class="h-16 form-input-monolith flex items-center justify-center bg-surface-container-low/50 text-[10px] font-black uppercase tracking-widest text-slate-300 border-dashed">
                                إدارة حوكمة مستمرة
                             </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">اسم المستخدم الحصين (admin)</label>
                            <input v-model="form.username" type="text" class="form-input-monolith h-16 font-black" placeholder="admin">
                        </div>

                        <div class="lg:col-span-3 space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مفتاح الوصول للبنية التحتية (Password)</label>
                            <input v-model="form.password" type="password" class="form-input-monolith h-16 font-mono" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- 3. Geospatial Infrastructure Registry -->
                <div class="surface-card p-10 rounded-xl border border-outline-variant/5 shadow-sm border-r-4 border-error">
                    <div class="flex items-center gap-4 mb-12 justify-end">
                        <h3 class="text-sm font-black text-primary uppercase tracking-widest">الموقع المادي والتوزيع الجغرافي</h3>
                        <div class="w-1.5 h-6 bg-error rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">مصدر تزويد العمود الفقري (Backbone)</label>
                            <select v-model="form.internet_source_id" class="form-input-monolith h-16 font-black text-[13px] uppercase">
                                <option value="">بوابة عقدة مستقلة (Independent Gateway)</option>
                                <option v-for="source in internetSources" :key="source.id" :value="source.id">
                                    {{ source.name }} ({{ source.type }})
                                </option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">البنية المظلية (البرج)</label>
                            <select v-model="form.location_tower_id" class="form-input-monolith h-16 font-black text-[13px] uppercase">
                                <option value="">توضع ذاتي مستقل</option>
                                <option v-for="tower in towers" :key="tower.id" :value="tower.id">
                                     {{ tower.name }} — 🗼
                                </option>
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-4">
                             <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">توصيف الموقع المادي الدقيق</label>
                             <input v-model="form.location" type="text" class="form-input-monolith h-16 text-sm font-bold" placeholder="مثلاً: الخزانة الشمالية - الرف 4">
                        </div>

                        <div class="space-y-4">
                             <label class="text-[10px] font-black text-rose-500 uppercase tracking-widest mr-2">خط العرض (Latitude)</label>
                             <input v-model="form.lat" type="text" class="form-input-monolith h-16 font-mono bg-rose-50/10 border-rose-100/20" placeholder="33.xxx">
                        </div>
                        <div class="space-y-4">
                             <label class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mr-2">خط الطول (Longitude)</label>
                             <input v-model="form.lng" type="text" class="form-input-monolith h-16 font-mono bg-emerald-50/10 border-emerald-100/20" placeholder="36.xxx">
                        </div>
                    </div>
                </div>

                <!-- Strategic Submission Finalization -->
                <div class="surface-card p-12 bg-slate-900 rounded-xl shadow-2xl overflow-hidden relative group/final">
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl group-hover/final:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10 flex-row-reverse">
                        <div class="flex items-center gap-8 flex-1 justify-end">
                            <div class="text-right">
                                 <h4 class="text-xl font-black text-white tracking-tight">تأكيد إضافة السيرفر</h4>
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">
                                    حوكمة البنية التحتية تتطلب دقة عالية في المزامنة.
                                 </p>
                            </div>
                            <div class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 shadow-inner group-hover/final:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-white text-[32px]" style="font-variation-settings: 'FILL' 1">bolt</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 relative z-10 flex-row-reverse">
                            <Link 
                                :href="route('servers.index')" 
                                class="px-10 py-5 bg-white/5 text-slate-400 font-black text-[11px] uppercase tracking-widest rounded-lg hover:bg-white/10 transition-all active:scale-95"
                            >
                                إلغاء العملية
                            </Link>
                            <button 
                                type="submit" 
                                class="px-14 py-5 bg-primary text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-lg shadow-2xl shadow-primary/20 hover:bg-emerald-600 transition-all hover:scale-[1.05] active:scale-95"
                                :disabled="form.processing"
                            >
                                إضافة العقدة إلى الشبكة
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>

