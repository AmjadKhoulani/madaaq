<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    Ticket, 
    Search, 
    Plus, 
    Printer, 
    Filter, 
    Trash2, 
    XCircle, 
    CheckCircle2, 
    Activity, 
    MoreHorizontal,
    ChevronDown,
    Calendar,
    Download,
    Eye,
    Zap
} from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';
import { pickBy, throttle } from 'lodash';

const props = defineProps({
    vouchers: Object,
    packages: Array,
    stats: Object,
    filters: Object
});

const selectedIds = ref([]);
const showFilters = ref(false);

const form = ref({
    search: props.filters.search || '',
    status: props.filters.status || '',
    package_id: props.filters.package_id || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || ''
});

watch(form, throttle(() => {
    router.get(route('hotspot.vouchers.index'), pickBy(form.value), {
        preserveState: true,
        replace: true
    });
}, 300), { deep: true });

const toggleSelectAll = (e) => {
    if (e.target.checked) {
        selectedIds.value = props.vouchers.data.map(v => v.id);
    } else {
        selectedIds.value = [];
    }
};

const handleBulkAction = (action) => {
    if (!selectedIds.value.length) return;
    if (confirm(`Execute ${action} on ${selectedIds.value.length} selected vouchers?`)) {
        router.post(route('hotspot.vouchers.bulk_action'), {
            ids: selectedIds.value,
            action: action
        }, {
            onSuccess: () => selectedIds.value = []
        });
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'active': return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        case 'inactive': return 'bg-rose-50 text-rose-600 border-rose-100';
        default: return 'bg-gray-50 text-gray-600 border-gray-100';
    }
};

</script>

