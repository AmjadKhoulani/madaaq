<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import debounce from 'lodash/debounce';
import { 
    Users,
    Search,
    ShieldCheck,
    UserCircle,
    UserPlus,
    Activity,
    ArrowRightLeft,
    CheckCircle2,
    Clock,
    Edit3,
    Eye,
    Filter,
    MoreVertical,
    Package,
    XCircle,
    Zap
} from 'lucide-vue-next';

const props = defineProps({
    clients: Object,
});

const filters = ref({
    search: new URLSearchParams(window.location.search).get('search') || '',
    status: new URLSearchParams(window.location.search).get('status') || '',
    type: new URLSearchParams(window.location.search).get('type') || '',
});

const search = ref(filters.value.search);

watch(search, debounce((value) => {
    router.get(route('crm.clients.index'), { search: value, status: filters.value.status, type: filters.value.type }, {
        preserveState: true,
        replace: true
    });
}, 300));

const filterData = () => {
    router.get(route('crm.clients.index'), { search: search.value, status: filters.value.status, type: filters.value.type }, {
        preserveState: true,
        replace: true
    });
};

const formatDate = (date) => {
    if (!date) return 'وصول سيادي دائم';
    return new Date(date).toLocaleDateString('ar-EG', { year: 'numeric', month: 'long', day: 'numeric' });
};

