<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstitutionalLayout from '@/Layouts/InstitutionalLayout.vue';
import debounce from 'lodash/debounce';
import { 
    Users,
    Search,
    UserPlus,
    Activity,
    CheckCircle2,
    Clock,
    Edit3,
    Eye,
    Filter,
    Package,
    XCircle,
    Zap,
    MapPin,
    RotateCw,
    MoreVertical
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
    if (!date) return 'مفتوح';
    return new Date(date).toLocaleDateString('en-CA'); // YYYY-MM-DD
};

const getStatusDetails = (status) => {
    return status === 'active' 
        ? { label: 'متصل', class: 'bg-emerald-100 text-emerald-600' }
        : { label: 'غير متصل', class: 'bg-slate-100 text-slate-500' };
};

</script>

<template>
    <InstitutionalLayout title="إدارة المشتركين">
        <div class="space-y-8 pb-20">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">إدارة المشتركين</h1>
                    <p class="text-slate-400 font-bold text-xs mt-1">عرض وتدقيق كافة الحسابات النشطة في النظام</p>
                </div>
                <div class="flex items-center gap-4 text-slate-400">
                    <span class="material-symbols-outlined">help</span>
                    <span class="material-symbols-outlined">notifications</span>
                    <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white shadow-sm overflow-hidden">
                        <div class="w-full h-full bg-slate-300"></div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="clean-card p-6 flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-bold mb-2">إجمالي المشتركين</p>
                        <h3 class="text-2xl font-black text-slate-900">1,284</h3>
                        <p class="text-emerald-500 text-[10px] font-bold mt-1">+12% من الشهر الماضي</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-soft rounded-full flex items-center justify-center text-primary">
                        <Users class="w-6 h-6" />
                    </div>
                </div>
                <div class="clean-card p-6 flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-bold mb-2">المشتركون النشطون</p>
                        <h3 class="text-2xl font-black text-slate-900">1,102</h3>
                        <div class="w-24 h-1.5 bg-slate-100 rounded-full mt-3">
                            <div class="w-[85%] h-full bg-primary rounded-full"></div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                        <CheckCircle2 class="w-6 h-6" />
                    </div>
                </div>
                <div class="clean-card p-6 flex items-center justify-between border-rose-100">
                    <div>
                        <p class="text-slate-500 text-xs font-bold mb-2">الاشتراكات المنتهية</p>
                        <h3 class="text-2xl font-black text-slate-900">42</h3>
                        <p class="text-rose-500 text-[10px] font-bold mt-1">⚠️ تحتاج للمتابعة الفورية</p>
                    </div>
                    <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center text-rose-500">
                        <Activity class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="clean-card p-6">
                <div class="flex flex-col lg:flex-row items-end gap-6">
                    <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">بحث عن مشترك</label>
                            <div class="relative">
                                <Search class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 w-4 h-4" />
                                <input v-model="search" type="text" placeholder="اسم المشترك أو IP..." class="w-full bg-slate-50 border-slate-200 rounded-xl pr-12 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">نوع الباقة</label>
                            <select v-model="filters.type" @change="filterData" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:border-primary focus:ring-0 transition-all appearance-none cursor-pointer">
                                <option value="">جميع الباقات</option>
                                <option value="pppoe">برودباند منزلي</option>
                                <option value="hotspot">هوتسبوت</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest mr-2">تصفية حسب الحالة</label>
                            <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-200">
                                <button @click="filters.status = ''; filterData()" class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all" :class="!filters.status ? 'bg-primary text-white shadow-md' : 'text-slate-400 hover:text-slate-600'">الكل</button>
                                <button @click="filters.status = 'active'; filterData()" class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all" :class="filters.status === 'active' ? 'bg-primary text-white shadow-md' : 'text-slate-400 hover:text-slate-600'">نشط</button>
                                <button @click="filters.status = 'inactive'; filterData()" class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all" :class="filters.status === 'inactive' ? 'bg-primary text-white shadow-md' : 'text-slate-400 hover:text-slate-600'">منتهي</button>
                                <button class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-all rounded-lg">معلق</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Table Section -->
            <div class="clean-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-clean">
                        <thead>
                            <tr>
                                <th class="text-right">اسم المشترك</th>
                                <th>IP ADDRESS</th>
                                <th>نوع الباقة</th>
                                <th>حالة الاتصال</th>
                                <th>تاريخ الانتهاء</th>
                                <th class="text-left">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="client in clients.data" :key="client.id" class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-primary-soft text-primary flex items-center justify-center font-black text-sm border border-primary/10">
                                            {{ client.username?.substring(0, 1).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800 leading-none">{{ client.name || client.username }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">ID: MD-{{ client.id + 55000 }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center font-mono text-xs text-slate-600 font-bold">
                                    {{ client.ip_address || '192.168.10.88' }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span :class="['px-3 py-1 rounded-lg text-[10px] font-black uppercase', client.type === 'pppoe' ? 'bg-indigo-50 text-indigo-500' : 'bg-amber-50 text-amber-600']">
                                        {{ client.type === 'pppoe' ? 'برودباند منزلي' : 'هوتسبوت' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div :class="['inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black', getStatusDetails(client.status).class]">
                                        <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                        {{ getStatusDetails(client.status).label }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <p class="text-sm font-bold" :class="client.status === 'inactive' ? 'text-rose-500' : 'text-slate-600'">
                                        {{ formatDate(client.expires_at) }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 text-left">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:bg-primary-soft transition-all">
                                            <RotateCw class="w-4 h-4" />
                                        </button>
                                        <Link :href="route('crm.clients.edit', client.id)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-all">
                                            <Edit3 class="w-4 h-4" />
                                        </Link>
                                        <button class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                                            <XCircle class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination matching reference -->
                <div v-if="clients.links && clients.links.length > 3" class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                    <p class="text-[10px] font-bold text-slate-400">عرض 10 من أصل {{ clients.total }} مشترك</p>
                    <nav class="flex gap-1">
                        <Link 
                            v-for="link in clients.links" 
                            :key="link.label"
                            :href="link.url || '#'"
                            class="h-8 min-w-[32px] flex items-center justify-center rounded-lg text-[11px] font-bold transition-all px-3"
                            :class="link.active ? 'bg-primary text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200 hover:bg-slate-50'"
                            v-html="link.label"
                        />
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Floating Add Button -->
        <Link :href="route('crm.clients.create')" class="fixed bottom-10 right-10 w-14 h-14 bg-primary text-white rounded-full flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 transition-all z-50 lg:hidden">
            <Plus class="w-8 h-8" />
        </Link>
    </InstitutionalLayout>
</template>