<template>
    <AppleLayout title="Voucher Hub">
        <Head title="Guest Access Vouchers" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="apple-card p-8 bg-black text-white relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">Total Minted</p>
                        <h3 class="text-4xl font-bold tracking-tight">{{ stats.total }}</h3>
                        <p class="text-[9px] font-bold text-emerald-400 mt-2 flex items-center gap-1">
                            <Activity class="w-3 h-3" /> System Lifetime Vouchers
                        </p>
                    </div>
                </div>
                <div class="apple-card p-8 bg-white border border-black/5 group">
                    <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-2">Active Circulation</p>
                    <h3 class="text-4xl font-bold tracking-tight text-emerald-600">{{ stats.active }}</h3>
                    <p class="text-[9px] font-bold text-[#86868b] mt-2 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Currently Valid Leases
                    </p>
                </div>
                <div class="apple-card p-8 bg-white border border-black/5 group">
                    <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-2">Inactive / Expired</p>
                    <h3 class="text-4xl font-bold tracking-tight text-rose-600">{{ stats.inactive }}</h3>
                    <p class="text-[9px] font-bold text-[#86868b] mt-2 flex items-center gap-1">
                        <XCircle class="w-1.5 h-1.5" /> Decommissioned Records
                    </p>
                </div>
            </div>

            <!-- Management Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868b] group-focus-within:text-black transition-colors" />
                        <input v-model="form.search" type="text" placeholder="Search voucher secret..." class="apple-input pl-11 h-12 w-64 lg:w-80 bg-black/[0.02] border-transparent focus:bg-white focus:border-black/5">
                    </div>
                    <button 
                        @click="showFilters = !showFilters"
                        class="w-12 h-12 apple-card flex items-center justify-center text-[#86868b] hover:text-black transition-all"
                        :class="showFilters ? 'bg-black text-white border-black' : ''"
                    >
                        <Filter class="w-5 h-5" />
                    </button>
                    <div v-if="selectedIds.length > 0" class="flex items-center gap-2 animate-in slide-in-from-left">
                        <div class="h-8 w-px bg-black/10 mx-2"></div>
                        <button @click="handleBulkAction('disable')" class="px-5 py-2.5 bg-amber-50 text-amber-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-100 transition-all">Disable ({{ selectedIds.length }})</button>
                        <button @click="handleBulkAction('delete')" class="px-5 py-2.5 bg-rose-50 text-rose-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 transition-all">Purge</button>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                     <Link 
                        :href="route('hotspot.vouchers.reprint_last')" 
                        class="px-6 h-12 apple-card flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest hover:text-black transition-all"
                     >
                        <Printer class="w-4 h-4" /> Last Batch
                     </Link>
                     <Link 
                        :href="route('hotspot.vouchers.create')" 
                        class="px-8 h-12 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <Plus class="w-4 h-4" /> Mint Vouchers
                     </Link>
                </div>
            </div>

            <!-- Filter Panel -->
            <div v-if="showFilters" class="apple-card p-8 mb-8 bg-black/[0.01] border-black/5 grid grid-cols-1 md:grid-cols-4 gap-6 animate-in slide-in-from-top">
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest ml-1">Status Protocol</label>
                    <select v-model="form.status" class="apple-input h-11 text-xs">
                        <option value="">All Statuses</option>
                        <option value="active">Active Circulation</option>
                        <option value="inactive">Decommissioned</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest ml-1">Velocity Package</label>
                    <select v-model="form.package_id" class="apple-input h-11 text-xs">
                        <option value="">All Packages</option>
                        <option v-for="p in packages" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest ml-1">Genesis Start (Date From)</label>
                    <input v-model="form.date_from" type="date" class="apple-input h-11 text-xs">
                </div>
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-[#86868b] uppercase tracking-widest ml-1">Genesis End (Date To)</label>
                    <input v-model="form.date_to" type="date" class="apple-input h-11 text-xs">
                </div>
            </div>

            <!-- Vouchers Registry -->
            <div class="apple-card overflow-hidden bg-white/50 backdrop-blur-xl border border-black/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-black/[0.02] bg-black/[0.01]">
                                <th class="px-8 py-5 w-12">
                                    <input type="checkbox" @change="toggleSelectAll" class="w-5 h-5 rounded-lg border-black/10 text-black">
                                </th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Voucher Intelligence</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Edge Synced Node</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Velocity Tier</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Presence</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest text-right">Commitment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.01]">
                            <tr v-for="v in vouchers.data" :key="v.id" class="group hover:bg-black/[0.01] transition-all" :class="selectedIds.includes(v.id) ? 'bg-indigo-50/30' : ''">
                                <td class="px-8 py-6">
                                    <input type="checkbox" v-model="selectedIds" :value="v.id" class="w-5 h-5 rounded-lg border-black/10 text-black">
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-black shadow-inner flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                                            <Ticket class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm tracking-widest font-mono uppercase group-hover:text-amber-600 transition-colors">{{ v.username }}</p>
                                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mt-0.5">Minted: {{ new Date(v.created_at).toLocaleDateString() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></div>
                                        <span class="text-[10px] font-bold text-black uppercase tracking-tight">{{ v.mikrotik_server?.name || 'Local Node' }}</span>
                                    </div>
                                    <p class="text-[9px] font-mono text-[#86868b] mt-1">{{ v.mikrotik_server?.ip || '0.0.0.0' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="px-3 py-1.5 bg-black/[0.02] border border-black/[0.05] rounded-xl inline-flex flex-col items-center">
                                         <p class="text-[10px] font-black tracking-tighter">{{ v.package?.name || 'GENERIC' }}</p>
                                         <p class="text-[8px] font-bold text-emerald-600">${{ v.package?.price || '0.00' }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span 
                                        class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border flex items-center justify-center gap-2 w-fit"
                                        :class="getStatusClass(v.status)"
                                    >
                                        <span v-if="v.status === 'active'" class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                                        {{ v.status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                         <button 
                                            class="w-10 h-10 apple-card flex items-center justify-center text-[#86868b] hover:text-indigo-600 transition-all shadow-sm"
                                         >
                                            <Eye class="w-4 h-4" />
                                         </button>
                                         <button 
                                            class="w-10 h-10 apple-card flex items-center justify-center text-[#86868b] hover:text-amber-600 transition-all shadow-sm"
                                         >
                                            <Printer class="w-4 h-4" />
                                         </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="vouchers.data.length === 0" class="py-24 text-center">
                    <div class="w-20 h-20 bg-black/5 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-[#86868b]">
                        <Ticket class="w-10 h-10" />
                    </div>
                    <h3 class="text-lg font-bold tracking-tight text-black mb-1">Hub Registry Depleted</h3>
                    <p class="text-sm text-[#86868b] max-w-xs mx-auto">Initialize a new minting protocol to generate guest access vouchers.</p>
                </div>

                <!-- Strategic Pagination -->
                <div class="px-8 py-6 border-t border-black/[0.02] bg-black/[0.01]">
                    <Pagination :links="vouchers.links" />
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