const getStatusDetails = (status) => {
    return status === 'active' 
        ? { label: 'نشط (Active)', class: 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20', icon: CheckCircle2 }
        : { label: 'مجمد (Inactive)', class: 'bg-rose-500/10 text-rose-500 border-rose-500/20', icon: XCircle };
};

</script>

<template>
    <InstitutionalLayout title="سجل الهوية الرقمية (CRM)">
        <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
            
            <!-- Strategic Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-12 h-1 bg-vendor rounded-full"></span>
                        <p class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] font-inter">Subscriber Identity Master</p>
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">سجل <span class="text-vendor">الهوية</span> الرقمية</h1>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-[11px] opacity-80 italic">حوكمة الأصول البشرية وتتبع استمرارية النبض</p>
                </div>
                
                <Link :href="route('crm.clients.create')" class="btn-radiant btn-vendor px-8 py-4 text-xs font-black uppercase tracking-[0.2em] flex items-center gap-3">
                    <UserPlus class="w-5 h-5 stroke-[3]" />
                    تسجيل هوية جديدة
                </Link>
            </div>

            <!-- Analytical Strategic Filters -->
            <div class="glass-card p-8 bg-white/60 flex flex-col lg:flex-row items-center gap-8 border-white/60">
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1 space-y-3">
                        <label class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                            <Search class="w-3 h-3" />
                            محرك البحث
                        </label>
                        <div class="relative group">
                            <input v-model="search" type="text" placeholder="اسم المشترك أو SSID..." class="w-full bg-white/50 border-white/60 rounded-2xl pr-12 py-3.5 text-sm font-bold focus:ring-4 focus:ring-vendor/5 transition-all">
                            <Search class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 w-5 h-5 group-focus-within:text-vendor transition-colors" />
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                            <Activity class="w-3 h-3" />
                            وضعية الخدمة
                        </label>
                        <select v-model="filters.status" @change="filterData" class="w-full bg-white/50 border-white/60 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-4 focus:ring-vendor/5 transition-all appearance-none cursor-pointer">
                            <option value="">كافة التصنيفات</option>
                            <option value="active">نشط (Active)</option>
                            <option value="inactive">مجمد (Inactive)</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-vendor uppercase tracking-[0.3em] flex items-center gap-2 mr-2">
                            <Zap class="w-3 h-3" />
                            بروتوكول الربط
                        </label>
                        <select v-model="filters.type" @change="filterData" class="w-full bg-white/50 border-white/60 rounded-2xl px-5 py-3.5 text-sm font-bold focus:ring-4 focus:ring-vendor/5 transition-all appearance-none cursor-pointer">
                            <option value="">كافة الأنظمة</option>
                            <option value="pppoe">PPPoE Tunnel</option>
                            <option value="hotspot">Hotspot Gateway</option>
                        </select>
                    </div>
                </div>

                <button @click="search = ''; filters.status = ''; filters.type = ''; filterData()" class="h-14 px-8 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all flex items-center gap-3 shrink-0">
                    <Filter class="w-4 h-4" />
                    تصفير الفلاتر
                </button>
            </div>

            <!-- Master Identity Ledger -->
            <div class="glass-card overflow-hidden bg-white/40 border-white/60">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white/40 text-[10px] font-black uppercase tracking-[0.3em] italic font-inter">
                                <th class="px-10 py-8">الهوية الرقمية (Service Identity)</th>
                                <th class="px-8 py-8 text-center">نمط الربط والحزمة</th>
                                <th class="px-8 py-8 text-center">أفق الصلاحية</th>
                                <th class="px-8 py-8 text-center">وضع النبض</th>
                                <th class="px-10 py-8"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/20">
                            <tr v-for="client in clients.data" :key="client.id" class="group hover:bg-white/60 transition-all duration-500">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-5 justify-end">
                                        <div class="text-right">
                                            <h4 class="text-lg font-black text-slate-900 italic tracking-tighter group-hover:translate-x-2 transition-transform uppercase font-inter">{{ client.username }}</h4>
                                            <div class="flex items-center gap-2 mt-1 opacity-40 justify-end">
                                                <span class="text-[9px] font-black uppercase tracking-widest">{{ client.name || 'Civil Identity Unconfirmed' }}</span>
                                                <ShieldCheck class="w-3 h-3" />
                                            </div>
                                        </div>
                                        <div class="w-14 h-14 rounded-2xl bg-slate-900 text-vendor flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shrink-0 border border-white/10 overflow-hidden">
                                            <img v-if="client.profile_photo_url" :src="client.profile_photo_url" class="w-full h-full object-cover" />
                                            <UserCircle v-else class="w-8 h-8 stroke-[1.5]" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <span :class="['px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border shadow-sm', client.type === 'pppoe' ? 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20' : 'bg-cyan-500/10 text-cyan-600 border-cyan-500/20']">
                                            {{ client.type === 'pppoe' ? 'Tunnel: PPPoE' : 'Tunnel: Hotspot' }}
                                        </span>
                                        <div class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest font-inter">
                                            <Package class="w-3 h-3 text-vendor" />
                                            {{ client.package?.name || 'CUSTOM_QOS' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="px-5 py-2 bg-slate-900 text-vendor rounded-xl text-[11px] font-black font-inter shadow-lg group-hover:bg-vendor group-hover:text-white transition-colors italic whitespace-nowrap">
                                            {{ formatDate(client.expires_at) }}
                                        </div>
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-2 font-inter">Expiration Vector</span>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div :class="[
                                        'inline-flex items-center justify-center gap-3 px-6 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all shadow-sm group-hover:translate-y-[-2px]',
                                        getStatusDetails(client.status).class
                                    ]">
                                        <component :is="getStatusDetails(client.status).icon" class="w-3.5 h-3.5" />
                                        {{ getStatusDetails(client.status).label }}
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                         <Link :href="route('crm.clients.show', client.id)" class="px-6 py-3.5 bg-white shadow-xl border border-white/60 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-vendor transition-all flex items-center gap-3 hover:scale-105 active:scale-95 group/btn">
                                            <Eye class="w-4 h-4 group-hover/btn:scale-110 transition-transform" />
                                            فحص المنظومة
                                         </Link>
                                         <Link :href="route('crm.clients.edit', client.id)" class="w-12 h-12 bg-white shadow-xl border border-white/60 rounded-xl flex items-center justify-center text-slate-400 hover:text-amber-500 hover:scale-110 active:scale-90 transition-all">
                                            <Edit3 class="w-5 h-5" />
                                         </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Protocol -->
                <div v-if="clients.data.length === 0" class="py-40 flex flex-col items-center gap-8 text-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-slate-900 text-vendor flex items-center justify-center border-8 border-white/20 shadow-2xl relative group">
                        <div class="absolute inset-0 bg-vendor/20 opacity-20 blur-2xl animate-pulse"></div>
                        <Users class="w-12 h-12 relative z-10" />
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 italic tracking-tighter uppercase">مصفوفة الهويات صفرية (Null Identity)</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] max-w-sm mt-3 opacity-70 italic font-inter">No subscriber instances detected in the current sovereign domain.</p>
                    </div>
                    <Link :href="route('crm.clients.create')" class="btn-radiant btn-vendor px-12 py-5 text-xs font-black uppercase tracking-[0.3em]">
                        حقن هوية جديدة
                    </Link>
                </div>

                <!-- Strategic Pagination -->
                <div v-if="clients.links && clients.links.length > 3" class="px-10 py-8 bg-slate-900/5 border-t border-white/40 flex justify-between items-center">
                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest font-inter opacity-40">Discovery Matrix v4.2</div>
                    <nav class="flex gap-2">
                        <Link 
                            v-for="link in clients.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-10 min-w-[40px] flex items-center justify-center rounded-xl text-[10px] font-black transition-all border px-4"
                            :class="link.active ? 'bg-vendor text-white border-vendor shadow-lg' : 'bg-white/50 text-slate-400 border-white/60 hover:text-vendor hover:bg-white'"
                            v-html="link.label"
                        />
                    </nav>
                </div>
            </div>
        </div>
    </InstitutionalLayout>
</template>


