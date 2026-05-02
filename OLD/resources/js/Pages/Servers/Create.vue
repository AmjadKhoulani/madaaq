<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import { 
    ChevronRight,
    Search,
    Router,
    Cpu,
    Network,
    Server,
    ShieldCheck,
    Zap,
    MapPin,
    Radio,
    HardDrive,
    Globe
} from 'lucide-vue-next';

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
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <Link 
                        :href="route('servers.index')" 
                        class="w-12 h-12 bg-white shadow-sm border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-vendor transition-all group"
                    >
                        <ChevronRight class="w-6 h-6 group-hover:translate-x-1 transition-transform" />
                    </Link>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                             <span class="w-8 h-1 bg-vendor rounded-full"></span>
                             <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Network Infrastructure Core</p>
                        </div>
                        <h1 class="text-3xl font-black text-slate-900 tracking-tight italic">تأسيس عقدة <span class="text-vendor">MikroTik</span></h1>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-2 italic">تهيئة وتثبيت نواة برمجية جديدة في البنية التحتية السيادية</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-10">
                <!-- 1. Hardware Architecture Intelligence -->
                <div class="glass-card p-10 bg-white/60">
                    <div class="flex items-center gap-4 mb-12">
                        <div class="w-1.5 h-6 bg-vendor rounded-full"></div>
                        <h3 class="text-xs font-black text-vendor uppercase tracking-[0.3em] italic font-inter">بيانات العتاد والطراز (Hardware Spec)</h3>
                    </div>

                    <!-- Smart Node Discovery -->
                    <div class="relative mb-12 group">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 mb-4 block italic">اكتشاف طراز العقدة (Smart Discovery)</label>
                        <div class="relative">
                            <Search class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-vendor transition-colors w-6 h-6 stroke-[3]" />
                            <input 
                                v-model="deviceQuery"
                                type="text" 
                                placeholder="ابحث في كتالوج MikroTik المركزي (مثلاً: CCR, hAP, RB...)"
                                class="w-full bg-white/50 border-white/60 rounded-2xl pr-16 h-16 font-black text-lg text-slate-900 focus:ring-4 focus:ring-vendor/5 transition-all"
                                @focus="showResults = true"
                            >
                        </div>

                        <!-- Search Dropdown Logic -->
                        <div v-if="showResults && deviceQuery" class="absolute z-50 w-full mt-4 bg-white/80 backdrop-blur-xl border border-white/60 rounded-[2rem] shadow-radiant overflow-hidden p-4 animate-in fade-in slide-in-from-top-4 duration-300">
                            <div 
                                v-for="product in filteredProducts.slice(0, 5)" 
                                :key="product.id"
                                @click="selectProduct(product)"
                                class="flex items-center gap-6 p-5 hover:bg-white rounded-2xl cursor-pointer transition-all group/item"
                            >
                                <div class="w-14 h-14 bg-slate-900 text-vendor rounded-2xl flex items-center justify-center shrink-0 border border-white/10 group-hover/item:scale-110 transition-transform">
                                    <Router class="w-8 h-8" />
                                </div>
                                <div class="flex-1 text-right">
                                    <p class="font-black text-lg text-slate-900 italic tracking-tighter">{{ product.manufacturer }} {{ product.model_name }}</p>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-1 italic">{{ product.device_type || 'CORE_ROUTING_UNIT' }}</p>
                                </div>
                                <ChevronRight class="w-6 h-6 text-slate-300 group-hover/item:text-vendor" />
                            </div>
                        </div>
                    </div>

                    <!-- Hardware Configuration Matrix -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-end">
                         <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2 italic">المُعرف المنطقي للعقدة (Node Logical ID)</label>
                            <input v-model="form.name" type="text" placeholder="مثلاً: DC01-PRIMARY-CORE" class="w-full bg-white/50 border-white/60 rounded-2xl px-8 h-16 font-black uppercase tracking-tight text-slate-900 focus:ring-4 focus:ring-vendor/5 transition-all" required>
                        </div>

                        <div v-if="selectedProduct" class="bg-slate-900 p-8 rounded-[2rem] flex items-center gap-8 shadow-radiant border border-white/5 relative overflow-hidden group/pod">
                            <div class="absolute inset-0 bg-vendor/10 animate-pulse pointer-events-none"></div>
                            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center shrink-0 border border-white/10 group-hover/pod:rotate-12 transition-transform">
                                <Cpu class="w-10 h-10 text-vendor" />
                            </div>
                            <div class="min-w-0 text-right relative z-10">
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-[0.3em] mb-2 leading-none font-inter italic">Topology Pod Assigned</p>
                                <p class="text-xl font-black text-white truncate uppercase italic tracking-tighter">{{ selectedProduct.model_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Connection Governance Protocols -->
                <div class="glass-card p-10 bg-white/60">
                    <div class="flex items-center gap-4 mb-12">
                        <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                        <h3 class="text-xs font-black text-amber-600 uppercase tracking-[0.3em] italic font-inter">بروتوكولات الاتصال والوصول (Access Matrix)</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="lg:col-span-2 space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">عنوان الواجهة العالمية (IP Address)</label>
                            <div class="relative group">
                                <Globe class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-amber-500 transition-colors w-6 h-6" />
                                <input v-model="form.ip" type="text" placeholder="10.x.x.x or 185.x.x.x" class="w-full bg-white/50 border-white/60 rounded-2xl pr-14 h-16 font-mono font-black text-lg text-slate-900 focus:ring-4 focus:ring-amber-500/5 transition-all" required>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">منفذ API</label>
                            <input v-model="form.api_port" type="number" class="w-full bg-white/50 border-white/60 rounded-2xl px-8 h-16 font-mono font-black text-lg text-slate-900 focus:ring-4 focus:ring-amber-500/5 transition-all" placeholder="8728">
                        </div>

                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">اسم المستخدم (Admin)</label>
                            <input v-model="form.username" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-8 h-16 font-black text-lg text-slate-900 focus:ring-4 focus:ring-amber-500/5 transition-all" placeholder="admin">
                        </div>

                        <div class="lg:col-span-4 space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">مفتاح الوصول السيادي (Infrastructure Key)</label>
                            <div class="relative group">
                                <ShieldCheck class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-amber-500 transition-colors w-6 h-6" />
                                <input v-model="form.password" type="password" class="w-full bg-white/50 border-white/60 rounded-2xl pr-14 h-16 font-mono font-black text-lg text-slate-900 focus:ring-4 focus:ring-amber-500/5 transition-all" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Geospatial Infrastructure Registry -->
                <div class="glass-card p-10 bg-white/60 border-rose-500/20">
                    <div class="flex items-center gap-4 mb-12">
                        <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
                        <h3 class="text-xs font-black text-rose-600 uppercase tracking-[0.3em] italic font-inter">الموقع المادي والتوزيع الجغرافي (Geospatial Pod)</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">مصدر تزويد العمود الفقري (Backbone Gateway)</label>
                            <div class="relative group">
                                <Network class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-rose-500 transition-colors w-6 h-6" />
                                <select v-model="form.internet_source_id" class="w-full bg-white/50 border-white/60 rounded-2xl pr-14 h-16 font-black text-[13px] uppercase focus:ring-4 focus:ring-rose-500/5 transition-all appearance-none">
                                    <option value="">بوابة عقدة مستقلة (INDEPENDENT_GW)</option>
                                    <option v-for="source in internetSources" :key="source.id" :value="source.id">
                                        {{ source.name }} ({{ source.type }})
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">البنية المظلية (Site Tower)</label>
                            <div class="relative group">
                                <Radio class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-rose-500 transition-colors w-6 h-6" />
                                <select v-model="form.location_tower_id" class="w-full bg-white/50 border-white/60 rounded-2xl pr-14 h-16 font-black text-[13px] uppercase focus:ring-4 focus:ring-rose-500/5 transition-all appearance-none">
                                    <option value="">توضع ذاتي مستقل (STANDALONE)</option>
                                    <option v-for="tower in towers" :key="tower.id" :value="tower.id">
                                         {{ tower.name }} — 🗼
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-4">
                             <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mr-2 italic">توصيف الموقع المادي الدقيق (Physical Asset Location)</label>
                             <div class="relative group">
                                <MapPin class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-rose-500 transition-colors w-6 h-6" />
                                <input v-model="form.location" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl pr-14 h-16 text-sm font-bold focus:ring-4 focus:ring-rose-500/5 transition-all" placeholder="مثلاً: الخزانة الشمالية - الرف 4">
                             </div>
                        </div>

                        <div class="space-y-4">
                             <label class="text-[11px] font-black text-rose-500 uppercase tracking-widest mr-2 italic">خط العرض (LAT)</label>
                             <input v-model="form.lat" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-8 h-16 font-mono font-black text-rose-600 focus:ring-4 focus:ring-rose-500/5 transition-all" placeholder="33.xxx">
                        </div>
                        <div class="space-y-4">
                             <label class="text-[11px] font-black text-emerald-500 uppercase tracking-widest mr-2 italic">خط الطول (LNG)</label>
                             <input v-model="form.lng" type="text" class="w-full bg-white/50 border-white/60 rounded-2xl px-8 h-16 font-mono font-black text-emerald-600 focus:ring-4 focus:ring-emerald-500/5 transition-all" placeholder="36.xxx">
                        </div>
                    </div>
                </div>

                <!-- Strategic Submission Finalization -->
                <div class="glass-card p-12 bg-slate-900 text-white border-none shadow-radiant relative overflow-hidden group/final flex flex-col md:flex-row items-center justify-between gap-12">
                    <div class="absolute inset-0 bg-vendor/10 animate-pulse pointer-events-none opacity-20"></div>
                    <div class="relative z-10 flex items-center gap-10 text-right flex-1">
                        <div class="w-20 h-20 bg-white/10 rounded-[2rem] flex items-center justify-center text-vendor border border-white/10 shadow-inner group-hover/final:scale-110 transition-transform">
                            <Zap class="w-12 h-12 stroke-[2.5]" />
                        </div>
                        <div>
                             <h4 class="text-2xl font-black text-white uppercase tracking-tight italic font-inter leading-none mb-3">تأكيد حقن العقدة (Commit Node)</h4>
                             <p class="text-[11px] font-black text-white/30 uppercase tracking-[0.3em] leading-none italic">
                                حوكمة البنية التحتية تتطلب دقة عالية في المزامنة الطوبولوجية.
                             </p>
                        </div>
                    </div>
                    <div class="relative z-10 flex items-center gap-6 w-full md:w-auto shrink-0">
                        <Link 
                            :href="route('servers.index')" 
                            class="flex-1 md:flex-none px-12 py-6 bg-white/5 text-white/40 font-black text-[11px] uppercase tracking-widest rounded-2xl border border-white/5 hover:bg-white/10 hover:text-white transition-all text-center italic"
                        >
                            إلغاء العملية
                        </Link>
                        <button 
                            type="submit" 
                            class="flex-1 md:flex-none px-16 py-6 bg-white text-slate-900 font-black text-[12px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl hover:bg-vendor hover:text-white transition-all hover:scale-105 active:scale-95 disabled:opacity-50 flex items-center justify-center gap-5 border border-white/10 font-inter"
                            :disabled="form.processing"
                        >
                            <HardDrive class="w-6 h-6" />
                            إضافة العقدة السيادية
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </InstitutionalLayout>
</template>
