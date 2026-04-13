<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppleLayout from '@/Layouts/AppleLayout.vue';
import { 
    FileText, 
    Search, 
    Plus, 
    DollarSign, 
    Calendar, 
    TrendingUp, 
    TrendingDown, 
    CheckCircle2, 
    XCircle, 
    Clock, 
    MoreHorizontal,
    Eye,
    Edit3,
    Trash2,
    Download,
    CreditCard
} from 'lucide-vue-next';
import Pagination from '@/Components/Pagination.vue';
import { pickBy, throttle } from 'lodash';

const props = defineProps({
    invoices: Object,
    stats: Object,
    filters: Object
});

const filters = ref({
    status: props.filters.status || 'all'
});

watch(filters, throttle(() => {
    router.get(route('accounting.invoices.index'), pickBy(filters.value), {
        preserveState: true,
        replace: true
    });
}, 300), { deep: true });

const getStatusBadge = (status) => {
    switch (status) {
        case 'paid': return { label: 'Settled', color: 'text-emerald-600 bg-emerald-50 border-emerald-100', icon: CheckCircle2 };
        case 'unpaid': return { label: 'Outstanding', color: 'text-amber-600 bg-amber-50 border-amber-100', icon: Clock };
        case 'overdue': return { label: 'Overdue', color: 'text-rose-600 bg-rose-50 border-rose-100', icon: XCircle };
        default: return { label: status, color: 'text-gray-600 bg-gray-50 border-gray-100', icon: FileText };
    }
};

</script>

<template>
    <AppleLayout title="Fiscal Registry">
        <Head title="Invoice Ledger" />

        <div class="max-w-[1400px] mx-auto pb-20">
            <!-- Strategic Fiscal Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div class="apple-card p-8 bg-black text-white relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">Settled Revenue</p>
                        <h3 class="text-4xl font-bold tracking-tight">${{ stats.total_revenue.toLocaleString() }}</h3>
                        <p class="text-[9px] font-bold text-emerald-400 mt-2 flex items-center gap-1">
                            <TrendingUp class="w-3 h-3" /> System Lifetime Extraction
                        </p>
                    </div>
                </div>
                <div class="apple-card p-8 bg-white border border-black/5 group">
                    <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-2">Receivables (Outstanding)</p>
                    <h3 class="text-4xl font-bold tracking-tight text-amber-600">${{ stats.unpaid_amount.toLocaleString() }}</h3>
                    <p class="text-[9px] font-bold text-[#86868b] mt-2 flex items-center gap-1">
                        <Clock class="w-3 h-3" /> Pending Commitment
                    </p>
                </div>
                <div class="apple-card p-8 bg-white border border-black/5 group">
                    <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-2">Paid Volume</p>
                    <h3 class="text-4xl font-bold tracking-tight text-black">{{ stats.paid_count }}</h3>
                    <p class="text-[9px] font-bold text-emerald-600 mt-2">Successful Handshakes</p>
                </div>
                <div class="apple-card p-8 bg-white border border-black/5 group">
                    <p class="text-[10px] font-black text-[#86868b] uppercase tracking-widest mb-2">Unpaid Artifacts</p>
                    <h3 class="text-4xl font-bold tracking-tight text-rose-600">{{ stats.unpaid_count }}</h3>
                    <p class="text-[9px] font-bold text-rose-500 mt-2">Critical Delta</p>
                </div>
            </div>

            <!-- Management Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-8">
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#86868b] group-focus-within:text-black transition-colors" />
                        <input type="text" placeholder="Search by Invoice # or Batch..." class="apple-input pl-11 h-12 w-64 lg:w-80 bg-black/[0.02] border-transparent focus:bg-white focus:border-black/5">
                    </div>
                    <div class="flex bg-black/[0.03] p-1.5 rounded-2xl">
                         <button @click="filters.status = 'all'" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.status === 'all' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">All Protocols</button>
                         <button @click="filters.status = 'paid'" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.status === 'paid' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">Settled</button>
                         <button @click="filters.status = 'unpaid'" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="filters.status === 'unpaid' ? 'bg-white text-black shadow-lg' : 'text-[#86868b]'">Outstanding</button>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                     <Link 
                        :href="route('accounting.invoices.create')" 
                        class="px-8 py-3.5 bg-black text-white rounded-2xl font-bold text-[11px] uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3"
                     >
                        <Plus class="w-4 h-4" /> Initialize Ledger
                     </Link>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="apple-card overflow-hidden bg-white/50 backdrop-blur-xl border border-black/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-black/[0.02] bg-black/[0.01]">
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Protocol Artifact (Invoice)</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Subscriber Identity</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Fiscal Pulse (Amount)</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Horizon (Due Date)</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#86868b] uppercase tracking-widest text-right">Commitment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.01]">
                            <tr v-for="inv in invoices.data" :key="inv.id" class="group hover:bg-black/[0.01] transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-black shadow-inner flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                                            <FileText class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm tracking-widest font-mono uppercase group-hover:text-indigo-600 transition-colors">{{ inv.invoice_number }}</p>
                                            <p class="text-[8px] font-black text-[#86868b] uppercase tracking-widest mt-0.5">Minted: {{ new Date(inv.created_at).toLocaleDateString() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-[11px] font-bold text-black uppercase tracking-tight">{{ inv.client?.name || 'Anonymous Peer' }}</p>
                                    <p class="text-[9px] font-mono text-[#86868b] mt-1">{{ inv.client?.username || 'SYSTEM_LEASE' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-lg font-black tracking-tight text-black">${{ inv.amount.toLocaleString() }}</p>
                                    <p class="text-[8px] font-black text-emerald-600 uppercase mt-0.5">Commercial Commit</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <Calendar class="w-3 h-3 text-[#86868b]" />
                                        <span class="text-[10px] font-bold uppercase">{{ new Date(inv.due_date).toLocaleDateString() }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span 
                                        class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border flex items-center justify-center gap-2 w-fit"
                                        :class="getStatusBadge(inv.status).color"
                                    >
                                        <component :is="getStatusBadge(inv.status).icon" class="w-3 h-3" />
                                        {{ getStatusBadge(inv.status).label }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                         <Link 
                                            :href="route('accounting.invoices.show', inv.id)" 
                                            class="w-10 h-10 apple-card flex items-center justify-center text-[#86868b] hover:text-indigo-600 hover:bg-white transition-all shadow-sm"
                                         >
                                            <Eye class="w-4 h-4" />
                                         </Link>
                                         <Link 
                                            :href="route('accounting.invoices.edit', inv.id)" 
                                            class="w-10 h-10 apple-card flex items-center justify-center text-[#86868b] hover:text-amber-600 hover:bg-white transition-all shadow-sm"
                                         >
                                            <Edit3 class="w-4 h-4" />
                                         </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Final Pagination -->
                <div class="px-8 py-6 border-t border-black/[0.02] bg-black/[0.01]">
                    <Pagination :links="invoices.links" />
                </div>
            </div>
        </div>
    </AppleLayout>
</template>
